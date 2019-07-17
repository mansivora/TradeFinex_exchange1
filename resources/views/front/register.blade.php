@extends('front.layout.front')
@section('css')
    <style>
        .form-group {
            margin-bottom: 0px !important;
        }

        .error_gap {
            height: 15px;
            min-height: 15px;
            max-height: 15px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection
@section('content')
    <div class="clearfix"></div>
    <div class="main-flex">
        <div class="main-content inner_content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="panel panel-default panel-heading-space">
                            <div class="panel-heading text-center">Register for {{get_config('site_name')}}</div>
                            <div class="panel-body">
                                <form id="register_form" action="{{url('/register')}}" method="post"
                                      accept-charset="UTF-8">
                                    {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group text-center"
                                                 style="padding-bottom: 25px !important;">
                                                <p><i class="fas fa-exclamation-circle" style="color: red;"></i> Please
                                                    check that you are visiting <strong>https://www.{{strtolower(get_config('site_url'))}}</strong>.
                                                </p>
                                            <!-- <img src="{{URL::asset('front')}}/assets/imgs/safeLink1.png" style="border:1px solid black"/> -->
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        {{--<div class="col-sm-12">--}}
                                        {{--<div class="form-group">--}}
                                        {{--<label for="name">Full Name</label>--}}
                                        {{--<input type="text" id="first_name" class="form-control"  placeholder="Enter Full Name" name="first_name" value="{{ old('first_name') }}">--}}
                                        {{--<div class="error_gap">--}}
                                        {{--<label for="first_name" generated="true" style="display: none;" class="error"></label>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-sm-12">--}}
                                        {{--<div class="form-group">--}}
                                        {{--<label for="name">Last Name</label>--}}
                                        {{--<input type="text" id="last_name" class="form-control" placeholder="Enter Last Name" name="last_name" value="{{ old('last_name') }}">--}}
                                        {{--<div class="error_gap">--}}
                                        {{--<label for="last_name" generated="true" style="display: none;" class="error"></label>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="clearfix"></div>--}}
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                {{--<label for="email">Email</label>--}}
                                                <input type="text" id="email_id" class="form-control"
                                                       placeholder="Email" name="email_id"
                                                       value="{{ old('email_id') }}">
                                                <div class="error_gap">
                                                    <label for="email_id" generated="true" style="display: none;"
                                                           class="error"></label>
                                                </div>
                                            </div>
                                        </div>
                                        {{--<div class="clearfix"></div>--}}
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                {{--<label for="password">Password</label>--}}
                                                <input type="password" id="password" class="form-control"
                                                       placeholder="Password" name="password">
                                                <div class="error_gap">
                                                    <label for="password" generated="true" style="display: none;"
                                                           class="error"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                {{--<label for="confirmpassword">Confirm Password</label>--}}
                                                <input type="password" name="password_confirmation"
                                                       id="password_confirmation" class="form-control"
                                                       placeholder="Confirm Password">
                                                <div class="error_gap">
                                                    <label for="password_confirmation" generated="true"
                                                           style="display: none;" class="error"></label>
                                                </div>
                                            </div>
                                        </div>
                                        {{--<div class="col-sm-12">--}}
                                        {{--<div class="col-xs-4">--}}
                                        {{--<div class="form-group">--}}
                                        {{--<label>Contact No.</label>--}}
                                        {{--<select name="isdcode" id="isdcode" class="form-control">--}}
                                        {{--<option value="">ISD</option>--}}
                                        {{--@foreach($country as $val)--}}
                                        {{--<option value="{{$val->phonecode}}"@if($val->id==old('country_id')) selected--}}
                                        {{--@elseif($val->phonecode==65) selected @endif data-id="{{strtolower($val->iso)}}">+{{$val->phonecode}}</option>--}}
                                        {{--@endforeach--}}
                                        {{--</select>--}}
                                        {{--<div class="error_gap">--}}
                                        {{--<label for="isdcode" generated="true" style="display: none;" class="error"></label>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-8">--}}
                                        {{--<div class="form-group">--}}
                                        {{--<label> &nbsp;</label>--}}
                                        {{--<input type="text" class="form-control" id="phone_no" placeholder="Mobile no without ISD code." name="phone_no" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'>--}}
                                        {{--<p id="phone_error" class="error" hidden>The number already exists.</p>--}}
                                        {{--<div class="error_gap">--}}
                                        {{--<label for="phone_no" generated="true" style="display: none;" class="error"></label>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-sm-12 otp">--}}
                                        {{--<div class="form-group">--}}
                                        {{--<label for="otp">OTP</label>--}}
                                        {{--<input type="text" class="form-control" id="otp" placeholder="Enter OTP" name="otp">--}}
                                        {{--<span class="send-otp"><a href="#" onclick="sendotp()" >Send OTP</a></span>--}}
                                        {{--<p id="otp_error" class="red" hidden>Enter a valid OTP.</p>--}}
                                        {{--<p id="otp_success" class="green" hidden>OTP verified successfully.</p>--}}
                                        {{--<div id="countdown" style="display: none">--}}
                                        {{--<span style="float:left"> OTP sent to your mobile number. Resend Link:&nbsp;</span>--}}
                                        {{--<div id="minutes" style="float:left;color: red">00</div>--}}
                                        {{--<div style="float:left">:</div>--}}
                                        {{--<div id="seconds" style="float:left;color: red">00</div>--}}
                                        {{--</div>--}}
                                        {{--<p id="otp_msg1" hidden>If you didn't get OTP via message, then to get OTP via call <a href="#" onclick="otp_call()">Click Here</a></p>--}}
                                        {{--<div id="aftercount" style="display:none">OTP via call:&nbsp;<a href="#" onclick="otp_call()" style="color: lightblue">Click Here</a></div>--}}
                                        {{--<div id="aftercount_msg" style="display:none">*If you do not recieve OTP within 15 minutes please contact support</div>--}}
                                        {{--<div class="error_gap">--}}
                                        {{--<label for="otp" generated="true" style="display: none;" class="error"></label>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="referral_code"
                                                       placeholder="Referral Code(Optional)" id="referral_code"
                                                       value="{{ old('referral_code') }}">
                                                <div class="error_gap">
                                                </div>
                                            </div>
                                        </div>
                                        {{--<div class="col-sm-12">--}}
                                        {{--<p class="signup-text">By Clicking Below. You indicate that you have read, understood and agree to our <a href="{{url('/terms')}}" target="_blank" >Terms & Condition</a> & <a href="{{url('/privacy')}}" target="_blank" >Privacy Policy.</a></p>--}}
                                        {{--</div>--}}

                                        <div class="col-sm-12">
                                            <div class="checkbox_field d-inline">
                                                {{--<input type="checkbox" name="rememberme" id="rememberme" onchange="activateButton(this)" value="rememberme">--}}
                                                <input name="rememberme" type="checkbox" id="rememberme"
                                                       onchange="activateButton(this)">
                                                <label for="rememberme">I agree to {{get_config('site_name')}} <a href="{{url('/terms')}}"
                                                                                              target="_blank">Terms &
                                                        Conditions</a> and <a href="{{url('/privacy')}}"
                                                                              target="_blank">Privacy Policy</a></label>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 otp">
                                            <div class="form-group">
                                                <button type="submit" class="btn yellow-btn min-width-btn"
                                                        id="submit_btn" disabled>Register
                                                </button>
                                                <p class="already-login">Already have an Account? <a
                                                            href="{{url('/login')}}" class="yellow-link">Login</a></p>
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
        <div class="clearfix"></div>
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

    <script type="text/javascript">

        $.validator.addMethod(
            "regex",
            function (value, element, regexp) {
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            },
            "Number Not valid."
        );

        $("#register_form").validate({
            rules:
                {
                    // first_name: {required:true, minlength:1, regex:"^(?!.*\\s{2,})(?!^ )[A-Za-z\\s]{1,50}$"},
                    // last_name: {required:true, minlength:1, regex:"^(?!.*\\s{2,})(?!^ )[A-Za-z\\s]{1,50}$"},
                    // isdcode:{required:true},
                    // phone_no: {required:true, number: true,regex:"^[1-9][0-9]*$"},
                    email_id: {required: true, email: true},
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
                    password_confirmation: {required: true, equalTo: '#password',},
                },
            messages:
                {
                    // first_name:{required:'Name is required',minlength:'Name should contain atleast one alphabet',regex:'Only alphabets allowed and it should not start with space.'},
                    // last_name:{required:'Last name is required',minlength:'Last name should contain atleast one alphabet',regex:'Only alphabets allowed and it should not start with space.'},
                    // isdcode:{required:'ISD code is required'},
                    // phone_no: { required:'Phone number is required',number: 'Digit only allowed',regex:'Number not valid should not start with zero'},
                    email_id: {required: 'Email id is required', email: 'Enter valid email id'},
                    password: {
                        required: 'Password is required',
                        minlength: 'Minimum 8 characters are required',
                        maxlength: 'Password cannot contain more than 25 characters'
                    },
                    password_confirmation: {
                        required: 'Password Confirmation is required',
                        equalTo: 'Password does not match',
                    },
                }
        });
        jQuery.validator.addMethod("noSpace", function (value, element) {
            return value.indexOf(" ") < 0 && value != "";
        }, "No space please and don't leave it empty");

        jQuery.validator.addMethod("pwcheckallowedchars", function (value) {
            return /^[a-zA-Z0-9!@#$%^&*()_=\[\]{};':"\\|,.<>\/?+-]+$/.test(value) // has only allowed chars letter
        }, "Password contains non-admitted characters");

        jQuery.validator.addMethod("pwcheckspechars", function (value) {
            return /[!@#$%^&*()_=\[\]{};':"\\|,.<>\/?+-]/.test(value)
        }, "Password must contain at least one special character");

        jQuery.validator.addMethod("pwcheckuppercase", function (value) {
            return /[A-Z]/.test(value) // has an uppercase letter
        }, "Password must contain at least one uppercase letter");

        jQuery.validator.addMethod("pwchecklowercase", function (value) {
            return /[a-z]/.test(value) // has an uppercase letter
        }, "Password must contain at least one lowercase letter");

    </script>

    {{--for countdown timer--}}
    {{--<script>--}}

    {{--$(document).ready(function(){--}}
    {{--$('.isdcode').dropdown();--}}
    {{--});--}}

    {{--function linkactivate()--}}
    {{--{--}}
    {{--try--}}
    {{--{--}}
    {{--var sTime = new Date().getTime();--}}
    {{--var countDown = 30;--}}
    {{--function UpdateTime() {--}}
    {{--var cTime = new Date().getTime();--}}
    {{--var diff = cTime - sTime;--}}
    {{--var seconds = countDown - Math.floor(diff / 1000);--}}
    {{--if (seconds >= 0) {--}}
    {{--var minutes = Math.floor(seconds / 60);--}}
    {{--seconds -= minutes * 60;--}}
    {{--$("#minutes").text(minutes < 10 ? "0" + minutes : minutes);--}}
    {{--$("#seconds").text(seconds < 10 ? "0" + seconds : seconds);--}}
    {{--} else {--}}
    {{--$("#countdown").hide();--}}
    {{--$("#aftercount").show();--}}
    {{--$('#aftercount_msg').show();--}}
    {{--clearInterval(counter);--}}
    {{--}--}}
    {{--}--}}
    {{--UpdateTime();--}}
    {{--var counter = setInterval(UpdateTime, 500);--}}
    {{--}--}}
    {{--catch(e)--}}
    {{--{--}}
    {{--console.log(e);--}}
    {{--}--}}
    {{--}--}}
    {{--</script>--}}

    {{--<script type="text/javascript">--}}
    {{--function sendotp() {--}}
    {{--var isdcode = $("#isdcode").val();--}}
    {{--var mobile = $("#phone_no").val();--}}
    {{--var email = $('#email_id').val();--}}
    {{--if (mobile!='' && isdcode!='')--}}
    {{--{--}}
    {{--$('#phone_no-error').hide();--}}
    {{--$.ajax({--}}
    {{--url: '{{url("ajax/checkphone")}}',--}}
    {{--method: 'post',--}}
    {{--data: {'mobile_no': mobile},--}}
    {{--success: function (data) {--}}
    {{--obj = JSON.parse(data);--}}
    {{--if (obj.message == '1') {--}}
    {{--$('#countdown').hide();--}}
    {{--$('#aftercount').hide();--}}
    {{--$('#aftercount_msg').hide();--}}
    {{--$('#phone_error').show().delay(5000).fadeOut();--}}
    {{--}--}}
    {{--else {--}}
    {{--$.ajax({--}}
    {{--url: '{{url("ajax/registerotp")}}',--}}
    {{--method: 'post',--}}
    {{--data: {'isdcode': isdcode, 'phone': mobile, 'reg_email': email, 'type': 'Register'},--}}
    {{--success: function (output) {--}}
    {{--console.log(output);--}}
    {{--obj = JSON.parse(output);--}}
    {{--console.log(output);--}}
    {{--if (obj.status == '1') {--}}
    {{--$('#countdown').show();--}}
    {{--linkactivate();--}}
    {{--// $('#otp_msg1').delay(30000).fadeIn();--}}
    {{--}--}}
    {{--else {--}}
    {{--$("#otp_msg").html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + obj.sms + '</div>')--}}
    {{--}--}}
    {{--}--}}
    {{--});--}}
    {{--}--}}
    {{--}--}}
    {{--});--}}
    {{--}--}}
    {{--else--}}
    {{--{--}}
    {{--var $validator = $("#register_form").validate();--}}
    {{--if(mobile!='') {--}}
    {{--errors = {isdcode: "ISD code is required."};--}}
    {{--}--}}
    {{--else if(isdcode!='') {--}}
    {{--errors = {phone_no: "Phone number is required."};--}}
    {{--}--}}
    {{--else {--}}
    {{--errors = {phone_no: "Phone number is required.", isdcode: "ISD code is required."};--}}
    {{--}--}}
    {{--$validator.showErrors(errors);--}}
    {{--}--}}
    {{--}--}}
    {{--</script>--}}
    {{--<script type="text/javascript">--}}
    {{--function otp_call()--}}
    {{--{--}}
    {{--var isdcode = $("#isdcode").val();--}}
    {{--var mobile=$("#phone_no").val();--}}
    {{--$.ajax({--}}
    {{--url:'{{url("ajax/otpcall")}}',--}}
    {{--method:'post',--}}
    {{--data:{'mobile':mobile,'isdcode':isdcode},--}}
    {{--success : function(data)--}}
    {{--{--}}
    {{--document.getElementById('aftercount').innerHTML = 'Call request have been placed';--}}
    {{--}--}}
    {{--});--}}
    {{--}--}}
    {{--</script>--}}

    {{--<script type="text/javascript">--}}
    {{--$('#register_form').one('submit', function(e) {--}}
    {{--e.preventDefault();--}}
    {{--var code=$('#otp').val();--}}
    {{--var mobile=$('#phone_no').val();--}}
    {{--$.ajax({--}}
    {{--url:'{{url("ajax/verify_otp")}}',--}}
    {{--method:'post',--}}
    {{--data:{'verify_code':code, 'mobile':mobile},--}}
    {{--success : function(data)--}}
    {{--{--}}
    {{--obj = JSON.parse(data);--}}
    {{--console.log(obj);--}}
    {{--if(obj.status=='1')--}}
    {{--{--}}
    {{--$('#countdown').hide();--}}
    {{--$('#aftercount').hide();--}}
    {{--$('#aftercount_msg').hide();--}}
    {{--$("#otp_success").show().delay(3000).fadeOut();--}}
    {{--$('form').submit();--}}
    {{--}--}}
    {{--else {--}}
    {{--$('#countdown').hide();--}}
    {{--$('#aftercount').hide();--}}
    {{--$('#aftercount_msg').hide();--}}
    {{--$('#otp_error').show().delay(5000).fadeOut();--}}
    {{--}--}}
    {{--}--}}
    {{--});--}}
    {{--});--}}
    {{--</script>--}}

    <script>
        function activateButton(element) {

            if (element.checked) {
                document.getElementById("submit_btn").disabled = false;
            }

            else {
                document.getElementById("submit_btn").disabled = true;
            }
        }
    </script>

@endsection