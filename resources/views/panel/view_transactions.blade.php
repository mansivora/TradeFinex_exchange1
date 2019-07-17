@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Transaction details</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">Find Transfer</li>
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

                                <form class="form-horizontal" id="confirm_form"
                                      action="{{url('check_admin/confirm_transfer/'.$result->id)}}" method="post">
                                    <h3>Request Details: </h3>
                                    <div class="panel-body pan">
                                        <div class="form-body pal">


                                            <div class="row">


                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Transaction
                                                                ID:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->transaction_id}}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Currency
                                                                Name:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->currency_name}}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Type:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->type}}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>User
                                                                ID:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->user_id}}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>User
                                                                Email:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{get_usermail($result->user_id)}}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>User
                                                                Verified
                                                                :</strong></label>

                                                        @if(get_user_verified($result->user_id)== 'Unverified')
                                                            <div class="col-md-10"><p
                                                                        class="form-control-static"><a href=""
                                                                                                       id="user_verified"
                                                                                                       style="color: red"
                                                                                                       onclick="user_verified()">{{get_user_verified($result->user_id)}}</a>
                                                                </p>
                                                            </div>
                                                        @else
                                                            <div class="col-md-10"><p
                                                                        class="form-control-static"><a href=""
                                                                                                       id="user_verified"
                                                                                                       style="color: green"
                                                                                                       onclick="user_verified()">{{get_user_verified($result->user_id)}}</a>
                                                                </p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Amount:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->amount}}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Fee:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->fee}}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Transfer
                                                                Amount:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->paid_amount}}</p>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>To
                                                                Address:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->crypto_address}}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($result->currency_name=='XRP')
                                                    <div class="col-md-12">
                                                        <div class="form-group"><label for="inputLastName"
                                                                                       class="col-md-2 control-label"><strong>Destination
                                                                    Tag:</strong></label>

                                                            <div class="col-md-10"><p
                                                                        class="form-control-static">{{$result->xrp_desttag}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Request
                                                                Date:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->created_at}}</p>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Status:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->status}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Transaction
                                                                Details:</strong></label>

                                                        <div class="col-md-10"><a
                                                                    class="form-contrtol-static" target="_blank"
                                                                    href="{{url('check_admin/user_transaction_details?user_id='.$result->user_id)}}">click
                                                                here</a>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($result->status=='Pending' || $result->status=='Processing')
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="txdid"
                                                           value="{{$result->transaction_id}}">
                                                    <input type="hidden" name="currency"
                                                           value="{{$result->currency_name}}">

                                                    {{--for otp--}}
                                                    {{--<div class="col-md-12">--}}
                                                    {{--<div class="form-group input-group"><label for="inputLastName" class="col-md-2 control-label"><strong>OTP:</strong></label>--}}

                                                    {{--<div class="col-md-10">--}}
                                                    {{--<input type="text" class="form-control" name="otp_code" required />--}}
                                                    {{--<span class="input-group-addon" id="trans_otp">--}}
                                                    {{--<a href="#" onclick="generate_otp();" class="btn btn-info btn-sm">Generate OTP</a>--}}
                                                    {{--</span>--}}
                                                    {{--</div>--}}
                                                    {{--</div>--}}
                                                    {{--</div>--}}

                                                    <textarea style="display:none;" id="description" name="description"
                                                              class="form-control-static"
                                                              placeholder="Remarks if any"></textarea>
                                                    <input type="hidden" id="transferred_by" name="transferred_by"
                                                           class="form-control-static" placeholder="Name">
                                                    <input type="hidden" class="btn btn-success" name="subbuton"
                                                           value="Confirm">

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="inputLastName"
                                                                   class="col-md-2 control-label"><strong></strong></label>
                                                            <input type="submit" class="btn btn-success"
                                                                   name="subbuton"
                                                                   value="Confirm">
                                                            <input type="submit" class="btn btn-primary" name="subbuton"
                                                                   value="Cancel">
                                                        </div>
                                                    </div>


                                                @else

                                                    <div class="col-md-12">
                                                        <div class="form-group"><label for="inputLastName"
                                                                                       class="col-md-2 control-label"><strong>Txd
                                                                    ID:</strong></label>

                                                            <div class="col-md-10"><p
                                                                        class="form-control-static">{{$result->wallet_txid}}</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @endif


                                            </div>
                                        </div>
                                    </div>

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

        $('#confirm_form').validate({
            ignore: [],
            rules:
                {
                    transferred_by: {required: true, minlength: 1, regex: "^(?!.*\\s{2,})(?!^ )[A-Za-z\\s]{1,50}$"},
                    description: {alphanumer: true, regex: "(?!^ +$)^.+$"},
                },
            messages:
                {
                    transferred_by: {
                        required: 'Name is required',
                        minlength: 'Name should contain atleast one alphabet',
                        regex: 'Only alphabets allowed and it should not start with space for the name.'
                    },
                    description: {regex: 'Enter valid remark.'},
                },
            errorPlacement: function (error, element) {
                alert(error.text());
            }
        });

        $.validator.addMethod(
            "regex",
            function (value, element, regexp) {
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            },
            "Number Not valid."
        );

        jQuery.validator.addMethod("alphanumer", function (value, element) {
            return this.optional(element) || /^([a-zA-Z0-9 _-]+)$/.test(value);
        }, 'Does not allow any grammatical connotation, like " : ./');

        function generate_otp() {
            $.ajax({
                type: 'post',
                url: '{{url("check_admin/generate_otp")}}',
                data: 'key={{time()}}&_token={{ csrf_token() }}',
                success: function (data) {
                    $("#trans_otp").html('<a href="#" class="btn btn-info btn-sm">Sent</a>');
                }
            });
        }

        function user_verified() {

            $.get("{{url('ajax/user_verification')}}/{{$result->user_id}}", function (data) {
                if (data == 1) {
                    document.location.reload();
                }
            });
        }
    </script>

    <script type="text/javascript">


        $(document).ready(function () {
            $("#transferred_by_m").keypress(function () {
                $('#transferred_by').val($('#transferred_by_m').val());
            });

            $("#description_m").keypress(function () {
                $('#description').val($('#description_m').val());
            });
        });

        function submit_form() {
            $('#transferred_by').val($('#transferred_by_m').val());
            $('#description').val($('#description_m').val());
            if ($('#confirm_form').valid()) {
                $('#subbuton').val('Confirm');
                $('#confirm_form').submit();
            }
        }
    </script>
@endsection
