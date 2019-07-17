@extends("wallet.layout.admin_layout")
@section("content")
<div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
                <div class="page-header pull-left">
                    <div class="page-title">Transfer - {{$currency}}</div>
                </div>
                <ol class="breadcrumb page-breadcrumb pull-right">
                    <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                    <li class="hidden"><a href="#">Transfer</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                    <li class="active">Transfer</li>
                </ol>
                <div class="clearfix"></div>
            </div>

<style type="text/css">
    .error
    {
        color: red;
    }
</style>
            <div class="page-content">
  <div class="row">
                    <div class="col-md-12">

                     @include('wallet.alert')

                        <div class="row mtl">

                            <div class="col-md-12">

                                <div id="generalTabContent" class="tab-content">
                                    <div id="tab-edit" class="tab-pane fade in active">
                                        <form action="{{url('walletjey/walletwithdraw/'.$currency)}}" method="post" class="form-horizontal" id="wallet_transfer">
                                        <h3>Transfer - {{$currency}}</h3>
                                        {{ csrf_field() }}
                                            <div class="form-group"><label class="col-sm-3 control-label">Your {{$currency}} Address *</label>

                                                <div class="col-sm-9 controls">
                                                    <div class="row">
                                                 <div class="col-xs-9"><input type="text"  class="form-control" name="" value="{{$addr}}" disabled="disabled" /></div>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="form-group"><label class="col-sm-3 control-label">To {{$currency}} Address *</label>

                                                <div class="col-sm-9 controls">
                                                      <div class="row">
                                                 <div class="col-xs-9"><input type="text"  class="form-control" name="to_addr" value="" /></div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($currency=='XRP')

                                            <div class="form-group"><label class="col-sm-3 control-label">Destination Tag</label>

                                                <div class="col-sm-9 controls">
                                                    <div class="row">
                                                 <div class="col-xs-9"><input type="text"  class="form-control" name="xrp_desttag" value="" /></div>
                                                    </div>
                                                </div>
                                            </div>

                                            @endif


                                             <div class="form-group"><label class="col-sm-3 control-label">{{$currency}} Amount *</label>

                                                <div class="col-sm-9 controls">
                                                      <div class="row">
                                                 <div class="col-xs-9"><input type="text"  class="form-control" id="to_amount" name="to_amount" value="" /></div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group"><label class="col-sm-3 control-label">OTP *</label>

                                                <div class="col-sm-9 controls input-group">
                                                     <div class="row">
                                                 <div class="col-xs-9 "><input type="text"  class="form-control" id="otp_num" name="otp_num" value="" />
                                                     <span class="input-group-addon" id="trans_otp">
                                                     <a href="javascript:;" onclick="generate_otp();" class="btn btn-info btn-sm">Generate OTP</a>
                                                     </span>
                                                 </div>
                                                    </div>
                                                </div>
                                            </div>




                                            <hr/>
                                            <button type="submit" class="btn btn-green btn-block">Submit</button>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.js"></script>

<script type="text/javascript">
$("#wallet_transfer").validate({
    rules:
    {
        to_addr: {required: true,remote:{
                url:'{{url("ajax/address_validation")}}',
                type:'post',
                data:{ 'curr':'{{$currency}}',},
            }},
        to_amount: {required:true,},
        otp_num: {required:true,number: true},
    },
    messages:
    {
        to_addr: {required: 'To {{$currency}} address is required', remote: 'Enter valid {{$currency}} address'},
        to_amount: {required: 'Amount is required',},
        otp_num: {required: 'OTP is required', number: 'Digit only allowed'},
    }
});


   $("#to_amount").keydown(function (evt) {
  var charCode = (evt.which) ? evt.which : evt.keyCode
if (charCode > 32 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 107) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
return false;
return true;
});

    $("#otp_num").keydown(function (evt) {
  var charCode = (evt.which) ? evt.which : evt.keyCode
if (charCode > 32 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 107) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
return false;
return true;
});


     function generate_otp()
    {
        $.ajax({
            type:'post',
            url:'{{url("walletjey/generate_otp")}}',
            data:'key={{time()}}&_token={{ csrf_token() }}',
            success:function(data)
            {
                $("#trans_otp").html('<a href="#" class="btn btn-info btn-sm">Sent</a>');
            }
        });
    }

</script>
@endsection