<?php

namespace App\Http\Controllers;

use App\model\Adminwallet;
use App\model\OTP;
use App\model\Profit;
use App\model\Wallettrans;
use Cache;
use DB;
use Hash;
use Illuminate\Http\Request;
use Mockery\Exception;
use Session;

class AdminwalletController extends Controller
{
    //

    public function __construct()
    {
        //$this->middleware('Adminlogin');
        $ip = \Request::ip();
        blockip_list($ip);
    }

    function index(Request $request)
    {
        try {
            if ($request->isMethod('post')) {

                $validator = $this->validate($request, [
                    'wallet_username' => 'required',
                    'wallet_password' => 'required|min:6',
                    'wallet_pattern' => 'required',
                ], [
                    'wallet_username.required' => 'Wallet username is required',
                    'wallet_password.required' => 'Password is required',
                    'wallet_password.min' => 'Password 6 characters is required',
                ]);

                $email = $request['wallet_username'];
                $password = $request['wallet_password'];
                $auth = $this->adminauth($email, $password);
                switch ($auth) {
                    case '1':
                        return redirect('walletjey/home');
                        break;
                    case '2':
                        Session::flash('error', 'Password is invalid');
                        return redirect('walletjey');
                        break;
                    case '0':
                        Session::flash('error', 'Account is deactive');
                        return redirect('walletjey');
                        break;

                    default:
                        return redirect('walletjey');
                        break;
                }
            }
            return view('wallet.login');
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function adminauth($email, $password)
    {
        try {
            $arr = array('CMB_username' => $email, 'status' => 'active');
            $check = Adminwallet::where($arr)->first();
            if ($check) {
                if (Hash::check($password, $check->CMB_password)) {
                    $sess = array('wallet_adid' => $check->id, 'role' => $check->role, 'walowner' => $email);
                    Session::put($sess);
                    owner_activity($email, 'Wallet Login');
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

    function checkpattern(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $walletpat = Adminwallet::where('id', '1')->first();
                $key1 = $request['key1'];
                $key2 = $request['key2'];
                $pattern = $key2 - $key1;
                $original = decrypt($walletpat->pattern);
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

    function home()
    {
        try {
            if (Session::get('wallet_adid') == "") {
                return redirect('walletjey');
            } else {
                $admincmbaddr = decrypt(get_config('cmb_address'));
                $adminxdceaddr = decrypt(get_config('xdce_address'));
                $cmb_bal = 0;
                $xdce_bal = get_livexdce_bal($adminxdceaddr);
                $adminethaddr = decrypt(get_config('eth_address'));
                $eth_bal = getting_eth_balance($adminethaddr);
                $adminbtcaddr = decrypt(get_config('btc_address'));
                $btc_bal = get_btc_wallet_info($adminbtcaddr);
                $btc_bal1 = $btc_bal['balance'];

                $adminbchaddr = decrypt(get_config('bch_address'));
                $bch_bal = get_bch_wallet_info($adminbchaddr);
                $bch_bal1 = $bch_bal['balance'];
//            $btc_bal1 = "qqqqq";
                $adminxrpaddr = decrypt(get_config('xrp_address'));
                $xrp_res = get_xrp_balance($adminxrpaddr);
                $res = $xrp_res->result;
                $xrp_bal1 = 0;
                if ($res == 'success') {
                    $xrp_bal = $xrp_res->balances;
                    $xrp_bal1 = $xrp_bal[0]->value;
                }
                Session::put('xrpbalad', $xrp_bal1);

                return view('wallet.home', ['cmb_bal' => $cmb_bal, 'xdce_bal' => $xdce_bal, 'eth_bal' => $eth_bal, 'btc_bal' => $btc_bal1, 'bch_bal' => $bch_bal1, 'xrp_bal' => $xrp_bal1]);
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
            if (Session::get('wallet_adid') == "") {
                return redirect('walletjey');
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function walletdeposit($currency = "")
    {
        try {
            if (Session::get('wallet_adid') == "") {
                return redirect('walletjey');
            } else {
                $currency = $currency ? $currency : 'CMB';
                $smcurl = strtolower($currency);
                $addr = decrypt(get_config($smcurl . '_address'));
                return view('wallet.deposit', ['currency' => $currency, 'addr' => $addr]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function walletwithdraw(Request $request, $currency = "")
    {
        try {
            if (Session::get('wallet_adid') == "") {
                return redirect('walletjey');
            } else {
                $currency = $currency ? $currency : 'CMB';
                $smcurl = strtolower($currency);
                $addr = decrypt(get_config($smcurl . '_address'));

                if ($request->isMethod('post')) {
                    $this->validate($request, [
                        'to_addr' => 'required',
                        'to_amount' => 'required',
                        'otp_num' => 'required|numeric',
                    ]);

                    $to_addr = trim($request['to_addr']);
                    $to_amount = $request['to_amount'];
                    $otp_num = $request['otp_num'];
                    $hash = time();
                    if ($currency == 'XRP') {
                        $xrp_desttag = $request['xrp_desttag'];
                    } else {
                        $xrp_desttag = "";
                    }
                    $ip = \Request::ip();
                    if ($this->verify_admin_otp($otp_num) === TRUE) {
                        if ($currency == 'CMB') {
//                            $admindet = DB::table('wallet')->where('type', 'CMB')->first();
//                            $xinusername = owndecrypt($admindet->CMB_username);
//                            $xinpass = owndecrypt($admindet->CMB_password);
//                            login_xdc_fun($xinusername, $xinpass);
//                            $adminxdcaddr = decrypt(get_config('xdc_address'));
//                            $xdc_bal = get_livexdc_bal($adminxdcaddr);
//                            if ($xdc_bal < $to_amount) {
//                                Session::flash('error', 'Insufficient Balance in admin wallet');
//                                return redirect()->back();
//                            }
//
//                            $res = transfer_xdctokenadmin($adminxdcaddr, $to_amount, $to_addr, $xinusername, $xinpass);
//                            $transid = 'WALCMB-' . time();
//                            $hash = "";

                        } elseif ($currency == 'XDCE') {
                            $admindet = DB::table('wallet')->where('type', 'XDCE')->first();
                            $xinusername = owndecrypt($admindet->CMB_username);
                            $xinpass = owndecrypt($admindet->CMB_password);
                            login_xdce_fun($xinusername, $xinpass);
                            $adminxdceaddr = decrypt(get_config('xdce_address'));
                            $xdce_bal = get_livexdce_bal($adminxdceaddr);
                            if ($xdce_bal < $to_amount) {
                                Session::flash('error', 'Insufficient Balance in admin wallet');
                                return redirect()->back();
                            }

                            $res = transfer_xdcetokenadmin($adminxdceaddr, $to_amount, $to_addr, $xinusername, $xinpass);
                            $transid = 'WALXDCE-' . time();
                            $hash = "";

                        } elseif ($currency == 'BTC') {
                            $transid = 'WALBTC-' . time();
                            $adminbtcaddr = decrypt(get_config('btc_address'));
                            $btc_bal = get_btc_wallet_info($adminbtcaddr);
                            $btc_bal1 = $btc_bal['balance'];
                            if ($btc_bal1 < $to_amount) {
                                Session::flash('error', 'Insufficient Balance in admin wallet');
                                return redirect()->back();
                            }
                            $hash = btc_transfer_fun($to_addr, $to_amount);
                        } elseif ($currency == 'BCH') {
                            $transid = 'WALBCH-' . time();
                            $adminbchaddr = decrypt(get_config('bch_address'));
                            $bch_bal = get_bch_wallet_info($adminbchaddr);
                            $bch_bal1 = $bch_bal['balance'];
                            if ($bch_bal1 < $to_amount) {
                                Session::flash('error', 'Insufficient Balance in admin wallet');
                                return redirect()->back();
                            }
                            $hash = bch_transfer_fun($to_addr, $to_amount);
                        } elseif ($currency == 'ETH') {
                            $transid = 'WALETH-' . time();
                            $adminethaddr = decrypt(get_config('eth_address'));
                            $eth_bal = getting_eth_balance($adminethaddr);
                            if ($eth_bal < $to_amount) {
                                Session::flash('error', 'Insufficient Balance in admin wallet');
                                return redirect()->back();
                            }
                            $hash = eth_transfer_fun_admin($adminethaddr, $to_amount, $to_addr);
                        } elseif ($currency == 'XRP') {
                            $getxrpbal = Session::get('xrpbalad');
                            if ($getxrpbal < $to_amount) {
                                Session::flash('error', 'Insufficient Balance in admin wallet');
                                return redirect()->back();
                            }
                            $transid = 'WALXRP-' . time();
                            $adminxrpaddr = decrypt(get_config('xrp_address'));
                            $adminxrpsecret = decrypt(get_config('xrp_secret'));
                            $hash = transfer_ripple_xrp($adminxrpaddr, $adminxrpsecret, $to_addr, $to_amount, $xrp_desttag);
                        }
                        $ins = new Wallettrans;
                        $ins->adtras_id = $transid;
                        $ins->currency = $currency;
                        $ins->address = $to_addr;
                        $ins->hash = $hash;
                        $ins->amount = $to_amount;
                        $ins->ip = $ip;
                        $ins->save();
                        Session::flash('success', $currency . ' Successfully transfered');
                        return redirect()->back();
                    } else {
                        Session::flash('error', 'OTP code is wrong');
                        return redirect()->back();
                    }
//                if ($currency == 'XDC') {
//                    $admindet = DB::table('wallet')->where('type', 'XDC')->first();
//                    $xinusername = owndecrypt($admindet->CMB_username);
//                    $xinpass = owndecrypt($admindet->CMB_password);
//                    login_xdc_fun($xinusername, $xinpass);
//                    $adminxdcaddr = decrypt(get_config('xdc_address'));
//                    $xdc_bal = get_livexdc_bal($adminxdcaddr);
//                    if ($xdc_bal < $to_amount) {
//                        Session::flash('error', 'Insufficient Balance in admin wallet');
//                        return redirect()->back();
//                    }
//
//                    $res = transfer_xdctokenadmin($adminxdcaddr, $to_amount, $to_addr, $xinusername, $xinpass);
//                    $transid = 'WALCMB-' . time();
//                    $hash = "";
//
//                }
//                elseif ($currency == 'XDCE') {
//                    $admindet = DB::table('wallet')->where('type', 'XDCE')->first();
//                    $xinusername = owndecrypt($admindet->CMB_username);
//                    $xinpass = owndecrypt($admindet->CMB_password);
//                    login_xdc_fun($xinusername, $xinpass);
//                    $adminxdceaddr = decrypt(get_config('xdce_address'));
//                    $xdce_bal = get_livexdce_bal($adminxdceaddr);
//                    if ($xdce_bal < $to_amount) {
//                        Session::flash('error', 'Insufficient Balance in admin wallet');
//                        return redirect()->back();
//                    }
//
//                    $res = transfer_xdcetokenadmin($adminxdceaddr, $to_amount, $to_addr, $xinusername, $xinpass);
//                    try{
//
//                        if($res->TransactionID != '')
//                        {
//                            $transid = 'WALXDCE-' . time();
//                        }
//                        else
//                            {
//                                $transid = '';
//                            }
//                        $transid = 'WALXDCE-' . time();
//                    }catch(\Exception $e)
//                    {
//                        $transid = '';
//                        Session::flash('error', 'Transaction could not be processed');
//                        return redirect()->back();
//                    }
//
//                    $hash = "";
//
//                }
//                elseif ($currency == 'BTC') {
//                    $transid = 'WALBTC-' . time();
//                    $adminbtcaddr = decrypt(get_config('btc_address'));
//                    $btc_bal = get_btc_wallet_info($adminbtcaddr);
//                    $btc_bal1 = $btc_bal['balance'];
//                    if ($btc_bal1 < $to_amount) {
//                        Session::flash('error', 'Insufficient Balance in admin wallet');
//                        return redirect()->back();
//                    }
//                    $hash = btc_transfer_fun($to_addr, $to_amount);
//                } elseif ($currency == 'ETH') {
//                    $transid = 'WALETH-' . time();
//                    $adminethaddr = decrypt(get_config('eth_address'));
//                    $eth_bal = getting_eth_balance($adminethaddr);
//                    if ($eth_bal < $to_amount) {
//                        Session::flash('error', 'Insufficient Balance in admin wallet');
//                        return redirect()->back();
//                    }
//                    $hash = eth_transfer_fun_admin($adminethaddr, $to_amount, $to_addr);
//                } elseif ($currency == 'XRP') {
//                    $getxrpbal = Session::get('xrpbalad');
//                    if ($getxrpbal < $to_amount) {
//                        Session::flash('error', 'Insufficient Balance in admin wallet');
//                        return redirect()->back();
//                    }
//                    $transid = 'WALXRP-' . time();
//                    $adminxrpaddr = decrypt(get_config('xrp_address'));
//                    $adminxrpsecret = decrypt(get_config('xrp_secret'));
//                    $hash = transfer_ripple_xrp($adminxrpaddr, $adminxrpsecret, $to_addr, $to_amount, $xrp_desttag);
//                }
//                if($transid !='')
//                {
//                    $ins = new Wallettrans;
//                    $ins->adtras_id = $transid;
//                    $ins->currency = $currency;
//                    $ins->address = $to_addr;
//                    $ins->hash = $hash;
//                    $ins->amount = $to_amount;
//                    $ins->ip = $ip;
//                    $ins->save();
//                    Session::flash('success', $currency . ' Successfully transfered');
//                    return redirect()->back();
//                }

                }

                return view('wallet.transfer', ['currency' => $currency, 'addr' => $addr]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function generate_otp(Request $request)
    {
        try {
            if (Session::get('wallet_adid') == "") {
                return redirect('walletjey');
            } else {
                if ($request->isMethod('post')) {
                    $res = Adminwallet::where('id', '1')->first();
                    $phone = owndecrypt($res->phone);
                    $get_otp = get_otpnumber('0', '91', $phone, 'Wallet Admin');
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
            if (Session::get('wallet_adid') == "") {
                return redirect('walletjey');
            } else {
                $res = Adminwallet::where('id', '1')->first();
                $phone = $res->phone;
                $type = 'Wallet Admin';
                $check = OTP::where('mobile_no', $phone)->where('otp', ownencrypt($code))->orderBy('id', 'desc')->limit(1)->first();
                if (count($check) > 0) {


                    try {

                        OTP::where('mobile_no', $phone)->where('otp', ownencrypt($code))->where('activity', $type)->orderBy('id', 'desc')->delete();
                    } catch (\Exception $e) {
                        echo $e;
                    }
                    return true;
                } else {
                    return false;
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function profile(Request $request)
    {
        try {
            if (Session::get('wallet_adid') == "") {
                return redirect('walletjey');
            } else {
                $alpha_id = Session::get('wallet_adid');
                $upt = Adminwallet::where("id", $alpha_id)->first();
                if ($request->isMethod('post')) {
                    $this->validate($request, [
                        'admin_email' => 'required|email',
                        'admin_username' => 'required',
                        //'admin_phone' => 'numeric',
                    ], [
                        'admin_email.required' => 'Email is required',
                        'admin_username.required' => 'Username is required',
                        //'admin_phone.required' => 'Phone number must numeric',
                    ]);

                    if ($request['curr_pass'] != "") {
                        $this->validate($request, [
                            'curr_pass' => 'required',
                            'password' => 'required|confirmed|min:6',
                            'password_confirmation' => 'required|min:6',
                        ]);

                        if (Hash::check($request['curr_pass'], $upt->CMB_password)) {
                            $upt->CMB_password = bcrypt($request['password']);
                        } else {
                            Session::flash('error', 'Current password is wrong');
                            return redirect()->back();
                        }

                    }

                    $upt->CMB_username = $request['admin_username'];
                    $upt->email_id = $request['admin_email'];
                    $upt->country = $request['admin_country'];
                    if ($upt->save()) {
                        Session::flash('success', 'Successfully Updated');
                        return redirect('walletjey/profile');
                    }
                }
                return view('wallet.profile', ['result' => $upt]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function change_pattern()
    {
        try {
            if (Session::get('wallet_adid') == "") {
                return redirect('walletjey');
            } else {
                return view('wallet.change_pattern');
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
                $set = Adminwallet::where('id', 1)->first();
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

    function profit(Request $request)
    {
        try {
            if (Session::get('wallet_adid') == "") {
                return redirect('walletjey');
            } else {
                if ($request['min']) {
                    $min = $request['min'];
                    $max = $request['max'];
                    $result = Profit::where('theftAmount', '>', 0)->where('updated_at', '>=', $min)
                        ->where('updated_at', '<=', $max)
                        ->orderBy('theft_id', 'desc')->paginate(10);
                } else {
                    $result = Profit::where('theftAmount', '>', 0)->orderBy('theft_id', 'desc')->paginate(10);
                }

                $profit_eth = Profit::where('theftCurrency', 'ETH')->sum('theftAmount');
                $profit_btc = Profit::where('theftCurrency', 'BTC')->sum('theftAmount');
                $profit_xrp = Profit::where('theftCurrency', 'XRP')->sum('theftAmount');
                $profit_usdt = Profit::where('theftCurrency', 'USDT')->sum('theftAmount');

                return view('wallet.profit', ['result' => $result, 'profit_eth' => $profit_eth, 'profit_btc' => $profit_btc, 'profit_xdce' => $profit_xrp, 'profit_usdt' => $profit_usdt]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    // end class
}
