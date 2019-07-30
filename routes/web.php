<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'check_admin', 'middleware' => ['web','XSS']], function (){

    Route::get('/', 'SiteadminController@index');
    Route::post('/', 'SiteadminController@index');
    Route::get('/logout', 'SiteadminController@logout');
    Route::get('/home', 'SiteadminController@home');
    Route::get('/profile', 'SiteadminController@profile');
    Route::post('/profile', 'SiteadminController@profile');
    Route::get('/site_settings', 'SiteadminController@site_settings');
    Route::post('/site_settings', 'SiteadminController@site_settings');
    Route::get('/test', 'SiteadminController@test');
    Route::post('/checkpattern', 'SiteadminController@checkpattern');
    Route::get('/change_pattern', 'SiteadminController@change_pattern');
    Route::post('/set_pattern', 'SiteadminController@set_pattern');
    Route::get('/cms', 'SiteadminController@cms');
    Route::get('/updatecms/{id}', 'SiteadminController@updatecms');
    Route::post('/updatecms/{id}', 'SiteadminController@updatecms');
    Route::get('/users', 'SiteadminController@users');
    Route::get('/userbalance', 'SiteadminController@userbalance');
    Route::get('/users_opening_balance', 'SiteadminController@users_opening_balance');
    Route::get('/users_closing_balance', 'SiteadminController@users_closing_balance');
    Route::post('/update_user_balance', 'SiteadminController@update_userbal');
    Route::get('/faq', 'SiteadminController@faq');
    Route::get('/mail_template', 'SiteadminController@mail_template');
    Route::get('/contact_query', 'SiteadminController@contact_query');
    Route::get('/view_enquiry/{id}', 'SiteadminController@view_enquiry');
    Route::post('/view_enquiry/{id}', 'SiteadminController@view_enquiry');
    Route::get('/add_faq', 'SiteadminController@add_faq');
    Route::post('/add_faq', 'SiteadminController@add_faq');
    Route::get('/delete_faq/{id}', 'SiteadminController@delete_faq');
    Route::get('/status_faq/{id}', 'SiteadminController@status_faq');
    Route::get('/update_faq/{id}', 'SiteadminController@update_faq');
    Route::post('/update_faq/{id}', 'SiteadminController@update_faq');
    Route::get('/update_template/{id}', 'SiteadminController@update_template');
    Route::get('/transactions', 'SiteadminController@transactions');
    Route::get('/profit', 'SiteadminController@profit');
    Route::get('/kyc_users', 'SiteadminController@kyc_users');
    Route::get('/token_request', 'SiteadminController@ico_listing');
    Route::get('/status_users/{id}', 'SiteadminController@status_users');
    Route::get('/view_users/{id}', 'SiteadminController@view_users');
    Route::get('/view_kyc/{id}', 'SiteadminController@view_kyc');
    Route::post('/view_kyc/{id}', 'SiteadminController@view_kyc');
    Route::get('/token_view/{id}', 'SiteadminController@ico_view');
    Route::post('/token_view/{id}', 'SiteadminController@ico_view');
    Route::get('/forgot', 'SiteadminController@forgot');
    Route::post('/forgot', 'SiteadminController@forgot');
    Route::get('/trade_history', 'SiteadminController@trade_history');
    Route::get('/swap_history', 'SiteadminController@swap_history');
    Route::get('/trade_mapping', 'SiteadminController@trade_mapping');
    Route::get('/create_ripple_tag', 'SiteadminController@create_riple_xrp_tag');

    Route::get('/cancel_trade/{id}', 'SiteadminController@cancel_trade');
    Route::get('/delete_transaction/{id}', 'SiteadminController@delete_trans');
    Route::get('/pending_history', 'SiteadminController@pending_history');
    Route::get('/deposit_history', 'SiteadminController@deposit_history');
    Route::get('/withdraw_history', 'SiteadminController@withdraw_history');
    Route::get('/updated_history', 'SiteadminController@updated_history');
    Route::get('/market_price', 'SiteadminController@market_price');
    Route::post('/market_price', 'SiteadminController@market_price');
    Route::get('/allprice', 'SiteadminController@all_price');
    Route::get('/meta_content', 'SiteadminController@meta_content');
    Route::get('/update_meta/{id}', 'SiteadminController@update_meta');
    Route::post('/update_meta/{id}', 'SiteadminController@update_meta');
    Route::get('/trading_fee/{currency?}', 'SiteadminController@trading_fee');
    Route::post('/trading_fee/{currency?}', 'SiteadminController@trading_fee');
    Route::get('/fee_config', 'SiteadminController@fee_config');
    Route::post('/fee_config', 'SiteadminController@fee_config');
    Route::get('/user_activity', 'SiteadminController@user_activity');
    Route::get('/whitelists', 'SiteadminController@whitelists');
    Route::post('/whitelists', 'SiteadminController@whitelists');
    Route::get('/delete_whitelist/{id}', 'SiteadminController@delete_whitelist');
    Route::get('/confirm_transfer/{id}', 'SiteadminController@confirm_transfer');
    Route::post('/generate_otp', 'SiteadminController@generate_otp');
    Route::post('/confirm_transfer/{id}', 'SiteadminController@confirm_transfer');
    Route::get('/view_transactions/{trans_id}', 'SiteadminController@view_transactions');
    Route::get('/tradeadmin', 'SiteadminController@tradeadmin');
    Route::get('/total_userbalance', 'SiteadminController@getTotal_Usersbalance');
    Route::get('/testmail', 'SiteadminController@testmail');
    Route::get('/valid_balance', 'SiteadminController@validate_XDC_bal');
    Route::get('/users_balance_validation', 'SiteadminController@users_balance_validation');
    Route::get('/validate_eth_block', 'SiteadminController@validate_eth_block');
    Route::get('/trade_details/{id}', 'SiteadminController@user_XDC_Sell');
    Route::get('/explorer_xdc', 'SiteadminController@users_explorer_validation');
    Route::get('/generate_eth/{id}', 'SiteadminController@generate_eth');
    Route::get('/xrp_withdraw/{id}', 'SiteadminController@xrp_withdraw');
    Route::get('/xrp_create', 'SiteadminController@adminxrpaddress');

    Route::get('/non_email_verified', 'SiteadminController@non_email_verified');
    Route::get('/create_email_verification/{id}', 'SiteadminController@create_email_verification');
    Route::get('/user_transaction_details', 'SiteadminController@user_transaction_details');
//    Route::get('/ftp', 'SiteadminController@ftp_test');
    Route::get('/ico_history', 'SiteadminController@ico_history');
    Route::get('/cancel_pending_ico_order/{id}', 'SiteadminController@Cancel_pending_ico_order');

    Route::get('/set_trade_cancel', 'SiteadminController@set_trade_cancel');
    Route::POST('/update_ico_price', 'SiteadminController@update_ico_price');

    Route::get('/create_bch_all', 'SiteadminController@create_bch_all');
    Route::get('/confirmation','SiteadminController@confirm');
    Route::get('/cancel_multiple/{id}','SiteadminController@cancel_multiple');
    Route::get('/export_user_list','SiteadminController@export_user_list');
    Route::get('/admin_details','SiteadminController@admin_details');
    Route::get('/update_admin/{id}','SiteadminController@update_admin');
    Route::post('/update_admin/{id}','SiteadminController@update_admin');
    Route::get('/referral','SiteadminController@referral');
    Route::post('/referral','SiteadminController@referral');
    Route::post('/deleteaccount','SiteadminController@deleteaccount');


});

