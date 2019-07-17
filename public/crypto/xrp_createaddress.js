
const RippleAPI = require('ripple-lib').RippleAPI;


const Ripple = new RippleAPI({
server: 'wss://s2.ripple.com', // Public rippled server
authorization: 'cmbxrp@cmbdex.com:PLMP$#2018BTC'
});


Ripple.on('error', function (errorCode, errorMessage) {
console.log(errorCode + ': ' + errorMessage);
resp.json('{"status":0,"msg":"Unable to withdraw, problem occured. '+errorMessage+'."}');
});

Ripple.on('connected', function () {
//console.log('connected');process.exit(-1);
});
Ripple.on('disconnected', function (code) {
// code - close code sent by the server
// will be 1000 if this was normal closure
console.log('disconnected, code:', code);
});
Ripple.connect().then(function () {
// console.log('ripple connected');
return Ripple.getServerInfo();
// insert code here //
}).then(function (server_info) {

var rippleAddress = Ripple.generateAddress();

console.log(JSON.stringify(rippleAddress));process.exit(-1);

}).catch(console.error);