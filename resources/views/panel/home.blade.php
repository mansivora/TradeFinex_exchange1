@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Dashboard</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>
            <li class="hidden"><a href="#">Dashboard</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
            <li class="active">Dashboard</li>
        </ol>
        <div class="clearfix"></div>
    </div>
    <!--END TITLE & BREADCRUMB PAGE--><!--BEGIN CONTENT-->
    <div class="page-content">
        <div id="tab-general">
            <div id="sum_box" class="row mbl">
                <div class="col-sm-6 col-md-3">
                    <div class="panel profit db mbm" onclick="window.location.href='{{url('check_admin/users')}}'"
                         style="cursor: pointer;">
                        <div class="panel-body"><p class="icon"><i class="icon fa fa-group"></i></p><h4 class="value">
                                <span data-counter="" data-start="10" data-end="50" data-step="1"
                                      data-duration="0">{{dashboard_usercount()}}</span></h4>

                            <p class="description">Registered Users</p>


                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="panel income db mbm"
                         onclick="window.location.href='{{url('check_admin/trade_history')}}'" style="cursor: pointer;">
                        <div class="panel-body"><p class="icon"><i class="icon fa fa-exchange"></i></p><h4>
                                <span>{{dashboard_totaltrans()}}</span></h4>

                            <p class="description">Total Transactions</p>


                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="panel task db mbm" onclick="window.location.href='{{url('check_admin/profit')}}'"
                         style="cursor: pointer;">
                        <div class="panel-body"><p class="icon"><i class="icon fa fa-signal"></i></p><h4 class="value">
                                <span>{{number_format(dashbard_totalbtcprofit(),4,'.','')}}</span><span><i
                                            class="fa fa-btc"></i></span></h4>

                            <p class="description">Profit</p>


                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="panel visit db mbm" onclick="window.location.href='{{url('check_admin/kyc_users')}}'"
                         style="cursor: pointer;">
                        <div class="panel-body"><p class="icon"><i class="icon fa fa-group"></i></p><h4 class="value">
                                <span>{{dashbard_totalkyc()}}</span></h4>

                            <p class="description">KYC verification</p>


                        </div>
                    </div>
                </div>
                {{--<div class="col-sm-6 col-md-3">--}}
                    {{--<div class="panel visit db mbm"--}}
                         {{--onclick="window.location.href='{{url('check_admin/token_request')}}'"--}}
                         {{--style="cursor: pointer;">--}}
                        {{--<div class="panel-body"><p class="icon"><i class="icon fa fa-group"></i></p><h4 class="value">--}}
                                {{--<span>{{dashboard_ico_listing_count()}}</span></h4>--}}

                            {{--<p class="description">Token Listing Request</p>--}}


                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="clearfix"></div>
                {{--admin usdt balance--}}
                <div class="col-sm-6 col-md-4">
                    <div class="panel db mbm" style="cursor: pointer;">
                        <div class="panel-body"
                             onclick="window.open('https://www.omniexplorer.info/','newtab');">

                            <div class="row">
                                <div class="col-md-12">
                                    <div style="text-align: center"><i class="fa font50"><img
                                                    src="{{URL::asset('front')}}/assets/icons/USDT.png"
                                                    class="stat-icon"
                                                    style="width: 50px;height: 50px;"></i></div>
                                    <div style="text-align: center"><p>
                                        <h2>{{$usdt_bal}}</h2></p>
                                        <p class="description "><strong>Admin USDT Balance</strong></p></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label><strong>Users:</strong></label>
                                </div>
                                <div class="col-md-8">
                                    <label>{{$user_usdt}}</label>
                                </div>

                                <div class="col-md-4">
                                    <label><strong>In Trade:</strong></label>
                                </div>
                                <div class="col-md-8">
                                    <label>{{$trade_usdt}}</label>
                                </div>


                                <div class="col-md-4">
                                    <label><strong>Total:</strong></label>
                                </div>
                                <div class="col-md-8">
                                    <label>{{$user_usdt+$trade_usdt}}</label>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>

                {{--admin xrp balance--}}
                <div class="col-sm-6 col-md-4">
                    <div class="panel db mbm" style="cursor: pointer;">
                        <div class="panel-body"
                             onclick="window.open('https://bithomp.com/','_newtab');">
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="text-align: center"><i class="fa font50"><img
                                                    src="{{URL::asset('front')}}/assets/icons/XRP.png"
                                                    class="stat-icon" style="width: 50px;height: 50px;"></i></div>
                                    <div style="text-align: center"><p>
                                        <h2>{{$xrp_bal}}</h2></p>
                                        <p class="description "><strong>Admin XRP Balance</strong></p></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label><strong>Users:</strong></label>
                                </div>
                                <div class="col-md-8">
                                    <label>{{$user_xrp}}</label>
                                </div>

                                <div class="col-md-4">
                                    <label><strong>In Trade:</strong></label>
                                </div>
                                <div class="col-md-8">
                                    <label>{{$trade_xrp}}</label>
                                </div>


                                <div class="col-md-4">
                                    <label><strong>Total:</strong></label>
                                </div>
                                <div class="col-md-8">
                                    <label>{{$user_xrp+$trade_xrp}}</label>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>

                {{--BTC Admin--}}
                <div class="col-sm-6 col-md-4">
                    <div class="panel db mbm" style="cursor: pointer;">
                        <div class="panel-body"
                             onclick="window.open('https://blockexplorer.com/','_newtab');">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div style="text-align: center"><i class="fa font50"><img
                                                        src="{{URL::asset('front')}}/assets/icons/BTC.png"
                                                        class="stat-icon"
                                                        style="width: 50px;height: 50px;"></i></div>
                                        <div style="text-align: center"><p>
                                            <h2>{{$btc_bal}}</h2></p>
                                            <p class="description "><strong>Admin BTC Balance</strong></p></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label><strong>Users:</strong></label>
                                    </div>
                                    <div class="col-md-8">
                                        <label>{{$user_btc}}</label>
                                    </div>

                                    <div class="col-md-4">
                                        <label><strong>In Trade:</strong></label>
                                    </div>
                                    <div class="col-md-8">
                                        <label>{{$trade_btc}}</label>
                                    </div>


                                    <div class="col-md-4">
                                        <label><strong>Total:</strong></label>
                                    </div>
                                    <div class="col-md-8">
                                        <label>{{$user_btc+$trade_btc}}</label>
                                    </div>

                                </div>


                            </div>
                        </div>
                    </div>
                </div>

                    {{--ETH Admin--}}
                    <div class="col-sm-6 col-md-4">
                        <div class="panel db mbm" style="cursor: pointer;">
                            <div class="panel-body"
                                 onclick="window.open('https://etherscan.io/','_newtab');">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div style="text-align: center"><i class="fa font50"><img
                                                        src="{{URL::asset('front')}}/assets/icons/ETH.png"
                                                        class="stat-icon"
                                                        style="width: 50px;height: 50px;"></i></div>
                                        <div style="text-align: center"><p>
                                            <h2>{{$eth_bal}}</h2></p>
                                            <p class="description "><strong>Admin ETH Balance</strong></p></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label><strong>Users:</strong></label>
                                    </div>
                                    <div class="col-md-8">
                                        <label>{{$user_eth}}</label>
                                    </div>

                                    <div class="col-md-4">
                                        <label><strong>In Trade:</strong></label>
                                    </div>
                                    <div class="col-md-8">
                                        <label>{{$trade_eth}}</label>
                                    </div>


                                    <div class="col-md-4">
                                        <label><strong>Total:</strong></label>
                                    </div>
                                    <div class="col-md-8">
                                        <label>{{$user_eth+$trade_eth}}</label>
                                    </div>

                                </div>


                            </div>
                        </div>
                    </div>

                </div>

                <div class="table-container">

                    <div>
                        <h2>&nbsp;&nbsp;Latest 25 Trades:</h2>
                        <br>
                    </div>
                    <table class="table table-hover table-striped table-bordered table-advanced tablesorter"
                           id="myTable">
                        <thead>
                        <tr>

                            <th>User ID</th>
                            <th>Username</th>
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
                        @if($trade_25)
                            @foreach($trade_25 as $key=>$val)
                                <tr>

                                    <td>{{$val->user_id}}</td>
                                    <td>@if(get_user_details($val->user_id,'enjoyer_name')==''){{get_usermail($val->user_id)}}@else{{get_user_details($val->user_id,'enjoyer_name')}}@endif</td>
                                    @if($val->type=='active'||$val->type=='partially')
                                        <td>{{number_format($val->updated_qty,'3','.','')}}</td>
                                    @else
                                        <td>{{number_format($val->original_qty,'3','.','')}}</td>
                                    @endif
                                    <td>{{number_format($val->price,'3','.','')}}</td>
                                    <td>{{number_format($val->fee,'4','.','')}}</td>
                                    @if($val->status=='active'||$val->status=='cancelled')
                                        <td>{{number_format($val->total,'3','.','')}}</td>
                                    @else
                                        <td>{{number_format($val->updated_total,'3','.','')}}</td>
                                    @endif
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
                    </table>
                </div>
            </div>
        </div>
        @endsection
        @section('script')
            @include('panel.layout.dashboard_script')
            <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#myTable').DataTable({
                        "searching": false,
                        "paging": false,
                        "ordering": false,
                        "info": false
                    });
                });
            </script>
@endsection