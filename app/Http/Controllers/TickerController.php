<?php

namespace App\Http\Controllers;

use App\model\Pair;
use App\model\PairStats;
use App\model\Trade;
use App\model\TradeMapping;
use App\model\Marketprice;
use function Sodium\add;

class TickerController extends Controller
{
    //

    function index()
    {
        abort('404');
    }

    function info($pair = "")
    {
        $pair = $pair ? $pair : '';
        $checkpair = Pair::where(['type' => 'trade', 'pair' => $pair])->count();
        $data = array();

        if ($checkpair > 0) {
            $cur = explode("-", $pair);
            $first_currency = $cur[0];
            $second_currency = $cur[1];
            $data['status'] = 'success';
            $data['message'] = $pair . ' Market information';
            $data['markets'] = array();
            $data['markets'][0]['bid'] = $this->get_tick_bidprice($pair);
            $data['markets'][0]['last_price'] = $this->get_last_price($pair);
            $data['markets'][0]['volume24h'] = $this->get_volume_24h($pair, $first_currency);
            $data['markets'][0]['currency'] = $second_currency;
            $data['markets'][0]['marketname'] = $pair;
            $data['markets'][0]['ask'] = $this->get_ticket_ask($pair);
            $data['markets'][0]['low24h'] = $this->get_low_24h($pair);
            $data['markets'][0]['change24h'] = $this->get_change_24h($pair);
            $data['markets'][0]['high24h'] = $this->get_high_24h($pair);
            $data['markets'][0]['usd_price'] = $this->get_lastusdprice($pair, $second_currency);
            $data['markets'][0]['basecurrency'] = $first_currency;

        } else {
            $data['status'] = 'error';
            $data['markets'] = array();
        }
        echo(json_encode($data));

    }


    function get_tick_bidprice($pair)
    {
        $data = Trade::where(['type' => 'Buy', 'pair' => $pair])->where(function ($query) {
            $query->where('status', 'active')->Orwhere('status', 'partially');
        })->max('price');
        return sprintf('%.8f', $data);
    }

    function get_last_price($pair)
    {
//        $data = Trade::where(['pair' => $pair, 'status' => 'completed'])->orderBy('id', 'desc')->first();
        $data = TradeMapping::where(['pair' => $pair])->orderBy('id', 'desc')->limit(1)->first();
        if ($data) {
            return sprintf('%.8f', $data->triggered_price);
        }
        return null;
    }

    function get_volume_24h($pair, $currency)
    {
        $cur_time = date('Y-m-d H:i:s');
        $bef_time = date('Y-m-d H:i:s', strtotime('-24 hours', strtotime($cur_time)));
//        $data = Trade::where(['status' => 'completed', 'pair' => $pair])->where(function ($query) use ($cur_time, $bef_time) {
//            $query->where('updated_at', '<=', $cur_time)->where('updated_at', '>=', $bef_time);
//        })->sum('original_qty');
        $data = TradeMapping::where('pair', $pair)->where(function ($query) use ($cur_time, $bef_time) {
            $query->where('updated_at', '<=', $cur_time)->where('updated_at', '>=', $bef_time);
        })->sum('triggered_qty');
        return $data;

    }

    function get_ticket_ask($pair)
    {
        $data = Trade::where('pair', $pair)->where(function ($query) {
            $query->where('status', 'active')->Orwhere('status', 'partially');
        })->min('price');
        if ($data) {
            return sprintf('%.8f', $data);
        }
        return null;
    }

    function get_low_24h($pair)
    {
        $cur_time = date('Y-m-d H:i:s');
        $bef_time = date('Y-m-d H:i:s', strtotime('-24 hours', strtotime($cur_time)));
//        $data = Trade::where(['pair' => $pair, 'status' => 'completed'])->where(function ($query) use ($cur_time, $bef_time) {
//            $query->where('updated_at', '<=', $cur_time)->where('updated_at', '>=', $bef_time);
//        })->min('price');
        $data = TradeMapping::where('pair', $pair)->where(function ($query) use ($cur_time, $bef_time) {
            $query->where('updated_at', '<=', $cur_time)->where('updated_at', '>=', $bef_time);
        })->min('triggered_price');
        if ($data) {
            return sprintf('%.8f', $data);
        }
        return null;
    }

