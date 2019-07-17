@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Update Meta Contents</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>

            <li class="active">Meta Content</li>
        </ol>
        <div class="clearfix"></div>
    </div>


    <div class="page-content">
        <div class="row">
            <div class="col-md-12">

                @include('panel.alert')

                <div class="row mtl">

                    <div class="col-md-12">

                        <div id="generalTabContent" class="tab-content">

                            <div id="tab-edit" class="tab-pane fade in active">
                                <form action="{{url('check_admin/update_meta/'.$id)}}" method="post"
                                      class="form-horizontal">
                                    <h3>Update Meta Contents</h3>
                                    {{ csrf_field() }}

                                    <div class="form-group"><label class="col-sm-3 control-label">Page </label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6">

                                                    <input type="text" value="{{$result->heading}}" name="heading"
                                                           readonly class="form-control"/>

                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group"><label class="col-sm-3 control-label">Meta Title </label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6">

                                                    <input type="text" value="{{$result->title}}" name="title"
                                                           class="form-control"/>

                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group"><label class="col-sm-3 control-label">Meta Keywords </label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6">

                                                    <input type="text" value="{{$result->meta_keywords}}"
                                                           name="meta_keywords" class="form-control"/>

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"><label class="col-sm-3 control-label">Meta
                                            Descriptions </label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6">

                                                    <textarea class="form-control"
                                                              name="meta_description">{{$result->meta_description}}</textarea>

                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <hr/>
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
    <script>
        $("#xdc_btc").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });

        $("#xdc_eth").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });

        $("#xdc_xrp").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 106) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });
    </script>
@endsection