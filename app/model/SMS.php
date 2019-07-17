<?php 
 
// Get the PHP helper library from twilio.com/docs/php/install 
require_once '/path/to/vendor/autoload.php'; // Loads the library 
 
use Twilio\Rest\Client; 
 
$account_sid = 'AC028ee715a074329472a0a4e14a0eb7f0'; 
$auth_token = '10b7ff5354a44b7c932f691cb59259ef'; 
$client = new Client($account_sid, $auth_token); 
 
$messages = $client->accounts("AC028ee715a074329472a0a4e14a0eb7f0") 
  ->messages->create("+919629542995", array( 
        'From' => "+12565988408",  
        'Body' => "First message",      
  ));