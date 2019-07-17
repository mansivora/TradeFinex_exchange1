@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Ether Block Status</div>
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


                <div class="row">
                    <div class="col-lg-6">
                        <h2>Ether Current Block: {{$Current}}</h2>

                    </div>
                    <div class="col-lg-6">
                        <h2>EtherScan Block:{{$Highest}}</h2>
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
                            <div class="form-group col-md-6">
                                <label>BTC Amount</label>
                                <input type="text" class="form-control" name="btc" id="btc">
                            </div>
                            <div class="form-group col-md-6">
                                <label>XDC Amount</label>
                                <input type="text" class="form-control" name="xdc" id="xdc">
                            </div>
                            <div class="form-group col-md-6">
                                <label>ETH Amount</label>
                                <input type="text" class="form-control" name="eth" id="eth">
                            </div>
                            <div class="form-group col-md-6">
                                <label>XRP Amount</label>
                                <input type="text" class="form-control" name="xrp" id="xrp">
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
            var url = document.getElementById('url').value;
            var anchor = document.getElementById('bal_tooltip');
            $.getJSON(url + "/total_userbalance", function (result) {
                anchor.title = 'Total BTC: ' + result.BTC +
                    ' Admin BTC: ' + result.Admin_BTC + '\nTotal ETH: ' + result.ETH +
                    ' Admin ETH: ' + result.Admin_ETH + '\nTotal XRP: ' + result.XRP +
                    ' Admin XRP: ' + result.Admin_XRP + '\nTotal XDC: ' + result.XDC +
                    ' Admin XDC: ' + result.Admin_XDC;
            });
            var myTable = $('#myTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false
            });

        });

        function update_userbal(user_id, name, xdc, btc, eth, xrp) {
            var User_id = document.getElementById('user_id');
            var User_name = document.getElementById('user_name');
            User_name.value = name;
            User_id.value = user_id;
            var Btc = document.getElementById('btc');
            var Xdc = document.getElementById('xdc');
            var Eth = document.getElementById('eth');
            var Xrp = document.getElementById('xrp');
            var header = document.getElementById('header');
            header.innerHTML = "Edit " + name + " Balance";
            Btc.placeholder = btc;
            Btc.value = btc;
            Xdc.placeholder = xdc;
            Xdc.value = xdc;
            Xrp.placeholder = xrp;
            Xrp.value = xrp;
            Eth.placeholder = eth;
            Eth.value = eth;
            $('#update_user').modal('show');


        }
    </script>
@endsection