Route::group(['prefix' => 'check_admin', 'middleware' => ['web']], function (){

    Route::post('/update_template/{id}', 'SiteadminController@update_template');

});

Route::group(['prefix' => 'auto', 'middleware' => ['web','XSS']], function ()
{
    Route::get('/trade/{pair}', 'AutoTradeController@main');

});

Route::group(['prefix' => '','middleware' => ['web','XSS']], function () {


    Route::get('/', 'UserController@index');
    Route::get('/create_usdt', 'UserController@index');
    Route::get('/home', 'UserController@index');
    Route::get('/investors', 'UserController@investors');
    Route::post('/investors', 'UserController@investors');
    Route::get('/login', 'UserController@login');
    Route::get('/register', 'UserController@register');
    Route::get('/registration', 'UserController@registraion');
    Route::get('/forgotpass', 'UserController@forgotpass');
    Route::get('/test', 'UserController@test');
    Route::post('/register', 'UserController@register');
    Route::get('/userverification/{time}', 'UserController@userverification');
    Route::post('/forgotpass', 'UserController@forgotpass');
    Route::get('/resetpassword/{code}', 'UserController@resetpassword');
    Route::post('/resetpassword/{code}', 'UserController@resetpassword');
    Route::get('/aboutus', 'UserController@aboutus');
    Route::get('/terms', 'UserController@terms');
    Route::get('/privacy', 'UserController@privacy');
    Route::get('/faq', 'UserController@faq');
    Route::get('/news', 'UserController@news');
    Route::get('/contact', 'UserController@contact');
    Route::get('/instrument/{pair}', 'UserController@instrument');
    Route::post('/login', 'UserController@login');
    Route::get('/sessionlogout', 'UserController@sessionlogout');
    Route::get('/logout', 'UserController@logout');
    Route::get('/dashboard', 'UserController@dashboard');
    Route::get('/profile', 'UserController@profile');
    Route::post('/profile', 'UserController@profile');
    Route::post('/change_password', 'UserController@change_password');
    Route::post('/document_submission', 'UserController@document_submission');
    Route::get('/security', 'UserController@security');
    Route::post('/security', 'UserController@security');
    Route::post('/logindo', 'UserController@logindo');
    Route::get('/deposit/{currency?}', 'UserController@deposit');
    Route::get('/wallet', 'UserController@wallet');
    Route::get('/transfercrypto/{currency?}', 'UserController@transfercrypto');
    Route::post('/transferverify/{currency}', 'UserController@transferverify');
    Route::get('/deposit_history', 'UserController@deposit_history');
    Route::get('/transfer_history', 'UserController@transfer_history');
    Route::get('/exchange/{pair?}', 'UserController@exchange');
    Route::get('/indexexchange/{pair?}', 'UserController@indexexchange');
    Route::post('/exchangepair/{pair?}', 'UserController@exchangepair');
    Route::get('/exchange_history/{pair?}', 'UserController@exchange_history');
    Route::get('/contact_us', 'UserController@contact_us');
    Route::post('/contact_us', 'UserController@contact_us');
    Route::get('/trade_history', 'TradeController@trade_history');
    Route::get('/history/{curr?}','UserController@history');
    Route::get('/swap_history', 'TradeController@swap_history');
    Route::get('/wallet_history/{currency?}', 'UserController@wallet_history');
    Route::get('/trade/{pair?}', 'TradeController@index');
    Route::get('/trade/cancel_order/{id}', 'TradeController@cancel_order');
    Route::get('/trade/cancel_multiple/{id}','TradeController@cancel_multiple');
    Route::get('/trade/trade_chart/{pair?}', 'TradeController@trade_chart');
    Route::post('/trade/limit_order', 'TradeController@limit_order');
    Route::post('/trade/stop_order', 'TradeController@stop_order');
    Route::get('/trade/stop_cancel_order/{id}', 'TradeController@stop_cancel_order');
    Route::get('/getotp', 'UserController@getotp');
    Route::get('/api', 'TickerController@getapi');
    Route::get('/checkuser', 'UserController@decryptnumber');
    Route::get('/decryptaddress', 'UserController@decryptAddress');
    Route::get('/encrypting', 'UserController@encryptdetails');
    Route::get('/datetime', 'UserController@currentDateTime');
    Route::get('/swap', 'UserController@swap');
//    Route::get('/ico_history', 'TradeController@ico_history');
    Route::get('/ico', 'ICOController@index');
    Route::get('/usdt', 'UserController@create_usdt');
    Route::get('/applytolist','ICOController@addtoken');
    Route::post('/applytolist','ICOController@addtoken');
    Route::get('/kyc', 'UserController@kyc_details');
    Route::get('/export_pdf', 'UserController@export_pdf');
    Route::get('/export_csv', 'UserController@export_csv');
    Route::get('/transaction_pdf/{type?}', 'UserController@transaction_pdf');
    Route::get('/transaction_csv/{type?}', 'UserController@transaction_csv');
    Route::get('/howtostart', 'UserController@howtostart');

    Route::get('/charts/{pair}', 'TradeController@charts');
    Route::get('/charts/{pair}/history', 'TradeController@chart_history');
    Route::get('/charts/{pair}/config', 'TradeController@chart_config');
    Route::get('/charts/{pair}/time', 'TradeController@chart_time');
    Route::get('/charts/{pair}/symbol_info', 'TradeController@symbol_info');
    Route::POST('/kyc', 'UserController@kyc_details');
    Route::POST('/buyico', 'ICOController@ico_buy');

    Route::post('/sendreferral', 'UserController@sendreferral');

    Route::POST('/test_trade', 'TradeController@trade_orders');

    Route::get('/testing_trade','TradeController@demo');
//    Route::get('/usdt','UserController@generate_currency_address');

    Route::get('/walletid/check','UserController@eth_checkdepositalready');
//    Route::get('/sitemap.xml','UserController@sitemap');

    Route::get('/eth_address','UserController@check_ripple_balance');
    Route::get('/demo_btc','UserController@demo_btc');

    Route::post('/market_order','TradeController@market_order');

    Route::get('/btc','UserController@btc');

    Route::get('/add_asset', 'UserController@add_asset');



//
//    Route::get('/cancel_ico_trade/{id}', 'ICOController@Cancel_pending_order');
//    Route::get('/ico', 'ICOController@index');
//    Route::POST('/ico', 'ICOController@ico1');
//    Route::POST('/buyico1', 'ICOController@ico_buy1');
//    Route::get('/testico', 'ICOController@ico1');
//    Route::POST('/testico', 'ICOController@ico1');

    Route::post('/getdata','UserController@getdata');
    Route::get('/get_erc20_blocknumber','UserController@get_erc20_blocknumber');
    Route::post('/referral','UserController@referral');

//

});

