var wallet_ip    = process.argv[2];
var wallet_port  = process.argv[3];
var balance;

var Web3         = require('web3');
var web3         = new Web3("http://"+wallet_ip+":"+wallet_port);
web3.eth.isSyncing(function(err, balance)
{
    console.log(JSON.stringify(balance));
});

