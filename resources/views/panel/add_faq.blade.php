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


    <div class="page-content">
        <div class="row">
            <div class="col-md-12">

                @include('panel.alert')

                <div class="row mtl">

                    <div class="col-md-12">

                        <div id="generalTabContent" class="tab-content">
                            @if($view=='add')
                                <div id="tab-edit" class="tab-pane fade in active">
                                    <form action="{{url('check_admin/add_faq')}}" method="post" class="form-horizontal">
                                        <h3>Add FAQ</h3>
                                        {{ csrf_field() }}

                                        <div class="form-group"><label class="col-sm-3 control-label">Question </label>

                                            <div class="col-sm-9 controls">
                                                <div class="row">
                                                    <div class="col-xs-9"><input type="text" value="{{old('question')}}"
                                                                                 name="question" class="form-control"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group"><label class="col-sm-3 control-label">Answer </label>

                                            <div class="col-sm-9 controls">
                                                <div class="row">
                                                    <div class="col-xs-9">
                                                        <textarea class="form-control" name="description"
                                                                  id="content">{{old('description')}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <hr/>
                                        <button type="submit" class="btn btn-green btn-block">Add</button>
                                    </form>
                                </div>
                            @endif

                            @if($view=='edit')
                                <div id="tab-edit" class="tab-pane fade in active">
                                    <form action="{{url('check_admin/update_faq/'.$id)}}" method="post"
                                          class="form-horizontal">
                                        <h3>Update FAQ</h3>
                                        {{ csrf_field() }}

                                        <div class="form-group"><label class="col-sm-3 control-label">Question </label>

                                            <div class="col-sm-9 controls">
                                                <div class="row">
                                                    <div class="col-xs-9"><input type="text"
                                                                                 value="{{$result->question}}"
                                                                                 name="question" class="form-control"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group"><label class="col-sm-3 control-label">Answer </label>

                                            <div class="col-sm-9 controls">
                                                <div class="row">
                                                    <div class="col-xs-9">
                                                        <textarea class="form-control" name="description"
                                                                  id="content">{{$result->description}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <hr/>
                                        <button type="submit" class="btn btn-green btn-block">Update</button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('script')
    <script type="text/javascript" src="{{ URL::asset('ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">

        CKEDITOR.replace('content',
            {
                filebrowserBrowseUrl: '{{ URL::asset("ckfinder/ckfinder.html")}}',
                filebrowserImageBrowseUrl: '{{ URL::asset("/") }}ckfinder/ckfinder.html?type=Images',
                filebrowserFlashBrowseUrl: '{{ URL::asset("/") }}ckfinder/ckfinder.html?type=Flash',
                filebrowserUploadUrl: '{{ URL::asset("/") }}ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                filebrowserFlashUploadUrl: '{{ URL::asset("/") }}ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            });
    </script>
@endsection