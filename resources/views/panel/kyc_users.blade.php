@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">KYC Status</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">KYC</li>
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
                                            <lable>Status</lable>
                                            <select class="form-control" name="status">
                                                <option value="all">All</option>
                                                <option value="1"
                                                        @if(app('request')->input('status')=='1') selected @endif>
                                                    Verified
                                                </option>
                                                <option value="3"
                                                        @if(app('request')->input('status')=='3') selected @endif>
                                                    Pending
                                                </option>
                                                <option value="2"
                                                        @if(app('request')->input('status')=='2') selected @endif>
                                                    Rejected
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button style="margin-top: 17px;" class="btn btn-default"><i
                                                        class="fa fa-search"></i></button>
                                        </div>
                                        {{csrf_field()}}
                                    </form>
                                </div>

                                <div class="table-container">

                                    <table class="table table-hover table-striped table-bordered table-advanced tablesorter"
                                           id="myTable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User ID</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>KYC Status</th>

                                            <th>Date time</th>

                                            <th>Action</th>


                                        </tr>
                                        <tbody>
                                        @if($result)
                                            @foreach($result as $key=>$val)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$val->user_id}}</td>
                                                    <td>{{$val->first_name}}</td>
                                                    <td>{{get_usermail($val->user_id)}}</td>
                                                    <td>
                                                        @if($val->document_status==3)
                                                            <p class="label label-warning">Pending</p>
                                                        @elseif($val->document_status==1)
                                                            <p class="label label-success">Verified</p>
                                                        @else
                                                            <p class="label label-danger">Rejected</p>
                                                        @endif
                                                    </td>
                                                    <td>{{$val->updated_at}}</td>
                                                    <td>
                                                        <a href="{{url('check_admin/view_kyc/'.$val->id)}}" title="view"><i
                                                                    class="fa fa-eye"></i></a>
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
                                    {{--<div class="col-lg-6 text-right">--}}
                                    {{--<div class="pagination-panel">--}}
                                    {{--@include('panel.pagination', ['paginator' => $result])--}}

                                    {{--</div>--}}
                                    {{--</div>--}}
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
                "paging": true,
                "ordering": true,
                "info": false,
                "lengthChange": false,
                "pageLength": 25
            });
        });

    </script>

    <link rel="stylesheet" href="{{URL::asset('datepicker/jquery-ui.css')}}">
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