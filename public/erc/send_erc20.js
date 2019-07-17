var wallet_ip    = process.argv[2];
var wallet_port  = process.argv[3];
var from_address = process.argv[4];
var to_address   = process.argv[5];
var sendamount= process.argv[6];
var coinAddress=process.argv[7];
var pass= process.argv[8];
var tokendecimal=process.argv[9];

const Web3 = require('web3');

if (typeof web3 !== 'undefined') {
    web3 = new Web3(web3.currentProvider);
} else {
    web3 = new Web3(new Web3.providers.HttpProvider("http://"+wallet_ip+":"+wallet_port));

    console.log("Connected")
}

if(tokendecimal == 18 || tokendecimal == null)
{
    var send_amount  = web3.toWei(sendamount);
}
else
{
    var send_amount = sendamount*(Math.pow(10,tokendecimal));
}

var est_main_gas = { from : from_address, to : to_address, value : send_amount };

// var coinAddress = coinAddress;        //contract adress
var coinabi = [{"constant":true,"inputs":[],"name":"name","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_spender","type":"address"},{"name":"_value","type":"uint256"}],"name":"approve","outputs":[{"name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"totalSupply","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_from","type":"address"},{"name":"_to","type":"address"},{"name":"_value","type":"uint256"}],"name":"transferFrom","outputs":[{"name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"_owner","type":"address"}],"name":"balanceOf","outputs":[{"name":"balance","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"owner","outputs":[{"name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"symbol","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_to","type":"address"},{"name":"_value","type":"uint256"}],"name":"transfer","outputs":[{"name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"_owner","type":"address"},{"name":"_spender","type":"address"}],"name":"allowance","outputs":[{"name":"remaining","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_newOwner","type":"address"}],"name":"transferOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"inputs":[],"payable":false,"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":true,"name":"from","type":"address"},{"indexed":true,"name":"to","type":"address"},{"indexed":false,"name":"value","type":"uint256"}],"name":"Transfer","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"owner","type":"address"},{"indexed":true,"name":"spender","type":"address"},{"indexed":false,"name":"value","type":"uint256"}],"name":"Approval","type":"event"}];

var coin = web3.eth.contract(coinabi).at(coinAddress);
var bal = coin.balanceOf(from_address);
bal = web3.fromWei(bal, "wei" ).toString(10);
if(bal >= send_amount)
{
    send_amount = bal;
}

var txdata=web3.eth.contract(coinabi).at(coinAddress).transfer.getData(to_address,send_amount); //to adress
const ethTx = require('ethereumjs-tx');

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
            transactionCount=web3.eth.getTransactionCount(from_address);
            const txParams = {
                nonce: transactionCount, // Replace by nonce for your account on geth node
                gasPrice: getGasPrice,
                gas: '200000',
                to: coinAddress,       //contract adress
                from:from_address,      //coin base
                data:txdata
            };
            if(lock_err)
                console.log(JSON.stringify({'status':0,'error2':lock_err}));

            web3.eth.sendTransaction(txParams, function(err, hash)
            {
                //coinbase
                if(err)
                    console.log(err);
                console.log(JSON.stringify({'status':0,'error':err}));

                if(hash && hash != "")
                    console.log(JSON.stringify({'status':1,'tx':hash,'hash':hash}));

                web3.personal.lockAccount(from_address)

            });

        });
    });
});

// web3.eth.getGasPrice(function(gas_err, getGasPrice) {
//     if (gas_err) {
//         console.log(JSON.stringify({'error1': gas_err}));
//
//         getGasPrice = 4000000000;
//     }
//     else {
//         if (getGasPrice < 4000000000 || getGasPrice == null) {
//             getGasPrice = 4000000000;
//         }
//     }
//     web3.personal.unlockAccount(from_address, pass, 60000, function (lock_err, res) {
//         coin.transfer.sendTransaction(to_address, send_amount, {
//             from: from_address,
//             gas: '250000',
//             gasPrice: getGasPrice
//         }, function(err,hash)
//         {
//             if(err)
//                     console.log(err);
//                 console.log(JSON.stringify({'status':0,'error':err}));
//
//                 if(hash && hash != "")
//                     console.log(JSON.stringify({'status':1,'tx':hash,'hash':hash}));
//         });
//     });
//     web3.personal.lockAccount(from_address);
// });
