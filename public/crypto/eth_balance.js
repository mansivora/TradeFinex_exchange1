var wallet_ip    = process.argv[2];
var wallet_port  = process.argv[3];
var from_address = process.argv[4];

var Web3         = require('web3');
var web3         = new Web3(new Web3.providers.HttpProvider("http://"+wallet_ip+":"+wallet_port));
 web3.eth.getBalance(from_address,function(err, balance)
{
    balance = balance.toNumber();
    console.log(balance);
});

