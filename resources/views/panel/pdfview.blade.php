<html>
<head>
    <title>User list - PDF</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!--Loading bootstrap css-->
    <link type="text/css" rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,700">
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,700,300">
    <link type="text/css" rel="stylesheet"
          href="{{ URL::asset('control/vendors/jquery-ui-1.10.4.custom/css/ui-lightness/jquery-ui-1.10.4.custom.min.css') }}">
    <link type="text/css" rel="stylesheet"
          href="{{ URL::asset('control/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ URL::asset('control/vendors/bootstrap/css/bootstrap.min.css') }}">
    <!--LOADING STYLESHEET FOR PAGE-->

<!-- <link type="text/css" rel="stylesheet" href="{{ URL::asset('control/vendors/calendar/zabuto_calendar.min.css') }}"> -->
    <!--Loading style vendors-->
    <link type="text/css" rel="stylesheet" href="{{ URL::asset('control/vendors/animate.css/animate.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ URL::asset('control/vendors/jquery-pace/pace.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ URL::asset('control/vendors/iCheck/skins/all.css') }}">
    <link type="text/css" rel="stylesheet"
          href="{{ URL::asset('control/vendors/jquery-news-ticker/jquery.news-ticker.css') }}">
    <!--Loading style-->
    <link type="text/css" rel="stylesheet" href="{{ URL::asset('control/css/themes/style3/orange-blue.css') }}"
          id="theme-change" class="style-change color-change">
    <link type="text/css" rel="stylesheet" href="{{ URL::asset('control/css/style-responsive.css') }}">
    <link href="{{ URL::asset('control/css/patternLock.css') }}" rel="stylesheet" type="text/css"/>
    <link type="text/css" rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
    <style>
        body {
            background-color: white;
        }
    </style>
