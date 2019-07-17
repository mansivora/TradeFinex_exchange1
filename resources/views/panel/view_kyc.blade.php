@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">User KYC Details</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">KYC Details</li>
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

                                <form class="form-horizontal" method="post"
                                      action="{{url('check_admin/view_kyc/'.$id)}}">
                                    {{ csrf_field() }}
                                    <h3>KYC Details: </h3>
                                    <div class="panel-body pan">
                                        <div class="form-body pal">


                                            <div class="row">

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="col-md-2 control-label"><strong>User
                                                                ID:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{ $result->user_id }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>


                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="col-md-2 control-label"><strong>Email:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{ get_usermail($result->user_id) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>


                                                {{--<div class="col-md-12">--}}
                                                {{--<div class="form-group"><label for="inputFirstName" class="col-md-2 control-label"><strong>User Name:</strong></label>--}}

                                                {{--<div class="col-md-10"><p class="form-control-static">--}}
                                                {{--{{$result->enjoyer_name}}--}}
                                                {{--</p></div>--}}
                                                {{--</div>--}}
                                                {{--</div> <br>--}}


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
                                                {{--<div class="form-group"><label for="inputLastName" class="col-md-2 control-label"><strong>Last Name:</strong></label>--}}

                                                {{--<div class="col-md-10"><p class="form-control-static">{{$result->last_name}}</p></div>--}}
                                                {{--</div>--}}
                                                {{--</div> <br>--}}

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Country:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{get_country_name($result->country_code)}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label
                                                                class="col-md-2 control-label"><strong>National ID Card
                                                                No:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->national_id}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label
                                                                class="col-md-2 control-label"><strong>Date of
                                                                Birth</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->dob}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label
                                                                class="col-md-2 control-label"><strong>Mobile
                                                                No:</strong></label>

                                                        <div class="col-md-10"><p class="form-control-static">
                                                                ({{$result->mob_isd}}
                                                                ) {{owndecrypt($result->mobile_no)}}</p></div>
                                                    </div>
                                                </div>
                                                <br>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label
                                                                class="col-md-2 control-label"><strong>Gender</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->gender}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Updated
                                                                date:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->updated_at}}</p>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>User
                                                                Status:</strong></label>

                                                        <div class="col-md-10"><p class="form-control-static">
                                                                @if($result->status==1)
                                                                    Active
                                                                @else
                                                                    Deactive
                                                                @endif
                                                            </p></div>
                                                    </div>
                                                </div>


                                                <h3>KYC Status: </h3>
                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>KYC
                                                                status:</strong></label>

                                                        <div class="col-md-10 form-control-static">
                                                            @if($result->document_status==3)
                                                                <p class="label label-warning">Pending</p>
                                                            @elseif($result->document_status==1)
                                                                <p class="label label-success">Verified</p>
                                                            @else
                                                                <p class="label label-danger">Rejected</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Proof
                                                                1:</strong></label>

                                                        <div class="col-md-6"><p class="form-control-static">
                                                                <img src="https://alphaex.net/src.php?name={{$result->proof1}}"
                                                                     style="width: 150px;height: 100px;" id="proof1"
                                                                     data-zoom-image="https://alphaex.net/src.php?name={{$result->proof1}}">
                                                            </p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="checkbox" name="proof1_status"
                                                                   @if($result->proof1_status==1) Checked @endif >
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Proof
                                                                2:</strong></label>

                                                        <div class="col-md-6"><p class="form-control-static">
                                                                <img src="https://alphaex.net/src.php?name={{$result->proof2}}"
                                                                     style="width: 150px;height: 100px;" id="proof2"
                                                                     data-zoom-image="https://alphaex.net/src.php?name={{$result->proof2}}">
                                                            </p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="checkbox" name="proof2_status"
                                                                   @if($result->proof2_status==1) Checked @endif >
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Proof
                                                                3:</strong></label>

                                                        <div class="col-md-6"><p class="form-control-static">
                                                                <img src="https://alphaex.net/src.php?name={{$result->proof3}}"
                                                                     style="width: 150px;height: 100px;" id="proof3"
                                                                     data-zoom-image="https://alphaex.net/src.php?name={{$result->proof3}}">
                                                            </p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="checkbox" name="proof3_status"
                                                                   @if($result->proof3_status==1) Checked @endif >
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Over
                                                                All Status:</strong></label>

                                                        <div class="col-md-6">
                                                            <p class="form-control-static">
                                                                <select class="form-control" name="kycstatus"
                                                                        onchange="kycscr(this.value)">
                                                                    <option value="0"
                                                                            @if($result->document_status==0)selected @endif>
                                                                        Pending
                                                                    </option>
                                                                    <option value="1"
                                                                            @if($result->document_status==1)selected @endif >
                                                                        Approve
                                                                    </option>
                                                                    <option value="2"
                                                                            @if($result->document_status==2)selected @endif>
                                                                        Reject
                                                                    </option>
                                                                    <option value="3"
                                                                            @if($result->document_status==3)selected @endif>
                                                                        Submitted
                                                                    </option>
                                                                </select>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="kyc_reason" style="display: none;">
                                                    <div class="col-md-12">
                                                        <div class="form-group"><label for="inputLastName"
                                                                                       class="col-md-2 control-label"><strong>Reason:</strong></label>

                                                            <div class="col-md-6"><p class="form-control-static">
                                                                    <textarea class="form-control"
                                                                              name="kycreason"></textarea>
                                                                </p></div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <small>Note: If you have approve please select proof checkbox</small>

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
    <script type="text/javascript">
        $("#proof1").elevateZoom();
        $("#proof2").elevateZoom();
        $("#proof3").elevateZoom();

        function kycscr(id) {
            if (id == 2) {
                $("#kyc_reason").show();
            }
            else {
                $("#kyc_reason").hide();
            }
        }
    </script>
@endsection