    function get_high_24h($pair)
    {
        $cur_time = date('Y-m-d H:i:s');
        $bef_time = date('Y-m-d H:i:s', strtotime('-24 hours', strtotime($cur_time)));
//        $data = Trade::where(['pair' => $pair, 'status' => 'completed'])->where(function ($query) use ($cur_time, $bef_time) {
//            $query->where('updated_at', '<=', $cur_time)->where('updated_at', '>=', $bef_time);
//        })->max('price');
        $data = TradeMapping::where('pair', $pair)->where(function ($query) use ($cur_time, $bef_time) {
            $query->where('updated_at', '<=', $cur_time)->where('updated_at', '>=', $bef_time);
        })->max('triggered_price');
        if ($data) {
            return sprintf('%.8f', $data);
        }
        return null;
    }

    function get_change_24h($pair)
    {
        $high = $this->get_high_24h($pair);
        $low = $this->get_low_24h($pair);
        $data = $high - $low;
        return sprintf('%.8f', $data);
    }

    function get_lastusdprice($pair, $currency)
    {
        $lastprice = $this->get_last_price($pair);
        if ($lastprice) {
            $cur_price = get_estusd_price($currency, '1');
            $res = $cur_price * $lastprice;
            return sprintf('%.8f', $res);
        } else {
            $res = Marketprice::where('currency', $currency)->first();
            $getusd = $res->USD;
            return sprintf('%.8f', $getusd);
        }
    }

    function get_open_price($pair, $datetime)
    {
//        $datetime = date('Y-m-d');
//        $data = Trade::where(['pair' => $pair, 'status' => 'completed'])->where('updated_at', 'like', '%' . $datetime . '%')->orderBy('id', 'asc')->limit(1)->first();
//            return sprintf('%.8f', $data->price);
        $data = TradeMapping::where('pair', $pair)->where('updated_at', 'like', '%' . $datetime . '%')->orderBy('id', 'asc')->limit(1)->first();
        return sprintf('%8f', $data->triggered_price);
    }

    function get_close_price($pair, $datetime)
    {
//        $datetime = date('Y-m-d');
//        $data = Trade::where(['pair' => $pair, 'status' => 'completed'])->where('updated_at', 'like', '%' . $datetime . '%')->orderBy('id', 'desc')->limit(1)->first();
//            return sprintf('%.8f', $data->price);
        $data = TradeMapping::where('pair', $pair)->where('updated_at', 'like', '%' . $datetime . '%')->orderBy('id', 'desc')->limit(1)->first();
        return sprintf('%8f', $data->triggered_price);
    }

    function getxmlres($str)
    {
        require_once app_path('array2xml/array2xml.php');
        $otp = "";
        $strlen = strlen($str);
        for ($i = 0; $i < $strlen; $i++) {
            $char = substr($str, $i, 1);
            $otp .= $char . ',';
        }
        $string = "e, x, block, exchange, authentication, code, is, " . $otp . '  repeat,  authentication, code, is, ' . $otp . '  thank you';
        $actual = array(
            'Speak' => $string,
        );
        $xml = new \Array2xml();
        $xml->setRootName('Response');
        header("Content-type: text/xml");
        echo $xml->convert($actual);

    }

    function history($pair = "")
    {
        $pair = $pair ? $pair : '';
        $checkpair = Pair::where(['type' => 'trade', 'pair' => $pair])->count();
        $data = array();
        $start_date = '2017-11-14';
        $end_date = date('Y-m-d');
        $datetime1 = new \DateTime($start_date);
        $datetime2 = new \DateTime($end_date);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');
        if ($checkpair > 0) {
            $data['status'] = 'success';
            $data['history'] = array();
            for ($i = 0; $i < $days; $i++) {
                $finddate = date('Y-m-d', strtotime('+' . $i . ' days', strtotime($start_date)));
                $data['history'][$i]['open'] = $this->get_open_price($pair, $finddate);
                $data['history'][$i]['close'] = $this->get_close_price($pair, $finddate);
                $data['history'][$i]['high'] = $this->get_high_date_price($pair, $finddate);
                $data['history'][$i]['low'] = $this->get_low_date_price($pair, $finddate);
                $data['history'][$i]['volume'] = $this->get_datewise_volume($pair, $finddate);
                $data['history'][$i]['date'] = $finddate;
            }
        } else {
            $data['status'] = 'error';
            $data['history'] = array();
        }
        die(json_encode($data));
    }

