@extends('front.layout.front')
@section('content')
    <div class="clearfix"></div>
    <div class="main-flex">
        <div class="main-content inner_content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">
                        <div class="panel panel-default panel-heading-space">
                            <div class="panel-heading">Open Orders</div>
                            <div class="panel-body">
                                <table id="order" class="table table-striped table-bordered dt-responsive nowrap"
                                       style="width:100%">
                                    <thead>
                                    <div>
                                        <button class="btn btn-danger pull-right btn-xs" id="cancel_button"
                                                style="display:none;" data-toggle="modal"
                                                data-target="#cancel_multiple">Cancel Trades
                                        </button>
                                        <button class="btn btn-info pull-right btn-xs" id="checknone"
                                                style="margin-right:5px; display:none;" onclick="check_none()">None
                                        </button>
                                        <button class="btn btn-info pull-right btn-xs" id="checkall"
                                                style="margin-right:5px; display:none;" onclick="check_all()">All
                                        </button>
                                    </div>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Type</th>
                                        <th>Pair</th>
                                        <th>Currency</th>
                                        <th>Amount</th>
                                        <th>Price</th>
                                        <th>Fees</th>
                                        <th>Total Price</th>
                                        {{--<th>Filled%</th>--}}
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($open_orders))
                                        @foreach($open_orders as $key=>$val)
                                            <tr>
                                                <td>{{$val->updated_at}} <span style="display: none;">{{$key+1}}</span>
                                                </td>
                                                @if($val->type=='Buy')
                                                    <td><span class="green">{{$val->type}}</span></td>
                                                @else
                                                    <td><span class="red">{{$val->type}}</span></td>
                                                @endif
                                                <td>{{$val->pair}}</td>
                                                <td>{{$val->firstCurrency}}</td>
                                                <td>{{number_format($val->updated_qty)}}</td>
                                                <td>{{sprintf('%.8f',$val->price)}}</td>
                                                <td>{{number_format($val->fee,9)}}</td>
                                                @if($val->status=="active")
                                                    <td>{{number_format($val->total,8)}}</td>
                                                @else
                                                    <td>{{number_format(($val->updated_total),8)}}</td>
                                                @endif
                                                {{--<td>0.00%</td>--}}
                                                <td>
                                                    <button class="btn btn-danger btn-xs" id="cancelsingle"
                                                            value="{{base64_encode($val->id)}}" data-toggle="modal"
                                                            data-target="#cancel_single">Cancel
                                                    </button>
                                                    &nbsp;&nbsp;<input type='checkbox' name='checked[]'
                                                                       id="checkbox{{$key}}" value="{{$val->id}}"
                                                                       onclick="check_selected()">
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-12">
                        <div class="panel panel-default panel-heading-space">
                            <div class="panel-heading">Trade History
                                <div class="pull-right"><a href="{{url('/export_csv')}}" class="btn btn-default"><i
                                                class="fa fa-download">CSV</i></a>&nbsp;<a href="{{url('/export_pdf')}}"
                                                                                           class="btn btn-default"><i
                                                class="fa fa-download">PDF</i></a></div>
                            </div>
                            <div class="panel-body">
                                <table id="trade" class="table table-striped table-bordered dt-responsive nowrap"
                                       style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Type</th>
                                        <th>Pair</th>
                                        <th>Currency</th>
                                        <th>Amount</th>
                                        <th>Price</th>
                                        <th>Fees</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($history))
                                        @foreach($history as $val)
                                            <tr>
                                                <td>{{$val->updated_at}}</td>
                                                @if($val->type=='Buy')
                                                    <td><span class="green">{{$val->type}}</span></td>
                                                @else
                                                    <td><span class="red">{{$val->type}}</span></td>
                                                @endif
                                                <td>{{$val->pair}}</td>
                                                <td>{{$val->firstCurrency}}</td>
                                                @if($val->status == 'completed')
                                                    <td>{{number_format($val->triggered_qty,2)}}</td>
                                                    <td>{{sprintf('%.8f',$val->triggered_price)}}</td>
                                                @else
                                                    <td>{{number_format($val->original_qty,2)}}</td>
                                                    <td>{{sprintf('%.8f',$val->price)}}</td>
                                                @endif
                                                <td>{{number_format($val->fee,9)}}</td>
                                                @if($val->status=='cancelled' || $val->status=='active')
                                                    <td>{{sprintf('%.8f',($val->total))}}</td>
                                                @elseif($val->status=='completed')
                                                    <td>{{sprintf('%.8f',($val->triggered_total))}}</td>
                                                @else
                                                    <td>{{sprintf('%.8f',($val->updated_total))}}</td>
                                                @endif
                                                <td>{{$val->status}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-12">
                        <div class="panel panel-default panel-heading-space">
                            <div class="panel-heading">Deposit History
                                <div class="pull-right"><a href="{{url('/transaction_csv/Deposit')}}"
                                                           class="btn btn-default"><i class="fa fa-download">CSV</i></a>&nbsp;<a
                                            href="{{url('/transaction_pdf/Deposit')}}" class="btn btn-default"><i
                                                class="fa fa-download">PDF</i></a></div>
                            </div>
                            <div class="panel-body">
                                <table id="deposit" class="table table-striped table-bordered dt-responsive nowrap"
                                       style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Transaction Id</th>
                                        <th>Currency</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($deposit))
                                        @foreach($deposit as $val)
                                            <tr>
                                                <td>{{$val->updated_at}}</td>
                                                <td>{{$val->transaction_id}}</td>
                                                <td>{{$val->currency_name}}</td>
                                                <td>{{$val->amount}}</td>
                                                <td>{{$val->status}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-12">
                        <div class="panel panel-default panel-heading-space">
                            <div class="panel-heading">Withdraw History
                                <div class="pull-right"><a href="{{url('/transaction_csv/Withdraw')}}"
                                                           class="btn btn-default"><i class="fa fa-download">CSV</i></a>&nbsp;<a
                                            href="{{url('/transaction_pdf/Withdraw')}}" class="btn btn-default"><i
                                                class="fa fa-download">PDF</i></a></div>
                            </div>
                            <div class="panel-body">
                                <table id="withdraw" class="table table-striped table-bordered dt-responsive nowrap"
                                       style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Transaction Id</th>
                                        <th>Currency</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($withdraw))
                                        @foreach($withdraw as $val)
                                            <tr>
                                                <td>{{$val->updated_at}}</td>
                                                <td>{{$val->transaction_id}}</td>
                                                <td>{{$val->currency_name}}</td>
                                                <td>{{number_format($val->amount,3)}}</td>
                                                <td>{{$val->status}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div id="cancel_single" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content custom-modal-background text-center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cancel Trade</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel the trade?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel min-width-btn" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn yellow-btn min-width-btn" onclick="cancel_single()">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancel_multiple" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content custom-modal-background text-center">
                <div class="modal-header">
                    Cancel Trade
                </div>
                <div class="modal-body">
                    Are you sure you want to cancel the selected trades?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel min-width-btn" data-dismiss="modal">No</button>
                    <button id="confirmed" class="btn yellow-btn min-width-btn" onclick="cancel_multiple()">Yes</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('xscript')

    <script src="https://js.pusher.com/4.2/pusher.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.bar-toggle').on('click', function () {
                $('.leftbar').toggleClass('open');
            });
            $('#order').DataTable(
                {
                    "ordering": false,
                    "pageLength": 10,
                    "lengthChange": false,
                    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    "bInfo": false,
                });
            $('#trade').DataTable(
                {
                    "ordering": false,
                    "pageLength": 10,
                    "lengthChange": false,
                    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    "bInfo": false,
                });
            var deposit_table = $('#deposit').DataTable(
                {
                    "ordering": false,
                    "pageLength": 10,
                    "lengthChange": false,
                    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    "bInfo": false,
                });
            var withdraw_table = $('#withdraw').DataTable(
                {
                    "ordering": false,
                    "pageLength": 10,
                    "lengthChange": false,
                    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    "bInfo": false,
                });

            var pusher = new Pusher('{{env('PUSHER_APP_KEY')}}',
                {
                    cluster: 'ap1',
                    auth: {
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                        }
                    },
                    authEndpoint: '/ajax/pusher/auth',
                }
            );

            var user_id = '{{$userid}}';
            var socketId = '';

            pusher.connection.bind('connected', function () {
                socketId = pusher.connection.socket_id;
            });

            var channel = pusher.subscribe('private-transaction_' + user_id);

            channel.bind('deposit-event', function (data) {
                if (data.Status == 'Completed' && data.User_id == user_id) {
                    toastr.success('Your ' + data.Currency + ' deposit of ' + data.Amount + ' is completed');
                    var currency = data.Currency;
                    if (currency == '{{$currency}}') {
                        var amount = data.Amount;
                        var transaction_id = data.Transaction_id;
                        var time = data.Time;
                        var status = data.Status;
                        rownode = deposit_table.row.add([time, transaction_id, currency, amount, status]).draw().node();
                        $(rownode).css('color', 'green').animate({color: 'green'});
                        setTimeout(function () {
                            $(rownode).css('color', '#909699')
                        }, 550);
                    }
                }
            });


            channel.bind('withdraw-event', function (data) {
                if (data.User_id == user_id) {
                    if (data.Status == 'Completed') {
                        toastr.success('Your ' + data.Currency + ' withdraw of ' + data.Amount + ' is completed');
                    }
                    var currency = data.Currency;
                    if (currency == '{{$currency}}') {
                        var amount = data.Amount;
                        var transaction_id = data.Transaction_id;
                        var time = data.Time;
                        var status = data.Status;
                        rownode = withdraw_table.row.add([time, transaction_id, currency, amount, status]).draw().node();
                        $(rownode).css('color', 'green').animate({color: 'green'});
                        setTimeout(function () {
                            $(rownode).css('color', '#909699')
                        }, 550);
                    }
                }
            });
        })
    </script>
    <script type="text/javascript">
        function cancel_single() {
            var id = $('#cancelsingle').val();
            window.location = "{{url('trade/cancel_order')}}/" + id;
        }
    </script>

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
            console.log(id);
            window.location.href = "/trade/cancel_multiple/" + encodeURIComponent(JSON.stringify(id));
        }

    </script>

@endsection