Route::group(['prefix' => 'api/v1','middleware' => ['api']], function () {
    Route::post('/getdata','UserController@getdata');
});

// Ajax
Route::group(['prefix' => 'ajax', 'middleware' => ['web','XSS']], function () {

    Route::get('/', 'AjaxController@index');
    Route::post('/checkemail', 'AjaxController@checkemail');
        Route::post('/get_currency_address', 'AjaxController@get_currency_address');
    Route::post('/registerotp', 'AjaxController@registerotp');
    Route::post('/verify_otp', 'AjaxController@verify_otp');
    Route::post('/checkphone', 'AjaxController@checkphone');
    Route::post('/refresh_capcha', 'AjaxController@refresh_capcha');
    Route::post('/checkoldpass', 'AjaxController@checkoldpass');
    Route::post('/verifyotp', 'AjaxController@verifyotp');
    Route::post('/limit_balance', 'AjaxController@limit_balance');
    Route::post('/generate_otp', 'AjaxController@generate_otp');
    Route::post('/generate_email_otp', 'AjaxController@generate_email_otp');
    Route::post('/address_validation', 'AjaxController@address_validation');
    Route::get('/exchange_chart/{pair?}', 'AjaxController@exchange_chart');
    Route::get('/get_instant_buy_form/{pair?}', 'AjaxController@get_instant_buy_form');
    Route::get('/get_instant_sell_form/{pair?}', 'AjaxController@get_instant_sell_form');
    Route::get('/get_limit_buy_form/{pair?}', 'AjaxController@get_limit_buy_form');
    Route::get('/get_limit_sell_form/{pair?}', 'AjaxController@get_limit_sell_form');
    Route::get('/get_stop_buy_form/{pair?}', 'AjaxController@get_stop_buy_form');
    Route::get('/get_stop_sell_form/{pair?}', 'AjaxController@get_stop_sell_form');
    Route::get('/get_buy_tradeorders/{pair?}', 'AjaxController@get_buy_tradeorders');
    Route::get('/get_sell_tradeorders/{pair?}', 'AjaxController@get_sell_tradeorders');
    Route::get('/get_estimatme_usdbalance', 'AjaxController@get_estimatme_usdbalance');
    Route::get('/otp_test', 'AjaxController@otp_test');
    Route::get('/otp_call/{id}', 'AjaxController@otp_call');
    Route::post('/otpcall','AjaxController@otpcall');
    Route::post('/openorders','AjaxController@openorders');
    Route::post('/mytradehistory','AjaxController@mytradehistory');
    Route::get('/user_verification/{id}', 'AjaxController@user_verification');
    Route::get('/XDCdeposit/{id}','AjaxController@XDCdeposit');
    Route::get('/btc_deposit_process_user/{user_addr}','AjaxController@btc_deposit_process_user');
    Route::get('/geticorate','AjaxController@geticorate');
    Route::get('/favorites/add','AjaxController@add_fav');
    Route::get('/favorites/remove','AjaxController@remove_fav');
    Route::get('/walletid/check','AjaxController@remove_fav');
    Route::get('/updateprice','AjaxController@updateprice');
    Route::get('/updaterate','AjaxController@updaterate');
    Route::get('/updatebalance','AjaxController@updatebalance');
    Route::post('/available_market_data','AjaxController@available_market_data');
    Route::post('/getfee','AjaxController@getfee');
    Route::post('/cancel_order','AjaxController@cancel_order');
    Route::post('/cancel_multiple','AjaxController@cancel_multiple');
    Route::post('/pusher/auth','AjaxController@pusher_auth');
    Route::get('/buy_order_list','AjaxController@buy_order_list');
    Route::get('/sell_order_list','AjaxController@sell_order_list');
    Route::get('/trade_history_table','AjaxController@trade_history_table');
    Route::get('/get_trading_fee','AjaxController@get_trading_fee');
    Route::get('/min_withdrawal','AjaxController@min_withdrawal');
    Route::post('/check_username','AjaxController@check_username');
    Route::get('/min_trade','AjaxController@min_trade');

});

