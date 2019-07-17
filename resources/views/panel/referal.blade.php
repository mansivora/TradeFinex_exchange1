@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Referral Bonus</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">Referral Bonus</li>
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
                                <form action="{{url('check_admin/referral')}}" method="post" class="form-horizontal">
                                    <h3>Referral Program</h3>
                                    {{ csrf_field() }}

                                    <div class="form-group"><label class="col-sm-3 control-label">Referrer Bonus <i
                                                    class="fa fa-random"></i></label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6 input-group">

                                                    <input type="text" value="{{$referral->referrer_bonus}}"
                                                           name="referrer_bonus" class="form-control" id="referrer_bonus"/>
                                                    <span class="input-group-addon">{{$referral->currency}}</span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"><label class="col-sm-3 control-label">Referred Bonus <i
                                                    class="fa fa-random"></i></label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6 input-group">

                                                    <input type="text" value="{{$referral->referred_bonus}}"
                                                           name="referred_bonus" class="form-control"
                                                           id="referred_bonus"/>
                                                    <span class="input-group-addon">{{$referral->currency}}</span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"><label class="col-sm-3 control-label">Currency </label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6 input-group">

                                                    <select class="form-control" name="currency">
                                                        @foreach($currencies as $val)
                                                            <option value="{{$val->currency_symbol}}"
                                                                    @if($referral->currency == $val->currency_symbol) selected @endif>
                                                                {{$val->currency_symbol}}
                                                            </option>
                                                        @endforeach
                                                    </select>
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