    function get_high_date_price($pair, $datetime)
    {
        $data = Trade::where(['pair' => $pair, 'status' => 'completed'])->where('updated_at', 'like', '%' . $datetime . '%')->max('price');
        return sprintf('%.8f', $data);
    }

    function get_low_date_price($pair, $datetime)
    {
        $data = Trade::where(['pair' => $pair, 'status' => 'completed'])->where('updated_at', 'like', '%' . $datetime . '%')->min('price');
        return sprintf('%.8f', $data);
    }

    function get_datewise_volume($pair, $datetime)
    {
        $data = Trade::where(['pair' => $pair, 'status' => 'completed'])->where('updated_at', 'like', '%' . $datetime . '%')->sum('Amount');
        return $data;
    }

    //api
    function apiinfo($pair = "")
    {
        $pair = $pair ? $pair : '';
        $checkpair = Pair::where(['type' => 'trade', 'pair' => $pair])->count();
        $data = array();

        if ($checkpair > 0) {
            $cur = explode("-", $pair);
            $first_currency = $cur[0];
            $second_currency = $cur[1];
            $data['status'] = 'success';
            $data['message'] = $pair . ' Market information';
            $data['markets'] = array();
            $data['markets'][0]['bid'] = $this->get_tick_bidprice($pair);
            $data['markets'][0]['last_price'] = $this->get_last_price($pair);
            $data['markets'][0]['volume24h'] = $this->get_volume_24h($pair, $first_currency);
            $data['markets'][0]['currency'] = $second_currency;
            $data['markets'][0]['marketname'] = $pair;
            $data['markets'][0]['ask'] = $this->get_ticket_ask($pair);
            $data['markets'][0]['low24h'] = $this->get_low_24h($pair);
            $data['markets'][0]['change24h'] = $this->get_change_24h($pair);
            $data['markets'][0]['high24h'] = $this->get_high_24h($pair);
            $data['markets'][0]['usd_price'] = $this->get_lastusdprice($pair, $second_currency);
            $data['markets'][0]['basecurrency'] = $first_currency;
        } else {
            $data['status'] = 'error';
            $data['markets'] = array();
        }
        return (json_encode($data));

    }

    //pair stats
    function pair_stats()
    {
        try {
            $get_all_pairs = Pair::all();
            if ($get_all_pairs) {
                foreach ($get_all_pairs as $get_all_pair) {
                    $get_pair_stat = PairStats::where('pair_id', $get_all_pair->id)->first();
                    $pair = $get_all_pair->pair;
                    $vol = $get_pair_stat->volume;
                    $low = $get_pair_stat->low;
                    $high = $get_pair_stat->high;
                    $percentage_change = $get_pair_stat->percent_change;
                    $change = $get_pair_stat->change;
                    $color = $get_pair_stat->colour;

                    $array = array('Pair' => $pair, 'Volume' => $vol, 'Low' => $low, 'High' => $high, 'Percentage' => $percentage_change, 'Change' => $change, 'Colour' => $color);
                    $result[] = $array;

                }
                $result_array = array('data' => $result);
                return json_encode($result_array);

            } else {
                return 0;
            }
        } catch (\Exception $exception) {
            return $exception->getMessage() . ' ' . $exception->getLine();
        }
    }

    function price_usd($pair = "")
    {
        try {

            $pair = $pair ? $pair : '';

            if ($pair != '') {
                $explode = explode("-", $pair);
                $record = Marketprice::where('currency', $explode[1])->first();
                $price = $this->get_last_price($pair) * $record->USD;
                $time = date('h:i:s A');
                $date = date('d/m/Y');
                $array = array('price' => number_format($price, 6, '.', ''), "time" => $time, "date" => $date);
                return json_encode($array);
            } else {
                return 0;
            }

        } catch (\Exception $exception) {
            return 0;
        }
    }

}