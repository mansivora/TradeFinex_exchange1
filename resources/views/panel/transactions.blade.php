@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">{{$Header}}</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">{{$Header}}</li>
        </ol>
        <div class="clearfix"></div>
    </div>


    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" value="{{url('/check_admin/delete_transaction')}}" id="url_addrs">
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
                                            <lable>Currency</lable>
                                            <select class="form-control" name="currency">
                                                <option value="all">All</option>

                                                @foreach($currencies as $val)
                                                    <option value="{{$val->currency_symbol}}"
                                                            @if(app('request')->input('currency') == $val->currency_symbol) selected @endif>{{$val->currency_symbol}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <lable>Status</lable>
                                            <select class="form-control" name="status">
                                                <option value="all">All</option>
                                                <option value="Pending"
                                                        @if(app('request')->input('status')=='Pending') selected @endif>
                                                    Pending
                                                </option>
                                                <option value="Processing"
                                                        @if(app('request')->input('status')=='Processing') selected @endif>
                                                    Processing
                                                </option>
                                                <option value="Completed"
                                                        @if(app('request')->input('status')=='Completed') selected @endif>
                                                    Completed
                                                </option>
                                                <option value="Cancelled"
                                                        @if(app('request')->input('status')=='Cancelled') selected @endif>
                                                    Cancelled
                                                </option>

                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <a style="margin-top: 17px;" class="btn btn-default"
                                               href="{{url('/check_admin/withdraw_history/')}}"><i
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
                                            <th>Transaction ID</th>
                                            <th>Username</th>
                                            <th>User ID</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            @if(@$result[0]->type=='Buy' || @$result[0]->type=='Sell')
                                                <th>Trade Pair</th>
                                            @else
                                                <th>Currency</th>
                                            @endif
                                            <th>Datetime</th>
                                            <th>Status</th>

                                            @if(@$result[0]->type=='Withdraw' || @$result[0]->type=='Deposit')
                                                <th>Action</th>
                                            @endif


                                        </tr>
                                        <tbody>
                                        @if($result)
                                            @foreach($result as $key=>$val)

                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$val->transaction_id}}</td>
                                                    <td>
                                                        <a href="{{url('/check_admin/user_transaction_details?user_id='.$val->user_id)}}"
                                                           target="_blank">@if(get_user_details($val->user_id,'enjoyer_name')==''){{get_usermail($val->user_id)}}@else{{get_user_details($val->user_id,'enjoyer_name')}}@endif</a>
                                                    </td>
                                                    <td>{{$val->user_id}}</td>
                                                    <td>{{$val->type}}</td>
                                                    <td>{{$val->amount}}</td>
                                                    @if($val->type=='Buy' || $val->type=='Sell')
                                                        <td>{{$val->pair}}</td>
                                                    @else
                                                        <td>{{$val->currency_name}}</td>
                                                    @endif
                                                    <td>{{$val->updated_at}}</td>
                                                    <td>{{$val->status}}</td>
                                                    @if($val->type=='Withdraw')
                                                        <td>
                                                            <a href="{{url('check_admin/confirm_transfer/'.$val->id)}}"><i
                                                                        class="fa fa-eye"></i></a>
                                                        </td>
                                                    @elseif($val->type=='Deposit')
                                                        <td>
                                                            <a href="{{url('check_admin/view_transactions/'.$val->transaction_id)}}"><i
                                                                        class="fa fa-eye"></i></a>&nbsp;
                                                            <a data-toggle="modal" data-target="#confirm_modal"
                                                               class="open-confirmModal"
                                                               data-id="{{$val->transaction_id}}"><i
                                                                        class="fa fa-trash-o"></i></a>
                                                        </td>
                                                    @endif
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
                    <h4 class="modal-title"><strong>Delete Trade</strong></h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this trade.?</p>
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
    <!-- <script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.3.1/js/buttons.flash.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
    <script src="//cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.3.1/js/buttons.print.min.js"></script> -->
    <script type="text/javascript">
        $(document).ready(function () {
            $('#myTable').DataTable({
                "searching": false,
                "paging": false,
                "ordering": true,
                "info": false,

                /* dom: 'Bfrtip',
                buttons: [
                {
                    extend: 'excel',
                    text:'Export to Excel',
                    filename:'{{@$result[0]->type}}-<?php echo date('d-m-Y'); ?>',
             exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8],
                }
        }
        ]*/
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
