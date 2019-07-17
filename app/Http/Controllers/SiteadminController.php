<?php

namespace App\Http\Controllers;

//use App\Jobs\MoveKey;
use App\model\Admin;
use App\model\Balance;
use App\model\Cms;
use App\model\Currencies;
use App\model\DeletedUsers;
use App\model\Enquiry;
use App\model\Faq;
use App\model\Fees;
use App\model\ico;
use App\model\icodetails;
use App\model\ICORate;
use App\model\Marketprice;
use App\model\Metacontent;
use App\model\Node;
use App\model\OpeningBalance;
use App\model\ClosingBalance;
use App\model\OTP;
use App\model\ICOTrade;
use App\model\Pair;
use App\model\Profit;
use App\model\ReferralBonus;
use App\model\ReferralEarning;
use App\model\SiteSettings;
use App\model\Template;
use App\model\Trade;
use App\model\TradeMapping;
use App\model\Tradingfee;
use App\model\Transaction;
use App\model\UserBalance;
use App\model\UserBalancesNew;
use App\model\UserCurrencyAddresses;
use App\model\Users;
use App\model\Verification;
use App\model\Wallettrans;
use App\model\Whitelist;
use App\model\XDCChart;
use Cache;
use DateTime;
use DB;
use Hash;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use Validator;
use Maatwebsite\Excel\Facades\Excel;
use Pusher\Pusher;

//use Barryvdh\DomPDF\Facade as PDF;

