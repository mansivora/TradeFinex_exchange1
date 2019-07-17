@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Trading Fee</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">Fee</li>
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
                                <form action="{{url('check_admin/trading_fee/'.$pair)}}" method="post"
                                      class="form-horizontal">
                                    <h3>Trading Fee ({{$pair}})</h3>
                                    {{ csrf_field() }}

                                    <div class="form-group"><label class="col-sm-3 control-label">{{$pair}} Buy
                                            Fees</label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-8">
                                                    <input type="text" class="form-control" name="buy_fee" id="buy_fee"
                                                           value="{{$result->buy_fee}}"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"><label class="col-sm-3 control-label">{{$pair}} Sell
                                            Fees</label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-8">
                                                    <input type="text" class="form-control" name="sell_fee"
                                                           id="sell_fee" value="{{$result->sell_fee}}"/>
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
        $("#buy_fee").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });

        $("#sell_fee").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });

    </script>
@endsection