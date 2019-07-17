<?php

/**
 * This controller includes trade mapping functions .
 * @developer Balakumaran
 * @ver 1.00 03/11/2017
 * @company Osiz technologies
 * @platform laravel 5.4
 **/

namespace App\Http\Controllers;

use App\model\Balance;
use App\model\CurrencyTradeLimit;
use App\model\ico;
use App\model\icodetails;
use App\model\ICORate;
use App\model\ICOTrade;
use App\model\Pair;
use App\model\Profit;
use App\model\Trade;
use App\model\Tradingfee;
use App\model\Transaction;
use App\model\UserBalance;
use DB;
use Illuminate\Http\Request;
use Session;

class ICOController extends Controller
{
    //
    public function __construct()
    {
        try {
            //cons;
            $ip = \Request::ip();
            blockip_list($ip);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //ico
    function index()
    {
        try {
            $ico = ico::whereIn('status', ['Active', 'Ended'])->get();
            $ico_rate = ICORate::all();
            return view('front.ico', ['result' => $ico, 'ico_rate' => $ico_rate]);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //add_ico
    function addtoken(Request $request)
    {
        try {
//            if (Session::get('alphauserid') == "")
//            {
//                return redirect('logout');
//            }
//                else
//                    {
            if ($request->isMethod('post')) {
                $coin_name = $request['coinname'];
                $ticker = $request['ticker'];
                $ico_type = $request['icotype'];
                $contract_address = $request['contractaddress'];
                $decimals = $request['decimals'];
                $total_supply = $request['totalsupply'];
                $circulating_supply = $request['circulatingsupply'];
//                            $ico_supply = $request['icosupply'];
                $token_price = $request['icoprice'];
                $btc_ico_price = $request['btcicoprice'];
                $eth_ico_price = $request['ethicoprice'];
                $xdc_ico_price = $request['xdcicoprice'];

//                            $ico_date = $request['icostartdate'];
//                            $strtotime = strtotime($ico_date);
//                            $new_icodate = date('Y-m-d',$strtotime);
//                            $ico_max = $request['icomaxdays'];
                $algo = $request['algo'];
                $dev = $request['devlanguage'];
                $git = $request['git'];
                $explorer = $request['explorer'];
                $email = $request['email'];
                $skype = $request['skype'];
                $social = $request['social'];
                $comments = $request['comments'];
                $verification_type = $request['verificationtype'];

                //create ico


                $ico = new ico();
                $ico->name = $coin_name;
                $ico->ticker = $ticker;
                $ico->total_supply = $total_supply;
                $ico->circulating_supply = $circulating_supply;
//                            $ico->ico_supply=$ico_supply;
                $ico->token_price = $token_price;
//                            $ico->start_date=$new_icodate;
//                            $ico->max_days=$ico_max;
                $ico->status = 'Pending';

                $ico->save();
                
                $ico_details = new icodetails();
                $ico_details->ico_id = $ico->id;
                $ico_details->type = $ico_type;
                $ico_details->contract_address = $contract_address;
                $ico_details->decimals = $decimals;
                $ico_details->algorithm = $algo;
                $ico_details->dev = $dev;
                $ico_details->email = $email;
                $ico_details->messenger = $skype;
                $ico_details->social_links = $social;
                $ico_details->comments = $comments;
                $ico_details->verification_type = $verification_type;
                $ico_details->source_code = $git;
                $ico_details->explorer = $explorer;
                $ico_details->save();
                Session::flash('success', 'Your Token Listing request is placed');

                //send mail to user
                $to = $email;
                $subject = get_template('15', 'subject');
                $message = get_template('15', 'template');
                $mailarr = array(
                    '###SITENAME###' => get_config('site_name'),
                );
                $message = strtr($message, $mailarr);
                $subject = strtr($subject, $mailarr);
                sendmail($to, $subject, ['content' => $message]);

                //send email to admin
                $to = get_config('contact_mail');
                $subject = get_template('16', 'subject');
                $message = get_template('16', 'template');
                $mailarr = array(
                    '###LINK###' => url('check_admin/token_view/' . $ico->id),
                    '###SITENAME###' => get_config('site_name'),
                );
                $message = strtr($message, $mailarr);
                $subject = strtr($subject, $mailarr);
                sendmail($to, $subject, ['content' => $message]);


                return redirect('/addtoken');


            } else {
                return view('front.add_ico');
            }
//                        }

        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function ico_buy(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                if ($request->isMethod('post')) {
                    $userid = Session::get('alphauserid');

                    $FirstCurrency = $request['first_currency'];
                    $SecondCurrency = $request['second_currency'];


                    $second_currency_bal = get_userbalance($userid, $SecondCurrency);
                    $First_currency_bal = get_userbalance($userid, $FirstCurrency);

                    $ico_rate = ICORate::where('FirstCurrency', $FirstCurrency)->where('SecondCurrency', $SecondCurrency)->first();

                    $ico_buy_rate = $ico_rate->Amount;
                    $ico_fee = $ico_rate->Fee;
                    $ico_discount = $ico_rate->Discount;

                    $SecondCurrency_amount = $request['second_currency_amount'];

                    $transid = 'TXD' . $userid . time();
                    $ip = \Request::ip();

                    if ($SecondCurrency_amount <= $second_currency_bal) {
                        $buy_first_currency = $SecondCurrency_amount * $ico_buy_rate;

                        $fee = $SecondCurrency_amount * ($ico_fee / 100);
                        $discount = $SecondCurrency_amount * ($ico_discount / 100);

                        $total = ($buy_first_currency + $discount) - $fee;

                        $updated_secondcurrency_bal = $second_currency_bal - $SecondCurrency_amount;

                        $updated_first_currency_bal = $First_currency_bal + $buy_first_currency;

                        //buy trade record
                        $ico_trade = new ICOTrade();
                        $ico_trade->Type = 'ICO';
                        $ico_trade->transaction_id = $transid;
                        $ico_trade->user_id = $userid;
                        $ico_trade->ip = $ip;
                        $ico_trade->FirstCurrency = $FirstCurrency;
                        $ico_trade->SecondCurrency = $SecondCurrency;
                        $ico_trade->Price = $ico_buy_rate;
                        $ico_trade->Amount = $SecondCurrency_amount;
                        $ico_trade->Fee = $fee;
                        $ico_trade->Discount = $discount;
                        $ico_trade->Total = $total;
                        $ico_trade->Status = 'Completed';
                        $ico_trade->Previous_Bal = $First_currency_bal;
                        $ico_trade->After_Bal = $updated_first_currency_bal;
                        $ico_trade->Previous_currency = $second_currency_bal;
                        $ico_trade->After_currency = $updated_secondcurrency_bal;

                        if ($ico_trade->save()) {
                            $lobjUserBal = UserBalance::where('user_id', $userid)->first();
                            $lobjUserBal->$SecondCurrency = $updated_secondcurrency_bal;
                            $lobjUserBal->$FirstCurrency = $updated_first_currency_bal;
                            $lobjUserBal->save();
                            Session::flash('success', 'The Amount have been credited. ');
                            return redirect('/ico');
                        }
                    } else {
                        Session::flash('error', 'Insufficient Balance.');
                        return redirect('/ico');
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function ico1(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                if ($request->isMethod('post')) {
                    $second_currency = $request['sell_currency'];
                } else {
                    $second_currency = 'ETH';
                }
                $userid = Session::get('alphauserid');

                $ico_buy_trade = ICOTrade::where('user_id', $userid)->orderBy('id', 'Desc')->get();
                $currency = 'XDCE';
                $second_currency_bal = get_userbalance($userid, $second_currency);
                $currency_bal = get_userbalance($userid, $currency);
                $ico = ICORate::where('FirstCurrency', $currency)->where('SecondCurrency', $second_currency)->first();
                $amount = $ico->Amount;

                $sell_min_limit = CurrencyTradeLimit::where('currency', $second_currency)->first();
                $sell_min_limit = floatval($sell_min_limit->sell_min);


                return view('front.ico1', ['user_id' => $userid, 'sell_limit' => $sell_min_limit, 'second_currency' => $second_currency, 'second_currency_bal' => $second_currency_bal, 'currency' => $currency, 'currency_bal' => $currency_bal, 'amount' => $amount, 'Trade' => $ico_buy_trade]);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function ico_buy1(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                if ($request->isMethod('post')) {
                    $userid = Session::get('alphauserid');

                    $FirstCurrency = $request['first_currency'];
                    $SecondCurrency = $request['second_currency'];


                    $second_currency_bal = get_userbalance($userid, $SecondCurrency);
                    $First_currency_bal = get_userbalance($userid, $FirstCurrency);

                    $ico_rate = ICORate::where('FirstCurrency', $FirstCurrency)->where('SecondCurrency', $SecondCurrency)->first();

                    $ico_buy_rate = $ico_rate->Amount;
                    $ico_fee = $ico_rate->Fee;
                    $ico_discount = $ico_rate->Discount;

                    $SecondCurrency_amount = $request['second_currency_amount'];

                    $transid = 'TXD' . $userid . time();
                    $ip = \Request::ip();
                    $today = date('Y-m-d H:i:s');
                    if ($SecondCurrency_amount <= $second_currency_bal) {
                        $buy_first_currency = $SecondCurrency_amount * $ico_buy_rate;

                        $fee = $SecondCurrency_amount * ($ico_fee / 100);
                        $discount = $SecondCurrency_amount * ($ico_discount / 100);

                        $total = ($buy_first_currency + $discount) - $fee;

                        $updated_secondcurrency_bal = $second_currency_bal - $SecondCurrency_amount;

                        $updated_first_currency_bal = $First_currency_bal + $buy_first_currency;

                        //buy trade record
                        $ico_trade = new ICOTrade();
                        $ico_trade->Type = 'ICO';
                        $ico_trade->transaction_id = $transid;
                        $ico_trade->user_id = $userid;
                        $ico_trade->ip = $ip;
                        $ico_trade->FirstCurrency = $FirstCurrency;
                        $ico_trade->SecondCurrency = $SecondCurrency;
                        $ico_trade->Price = $ico_buy_rate;
                        $ico_trade->Amount = $SecondCurrency_amount;
                        $ico_trade->Fee = $fee;
                        $ico_trade->Discount = $discount;
                        $ico_trade->Total = $total;
                        $ico_trade->Status = 'Completed';
                        $ico_trade->Previous_Bal = $First_currency_bal;
                        $ico_trade->After_Bal = $updated_first_currency_bal;
                        $ico_trade->Previous_currency = $second_currency_bal;
                        $ico_trade->After_currency = $updated_secondcurrency_bal;


                        if ($ico_trade->save()) {
                            $lobjUserBal = UserBalance::where('user_id', $userid)->first();
                            $lobjUserBal->$SecondCurrency = $updated_secondcurrency_bal;
                            $lobjUserBal->$FirstCurrency = $updated_first_currency_bal;
                            $lobjUserBal->save();
                            Session::flash('success', 'The Amount have been credited. ');
                            return redirect('/testico');
                        }

                    } else {
                        $buy_first_currency = $SecondCurrency_amount * $ico_buy_rate;

                        $fee = $SecondCurrency_amount * ($ico_fee / 100);
                        $discount = $SecondCurrency_amount * ($ico_discount / 100);

                        $total = ($buy_first_currency + $discount) - $fee;

                        $updated_secondcurrency_bal = $second_currency_bal - $SecondCurrency_amount;

                        $updated_first_currency_bal = $First_currency_bal + $buy_first_currency;

                        //buy trade record
                        $ico_trade = new ICOTrade();
                        $ico_trade->Type = 'ICO';
                        $ico_trade->transaction_id = $transid;
                        $ico_trade->user_id = $userid;
                        $ico_trade->ip = $ip;
                        $ico_trade->FirstCurrency = $FirstCurrency;
                        $ico_trade->SecondCurrency = $SecondCurrency;
                        $ico_trade->Price = $ico_buy_rate;
                        $ico_trade->Amount = $SecondCurrency_amount;
                        $ico_trade->Fee = $fee;
                        $ico_trade->Discount = $discount;
                        $ico_trade->Total = $total;
                        $ico_trade->Status = 'Pending';
                        $ico_trade->Previous_Bal = $First_currency_bal;
                        $ico_trade->After_Bal = $updated_first_currency_bal;
                        $ico_trade->Previous_currency = $second_currency_bal;
                        $ico_trade->After_currency = $updated_secondcurrency_bal;
                        $ico_trade->created_at = $today;
                        $ico_trade->updated_at = $today;

                        if ($ico_trade->save()) {
                            $lobjUserBal = UserBalance::where('user_id', $userid)->first();
                            $lobjUserBal->$SecondCurrency = $updated_secondcurrency_bal;
                            $lobjUserBal->save();
                        }
                        Session::flash('success', 'Your order is pending will be completed once you deposit remaining amount');
                        return redirect('/testico');
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //pending request
    function pending_ico_order(Request $request)
    {
        try {
            $ico_buy_trade = ICOTrade::where('Status', 'Pending')->orderBy('id', 'Desc')->get();

            if ($ico_buy_trade) {
                foreach ($ico_buy_trade as $pending) {
                    $sell_currency = $pending->SecondCurrency;
                    $amount = $pending->Amount;
                    $total = $pending->Total;
                    $user_id = $pending->user_id;
                    $transid = $pending->transaction_id;
                    $created_at = $pending->created_at;
                    echo $created_at;
                    $after_currency = $pending->After_currency;

                    if ($pending->Previous_currency > 0) {
                        $difference_amount = $amount - $pending->Previous_currency;
                    } else {
                        $difference_amount = $amount;
                    }

                    //user current balance
                    $sell_currency_bal = get_userbalance($user_id, $sell_currency);

                    if ($sell_currency_bal > $after_currency) {
                        $user_deposits = Transaction::where('created_at', '>=', $created_at)->where('type', 'Deposit')
                            ->where('status', 'Completed')->where('currency_name', $sell_currency)->orderBy('id', 'desc')->sum('amount');
                        echo $user_deposits;
                        if ($user_deposits >= $difference_amount) {
                            $pending->Status = 'Completed';
                            $pending->save();
                            $Update_userbal = UserBalance::where('user_id', $user_id)->first();
                            $Update_userbal->XDCE = $Update_userbal->XDCE + $total;
                            if ($Update_userbal->save()) {
                                ico_mail($user_id, $amount, $transid, 'XDCE');
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //cancel trade
    function Cancel_pending_order($id)
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
                Session::flash('success', 'Your order is been cancelled');

                return redirect('/testico');
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //cancel pending order more than one day
    function old_pending_cancel()
    {
        try {
            $ico_buy_trade = ICOTrade::where('Status', 'Pending')->orderBy('id', 'Desc')->get();

            foreach ($ico_buy_trade as $trade) {
                $date = $trade->created_at;
                $now = Carbon::now();
                $end = Carbon::parse($date);
                $length = $end->diffInDays($now);
                echo($length);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

}