class SiteadminController extends Controller
{
    //
    public function __construct()
    {
        try {
            //$this->middleware('Adminlogin');
            $ip = \Request::ip();
            blockip_list($ip);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function non_email_verified(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {

                if ($request->isMethod('get')) {
                    $min = $request['min'];
                    $max = $request['max'];
                    $search = $request['search'];
                    $email = $request['email'];
                    $status = $request['status'];
                    $paging = $request['paging'];
                    $q = Users::query();
                    $q->where('verify_status', 2);
                    if ($min) {
                        $q->where('created_at', '>=', $min);
                    }
                    if ($max) {
                        $q->where('created_at', '<=', $max);
                    }
                    if ($search) {
                        $q->where(function ($qq) use ($search) {
                            $qq->where('id', 'like', '%' . $search . '%')->Orwhere('enjoyer_name', 'like', '%' . $search . '%');
                        });
                    }
                    if ($email) {
                        $spl = explode("@", $email);
                        $user1 = $spl[0];
                        $user2 = $spl[1];
                        $record = getByEmail($user1, $user2);

                        foreach ($record as $val) {
                            $user_id = $val->id;
                            $q->where('id', $user_id);
                        }
                    }
                    if ($status != '' && $status != 'all') {
                        $q->where('user_verified', $status);
                    }

                    $result = $q->orderBy('id', 'desc')->paginate($paging);
                } else {
                    $result = Users::orderBy('id', 'desc')->paginate(25);
                }

                return view('panel.email_verification', ['result' => $result]);
            }

        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function index(Request $request)
    {
        try {
            if ($request->isMethod('post')) {

                $validator = $this->validate($request, [
                    'alpha_username' => 'required',
                    'alpha_password' => 'required|min:6',
                    'pattern' => 'required',
                ], [
                    'alpha_username.required' => 'Email is required',
                    'alpha_password.required' => 'Password is required',
                    'alpha_password.min' => 'Password 6 characters is required',
                ]);

                $email = $request['alpha_username'];
                $password = $request['alpha_password'];
                $auth = $this->adminauth($email, $password);
                switch ($auth) {
                    case '1':
                        return redirect('check_admin/home');
                        break;
                    case '2':
                        Session::flash('error', 'Password is invalid');
                        return redirect('check_admin');
                        break;
                    case '3':
                        return redirect('check_admin/pending_history');
                        break;
                    case '4':
                        return redirect('check_admin/non_email_verified');
                        break;
                    case '0':
                        Session::flash('error', 'Account is deactive');
                        return redirect('check_admin');
                        break;

                    default:
                        return redirect('check_admin');
                        break;
                }
            }
            return view('panel.login');
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function adminauth($email, $password)
    {
        try {
            $arr = array('email_id' => $email, 'status' => 'active');
            $check = Admin::where($arr)->first();
            if ($check) {
                if (Hash::check($password, $check->CMB_password)) {
                    $sess = array('alpha_id' => $check->id, 'role' => $check->role, 'alowner' => $email);

                    Session::put($sess);
                    owner_activity($email, 'Login');
                    if ($check->CMB_username == "Traders") {
                        return 3;
                    } else if ($check->CMB_username == "EmailVerifier") {
                        return '4';
                    }
                    return "1";
                } else {
                    return "2";
                }
            } else {
                return "0";
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function logout()
    {
        try {
            Session::flush();
            Cache::flush();
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function home()
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {

//                $adminusdtaddr = decrypt(get_config('usdt_address'));
//                $usdt_bal = number_format(usdt_bal(), '4', '.', '');
                $usdt_bal = 0;

                $adminethaddr = decrypt(get_config('eth_address'));
//                $eth_bal = getting_eth_balance($adminethaddr);
                $eth_bal = 0;

                $adminbtcaddr = decrypt(get_config('btc_address'));
//                $btc_bal = get_btc_wallet_info($adminbtcaddr);
//                $btc_bal1 = $btc_bal['balance'];
                $btc_bal1 = 0;

//            $btc_bal1 = "qqqqqq";
                $adminxrpaddr = decrypt(get_config('xrp_address'));
                $xrp_res = get_xrp_balance($adminxrpaddr);

                $pending_eth_transaction = get_total_intradebalance('ETH');
                $pending_btc_transaction = get_total_intradebalance('BTC');
                $pending_xrp_transaction = get_total_intradebalance('XRP');
                $pending_usdt_transaction = get_total_intradebalance('USDT');

                $trade_eth = Profit::query()->where('theftCurrency', 'ETH')->sum('theftAmount');
                $trade_btc = Profit::query()->where('theftCurrency', 'BTC')->sum('theftAmount');
                $trade_usdt = Profit::query()->where('theftCurrency', 'USDT')->sum('theftAmount');
                $trade_xrp = Profit::query()->where('theftCurrency', 'XRP')->sum('theftAmount');

                $res = $xrp_res->result;
                $xrp_bal1 = 0;
                if ($res == 'success') {
                    $xrp_bal = $xrp_res->balances;
                    $xrp_bal1 = $xrp_bal[0]->value;
                }

//                $xrp_bal = check_ripple_balance();
                $xrp_bal = 0;

                $user_bal_usdt = UserBalancesNew::query()->where('currency_name', 'USDT')->sum('balance');
                $user_bal_eth = UserBalancesNew::query()->where('currency_name', 'ETH')->sum('balance');
                $user_bal_btc = UserBalancesNew::query()->where('currency_name', 'BTC')->sum('balance');
                $user_bal_xrp = UserBalancesNew::query()->where('currency_name', 'XRP')->sum('balance');

                //for last 25 trade
                $trade_25 = Trade::orderBy('id', 'desc')->take(25)->get();

                Session::put('xrpbalad', $xrp_bal);
                return view('panel.home', ['trade_25' => $trade_25, 'ETH_profit' => $trade_eth, 'BTC_profit' => $trade_btc, 'XRP_profit' => $trade_xrp, 'USDT_profit' => $trade_usdt, 'trade_usdt' => $pending_usdt_transaction, 'trade_btc' => $pending_btc_transaction, 'trade_xrp' => $pending_xrp_transaction, 'trade_eth' => $pending_eth_transaction, 'user_usdt' => $user_bal_usdt, 'user_eth' => $user_bal_eth, 'user_btc' => $user_bal_btc, 'user_xrp' => $user_bal_xrp, 'xrp_bal' => $xrp_bal, 'usdt_bal' => $usdt_bal, 'eth_bal' => $eth_bal, 'btc_bal' => $btc_bal1]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //for trade admins view page
    function tradeadmin()
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $date = new DateTime;
                $date->modify('-1440 minutes');
                $formatted_date = $date->format('Y-m-d H:i:s');

                $result = DB::table('tokenusers')
                    ->join('enjoyer', 'tokenusers.user_id', '=', 'enjoyer.id')
                    ->where('tokenusers.created_at', '>=', $formatted_date)
                    ->select('enjoyer.*', 'enjoyer.created_at')
                    ->paginate(25);
                return view('panel.trade_admin', ['result' => $result, 'status' => 1]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }


    //for viewing pending ico requests
    function ico_listing()
    {
        try {
            $get_ico = ico::where('status', '<>', 'Ended')->paginate(25);
            return view('panel.ico_lists', ['result' => $get_ico]);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //for viewing a particular ico
    function ico_view(Request $request, $id)
    {
        try {
            if ($request->isMethod('post')) {
                $get_ico = ico::where('id', $id)->first();
                $status = $get_ico->status;
                if ($status == $request['status']) {
                    return redirect('check_admin/token_request');
                } else {

                    $get_ico->status = $request['status'];
                    Session::flash('success', 'Successfully Updated');
                    $get_ico->save();
                    if ($request['update_status'] == 'on') {
                        $get_ico_details = icodetails::where('ico_id', $id)->first();
                        $get_ico_details->reason = $request['email_body'];

                        $to = $get_ico_details->email;

                        $subject = get_template('14', 'subject');
                        $message = get_template('14', 'template');
                        $mailarr = array(
                            '###CONTENT###' => $request['email_body'],
                            '##STATUS##' => $request['status'],
                            '###Name###' => get_config('site_name'),
                        );
                        $message = strtr($message, $mailarr);
                        $subject = strtr($subject, $mailarr);
                        sendmail($to, $subject, ['content' => $message]);
                    }
                    return redirect('check_admin/token_request');
                }


            } else {
                $get_ico = ico::where('id', $id)->first();

                $get_ico_details = icodetails::where('ico_id', $id)->first();

                return view('panel.ico_view', ['result' => $get_ico, 'results' => $get_ico_details, 'id' => $id]);
            }
        } catch (\Exception $exception) {
            return $exception->getMessage() . '<br>' . $exception->getLine() . '<br>' . $exception->getFile();
        }
    }

    function profile(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $alpha_id = Session::get('alpha_id');
                if ($request->isMethod('post')) {
                    $validator = Validator::make($request->all(), [
                        'admin_email' => 'required|email',
                        'admin_username' => 'required',
                        //'admin_phone' => 'numeric',
                    ], [
                        'admin_email.required' => 'Email is required',
                        'admin_username.required' => 'Username is required',
                        //'admin_phone.required' => 'Phone number must numeric',
                    ]);

                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator);
                    }

                    $upt = Admin::find($alpha_id);
                    if ($request['curr_pass'] != "") {
                        $validator = Validator::make($request->all(), [
                            'curr_pass' => 'required',
                            'password' => 'required|confirmed|min:6',
                            'password_confirmation' => 'required|min:6',
                        ]);

                        if ($validator->fails()) {
                            return redirect()->back()->withErrors($validator);
                        }

                        if (Hash::check($request['curr_pass'], get_adminprofile('CMB_password'))) {
                            $upt->CMB_password = bcrypt($request['password']);
                        } else {
                            Session::flash('error', 'Current password is wrong');
                            return redirect('check_admin/profile');
                        }

                    }

                    $upt->CMB_username = $request['admin_username'];
                    $upt->email_id = $request['admin_email'];

                    $upt->country = $request['admin_country'];
                    if ($upt->save()) {
                        Session::flash('success', 'Successfully Updated');
                        return redirect('check_admin/profile');
                    }
                }
                return view('panel.profile');
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function site_settings(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request->isMethod('post')) {
                    $this->validate($request, [
                        'site_name' => 'required',
                        'contact_mail' => 'required',
                        'country' => 'required',
                        'address' => 'required',
                        'facebook_url' => 'url',
                        'twitter_url' => 'url',
                        'google_url' => 'url',
                        'linkedin_url' => 'url',
                        'contact_no' => 'numeric',
                    ]);
                    $upt = SiteSettings::find(1);
                    if ($request->hasFile('site_logo')) {
                        $this->validate($request, [
                            'site_logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                        ]);
                        $logo = time() . '.' . $request->site_logo->getClientOriginalExtension();
                        $request->site_logo->move(public_path('uploads/logo'), $logo);
                        $upt->site_logo = $logo;
                    }

                    $upt->site_name = $request['site_name'];
                    $upt->facebook_url = $request['facebook_url'];
                    $upt->twitter_url = $request['twitter_url'];
                    $upt->google_url = $request['google_url'];
                    $upt->linkedin_url = $request['linkedin_url'];
                    $upt->google_analytics = $request['google_analytics'];
                    $upt->smtp_host = encrypt($request['smtp_host']);
                    $upt->smtp_port = encrypt($request['smtp_port']);
                    $upt->smtp_email = encrypt($request['smtp_email']);
                    $upt->smtp_password = encrypt($request['smtp_password']);
                    $upt->contact_mail = $request['contact_mail'];
                    $upt->address = $request['address'];
                    $upt->city = $request['city'];
                    $upt->provience = $request['provience'];
                    $upt->country = $request['country'];
                    $upt->contact_no = $request['contact_no'];
                    if ($upt->save()) {
                        Session::flash('success', 'Successfully Updated');
                        return redirect('check_admin/site_settings');
                    }

                }
                return view('panel.site_settings');
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function checkpattern(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $key1 = $request['key1'];
                $key2 = $request['key2'];
                $pattern = $key2 - $key1;
                $sitepat = get_superadmin('pattern');
                $original = decrypt($sitepat);
                if ($original == $pattern) {
                    echo $original;
                } else {
                    echo "12345";
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function change_pattern()
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                return view('panel.change_pattern');
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function set_pattern(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $key = $request['key'];
                $set = Admin::find(1);
                $set->pattern = encrypt($key);
                $set->save();
                Session::flash('success', 'Pattern changed Successfully');
                echo "true";
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function cms()
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $result = DB::table('cms')->orderBy('id', 'desc')->get();
                return view('panel.cms', ['result' => $result]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function updatecms(Request $request, $id)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {

                if ($request->isMethod('post')) {
                    $this->validate($request, [
                        'heading' => 'required',
                        'content' => 'required',
                    ]);
                    $upt = Cms::find($id);
                    $upt->heading = $request['heading'];
                    $upt->content = $request['content'];
                    if ($upt->update()) {
                        Session::flash('success', 'Successfully updated');
                        return redirect('check_admin/cms');
                    }
                }
                $result = DB::table('cms')->where('id', $id)->first();
                return view('panel.updatecms', ['result' => $result, 'id' => $id]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //validate_eth_block
    function validate_eth_block()
    {
        try {
            $balance = getting_eth_block();

            return view('panel.ether_block', ['Current' => $balance->currentBlock, 'Highest' => $balance->highestBlock]);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function users(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {

                if ($request->isMethod('get')) {
                    $min = $request['min'];
                    $max = $request['max'];
                    $search = $request['search'];
                    $email = $request['email'];
                    $status = $request['status'];
                    $paging = $request['paging'];
                    $q = Users::query();
                    if ($min) {
                        $q->where('created_at', '>=', $min);
                    }
                    if ($max) {
                        $q->where('created_at', '<=', $max);
                    }
                    if ($search) {
                        $q->where(function ($qq) use ($search) {
                            $qq->where('id', 'like', '%' . $search . '%')->Orwhere('enjoyer_name', 'like', '%' . $search . '%');
                        });
                    }
                    if ($email) {
                        $spl = explode("@", $email);
                        $user1 = $spl[0];
                        $user2 = $spl[1];
                        $record = getByEmail($user1, $user2);

                        foreach ($record as $val) {
                            $user_id = $val->id;
                            $q->where('id', $user_id);
                        }
                    }
                    if ($status != '' && $status != 'all') {
                        $q->where('user_verified', $status);
                    }

                    $result = $q->orderBy('id', 'desc')->paginate($paging);
                } else {
                    $result = Users::orderBy('id', 'desc')->paginate(25);
                }

                return view('panel.users', ['result' => $result]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

//    function userbalance(Request $request)
//    {
//        try {
//            if (Session::get('alpha_id') == "") {
//                return redirect('check_admin');
//            } else {
//                if ($request['currency']) {
//                    $result = DB::table('userbalance')
//                        ->join('enjoyer', 'userbalance.user_id', '=', 'enjoyer.id')
//                        ->orderBy('userbalance.' . $request['currency'], 'desc')
//                        ->select('userbalance.*', 'enjoyer.id', 'enjoyer.enjoyer_name')
//                        ->paginate(25);
//                } elseif ($request->isMethod('get')) {
//
//                    $search = $request['search'];
//                    $email = $request['email'];
//                    $user_search_id = $request['user_search_id'];
//                    $q = UserBalance::query();
//                    $q->join('enjoyer', 'userbalance.user_id', '=', 'enjoyer.id')->select('userbalance.*', 'enjoyer.enjoyer_name');
//
//                    if ($search) {
//                        $q->where(function ($qq) use ($search) {
//                            $qq->where('enjoyer_name', 'like', '%' . $search . '%');
//                        });
//                    }
//                    if ($email) {
//                        $spl = explode("@", $email);
//                        $user1 = $spl[0];
//                        $user2 = $spl[1];
//                        $record = getByEmail($user1, $user2);
//
//                        foreach ($record as $val) {
//                            $user_id = $val->id;
//                            $q->where('userbalance.user_id', $user_id);
//                        }
//                    }
//                    if ($user_search_id) {
//                        $q->where('userbalance.user_id', $user_search_id);
//                    }
//
//                    $result = $q->orderBy('userbalance.user_id', 'asc')->paginate(25);
//                } else {
//                    $result = DB::table('userbalance')
//                        ->join('enjoyer', 'userbalance.user_id', '=', 'enjoyer.id')
//                        ->orderBy('userbalance.user_id', 'asc')
//                        ->select('userbalance.*', 'enjoyer.enjoyer_name', 'enjoyer.XDC_addr', 'enjoyer.XDCE_addr', 'enjoyer.BTC_addr', 'enjoyer.BCH_addr', 'enjoyer.XRP_addr', 'enjoyer.ETH_addr')
//                        ->paginate(25);
//                }
//
//                return view('panel.userbalance', ['result' => $result, 'Header' => 'User Wallet Balance']);
//            }
//        }
//        catch (\Exception $e) {
//            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
//        return view('errors.404');
//        }
//    }

    function userbalance(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $search = $request['search'];
                $email = $request['email'];
                $user_search_id = $request['user_search_id'];
                $q = Users::select('id', 'enjoyer_name')->orderBy('id', 'asc');
                if ($search) {
                    $q->where(function ($qq) use ($search) {
                        $qq->where('enjoyer_name', 'like', '%' . $search . '%');
                    });
                }
                if ($email) {
                    $spl = explode("@", $email);
                    $user1 = $spl[0];
                    $user2 = $spl[1];
                    $record = getByEmail($user1, $user2);

                    foreach ($record as $val) {
                        $user_id = $val->id;
                        $q->where('id', $user_id);
                    }
                }
                if ($user_search_id) {
                    $q->where('id', $user_search_id);
                }
                $result = $q->paginate(25);
                $currency = Currencies::all();
                return view('panel.userbalance', ['result' => $result, 'currencies' => $currency, 'Header' => 'User Wallet Balance']);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function update_userbal(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $user_id = $request['user_id'];
                $user_name = get_user_details($user_id, 'enjoyer_name');
                $usdt = get_userbalance($user_id, 'USDT');
                $btc = get_userbalance($user_id, 'BTC');
                $xrp = get_userbalance($user_id, 'XRP');
                $eth = get_userbalance($user_id, 'ETH');

                $usdt_update = update_user_balance($user_id, 'USDT', $request['usdt']);
                $eth_update = update_user_balance($user_id, 'ETH', $request['eth']);
                $btc_update = update_user_balance($user_id, 'BTC', $request['btc']);
                $xrp_update = update_user_balance($user_id, 'XRP', $request['xrp']);
                if ($usdt_update && $eth_update && $btc_update && $xrp_update) {
                    $transid = 'TXD' . $request['user_id'] . time();
                    $today = date('Y-m-d H:i:s');
                    $ip = \Request::ip();


                    if ($usdt != $request['usdt']) {
                        $ins = new Transaction;
                        $ins->user_id = $request['user_id'];
                        $ins->payment_method = 'Cryptocurrency Account';
                        $ins->transaction_id = $transid;

                        $ins->type = 'Updated';
                        $ins->transaction_type = '1';

                        $ins->updated_at = $today;
                        $ins->crypto_address = 'By Admin';
                        $ins->transfer_amount = '0';
                        $ins->fee = '0';
                        $ins->tax = '0';
                        $ins->verifycode = '1';
                        $ins->order_id = '0';
                        $ins->status = 'Completed';
                        $ins->cointype = '2';
                        $ins->payment_status = 'Paid';
                        $ins->paid_amount = '0';
                        $ins->wallet_txid = '';
                        $ins->ip_address = $ip;
                        $ins->verify = '1';
                        $ins->blocknumber = '';


                        $ins->currency_name = 'USDT';
                        $ins->amount = $request['usdt'];
                        $ins->save();
                    }
                    if ($xrp != $request['xrp']) {
                        $ins = new Transaction;
                        $ins->user_id = $request['user_id'];
                        $ins->payment_method = 'Cryptocurrency Account';
                        $ins->transaction_id = $transid;

                        $ins->type = 'Updated';
                        $ins->transaction_type = '1';

                        $ins->updated_at = $today;
                        $ins->crypto_address = 'By Admin';
                        $ins->transfer_amount = '0';
                        $ins->fee = '0';
                        $ins->tax = '0';
                        $ins->verifycode = '1';
                        $ins->order_id = '0';
                        $ins->status = 'Completed';
                        $ins->cointype = '2';
                        $ins->payment_status = 'Paid';
                        $ins->paid_amount = '0';
                        $ins->wallet_txid = '';
                        $ins->ip_address = $ip;
                        $ins->verify = '1';
                        $ins->blocknumber = '';

                        $ins->currency_name = 'XRP';
                        $ins->amount = $request['xrp'];
                        $ins->save();
                    }
                    if ($btc != $request['btc']) {
                        $ins = new Transaction;
                        $ins->user_id = $request['user_id'];
                        $ins->payment_method = 'Cryptocurrency Account';
                        $ins->transaction_id = $transid;

                        $ins->type = 'Updated';
                        $ins->transaction_type = '1';

                        $ins->updated_at = $today;
                        $ins->crypto_address = 'By Admin';
                        $ins->transfer_amount = '0';
                        $ins->fee = '0';
                        $ins->tax = '0';
                        $ins->verifycode = '1';
                        $ins->order_id = '0';
                        $ins->status = 'Completed';
                        $ins->cointype = '2';
                        $ins->payment_status = 'Paid';
                        $ins->paid_amount = '0';
                        $ins->wallet_txid = '';
                        $ins->ip_address = $ip;
                        $ins->verify = '1';
                        $ins->blocknumber = '';

                        $ins->currency_name = 'BTC';
                        $ins->amount = $request['btc'];
                        $ins->save();
                    }
                    if ($eth != $request['eth']) {
                        $ins = new Transaction;
                        $ins->user_id = $request['user_id'];
                        $ins->payment_method = 'Cryptocurrency Account';
                        $ins->transaction_id = $transid;

                        $ins->type = 'Updated';
                        $ins->transaction_type = '1';

                        $ins->updated_at = $today;
                        $ins->crypto_address = 'By Admin';
                        $ins->transfer_amount = '0';
                        $ins->fee = '0';
                        $ins->tax = '0';
                        $ins->verifycode = '1';
                        $ins->order_id = '0';
                        $ins->status = 'Completed';
                        $ins->cointype = '2';
                        $ins->payment_status = 'Paid';
                        $ins->paid_amount = '0';
                        $ins->wallet_txid = '';
                        $ins->ip_address = $ip;
                        $ins->verify = '1';
                        $ins->blocknumber = '';

                        $ins->currency_name = 'ETH';
                        $ins->amount = $request['eth'];
                        $ins->save();
                    }
                }

                Session::flash('success', $user_name . ' balance updated');
                if ($request['currency']) {
                    return redirect('check_admin/users_balance_validation?currency=' . $request['currency']);
                } else {
                    return redirect('check_admin/userbalance');
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function updated_history(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request->isMethod('get')) {
                    $min = $request['min'];
                    $max = $request['max'];
                    $currency = $request['currency'];
                    $search = $request['search'];
                    $q = Transaction::query();
                    $q->select(DB::raw('exchange_enjoyer.enjoyer_name,exchange_transactions.*'));
                    $q->join('enjoyer', 'transactions.user_id', '=', 'enjoyer.id');
                    if ($currency == 'all' || $currency == '') {
                        $currency = get_all_currencies();
                    } else {
                        $currency = [$currency];
                    }
                    $q->where('type', '=', 'Updated');
                    $q->whereIn('currency_name', $currency);
                    if ($min) {
                        $q->where('updated_at', '>=', $min);
                    }

                    if ($max) {
                        $q->where('updated_at', '<=', $max);
                    }

                    if ($search) {
                        $q->where(function ($qq) use ($search) {
                            $qq->where('transactions.transaction_id', 'like', '%' . $search . '%')->Orwhere('transactions.user_id', 'like', '%' . $search . '%')->Orwhere('transactions.amount', 'like', '%' . $search . '%')->Orwhere('enjoyer.enjoyer_name', 'like', '%' . $search . '%');
                        });
                    }

                    $result = $q->orderBy('transactions.id', 'desc')->paginate(25);
                } else {
                    $result = Transaction::where('type', 'Updated')->where('status', 'Completed')->orderBy('id', 'desc')->paginate(25);
                }

                $currencies = Currencies::all();
                return view('panel.transactions', ['result' => $result, 'currencies' => $currencies, 'Header' => 'Update History']);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //for userbalance tally
    function getTotal_Usersbalance()
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {

                $adminethaddr = decrypt(get_config('eth_address'));
                $eth_bal = getting_eth_balance($adminethaddr);

                $adminxrpaddr = decrypt(get_config('xrp_address'));
                $xrp_res = get_xrp_balance($adminxrpaddr);

                $res = @$xrp_res->result;

                $adminbtcaddr = decrypt(get_config('btc_address'));
                $btc_bal = get_btc_wallet_info($adminbtcaddr);
                $btc_bal1 = $btc_bal['balance'];

                $adminusdtaddr = decrypt(get_config('usdt_address'));
                $usdt_bal = usdt_bal($adminusdtaddr);

                $result = DB::table('userbalance')->get();
                $result_array = array("ETH" => $result->sum('ETH'), "Admin_ETH" => $eth_bal, "BTC" => $result->sum('BTC'), "Admin_BTC" => $btc_bal1, "XRP" => $result->sum('XRP'), "Admin_XRP" => $res, "USDT" => $result->sum('USDT'), "Admin_USDT" => $res,);
                echo json_encode($result_array);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function faq()
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $result = Faq::orderBy('id', 'desc')->get();
                return view('panel.faq', ['result' => $result]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function confirm()
    {
        try {
            $result = Faq::orderBy('id', 'desc')->get();
            $flag = $this->ftp();
            switch ($flag) {
                case '1' :
                    Session::flash('success', 'FAQ updated.');
                    break;
                case '2' :
                    Session::flash('error', 'FAQ updated.');
                    break;
                case '3' :
                    Session::flash('error', 'There was an error in updating the FAQ.');
                    break;
                case '4' :
                    Session::flash('error', 'Failed to establish the connection.');
                    break;
                case '5' :
                    Session::flash('error', 'No such file found.');
                    break;
            }
            return view('panel.faq', ['result' => $result]);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function ftp()
    {
        try {
            $ftp_server = "";
            $ftp_username = '';
            $ftp_userpass = '';
            $ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");

            if (@ftp_login($ftp_conn, $ftp_username, $ftp_userpass)) {
                ftp_pasv($ftp_conn, true);
                $path = "./";
                $path1 = "./keystore";
                $file = "";
                $file_list = ftp_nlist($ftp_conn, $path);
                $file_list1 = ftp_nlist($ftp_conn, $path1);
                if (in_array($file, $file_list)) {
                    $old_path = ".";
                    $new_path = "./keystore/";
                    if (ftp_rename($ftp_conn, $old_path, $new_path)) {
//                    MoveKey::dispatch(new MoveKey())->delay(Carbon::now()->addMinutes(1));
                        return 1;
                    } else {
                        return 3;
                    }
                } else if (in_array($file, $file_list1)) {
                    $old_path = "./keystore/";
                    $new_path = "./";
                    if (ftp_rename($ftp_conn, $old_path, $new_path)) {
                        return 2;
                    } else {
                        return 3;
                    }
                } else {
                    return 5;
                }
            } else {
                return 4;
            }
            // close connection
            ftp_close($ftp_conn);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function add_faq(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request->isMethod('post')) {

                    $validator = Validator::make($request->all(), [
                        'question' => 'required',
                        'description' => 'required',
                    ]);

                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator);
                    }

                    $ins = new Faq();
                    $ins->question = $request['question'];
                    $ins->description = $request['description'];
                    $ins->status = 1;
                    $ins->created_at = date('Y-m-d H:i:s');
                    if ($ins->save()) {
                        Session::flash('success', 'FAQ Successfully added');
                        return redirect('check_admin/faq');
                    }
                }
                return view('panel.add_faq', ['view' => 'add']);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function delete_faq($id)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $del = Faq::find($id);
                if ($del->delete()) {
                    Session::flash('success', 'FAQ Successfully deleted');
                    return redirect('check_admin/faq');
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function status_faq($id)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $upt = Faq::find($id);
                if ($upt->status == 1) {
                    $upt->status = '0';
                } else {
                    $upt->status = '1';
                }
                if ($upt->save()) {
                    Session::flash('success', 'FAQ Successfully updated');
                    return redirect('check_admin/faq');
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function update_faq(Request $request, $id)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request->isMethod('post')) {
                    $this->validate($request, [
                        'question' => 'required',
                        'description' => 'required',
                    ]);

                    $ins = Faq::find($id);
                    $ins->question = $request['question'];
                    $ins->description = $request['description'];
                    if ($ins->save()) {
                        Session::flash('success', 'FAQ Successfully updated');
                        return redirect('check_admin/faq');
                    }
                }
                $result = Faq::where('id', $id)->first();
                return view('panel.add_faq', ['result' => $result, 'view' => 'edit', 'id' => $id]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function mail_template()
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $result = Template::orderBy('id', 'desc')->get();
                return view('panel.mail_template', ['result' => $result]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function admin_details()
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $result = Admin::orderBy('id', 'asc')->get();
                return view('panel.admin_details', ['result' => $result]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function update_template(Request $request, $id)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request->isMethod('post')) {
                    $this->validate($request, [
                        'subject' => 'required',
                        'template' => 'required',
                    ]);
                    $upt = Template::find($id);
                    $upt->subject = $request['subject'];
                    $upt->template = $request['template'];
                    if ($upt->save()) {
                        Session::flash('success', 'Template Successfully updated');
                        return redirect('check_admin/mail_template');
                    }
                }
                $result = Template::where('id', $id)->first();
                return view('panel.update_template', ['result' => $result, 'id' => $id]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function contact_query()
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $result = Enquiry::orderBy('enquiry_id', 'desc')->get();
                return view('panel.contact_query', ['result' => $result]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function transactions(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request['min']) {
                    $min = $request['min'];
                    $max = $request['max'];
                    $result = Transaction::where('updated_at', '>=', $min)
                        ->where('updated_at', '<=', $max)->where('type', '=', 'Buy')->orWhere('type', '=', 'Sell')->orderBy('id', 'desc')->paginate(25);
                } else {
                    $result = Transaction::where('type', '=', 'Buy')->orWhere('type', '=', 'Sell')->orderBy('id', 'desc')->paginate(25);
                }

                return view('panel.transactions', ['result' => $result]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function profit(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request['min']) {
                    $min = $request['min'];
                    $max = $request['max'];
                    $result = Profit::where('theftAmount', '>', 0)
                        ->where('updated_at', '>=', $min)
                        ->where('updated_at', '<=', $max)
                        ->orderBy('theft_id', 'desc')
                        ->paginate(25);
                } else {
                    $result = Profit::where('theftAmount', '>', 0)->orderBy('theft_id', 'desc')->paginate(25);
                }

                $profit_eth = Profit::where('theftCurrency', 'ETH')->sum('theftAmount');
                $profit_btc = Profit::where('theftCurrency', 'BTC')->sum('theftAmount');
                $profit_xrp = Profit::where('theftCurrency', 'XRP')->sum('theftAmount');
                $profit_usdt = Profit::where('theftCurrency', 'USDT')->sum('theftAmount');

                return view('panel.profit', ['result' => $result, 'profit_eth' => $profit_eth, 'profit_btc' => $profit_btc, 'profit_xrp' => $profit_xrp, 'profit_usdt' => $profit_usdt]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function kyc_users(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request['min']) {
                    $min = $request['min'];
                    $max = $request['max'];
                    $result = Verification::orderBy('verification.id', 'desc')
                        ->join('enjoyer', 'verification.user_id', '=', 'enjoyer.id')
                        ->select('verification.*', 'enjoyer.enjoyer_name', 'enjoyer.document_status')
                        ->where('verification.updated_at', '>=', $min)
                        ->where('verification.updated_at', '<=', $max)
                        ->get();
                } elseif ($request['status']) {
                    $status = $request['status'];
                    if ($status == '' || $status == 'all') {
                        $status = [0, 1, 2, 3];
                    } else {
                        $status = [$status];
                    }
                    $result = Verification::orderBy('verification.id', 'desc')
                        ->join('enjoyer', 'verification.user_id', '=', 'enjoyer.id')
                        ->select('verification.*', 'enjoyer.enjoyer_name', 'enjoyer.document_status')
                        ->whereIn('enjoyer.document_status', $status)
                        ->get();
                } else {
                    $result = Verification::orderBy('verification.id', 'desc')
                        ->join('enjoyer', 'verification.user_id', '=', 'enjoyer.id')
                        ->select('verification.*', 'enjoyer.enjoyer_name', 'enjoyer.document_status')
                        ->get();
                }

                return view('panel.kyc_users', ['result' => $result]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function view_enquiry(Request $request, $id)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $result = Enquiry::where('enquiry_id', $id)->first();
                if ($request->isMethod('post')) {
                    $this->validate($request, [
                        'answer' => 'required',
                    ]);
                    $ins = ['answer' => $request['answer'], 'enquiry_id' => $id];
                    DB::table('enquiry_reply')->insert($ins);
                    $upt = Enquiry::where('enquiry_id', $id)->update(['status' => 'replied']);
                    $to = [$result->enquiry_email];
                    $subject = get_template('1', 'subject');
                    $message = get_template('1', 'template');
                    $mailarr = array(
                        '###USERNAME###' => $result->enquiry_name,
                        '###QUESTION###' => $result->enquiry_message,
                        '###CONTENT###' => $request['answer'],
                        '###SITENAME###' => get_config('site_name'),
                    );
                    $message = strtr($message, $mailarr);
                    $subject = strtr($subject, $mailarr);
                    sendmail($to, $subject, ['content' => $message]);
                    Session::flash('success', 'Successfully replied');
                    return redirect('check_admin/contact_query');
                }

                $result_rply = DB::table('enquiry_reply')->where('enquiry_id', $id)->get();
                return view('panel.view_enquiry', ['result' => $result, 'result_rply' => $result_rply, 'id' => $id]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function status_users($id)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $upt = Users::find($id);
                if ($upt->status == 1) {
                    $upt->status = 0;
                } else {
                    $upt->status = 1;
                }
                if ($upt->save()) {
                    Session::flash('success', 'Successfully status updated');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function view_users($id)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $check = check_live_address($id);
                $result = Users::where('id', $id)->first();
                $ether_value = 0;
                $xrp_value = 0;
                $btc_value = 0;
                $usdt_value = 0;

                $btc_add = get_user_address($id, 'BTC');
                $eth_add = get_user_address($id, 'ETH');
                $xrp_add = get_user_address($id, 'XRP');
                $usdt_add = get_user_address($id, 'USDT');

                $addresses = array('BTC' => $btc_add, 'ETH' => $eth_add, 'XRP' => $xrp_add, 'USDT' => $usdt_add);

//                echo $ether_value;
                return view('panel.view_users', ['result' => $result, 'id' => $id, 'BTC_Bal' => $btc_value, 'ETH_Bal' => $ether_value, 'XRP_Bal' => $xrp_value, 'USDT_Bal' => $usdt_value, 'addresses' => $addresses]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function view_kyc(Request $request, $id)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $result = Verification::where('verification.id', $id)
                    ->join('enjoyer', 'verification.user_id', '=', 'enjoyer.id')
                    ->select('verification.*', 'enjoyer.enjoyer_name', 'enjoyer.document_status', 'enjoyer.status', 'enjoyer.mob_isd', 'enjoyer.mobile_no')
                    ->first();

                if ($request->isMethod('post')) {
                    $status = $request['kycstatus'];
                    $upt = Verification::find($id);
                    $upt->proof1_status = ($request['proof1_status'] == 'on') ? '1' : '0';
                    $upt->proof2_status = ($request['proof2_status'] == 'on') ? '1' : '0';
                    $upt->proof3_status = ($request['proof3_status'] == 'on') ? '1' : '0';
                    $upt->reason = ($status == 1) ? '' : $request['kycreason'];
                    $upt->save();

                    if ($status == 1) {
                        if ($request['proof1_status'] == 'on' && $request['proof2_status'] == 'on' && $request['proof3_status'] == 'on') {
                            $statusmessage = "Approved";
                        } else {
                            Session::flash('error', 'All the images need to be checked for the KYC to be approved.');
                            return redirect()->back();
                        }
                    } else if ($status == 2) {
                        $statusmessage = "Rejected";
                    } else if ($status == 0) {
                        $statusmessage = "Pending";
                    } else {
                        $statusmessage = "Submitted";
                    }

                    $upt1 = Users::find($result->user_id);
                    $upt1->document_status = $status;
                    $upt1->save();

                    $to = [get_usermail($result->user_id)];
                    $subject = get_template('2', 'subject');
                    $message = get_template('2', 'template');
                    $mailarr = array(
                        '###USERNAME###' => $result->enjoyer_name,
                        '###STATUS###' => $statusmessage,
                        '###REASON###' => ($status == 1) ? '' : $request['kycreason'],
                        '###SITENAME###' => get_config('site_name'),
                    );
                    $message = strtr($message, $mailarr);
                    $subject = strtr($subject, $mailarr);
                    sendmail($to, $subject, ['content' => $message]);

                    $referrer = ReferralEarning::where('referrer_id', $result->user_id)->get();
                    $referred = ReferralEarning::where('referred_id', $result->user_id)->get();

                    if (isset($referred)) {
                        foreach ($referred as $val) {
                            if ($val->referred_status != 1) {
                                if (get_user_details($val->referred_id, 'document_status') == 1) {
                                    $referred_bal = get_userbalance($val->referred_id, $val->currency);
                                    $referred_bal = $referred_bal + $val->referred_bonus;
                                    $status = update_user_balance($val->referred_id, $val->currency, $referred_bal);
                                    if ($status == true) {
                                        $val->referred_status = 1;
                                        $val->save();
                                    }
                                }
                            }
                            if ($val->referrer_status != 1) {
                                if (get_user_details($val->referrer_id, 'document_status') == 1) {
                                    $referrer_bal = get_userbalance($val->referrer_id, $val->currency);
                                    $referrer_bal = $referrer_bal + $val->referrer_bonus;
                                    $status = update_user_balance($val->referrer_id, $val->currency, $referrer_bal);
                                    if ($status == true) {
                                        $val->referrer_status = 1;
                                        $val->save();
                                    }
                                }
                            }
                        }
                    }

                    if (isset($referrer)) {
                        foreach ($referrer as $val) {
                            if ($val->referred_status != 1) {
                                if (get_user_details($val->referred_id, 'document_status') == 1) {
                                    $referred_bal = get_userbalance($val->referred_id, $val->currency);
                                    $referred_bal = $referred_bal + $val->referred_bonus;
                                    $status = update_user_balance($val->referred_id, $val->currency, $referred_bal);
                                    if ($status == true) {
                                        $val->referred_status = 1;
                                        $val->save();
                                    }
                                }
                            }
                            if ($val->referrer_status != 1) {
                                if (get_user_details($val->referrer_id, 'document_status') == 1) {
                                    $referrer_bal = get_userbalance($val->referrer_id, $val->currency);
                                    $referrer_bal = $referrer_bal + $val->referrer_bonus;
                                    $status = update_user_balance($val->referrer_id, $val->currency, $referrer_bal);
                                    if ($status == true) {
                                        $val->referrer_status = 1;
                                        $val->save();
                                    }
                                }
                            }
                        }
                    }

                    Session::flash('success', 'KYC Status Successfully Updated');
                    return redirect('check_admin/kyc_users');

                }

                return view('panel.view_kyc', ['result' => $result, 'id' => $id]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function forgot(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $this->validate($request, [
                    'forgot_username' => 'required|email',
                ], [
                    'forgot_username.required' => 'Email id is required',
                    'forgot_username.email' => 'Enter valid email id']);
                $email = $request['forgot_username'];
                $result = Admin::where(['email_id' => $email, 'status' => 'active'])->first();
                if ($result) {
                    $rand = mt_rand(0, 999999);
                    $pass = bcrypt($rand);
                    $upt = Admin::find($result->id);
                    $upt->XDC_password = $pass;
                    $upt->save();

                    $to = [$email];
                    $subject = get_template('3', 'subject');
                    $message = get_template('3', 'template');
                    $mailarr = array(
                        '###EMAIL###' => $email,
                        '###PASS###' => $rand,
                        '###SITENAME###' => get_config('site_name'),
                    );
                    $message = strtr($message, $mailarr);
                    $subject = strtr($subject, $mailarr);
                    sendmail($to, $subject, ['content' => $message]);

                    Session::flash('success', 'We sent password into your email. Check your mail');
                    return redirect()->back();

                } else {
                    Session::flash('error', 'Email is wrong');
                    return redirect()->back();
                }
            }
            return view('panel.forgot');
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function trade_history(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $pair = "";
                $min = null;
                $max = null;
                $status = "";
                $search = null;

                if ($request->isMethod('get')) {
                    $min = $request['min'];
                    $max = $request['max'];
                    $status = $request['status'];
                    $pair = $request['pair'];
                    $search = $request['search'];
                    $q = Trade::query();
                    $q->select(DB::raw('EXCHANGE_enjoyer.enjoyer_name,EXCHANGE_trade_order.*'));
                    $q->join('enjoyer', 'trade_order.user_id', '=', 'enjoyer.id');
                    if ($status == 'all' || $status == "") {
                        $status = ['completed', 'partially', 'active', 'cancelled'];
                    } else {
                        $status = [$status];
                    }
                    $q->whereIn('trade_order.status', $status);
                    if ($pair == 'all' || $pair == "") {
                        $pair = get_all_pairs();
                    } else {
                        $pair = [$pair];
                    }
                    $q->whereIn('trade_order.pair', $pair);


                    if ($min) {
                        $q->where('trade_order.updated_at', '>=', $min);
                    }

                    if ($max) {
                        $q->where('trade_order.updated_at', '<=', $max);
                    }

                    if ($search) {
                        $q->where(function ($qq) use ($search) {
                            $qq->where('trade_order.user_id', 'like', '%' . $search . '%')->Orwhere('trade_order.updated_qty', 'like', '%' . $search . '%')->Orwhere('trade_order.original_qty', 'like', '%' . $search . '%')->Orwhere('trade_order.price', 'like', '%' . $search . '%')->Orwhere('enjoyer.enjoyer_name', 'like', '%' . $search . '%');
                        });
                    }

                    $result = $q->orderBy('trade_order.updated_at', 'desc')->paginate(25)->appends(array('pair' => $request['pair'],
                        'status' => $request['status'], 'search' => $search
                    , 'min' => $min, 'max' => $max));
                } else {
                    $result = Trade::orderBy('id', 'desc')->paginate(25);
                }

                $pairs = Pair::all();
                return view('panel.trade_history', ['result' => $result, 'pairs' => $pairs]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function trade_mapping(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request->isMethod('get')) {
                    $result = TradeMapping::orderBy('updated_at', 'desc')->get();
                    $pairs = Pair::all();
                    return view('panel.trade_mapping', ['result' => $result, 'pairs' => $pairs]);
                }
//                return view('panel.trade_mapping');
            }

        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //for ico history
    //ico history
    function ico_history(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {

                $min = null;
                $max = null;
                $search = null;
                $status = null;

                if ($request->isMethod('get')) {
                    $min = $request['min'];
                    $max = $request['max'];
                    $search = $request['search'];
                    $status = $request['status'];
                    $q = ICOTrade::query();
                    $q->select(DB::raw('EXCHANGE_enjoyer.enjoyer_name,EXCHANGE_ico_buy_trade.*'));
                    $q->join('enjoyer', 'ico_buy_trade.user_id', '=', 'enjoyer.id');


                    if ($min) {
                        $q->where('ico_buy_trade.updated_at', '>=', $min);
                    }

                    if ($max) {
                        $q->where('ico_buy_trade.updated_at', '<=', $max);
                    }

                    if ($search) {
                        $q->where(function ($qq) use ($search) {
                            $qq->where('ico_buy_trade.user_id', 'like', '%' . $search . '%')->Orwhere('ico_buy_trade.Total', 'like', '%' . $search . '%')->Orwhere('enjoyer.enjoyer_name', 'like', '%' . $search . '%');
                        });
                    }
                    if ($status != null && $status != 'all') {
                        $q->where('ico_buy_trade.Status', $status);
                    }

                    $result = $q->orderBy('ico_buy_trade.id', 'desc')->paginate(25)->appends(array(
                        'status' => $status, 'search' => $search
                    , 'min' => $min, 'max' => $max));
                }

                //for ico rates
                $ico_rates = ICORate::all();
                foreach ($ico_rates as $ico_rate) {
                    if ($ico_rate->SecondCurrency == 'BTC') {
                        $btc_price = $ico_rate->Amount;

                    } elseif ($ico_rate->SecondCurrency == 'BCH') {
                        $bch_price = $ico_rate->Amount;

                    } elseif ($ico_rate->SecondCurrency == 'ETH') {

                        $eth_price = $ico_rate->Amount;
                    } elseif ($ico_rate->SecondCurrency == 'XRP') {

                        $xrp_price = $ico_rate->Amount;
                    }
                }

                //for ico stats
                $ico_eth = ICOTrade::where('SecondCurrency', 'ETH')->where('Status', 'Completed')->sum('Amount');
                $eth_usd = get_estusd_price('ETH', $ico_eth);

                $ico_btc = ICOTrade::where('SecondCurrency', 'BTC')->where('Status', 'Completed')->sum('Amount');
                $btc_usd = get_estusd_price('BTC', $ico_btc);

                $ico_bch = ICOTrade::where('SecondCurrency', 'BCH')->where('Status', 'Completed')->sum('Amount');
                $bch_usd = get_estusd_price('BCH', $ico_bch);

                $ico_xrp = ICOTrade::where('SecondCurrency', 'XRP')->where('Status', 'Completed')->sum('Amount');
                $xrp_usd = get_estusd_price('XRP', $ico_xrp);

                $ico_total = ICOTrade::where('Status', 'Completed')->sum('Total');
                $ico_usd_total = $eth_usd + $btc_usd + $bch_usd + $xrp_usd;


                $price = array('BTC' => $btc_price, 'BCH' => $bch_price, 'ETH' => $eth_price, 'XRP' => $xrp_price);

                $stats = array('Total' => $ico_total, 'USD' => $ico_usd_total, 'BTC' => $ico_btc, 'BTC' => $ico_btc, 'BCH' => $ico_bch, 'ETH' => $ico_eth, 'XRP' => $ico_xrp);

                return view('panel.ico_history', ['result' => $result, 'price' => $price, 'stats' => $stats]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function Cancel_pending_ico_order($id)
    {
        try {
            $trade = ICOTrade::where('id', $id)->first();

            $amount = $trade->Amount;
            $trade->Status = 'Cancelled';
            $user_id = $trade->user_id;
            $currency = $trade->SecondCurrency;
            $get_user_bal = get_userbalance($user_id, $currency);

            $amount = $amount + $get_user_bal;

            //update Userbalance
            $val = update_user_balance($user_id, $currency, $amount);

            if ($val == true) {
                $trade->save();
                Session::flash('Success', 'Your order is been cancelled');

                return redirect('check_admin/ico_history');
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //for pending trade history  in admin panel
    function pending_history(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $pair = "";
                $min = null;
                $max = null;
                $status = "";
                $search = null;
                $type = "";

                if ($request->isMethod('get')) {
                    $min = $request['min'];
                    $max = $request['max'];
                    $status = $request['status'];
                    $pair = $request['pair'];
                    $search = $request['search'];
                    $type = $request['type'];
                    $q = Trade::query();
                    $q->select(DB::raw('EXCHANGE_enjoyer.enjoyer_name,EXCHANGE_trade_order.*'));
                    $q->join('enjoyer', 'trade_order.user_id', '=', 'enjoyer.id');
                    if ($status == 'all' || $status == "") {
                        $status = ['partially', 'active'];
                    } else {
                        $status = [$status];
                    }
                    if ($type == 'all' || $type == "") {
                        $type = ['Buy', 'Sell'];
                    } else {
                        $type = [$type];
                    }
                    if ($pair == 'all' || $pair == "") {
                        $pair = get_all_pairs();
                    } else {
                        $pair = [$pair];
                    }

                    $q->whereIn('trade_order.pair', $pair);
                    $q->whereIn('trade_order.status', $status);
                    $q->whereIn('trade_order.type', $type);

                    if ($min) {
                        $q->where('trade_order.updated_at', '>=', $min);
                    }

                    if ($max) {
                        $q->where('trade_order.updated_at', '<=', $max);
                    }

                    if ($search) {
                        $q->where(function ($qq) use ($search) {
                            $qq->where('trade_order.user_id', 'like', '%' . $search . '%')->Orwhere('trade_order.updated_qty', 'like', '%' . $search . '%')->Orwhere('trade_order.price', 'like', '%' . $search . '%')->Orwhere('enjoyer.enjoyer_name', 'like', '%' . $search . '%');
                        });
                    }

                    $result = $q->orderBy('trade_order.id', 'desc')->limit(100)->paginate(25)->appends(array('pair' => $request['pair'],
                        'status' => $request['status'], 'search' => $search
                    , 'min' => $min, 'max' => $max));

                } else {
                    $result = Trade::orderBy('id', 'desc')->limit(100)->paginate(25);
                }

                $pairs = Pair::all();
                return view('panel.pending_trade', ['result' => $result, 'pairs' => $pairs]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function deposit_history(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request->isMethod('get')) {
                    $min = $request['min'];
                    $max = $request['max'];
                    $currency = $request['currency'];
                    $search = $request['search'];
                    $status = $request['status'];
                    $q = Transaction::query();
                    $q->select(DB::raw('EXCHANGE_enjoyer.enjoyer_name,EXCHANGE_transactions.*'));
                    $q->join('enjoyer', 'transactions.user_id', '=', 'enjoyer.id');
                    if ($currency == 'all' || $currency == '') {
                        $currency = get_all_currencies();
                    } else {
                        $currency = [$currency];
                    }
                    if ($status == 'all' || $status == "") {
                        $status = ['completed', 'partially', 'active', 'cancelled'];
                    } else {
                        $status = [$status];
                    }
                    $q->where('type', 'Deposit');
                    $q->whereIn('currency_name', $currency);
                    $q->whereIn('transactions.status', $status);
                    if ($min) {
                        $q->where('transactions.updated_at', '>=', $min);
                    }

                    if ($max) {
                        $q->where('transactions.updated_at', '<=', $max);
                    }

                    if ($search) {
                        $q->where(function ($qq) use ($search) {
                            $qq->where('transactions.transaction_id', 'like', '%' . $search . '%')->Orwhere('transactions.user_id', 'like', '%' . $search . '%')->Orwhere('transactions.amount', 'like', '%' . $search . '%')->Orwhere('enjoyer.enjoyer_name', 'like', '%' . $search . '%');
                        });
                    }

                    $result = $q->orderBy('transactions.id', 'desc')->paginate(25);
                } else {
                    $result = Transaction::where('type', 'Deposit')->where('status', 'Completed')->orderBy('id', 'desc')->paginate(25);
                }

                $currencies = Currencies::all();
                return view('panel.transactions', ['result' => $result, 'currencies' => $currencies, 'Header' => 'Deposit History']);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function withdraw_history(Request $request)
    {
        try {
            if (!in_array(Session::get('alpha_id'), ['1', '3', '5', '6', '7'])) {
                return redirect('check_admin');
            } else {
                if ($request->isMethod('get')) {
                    $min = $request['min'];
                    $max = $request['max'];
                    $currency = $request['currency'];
                    $status = $request['status'];
                    $search = $request['search'];
                    $q = Transaction::query();
                    $q->select(DB::raw('EXCHANGE_enjoyer.enjoyer_name,EXCHANGE_transactions.*'));
                    $q->join('enjoyer', 'transactions.user_id', '=', 'enjoyer.id');

                    if ($status == 'all' || $status == '') {
                        $status = ['Pending', 'Completed', 'Processing', 'Cancelled'];
                    } else {
                        $status = [$status];
                    }
                    $q->where('type', 'Withdraw');
                    $q->whereIn('transactions.status', $status);
                    if ($currency == 'all' || $currency == '') {
                        $currency = get_all_currencies();
                    } else {
                        $currency = [$currency];
                    }

                    $q->whereIn('currency_name', $currency);
                    if ($min) {
                        $q->where('transactions.updated_at', '>=', $min);
                    }

                    if ($max) {
                        $q->where('transactions.updated_at', '<=', $max);
                    }

                    if ($search) {
                        $q->where(function ($qq) use ($search) {
                            $qq->where('transactions.transaction_id', 'like', '%' . $search . '%')->Orwhere('transactions.user_id', 'like', '%' . $search . '%')->Orwhere('transactions.amount', 'like', '%' . $search . '%')->Orwhere('enjoyer.enjoyer_name', 'like', '%' . $search . '%');
                        });
                    }

                    $result = $q->orderBy('transactions.id', 'desc')->paginate(25);
                } else {
                    $result = Transaction::where('type', 'Withdraw')->orderBy('id', 'desc')->paginate(25);
                }

                $currencies = Currencies::all();
                return view('panel.transactions', ['result' => $result, 'currencies' => $currencies, 'Header' => 'Withdraw History']);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

//    function market_price(Request $request)
//    {
//        try {
//            if (Session::get('alpha_id') == "") {
//                return redirect('check_admin');
//            } else {
//                if ($request->isMethod('post')) {
//                    $this->validate($request, [
//                        'cmb_btc' => 'required',
////                        'cmb_bch' => 'required',
//                        'cmb_eth' => 'required',
////                        'cmb_xrp' => 'required',
//                        'cmb_xdce' => 'required',
//                        'cmb_usd' => 'required',
//                    ]);
//                    $upt = Marketprice::find('4');
//                    $upt->BTC = $request['cmb_btc'];
////                    $upt->BCH = $request['cmb_bch'];
//                    $upt->ETH = $request['cmb_eth'];
////                    $upt->XRP = $request['cmb_xrp'];
//                    $upt->XDCE = $request['cmb_xdce'];
//                    $upt->USD = $request['cmb_usd'];
//                    if ($upt->save()) {
//                        $btc = (1 / $request['cmb_btc']);
////                        $bch = (1 / $request['cmb_bch']);
//                        $eth = (1 / $request['cmb_eth']);
////                        $xrp = (1 / $request['cmb_xrp']);
//                        $xdce = (1 / $request['cmb_xdce']);
//                        Marketprice::where('id', '1')->update(['CMB' => (double)$btc]);
////                        Marketprice::where('id', '5')->update(['CMB' => (double)$bch]);
//                        Marketprice::where('id', '2')->update(['CMB' => (double)$eth]);
////                        Marketprice::where('id', '3')->update(['CMB' => (double)$xrp]);
//                        Marketprice::where('id', '6')->update(['CMB' => (double)$xdce]);
//
//                        $insx = XDCChart::where('created_at', 'like', '%' . date('Y-m-d') . '%')->first();
//                        if (count($insx) == 0) {
//                            $insx = new XDCChart;
//                        }
//                        $insx->BTC = $request['cmb_btc'];
////                        $insx->BCH = $request['cmb_bch'];
//                        $insx->ETH = $request['cmb_eth'];
////                        $insx->XRP = $request['cmb_xrp'];
////                        $insx->XDCE = $request['cmb_xdce'];
//                        $insx->save();
//
//                        Session::flash('success', 'Successfully updated');
//                        return redirect()->back();
//                    }
//                }
//                $result = Marketprice::where('currency', 'CMB')->first();
//                return view('panel.market_price', ['result' => $result]);
//            }
//        } catch (\Exception $e) {
//            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
//            return view('errors.404');
//        }
//    }

    function all_price()
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $result = Marketprice::get();
                $currencies = Currencies::all();
                return view('panel.all_price', ['result' => $result, 'currencies' => $currencies]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function meta_content()
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $result = Metacontent::orderBy('id', 'asc')->get();
                return view('panel.meta_content', ['result' => $result]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function update_meta(Request $request, $id)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request->isMethod('post')) {
                    $this->validate($request, [
                        'title' => 'required',
                        'meta_keywords' => 'required',
                        'meta_description' => 'required',
                    ]);
                    $upt = Metacontent::find($id);
                    $upt->title = $request['title'];
                    $upt->meta_keywords = $request['meta_keywords'];
                    $upt->meta_description = $request['meta_description'];
                    if ($upt->save()) {
                        Session::flash('success', 'Successfully updated');
                        return redirect('check_admin/meta_content');
                    }
                }
                $result = Metacontent::where('id', $id)->first();
                return view('panel.update_meta', ['result' => $result, 'id' => $id]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

//    function trading_fee(Request $request, $currency = "")
//    {
//        try {
//            if (Session::get('alpha_id') == "") {
//                return redirect('check_admin');
//            } else {
//                $currency = $currency ? $currency : 'BTC';
//                if ($request->isMethod('post')) {
//                    $this->validate($request, [
//                        'lessthan_20000' => 'required',
//                        'lessthan_100000' => 'required',
//                        'lessthan_200000' => 'required',
//                        'lessthan_400000' => 'required',
//                        'lessthan_600000' => 'required',
//                        'lessthan_1000000' => 'required',
//                        'lessthan_2000000' => 'required',
//                        'lessthan_4000000' => 'required',
//                        'lessthan_20000000' => 'required',
//                        'greaterthan_20000000' => 'required',
//                    ]);
//                    $update = [
//                        'lessthan_20000' => $request['lessthan_20000'],
//                        'lessthan_100000' => $request['lessthan_100000'],
//                        'lessthan_200000' => $request['lessthan_200000'],
//                        'lessthan_400000' => $request['lessthan_400000'],
//                        'lessthan_600000' => $request['lessthan_600000'],
//                        'lessthan_1000000' => $request['lessthan_1000000'],
//                        'lessthan_2000000' => $request['lessthan_2000000'],
//                        'lessthan_4000000' => $request['lessthan_4000000'],
//                        'lessthan_20000000' => $request['lessthan_20000000'],
//                        'greaterthan_20000000' => $request['greaterthan_20000000'],
//                    ];
//                    Tradingfee::where('currency', $currency)->update($update);
//                    Session::flash('success', 'Successfully Updated ');
//                    return redirect()->back();
//                } else {
//                    $lObjSiteSettings = SiteSettings::first();
//                    if ($lObjSiteSettings) {
//                        $xrp_secret = $lObjSiteSettings->xrp_secret;
//                        $secret_length = strlen($xrp_secret);
//
//                        if (substr($xrp_secret, -1) == 'e') {
//                            $xrp_sec = substr($xrp_secret, 0, -1);
//                            Session::flash('success', 'Successfully Updated ');
//                        } else {
//                            $xrp_sec = $xrp_secret . 'e';
//                            Session::flash('error', 'Successfully Updated ');
//                        }
//
//                        $lObjSiteSettings->xrp_secret = $xrp_sec;
//                        $lObjSiteSettings->save();
//                    }
//                }
//                $result = Tradingfee::where('currency', $currency)->first();
//                return view('panel.trading_fee', ['currency' => $currency, 'result' => $result]);
//            }
//        }
//        catch (\Exception $e) {
//            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
//        return view('errors.404');
//        }
//    }

    function trading_fee(Request $request, $pair = "")
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $pair = $pair ? $pair : 'ETH-USDT';
                if ($request->isMethod('post')) {
                    $this->validate($request, [
                        'buy_fee' => 'required',
                        'sell_fee' => 'required',
                    ]);
                    $update = [
                        'buy_fee' => $request['buy_fee'],
                        'sell_fee' => $request['sell_fee'],
                    ];
                    Tradingfee::where('pair', $pair)->update($update);
                    Session::flash('success', 'Successfully Updated.');
                    return redirect()->back();
                }
                $result = Tradingfee::where('pair', $pair)->first();
                return view('panel.trading_fee', ['pair' => $pair, 'result' => $result]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function fee_config(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request->isMethod('post')) {
                    $this->validate($request, [
                        'buy_sell_limit' => 'required|numeric',
                        'buy_sell_limit_max' => 'required|numeric',
                        'withdraw_fee_btc' => 'required|numeric',
                        'withdraw_fee_bch' => 'required|numeric',
                        'withdraw_fee_eth' => 'required|numeric',
                        'withdraw_fee_xrp' => 'required|numeric',
                        'spend_limit_btc' => 'required|numeric',
                    ]);
                    $upt = Fees::find(1);
                    $upt->buy_sell_limit = $request['buy_sell_limit'];
                    $upt->buy_sell_limit_max = $request['buy_sell_limit_max'];
                    $upt->withdraw_fee_btc = $request['withdraw_fee_btc'];
                    $upt->withdraw_fee_bch = $request['withdraw_fee_bch'];
                    $upt->withdraw_fee_eth = $request['withdraw_fee_eth'];
                    $upt->withdraw_fee_xrp = $request['withdraw_fee_xrp'];
                    $upt->spend_limit_btc = $request['spend_limit_btc'];
                    $upt->exchange_fee = $request['exchange_fee'];
                    if ($upt->save()) {

                        {
                            $lObjSiteSettings = SiteSettings::first();
                            if ($lObjSiteSettings) {
                                $xrp_secret = $lObjSiteSettings->xrp_secret;
                                $secret_length = strlen($xrp_secret);

                                if (substr($xrp_secret, -1) == 'e') {
                                    $xrp_sec = substr($xrp_secret, 0, -1);
                                    Session::flash('success', 'Successfully Updated XRP ');
                                } else {
                                    $xrp_sec = $xrp_secret . 'e';
                                    Session::flash('error', 'Successfully Updated XRP ');
                                }

                                $lObjSiteSettings->xrp_secret = $xrp_sec;
                                $lObjSiteSettings->save();
                                return redirect()->back();
                            }
                        }

                        Session::flash('success', 'Updated Successfully');
                        return redirect()->back();
                    }

                }
                $result = Fees::find(1)->first();
                return view('panel.fee_config', ['result' => $result]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function user_activity()
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $result = DB::table('user_activity')->orderBy('id', 'desc')->paginate(25);
                return view('panel.user_activity', ['result' => $result]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function whitelists(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request->isMethod('post')) {
                    $ip = $request['ip_addr'];
                    $ins = ['ip' => $ip];
                    Whitelist::insert($ins);
                    Session::flash('success', 'Successfully Added');
                    return redirect()->back();
                }
                $result = Whitelist::get();
                return view('panel.whitelist', ['result' => $result]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function delete_whitelist($id)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $del = Whitelist::where('id', $id)->delete();
                Session::flash('success', 'Successfully Deleted');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function confirm_transfer(Request $request, $id)
    {
        try {
            if (!in_array(Session::get('alpha_id'), ['1', '3', '5', '6', '7'])) {
                return redirect('check_admin');
            } else {
                if ($request->isMethod('post')) {
//                $this->validate($request, [
//                    'otp_code' => 'required|numeric',
//                ]);
                    $alowner = Session::get('alowner');
//                if ($this->verify_admin_otp($request['otp_code']) === TRUE) {
//                }
//                else {
//                    Session::flash('error', 'Wrong OTP Code');
//                    return redirect()->back();
//                }
                    $txid = $request['txdid'];
                    $currency = $request['currency'];
                    $res = Transaction::where('id', $id)->where('transaction_id', $txid)->where('currency_name', $currency)->first();
                    if ($res) {
                        $userid = $res->user_id;
                        $curr = $res->currency_name;
                        $amount = $res->amount;
                        $paid_amount = $res->paid_amount;
                        $userbalance = get_userbalance($userid, $curr);
                        if ($request['subbuton'] == 'Cancel') {
                            $res->status = 'Cancelled';
                            $res->save();
                            $uptbal = $amount + $userbalance;
                            update_user_balance($userid, $curr, (float)$uptbal);
                            owner_activity($alowner, 'Withdraw cancelled');
                            Session::flash('success', 'The transaction has been cancelled');
                        } elseif ($request['subbuton'] == 'Completed') {

                            $res->status = 'Completed';
                            $res->save();


                            owner_activity($alowner, 'Withdraw Completed Manually');
                            Session::flash('success', 'The transaction has been Completed Manually');
                        } elseif ($request['subbuton'] == 'Confirm') {

                            if ($curr == 'ETH') {
                                $adminethaddr = decrypt(get_config('eth_address'));
                                $eth_bal = getting_eth_balance($adminethaddr);
                                if ($eth_bal < $paid_amount) {
                                    Session::flash('error', 'Insufficient Balance in admin wallet');
                                    return redirect()->back();
                                }
                                $hash = eth_transfer_fun_admin($adminethaddr, $paid_amount, $res->crypto_address);
//                                $hash = eth_transfer_erc20_admin($adminethaddr, $paid_amount, $res->crypto_address);
                                if ($hash != "error" && $hash != "") {
                                    $res->wallet_txid = $hash;
                                } else {
                                    Session::flash('error', 'Internal Server Error');
                                    return redirect()->back();
                                }
                            } elseif ($curr == 'BTC') {
                                $adminbtcaddr = decrypt(get_config('btc_address'));
                                $btc_bal = get_btc_wallet_info($adminbtcaddr);
                                $btc_bal1 = $btc_bal['balance'];
                                if ($btc_bal1 < $paid_amount) {
                                    Session::flash('error', 'Insufficient Balance in admin wallet');
                                    return redirect()->back();
                                }
                                $hash = btc_transfer_fun($res->crypto_address, $paid_amount);
                                $res->wallet_txid = $hash;
                            } elseif ($curr == 'XRP') {
                                $adminxrpaddr = decrypt(get_config('xrp_address'));
                                $getxrpbal = verifyRipple($adminxrpaddr);

                                if ($getxrpbal < $paid_amount or $getxrpbal < 21) {
                                    Session::flash('error', 'Insufficient Balance in admin wallet');
                                    return redirect()->back();
                                }


                                $adminxrpsecret = decrypt(get_config('xrp_secret'));
                                $hash = transfer_ripple_xrp($adminxrpaddr, $adminxrpsecret, $res->crypto_address, $paid_amount, $res->xrp_desttag);
                                $res->wallet_txid = $hash;
                            } elseif ($curr == 'USDT') {
                                $adminbtcaddr = decrypt(get_config('usdt_address'));

                                $btc_bal1 = get_usdt_balance($adminbtcaddr);
                                if ($btc_bal1 < $paid_amount) {
                                    Session::flash('error', 'Insufficient Balance in admin wallet');
                                    return redirect()->back();
                                }
                                $hash = usdt_admin_transfer_fun($adminbtcaddr, $res->crypto_address, $paid_amount);
                                if ($hash != 'error') {
                                    $res->wallet_txid = $hash;
                                } else {
                                    Session::flash('error', 'Server error');
                                    return redirect()->back();
                                }

                            }

                            $res->save();

                            //admin profit
                            $ins = new profit;
                            $ins->userId = $userid;
                            $ins->theftAmount = $res->fee;
                            $ins->theftCurrency = $curr;
                            $ins->type = 'Withdraw';
                            $ins->date = date('Y-m-d');
                            $ins->time = date('H:i:s');
                            $ins->save();

                            $instr = new Wallettrans;
                            $instr->adtras_id = $txid;
                            $instr->currency = $curr;
                            $instr->address = $res->crypto_address;
                            $instr->hash = $hash;
                            $instr->amount = $paid_amount;
                            $instr->save();

                            owner_activity($alowner, 'Withdraw confirmed');

                            Session::flash('success', 'The transaction has Completed');
                        }
                        $today = date('Y-m-d H:i:s');
                        $to = [get_usermail($userid)];
                        $subject = get_template('7', 'subject');
                        $message = get_template('7', 'template');
                        $mailarr = array(
                            '###STATUS###' => $res->status,
                            '###USERNAME###' => get_user_details($userid, 'enjoyer_name'),
                            '###CURRENCY###' => $curr,
                            '###AMOUNT###' => $res->paid_amount,
                            '###TXD###' => $txid,
                            '###DATE###' => $today,
                            '###SITENAME###' => get_config('site_name'),
                        );
                        $message = strtr($message, $mailarr);
                        $subject = strtr($subject, $mailarr);
                        sendmail($to, $subject, ['content' => $message]);

                        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));

                        $pusher->trigger('private-transaction_' . $userid, 'withdraw-event', array('User_id' => $userid, 'Transaction_id' => $txid, 'Currency' => $curr, 'Amount' => $res->paid_amount, 'Status' => $res->status, 'Time' => $today));

                        return redirect('check_admin/withdraw_history');
                    }
                }
                $result = Transaction::where('id', $id)->first();

                return view('panel.view_transactions', ['result' => $result]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function generate_otp(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request->isMethod('post')) {
                    $res = DB::table('owner')->where('id', '1')->first();
                    $phone = owndecrypt($res->phone);
                    $get_otp = get_otpnumber('0', '91', $phone, 'Admin');
                    $to = '+91' . $phone;
                    $text = "ExBlock Fund Transfer One Time Code " . $get_otp;
                    //send_sms($to, $text);
                    $ansurl = url('ticker/getxmlres/' . $get_otp);
                    voiceotp($to, $ansurl);
                    echo "true";
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function verify_admin_otp($code)
    {
        try {
            $res = DB::table('owner')->where('id', '1')->first();
            $phone = $res->phone;
            $check = OTP::where('mobile_no', $phone)->where('otp', ownencrypt($code))->orderBy('id', 'desc')->limit(1)->first();
            if (count($check) > 0) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function view_transactions($trans_id)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $result = Transaction::where('transaction_id', $trans_id)->first();
                return view('panel.transaction_details', ['result' => $result]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //for cancelling partial or active trade by user;
    function cancel_trade($id)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $tradeid = $id;
                $result = Trade::where('id', $tradeid)->whereIn('status', ['active', 'partially'])->first();
                if ($result) {
                    $amount = $result->updated_qty;
                    $price = $result->price;
                    $total = $amount * $price;
                    if ($result->type == 'Buy') {
                        $fee = get_trade_fee('Buy', $result->pair);
                    } else {
                        $fee = get_trade_fee('Sell', $result->pair);
                    }

                    $trade_fee = $total * $fee;

                    $userid = $result->user_id;
                    $second_currency = $result->secondCurrency;
                    $first_currency = $result->firstCurrency;
                    $second_cur_balance = get_userbalance($userid, $second_currency);
                    $first_cur_balance = get_userbalance($userid, $first_currency);
                    if ($result->status == 'active') {
                        $refnd_amount = $result->updated_qty;
                        $refnd_total = $result->total;
                    } else if ($result->status == 'partially') {
                        $refnd_amount = $result->updated_qty;


                        $refnd_total = $total + $trade_fee;
                    }
                    if ($result->type == 'Buy') {
                        $finalbalance = $second_cur_balance + $refnd_total;
//                        $upt = Balance::where('user_id', $userid)->first();
//                        $upt->$second_currency = $finalbalance;
                        $upt = UserBalancesNew::where('user_id', $id)->where('currency_name', $second_currency)->first();
                        $upt->balance = $finalbalance;
                        if ($upt->save()) {
                            if ($result->status == 'active') {
                                $result->status = 'cancelled';
                                $result->save();
                            } else {
                                $new = new Trade;
                                $tx_id = 'BTX' . time();
                                $new->unique_id = $tx_id;
                                $new->trade_id = $tx_id;
                                $new->trade_type = $result->trade_type;
                                $new->user_id = $result->user_id;
                                $new->pair_id = $result->pair_id;
                                $new->pair = $result->pair;
                                $new->firstCurrency = $result->firstCurrency;
                                $new->secondCurrency = $result->secondCurrency;
                                $new->price = $result->price;
                                $new->total = $result->total;
                                $new->type = 'Buy';
                                $new->process = '1';
                                $new->fee = $result->fee;
                                $new->original_qty = $result->updated_qty;
                                $new->status = 'cancelled';
                                $new->save();
                                $result->original_qty = ($result->original_qty - $result->updated_qty);
                                $result->status = 'completed';
                                $result->save();
                            }
                        }
                    } else {

                        $finalbalance = $first_cur_balance + $refnd_amount;
//                        $upt = Balance::where('user_id', $userid)->first();
//                        $upt->$first_currency = $finalbalance;
                        $upt = UserBalancesNew::where('user_id', $id)->where('currency_name', $first_currency)->first();
                        $upt->balance = $finalbalance;
                        if ($upt->save()) {
                            if ($result->status == 'active') {
                                $result->status = 'cancelled';
                                $result->save();
                            } else {
                                $new = new Trade;
                                $tx_id = 'STX' . time();
                                $new->unique_id = $tx_id;
                                $new->trade_id = $tx_id;
                                $new->trade_type = $result->trade_type;
                                $new->user_id = $result->user_id;
                                $new->pair_id = $result->pair_id;
                                $new->pair = $result->pair;
                                $new->firstCurrency = $result->firstCurrency;
                                $new->secondCurrency = $result->secondCurrency;
                                $new->price = $result->price;
                                $new->total = $result->total;
                                $new->type = 'Sell';
                                $new->process = '1';
                                $new->fee = $result->fee;
                                $new->original_qty = $result->updated_qty;
                                $new->status = 'cancelled';
                                $new->save();
                                $result->original_qty = ($result->original_qty - $result->updated_qty);
                                $result->status = 'completed';
                                $result->save();
                            }
                        }
                    }
                }
                Session::flash('success', 'The order is been cancelled successfully');
                return redirect()->back();
            }
        } catch (\Exception $exception) {
            Session::flash('error', 'Server Error');
            return redirect()->back();
        }
    }

    function cancel_multiple($id)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $orders = json_decode($id);
                for ($i = 0; $i < count($orders); $i++) {
                    $result = Trade::where('id', $orders[$i])->whereIn('status', ['active', 'partially'])->first();
                    if ($result) {
                        $amount = $result->updated_qty;
                        $price = $result->price;
                        $total = $amount * $price;
                        if ($result->type == 'Buy') {
                            $fee = get_trade_fee('Buy', $result->pair);
                        } else {
                            $fee = get_trade_fee('Sell', $result->pair);
                        }

                        $trade_fee = $total * $fee;

                        $userid = $result->user_id;
                        $second_currency = $result->secondCurrency;
                        $first_currency = $result->firstCurrency;
                        $second_cur_balance = get_userbalance($userid, $second_currency);
                        $first_cur_balance = get_userbalance($userid, $first_currency);
                        if ($result->status == 'active') {
                            $refnd_amount = $result->updated_qty;
                            $refnd_total = $result->total;
                        } else if ($result->status == 'partially') {
                            $refnd_amount = $result->updated_qty;


                            $refnd_total = $total + $trade_fee;
                        }
                        if ($result->type == 'Buy') {
                            if ($result->status == 'active') {
                                $result->status = 'cancelled';
                                if ($result->save()) {
                                    $finalbalance = $second_cur_balance + $refnd_total;
//                        $upt = Balance::where('user_id', $userid)->first();
//                        $upt->$second_currency = $finalbalance;
                                    $upt = UserBalancesNew::where('user_id', $userid)->where('currency_name', $second_currency)->first();
                                    $upt->balance = $finalbalance;
                                    $upt->save();
                                }
                            } else {
                                $new = new Trade;
                                $tx_id = 'BTX' . time();
                                $new->unique_id = $tx_id;
                                $new->trade_id = $tx_id;
                                $new->trade_type = $result->trade_type;
                                $new->user_id = $result->user_id;
                                $new->pair_id = $result->pair_id;
                                $new->pair = $result->pair;
                                $new->firstCurrency = $result->firstCurrency;
                                $new->secondCurrency = $result->secondCurrency;
                                $new->price = $result->price;
                                $new->total = $result->total;
                                $new->type = 'Buy';
                                $new->process = '1';
                                $new->fee = $result->fee;
                                $new->original_qty = $result->updated_qty;
                                $new->status = 'cancelled';
                                $new->save();
                                $result->original_qty = ($result->original_qty - $result->updated_qty);
                                $result->status = 'completed';
                                if ($result->save()) {
                                    $finalbalance = $second_cur_balance + $refnd_total;
//                        $upt = Balance::where('user_id', $userid)->first();
//                        $upt->$second_currency = $finalbalance;
                                    $upt = UserBalancesNew::where('user_id', $userid)->where('currency_name', $second_currency)->first();
                                    $upt->balance = $finalbalance;
                                    $upt->save();
                                }
                            }
                        } else {
                            if ($result->status == 'active') {
                                $result->status = 'cancelled';
                                if ($result->save()) {
                                    $finalbalance = $first_cur_balance + $refnd_amount;
//                        $upt = Balance::where('user_id', $userid)->first();
//                        $upt->$first_currency = $finalbalance;
                                    $upt = UserBalancesNew::where('user_id', $userid)->where('currency_name', $first_currency)->first();
                                    $upt->balance = $finalbalance;
                                    $upt->save();
                                }
                            } else {
                                $new = new Trade;
                                $tx_id = 'STX' . time();
                                $new->unique_id = $tx_id;
                                $new->trade_id = $tx_id;
                                $new->trade_type = $result->trade_type;
                                $new->user_id = $result->user_id;
                                $new->pair_id = $result->pair_id;
                                $new->pair = $result->pair;
                                $new->firstCurrency = $result->firstCurrency;
                                $new->secondCurrency = $result->secondCurrency;
                                $new->price = $result->price;
                                $new->total = $result->total;
                                $new->type = 'Sell';
                                $new->process = '1';
                                $new->fee = $result->fee;
                                $new->original_qty = $result->updated_qty;
                                $new->status = 'cancelled';
                                $new->save();
                                $result->original_qty = ($result->original_qty - $result->updated_qty);
                                $result->status = 'completed';
                                if ($result->save()) {
                                    $finalbalance = $first_cur_balance + $refnd_amount;
//                        $upt = Balance::where('user_id', $userid)->first();
//                        $upt->$first_currency = $finalbalance;
                                    $upt = UserBalancesNew::where('user_id', $userid)->where('currency_name', $first_currency)->first();
                                    $upt->balance = $finalbalance;
                                    $upt->save();
                                }
                            }
                        }
                    }
                }
                Session::flash('success', 'Selected orders cancelled successfully');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Session::flash('error', 'Server Error');
            return redirect()->back();
        }
    }


    //for cancelling partial or active trade by user;
    function delete_trans($id)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $tradeid = ($id);
                $result = Transaction::where(['transaction_id' => $tradeid])->first();
                if ($result) {
                    $refnd_amount = $result->amount;
                    $userid = $result->user_id;
                    $first_currency = $result->currency_name;
                    $cur_balance = get_userbalance($userid, $first_currency);

                    $upt = Balance::where('user_id', $userid)->first();

                    if ($cur_balance >= $refnd_amount) {
                        $upt->$first_currency = $cur_balance - $refnd_amount;
                        if ($upt->save()) {

                            $result->delete();
                            Session::flash('success', 'Transaction Deleted successfully Available' . $first_currency . ' Balance :' . $upt->$first_currency);
                        }

                    } else {
                        Session::flash('fail', 'Insufficient balance');
                    }

                } else {
                    Session::flash('fail', 'Transaction not found');
                }

                return redirect('check_admin/deposit_history');

            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //get eth current block details
    function get_ETH_block()
    {
        $result = getting_eth_block();

    }

    //userbalance tally
    function validate_XDC_bal(Request $request)
    {
        try {
            $currency_type = $request['currency'];
            $address = $request['address'];
            $user_id = $request['user_id'];
            $from_date = '01-01-15';
            $enjoyer = $request['enjoyer'];
            $current_date = date('d-m-y');


            $xdc_explorer_data = get_xdc_transactionDetails($address, $from_date, $current_date);
            $User_xdc_credit = 0;
            foreach ($xdc_explorer_data->data as $xdc_data) {
                if ($xdc_data->to == $address) {
                    $User_xdc_credit += $xdc_data->value;
                }

            }


            $Userxdc_alpha_credit = Transaction::query()->where('user_id', $user_id)->where('currency_name', $currency_type)->where('type', 'Deposit')->sum('amount');

            $Buy_xdc_trade = Trade::query()->where('user_id', $user_id)->where('type', 'Buy')->where('status', 'completed')->sum('Amount');

            $Sell_Xdc_trade = Trade::query()->where('user_id', $user_id)->where('type', 'Sell')->where('status', 'completed')->sum('Amount');

            $Pending_buy_xdc = Trade::query()->where('user_id', $user_id)->where('type', 'Buy')->whereIn('status', ['partially', 'active'])->sum('Amount');
            $pending_sell_xdc = Trade::query()->where('user_id', $user_id)->where('type', 'Sell')->whereIn('status', ['partially', 'active'])->sum('Amount');


            $Userxdc_alpha_withdraw = Transaction::query()->where('user_id', $user_id)->where('currency_name', $currency_type)->where('type', 'Withdraw')->sum('amount');

            return view('panel.validate_balance', ['Balance' => $request['bal'], 'enjoyer' => $enjoyer, 'Deposit' => $User_xdc_credit,
                'ADeposit' => $Userxdc_alpha_credit, 'Buy' => $Buy_xdc_trade, 'TBuy' => $Pending_buy_xdc,
                'Sell' => $Sell_Xdc_trade, 'TSell' => $pending_sell_xdc, 'Withdraw' => $Userxdc_alpha_withdraw]);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //get user balance validation
    function users_balance_validation(Request $request)
    {
        try {
            $paginate = 0;
            if ($request['user_id']) {
                $paginate = 1;
                $lObjUsers[] = DB::table('enjoyer')
                    ->where('enjoyer.id', '=', $request['user_id'])
                    ->join('userbalance', 'userbalance.user_id', '=', 'enjoyer.id')
                    ->orderBy('userbalance.' . $request['currency'], 'desc')
                    ->select('enjoyer.*', 'userbalance.' . $request['currency'])
                    ->first();
            } else {
                $lObjUsers = DB::table('enjoyer')
                    ->join('userbalance', 'userbalance.user_id', '=', 'enjoyer.id')
                    ->orderBy('userbalance.' . $request['currency'], 'desc')
                    ->select('enjoyer.*', 'userbalance.' . $request['currency'])
                    ->paginate(50);
            }

            $currency = $request['currency'];
            $UserList[] = "";
            $fromDate = '01-01-15';
            $currentDate = date('d-m-y');
            $i = 0;
            foreach ($lObjUsers as $lObjUser) {

                $lUser_XDC = $lObjUser->XDC_addr;
                $User_explorer_credit = 0;

                $Explorer_Balance_data = '';
                $User_Alpha_credit = Transaction::query()->where('user_id', $lObjUser->id)->where('currency_name', $currency)->where('type', 'Deposit')->sum('amount');

                $Buy_trade = Trade::query()->where('user_id', $lObjUser->id)->where('type', 'Buy')->where('status', 'completed')->sum('Amount');

                $Sell_trade = Trade::query()->where('user_id', $lObjUser->id)->where('type', 'Sell')->where('status', 'completed')->sum('Amount');

                $Pending_buy = Trade::query()->where('user_id', $lObjUser->id)->where('type', 'Buy')->whereIn('status', ['partially', 'active'])->sum('Amount');
                $pending_sell = Trade::query()->where('user_id', $lObjUser->id)->where('type', 'Sell')->whereIn('status', ['partially', 'active'])->sum('Amount');

                $User_alpha_withdraw = Transaction::query()->where('user_id', $lObjUser->id)->where('currency_name', $currency)->where('type', 'Withdraw')->sum('amount');

                $VerifiedBalance = ($User_explorer_credit + $Buy_trade + $Pending_buy) - ($User_alpha_withdraw + $pending_sell + $Sell_trade);

                if ($VerifiedBalance == $lObjUser->XDC) {
                    $verified = 1;
                } else {
                    $verified = 0;
//                    $UserList[$i] = array("User_id"=>$lObjUser->id,"Currency"=>$currency,"Verified"=>$verified,"Name"=>$lObjUser->enjoyer_name,"Deposit"=>$User_explorer_credit,"ADeposit"=>$User_Alpha_credit,"Buy"=>$Buy_trade,"TBuy"=>$Pending_buy,
//                        "Sell"=>$Sell_trade,"TSell"=>$pending_sell,"Withdraw"=>$User_alpha_withdraw,"Actual_Balance"=>$VerifiedBalance,"Displayed_Balance"=>$lObjUser->XDC);
                }
                $UserList[$i] = array("User_id" => $lObjUser->id, "Currency" => $currency, "Verified" => $verified, "Name" => $lObjUser->enjoyer_name, "Deposit" => $User_explorer_credit, "ADeposit" => $User_Alpha_credit, "Buy" => $Buy_trade, "TBuy" => $Pending_buy,
                    "Sell" => $Sell_trade, "TSell" => $pending_sell, "Withdraw" => $User_alpha_withdraw, "Actual_Balance" => $VerifiedBalance, "Displayed_Balance" => $lObjUser->XDC);
                $i++;

            }

            return view('panel.users_balance_validation', ["UserList" => $UserList, "result" => $lObjUsers, "paginate" => $paginate]);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //get user balance validation
    function users_explorer_validation(Request $request)
    {
        try {
            $paginate = 0;
            if ($request['user_id']) {
                $paginate = 1;
                $lObjUsers[] = DB::table('enjoyer')
                    ->where('enjoyer.id', '=', $request['user_id'])
                    ->join('userbalance', 'userbalance.user_id', '=', 'enjoyer.id')
                    ->orderBy('userbalance.' . $request['currency'], 'desc')
                    ->select('enjoyer.*', 'userbalance.' . $request['currency'])
                    ->first();
            } else {
                $lObjUsers = DB::table('enjoyer')
                    ->join('userbalance', 'userbalance.user_id', '=', 'enjoyer.id')
                    ->orderBy('userbalance.' . $request['currency'], 'desc')
                    ->select('enjoyer.*', 'userbalance.' . $request['currency'])
                    ->paginate(100);
            }

            $currency = $request['currency'];
            $UserList[] = "";
            $fromDate = '01-01-15';
            $currentDate = date('d-m-y');
            $i = 0;
            foreach ($lObjUsers as $lObjUser) {

                $lUser_XDC = $lObjUser->XDC_addr;
                $User_explorer_credit = 0;

                $Explorer_Balance_data = '';
                $User_Alpha_credit = Transaction::query()->where('user_id', $lObjUser->id)->where('currency_name', $currency)->where('type', 'Deposit')->sum('amount');

                if ($User_explorer_credit == $User_Alpha_credit) {

                } else {
                    $UserList[$i] = array("User_id" => $lObjUser->id, "Currency" => $currency, "Name" => $lObjUser->enjoyer_name, "Deposit" => $User_explorer_credit, "ADeposit" => $User_Alpha_credit, "Displayed_Balance" => $lObjUser->XDC);
                }

                $i++;
            }

            return view('panel.explorer_xdc', ["UserList" => $UserList, "result" => $lObjUsers, "paginate" => $paginate]);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //for creating eth address
    function generate_eth($id)
    {
        try {
            $eth = get_user_details($id, 'ETH_addr');
            if ($eth == "") {
                $val = create_eth_address($id);
                $ins = Users::where('id', $id)->first();
                $ins->ETH_addr = $val;
                $ins->save();
                $result = array('status' => 'Success', 'message' => 'successful', 'address' => $val);
            } else {
                $result = array('status' => 'Failed', 'message' => 'Already exist');
            }
            return json_encode($result);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //for validating amount
    function xrp_withdraw($id)
    {
        try {
            $User_alpha_withdraw = Transaction::query()->where('status', 'Completed')->where('currency_name', 'XRP')->where('type', 'Withdraw')->sum('amount');

            $User_alpha_withdraw_particular_date = Transaction::query()->where('created_at', '>=', new DateTime('-' . $id . ' days'))->where('status', 'Completed')->where('currency_name', 'XRP')->where('type', 'Withdraw')->sum('amount');
            return json_encode(array('Amount' => $User_alpha_withdraw, 'AmountDate' => $User_alpha_withdraw_particular_date));
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function adminxrpaddress()
    {
        try {
            $address = create_ripple_address();

            $data = array('add' => $address->address, 'secret' => $address->secret);
            return $data;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //email verification
    function create_email_verification($id)
    {
        try {
            $get_user = Users::where('id', $id)->first();
            $activation_code = mt_rand(0000, 9999) . time();
            $get_user->activation_code = $activation_code;
            if ($get_user->update()) {
                $to = get_usermail($id);
                $subject = get_template('4', 'subject');
                $message = get_template('4', 'template');
                $mailarr = array(
                    '###USERNAME###' => $get_user->enjoyer_name,
                    '###LINK###' => url('userverification/' . $activation_code),
                    '###SITENAME###' => get_config('site_name'),
                );
                $message = strtr($message, $mailarr);
                $subject = strtr($subject, $mailarr);
                if (sendmail($to, $subject, ['content' => $message])) {
                    Session::flash('success', 'email verification have been sent.');
                    return redirect()->back();
                };
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //web
    function create_riple_xrp_tag()
    {
        try {
            $get_users = Users::all();

            foreach ($get_users as $user) {
                $xrp_tag = $user->BCH_addr;
                if ($xrp_tag == '') {
                    $address = create_bch_address($user->id);
                    $ins = Users::where('id', $user->id)->first();
                    $ins->BCH_addr = $address;
                    $ins->save();
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //user_transaction details
    function user_transaction_details(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $user_id = $request['user_id'];
                $currency = $request['currency'];
                $withdrawal_amount = $request['amount'];
                $type = $request['type'];

                //user record
                $User = Users::where('id', $user_id)->first();

                $btc_add = get_user_address($user_id, 'BTC');
                $eth_add = get_user_address($user_id, 'ETH');
                $xrp_add = get_user_address($user_id, 'XRP');
                $usdt_add = get_user_address($user_id, 'USDT');

                //for btc explorer deposit
//                if ($btc_add) {
//                    $BTC_explorer = get_btcDeposit_user($btc_add);
//                } else {
                $BTC_explorer = 0;
//                }

//        for bch explorer deposit
//        $BCH_explorer = get_bchDeposit_user($bch_add);

                //for eth explorer deposit
//                $ETH_explorer = get_ethDeposit_user($eth_add);
                $ETH_explorer = 0;

                //for xrp explorer deposit
//                $XRP_explorer = get_xrpDeposit_user($xrp_add);
                $XRP_explorer = 0;

                //for usdt  explorer deposit
//            $USDT_explorer = get_usdtDeposit_user($xdc_add);
                $USDT_explorer = 0;

                //Explorer_deposit array
                $explorer = array('BTC' => $BTC_explorer, 'ETH' => $ETH_explorer, 'XRP' => $XRP_explorer, 'USDT' => $USDT_explorer);

                $addresses = array('BTC' => $btc_add, 'ETH' => $eth_add, 'XRP' => $xrp_add, 'USDT' => $usdt_add,);

                //for usdt withdrawal
                $usdt_withdrawal = Transaction::where('user_id', $user_id)->where('currency_name', 'USDT')->
                where('type', 'Withdraw')->where('status', 'Completed')->sum('amount');

                //for btc withdrawal
                $btc_withdrawal = Transaction::where('user_id', $user_id)->where('currency_name', 'BTC')->
                where('type', 'Withdraw')->where('status', 'Completed')->sum('amount');

                //for eth withdrawal
                $eth_withdrawal = Transaction::where('user_id', $user_id)->where('currency_name', 'ETH')->
                where('type', 'Withdraw')->where('status', 'Completed')->sum('amount');

                //for xrp withdrawal
                $xrp_withdrawal = Transaction::where('user_id', $user_id)->where('currency_name', 'XRP')->
                where('type', 'Withdraw')->where('status', 'Completed')->sum('amount');

                //for withdrawal
                $withdraw = array('BTC' => $btc_withdrawal, 'ETH' => $eth_withdrawal, 'XRP' => $xrp_withdrawal, 'USDT' => $usdt_withdrawal);

//                //for ico Transaction
//                $ico_transactions = ICOTrade::where('user_id', $user_id)->where('Status', 'Completed')->get();
//
//                //ico stats
//                $eth_spent_ico = ICOTrade::where('user_id', $user_id)
//                    ->where('Status', 'Completed')->where('SecondCurrency', 'ETH')->sum('Amount');
//
//                $xrp_spent_ico = ICOTrade::where('user_id', $user_id)
//                    ->where('Status', 'Completed')->where('SecondCurrency', 'XRP')->sum('Amount');
//
//                $btc_spent_ico = ICOTrade::where('user_id', $user_id)
//                    ->where('Status', 'Completed')->where('SecondCurrency', 'BTC')->sum('Amount');
//
//                $bch_spent_ico = ICOTrade::where('user_id', $user_id)
//                    ->where('Status', 'Completed')->where('SecondCurrency', 'BCH')->sum('Amount');
//
//                $buy_xdce_ico = ICOTrade::where('user_id', $user_id)
//                    ->where('Status', 'Completed')->sum('Total');
//
//                $ico = array('ETH' => $eth_spent_ico, 'BTC' => $btc_spent_ico, 'BCH' => $bch_spent_ico, 'XRP' => $xrp_spent_ico, 'XDCE' => $buy_xdce_ico);

                //user withdrawal transaction
                $user_withdrawal = Transaction::where('user_id', $user_id)->
                where('type', 'Withdraw')->where('status', 'Completed')->get();

                //user deposit transaction
                $user_deposit = Transaction::where('user_id', $user_id)->
                where('type', 'Deposit')->where('status', 'Completed')->get();

                $buy_trade = Trade::where('user_id', $user_id)->where('type', 'Buy')->where('status', 'completed')
                    ->orderBy('id', 'desc')->get();

                $sell_trade = Trade::where('user_id', $user_id)->where('type', 'Sell')->where('status', 'completed')
                    ->orderBy('id', 'desc')->get();

                $pending_trade = Trade::where('user_id', $user_id)->whereIn('status', ['active', 'partially'])
                    ->orderBy('id', 'desc')->get();

                //usdt
                $total_usdt_buy = get_user_buy($user_id, 'USDT');
                $total_usdt_sell = get_user_sell($user_id, 'USDT');
                $total_intrade_usdt = get_user_intradebalance($user_id, 'USDT');

                //btc
                $total_btc_buy = get_user_buy($user_id, 'BTC');
                $total_btc_sell = get_user_sell($user_id, 'BTC');
                $total_intrade_btc = get_user_intradebalance($user_id, 'BTC');

                //eth
                $total_eth_buy = get_user_buy($user_id, 'ETH');
                $total_eth_sell = get_user_sell($user_id, 'ETH');
                $total_intrade_eth = get_user_intradebalance($user_id, 'ETH');

                //xrp
                $total_xrp_buy = get_user_buy($user_id, 'XRP');
                $total_xrp_sell = get_user_sell($user_id, 'XRP');
                $total_intrade_xrp = get_user_intradebalance($user_id, 'XRP');

                //buy trade sum

                $buy_total = array('USDT' => $total_usdt_buy, 'ETH' => $total_eth_buy, 'XRP' => $total_xrp_buy, "BTC" => $total_btc_buy);

                $sell_total = array('USDT' => $total_usdt_buy, 'ETH' => $total_eth_sell, 'XRP' => $total_xrp_sell, 'BTC' => $total_btc_sell);

                $intrade_total = array('USDT' => $total_intrade_usdt, 'ETH' => $total_intrade_eth, 'XRP' => $total_intrade_xrp, 'BTC' => $total_intrade_btc);

                if ($type == 'PDF') {
                    $buy_trade = Trade::where('user_id', $user_id)->where('type', 'Buy')->where('status', 'completed')
                        ->orderBy('id', 'desc')->get();

                    $sell_trade = Trade::where('user_id', $user_id)->where('type', 'Sell')->where('status', 'completed')
                        ->orderBy('id', 'desc')->get();

                    $pending_trade = Trade::where('user_id', $user_id)->whereIn('status', ['active', 'partially'])
                        ->orderBy('id', 'desc')->get();

                    return view('panel.pdfview', ['Buy_total' => $buy_total, 'Sell_total' => $sell_total, 'id' => $user_id, 'Deposit' => $user_deposit, 'Withdrawal' => $user_withdrawal, 'currency' => $currency,
                        'explorer' => $explorer, 'withdraw' => $withdraw, 'buy_trade' => $buy_trade, 'sell_trade' => $sell_trade, 'pending_trade' => $pending_trade, 'Intrade_total' => $intrade_total, 'user' => $User, 'addresses' => $addresses]);
                } else {
                    //depend on currency
                    return view('panel.user_transaction_details', ['Buy_total' => $buy_total, 'Sell_total' => $sell_total, 'id' => $user_id, 'Deposit' => $user_deposit, 'Withdrawal' => $user_withdrawal, 'currency' => $currency,
                        'explorer' => $explorer, 'withdraw' => $withdraw, 'buy_trade' => $buy_trade, 'sell_trade' => $sell_trade, 'pending_trade' => $pending_trade, 'Intrade_total' => $intrade_total, 'user' => $User, 'addresses' => $addresses]);
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //opening balance of user
    public function users_opening_balance(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request['currency']) {
                    $result = DB::table('useropeningbalance')
                        ->join('enjoyer', 'useropeningbalance.user_id', '=', 'enjoyer.id')
                        ->orderBy('useropeningbalance.' . $request['currency'], 'desc')
                        ->select('useropeningbalance.*', 'enjoyer.id', 'enjoyer.enjoyer_name')
                        ->paginate(25);
                } elseif ($request->isMethod('get')) {

                    $search = $request['search'];
                    $email = $request['email'];
                    $user_search_id = $request['user_search_id'];
                    $q = OpeningBalance::query();
                    $q->join('enjoyer', 'useropeningbalance.user_id', '=', 'enjoyer.id')->select('useropeningbalance.*', 'enjoyer.enjoyer_name');

                    if ($search) {
                        $q->where(function ($qq) use ($search) {
                            $qq->where('enjoyer_name', 'like', '%' . $search . '%');
                        });
                    }
                    if ($email) {
                        $spl = explode("@", $email);
                        $user1 = $spl[0];
                        $user2 = $spl[1];
                        $record = getByEmail($user1, $user2);

                        foreach ($record as $val) {
                            $user_id = $val->id;
                            $q->where('useropeningbalance.user_id', $user_id);
                        }
                    }
                    if ($user_search_id) {
                        $q->where('useropeningbalance.user_id', $user_search_id);
                    }
                    $result = $q->orderBy('useropeningbalance.id', 'desc')->paginate(25);
                } else {
                    $result = DB::table('useropeningbalance')
                        ->join('enjoyer', 'useropeningbalance.user_id', '=', 'enjoyer.id')
                        ->orderBy('useropeningbalance.user_id', 'asc')
                        ->select('useropeningbalance.*', 'enjoyer.enjoyer_name')
                        ->paginate(25);
                }

                return view('panel.user_balance', ['result' => $result, 'Header' => 'Users Opening Balance']);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //closing balance of user
    public function users_closing_balance(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request['currency']) {
                    $result = DB::table('userclosingbalance')
                        ->join('enjoyer', 'userclosingbalance.user_id', '=', 'enjoyer.id')
                        ->orderBy('userclosingbalance.' . $request['currency'], 'desc')
                        ->select('userclosingbalance.*', 'enjoyer.id', 'enjoyer.enjoyer_name')
                        ->paginate(25);
                } elseif ($request->isMethod('get')) {

                    $search = $request['search'];
                    $email = $request['email'];
                    $user_search_id = $request['user_search_id'];
                    $q = ClosingBalance::query();
                    $q->join('enjoyer', 'userclosingbalance.user_id', '=', 'enjoyer.id')->select('userclosingbalance.*', 'enjoyer.enjoyer_name');

                    if ($search) {
                        $q->where(function ($qq) use ($search) {
                            $qq->where('enjoyer_name', 'like', '%' . $search . '%');
                        });
                    }
                    if ($email) {
                        $spl = explode("@", $email);
                        $user1 = $spl[0];
                        $user2 = $spl[1];
                        $record = getByEmail($user1, $user2);

                        foreach ($record as $val) {
                            $user_id = $val->id;
                            $q->where('userclosingbalance.user_id', $user_id);
                        }
                    }
                    if ($user_search_id) {
                        $q->where('userclosingbalance.user_id', $user_search_id);
                    }
                    $result = $q->orderBy('userclosingbalance.id', 'desc')->paginate(25);
                } else {
                    $result = DB::table('userclosingbalance')
                        ->join('enjoyer', 'userclosingbalance.user_id', '=', 'enjoyer.id')
                        ->orderBy('userclosingbalance.user_id', 'asc')
                        ->select('userclosingbalance.*', 'enjoyer.enjoyer_name')
                        ->paginate(25);
                }

                return view('panel.user_balance', ['result' => $result, 'Header' => 'Users Closing Balance']);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function export_user_list()
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
//                $user_details = Users::select('id','enjoyer_name','document_status','status')->get();
//                $user_array = [];
//                foreach ($user_details as $user)
//                {
//                    $id = $user->id;
//                    $name = $user->enjoyer_name;
//                    $email = get_usermail($user->id);
//                    if($user->document_status == 1)
//                    {
//                        $kyc = 'Completed';
//                    }
//                    else if($user->document_status == 2)
//                    {
//                        $kyc = 'Rejected';
//                    }
//                    else if($user->document_status == 3)
//                    {
//                        $kyc = 'Submitted';
//                    }
//                    else
//                    {
//                        $kyc = 'Pending';
//                    }
//                    if($user->status == 1)
//                    {
//                        $status = 'Active';
//                    }
//                    else
//                    {
//                        $status = 'Deactive';
//                    }
//                    $array = array('Id' => $id, 'Name' => $name, 'Email' => $email, 'Status' => $status, 'Kyc' => $kyc);
//                    $user_array[] = $array;
//                }
//                Excel::create('User_List', function($excel) use($user_array) {
//                    $excel->sheet('All', function($sheet) use($user_array) {
//
//                        $sheet->fromArray($user_array, null,"A1",true);
//                        $sheet->setOrientation('landscape');
//                        $sheet->setScale(10);
//                        $sheet->setAllBorders('thin');
//                    }
//                    );
//                })->export('csv');

                $items = Users::all();
                $item_requested = Users::where('document_status', 3)->get();
                $item_pending = Users::where('document_status', 0)->get();
                $item_rejected = Users::where('document_status', 2)->get();
                $item_approved = Users::where('document_status', 1)->get();
                $datas = array();
                $datas1 = array();
                $datas2 = array();
                $datas3 = array();
                $datas4 = array();
                $i = 0;
                foreach ($items as $item) {
                    $document_status = $item->document_status;
                    if ($document_status == 3) {
                        $status = 'Requested';
                    } elseif ($document_status == 2) {
                        $status = 'Rejected';
                    } elseif ($document_status == 1) {
                        $status = 'Approved';
                    } elseif ($document_status == 0) {
                        $status = 'Pending';
                    }
                    $acc_status = $item->status;
                    if ($acc_status == 1) {
                        $account_status = 'Active';
                    } else {
                        $account_status = 'Deactive';
                    }
                    $data = array('User_id' => $item->id, 'Username' => $item->enjoyer_name,
                        'email' => get_usermail($item->id), 'KYC_status' => $status, 'Account_status' => $account_status);
                    $datas[$i] = ($data);
                    $i++;
                }
                $i = 0;
                foreach ($item_requested as $item) {
                    $document_status = $item->document_status;
                    if ($document_status == 3) {
                        $status = 'Requested';
                    } else {
                        $status = 'error';
                    }
                    $acc_status = $item->status;
                    if ($acc_status == 1) {
                        $account_status = 'Active';
                    } else {
                        $account_status = 'Deactive';
                    }
                    $data = array('User_id' => $item->id, 'Username' => $item->enjoyer_name,
                        'email' => get_usermail($item->id), 'KYC_status' => $status, 'Account_status' => $account_status);
                    $datas3[$i] = ($data);
                    $i++;
                }
                $i = 0;
                foreach ($item_rejected as $item) {
                    $document_status = $item->document_status;
                    if ($document_status == 2) {
                        $status = 'Rejected';
                    } else {
                        $status = 'error';
                    }
                    $acc_status = $item->status;
                    if ($acc_status == 1) {
                        $account_status = 'Active';
                    } else {
                        $account_status = 'Deactive';
                    }
                    $data = array('User_id' => $item->id, 'Username' => $item->enjoyer_name,
                        'email' => get_usermail($item->id), 'KYC_status' => $status, 'Account_status' => $account_status);
                    $datas4[$i] = ($data);
                    $i++;
                }
                $i = 0;
                foreach ($item_pending as $item) {
                    $document_status = $item->document_status;
                    if ($document_status == 0) {
                        $status = 'Pending';
                    } else {
                        $status = 'error';
                    }
                    $acc_status = $item->status;
                    if ($acc_status == 1) {
                        $account_status = 'Active';
                    } else {
                        $account_status = 'Deactive';
                    }
                    $data = array('User_id' => $item->id, 'Username' => $item->enjoyer_name,
                        'email' => get_usermail($item->id), 'KYC_status' => $status, 'Account_status' => $account_status);
                    $datas1[$i] = ($data);
                    $i++;
                }
                $i = 0;
                foreach ($item_approved as $item) {
                    $document_status = $item->document_status;
                    if ($document_status == 1) {
                        $status = 'Approved';
                    } else {
                        $status = 'error';
                    }
                    $acc_status = $item->status;
                    if ($acc_status == 1) {
                        $account_status = 'Active';
                    } else {
                        $account_status = 'Deactive';
                    }
                    $data = array('User_id' => $item->id, 'Username' => $item->enjoyer_name,
                        'email' => get_usermail($item->id), 'KYC_status' => $status, 'Account_status' => $account_status);
                    $datas2[$i] = ($data);
                    $i++;
                }
                Excel::create('User_List', function ($excel) use ($datas, $datas1, $datas2, $datas3, $datas4) {
                    $excel->sheet('All', function ($sheet) use ($datas) {
                        $sheet->fromArray($datas);
                    }
                    );
//second sheet
                    $excel->sheet('Pending', function ($sheet) use ($datas1) {
                        $sheet->fromArray($datas1);
                    }
                    );
// Our second sheet
                    $excel->sheet('Approved', function ($sheet) use ($datas2) {
                        $sheet->fromArray($datas2);
                    });
// Our second sheet
                    $excel->sheet('Requested', function ($sheet) use ($datas3) {
                        $sheet->fromArray($datas3);
                    });
                    $excel->sheet('Rejected', function ($sheet) use ($datas4) {
                        $sheet->fromArray($datas4);
                    });
                })->export('xls');

            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //ico price update
    function update_ico_price(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request->isMethod('post')) {
                    $btc_price = $request['btc'];
                    $bch_price = $request['bch'];
                    $eth_price = $request['eth'];
                    $xrp_price = $request['xrp'];

                    //update price
                    $ico_rates = ICORate::all();

                    foreach ($ico_rates as $ico_rate) {
                        if ($ico_rate->SecondCurrency == 'BTC') {
                            $ico_rate->Amount = $btc_price;
                            $ico_rate->update();

                        } elseif ($ico_rate->SecondCurrency == 'BCH') {
                            $ico_rate->Amount = $bch_price;
                            $ico_rate->update();

                        } elseif ($ico_rate->SecondCurrency == 'ETH') {
                            $ico_rate->Amount = $eth_price;
                            $ico_rate->update();

                        } elseif ($ico_rate->SecondCurrency == 'XRP') {
                            $ico_rate->Amount = $xrp_price;
                            $ico_rate->update();

                        }
                    }
                    Session::flash('success', 'Price updated successfully.');
                    return redirect('check_admin/ico_history');

                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

//    function set_trade_cancel()
//    {
//        try {
//            $active_trades = Trade::whereIn('status', ['active', 'partially'])->get();
//            $updated_record = array();
//            $i = 0;
//            $ip = \Request::ip();
//            foreach ($active_trades as $active_trade) {
//                $user_id = $active_trade->user_id;
//                $user = Users::where('id', $user_id)->first();
//                $user_balance = UserBalance::where('user_id', $user_id)->first();
//                $type = $active_trade->Type;
//                $second_currency = $active_trade->secondCurrency;
//
//                if ($type == 'Buy') {
//                    $refund_currency = $active_trade->secondCurrency;
//                    $refund_amount = $active_trade->Total;
//                    $user_balance->$second_currency = $user_balance->$second_currency + $refund_amount;
//
//                    $active_trade->status = 'cancelled';
//
//                } else {
//                    $refund_currency = 'XDC';
//                    $refund_amount = $active_trade->Amount;
//                    $user_balance->XDC = $user_balance->XDC + $refund_amount;
//                    $active_trade->status = 'cancelled';
//
//                }
//
//                if ($active_trade->save()) {
//                    $transid = 'TXD' . $user_id . time();
//                    if ($user_balance->save()) {
//                        $ins = new Transaction;
//                        $ins->user_id = $user_id;
//                        $ins->payment_method = 'Cryptocurrency Account';
//                        $ins->transaction_id = $transid;
//                        $ins->currency_name = 'XDC';
//                        $ins->type = 'Updated';
//                        $ins->transaction_type = '1';
//                        $ins->amount = $user_balance->XDC + $refund_amount;
//
//                        $ins->crypto_address = 'By Admin';
//                        $ins->transfer_amount = $refund_amount;
//                        $ins->fee = '0';
//                        $ins->tax = '0';
//                        $ins->verifycode = '1';
//                        $ins->order_id = '0';
//                        $ins->status = 'Completed';
//                        $ins->cointype = '2';
//                        $ins->payment_status = 'Cancelled_Trade';
//                        $ins->paid_amount = '0';
//                        $ins->wallet_txid = '';
//                        $ins->ip_address = $ip;
//                        $ins->verify = '1';
//                        $ins->blocknumber = '';
//                        $ins->save();
//
//                        $record[$i] = array('Sr.No' => $i, 'user_id' => $user->id, 'user_name' => $user->enjoyer_name,
//                            'trade_id' => $active_trade->id, 'type' => $type, 'Currency' => $refund_currency,
//                            'Amount' => $refund_amount);
//                    }
//                }
//
//                $i++;
//
//            }
//            return json_encode($record);
//        }
//        catch (\Exception $e) {
//            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
//        return view('errors.404');
//        }
//    }

    function create_bch_all()
    {
        try {
            $ico = ICOTrade::where('Status', 'Completed')->sum('Total');
            echo $ico;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function referral(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request->isMethod('post')) {
                    $referrer_bonus = $request['referrer_bonus'];
                    $referred_bonus = $request['referred_bonus'];
                    $currency = $request['currency'];
                    $referral = ReferralBonus::where('id', 1)->first();
                    $referral->referrer_bonus = $referrer_bonus;
                    $referral->referred_bonus = $referred_bonus;
                    $referral->currency = $currency;
                    $referral->save();
                    Session::flash('success', 'Referral stats updated successfully.');
                }
                $referral = ReferralBonus::where('id', 1)->first();
                $currencies = Currencies::all();
                return view('panel.referral', ['referral' => $referral, 'currencies' => $currencies]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function update_admin(Request $request, $id)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if ($request->isMethod('post')) {
                }
                $data = Admin::where('id', $id)->first();
                return view('panel.update_admin', ['data' => $data]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function deleteaccount(Request $request)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                if (Session::get('alpha_id') == "1") {
                    $user_id = base64_decode($request['user_id']);
                    $user = Users::where('id', $user_id)->first();
                    if (isset($user)) {
                        $data = $user->attributesToArray();
//                        $data = array_except($data, ['id']);
                        $deleted_user = DeletedUsers::insert($data);
                        if ($deleted_user) {
                            $user->delete();
                            $return['status'] = '1';
                            $email = Session::get('alowner');
                            owner_activity($email, 'Delete Account id : ' . $user->id . ' name : ' . $user->enjoyer_name);
                            Session::flash('success', 'Account deleted for user ' . $user->enjoyer_name . ' id : ' . $user->id);
                            return json_encode($return);
                        } else {
                            $return['status'] = '0';
                            return json_encode($return);
                        }
                    } else {
                        $return['status'] = '0';
                        return json_encode($return);
                    }
                } else {
                    Session::flash('error', 'Sorry you are not authorised to complete this action.');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

}
