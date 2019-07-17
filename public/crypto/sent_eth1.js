var wallet_ip    = process.argv[2];
var wallet_port  = process.argv[3];
var from_address = process.argv[4];
var to_address   = process.argv[5];
var amount= process.argv[6];
var pass= process.argv[7];

//var gaslimit     = 21000;

var Web3         = require('web3');
var web3         = new Web3("http://"+wallet_ip+":"+wallet_port);
var send_amount  = web3.utils.toWei(amount);

var fs = require('fs')
    , Log = require('log')
    , log = new Log('info', fs.createWriteStream('Eth_error.log'));
log.debug('preparing email');
log.info('sending email');
log.error('failed to send email');

console.log("testing");
var est_main_gas = { from : from_address, to : to_address, value : send_amount };

web3.eth.getBalance(from_address,function(err, balance)
{
    //if(balance >= send_amount)
    //{
    web3.eth.estimateGas(est_main_gas,function(gaslimit_err, gaslimit)
    {
        web3.eth.getGasPrice(function(gas_err, getGasPrice)
        {
            if(gas_err)
                log.error(gas_err);

            web3.eth.personal.unlockAccount(from_address, pass, 60000, function(lock_err, res)
            {
                if(lock_err)
                    log.error(lock_err);

                send_amount = send_amount - (getGasPrice * gaslimit);
                var trans_det = { from : from_address, to : to_address, value : send_amount, gas : gaslimit, gasPrice : getGasPrice };
                //var comm_trans_det = { from : from_address, to : admin_address, value : fee_amount, gas : gaslimit, gasPrice : getGasPrice };

                //console.log(JSON.stringify(trans_det));
                //console.log(JSON.stringify(comm_trans_det));

                web3.eth.sendTransaction(trans_det,function(trans_err,txid)
                {
                    if(trans_err)
                        log.error(trans_err);

                    if(txid && txid != "")
                        log.info(JSON.stringify({'status':1,'tx':txid,'hash':txid}));

                });



            });
        });
    });
    //}
});


