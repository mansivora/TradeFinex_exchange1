var wallet_ip    = process.argv[2];
var wallet_port  = process.argv[3];
var hash = process.argv[4];

const Web3 = require('web3');

if (typeof web3 !== 'undefined') {
    web3 = new Web3(web3.currentProvider);
} else {
    web3 = new Web3(new Web3.providers.HttpProvider("http://"+wallet_ip+":"+wallet_port));
}

web3.eth.getTransactionReceipt(hash,function(err, result)
{
    if(result) {
        status = result.status;
        console.log(status);
    }
    else
    {
        console.log("0x0");
    }
});
