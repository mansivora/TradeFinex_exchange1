@extends("wallet.layout.admin_layout")
@section("content")
<div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
                <div class="page-header pull-left">
                    <div class="page-title">Change Pattern</div>
                </div>
                <ol class="breadcrumb page-breadcrumb pull-right">
                    <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>

                    <li class="active">Change Pattern</li>
                </ol>
                <div class="clearfix"></div>
            </div>


            <div class="page-content">
  <div class="row">
                    <div class="col-md-12">

                     @include('wallet.alert')
                      <div class="alert alert-danger" id="pat_error" style="display: none;">
 <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
       <p id="pat_error_message"></p>
    </div>

                        <div class="row mtl">

                            <div class="col-md-12">

                                <div id="generalTabContent" class="tab-content">
                                    <div id="tab-edit" class="tab-pane fade in active">
                                        <form action="{{url('walletjey/change_pattern')}}" method="post" class="form-horizontal">

                                        {{ csrf_field() }}
                                        <h3>Current Pattern</h3>


                      <div class="form-group"><label class="col-sm-3 control-label"></label>

                                                <div class="col-sm-9 controls">
                                            <div id="old_pattern"></div>
                                                </div>
                                            </div>
                    <br>

                                         <h3 id="new_div">New Pattern</h3>


                      <div class="form-group"><label class="col-sm-3 control-label"></label>

                                                <div class="col-sm-9 controls">
                                            <div id="new_pattern"></div>
                                                </div>
                                            </div>

                                            <h3>Confirm New Pattern</h3>


                      <div class="form-group"><label class="col-sm-3 control-label"></label>

                                                <div class="col-sm-9 controls">
                                            <div id="conf_new_pattern"></div>
                                                </div>
                                            </div>





                                           <hr>
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

<script src="{{ URL::asset('control/js/patternLock.js') }}"></script>
<script>
  var lock = new PatternLock("#old_pattern",{
    mapper: function(idx){
        return (idx%9) + 1;
    },
     onDraw:function(pattern){
           oldpattern(pattern);
    }
});

  var lock1 = new PatternLock("#new_pattern",{
    mapper: function(idx){
        return (idx%9) + 1;
    },
  /*   onDraw:function(pattern){
           alert(pattern);
    }*/
});

   var lock2 = new PatternLock("#conf_new_pattern",{
    mapper: function(idx){
        return (idx%9) + 1;
    },
     onDraw:function(pattern){
           confirm_pattern(pattern);
    }
});

$(document).ready(function()
{
    lock1.disable();
    lock2.disable();
});

function oldpattern(pattern)
{
    $.ajax({
        type:'Post',
        url:'{{url("walletjey/checkpattern")}}',
        data:{'key1':0,'key2':pattern,'_token': '{{ csrf_token() }}'},
        success:function(data)
        {
            if(data==pattern)
            {
                lock.setPattern(data);
                lock.disable();
                 lock1.enable();
                 lock2.enable();
                 window.location.hash="#new_div";
            }
            else
            {
                 lock.error();
                $("#pat_error").show();
                $("#pat_error_message").html('Old Pattern is wrong');
                $("#pat_error").fadeOut(5000);
            }
        }
    });
}

function confirm_pattern(pattern)
{
    var newpat=lock1.getPattern();
    if(newpat==pattern)
    {
       $.ajax({
        type:'post',
        url:'{{url("walletjey/set_pattern")}}',
        data:{'key':newpat,'_token': '{{ csrf_token() }}'},
        success:function(data)
        {
            window.location.hash="#";
            location.reload();
        }
       });
    }
    else
    {
        lock2.error();
        $("#pat_error").show();
        $("#pat_error_message").html("Doesn't match confirm new pattern");
        window.location.hash="#generalTabContent";
    }
}

  </script>
@endsection