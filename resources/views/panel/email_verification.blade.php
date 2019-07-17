@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Email Unverified Users</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">Unverified Users</li>
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


                                <div class="row">
                                    <form id="form_filters" method="get">

                                        <div class="col-md-3">
                                            <lable>Start date</lable>
                                            <input type="text" id="min" name="min"
                                                   value="{{ app('request')->input('min') }}" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <lable>End date</lable>
                                            <input type="text" id="max" name="max"
                                                   value="{{ app('request')->input('max') }}" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <lable>Search</lable>
                                            <input type="text" id="search_id" name="search"
                                                   value="{{ app('request')->input('search') }}" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Status</label>
                                            <select class="form-control" name="status">
                                                <option value="all">All</option>
                                                <option value="1"
                                                        @if(app('request')->input('status')=='1') selected @endif>
                                                    Verified
                                                </option>
                                                <option value="0"
                                                        @if(app('request')->input('status')=='0') selected @endif>
                                                    Unverified
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Paging</label>
                                            <select class="form-control" name="paging">
                                                <option value="25">25</option>
                                                <option value="50"
                                                        @if(app('request')->input('paging')=='50') selected @endif>
                                                    50
                                                </option>
                                                <option value="100"
                                                        @if(app('request')->input('paging')=='100') selected @endif>
                                                    100
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <lable>Email</lable>
                                            <input type="text" id="email_id" name="email"
                                                   value="{{ app('request')->input('email') }}" class="form-control">
                                        </div>
                                        {{--<div class ="col-md-3">--}}
                                        {{--<button style="margin-top: 17px;" class="btn btn-default"><i class="fa fa-search"></i></button>--}}
                                        {{--<a href="{{url('/check_admin/export_user_list')}}" class="btn btn-default" style="margin-top: 17px;"><i class="fa fa-download">CSV</i></a>--}}
                                        {{--</div>--}}

                                        {{csrf_field()}}
                                    </form>
                                </div>

                                <div class="table-container">

                                    <table class="table table-hover table-striped table-bordered table-advanced tablesorter"
                                           id="myTable">
                                        <thead>
                                        <tr>
                                            <th>#Id</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Country</th>
                                            <th>Verification</th>
                                            <th>KYC Status</th>
                                            <th>Status</th>
                                            <th>Updated date</th>
                                            <th>Action</th>

                                        </tr>
                                        <tbody>
                                        @if($result)
                                            @foreach($result as $val)
                                                <tr>
                                                    <td>{{$val->id}}</td>
                                                    {{--<td><a class="form-control-static"target="_blank" href="{{url('check_admin/user_transaction_details?user_id='.$val->id)}}">--}}
                                                    {{--{{$val->enjoyer_name}}</a></td>--}}
                                                    <td>@if($val->enjoyer_name==''){{get_usermail($val->id)}}@else{{$val->enjoyer_name}}@endif</td>
                                                    <td>{{get_usermail($val->id)}}</td>
                                                    <td>{{$val->country}}</td>
                                                    <td>
                                                        @if($val->user_verified == 1 )
                                                            <p class="label label-success"><a href="" id="user_verified"
                                                                                              style="color: white"
                                                                                              onclick="user_verified({{$val->id}})">Verified</a>
                                                            </p>
                                                        @else
                                                            <p class="label label-danger"><a href="" id="user_verified"
                                                                                             style="color: white"
                                                                                             onclick="user_verified({{$val->id}})">Unverified</a>
                                                            </p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($val->document_status==0)
                                                            <p class="label label-warning">Pending</p>
                                                        @elseif($val->document_status==1)
                                                            <p class="label label-success">Verified</p>
                                                        @elseif($val->document_status==3)
                                                            <p class="label label-info">Submitted</p>
                                                        @else
                                                            <p class="label label-danger">Rejected</p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($val->status==1)
                                                            <p class="label label-success">Active</p>
                                                        @else
                                                            <p class="label label-danger">Deactive</p>
                                                        @endif
                                                    </td>
                                                    <td>{{$val->created_at}}</td>
                                                    <td>
                                                        <a href="{{url('check_admin/create_email_verification/'.$val->id)}}"
                                                           title="send verification email"><i class="fa fa-cog"></i></a>
                                                        {{--<a href="{{url('check_admin/view_users/'.$val->id)}}" title="view"><i class="fa fa-eye"></i></a>--}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        </thead></table>

                                </div>

                                <div class="row">
                                    <div class="col-lg-6">

                                    </div>
                                    <div class="col-lg-6 text-right">
                                        <div class="pagination-panel">
                                            @include('panel.pagination', ['paginator' => $result])
                                            {{--<a href="{{ $result->appends(array('min' => Request::get('min'),--}}
                                            {{--'max'=> Request::get('max'),'currency' => Request::get('currency'),'status' => Request::get('status'),'search' => Request::get('search')))->url(4)}}"><input value="4"></a>--}}
                                            {{--<ul class="pagination pagination-sm man">--}}


                                            {{--</ul>--}}

                                        </div>
                                    </div>
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
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#myTable').DataTable({
                "paging": false,
                "ordering": true,
                "info": false,
                "searching": false,
            });
        });
    </script>

    <script type="text/javascript">
        function user_verified(user_id) {
            $.get("{{url('ajax/user_verification')}}/" + user_id, function (data) {
                if (data == 1) {
                    document.location.reload();
                }
            });
        }
    </script>

    <link rel="stylesheet" href="{{URL::asset('datepicker/jquery-ui.css')}}">
    <!-- <script src="{{URL::asset('datepicker/jquery-1.12.4.js')}}"></script> -->
    <script src="{{URL::asset('datepicker/jquery-ui.js')}}"></script>
    <script>
        $(function () {
            $("#max,#min").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'yy-mm-dd',
                onSelect: function (selectedDate) {
                    if (this.id == 'min') {
                        var dateMin = $('#min').datepicker("getDate");
                        var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(), dateMin.getDate() + 1);
                        $('#max').datepicker("option", "minDate", rMin);
                    }

                }
            });
        });
    </script>
@endsection
