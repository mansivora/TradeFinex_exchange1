<?php

namespace App\Http\Controllers;

use App\model\Balance;
use App\model\Cms;
use App\model\Country;
use App\model\Enquiry;
use App\model\Faq;
use App\model\OTP;
use App\model\Pair;
use App\model\PairStats;
use App\model\Profit;
use App\model\ReferralBonus;
use App\model\ReferralEarning;
use App\model\SiteSettings;
use App\model\Tokenusers;
use App\model\Trade;
use App\model\Transaction;
use App\model\UserBalance;
use App\model\UserCurrencyAddresses;
use App\model\UserCurrencyAddressesTest;
use App\model\Users;
use App\model\Verification;
use App\model\Currencies;
use App\model\UserBalancesNew;
use App\model\Xdce_transfer;
use App\model\TradeMapping;
use Cache;
use DB;
use Hash;
use Illuminate\Http\Request;
use jsonRPCClient;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\Exception;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Storage;
use Session;
use Validator;
use Pusher\Pusher;

class UserController extends Controller
{
    //
    public function __construct()
    {
        try {
            //cons;
            $ip = \Request::ip();
            blockip_list($ip);
            return view('errors.404');
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function index()
    {
        try {
//            if (Session::get('alphauserid') == "") {

            $result_array = '';
            $get_all_pairs = Pair::all();
            if ($get_all_pairs) {
                foreach ($get_all_pairs as $get_all_pair) {
                    $this->update_pairstats($get_all_pair->id);
                    $get_pair_stat = PairStats::where('pair_id', $get_all_pair->id)->first();
                    $explode = explode('-', $get_all_pair->pair);
                    $first_currency = $explode[0];
                    $currency = $explode[1];
                    $pair = $get_all_pair->pair;
                    $id = $get_pair_stat->id;
                    $vol = $get_pair_stat->volume;
                    $low = $get_pair_stat->low;
                    $high = $get_pair_stat->high;
                    $last = $get_pair_stat->last;
                    $percentage_change = $get_pair_stat->percent_change . '%';
                    $change = $get_pair_stat->change;
                    $color = strtolower($get_pair_stat->colour);

                    $array = array('id' => $id, 'first_currency' => $first_currency, 'currency' => $currency, 'Pair' => $pair, 'Volume' => number_format($vol, 2, '.', ''), 'Low' => number_format($low, 3, '.', '')
                    , 'High' => number_format($high, 3, '.', ''), 'Percentage' => $percentage_change, 'Change' => number_format($change, 3, '.', ''), 'Colour' => $color, 'Last' => number_format($last, 3, '.', ''));
                    $result[] = $array;

                }
                $result_array = array('data' => $result);
            }
            return view('front.index', ['results' => $result]);
//            }
//            else
//            {
//                return redirect('/trade');
//            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function update_pairstats($pairid)
    {
        try {
            $date = date('Y-m-d H:i:s', strtotime("-1 days"));
            $get_pair = Pair::where('id', $pairid)->first();
            $get_pair_name = $get_pair->pair;

            $pair_stats = PairStats::where('pair_id', $pairid)->first();

            $volume = TradeMapping::where('pair', $get_pair_name)->where('updated_at', '>=', $date)
                ->orderBy('created_at', 'desc')->sum('triggered_qty');
            $low = TradeMapping::where('pair', $get_pair_name)->where('updated_at', '>=', $date)
                ->orderBy('created_at', 'desc')->min('triggered_price');

            if ($low == null || $low == "") {
                $low = 0;
            }

            $high = TradeMapping::where('pair', $get_pair_name)->where('updated_at', '>=', $date)
                ->orderBy('created_at', 'desc')->max('triggered_price');

            if ($high == null || $high == "") {
                $high = 0;
            }

            $last_executed = TradeMapping::where('pair', $get_pair_name)->where('updated_at', '>=', $date)
                ->orderBy('created_at', 'desc')->first();
            if ($last_executed != null || $last_executed != "") {
                $triggered_price = $last_executed->triggered_price;
            } else {
                $triggered_price = 0;
            }

            $first_executed = TradeMapping::where('pair', $get_pair_name)->where('updated_at', '>=', $date)
                ->orderBy('created_at', 'asc')->first();
            if ($first_executed != null || $first_executed != "") {
                $first_executed_price = $first_executed->triggered_price;
            } else {
                $first_executed_price = 0;
            }

            $pair_stats->volume = $volume;
            $pair_stats->low = $low;
            $pair_stats->high = $high;
            if ($triggered_price != 0) {
                $pair_stats->last = $triggered_price;
            }

            if ($first_executed_price != 0) {
                $percent_change = (($triggered_price - $first_executed_price) / $first_executed_price) * 100;
            } else {
                $percent_change = 0;
            }

            if ($percent_change < 0) {
                $color = 'red';
            } else {
                $color = 'green';
            }

            if ($first_executed_price >= $triggered_price) {
                $change = $first_executed_price - $triggered_price;
                $change = number_format($change, 8, '.', '');
            } else {
                $change = $triggered_price - $first_executed_price;
                $change = number_format($change, 8, '.', '');
            }

            $pair_stats->percent_change = $percent_change;
            $pair_stats->colour = $color;
            $pair_stats->change = $change;
            $pair_stats->save();
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function howtostart()
    {
        try {
            return view('front.howtostart');
        } catch (\Exception $exception) {
            return view('front.error');
        }
    }

    function login(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'login_mail' => 'required|email',
                    'password' => 'required',
//                'g-recaptcha-response' => 'required|captcha'
                    'captcha' => 'required|captcha',
                ], [
                    'login_mail.required' => 'Email id is required',
                    'login_mail.email' => 'Enter valid email id',
                    'password.required' => 'Password is required',
//                'g-recaptcha-response.required' => 'Please verify that you are human.',
//                'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.'
                    'captcha.required' => 'Captcha is required',
                    'captcha.captcha' => 'Captcha is wrong',
                ]);
                if ($validator->fails()) {
                    return redirect('login')->withErrors($validator);
                }
                $remember_me = $request['remember_me'];
                $email = strtolower($request['login_mail']);
                $password = $request['password'];
                $ip = \Request::ip();
                $res = $this->user_login_check($email, $password);
                switch ($res) {
                    case '1':
                        //send login status mail
                        $to = [$email];
                        $subject = get_template('17', 'subject');
                        $message = get_template('17', 'template');
//                        $offset = $_COOKIE['offset'];
//                        if(isset($offset))
//                        {
//                            $offset = intval($offset)*-60;
//                            $tz = timezone_name_from_abbr("", $offset, 0);
//                            if($tz) {
//                                date_default_timezone_set($tz);
//                            }
//                        }
                        $tz = $_COOKIE['tz'];
                        if (isset($tz)) {
                            date_default_timezone_set($tz);
                        }
                        $mailarr = array(
                            '###EMAIL###' => $email,
                            '###OS###' => getOS(),
                            '###BROWSER###' => getBrowser(),
                            '###IP###' => $ip,
                            '###TIME###' => date('Y-m-d H:i:s'),
                        );
                        $message = strtr($message, $mailarr);
                        $subject = strtr($subject, $mailarr);
                        sendmail($to, $subject, ['content' => $message]);
                        if ($remember_me == 'on') {
                            $hour = time() + 3600 * 24 * 30;
                            setcookie('email', $email, $hour);
                            setcookie('password', $password, $hour);
                        }
                        Session::flash('info', 'Welcome To ' . get_config('site_name') . ' Exchange');
//                        return redirect('trade');
                        return redirect('trade');
                        break;
                    case '5':
                        return view('front.tfacode');
                        break;
                    case '2':
                        Session::flash('error', 'Email or password is wrong');
                        return redirect()->back();
                        break;
                    case '3':
                        Session::flash('error', 'Your account is deactive');
                        return redirect()->back();
                        break;
                    case '4':
                        Session::flash('error', "Email or password is wrong / User dosen't exist.");
                        return redirect()->back();
                        break;
                    case '6':
                        Session::flash('error', 'Please Verify your email address to Login');
                        return redirect()->back();
                        break;
                    case '7':
                        Session::flash('error', 'Please Verify your Mobile Number to Login');
                        return redirect()->back();
                        break;

                    default:
                        Session::flash('error', 'Please Verify your email address to Login');
                        return redirect()->back();
                        break;
                }
            }
            if (Session::get('alphauserid') == "") {

                return view('front.login', ['user_id' => 0]);
            } else {
                return redirect('home');
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function check_ripple_balance()
    {
        try {
            $output = array();
            $return_var = -1;
            $path = $_SERVER["DOCUMENT_ROOT"];


            $address = exec('cd ' . $path . '/public/crypto; node xrp.js ', $output, $return_var);
            $result = json_decode($address);
            $bal = $result->xrpBalance;
            return $bal;
        } catch (\Exception $exception) {

            return $path .
                $exception->getMessage();
        }

    }

    function register(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                //  echo "okay"; exit;

                $validator = Validator::make($request->all(), [
//                    'first_name' => 'required',
//                'last_name' => 'required',
                    //'username' => 'required',
                    'email_id' => 'required|email',
                    'password' => 'required|confirmed|min:6',
                    'password_confirmation' => 'required|min:6',
//                'country_id' => 'required',
//                    'phone_no' => 'required|numeric',
                ], [
//                        'first_name.required' => 'Name is required',
//                    'last_name.required' => 'Last name is required',
                        //'username.required' => 'Username is required',
                        'email_id.required' => 'Email id is required',
                        'email_id.email' => 'Enter valid email id',
                        'password.required' => 'Password is required',
//                    'country_id.required' => 'Country is required',
//                        'phone_no.required' => 'Phone number is required',
//                        'phone_no.numeric' => 'Phone number should be digit format',
                    ]
                );
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator);
                }
                $ip = \Request::ip();

//                $first_name = $request['first_name'];
//                $last_name = $request['last_name'];

                $ref_code = $request['referral_code'];

                if ($ref_code == '') {
                    $user_code = '';
                    $referral_status = 0;
                } else {
                    $user_code = $ref_code;
                    $occurance = Users::where('referral_code', $user_code)->first();
                    if ($occurance == null) {
                        Session::flash('error', 'Referral Code you entered is not valid');
                        return redirect()->back()->withInput($request->all());
                    }
                    $referral_status = 1;
                }

                $email = strtolower($request['email_id']);
                $splemail = explode("@", $email);
                $end_user1 = encrypt($splemail[0]);
                $end_user2 = encrypt($splemail[1]);
                $res = $this->checking($splemail[0], $splemail[1]);
                if (count($res) > 0) {
                    Session::flash('error', 'The email address already exsits');
                    return redirect()->back()->withInput($request->all());
                }
                $pass_code = bcrypt($request['password']);
                $activation_code = mt_rand(0000, 9999) . time();
                $forgot_code = mt_rand(111111, 999999) . time();
                $verify_status = 2;
                $status = 2;
                $created_at = date('Y-m-d H:i:s');
//                $country = $request['isdcode'];
//                $country = Country::where('phonecode', $country)->first();
//                $country = $country->id;
                $document_status = 0;
//                $mobile_no = $request['phone_no'];
//                $check = $this->checkphone($mobile_no);
//                if (count($check) > 0) {
//                    Session::flash('error', 'The Phone Number Already Exsits.');
//                    return redirect()->back()->withInput($request->all());
//                }
//                $mob_isd = $request['isdcode'];
                $mobile_status = 0;

//                $referral_code = generate_uid();
//                $true = Users::where('referral_code',$referral_code)->count();
//                while($true > 0)
//                {
//                    $referral_code = generate_uid();
//                   $true = Users::where('referral_code',$referral_code)->get();
//
//                }

                $xdc_addr = "";
//                $xdcres = signup_XDC($email, $mobile_no, $request['password']);
//                if ($xdcres->status == 'FAILED') {
//                    Session::flash('error', 'Problem -' . $xdcres->message);
//                    return redirect()->back();
//                } else {
//                    $xdc_addr = $xdcres->public;
//                }

                $xinpass = ownencrypt($request['password']);

                $ins = ['ip' => $ip, 'user_code' => $user_code, 'referral_code' => '', 'end_user1' => $end_user1, 'end_user2' => $end_user2, 'pass_code' => $pass_code, 'activation_code' => $activation_code, 'forgot_code' => $forgot_code, 'verify_status' => $verify_status, 'status' => $status, 'referral_status' => $referral_status, 'created_at' => $created_at, 'document_status' => $document_status, 'mobile_status' => $mobile_status, 'profile_image' => 'noimage.png', 'xinpass' => $xinpass];

                $insert = Users::insertGetId($ins);
                //$lastid=$insert->id;

                $currencies = Currencies::all();
                foreach ($currencies as $val) {
                    $bal_new = new UserBalancesNew;
                    $bal_new->user_id = $insert;
                    $bal_new->currency_id = $val->unique_id;
                    $bal_new->currency_name = $val->currency_symbol;
                    $bal_new->balance = 0;
                    $bal_new->save();
                }

                if ($ref_code) {
                    if ($occurance) {
                        $referrer_id = $occurance->id;
                        $referred_id = $insert;
                        $referral_bonus = ReferralBonus::where('id', '1')->first();
                        $record = new ReferralEarning;
                        $record->referrer_id = $referrer_id;
                        $record->referred_id = $referred_id;
                        $record->referrer_name = get_user_details($referrer_id, 'enjoyer_name');
                        $record->referred_name = get_user_details($referred_id, 'enjoyer_name');
                        $record->referrer_email = get_usermail($referrer_id);
                        $record->referred_email = get_usermail($referred_id);
                        $record->referrer_bonus = $referral_bonus->referrer_bonus;
                        $record->referred_bonus = $referral_bonus->referred_bonus;
                        $record->referrer_status = 0;
                        $record->referred_status = 0;
                        $record->currency = $referral_bonus->currency;
                        $record->save();
                    }
                }

                //address generator
                check_live_address($insert);

                //log
                last_activity($email, 'Registration', 0);

                $inst = new Tokenusers;
                $inst->user_id = $insert;
                $inst->email = ownencrypt($email);
                $inst->passcode = ownencrypt($request['password']);
//                $inst->phone = ownencrypt($mobile_no);
                $inst->created_at = $created_at;
                $inst->save();

                $to = $email;
                $subject = get_template('4', 'subject');
                $message = get_template('4', 'template');
                $mailarr = array(
                    '###USERNAME###' => 'User',
                    '###LINK###' => url('userverification/' . $activation_code),
                    '###SITENAME###' => get_config('site_name'),
                );
                $message = strtr($message, $mailarr);
                $subject = strtr($subject, $mailarr);
                sendmail($to, $subject, ['content' => $message]);
//                Session::flash('success', 'Registration Request Sent. Thank you for registering with ExBlock. You will be sent an activation link within the next 5 minutes. If you did not received an email with the activation link, please check that you have input the correct email address if not please re-register with the intended email account.');

//            if($mobile_no != "")
//            {
//              $res= verify_user_registeration($mob_isd,$mobile_no,$email);
//              if($res['status'] == 0)
//              {
//                  $insert =0;
//              }
//            }
//            else
//                {
//                    $insert = 0;
//                }

//                return view('front.login', ['user_id' => 0]);
                return redirect('/registration');
            }
            if (Session::get('alphauserid') == "") {
//                $country = Country::orderBy('phonecode', 'asc')->get();
                if ($request['referral_code']) {
                    Session::flash('_old_input', $request->all());
                }
                return view('front.register', ['user_id' => 0]);
            } else {
                return redirect('home');
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function registraion()
    {
        return view('front.registration');
    }

    function export_pdf()
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                $user_id = Session::get('alphauserid');

                $trade_records = Trade::select('trade_id', 'pair', 'type', 'original_qty', 'price', 'updated_total', 'status', 'updated_at')->where('user_id', $user_id)->
                where('status', 'completed')->orderBy('updated_at', 'desc')->get();
                if ($trade_records->count() > 0) {
                    Excel::create('Trade_Records', function ($excel) use ($trade_records) {
                        $excel->sheet('All', function ($sheet) use ($trade_records) {

                            $sheet->fromModel($trade_records, null, "A1", true);
                            $sheet->setOrientation('landscape');
                            $sheet->setScale(5);
                            $sheet->setAllBorders('thin');
                        }
                        );
                    })->export('pdf');
                } else {
                    Session::flash('error', 'No trade records found for you.');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function export_csv()
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                $user_id = Session::get('alphauserid');

                $trade_records = Trade::select('trade_id', 'pair', 'type', 'original_qty', 'price', 'fee', 'updated_total', 'status', 'updated_at')->where('user_id', $user_id)->
                where('status', 'completed')->orderBy('updated_at', 'desc')->get();
                if ($trade_records->count() > 0) {
                    Excel::create('Trade_Records', function ($excel) use ($trade_records) {
                        $excel->sheet('All', function ($sheet) use ($trade_records) {

                            $sheet->fromModel($trade_records, null, "A1", true);
                            $sheet->setOrientation('landscape');
                            $sheet->setScale(5);
                            $sheet->setAllBorders('thin');
                        }
                        );
                    })->export('csv');
                } else {
                    Session::flash('error', 'No trade records found for you.');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function transaction_csv($type)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {

                $user_id = Session::get('alphauserid');
                $transaction_records = Transaction::select('updated_at', 'transaction_id', 'currency_name', 'amount')->where('user_id', $user_id)->
                where('type', $type)->where('status', 'completed')->orderBy('updated_at', 'desc')->get();
                if ($transaction_records->count() > 0) {
                    Excel::create($type, function ($excel) use ($transaction_records) {
                        $excel->sheet('All', function ($sheet) use ($transaction_records) {

                            $sheet->fromModel($transaction_records, null, "A1", true);
                            $sheet->setOrientation('landscape');
                            $sheet->setScale(5);
                            $sheet->setAllBorders('thin');
                        }
                        );
                    })->export('csv');
                } else {
                    Session::flash('error', 'No ' . $type . ' records found for you.');
                    return redirect()->back();
                }

            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');

        }

    }

    function transaction_pdf($type)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                $user_id = Session::get('alphauserid');

                $transaction_records = Transaction::select('updated_at', 'transaction_id', 'currency_name', 'amount')->where('user_id', $user_id)->
                where('type', $type)->where('status', 'completed')->orderBy('updated_at', 'desc')->get();
                if ($transaction_records->count() > 0) {
                    Excel::create($type, function ($excel) use ($transaction_records) {
                        $excel->sheet('All', function ($sheet) use ($transaction_records) {

                            $sheet->fromModel($transaction_records, null, "A1", true);
                            $sheet->setOrientation('landscape');
                            $sheet->setScale(5);
                            $sheet->setAllBorders('thin');
                        }
                        );
                    })->export('pdf');
                } else {
                    Session::flash('error', 'No ' . $type . ' records found for you.');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function forgotpass(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'forgot_mail' => 'required|email',
                ], [
                    'forgot_mail.required' => 'Email id is required',
                    'forgot_mail.email' => 'Enter valid email id',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator);
                }
                $email = strtolower($request['forgot_mail']);
                $spl = explode("@", $email);
                $user1 = $spl[0];
                $user2 = $spl[1];
                $res = $this->checking($user1, $user2);

                if (count($res) > 0) {
                    foreach ($res as $val) {
                        $to = $email;
                        $subject = get_template('5', 'subject');
                        $message = get_template('5', 'template');
                        $mailarr = array(
                            '###USERNAME###' => $val->enjoyer_name,
                            '###LINK###' => url('resetpassword/' . $val->forgot_code),
                            '###SITENAME###' => get_config('site_name'),
                            '###SITELINK###' => url('/'),
                        );
                        $message = strtr($message, $mailarr);
                        $subject = strtr($subject, $mailarr);
                        sendmail($to, $subject, ['content' => $message]);

                        Session::flash('success', 'Check your mail we have sent password reset link');
                        return redirect('/forgotpass');
                    }
                } else {
                    Session::flash('error', 'This email ID does not exist in our database.');
                    return redirect('/forgotpass');
                }

            }
            return view('front.forgotpass');
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function userverification($time)
    {
        try {
            $check = Users::where('activation_code', $time)->first();
            if ($check) {
                $activation_code = mt_rand(0000, 9999) . time();
                $check->activation_code = $activation_code;
                $check->verify_status = 1;
                $check->status = 1;
                $check->save();
                Session::flash('success', 'Your account is activated, Now you can login with your credentials');
                return redirect('/login');
            } else {
                Session::flash('error', 'Invalid Link');
                return redirect('/login');
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function checking($end_user1, $end_user2)
    {
        try {
            $items = Users::all()->filter(function ($record) use ($end_user1, $end_user2) {
                if (decrypt($record->end_user1) == $end_user1 && decrypt($record->end_user2) == $end_user2) {
                    return $record;
                } else {
                    return false;
                }
            });

            return $items;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function checkphone($mobile_no)
    {
        try {
            $numbers = Users::all()->filter(function ($record) use ($mobile_no) {
                if (owndecrypt($record->mobile_no) == $mobile_no) {
                    return $record;
                } else {
                    return false;
                }
            });
            return $numbers;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function resetpassword(Request $request, $code)
    {
        try {
            if ($code) {
                $check = Users::where('forgot_code', $code)->first();
                if ($check) {

                    if ($request->isMethod('post')) {
                        $validator = Validator::make($request->all(), [
                            'password' => 'required|confirmed|min:6',
                            'password_confirmation' => 'required|min:6',
                        ]);
                        if ($validator->fails()) {
                            return redirect()->back()->withErrors($validator);
                        }
                        $forgot_code = mt_rand(111111, 999999) . time();
                        $check->forgot_code = $forgot_code;
                        $check->pass_code = bcrypt($request['password']);
                        $check->save();
                        //log
                        last_activity(get_usermail($check->id), 'Reset Password', $check->id);
                        Session::flash('success', 'Your password has been reset successfully.');
                        return redirect('/login');
                    }

                    return view('front.newpass', ['code' => $code]);

                } else {
                    Session::flash('error', 'Invalid Link');
                    return redirect('/forgotpass');
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function aboutus()
    {
        try {
//        $page = Cms::where('link', 'aboutus')->first();
//        return view('front.pages', ['page' => $page]);
            return view('front.aboutus');
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function terms()
    {
        try {
//        $page = Cms::where('link', 'terms')->first();
//        return view('front.pages', ['page' => $page]);
            return view('front.terms');
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function privacy()
    {
        try {
//        $page = Cms::where('link', 'privacy')->first();
//        return view('front.pages', ['page' => $page]);
            return view('front.privacy');
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function faq()
    {
        try {
//            $data = Faq::where('status', '1')->orderBy('id', 'asc')->get();
//            return view('front.faq', ['data' => $data]);
            return view('front.faq');
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function news()
    {
        try {
//            $data = Faq::where('status', '1')->orderBy('id', 'asc')->get();
            return view('front.news');
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function user_login_check($email, $password)
    {
        try {
            $spl = explode("@", $email);
            $user1 = $spl[0];
            $user2 = $spl[1];
            $res = $this->checking($user1, $user2);
            /*$xdclogin = login_xdc_fun($email, $password);
                if ($xdclogin->status != 'SUCCESS') {
                    return "4";exit();
            */

            if ($res) {
                foreach ($res as $val) {
                    $recordid = $val->id;
                    $recordpass = $val->pass_code;
                    if (Hash::check($password, $recordpass)) {
                        if ($val->status == '1' && ($val->tfa_status == 'disable' || $val->tfa_status == '')) {
                            //log
                            last_activity(get_usermail($recordid), 'Login', $recordid);
                            $sess = array('alphauserid' => $recordid, 'alphausername' => $val->enjoyer_name, 'xinfinpass' => $password);
                            Session::put($sess);
                            return "1";
                        } elseif ($val->status == '1' && $val->tfa_status == 'enable') {
                            //log
                            last_activity(get_usermail($recordid), 'Login', $recordid);
                            Session::put('tfa_key', ownencrypt($recordid));
                            return "5";
                        } elseif ($val->verify_status == '2') {
                            return "6";
                        }
// elseif ($val->mobile_status == '0') {
//                        return "7";
//                    }
                        else {
                            return "3";
                        }

                    } else {
                        return "2";
                    }

                }
            } else {
                return "4";
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function sessionlogout()
    {
        try {
            Session::flush();
            Cache::flush();
            if (Session::get('alphauserid') == "") {
                sleep(1);
                return redirect('login');
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
            if (Session::get('alphauserid') == "") {
                return redirect('sessionlogout');
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function dashboard()
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {

                $num = encrypt('bitcoincash:qrat6a4jscp7qqfuqquvrjnwha66uk7sfq2hs3cjut');
                $num = owndecrypt('OahuNFlHaGRncWWMemawoFs+QdjPjItcg7jWwaHL+aM=');

                $bch_host = ownencrypt('78.129.229.69');
                $bch_port = ownencrypt('9555');
                $rpcuser = ownencrypt('apexcbitcash');
                $rpcpassword = ownencrypt('ApLExCash@2018%');


                $user_id = Session::get('alphauserid');
//            check_live_address($user_id);

                $xdcaddr = get_user_details($user_id, 'XDC_addr');
                $xdceaddr = get_user_details($user_id, 'XDCE_addr');
//			$xdceaddr ='0x853ac436688033ff32e80ba1f3ea19d19ab098b0';
                $xdcbal = get_livexdc_bal($xdcaddr);
                $xdcebal = get_livexdce_bal($xdceaddr);
                $xdce_blocknum = Transaction::where('type', 'Deposit')->where('currency_name', 'XDCE')->max('blocknumber');

//            $xdceTransactionList = get_xdce_transactions($xdceaddr,$xdce_blocknum);
//
//
//			if ($xdceTransactionList->status == 'true') {
//                    $transaction = $xdceTransactionList->data;
//                    for ($tr = 0; $tr < count($transaction); $tr++) {
//
//                        $block_number = $transaction[$tr]->blockNumber;
//                        $address = $transaction[$tr]->to;
//                        $txid = $transaction[$tr]->tx;
//                        $value = $transaction[$tr]->value;
//
//                        $dep_id = $txid;
//                        $eth_balance = $value;
//                        $ether_balance = $eth_balance;
//
//                        $dep_already = xdce_checkdepositalready($user_id, $dep_id);
//                        if ($dep_already === TRUE && (float)$ether_balance > 0) {
//                            if ($xdceaddr == $address) {
//
//                                $ether_balance = sprintf('%.10f', $ether_balance);
//
//                                //deposit transaction
//                                $transid = 'TXD' . $user_id . time();
//                                $today = date('Y-m-d H:i:s');
//                                $ip = \Request::ip();
//                                $ins = new Transaction;
//                                $ins->user_id = $user_id;
//                                $ins->payment_method = 'Cryptocurrency Account';
//                                $ins->transaction_id = $transid;
//                                $ins->currency_name = 'XDCE';
//                                $ins->type = 'Deposit';
//                                $ins->transaction_type = '1';
//                                $ins->amount = $ether_balance;
//                                $ins->updated_at = $today;
//                                $ins->crypto_address = $xdceaddr;
//                                $ins->transfer_amount = '0';
//                                $ins->fee = '0';
//                                $ins->tax = '0';
//                                $ins->verifycode = '1';
//                                $ins->order_id = '0';
//                                $ins->status = 'Completed';
//                                $ins->cointype = '2';
//                                $ins->payment_status = 'Paid';
//                                $ins->paid_amount = '0';
//                                $ins->wallet_txid = $dep_id;
//                                $ins->ip_address = $ip;
//                                $ins->verify = '1';
//                                $ins->blocknumber = '';
//                                if($ins->save())
//                                {
//                                    //update user
//                                    $fetchbalance = get_userbalance($user_id, 'XDCE');
//                                    $finalbalance = $fetchbalance + $ether_balance;
//                                    $upt = Balance::where('user_id', $user_id)->first();
//                                    $upt->XDCE = $finalbalance;
//                                    $upt->save();
//                                    deposit_mail($user_id, $ether_balance, $transid, 'XDCE');
//                                }
//                            }
//                        }
//                    }
//
//                }


//            if ($xdcbal > 0) {
//                $email = get_usermail($user_id);
//                //$pass = Session::get('xinfinpass');
//                $pass = get_user_details($user_id, 'xinpass');
//                login_xdc_fun($email, owndecrypt($pass));
//                $adminxdcaddr = decrypt(get_config('xdc_address'));
//                $res = transfer_xdctoken($xdcaddr, $xdcbal, $adminxdcaddr, $user_id, owndecrypt($pass));
//                if ($res->status == 'SUCCESS') {
//                    $fetchbalance = get_userbalance($user_id, 'XDC');
//                    $uptbal = $fetchbalance + $xdcbal;
//                    $upt = Balance::where('user_id', $user_id)->first();
//                    $upt->XDC = $uptbal;
//                    $upt->save();
//
//                    $transid = 'TXD' . $user_id . time();
//                    $today = date('Y-m-d H:i:s');
//                    $ip = \Request::ip();
//                    $ins = new Transaction;
//                    $ins->user_id = $user_id;
//                    $ins->payment_method = 'Cryptocurrency Account';
//                    $ins->transaction_id = $transid;
//                    $ins->currency_name = 'XDC';
//                    $ins->type = 'Deposit';
//                    $ins->transaction_type = '1';
//                    $ins->amount = $xdcbal;
//                    $ins->updated_at = $today;
//                    $ins->crypto_address = $xdcaddr;
//                    $ins->transfer_amount = '0';
//                    $ins->fee = '0';
//                    $ins->tax = '0';
//                    $ins->verifycode = '1';
//                    $ins->order_id = '0';
//                    $ins->status = 'Completed';
//                    $ins->cointype = '2';
//                    $ins->payment_status = 'Paid';
//                    $ins->paid_amount = '0';
//                    $ins->wallet_txid = '';
//                    $ins->ip_address = $ip;
//                    $ins->verify = '1';
//                    $ins->blocknumber = '';
//                    $ins->save();
//                }
//            }
                if ($xdceaddr != '') {
                    xdce_deposit_process_user($xdceaddr);
                }

                $data = [
                    'aid' => $user_id,
                    'status' => get_user_details($user_id, 'status'),
                    'mobile_status' => get_user_details($user_id, 'mobile_status'),
                    'document_status' => get_user_details($user_id, 'document_status'),
                ];
                return view('front.dashboard', $data);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //history page
    function history($curr = '')
    {
        try {
            if (Session::get('alphauserid') == '') {
                return redirect('logout');
            } else {
                $userid = Session::get('alphauserid');

//                if (get_user_details($userid, 'document_status') != '1') {
//                    Session::flash('error', 'Please Complete your KYC process, to access the platform.');
//                    return redirect('profile');
//                } else {
                $curr = $curr ? $curr : 'USDT';

                $open_orders = Trade::where('user_id', $userid)->whereIn('status', ['active', 'partially'])
                    ->where(function ($query) use ($curr) {
                        $query->where('firstCurrency', '=', $curr)->orWhere('secondCurrency', '=', $curr);
                    })->orderBy('created_at', 'desc')->get();

                $history = DB::table('trade_order')->where('trade_order.user_id', '=', $userid)->where(function ($query) use ($curr) {
                    $query->where('trade_order.firstCurrency', '=', $curr)->orWhere('trade_order.secondCurrency', '=', $curr);
                })
                    ->leftJoin('trade_mapping', function ($join) {
                        $join->on(function ($query) {
                            $query->on('trade_mapping.buy_trade_order_id', '=', 'trade_order.id');
                            $query->orOn('trade_mapping.sell_trade_order_id', '=', 'trade_order.id');
                        });
                    })
                    ->orderBy('trade_order.updated_at', 'desc')
                    ->select('trade_order.*', 'trade_mapping.buy_trade_order_id', 'trade_mapping.sell_trade_order_id', 'trade_mapping.triggered_price', 'trade_mapping.triggered_qty', 'trade_mapping.total as triggered_total')
                    ->get();

                $deposit = Transaction::where('user_id', $userid)->where('type', 'Deposit')->orderBy('created_at', 'desc')->get();
                $withdraw = Transaction::where('user_id', $userid)->where('type', 'Withdraw')->orderBy('created_at', 'desc')->get();

                return view('front.history', ['open_orders' => $open_orders, 'history' => $history, 'deposit' => $deposit, 'withdraw' => $withdraw, 'currency' => $curr, 'userid' => $userid]);
//                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //kyc page
    function kyc_details(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                if ($request->isMethod('post')) {
                    $user_id = Session::get('alphauserid');
                    $result = Users::where('id', $user_id)->first();
                    $verification = Verification::where('user_id', $user_id)->first();
                    if ($verification == null) {
                        $verification = new Verification();
                        $validator = Validator::make($request->all(), [
                            'first_name' => 'required',
//                            'last_name' => 'required',
                            'kyc_country_id' => 'required',
                            'gender' => 'required',
                            'document_id' => 'required',
                            'dob' => 'required|date_format:d/m/Y|before:-18 years',
                            'f_side' => 'required',
                            'b_side' => 'required',
                            'h_side' => 'required',
                        ], [
                            'f_side.required' => 'Front side required.',
                            'b_side.required' => 'Back side required.',
                            'h_side.required' => 'Selfie with ID side required.',
                            'dob.date_format' => 'The DOB should be in DD/MM/YYYY format.',
                            'dob.before' => 'The age in KYC DOB should be greater than 18 years.'
                        ]);
                        if ($validator->fails()) {
                            return redirect()->back()->withErrors($validator)->withInput();
                        }
                    } else {
                        $validator = Validator::make($request->all(), [
                            'first_name' => 'required',
//                            'last_name' => 'required',
                            'kyc_country_id' => 'required',
                            'gender' => 'required',
                            'document_id' => 'required',
                            'dob' => 'required|date_format:d/m/Y|before:-18 years',
                        ], [
                            'dob.date_format' => 'The DOB should be in DD/MM/YYYY format.',
                            'dob.before' => 'The age in KYC DOB should be greater than 18 years.']);
                        if ($validator->fails()) {
                            return redirect()->back()->withErrors($validator)->withInput();
                        }
                    }
                    $verify = Verification::where('user_id', '<>', $user_id)->where('national_id', 'like', $request['document_id'])->first();
                    if ($verify != null) {
                        Session::flash('error', 'The entered ID number is already registered.');
                        return redirect()->back()->withInput();
                    }
                    $verification->user_id = $user_id;
                    $verification->proof1_status = 0;
                    $verification->proof2_status = 0;
                    $verification->proof3_status = 0;
                    $verification->first_name = $request['first_name'];
                    $verification->last_name = $request['last_name'];
                    $verification->country_code = $request['kyc_country_id'];
                    $verification->gender = $request['gender'];
                    $verification->national_id = $request['document_id'];
                    $verification->dob = $request['dob'];
                    if ($request['f_side']) {
                        $info = pathinfo($_FILES['f_side']['name']);
                        $mime = mime_content_type($_FILES['f_side']['tmp_name']);
                        $mime_array = ['image/png', 'image/jpeg'];
                        $ext = strtolower($info['extension']);
                        $ext_array = ['png', 'jpg', 'jpeg'];
                        if (in_array($mime, $mime_array) && in_array($ext, $ext_array)) {
//                    $file_name = $user_id.'_'.'f_side.'.$ext;
                            $check = uniqid("", 'true') . '.' . $ext;
                            $file_name = $this->check_unique($check, $ext);
                            while ($check != $file_name) {
                                $file_name = $this->check_unique($check, $ext);
                            }
//                    $target = $_SERVER['DOCUMENT_ROOT'].'/public/uploads/users/documents/'.$file_name;
//                    if($verification->proof1!='') {
//                        $target1 = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/users/documents/' . $verification->proof1;
//                        unlink($target1);
//                    }
//                    move_uploaded_file( $_FILES['f_side']['tmp_name'], $target);
                            if ($verification->proof1 != '') {
                                Storage::disk('ftp')->delete($verification->proof1);
                            }
                            Storage::disk('ftp')->put($file_name, fopen($request->file('f_side'), 'r+'));
                            $verification->proof1 = $file_name;
                        } else {
                            $to = ['raj@xinfin.org', 'rahul@xinfin.org', 'anil@xinfin.org', 'omkar@xinfin.org', 'aakash@xinfin.org', 'murphy@xinfin.org', 'alkeshc07@gmail.com', 'patelbunti@gmail.com'];
                            $subject = 'Some user trying to upload malicious file in KYC.';
                            $message = 'User id :' . $user_id . ', Email id : ' . get_usermail($user_id) . ' is trying to upload a file other than the allowed extensions. His account has been deactivated. He is trying to upload a file with extension : ' . $ext . ' and the file format is : ' . $mime;
                            sendmail($to, $subject, ['content' => $message]);
                            $result->status = '2';
                            $result->save();
                            Session::flush();
                            Cache::flush();
                            return redirect('login')->withErrors('Your account has been deactivated as you are trying to upload an invalid file, if you have any query regarding this then please contact our support at' . get_config('contact_mail') . '.');
                        }
                    }
                    if ($request['b_side']) {
                        $info = pathinfo($_FILES['b_side']['name']);
                        $mime = mime_content_type($_FILES['b_side']['tmp_name']);
                        $mime_array = ['image/png', 'image/jpeg'];
                        $ext = strtolower($info['extension']);
                        $ext_array = ['png', 'jpg', 'jpeg'];
                        if (in_array($mime, $mime_array) && in_array($ext, $ext_array)) {
//                    $file_name = $user_id.'_'.'b_side.'.$ext;
                            $check = uniqid("", 'true') . '.' . $ext;
                            $file_name = $this->check_unique($check, $ext);
                            while ($check != $file_name) {
                                $file_name = $this->check_unique($check, $ext);
                            }
//                    $target = $_SERVER['DOCUMENT_ROOT'].'/public/uploads/users/documents/'.$file_name;
//                    if($verification->proof2!='') {
//                        $target1 = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/users/documents/' . $verification->proof2;
//                        unlink($target1);
//                    }
//                    move_uploaded_file( $_FILES['b_side']['tmp_name'], $target);
                            if ($verification->proof2 != '') {
                                Storage::disk('ftp')->delete($verification->proof2);
                            }
                            Storage::disk('ftp')->put($file_name, fopen($request->file('b_side'), 'r+'));
                            $verification->proof2 = $file_name;
                        } else {
                            $to = ['raj@xinfin.org', 'rahul@xinfin.org', 'anil@xinfin.org', 'omkar@xinfin.org', 'aakash@xinfin.org', 'murphy@xinfin.org', 'alkeshc07@gmail.com', 'patelbunti@gmail.com'];
                            $subject = 'Some user trying to upload malicious file in KYC.';
                            $message = 'User id :' . $user_id . ', Email id : ' . get_usermail($user_id) . ' is trying to upload a file other than the allowed extensions. His account has been deactivated. He is trying to upload a file with extension : ' . $ext . ' and the file format is : ' . $mime;
                            sendmail($to, $subject, ['content' => $message]);
                            $result->status = '2';
                            $result->save();
                            Session::flush();
                            Cache::flush();
                            return redirect('login')->withErrors('Your account has been deactivated as you are trying to upload an invalid file, if you have any query regarding this then please contact our support at' . get_config('contact_mail') . '.');
                        }
                    }
                    if ($request['h_side']) {
                        $info = pathinfo($_FILES['h_side']['name']);
                        $mime = mime_content_type($_FILES['h_side']['tmp_name']);
                        $mime_array = ['image/png', 'image/jpeg'];
                        $ext = strtolower($info['extension']);
                        $ext_array = ['png', 'jpg', 'jpeg'];
                        if (in_array($mime, $mime_array) && in_array($ext, $ext_array)) {
//                    $file_name = $user_id.'_'.'h_side.'.$ext;
                            $check = uniqid("", 'true') . '.' . $ext;
                            $file_name = $this->check_unique($check, $ext);
                            while ($check != $file_name) {
                                $file_name = $this->check_unique($check, $ext);
                            }
//                    $target = $_SERVER['DOCUMENT_ROOT'].'/public/uploads/users/documents/'.$file_name;
//                    if($verification->proof3!='') {
//                        $target1 = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/users/documents/' . $verification->proof3;
//                        unlink($target1);
//                    }
//                    move_uploaded_file( $_FILES['h_side']['tmp_name'], $target);
                            if ($verification->proof3 != '') {
                                Storage::disk('ftp')->delete($verification->proof3);
                            }
                            Storage::disk('ftp')->put($file_name, fopen($request->file('h_side'), 'r+'));
                            $verification->proof3 = $file_name;
                        } else {
                            $to = ['raj@xinfin.org', 'rahul@xinfin.org', 'anil@xinfin.org', 'omkar@xinfin.org', 'aakash@xinfin.org', 'murphy@xinfin.org', 'alkeshc07@gmail.com', 'patelbunti@gmail.com'];
                            $subject = 'Some user trying to upload malicious file in KYC.';
                            $message = 'User id :' . $user_id . ', Email id : ' . get_usermail($user_id) . ' is trying to upload a file other than the allowed extensions. His account has been deactivated. He is trying to upload a file with extension : ' . $ext . ' and the file format is : ' . $mime;
                            sendmail($to, $subject, ['content' => $message]);
                            $result->status = '2';
                            $result->save();
                            Session::flush();
                            Cache::flush();
                            return redirect('login')->withErrors('Your account has been deactivated as you are trying to upload an invalid file, if you have any query regarding this then please contact our support at' . get_config('contact_mail') . '.');
                        }
                    }
                    if ($verification->save()) {
                        $user = Users::where('id', $user_id)->first();
                        $user->document_status = 3;
                        $user->first_name = $request['first_name'];
                        $user->enjoyer_name = $request['first_name'];
                        $user->country = $request['kyc_country_id'];
                        $user->save();
                        Session::flash('success', 'Your Kyc Verification request is placed.');
                        return redirect()->back();
                    } else {
                        Session::flash('error', 'Server error please retry again.');
                        return redirect()->back();
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function check_unique($file_name, $ext)
    {
        try {
            $check = Verification::where('proof1', $file_name)->orWhere('proof2', $file_name)->orWhere('proof3', $file_name)->get();
            if (count($check) > 0) {
                $file_name = uniqid("", 'true') . '.' . $ext;
                return $file_name;
            } else {
                return $file_name;
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function profile(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                $flag = 0;
                $user_id = Session::get('alphauserid');
                $result = Users::where('id', $user_id)->first();
                if ($request->isMethod('post')) {
                    $validator = Validator::make($request->all(), [
//                        'username' => 'required',
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'telephone' => 'required|numeric',
//                        'city' => 'required',
//                        'address' => 'required',
//                        'state' => 'required',
//                        'postal_code'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator);
                    }
                    if ($request['ptostatus']) {
                        $checkmobile = decrypt($request['ptostatus']);
                        if ($checkmobile) {
                            $splmob = explode("#", $checkmobile);
                            if ($splmob[0] == $request['telephone']) {
                                $result->mobile_no = ownencrypt($request['telephone']);
                                $result->mobile_status = '1';
                            }
                        }
                    }
//                    $result->enjoyer_name = $request['username'];
//                    if($request['username'])
//                    {
//                        $check = Users::where('id','!=',$result->id)->where('referral_code',strtolower($request['username']))->get();
//                        if(count($check) > 0)
//                        {
//                            Session::flash('error','User Name should be unique.');
//                            return redirect()->back();
//                        }
//                        else
//                        {
//                            $result->referral_code = strtolower($request['username']);
//                        }
//                    }
                    $result->first_name = $request['first_name'];
                    $result->enjoyer_name = $request['first_name'];
                    $result->last_name = $request['last_name'];
                    $result->mobile_no = ownencrypt($request['telephone']);
                    $result->mob_isd = $request['isdcode'];
                    $result->city = $request['city'];
                    $result->address = $request['address'];
                    $result->state = $request['state'];
                    $result->postal_code = $request['postal_code'];
                    $result->country = $request['country_id'];
                    if ($request['imageUpload']) {
                        $info = pathinfo($_FILES['imageUpload']['name']);
                        $mime = mime_content_type($_FILES['imageUpload']['tmp_name']);
                        $mime_array = ['image/png', 'image/jpeg'];
                        $ext = strtolower($info['extension']);
                        $ext_array = ['png', 'jpg', 'jpeg'];
                        if (in_array($mime, $mime_array) && in_array($ext, $ext_array)) {
                            $unique = uniqid("", true) . '.';

                            $file_name = $user_id . '_' . $unique . $ext;

                            $target = $_SERVER['DOCUMENT_ROOT'] . '/uploads/users/profileimg/' . $file_name;
                            if ($result->profile_image != '' && $result->profile_image != 'noimage.png') {
                                $target1 = $_SERVER['DOCUMENT_ROOT'] . '/uploads/users/profileimg/' . $result->profile_image;
                                unlink($target1);
                            }
                            move_uploaded_file($_FILES['imageUpload']['tmp_name'], $target);
                            $result->profile_image = $file_name;
                        } else {
                            $to = ['raj@xinfin.org', 'rahul@xinfin.org', 'anil@xinfin.org', 'omkar@xinfin.org', 'aakash@xinfin.org', 'murphy@xinfin.org'];
                            $subject = 'Some user trying to upload malicious file in profile image.';
                            $message = 'User id : ' . $result->id . ', Email id : ' . get_usermail($result->id) . ' trying to upload a file other than the allowed extensions. His account has been deactivated. He is trying to upload a file with extension : ' . $ext . ' and the file format is : ' . $mime;
                            sendmail($to, $subject, ['content' => $message]);
                            $result->status = '2';
                            $result->save();
                            Session::flush();
                            Cache::flush();
                            return redirect('login')->withErrors('Your account has been deactivated as you are trying to upload an invalid file, if you have any query regarding this then please contact our support at' . get_config('contact_mail') . '.');
                        }
                    }
                    if ($result->isDirty('mobile_no')) {
                        $check = $this->checkphone($request['telephone']);
                        if (count($check) > 0) {
                            Session::flash('error', 'The Phone Number Already Exists.');
                            return redirect()->back();
                        }
//                        $status = $request['change_number'];
//                        $result->mobile_status = $status;
                        $result->mobile_status = 0;
                        $result->save();
                    } else {
                        $result->save();
                        Session::flash('success', 'Profile Updated Successfully');
                    }
                    //log
                    last_activity(get_usermail($user_id), 'Profile Update', $user_id);
                    $country = Country::orderBy('phonecode', 'asc')->get();
                    $country_name = Country::all();
                    $countrycode = Country::where('phonecode', $result->mob_isd)->first();
                    $verification = Verification::where('user_id', $user_id)->first();

                    $google2fa = new Google2FA();
                    if ($result->tfa_code != "") {
                        $secret_code = $result->tfa_code;
                    } else {
                        $secret_code = $google2fa->generateSecretKey();
                        $result->tfa_code = $secret_code;
                        $result->tfa_status = 'disable';
                        $result->save();
                    }
                    $tfa_url = $google2fa->getQRCodeGoogleUrl(get_config('site_name'), get_usermail($user_id), $secret_code);

                    $referral_code = get_user_details($user_id, 'referral_code');
//                    if($referral_code == null)
//                    {
//                        $referral_code = generate_uid();
//                        $true = Users::where('referral_code',$referral_code)->get();
//                        while($true == null)
//                        {
//                            $referral_code = generate_uid();
//                            $true = Users::where('referral_code',$referral_code)->get();
//
//                        }
//                        $users = Users::where('id',$user_id)->first();
//                        $users->referral_code = $referral_code;
//                        $users->save();
//                    }

                    $referrer = ReferralEarning::where('referrer_id', $user_id)->get();
                    $referred = ReferralEarning::where('referred_id', $user_id)->get();

                    $data = ['result' => $result, 'countrycode' => $countrycode, 'flag' => 0, 'country' => $country, 'verification' => $verification, 'country_name' => $country_name, 'tfa_url' => $tfa_url, 'secret_code' => $secret_code, 'referral_code' => $referral_code, 'referrer' => $referrer, 'referred' => $referred];
                    return view('front.profile', $data);
                }
                $country = Country::orderBy('phonecode', 'asc')->get();
                $country_name = Country::all();
                $countrycode = Country::where('phonecode', $result->mob_isd)->first();
                $verification = Verification::where('user_id', $user_id)->first();

                $google2fa = new Google2FA();
                if ($result->tfa_code != "") {
                    $secret_code = $result->tfa_code;
                } else {
                    $secret_code = $google2fa->generateSecretKey();
                    $result->tfa_code = $secret_code;
                    $result->tfa_status = 'disable';
                    $result->save();
                }
                $tfa_url = $google2fa->getQRCodeGoogleUrl(get_config('site_name'), get_usermail($user_id), $secret_code);

                $referral_code = get_user_details($user_id, 'referral_code');
//                if($referral_code == null)
//                {
//                    $referral_code = generate_uid();
//                    $true = Users::where('referral_code',$referral_code)->get();
//                    while($true == null)
//                    {
//                        $referral_code = generate_uid();
//                        $true = Users::where('referral_code',$referral_code)->get();
//
//                    }
//                    $users = Users::where('id',$user_id)->first();
//                    $users->referral_code = $referral_code;
//                    $users->save();
//                }

                $referrer = referralEarning::where('referrer_id', $user_id)->get();
                $referred = referralEarning::where('referred_id', $user_id)->get();

                $data = ['result' => $result, 'countrycode' => $countrycode, 'flag' => 0, 'country' => $country, 'verification' => $verification, 'country_name' => $country_name, 'tfa_url' => $tfa_url, 'secret_code' => $secret_code, 'referral_code' => $referral_code, 'referrer' => $referrer, 'referred' => $referred];
                return view('front.profile', $data);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function create_usdt()
    {
        try {


            echo json_encode($transaction_details);
        } catch (\Exception $e) {
            return ($e->getMessage() . '<br>' . $e->getLine() . '<br>' . $e->getFile());
//            return view('errors.404');
        }
    }

    function change_password(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                if ($request->isMethod('post')) {
                    $validator = Validator::make($request->all(), [
                        'old_password' => 'required',
                        'password' => 'required|confirmed|min:6',
                        'password_confirmation' => 'required|min:6',
                    ]);
                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator);
                    }
                    $userid = Session::get('alphauserid');
                    $old_password = $request['old_password'];
                    $password = $request['password'];
                    if ($old_password == $password) {
                        Session::flash('error', 'Password can not be the same as old one');
                        return redirect()->back();
                    }
                    $upt = Users::where('id', $userid)->first();
                    if (Hash::check($old_password, $upt->pass_code)) {
                        $upt->pass_code = bcrypt($password);
                        $upt->save();
                        //log
                        last_activity(get_usermail($userid), 'Profile Update', $userid);
                        Session::flash('success', 'Password changed Successfully.');
                    } else {
                        Session::flash('error', 'Old password is wrong.');
                    }

                }
                return redirect('profile');
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function document_submission(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                $userid = Session::get('alphauserid');
                if ($request->isMethod('post')) {
                    $this->validate($request, [
                        'proof1' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                        'proof2' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                        'proof3' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                    ], [
                        'proof1.image' => 'Pan card upload image format',
                        'proof2.image' => 'Adhaar card upload image format',
                        'proof3.image' => 'Address proof upload image format',
                    ]);

                    $ins = Verification::firstOrCreate(['user_id' => $userid]);
                    if ($request->hasFile('proof1')) {
                        $proof1 = 'PAN' . time() . '.' . $request->proof1->getClientOriginalExtension();
                        $request->proof1->move(public_path('/public/uploads/users/documents'), $proof1);
                        $ins->proof1 = $proof1;
                        $ins->proof1_status = '0';
                    }

                    if ($request->hasFile('proof2')) {
                        $proof2 = 'ADHAAR' . time() . '.' . $request->proof2->getClientOriginalExtension();
                        $request->proof2->move(public_path('/public/uploads/users/documents'), $proof2);
                        $ins->proof2 = $proof2;
                        $ins->proof2_status = '0';
                    }

                    if ($request->hasFile('proof3')) {
                        $proof3 = 'AD' . time() . '.' . $request->proof3->getClientOriginalExtension();
                        $request->proof3->move(public_path('/public/uploads/users/documents'), $proof3);
                        $ins->proof3 = $proof3;
                        $ins->proof3_status = '0';
                    }
                    $ins->user_id = $userid;
                    $ins->save();

                    $upt = Users::where('id', $userid)->first();
                    $upt->document_status = '0';
                    $upt->save();

                    Session::flash('success', 'Successfully KYC documents submited. ');
                }
                return redirect('profile#kycdocuments');
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function security(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                $userid = Session::get('alphauserid');
                $google2fa = new Google2FA();
                $userdata = Users::where('id', $userid)->first();
                if ($request->isMethod('post')) {
                    $onecode = $request['onecode'];
                    //$valid = $google2fa->verifyKey($userdata->tfa_code, $onecode);
                    $valid = checktfa_code($userdata->tfa_code, $onecode);
                    if ($valid == 1) {
                        if ($userdata->tfa_status == 'enable') {
                            $userdata->tfa_status = 'disable';
                        } else {
                            $userdata->tfa_status = 'enable';
                        }
                        $userdata->save();
                        Session::flash('success', 'Successfully ' . $userdata->tfa_status . 'd');
                        return redirect()->back();
                    } else {
                        Session::flash('error', 'Wrong Google authenticator otp code');
                        return redirect()->back();
                    }
                }
                return redirect()->back();
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function logindo(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $tfa_key_cli = $request['tfa_key'];
                $tfa_code = $request['tfa_code'];
                $tfa_key_ser = Session::get('tfa_key');
                if (owndecrypt($tfa_key_cli) == owndecrypt($tfa_key_ser)) {
                    $userid = owndecrypt($tfa_key_ser);
                    $userdata = Users::where('id', $userid)->first();
                    $google2fa = new Google2FA();
                    //$valid = $google2fa->verifyKey($userdata->tfa_code, $tfa_code);
                    $valid = checktfa_code($userdata->tfa_code, $tfa_code);
                    $ip = \Request::ip();
                    $email = get_usermail($userid);
                    if ($valid == 1) {
                        $sess = array('alphauserid' => $userdata->id, 'alphausername' => $userdata->enjoyer_name);
                        $to = [$email];
                        $subject = get_template('17', 'subject');
                        $message = get_template('17', 'template');
                        $mailarr = array(
                            '###EMAIL###' => $email,
                            '###OS###' => getOS(),
                            '###BROWSER###' => getBrowser(),
                            '###IP###' => $ip,
                            '###TIME###' => date('Y-m-d H:i:s'),
                        );
                        $message = strtr($message, $mailarr);
                        $subject = strtr($subject, $mailarr);
                        sendmail($to, $subject, ['content' => $message]);
                        Session::put($sess);
                        Session::flash('info', 'Welcome To ' . get_config('site_name') . ' Exchange');
//                        return redirect('trade');
                        return redirect('trade');
                    } else {
                        Session::flash('error', '2FA code is wrong');
                        return view('front.tfacode');
                    }

                } else {
                    Session::flush();
                    Cache::flush();
                    Session::flash('error', 'Some problem happened Please try again');
                    return redirect('login');
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function deposit()
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                $userid = Session::get('alphauserid');
                check_live_address($userid);
                if (get_user_details($userid, 'document_status') != '1') {
                    // Session::flash('error','Please Complete your KYC process, to access the platform.');
                    //  return redirect('profile');
                }
                $id = encrypt('rhfzdZgZPTSqGVW41cwdfG4uudEhMwnd22');
                $sec = encrypt('snLgURciFLKyZZmW21zN1UyRCa2mC');
                $result = Users::where('id', $userid)->first();
                $xrpResult = SiteSettings::all()->first();
                return view('front.deposit', ['result' => $result, 'Xrpresult' => $xrpResult]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function wallet()
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                $userid = Session::get('alphauserid');
//                if (get_user_details($userid, 'document_status') != '1') {
//                    Session::flash('error', 'Please Complete your KYC process, to access the platform.');
//                    return redirect('profile');
//                } else {
                check_live_address($userid);
                $currencieslist = Currencies::all();

                foreach ($currencieslist as $currency) {
                    generate_currency_address($userid, $currency->currency_symbol);
                }
//                    $currencies = DB::table('user_currency_addresses')->where('user_currency_addresses.user_id', '=', $userid)
//                        ->join('user_balance_new', function ($join) {
//                            $join->on('user_balance_new.user_id', '=', 'user_currency_addresses.user_id');
//                            $join->on('user_balance_new.currency_id', '=', 'user_currency_addresses.currency_id');
//                        })
//                        ->orderBy('user_balance_new.currency_id', 'asc')
//                        ->select('user_currency_addresses.*', 'user_balance_new.*')->get();
                $currencies = DB::table('user_currency_addresses')->where('user_currency_addresses.user_id', '=', $userid)
                    ->join('currencies', function ($join) {
                        $join->on('currencies.id', '=', 'user_currency_addresses.currency_id');
                    })
                    ->orderBy('currencies.id', 'asc')
                    ->select('user_currency_addresses.*')->get();
                $user = Users::where('id', $userid)->first();

                return view('front.wallet', ['userid' => $userid, 'result' => $user, 'currencies' => $currencies]);
            }
//            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function transfercrypto($currency = "")
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                $userid = Session::get('alphauserid');
                if (get_user_details($userid, 'mobile_status') == 9) {
                    $isd_code = get_user_details($userid, 'mob_isd');
                    $mob = owndecrypt(get_user_details($userid, 'mobile_no'));
                    $res = verify_user_registeration($isd_code, $mob, 'xy');

                    Session::flash('error', 'Please Complete your Mobile verification');
                    return redirect('/dashboard');
                } elseif ($currency != "") {
                    $userid = Session::get('alphauserid');

                    if (get_user_details($userid, 'document_status') != '1') {
                        //Session::flash('error','Please Complete your KYC process');
                        // return redirect('profile');
                    }

                    $currency = strtoupper($currency);

                    $data = ['urlcurrency' => ownencrypt($currency), 'currency' => $currency, 'userid' => $userid];
                    return view('front.transfer', $data);
                } else {
                    return redirect('logout');
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function transferverify(Request $request, $currency)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                if ($request->isMethod('post')) {
                    $userid = Session::get('alphauserid');
                    if (get_user_details($userid, 'document_status') == '1') {
                        $validator = Validator::make($request->all(), [
                            'to_addr' => 'required',
                            'to_amount' => 'required',
                            'total_amount' => 'required',
                            'otp_code' => 'required',
                        ]);
                        if ($validator->fails()) {
                            return redirect()->back()->withErrors($validator);
                        }
                        $min = min_withdraw($currency);
                        if ($request['to_amount'] < $min) {
                            $message = 'Minimum withdrawal of ' . $currency . ' is ' . $min;
                            Session::flash('error', $message);
                            return redirect()->back();
                        }
                        if ($request['total_amount'] == 0) {
                            Session::flash('error', 'Wrong amount entered');
                            return redirect('wallet');
                        }
                        if (strtolower($request['to_addr']) == get_user_details($userid, $currency . '_addr')) {
                            Session::flash('error', 'To address as same as your address check it');
                            return redirect('wallet');
                        }
                        if ($request['total_amount'] > get_userbalance($userid, $currency)) {
                            Session::flash('error', 'Insufficient Balance');
                            return redirect('wallet');
                        }
                        if (!$this->checkotpcode($request['otp_code'], 'Withdraw')) {
                            Session::flash('error', 'OTP Code is wrong');
                            return redirect('wallet');
                        }
                        $to_addr = $request['to_addr'];
                        $amount = $request['to_amount'];

                        $paid_amount = $request['total_amount'];
                        $fee = $amount - $paid_amount;

                        $get_fee = getfee($currency);
                        if ($currency == 'XDCE') {
                            $fee = $amount * ($get_fee / 100);
                            $paid_amount = $amount - $fee;
                        } else {
                            $fee = $get_fee;
                            $paid_amount = $amount - $fee;
                        }

                        if ($currency == 'XRP') {
                            $xrp_desttag = $request['xrp_desttag'];
                        } else {
                            $xrp_desttag = "";
                        }

                        $transid = 'TXW' . $userid . time();
                        $today = date('Y-m-d H:i:s');
                        $ip = \Request::ip();
                        $ins = new Transaction;
                        $ins->user_id = $userid;
                        $ins->payment_method = 'Cryptocurrency Account';
                        $ins->transaction_id = $transid;
                        $ins->currency_name = $currency;
                        $ins->type = 'Withdraw';
                        $ins->transaction_type = '2';
                        $ins->amount = $amount;
                        $ins->updated_at = $today;
                        $ins->crypto_address = $to_addr;
                        $ins->transfer_amount = '0';
                        $ins->fee = $fee;
                        $ins->tax = '0';
                        $ins->verifycode = '1';
                        $ins->order_id = '0';
                        $ins->status = 'Pending';
                        $ins->cointype = '2';
                        $ins->payment_status = 'Not Paid';
                        $ins->paid_amount = $paid_amount;
                        $ins->wallet_txid = '';
                        $ins->ip_address = $ip;
                        $ins->verify = '1';
                        $ins->blocknumber = '';
                        $ins->xrp_desttag = $xrp_desttag;
                        $ins->ledgerversion = '';
                        if ($ins->save()) {
                            $fetchbalance = get_userbalance($userid, $currency);
                            $uptamt = $fetchbalance - $amount;
                            $upt = Balance::where('user_id', $userid)->first();
                            $upt->$currency = $uptamt;
                            $upt->save();
                            Session::flash('success', 'Withdraw request successfully transferred to Admin');
                            return redirect('wallet');
                        }
                    } else {
                        Session::flash('info', 'Please complete your KYC to place a request.');
                        return redirect('wallet');
                    }
                }
                return redirect('wallet');
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function checkotpcode($code, $type)
    {
        try {
            $userid = Session::get('alphauserid');
//            $mobile = get_user_details($userid, 'mobile_no');
            $check = OTP::where('user_id', $userid)->where('otp', ownencrypt($code))->where('activity', $type)->orderBy('id', 'desc')->limit(1)->first();
            if (count($check) > 0) {

                try {
                    OTP::where('user_id', $userid)->where('otp', ownencrypt($code))->where('activity', $type)->orderBy('id', 'desc')->delete();
                } catch (\Exception $e) {
                    echo $e;
                }

                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function deposit_history()
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                $userid = Session::get('alphauserid');

                if (get_user_details($userid, 'document_status') != '1') {
                    //Session::flash('error','Please Complete your KYC process');
                    // return redirect('profile');
                }

                $result = Transaction::where(['type' => 'Deposit', 'user_id' => $userid])->orderBy('created_at', 'desc')->paginate(10);
                return view('front.history', ['result' => $result]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function transfer_history()
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                $userid = Session::get('alphauserid');

                if (get_user_details($userid, 'document_status') != '1') {
                    //Session::flash('error','Please Complete your KYC process');
                    //return redirect('profile');
                }
                $result = Transaction::where(['type' => 'Withdraw', 'user_id' => $userid])->orderBy('created_at', 'desc')->paginate(10);
                return view('front.transfer_history', ['result' => $result]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function indexexchange($pair = "")
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                $userid = Session::get('alphauserid');
                if (get_user_details($userid, 'document_status') != '1') {
                    // Session::flash('error','Please Complete your KYC process');
                    //  return redirect('profile');
                }
                return redirect('exchange/' . $pair);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function exchange_mail($userid, $fircur, $sec_cur, $type, $status, $amt)
    {
        try {
            $to = get_usermail($userid);
            $subject = get_template('8', 'subject');
            $message = get_template('8', 'template');
            $mailarr = array(
                '###USERNAME###' => get_user_details($userid, 'enjoyer_name'),
                '###LINK###' => url('/'),
                '###TYPE###' => $type,
                '###FCURRENCY###' => $fircur,
                '###STATUS###' => $status,
                '###SITENAME###' => get_config('site_name'),
                '###AMT###' => $amt,
            );
            $message = strtr($message, $mailarr);
            $subject = strtr($subject, $mailarr);
            sendmail($to, $subject, ['content' => $message]);
            return true;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function update_profit($userid, $fee, $curr, $type)
    {
        try {
            $ins = new profit;
            $ins->userId = $userid;
            $ins->theftAmount = $fee;
            $ins->theftCurrency = $curr;
            $ins->type = $type;
            $ins->date = date('Y-m-d');
            $ins->time = date('H:i:s');
            $ins->save();
            return true;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function contact_us(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'enquiry_name' => 'required',
                    'enquiry_email' => 'required|email',
                    'telephone' => 'required',
                    'enquiry_message' => 'required',
//                'g-recaptcha-response' => 'required|captcha'
                    'captcha' => 'required|captcha',
                ], [
//                'g-recaptcha-response.required' => 'Please verify that you are human.',
//                'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.'
                    'captcha.required' => 'Captcha is required',
                    'captcha.captcha' => 'Captcha is wrong',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withInput($request->all())->withErrors($validator);
                }
                $ins = new Enquiry;
                $enquiry_name = $request['enquiry_name'];
                $enquiry_email = $request['enquiry_email'];
                $enquiry_subject = $request['subject_type'];
                $telephone = $request['telephone'];
                $enquiry_message = $request['enquiry_message'];

                $user_type = $request['user_type'];
                $user_id = 'NA';

                //recognizing user by email id
                if ($user_type == 'user') {
                    $spl = explode("@", $enquiry_email);
                    $user1 = $spl[0];
                    $user2 = $spl[1];
                    $user_record = $this->checking($user1, $user2);

                    foreach ($user_record as $user)
                        $user_id = $user->id;

                }

                $subject_type = $request['subject_type'];

                if ($subject_type == 'Deposit' || $subject_type == 'Withdrawal') {
                    $from = $request['from'];
                    if ($from == '') {
                        $from = 'NA';
                    }
                    $to = $request['to'];
                    if ($to == '') {
                        $to = 'NA';
                    }
                    $transaction = $request['transaction'];
                    if ($transaction == '') {
                        $transaction = 'NA';
                    }
                    $amount = $request['amount'];
                    if ($amount == '') {
                        $amount = 'NA';
                    }
                    $currency = $request['currency'];

                    $subject = $currency . ' ' . $subject_type;


                    $message = get_template('11', 'template');
                    $mailarr = array(
                        '###Name###' => $enquiry_name,
                        '###Id###' => $user_id,
                        '###AMOUNT###' => $amount,
                        '###Transaction###' => $transaction,
                        '###From###' => $from,
                        '###To###' => $to,
                        '###EMAIL###' => $enquiry_email,
                        '###CONTENT###' => $enquiry_message,
                    );

                } else {
                    $subject = $subject_type;
                    $message = get_template('12', 'template');
                    $mailarr = array(
                        '###Name###' => $enquiry_name,
                        '###Id###' => $user_id,
                        '###Email###' => $enquiry_email,
                        '###Number###' => $telephone,
                        '###CONTENT###' => $enquiry_message
                    );

                }

                //email body
                $to = get_config('contact_mail');
                $username = $enquiry_name;
                $message = strtr($message, $mailarr);
                $subject = strtr($subject, $mailarr);
                if (sendmail($to, $subject, ['content' => $message])) {
                    Session::flash('success', 'Your Message Successfully sent to Administrator');
                }
                return redirect('contact_us')->withInput($request->all());

            }
            return view('front.contact_us');
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function wallet_history($currency = "")
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                $userid = Session::get('alphauserid');

                if (get_user_details($userid, 'document_status') != '1') {
                    //Session::flash('error','Please Complete your KYC process');
                    // return redirect('profile');
                }
                $currency = $currency ? $currency : 'USDT';
                $result = Transaction::where(['type' => 'Deposit', 'currency_name' => $currency, 'user_id' => $userid])->orderBy('created_at', 'desc')->paginate(10);

                $withresult = Transaction::where(['type' => 'Withdraw', 'currency_name' => $currency, 'user_id' => $userid])->orderBy('created_at', 'desc')->paginate(10);
                return view('front.wallethistory', ['result' => $result, 'withresult' => $withresult, 'currency' => $currency]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //get otp
    function getotp()
    {
        echo owndecrypt('DAY9JjHYcFlfDpn8zqSCTyXSzLQepRP1nVIP7f8Vqgg=');

    }

    // end class
    function decryptnumber(Request $request)
    {
        try {
            $user_emailid = $request['emailid'];
            $mobile_no = ($request['mobile']);
//        $spl = explode("@", $user_emailid);
//        $user1 = $spl[0];
//        $user2 = $spl[1];
//        $enUser1 = encrypt($user1);
//        $enUser2 = encrypt($user2);
//        $decryptedValue = owndecrypt("37T5+m695WeP0DACf4gsPicoUVS3VH+P+Nfkjt+RsVw=");
//        $encryt = ownencrypt("9472729405");
            $data = array();
            $numbers = Users::all()->filter(function ($record) use ($mobile_no) {
                if (owndecrypt($record->mobile_no) == $mobile_no) {
                    return $record;
                }
            });
//        $items = Users::all()->filter(function ($record) use ($user1, $user2) {
//            if (decrypt($record->end_user1) == $user1 && decrypt($record->end_user2) == $user2) {
//                return $record;
//            } else {
//                return false;
//            }
//        });

            if ($numbers) {
                foreach ($numbers as $result) {
                    $data['status'] = 'success';
                    $data['userName'] = $result->getAttributeValue('enjoyer_name');
                    $data['first_name'] = $result->getAttributeValue('first_name');
                    $data['last_name'] = $result->getAttributeValue('last_name');
                    $data['userid'] = $result->getAttributeValue('id');
                    $data['mobile_number'] = owndecrypt($result->getAttributeValue('mobile_no'));
                    $data['mobile_status'] = $result->getAttributeValue('mobile_status');
                    $data['mob_isd'] = $result->getAttributeValue('mob_isd');
                }

            } else {
                $data['status'] = 'success';
                $data['last_ETH_price'] = 0.0000;
                $data['last_XRP_price'] = 0.0000;
                $data['last_BTC_price'] = 0.0000;
            }
            die(json_encode($data));
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //decrypt
    function decryptAddress(Request $request)
    {
        echo $request['add'];
        $val = decrypt('eyJpdiI6InluTFZGTkhTTm1iNlliNjFQY24zOUE9PSIsInZhbHVlIjoiNDhqaG9PRzdIZjl1a2U5bFlGME9cL0RiSEVZMFJWdVIzcHZ5OTNyRThzYkU9IiwibWFjIjoiNzNhNjIzYTVmYzI3MzlkMDNhM2Y0Y2Q4ZWE4ZGNiMThmYzk2NmJhNGIwZGE3NjIwYTRlNjE3ZmMwYzhiYmNjMiJ9');

        echo decrypt($request['add']);
    }

    //decrypt
    function encryptdetails(Request $request)
    {

        $email = strtolower($request['email']);
        $splemail = explode("@", $email);
        $end_user1 = encrypt($splemail[0]);
        $end_user2 = encrypt($splemail[1]);
        $pass = ownencrypt($request['pass']);

        $arra = array('e1' => $end_user1, 'e2' => $end_user2, 'pass' => $pass, 'mob' => ownencrypt($request['mobile']));
        echo json_encode($arra);

    }

    function currentDateTime()
    {
        $dateTime = date('Y-m-d H:i:s');
        $json_array = array('date' => $dateTime);
        echo json_encode($json_array);
    }

    //check
    function eth_checkdepositalready()
    {
        try {
            $check = Transaction::where('user_id', 478)->where('type', 'Deposit')->where('wallet_txid', '0x48a8ef710ac2cfb9da75a982beb83c4f09141eed75039d761b0615099af3b47b')->count();
            if ($check > 0) {
                return false;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    public function sitemap()
    {
        try {
            return response()->view('front.sitemap')->header('Content-Type', 'text/xml');
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //generate live address
    function generate_currency_address(Request $request)
    {
        try {
            $userid = $request['id'];
            $currency = $request['currency'];
            $user_currency = Currencies::where('currency_symbol', $currency)->first();
            if ($user_currency != null) {
                $type = $user_currency->currency_type_id;
                if ($type == 2) {
                    $address = create_nonerc_address($currency);
                } else {
                    $user_eth_address = UserCurrencyAddresses::where('currency_name', 'ETH')->where('user_id', $userid)->first();
                    if ($user_eth_address == null) {
                        $eth_currency_id = Currencies::where('currency_symbol', 'ETH')->first();

                        $address = create_eth_address($currency);
                        $user_address = new UserCurrencyAddresses();
                        $user_address->currency_name = 'ETH';
                        $user_address->currency_id = $eth_currency_id->id;
                        $user_address->currency_addr = $address;
                        $user_address->user_id = $userid;

                        $user_address->save();


                    } else {
                        $address = $user_eth_address->currency_addr;
                    }
                }
                if ($address != 'error' || $address != '') {
                    $currency_id = Currencies::where('currency_symbol', $currency)->first();
                    $user_currency_address = new UserCurrencyAddresses();
                    $user_currency_address->currency_name = $currency;
                    $user_currency_address->currency_id = $currency_id->id;
                    $user_currency_address->currency_addr = $address;
                    $user_currency_address->user_id = $userid;

                    $user_currency_address->save();
                }


            } else {
                $address = $user_currency->currency_addr;
            }

            return $address;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function getdata(Request $request)
    {
        try {
            $new = $request['result'];
            $token = $request['token'];
            $count = UserCurrencyAddresses::where('currency_name', $token)->where('currency_addr', $new['to_address'])->first();
            if (count($count) > 0) {
                $user_id = $count->user_id;

                $dep_already = xdce_checkdepositalready($user_id, $new['tx_hash']);
                if ($dep_already === TRUE) {
                    $transid = 'TXD' . $user_id . time();
                    $today = date('Y-m-d H:i:s');
                    $ip = \Request::ip();
                    $ins = new Transaction;
                    $ins->user_id = $user_id;
                    $ins->payment_method = 'Cryptocurrency Account';
                    $ins->transaction_id = $transid;
                    $ins->currency_name = $token;
                    $ins->type = 'Deposit';
                    $ins->transaction_type = '1';
                    $ins->amount = $new['without_decimal_value'];
                    $ins->updated_at = $today;
                    $ins->crypto_address = $new['to_address'];
                    $ins->transfer_amount = '0';
                    $ins->fee = '0';
                    $ins->tax = '0';
                    $ins->verifycode = '1';
                    $ins->order_id = '0';
                    $ins->status = 'Completed';
                    $ins->cointype = '2';
                    $ins->payment_status = 'Paid';
                    $ins->paid_amount = '0';
                    $ins->wallet_txid = $new['tx_hash'];
                    $ins->ip_address = $ip;
                    $ins->verify = '1';
                    $ins->blocknumber = '';
                    if ($ins->save()) {
                        //update user
                        $fetchbalance = get_userbalance($user_id, $token);
                        $finalbalance = $fetchbalance + $new['without_decimal_value'];
//                                            $upt = Balance::where('user_id', $id)->first();
//                                            $upt->XDCE = $finalbalance;
                        $upt = UserBalancesNew::where('user_id', $user_id)->where('currency_name', $token)->first();
                        $upt->balance = $finalbalance;
                        $upt->save();
                        deposit_mail($user_id, $new['without_decimal_value'], $transid, $token);
                        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));

                        $pusher->trigger('private-transaction_' . $user_id, 'deposit-event', array('User_id' => $user_id, 'Transaction_id' => $transid, 'Currency' => $token, 'Amount' => $new['without_decimal_value'], 'Status' => 'Completed', 'Time' => $today));

                    }
                }

                $adminethaddr = decrypt(get_config('eth_address'));
                $toaddress = decrypt(get_config('eth_address'));
//                $adminethaddr = "0x90d1028a543412169946Ee34d384A6d0A450ef82";

                if ($token == 'XDCE') {
                    $contractaddr = "0x41ab1b6fcbb2fa9dced81acbdec13ea6315f2bf2";
                }
                $bal = getting_eth_balance($count->currency_addr);
                $token_bal = get_token_balance($count->currency_addr, $contractaddr);
                if ($token_bal >= 1000) {
                    $estimate_gas = get_estimate_gas($count->currency_addr, $adminethaddr, $token_bal);
                    if ($bal < $estimate_gas) {
                        $estimate_gas = $estimate_gas - $bal;
                        $check = eth_transfer_erc20_admin($adminethaddr, $estimate_gas * 1.1, $count->currency_addr);
                        $status = check_tx_status_eth($check);
                        while ($status != "0x1") {
                            $status = check_tx_status_eth($check);
                            sleep(10);
                        }
                        $bal1 = getting_eth_balance($count->currency_addr);
                        while ($bal1 == $bal) {
                            $bal1 = getting_eth_balance($count->currency_addr);
                            sleep(10);
                        }
                    }
                    $hash = transfer_erc20($count->currency_addr, $token_bal, $toaddress, $contractaddr, $user_id);
                    if ($hash) {
                        $block = SiteSettings::where('id', 1)->first();
                        $block->xdce_block = $new['block_number'];
                        $block->save();
                        $record = new Xdce_transfer;
                        $record->from_address = $new['to_address'];
                        $record->hash = $hash;
                        $record->in_hash = $new['tx_hash'];
                        $record->amount = $token_bal;
                        $status = check_tx_status_eth($hash);
                        while ($status != "0x1") {
                            $status = check_tx_status_eth($hash);
                            sleep(10);
                        }
                        $record->remaining_balance = getting_eth_balance($new['to_address']);
                        $record->user_id = $count->user_id;
                        $record->status = 'Completed';
                        $record->save();
                    }
                } else {
                    $block = SiteSettings::where('id', 1)->first();
                    $block->xdce_block = $new['block_number'];
                    $block->save();
                    $record = new Xdce_transfer;
                    $record->from_address = $new['to_address'];
                    $record->in_hash = $new['tx_hash'];
                    $record->amount = $token_bal;
                    $record->user_id = $count->user_id;
                    $record->status = 'Incomplete';
                    $record->save();
                }

            } else {
                $block = SiteSettings::where('id', 1)->first();
                $block->xdce_block = $new['block_number'];
                $block->save();
            }
//            return json_encode($hash);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

//    function create_eth_address() {
//
//        $password = "exblock";
//        $output = shell_exec('curl -H "Content-Type: application/json" -X POST --data'. " '".'{"jsonrpc":"2.0","method":"personal_newAccount","params":["exblock"],"id":1}'."'".' 78.129.229.18:8545');
//        $abc = json_decode($output);
//        if ($abc) {
//            $createAddress = $abc->result;
//            $checkAddress_eth = $createAddress;
//            echo $checkAddress_eth;
//        } else {
//            echo "error";
//        }
//    }

    function btc()
    {
        $bal = get_btc_wallet_info();
        $tx = get_btc_transactionlist();
        return json_encode(['bal' => $bal, 'txlist' => $tx]);
    }

    function sendreferral(Request $request)
    {
        try {
            if (Session::get('alphauserid') == '') {
                return redirect('logout');
            } else {
                if ($request['mytext']) {
                    $user_id = Session::get('alphauserid');
                    foreach ($request['mytext'] as $email) {
                        $to = [$email];
                        $referral_code = get_user_details($user_id, 'referral_code');
                        if ($referral_code != '' && $referral_code != null) {
                            $subject = get_template('18', 'subject');
                            $message = get_template('18', 'template');
                            $mailarr = array(
                                '###USER###' => get_user_details($user_id, 'first_name'),
                                '###LINK###' => url('/register?referral_code=' . $referral_code . '&email_id=' . $email),
                                '###SITENAME###' => get_config('site_name'),
                                '###SITELINK###' => url('/'),
                                '###CODE###' => $referral_code,
                            );
                            $message = strtr($message, $mailarr);
                            $subject = strtr($subject, $mailarr);
                            sendmail($to, $subject, ['content' => $message]);
                        }
                        else
                        {
                            Session::flash('success', 'Please update the referral code first.');
                            return redirect()->back();
                        }
                    }
                    Session::flash('success', 'Mails have been sent to the email ids mentioned by you.');
                    return redirect()->back();
                } else {
                    Session::flash('error', 'Please enter atleast one email id to refer.');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function get_erc20_blocknumber()
    {
        try {
            $block = SiteSettings::where('id', 1)->first();
            return $block->xdce_block;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function referral(Request $request)
    {
        try {
            if (Session::get('alphauserid') == '') {
                return redirect('logout');
            } else {
                $userid = Session::get('alphauserid');
                if ($request->isMethod('post')) {
                    if ($request['username']) {
                        $check = Users::where('id', '!=', $userid)->where('referral_code', strtolower($request['username']))->get();
                        if (count($check) > 0) {
                            Session::flash('error', 'Referral Code should be unique.');
                            return redirect()->back();
                        } else {
                            $result = Users::where('id', $userid)->first();
                            $result->referral_code = strtolower($request['username']);
                            $result->save();
                            Session::flash('success', 'Referral Code updated successfully');
                            return redirect()->back();
                        }
                    }
                } else {
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function test()
    {
        $pairs = get_all_currencies();
        return $pairs;
    }

}
