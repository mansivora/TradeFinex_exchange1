@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">{{$Header}}</div>
            <input type="hidden" id="url" value="{{url('check_admin/')}}">
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">User Balance</li>

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
                            <form id="form_filters" method="get">

                                <div class="col-md-3">
                                    <lable>User Id</lable>
                                    <input type="text" id="user_search_id" name="user_search_id"
                                           value="{{ app('request')->input('user_id') }}" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <lable>Search</lable>
                                    <input type="text" id="search_id" name="search"
                                           value="{{ app('request')->input('search') }}" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <lable>Email</lable>
                                    <input type="text" id="email_id" name="email"
                                           value="{{ app('request')->input('email') }}" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <a style="margin-top: 17px;" class="btn btn-default"
                                       href="{{url('/check_admin/userbalance/')}}"><i
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
                                    <th><a href="#" id="bal_tooltip" data-html="true" data-toggle="tooltip">#</a></th>
                                    <th>Username</th>
                                    <th>UserID</th>
                                    @if(isset($currencies))
                                        @foreach($currencies as $currency)
                                            <th>{{$currency->currency_symbol}}</th>
                                        @endforeach
                                    @endif
                                    @if($Header == 'User Wallet Balance')
                                        <th>Edit</th>
                                    @else
                                        <th>Date Time</th>
                                    @endif

                                </tr>
                                </thead>
                                <tbody>
                                @if($result)
                                    @foreach($result as $key=>$val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td><a class="form-control-static" target="_blank"
                                                   href="{{url('check_admin/user_transaction_details?user_id='.$val->id)}}">
                                                    @if($val->enjoyer_name==''){{get_usermail($val->id)}}@else{{$val->enjoyer_name}}@endif</a>
                                            </td>
                                            <td>{{$val->id}}</td>
                                            @if(isset($currencies))
                                                @foreach($currencies as $currency)
                                                    <td>{{get_userbalance($val->id,$currency->currency_symbol)}}</td>
                                                @endforeach
                                            @endif
                                            <td>
                                                <button><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
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


    <!-- Modal -->
    <div id="update_user" class="modal danger fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="header"><strong>Edit Balance</strong></h4>
                </div>
                <div class="modal-body">
                    <form id="userbalupdate" method="post" action="{{url('/check_admin/update_user_balance')}}">
                        <div class="row">
                            <input type="hidden" class="form-control" name="user_id" id="user_id">
                            <input type="hidden" class="form-control" name="user_name" id="user_name">
                            {{--<div class="form-group col-md-6">--}}
                            {{--<label >BTC Amount</label>--}}
                            {{--<input type="text" class="form-control" name="btc" id="btc" >--}}
                            {{--</div>--}}
                            {{--<div class="form-group col-md-6">--}}
                            {{--<label >BCH Amount</label>--}}
                            {{--<input type="text" class="form-control" name="bch" id="bch" >--}}
                            {{--</div>--}}

                            @if(isset($currencies))
                                @foreach($currencies as $currency)
                                    <div class="form-group col-md-6">
                                        <label>{{$currency->currency_symbol}} Amount</label>
                                        <input type="text" class="form-control"
                                               name="{{strtolower($currency->currency_symbol)}}"
                                               id="{{strtolower($currency->currency_symbol)}}">
                                    </div>
                                @endforeach
                            @endif

                            {{--<div class="form-group col-md-6">--}}
                            {{--<label>XRP Amount</label>--}}
                            {{--<input type="text" class="form-control" name="xrp" id="xrp">--}}
                            {{--</div>--}}
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
            var url = document.getElementById('url').value;
            var anchor = document.getElementById('bal_tooltip');
            $.getJSON(url + "/total_userbalance", function (result) {
                anchor.title = 'Total BTC: ' + result.BTC +
                    ' Admin BTC: ' + result.Admin_BTC + '\nTotal ETH: ' + result.ETH +
                    ' Admin ETH: ' + result.Admin_ETH + '\nTotal XRP: ' + result.XRP +
                    ' Admin XRP: ' + result.Admin_XRP + '\nTotal USDT: ' + result.USDT +
                    ' Admin : USDT' + result.Admin_USDT;
            });
            var myTable = $('#myTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "searching": false
            });

            $('#myTable tbody').on('click', 'button', function () {
                var data = myTable.row($(this).parents('tr')).data();
                update_userbal(data[2], data[1], data[3], data[4], data[5], data[6]);
            });

        });

        function update_userbal(user_id, name, eth, btc, xrp, usdt) {
            var User_id = document.getElementById('user_id');
            var User_name = document.getElementById('user_name');
            User_name.value = name;
            User_id.value = user_id;
            var Btc = document.getElementById('btc');
            var Eth = document.getElementById('eth');
            var Usdt = document.getElementById('usdt');
            var Xrp = document.getElementById('xrp');
            var header = document.getElementById('header');
            header.innerHTML = "Edit " + name + " Balance";
            Btc.placeholder = btc;
            Btc.value = btc;
            Eth.placeholder = eth;
            Eth.value = eth;
            Xrp.placeholder = xrp;
            Xrp.value = xrp;
            Usdt.placeholder = usdt;
            Usdt.value = usdt;
            $('#update_user').modal('show');
        }
    </script>
@endsection
