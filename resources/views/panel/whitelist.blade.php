@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Whitelists</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">Whitelists</li>
        </ol>
        <div class="clearfix"></div>
    </div>


    <div class="page-content">
        <div class="row">
            <div class="col-md-12">

                @include('panel.alert')

                <div id="tableactionTabContent" class="tab-content">
                    <a href="#" data-target="#modal-ip" data-toggle="modal" class="btn btn-info pull-right">Add IP</a>
                    <div id="table-table-tab" class="tab-pane fade in active">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="table-container">

                                    <table class="table table-hover table-striped table-bordered table-advanced tablesorter"
                                           id="myTable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>IP</th>
                                            <th>Updated at</th>
                                            <th>Actions</th>


                                        </tr>
                                        <tbody>
                                        @if($result)
                                            @foreach($result as $key=>$val)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$val->ip}}</td>
                                                    <td>{{$val->updated_at}}</td>
                                                    <td>
                                                        <a href="{{url('check_admin/delete_whitelist/'.$val->id)}}"
                                                           title="delete"
                                                           onclick="return confirm('Are you sure to delete ?')"><i
                                                                    class="fa fa-trash-o"></i></a>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        </thead></table>

                                </div>


                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



    <div id="modal-ip" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="false"
         class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">×</button>
                    <h4 id="modal-login-label" class="modal-title">Add IP Address</h4></div>
                <div class="modal-body">
                    <div class="form">

                        <form class="form-horizontal" method="post" action="{{url('check_admin/whitelists')}}">
                            {{ csrf_field() }}
                            <div class="form-group"><label for="username" class="control-label col-md-3">IP
                                    Address</label>

                                <div class="col-md-9"><input id="ip_addr" name="ip_addr" class="form-control"
                                                             type="text" required></div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </form>
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
            $('#myTable').DataTable();
        });

        $("#ip_addr").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });
    </script>

@endsection