<?php

namespace App\Http\Controllers;

use App\model\Admin;
use App\model\Balance;
use App\model\Node;
use App\model\ClosingBalance;
use App\model\Marketprice;
use App\model\OpeningBalance;
use App\model\PairStats;
use App\model\SiteSettings;
use App\model\Trade;
use App\model\Transaction;
use App\model\UserBalance;
use App\model\UserBalancesNew;
use App\model\UserCurrencyAddresses;
use App\model\Users;
use App\model\Wallettrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;
use Psy\Exception\ErrorException;

class CronController extends Controller
{
    //

    function index()
    {
        abort('404');
    }

    function update_prices()
    {
        try {
            //BTC
//            $btc_usd = $this->get_live_estusd_price('tBTCUSD');
            $btc_usd = $this->get_live_estusd_price('bitcoin');
            echo "BTC-USD  : " . $btc_usd;
            echo "<br>";

            if ($btc_usd != '' || $btc_usd != null) {
                $lobjMarket = Marketprice::where('id', '1')->first();
                $lobjMarket->USD = $btc_usd;
                $lobjMarket->save();
            }

            echo "<br>";

            //ETH
//            $eth_usd = $this->get_live_estusd_price('tETHUSD');
            $eth_usd = $this->get_live_estusd_price('ethereum');
            echo "ETH-USD  : " . $eth_usd;
            echo "<br>";

            if ($eth_usd != '' || $eth_usd != null) {
                $lobjMarket = Marketprice::where('id', '2')->first();
                $lobjMarket->USD = $eth_usd;
                $lobjMarket->save();
                echo "<br>";
            }

            echo "<br>";

            //XRP
//            $xrp_usd = $this->get_live_estusd_price('tXRPUSD');
            $xrp_usd = $this->get_live_estusd_price('ripple');
            echo "XRP-USD  : " . $xrp_usd;
            echo "<br>";

            if ($xrp_usd != '' || $xrp_usd != null) {
                $lobjMarket = Marketprice::where('id', '3')->first();
                $lobjMarket->USD = $xrp_usd;
                $lobjMarket->save();
            }

            echo "<br>";
            //USDT
            $usdt_usd = $this->get_live_estusd_price('tether');
            echo "USDT-USD  : " . $usdt_usd;
            echo "<br>";

            if ($usdt_usd != '' || $usdt_usd != null) {
                $lobjMarket = Marketprice::where('id', '4')->first();
                $lobjMarket->USD = $usdt_usd;
                $lobjMarket->save();
                echo "<br>";
            }

        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function get_market_prices($cur1, $cur2)
    {
        try {
            $currency = $cur1 . '-' . $cur2;
            $url = "https://api.cryptonator.com/api/ticker/" . $currency;
            $result = file_get_contents($url);
            $res = json_decode($result);
            $out = $res->ticker;
            return $out->price;
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
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

    function eth_deposit_process()
    {
        try {
            $admin = SiteSettings::where('id', 1)->first();
            $last_block = $admin->mined_block;
            $last_block = $last_block + 1;

            $recent_block = get_recent_block();

            if ($recent_block > 0) {
                for ($i = $last_block; $i <= $recent_block; $i++) {


                    $block_number = dechex($i);

                    $eurl = 'https://api.etherscan.io/api?module=proxy&action=eth_getBlockByNumber&tag=' . $block_number . '&boolean=true';

                    $cObj = curl_init();
                    curl_setopt($cObj, CURLOPT_URL, $eurl);
                    curl_setopt($cObj, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($cObj, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($cObj, CURLOPT_RETURNTRANSFER, TRUE);
                    $output = curl_exec($cObj);
                    $curlinfos = curl_getinfo($cObj);

                    $result = json_decode($output);

                    if ($result) {
                        $transactionList = $result->result->transactions;

                    } else {
                        $transactionList = '';
                    }

                    //iterating transaction lists
                    foreach ($transactionList as $transaction) {
                        $to_address = $transaction->to;
                        $user = UserCurrencyAddresses::where('currency_addr', '=', $to_address)->first();
                        if ($transaction->from != "0x4f0be83d995fafaeb6eec0bcfd188416b88ad53f") {
                            if ($user != null && $to_address != null) {
                                $dep_id = $transaction->hash;
                                $ether_balance = hexdec($transaction->value) / 1000000000000000000;
                                $dep_already = $this->eth_checkdepositalready($user->user_id, $dep_id);
                                if ($dep_already === TRUE && (float)$ether_balance > 0) {

                                    $ether_balance = sprintf('%.10f', $ether_balance);

                                    $fetchbalance = get_userbalance($user->user_id, 'ETH');
                                    $finalbalance = $fetchbalance + $ether_balance;

                                    $adminethaddr = decrypt(get_config('eth_address'));
                                    $transid = 'TXD' . $user->user_id . time();

                                    $hash = eth_transfer_fun($to_address, $ether_balance, $adminethaddr, $user->user_id);

                                    if ($hash != "") {
                                        $instr = new Wallettrans;
                                        $instr->adtras_id = $transid;
                                        $instr->currency = 'ETH';
                                        $instr->address = $to_address;
                                        $instr->hash = $hash;
                                        $instr->amount = $ether_balance;
                                        $instr->save();

//                                    $upt = Balance::where('user_id', $user->user_id)->first();
//                                    $upt->ETH = $finalbalance;
                                        $upt = UserBalancesNew::where('user_id', $user->user_id)->where('currency_name', 'ETH')->first();
                                        $upt->balance = $finalbalance;
                                        $upt->save();

                                        $today = date('Y-m-d H:i:s');
                                        $ip = \Request::ip();
                                        $ins = new Transaction;
                                        $ins->user_id = $user->user_id;
                                        $ins->payment_method = 'Cryptocurrency Account';
                                        $ins->transaction_id = $transid;
                                        $ins->currency_name = 'ETH';
                                        $ins->type = 'Deposit';
                                        $ins->transaction_type = '1';
                                        $ins->amount = $ether_balance;
                                        $ins->updated_at = $today;
                                        $ins->crypto_address = $to_address;
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
                                        $ins->blocknumber = $i;
                                        if ($ins->save()) {

                                            $this->deposit_mail($user->user_id, $ether_balance, $transid, 'ETH');

                                            $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));

                                            $pusher->trigger('private-transaction_' . $user->user_id, 'deposit-event', array('User_id' => $user->user_id, 'Transaction_id' => $transid, 'Currency' => 'ETH', 'Amount' => $ether_balance, 'Status' => 'Completed', 'Time' => $today));

                                        }
                                    }
                                }
                            }

                        }
                    }
                    if ($last_block < $i) {
                        $admin->mined_block = $i;
                        $admin->save();
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }


    function eth_deposit_process_user($id)
    {
        try {
            $verifyBal = verifyEther($id);
            $jsonresult = [];
            if ($verifyBal != '' && $verifyBal > 0) {
                $userslist[] = Users::orderBy('id', 'asc')->where('ETH_addr', $id)->first();

                if ($userslist) {
                    foreach ($userslist as $userval) {
                        $userid = $userval->id;
                        $ethaddress = get_user_address($userid, 'ETH');
                        $blocknum = Transaction::max('blocknumber');

                        if ($blocknum == "") {
                            $blocknum = "3500000";
                        }
                        $blocknum = "4500000";
                        if ($ethaddress != "") {

                            $eurl = 'https://api.etherscan.io/api?module=account&action=txlist&address=' . $ethaddress . '&startblock=' . $blocknum . '&endblock=latest';

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

                                    $block_number = $transaction[$tr]->blockNumber;
                                    $address = $transaction[$tr]->to;
                                    $txid = $transaction[$tr]->hash;
                                    $value = $transaction[$tr]->value;
                                    $from = $transaction[$tr]->from;
                                    $adminethaddr = decrypt(get_config('eth_address'));
                                    if ($from != $adminethaddr) {
                                        $dep_id = $txid;
                                        $eth_balance = $value;
                                        $ether_balance = ($eth_balance / 1000000000000000000);

                                        $dep_already = $this->eth_checkdepositalready($userid, $dep_id);
                                        if ($dep_already === TRUE && (float)$ether_balance > 0) {
                                            if ($ethaddress == $address) {

                                                $ether_balance = sprintf('%.10f', $ether_balance);

                                                $fetchbalance = get_userbalance($userid, 'ETH');
                                                $finalbalance = $fetchbalance + $ether_balance;

                                                $adminethaddr = decrypt(get_config('eth_address'));
                                                $transid = 'TXD' . $userid . time();
                                                $hash = eth_transfer_fun($ethaddress, $ether_balance, $adminethaddr, $userid);

                                                if ($hash != '' && $hash != 'Error') {
                                                    //wallet record generation
                                                    $instr = new Wallettrans;
                                                    $instr->adtras_id = $transid;
                                                    $instr->currency = 'ETH';
                                                    $instr->address = $ethaddress;
                                                    $instr->hash = $hash;
                                                    $instr->amount = $ether_balance;
                                                    $instr->save();

                                                    //update userbalance
//                                                $upt = Balance::where('user_id', $userid)->first();
//                                                $upt->ETH = $finalbalance;
                                                    $upt = UserBalancesNew::where('user_id', $userid)->where('currency_name', 'ETH')->first();
                                                    $upt->balance = $finalbalance;
                                                    $upt->save();

                                                    //generate transcation record
                                                    $today = date('Y-m-d H:i:s');
                                                    $ip = \Request::ip();
                                                    $ins = new Transaction;
                                                    $ins->user_id = $userid;
                                                    $ins->payment_method = 'Cryptocurrency Account';
                                                    $ins->transaction_id = $transid;
                                                    $ins->currency_name = 'ETH';
                                                    $ins->type = 'Deposit';
                                                    $ins->transaction_type = '1';
                                                    $ins->amount = $ether_balance;
                                                    $ins->updated_at = $today;
                                                    $ins->crypto_address = $address;
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
                                                    $ins->blocknumber = $block_number;

                                                    //sent mail to enduser
                                                    if ($ins->save()) {
                                                        $this->deposit_mail($userid, $ether_balance, $transid, 'ETH');

                                                        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));

                                                        $pusher->trigger('private-transaction_' . $userid, 'deposit-event', array('User_id' => $userid, 'Transaction_id' => $transid, 'Currency' => 'ETH', 'Amount' => $ether_balance, 'Status' => 'Completed', 'Time' => $today));

                                                    }
                                                    $jsonresult[$tr] = array('status' => 'Ok', 'message' => 'Transfer Completed', 'Block_number' => $block_number, 'TransactionId' => $txid, 'Value' => $value);

                                                } //if block of ether block transaction
                                                elseif ($hash == 'Error') {
                                                    $jsonresult[$tr] = array('Status' => 'Error');
                                                }

                                            }
                                        }
                                    } else {
                                        \Log::info('From admin address.');
                                    }
                                }

                            } //for internal transactions
                            else {
                                $eurl = 'https://api.etherscan.io/api?module=account&action=txlistinternal&address=' . $ethaddress . '&startblock=' . $blocknum . '&endblock=latest';

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

                                        $block_number = $transaction[$tr]->blockNumber;
                                        $address = $transaction[$tr]->to;
                                        $txid = $transaction[$tr]->hash;
                                        $value = $transaction[$tr]->value;
                                        $from = $transaction[$tr]->from;
                                        $adminethaddr = decrypt(get_config('eth_address'));
                                        if ($from != $adminethaddr) {
                                            $dep_id = $txid;
                                            $eth_balance = $value;
                                            $ether_balance = ($eth_balance / 1000000000000000000);

                                            $dep_already = $this->eth_checkdepositalready($userid, $dep_id);
                                            if ($dep_already === TRUE && (float)$ether_balance > 0) {
                                                if ($ethaddress == $address) {

                                                    $ether_balance = sprintf('%.10f', $ether_balance);

                                                    $fetchbalance = get_userbalance($userid, 'ETH');
                                                    $finalbalance = $fetchbalance + $ether_balance;

                                                    $adminethaddr = decrypt(get_config('eth_address'));
                                                    $transid = 'TXD' . $userid . time();
                                                    $hash = eth_transfer_fun($ethaddress, $ether_balance, $adminethaddr, $userid);

                                                    if ($hash != '' && $hash != 'Error') {
                                                        //wallet record generation
                                                        $instr = new Wallettrans;
                                                        $instr->adtras_id = $transid;
                                                        $instr->currency = 'ETH';
                                                        $instr->address = $ethaddress;
                                                        $instr->hash = $hash;
                                                        $instr->amount = $ether_balance;
                                                        $instr->save();

                                                        //update userbalance
//                                                    $upt = Balance::where('user_id', $userid)->first();
//                                                    $upt->ETH = $finalbalance;
                                                        $upt = UserBalancesNew::where('user_id', $userid)->where('currency_name', 'ETH')->first();
                                                        $upt->balance = $finalbalance;
                                                        $upt->save();

                                                        //generate transcation record
                                                        $today = date('Y-m-d H:i:s');
                                                        $ip = \Request::ip();
                                                        $ins = new Transaction;
                                                        $ins->user_id = $userid;
                                                        $ins->payment_method = 'Cryptocurrency Account';
                                                        $ins->transaction_id = $transid;
                                                        $ins->currency_name = 'ETH';
                                                        $ins->type = 'Deposit';
                                                        $ins->transaction_type = '1';
                                                        $ins->amount = $ether_balance;
                                                        $ins->updated_at = $today;
                                                        $ins->crypto_address = $address;
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
                                                        $ins->blocknumber = $block_number;

                                                        //sent mail to enduser
                                                        if ($ins->save()) {
                                                            $this->deposit_mail($userid, $ether_balance, $transid, 'ETH');
                                                            $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));

                                                            $pusher->trigger('private-transaction_' . $userid, 'deposit-event', array('User_id' => $userid, 'Transaction_id' => $transid, 'Currency' => 'ETH', 'Amount' => $ether_balance, 'Status' => 'Completed', 'Time' => $today));

                                                        }
                                                        $jsonresult[$tr] = array('status' => 'Ok', 'message' => 'Transfer Completed', 'Block_number' => $block_number, 'TransactionId' => $txid, 'Value' => $value);

                                                    } //if block of ether block transaction
                                                    elseif ($hash == 'Error') {
                                                        $jsonresult[$tr] = array('Status' => 'Error');
                                                    }

                                                }
                                            }
                                        } else {
                                            \Log::info('From admin address.');
                                        }
                                    }

                                }
                            }

                        }

                    }
                }

                return $jsonresult;

            } //verify balance if block
            else {
                $jsonresult[] = array('status' => 'Failed', 'message' => 'Insuffficient Balnce');
                return json_encode($jsonresult);
            } //verify blance else block
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //eth deposit console
    function eth_deposit_console_user(Request $request)
    {
        try {
            $hash = $request['hash'];
            $id = $request['id'];
            $amount = $request['amount'];
            $fetchbalance = get_userbalance($id, 'ETH');
            $finalbalance = $fetchbalance + $amount;
            $transid = 'TXD' . $id . time();
            $block_number = $request['block'];
            $dep_id = $request['txid'];
            $userslist = Users::orderBy('id', 'asc')->where('id', $id)->first();

            if ($hash != '' && $hash != 'Error') {
                //wallet record generation
                $instr = new Wallettrans;
                $instr->adtras_id = $transid;
                $instr->currency = 'ETH';
                $instr->address = get_user_address($id, 'ETH');
                $instr->hash = $hash;
                $instr->amount = $amount;
                $instr->save();

                //update userbalance
//                $upt = Balance::where('user_id', $id)->first();
//                $upt->ETH = $finalbalance;
                $upt = UserBalancesNew::where('user_id', $id)->where('currency_name', 'ETH')->first();
                $upt->balance = $finalbalance;
                $upt->save();

                //generate transcation record
                $today = date('Y-m-d H:i:s');
                $ip = \Request::ip();
                $ins = new Transaction;
                $ins->user_id = $id;
                $ins->payment_method = 'Cryptocurrency Account';
                $ins->transaction_id = $transid;
                $ins->currency_name = 'ETH';
                $ins->type = 'Deposit';
                $ins->transaction_type = '1';
                $ins->amount = $amount;
                $ins->updated_at = $today;
                $ins->crypto_address = $userslist->ETH_addr;
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
                $ins->blocknumber = $block_number;

                //sent mail to enduser
                if ($ins->save()) {
                    $this->deposit_mail($id, $amount, $transid, 'ETH');
                    $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));

                    $pusher->trigger('private-transaction_' . $id, 'deposit-event', array('User_id' => $id, 'Transaction_id' => $transid, 'Currency' => 'ETH', 'Amount' => $amount, 'Status' => 'Completed', 'Time' => $today));

                }
                $jsonresult = array('status' => 'Ok', 'message' => 'Transfer Completed', 'Block_number' => $block_number, 'TransactionId' => $txid, 'Value' => $value);
                return json_encode($jsonresult);
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function eth_checkdepositalready($user_id, $txd_id)
    {
        try {
            $check = Transaction::where('user_id', $user_id)->where('type', 'Deposit')->where('wallet_txid', $txd_id)->count();
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

    //for latest mined node difference
    function last_mined_block_difference()
    {
        try {
            $get_currency_list = Node::all();
            if ($get_currency_list) {
                foreach ($get_currency_list as $currency) {
                    $name = $currency->currency_name;
                    $ip = ($currency->ip_address);
                    $port = ($currency->port_no);

                    $get_last_block = get_last_block($ip, $port);

                    $get_live_block = get_recent_block();

                    if ($get_live_block > $get_last_block) {
                        $diff = $get_live_block - $get_last_block;
                        if ($diff > 100) {
                            sendBlocklagMail($name, $diff);
                            $currency->save();

                        }
                    }

                }

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

    function btc_deposit_process()
    {
        try {
            $date = date('Y-m-d');
            $time = date('h:i:s');
            $bitcoin = get_btc_transactionlist();
            $bitcoin_isvalid = $bitcoin->listtransactions();

            if ($bitcoin_isvalid) {
                for ($i = 0; $i < count($bitcoin_isvalid); $i++) {
                    $account = $bitcoin_isvalid[$i]['account'];
                    $address = $bitcoin_isvalid[$i]['address'];
                    $category = $bitcoin_isvalid[$i]['category'];
                    $btctxid = $bitcoin_isvalid[$i]['txid'];
                    if ($category == 'receive') {
                        $isvalid = $bitcoin->gettransaction($btctxid);
                        $det_category = $isvalid['details'][0]['category'];
                        if ($det_category == "receive") {
                            $btcaccount = $isvalid['details'][0]['account'];
                            $btcaddress = $isvalid['details'][0]['address'];
                            $bitcoin_balance = $isvalid['details'][0]['amount'];
                            $btcconfirmations = $isvalid['confirmations'];
                        } else {
                            $btcaccount = $isvalid['details'][1]['account'];
                            $btcaddress = $isvalid['details'][1]['address'];
                            $bitcoin_balance = $isvalid['details'][1]['amount'];
                            $btcconfirmations = $isvalid['confirmations'];
                        }
                        $amount = $bitcoin_balance;
                        if ($btcconfirmations >= 3) {
                            $userid = get_userid_btcaddr($btcaddress);
                            if (is_numeric($userid)) {
                                $checktrans = Transaction::where('type', 'Deposit')->where('wallet_txid', $btctxid)->first();
                                if (count($checktrans) == 0) {
                                    $fetchbalance = get_userbalance($userid, 'BTC');
                                    $finalbalance = $fetchbalance + $bitcoin_balance;
//                                    $upt = Balance::where('user_id', $userid)->first();
//                                    $upt->BTC = $finalbalance;
                                    $upt = UserBalancesNew::where('user_id', $userid)->where('currency_name', 'BTC')->first();
                                    $upt->balance = $finalbalance;
                                    $upt->save();

                                    $transid = 'TXD' . $userid . time();
                                    $today = date('Y-m-d H:i:s');
                                    $ip = \Request::ip();
                                    $ins = new Transaction;
                                    $ins->user_id = $userid;
                                    $ins->payment_method = 'Cryptocurrency Account';
                                    $ins->transaction_id = $transid;
                                    $ins->currency_name = 'BTC';
                                    $ins->type = 'Deposit';
                                    $ins->transaction_type = '1';
                                    $ins->amount = $bitcoin_balance;
                                    $ins->updated_at = $today;
                                    $ins->crypto_address = $btcaddress;
                                    $ins->transfer_amount = '0';
                                    $ins->fee = '0';
                                    $ins->tax = '0';
                                    $ins->verifycode = '1';
                                    $ins->order_id = '0';
                                    $ins->status = 'Completed';
                                    $ins->cointype = '2';
                                    $ins->payment_status = 'Paid';
                                    $ins->paid_amount = '0';
                                    $ins->wallet_txid = $btctxid;
                                    $ins->ip_address = $ip;
                                    $ins->verify = '1';
                                    $ins->blocknumber = '';
                                    if ($ins->save()) {
                                        $this->deposit_mail($userid, $bitcoin_balance, $transid, 'BTC');
                                        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));

                                        $pusher->trigger('private-transaction_' . $userid, 'deposit-event', array('User_id' => $userid, 'Transaction_id' => $transid, 'Currency' => 'BTC', 'Amount' => $bitcoin_balance, 'Status' => 'Completed', 'Time' => $today));

                                    }
                                }
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


    function usdt_deposit_process()
    {
        try {
            $date = date('Y-m-d');
            $time = date('h:i:s');
            $bitcoin_isvalid = get_usdt_transactionlist();

            if ($bitcoin_isvalid) {
                for ($i = 0; $i < count($bitcoin_isvalid); $i++) {
                    $propertyid = $bitcoin_isvalid[$i]['propertyid'];
                    $mine_flag = $bitcoin_isvalid[$i]['ismine'];

                    if ($propertyid == 31 && $mine_flag == true) {
                        $isvalid = $bitcoin_isvalid[$i]['txid'];
                        $checktrans = Transaction::where('type', 'Deposit')->where('wallet_txid', $isvalid)->first();
                        $reference_address = $bitcoin_isvalid[$i]['referenceaddress'];
                        $user_check = get_userid_btcaddr($reference_address);
                        if ($user_check != 'no' && count($checktrans) == 0) {
                            $userid = $user_check;
                            $btcconfirmations = $bitcoin_isvalid[$i]['confirmations'];
                            $sendaddress = $bitcoin_isvalid[$i]['sendingaddress'];

                            if ($btcconfirmations >= 3) {
                                if (is_numeric($userid)) {
                                    $usdt_balance = $bitcoin_isvalid[$i]['amount'];
                                    $fee = $bitcoin_isvalid[$i]['fee'];
                                    $blocknumber = $bitcoin_isvalid[$i]['block'];
                                    $fetchbalance = get_userbalance($userid, 'USDT');
                                    $finalbalance = $fetchbalance + $usdt_balance;
//                                    $upt = Balance::where('user_id', $userid)->first();
//                                    $upt->BTC = $finalbalance;
                                    $upt = UserBalancesNew::where('user_id', $userid)->where('currency_name', 'USDT')->first();
                                    $upt->balance = $finalbalance;
                                    $upt->save();

                                    $transid = 'TXD' . $userid . time();
                                    $today = date('Y-m-d H:i:s');
                                    $ip = \Request::ip();
                                    $ins = new Transaction;
                                    $ins->user_id = $userid;
                                    $ins->payment_method = 'Cryptocurrency Account';
                                    $ins->transaction_id = $transid;
                                    $ins->currency_name = 'USDT';
                                    $ins->type = 'Deposit';
                                    $ins->transaction_type = '1';
                                    $ins->amount = $usdt_balance;
                                    $ins->updated_at = $today;
                                    $ins->crypto_address = $sendaddress;
                                    $ins->transfer_amount = '0';
                                    $ins->fee = $fee;
                                    $ins->tax = '0';
                                    $ins->verifycode = '1';
                                    $ins->order_id = '0';
                                    $ins->status = 'Completed';
                                    $ins->cointype = '2';
                                    $ins->payment_status = 'Paid';
                                    $ins->paid_amount = '0';
                                    $ins->wallet_txid = $isvalid;
                                    $ins->ip_address = $ip;
                                    $ins->verify = '1';
                                    $ins->blocknumber = $blocknumber;
                                    if ($ins->save()) {
                                        $this->deposit_mail($userid, $usdt_balance, $transid, 'USDT');
                                        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));

                                        $pusher->trigger('private-transaction_' . $userid, 'deposit-event', array('User_id' => $userid, 'Transaction_id' => $transid, 'Currency' => 'USDT', 'Amount' => $usdt_balance, 'Status' => 'Completed', 'Time' => $today));

                                        $adminusdtaddr = decrypt(get_config('usdt_address'));
                                        usdt_transfer($reference_address, $adminusdtaddr);

                                    }

                                }

                            }
                        }

                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return $e->getMessage() . '<br>' . $e->getLine() . ' ' . $e->getFile();
        }
    }

    //bch deposit
//    function bch_deposit_process()
//    {
//        try {
//            $date = date('Y-m-d');
//            $time = date('h:i:s');
//            $bitcoin = get_bch_transactionlist();
//            $bitcoin_isvalid = $bitcoin->listtransactions();
//
//            if ($bitcoin_isvalid) {
//                for ($i = 0; $i < count($bitcoin_isvalid); $i++) {
//                    $account = $bitcoin_isvalid[$i]['account'];
//                    $address = $bitcoin_isvalid[$i]['address'];
//                    $category = $bitcoin_isvalid[$i]['category'];
//                    $btctxid = $bitcoin_isvalid[$i]['txid'];
//                    if ($category == 'receive') {
//                        $isvalid = $bitcoin->gettransaction($btctxid);
//                        $det_category = $isvalid['details'][0]['category'];
//                        if ($det_category == "receive") {
//                            $btcaccount = $isvalid['details'][0]['account'];
//                            $btcaddress = $isvalid['details'][0]['address'];
//                            $bitcoin_balance = $isvalid['details'][0]['amount'];
//                            $btcconfirmations = $isvalid['confirmations'];
//                        } else {
//                            $btcaccount = $isvalid['details'][1]['account'];
//                            $btcaddress = $isvalid['details'][1]['address'];
//                            $bitcoin_balance = $isvalid['details'][1]['amount'];
//                            $btcconfirmations = $isvalid['confirmations'];
//                        }
//                        $amount = $bitcoin_balance;
//                        if ($btcconfirmations >= 3) {
//                            $userid = get_userid_bchaddr($btcaddress);
//                            if (is_numeric($userid)) {
//                                $checktrans = Transaction::where('type', 'Deposit')->where('wallet_txid', $btctxid)->first();
//                                if (count($checktrans) == 0) {
//                                    $fetchbalance = get_userbalance($userid, 'BCH');
//                                    $finalbalance = $fetchbalance + $bitcoin_balance;
////                                    $upt = Balance::where('user_id', $userid)->first();
////                                    $upt->BCH = $finalbalance;
//                                    $upt = UserBalancesNew::where('user_id',$userid)->where('currency_name','BCH')->first();
//                                    $upt->balance = $finalbalance;
//                                    $upt->save();
//
//                                    $transid = 'TXD' . $userid . time();
//                                    $today = date('Y-m-d H:i:s');
//                                    $ip = \Request::ip();
//                                    $ins = new Transaction;
//                                    $ins->user_id = $userid;
//                                    $ins->payment_method = 'Cryptocurrency Account';
//                                    $ins->transaction_id = $transid;
//                                    $ins->currency_name = 'BCH';
//                                    $ins->type = 'Deposit';
//                                    $ins->transaction_type = '1';
//                                    $ins->amount = $bitcoin_balance;
//                                    $ins->updated_at = $today;
//                                    $ins->crypto_address = $btcaddress;
//                                    $ins->transfer_amount = '0';
//                                    $ins->fee = '0';
//                                    $ins->tax = '0';
//                                    $ins->verifycode = '1';
//                                    $ins->order_id = '0';
//                                    $ins->status = 'Completed';
//                                    $ins->cointype = '2';
//                                    $ins->payment_status = 'Paid';
//                                    $ins->paid_amount = '0';
//                                    $ins->wallet_txid = $btctxid;
//                                    $ins->ip_address = $ip;
//                                    $ins->verify = '1';
//                                    $ins->blocknumber = '';
//                                    if ($ins->save()) {
//                                        $this->deposit_mail($userid, $bitcoin_balance, $transid, 'BCH');
//                                        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));
//
//                                        $pusher->trigger('private-transaction_' . $userid, 'deposit-event', array('User_id' => $userid, 'Transaction_id' => $transid, 'Currency' => 'BCH', 'Amount' => $bitcoin_balance, 'Status' => 'Completed', 'Time' => $today));
//
//                                    }
//                                }
//                            }
//
//                        }
//                    }
//                }
//            }
//        }
//        catch (\Exception $e) {
//            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
//        return view('errors.404');
//        }
//    }


//    function xrp_deposit_process()
//    {
//        try {
//            $ripple_address = decrypt(get_config('xrp_address'));
//            $max_ledgerversion = Transaction::max('ledgerversion');
//            if ($max_ledgerversion == "" || $max_ledgerversion == '0') {
//                $max_ledgerversion = "3776014";
//            }
//            $output = array();
//            $return_var = -1;
//            $result = exec('cd /var/www/html/public/crypto; node ripple_transaction.js ' . trim($max_ledgerversion), $output, $return_var);
//            $result = json_decode($result);
//            if ($result) {
//                foreach ($result as $res) {
//                    $txid = $res->id;
//                    $checktrans = Transaction::where('type', 'Deposit')->where('wallet_txid', $txid)->where('currency_name', 'XRP')->first();
//                    if (count($checktrans) == 0) {
//                        $address = $res->specification->destination->address;
//                        $amount = $res->specification->destination->amount->value;
//                        $ledgerversion = $res->outcome->ledgerVersion;
//                        try {
//                            $tag = $res->specification->destination->tag;
//                        } catch (\Exception $exception) {
//                            $tag = "";
//                            $transid = 'TXD' . 00 . time();
//                            $today = date('Y-m-d H:i:s');
//                            $ip = \Request::ip();
//                            $ins = new Transaction;
//                            $ins->user_id = "";
//                            $ins->payment_method = 'Cryptocurrency Account';
//                            $ins->transaction_id = $transid;
//                            $ins->currency_name = 'XRP';
//                            $ins->type = 'Deposit_without_tag';
//                            $ins->transaction_type = '1';
//                            $ins->amount = $amount;
//                            $ins->updated_at = $today;
//                            $ins->crypto_address = $address;
//                            $ins->transfer_amount = '0';
//                            $ins->fee = '0';
//                            $ins->tax = '0';
//                            $ins->verifycode = '1';
//                            $ins->order_id = '0';
//                            $ins->status = 'Admin';
//                            $ins->cointype = '2';
//                            $ins->payment_status = 'Paid';
//                            $ins->paid_amount = '0';
//                            $ins->wallet_txid = $txid;
//                            $ins->ip_address = $ip;
//                            $ins->verify = '1';
//                            $ins->blocknumber = '0';
//                            $ins->xrp_desttag = $tag;
//                            $ins->ledgerversion = $ledgerversion;
//                            if ($ins->save()) {
//                                $this->deposit_admin_mail('10', $amount, $txid, 'XRP');
//                            }
//                        }
//
//                        if ($address == $ripple_address) {
//                            if ($tag != "") {
//                                $userdetails = get_dest_userid($tag);
//                                if ($userdetails) {
//                                    $userid = $userdetails;
//
//                                    $fetchbalance = get_userbalance($userid, 'XRP');
//                                    $finalbalance = $fetchbalance + $amount;
////                                    $upt = Balance::where('user_id', $userid)->first();
////                                    $upt->XRP = $finalbalance;
//                                    $upt = UserBalancesNew::where('user_id',$userid)->where('currency_name','XRP')->first();
//                                    $upt->balance = $finalbalance;
//                                    $upt->save();
//
//                                    $transid = 'TXD' . $userid . time();
//                                    $today = date('Y-m-d H:i:s');
//                                    $ip = \Request::ip();
//                                    $ins = new Transaction;
//                                    $ins->user_id = $userid;
//                                    $ins->payment_method = 'Cryptocurrency Account';
//                                    $ins->transaction_id = $transid;
//                                    $ins->currency_name = 'XRP';
//                                    $ins->type = 'Deposit';
//                                    $ins->transaction_type = '1';
//                                    $ins->amount = $amount;
//                                    $ins->updated_at = $today;
//                                    $ins->crypto_address = $address;
//                                    $ins->transfer_amount = '0';
//                                    $ins->fee = '0';
//                                    $ins->tax = '0';
//                                    $ins->verifycode = '1';
//                                    $ins->order_id = '0';
//                                    $ins->status = 'Completed';
//                                    $ins->cointype = '2';
//                                    $ins->payment_status = 'Paid';
//                                    $ins->paid_amount = '0';
//                                    $ins->wallet_txid = $txid;
//                                    $ins->ip_address = $ip;
//                                    $ins->verify = '1';
//                                    $ins->blocknumber = '0';
//                                    $ins->xrp_desttag = $tag;
//                                    $ins->ledgerversion = $ledgerversion;
//                                    if ($ins->save()) {
//                                        $this->deposit_mail($userid, $amount, $transid, 'XRP');
//                                        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));
//
//                                        $pusher->trigger('private-transaction_' . $userid, 'deposit-event', array('User_id' => $userid, 'Transaction_id' => $transid, 'Currency' => 'XRP', 'Amount' => $amount, 'Status' => 'Completed', 'Time' => $today));
//
//                                    }
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//        }
//        catch (\Exception $e) {
//            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
//        return view('errors.404');
//        }
//    }

    //opening balance
    function opening_balance()
    {
        try {
            $userslist = UserBalance::orderBy('user_id', 'asc')
                ->get();

            foreach ($userslist as $user) {
                $i = 0;
                $User_id = $user->user_id;
                $XDC = $user->XDC;
                $XDCE = $user->XDCE;
                $BTC = $user->BTC;
                $ETH = $user->ETH;
                $XRP = $user->XRP;

                $opening_bal = new OpeningBalance();
                $opening_bal->user_id = $User_id;
                $opening_bal->XDC = $XDC;
                $opening_bal->BTC = $BTC;
                $opening_bal->XDCE = $XDCE;
                $opening_bal->ETH = $ETH;
                $opening_bal->XRP = $XRP;
                $opening_bal->save();
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //closing Balance
    function closing_balance()
    {
        try {
            $userslist = UserBalance::orderBy('user_id', 'asc')
                ->get();

            foreach ($userslist as $user) {
                $i = 0;
                $User_id = $user->user_id;
                $XDC = $user->XDC;
                $XDCE = $user->XDCE;
                $BTC = $user->BTC;
                $ETH = $user->ETH;
                $XRP = $user->XRP;

                $closing_bal = new ClosingBalance();
                $closing_bal->user_id = $User_id;
                $closing_bal->XDC = $XDC;
                $closing_bal->BTC = $BTC;
                $closing_bal->XDCE = $XDCE;
                $closing_bal->ETH = $ETH;
                $closing_bal->XRP = $XRP;
                $closing_bal->save();
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }


    //xdc deposit
    function xdc_deposit_process()
    {
        try {
            $admin = SiteSettings::where('id', 1)->first();
            $last_block = $admin->xdc_block;
            $last_block = $last_block + 1;
            $latest_xdc_block_number = get_last_block('18.188.115.125', 22001);

            if ($latest_xdc_block_number > 0) {
                for ($i = $last_block; $i <= $latest_xdc_block_number; $i++) {


                    $block_number = $i;

                    $eurl = 'http://xinfin.info/api/blocknumber/' . $block_number . '_0_0';

                    $cObj = curl_init();
                    curl_setopt($cObj, CURLOPT_URL, $eurl);
                    curl_setopt($cObj, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($cObj, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($cObj, CURLOPT_RETURNTRANSFER, TRUE);
                    $output = curl_exec($cObj);
                    $curlinfos = curl_getinfo($cObj);

                    $result = json_decode($output);

                    if ($result && $result->status == 'SUCCESS') {
                        $transactionList = $result->message;

                    } else {
                        $transactionList = '';
                    }

                    //iterating transaction lists
                    foreach ($transactionList as $transaction) {
                        $to_address = $transaction->args->_to;
                        $user = UserCurrencyAddresses::where('currency_addr', '=', $to_address)->first();
                        if ($user != null && $to_address != null) {
                            $dep_id = $transaction->transactionHash;
                            $ether_balance = $transaction->args->_value;
                            $dep_already = $this->eth_checkdepositalready($user->user_id, $dep_id);
                            if ($dep_already === TRUE && (float)$ether_balance > 0) {


                                $email = get_usermail($user->user_id);
                                //$pass = Session::get('xinfinpass');
                                $pass = get_user_details($user->user_id, 'xinpass');
                                login_xdc_fun($email, owndecrypt($pass));


                                $adminethaddr = decrypt(get_config('xdc_address'));
                                $res = transfer_xdctoken($to_address, $ether_balance, $adminethaddr, $user->user_id, owndecrypt($pass));
                                $userid = $user->user_id;
                                if ($res->status == 'SUCCESS') {
                                    $fetchbalance = get_userbalance($userid, 'XDC');
                                    $uptbal = $fetchbalance + $ether_balance;
//                                    $upt = Balance::where('user_id', $userid)->first();
//                                    $upt->XDC = $uptbal;
                                    $upt = UserBalancesNew::where('user_id', $userid)->where('currency_name', 'XDC')->first();
                                    $upt->balance = $finalbalance;
                                    $upt->save();

                                    $transid = 'TXD' . $userid . time();
                                    $today = date('Y-m-d H:i:s');
                                    $ip = \Request::ip();
                                    $ins = new Transaction;
                                    $ins->user_id = $userid;
                                    $ins->payment_method = 'Cryptocurrency Account';
                                    $ins->transaction_id = $transid;
                                    $ins->currency_name = 'XDC';
                                    $ins->type = 'Deposit';
                                    $ins->transaction_type = '1';
                                    $ins->amount = $ether_balance;
                                    $ins->updated_at = $today;
                                    $ins->crypto_address = $to_address;
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
                                    $ins->blocknumber = $transaction->blockNumber;
                                    $ins->save();
                                    $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));

                                    $pusher->trigger('private-transaction_' . $userid, 'deposit-event', array('User_id' => $userid, 'Transaction_id' => $transid, 'Currency' => 'XDC', 'Amount' => $ether_balance, 'Status' => 'Completed', 'Time' => $today));

                                }
                            }
                        }


                    }
                    if ($last_block < $i) {
                        $admin->xdc_block = $i;
                        $admin->save();
                    }

                }
            }

        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //xdce deposit block wise
    function xdce_deposit_process1()
    {
        try {
            $admin = SiteSettings::where('id', 1)->first();
            $last_block = $admin->xdce_block;
            $last_block = $last_block + 1;
            $latest_xdc_block_number = get_last_block(env('ETH_IP'), env('ETH_PORT'));

            if ($latest_xdc_block_number > 0) {
                for ($i = $last_block; $i <= $latest_xdc_block_number; $i++) {


                    $block_number = $i;

                    $eurl = 'http://xinfin.info/api/blocknumber/' . $block_number . '_0_0';

                    $cObj = curl_init();
                    curl_setopt($cObj, CURLOPT_URL, $eurl);
                    curl_setopt($cObj, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($cObj, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($cObj, CURLOPT_RETURNTRANSFER, TRUE);
                    $output = curl_exec($cObj);
                    $curlinfos = curl_getinfo($cObj);

                    $result = json_decode($output);

                    if ($result && $result->status == 'SUCCESS') {
                        $transactionList = $result->message;

                    } else {
                        $transactionList = '';
                    }

                    //iterating transaction lists
                    foreach ($transactionList as $transaction) {
                        $to_address = $transaction->args->_to;
                        $user = Users::where('XDC_addr', '=', $to_address)->first();
                        if ($user != null && $to_address != null) {
                            $dep_id = $transaction->transactionHash;
                            $ether_balance = $transaction->args->_value;
                            $dep_already = $this->eth_checkdepositalready($user->id, $dep_id);
                            if ($dep_already === TRUE && (float)$ether_balance > 0) {


                                $email = get_usermail($user->id);
                                //$pass = Session::get('xinfinpass');
                                $pass = get_user_details($user->id, 'xinpass');
                                login_xdc_fun($email, owndecrypt($pass));


                                $adminethaddr = decrypt(get_config('xdc_address'));
                                $res = transfer_xdctoken($to_address, $ether_balance, $adminethaddr, $user->id, owndecrypt($pass));
                                $userid = $user->id;
                                if ($res->status == 'SUCCESS') {
                                    $fetchbalance = get_userbalance($userid, 'XDC');
                                    $uptbal = $fetchbalance + $ether_balance;
//                                    $upt = Balance::where('user_id', $userid)->first();
//                                    $upt->XDC = $uptbal;
                                    $upt = UserBalancesNew::where('user_id', $userid)->where('currency_name', 'XDC')->first();
                                    $upt->balance = $finalbalance;
                                    $upt->save();

                                    $transid = 'TXD' . $userid . time();
                                    $today = date('Y-m-d H:i:s');
                                    $ip = \Request::ip();
                                    $ins = new Transaction;
                                    $ins->user_id = $userid;
                                    $ins->payment_method = 'Cryptocurrency Account';
                                    $ins->transaction_id = $transid;
                                    $ins->currency_name = 'XDC';
                                    $ins->type = 'Deposit';
                                    $ins->transaction_type = '1';
                                    $ins->amount = $ether_balance;
                                    $ins->updated_at = $today;
                                    $ins->crypto_address = $to_address;
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
                                    $ins->blocknumber = $transaction->blockNumber;
                                    $ins->save();
                                }
                            }
                        }


                    }
                    if ($last_block < $i) {
                        $admin->mined_block = $i;
                        $admin->save();
                    }
                }
            }

        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }


    //end class
    function xdce_deposit_process()
    {
        try {
            $userslist = Users::orderBy('id', 'asc')->get();

            foreach ($userslist as $user) {
                $id = $user->id;
                $xdceaddr = get_user_address($id, 'XDCE');
                if ($xdceaddr == '') {
                    $xdcebal = 0;
                } else {
                    $xdcebal = get_livexdce_bal($xdceaddr);
                }
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
                                            $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));

                                            $pusher->trigger('private-transaction_' . $id, 'deposit-event', array('User_id' => $id, 'Transaction_id' => $transid, 'Currency' => 'XDCE', 'Amount' => $ether_balance, 'Status' => 'Completed', 'Time' => $today));

                                        }
                                    }
                                }
                            }

                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }

            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }


    //for each user xdce deposit
    function xdce_deposit_process_user($id)
    {
        try {
            $userslist[] = Users::orderBy('id', 'asc')->where('XDCE_addr', $id)->first();
            foreach ($userslist as $user) {
                $id = $user->id;
                $xdceaddr = get_user_address($id, 'XDCE');
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
                                            $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), array('cluster' => 'ap1'));

                                            $pusher->trigger('private-transaction_' . $id, 'deposit-event', array('User_id' => $id, 'Transaction_id' => $transid, 'Currency' => 'XDCE', 'Amount' => $ether_balance, 'Status' => 'Completed', 'Time' => $today));

                                            echo "Deposit of " . $ether_balance . " XDCE completed for user id " . $id . ".";
                                        } else {
                                            echo "Database entry not successful";
                                        }
                                    } else {
                                        echo "Address not matching";
                                    }
                                } else {
                                    echo "Deposit already completed or Insufficient balance.";
                                }
                            }

                        } else {
                            echo $xdceTransactionList->status;
                        }
                    } catch (\Exception $e) {
                        echo $e->getMessage();
                        continue;
                    }
                } else {
                    echo "No pending XDCE deposit for user id " . $id . ".";
                }

//            if($)

            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    //for updating duplicate_record
    function duplicate_record()
    {
        try {
            $lObjTrades = DB::select("SELECT *,
COUNT(1) as CNT
FROM		xdc_trade_order
GROUP BY	updated_at,Amount,Price,Type
HAVING		COUNT(1) > 1  
ORDER BY xdc_trade_order.id ASC");

            foreach ($lObjTrades as $lObjTrade) {

                $lObjDuplicateTrade = Trade::where('id', $lObjTrade->id)->first();
                $lObjDuplicateTrade->duplicate = 1;
                $lObjDuplicateTrade->save();
            }
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function btc_records()
    {
        try {
            $bitcoin = get_btc_transactionlist();
            $bitcoin_isvalid = $bitcoin->listtransactions();
            echo json_encode($bitcoin_isvalid);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

}


