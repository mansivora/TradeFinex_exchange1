@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Pending History</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">Pending History</li>
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
                                                <option value="active"
                                                        @if(app('request')->input('status')=='active') selected @endif>
                                                    Active
                                                </option>
                                                <option value="partially"
                                                        @if(app('request')->input('status')=='partially') selected @endif>
                                                    Partially
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <lable>Pair</lable>
                                            <select class="form-control" name="pair">
                                                <option value="all">All</option>
                                                @foreach($pairs as $val)
                                                    <option value="{{$val->pair}}"
                                                            @if(app('request')->input('pair') == $val->pair) selected @endif>{{$val->pair}}</option>
                                                @endforeach

                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <lable>Type</lable>
                                            <select class="form-control" name="type">
                                                <option value="all">All</option>
                                                <option value="Sell"
                                                        @if(app('request')->input('type')=='Sell') selected @endif>
                                                    Sell
                                                </option>
                                                <option value="Buy"
                                                        @if(app('request')->input('type')=='Buy') selected @endif>
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
                                    <div class="col-md-3">
                                        <button class="pull-right" id="cancel_button"
                                                style="margin-top:35px; display:none;" onclick="cancel_multiple()">
                                            Cancel Trades
                                        </button>
                                        <button class="pull-right" id="checknone"
                                                style="margin-top:35px; margin-right:5px; display:none;"
                                                onclick="check_none()">None
                                        </button>
                                        <button class="pull-right" id="checkall"
                                                style="margin-top:35px; margin-right:5px; display:none;"
                                                onclick="check_all()">All
                                        </button>
                                    </div>
                                </div>


                                <div class="table-container">

                                    <table class="table table-hover table-striped table-bordered table-advanced tablesorter"
                                           id="myTable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
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
                                                    <td>{{$val->id}}</td>
                                                    <td>{{$val->user_id}}</td>
                                                    <td>@if(get_user_details($val->user_id,'enjoyer_name')==''){{get_usermail($val->user_id)}}@else{{get_user_details($val->user_id,'enjoyer_name')}}@endif</td>
                                                    <td>{{number_format($val->updated_qty,2,'.','')}}</td>
                                                    <td>{{number_format($val->price,8,'.','')}}</td>
                                                    <td>{{number_format($val->fee,8,'.','')}}</td>
                                                    <td>{{number_format($val->total,4,'.','')}}</td>
                                                    <td>{{$val->type}}</td>
                                                    <td>{{$val->firstCurrency}}</td>
                                                    <td>{{$val->secondCurrency}}</td>
                                                    <td>{{$val->status}}</td>
                                                    <td>{{$val->updated_at}}</td>
                                                    <td><a href="{{url('check_admin/cancel_trade')}}/{{$val->id}}"
                                                           onclick="return confirm('Are you sure want to cancel the order ?')"><i
                                                                    class="fa fa-times"></i></a>&nbsp;&nbsp;<input
                                                                class="checkbox" type='checkbox' name='checked[]'
                                                                id="checkbox{{$key}}" value="{{$val->id}}"
                                                                onclick="check_selected()" ">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        </thead>

                                    </table>

                                </div>

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
                    <a id="cancel_confirm" class="btn btn-danger" onclick="cancel_trade()">Ok</a>
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
                "ordering": true,
                "info": false
            });

        });

    </script>

    {{--<script type="text/javascript">--}}
    {{--var url_address = document.getElementById('url_addrs').value;--}}
    {{--var link_tag = document.getElementById('cancel_confirm');--}}

    {{--var cancel_id = "";--}}
    {{--$(function(){--}}
    {{--$(".open-confirmModal").click(function(){--}}
    {{--cancel_id = $(this).data('id');--}}
    {{--link_tag.setAttribute("href",url_address+"/"+cancel_id);--}}

    {{--});--}}
    {{--});--}}

    {{--</script>--}}


    <script type="text/javascript">
        function check_all() {
            var chk_arr = document.getElementsByName("checked[]");
            for (k = 0; k < chk_arr.length; k++) {
                chk_arr[k].checked = true;
            }
            check_selected();
        }

        function check_none() {
            var chk_arr = document.getElementsByName("checked[]");
            for (k = 0; k < chk_arr.length; k++) {
                chk_arr[k].checked = false;
            }
            check_selected();
        }

        function checkany() {
            var chk_arr = document.getElementsByName("checked[]");
            for (k = 0; k < chk_arr.length; k++) {
                if (chk_arr[k].checked == true) {
                    return true;
                }
            }
            return false;
        }

        function show() {
            document.getElementById('cancel_button').style.display = "block";
            document.getElementById('checkall').style.display = "block";
            document.getElementById('checknone').style.display = "block";
        }

        function hide() {
            document.getElementById('cancel_button').style.display = "none";
            document.getElementById('checkall').style.display = "none";
            document.getElementById('checknone').style.display = "none";
        }


        function check_selected() {
            checkany() ? show() : hide();
        }

        function cancel_multiple() {
            var chk_arr = document.getElementsByName("checked[]");
            var id = new Array();
            var i = 0;
            for (var k = 0; k < chk_arr.length; k++) {
                if (chk_arr[k].checked == true) {
                    id[i] = chk_arr[k].value;
                    i++;
                }
            }
            if (confirm("Are you sure you want to cancel the trades with the following id:\n" + id.join("\n"))) {
                window.location.href = "/check_admin/cancel_multiple/" + encodeURIComponent(JSON.stringify(id));
            }
        }

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