@extends("panel.layout.admin_layout")
@section("content")
    <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">Site Configuration</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;&nbsp;
            </li>
            <li class="hidden"><a href="#">Profile</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
            <li class="active">Site settings</li>
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
                                <form action="{{url('check_admin/site_settings')}}" method="post" class="form-horizontal"
                                      enctype="multipart/form-data">
                                    <h3>Site Settings</h3>
                                    {{ csrf_field() }}
                                    <div class="form-group"><label class="col-sm-3 control-label">Site Name</label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-9"><input type="text" class="form-control"
                                                                             name="site_name"
                                                                             value="{{get_config('site_name')}}"/></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"><label class="col-sm-3 control-label">Site Logo</label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-9"><input type="file" class="" name="site_logo"/>
                                                </div>
                                                <img src="{{url('uploads/logo')}}/{{get_config('site_logo')}}"
                                                     style="width: 150px;"/>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group"><label class="col-sm-3 control-label">Contact Mail</label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-9"><input type="text" name="contact_mail"
                                                                             value="{{get_config('contact_mail')}}"
                                                                             class="form-control"/></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"><label class="col-sm-3 control-label">Address</label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-9">
                                                    <textarea class="form-control"
                                                              name="address">{{get_config('address')}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group"><label class="col-sm-3 control-label">City</label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6"><input type="text" name="city"
                                                                             value="{{get_config('city')}}"
                                                                             class="form-control"/></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"><label class="col-sm-3 control-label">Provience</label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6"><input type="text" name="provience"
                                                                             value="{{get_config('provience')}}"
                                                                             class="form-control"/></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"><label class="col-sm-3 control-label">Country</label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6"><input type="text" name="country"
                                                                             value="{{get_config('country')}}"
                                                                             class="form-control"/></div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group"><label class="col-sm-3 control-label">Contact No</label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6"><input type="text" name="contact_no"
                                                                             value="{{get_config('contact_no')}}"
                                                                             class="form-control"/></div>
                                            </div>
                                        </div>
                                    </div>


                                    <hr/>

                                    <h3>Social Links</h3>

                                    <div class="form-group"><label class="col-sm-3 control-label">Facebook Url</label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6"><input type="url" name="facebook_url"
                                                                             value="{{get_config('facebook_url')}}"
                                                                             class="form-control"/></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"><label class="col-sm-3 control-label">Twitter Url</label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6"><input type="url" name="twitter_url"
                                                                             value="{{get_config('twitter_url')}}"
                                                                             class="form-control"/></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"><label class="col-sm-3 control-label">Google Url</label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6"><input type="url" name="google_url"
                                                                             value="{{get_config('google_url')}}"
                                                                             class="form-control"/></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"><label class="col-sm-3 control-label">LinkedIn Url</label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6"><input type="url" name="linkedin_url"
                                                                             value="{{get_config('linkedin_url')}}"
                                                                             class="form-control"/></div>
                                            </div>
                                        </div>
                                    </div>


                                    <hr/>

                                    <h3>Admin Address</h3>

                                    <div class="form-group"><label class="col-sm-3 control-label">ETH Address</label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-6"><input type="text"
                                                                             value="{{decrypt(get_config('eth_address'))}}"
                                                                             class="form-control" readonly="readonly"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                <!--  <h3>SMTP</h3>


                                              <div class="form-group"><label class="col-sm-3 control-label">SMTP Host</label>

                                                <div class="col-sm-9 controls">
                                                    <div class="row">
                                                        <div class="col-xs-6"><input type="text" name="smtp_host" value="{{get_config('smtp_host')}}" class="form-control"/></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group"><label class="col-sm-3 control-label">SMTP Port</label>

                                                <div class="col-sm-9 controls">
                                                    <div class="row">
                                                        <div class="col-xs-6"><input type="text" name="smtp_port" value="{{get_config('smtp_port')}}" class="form-control"/></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group"><label class="col-sm-3 control-label">SMTP Username</label>

                                                <div class="col-sm-9 controls">
                                                    <div class="row">
                                                        <div class="col-xs-6"><input type="text" name="smtp_email" value="{{get_config('smtp_email')}}" class="form-control"/></div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group"><label class="col-sm-3 control-label">SMTP Password</label>

                                                <div class="col-sm-9 controls">
                                                    <div class="row">
                                                        <div class="col-xs-6"><input type="text" name="smtp_password" value="{{get_config('smtp_password')}}" class="form-control"/></div>
                                                    </div>
                                                </div>
                                            </div> -->


                                    <hr/>

                                    <h3>Analytics script</h3>

                                    <div class="form-group"><label class="col-sm-3 control-label">Google Analytics
                                            Script</label>

                                        <div class="col-sm-9 controls">
                                            <div class="row">
                                                <div class="col-xs-9">
                                                    <textarea class="form-control"
                                                              name="google_analytics">{{get_config('google_analytics')}}</textarea>
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