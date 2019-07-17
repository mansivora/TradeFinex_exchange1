@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Fee / Limit Settings</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">Fee / Limit</li>
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
                                <form action="{{url('check_admin/fee_config')}}" method="post" class="form-horizontal">
                                    <h3>Fee / Limit Settings</h3>
                                    {{ csrf_field() }}

                                    <div class="form-group"><label class="col-sm-3 control-label">Minimum Exchange Limit
                                            (XDC) </label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6">

                                                    <input type="text" value="{{$result->buy_sell_limit}}"
                                                           name="buy_sell_limit" class="form-control"
                                                           id="buy_sell_limit"/>

                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group"><label class="col-sm-3 control-label">Maximum Exchange Limit
                                            (XDC) </label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6">

                                                    <input type="text" value="{{$result->buy_sell_limit_max}}"
                                                           name="buy_sell_limit_max" class="form-control"
                                                           id="buy_sell_limit_max"/>

                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group"><label class="col-sm-3 control-label">Exchange Fee </label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6 input-group">

                                                    <input type="text" value="{{$result->exchange_fee}}"
                                                           name="exchange_fee" class="form-control" id="exchange_fee"/>
                                                    <span class="input-group-addon">%</span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group"><label class="col-sm-3 control-label">Transfer Fee
                                            (BTC) </label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6 input-group"><input type="text"
                                                                                         value="{{$result->withdraw_fee_btc}}"
                                                                                         name="withdraw_fee_btc"
                                                                                         class="form-control"
                                                                                         id="withdraw_fee_btc"/>
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"><label class="col-sm-3 control-label">Transfer Fee
                                            (BCH) </label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6 input-group"><input type="text"
                                                                                         value="{{$result->withdraw_fee_bch}}"
                                                                                         name="withdraw_fee_bch"
                                                                                         class="form-control"
                                                                                         id="withdraw_fee_bch"/>
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"><label class="col-sm-3 control-label">Transfer Fee
                                            (ETH) </label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6 input-group"><input type="text"
                                                                                         value="{{$result->withdraw_fee_eth}}"
                                                                                         name="withdraw_fee_eth"
                                                                                         class="form-control"
                                                                                         id="withdraw_fee_eth"/>
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group"><label class="col-sm-3 control-label">Transfer Fee
                                            (XRP) </label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6 input-group"><input type="text"
                                                                                         value="{{$result->withdraw_fee_xrp}}"
                                                                                         name="withdraw_fee_xrp"
                                                                                         class="form-control"
                                                                                         id="withdraw_fee_xrp"/>
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group"><label class="col-sm-3 control-label">User Total Spend
                                            (BTC)</label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6 input-group"><input type="text"
                                                                                         value="{{$result->spend_limit_btc}}"
                                                                                         name="spend_limit_btc"
                                                                                         class="form-control"
                                                                                         id="spend_limit_btc"/>
                                                    <span class="input-group-addon">BTC</span>
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
        $("#buy_sell_limit").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });

        $("#buy_sell_limit_max").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });

        $("#withdraw_fee_btc").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });

        $("#withdraw_fee_bch").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });

        $("#withdraw_fee_eth").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });

        $("#withdraw_fee_xrp").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });

        $("#exchange_fee").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });

        $("#spend_limit_btc").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });


    </script>
@endsection