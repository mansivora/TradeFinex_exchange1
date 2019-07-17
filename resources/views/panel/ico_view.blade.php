@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Token Request Details</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">Token Details</li>
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

                                <form class="form-horizontal" method="post"
                                      action="{{url('check_admin/token_view/'.$id)}}">
                                    {{ csrf_field() }}
                                    <h3>Token Details: </h3>
                                    <div class="panel-body pan">
                                        <div class="form-body pal">


                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="inputFirstName" class=" control-label"><strong>Coin
                                                                Name:</strong></label>

                                                        <br>
                                                        <p class="form-control-static">{{ $result->name }}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="inputFirstName" class="control-label"><strong>Ticker
                                                                Name:</strong></label>

                                                        <br>
                                                        <p class="form-control-static">{{ $result->ticker }}</p>
                                                    </div>

                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="inputFirstName" class="control-label"><strong>Coin
                                                                Type:</strong></label>

                                                        <br>
                                                        <p class="form-control-static">{{ $results->type }}</p>
                                                    </div>
                                                </div>


                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <label for="inputFirstName" class="control-label"><strong>Contract
                                                                Address:</strong></label>

                                                        <br>
                                                        <p class="form-control-static">{{ $results->contract_address }}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="inputFirstName" class="control-label"><strong>Decimals:</strong></label>

                                                        <br>
                                                        <p class="form-control-static">{{ $results->decimals}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="control-label"><strong>Total
                                                                Supply:</strong></label>

                                                        <br>
                                                        <p class="form-control-static">{{ $result->total_supply }}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="control-label"><strong>Circulating
                                                                Supply:</strong></label>

                                                        <br>
                                                        <p class="form-control-static">{{ $result->circulating_supply}}</p>
                                                    </div>
                                                </div>

                                                {{--<div class="col-md-4">--}}
                                                {{--<div class="form-group"><label for="inputFirstName" class="control-label"><strong>ICO Supply:</strong></label>--}}

                                                {{--<br><p class="form-control-static">{{ $result->ico_supply }}</p>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}

                                                <div class="col-md-4">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="control-label"><strong>ICO
                                                                Price:</strong></label>

                                                        <br>
                                                        <p class="form-control-static">{{ $result->token_price }}</p>
                                                    </div>
                                                </div>

                                                {{--<div class="col-md-4">--}}
                                                {{--<div class="form-group"><label for="inputFirstName" class=" control-label"><strong>Start Date:</strong></label>--}}

                                                {{--<br><p class="form-control-static">{{ $result->start_date}}</p>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}

                                                {{--<div class="col-md-4">--}}
                                                {{--<div class="form-group"><label for="inputFirstName" class="control-label"><strong>Number of Days:</strong></label>--}}

                                                {{--<br><p class="form-control-static">{{ $result->max_days }}</p>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}

                                                <div class="col-md-4">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="  control-label"><strong>Algorithm
                                                                Used:</strong></label>

                                                        <p class="form-control-static">{{ $results->algorithm }}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="  control-label"><strong>Development
                                                                Language:</strong></label>

                                                        <p class="form-control-static">{{ $results->dev }}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="  control-label"><strong>Verification
                                                                Type:</strong></label>

                                                        <p class="form-control-static">{{ $results->verification_type }}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="  control-label"><strong>Source
                                                                Code:</strong></label>

                                                        <p class="form-control-static"><a
                                                                    href="{{$results->source_code}}"></a>{{ $results->source_code }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="  control-label"><strong>Explorer
                                                                Link:</strong></label>

                                                        <p class="form-control-static"><a
                                                                    href="{{$results->explorer}}"></a>{{ $results->explorer }}
                                                        </p>
                                                    </div>
                                                </div>


                                                <div class="col-md-4">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="  control-label"><strong>Email:</strong></label>

                                                        <p class="form-control-static">{{ $results->email }}</p>
                                                    </div>
                                                </div>
                                                <br>

                                                <div class="col-md-4">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="  control-label"><strong>Skype/telegram
                                                                Id:</strong></label>

                                                        <p class="form-control-static"><a
                                                                    href="{{$results->messenger}}"></a>{{ $results->messenger }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="control-label"><strong>Social
                                                                link:</strong></label>

                                                        <div><textarea readonly>{{$results->social_links}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="control-label"><strong>Comments:</strong></label>

                                                        <div><textarea readonly>{{$results->Comments}}</textarea></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="control-label"><strong>Status:</strong></label>

                                                        <div>
                                                            <select id="status" class="form-control" name="status"
                                                                    onchange="response(this.value)">
                                                                <option value="Pending"
                                                                        @if($result->status =='Pending') selected @endif>
                                                                    Pending
                                                                </option>
                                                                <option value="Active"
                                                                        @if($result->status =='Active') selected @endif>
                                                                    Active
                                                                </option>
                                                                <option value="Ended"
                                                                        @if($result->status =='Ended') selected @endif>
                                                                    Ended
                                                                </option>
                                                                <option value="Rejected"
                                                                        @if($result->status =='Rejected') selected @endif>
                                                                    Rejected
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6" id="ico_reason" style="display: none">
                                                    <div class="form-group"><label for="inputFirstName"
                                                                                   class="control-label"><strong>&nbsp;&nbsp;Message:</strong></label>

                                                        <div>&nbsp;&nbsp;<textarea id="email_body" name="email_body"
                                                                                   style="height: 80px;width: 250px"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inputFirstName" class="control-label">
                                                <strong>&nbsp;&nbsp;Update Client:</strong></label>
                                            &nbsp;&nbsp;<input type="checkbox" name="update_status">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-green btn-block">Update</button>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



@endsection

@section('script')
    <script type="text/javascript">
        function response(id) {
            if (id == 'Rejected' || id == 'Pending') {
                $("#ico_reason").show();
            }
            else {
                $("#ico_reason").hide();
            }
        }
    </script>
@endsection

