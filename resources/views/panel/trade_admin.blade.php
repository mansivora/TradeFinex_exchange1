@extends("panel.layout.trade_admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Trades Verification</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/pending_history')}}">Pending transactions</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </li>

            <li class="active"><a href="{{url('check_admin/tradeadmin')}}">New Users</a></li>
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
                                <input type="hidden" value="{{url('/check_admin/cancel_trade')}}" id="url_addrs">

                                <div class="row">
                                    @if($status == 2)
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
                                                       value="{{ app('request')->input('search') }}"
                                                       class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                <lable>Status</lable>
                                                <select class="form-control" name="status">
                                                    <option value="all">All</option>
                                                    <option value="active"
                                                            @if(app('request')->input('status')=='active') selected @endif>
                                                        Active
                                                    </option>
                                                    <option value="partially"
                                                            @if(app('request')->input('status')=='partially') selected @endif>
                                                        Partially
                                                    </option>
                                                    <option value="completed"
                                                            @if(app('request')->input('status')=='completed') selected @endif>
                                                        Completed
                                                    </option>

                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <lable>Pair</lable>
                                                <select class="form-control" name="pair">
                                                    <option value="all">All</option>
                                                    <option value="CMB-ETH"
                                                            @if(app('request')->input('pair')=='CMB-ETH') selected @endif>
                                                        CMB-ETH
                                                    </option>
                                                    <option value="CMB-BTC"
                                                            @if(app('request')->input('pair')=='CMB-BTC') selected @endif>
                                                        CMB-BTC
                                                    </option>
                                                    <option value="CMB-XDCE"
                                                            @if(app('request')->input('pair')=='CMB-XDCE') selected @endif>
                                                        CMB-XDCE
                                                    </option>

                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <lable>Type</lable>
                                                <select class="form-control" name="type">
                                                    <option value="all">All</option>
                                                    <option value="Sell"
                                                            @if(app('request')->input('pair')=='Sell') selected @endif>
                                                        Sell
                                                    </option>
                                                    <option value="Buy"
                                                            @if(app('request')->input('pair')=='Buy') selected @endif>
                                                        Buy
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <a style="margin-top: 17px;" class="btn btn-default"
                                                   href="{{url('/check_admin/pending_history/')}}"><i
                                                            class="fa fa-refresh"></i></a>&nbsp;
                                                <button style="margin-top: 17px;" class="btn btn-default"><i
                                                            class="fa fa-search"></i></button>
                                            </div>
                                            {{csrf_field()}}
                                        </form>
                                    @endif

                                </div>


                                @if($status==2)
                                    <div class="table-container">

                                        <table class="table table-hover table-striped table-bordered table-advanced tablesorter"
                                               id="pendingTable">
                                            <thead>
                                            <tr>
                                                <th>User ID</th>
                                                <th>Username</th>
                                                <th>Amount</th>
                                                <th>Price</th>
                                                <th>Fee</th>
                                                <th>Total</th>
                                                <th>Type</th>
                                                <th>First Currency</th>
                                                <th>Second Currency</th>
                                                <th>Status</th>
                                                <th>Updated time</th>
                                                <th>Action</th>


                                            </tr>
                                            <tbody>
                                            @if($result)
                                                @foreach($result as $key=>$val)
                                                    <tr>

                                                        <td>{{$val->user_id}}</td>
                                                        <td>
                                                            <a href="{{url('/check_admin/view_users/'.$val->user_id)}}">@if(get_user_details($val->user_id,'enjoyer_name')==''){{get_usermail($val->user_id)}}@else{{get_user_details($val->user_id,'enjoyer_name')}}@endif</a>
                                                        </td>
                                                        <td>{{$val->Amount}}</td>
                                                        <td>{{$val->Price}}</td>
                                                        <td>{{$val->Fee}}</td>
                                                        <td>{{$val->Total}}</td>
                                                        <td>{{$val->Type}}</td>
                                                        <td>{{$val->firstCurrency}}</td>
                                                        <td>{{$val->secondCurrency}}</td>
                                                        <td>{{$val->status}}</td>
                                                        <td>{{$val->updated_at}}</td>
                                                        <td>&nbsp; <a data-toggle="modal" data-target="#confirm_modal"
                                                                      class="open-confirmModal"
                                                                      data-id="{{$val->id}}"><i class="fa fa-times"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                            </thead>

                                        </table>

                                    </div>
                                @endif

                                @if($status==1)
                                    <div class="table-container">

                                        <table class="table table-hover table-striped table-bordered table-advanced tablesorter"
                                               id="userTable">
                                            <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>BTC</th>
                                                <th>ETH</th>
                                                <th>XDC</th>
                                                <th>XRP</th>
                                                <th>Status</th>
                                                <th>Created date</th>


                                            </tr>
                                            <tbody>
                                            @if($result)
                                                @foreach($result as $key=>$val)
                                                    <tr>
                                                        <td>{{$val->enjoyer_name}}</td>
                                                        <td>{{get_usermail($val->id)}}</td>
                                                        <td>@if($val->BTC_addr=="")
                                                                <p class="label label-danger">Deactive</p>
                                                            @else
                                                                <p class="label label-success">Active</p>
                                                            @endif
                                                        </td>
                                                        <td>@if($val->ETH_addr=="")
                                                                <p class="label label-danger">Deactive</p>
                                                            @else
                                                                <p class="label label-success">Active</p>
                                                            @endif
                                                        </td>
                                                        <td>@if($val->XDC_addr=="")
                                                                <p class="label label-danger">Deactive</p>
                                                            @else
                                                                <p class="label label-success">Active</p>
                                                            @endif
                                                        </td>
                                                        <td>@if($val->XRP_addr=="")
                                                                <p class="label label-danger">Deactive</p>
                                                            @else
                                                                <p class="label label-success">Active</p>
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
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                            </thead>

                                        </table>

                                    </div>
                                @endif


                                <div class="row">
                                    <div class="col-lg-6">

                                    </div>
                                    <div class="col-lg-6 text-right">
                                        <div class="pagination-panel">
                                            @include('panel.pagination', ['paginator' => $result])

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

    <!-- Modal -->
    <div id="confirm_modal" class="modal danger fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><strong>Cancel Trade</strong></h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel this trade.?</p>
                </div>
                <div class="modal-footer">
                    <a id="cancel_confirm" class="btn btn-danger">Ok</a>
                    <button type="button" class="btn btn-blue" data-dismiss="modal">Close</button>
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
                "searching": false,
                "paging": false,
                "ordering": false,
                "info": false
            });
            $('#myTable').DataTable({
                "searching": false,
                "paging": false,
                "ordering": false,
                "info": false
            });
        });

    </script>

    <script type="text/javascript">
        var url_address = document.getElementById('url_addrs').value;
        var link_tag = document.getElementById('cancel_confirm');
        var cancel_id = "";
        $(function () {
            $(".open-confirmModal").click(function () {
                cancel_id = $(this).data('id');
                link_tag.setAttribute("href", url_address + "/" + cancel_id);

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