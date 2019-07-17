@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Market Price</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">Market Price</li>
        </ol>
        <div class="clearfix"></div>
    </div>


    <div class="page-content">
        <div class="row">
            <div class="col-md-12">

                @include('panel.alert')

                <div class="row mtl">

                    <div class="col-md-12">

                        <div id="generalTabContent" class="tab-content">

                            <div id="tab-edit" class="tab-pane fade in active">
                                <form action="{{url('check_admin/market_price')}}" method="post" class="form-horizontal">
                                    <h3>Market Price - </h3>
                                    {{ csrf_field() }}

                                    <div class="form-group"><label class="col-sm-3 control-label">1 CMB <i
                                                    class="fa fa-random"></i></label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6 input-group">

                                                    <input type="text" value="{{$result->BTC}}" name="cmb_btc"
                                                           class="form-control" id="cmb_btc"/>
                                                    <span class="input-group-addon">BTC</span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    {{--<div class="form-group"><label class="col-sm-3 control-label">1 CMB <i class="fa fa-random"></i></label>--}}

                                    {{--<div class="col-sm-9 controls">--}}
                                    {{--<div class="row">--}}
                                    {{--<div class="col-xs-6 input-group">--}}

                                    {{--<input type="text" value="{{$result->BCH}}" name="cmb_bch" class="form-control" id="cmb_bch"  />--}}
                                    {{--<span class="input-group-addon">BCH</span>--}}
                                    {{--</div>--}}

                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}


                                    <div class="form-group"><label class="col-sm-3 control-label">1 CMB <i
                                                    class="fa fa-random"></i></label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6 input-group"><input type="text"
                                                                                         value="{{$result->ETH}}"
                                                                                         name="cmb_eth"
                                                                                         class="form-control"
                                                                                         id="cmb_eth"/>
                                                    <span class="input-group-addon">ETH</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--<div class="form-group"><label class="col-sm-3 control-label">1 CMB <i class="fa fa-random"></i></label>--}}

                                    {{--<div class="col-sm-9 controls">--}}
                                    {{--<div class="row">--}}
                                    {{--<div class="col-xs-6 input-group"><input type="text" value="{{$result->XRP}}" name="cmb_xrp" class="form-control" id="cmb_xrp"  />--}}
                                    {{--<span class="input-group-addon">XRP</span>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}

                                    <div class="form-group"><label class="col-sm-3 control-label">1 CMB <i
                                                    class="fa fa-random"></i></label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6 input-group"><input type="text"
                                                                                         value="{{$result->XDCE}}"
                                                                                         name="cmb_xdce"
                                                                                         class="form-control"
                                                                                         id="cmb_xdce"/>
                                                    <span class="input-group-addon">XDCE</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"><label class="col-sm-3 control-label">1 CMB <i
                                                    class="fa fa-random"></i></label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6 input-group"><input type="text"
                                                                                         value="{{$result->USD}}"
                                                                                         name="cmb_usd"
                                                                                         class="form-control"
                                                                                         id="cmb_usd"/>
                                                    <span class="input-group-addon">USD</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <hr/>
                                    <button type="submit" class="btn btn-green btn-block">Update</button>
                                </form>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('script')
    <script>
        $("#cmb_btc").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });

        $("#cmb_eth").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });

        // $("#cmb_xrp").keydown(function (evt) {
        //   var charCode = (evt.which) ? evt.which : evt.keyCode
        // if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
        // return false;
        // return true;
        // });

        $("#cmb_xdce").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });

        $("#cmb_usd").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });
    </script>
@endsection