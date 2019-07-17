@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">User Trade Transaction Details</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            {{--<li class="active">Verification Status  &nbsp;</li>--}}
            {{--@if($flag == 1)--}}
            {{--<li><i class="fa fa-angle-right"></i>&nbsp;&nbsp;<i class="fa fa-circle" style="color: green"--}}
            {{--aria-hidden="true"></i> &nbsp;</li>--}}
            {{--@else--}}
            {{--<li><i class="fa fa-angle-right"></i>&nbsp;&nbsp;<i class="fa fa-circle" style="color: red"--}}
            {{--aria-hidden="true"></i> &nbsp;</li>--}}
            {{--@endif--}}
        </ol>
        <div class="clearfix"></div>
    </div>

    <div style="float: right"><a href="{{url('check_admin/user_transaction_details?&type=PDF&user_id='.$id)}}"
                                 target="_blank" style="cursor:pointer;">Download</a></div>
    <div class="page-content">
        <div class="row mbl">
            {{--user details--}}

            {{--User Profile--}}
            <div class="col-sm-6 col-md-4">
                <div class="panel db mbm" style="cursor: pointer;">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div style="text-align: center"><i class="fa fa-user fa-3"></i></div>
                                <div style="text-align: center"><p>
                                    <h2>{{get_user_details($id,'enjoyer_name')}}</h2></p>
                                    <p class="description "><strong>{{ get_usermail($id) }}</strong></p></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label><strong>BTC:</strong></label>
                            </div>
                            <div class="col-md-8">
                                <label>{{get_userbalance($id,'BTC')}}</label>
                            </div>

                            <div class="col-md-4">
                                <label><strong>ETH:</strong></label>
                            </div>
                            <div class="col-md-8">
                                <label>{{get_userbalance($id,'ETH')}}</label>
                            </div>


                            {{--<div class="col-md-4">--}}
                            {{--<label><strong>XRP:</strong></label>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-8">--}}
                            {{--<label>{{get_userbalance($id,'XRP')}}</label>--}}
                            {{--</div>--}}

                            <div class="col-md-4">
                                <label><strong>USDT:</strong></label>
                            </div>
                            <div class="col-md-8">
                                <label>{{get_userbalance($id,'USDT')}}</label>
                            </div>

                            <div class="col-md-4">
                                <label><strong>XRP:</strong></label>
                            </div>
                            <div class="col-md-8">
                                <label>{{get_userbalance($id,'XRP')}}</label>
                            </div>

                            {{--<div class="col-md-4">--}}
                            {{--<label><strong>BCH:</strong></label>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-8">--}}
                            {{--<label>{{get_userbalance($id,'BCH')}}</label>--}}
                            {{--</div>--}}


                        </div>


                    </div>
                </div>
            </div>

            {{--Explorer Deposit--}}
            <div class="col-sm-6 col-md-4">
                <div class="panel db mbm" style="cursor: pointer;">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div style="text-align: center"><i class="fa fa-exchange fa-6x"></i></div>
                                <div style="text-align: center"><p>
                                    <h2>Explorer Deposit</h2></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label><strong>BTC:</strong></label>
                            </div>
                            <div class="col-md-8">
                                <label>{{$explorer['BTC']}}</label>
                            </div>

                            <div class="col-md-4">
                                <label><strong>ETH:</strong></label>
                            </div>
                            <div class="col-md-8">
                                <label>{{$explorer['ETH']}}</label>
                            </div>


                            {{--<div class="col-md-4">--}}
                            {{--<label><strong>XRP:</strong></label>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-8">--}}
                            {{--<label>{{$explorer['XRP']}}</label>--}}
                            {{--</div>--}}

                            <div class="col-md-4">
                                <label><strong>USDT:</strong></label>
                            </div>
                            <div class="col-md-8">
                                <label>{{$explorer['USDT']}}</label>
                            </div>

                            <div class="col-md-4">
                                <label><strong>XRP:</strong></label>
                            </div>
                            <div class="col-md-8">
                                <label>{{$explorer['XRP']}}</label>
                            </div>

                            {{--<div class="col-md-4">--}}
                            {{--<label><strong>ICO XRP:</strong></label>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-8">--}}
                            {{--<label>{{$ico['XRP']}}</label>--}}
                            {{--</div>--}}

                            {{--<div class="col-md-4">--}}
                            {{--<label><strong>BCH:</strong></label>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-8">--}}
                            {{--<label>{{$explorer['BCH']}}</label>--}}
                            {{--</div>--}}


                        </div>


                    </div>
                </div>
            </div>

            {{--User Profile--}}
            <div class="col-sm-6 col-md-4">
                <div class="panel db mbm" style="cursor: pointer;">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div style="text-align: center"><i class="fa fa-exchange fa-6x"></i></div>
                                <div style="text-align: center"><p>
                                    <h2>Withdrawals</h2></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-4">
                                <label><strong>BTC:</strong></label>
                            </div>

                            <div class="col-md-8">
                                <label>{{$withdraw['BTC']}}</label>
                            </div>


                            <div class="col-md-4">
                                <label><strong>ETH:</strong></label>
                            </div>

                            <div class="col-md-8">
                                <label>{{$withdraw['ETH']}}</label>
                            </div>


                            {{--<div class="col-md-4">--}}
                            {{--<label><strong>XRP:</strong></label>--}}
                            {{--</div>--}}

                            {{--<div class="col-md-8">--}}
                            {{--<label>{{$withdraw['XRP']}}</label>--}}
                            {{--</div>--}}

                            <div class="col-md-4">
                                <label><strong>USDT:</strong></label>
                            </div>

                            <div class="col-md-8">
                                <label>{{$withdraw['USDT']}}</label>
                            </div>

                            <div class="col-md-4">
                                <label><strong>XRP:</strong></label>
                            </div>

                            <div class="col-md-8">
                                <label>{{$withdraw['XRP']}}</label>
                            </div>

                            {{--<div class="col-md-4">--}}
                            {{--<label><strong>BCH:</strong></label>--}}
                            {{--</div>--}}

                            {{--<div class="col-md-8">--}}
                            {{--<label>{{$withdraw['BCH']}}</label>--}}
                            {{--</div>--}}

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h2>Total Trade Supply:</h2>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Buyed USDT:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Buy_total['USDT']}}</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Sell USDT:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Sell_total['USDT']}}</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Intrade USDT:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Intrade_total['USDT']}}</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Buyed XRP:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Buy_total['XRP']}}</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Sell XRP:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Sell_total['XRP']}}</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Intrade XRP:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Intrade_total['XRP']}}</label>
                    </div>
                </div>

                {{--<div class="col-md-4">--}}
                {{--<div class="col-md-6">--}}
                {{--<label><strong>Buyed XRP:</strong></label>--}}
                {{--</div>--}}

                {{--<div class="col-md-6">--}}
                {{--<label>{{$Buy_total['XRP']}}</label>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-4">--}}
                {{--<div class="col-md-6">--}}
                {{--<label><strong>Sell XRP:</strong></label>--}}
                {{--</div>--}}

                {{--<div class="col-md-6">--}}
                {{--<label>{{$Sell_total['XRP']}}</label>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-4">--}}
                {{--<div class="col-md-6">--}}
                {{--<label><strong>Intrade XRP:</strong></label>--}}
                {{--</div>--}}

                {{--<div class="col-md-6">--}}
                {{--<label>{{$Intrade_total['XRP']}}</label>--}}
                {{--</div>--}}
                {{--</div>--}}

                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Buyed ETH:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Buy_total['ETH']}}</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Sell ETH:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Sell_total['ETH']}}</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Intrade ETH:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Intrade_total['ETH']}}</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Buyed BTC:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Buy_total['BTC']}}</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Sell BTC:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Sell_total['BTC']}}</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Intrade BTC:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Intrade_total['BTC']}}</label>
                    </div>
                </div>

                {{--<div class="col-md-4">--}}
                {{--<div class="col-md-6">--}}
                {{--<label><strong>Buyed BCH:</strong></label>--}}
                {{--</div>--}}

                {{--<div class="col-md-6">--}}
                {{--<label>{{$Buy_total['BCH']}}</label>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-4">--}}
                {{--<div class="col-md-6">--}}
                {{--<label><strong>Sell BCH:</strong></label>--}}
                {{--</div>--}}
                {{--<div class="col-md-6">--}}
                {{--<label>{{$Sell_total['BCH']}}</label>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-4">--}}
                {{--<div class="col-md-6">--}}
                {{--<label><strong>Intrade BCH:</strong></label>--}}
                {{--</div>--}}

                {{--<div class="col-md-6">--}}
                {{--<label>{{$Intrade_total['BCH']}}</label>--}}
                {{--</div>--}}
                {{--</div>--}}

            </div>
        </div>

        <form class="form-horizontal">
            <h3>User Addresses: </h3>
            <div class="col-md-12">
                <div class="form-group"><label for="inputLastName"
                                               class="col-md-2 control-label"><strong>BTC:</strong></label>

                    <div class="col-md-10"><p class="form-control-static">
                            {{$addresses['BTC']}}<br>
                            {{--<a href="{{url('ajax/btc_deposit_process_user/'.$user->BTC_addr)}}" style="color: red;">--}}
                            {{--Click here--}}
                            {{--</a>to manually deposit <strong>BTC</strong>.--}}
                        </p></div>
                </div>
            </div>

            {{--<div class="col-md-12">--}}
            {{--<div class="form-group"><label for="inputLastName"--}}
            {{--class="col-md-2 control-label"><strong>BCH:</strong></label>--}}

            {{--<div class="col-md-10"><p class="form-control-static">--}}
            {{--{{$addresses['BCH']}}--}}
            {{--</p></div>--}}
            {{--</div>--}}
            {{--</div>--}}


            <div class="col-md-12">
                <div class="form-group"><label for="inputLastName"
                                               class="col-md-2 control-label"><strong>ETH:</strong></label>

                    <div class="col-md-10"><p class="form-control-static">
                            {{$addresses['ETH']}}<br>
                            <a href="{{url('cron/eth_deposit_process_user/'.$id)}}" style="color: red;">
                                Click here
                            </a>to manually deposit <strong>ETH</strong>.
                        </p></div>
                </div>
            </div>

            {{--<div class="col-md-12">--}}
            {{--<div class="form-group"><label for="inputLastName"--}}
            {{--class="col-md-2 control-label"><strong>XRP--}}
            {{--:</strong></label>--}}

            {{--<div class="col-md-10"><p class="form-control-static">--}}
            {{--{{$user->XRP_addr}}--}}
            {{--</p></div>--}}
            {{--</div>--}}
            {{--</div>--}}

            {{--<div class="col-md-12">--}}
            {{--<div class="form-group"><label for="inputLastName"--}}
            {{--class="col-md-2 control-label"><strong>XRP Destination Tag--}}
            {{--:</strong></label>--}}

            {{--<div class="col-md-10"><p class="form-control-static">--}}
            {{--{{$addresses['XRP']}}--}}
            {{--</p></div>--}}
            {{--</div>--}}
            {{--</div>--}}

            <div class="col-md-12">
                <div class="form-group"><label for="inputLastName"
                                               class="col-md-2 control-label"><strong>USDT
                            :</strong></label>

                    <div class="col-md-10"><p class="form-control-static">
                            {{$addresses['USDT']}}<br>
                            <a href="{{url('ajax/XDCdeposit/'.$id)}}" style="color: red;">
                                Click here
                            </a>to manually deposit <strong>USDT</strong>.
                        </p></div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group"><label for="inputLastName"
                                               class="col-md-2 control-label"><strong>XRP
                            :</strong></label>

                    <div class="col-md-10"><p class="form-control-static">
                            {{$addresses['XRP']}}<br>
                            {{--<a href="{{url('cron/xdce_deposit_process_user/'.$addresses['XRP'])}}" style="color: red;">--}}
                                {{--Click here--}}
                            {{--</a>to manually deposit <strong>XRP</strong>.--}}
                        </p></div>
                </div>
            </div>
        </form>


        {{--user buy Trade of that particular currency--}}
        <div class="table-container">
            <div>
                <h2>&nbsp;&nbsp;Buy Trades:</h2>
                <br>
            </div>
            <table class="table table-hover table-striped table-advanced tablesorter"
                   id="myBuyTrade">
                <thead>
                <tr>
                    <th>Amount</th>
                    <th>Price</th>
                    <th>Fee</th>
                    <th>Total</th>
                    <th>Type</th>
                    <th>First Currency</th>
                    <th>Second Currency</th>
                    <th>Status</th>
                    <th>Updated time</th>
                </tr>
                </thead>

                <tbody>
                @if($buy_trade)
                    @foreach($buy_trade as $key=>$val)
                        <tr>
                            <td>{{$val->original_qty}}</td>
                            <td>{{$val->price}}</td>
                            <td>{{$val->fee}}</td>
                            <td>{{$val->updated_total}}</td>
                            <td>{{$val->type}}</td>
                            <td>{{$val->firstCurrency}}</td>
                            <td>{{$val->secondCurrency}}</td>
                            <td>{{$val->status}}</td>
                            <td>{{$val->updated_at}}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="0" style="text-align:right">Amount:</th>
                    <th colspan="3" style="text-align:right">Total:</th>
                    <th></th>

                </tr>
                </tfoot>

            </table>

        </div>

        {{--user buy Trade of that particular currency--}}
        <div class="table-container">
            <div>
                <h2>&nbsp;&nbsp;Sell Trades:</h2>
                <br>
            </div>
            <table class="table table-hover table-striped  table-advanced tablesorter"
                   id="mySellTrade">
                <thead>
                <tr>
                    <th>Amount</th>
                    <th>Price</th>
                    <th>Fee</th>
                    <th>Total</th>
                    <th>Type</th>
                    <th>First Currency</th>
                    <th>Second Currency</th>
                    <th>Status</th>
                    <th>Updated time</th>


                </tr>
                <tbody>
                @if($sell_trade)
                    @foreach($sell_trade as $key=>$val)
                        <tr>
                            <td>{{$val->original_qty}}</td>
                            <td>{{$val->price}}</td>
                            <td>{{$val->fee}}</td>
                            <td>{{$val->updated_total}}</td>
                            <td>{{$val->type}}</td>
                            <td>{{$val->firstCurrency}}</td>
                            <td>{{$val->secondCurrency}}</td>
                            <td>{{$val->status}}</td>
                            <td>{{$val->updated_at}}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
                </thead>
                <tfoot>
                <tr>
                    <th colspan="0" style="text-align:right">Amount:</th>
                    <th colspan="3" style="text-align:right">Total:</th>
                    <th></th>

                </tr>
                </tfoot>

            </table>

        </div>

        {{--user penidng trades--}}
        <div class="table-container">
            <div>
                <h2>&nbsp; Pending Trades:</h2>
                <br>
            </div>
            <table class="table table-hover table-striped  table-advanced tablesorter"
                   id="myPendingTrade">
                <thead>
                <tr>
                    <th>Amount</th>
                    <th>Price</th>
                    <th>Fee</th>
                    <th>Total</th>
                    <th>Type</th>
                    <th>First Currency</th>
                    <th>Second Currency</th>
                    <th>Status</th>
                    <th>Updated time</th>


                </tr>
                <tbody>
                @if($pending_trade)
                    @foreach($pending_trade as $key=>$val)
                        <tr>
                            <td>{{$val->updated_qty}}</td>
                            <td>{{$val->price}}</td>
                            <td>{{$val->fee}}</td>
                            <td>{{$val->total}}</td>
                            <td>{{$val->type}}</td>
                            <td>{{$val->firstCurrency}}</td>
                            <td>{{$val->secondCurrency}}</td>
                            <td>{{$val->status}}</td>
                            <td>{{$val->updated_at}}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
                </thead>
                <tfoot>
                <tr>
                    <th colspan="0" style="text-align:right">Amount:</th>
                    <th colspan="3" style="text-align:right">Total:</th>
                    <th></th>

                </tr>
                </tfoot>

            </table>

        </div>

        {{--ico buy Trade of that particular currency--}}
        {{--<div class="table-container">--}}
        {{--<div>--}}
        {{--<h2>&nbsp;&nbsp;ICO Trades:</h2>--}}
        {{--<br>--}}
        {{--</div>--}}
        {{--<table class="table table-hover table-striped  table-advanced tablesorter"--}}
        {{--id="myICO">--}}
        {{--<thead>--}}
        {{--<tr>--}}
        {{--<th>Amount</th>--}}
        {{--<th>Price</th>--}}
        {{--<th>Total</th>--}}
        {{--<th>Type</th>--}}
        {{--<th>First Currency</th>--}}
        {{--<th>Second Currency</th>--}}
        {{--<th>Status</th>--}}
        {{--<th>Updated time</th>--}}


        {{--</tr>--}}
        {{--<tbody>--}}
        {{--@if($ico_trade)--}}
        {{--@foreach($ico_trade as $key=>$val)--}}
        {{--<tr>--}}
        {{--<td>{{$val->Amount}}</td>--}}
        {{--<td>{{$val->Price}}</td>--}}
        {{--<td>{{$val->Total}}</td>--}}
        {{--<td>{{$val->Type}}</td>--}}
        {{--<td>{{$val->FirstCurrency}}</td>--}}
        {{--<td>{{$val->SecondCurrency}}</td>--}}
        {{--<td>{{$val->Status}}</td>--}}
        {{--<td>{{$val->updated_at}}</td>--}}
        {{--</tr>--}}
        {{--@endforeach--}}
        {{--@endif--}}
        {{--</tbody>--}}
        {{--</thead>--}}
        {{--<tfoot>--}}
        {{--<tr>--}}
        {{--<th colspan="0" style="text-align:right">Amount:</th>--}}
        {{--<th colspan="3" style="text-align:right">Total:</th>--}}
        {{--<th></th>--}}

        {{--</tr>--}}
        {{--</tfoot>--}}

        {{--</table>--}}

        {{--</div>--}}


        {{--user Deposit Trade of that particular currency--}}
        <div class="table-container">
            <div>
                <h2>&nbsp;&nbsp;Deposits:</h2>
                <br>
            </div>
            <table class="table table-hover table-striped  table-advanced tablesorter"
                   id="Deposits">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Transaction ID</th>
                    <th>Type</th>
                    <th>Currency</th>
                    <th>Amount</th>
                    <th>Datetime</th>
                    <th>Status</th>


                </tr>
                <tbody>
                @if($Deposit)
                    @foreach($Deposit as $key=>$val)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$val->transaction_id}}</td>
                            <td>{{$val->type}}</td>
                            <td>{{$val->currency_name}}</td>
                            <td>{{$val->amount}}</td>
                            <td>{{$val->updated_at}}</td>
                            <td>{{$val->status}}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
                </thead>
                <tfoot>
                <tr>
                    <th colspan="5" style="text-align:right"></th>
                    <th></th>

                </tr>
                </tfoot>
            </table>

        </div>

        {{--user Withdrawal Trade of that particular currency--}}
        <div class="table-container">
            <div>
                <h2>&nbsp;&nbsp;Withdrawal:</h2>
                <br>
            </div>
            <table class="table table-hover table-striped  table-advanced tablesorter"
                   id="Withdrawals">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Transaction ID</th>
                    <th>Type</th>
                    <th>Currency</th>
                    <th>Amount</th>
                    <th>Datetime</th>
                    <th>Status</th>


                </tr>
                <tbody>
                @if($Withdrawal)
                    @foreach($Withdrawal as $key=>$val)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$val->transaction_id}}</td>
                            <td>{{$val->type}}</td>
                            <td>{{$val->currency_name}}</td>
                            <td>{{$val->amount}}</td>
                            <td>{{$val->updated_at}}</td>
                            <td>{{$val->status}}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
                </thead>
                <tfoot>
                <tr>
                    <th colspan="5" style="text-align:right"></th>
                    <th></th>

                </tr>
                </tfoot>

            </table>

        </div>


    </div>
