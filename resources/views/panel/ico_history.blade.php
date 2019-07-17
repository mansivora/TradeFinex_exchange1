@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">ICO History</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">ICO History</li>
        </ol>
        <div class="clearfix"></div>
    </div>


    <div class="page-content">
        <div class="row">
            <div class="col-md-12">


                @include('panel.alert')

                {{--for ico stats--}}
                {{--ico xdce amount--}}

                <div class="col-sm-6 col-md-6">
                    <br>
                    <div class="panel db mbm" style="cursor: pointer;">
                        <div class="panel-body" data-toggle="modal" data-target="#update_price">
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="text-align: center"><i class="fa font50"><img
                                                    src="{{URL::asset('front')}}/assets/icons/XDCE.png"
                                                    class="stat-icon" style="width: 50px;height: 50px;"></i></div>
                                    <div style="text-align: center"><p>
                                        <h2>{{$stats['Total']}}</h2></p>
                                        <p class="description "><strong>ICO Purchased XDCE</strong></p></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label><strong>ETH Price:&nbsp;</strong>{{$price['ETH']}}</label>
                                </div>
                                <div class="col-md-12">
                                    <label><strong>BTC Price:&nbsp;</strong>{{$price['BTC']}}</label>
                                </div>
                                <div class="col-md-12">
                                    <label><strong>BCH Price:&nbsp;</strong>{{$price['BCH']}}</label>
                                </div>
                                <div class="col-md-12">
                                    <label><strong>XRP Price:&nbsp;</strong>{{$price['XRP']}}</label>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>

                {{--for ico stats--}}
                <div class="col-sm-6 col-md-6">
                    <br>
                    <div class="panel db mbm" style="cursor: pointer;">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="text-align: center"><i class="fa font50"><img
                                                    src="{{URL::asset('front')}}/assets/icons/stats.png"
                                                    class="stat-icon" style="width: 50px;height: 50px;"></i></div>
                                    <div style="text-align: center"><p>
                                        <h2>{{$stats['USD']}}$</h2></p>
                                        <p class="description "><strong>ICO Purchased Stats</strong></p></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label><strong>ETH :&nbsp;</strong>{{$stats['ETH']}}</label>
                                </div>
                                <div class="col-md-12">
                                    <label><strong>BTC :&nbsp;</strong>{{$stats['BTC']}}</label>
                                </div>
                                <div class="col-md-12">
                                    <label><strong>BCH :&nbsp;</strong>{{$stats['BCH']}}</label>
                                </div>
                                <div class="col-md-12">
                                    <label><strong>XRP :&nbsp;</strong>{{$stats['XRP']}}</label>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>


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
                                            <lable>Status</lable>
                                            <select class="form-control" name="status">
                                                <option value="all">All</option>

                                                <option value="Completed"
                                                        @if(app('request')->input('status')=='Completed') selected @endif>
                                                    Completed
                                                </option>
                                                <option value="Pending"
                                                        @if(app('request')->input('status')=='Pending') selected @endif>
                                                    Pending
                                                </option>
                                                <option value="Cancelled"
                                                        @if(app('request')->input('status')=='Cancelled') selected @endif>
                                                    Cancelled
                                                </option>
                                            </select>
                                        </div>


                                        <div class="col-md-3">
                                            <a style="margin-top: 17px;" class="btn btn-default"
                                               href="{{url('/check_admin/ico_history/')}}"><i
                                                        class="fa fa-refresh"></i></a>&nbsp;
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
                                            <th>Username</th>
                                            <th>FirstCurrency</th>
                                            <th>SecondCurrency</th>
                                            <th>Amount</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Created time</th>
                                            <th>Action</th>


                                        </tr>
                                        <tbody>
                                        @if($result)
                                            @foreach($result as $key=>$val)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$val->user_id}}</td>
                                                    <td>{{get_user_details($val->user_id,'enjoyer_name')}}</td>
                                                    <td>{{$val->FirstCurrency}}</td>
                                                    <td>{{$val->SecondCurrency}}</td>
                                                    <td>{{$val->Amount}}</td>
                                                    <td>{{round($val->Price)}}</td>
                                                    <td>{{round($val->Total)}}</td>
                                                    <td>{{$val->Status}}</td>
                                                    <td>{{$val->created_at}}</td>
                                                    @if($val->Status == 'Pending')
                                                        <td>&nbsp;<a
                                                                    href="{{url('/check_admin/cancel_pending_ico_order/'.$val->id)}}"><i
                                                                        class="fa fa-times" style="color: red"></i></a>
                                                        </td>
                                                    @else
                                                        <td></td>
                                                    @endif
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

    <!-- price update Modal -->
    <div id="update_price" class="modal danger fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="header"><strong>Edit ICO Price</strong></h4>
                </div>
                <div class="modal-body">
                    <form id="icopriceupdate" method="post" action="{{url('/check_admin/update_ico_price')}}">
                        <div class="row">

                            <div class="form-group col-md-6">
                                <label>BTC Price</label>
                                <input type="text" class="form-control" name="btc" id="btc" value="{{$price['BTC']}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>BCH Price</label>
                                <input type="text" class="form-control" name="bch" id="bch" value="{{$price['BCH']}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>ETH Price</label>
                                <input type="text" class="form-control" name="eth" id="eth" value="{{$price['ETH']}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>XRP Price</label>
                                <input type="text" class="form-control" name="xrp" id="xrp" value="{{$price['XRP']}}">
                            </div>

                        </div>
                        <div class="form-group col-md-6 pull-right">

                            <button type="button" class="btn btn-blue pull-right" data-dismiss="modal"
                                    style="margin: 5px">Close
                            </button>&nbsp;
                            <button type="submit" class="btn btn-danger pull-right" style="margin: 5px;">Submit</button>&nbsp;&nbsp;

                        </div>
                        <br><br>
                        {{csrf_field()}}
                    </form>
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