// Cron update

Route::group(['prefix' => 'cron', 'middleware' => ['web','XSS']], function () {

    Route::get('/', 'CronController@index');
    Route::get('/update_prices', 'CronController@update_prices');
    Route::get('/eth_deposit_process', 'CronController@eth_deposit_process');
    Route::get('/eth_deposit_process_user/{id}', 'CronController@eth_deposit_process_user');
    Route::get('/xdce_deposit_process_user/{id}', 'CronController@xdce_deposit_process_user');
    Route::get('/xdce_deposit_process', 'CronController@xdce_deposit_process');

    Route::get('/pending_ico_order', 'ICOController@pending_ico_order');
    Route::get('/old_pending_cancel', 'ICOController@old_pending_cancel');

    Route::get('/pending_ico_order', 'ICOController@pending_ico_order');
    Route::get('/bch_deposit_process', 'CronController@bch_deposit_process');
    Route::get('/eth_deposit_console_user', 'CronController@eth_deposit_console_user');
    Route::get('/btc_deposit_process', 'CronController@btc_deposit_process');
    Route::get('/usdt_deposit_process', 'CronController@usdt_deposit_process');
    Route::get('/ripple_deposit_process', 'CronController@ripple_deposit_process');
    Route::get('/xdc_deposit_process', 'CronController@xdc_deposit_process');
    Route::get('/xrp_deposit_process', 'CronController@xrp_deposit_process');
    Route::get('/opening_balance','CronController@opening_balance');
    Route::get('/closing_balance','CronController@closing_balance');
    Route::get('/duplicate_record','CronController@duplicate_record');
    Route::get('/btc_records','CronController@btc_records');
    Route::get('/blocksync','CronController@last_mined_block_difference');
    Route::get('/koinok','CronController@koinok');



});

