<?php

namespace App\Http\Controllers;

use App\model\ICORate;
use App\model\OTP;
use App\model\Trade;
use App\model\UserCurrencyAddresses;
use App\model\Users;
use App\model\XDCChart;
use App\model\Balance;
use App\model\UserBalancesNew;
use App\model\Transaction;
use App\model\Favorite;
use App\model\Pair;
use App\model\PairStats;
use App\model\Currencies;
use App\model\Transactionfee;
use App\model\TradeMapping;
use Pusher\Pusher;
use Hash;
use Illuminate\Http\Request;
use Session;
use DB;

class AjaxController extends Controller
{
    //
    function registerotp(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $isdcode = $request['isdcode'];
                $phone = $request['phone'];
                $email = $request['reg_email'];
                $type = $request['type'];
                $otp = get_otpnumber('0', $isdcode, $phone, $type);
                if (is_numeric($otp)) {
                    $to = '+' . $isdcode . $phone;
                    $text = get_config('site_name') . " authentication code is " . $otp;
                    send_sms($to, $text);
                    $ansurl = url('ticker/getxmlres/' . $otp);
//				voiceotp($to, $ansurl);

                    $to = $email;
                    $subject = get_template('9', 'subject');
                    $message = get_template('9', 'template');
                    $mailarr = array(
                        '###OTP###' => $otp,
                        '###SITENAME###' => get_config('site_name'),
                        '###SITELINK###' => url('/'),
                    );
                    $message = strtr($message, $mailarr);
                    $subject = strtr($subject, $mailarr);
                    //sendmail($to, $subject, ['content' => $message]);
                    $res = array('status' => 1, 'sms' => 'send', 'message' => 'OTP has been successfully sent to your registered mobile number.');
                } else {
                    $res = array('status' => 0, 'sms' => 'notsend', 'message' => 'OTP failed.');
                }
                //echo Response::json($res);
                $var = json_encode($res);
                return $var;
//            echo $var;
//            echo json_encode($res);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function otpcall(Request $request)
    {
        try {
            $mobile = $request['mobile'];
            $isd_code = $request['isdcode'];
            $check = OTP::where('mobile_no', ownencrypt($mobile))->orderBy('id', 'desc')->first();
            $otp = owndecrypt($check->otp);
            $to = '+' . $isd_code . $mobile;
//            $ansurl = url('https://ExBlock.net/ticker/getxmlres/' . $otp);
            $ansurl = url('ticker/getxmlres/' . $otp);
            $result = voiceotp($to, $ansurl);
            if ($result == true) {
                $res = array('status' => 1, 'sms' => 'Call Sent');
            } else {
                $res = array('status' => 0, 'sms' => 'Call not sent contact Support to verify your issue');
            }
            echo json_encode($res);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function verifyotp(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                if ($request->isMethod('post')) {
                    $userid = Session::get('alphauserid');
                    $isdcode = $request['isdcode'];
                    $phone = $request['phone'];
                    $type = $request['type'];
                    if ($type == 'Update') {
                        $check = Users::where('id', $userid)->where('mobile_no', ownencrypt($phone))->count();
                        if ($check > 0) {
                            $res = array('status' => 0, 'sms' => 'This Current mobile number');
                            echo json_encode($res);
                            exit;
                        }
                    }
                    $otp = get_otpnumber($userid, $isdcode, $phone, $type);
                    if (is_numeric($otp)) {
                        $to = '+' . $isdcode . $phone;
                        $text = get_config('site_name') . " authentication code is " . $otp;
                        send_sms($to, $text);
                        $ansurl = url('ticker/getxmlres/' . $otp);
//                    voiceotp($to, $ansurl);
                        $res = array('status' => 1, 'sms' => 'send', 'message' => 'OTP has been successfully sent to your registered mobile number.');
                    } else {
                        $res = array('status' => 0, 'sms' => 'notsend', 'message' => 'OTP failed.');
                    }
                    //echo Response::json($res);
                    echo json_encode($res);
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function checkphone(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $mobile_no = $request['mobile_no'];
                if ($request['user_id']) {
                    $user_id = $request['user_id'];
                    $user = Users::where('id', '=', $user_id)->first();
                    $user->mobile_no = ownencrypt($mobile_no);
                    if ($user->isDirty('mobile_no')) {
                        $numbers = Users::where('mobile_no', '=', $user->mobile_no)->first();
                        if ($numbers == null) {
                            $data['message'] = 0;
                        } else {
                            $data['message'] = 1;
                            Session::flash('error', 'The Phone Number Already Exsits.');
                        }
                    } else {
                        $data['message'] = 0;
                    }
                } else {
                    $numbers = Users::where('mobile_no', '=', ownencrypt($mobile_no))->first();
                    if ($numbers == null) {
                        $data['message'] = 0;
                    } else {
                        $data['message'] = 1;
                    }
                }
                echo json_encode($data);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function verify_otp(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $code = $request['verify_code'];
                $mobile = $request['mobile'];
                $user_id = $request['user_id'];
                $vcode = ownencrypt($code);
                /*$check = OTP::where('mobile_no', ownencrypt($mobile))->where('otp', ownencrypt($code))->orderBy('id', 'desc')->limit(1)->first();*/
                $check = OTP::where('mobile_no', ownencrypt($mobile))->orderBy('id', 'desc')->limit(1)->first();
                if (count($check) > 0 && $check->otp == $vcode) {
                    $isd = $check->isd;
                    $data['message'] = "Mobile verified successfully";
                    $data['status'] = 1;
                    $data['key'] = encrypt($mobile . '#' . $check->otp);
                    $check->delete();
                    if ($user_id != 0) {
                        $user = Users::where('id', $user_id)->first();
                        if ($user->mobile_status != 1) {
                            $user->mobile_no = ownencrypt($mobile);
                            $user->mobile_status = 1;
                            $user->mob_isd = $isd;
                            $user->save();
                            Session::flash('success', 'Your Mobile verification has been completed.');
                        }
                    }
                } else {
                    $data['message'] = "Enter valid code";
                    $data['status'] = 0;
                    $data['key'] = encrypt('wrong');
                }
                echo json_encode($data);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function get_currency_address(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return "Sorry your session seems to have expired please login again.";
            } else {
                if ($request->isMethod('post')) {
                    $userid = Session::get('alphauserid');
                    $oldpass = $request['currency'];
                    $record = UserCurrencyAddresses::where('user_id', $userid)->where('currency_name', $oldpass)->first();
                    $recordpass = $record->currency_addr;
                    if ($recordpass) {
                        echo $recordpass;
                    } else {

                        $data = generate_currency_address($userid, $oldpass);

                        echo $data;
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function address_validation(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $currency = $request['curr'];
                $toaddr = $request['to_addr'];
                if ($currency == 'BTC') {
                    $url = "https://blockchain.info/rawaddr/" . $toaddr;

                    $cObj = curl_init();
                    curl_setopt($cObj, CURLOPT_URL, $url);
                    curl_setopt($cObj, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($cObj, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($cObj, CURLOPT_RETURNTRANSFER, TRUE);
                    $btc = json_decode(curl_exec($cObj));
                    $curlinfos = curl_getinfo($cObj);
                    $data = (count($btc) > 0) ? 'true' : 'false';
                } else if ($currency == 'BCH') {
                    $url = "https://blockchain.info/rawaddr/" . $toaddr;

                    $cObj = curl_init();
                    curl_setopt($cObj, CURLOPT_URL, $url);
                    curl_setopt($cObj, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($cObj, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($cObj, CURLOPT_RETURNTRANSFER, TRUE);
                    $bch = json_decode(curl_exec($cObj));
                    $curlinfos = curl_getinfo($cObj);
                    $data = (count($bch) > 0) ? 'true' : 'false';
                } else if ($currency == 'XDC') {
                    //$toaddr = strtolower($toaddr);
                    $res = verify_xdc_addr($toaddr);
                    $data = ($res->status == 'SUCCESS') ? 'true' : 'false';
                } elseif ($currency == 'XRP') {
                    $url = "https://data.ripple.com/v2/accounts/" . $toaddr . "/balances?currency=XRP";

                    $cObj = curl_init();
                    curl_setopt($cObj, CURLOPT_URL, $url);
                    curl_setopt($cObj, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($cObj, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($cObj, CURLOPT_RETURNTRANSFER, TRUE);
                    $xrp = json_decode(curl_exec($cObj));
                    $curlinfos = curl_getinfo($cObj);
                    $data = ($xrp->result == 'error' && @$xrp->message == 'invalid ripple address') ? 'false' : 'true';
                } else {
                    $data = "true";
                }
                return $data;
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function getfee(Request $request)
    {
        try {
            $currency = $request['curr'];
            $data = Transactionfee::where('currency', $currency)->first();
            $fee = $data->withdrawal_fee;
            return $fee;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function limit_balance(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                if ($request->isMethod('post')) {
                    $userid = Session::get('alphauserid');
                    $amount = $request['to_amount'];
                    $curr = $request['curr'];
                    $getuserbal = get_userbalance($userid, $curr);
                    if ($amount <= $getuserbal) {
                        $data = "true";
                    } else {
                        $data = "false";
                    }
                    return $data;
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function geticorate(Request $request)
    {
        try {
            $first_currency = $request['first_currency'];
            $second_currency = $request['second_currency'];
            $ico_rate = ICORate::where('FirstCurrency', $first_currency)->where('SecondCurrency', $second_currency)->first();
            $price = $ico_rate->Amount;
            return $price;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function add_fav(Request $request)
    {
        try {
            $user_id = $request['user_id'];
            $pair_id = $request['pair_id'];
            $check = Favorite::where('user_id', $user_id)->where('pair_id', $pair_id)->first();
            if (count($check) > 0) {
                $data = '2';
            } else {
                $fav = new Favorite;
                $fav->user_id = $user_id;
                $fav->pair_id = $pair_id;
                if ($fav->save())
                    $data = '1';
                else
                    $data = '2';
            }
            $data = json_encode($data);
            return $data;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function remove_fav(Request $request)
    {
        try {
            $user_id = $request['user_id'];
            $pair_id = $request['pair_id'];
            $fav = Favorite::where('user_id', $user_id)->where('pair_id', $pair_id)->first();
            if ($fav->delete())
                $data = '1';
            else
                $data = '0';
            $data = json_encode($data);
            return $data;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function updateprice()
    {
        try {
            $get_all_pairs = Pair::all();
            if ($get_all_pairs) {
                foreach ($get_all_pairs as $get_all_pair) {
                    $get_pair_stat = PairStats::where('pair_id', $get_all_pair->id)->first();
                    $explode = explode('-', $get_all_pair->pair);
                    $first_currency = $explode[0];
                    $currency = $explode[1];
                    $pair1 = $get_all_pair->pair;
                    $id = $get_pair_stat->id;
                    $pair_id = $get_pair_stat->pair_id;
                    $vol = $get_pair_stat->volume;
                    $low = $get_pair_stat->low;
                    $high = $get_pair_stat->high;
                    $last = $get_pair_stat->last;
                    $percentage_change = $get_pair_stat->percent_change . '%';
                    $change = $get_pair_stat->change;
                    $color = strtolower($get_pair_stat->colour);

                    $array = array('id' => $id, 'pair_id' => $pair_id, 'first_currency' => $first_currency, 'currency' => $currency, 'Pair' => $pair1, 'Volume' => number_format($vol, 2, '.', ''), 'Low' =>
                        number_format($low, 3, '.', ''),
                        'High' => number_format($high, 3, '.', ''), 'Percentage' => $percentage_change, 'Change' => number_format($change, 3, '.', ''), 'Colour' => $color, 'Last' => number_format($last, '3', '.', ''));
                    $all_pair[] = $array;
                }
            }
            $data = $all_pair;
            return $data;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function updaterate(Request $request)
    {
        try {
            $pair = $request['pair'];
            $cur = explode("-", $pair);
            $first_currency = $cur[0];
            $second_currency = $cur[1];
            $buy_rate = get_buy_market_rate($first_currency, $second_currency);
            $sell_rate = get_sell_market_rate($first_currency, $second_currency);
            $data = ['buy_rate' => $buy_rate, 'sell_rate' => $sell_rate];
            return $data;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function updatebalance(Request $request)
    {
        try {
            $user_id = base64_decode($request['userid']);
            $all_currency = Currencies::all();
            foreach ($all_currency as $currency) {
                $curr = $currency->currency_symbol;
                $bal = get_userbalance($user_id, $curr);
                $array = array('curr' => $curr, 'bal' => $bal);
                $user_balance[] = $array;
            }
            return $user_balance;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function checkoldpass(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                if ($request->isMethod('post')) {
                    $userid = Session::get('alphauserid');
                    $oldpass = $request['old_password'];
                    $recordpass = get_user_details($userid, 'pass_code');
                    if (Hash::check($oldpass, $recordpass)) {
                        echo "true";
                    } else {
                        echo "false";
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function index()
    {
        abort(404);
    }

    //for user verification
    function user_verification($id)
    {
        try {
            if (Session::get('alpha_id') == "") {
                return redirect('check_admin');
            } else {
                $get_user = Users::where('id', $id)->first();

                if ($get_user->user_verified == 0) {
                    $get_user->user_verified = 1;
                } else {
                    $get_user->user_verified = 0;
                }
                if ($get_user->save()) {
                    return 1;

                } else {
                    return 0;
                }
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
                    echo "false";
                } else {
                    echo "true";
                }
            });
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function pusher_auth(Request $request)
    {
        if (Session::has('alphauserid')) {
//            $user_id=Session::get('alphauserid');
            $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));
            echo $pusher->socket_auth($request['channel_name'], $request['socket_id']);
        } else {
            header('', true, 403);
            echo "Forbidden";
        }
    }

    function buy_order_list(Request $request)
    {
        try {
            $pair = $request['pair'];
            $buy_order_list = Trade::select(DB::raw('SUM(total) as total,SUM(updated_total) as updated_total,status,price,SUM(updated_qty) as updated_qty'))->where(['pair' => $pair, 'type' => 'Sell'])->where(function ($query) {
                $query->where('status', 'active')->Orwhere('status', 'partially');
            })->groupBy('price')->orderBy('price', 'asc')->limit(5)->get()->reverse();
            if (isset($buy_order_list[0])) {
                foreach ($buy_order_list as $val) {
                    $buy_order_array[] = array('price' => $val->price, 'total' => $val->total, 'status' => $val->active, 'updated_total' => $val->updated_total, 'updated_qty' => $val->updated_qty);
                }
                return json_encode($buy_order_array);
            } else {
                return json_encode($buy_order_list);
            }

        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function sell_order_list(Request $request)
    {
        try {
            $pair = $request['pair'];
            $sell_order_list = Trade::select(DB::raw('SUM(total) as total,SUM(updated_total) as updated_total,status,price,SUM(updated_qty) as updated_qty'))->where(['pair' => $pair, 'type' => 'Buy'])->where(function ($query) {
                $query->where('status', 'active')->Orwhere('status', 'partially');
            })->groupBy('price')->orderBy('price', 'desc')->limit(5)->get();
            return json_encode($sell_order_list);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function trade_history_table(Request $request)
    {
        try {
            $pair = $request['pair'];
            $trade_history = TradeMapping::where('pair', $pair)->orderBy('updated_at', 'desc')->limit(17)->get();
            return json_encode($trade_history);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function get_trading_fee(Request $request)
    {
        try {
            $type = $request['type'];
            $pair = $request['pair'];
            $fee = get_trade_fee($type, $pair);
            return json_encode($fee);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //    particular open order record
    function openorders(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                if ($request->isMethod('post')) {
                    $user_id = $request['user_id'];
//                    $pair = $request['pair'];
                    if ($user_id == Session::get('alphauserid')) {

                        $active_orders = Trade::select('id', 'created_at', 'type', 'pair', 'updated_qty', 'price', 'total', 'updated_total', 'status')->where('user_id', $user_id)->where(function ($query) {
                            $query->where('status', 'active')->Orwhere('status', 'partially');
                        })->orderBy('id', 'desc')->limit(12)->get()->toJson();
                        return $active_orders;
                    } else {
                        return 0;
                    }

                }

            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    // USER TRADE HISTORY
    function mytradehistory(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                if ($request->isMethod('post')) {
                    $user_id = $request['user_id'];
                    $pair = $request['pair'];
                    if ($user_id == Session::get('alphauserid')) {

                        $active_orders = DB::table('trade_order')->where('trade_order.user_id', '=', $user_id)->where('trade_order.pair', '=', $pair)
                            ->join('trade_mapping', function ($join) {
                                $join->on(function ($query) {
                                    $query->on('trade_mapping.buy_trade_order_id', '=', 'trade_order.id');
                                    $query->orOn('trade_mapping.sell_trade_order_id', '=', 'trade_order.id');
                                });
                            })
                            ->orderBy('trade_mapping.created_at', 'desc')
                            ->select('trade_order.id', 'trade_order.user_id', 'trade_order.pair', 'trade_order.type', 'trade_mapping.buy_trade_order_id', 'trade_mapping.sell_trade_order_id', 'trade_mapping.triggered_price', 'trade_mapping.triggered_qty', 'trade_mapping.total', 'trade_mapping.created_at')
                            ->limit(18)->get()->toJson();
                        return $active_orders;

                    } else {
                        return 0;
                    }

                }

            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function cancel_order(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                if ($request->isMethod('post')) {
                    $id = $request['id'];
                    $tradeid = base64_decode($id);
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
                            if ($result->status == 'active') {
                                $result->status = 'cancelled';
                                if ($result->save()) {
                                    $finalbalance = $second_cur_balance + $refnd_total;
//                            $upt = Balance::where('user_id', $userid)->first();
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
//                            $upt = Balance::where('user_id', $userid)->first();
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
//                            $upt = Balance::where('user_id', $userid)->first();
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
//                            $upt = Balance::where('user_id', $userid)->first();
                                    $upt = UserBalancesNew::where('user_id', $userid)->where('currency_name', $first_currency)->first();
                                    $upt->balance = $finalbalance;
                                    $upt->save();
                                }
                            }
                        }

                        $data['status'] = '200';
                        $data['message'] = 'Your order is been cancelled successfully';


                        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));

                        $pusher->trigger('order', 'order-cancelled', array('Pair' => $result->pair, 'Type' => $result->type,
                            'Amount' => number_format($result->updated_qty, 0, '.', ''),
                            'Price' => number_format($result->price, 8, '.', ''),
                            'Total' => number_format($result->total, 8, '.', ''),
                            'Fee' => number_format($trade_fee, 9, '.', ''),
                        ));

                        return json_encode($data);
                    }
                }
            }
        } catch (\Exception $exception) {
            $data['status'] = '500';
            $data['message'] = 'Server Error';
            return json_encode($data);
        }
    }

    function get_estimatme_usdbalance(Request $request)
    {
        try {
            if ($request->isMethod('get')) {
                $currency = $request['currency'];
                //$amount = $request['amount'];
                $amount = 1;
                $price = $request['price'];
                $cur_price = get_estusd_price($currency, $amount);
                echo $cur_price * $price;
            }
        } catch (\Exception $exception) {
            $data['status'] = '500';
            $data['message'] = 'Server Error';
            return json_encode($data);
        }
    }

    function cancel_multiple(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                if ($request->isMethod('post')) {
                    $orders = $request['orders'];
                    for ($i = 0; $i < count($orders); $i++) {
                        $tradeid = base64_decode($orders[$i]);
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
                                if ($result->status == 'active') {
                                    $result->status = 'cancelled';
                                    if ($result->save()) {
                                        $finalbalance = $second_cur_balance + $refnd_total;
//                            $upt = Balance::where('user_id', $userid)->first();
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
//                            $upt = Balance::where('user_id', $userid)->first();
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
//                            $upt = Balance::where('user_id', $userid)->first();
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
//                            $upt = Balance::where('user_id', $userid)->first();
                                        $upt = UserBalancesNew::where('user_id', $userid)->where('currency_name', $first_currency)->first();
                                        $upt->balance = $finalbalance;
                                        $upt->save();
                                    }
                                }
                            }

                            $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));

                            $pusher->trigger('order', 'order-cancelled', array('Pair' => $result->pair, 'Type' => $result->type,
                                'Amount' => number_format($result->updated_qty, 0, '.', ''),
                                'Price' => number_format($result->price, 8, '.', ''),
                                'Total' => number_format($result->total, 8, '.', ''),
                                'Fee' => number_format($trade_fee, 9, '.', ''),
                            ));

                        }
                    }
                    $data['status'] = '200';
                    $data['message'] = 'Your orders have been cancelled successfully';

                    return json_encode($data);
                }
            }
        } catch (\Exception $exception) {
            $data['status'] = '500';
            $data['message'] = 'Server Error';
            return json_encode($data);
        }
    }

    function min_withdrawal(Request $request)
    {
        try {
            $currency = $request['currency'];
            $data = min_withdraw($currency);
            return json_encode($data);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function xdce_deposit_process_user($id)
    {
        try {
            $userslist[] = Users::orderBy('id', 'asc')->where('XDCE_addr', $id)->first();
            foreach ($userslist as $user) {
                $id = $user->id;
                $xdceaddr = $user->XDCE_addr;
                $xdcebal = get_livexdce_bal($xdceaddr);
                $xdce_blocknum = 0;

                if ($xdcebal > 1) {
                    $xdceTransactionList = get_xdce_transactions($xdceaddr, $xdce_blocknum);
                    try {
                        if ($xdceTransactionList->status == 'SUCCESS') {
                            $transaction = $xdceTransactionList->message;
                            for ($tr = 0; $tr < count($transaction); $tr++) {

                                $block_number = $transaction[$tr]->blockNumber;
                                $address = $transaction[$tr]->args->_to;
                                $txid = $transaction[$tr]->transactionHash;
                                $value = $transaction[$tr]->args->_value;

                                $dep_id = $txid;
                                $eth_balance = (float)$value;
                                $ether_balance = $eth_balance / 1000000000000000000;

                                $dep_already = xdce_checkdepositalready($id, $dep_id);
                                if ($dep_already === TRUE && (float)$ether_balance > 1) {
                                    if ($xdceaddr == $address) {

                                        $ether_balance = sprintf('%.10f', $ether_balance);

                                        //deposit transaction
                                        $transid = 'TXD' . $id . time();
                                        $today = date('Y-m-d H:i:s');
                                        $ip = \Request::ip();
                                        $ins = new Transaction;
                                        $ins->user_id = $id;
                                        $ins->payment_method = 'Cryptocurrency Account';
                                        $ins->transaction_id = $transid;
                                        $ins->currency_name = 'XDCE';
                                        $ins->type = 'Deposit';
                                        $ins->transaction_type = '1';
                                        $ins->amount = $ether_balance;
                                        $ins->updated_at = $today;
                                        $ins->crypto_address = $xdceaddr;
                                        $ins->transfer_amount = '0';
                                        $ins->fee = '0';
                                        $ins->tax = '0';
                                        $ins->verifycode = '1';
                                        $ins->order_id = '0';
                                        $ins->status = 'Completed';
                                        $ins->cointype = '2';
                                        $ins->payment_status = 'Paid';
                                        $ins->paid_amount = '0';
                                        $ins->wallet_txid = $dep_id;
                                        $ins->ip_address = $ip;
                                        $ins->verify = '1';
                                        $ins->blocknumber = '';
                                        if ($ins->save()) {
                                            //update user
                                            $fetchbalance = get_userbalance($id, 'XDCE');
                                            $finalbalance = $fetchbalance + $ether_balance;
//                                            $upt = Balance::where('user_id', $id)->first();
//                                            $upt->XDCE = $finalbalance;
                                            $upt = UserBalancesNew::where('user_id', $id)->where('currency_name', 'XDCE')->first();
                                            $upt->balance = $finalbalance;
                                            $upt->save();
                                            deposit_mail($id, $ether_balance, $transid, 'XDCE');
                                        }
                                    }
                                }
                            }

                        }
                    } catch (\Exception $e) {
                        continue;
                    }

                }

//            if($)

            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function generate_email_otp(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                if ($request->isMethod('post')) {
                    $userid = Session::get('alphauserid');
                    $type = $request['type'];
                    $isdcode = get_user_details($userid, 'mob_isd');
                    $mob = get_user_details($userid, 'mobile_no');
                    $phone = owndecrypt($mob);
                    $email = get_usermail($userid);
                    $otp = get_otpnumber($userid, $isdcode, $phone, $type);

                    $to = $email;
                    $subject = 'Withdrawal OTP';
                    $message = get_template('9', 'template');
                    $mailarr = array(
                        '###OTP###' => $otp,
                        '###SITENAME###' => get_config('site_name'),
                    );
                    $message = strtr($message, $mailarr);
                    $subject = strtr($subject, $mailarr);
                    sendmail($to, $subject, ['content' => $message]);
//                Session::flash('success', 'Please check your email address for otp');
                    echo "sent";
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //market_order_availability
    function available_market_data(Request $request)
    {
        try {
//            if (Session::get('alphauserid') == "") {
//                return redirect('logout');
//            }
//            else
//            {
            if ($request->isMethod('post')) {
                $user_id = Session::get('alphauserid');
                if ($user_id != "" || $user_id != null) {
                    $user_id = 0;
                }
                $pair = $request['pair'];

                $findorders = Trade::where('user_id', '<>', $user_id)->where(['pair' => $pair, 'type' => 'Sell'])->where(function ($query) {
                    $query->where('status', 'active')->Orwhere('status', 'partially');
                })->orderBy('price', 'asc')->sum('updated_qty');
                $find_buy_orders = number_format($findorders, 3, '.', '');

                $findorders = Trade::where('user_id', '<>', $user_id)->where(['pair' => $pair, 'type' => 'Buy'])->where(function ($query) {
                    $query->where('status', 'active')->Orwhere('status', 'partially');
                })->orderBy('price', 'desc')->sum('updated_qty');
                $find_sell_orders = number_format($findorders, 3, '.', '');

                if ($find_buy_orders > 0 && $find_sell_orders > 0) {
                    $data['status'] = 200;
                    $data['total'] = $find_buy_orders;
                    $data['amount'] = $find_sell_orders;
                    return json_encode($data);
                } else {
                    if ($find_buy_orders == 0 && $find_sell_orders == 0) {
                        $data['status'] = 401;
                        $data['message'] = 'No active orders available';
                        return json_encode($data);
                    } elseif ($find_buy_orders == 0) {
                        $data['status'] = 422;
                        $data['message'] = 'No active buy orders available';
                        $data['total'] = $find_buy_orders;
                        $data['amount'] = $find_sell_orders;
                        return json_encode($data);
                    } elseif ($find_sell_orders == 0) {
                        $data['status'] = 422;
                        $data['message'] = 'No active sell orders available';
                        $data['total'] = $find_buy_orders;
                        $data['amount'] = $find_sell_orders;
                        return json_encode($data);
                    }

                }
            }
//            }

        } catch (\Exception $exception) {
            $data['status'] = '500';
            $data['message'] = 'Server Error';
            return json_encode($data);
        }
    }

    function check_username(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $users = Users::where('id', '!=', $request['user_id'])->where('referral_code', $request['username'])->get();
                if (count($users) > 0) {
                    $data['status'] = '409';
                    $data['message'] = 'Username already exists.';
                    return json_encode($data);
                } else {
                    $data['status'] = '200';
                    $data['message'] = 'Username available.';
                    return json_encode($data);
                }
            } else {
                $data['status'] = '500';
                $data['message'] = 'Invalid request.';
                return json_encode($data);
            }
        } catch (\Exception $exception) {
            $data['status'] = '500';
            $data['message'] = 'Server Error';
            return json_encode($data);
        }
    }

    function refresh_capcha()
    {
        return captcha_img();
    }

    function min_trade(Request $request)
    {
        try {
            $currency = $request['currency'];
            $data = min_trade($currency);
            return json_encode($data);
        } catch (\Exception $e) {
            \Log::error([$e->getFile(), $e->getLine(), $e->getMessage()]);
            return 0;
        }

    }

}
