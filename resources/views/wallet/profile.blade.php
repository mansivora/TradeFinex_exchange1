@extends("wallet.layout.admin_layout")
@section("content")
<div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
                <div class="page-header pull-left">
                    <div class="page-title">Wallet Profile</div>
                </div>
                <ol class="breadcrumb page-breadcrumb pull-right">
                    <li><i class="fa fa-home"></i>&nbsp;<a href="{{url('check_admin/home')}}">Home</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                    <li class="hidden"><a href="#">Profile</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                    <li class="active">Profile</li>
                </ol>
                <div class="clearfix"></div>
            </div>


            <div class="page-content">
  <div class="row">
                    <div class="col-md-12">

                     @include('wallet.alert')

                        <div class="row mtl">

                            <div class="col-md-12">

                                <div id="generalTabContent" class="tab-content">
                                    <div id="tab-edit" class="tab-pane fade in active">
                                        <form action="{{url('walletjey/profile')}}" method="post" class="form-horizontal">
                                        <h3>Account Setting</h3>
                                        {{ csrf_field() }}
                                            <div class="form-group"><label class="col-sm-3 control-label">Email *</label>

                                                <div class="col-sm-9 controls">
                                                    <div class="row">
                                                 <div class="col-xs-9"><input type="email" placeholder="email@yourcompany.com" class="form-control" name="admin_email" value="{{$result->email_id}}" /></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group"><label class="col-sm-3 control-label">Username *</label>

                                                <div class="col-sm-9 controls">
                                                    <div class="row">
                                                        <div class="col-xs-9"><input type="text" placeholder="username" name="admin_username" value="{{$result->CMB_username}}" class="form-control"/></div>
                                                    </div>
                                                </div>
                                            </div>


                                             <div class="form-group"><label class="col-sm-3 control-label">Phone</label>

                                                <div class="col-sm-9 controls">
                                                    <div class="row">
                                                        <div class="col-xs-9"><input type="text" placeholder="9876543210" name="" value="{{owndecrypt($result->phone)}}" class="form-control" disabled="disabled" /></div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group"><label class="col-sm-3 control-label">Country</label>

                                                <div class="col-sm-9 controls">
                                                    <div class="row">
                                                        <div class="col-xs-9"><input type="text" placeholder="India" name="admin_country" value="{{$result->country}}" class="form-control"/></div>
                                                    </div>
                                                </div>
                                            </div>


                                            <hr/>

                                            <h3>Change password</h3>

                                            <div class="form-group"><label class="col-sm-3 control-label">Current Password</label>

                                                <div class="col-sm-9 controls">
                                                    <div class="row">
                                                        <div class="col-xs-4"><input type="password" placeholder="Current Password" name="curr_pass" class="form-control" /></div>
                                                    </div>
                                                </div>
                                            </div>

                                             <div class="form-group"><label class="col-sm-3 control-label">New Password</label>

                                                <div class="col-sm-9 controls">
                                                    <div class="row">
                                                        <div class="col-xs-4"><input type="password" placeholder="New Password" name="password" class="form-control"/></div>
                                                    </div>
                                                </div>
                                            </div>

                                             <div class="form-group"><label class="col-sm-3 control-label">Confirm New Password</label>

                                                <div class="col-sm-9 controls">
                                                    <div class="row">
                                                        <div class="col-xs-4"><input type="password" placeholder="Confirm New Password" name="password_confirmation" class="form-control"/></div>
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