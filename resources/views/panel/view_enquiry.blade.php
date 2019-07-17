@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Enquiry</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">Enquiry</li>
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


                                <form method="post" action="{{url('check_admin/view_enquiry/'.$id)}}"
                                      class="form-horizontal">
                                    {{ csrf_field() }}
                                    <h3>User Query Details: </h3>
                                    <div class="panel-body pan">
                                        <div class="form-body pal">

                                            <div class="row">

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="col-md-2 control-label"><strong>Name:</strong></label>
                                                        <div class="col-md-10">
                                                            <p class="form-control-static">{{$result->enquiry_name}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="col-md-2 control-label"><strong>Email:</strong></label>
                                                        <div class="col-md-10">
                                                            <p class="form-control-static">{{$result->enquiry_email}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="col-md-2 control-label"><strong>Subject:</strong></label>
                                                        <div class="col-md-10">
                                                            <p class="form-control-static">{{$result->enquiry_subject}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>


                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="col-md-2 control-label"><strong>Query:</strong></label>
                                                        <div class="col-md-10">
                                                            <p class="form-control-static">{{$result->enquiry_message}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="col-md-2 control-label"><strong>Created
                                                                Date:</strong></label>
                                                        <div class="col-md-10">
                                                            <p class="form-control-static">{{$result->updated_at}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>

                                                @if(count($result_rply) > 0)
                                                    <h3>Reply Message: </h3>
                                                    @foreach($result_rply as $fetrply)

                                                        <div class="col-md-12">
                                                            <div class="form-group"><label for="inputFirstName"
                                                                                           class="col-md-2 control-label"><strong>Reply
                                                                        Message:</strong></label>
                                                                <div class="col-md-10">
                                                                    <p class="form-control-static">
                                                                        <strong>Date: </strong>{{ $fetrply->updated_at}}
                                                                    </p>

                                                                    <p class="form-control-static">{{$fetrply->answer}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br/>
                                                        <br/>
                                                        <br/>

                                                    @endforeach
                                                @endif

                                                <div class="col-md-12">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="col-md-2 control-label"><strong>Reply
                                                                Message:</strong></label>
                                                        <div class="col-md-10">
                                                            <textarea class="form-control" name="answer"
                                                                      id="answer">{{old('answer')}}</textarea>

                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
                                                <br/>

                                            </div>
                                        </div>
                                    </div>
                                    <input type="submit" name="submit" id="submit" class="btn btn-green btn-block"
                                           value="Submit">


                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



@endsection

