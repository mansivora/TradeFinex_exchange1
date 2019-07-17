const RippleAPI = require('ripple-lib').RippleAPI;


const Ripple = new RippleAPI({
//server: 'wss://s1.ripple.com', // Public rippled server
// server: 'wss://s.altnet.rippletest.net:51233', // Public rippled server
    server: 'wss://s2.ripple.com', // Public rippled server
});

Ripple.on('error', function (errorCode, errorMessage) {
//console.log(errorCode + ': ' + errorMessage);
    resp.json('{"status":0,"msg":"Unable to withdraw, problem occured. ' + errorMessage + '."}');
});

Ripple.on('connected', function () {
//console.log('connected');process.exit(-1);
});
Ripple.on('disconnected', function (code) {
// code - close code sent by the server
// will be 1000 if this was normal closure
    console.log('disconnected, code:', code);
});

var version = process.argv[2];


Ripple.connect().then(function () {
// console.log('ripple connected');
    return Ripple.getServerInfo();
// insert code here //
}).then(function (server_info) {


    Ripple.getTransactions('rhfzdZgZPTSqGVW41cwdfG4uudEhMwnd22', {"minLedgerVersion": parseInt(version)}).then(function (transaction) {
        console.log(JSON.stringify(transaction));
        process.exit(-1);
    }).catch(console.error);


    /*Ripple.getBalances('rnYH9rbpyCz94PaKAaxp47XQec2mdCaFns').then(function (transaction) {
    console.log(JSON.stringify(transaction));process.exit(-1);
    }).catch(console.error);*/

}).catch(console.error);