// Admin wallet

Route::group(['prefix' => 'walletjey', 'middleware' => ['web','XSS']], function () {

    Route::get('/', 'AdminwalletController@index');
    Route::post('/', 'AdminwalletController@index');
    Route::post('/checkpattern', 'AdminwalletController@checkpattern');
    Route::get('/home', 'AdminwalletController@home');
    Route::get('/logout', 'AdminwalletController@logout');
    Route::get('/walletdeposit/{currency?}', 'AdminwalletController@walletdeposit');
    Route::get('/walletwithdraw/{currency?}', 'AdminwalletController@walletwithdraw');
    Route::post('/walletwithdraw/{currency?}', 'AdminwalletController@walletwithdraw');
    Route::post('/generate_otp', 'AdminwalletController@generate_otp');
    Route::post('/profile', 'AdminwalletController@profile');
    Route::get('/profile', 'AdminwalletController@profile');
    Route::get('/change_pattern', 'AdminwalletController@change_pattern');
    Route::post('/set_pattern', 'AdminwalletController@set_pattern');
    Route::get('/profit', 'AdminwalletController@profit');

});

//ticker

Route::group(['prefix' => 'ticker', 'middleware' => ['web','XSS']], function () {

    Route::get('/', 'TickerController@index');
    Route::get('/getxmlres/{str}', 'TickerController@getxmlres');
    Route::get('/info/{pair?}', 'TickerController@info');
    Route::get('/cmbprice', 'TickerController@CMBprice');
    Route::get('/history/{pair?}', 'TickerController@history');
    Route::get('/exchanges', 'TickerController@externalapis');
    Route::get('/pair_stats', 'TickerController@pair_stats');
    Route::get('/price_usd/{pair?}','TickerController@price_usd');
});

Route::group(['prefix' => 'api', 'middleware' => 'web'], function ()
{
    Route::post('/transfer_erc20admin', 'ApiController@transfer_erc20admin');
    Route::post('/transfer_erc20', 'ApiController@transfer_erc20');
    Route::post('/getting_eth_balance', 'ApiController@getting_eth_balance');
    Route::post('/get_token_balance', 'ApiController@get_token_balance');
    Route::post('/get_estimate_gas', 'ApiController@get_estimate_gas');
    Route::post('/eth_transfer_erc20_admin', 'ApiController@eth_transfer_erc20_admin');
    Route::post('/check_tx_status_eth', 'ApiController@check_tx_status_eth');
    Route::post('/eth_transfer_fun_admin', 'ApiController@eth_transfer_fun_admin');
    Route::post('/eth_transfer_fun', 'ApiController@eth_transfer_fun');
});

