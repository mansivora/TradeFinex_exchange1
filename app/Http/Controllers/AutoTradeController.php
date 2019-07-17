<?php

namespace App\Http\Controllers;

use App\model\Balance;
use Illuminate\Http\Request;
use App\model\Trade;

class AutoTradeController extends Controller
{
    public function __construct()
    {
        //$this->middleware('Adminlogin');
        $ip = \Request::ip();
        blockip_list($ip);
    }

    //main function of deciding order
    function main($pair)
    {
        $get_avg_price = $this->get_order_price($pair);
        if ($get_avg_price == 0) {
            $get_random_price = $this->get_random_price($pair);
        } else {
            $get_random_price = $this->get_order_price($pair);
        }

        $amount = $this->get_random_amount();

        $buy_trade_id = $this->place_buy_order($pair, $amount, $get_random_price);
        sleep(5);

        $sell_trade_id = $this->place_sell_order($pair, $amount, $get_random_price);

        $this->order_execute($buy_trade_id, $sell_trade_id);
    }

//    for executing order
    function order_execute($buy_trade_id, $sell_trade_id)
    {
        $auto_trades = Trade::where('id', $buy_trade_id)->Orwhere('id', $sell_trade_id)->get();

        foreach ($auto_trades as $auto_trade) {
            if ($auto_trade->Type == 'Buy') {
                $auto_trade->status = 'completed';

                if ($auto_trade->save()) {
                    $user_first_currency_bal = get_userbalance($auto_trade->user_id, $auto_trade->firstCurrency);
                    $currency = $auto_trade->firstCurrency;
                    $finalbalance = $user_first_currency_bal + $auto_trade->Amount;
                    $upt = Balance::where('user_id', $auto_trade->user_id)->first();
                    $upt->$currency = $finalbalance;
                    $upt->save();

                }
            } elseif ($auto_trade->Type == 'Sell') {
                $auto_trade->status = 'completed';

                if ($auto_trade->save()) {
                    $user_second_currency_bal = get_userbalance($auto_trade->user_id, $auto_trade->secondCurrency);
                    $currency = $auto_trade->secondCurrency;
                    $finalbalance = $user_second_currency_bal + $auto_trade->Total;
                    $upt = Balance::where('user_id', $auto_trade->user_id)->first();
                    $upt->$currency = $finalbalance;
                    $upt->save();
                }

            }
        }
    }

    //for placing buy order
    function place_buy_order($pair, $amount, $get_random_price)
    {
        $cur = explode("-", $pair);
        $first_currency = $cur[0];
        $second_currency = $cur[1];
        $buyer = 123;
        $ip = \Request::ip();
        $priceamount = $amount * $get_random_price;

        $ins = new Trade();
        $ins->user_id = $buyer;
        $ins->ip = $ip;
        $ins->firstCurrency = $first_currency;
        $ins->secondCurrency = $second_currency;
        $ins->Amount = $amount;
        $ins->Price = $get_random_price;
        $ins->Type = 'Buy';
        $ins->Process = 'Auto';
        $ins->Fee = 0;
        $ins->Total = $priceamount;
        $ins->orderTime = date('H:i:s');
        $ins->datetime = date('Y-m-d');
        $ins->pair = $pair;
        $ins->status = 'active';
        $ins->fee_per = 0;
        if ($ins->save()) {
            $second_currency_bal = get_userbalance($buyer, $second_currency);
            $finalbalance = $second_currency_bal - $priceamount;
            $upt = Balance::where('user_id', $buyer)->first();
            $upt->$second_currency = $finalbalance;
            $upt->save();
            return $ins->id;
        }

    }

    //for placing sell order
    function place_sell_order($pair, $amount, $get_random_price)
    {
        $cur = explode("-", $pair);
        $first_currency = $cur[0];
        $second_currency = $cur[1];
        $buyer = 124;
        $ip = \Request::ip();
        $priceamount = $amount * $get_random_price;

        $ins = new Trade();
        $ins->user_id = $buyer;
        $ins->ip = $ip;
        $ins->firstCurrency = $first_currency;
        $ins->secondCurrency = $second_currency;
        $ins->Amount = $amount;
        $ins->Price = $get_random_price;
        $ins->Type = 'Sell';
        $ins->Process = 'Auto';
        $ins->Fee = 0;
        $ins->Total = $priceamount;
        $ins->orderTime = date('H:i:s');
        $ins->datetime = date('Y-m-d');
        $ins->pair = $pair;
        $ins->status = 'active';
        $ins->fee_per = 0;
        if ($ins->save()) {
            $first_currency_bal = get_userbalance($buyer, $first_currency);
            $finalbalance = $first_currency_bal - $amount;
            $upt = Balance::where('user_id', $buyer)->first();
            $upt->$second_currency = $finalbalance;
            $upt->save();
            return $ins->id;
        }
    }

    //for calculating price for order
    function get_order_price($pair)
    {
        $buy_order_list = Trade::where(['pair' => $pair, 'Type' => 'Sell'])->where(function ($query) {
            $query->where('status', 'active')->Orwhere('status', 'partially');
        })->groupBy('Price')->orderBy('Price', 'asc')->first();

        if ($buy_order_list) {
            $buy_price = $buy_order_list->Price;
        } else {
            $buy_price = 0;
        }


        $sell_order_list = Trade::where(['pair' => $pair, 'Type' => 'Buy'])->where(function ($query) {
            $query->where('status', 'active')->Orwhere('status', 'partially');
        })->groupBy('Price')->orderBy('Price', 'desc')->first();

        if ($sell_order_list) {
            $sell_price = $sell_order_list->Price;
        } else {
            $sell_price = 0;
        }

        if ($buy_price > 0 && $sell_price > 0) {


            $average_price = $sell_price + mt_rand() / mt_getrandmax() * ($buy_price - $sell_price);
            $average_price = rtrim(sprintf('%.8F', $average_price), '0');
        } else {
            $average_price = 0;
        }

        return $average_price;
    }

    //for getting a random price
    function get_random_price($pair)
    {
        $buy_order_list = Trade::where(['pair' => $pair, 'status' => 'completed'])->orderBy('id', 'desc')->limit(1)->first();

        $value = ($buy_order_list->Type == 'Buy') ? $buy_order_list->Price : $buy_order_list->opt_price;

        $buy_price = $value;

        $upated_buy_price = $buy_price + ($buy_price * 0.05);
        $average_price = $buy_price + mt_rand() / mt_getrandmax() * ($upated_buy_price - $buy_price);
        $average_price = rtrim(sprintf('%.8F', $average_price), '0');

        return $average_price;

    }


    //for recording auto trade records
    function get_random_amount()
    {
        $min = 10000;
        $max = 15000;

        $random = rand($min, $max);
        return $random;

    }


}

