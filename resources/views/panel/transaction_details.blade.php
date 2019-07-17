@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Transaction details</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">Details</li>
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

                                <form class="form-horizontal" action="#" method="post">
                                    <h3>Deposit Details: </h3>
                                    <div class="panel-body pan">
                                        <div class="form-body pal">


                                            <div class="row">


                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Transaction
                                                                ID:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->transaction_id}}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Currency
                                                                Name:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->currency_name}}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Type:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->type}}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>User
                                                                ID:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->user_id}}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>User
                                                                Email:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{get_usermail($result->user_id)}}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Deposit
                                                                Amount:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->amount}}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Fee:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->fee}}</p>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Deposit
                                                                Address:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->crypto_address}}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Date:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->created_at}}</p>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputLastName"
                                                                                   class="col-md-2 control-label"><strong>Status:</strong></label>

                                                        <div class="col-md-10"><p
                                                                    class="form-control-static">{{$result->status}}</p>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



@endsection