</head>
<body onload="window.print()">
<div class="container">


    <div class="page-content">
        <div class="row mbl">
            {{--user details--}}
            <div class="col-md-12">
                <div class="row">
                    <h2><strong>Name: &nbsp;{{get_user_details($bal->user_id,'enjoyer_name')}}</strong><br></h2>
                    <label class="col-md-6"><strong>User Id:&nbsp;</strong>{{$id}}</label>
                    <label class="col-md-6"><strong>Email Id:&nbsp;</strong>{{ get_usermail($id) }}</label>
                </div>
            </div>
            {{--User Profile--}}
            <div class="col-md-12">
                <div class="row">
                    <h2><strong>Account Balances:</strong><br></h2>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label><strong>XDC:</strong>&nbsp;{{$bal->XDC}}</label>
                    </div>

                    <div class="col-md-4">
                        <label><strong>XDCE:</strong>&nbsp;{{$bal->XDCE}}</label>
                    </div>

                    <div class="col-md-4">
                        <label><strong>ETH:</strong>&nbsp;{{$bal->ETH}}</label>
                    </div>

                    <div class="col-md-4">
                        <label><strong>BTC:</strong>&nbsp;{{$bal->BTC}}</label>
                    </div>

                    <div class="col-md-4">
                        <label><strong>XRP:</strong>&nbsp;{{$bal->XRP}}<br><br></label>
                    </div>
                    <div class="col-md-4">
                        <label><strong>BCH:</strong>&nbsp;{{$bal->BCH}}<br><br></label>
                    </div>
                </div>


                <div class="row">
                    <h2><strong>Explorer Deposits:</strong><br></h2>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label><strong>XDC:</strong>&nbsp;{{$explorer['XDC']}}</label>
                    </div>

                    <div class="col-md-4">
                        <label><strong>XDCE:</strong>&nbsp;{{$explorer['XDCE']}}</label>
                    </div>

                    <div class="col-md-4">
                        <label><strong>ETH:</strong>&nbsp;{{$explorer['ETH']}}</label>
                    </div>

                    <div class="col-md-4">
                        <label><strong>BTC:</strong>&nbsp;{{$explorer['BTC']}}</label>
                    </div>

                    <div class="col-md-4">
                        <label><strong>XRP:</strong>&nbsp;{{$explorer['XRP']}}<br><br></label>
                    </div>

                    <div class="col-md-4">
                        <label><strong>BCH:</strong>&nbsp;{{$explorer['BCH']}}<br><br></label>
                    </div>
                </div>

                <div class="row">
                    <h2><strong>Completed Withdrawals:</strong><br></h2>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label><strong>XDC:</strong>&nbsp;{{$withdraw['XDC']}}</label>
                    </div>

                    <div class="col-md-4">
                        <label><strong>XDCE:</strong>&nbsp;{{$withdraw['XDCE']}}</label>
                    </div>

                    <div class="col-md-4">
                        <label><strong>ETH:</strong>&nbsp;{{$withdraw['ETH']}}</label>
                    </div>

                    <div class="col-md-4">
                        <label><strong>BTC:</strong>&nbsp;{{$withdraw['BTC']}}</label>
                    </div>

                    <div class="col-md-4">
                        <label><strong>XRP:</strong>&nbsp;{{$withdraw['XRP']}}<br><br></label>
                    </div>

                    <div class="col-md-4">
                        <label><strong>BCH:</strong>&nbsp;{{$withdraw['BCH']}}<br><br></label>
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
                        <label><strong>Buyed XDC:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Buy_total['XDC']}}</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Sell XDC:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Sell_total['XDC']}}</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Intrade XDC:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Intrade_total['XDC']}}</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Buyed XDCE:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Buy_total['XDCE']+$ico['XDCE']}}</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Sell XDCE:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Sell_total['XDCE']}}</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Intrade XDCE:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Intrade_total['XDCE']}}</label>
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
                        <label>{{$Sell_total['XRP']+$ico['XRP']}}</label>
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
                        <label>{{$Sell_total['ETH']+$ico['ETH']}}</label>
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
                        <label>{{$Sell_total['BTC']+$ico['BTC']}}</label>
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

                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Buyed BCH:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Buy_total['BCH']}}</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Sell BCH:</strong></label>
                    </div>
                    <div class="col-md-6">
                        <label>{{$Sell_total['BCH']+$ico['BCH']}}</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6">
                        <label><strong>Intrade BCH:</strong></label>
                    </div>

                    <div class="col-md-6">
                        <label>{{$Intrade_total['BCH']}}</label>
                    </div>
                </div>

            </div>
        </div>
        {{--user buy Trade of that particular currency--}}
        <div class="table-container">
            <div>
                <h2>&nbsp;&nbsp;Buy Trades:</h2>
                <br>
            </div>
            <table class="table table-hover table-striped table-bordered table-advanced tablesorter"
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
                <tbody>
                @if($buy_trade)
                    @foreach($buy_trade as $key=>$val)
                        <tr>
                            <td>{{$val->Amount}}</td>
                            <td>{{$val->Price}}</td>
                            <td>{{$val->Fee}}</td>
                            <td>{{$val->Total}}</td>
                            <td>{{$val->Type}}</td>
                            <td>{{$val->firstCurrency}}</td>
                            <td>{{$val->secondCurrency}}</td>
                            <td>{{$val->status}}</td>
                            <td>{{$val->updated_at}}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
                </thead>

            </table>

        </div>

        {{--user buy Trade of that particular currency--}}
        <div class="table-container">
            <div>
                <h2>&nbsp;&nbsp;Sell Trades:</h2>
                <br>
            </div>
            <table class="table table-hover table-striped table-bordered table-advanced tablesorter"
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
                            <td>{{$val->Amount}}</td>
                            <td>{{$val->Price}}</td>
                            <td>{{$val->Fee}}</td>
                            <td>{{$val->Total}}</td>
                            <td>{{$val->Type}}</td>
                            <td>{{$val->firstCurrency}}</td>
                            <td>{{$val->secondCurrency}}</td>
                            <td>{{$val->status}}</td>
                            <td>{{$val->updated_at}}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
                </thead>

            </table>

        </div>
        <div class="table-container">
            <div>
                <h2>&nbsp; Pending Trades:</h2>
                <br>
            </div>
            <table class="table table-hover table-striped table-bordered table-advanced tablesorter"
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
                            <td>{{$val->Amount}}</td>
                            <td>{{$val->Price}}</td>
                            <td>{{$val->Fee}}</td>
                            <td>{{$val->Total}}</td>
                            <td>{{$val->Type}}</td>
                            <td>{{$val->firstCurrency}}</td>
                            <td>{{$val->secondCurrency}}</td>
                            <td>{{$val->status}}</td>
                            <td>{{$val->updated_at}}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
                </thead>

            </table>

        </div>

        {{--ico buy Trade of that particular currency--}}
        <div class="table-container">
            <div>
                <h2>&nbsp;&nbsp;ICO Trades:</h2>
                <br>
            </div>
            <table class="table table-hover table-striped table-bordered table-advanced tablesorter"
                   id="myICO">
                <thead>
                <tr>
                    <th>Amount</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Type</th>
                    <th>First Currency</th>
                    <th>Second Currency</th>
                    <th>Status</th>
                    <th>Updated time</th>


                </tr>
                <tbody>
                @if($ico_trade)
                    @foreach($ico_trade as $key=>$val)
                        <tr>
                            <td>{{$val->Amount}}</td>
                            <td>{{$val->Price}}</td>
                            <td>{{$val->Total}}</td>
                            <td>{{$val->Type}}</td>
                            <td>{{$val->FirstCurrency}}</td>
                            <td>{{$val->SecondCurrency}}</td>
                            <td>{{$val->Status}}</td>
                            <td>{{$val->updated_at}}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
                </thead>
            </table>

        </div>

        {{--user Deposit Trade of that particular currency--}}
        <div class="table-container">
            <div>
                <h2>&nbsp;&nbsp;Deposits:</h2>
                <br>
            </div>
            <table class="table table-hover table-striped table-bordered table-advanced tablesorter"
                   id="mySellTrade">
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

            </table>

        </div>

        {{--user Withdrawal Trade of that particular currency--}}
        <div class="table-container">
            <div>
                <h2>&nbsp;&nbsp;Withdrawal:</h2>
                <br>
            </div>
            <table class="table table-hover table-striped table-bordered table-advanced tablesorter"
                   id="mySellTrade">
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

            </table>

        </div>


    </div>

