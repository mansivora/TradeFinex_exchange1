var wallet_ip    = process.argv[2];
var wallet_port  = process.argv[3];
var from_address = process.argv[4];
var to_address   = process.argv[5];
var amount= process.argv[6];
var pass= process.argv[7];

// var gaslimit     = 210000;

var Web3         = require('web3');
var web3         = new Web3(new Web3.providers.HttpProvider("http://"+wallet_ip+":"+wallet_port));

var send_amount  = web3.toWei(amount);


var est_main_gas = { from : from_address, to : to_address, value : send_amount };

const ethTx = require('ethereumjs-tx');

var a= web3.eth.getBalance(from_address);

web3.eth.getBalance(from_address,function(err, balance)
{

    if(balance > 0)
    {
	
        web3.eth.estimateGas(est_main_gas,function(gaslimit_err, gaslimit)
        {
            console.log(gaslimit);
            web3.eth.getGasPrice(function(gas_err, getGasPrice)
            {
                console.log(getGasPrice);

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
                web3.personal.unlockAccount(from_address, pass, 60000, function(lock_err, res)
                {

                    // console.log(lock_err);
                    if(lock_err)
                        console.log(JSON.stringify({'error2':lock_err}));


                    var transactionCount = web3.eth.getTransactionCount(from_address);

                    var trans_det = { nonce : transactionCount, from : from_address, to : to_address, value : send_amount, gas : gaslimit, gasPrice : getGasPrice };
                    //var comm_trans_det = { from : from_address, to : admin_address, value : fee_amount, gas : gaslimit, gasPrice : getGasPrice };

                    console.log(JSON.stringify(trans_det));
                    //console.log(JSON.stringify(comm_trans_det));

                    web3.eth.sendTransaction(trans_det,function(trans_err,txid)
                    {
                        if(trans_err)
                            console.log(JSON.stringify({'error':trans_err}));

                        if(txid && txid != "")
                            console.log(JSON.stringify({'tx':txid,'hash':txid}));

                    });



                });
            });
        });
    }
});


