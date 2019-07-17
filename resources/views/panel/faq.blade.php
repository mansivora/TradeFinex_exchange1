@extends("panel.layout.admin_layout")
@section("content")

    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">FAQ</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">FAQ</li>
        </ol>
        <div class="clearfix"></div>
    </div>


    {{--<div class="col-md-12 clearfix">--}}
    {{--@include('panel.alert')--}}
    {{--<button class="btn btn-danger pull-right" id="confirm" data-toggle="modal" data-target="#myModal">Update FAQ?</button>--}}
    {{--</div>--}}

    <div class="page-content">
        <div class="row">
            <div class="col-md-12">


                <div id="tableactionTabContent" class="tab-content">
                    <a href="{{url('check_admin/add_faq')}}" class="btn btn-info pull-right">Add FAQ</a>
                    <div id="table-table-tab" class="tab-pane fade in active">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="table-container">

                                    <table class="table table-hover table-striped table-bordered table-advanced tablesorter"
                                           id="myTable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Question</th>
                                            <th>Status</th>
                                            <th>Updated at</th>

                                            <th>Actions</th>


                                        </tr>
                                        <tbody>
                                        @if($result)
                                            @foreach($result as $key=>$val)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$val->question}}</td>
                                                    <td>@if($val->status==1) Active @else Deactive @endif</td>
                                                    <td>{{$val->updated_at}}</td>
                                                    <td>

                                                        <a href="{{url('check_admin/update_faq/'.$val->id)}}"
                                                           title="edit"><i class="fa fa-pencil"></i></a>

                                                        <a href="{{url('check_admin/status_faq/'.$val->id)}}"
                                                           title="Status"><i class="fa fa-cog"></i></a>

                                                        <a href="{{url('check_admin/delete_faq/'.$val->id)}}"
                                                           title="delete"
                                                           onclick="return confirm('Are you sure to delete')"><i
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

    <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Update FAQ?
                </div>
                <div class="modal-body">
                    Are you sure you want to update the FAQs?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                    <button id="confirmed" class="btn btn-success success" onclick="confirmation()">Yes</button>
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

    <script>

        function confirmation() {
            window.location.href = "{{URL::to('/check_admin/confirmation')}}";
        }

    </script>



@endsection
