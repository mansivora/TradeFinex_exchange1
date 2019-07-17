@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Users Wallet Balance</div>
            <input type="hidden" id="url" value="{{url('check_admin/')}}">
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">User Balance</li>
        </ol>
        <div class="clearfix"></div>
    </div>


    <div class="page-content">
        <div class="row">
            <div class="col-md-12">

                @include('panel.alert')


                <div id="tableactionTabContent" class="tab-content">
                    <div id="table-table-tab" class="tab-pane fade in active">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="table-container">

                                    <table class="table table-hover table-striped table-bordered table-advanced tablesorter"
                                           id="myTable">
                                        <thead>
                                        <tr>

                                            <th>Username</th>
                                            <th>Explorer Deposit</th>
                                            <th>Alphaex Deposit</th>
                                            <th>Buy</th>
                                            <th>Sell</th>
                                            <th>InTrade Buy</th>
                                            <th>InTrade Sell</th>
                                            <th>Withdraw</th>

                                        </tr>
                                        <tbody>
                                        <tr>
                                            <td>{{$enjoyer}}</td>
                                            <td>{{$Deposit}}</td>
                                            <td>{{$ADeposit}}</td>
                                            <td>{{$Buy}}</td>
                                            <td>{{$Sell}}</td>
                                            <td>{{$TBuy}}</td>
                                            <td>{{$TSell}}</td>
                                            <td>{{$Withdraw}}</td>
                                        </tr>
                                        </tbody>
                                        </thead></table>

                                </div>


                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h2>Displayed Balance: {{$Balance}}</h2>

                    </div>
                    <div class="col-md-6">
                        <h2>Validated Balance: {{($Deposit+$Buy+$TBuy)-($Withdraw+$Sell+$TSell)}}</h2>

                    </div>
                    <div class="col-md-12">

                        @if($Deposit+$Buy+$TBuy-$Withdraw-$Sell-$TSell>$Balance)
                            <h2>Difference In Balance:{{($Deposit+$Buy+$TBuy-$Withdraw-$Sell-$TSell)-$Balance}}</h2>
                        @else
                            <h2>Difference In Balance:{{$Balance-($Deposit+$Buy+$TBuy-$Withdraw-$Sell-$TSell)}}</h2>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>



@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var url = document.getElementById('url').value;
            var anchor = document.getElementById('bal_tooltip');
            $.getJSON(url + "/total_userbalance", function (result) {
                anchor.title = 'Total BTC: ' + result.BTC +
                    ' Admin BTC: ' + result.Admin_BTC + '\nTotal ETH: ' + result.ETH +
                    ' Admin ETH: ' + result.Admin_ETH + '\nTotal XRP: ' + result.XRP +
                    ' Admin XRP: ' + result.Admin_XRP + '\nTotal XDC: ' + result.XDC +
                    ' Admin XDC: ' + result.Admin_XDC;
            });
            var myTable = $('#myTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false
            });

        });

    </script>
@endsection