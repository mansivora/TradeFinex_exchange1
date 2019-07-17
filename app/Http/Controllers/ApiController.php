<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class ApiController extends Controller
{

    function transfer_erc20admin(Request $request)
    {
        try {
            $fromaddress = $request['from_address'];
            $toaddr = $request['to_address'];
            $contractaddress = $request['contract_address'];
            $amount = $request['amount'];
            $password = $request['password'];
            $wallet_ip = $request['eth_ip'];
            $wallet_port = $request['port'];
            $tokendecimal = $request['tokendecimal'];
            $server_path = $_SERVER["DOCUMENT_ROOT"];
            $result = exec('cd ' . $server_path . '/erc && node send_erc20_admin.js ' . $wallet_ip . ' ' . $wallet_port . ' ' . $fromaddress . ' ' . $toaddr . ' ' . $amount . ' ' . $contractaddress . ' ' . $password . ' ' . $tokendecimal, $output, $return_var);
            $out = json_decode($result);
            return json_encode($out);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function transfer_erc20(Request $request)
    {
        try {
            $fromaddress = $request['from_address'];
            $toaddr = $request['to_address'];
            $contractaddress = $request['contract_address'];
            $amount = $request['amount'];
            $password = $request['password'];
            $wallet_ip = $request['eth_ip'];
            $wallet_port = $request['port'];
            $tokendecimal = $request['tokendecimal'];
            $server_path = $_SERVER["DOCUMENT_ROOT"];
            $result = exec('cd ' . $server_path . '/erc && node send_erc20.js ' . $wallet_ip . ' ' . $wallet_port . ' ' . $fromaddress . ' ' . $toaddr . ' ' . $amount . ' ' . $contractaddress . ' ' . $password . ' ' . $tokendecimal, $output, $return_var);
            $out = json_decode($result);
            return json_encode($out);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return json_encode('error');
        }
    }

    function getting_eth_balance(Request $request)
    {
        try {
            $output = array();
            $return_var = -1;
            $ip = $request['eth_ip'];
            $port = $request['port'];
            $address = $request['address'];
            $server_path = $_SERVER["DOCUMENT_ROOT"];
            $balance = exec('cd ' . $server_path . '/crypto && node eth_balance.js ' . $ip . ' ' . $port . ' ' . $address, $output, $return_var);
            $bal = $balance / 1000000000000000000;
            return json_encode($bal);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function get_token_balance(Request $request)
    {
        try {
            $output = array();
            $return_var = -1;
            $ip = $request['eth_ip'];
            $port = $request['port'];
            $address = $request['address'];
            $contract_addr = $request['contract_address'];
            $tokendecimal = $request['tokendecimal'];
            $server_path = $_SERVER["DOCUMENT_ROOT"];
            $balance = exec('cd ' . $server_path . '/erc && node token_balance.js ' . $ip . ' ' . $port . ' ' . $address . ' ' . $contract_addr . ' ' . $tokendecimal, $output, $return_var);
            return json_encode($balance);
        } catch (\Exception $e) {
            return 0;
        }
    }

    function get_estimate_gas(Request $request)
    {
        try {
            $output = array();
            $return_var = -1;
            $ip = $request['eth_ip'];
            $port = $request['port'];
            $fromaddr = $request['from_address'];
            $toaddr = $request['to_address'];
            $amount = $request['amount'];
            $server_path = $_SERVER["DOCUMENT_ROOT"];
            $gas = exec('cd ' . $server_path . '/erc && node estimate_gas.js ' . $ip . ' ' . $port . ' ' . $fromaddr . ' ' . $toaddr . ' ' . $amount, $output, $return_var);
            $estimate_gas = $gas / 1000000000000000000;
            return json_encode($estimate_gas);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function eth_transfer_erc20_admin(Request $request)
    {
        try {
            $output = array();
            $return_var = -1;
            $wallet_ip = $request['eth_ip'];
            $wallet_port = $request['port'];
            $password = $request['password'];
            $fromaddr = $request['from_address'];
            $toaddr = $request['to_address'];
            $amount = $request['amount'];
            $server_path = $_SERVER["DOCUMENT_ROOT"];
            $result = exec('cd ' . $server_path . '/crypto && node sent_eth_admin.js ' . $wallet_ip . ' ' . $wallet_port . ' ' . $fromaddr . ' ' . $toaddr . ' ' . $amount . ' ' . $password, $output, $return_var);
            $out = json_decode($result);
            return json_encode($out);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function check_tx_status_eth(Request $request)
    {
        try {
            $output = array();
            $return_var = -1;
            $wallet_ip = $request['eth_ip'];
            $wallet_port = $request['port'];
            $hash = $request['hash'];
            $server_path = $_SERVER["DOCUMENT_ROOT"];
            $result = exec('cd ' . $server_path . '/erc && node transaction_status.js ' . $wallet_ip . ' ' . $wallet_port . ' ' . $hash, $output, $return_var);
            return json_encode($result);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function eth_transfer_fun_admin(Request $request)
    {
        try {
            $output = array();
            $return_var = -1;
            $wallet_ip = $request['eth_ip'];
            $wallet_port = $request['port'];
            $password = $request['password'];
            $fromaddr = $request['from_address'];
            $toaddr = $request['to_address'];
            $amount = $request['amount'];
            $server_path = $_SERVER["DOCUMENT_ROOT"];
            $result = exec('cd ' . $server_path . '/crypto && node sent_eth_admin.js ' . $wallet_ip . ' ' . $wallet_port . ' ' . $fromaddr . ' ' . $toaddr . ' ' . $amount . ' ' . $password, $output, $return_var);
            $out = json_decode($result);
            return json_encode($out);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

    function eth_transfer_fun(Request $request)
    {
        try {
            $output = array();
            $return_var = -1;
            $wallet_ip = $request['eth_ip'];
            $wallet_port = $request['port'];
            $password = $request['password'];
            $fromaddr = $request['from_address'];
            $toaddr = $request['to_address'];
            $amount = $request['amount'];
            $server_path = $_SERVER["DOCUMENT_ROOT"];
            $result = exec('cd ' . $server_path . '/crypto && node sent_eth.js ' . $wallet_ip . ' ' . $wallet_port . ' ' . $fromaddr . ' ' . $toaddr . ' ' . $amount . ' ' . $password, $output, $return_var);
            $out = json_decode($result);
            return json_encode($out);
        } catch (\Exception $e) {
            \Log::error([$e->getMessage(), $e->getLine(), $e->getFile()]);
            return view('errors.404');
        }
    }

}