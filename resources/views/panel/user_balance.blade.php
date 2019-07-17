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

            @if($Header == 'Users Opening Balance')
                <li class="active">Opening Balance</li>
            @else
                <li class="active">Closing Balance</li>
            @endif

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
                                    @if($Header == 'Users Opening Balance')
                                        <a style="margin-top: 17px;" class="btn btn-default"
                                           href="{{url('/check_admin/users_opening_balance/')}}"><i
                                                    class="fa fa-refresh"></i></a>
                                    @else
                                        <a style="margin-top: 17px;" class="btn btn-default"
                                           href="{{url('/check_admin/users_closing_balance/')}}"><i
                                                    class="fa fa-refresh"></i></a>&nbsp;
                                    @endif
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
                                    <th>BTC<a href="{{url('/check_admin/userbalance?currency=BTC')}}"><i
                                                    class="fa fa-fw fa-sort pull-right"></i></a></th>
                                    {{--<th>BCH<a href="{{url('/check_admin/userbalance?currency=BCH')}}"><i class="fa fa-fw fa-sort pull-right"></i></a></th>--}}
                                    {{--<th>XRP<a href="{{url('/check_admin/userbalance?currency=XRP')}}"><i class="fa fa-fw fa-sort pull-right"></i></a></th>--}}
                                    <th>ETH<a href="{{url('/check_admin/userbalance?currency=ETH')}}"><i
                                                    class="fa fa-fw fa-sort pull-right"></i></a></th>
                                    <th>CMB<a href="{{url('/check_admin/userbalance?currency=CMB')}}"><i
                                                    class="fa fa-fw fa-sort pull-right"></i></a></th>
                                    <th>XDCE<a href="{{url('/check_admin/userbalance?currency=XDCE')}}"><i
                                                    class="fa fa-fw fa-sort pull-right"></i></a></th>
                                    @if($Header == 'User Wallet Balance')
                                        <th>Edit</th>
                                    @else
                                        <th>Date Time</th>
                                    @endif


                                </tr>
                                <tbody>
                                @if($result)
                                    @foreach($result as $key=>$val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td><a class="form-control-static" target="_blank"
                                                   href="{{url('check_admin/user_transaction_details?user_id='.$val->user_id)}}">
                                                    {{$val->enjoyer_name}}</a></td>
                                            <td>{{$val->user_id}}</td>
                                            <td>{{$val->BTC}}</td>
                                            {{--<td>{{$val->BCH}}</td>--}}
                                            {{--<td>{{$val->XRP}}</td>--}}
                                            <td>{{$val->ETH}}</td>
                                            <td>{{$val->CMB}}</td>
                                            <td>{{$val->XDCE}}</td>
                                            <td>{{$val->created_at}}</td>
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

                    ' Admin BTC: ' + result.Admin_BTC + '\nTotal BCH: ' + result.BCH +
                    ' Admin BCH: ' + result.Admin_BCH + '\nTotal ETH: ' + result.ETH +
                    ' Admin ETH: ' + result.Admin_ETH + '\nTotal XRP: ' + result.XRP +
                    ' Admin XRP: ' + result.Admin_XRP + '\nTotal XDC: ' + result.XDC +
                    ' Admin XDC: ' + result.Admin_XDC;
            });
            var myTable = $('#myTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "searching": false
            });

        });

    </script>
@endsection
