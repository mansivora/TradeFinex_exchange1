var wallet_ip    = process.argv[2];
var wallet_port  = process.argv[3];
var from_address = process.argv[4];
var to_address   = process.argv[5];
var sendamount= process.argv[6];

const Web3 = require('web3');

if (typeof web3 !== 'undefined') {
    web3 = new Web3(web3.currentProvider);
} else {
    web3 = new Web3(new Web3.providers.HttpProvider("http://"+wallet_ip+":"+wallet_port));
}

var send_amount  = web3.toWei(sendamount);

var est_main_gas = { from : from_address, to : to_address, value : send_amount };

web3.eth.estimateGas(est_main_gas,function(gaslimit_err, gaslimit)
{
    web3.eth.getGasPrice(function(gas_err, getGasPrice)
    {
        if(gas_err)
        {
            console.log(JSON.stringify({'error1':gas_err}));
            getGasPrice = 4000000000;
        }
        else
        {
            if(getGasPrice < 4000000000 || getGasPrice == null)
            {
                getGasPrice = 4000000000;
            }
        }

        var gas = getGasPrice * 200000;

        console.log(gas);
    });
});