@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#myBuyTrade').DataTable({
                "searching": false,
                "paging": false,
                "ordering": false,
                "info": false,

                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api(), data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i * 1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    // Total over all pages
                    total = api
                        .column(3)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                            ;
                        });

                    amount = api.column(0)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        });

                    // Update footer
                    // Update footer
                    $(api.column(0).footer()).html(
                        'Amount: ' + amount
                    );


                    $(api.column(3).footer()).html(
                        'Total: ' + total
                    );
                }
            });

            $('#mySellTrade').DataTable({
                "searching": false,
                "paging": false,
                "ordering": false,
                "info": false,
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api(), data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i * 1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    // Total over all pages
                    total = api
                        .column(3)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                            ;
                        });

                    amount = api.column(0)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        });

                    // Update footer
                    // Update footer
                    $(api.column(0).footer()).html(
                        'Amount: ' + amount
                    );


                    $(api.column(3).footer()).html(
                        'Total: ' + total
                    );
                }
            });

            $('#Deposits').DataTable({
                "searching": false,
                "paging": false,
                "ordering": false,
                "info": false,
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api(), data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i * 1 :
                            typeof i === 'number' ?
                                i : 0;
                    };


                    amount = api.column(4)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        });

                    // Update footer
                    // Update footer
                    $(api.column(4).footer()).html(
                        'Amount: ' + amount
                    );

                }
            });

            $('#Withdrawals').DataTable({
                "searching": false,
                "paging": false,
                "ordering": false,
                "info": false,
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api(), data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i * 1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    amount = api.column(4)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        });

                    // Update footer
                    // Update footer
                    $(api.column(4).footer()).html(
                        'Amount: ' + amount
                    );

                }
            });

        });
    </script>


    <link rel="stylesheet" href="{{URL::asset('datepicker/jquery-ui.css')}}">
    <script src="{{URL::asset('datepicker/jquery-ui.js')}}"></script>
    <script>
        $(function () {

            $("#max,#min").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'yy-mm-dd',
                onSelect: function (selectedDate) {
                    if (this.id == 'min') {
                        var dateMin = $('#min').datepicker("getDate");
                        var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(), dateMin.getDate() + 1);
                        $('#max').datepicker("option", "minDate", rMin);
                    }

                }


            });


        });
    </script>
    <script>
        function pdf() {

        }
    </script>

@endsection
