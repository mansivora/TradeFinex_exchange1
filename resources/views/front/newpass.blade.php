@extends('front.layout.front')
@section('content')


    <!-- login -->
    <section style="margin-top:50px; min-height:740px;">
        <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <h3>Reset password</h3>
                    <form action="{{url('/resetpassword/'.$code)}}" id="forgotreset" method="POST">
                        <!--<div class="row">-->
                        {{ csrf_field() }}
                        <div class="">
                            <hr>
                            <div class="form-group">
                                <input type="password" class="form-control input-lg" name="password" id="password"
                                       placeholder="New password">
                            </div>

                            <div class="form-group">
                                <input type="password" class="form-control input-lg" name="password_confirmation"
                                       placeholder="Confirm New password">
                            </div>


                            <hr>
                            <button type="submit" class="btn btn-primary pull-left" name="ec_login"><i
                                        class="fa fa-sign-in"></i> &nbsp; Submit
                            </button>


                            </span>
                        </div>
                    </form>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </section>
    <!-- / login -->
@endsection

@section('xscript')

    <script>
        $("#forgotreset").validate({
            rules:
                {
                    password: {
                        required: true,
                        minlength: 6,
                        noSpace: true,
                        pwcheckallowedchars: true,
                        pwcheckspechars: true,
                        pwcheckuppercase: true
                    },
                    password_confirmation: {required: true, equalTo: '#password'},
                },
            messages:
                {
                    password: {required: 'password is required', minlength: 'Minimum six characters is required'},
                    password_confirmation: {required: 'Confirm password is required', equalTo: "Password doesn't match"}
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
    </script>

@endsection