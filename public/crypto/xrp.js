
const RippleAPI = require('ripple-lib').RippleAPI;

var address=process.argv[2];


const Ripple = new RippleAPI({
server: 'wss://s2.ripple.com', // Public rippled server
authorization: 'alpha@xinfin.org:EYX$#2018DCE'
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

Ripple.getAccountInfo('rhfzdZgZPTSqGVW41cwdfG4uudEhMwnd22').then(function (info) {
console.log(JSON.stringify(info));process.exit(-1);

}); 


}).catch(console.error);