@extends("panel.layout.trade_admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Balance Verification</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/userbalance')}}">Users Balance</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </li>

            <li class="active"><a href="{{url('check_admin/users_balance_validation')}}">Users Valid Balance</a></li>
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
                                            <lable>User Id</lable>

                                            <input type="text" id="user_id" name="user_id"
                                                   class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <lable>Currency</lable>

                                            <input type="text" id="currency" name="currency"
                                                   class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <a style="margin-top: 17px;" class="btn btn-default"
                                               href="{{url('/check_admin/users_balance_validation/')}}"><i
                                                        class="fa fa-refresh"></i></a>&nbsp;
                                            <button style="margin-top: 17px;" class="btn btn-default"><i
                                                        class="fa fa-search"></i></button>
                                        </div>
                                        {{csrf_field()}}
                                    </form>
                                </div>
                                <input type="hidden" value="{{url('/check_admin/cancel_trade')}}" id="url_addrs">

                                <div class="table-container">

                                    <table class="table table-hover table-striped table-bordered table-advanced tablesorter"
                                           id="myTable">
                                        <thead>
                                        <tr>

                                            <th>#</th>
                                            <th>Username</th>
                                            <th>Displayed Balance</th>
                                            <th>Validated Balance</th>
                                            <th>Explorer Deposit</th>
                                            <th>Alphaex Deposit</th>
                                            <th>Buy</th>
                                            <th>Sell</th>
                                            <th>InTrade Buy</th>
                                            <th>InTrade Sell</th>
                                            <th>Withdraw</th>
                                        </tr>
                                        <tbody>
                                        @if($UserList)
                                            @foreach($UserList as $user)
                                                <tr>
                                                    @if($user['Verified']==1)
                                                        <td><a></a><i class="fa fa-circle" style="color: green"
                                                                      aria-hidden="true"></i></td>
                                                    @else
                                                        <td><a style="cursor: pointer;color: red" class="fa fa-circle"
                                                               onclick="update_userbal({{$user['User_id']}},'{{$user['Currency']}}',{{$user['Actual_Balance']}})"></a>
                                                        </td>
                                                    @endif
                                                    <td>{{$user['Name']}}</td>
                                                    <td>{{$user['Displayed_Balance']}}</td>
                                                    <td>{{$user['Actual_Balance']}}</td>
                                                    <td>{{$user['Deposit']}}</td>
                                                    <td>{{$user['ADeposit']}}</td>
                                                    <td>{{$user['Buy']}}</td>
                                                    <td>{{$user['Sell']}}</td>
                                                    <td>{{$user['TBuy']}}</td>
                                                    <td>{{$user['TSell']}}</td>
                                                    <td>{{$user['Withdraw']}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        </thead></table>

                                </div>

                                @if($paginate ==0)
                                    <div class="row">
                                        <div class="col-lg-6">

                                        </div>
                                        <div class="col-lg-6 text-right">
                                            <div class="pagination-panel">
                                                @include('panel.pagination', ['paginator' => $result])

                                            </div>
                                        </div>
                                    </div>
                                @endif


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
                    <h4 class="modal-title"><strong>Modify Balance</strong></h4>
                </div>
                <div class="modal-body">
                    <form id="userbalupdate" method="post" action="{{url('/check_admin/update_user_balance')}}">
                        <div class="row">
                            <input type="hidden" class="form-control" name="user_id" id="user_id">
                            <div class="form-group col-md-12">
                                <label>Currency</label>
                                <input type="text" class="form-control" name="currency" id="currency" readonly>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Update Balance</label>
                                <input type="text" class="form-control" name="amount" id="amount" readonly>
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
                "ordering": false,
                "info": false
            });

        });

    </script>

    <script type="text/javascript">


        function update_userbal(user_id, currency, amount) {
            console.log("in");
            var Currency = document.getElementById('currency');
            var User_id = document.getElementById('user_id');
            var Amount = document.getElementById('amount');

            Currency.value = currency;
            Amount.value = amount;
            User_id.value = user_id;
            $('#update_user').modal('show');


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