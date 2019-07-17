@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Email Template</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">Template</li>
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

                                <div class="table-container">

                                    <table class="table table-hover table-striped table-bordered table-advanced tablesorter"
                                           id="myTable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Email Id</th>
                                            <th>Username</th>
                                            <th>Role</th>
                                            <th>Actions</th>


                                        </tr>
                                        <tbody>

                                        @if($result)
                                            @foreach($result as $key=>$val)
                                                <tr>
                                                    <td>{{$val->id}}</td>
                                                    <td>{{$val->email_id}}</td>
                                                    <td>{{$val->CMB_username}}</td>
                                                    <td>{{$val->role}}</td>
                                                    <td>
                                                        <a href="{{url('check_admin/update_admin/'.$val->id)}}"
                                                           title="edit"><i class="fa fa-pencil"></i></a>
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



@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#myTable').DataTable();
        });
    </script>
@endsection