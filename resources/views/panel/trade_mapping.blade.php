@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Trade Mapping</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">Trade Mapping</li>
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
                                            <a style="margin-top: 17px;" class="btn btn-default"
                                               href="{{url('/check_admin/trade_history/')}}"><i
                                                        class="fa fa-refresh"></i></a>&nbsp;
                                            <button style="margin-top: 17px;" class="btn btn-default"><i
                                                        class="fa fa-search"></i></button>
                                        </div>
                                        {{csrf_field()}}
                                    </form>
                                </div>
                                <br><br>
                                <div class="table-container">

                                    <table class="table table-hover table-striped table-bordered table-advanced tablesorter"
                                           id="myTable">
                                        <thead>
                                        <tr>
                                            <th>Sr no.</th>
                                            <th>Transaction ID</th>
                                            <th>Pair</th>
                                            <th>Buyer</th>
                                            <th>Seller</th>
                                            <th>Type</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>Updated time</th>
                                        </tr>
                                        <tbody>
                                        @if($result)
                                            @foreach($result as $key=>$val)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$val->unique_id}}</td>
                                                    <td>{{$val->pair}}</td>
                                                    <td>{{get_name($val->buy_trade_order_id)}}</td>
                                                    <td>{{get_name($val->sell_trade_order_id)}}</td>
                                                    <td>{{$val->type}}</td>
                                                    <td>{{number_format($val->triggered_price,'8','.','')}}</td>
                                                    <td>{{number_format($val->triggered_qty,'2','.','')}}</td>
                                                    <td>{{number_format($val->total,'8','.','')}}</td>
                                                    <td>{{$val->updated_at}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        </thead>

                                    </table>

                                </div>
                                {{--<div class="row">--}}
                                {{--<div class="col-lg-6">--}}

                                {{--</div>--}}
                                {{--<div class="col-lg-6 text-right">--}}
                                {{--<div class="pagination-panel">--}}
                                {{--@include('panel.pagination', ['paginator' => $result])--}}

                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}
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
                "pageLength": 25,
                "order": [[9, "desc"]]
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