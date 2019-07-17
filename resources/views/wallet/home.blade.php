@extends("wallet.layout.admin_layout")
@section("content")
<div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
                <div class="page-header pull-left">
                    <div class="page-title">Dashboard</div>
                </div>
                <ol class="breadcrumb page-breadcrumb pull-right">
                    <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                    <li class="hidden"><a href="#">Dashboard</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                    <li class="active">Dashboard</li>
                </ol>
                <div class="clearfix"></div>
            </div>
            <!--END TITLE & BREADCRUMB PAGE--><!--BEGIN CONTENT-->
            <div class="page-content">
                <div id="tab-general">
                    <div id="sum_box" class="row mbl">




                        <div class="col-sm-6 col-md-6">
                            <div class="panel db mbm" style="cursor: pointer;">
                                <div class="panel-body"><p class="icon">
                                   <i class="fa font50"><img src="{{URL::asset('front')}}/assets/icons/USDT.png" class="stat-icon" style="width: 50px;height: 50px;"></i>
                                </p><h4 class="value"><span>{{$usdt_bal}}</span></h4>

                                    <p class="description">Admin USDT Balance</p>


                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <div class="panel db mbm" style="cursor: pointer;">
                                <div class="panel-body"><p class="icon">
                                        <i class="fa font50"><img src="{{URL::asset('front')}}/assets/icons/XRP.png" class="stat-icon" style="width: 50px;height: 50px;"></i>
                                    </p><h4 class="value"><span>{{$xrp_bal}}</span></h4>

                                    <p class="description">Admin XRP Balance</p>


                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="panel db mbm" style="cursor: pointer;">
                                <div class="panel-body"><p class="icon">
                                    <i class="fa font50"><img src="{{URL::asset('front')}}/assets/icons/BTC.png" class="stat-icon" style="width: 50px;height: 50px;"></i>
                                </p><h4 class="value"><span>{{$btc_bal}}</span></h4>

                                    <p class="description">BTC Wallet Balance</p>


                                </div>
                            </div>
                        </div>


                        <div class="col-sm-6 col-md-4">
                            <div class="panel db mbm" style="cursor: pointer;">
                                <div class="panel-body"><p class="icon">
                                    <i class="fa font50"><img src="{{URL::asset('front')}}/assets/icons/ETH.png" class="stat-icon" style="width: 50px;height: 50px;"></i>
                                </p><h4 class="value"><span>{{$eth_bal}}</span></h4>

                                    <p class="description">Admin ETH balance</p>


                                </div>
                            </div>
                        </div>

                        {{--<div class="col-sm-6 col-md-4">--}}
                            {{--<div class="panel db mbm" style="cursor: pointer;">--}}
                                {{--<div class="panel-body"><p class="icon">--}}
                                    {{--<i class="fa font50"><img src="{{URL::asset('front')}}/assets/icons/XRP.png" class="stat-icon" style="width: 50px;height: 50px;"></i>--}}
                                {{--</p><h4 class="value"><span>{{$xrp_bal}}</span></h4>--}}

                                    {{--<p class="description">Admin XRP Balance</p>--}}


                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}


                    </div>
                    <div class="row mbl">
                        <div class="col-lg-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12"><h4 class="mbs">Admin Profit</h4>
                        <div id="container" style="width: 100%; height:425px"></div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
            @endsection
@section('script')
@include('wallet.layout.dashboard_script')
@endsection