<?php

use App\model\Admin;
use App\model\Balance;
use App\model\Country;
use App\model\Currencies;
use App\model\Marketprice;
use App\model\Metacontent;
use App\model\SiteSettings;
use App\model\Trade;
use App\model\Transaction;
use App\model\Useractivity;
use App\model\Users;
use App\model\Verification;
use App\model\Tradingfee;
use App\model\Transactionfee;
use App\model\ico;
use App\model\UserCurrencyAddresses;
use App\model\MinWithdrawal;
use App\model\UserBalancesNew;
use App\model\Pair;
use App\model\Mintrade;

//use Mail;

function get_adminprofile($key)
{
    try {
        $id = Session::get('alpha_id');
        $result = Admin::where('id', $id)->first();
        return $result->$key;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_superadmin($key)
{
    try {
        $result = Admin::where('id', 1)->first();
        return $result->$key;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_config($key)
{
    try {
        $result = SiteSettings::where('id', 1)->first();
        return $result->$key;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function admin_class($key = "")
{
    try {
        $uri = Request::segment(2);
        if ($uri == $key) {
            return "active";
        } else {
            return "";
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function sendmail($emails, $subject, $data = "")
{
    try {
        Mail::send(['html' => 'emails.template'], $data, function ($message) use ($emails, $subject) {
            $message->to($emails)->from(get_config('contact_mail'), get_config('site_name'))->subject($subject);
            $message->replyTo(get_config('contact_mail'));
        });
        return true;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}


function get_template($id, $key)
{
    try {
        $result = DB::table('email_template')->where('id', $id)->first();
        return $result->$key;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }

}

function get_usermail($user_id)
{
    try {
        $result = Users::where('id', $user_id)->first();
        return decrypt($result->end_user1) . '@' . decrypt($result->end_user2);
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return '';
    }
}

function get_user_verified($user_id)
{
    try {
        $result = Users::where('id', $user_id)->first();
        $user_verified = $result->user_verified;

        if ($user_verified == 1) {
            return 'Verified';
        } else {
            return 'Unverified';
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_userbalance($user_id, $key)
{
    try {
//        $result = DB::table('userbalance')->where('user_id', $user_id)->first();
//        return $result->$key;
        $result = UserBalancesNew::where('user_id', $user_id)->where('currency_name', $key)->first();
        if ($result != null) {
            return $result->balance;
        } else {
            $currency_id = Currencies::where('currency_symbol', $key)->first();
            if ($currency_id != null) {
                $userbalance = new UserBalancesNew();
                $userbalance->user_id = $user_id;
                $userbalance->currency_name = $key;
                $userbalance->currency_id = $currency_id->id;
                $userbalance->balance = 0;
                $userbalance->save();
                return 0;
            }
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return 0;
    }
}

function get_user_intradebalance($user_id, $key)
{
    try {
        $buy_total = Trade::where('user_id', $user_id)->where('type', 'Buy')->where('secondCurrency', $key)->whereIn('status', ['active', 'partially'])->sum('total');
        $sell_total = Trade::where('user_id', $user_id)->where('type', 'Sell')->where('firstCurrency', $key)->whereIn('status', ['active', 'partially'])->sum('updated_qty');
        $result = $buy_total + $sell_total;
        return $result;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_user_buy($user_id,$curr)
{
    try{
        $buy_total = Trade::where('user_id', $user_id)->where('type', 'Buy')->where('secondCurrency', $curr)->where('status', 'completed')->sum('total');
        return $buy_total;
    }
    catch (\Exception $e)
    {
        \Log::error([$e->getFile(),$e->getLine(),$e->getMessage()]);
        return 0;
    }
}

function get_user_sell($user_id,$curr)
{
    try{
        $sell_total = Trade::where('user_id', $user_id)->where('type', 'Sell')->where('firstCurrency', $curr)->where('status', 'completed')->sum('updated_qty');
        return $sell_total;
    }
    catch (\Exception $e)
    {
        \Log::error([$e->getFile(),$e->getLine(),$e->getMessage()]);
        return 0;
    }
}

function get_total_intradebalance($curr)
{
    try {
        $pending_transaction = Trade::query()->whereIn('trade_order.status', ['partially', 'active'])->get();
        $pending_transaction_buy = $pending_transaction->where('type', 'Buy')->where('secondCurrency', $curr)->sum('total');
        $pending_transaction_sell = $pending_transaction->where('type', 'Sell')->where('firstCurrency', $curr)->sum('updated_qty');
        $intrade_total = $pending_transaction_buy + $pending_transaction_sell;
        return number_format($intrade_total, '8', '.', '');
    } catch (\Exception $e) {
        \Log::error([$e->getFile(), $e->getLine(), $e->getMessage()]);
        return '0';
    }
}

function get_cointype($id = "")
{
    if ($id == 1) {
        return "BTC";
    } elseif ($id == 2) {
        return "ETH";
    } elseif ($id == 3) {
        return "XRP";
    }
}

function dashboard_usercount()
{
    try {
        return Users::count();
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function dashboard_totaltrans()
{
    try {
        return Trade::count();
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function dashbard_totalbtcprofit()
{
    try {
        $sum = DB::table('coin_theft')->where('theftCurrency', 'BTC')->sum('theftAmount');
        return $sum;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function dashbard_totalkyc()
{
    try {
        return Verification::count();
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function dashboard_ico_listing_count()
{
    try {
        return ico::where('status', 'Pending')->count();
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function dasboard_chart_btc($currency)
{
    try {
        /*$result=DB::table('coin_theft')->select('updated_at','theftAmount')->where('theftCurrency',$currency)->get();*/
        $daily = "SELECT DATE(date) as dateval, SUM(theftAmount) as total FROM EXCHANGE_coin_theft where theftCurrency='$currency' GROUP BY YEAR(date), MONTH(date), DATE(date)";
        $result = DB::select(DB::raw($daily));
        $arr = "";
        if ($result) {
            foreach ($result as $val) {
                $millsec = strtotime($val->dateval) * 1000;
                $arr .= "[" . $millsec . "," . $val->total . "]";
                $arr .= ",";
            }
            echo $arr;
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function getBrowser()
{
    try {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $browser = "Unknown Browser";

        $browser_array = array(
            '/msie/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/edge/i' => 'Edge',
            '/opera/i' => 'Opera',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i' => 'Handheld Browser',
        );

        foreach ($browser_array as $regex => $value) {

            if (preg_match($regex, $user_agent)) {
                $browser = $value;
            }

        }

        return $browser;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function getOS()
{
    try {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $os_platform = "Unknown OS Platform";

        $os_array = array(
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile',
        );

        foreach ($os_array as $regex => $value) {

            if (preg_match($regex, $user_agent)) {
                $os_platform = $value;
            }

        }

        return $os_platform;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function last_activity($email, $activity, $id = 0)
{
    try {
        //$ip_address = $_SERVER['REMOTE_ADDR'];
        $ip = \Request::ip();
        $ins = [
            'user_email' => $email,
            'ip_address' => $ip,
            'activity' => $activity,
            'browser_name' => getBrowser(),
            'os_name' => getOS(),
            'user_id' => $id,
        ];
        if ($activity == 'Login') {
            $getdatetime = $today = date('Y-m-d H:i:s');
            //send mail to user
            $to = $email;
            $subject = get_template('17', 'subject');
            $message = get_template('17', 'template');
            $mailarr = array(
                '###SITENAME###' => get_config('site_name'),
                '###EMAIL###' => $email,
                '###NAME###' => get_user_details($id, 'enjoyer_name'),
                '###IP###' => $ip,
                '###TIME###' => $getdatetime,
                '###BROWSER###' => getBrowser(),
                '###OS###' => getOS(),
            );
            $message = strtr($message, $mailarr);
            $subject = strtr($subject, $mailarr);
//        sendmail($to, $subject, ['content' => $message]);
        }
        Useractivity::insert($ins);
        return true;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function front_class($key = "")
{
    try {
        $uri = Request::segment(1);
        if ($uri == $key) {
            return "active";
        } else {
            return "";
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function second_class($key = "")
{
    try {
        $uri = Request::segment(2);
        if ($uri == $key) {
            return "active";
        } else {
            return "";
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function second_selected($key = "")
{
    try {
        $uri = Request::segment(2);
        if ($uri == $key) {
            return "selected";
        } else {
            return "";
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_otpnumber($user_id, $isd, $mobile, $activity)
{
    try {
        $rand = mt_rand(000000, 999999);
        $check = \App\model\OTP::where('user_id', $user_id)->count();
        if ($check > 0) {
            \App\model\OTP::where('user_id', $user_id)->delete();
        }
        $ins = ['user_id' => $user_id, 'isd' => $isd, 'mobile_no' => ownencrypt($mobile), 'otp' => ownencrypt($rand), 'activity' => $activity];
        DB::table('otp')->insert($ins);
        return $rand;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_user_details($id, $key)
{
    try {
        $res = Users::where('id', $id)->first();
        return $res->$key;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return '';
    }
}

function get_fee_settings($key)
{
    try {
        $res = DB::table('fee_settings')->where('id', '1')->first();
        return $res->$key;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

//for ico mail
function ico_mail($userid, $amount, $txid, $currency)
{
    try {
        $to = get_usermail($userid);
        $username = get_user_details($userid, 'enjoyer_name');
        $subject = get_template('7', 'subject');
        $message = get_template('7', 'template');
        $mailarr = array(
            '###USERNAME###' => $username,
            '###CURRENCY###' => $currency,
            '###AMOUNT###' => $amount,
            '###TXD###' => $txid,
            '###STATUS###' => 'Completed',
            '###SITENAME###' => get_config('site_name'),
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

//get trade fee
function get_trade_fee($type, $pair)
{
    try {
        if ($type == 'Buy') {
            $trade_fee = Tradingfee::where('pair', $pair)->first();
            $buy_fee = $trade_fee->buy_fee;
            return $buy_fee;
        } else if ($type == 'Sell') {
            $trade_fee = Tradingfee::where('pair', $pair)->first();
            $sell_fee = $trade_fee->sell_fee;
            return $sell_fee;
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function send_sms($to, $text)
{
    try {
        $AUTH_ID = "MAOTZLZGI0ODGXZGM4MZ";
        $AUTH_TOKEN = "NDA5ZmJkY2NiODg3N2QyMGJjNzliOWNhYjIxMTZi";
        //$fromnum = "+919930403019";
//	$fromnum = "+6588769089";
        $fromnum = "+18604304028";
        $url = 'https://api.plivo.com/v1/Account/' . $AUTH_ID . '/Message/';
        $data = array("src" => "$fromnum", "dst" => "$to", "text" => "$text");
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_USERPWD, $AUTH_ID . ":" . $AUTH_TOKEN);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        /*echo "<pre>";
            print_r(json_decode($response));*/

        return true;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_country_name($country_id)
{
    try {
        $res = Country::where('id', $country_id)->first();
        return $res->nicename;
    } catch (\Exception $e) {
        return "";
    }
}

function ownencrypt($q)
{
    try {
        $cryptKey = 'xeahpla';
        $qEncoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), $q, MCRYPT_MODE_CBC, md5(md5($cryptKey))));
        return ($qEncoded);
        return $q;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function owndecrypt($q)
{
    try {
        $cryptKey = 'xeahpla';
        $qDecoded = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), base64_decode($q), MCRYPT_MODE_CBC, md5(md5($cryptKey))), "\0");
        return ($qDecoded);
        return $q;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_document_status($user_id, $key)
{
    try {
        $res = Verification::where('user_id', $user_id)->first();
        if ($res) {
            return $res->$key;
        } else {
            return "";
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function getDataURI($image, $mime = '')
{
    try {
        return 'data: ' . (function_exists('mime_content_type') ? mime_content_type($image) : $mime) . ';base64,' . base64_encode(file_get_contents($image));
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function checktfa_code($secret, $onecode)
{
    try {
        include app_path() . '/Googleauthenticator.php';
        $ga = new \Googleauthenticator();
        if ($ga->verifyCode($secret, $onecode, 3)) {
            return "1";
        } else {
            return "0";
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_estusd_price($currency, $amt)
{
    try {
        $res = Marketprice::where('currency', $currency)->first();
        $getusd = $res->USD;
        $retres = $getusd * $amt;
        return (float)$retres;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return 0;
    }
}

function get_live_estusd_price($cur)
{
    try {
//            $url = "https://api.bitfinex.com/v2/ticker/" . $cur;
//            $result = file_get_contents($url);
//            $res = json_decode($result);
//            $price = $res[6];
//            return $price;
        $url = "https://api.coinmarketcap.com/v1/ticker/" . $cur;
        $result = file_get_contents($url);
        $res = json_decode($result);
        $price = $res[0]->price_usd;
        return $price;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_user_address($userid, $currency)
{
    try {
        $addr = UserCurrencyAddresses::where('user_id', $userid)->where('currency_name', $currency)->first();
        if (count($addr) > 0)
            return $addr->currency_addr;
        else
            return "";
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

//generate live address
function generate_currency_address($userid, $currency)
{
    try {
        $user_currency = Currencies::where('currency_symbol', $currency)->first();
        if ($user_currency != null) {
            $type = $user_currency->currency_type_id;
            if ($type == 2) {
                $user_curr_address = UserCurrencyAddresses::where('currency_name', $currency)->where('user_id', $userid)->first();
                if ($user_curr_address == null) {
                    $address = create_nonerc_address($currency);
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
                    $address = $user_curr_address->currency_addr;
                }

            } else {
                $user_eth_address = UserCurrencyAddresses::where('currency_name', 'ETH')->where('user_id', $userid)->first();
                if ($user_eth_address == null) {
                    $eth_currency_id = Currencies::where('currency_symbol', 'ETH')->first();

                    $address = create_eth_address($currency);
                    if ($address != '' || $address != 'Error') {
                        $user_address = new UserCurrencyAddresses();
                        $user_address->currency_name = 'ETH';
                        $user_address->currency_id = $eth_currency_id->id;
                        $user_address->currency_addr = $address;
                        $user_address->user_id = $userid;

                        $user_address->save();
                    }

                } else {
                    $address = $user_eth_address->currency_addr;
                }
            }

        } else {
            $address = $user_currency->currency_addr;
        }

        return $address;
    } catch (\Exception $exception) {
        return 'error';
    }
}

//create address
function create_nonerc_address($currency)
{
    try {
        $address = create_usdt_address($currency);
        return $address;
    } catch (\Exception $exception) {
        return 'error';
    }
}

function check_live_address($userid)
{
    try {
        $eth = get_user_address($userid, 'ETH');
        $btc = get_user_address($userid, 'BTC');
        $xrp_tag = get_user_address($userid, 'XRP');
        $usdt = get_user_address($userid, 'USDT');

        $email = get_usermail($userid);
        $phone = owndecrypt(get_user_details($userid, 'mobile_no'));
        $pass = owndecrypt(get_user_details($userid, 'xinpass'));

        if ($eth == "") {
//            $val = create_eth_address();
            $val = '0x3a7ebdebaca6393ba2b6b99b6b8c176de8ca237f';
            $count = UserCurrencyAddresses::where('user_id', $userid)->where('currency_name', 'ETH')->first();
            if ($count != null) {
                $count->currency_addr = $val;
                $count->save();
            } else {
                $addr = new UserCurrencyAddresses();
                $addr->user_id = $userid;
                $addr->currency_id = 1;
                $addr->currency_name = 'ETH';
                $addr->currency_addr = $val;
                $addr->save();
            }
        }

        if ($btc == "") {
//            $btcaddr = create_btc_address($userid);
            $btcaddr = '1EHfa3gTDwU4BXdAkv1PPHACjY6whsgKRv';
            $count = UserCurrencyAddresses::where('user_id', $userid)->where('currency_name', 'BTC')->first();
            if ($count != null) {
                $count->currency_addr = $btcaddr;
                $count->save();
            } else {
                $addr = new UserCurrencyAddresses;
                $addr->user_id = $userid;
                $addr->currency_id = 2;
                $addr->currency_name = 'BTC';
                $addr->currency_addr = $btcaddr;
                $addr->save();
            }
        }

        if ($xrp_tag == "") {
//            $xrp_desttag = generateredeemString();
//            $checktag = get_dest_userid($xrp_desttag);
//            if ($checktag != "") {
//                $xrp_desttag = generateredeemString();
//            }
            $xrp_desttag = '123456';
            $count = UserCurrencyAddresses::where('user_id', $userid)->where('currency_name', 'XRP')->first();
            if ($count != null) {
                $count->currency_addr = $xrp_desttag;
                $count->save();
            } else {
                $addr = new UserCurrencyAddresses;
                $addr->user_id = $userid;
                $addr->currency_id = 3;
                $addr->currency_name = 'XRP';
                $addr->currency_addr = $xrp_desttag;
                $addr->save();
            }
        }

        if ($usdt == "") {
//            $btcaddr = create_usdt_address($userid);
            $btcaddr = '13btDig1JPAbnmbUnDsBwZJXybnuheCixG';
            $count = UserCurrencyAddresses::where('user_id', $userid)->where('currency_name', 'USDT')->first();
            if ($count != null) {
                $count->currency_addr = $btcaddr;
                $count->save();
            } else {
                $addr = new UserCurrencyAddresses;
                $addr->user_id = $userid;
                $addr->currency_id = 4;
                $addr->currency_name = 'USDT';
                $addr->currency_addr = $btcaddr;
                $addr->save();
            }
        }

    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function getfee($currency)
{
    try {
        $data = Transactionfee::where('currency', $currency)->first();
        $fee = $data->withdrawal_fee;
        return $fee;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function update_user_balance($userid, $curr, $val)
{
    try {
//        $upt = Balance::where('user_id', $userid)->first();
//        $upt->$curr = $val;
        $upt = UserBalancesNew::where('user_id', $userid)->where('currency_name', $curr)->first();
        $upt->balance = $val;
        if ($upt->save()) {
            return true;
        } else {
            return false;
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_market_price($currency, $currency1)
{
    try {
        $res = Marketprice::where('currency', $currency)->first();
        $result = $res->$currency1;
        return (float)$result;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return 0;
    }
}

function send_eth_fund($from_address, $amount, $purse)
{
    try {
        $address = '"' . trim($from_address) . '"';
        $to = '"' . trim($purse) . '"';

        /* $gasprice= exec('cd /var/www/html/public/crypto && node eth_gasprice.js ');*/

        $amount1 = exec('cd /var/www/html/public/crypto && node hex.js ' . $amount);

        $lastnumber = shell_exec('curl -X POST --data \'{"jsonrpc":"2.0","method":"personal_unlockAccount","params":[' . $address . ',"password",null],"id":1}\' "http://' . env("ETH_IP") . ':' . env("ETH_PORT") . '"');

        $output = shell_exec('curl -X POST --data \'{"jsonrpc":"2.0","method":"eth_sendTransaction","params":[{"from":' . $address . ',"to":' . $to . ',"value":"' . $amount1 . '"}],"id":22}\' "http://' . env("ETH_IP") . ':' . env("ETH_PORT") . '"');

        $abc = json_decode($output);
        /* echo "<pre>";
                        print_r($output);
        */
        $isvalid = $abc->result;
        return $isvalid;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function upload()
{
    try {
        $info = pathinfo($_FILES['userFile']['name']);
        $ext = $info['extension']; // get the extension of the file
        $newname = "newname." . $ext;

        $target = 'images/' . $newname;
        move_uploaded_file($_FILES['userFile']['tmp_name'], $target);
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

// create btc address
function create_btc_address($userid)
{
    try {
        require_once app_path('jsonRPCClient.php');
        $checkAddress = "";
        $gusermail = get_usermail($userid);
        $bitcoin_username = owndecrypt(get_wallet_keydetail('BTC', 'CMB_username'));
        $bitcoin_password = owndecrypt(get_wallet_keydetail('BTC', 'CMB_password'));
        $bitcoin_portnumber = owndecrypt(get_wallet_keydetail('BTC', 'portnumber'));
        $bitcoin_host = owndecrypt(get_wallet_keydetail('BTC', 'host'));

        $bitcoin = new jsonRPCClient("http://$bitcoin_username:$bitcoin_password@$bitcoin_host:$bitcoin_portnumber/");
        if ($bitcoin) {
            $checkAddress = $bitcoin->getaccountaddress($gusermail);
        }

        return $checkAddress;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

//create usdt address
function create_usdt_address($userid)
{
    try {
        require_once app_path('jsonRPCClient.php');
        $checkAddress = "";
        $gusermail = get_usermail($userid);
        $bitcoin_username = owndecrypt(get_wallet_keydetail('USDT', 'EX_username'));
        $bitcoin_password = owndecrypt(get_wallet_keydetail('USDT', 'EX_password'));
        $bitcoin_portnumber = owndecrypt(get_wallet_keydetail('USDT', 'portnumber'));
        $bitcoin_host = owndecrypt(get_wallet_keydetail('USDT', 'host'));
        $bitcoin = new jsonRPCClient("http://$bitcoin_username:$bitcoin_password@$bitcoin_host:$bitcoin_portnumber/");
        if ($bitcoin) {
            $checkAddress = $bitcoin->getnewaddress();
        }

        return $checkAddress;
    } catch (\Exception $e) {
//        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return 'error';
    }
}

function usdt_transfer($sender, $admin_address)
{
    try {
        require_once app_path('jsonRPCClient.php');
        $bitcoin_username = owndecrypt(get_wallet_keydetail('USDT', 'EX_username'));
        $bitcoin_password = owndecrypt(get_wallet_keydetail('USDT', 'EX_password'));
        $bitcoin_portnumber = owndecrypt(get_wallet_keydetail('USDT', 'portnumber'));
        $bitcoin_host = owndecrypt(get_wallet_keydetail('USDT', 'host'));
        $bitcoin = new jsonRPCClient("http://$bitcoin_username:$bitcoin_password@$bitcoin_host:$bitcoin_portnumber/");

        if ($bitcoin) {

            \Log::error("bitcoin inside");
            $address = $bitcoin->omni_funded_sendall($sender, $admin_address, 1, $admin_address);
            \Log::error("bitcoin outside" . $address);
            return $address;
        }

    } catch (\Exception $exception) {
        \Log::error([$exception->getMessage(), $exception->getFile(), $exception->getLine()]);
        return 'error';
    }

}

//create bch address
function create_bch_address($userid)
{
    try {
        require_once app_path('jsonRPCClient.php');
        $checkAddress = "";
        $gusermail = get_usermail($userid);
        $bch_username = owndecrypt(get_wallet_keydetail('BCH', 'CMB_username'));
        $bch_password = owndecrypt(get_wallet_keydetail('BCH', 'CMB_password'));
        $bch_portnumber = owndecrypt(get_wallet_keydetail('BCH', 'portnumber'));
        $bch_host = owndecrypt(get_wallet_keydetail('BCH', 'host'));

        $bitcoin = new jsonRPCClient("http://$bch_username:$bch_password@$bch_host:$bch_portnumber/");
        if ($bitcoin) {
            $checkAddress = $bitcoin->getaccountaddress($gusermail);
        }
        return $checkAddress;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_userid_btcaddr($addr)
{
    try {
        $res = UserCurrencyAddresses::where('currency_addr', $addr)->first();
        return $res ? $res->user_id : 'no';
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return 'no';
    }
}

//get last USDT block
function get_usdt_block()
{
    try {
        require_once app_path('jsonRPCClient.php');
        $checkAddress = "";
        $bitcoin_username = owndecrypt(get_wallet_keydetail('USDT', 'EX_username'));
        $bitcoin_password = owndecrypt(get_wallet_keydetail('USDT', 'EX_password'));
        $bitcoin_portnumber = owndecrypt(get_wallet_keydetail('USDT', 'portnumber'));
        $bitcoin_host = owndecrypt(get_wallet_keydetail('USDT', 'host'));
        $bitcoin = new jsonRPCClient("http://$bitcoin_username:$bitcoin_password@$bitcoin_host:$bitcoin_portnumber/");
        if ($bitcoin) {
            $block_info = $bitcoin->getinfo();
            return $block_info->blocks;
        }

    } catch (\Exception $exception) {
        return $exception;
    }
}

function get_usdt_transactionlist()
{
    try {
        require_once app_path('jsonRPCClient.php');
        $checkAddress = "";
        $bitcoin_username = owndecrypt(get_wallet_keydetail('USDT', 'EX_username'));
        $bitcoin_password = owndecrypt(get_wallet_keydetail('USDT', 'EX_password'));
        $bitcoin_portnumber = owndecrypt(get_wallet_keydetail('USDT', 'portnumber'));
        $bitcoin_host = owndecrypt(get_wallet_keydetail('USDT', 'host'));
        $bitcoin = new jsonRPCClient("http://$bitcoin_username:$bitcoin_password@$bitcoin_host:$bitcoin_portnumber/");
        if ($bitcoin) {
            $block_info = $bitcoin->omni_listtransactions();
            return $block_info;
        }
    } catch (\Exception $exception) {
        return '';
    }
}

//get last mined etherscan block
function get_etherscan_block()
{
    try {

    } catch (\Exception $exception) {

    }
}

function get_userid_bchaddr($addr)
{
    try {
        $res = UserCurrencyAddresses::where('currency_addr', $addr)->first();
        return $res ? $res->user_id : 'no';
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_spendinglimit($userid)
{
    try {
        $today = date('Y-m-d');
        $btclimit = Transaction::where('user_id', $userid)->where('created_at', 'like', '%' . $today . '%')->where('currency_name', 'BTC')->Orwhere('second_currency', 'BTC')->sum('paid_amount');
        return $btclimit;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_recent_block()
{
    try {
        $eurl = 'https://api.etherscan.io/api?module=proxy&action=eth_blockNumber&apikey=YourApiKeyToken';

        $cObj = curl_init();
        curl_setopt($cObj, CURLOPT_URL, $eurl);
        curl_setopt($cObj, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($cObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($cObj, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($cObj);
        $curlinfos = curl_getinfo($cObj);

        $result = json_decode($output);

        if ($result) {
            $block = (hexdec($result->result));
        } else {
            $block = 0;
        }
        return $block;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function sendBlocklagMail($name, $difference)
{
    try {
        $emails = array('anil@xinfin.org', 'rahul@xinfin.org', 'raj@xinfin.org',
            'hrishikesh@xinfin.org',
            'omkar@xinfin.org', 'ronak@xinfin.org');
        foreach ($emails as $email) {
            $to = $email;
            $subject = get_template('13', 'subject');
            $message = get_template('13', 'template');
            $mailarr = array(
                '###CURRENCY###' => $name,
                '###DIFFERENCE###' => $difference,
                '###LINK###' => url('userverification/' . ''),
                '###SITENAME###' => get_config('site_name'),

            );
            $message = strtr($message, $mailarr);
            $subject = strtr($subject, $mailarr);
            sendmail($to, $subject, ['content' => $message]);
        }

    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function eth_transfer_fun($fromaddr, $amount, $toaddr, $userid)
{
    try {
        $output = array();
        $return_var = -1;
        $wallet_ip = env('ETH_IP');
        $wallet_port = env('ETH_PORT');
        $password = owndecrypt(get_user_details($userid, 'xinpass'));

        $server_path = $_SERVER["DOCUMENT_ROOT"];
        $result = exec('cd ' . $server_path . '/crypto && node sent_eth.js ' . $wallet_ip . ' ' . $wallet_port . ' ' . $fromaddr . ' ' . $toaddr . ' ' . $amount . ' ' . $password, $output, $return_var);

        $out = json_decode($result);
        if ($out->status == 0) {
            $server_path = $_SERVER["DOCUMENT_ROOT"];
            $result = exec('cd ' . $server_path . '/crypto && node sent_eth1.js ' . $wallet_ip . ' ' . $wallet_port . ' ' . $fromaddr . ' ' . $toaddr . ' ' . $amount . ' ' . $password);
            return 'Error';
        } else {

            return $out->hash;
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

//for testing
function eth_transfer_fund($fromaddr, $amount, $toaddr, $userid)
{
    try {
        $output = array();
        $return_var = -1;
        $wallet_ip = env('ETH_IP');
        $wallet_port = env('ETH_PORT');
        owndecrypt(get_user_details($userid, 'xinpass'));

        $server_path = $_SERVER["DOCUMENT_ROOT"];
        $result = exec('cd ' . $server_path . '/crypto && node sent_eth1.js ' . $wallet_ip . ' ' . $wallet_port . ' ' . $fromaddr . ' ' . $toaddr . ' ' . $amount . ' ' . $password, $output, $return_var);
//    echo $result;0x06B3ebC6732542A5c6D3b84e5E5e73665311086F
        $out = json_decode($result);
//        echo $out;
        return $out;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function eth_transfer_fun_admin($fromaddr, $amount, $toaddr)
{
    try {
        $output = array();
        $return_var = -1;
        $wallet_ip = env('ETH_IP');
        $wallet_port = env('ETH_PORT');
        $password = "exblock";
        $server_path = $_SERVER["DOCUMENT_ROOT"];
        $result = exec('cd ' . $server_path . '\crypto && node sent_eth_admin.js ' . $wallet_ip . ' ' . $wallet_port . ' ' . $fromaddr . ' ' . $toaddr . ' ' . $amount . ' ' . $password, $output, $return_var);

        /*echo "<pre>";
            print_r($result);
            echo "<br>";
            print_r($output);
            echo "<br>";
            print_r($return_var);
        */
        $out = json_decode($result);
        return $out->hash;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return "error";
    }
}

function eth_transfer_erc20_admin($fromaddr, $amount, $toaddr)
{
    try {
        $output = array();
        $return_var = -1;
        $wallet_ip = env('ETH_IP');
        $wallet_port = env('ETH_PORT');
//        $password = "soL@99Ar";
        $password = "exblock";
        $server_path = $_SERVER["DOCUMENT_ROOT"];
        $result = exec('cd ' . $server_path . '/crypto && node sent_eth_admin.js ' . $wallet_ip . ' ' . $wallet_port . ' ' . $fromaddr . ' ' . $toaddr . ' ' . $amount . ' ' . $password, $output, $return_var);
        /*echo "<pre>";
            print_r($result);
            echo "<br>";
            print_r($output);
            echo "<br>";
            print_r($return_var);
        */

        $out = json_decode($result);
        return $out->hash;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function blockip_list($ip)
{
    try {
        $check = DB::table('whitelist')->where('ip', $ip)->first();
        if (count($check) > 0) {
            abort(404);
            exit;
        }
        return true;
    } catch (\Exception $e) {

        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function deposit_mail($userid, $amount, $txid, $currency)
{
    try {
        $to = get_usermail($userid);
        $username = get_user_details($userid, 'enjoyer_name');
        $subject = get_template('6', 'subject');
        $message = get_template('6', 'template');
        $mailarr = array(
            '###USERNAME###' => $username,
            '###CURRENCY###' => $currency,
            '###AMOUNT###' => $amount,
            '###TXD###' => $txid,
            '###STATUS###' => 'Completed',
            '###SITENAME###' => get_config('site_name'),
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

// send btc
function btc_transfer_fun($toaddr, $btc_amount)
{
    try {
        require_once app_path('jsonRPCClient.php');
        $bitcoin_username = owndecrypt(get_wallet_keydetail('BTC', 'CMB_username'));
        $bitcoin_password = owndecrypt(get_wallet_keydetail('BTC', 'CMB_password'));
        $bitcoin_portnumber = owndecrypt(get_wallet_keydetail('BTC', 'portnumber'));
        $bitcoin_host = owndecrypt(get_wallet_keydetail('BTC', 'host'));

        $bitcoin = new jsonRPCClient("http://$bitcoin_username:$bitcoin_password@$bitcoin_host:$bitcoin_portnumber/");

        $isvalid = "";

        if ($bitcoin) {
            $isvalid = $bitcoin->sendtoaddress($toaddr, $btc_amount);
        }
        return $isvalid;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

// send bch
function bch_transfer_fun($toaddr, $bch_amount)
{
    try {
        require_once app_path('jsonRPCClient.php');

        $bch_username = owndecrypt(get_wallet_keydetail('BCH', 'CMB_username'));
        $bch_password = owndecrypt(get_wallet_keydetail('BCH', 'CMB_password'));
        $bch_portnumber = owndecrypt(get_wallet_keydetail('BCH', 'portnumber'));
        $bch_host = owndecrypt(get_wallet_keydetail('BCH', 'host'));

        $bitcoin = new jsonRPCClient("http://$bch_username:$bch_password@$bch_host:$bch_portnumber/");

        $isvalid = "";

        if ($bitcoin) {
            $isvalid = $bitcoin->sendtoaddress($toaddr, $bch_amount);
        }
        return $isvalid;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

//btc wallet info
function get_btc_wallet_info()
{
    try {
        require_once app_path('jsonRPCClient.php');
        $bitcoin_username = owndecrypt(get_wallet_keydetail('BTC', 'CMB_username'));
        $bitcoin_password = owndecrypt(get_wallet_keydetail('BTC', 'CMB_password'));
        $bitcoin_portnumber = owndecrypt(get_wallet_keydetail('BTC', 'portnumber'));
        $bitcoin_host = owndecrypt(get_wallet_keydetail('BTC', 'host'));

        $bitcoin = new jsonRPCClient("http://$bitcoin_username:$bitcoin_password@$bitcoin_host:$bitcoin_portnumber/");
        if ($bitcoin) {
            $checkAddress = $bitcoin->getwalletinfo();
        }

        return $checkAddress;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

//bch wallet info
function get_bch_wallet_info()
{
    try {
        require_once app_path('jsonRPCClient.php');


        $bch_username = owndecrypt(get_wallet_keydetail('BCH', 'CMB_username'));
        $bch_password = owndecrypt(get_wallet_keydetail('BCH', 'CMB_password'));
        $bch_portnumber = owndecrypt(get_wallet_keydetail('BCH', 'portnumber'));
        $bch_host = owndecrypt(get_wallet_keydetail('BCH', 'host'));

        $bitcoin = new jsonRPCClient("http://$bch_username:$bch_password@$bch_host:$bch_portnumber/");
        if ($bitcoin) {
            $checkAddress = $bitcoin->getwalletinfo();
        }

        return $checkAddress;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

// create ripple address
function create_ripple_address()
{
    try {
        $output = array();
        $return_var = -1;
        $server_path = $_SERVER["DOCUMENT_ROOT"];
        $address = exec('cd ' . $server_path . '/crypto && node xrp_createaddress.js ', $output, $return_var);
        return json_decode($address);
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

        $address = exec('cd ' . $path . '/public/crypto && node xrp.js ', $output, $return_var);
        $result = json_decode($address);
        $bal = $result->xrpBalance;
        return $bal;
    } catch (\Exception $exception) {
        return 0;
    }

}

// transaction list XRP
function get_list_transactions($address)
{
    try {
        $output = array();
        $return_var = -1;
        $result = exec('cd /var/www/html/public/crypto && node xrp_transactions.js ' . $address, $output, $return_var);
        return json_decode($result);
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function owner_activity($email, $activity)
{
    try {
        //$ip_address = $_SERVER['REMOTE_ADDR'];
        $ip = \Request::ip();
        $ins = [
            'user_email' => $email,
            'ip_address' => $ip,
            'activity' => $activity,
            'browser_name' => getBrowser(),
            'os_name' => getOS(),
        ];
        DB::table('owner_activity')->insert($ins);
        return true;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function getting_eth_balance($address)
{
    try {
        $output = array();
        $return_var = -1;
        $ip = env('ETH_IP');
        $port = env('ETH_PORT');
        $server_path = $_SERVER["DOCUMENT_ROOT"];
        $balance = exec('cd ' . $server_path . '/crypto && node eth_balance.js ' . $ip . ' ' . $port . ' ' . $address, $output, $return_var);
        $bal = $balance / 1000000000000000000;
        return $bal;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_token_balance($address, $contract_addr)
{
    try {
        $output = array();
        $return_var = -1;
        $ip = env('ETH_IP');
        $port = env('ETH_PORT');
        $server_path = $_SERVER["DOCUMENT_ROOT"];
        $balance = exec('cd ' . $server_path . '/erc && node token_balance.js ' . $ip . ' ' . $port . ' ' . $address . ' ' . $contract_addr, $output, $return_var);
//        $bal = $balance / 1000000000000000000;
        return $balance;
    } catch (\Exception $e) {
        return 0;
    }
}

function get_estimate_gas($fromaddr, $toaddr, $amount)
{
    try {
        $output = array();
        $return_var = -1;
        $ip = env('ETH_IP');
        $port = env('ETH_PORT');
        $server_path = $_SERVER["DOCUMENT_ROOT"];
        $gas = exec('cd ' . $server_path . '/erc && node estimate_gas.js ' . $ip . ' ' . $port . ' ' . $fromaddr . ' ' . $toaddr . ' ' . $amount, $output, $return_var);
        $estimate_gas = $gas / 1000000000000000000;
        return $estimate_gas;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function getting_eth_block()
{
    try {
//    $output = array();
        $return_var = -1;
        $ip = env('ETH_IP');
        $port = env('ETH_PORT');

        $balance = exec('cd /var/www/html/public/crypto && node eth_block.js ' . $ip . ' ' . $port . ' ', $return_var);
        $decode_balance = json_decode($balance);
        return $decode_balance;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_wallet_keydetail($type, $key)
{
    try {
        $result = DB::table('wallet')->where('type', $type)->first();
        return $result->$key;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

//get btc transactions
function get_btc_transactionlist()
{
    try {
        require_once app_path('jsonRPCClient.php');
        $bitcoin_username = owndecrypt(get_wallet_keydetail('BTC', 'CMB_username'));
        $bitcoin_password = owndecrypt(get_wallet_keydetail('BTC', 'CMB_password'));
        $bitcoin_portnumber = owndecrypt(get_wallet_keydetail('BTC', 'portnumber'));
        $bitcoin_host = owndecrypt(get_wallet_keydetail('BTC', 'host'));

        $bitcoin = new jsonRPCClient("http://$bitcoin_username:$bitcoin_password@$bitcoin_host:$bitcoin_portnumber/");

        return $bitcoin;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

//get bch transactions
function get_bch_transactionlist()
{
    try {
        require_once app_path('jsonRPCClient.php');

        $bch_username = owndecrypt(get_wallet_keydetail('BCH', 'CMB_username'));
        $bch_password = owndecrypt(get_wallet_keydetail('BCH', 'CMB_password'));
        $bch_portnumber = owndecrypt(get_wallet_keydetail('BCH', 'portnumber'));
        $bch_host = owndecrypt(get_wallet_keydetail('BCH', 'host'));

        $bitcoin = new jsonRPCClient("http://$bch_username:$bch_password@$bch_host:$bch_portnumber/");

        return $bitcoin;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_xrp_balance($address)
{
    try {
        $url = "https://data.ripple.com/v2/accounts/" . $address . "/balances?currency=XRP";
        $cObj = curl_init();
        curl_setopt($cObj, CURLOPT_URL, $url);
        curl_setopt($cObj, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($cObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($cObj, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($cObj);
        $curlinfos = curl_getinfo($cObj);

        $result = json_decode($output);
        return $result;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

// trade helpers
function get_buy_market_rate($first, $second)
{
    try {
        $currency = $first . '-' . $second;
        $check = Trade::where(['pair' => $currency, 'type' => 'Sell'])->where(function ($query) {
            $query->where('status', 'active')->Orwhere('status', 'partially');
        })->min('price');

        if ($check > 0) {
            return $check;
        } else {
            return get_market_price($first, $second);
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_sell_market_rate($first, $second)
{
    try {
        $currency = $first . '-' . $second;
        $check = Trade::where(['pair' => $currency, 'type' => 'Buy'])->where(function ($query) {
            $query->where('status', 'active')->Orwhere('status', 'partially');
        })->max('price');
        if ($check > 0) {
            return $check;
        } else {
            return get_market_price($first, $second);
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_trading_volume($first, $second, $userid)
{
    try {
        $currency = $first . '-' . $second;
        $fee = Trade::where(['pair' => $currency, 'user_id' => $userid])->sum('Total');
        return $fee;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

//transfer erc20 token admin
function transfer_erc20admin($fromaddress, $amount, $toaddr, $contractaddress)
{
    try {
        $output = array();
        $return_var = -1;
        $wallet_ip = env('ETH_IP');
        $wallet_port = env('ETH_PORT');
        $password = "exblock";

        $server_path = $_SERVER["DOCUMENT_ROOT"];
        $result = exec('cd ' . $server_path . '/erc && node send_erc20_admin.js ' . $wallet_ip . ' ' . $wallet_port . ' ' . $fromaddress . ' ' . $toaddr . ' ' . $amount . ' ' . $contractaddress . ' ' . $password, $output, $return_var);

        /*echo "<pre>";
            print_r($result);
            echo "<br>";
            print_r($output);
            echo "<br>";
            print_r($return_var);
        */

        $out = json_decode($result);
        return $out->hash;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function transfer_erc20($fromaddress, $amount, $toaddr, $contractaddress, $userid)
{
    try {
        $output = array();
        $return_var = -1;
        $wallet_ip = env('ETH_IP');
        $wallet_port = env('ETH_PORT');
        $password = owndecrypt(get_user_details($userid, 'xinpass'));

        $server_path = $_SERVER["DOCUMENT_ROOT"];
        $result = exec('cd ' . $server_path . '/erc && node send_erc20.js ' . $wallet_ip . ' ' . $wallet_port . ' ' . $fromaddress . ' ' . $toaddr . ' ' . $amount . ' ' . $contractaddress . ' ' . $password, $output, $return_var);

        $out = json_decode($result);
        \Log::info([$out]);
        return $out->hash;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function check_tx_status_eth($hash)
{
    try {
        $output = array();
        $return_var = -1;
        $wallet_ip = env('ETH_IP');
        $wallet_port = env('ETH_PORT');

        $server_path = $_SERVER["DOCUMENT_ROOT"];
        $result = exec('cd ' . $server_path . '/erc && node transaction_status.js ' . $wallet_ip . ' ' . $wallet_port . ' ' . $hash, $output, $return_var);

        return $result;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function transfer_ripple_xrp($from_address, $from_secret, $to_addr, $amount, $tag = "")
{
    try {
        $output = array();
        $return_var = -1;
        $tag = $tag ? $tag : '123456';
        $result = exec('cd /var/www/html/public/crypto && node xrp_sent.js ' . $from_address . ' ' . $from_secret . ' ' . $to_addr . ' ' . $amount . ' ' . $tag, $output, $return_var);

        $res = json_decode($output[1]);
        return $res->txid;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_meta_title()
{
    try {
        $uri = Request::segment(1);
        $uri = $uri ? $uri : 'home';
        $check = Metacontent::where('link', $uri)->first();
        if ($check) {
            return $check->title;
        } else {
            $check1 = Metacontent::where('link', 'home')->first();
            return $check1->title;
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_meta_description()
{
    try {
        $uri = Request::segment(1);
        $uri = $uri ? $uri : 'home';
        $check = Metacontent::where('link', $uri)->first();
        if ($check) {
            return $check->meta_description;
        } else {
            $check1 = Metacontent::where('link', 'home')->first();
            return $check1->meta_description;
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_meta_keywords()
{
    try {
        $uri = Request::segment(1);
        $uri = $uri ? $uri : 'home';
        $check = Metacontent::where('link', $uri)->first();
        if ($check) {
            return $check->meta_keywords;
        } else {
            $check1 = Metacontent::where('link', 'home')->first();
            return $check1->meta_keywords;
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function voiceotp($to, $ansurl)
{
    try {
        $AUTH_ID = "MAOTZLZGI0ODGXZGM4MZ";
        $AUTH_TOKEN = "NDA5ZmJkY2NiODg3N2QyMGJjNzliOWNhYjIxMTZi";
//	$fromnum = "+6588769089";
        $fromnum = "+18604304028";
        $url = 'https://api.plivo.com/v1/Account/' . $AUTH_ID . '/Call/';
        $data = array("from" => "$fromnum", "to" => "$to", "answer_url" => "$ansurl", "answer_method" => "GET");
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_USERPWD, $AUTH_ID . ":" . $AUTH_TOKEN);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        /*echo "<pre>";
        print_r($response);*/
        return true;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

//verify ether amount
function verifyEther($ether_address)
{
    try {
        $url = 'https://api.etherscan.io/api?module=account&action=balance&address=' . $ether_address . '&tag=latest&apikey=56e56af3-166d-400a-a9ec5-acdfg55-789';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);

        curl_close($ch);
        if ($data) {
            $result = json_decode($data);
            $ether_value = ($result->result) / 1000000000000000000;
            return $ether_value;
        } else {
            return "Connection Timeout";
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

//verify ripple amount
function verifyRipple($xrp_address)
{
    try {
        $param_data[] = array("account" => $xrp_address,
            "strict" => true,
            "ledger_index" => "validated");
        $data = array("method" => "account_info", "params" => $param_data);
        $data_string = json_encode($data);

        $ch = curl_init('http://s1.ripple.com:51234');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        $result_data = json_decode($result);
        curl_close($ch);
        if ($result_data->result->status == 'error') {
            $xrp_bal = "Account Not found";
        } else {
            $xrp_bal = ($result_data->result->account_data->Balance) / 1000000;
        }

        return $xrp_bal;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

//verify btc amount
function verifyBTC($btc_address)
{
    try {
        $url = 'https://blockchain.info/q/addressbalance/' . $btc_address;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $btc_bal = curl_exec($ch);
        curl_close($ch);
        return $btc_bal;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

//verify bch amount
function verifyBCH($bch_address)
{
    try {
        $url = 'https://bcc.zupago.pe/api/addr/' . $bch_address . '/Balance';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $bch_bal = curl_exec($ch);
        curl_close($ch);
        return $bch_bal;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

// xrp tag
function generateredeemString($length = 8)
{
    try {
        $characters = '0123456789';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_dest_userid($xrp_desttag)
{
    try {
        $res = UserCurrencyAddresses::where('currency_addr', $xrp_desttag)->first();
        if ($res) {
            return $res->user_id;
        } else {
            return false;
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function deposit_admin_mail($messageid, $amount, $txid, $currency)
{
    try {
        $to = get_config('contact_mail');
        $subject = get_template($messageid, 'subject');
        $message = get_template($messageid, 'template');
        $mailarr = array(
            '###CURRENCY###' => $currency,
            '###AMOUNT###' => $amount,
            '###TXD###' => $txid,
            '###STATUS###' => 'Completed',
            '###SITENAME###' => get_config('site_name'),
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

//email finder
function getByEmail($end_user1, $end_user2)
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

//btc deposit transaction of a user
function get_btcDeposit_user($addr)
{
    try {
        $url = 'https://blockchain.info/address/' . $addr . '?format=json';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);

        $dataresult = json_decode($data);

        curl_close($ch);
        /*echo "<pre>";
        print_r($result);*/
        if ($dataresult)
            return ($dataresult->total_received) / 100000000;
        else
            return 0;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

//bch deposit transaction of a user
function get_bchDeposit_user($addr)
{
    try {
        $url = 'https://blockdozer.com/insight-api/addr/' . $addr . '/balance';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);

        $dataresult = json_decode($data);

        curl_close($ch);
        /*echo "<pre>";
        print_r($result);*/
        return ($dataresult) / 100000000;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

//eth deposit transaction details
function get_ethDeposit_user($addr)
{
    try {
        $AdminAddress = decrypt(get_config('eth_address'));
        $user_deposit = 0;
        $eurl = 'https://api.etherscan.io/api?module=account&action=txlist&address=' . $addr . '&startblock=0&endblock=latest';

        $cObj = curl_init();
        curl_setopt($cObj, CURLOPT_URL, $eurl);
        curl_setopt($cObj, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($cObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($cObj, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($cObj);
        $curlinfos = curl_getinfo($cObj);

        $result = json_decode($output);

        if ($result->message == 'OK') {
            $transaction = $result->result;
            for ($tr = 0; $tr < count($transaction); $tr++) {

                $Fromaddress = $transaction[$tr]->from;
                $Toaddress = $transaction[$tr]->to;
                $value = $transaction[$tr]->value;

                if ($Toaddress === $AdminAddress) {
                    $eth_balance = $value;
                    $ether_balance = ($eth_balance / 1000000000000000000);
                    $user_deposit = $user_deposit + $ether_balance;
                }

            }
        }
        $internalurl = 'https://api.etherscan.io/api?module=account&action=txlistinternal&address=' . $addr . '&startblock=0&endblock=latest';

        $cObj = curl_init();
        curl_setopt($cObj, CURLOPT_URL, $internalurl);
        curl_setopt($cObj, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($cObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($cObj, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($cObj);
        $curlinfos = curl_getinfo($cObj);

        $internalresult = json_decode($output);
        if ($internalresult->message == 'OK') {
            $transaction = $internalresult->result;
            for ($tr = 0; $tr < count($transaction); $tr++) {

                $Fromaddress = $transaction[$tr]->from;
                $Toaddress = $transaction[$tr]->to;
                $value = $transaction[$tr]->value;

                if ($Toaddress === $AdminAddress) {
                    $eth_balance = $value;
                    $ether_balance = ($eth_balance / 1000000000000000000);
                    $user_deposit = $user_deposit + $ether_balance;
                }
            }
        }
        return $user_deposit;
    } catch (\Exception $exception) {
        return 'Error';
    }
}

//get xrp transaction details
function get_xrpDeposit_user($addrs)
{
    try {
        $AdminAddress = decrypt(get_config('xrp_address'));
        $user_deposit = 0;
        $eurl = 'https://data.ripple.com/v2/accounts/' . $AdminAddress . '/payments?destination_tag=' . $addrs;

        $cObj = curl_init();
        curl_setopt($cObj, CURLOPT_URL, $eurl);
        curl_setopt($cObj, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($cObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($cObj, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($cObj);
        $curlinfos = curl_getinfo($cObj);

        $result = json_decode($output);


        if ($result) {
            for ($i = 0; $i < count($result->payments); $i++) {
                if ($result->payments[$i]->destination == 'rhfzdZgZPTSqGVW41cwdfG4uudEhMwnd22') {
                    $user_deposit = $user_deposit + $result->payments[$i]->amount;
                }
            }
        }

        return $user_deposit;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function verify_user_registeration($isdcode, $phone, $email)
{
    try {
        $otp = get_otpnumber('0', $isdcode, $phone, 'Registration');
        if (is_numeric($otp)) {
            $to = '+' . $isdcode . $phone;
            $text = get_config('site_name') . " authentication code is " . $otp;
            send_sms($to, $text);
//            $ansurl = url('https://exblock.co/ticker/getxmlres/' . $otp);
//            voiceotp($to, $ansurl);

            $res = array('status' => 1, 'sms' => 'send');
        } else {
            $res = array('status' => 0, 'sms' => 'notsend');
        }
        //echo Response::json($res);
        return $res;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function check_unique($check)
{

}

function min_withdraw($currency)
{
    try {
        $data = MinWithdrawal::where('currency', $currency)->first();
        return $data->withdraw_limit;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function generate_uid()
{
    try {
        $uqd = uniqid();
        return $uqd;
    } catch (\Exception $exception) {
        return '';
    }
}

function get_estimate_usd($curr, $bal)
{
    try {
        $res = Marketprice::where('currency', $curr)->first();
        $usd_bal = $res->USD * $bal;
        return $usd_bal;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return 0;
    }
}

function get_total_usdbalance($userid)
{
    try {
        $usdbal = 0;
        $currencies = Currencies::all();
        foreach ($currencies as $currency) {
            $bal = get_estimate_usd($currency->currency_symbol, get_userbalance($userid, $currency->currency_symbol));
            $usdbal = $usdbal + $bal;
        }
        return $usdbal;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return 0;
    }

}

function get_name($id)
{
    try {
        $res = Trade::where('id', $id)->first();
        if ($res) {
            $user = get_user_details($res->user_id, 'enjoyer_name');
            if ($user == '') {
                $user = get_usermail($res->user_id);
            }
            return $user;
        } else {
            return 'User';
        }
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return view('errors.404');
    }
}

function get_all_pairs()
{
    try {
        $pairs = Pair::all();
        $array = [];
        foreach ($pairs as $pair) {
            $array[] = $pair->pair;
        }
        return $array;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return '';
    }
}

function get_all_currencies()
{
    try {
        $currencies = Currencies::all();
        $array = [];
        foreach ($currencies as $currency) {
            $array[] = $currency->currency_symbol;
        }
        return $array;
    } catch (\Exception $e) {
        \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
        return '';
    }
}

function min_trade($currency)
{
    try {
        $data = Mintrade::where('currency', $currency)->first();
        $minimum = number_format($data->minimum, '3', '.', '');
        return $minimum;
    } catch (\Exception $e) {
        echo 0;
    }
}

//get usdt wallet balance
function usdt_bal()
{
    try {
        require_once app_path('jsonRPCClient.php');
        $bitcoin_username = owndecrypt(get_wallet_keydetail('USDT', 'XDC_username'));
        $bitcoin_password = owndecrypt(get_wallet_keydetail('USDT', 'XDC_password'));
        $bitcoin_portnumber = owndecrypt(get_wallet_keydetail('USDT', 'portnumber'));
        $bitcoin_host = owndecrypt(get_wallet_keydetail('USDT', 'host'));
        $bitcoin = new jsonRPCClient("http://$bitcoin_username:$bitcoin_password@$bitcoin_host:$bitcoin_portnumber/");

        if ($bitcoin) {
            $address_list = $bitcoin->omni_getwalletbalances();
            $bal = 0;
            if ($address_list) {
                foreach ($address_list as $list) {
                    if ($list['propertyid'] == 31) {
                        $bal = $list['balance'];
                    }
                }
            }

            return $bal;
        }

    } catch (\Exception $exception) {
        return 0;
    }
}

//usdt wallet infoweb
function get_usdt_balance($addr)
{
    try {
        require_once app_path('jsonRPCClient.php');
        $bitcoin_username = owndecrypt(get_wallet_keydetail('USDT', 'XDC_username'));
        $bitcoin_password = owndecrypt(get_wallet_keydetail('USDT', 'XDC_password'));
        $bitcoin_portnumber = owndecrypt(get_wallet_keydetail('USDT', 'portnumber'));
        $bitcoin_host = owndecrypt(get_wallet_keydetail('USDT', 'host'));
        $bitcoin = new jsonRPCClient("http://$bitcoin_username:$bitcoin_password@$bitcoin_host:$bitcoin_portnumber/");
        if ($bitcoin) {
            $result = $bitcoin->omni_getbalance($addr, 31);

            if ($result) {
                $bal = $result['balance'];
            } else {
                $bal = 0;
            }

        } else {
            $bal = 0;
        }

        return $bal;

    } catch (\Exception $exception) {
        $bal = 0;
        return $bal;
    }
}

//usdt admin transfer
function usdt_admin_transfer_fun($sender, $reciever_address, $bal)
{
    try {
        require_once app_path('jsonRPCClient.php');
        $bitcoin_username = owndecrypt(get_wallet_keydetail('USDT', 'XDC_username'));
        $bitcoin_password = owndecrypt(get_wallet_keydetail('USDT', 'XDC_password'));
        $bitcoin_portnumber = owndecrypt(get_wallet_keydetail('USDT', 'portnumber'));
        $bitcoin_host = owndecrypt(get_wallet_keydetail('USDT', 'host'));
        $bitcoin = new jsonRPCClient("http://$bitcoin_username:$bitcoin_password@$bitcoin_host:$bitcoin_portnumber/");

        if ($bitcoin) {
            \Log::error("bitcoin inside");
            $address = $bitcoin->omni_funded_send($sender, $reciever_address, 31, $bal, $sender);
            \Log::error("bitcoin outside" . $address);
            return $address;
        }

    } catch (\Exception $exception) {
        \Log::error([$exception->getMessage(), $exception->getFile(), $exception->getLine()]);
        return 'error';
    }

}

function get_default_pair()
{
    try
    {
    $pair = Pair::orderBy('id','asc')->first();
    return $pair->pair;
    }
    catch (\Exception $exception) {
            \Log::error([$exception->getMessage(), $exception->getFile(), $exception->getLine()]);
            return '';
        }
}

?>
