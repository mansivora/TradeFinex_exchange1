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
use App\model\Charts;
use App\model\Currencies;
use App\model\Favorite;
use App\model\Pair;
use App\model\PairStats;
use App\model\Profit;
use App\model\Trade;
use App\model\TradeMapping;
use App\model\Tradingfee;
use App\model\ICOTrade;
use App\model\Transaction;
use App\model\UserBalance;
use App\model\UserBalancesNew;
use function GuzzleHttp\Psr7\_caseless_remove;
use Illuminate\Foundation\Auth\User;
use Pusher\Pusher;
use DB;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;

class TradeController extends Controller
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

    function index($pair = "")
    {
        try {
            if (Session::get('alphauserid') == "") {
                $userid = Session::get('alphauserid');
                // $pair = $pair ? $pair : 'ETH-USDT';
                $pair = $pair ? $pair : get_default_pair();

                $checkpair = Pair::where(['type' => 'trade', 'pair' => $pair])->count();
                if ($checkpair == 0) {
                    abort(404);
                }
                $cur = explode("-", $pair);
                $first_currency = $cur[0];
                $second_currency = $cur[1];

                $min_amount = min_trade($first_currency);

                $first_cur_balance = "";
                $second_cur_balance = "";

                if ($second_currency == 'XDCE') {
                    $buy_rate = 1;
                    $sell_rate = 1;
                    $trading_fee = 0;

                    $active_orders = "";

                } else {

                    $buy_rate = get_buy_market_rate($first_currency, $second_currency);
                    $sell_rate = get_sell_market_rate($first_currency, $second_currency);
                    $volume = get_trading_volume($first_currency, $second_currency, $userid);
                    $trading_fee = get_trade_fee('Buy', $pair);

                    $active_orders = "";
                }

//            $buy_order_list = Trade::select(DB::raw('SUM(total) as total,SUM(updated_total) as updated_total,status,price,SUM(updated_qty) as updated_qty'))->where(['pair' => $pair, 'type' => 'Sell'])->where(function ($query) {
//                $query->where('status', 'active')->Orwhere('status', 'partially');
//            })->groupBy('price')->orderBy('price', 'asc')->limit(5)->get();

                $buy_order_list = "";

                /*$buy_order_list = DB::select(DB::raw("SELECT * FROM `XDC_trade_order` WHERE pair='$pair' AND Type='Sell' AND (status='active' or status='partially') ORDER BY id DESC"));*/

//            $sell_order_list = Trade::select(DB::raw('SUM(total) as total,SUM(updated_total) as updated_total,status,price,SUM(updated_qty) as updated_qty'))->where(['pair' => $pair, 'type' => 'Buy'])->where(function ($query) {
//                $query->where('status', 'active')->Orwhere('status', 'partially');
//            })->groupBy('price')->orderBy('price', 'desc')->limit(5)->get();

                $sell_order_list = "";

                /*$sell_order_list = DB::select(DB::raw("SELECT * FROM `XDC_trade_order` WHERE pair='$pair' AND Type='Buy' AND (status='active' or status='partially') ORDER BY id DESC"));*/


                /*	$active_orders = DB::select(DB::raw("SELECT * FROM `XDC_trade_order` WHERE user_id='$userid' and pair='$pair' AND (status='active' or status='partially')"));*/

                $stop_orders = Trade::where(['pair' => $pair, 'user_id' => $userid, 'status' => 'stop'])->orderBy('id', 'desc')->get();
//            $trade_history = TradeMapping::where('pair', $pair)->orderBy('updated_at', 'desc')->limit(16)->get();
                $trade_history = "";
                $currencies = Currencies::all();


                $fav = "";
                $favarr = "";

                if (isset($favarr[0])) {
                    $get_fav_pairs = Pair::whereIn('id', $favarr)->get();
                    if ($get_fav_pairs) {
                        foreach ($get_fav_pairs as $get_fav_pair) {

                            $get_pair_stat = PairStats::where('pair_id', $get_fav_pair->id)->first();
                            $explode = explode('-', $get_fav_pair->pair);
                            $first_currency1 = $explode[0];
                            $currency = $explode[1];
                            $pair1 = $get_fav_pair->pair;
                            $id = $get_pair_stat->id;
                            $pair_id = $get_pair_stat->pair_id;
                            $vol = $get_pair_stat->volume;
                            $low = $get_pair_stat->low;
                            $high = $get_pair_stat->high;
                            $last = $get_pair_stat->last;
                            $percentage_change = $get_pair_stat->percent_change . '%';
                            $change = $get_pair_stat->change;
                            $color = strtolower($get_pair_stat->colour);

                            $array = array('id' => $id, 'pair_id' => $pair_id, 'first_currency' => $first_currency1, 'currency' => $currency, 'Pair' => $pair1, 'Volume' => $vol, 'Low' => $low, 'High' => $high, 'Percentage' => $percentage_change, 'Change' => $change, 'Colour' => $color, 'Last' => $last);
                            $fav_pair[] = $array;

                        }
                    }
                } else {
                    $fav_pair = array();
                }

                if (isset($favarr[0])) {
                    $get_remain_pairs = Pair::wherenotIn('id', $favarr)->get();
                } else {
                    $get_remain_pairs = Pair::all();
                }
                if ($get_remain_pairs) {
                    foreach ($get_remain_pairs as $get_remain_pair) {

                        $get_pair_stat = PairStats::where('pair_id', $get_remain_pair->id)->first();
                        $explode = explode('-', $get_remain_pair->pair);
                        $first_currency1 = $explode[0];
                        $currency = $explode[1];
                        $pair1 = $get_remain_pair->pair;
                        $id = $get_pair_stat->id;
                        $pair_id = $get_pair_stat->pair_id;
                        $vol = $get_pair_stat->volume;
                        $low = $get_pair_stat->low;
                        $high = $get_pair_stat->high;
                        $last = $get_pair_stat->last;
                        $percentage_change = $get_pair_stat->percent_change . '%';
                        $change = $get_pair_stat->change;
                        $color = strtolower($get_pair_stat->colour);

                        $array = array('id' => $id, 'pair_id' => $pair_id, 'first_currency' => $first_currency1, 'currency' => $currency, 'Pair' => $pair1, 'Volume' => $vol, 'Low' => $low, 'High' => $high, 'Percentage' => $percentage_change, 'Change' => $change, 'Colour' => $color, 'Last' => $last);
                        $result_pair[] = $array;

                    }
                }

                $get_all_pairs = Pair::all();
                if ($get_all_pairs) {
                    foreach ($get_all_pairs as $get_all_pair) {
                        $this->update_pairstats($get_all_pair->id);
                        $get_pair_stat = PairStats::where('pair_id', $get_all_pair->id)->first();
                        $explode = explode('-', $get_all_pair->pair);
                        $first_currency1 = $explode[0];
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

                        $array = array('id' => $id, 'pair_id' => $pair_id, 'first_currency' => $first_currency1, 'currency' => $currency, 'Pair' => $pair1, 'Volume' => $vol, 'Low' => $low, 'High' => $high, 'Percentage' => $percentage_change, 'Change' => $change, 'Colour' => $color, 'Last' => $last);
                        $all_pair[] = $array;

                    }
                }

                $user_trade = "";

                $data = ['currency' => $currencies, 'pair' => $pair, 'first_currency' => $first_currency, 'second_currency' => $second_currency, 'first_cur_balance' => $first_cur_balance, 'second_cur_balance' => $second_cur_balance, 'buy_rate' => (float)$buy_rate, 'sell_rate' => (float)$sell_rate, 'trading_fee' => $trading_fee, 'buy_order_list' => $buy_order_list, 'sell_order_list' => $sell_order_list, 'active_orders' => $active_orders, 'stop_orders' => $stop_orders, 'trade_history' => $trade_history, 'fav_pairs' => $fav_pair, 'remain_pairs' => $result_pair, 'user_trade' => $user_trade, 'user_id' => $userid, 'fav' => $fav, 'pairs' => $all_pair, 'min_trade' => $min_amount];

            } else {
                $userid = Session::get('alphauserid');
//                if (get_user_details($userid, 'document_status') != '1') {
//                     Session::flash('error','Please Complete your KYC process, to access the platform.');
//                     return redirect('profile');
//                } else {
                $transid = 'TXD' . $userid . time();

                $xdcaddr = get_user_details($userid, 'XDC_addr');
                $xdceaddr = get_user_details($userid, 'XDCE_addr');
                //$xdcbal = 0;
                // $pair = $pair ? $pair : 'ETH-USDT';
                $pair = $pair ? $pair : get_default_pair();

                $fav = Favorite::where('user_id', $userid)->get();
                if (count($fav) > 0) {
                    foreach ($fav as $key => $favs)
                        $favarr[$key] = $favs->pair_id;
                }

                $checkpair = Pair::where(['type' => 'trade', 'pair' => $pair])->count();
                if ($checkpair == 0) {
                    abort(404);
                }
                $cur = explode("-", $pair);
                $first_currency = $cur[0];
                $second_currency = $cur[1];

                $min_amount = min_trade($first_currency);

                $first_cur_balance = get_userbalance($userid, $first_currency);
                $second_cur_balance = get_userbalance($userid, $second_currency);

                if ($second_currency == 'XDCE') {
                    $buy_rate = 1;
                    $sell_rate = 1;
                    $trading_fee = 0;

                    $active_orders = "";

                } else {
                    $buy_rate = get_buy_market_rate($first_currency, $second_currency);
                    $sell_rate = get_sell_market_rate($first_currency, $second_currency);
                    $volume = get_trading_volume($first_currency, $second_currency, $userid);
                    $trading_fee = get_trade_fee('Buy', $pair);

//                $active_orders = Trade::where(['user_id' => $userid, 'pair' => $pair])->where(function ($query) {
//                    $query->where('status', 'active')->Orwhere('status', 'partially');
//                })->orderBy('id', 'desc')->limit(5)->get();
                    $active_orders = "";
                }

//            $buy_order_list = Trade::select(DB::raw('SUM(total) as total,SUM(updated_total) as updated_total,status,price,SUM(updated_qty) as updated_qty'))->where(['pair' => $pair, 'type' => 'Sell'])->where(function ($query) {
//                $query->where('status', 'active')->Orwhere('status', 'partially');
//            })->groupBy('price')->orderBy('price', 'asc')->limit(5)->get();

                $buy_order_list = "";

                /*$buy_order_list = DB::select(DB::raw("SELECT * FROM `XDC_trade_order` WHERE pair='$pair' AND Type='Sell' AND (status='active' or status='partially') ORDER BY id DESC"));*/

//            $sell_order_list = Trade::select(DB::raw('SUM(total) as total,SUM(updated_total) as updated_total,status,price,SUM(updated_qty) as updated_qty'))->where(['pair' => $pair, 'type' => 'Buy'])->where(function ($query) {
//                $query->where('status', 'active')->Orwhere('status', 'partially');
//            })->groupBy('price')->orderBy('price', 'desc')->limit(5)->get();

                $sell_order_list = "";

                /*$sell_order_list = DB::select(DB::raw("SELECT * FROM `XDC_trade_order` WHERE pair='$pair' AND Type='Buy' AND (status='active' or status='partially') ORDER BY id DESC"));*/


                /*	$active_orders = DB::select(DB::raw("SELECT * FROM `XDC_trade_order` WHERE user_id='$userid' and pair='$pair' AND (status='active' or status='partially')"));*/

                $stop_orders = Trade::where(['pair' => $pair, 'user_id' => $userid, 'status' => 'stop'])->orderBy('id', 'desc')->get();
//            $trade_history = TradeMapping::where('pair', $pair)->orderBy('updated_at', 'desc')->limit(16)->get();

                $trade_history = "";

                $currencies = Currencies::all();

                if (isset($favarr[0])) {
                    $get_fav_pairs = Pair::whereIn('id', $favarr)->get();
                    if ($get_fav_pairs) {
                        foreach ($get_fav_pairs as $get_fav_pair) {

                            $get_pair_stat = PairStats::where('pair_id', $get_fav_pair->id)->first();
                            $explode = explode('-', $get_fav_pair->pair);
                            $first_currency1 = $explode[0];
                            $currency = $explode[1];
                            $pair1 = $get_fav_pair->pair;
                            $id = $get_pair_stat->id;
                            $pair_id = $get_pair_stat->pair_id;
                            $vol = $get_pair_stat->volume;
                            $low = $get_pair_stat->low;
                            $high = $get_pair_stat->high;
                            $last = $get_pair_stat->last;
                            $percentage_change = $get_pair_stat->percent_change . '%';
                            $change = $get_pair_stat->change;
                            $color = strtolower($get_pair_stat->colour);

                            $array = array('id' => $id, 'pair_id' => $pair_id, 'first_currency' => $first_currency1, 'currency' => $currency, 'Pair' => $pair1, 'Volume' => $vol, 'Low' => $low, 'High' => $high, 'Percentage' => $percentage_change, 'Change' => $change, 'Colour' => $color, 'Last' => $last);
                            $fav_pair[] = $array;

                        }
                    }
                } else {
                    $fav_pair = array();
                }

                if (isset($favarr[0])) {
                    $get_remain_pairs = Pair::wherenotIn('id', $favarr)->get();
                } else {
                    $get_remain_pairs = Pair::all();
                }
                if (isset($get_remain_pairs[0])) {
                    foreach ($get_remain_pairs as $get_remain_pair) {

                        $get_pair_stat = PairStats::where('pair_id', $get_remain_pair->id)->first();
                        $explode = explode('-', $get_remain_pair->pair);
                        $first_currency1 = $explode[0];
                        $currency = $explode[1];
                        $pair1 = $get_remain_pair->pair;
                        $id = $get_pair_stat->id;
                        $pair_id = $get_pair_stat->pair_id;
                        $vol = $get_pair_stat->volume;
                        $low = $get_pair_stat->low;
                        $high = $get_pair_stat->high;
                        $last = $get_pair_stat->last;
                        $percentage_change = $get_pair_stat->percent_change . '%';
                        $change = $get_pair_stat->change;
                        $color = strtolower($get_pair_stat->colour);

                        $array = array('id' => $id, 'pair_id' => $pair_id, 'first_currency' => $first_currency1, 'currency' => $currency, 'Pair' => $pair1, 'Volume' => $vol, 'Low' => $low, 'High' => $high, 'Percentage' => $percentage_change, 'Change' => $change, 'Colour' => $color, 'Last' => $last);
                        $result_pair[] = $array;

                    }
                } else {
                    $result_pair = array();
                }

                $get_all_pairs = Pair::all();
                if ($get_all_pairs) {
                    foreach ($get_all_pairs as $get_all_pair) {
                        $this->update_pairstats($get_all_pair->id);
                        $get_pair_stat = PairStats::where('pair_id', $get_all_pair->id)->first();
                        $explode = explode('-', $get_all_pair->pair);
                        $first_currency1 = $explode[0];
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


                        $array = array('id' => $id, 'pair_id' => $pair_id, 'first_currency' => $first_currency1, 'currency' => $currency, 'Pair' => $pair1, 'Volume' => $vol, 'Low' => $low, 'High' => $high, 'Percentage' => $percentage_change, 'Change' => $change, 'Colour' => $color, 'Last' => $last);
                        $all_pair[] = $array;

                    }
                }

                $user_trade = Trade::where(['pair' => $pair, 'user_id' => $userid])->whereIn('status', ['completed', 'partially'])->orderBy('updated_at', 'desc')->limit(10)->get();

                $data = ['currency' => $currencies, 'pair' => $pair, 'first_currency' => $first_currency, 'second_currency' => $second_currency, 'first_cur_balance' => $first_cur_balance, 'second_cur_balance' => $second_cur_balance, 'buy_rate' => (float)$buy_rate, 'sell_rate' => (float)$sell_rate, 'trading_fee' => $trading_fee, 'buy_order_list' => $buy_order_list, 'sell_order_list' => $sell_order_list, 'active_orders' => $active_orders, 'stop_orders' => $stop_orders, 'trade_history' => $trade_history, 'fav_pairs' => $fav_pair, 'remain_pairs' => $result_pair, 'user_trade' => $user_trade, 'user_id' => $userid, 'fav' => $fav, 'pairs' => $all_pair, 'min_trade' => $min_amount];

//            return view('front.construction');
//                }
            }
            return view('front.trade', $data);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

//    function get_trading_fee($vol, $second_currency)
//    {
//        $result = Tradingfee::where('currency', $second_currency)->first();
//        if ($result) {
//            if ($vol < 20000) {
//                return $result->lessthan_20000;
//            } elseif ($vol < 100000) {
//                return $result->lessthan_100000;
//            } elseif ($vol < 200000) {
//                return $result->lessthan_200000;
//            } elseif ($vol < 400000) {
//                return $result->lessthan_400000;
//            } elseif ($vol < 600000) {
//                return $result->lessthan_600000;
//            } elseif ($vol < 1000000) {
//                return $result->lessthan_1000000;
//            } elseif ($vol < 2000000) {
//                return $result->lessthan_2000000;
//            } elseif ($vol < 4000000) {
//                return $result->lessthan_4000000;
//            } elseif ($vol < 20000000) {
//                return $result->lessthan_20000000;
//            } else {
//                return $result->greaterthan_20000000;
//            }
//
//        }
//    }

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

    //limit order
    function trade_orders(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                if ($request->isMethod('post')) {
                    $type = $request['type'];
                    if ($type == 'Buy')
                        $pair = $request['pair-buy'];
                    else
                        $pair = $request['pair-sell'];
                    $user_id = Session::get('alphauserid');
                    $trade_type = $request['tradetype'];
                    if ($type == 'Buy') {
                        $amount = $request['buy_amount'];
                        $price = $request['buy_price'];
//                        $rate = $request['buy_rate'];
//                        $upper_limit = $rate*1.3;
//                        $lower_limit = $rate*0.7;
//                        if($lower_limit <= $price && $price <= $upper_limit)
//                        {
//                        }
//                        else
//                        {
//                            $message = 'Maximum 30% +/- deviation allowed from buy price.';
//                            $data['status'] = '4';
//                            $data['message'] = $message;
//                            return json_encode($data);
//                        }
                        $matching_type = 'Sell';
                        $matching_trade = Trade::where('user_id', $user_id)
                            ->where('type', $matching_type)->where('price', '<=', $price)
                            ->where('pair', $pair)
                            ->where(function ($query) {
                                $query->where('status', 'active')->orWhere('status', 'partially');
                            })->count();
                        $message = 'You have already a Sell order less than or equal to buy price';
                    } else {
                        $amount = $request['sell_amount'];
                        $price = $request['sell_price'];
//                        $rate = $request['sell_rate'];
//                        $upper_limit = $rate*1.3;
//                        $lower_limit = $rate*0.7;
//                        if($lower_limit <= $price && $price <= $upper_limit)
//                        {
//                        }
//                        else
//                        {
//                            $message = 'Maximum 30% +/- deviation allowed from sell price.';
//                            $data['status'] = '4';
//                            $data['message'] = $message;
//                            return json_encode($data);
//                        }
                        $matching_type = 'Buy';
                        $matching_trade = Trade::where('user_id', $user_id)
                            ->where('type', $matching_type)->where('price', '>=', $price)
                            ->where('pair', $pair)
                            ->where(function ($query) {
                                $query->where('status', '=', 'active')->orWhere('status', '=', 'partially');
                            })->count();
                        $message = 'You have already a Buy order greater than or equal to sell price';
                    }

                    if (!($price > 0)) {
                        $data['status'] = '4';
                        $data['message'] = 'Price should be greater than 0.';
                        return json_encode($data);
                    }

                    if ($matching_trade > 0) {
                        $data['status'] = '4';
                        $data['message'] = $message;
                        return json_encode($data);
                    } else {

                        $cur = explode("-", $pair);
                        $first_currency = $cur[0];
                        $second_currency = $cur[1];

                        $min_amount = min_trade($first_currency);

                        if ($amount < $min_amount) {
                            $data['status'] = 0;
                            $data['message'] = 'Minimum ' . $min_amount . ' ' . $first_currency . '.';
                            return json_encode($data);
                        }

                        $first_cur_balance = get_userbalance($user_id, $first_currency);
                        $second_cur_balance = get_userbalance($user_id, $second_currency);

                        $get_pair_id = Pair::where('pair', $pair)->first();

                        $amount = number_format($amount, 3, '.', '');
                        $price = number_format($price, 3, '.', '');

//                    if($price<0.3)
//                    {
//                        $data['status'] = '4';
//                        $data['message'] = 'Price should be greater than 0.3';
//                        return json_encode($data);
//                    }

                        $total = $amount * $price;

                        if ($type == 'Buy') {
                            $get_txn_fee = get_trade_fee($type, $pair);
                            $trade_fee = $total * $get_txn_fee;
                            $trade_fee = number_format($trade_fee, 12, '.', '');
                            $total = $total + $trade_fee;
                            $deduct_bal = $total;
                            $available_bal = $second_cur_balance;
                            $currency = $second_currency;
                            $update_balance = $second_cur_balance - $deduct_bal;
                            $txd_id = 'BTX' . time() . mt_rand(0, 999);
                        } else {
                            $get_txn_fee = get_trade_fee($type, $pair);
                            $trade_fee = $total * $get_txn_fee;
                            $trade_fee = number_format($trade_fee, 12, '.', '');
                            $total = $total - $trade_fee;
                            $deduct_bal = $amount;
                            $available_bal = $first_cur_balance;
                            $currency = $first_currency;
                            $update_balance = $first_cur_balance - $amount;
                            $txd_id = 'STX' . time() . mt_rand(0, 999);
                        }

                        if ($deduct_bal <= $available_bal) {
                            $trade = new Trade();
                            if ($trade_type == 'stop_limit') {
                                $stop_limit = $request['stop'];
                                $trade->stoporderprice = $stop_limit;
                            }

                            $trade->unique_id = $txd_id;
                            $trade->trade_id = $txd_id;
                            $trade->trade_type = $trade_type;
                            $trade->user_id = $user_id;
                            $trade->pair_id = $get_pair_id->id;
                            $trade->pair = $pair;
                            $trade->firstCurrency = $first_currency;
                            $trade->secondCurrency = $second_currency;
                            $trade->price = $price;
                            $trade->total = $total;
                            $trade->type = $type;
                            $trade->process = '0';
                            $trade->fee = $trade_fee;
                            $trade->original_qty = $amount;
                            $trade->updated_qty = $amount;

                            $trade->status = 'active';

                            if ($trade->save()) {
//                            $user_bal = UserBalance::where('user_id', $user_id)->first();
//                            $user_bal->$currency = $update_balance;
//                            $user_bal->save();

                                $user_bal = UserBalancesNew::where('user_id', $user_id)->where('currency_name', $currency)->first();
                                $user_bal->balance = $update_balance;
                                $user_bal->save();

                                //user activity
                                last_activity(get_usermail($user_id), 'Limit Buy order', $user_id);

                                //id
                                $active_id = $trade->id;

                                //find trade now

                                $this->find_trades($active_id, $type, $price, $amount, $pair);

                                $trade_status = Trade::where('id', $active_id)->first();
                                $status = $trade_status->status;
                                if ($status == 'completed') {
                                    $data['status'] = '1';
                                    $data['id'] = $user_id;
                                    $data['message'] = 'Order is been Completed';
                                } else if ($status == 'active' || $status == 'partially') {
                                    $amount = $trade_status->updated_qty;
                                    $price = $trade_status->price;
                                    $pusher_total = $amount * $price;
                                    $trade_fee = $pusher_total * $get_txn_fee;
                                    if ($type == 'Buy') {
                                        $pusher_total = number_format($pusher_total + $trade_fee, 4, '.', '');
                                    } else {
                                        $pusher_total = number_format($pusher_total - $trade_fee, 4, '.', '');
                                    }

                                    $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));

                                    $pusher->trigger('trade', 'trade-event', array('User_id' => $user_id, 'Pair' => $pair, 'Total' => $pusher_total, 'Amount' => number_format($amount, 0, '.', ''), 'Price' => number_format($price, 8, '.', ''), 'Type' => $type));

                                    $data['status'] = '2';
                                    $data['message'] = 'Your Order placed';
                                } else {
                                    $data['status'] = '3';
                                    $data['message'] = 'Your order is partially executed';
                                }

                                $this->update_pairstats($get_pair_id->id);
                                return json_encode($data);
                            } else {
                                $data['status'] = '0';
                                $data['message'] = 'Some error occurred while placing trade';
                                return json_encode($data);
                            }

                        } else {
                            $data['status'] = '0';
                            $data['message'] = 'Insufficient Balance of ' . $currency;
                            return json_encode($data);
                        }
                    }
                }  //post method
            }
        } catch (\Exception $exception) {
            echo $exception->getMessage();
            return view('front.error');
        }

    }

    function market_orders(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                $pair = $request['pair'];
            }
        } catch (\Exception $exception) {
            $message = $exception->getLine() . '<br>' . $exception->getMessage() . '<br>' . $exception->getFile();
            $data['status'] = 500;
            $data['message'] = 'Server Error';
            return json_encode($data);
        }
    }

    //find trades
    function find_trades($active_id, $type, $price, $amount, $pair)
    {
        try {
            $find_trade_type = ($type == 'Buy') ? 'Sell' : 'Buy';
            $trade_user_id = $this->single_trade_details($active_id, 'user_id');
            if ($type == 'Buy') {

                $findorders = Trade::where('user_id', '<>', $trade_user_id)->where('price', '<=', $price)->where(['pair' => $pair, 'type' => $find_trade_type])->where(function ($query) {
                    $query->where('status', 'active')->Orwhere('status', 'partially');
                })->orderBy('price', 'asc')->get();

            } else {
                $findorders = Trade::where('user_id', '<>', $trade_user_id)->where('price', '>=', $price)->where(['pair' => $pair, 'type' => $find_trade_type])->where(function ($query) {
                    $query->where('status', 'active')->Orwhere('status', 'partially');
                })->orderBy('price', 'desc')->get();
            }

            $order_process_result = 0;

            //iterating each record
            foreach ($findorders as $foundorder) {
                $found_order_amount = $foundorder->updated_qty;
                $pairid = $foundorder->pair_id;
                if ($found_order_amount > $amount) {
                    $active_status = 'completed';
                    $found_status = 'partially';
                    $executed_price = $foundorder->price;


                    //active order completed
                    $order_process_result = $this->order_process($active_status, $found_status, $active_id, $foundorder->id, $amount, $executed_price);
                    $this->update_pairstats($pairid);

                    break;
                } else if ($found_order_amount == $amount) {
                    $active_status = 'completed';
                    $found_status = 'completed';
                    $executed_price = $foundorder->price;


                    //active order completed
                    $order_process_result = $this->order_process($active_status, $found_status, $active_id, $foundorder->id, $amount, $executed_price);
                    $this->update_pairstats($pairid);

                    break;
                } else {
                    $active_status = 'partially';
                    $found_status = 'completed';
                    $executed_price = $foundorder->price;


                    //active order completed
                    $order_process_result = $this->order_process($active_status, $found_status, $active_id, $foundorder->id, $found_order_amount, $executed_price);
                    $this->update_pairstats($pairid);


                    $amount = $amount - $found_order_amount;
                    if ($amount == 0) {
                        $order_process_result = 1;
                        break;
                    }

                }
            }
            return $order_process_result;
        } catch (\Exception $exception) {
            return $exception->getMessage() . ' ' . $exception->getFile() . ' ' . $exception->getLine();

        }
    }

    //order completed
    function order_process($user_status, $trader_status, $user_trade_id, $trader_id, $amount, $executed_price)
    {
        try {

            DB::transaction(function () use ($user_status, $trader_status, $user_trade_id, $trader_id, $amount, $executed_price) {
                $trade = Trade::where('id', $trader_id)->first();

                $up_amt = $trade->updated_qty;
                if ($up_amt >= $amount) {
                    $trader_user_id = $trade->user_id;
                    $trader_type = $trade->type;
                    $pair = $trade->pair;
                    $cur = explode("-", $pair);
                    $first_currency = $cur[0];
                    $second_currency = $cur[1];
                    $trader_total = ($amount * $executed_price);

                    $trader_expected_total = ($amount * $trade->price);
                    if ($trader_type == 'Buy') {

                        $trading_fee = get_trade_fee('Buy', $pair);
                        $trader_fee_charged = $trader_expected_total * $trading_fee;
                        $trader_expected_fee = $trader_total * $trading_fee;
                    } else {
                        $trading_fee = get_trade_fee('Sell', $pair);;
                        $trader_fee_charged = $trader_expected_total * $trading_fee;
                        $trader_expected_fee = $trader_total * $trading_fee;
                    }


                    $trade->status = $trader_status;
                    $upt_qty = $trade->updated_qty - $amount;
                    $trade->updated_qty = $trade->updated_qty - $amount;


                    if ($trader_type == 'Buy') {
                        $trade->updated_total = $trade->updated_total + $trader_total + $trader_expected_fee;

                        $trade->total = ($upt_qty * $trade->price) + ($upt_qty * $trade->price * $trading_fee);
                    } else {
                        $trade->updated_total = $trade->updated_total + $trader_total - $trader_expected_fee;
                        $trade->total = ($upt_qty * $trade->price) - ($upt_qty * $trade->price * $trading_fee);
                    }

                    $trade->save();

//                    $trader_user_balance = UserBalance::where('user_id', $trader_user_id)->first();
                    $trader_user_balance_fc = UserBalancesNew::where('user_id', $trader_user_id)->where('currency_name', $first_currency)->first();
                    $trader_user_balance_sc = UserBalancesNew::where('user_id', $trader_user_id)->where('currency_name', $second_currency)->first();
                    if ($trader_type == 'Buy') {
                        if ($executed_price < $trade->price) {
                            $return_total = ($trader_expected_total - $trader_total) + ($trader_fee_charged - $trader_expected_fee);
                        } else {
                            $return_total = 0;
                        }

//                        $trader_user_balance->$first_currency = $trader_user_balance->$first_currency + $amount;
//                        $trader_user_balance->$second_currency = $trader_user_balance->$second_currency + $return_total;
                        $trader_user_balance_fc->balance = $trader_user_balance_fc->balance + $amount;
                        $trader_user_balance_sc->balance = $trader_user_balance_sc->balance + $return_total;
                        $trader_user_balance_fc->save();
                        $trader_user_balance_sc->save();


                    } else {
                        $updated_total = $trader_total - $trader_expected_fee;
//                        $trader_user_balance->$second_currency = $trader_user_balance->$second_currency + $updated_total;
                        $trader_user_balance_sc->balance = $trader_user_balance_sc->balance + $updated_total;
                        $trader_user_balance_sc->save();

                    }

                    //user trade process
                    $user_trade = Trade::where('id', $user_trade_id)->first();
                    $user_user_id = $user_trade->user_id;
                    $user_trade_type = $user_trade->type;

                    $user_trade_expected_total = ($amount * $user_trade->price);

                    if ($user_trade_type == 'Buy') {
                        $trading_fee = get_trade_fee('Buy', $pair);;
                        $user_trade_fee_charged = $trader_expected_total * $trading_fee;
                        $user_trader_expected_fee = $trader_total * $trading_fee;
                    } else {
                        $trading_fee = get_trade_fee('Sell', $pair);;
                        $user_trade_fee_charged = $trader_expected_total * $trading_fee;
                        $user_trader_expected_fee = $trader_total * $trading_fee;
                    }

                    $user_trade->status = $user_status;
                    $user_updated_qty = $user_trade->updated_qty - $amount;
                    $user_trade->updated_qty = $user_trade->updated_qty - $amount;

                    if ($user_trade_type == 'Buy') {
                        $user_trade->updated_total = $user_trade->updated_total + $trader_total + $user_trader_expected_fee;
                        $user_trade->total = ($user_updated_qty * $user_trade->price) + ($user_updated_qty * $user_trade->price * $trading_fee);
                    } else {
                        $user_trade->updated_total = $user_trade->updated_total + $trader_total - $user_trader_expected_fee;
                        $user_trade->total = ($user_updated_qty * $user_trade->price) - ($user_updated_qty * $user_trade->price * $trading_fee);
                    }

                    $user_trade->save();

//                    $user_user_balance = UserBalance::where('user_id', $user_user_id)->first();
                    $user_user_balance_fc = UserBalancesNew::where('user_id', $user_user_id)->where('currency_name', $first_currency)->first();
                    $user_user_balance_sc = UserBalancesNew::where('user_id', $user_user_id)->where('currency_name', $second_currency)->first();
                    if ($user_trade_type == 'Buy') {

                        if ($executed_price < $user_trade->price) {
                            $return_total = ($user_trade_expected_total - $trader_total) + ($user_trade_fee_charged - $user_trader_expected_fee);
                        } else {
                            $return_total = 0;
                        }

//                        $user_user_balance->$first_currency = $user_user_balance->$first_currency + $amount;
//                        $user_user_balance->$second_currency = $user_user_balance->$second_currency + $return_total;
                        $user_user_balance_fc->balance = $user_user_balance_fc->balance + $amount;
                        $user_user_balance_sc->balance = $user_user_balance_sc->balance + $return_total;
                        $user_user_balance_fc->save();
                        $user_user_balance_sc->save();


                    } else {

                        $updated_total = $trader_total - $user_trader_expected_fee;
//                        $user_user_balance->$second_currency = $user_user_balance->$second_currency + $updated_total;
                        $user_user_balance_sc->balance = $user_user_balance_sc->balance + $updated_total;
                        $user_user_balance_sc->save();

                    }

                    //admin  trade profit
                    $Trader_admin_coin_teft = new Profit();
                    $Trader_admin_coin_teft->userId = $trader_user_id;
                    $Trader_admin_coin_teft->type = 'Trd-' . $trade->type;
                    $Trader_admin_coin_teft->record_id = $trader_id;
                    $Trader_admin_coin_teft->theftAmount = $trader_expected_fee;
                    $Trader_admin_coin_teft->theftCurrency = $second_currency;
                    $Trader_admin_coin_teft->date = date('Y-m-d');
                    $Trader_admin_coin_teft->time = date('H:i:s');
                    $Trader_admin_coin_teft->save();

                    //admin  trade profit
                    $user_admin_coin_teft = new Profit();
                    $user_admin_coin_teft->userId = $user_user_id;
                    $user_admin_coin_teft->type = 'Trd-' . $user_trade->type;
                    $user_admin_coin_teft->record_id = $user_trade_id;
                    $user_admin_coin_teft->theftAmount = $user_trader_expected_fee;
                    $user_admin_coin_teft->theftCurrency = $second_currency;
                    $user_admin_coin_teft->date = date('Y-m-d');
                    $user_admin_coin_teft->time = date('H:i:s');
                    $user_admin_coin_teft->save();

                    //trade history


                    //trade mapping

                    if ($user_trade_type == 'Buy') {
                        $buy_id = $user_trade_id;
                        $sell_id = $trader_id;
                    } else {
                        $buy_id = $trader_id;
                        $sell_id = $user_trade_id;
                    }
                    $trade_mapping = new TradeMapping();
                    $trade_mapping->unique_id = 'MTX' . time();
                    $trade_mapping->pair = $pair;
                    $trade_mapping->buy_trade_order_id = $buy_id;
                    $trade_mapping->sell_trade_order_id = $sell_id;
                    $trade_mapping->type = $user_trade_type;
                    $trade_mapping->triggered_price = $executed_price;
                    $trade_mapping->triggered_qty = $amount;
                    if ($user_trade_type == 'Buy') {
                        $trade_mapping->total = $trader_total + $user_trader_expected_fee;
                    } else {
                        $trade_mapping->total = $trader_total - $user_trader_expected_fee;
                    }
                    $trade_mapping->save();


//                    //charts data
//                    $xdc_charts = new Charts();
//                                        $vdate = date('Y-m-d H:i:s');
//
//                                        $high = $this->trade_max_value($vdate,$pair);
//                                        $open = $this->trade_open_value($vdate,$pair);
//                                        $close = $this->trade_close_value($vdate,$pair);
//                                        $low = $this->trade_min_value($vdate,$pair);
//
//                                        $xdc_charts->datetime = $vdate;
//                                        $xdc_charts->high = $high;
//                                        $xdc_charts->millsec = strtotime($vdate) * 1000;
//                                        $xdc_charts->open = $open;
//                                        $xdc_charts->low = $low;
//                                        $xdc_charts->volume = $amount;
//                                        $xdc_charts->close = $close;
//                                        $xdc_charts->pair = $pair;
//
//                                        $xdc_charts->save();
                    $this->trade_chart($pair);

                    $trade_mapping_data = TradeMapping::where('id', $trade_mapping->id)->first();

                    $date = $trade_mapping_data->created_at->format('Y-m-d H:i:s');
                    //for trade history
                    $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));


                    $pusher->trigger('trade1', 'history-trade', array('Pair' => $pair, 'Time' => $date, 'Type' => $user_trade_type, 'Amount' => number_format($amount, 3, '.', ''), 'Price' => number_format($executed_price, 3, '.', ''), 'Total' => number_format($trade_mapping_data->total, 4, '.', '')));


                    //for pusher
                    $pusher->trigger('order', 'completed-history', array('trade_status' => $trader_status, 'user_status' => $user_status, 'Pair' => $pair, 'user_id' => $user_user_id, 'trade_id' => $trader_user_id, 'Type' => $user_trade_type,
                        'Amount' => number_format($amount, 0, '.', ''),
                        'Price' => number_format($executed_price, 8, '.', ''),
                        'Total' => number_format($trade_mapping_data->total, 8, '.', ''),
                        'Fee' => $trading_fee));

                    return 1;

                } else {

                    return 0;
                }

            });
        } catch (\Exception $exception) {

            return 0;
        }
    }

    function market_order(Request $request)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                $type = $request['type'];
                if ($type == 'Buy')
                    $pair = $request['pair-buy_market'];
                else
                    $pair = $request['pair-sell_market'];
                $user_id = Session::get('alphauserid');
                $trade_type = $request['tradetype'];

                if ($type == 'Buy') {
                    $amount = $request['buy_amount_market'];
                    $price = $request['buy_rate_market'];
                } else {
                    $amount = $request['sell_amount_market'];
                    $price = $request['sell_rate_market'];
                }

                $cur = explode("-", $pair);
                $first_currency = $cur[0];
                $second_currency = $cur[1];

                if ($type === 'Buy') {
                    $find_orders = Trade::where('user_id', '<>', $user_id)->where(['pair' => $pair, 'type' => 'Sell'])->where(function ($query) {
                        $query->where('status', 'active')->Orwhere('status', 'partially');
                    })->orderBy('price', 'asc')->sum('updated_qty');
                    $find_buy_orders = number_format($find_orders, 3, '.', '');
                    if ($amount > $find_buy_orders) {
                        $data['status'] = '0';
                        $data['message'] = 'You cannot place a market buy order of amount greater than ' . $find_buy_orders . ' ' . $first_currency;
                        return json_encode($data);
                    }
                } else {
                    $find_orders = Trade::where('user_id', '<>', $user_id)->where(['pair' => $pair, 'type' => 'Buy'])->where(function ($query) {
                        $query->where('status', 'active')->Orwhere('status', 'partially');
                    })->orderBy('price', 'desc')->sum('updated_qty');
                    $find_sell_orders = number_format($find_orders, 3, '.', '');
                    if ($amount > $find_sell_orders) {
                        $data['status'] = '0';
                        $data['message'] = 'You cannot place a market sell order of amount greater than ' . $find_sell_orders . ' ' . $first_currency;
                        return json_encode($data);
                    }
                }

                $first_cur_balance = get_userbalance($user_id, $first_currency);
                $second_cur_balance = get_userbalance($user_id, $second_currency);

                $get_pair_id = Pair::where('pair', $pair)->first();

                $price = number_format($price, 8, '.', '');
                $total = $amount * $price;

                if ($type == 'Buy') {
                    $get_txn_fee = get_trade_fee($type, $pair);
                    $trade_fee = $total * $get_txn_fee;
                    $trade_fee = number_format($trade_fee, 12, '.', '');
                    $total = $total + $trade_fee;
                    $deduct_bal = $total;
                    $available_bal = $second_cur_balance;
                    $currency = $second_currency;
                    $update_balance = $second_cur_balance - $deduct_bal;
                    $txd_id = 'BTX' . time() . mt_rand(0, 999);
                } else {
                    $get_txn_fee = get_trade_fee($type, $pair);
                    $trade_fee = $total * $get_txn_fee;
                    $trade_fee = number_format($trade_fee, 12, '.', '');
                    $total = $total - $trade_fee;
                    $deduct_bal = $amount;
                    $available_bal = $first_cur_balance;
                    $currency = $first_currency;
                    $update_balance = $first_cur_balance - $amount;
                    $txd_id = 'STX' . time() . mt_rand(0, 999);
                }

                if ($deduct_bal <= $available_bal) {
                    $trade = new Trade();
                    if ($trade_type == 'stop_limit') {
                        $stop_limit = $request['stop'];
                        $trade->stoporderprice = $stop_limit;
                    }

                    $trade->unique_id = $txd_id;
                    $trade->trade_id = $txd_id;
                    $trade->trade_type = $trade_type;
                    $trade->user_id = $user_id;
                    $trade->pair_id = $get_pair_id->id;
                    $trade->pair = $pair;
                    $trade->firstCurrency = $first_currency;
                    $trade->secondCurrency = $second_currency;
                    $trade->price = $price;
                    $trade->total = $total;
                    $trade->type = $type;
                    $trade->process = '0';
                    $trade->fee = $trade_fee;
                    $trade->original_qty = $amount;
                    $trade->updated_qty = $amount;

                    $trade->status = 'active';

                    if ($trade->save()) {
//                            $user_bal = UserBalance::where('user_id', $user_id)->first();
//                            $user_bal->$currency = $update_balance;
//                            $user_bal->save();

//                            $user_bal = UserBalancesNew::where('user_id', $user_id)->where('currency_name', $currency)->first();
//                            $user_bal->balance = $update_balance;
//                            $user_bal->save();

                        //user activity
                        last_activity(get_usermail($user_id), 'Market Buy order', $user_id);

                        //id
                        $active_id = $trade->id;

                        $find_trade_type = ($type == 'Buy') ? 'Sell' : 'Buy';

                        if ($type == 'Buy') {

                            $findorders = Trade::where('user_id', '<>', $user_id)->where(['pair' => $pair, 'type' => $find_trade_type])->where(function ($query) {
                                $query->where('status', 'active')->Orwhere('status', 'partially');
                            })->orderBy('price', 'asc')->get();

                        } else {
                            $findorders = Trade::where('user_id', '<>', $user_id)->where(['pair' => $pair, 'type' => $find_trade_type])->where(function ($query) {
                                $query->where('status', 'active')->Orwhere('status', 'partially');
                            })->orderBy('price', 'desc')->get();
                        }

                        $order_process_result = 0;

                        foreach ($findorders as $foundorder) {
                            $found_order_amount = $foundorder->updated_qty;
                            $pairid = $foundorder->pair_id;
                            if ($type == 'Buy') {
                                if ($amount >= $found_order_amount) {
                                    $txn_fee = get_trade_fee($type, $pair);
                                    $total = $found_order_amount * $foundorder->price;
                                    $fee = $total * $txn_fee;
                                    $total = $total + $fee;
                                } else {
                                    $txn_fee = get_trade_fee($type, $pair);
                                    $total = $amount * $foundorder->price;
                                    $fee = $total * $txn_fee;
                                    $total = $total + $fee;
                                }
                            } else {
                                if ($amount >= $found_order_amount) {
                                    $total = $found_order_amount;
                                    $txn_fee = get_trade_fee($type, $pair);
                                    $total1 = $found_order_amount * $foundorder->price;
                                    $fee = $total1 * $txn_fee;
                                    $total1 = $total1 - $fee;
                                } else {
                                    $total = $amount;
                                    $txn_fee = get_trade_fee($type, $pair);
                                    $total1 = $amount * $foundorder->price;
                                    $fee = $total1 * $txn_fee;
                                    $total1 = $total1 - $fee;
                                }
                            }
                            if ($amount > 0 && $amount > $found_order_amount && $total <= $available_bal) {
                                $active_status = 'partially';
                                $found_status = 'completed';
                                $executed_price = $foundorder->price;
                                $order_process_result = $this->order_process($active_status, $found_status, $active_id, $foundorder->id, $found_order_amount, $executed_price);
                                $this->update_pairstats($pairid);
                                $amount = $amount - $foundorder->updated_qty;
                                $bal = get_userbalance($user_id, $currency);
                                $update_balance = $bal - $total;
                                $user_bal = UserBalancesNew::where('user_id', $user_id)->where('currency_name', $currency)->first();
                                $user_bal->balance = $update_balance;
                                $user_bal->save();
                                $available_bal = get_userbalance($user_id, $currency);
                                if ($type === 'Buy') {
                                    $trade->updated_total = $trade->updated_total + $total;
                                    $trade->save();
                                } else {
                                    $trade->updated_total = $trade->updated_total + $total1;
                                    $trade->save();
                                }
                            } else if ($amount > 0 && $amount == $found_order_amount && $total <= $available_bal) {
                                $active_status = 'completed';
                                $found_status = 'completed';
                                $executed_price = $foundorder->price;
                                $order_process_result = $this->order_process($active_status, $found_status, $active_id, $foundorder->id, $found_order_amount, $executed_price);
                                $this->update_pairstats($pairid);
                                $amount = $amount - $foundorder->updated_qty;
                                $bal = get_userbalance($user_id, $currency);
                                $update_balance = $bal - $total;
                                $user_bal = UserBalancesNew::where('user_id', $user_id)->where('currency_name', $currency)->first();
                                $user_bal->balance = $update_balance;
                                $user_bal->save();
                                $available_bal = get_userbalance($user_id, $currency);
                                if ($type === 'Buy') {
                                    $trade->updated_total = $trade->updated_total + $total;
                                    $trade->save();
                                } else {
                                    $trade->updated_total = $trade->updated_total + $total1;
                                    $trade->save();
                                }
                            } else if ($amount > 0 && $amount < $found_order_amount && $total <= $available_bal) {
                                $active_status = 'completed';
                                $found_status = 'partially';
                                $executed_price = $foundorder->price;
                                $order_process_result = $this->order_process($active_status, $found_status, $active_id, $foundorder->id, $amount, $executed_price);
                                $this->update_pairstats($pairid);
                                $amount = 0;
                                $bal = get_userbalance($user_id, $currency);
                                $update_balance = $bal - $total;
                                $user_bal = UserBalancesNew::where('user_id', $user_id)->where('currency_name', $currency)->first();
                                $user_bal->balance = $update_balance;
                                $user_bal->save();
                                $available_bal = get_userbalance($user_id, $currency);
                                if ($type === 'Buy') {
                                    $trade->updated_total = $trade->updated_total + $total;
                                    $trade->save();
                                } else {
                                    $trade->updated_total = $trade->updated_total + $total1;
                                    $trade->save();
                                }
                            } else if ($amount == 0) {
                                $order_process_result = 1;
                                break;
                            }
                        }
                        if ($amount > 0 && $total <= $available_bal) {
                            $pair_stats = PairStats::where('pair_id', $pairid)->first();
                            $last_price = $pair_stats->last;
                            if ($type == 'Buy') {
                                $get_fee = get_trade_fee($type, $pair);
                                $total_1 = $amount * $last_price;
                                $fee = $total_1 * $get_fee;
                                $total_1 = $total_1 + $fee;
                            } else {
                                $get_fee = get_trade_fee($type, $pair);
                                $total_1 = $amount * $last_price;
                                $fee = $total_1 * $get_fee;
                                $total_1 = $total_1 - $fee;
                            }
                            $trade_order = Trade::where('id', $active_id)->first();
                            $trade_order->updated_qty = $amount;
                            $trade_order->price = $last_price;
                            $trade_order->fee = $fee;
                            $trade_order->total = $total_1;
                            $trade_order->status = 'partially';
                            $trade_order->save();
                            $data['status'] = '1';
                            $data['message'] = 'Your order has been completed partially.';
                            return json_encode($data);
                        } else if ($amount > 0 && $total > $available_bal) {
                            $data['status'] = '3';
                            $data['message'] = 'Not sufficient balance to complete your whole market order.';
                            return json_encode($data);
                        } else if ($amount == 0) {
                            $pair_stats = PairStats::where('pair_id', $pairid)->first();
                            $last_price = $pair_stats->last;
                            if ($type == 'Buy') {
                                $get_fee = get_trade_fee($type, $pair);
                                $total_1 = $amount * $last_price;
                                $fee = $total_1 * $get_fee;
                                $total_1 = $total_1 + $fee;
                            } else {
                                $get_fee = get_trade_fee($type, $pair);
                                $total_1 = $amount * $last_price;
                                $fee = $total_1 * $get_fee;
                                $total_1 = $total_1 - $fee;
                            }
                            $trade_order = Trade::where('id', $active_id)->first();
                            $trade_order->updated_qty = $amount;
                            $trade_order->price = $last_price;
                            $trade_order->fee = $fee;
                            $trade_order->total = $total_1;
                            $trade_order->status = 'completed';
                            $trade_order->save();
                            $data['status'] = '1';
                            $data['message'] = 'Your has been completed successfully.';
                            return json_encode($data);
                        }
                    }
                } else {
                    $data['status'] = '0';
                    $data['message'] = 'Insufficient Balance of ' . $currency;
                    return json_encode($data);
                }
            }
        } catch (\Exception $exception) {
            $data['status'] = '500';
            $data['message'] = 'Server Error';
            return json_encode($data);
        }
    }

    function cancel_order($id)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
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

                    $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));

                    $pusher->trigger('order', 'order-cancelled', array('Pair' => $result->pair, 'Type' => $result->type,
                        'Amount' => number_format($result->updated_qty, 0, '.', ''),
                        'Price' => number_format($result->price, 8, '.', ''),
                        'Total' => number_format($result->total, 8, '.', ''),
                        'Fee' => number_format($trade_fee, 9, '.', ''),
                    ));

                    Session::flash('success', 'Your order is been cancelled successfully');
                    return redirect()->back();
                }
            }
        } catch (\Exception $exception) {
            Session::flash('error', 'Server Error');
            return redirect()->back();
        }
    }

    function cancel_multiple($id)
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                $orders = json_decode($id);
                for ($i = 0; $i < count($orders); $i++) {
                    $tradeid = $orders[$i];
                    $result = Trade::where('id', $tradeid)->where(function ($query) {
                        $query->where('status', 'active')->Orwhere('status', 'partially');
                    })->first();
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
                Session::flash('success', 'Selected orders cancelled successfully');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function single_trade_details($tradid, $key)
    {
        try {
            $result = Trade::where('id', $tradid)->first();
            return $result->$key;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function update_trade_details($tradid, $key, $value)
    {
        try {
            $upt = Trade::where('id', $tradid)->first();
            $upt->$key = $value;
            $upt->save();
            return true;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function user_balance_update($userid, $currency, $amount)
    {
        try {
            $paiduserbalance = get_userbalance($userid, $currency);
            $finbalance = $amount + $paiduserbalance;
            $upt = Balance::where('user_id', $userid)->first();
            $upt->$currency = $finbalance;
            $upt->save();
            return true;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function trade_chart($pair)
    {
        try {
            // $pair = $pair ? $pair : 'ETH-USDT';
            $pair = $pair ? $pair : get_default_pair();
            //high value
            $charts = Charts::where('pair', $pair)->orderBy('datetime', 'desc')->first();
            if ($charts != '' || $charts != null) {
                $startdate = date("Y-m-d", strtotime($charts->datetime));
            } else {
                $startdate = '2018-10-25';
            }
            $enddate = date("Y-m-d");

            for ($date = $startdate; $date <= $enddate;) {

                $high = "SELECT DATE(updated_at) as dateval, SUM(triggered_qty) as volume, MAX(triggered_price) as total FROM EXCHANGE_trade_mapping where pair='$pair' AND DATE(updated_at) = DATE('$date')  GROUP BY YEAR(updated_at), MONTH(updated_at), DATE(updated_at) ORDER BY DATE(updated_at) ASC";

                $result = DB::select(DB::raw($high));
                $arr = "";
                if ($result) {
                    $arr .= "[";
                    foreach ($result as $key => $val) {
                        $vdate = $val->dateval;
                        $high = $val->total;
                        $millsec = strtotime($vdate) * 1000;
                        $open = $this->trade_open_value($vdate, $pair);
                        $close = $this->trade_close_value($vdate, $pair);
                        $low = $this->trade_min_value($vdate, $pair);
                        $volume = $val->volume;

                        $check = Charts::where('pair', $pair)->whereDate('datetime', '=', $vdate)->first();

                        if (count($check) > 0) {
                            $xdc_charts = $check;
                        } else {
                            //xdc charts
                            $xdc_charts = new Charts();
                        }

                        $xdc_charts->datetime = $vdate;
                        $xdc_charts->high = $high;
                        $xdc_charts->millsec = strtotime($vdate) * 1000;
                        $xdc_charts->open = $open;
                        $xdc_charts->low = $low;
                        $xdc_charts->volume = $volume;
                        $xdc_charts->close = $close;
                        $xdc_charts->pair = $pair;

                        $xdc_charts->save();
                        $arr .= "[" . $millsec . "," . $open . "," . $high . "," . $low . "," . $close . "," . $volume . "]";
                        if (count($result) != ($key + 1)) {
                            $arr .= ",";
                        }

                    }
                    $arr .= "]";
                } else {
                    $vdate = $date;
                    $millsec = strtotime($vdate) * 1000;
                    $eval = 0;

                    $check = Charts::where('pair', $pair)->whereDate('datetime', '=', $vdate)->first();

                    if (count($check) > 0) {
                        $xdc_charts = $check;
                    } else {
                        //xdc charts
                        $xdc_charts = new Charts();
                    }

                    $xdc_charts->datetime = $vdate;
                    $xdc_charts->high = $eval;
                    $xdc_charts->millsec = strtotime($vdate) * 1000;
                    $xdc_charts->open = $eval;
                    $xdc_charts->low = $eval;
                    $xdc_charts->volume = $eval;
                    $xdc_charts->close = $eval;
                    $xdc_charts->pair = $pair;

                    $xdc_charts->save();
                    $arr .= "[[" . $millsec . "," . $eval . "," . $eval . "," . $eval . "," . $eval . "," . $eval . "]]";
                }
                echo $arr;
                $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function chart_history($pair)
    {
        try {
            // $pair = $pair ? $pair : 'ETH-USDT';
            $pair = $pair ? $pair : get_default_pair();
            $time = Charts::where('pair', $pair)->get();
            $t = array();
            $c = array();
            $o = array();
            $h = array();
            $l = array();
            $v = array();
            foreach ($time as $key => $res) {
                $res['millsec'] = strtotime($res['datetime'] . ' ' . 'UTC');
                array_push($t, number_format($res['millsec'], 0, '.', ''));
                array_push($c, number_format($res['close'], 8, '.', ''));
                array_push($o, number_format($res['open'], 8, '.', ''));
                array_push($h, number_format($res['high'], 8, '.', ''));
                array_push($l, number_format($res['low'], 8, '.', ''));
                array_push($v, number_format($res['volume'], 0, '.', ''));
            }
            $data = array(
                's' => "ok",
                't' => $t,
                'c' => $c,
                'o' => $o,
                'h' => $h,
                'l' => $l,
                'v' => $v
            );
            return json_encode($data);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function charts($pair)
    {
        try {
            // $pair = $pair ? $pair : 'ETH-USDT';
            $pair = $pair ? $pair : get_default_pair();
            $trade_chart = Charts::where('pair', $pair)
                ->orderBy('datetime', 'asc')->get();
            $trade_chart = $trade_chart->toJson();
            echo $trade_chart;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function chart_config()
    {
        try {
            $config = array(
                'supports_search' => true,
                'supports_group_request' => true,
                'supports_marks' => false,
                'supports_timescale_marks' => false,
                'supports_time' => true,
                'exchanges' =>
                    array(
                        0 =>
                            array(
                                'value' => '',
                                'name' => 'All Exchanges',
                                'desc' => '',
                            ),
                        1 =>
                            array(
                                // 'value' => 'ETH-USDT',
                                // 'name' => 'ETH-USDT',
                                // 'desc' => 'ETH-USDT',
                                'value' => 'ABC-USD',
                                'name' => 'ABC-USD',
                                'desc' => 'ABC-USD',
                            )
                    ),
                'symbols_types' =>
                    array(
                        0 =>
                            array(
                                'name' => 'All types',
                                'value' => '',
                            ),
                        1 =>
                            array(
                                'name' => 'Stock',
                                'value' => 'stock',
                            ),
                        2 =>
                            array(
                                'name' => 'Index',
                                'value' => 'index',
                            ),
                    ),
                'supported_resolutions' =>
                    array(
                        0 => '1',
                        1 => '5',
                        2 => '15',
                        3 => '60',
                        4 => '240',
                        5 => '480',
                        6 => 'D',
                        7 => '2D',
                        8 => '3D',
                        9 => 'W',
                        10 => '3W',
                        11 => 'M',
                        12 => '6M',
                    ),
            );

            return json_encode($config);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function chart_time()
    {
        try {
            return Carbon::now()->timestamp;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function symbol_info()
    {
        try {
//            $symbols = array("CMB-ETH", "AAPL", "FB", "GOOG");
//            $desc = array("Microsoft corp.", "Apple Inc", "Facebook", "Google");
//            $data= array('symbols'=>$symbols,'min_price_move'=>1,'description'=>$desc);
            $data = array(
                'symbol' => get_all_pairs(),
                'description' => get_all_pairs(),
                'exchange-listed' => get_config('site_name'),
                'exchange-traded' => get_config('site_name'),
                'minmovement' => 1,
                'minmovement2' => 0,
                'pricescale' => array(100000000, 100000000, 100000000),
                'has_dwm' => true,
                'has_intraday' => false,
                'has-no-volume' => array(false, false, false),
                'type' => array('bitcoin', 'bitcoin', 'bitcoin'),
                'ticker' => get_all_pairs(),
                'timezone' => "Asia/Singapore",
                'session-regular' => "24x7",
                'has_empty_bars' => true,
                'has_daily' => true,
            );
            return json_encode($data);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function trade_open_value($date, $pair)
    {
        try {
            $res = TradeMapping::select('triggered_price')->whereDate('updated_at', '=', $date)->where(['pair' => $pair])->orderBy('id', 'asc')->limit(1)->first();
            //$value = ($res->Type == 'Buy') ? $res->Price : $res->opt_price;
            //return $value;
            return $res->triggered_price;
        } catch (\Exception $e) {
            return 0;
        }
    }

    function trade_close_value($date, $pair)
    {
        try {
            $res = TradeMapping::select('triggered_price')->whereDate('updated_at', '=', $date)->where(['pair' => $pair])->orderBy('id', 'desc')->limit(1)->first();
            //$value = ($res->Type == 'Buy') ? $res->Price : $res->opt_price;
            //return $value;
            return $res->triggered_price;
        } catch (\Exception $e) {
            return 0;
        }
    }

    function trade_min_value($date, $pair)
    {
        try {
            $res = TradeMapping::whereDate('updated_at', '=', $date)->where(['pair' => $pair])->min('triggered_price');
            return $res;
        } catch (\Exception $e) {
            return 0;
        }
    }

    function trade_max_value($date, $pair)
    {
        try {
            $res = TradeMapping::whereDate('updated_at', '=', $date)->where(['pair' => $pair])->max('triggered_price');
            return $res;
        } catch (\Exception $e) {
            return 0;
        }
    }

    function check_market_price($curr_price, $user_price, $type)
    {
        try {
            if ($type == 'Buy') {
                $min_price = $curr_price * (30 / 100);
                $min_price = $curr_price - $min_price;
                return ($min_price > $user_price) ? 'ok' : 'false';
            } else {
                $max_price = $curr_price * (30 / 100);
                $max_price = $curr_price + $max_price;
                return ($max_price < $user_price) ? 'ok' : 'false';
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

//for swap history
    function swap_history()
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                $userid = Session::get('alphauserid');
                $trade_history = Trade::where(['user_id' => $userid])->whereIn('pair', ['ABC-USD'])->whereIn('status', ['completed'])->orderBy('created_at', 'desc')->paginate(10);
                return view('front.trade_history', ['result' => $trade_history, 'getpair' => 'ABC-USD', 'getstatus' => 'completed']);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

// end class
//fpr ico history
    function ico_history()
    {
        try {
            if (Session::get('alphauserid') == "") {
                return redirect('logout');
            } else {
                $userid = Session::get('alphauserid');
                $trade_history = ICOTrade::where('user_id', $userid)->orderBy('id', 'desc')->paginate(10);
                return view('front.trade_history', ['result' => $trade_history, 'getpair' => 'XDC-ICO', 'getstatus' => 'completed']);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function demo(Request $request)
    {
        $data = $request['id'];
        $b = $request['type'];
        $c = $request['amt'];
        $d = $request['p'];
        $e = $request['pair'];

        $this->find_trades($data, $b, $d, $c, $e);
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
}