</div>

{{--scripts--}}

<script src="{{ URL::asset('control/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ URL::asset('control/js/jquery-migrate-1.2.1.min.js') }}"></script>
<script src="{{ URL::asset('control/js/jquery-ui.js') }}"></script>
<!--loading bootstrap js-->
<script src="{{ URL::asset('control/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('control/vendors/bootstrap-hover-dropdown/bootstrap-hover-dropdown.js') }}"></script>

<script src="{{ URL::asset('control/js/respond.min.js') }}"></script>
<script src="{{ URL::asset('control/vendors/metisMenu/jquery.metisMenu.js') }}"></script>
<script src="{{ URL::asset('control/vendors/slimScroll/jquery.slimscroll.js') }}"></script>
<script src="{{ URL::asset('control/vendors/jquery-cookie/jquery.cookie.js') }}"></script>
<script src="{{ URL::asset('control/vendors/iCheck/icheck.min.js') }}"></script>
<script src="{{ URL::asset('control/vendors/iCheck/custom.min.js') }}"></script>
<script src="{{ URL::asset('control/vendors/jquery-news-ticker/jquery.news-ticker.js') }}"></script>
<script src="{{ URL::asset('control/js/jquery.menu.js') }}"></script>
<script src="{{ URL::asset('control/vendors/jquery-pace/pace.min.js') }}"></script>
<script src="{{ URL::asset('control/vendors/holder/holder.js') }}"></script>
<script src="{{ URL::asset('control/vendors/responsive-tabs/responsive-tabs.js') }}"></script>

<script src="{{ URL::asset('control/js/main.js') }}"></script>

<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#myBuyTrade').DataTable({
            "searching": false,
            "paging": false,
            "ordering": false,
            "info": false
        });

        $('#mySellTrade').DataTable({
            "searching": false,
            "paging": false,
            "ordering": false,
            "info": false
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


</body>

</html>