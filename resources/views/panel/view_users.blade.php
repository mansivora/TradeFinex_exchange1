@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Users</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">Users</li>
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

                                <form class="form-horizontal" id="form">
                                    <h3>User Details: </h3>
                                    <div class="panel-body pan">
                                        <div class="form-body pal">


                                            <div class="row">


                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="col-md-2 control-label"><strong>Email:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{ get_usermail($id) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>


                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="col-md-2 control-label"><strong>User
                                                                Name:</strong></label>

                                                        <div class="col-md-10"><p class="form-control-static">
                                                                {{$result->enjoyer_name}}
                                                            </p></div>
                                                    </div>
                                                </div>
                                                <br>


                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="col-md-2 control-label"><strong>Full
                                                                Name:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->first_name}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>


                                                {{--<div class="col-md-12">--}}
                                                {{--<div class="form-group"><label for="inputLastName"--}}
                                                {{--class="col-md-2 control-label"><strong>Last--}}
                                                {{--Name:</strong></label>--}}

                                                {{--<div class="col-md-10"><p--}}
                                                {{--class="form-control-static">{{$result->last_name}}</p>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}
                                                {{--<br>--}}

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Created
                                                                date:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->created_at}}</p>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Status:</strong></label>

                                                        <div class="col-md-10"><p class="form-control-static">
                                                                @if($result->status==1)
                                                                    Active
                                                                @else
                                                                    Deactive
                                                                @endif
                                                            </p></div>
                                                    </div>
                                                </div>


                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Image:</strong></label>

                                                        <div class="col-md-10">
                                                            <img src="{{URL::asset('uploads/users/profileimg')}}/{{$result->profile_image}}"
                                                                 style="width: 150px;height: 150px;"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>IP
                                                                Address:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->ip}}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Country:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{get_country_name($result->country)}}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{--<div class="col-md-12">--}}
                                                {{--<div class="form-group"><label for="inputLastName"--}}
                                                {{--class="col-md-2 control-label"><strong>Postal--}}
                                                {{--Code:</strong></label>--}}

                                                {{--<div class="col-md-10"><p--}}
                                                {{--class="form-control-static">{{$result->postal_code}}</p>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Mobile:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{owndecrypt($result->mobile_no)}}</p>
                                                        </div>
                                                    </div>
                                                </div>


                                                <h3>KYC Status: </h3>
                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>KYC
                                                                status:</strong></label>

                                                        <div class="col-md-10"><p class="form-control-static">
                                                            @if($result->document_status==3)
                                                                <p class="label label-info">Submitted</p>
                                                            @elseif($result->document_status==1)
                                                                <p class="label label-success">Verified</p>
                                                            @elseif($result->document_status==2)
                                                                <p class="label label-danger">Rejected</p>
                                                            @else
                                                                <p class="label label-warning">Pending</p>
                                                                @endif
                                                                </p>
                                                        </div>
                                                    </div>
                                                </div>


                                                <h3>User Balance: </h3>


                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="inputLastName"
                                                               class="col-md-2 control-label"><strong>BTC:</strong>
                                                        </label>

                                                        <div class="col-md-10"><p class="form-control-static">
                                                                {{get_userbalance($id,'BTC')}}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="inputLastName"
                                                               class="col-md-2 control-label"><strong> Verified
                                                                BTC:</strong>
                                                        </label>

                                                        <div class="col-md-10"><p class="form-control-static">
                                                                {{$BTC_Bal}}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{--<div class="col-md-6">--}}
                                                {{--<div class="form-group">--}}
                                                {{--<label for="inputLastName"--}}
                                                {{--class="col-md-2 control-label"><strong>BCH:</strong>--}}
                                                {{--</label>--}}

                                                {{--<div class="col-md-10"><p class="form-control-static">--}}
                                                {{--{{get_userbalance($id,'BCH')}}--}}
                                                {{--</p>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}
                                                {{--<div class="col-md-6">--}}
                                                {{--<div class="form-group">--}}
                                                {{--<label for="inputLastName"--}}
                                                {{--class="col-md-2 control-label"><strong> Verified BCH:</strong>--}}
                                                {{--</label>--}}

                                                {{--<div class="col-md-10"><p class="form-control-static">--}}
                                                {{--{{$BCH_Bal}}--}}
                                                {{--</p>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}


                                                {{--<div class="col-md-6">--}}
                                                {{--<div class="form-group"><label for="inputLastName"--}}
                                                {{--class="col-md-2 control-label"><strong>XRP:</strong></label>--}}

                                                {{--<div class="col-md-10"><p class="form-control-static">--}}
                                                {{--{{get_userbalance($id,'XRP')}}--}}
                                                {{--</p></div>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}
                                                {{--<div class="col-md-6">--}}
                                                {{--<div class="form-group"><label for="inputLastName"--}}
                                                {{--class="col-md-2 control-label"><strong>Verified XRP:</strong></label>--}}

                                                {{--<div class="col-md-10"><p class="form-control-static">--}}
                                                {{--{{$XRP_Bal}}--}}
                                                {{--</p></div>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}

                                                <div class="col-md-6">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>ETH:</strong></label>

                                                        <div class="col-md-10"><p class="form-control-static">
                                                                {{get_userbalance($id,'ETH')}}
                                                            </p></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Verified
                                                                ETH:</strong></label>

                                                        <div class="col-md-10"><p class="form-control-static">
                                                                {{$ETH_Bal}}
                                                            </p></div>
                                                    </div>
                                                </div>


                                                <div class="col-md-6">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>USDT:</strong></label>

                                                        <div class="col-md-10"><p class="form-control-static">
                                                                {{get_userbalance($id,'USDT')}}
                                                            </p></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Verified
                                                                USDT:</strong></label>

                                                        <div class="col-md-10"><p class="form-control-static">
                                                                {{$USDT_Bal}}
                                                            </p></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>XRP:</strong></label>

                                                        <div class="col-md-10"><p class="form-control-static">
                                                                {{get_userbalance($id,'XRP')}}
                                                            </p></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Verified
                                                                XRP:</strong></label>

                                                        <div class="col-md-10"><p class="form-control-static">
                                                                {{$XRP_Bal}}
                                                            </p></div>
                                                    </div>
                                                </div>


                                                <h3>User Addresses: </h3>
                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>BTC:</strong></label>

                                                        <div class="col-md-10"><p class="form-control-static">
                                                                {{$addresses['BTC']}}<br>
                                                                {{--<a href="{{url('ajax/btc_deposit_process_user/'.$result->BTC_addr)}}" style="color: red;">--}}
                                                                {{--Click here--}}
                                                                {{--</a>to manually deposit <strong>BTC</strong>.--}}
                                                            </p></div>
                                                    </div>
                                                </div>

                                                {{--<div class="col-md-12">--}}
                                                {{--<div class="form-group"><label for="inputLastName"--}}
                                                {{--class="col-md-2 control-label"><strong>BCH:</strong></label>--}}

                                                {{--<div class="col-md-10"><p class="form-control-static">--}}
                                                {{--{{$addresses['BCH']}}--}}
                                                {{--</p></div>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}


                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>ETH:</strong></label>

                                                        <div class="col-md-10"><p class="form-control-static">
                                                                {{$addresses['ETH']}}<br>
                                                                <a href="{{url('cron/eth_deposit_process_user/'.$id)}}"
                                                                   style="color: red;">
                                                                    Click here
                                                                </a>to manually deposit <strong>ETH</strong>.
                                                            </p></div>
                                                    </div>
                                                </div>

                                                {{--<div class="col-md-12">--}}
                                                {{--<div class="form-group"><label for="inputLastName"--}}
                                                {{--class="col-md-2 control-label"><strong>XRP--}}
                                                {{--:</strong></label>--}}

                                                {{--<div class="col-md-10"><p class="form-control-static">--}}
                                                {{--{{$result->XRP_addr}}--}}
                                                {{--</p></div>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}

                                                {{--<div class="col-md-12">--}}
                                                {{--<div class="form-group"><label for="inputLastName"--}}
                                                {{--class="col-md-2 control-label"><strong>XRP Destination Tag--}}
                                                {{--:</strong></label>--}}

                                                {{--<div class="col-md-10"><p class="form-control-static">--}}
                                                {{--{{$addresses['XRP']}}--}}
                                                {{--</p></div>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>USDT
                                                                :</strong></label>

                                                        <div class="col-md-10"><p class="form-control-static">
                                                                {{$addresses['USDT']}}<br>
                                                            </p></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>XRP
                                                                :</strong></label>

                                                        <div class="col-md-10"><p class="form-control-static">
                                                                {{$addresses['XRP']}}<br>
                                                                {{--<a href="{{url('cron/xdce_deposit_process_user/'.$addresses['XRP'])}}"--}}
                                                                   {{--style="color: red;">--}}
                                                                    {{--Click here--}}
                                                                {{--</a>to manually deposit <strong>XRP</strong>.--}}
                                                            </p></div>
                                                    </div>
                                                </div>
                                                @if(Session::get('alpha_id') == "1")
                                                    <button class="btn btn-danger pull-right" id="delete"
                                                            data-toggle="modal" data-target="#myModal">Delete Account
                                                    </button>
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
    <div class="clearfix"></div>
    <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="color: red;">
                    <h4><strong>Delete Account!</strong></h4>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this Account?
                    <br>
                    <br>
                    <strong><p style="color: red;">Note : Account once deleted can't be recovered. So please be sure
                            about deleting the account.</p></strong>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                    <button class="btn btn-success success" onclick="confirmation()">Yes</button>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('script')

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>

        $("#form").submit(function () {
            event.preventDefault();
        });

        function confirmation() {
            var user_id = '{{$id}}';
            user_id = btoa(user_id);
            $.ajax({
                url: '/check_admin/deleteaccount',
                method: 'post',
                data: {'user_id': user_id},
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.status == '1') {
                        window.location.href = "{{URL::to('/check_admin/users')}}";
                    }
                    else {
                        alert('Server Error');
                    }
                }
            });
        }
    </script>

@endsection
