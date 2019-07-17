@extends('front.layout.front')
@section('css')
    <link type="text/css" rel="Stylesheet"
          href="https://ajax.microsoft.com/ajax/jquery.ui/1.8.6/themes/smoothness/jquery-ui.css"/>
    <style>
        * {
        . border-radius(0) !important;
        }

        #field {
            margin-bottom: 20px;
        }
    </style>
@endsection
@section('content')
    <div class="clearfix"></div>
    <div class="main-flex">


        <div class="main-content profile_content inner_content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="block-radius">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#profile-view">Profile</a></li>
                                        <li><a data-toggle="tab" href="#edit-profile">Edit Profile </a></li>
                                        <li><a data-toggle="tab" href="#change-password">Change Password </a></li>
                                        <li><a data-toggle="tab" href="#2fa">Security</a></li>
                                        <li><a data-toggle="tab" href="#kyc">KYC</a></li>
                                        <li><a data-toggle="tab" href="#referral">Referral</a></li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="profile-view" class="tab-pane fade  in active">
                                            <div class="flex-row">
                                                <div class="col-md-4 col-sm-5  border-right profile-part">
                                                    <div class="heading profile_heading flex-bit">
                                                        <h4>Profile Picture</h4>
                                                    </div>
                                                    <div class="profile custom-profile-avatar">
                                                        <!--<input class="upload-hidden" type="file">-->
                                                        <div class="profile-thumb">
                                                            <img src="{{URL::asset('uploads/users/profileimg')}}/{{$result->profile_image}}"
                                                                 alt="">
                                                        </div>
                                                        <h3>{{$result->first_name}}</h3>
                                                    {{--<h4>$ 0</h4>--}}
                                                    <!--<span class="d-block white">Click to Select</span>-->
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="col-md-6 col-sm-6  border-right profile-part">
                                                    <div class="heading profile_heading flex-bit">
                                                        <h4>Personal Information</h4>

                                                    </div>
                                                    <table id="ico" class="dt-responsive">
                                                        <tbody>
                                                        <tr>
                                                            <td><i class="fa fa-envelope custom-margin-font"
                                                                   aria-hidden="true"></i> Email -
                                                            </td>
                                                            <td>{{get_usermail($result->id)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><i class="fa fa-user-circle custom-margin-font"
                                                                   aria-hidden="true"></i> Full Name -
                                                            </td>
                                                            <td>{{$result->first_name}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><i class="fa fa-phone custom-margin-font"
                                                                   aria-hidden="true"></i> Contact No. -
                                                            </td>
                                                            <td>({{$result->mob_isd}}
                                                                ) {{owndecrypt($result->mobile_no)}}</td>

                                                        </tr>

                                                        <tr>
                                                            <td><i class="fas fa-globe custom-margin-font"></i> Country
                                                                -
                                                            </td>
                                                            <td>@if($result->country != 'null' || $result->country != ""){{get_country_name($result->country)}}@endif</td>
                                                        </tr>
                                                        {{--<tr>--}}
                                                        {{--<td><i class="fas fa-users custom-margin-font"></i> Referral Code -</td>--}}
                                                        {{--<td>{{$referral_code}}</td>--}}
                                                        {{--</tr>--}}

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-6 col-sm-6 profile-part">
                                                    <div class="heading profile_heading flex-bit">
                                                        <h4>Status</h4>
                                                    </div>
                                                    <table id="ico" class="dt-responsive">
                                                        <tbody>
                                                        <tr>
                                                            <td>Email Status</td>
                                                            @if($result->verify_status=='1')
                                                                <td>
                                                                    <img src="{{URL::asset('front')}}/assets/imgs/like.png"
                                                                         alt="like"/> <span
                                                                            class="green">Verified</span></td>
                                                            @else
                                                                <td>
                                                                    <img src="{{URL::asset('front')}}/assets/imgs/dislike.png"
                                                                         alt="dislike"/> <span
                                                                            class="red">Un-Verified</span></td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <td>Mobile Status</td>
                                                            @if($result->mobile_status=='1')
                                                                <td>
                                                                    <img src="{{URL::asset('front')}}/assets/imgs/like.png"
                                                                         alt="like"/> <span
                                                                            class="green">Verified</span></td>
                                                            @else
                                                                <td>
                                                                    <img src="{{URL::asset('front')}}/assets/imgs/dislike.png"
                                                                         alt="dislike"/> <span
                                                                            class="red">Un-Verified</span></td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <td>TFA Status</td>
                                                            @if($result->tfa_status=='enable')
                                                                <td><i class="fas fa-toggle-on"
                                                                       style="color:#76C9B1 !important;"></i><span
                                                                            class="green"> Enabled</span></td>
                                                            @else
                                                                <td><i class="fas fa-toggle-off"
                                                                       style="color:#F56F70 !important;"></i><span
                                                                            class="red"> Disabled</span></td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <td>KYC Status</td>
                                                            @if($result->document_status==0)
                                                                <td><i class="fas fa-hourglass-start"
                                                                       style="color:#e4a10d !important;"></i><span
                                                                            class="yellow"> Pending</span></td>
                                                            @elseif($result->document_status==1)
                                                                <td>
                                                                    <img src="{{URL::asset('front')}}/assets/imgs/like.png"
                                                                         alt="like"/><span
                                                                            class="green"> Approved</span></td>
                                                            @elseif($result->document_status==2)
                                                                <td><i class="fas fa-ban"
                                                                       style="color:#F56F70 !important;"></i><span
                                                                            class="red"> Rejected</span></td>
                                                            @elseif($result->document_status==3)
                                                                <td><i class="fas fa-paper-plane"
                                                                       style="color:#0484c7 !important;"></i><span
                                                                            class="blue"> Submitted</span></td>
                                                            @endif
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="edit-profile" class="tab-pane fade">
                                            <form id="profile-update" action="{{url('profile')}}" method="post"
                                                  enctype="multipart/form-data">
                                                {{csrf_field()}}
                                                <div class="flex-row">
                                                    <div class="col-md-3 col-sm-5  border-right profile-part">
                                                        <div class="heading profile_heading flex-bit">
                                                            <h4>Avatar</h4>
                                                        </div>
                                                        <div class="profile upload-file">
                                                            <input id="imageUpload" type="file" accept="image/*"
                                                                   name="imageUpload" class="upload-hidden"
                                                                   data-msg-accept="Only .jpg/.png images allowed.">
                                                            <div class="profile-thumb">
                                                                <img id="profileImage"
                                                                     src="{{URL::asset('uploads/users/profileimg')}}/{{$result->profile_image}}"
                                                                     alt="">
                                                            </div>
                                                            <span class="d-block black">Click to Select</span>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="col-md-9 col-sm-7 ">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="heading profile_heading flex-bit">
                                                                    <h4>Personal Information</h4>
                                                                </div>
                                                                <div class="row">
                                                                    {{--<div class="col-sm-12">--}}
                                                                    {{--<div class="form-group">--}}
                                                                    {{--<label >Referral Code</label>--}}
                                                                    {{--<input type="text" id="username" name="username" class="form-control" value="{{$result->referral_code}}">--}}
                                                                    {{--</div>--}}
                                                                    {{--</div>--}}
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label>Email</label>
                                                                            <input value="{{get_usermail($result->id)}}"
                                                                                   id="email_id" name="email_id"
                                                                                   type="text" class="form-control"
                                                                                   disabled>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label>Full Name</label>
                                                                            <input type="text" name="first_name"
                                                                                   class="form-control"
                                                                                   value="{{$result->first_name}}">
                                                                        </div>
                                                                    </div>
                                                                    {{--<div class="col-sm-6">--}}
                                                                    {{--<div class="form-group">--}}
                                                                    {{--<label >Last Name</label>--}}
                                                                    {{--<input type="text" name="last_name" class="form-control" value="{{$result->last_name}}">--}}
                                                                    {{--</div>--}}
                                                                    {{--</div>--}}
                                                                    <div class="clearfix"></div>
                                                                    <div class="col-xs-4">
                                                                        <div class="form-group">
                                                                            <label>Contact No.</label>
                                                                            <select name="isdcode" id="isdcode"
                                                                                    class="form-control">
                                                                                <option value="">ISD Code</option>
                                                                                @foreach($country as $val)
                                                                                    <option value="{{$val->phonecode}}"
                                                                                            @if($val->phonecode==$result->mob_isd) selected
                                                                                            @endif data-id="{{strtolower($val->iso)}}">
                                                                                        +{{$val->phonecode}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-8">
                                                                        <div class="form-group">
                                                                            <label> &nbsp;</label>
                                                                            <input type="text" id="telephone"
                                                                                   name="telephone" class="form-control"
                                                                                   value="{{owndecrypt($result->mobile_no)}}"
                                                                                   onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'>
                                                                            <span id="otp" class="send-otp"
                                                                                  @if($result->mobile_status!='1')style="display:;"
                                                                                  @else style="display: none;" @endif>
                                                                                <a href="#"
                                                                                   onclick="sendotp()">Send OTP</a></span>
                                                                            {{--<span id="edit" class="send-otp" @if($result->mobile_status!='1')style="display:none;" @else style="display:;" @endif><a href="#" id="oldedit"><i class="far fa-edit verify-old custom-margin-font" aria-hidden="true"></i></a></span>--}}
                                                                            <input type="hidden" name="change_number"
                                                                                   id="change_number"
                                                                                   class="form-control" value="0">
                                                                            <p id="phone_error" class="error" hidden>The
                                                                                number already exists.</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="heading profile_heading flex-bit">
                                                                    <h4>Address</h4>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="email">Street address</label>
                                                                            <input type="text" name="address"
                                                                                   class="form-control"
                                                                                   value="{{$result->address}}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="email">State</label>
                                                                            <input type="text" name="state"
                                                                                   class="form-control"
                                                                                   value="{{$result->state}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="email">City</label>
                                                                            <input type="text" name="city"
                                                                                   class="form-control"
                                                                                   value="{{$result->city}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="postal_code">Postal Code</label>
                                                                            <input type="text" name="postal_code"
                                                                                   class="form-control"
                                                                                   value="{{$result->postal_code}}"
                                                                                   onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="email">Country</label>
                                                                            <select name="country_id" id="country_id"
                                                                                    class="form-control">
                                                                                <option value="">Select Country</option>
                                                                                @foreach($country_name as $val)
                                                                                    <option value="{{$val->id}}"
                                                                                            @if($val->id==$result->country) selected
                                                                                            @endif data-id="{{strtolower($val->iso)}}">{{$val->nicename}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>


                                                                    <div class="col-sm-12">
                                                                        <div class="form-group text-right">
                                                                            <button type="submit"
                                                                                    class="btn yellow-btn min-width-btn">
                                                                                Save Changes
                                                                            </button>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                            </form>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="change-password" class="tab-pane fade">
                            <div class="flex-row">
                                <div class="col-md-4 col-md-offset-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="heading profile_heading flex-bit">
                                                <h4>Change Password</h4>
                                            </div>
                                            <form id="change_pass" action="{{url('/change_password')}}" method="post"
                                                  role="form">
                                                {{csrf_field()}}
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            {{--<label >Old Password</label>--}}
                                                            <input type="password" class="form-control"
                                                                   name="old_password" placeholder="Current Password">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            {{--<label >New Password</label>--}}
                                                            <input type="password" class="form-control" id="new_pass"
                                                                   name="password" placeholder="New Password">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            {{--<label >Re-type New Password</label>--}}
                                                            <input type="password" class="form-control"
                                                                   name="password_confirmation"
                                                                   placeholder="Re-type New Password">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group text-right">
                                                            <button type="submit" class="btn yellow-btn min-width-btn">
                                                                Save
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="2fa" class="tab-pane fade">
                            <div class="clearfix"></div>
                            <div class="main-flex">
                                <div class="main-content inner_content">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="panel panel-default panel-heading-space">

                                                <div class="panel-heading">Google Two-Factor Authentication</div>
                                                <div class="panel-body">
                                                    <div class="col-md-3 security-qrcode">
                                                        <img src="{{$tfa_url}}" class="mb20" alt="qrcode"/>
                                                    </div>
                                                    {{--<div class="col-md-9">--}}
                                                    {{--<p>We recommend to switch to Google Authenticator as more convenient, secure and faster method.In case of method change don't forget to enter new code and save selection.</p>--}}
                                                    {{--<p>--}}
                                                    {{--<span class="white-color">Q. What does Google Authenticator do?</span><br>--}}
                                                    {{--A. Google Authenticator is an application that implements two-step verification services using the Time-based One-time Password Algorithm(HOTP), for authenticating users of mobile application by Google.--}}
                                                    {{--</p>--}}
                                                    {{--<p>--}}
                                                    {{--<span class="white-color">Q. How do i install Google Authenticator?</span><br>--}}
                                                    {{--A. Android Users (Click) <span class="yellow"><a class="yellow custom-hover" href="https://tinyurl.com/coinspilotGAandroid" target="_blank">https://tinyurl.com/coinspilotGAandroid</a></span>   IOS Users(Click) <span class="yellow"><a class="yellow custom-hover" href="https://tinyurl.com/coinspilotGAios" target="_blank">https://tinyurl.com/coinspilotGAios</a></span>--}}
                                                    {{--</p>--}}
                                                    {{--<p>--}}
                                                    {{--<span class="white-color">Q. How do you use the QR code?</span><br>--}}
                                                    {{--A. Simple open the installed app, click "+" add button and scan the given QR to generate code.--}}
                                                    {{--</p>--}}
                                                    {{--</div>--}}
                                                    <div class="col-md-9">
                                                        <p>Google authenticator is an application that implements two
                                                            step verification
                                                            using a time based, one-time password algorithm. We
                                                            recommend users to
                                                            enable 2 factor authentication as it provides an extra layer
                                                            of security.</p>
                                                        <p>For Android Users: <span class="yellow"><a
                                                                        class="yellow custom-hover"
                                                                        href="https://tinyurl.com/coinspilotGAandroid"
                                                                        target="_blank">https://tinyurl.com/coinspilotGAandroid</a></span>
                                                        </p>
                                                        <p>For IOS Users: <span class="yellow"><a
                                                                        class="yellow custom-hover"
                                                                        href="https://tinyurl.com/coinspilotGAios"
                                                                        target="_blank">https://tinyurl.com/coinspilotGAios</a></span>
                                                        </p>
                                                        <p>To enable this feature:</p>
                                                        <p>1. Install Google Authenticator on your mobile device</p>
                                                        <p>2. Open up the Google Authenticator App</p>
                                                        <p>3. Click on + , and you will be prompted to scan a QR
                                                            Code</p>
                                                        <p>4. Scan the given QR code to generate the 2FA code</p>
                                                    </div>
                                                    <p class="col-md-12">Please save this Google Authentication Key for
                                                        your future reference.</p>
                                                    <div class="clearfix"></div>
                                                    <hr>
                                                    <form action="{{url('/security')}}" id="securityForm" method="post"
                                                          accept-charset="utf-8" novalidate="novalidate">
                                                        <div class="col-md-3">
                                                            {{csrf_field()}}
                                                            <div class="form-group custom-security">
                                                                GOOGLE AUTHENTICATION KEY
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group custom-security">
                                                                {{$secret_code}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <input class="form-control" name="onecode" id="onecode"
                                                                       placeholder=" 6 digit code"
                                                                       data-bv-field="onecode" type="text">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                @if($result->tfa_status=='disable')
                                                                    <button type="submit"
                                                                            class="btn yellow-btn min-width-btn min-width-btn-security">
                                                                        ENABLE SECURITY
                                                                    </button>
                                                                @else
                                                                    <button type="submit"
                                                                            class="btn yellow-btn min-width-btn min-width-btn-security">
                                                                        DISABLE SECURITY
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>


                                        </div>

                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div id="kyc" class="tab-pane fade">
                            @if($result->document_status=='1')
                                <div class="flex-row">
                                    <div class="col-md-12">
                                        <p style="text-align: center">Your KYC is Completed.</p>
                                    </div>
                                </div>
                            @else
                                <div class="flex-row">
                                    <div class="col-md-12">
                                        <div class="panel-heading-space">
                                            @if($result->document_status=='2')
                                                <p class="red" style="text-align: center">Your KYC was rejected. Please
                                                    check your mail for the reason.</p>
                                            @elseif($result->document_status=='3')
                                                <p class="green" style="text-align: center">Your KYC is under
                                                    verification. You can change the uploaded files if you wish to.</p>
                                            @endif
                                            <div><h5 class="blue-color" style="font-size: 18px !important;">Personal
                                                    Details</h5></div>
                                            <div class="panel-body">
                                                <form id="kycform" method="post" action="{{url('/kyc')}}"
                                                      enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <p>Please make sure you use your real Identity to do this
                                                                verification, We will protect your personal
                                                                information.</p>
                                                            <p>For completing KYC you can use any of the below mentioned
                                                                documents.</p>
                                                            <p style="font-weight: bold;" id="allowed_ids">1. Passport
                                                                &nbsp; &nbsp; 2. Driver's license &nbsp; &nbsp; 3.
                                                                National ID Card</p><br>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="firstname">First Name<span
                                                                            class="required">*</span></label>
                                                                <input type="text" id="first_name" class="form-control"
                                                                       placeholder="Enter First Name" name="first_name"
                                                                       @if(isset($verification)) @if(isset($verification->first_name)) value="{{$verification->first_name}}"
                                                                       @endif @else value="{{$result->first_name}}" @endif>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="lastname">Last Name<span
                                                                            class="required">*</span></label>
                                                                <input type="text" id="last_name" class="form-control"
                                                                       placeholder="Enter Last Name" name="last_name"
                                                                       @if(isset($verification)) @if(isset($verification->last_name)) value="{{$verification->last_name}}"
                                                                       @endif @else value="{{$result->last_name}}" @endif>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="gender"
                                                                       class="gender-main-label">Gender<span
                                                                            class="required">*</span></label>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="gender" value="M"
                                                                           class="gender-list"
                                                                           @if(isset($verification)) @if($verification->gender=='M')checked @endif @endif><span
                                                                            class="male-female">Male</span>
                                                                    {{--<span class="checkmark"></span>--}}
                                                                </label>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="gender" value="F"
                                                                           class="gender-list"
                                                                           @if(isset($verification)) @if($verification->gender=='F')checked @endif @endif><span
                                                                            class="male-female">Female</span>
                                                                    {{--<span class="checkmark"></span>--}}
                                                                </label>
                                                                <label for="gender" generated="true"
                                                                       class="error"></label>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="document">Document Number<span
                                                                            class="required">*</span></label>
                                                                <input type="text" class="form-control"
                                                                       placeholder="Enter Id" name="document_id"
                                                                       id="document_id"
                                                                       @if(isset($verification))value="{{$verification->national_id}}"@endif>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="country">Country And Territory</label><span
                                                                        class="required">*</span>
                                                                <select name="kyc_country_id" id="kyc_country_id"
                                                                        class="form-control">
                                                                    <option value="">Select Country</option>
                                                                    @foreach($country_name as $val)
                                                                        <option value="{{$val->id}}"
                                                                                @if(isset($verification)) @if($val->id==$verification->country_code) selected
                                                                                @endif @elseif($val->id==$result->country) selected
                                                                                @endif data-id="{{strtolower($val->iso)}}">{{$val->nicename}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="dob">Date of Birth<span
                                                                            class="required">*</span><span
                                                                            style="font-size: 9px; opacity: 0.8; color:red;"> Age should be greater than 18 years.</span></label>
                                                                <input id="dob" type="text" name="dob"
                                                                       readonly="readonly" placeholder="Date of Birth"
                                                                       class="form-control"
                                                                       @if(isset($verification))value="{{$verification->dob}}"@endif>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="col-md-12">
                                                                <label for="documentfile"><span id="doc1_text">Front of Passport, Driver's License or National ID.</span><span
                                                                            class="required">*</span></label>&nbsp;&nbsp;
                                                                <small class="instraction">Please make sure that the
                                                                    photo is complete and clearly visible, in JPG/PNG
                                                                    format and the file size should not exceed 3 MB.
                                                                </small>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    @if(isset($verification))
                                                                        @if($verification->proof1!='')
                                                                            <div>
                                                                                <canvas id="cf_side" width="350"
                                                                                        height="250"
                                                                                        style="border:1px     solid #d3d3d3;background:#2B3542;display: none"></canvas>
                                                                                <img id="df_side"
                                                                                     src="https://alphaex.net/src.php?name={{$verification->proof1}}"
                                                                                     style="width: 350px;height: 250px; display:block">
                                                                            </div>
                                                                            <label class="custom-file-input custom-upload"
                                                                                   style="margin-top: 10px;">
                                                                                <input id="f_side" type="file"
                                                                                       accept="image/jpg,image/jpeg,image/png"
                                                                                       name="f_side"
                                                                                       data-msg-accept="Only .jpg/.png images allowed.">
                                                                            </label>
                                                                        @endif
                                                                    @else
                                                                    <!--<input type="file" class="form-control documentfile" name="documentfile">-->
                                                                        <canvas id="cf_side" width="350" height="250"
                                                                                style="border:1px     solid #d3d3d3;background:#2B3542;display: none"></canvas>
                                                                        <div id="df_side"
                                                                             class="form-control documentfile"
                                                                             style="display: block">
                                                                        </div>
                                                                        <label class="custom-file-input custom-upload"
                                                                               style="margin-top: 10px;">
                                                                            <input id="f_side" type="file"
                                                                                   accept="image/jpg,image/jpeg,image/png"
                                                                                   name="f_side"
                                                                                   data-msg-accept="Only .jpg/.png images allowed."
                                                                                   required
                                                                                   data-msg-required="Front side required.">
                                                                        </label>
                                                                    @endif
                                                                    {{--<label  class="custom-file-input custom-upload" style="margin-top: 10px;">--}}
                                                                    {{--<input id="f_side" type="file" accept="image/jpg,image/jpeg,image/png" name="f_side" data-msg-accept="Only .jpg/.png images allowed.">--}}
                                                                    {{--</label>--}}
                                                                    <label id="f_side-error" class="error" for="f_side"
                                                                           hidden></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group example-main">
                                                        <span class="example">
                                                            <img src="{{URL::asset('front')}}/assets/imgs/example-arrow.png"
                                                                 alt="example-arrow"/>
                                                            <span class="example-text">Example</span>
                                                            <img src="{{URL::asset('front')}}/assets/imgs/example-arrow.png"
                                                                 alt="example-arrow"/>
                                                        </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="form-group kyc-doc-cenrer">
                                                                    <img id="doc1" class="kyc-doc1"
                                                                         src="{{URL::asset('front')}}/assets/imgs/doc1.png"
                                                                         alt="doc1"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <hr>
                                                        <div class="col-md-12">
                                                            <div class="col-md-12">
                                                                <label for="documentfile1"><span id="doc2_text">Back of Passport ID page, Driver's License or National ID.</span><span
                                                                            class="required">*</span></label>
                                                                <small class="instraction">Please make sure that the
                                                                    photo is complete and clearly visible, in JPG/PNG
                                                                    format and the file size should not exceed 3 MB.
                                                                </small>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    @if(isset($verification))
                                                                        @if($verification->proof2!='')
                                                                            <div>
                                                                                <canvas id="cb_side" width="350"
                                                                                        height="250"
                                                                                        style="border:1px solid #d3d3d3;background:#2B3542;display: none"></canvas>
                                                                                <img id="db_side"
                                                                                     src="https://alphaex.net/src.php?name={{$verification->proof2}}"
                                                                                     style="width: 350px;height: 250px; display:block">
                                                                            </div>
                                                                            <label class="custom-file-input custom-upload"
                                                                                   style="margin-top: 10px;">
                                                                                <input id="b_side" type="file"
                                                                                       name="b_side"
                                                                                       accept="image/jpg,image/jpeg,image/png"
                                                                                       data-msg-accept="Only .jpg/.png images allowed.">
                                                                            </label>
                                                                        @endif
                                                                    @else
                                                                    <!--<input type="file" class="form-control documentfile" name="documentfile">-->
                                                                        <canvas id="cb_side" width="350" height="250"
                                                                                style="border:1px     solid #d3d3d3;background:#2B3542;display: none"></canvas>
                                                                        <div id="db_side"
                                                                             class="form-control documentfile"
                                                                             style="display: block">
                                                                        </div>
                                                                        <label class="custom-file-input custom-upload"
                                                                               style="margin-top: 10px;">
                                                                            <input id="b_side" type="file" name="b_side"
                                                                                   accept="image/jpg,image/jpeg,image/png"
                                                                                   data-msg-accept="Only .jpg/.png images allowed."
                                                                                   required
                                                                                   data-msg-required="Back side required.">
                                                                        </label>
                                                                    @endif
                                                                    {{--<label  class="custom-file-input custom-upload" style="margin-top: 10px;">--}}
                                                                    {{--<input id="b_side" type="file" name="b_side" accept="image/jpg,image/jpeg,image/png" data-msg-accept="Only .jpg/.png images allowed.">--}}
                                                                    {{--</label>--}}
                                                                    <label id="b_side-error" class="error" for="b_side"
                                                                           hidden></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group example-main">
                                                        <span class="example">
                                                            <img src="{{URL::asset('front')}}/assets/imgs/example-arrow.png"
                                                                 alt="example-arrow"/>
                                                            <span class="example-text">Example</span>
                                                            <img src="{{URL::asset('front')}}/assets/imgs/example-arrow.png"
                                                                 alt="example-arrow"/>
                                                        </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="form-group kyc-doc-cenrer">
                                                                    <img id="doc2" class="kyc-doc1"
                                                                         src="{{URL::asset('front')}}/assets/imgs/doc2.png"
                                                                         alt="doc2"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="documentfile2"><span id="doc3_text">Selfie with note and ID using either the front of your National ID, Passport or Driver’s License.</span><span
                                                                        class="required">*</span></label>
                                                            <small class="instraction">Please make sure that the photo
                                                                is complete and clearly visible, in JPG/PNG format and
                                                                the file size should not exceed 3 MB.<br>Please provide
                                                                a photo of you holding your Identity card.<br> In the
                                                                same picture, make a reference
                                                                to {{get_config('site_name')}} and today's
                                                                date displayed.<br> Make sure your face is clearly
                                                                visible and that all Identity care details are clearly
                                                                readable.
                                                            </small>
                                                            <ul class="kyc-list">
                                                                <li>Face clearly visible</li>
                                                                <li>Note with word '{{get_config('site_name')}}'</li>
                                                            </ul>
                                                            <ul class="kyc-list1">
                                                                <li>Photo ID clearly visible</li>
                                                                <li>Note with today's date</li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                @if(isset($verification))
                                                                    @if($verification->proof3!='')
                                                                        <div>
                                                                            <canvas id="ch_side" width="350"
                                                                                    height="250"
                                                                                    style="border:1px     solid #d3d3d3;background:#2B3542;display: none"></canvas>
                                                                            <img id="dh_side"
                                                                                 src="https://alphaex.net/src.php?name={{$verification->proof3}}"
                                                                                 style="width: 350px;height: 250px; display:block">
                                                                        </div>
                                                                        <label class="custom-file-input custom-upload"
                                                                               style="margin-top: 10px;">
                                                                            <input id="h_side" type="file" name="h_side"
                                                                                   accept="image/jpg,image/jpeg,image/png"
                                                                                   data-msg-accept="Only .jpg/.png images allowed.">
                                                                        </label>
                                                                    @endif
                                                                @else
                                                                <!--<input type="file" class="form-control documentfile" name="documentfile">-->
                                                                    <canvas id="ch_side" width="350" height="250"
                                                                            style="border:1px     solid #d3d3d3;background:#2B3542;display: none"></canvas>
                                                                    <div id="dh_side" class="form-control documentfile"
                                                                         style="display: block">
                                                                    </div>
                                                                    <label class="custom-file-input custom-upload"
                                                                           style="margin-top: 10px;">
                                                                        <input id="h_side" type="file" name="h_side"
                                                                               accept="image/jpg,image/jpeg,image/png"
                                                                               data-msg-accept="Only .jpg/.png images allowed."
                                                                               required
                                                                               data-msg-required="Selfie with ID required.">
                                                                    </label>
                                                                @endif
                                                                {{--<label  class="custom-file-input custom-upload" style="margin-top: 10px;">--}}
                                                                {{--<input id="h_side" type="file" name="h_side" accept="image/jpg,image/jpeg,image/png" data-msg-accept="Only .jpg/.png images allowed.">--}}
                                                                {{--</label>--}}
                                                                <label id="h_side-error" class="error" for="h_side"
                                                                       hidden></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group example-main">
                                                    <span class="example">
                                                        <img src="{{URL::asset('front')}}/assets/imgs/example-arrow.png"
                                                             alt="example-arrow"/>
                                                        <span class="example-text">Example</span>
                                                        <img src="{{URL::asset('front')}}/assets/imgs/example-arrow.png"
                                                             alt="example-arrow"/>
                                                    </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="form-group kyc-doc-cenrer">
                                                                <img id="doc3" class="kyc-doc1"
                                                                     src="{{URL::asset('front')}}/assets/imgs/doc3.png"
                                                                     alt="doc3"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 col-sm-offset-8">
                                                            <div class="form-group text-right">
                                                                <button type="submit"
                                                                        class="btn btn-primary yellow-btn min-width-btn">
                                                                    Submit
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="clearfix"></div>

                        <div id="referral" class="tab-pane fade">
                            <div class="clearfix"></div>
                            <div class="main-flex">
                                <div class="main-content inner_content">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="panel-default panel-heading-space">

                                                <div class="panel-heading" style="padding-top: 0px !important;">Referral
                                                    Program
                                                </div>
                                                <div class="panel-body">
                                                    <div class="col-md-12">
                                                        @if($result->document_status!='1')
                                                            <div style="padding-left:15px; font-size: 14px; color: red !important;">
                                                                You need to complete your KYC before you can refer any
                                                                one.
                                                            </div>
                                                        @else
                                                            <div style="padding-left: 15px; font-size: 16px;"><p>
                                                                    <strong><i class="fas fa-users custom-margin-font"></i>
                                                                        Referral Code : </strong></p>
                                                                <div class="row">
                                                                    <form id="referral_form" method="post"
                                                                          action="{{url('/referral')}}">
                                                                        {{csrf_field()}}
                                                                        <div class="col-sm-3">
                                                                            <div class="form-group">
                                                                                {{--<label >Referral Code</label>--}}
                                                                                <input type="text" id="username"
                                                                                       name="username"
                                                                                       class="form-control"
                                                                                       value="{{$result->referral_code}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="clearfix"></div>
                                                                        <div class="col-sm-3">
                                                                            <div class="form-group">
                                                                                {{--<button type="submit" class="btn yellow-btn min-width-btn">Update</button>--}}
                                                                                <button type="submit" value="send"
                                                                                        class="btn btn-primary"
                                                                                        style="margin-left: 15px">Update
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                            <div class="panel-default panel-heading-space">
                                                                <div class="panel-heading"
                                                                     style="padding-left:15px !important;">Send
                                                                    Referrals :
                                                                </div>
                                                            </div>

                                                            <div class="container">
                                                                <div class="row">
                                                                    <form method="post"
                                                                          action="{{url('/sendreferral')}}">
                                                                        {{csrf_field()}}
                                                                        <div id="field" class="col-md-6"><input
                                                                                    class=" input " id="mytext[]"
                                                                                    name="mytext[]" type="email"
                                                                                    placeholder="Enter Email"
                                                                                    style="width:50%; padding: 10px"/>
                                                                            <i class="fas fa-plus add-more input"
                                                                               style="font-size: 150%"></i>
                                                                        </div>
                                                                        <div class="clearfix"></div>
                                                                        <button type="submit" value="send"
                                                                                class="btn btn-primary"
                                                                                style="margin-left: 15px">Send
                                                                        </button>
                                                                    </form>
                                                                    <br>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="panel-default panel-heading-space">
                                                                        <div class="panel-heading"
                                                                             style="padding-left: 15px !important;">
                                                                            Referral History
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <table id="referral"
                                                                                   class="table table-striped table-bordered dt-responsive nowrap"
                                                                                   style="width:100%">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>Date & Time</th>
                                                                                    <th>Referred User</th>
                                                                                    {{--<th>referred Email</th>--}}
                                                                                    <th>Bonus</th>
                                                                                    <th>Currency</th>
                                                                                    <th>Status</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                @if(isset($referred))
                                                                                    @foreach($referred as $val)
                                                                                        <tr>
                                                                                            <td>{{$val->updated_at}}</td>
                                                                                            <td>{{$val->referrer_name}}</td>
                                                                                            {{--<td>{{$val->referrer_email}}</td>--}}
                                                                                            <td>{{$val->referred_bonus}}</td>
                                                                                            <td>{{$val->currency}}</td>
                                                                                            <td>
                                                                                                @if($val->referred_status == 1)
                                                                                                    Bonus added.
                                                                                                @elseif($val->referred_status == 0)
                                                                                                    Your KYC Pending.
                                                                                                @endif
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                @endif

                                                                                @if(isset($referrer))
                                                                                    @foreach($referrer as $val)
                                                                                        <tr>
                                                                                            <td>{{$val->updated_at}}</td>
                                                                                            <td>{{$val->referred_name}}</td>
                                                                                            {{--<td>{{$val->referred_email}}</td>--}}
                                                                                            <td>{{$val->referrer_bonus}}</td>
                                                                                            <td>{{$val->currency}}</td>
                                                                                            <td>@if($val->referrer_status == 1 && $val->referred_status == 1)
                                                                                                    Bonus added.
                                                                                                @elseif($val->referred_status == 0)
                                                                                                    User KYC Pending.
                                                                                                @elseif($val->referrer_status == 0)
                                                                                                    Your KYC Pending.
                                                                                                @endif
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                @endif
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="clearfix"></div>

                                                </div>
                                            </div>


                                        </div>

                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    <div class="clearfix"></div>
    </div>
    <div id="modal-otp" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content custom-modal-background text-center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal-title">Mobile Number Verification</h4>
                </div>
                <div id="otp_message"></div>
                <div class="modal-body">
                    {{--<form class="form-horizontal" id="otp_form">--}}
                    {{--{{csrf_field()}}--}}
                    <div class="form-group"><label class="control-label col-md-3">Enter Verification code</label>

                        <div class="col-md-9"><input id="verify_code" class="form-control" name="verify_code"
                                                     type="text">
                            <br>
                            <div id="countdown">
                                <span style="float:left">OTP message sent to your mobile number. Resend Link: </span>
                                <div id="minutes" style="float:left;color: red">00</div>
                                <div style="float:left">:</div>
                                <div id="seconds" style="float:left;color: red">00</div>
                            </div>
                            <div id="aftercount" style="display:none;">OTP via call:&nbsp;<a href="#"
                                                                                             onclick="otp_call()"
                                                                                             style="color: lightblue">Click
                                    Here</a></div>
                            <div id="aftercount_msg" style="display:none;">*If you do not recieve OTP within 15 minutes
                                please contact support
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-cancel min-width-btn" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn yellow-btn min-width-btn" onclick="verify_otp(event)">Submit
                        </button>&nbsp;&nbsp;
                    </div>
                    <div class="modal-footer">
                        <label><strong>Note:</strong> &nbsp;Withdrawal cannot be done until mobile number is
                            verified.<br>&nbsp;</label>
                    </div>
                    <div>
                        <p class="otperror text-center alert alert-danger hidden"></p>
                    </div>
                    {{--</form>--}}
                </div>
            </div>
        </div>
    </div>


@endsection
@section('xscript')

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    {{--form validation--}}
    <script type="text/javascript">
        var message = "";
        $.validator.addMethod(
            "regex",
            function (value, element, regexp) {
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            },
            "Number Not valid."
        );

        $('#profile-update').validate({
            rules:
                {
                    // username:{required:true,minlength:2,regex:"^[a-zA-Z0-9]*$"},
                    first_name: {required: true, minlength: 1, regex: "^(?!.*\\s{2,})(?!^ )[A-Za-z\\s]{1,500}$"},
                    // last_name: {required:true,minlength:1,regex:"^(?!.*\\s{2,})(?!^ )[A-Za-z\\s]{1,50}$"},
                    isdcode: {required: true},
                    telephone: {required: true, number: true, regex: "^[1-9][0-9]*$"},
                    // address:{required:true,regex:"(?!^ +$)^.+$"},
                    // state:{required:true,regex:"(?!^ +$)^.+$"},
                    // city:{required:true,regex:"(?!^ +$)^.+$"},
                },
            messages:
                {
                    // username:{required:'Username is required',minlength:'Username should contain atleast two alphabets',regex:'Only alphabets and numbers allowed.'},
                    first_name: {
                        required: 'Name is required',
                        minlength: 'Name should contain atleast one alphabet',
                        regex: 'Only alphabets allowed, it should not start with space and no more than one space consecutively.'
                    },
                    // last_name:{required:'Last name is required',minlength:'Last name should contain atleast one alphabet',regex:'Only alphabets allowed and it should not start with space.'},
                    isdcode: {required: 'ISD code is required.'},
                    telephone: {
                        required: 'Mobile number is required',
                        number: 'Digit only allowed',
                        regex: 'Number not valid should not start with zero'
                    },
                    // address:{required:'Address is required',regex:"Only spaces not allowed."},
                    // state:{required:'State is required',regex:"Only spaces not allowed."},
                    // city:{required:'City is required',regex:"Only spaces not allowed."},
                },
        });

        $('#referral_form').validate({
            rules:
                {
                    username: {required: true, minlength: 2, regex: "^[a-zA-Z0-9]*$"},
                },
            messages:
                {
                    username: {
                        required: 'Referral Code is required',
                        minlength: 'Referral Code should contain atleast two alphabets',
                        regex: 'Only alphabets and numbers allowed.'
                    },
                },
        })
    </script>

    <script type="text/javascript">
        $("#change_pass").validate({
            rules:
                {
                    old_password: {
                        required: true,
                        remote: {
                            url: "{{url('ajax/checkoldpass')}}",
                            type: 'post',
                            data: {'_token': "{{ csrf_token() }}"}
                        }
                    },
                    password: {
                        required: true,
                        minlength: 8,
                        noSpace: true,
                        pwcheckallowedchars: true,
                        pwcheckspechars: true,
                        pwcheckuppercase: true,
                        pwchecklowercase: true,
                        maxlength: 25,
                    },
                    password_confirmation: {required: true, equalTo: '#new_pass',},
                },
            messages:
                {
                    old_password: {required: 'Old Password is required', remote: 'Old password is wrong',},
                    password: {
                        required: 'Password is required',
                        minlength: 'Minimum 8 characters are required',
                        maxlength: 'Password cannot contain more than 25 characters'
                    },
                    password_confirmation: {
                        required: 'Confirm password is required',
                        equalTo: 'Password does not match',
                    },
                },
        });

        jQuery.validator.addMethod("noSpace", function (value, element) {
            return value.indexOf(" ") < 0 && value != "";
        }, "No space please and don't leave it empty");

        jQuery.validator.addMethod("pwcheckallowedchars", function (value) {
            return /^[a-zA-Z0-9!@#$%^&*()_=\[\]{};':"\\|,.<>\/?+-]+$/.test(value) // has only allowed chars letter
        }, "The password contains non-admitted characters");

        jQuery.validator.addMethod("pwcheckspechars", function (value) {
            return /[!@#$%^&*()_=\[\]{};':"\\|,.<>\/?+-]/.test(value)
        }, "The password must contain at least one special character");

        jQuery.validator.addMethod("pwcheckuppercase", function (value) {
            return /[A-Z]/.test(value) // has an uppercase letter
        }, "The password must contain at least one uppercase letter");

        jQuery.validator.addMethod("pwchecklowercase", function (value) {
            return /[a-z]/.test(value) // has an uppercase letter
        }, "The password must contain at least one lowercase letter");

    </script>

    <script type="text/javascript">
        $("#securityForm").validate({
            rules:
                {
                    onecode: {required: true, number: true,},
                },
            messages:
                {
                    onecode: {required: 'Please enter otp code', number: 'Enter digit only',},
                },
        });
    </script>

    <script>
        $.validator.addMethod('filesize', function (value, element, arg) {

            return this.optional(element) || (element.files[0].size <= arg)
        }, 'File size must be less than {0}');

        $('#kycform').validate({
            rules:
                {
                    first_name: {required: true, minlength: 1, regex: "^(?!.*\\s{2,})(?!^ )[A-Za-z\\s]{1,500}$"},
                    last_name: {required: true, minlength: 1, regex: "^(?!.*\\s{2,})(?!^ )[A-Za-z\\s]{1,50}$"},
                    kyc_country_id: {required: true},
                    document_id: {required: true, regex: "^[a-zA-Z0-9_]*$"},
                    doc_id: {required: true, regex: "(?!^ +$)^.+$"},
                    f_side: {filesize: 3145728},
                    b_side: {filesize: 3145728},
                    h_side: {filesize: 3145728},
                    gender: {required: true}
                },
            messages:
                {
                    first_name: {
                        required: 'Name  is required',
                        minlength: 'Name should contain atleast one alphabet',
                        regex: 'Only alphabets allowed, it should not start with space and no more than one space consecutively.'
                    },
                    last_name: {
                        required: 'Last Name  is required',
                        minlength: 'Last name should contain atleast one alphabet',
                        regex: 'Only alphabets allowed and it should not start with space.'
                    },
                    kyc_country_id: {required: 'Country  is required'},
                    document_id: {required: 'Document Number is required', regex: 'No special characters allowed.'},
                    dob: {required: 'Date of Birth  is required'},
                    f_side: {filesize: "Maximum size is 3mb"},
                    b_side: {filesize: "Maximum size is 3mb"},
                    h_side: {filesize: "Maximum size is 3mb"},
                    gender: {required: 'Please select a gender'}
                }
        });
    </script>

    <script>
        $(function () {
            $("#dob").datepicker({
                    changeMonth: true,
                    changeYear: true, yearRange: "1910:+0", minDate: '01/01/1910', maxDate: '-18Y', dateFormat: 'dd/mm/yy'
                }
            );
        });

        $('#profile-update').on('submit', function () {
            $('#isdcode').prop('disabled', false);
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.bar-toggle').on('click', function () {
                $('.leftbar').toggleClass('open');
            });

            $('#telephone').change(function () {
                var old_no = {{owndecrypt($result->mobile_no)}};
                var new_no = $('#telephone').val();
                if (old_no != new_no) {
                    $('#otp').css('display', '');
                    $('#edit').css('display', 'none');
                }
                else {
                    $('#otp').css('display', 'none');
                    $('#edit').css('display', '');
                }
            });

            $('#username').change(function () {
                var validator = $("#referral_form").validate();
                var username = $('#username').val();
                $.ajax({
                    // async : false,
                    url: '/ajax/check_username',
                    method: 'post',
                    data: {'username': username, 'user_id':{{$result->id}} },
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.status == 200) {
                        }
                        else {
                            errors = {username: "Referral code already in use, please try a different referral code."};
                            validator.showErrors(errors);
                        }
                    }
                });
            });

            if ($('#kyc_country_id').val() == 192) {
                $('#doc1').attr('src', "{{URL::asset('front')}}/assets/imgs/doc1.png");
                $('#doc2').attr('src', "{{URL::asset('front')}}/assets/imgs/doc2.png");
                $('#doc3').attr('src', "{{URL::asset('front')}}/assets/imgs/doc3.png");
                $('#allowed_ids').html('1. National ID Card');
                $('#doc1_text').text('Front of National ID.');
                $('#doc2_text').text('Back of National ID.');
                $('#doc3_text').text('Selfie with note and ID using the front of your National ID.');
            }
            else {
                $('#doc1').attr('src', "{{URL::asset('front')}}/assets/imgs/doc1.png");
                $('#doc2').attr('src', "{{URL::asset('front')}}/assets/imgs/doc2.png");
                $('#doc3').attr('src', "{{URL::asset('front')}}/assets/imgs/doc3.png");
                $('#allowed_ids').html('1. Passport &nbsp; &nbsp; 2. Driver\'s license &nbsp; &nbsp; 3. National ID Card');
                $('#doc1_text').text('Front of Passport, Driver\'s License or National ID.');
                $('#doc2_text').text('Back of Passport ID page, Driver\'s License or National ID.');
                $('#doc3_text').text('Selfie with note and ID using either the front of your National ID, Passport or Driver’s License.');
            }

            $('#kyc_country_id').change(function () {
                if ($('#kyc_country_id').val() == 192) {
                    $('#doc1').attr('src', "{{URL::asset('front')}}/assets/imgs/doc1.png");
                    $('#doc2').attr('src', "{{URL::asset('front')}}/assets/imgs/doc2.png");
                    $('#doc3').attr('src', "{{URL::asset('front')}}/assets/imgs/doc3.png");
                    $('#allowed_ids').html('1. National ID Card');
                    $('#doc1_text').text('Front of National ID.');
                    $('#doc2_text').text('Back of National ID.');
                    $('#doc3_text').text('Selfie with note and ID using the front of your National ID.');
                }
                else {
                    $('#doc1').attr('src', "{{URL::asset('front')}}/assets/imgs/doc1.png");
                    $('#doc2').attr('src', "{{URL::asset('front')}}/assets/imgs/doc2.png");
                    $('#doc3').attr('src', "{{URL::asset('front')}}/assets/imgs/doc3.png");
                    $('#allowed_ids').html('1. Passport &nbsp; &nbsp; 2. Driver\'s license &nbsp; &nbsp; 3. National ID Card');
                    $('#doc1_text').text('Front of Passport, Driver\'s License or National ID.');
                    $('#doc2_text').text('Back of Passport ID page, Driver\'s License or National ID.');
                    $('#doc3_text').text('Selfie with note and ID using either the front of your National ID, Passport or Driver’s License.');
                }
            });

        });

        $('#referral').DataTable(
            {
                "ordering": false,
                "pageLength": 10,
                "lengthChange": false,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                "bInfo": false,
            });

    </script>

    {{--edit profile image--}}
    <script>
        function previewProfileImage(uploader) {
            //ensure a file was selected
            if (uploader.files && uploader.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var img = new Image();
                    img.src = e.target.result;
                    //set the image data as source
                    $('#profileImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(uploader.files[0]);
            }
        }

        $("#imageUpload").change(function () {
            previewProfileImage(this);
        });
    </script>

    <script>
        function el(id) {
            return document.getElementById(id);
        } // Get elem by ID

        var canvas1 = el("cf_side");
        var image1 = el("df_side");
        var context1 = canvas1.getContext("2d");

        function readImage1() {

            canvas1.style.display = 'block';
            image1.style.display = 'none';

            if (this.files && this.files[0]) {
                var FR = new FileReader();
                FR.onload = function (e) {
                    var img = new Image();

                    img.addEventListener("load", function () {
                        var x = 0;
                        var y = 0;
                        var width = 350;
                        var height = 250;

                        context1.drawImage(img, x, y, width, height);
                    });
                    img.src = e.target.result;
                };
                FR.readAsDataURL(this.files[0]);
            }
        }

        el("f_side").addEventListener("change", readImage1, false);
    </script>

    <script>
        function el(id) {
            return document.getElementById(id);
        } // Get elem by ID

        var canvas2 = el("cb_side");
        var context2 = canvas2.getContext("2d");
        var image2 = el("db_side");

        function readImage2() {
            canvas2.style.display = 'block';
            image2.style.display = 'none';

            if (this.files && this.files[0]) {
                var FR = new FileReader();
                FR.onload = function (e) {
                    var img = new Image();

                    img.addEventListener("load", function () {
                        var x = 0;
                        var y = 0;
                        var width = 350;
                        var height = 250;

                        context2.drawImage(img, x, y, width, height);
                    });
                    img.src = e.target.result;
                };
                FR.readAsDataURL(this.files[0]);
            }
        }

        el("b_side").addEventListener("change", readImage2, false);
    </script>

    <script>
        function el(id) {
            return document.getElementById(id);
        } // Get elem by ID

        var canvas3 = el("ch_side");
        var context3 = canvas3.getContext("2d");
        var image3 = el("dh_side");

        function readImage3() {
            canvas3.style.display = 'block';
            image3.style.display = 'none';

            if (this.files && this.files[0]) {
                var FR = new FileReader();
                FR.onload = function (e) {
                    var img = new Image();

                    img.addEventListener("load", function () {
                        var x = 0;
                        var y = 0;
                        var width = 350;
                        var height = 250;
                        context3.drawImage(img, x, y, width, height);
                    });
                    img.src = e.target.result;
                };
                FR.readAsDataURL(this.files[0]);
            }
        }

        el("h_side").addEventListener("change", readImage3, false);
    </script>

    <script>
        function linkactivate() {
            try {
                var sTime = new Date().getTime();
                var countDown = 30;

                function UpdateTime() {
                    var cTime = new Date().getTime();
                    var diff = cTime - sTime;
                    var seconds = countDown - Math.floor(diff / 1000);
                    if (seconds >= 0) {
                        var minutes = Math.floor(seconds / 60);
                        seconds -= minutes * 60;
                        $("#minutes").text(minutes < 10 ? "0" + minutes : minutes);
                        $("#seconds").text(seconds < 10 ? "0" + seconds : seconds);
                    } else {
                        $("#countdown").hide();
                        $("#aftercount").show();
                        $('#aftercount_msg').show();
                        clearInterval(counter);
                    }
                }

                UpdateTime();
                var counter = setInterval(UpdateTime, 500);

            }
            catch (e) {
                console.log(e);
            }
        }
    </script>

    <script type="text/javascript">

        function sendotp() {

            var isdcode = $("#isdcode").val();
            var mobile = $("#telephone").val();
            var email = $('#email_id').val();
            var user_id = {{$result->id}};
            if (mobile != '' && isdcode != '' && isdcode != '0') {
                $.ajax({
                    url: '{{url("ajax/checkphone")}}',
                    method: 'post',
                    data: {'mobile_no': mobile, 'user_id': user_id},
                    success: function (data) {
                        obj = JSON.parse(data);
                        if (obj.message == '1') {
                            $('#modal-otp').modal('hide');
                            $('#phone_error').show().delay(5000).fadeOut();
                        }
                        else {
                            $.ajax({
                                url: '{{url("ajax/registerotp")}}',
                                method: 'post',
                                data: {'isdcode': isdcode, 'phone': mobile, 'reg_email': email, 'type': 'Update'},
                                success: function (output) {
                                    obj = JSON.parse(output);
                                    if (obj.status == '1') {
                                        $('#aftercount_msg').hide();
                                        $("#aftercount").hide();
                                        $("#countdown").show();
                                        $('#modal-otp').modal('show');
                                        linkactivate();
                                        // $('#otp_msg1').delay(30000).fadeIn();
                                    }
                                    else {
                                        $("#otp_msg").html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + obj.sms + '</div>')
                                    }
                                }
                            });
                        }
                    }
                });
            }
            else {
                var $validator = $("#profile-update").validate();
                if (mobile != '') {
                    errors = {isdcode: "ISD code is required."};
                }
                else if (isdcode != '') {
                    errors = {telephone: "Phone number is required."};
                }
                else if (isdcode == '0') {
                    errors = {isdcode: "ISD code cannot be 0."};
                }
                else {
                    errors = {telephone: "Phone number is required.", isdcode: "ISD code is required."};
                }
                $validator.showErrors(errors);
            }
        }
    </script>

    <script type="text/javascript">
        function otp_call() {
            var isdcode = $("#isdcode").val();
            var mobile = $("#telephone").val();
            $.ajax({
                url: '{{url("ajax/otpcall")}}',
                method: 'post',
                data: {'mobile': mobile, 'isdcode': isdcode},
                success: function (data) {
                    document.getElementById('aftercount').innerHTML = 'Call request have been placed';
                }
            });
        }
    </script>

    <script>
        function verify_otp() {
            event.preventDefault();
            var code = $('#verify_code').val();
            var mobile = $('#telephone').val();
                    {{--var old_no = {{owndecrypt($result->mobile_no)}};--}}
            var user_id ={{$result->id}};
            $.ajax({
                url: '{{url("ajax/verify_otp")}}',
                method: 'post',
                data: {'verify_code': code, 'mobile': mobile, 'user_id': user_id},
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.status == '1') {
                        $('#modal-otp').modal('hide');
                        // $("#telephone").prop('readonly', false);
                        // $("#isdcode").prop('disabled', false);
                        toastr.success(data.message);
                        // $('#otp').css('display', 'none');
                        // $('#edit').css('display', '');
                        // if(old_no != mobile)
                        // {
                        //     $('#change_number').val('1');
                        // }
                    }
                    else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    </script>


    <script type="text/javascript">
        $(document).on('click', '#oldedit', function () {
            // $('#oldedit').on('click', function() {
            $(this).prop('disabled', true);
            // });
            var isdcode = $("#isdcode").val();
            var mobile = $("#telephone").val();
            var email = $('#email_id').val();
            var user_id = {{$result->id}};
            if (mobile != '' && isdcode != '') {
                $.ajax({
                    url: '{{url("ajax/checkphone")}}',
                    method: 'post',
                    data: {'mobile_no': mobile, 'user_id': user_id},
                    success: function (data) {
                        obj = JSON.parse(data);
                        if (obj.message == '1') {
                            $('#modal-otp').modal('hide');
                            $('#phone_error').show().delay(5000).fadeOut();
                        }
                        else {
                            $.ajax({
                                url: '{{url("ajax/registerotp")}}',
                                method: 'post',
                                data: {'isdcode': isdcode, 'phone': mobile, 'reg_email': email, 'type': 'Update'},
                                success: function (output) {
                                    obj = JSON.parse(output);
                                    if (obj.status == '1') {
                                        $('#modal-otp').modal('show');
                                        linkactivate();
                                        // $('#otp_msg1').delay(30000).fadeIn();
                                    }
                                    else {
                                        $("#otp_msg").html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + obj.sms + '</div>')
                                    }
                                }
                            });
                        }
                    }
                });
            }
        });

    </script>

    <script type="text/javascript">

        $(document).ready(function () {
            var max_fields = 10; //maximum input boxes allowed
            var wrapper = $("#field"); //Fields wrapper
            var add_button = $(".add-more"); //Add button ID

            var x = 1; //initlal text box count
            $(add_button).click(function (e) {//on add input button click
                console.log('hi');
                console.log(wrapper);
                e.preventDefault();
                if (x < max_fields) { //max input box allowed
                    x++; //text box increment
                    $(wrapper).append('<div><input class=" input" id="mytext[]" name="mytext[]" type="text" placeholder="Enter Email" style="width:50%; padding: 10px; margin-top: 5px "><i class="fas fa-minus remove-me" style="font-size: 150%; margin-left: 5px;"></i></div>'); //add input box

                }
            });

            $(wrapper).on("click", ".remove-me", function (e) { //user click on remove text
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            })
        });

    </script>
@endsection