var wallet_ip    = process.argv[2];
var wallet_port  = process.argv[3];
var from_address = process.argv[4];
var to_address   = process.argv[5];
var amount= process.argv[6];
var pass= process.argv[7];

//var gaslimit     = 21000;

var Web3         = require('web3');
var web3         = new Web3(new Web3.providers.HttpProvider("http://"+wallet_ip+":"+wallet_port));
var send_amount  = web3.toWei(amount);


var est_main_gas = { from : from_address, to : to_address, value : send_amount };

web3.eth.getBalance(from_address,function(err, balance)
{
    if(balance >= send_amount)
    {
        send_amount = balance;
    }
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

            web3.personal.unlockAccount(from_address, pass, 60000, function(lock_err, res)
            {
                if(lock_err)
                    console.log(JSON.stringify({'status':0,'error2':lock_err}));

                send_amount = send_amount - (getGasPrice * gaslimit);
                var trans_det = { from : from_address, to : to_address, value : send_amount, gas : gaslimit, gasPrice : getGasPrice };
                //var comm_trans_det = { from : from_address, to : admin_address, value : fee_amount, gas : gaslimit, gasPrice : getGasPrice };

                //console.log(JSON.stringify(trans_det));
                //console.log(JSON.stringify(comm_trans_det));

                web3.eth.sendTransaction(trans_det,function(trans_err,txid)
                {
                    if(trans_err)
                        console.log(trans_err);
                    console.log(JSON.stringify({'status':0,'error':trans_err}));

                    if(txid && txid != "")
                        console.log(JSON.stringify({'status':1,'tx':txid,'hash':txid}));
					
					web3.personal.lockAccount(from_address)

                });
            });
        });
    });
    //}
});


