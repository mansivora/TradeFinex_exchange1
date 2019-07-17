@extends('front.layout.front')
@section('css')
    <style>
        .form-group {
            margin-bottom: 0px !important;
        }

        .error_gap {
            height: 20px;
            min-height: 20px;
            max-height: 20px;
        }
    </style>
@endsection
@section('content')
    <div class="clearfix"></div>
    <div class="main-flex">
        <div class="main-content inner_content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-4 col-md-offset-4">
                        <div class="panel panel-default panel-heading-space">
                            <div class="panel-heading text-center">Log in into {{get_config('site_name')}}</div>
                            <div class="panel-body">
                                <form action="{{url('/login')}}" method="POST" id="login_form">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group text-center"
                                                 style="padding-bottom: 25px !important;">
                                                <p><i class="fas fa-exclamation-circle" style="color: red;"></i> Please
                                                    check that you are visiting <strong>https://www.{{strtolower(get_config('site_url'))}}</strong>.
                                                </p>
                                            <!-- <img src="{{URL::asset('front')}}/assets/imgs/safeLink1.png" style="border:1px solid black" /> -->
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                {{--<label for="login_mail">Email</label>--}}
                                                <input type="text" class="form-control" id="login_mail"
                                                       name="login_mail" placeholder="Email" required="required">
                                                <div class="error_gap">
                                                    <label for="login_mail" generated="true" style="display: none;"
                                                           class="error"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                {{--<label for="password">Password</label>--}}
                                                <input type="password" class="form-control" id="password"
                                                       name="password" placeholder="Password" required="required">
                                                <div class="error_gap">
                                                    <label for="password" generated="true" style="display: none;"
                                                           class="error"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="captcha">
                                                    {{--{!! NoCaptcha::display(['data-theme' => 'dark'])  !!}--}}
                                                    {{--<p id="grecaptcha_error" class="error" hidden>Please verify that you are human.</p>--}}
                                                    <span id="capimg"
                                                          style="margin-bottom: 20px; display: inline-block;">{!! captcha_img() !!}
                                                    </span>
                                                    <a href="javascript:;" onclick="change_captcha()" class="m_tl">
                                                        <i class="fa fa-refresh fa-3x"></i></a>
                                                    <input type="text" class="form-control input-lg" autocomplete="off"
                                                           name="captcha" placeholder="Captcha code">
                                                    <div class="error_gap">
                                                        <label for="captcha" generated="true" style="display: none;"
                                                               class="error"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="rememberme">Remember me
                                                    <input name="remember_me" type="checkbox">
                                                    <span class="checkmark"></span>
                                                </label>
                                                <span class="forgot-passsword"><a href="{{url('/forgotpass')}}"
                                                                                  style="color:#eb9c16 !important">Forgot your Password?</a></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 custom-margin-top">
                                            <div class="form-group">
                                                <button type="submit" class="btn yellow-btn min-width-btn">login
                                                </button>
                                                <br>
                                                <p>Don't have an Account? <a href="{{url('/register')}}"
                                                                             class="yellow-link">Create Now</a></p>
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
@endsection
@section('xscript')
    <script>
        function change_captcha() {
            $("#capimg").html('Loading....');
            $.post('{{url("ajax/refresh_capcha")}}', function (data, result) {
                $("#capimg").html(data);
            });
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            fillByMemory();
        });

        function fillByMemory() {
            if ($.cookie('email'))
                $('#login_mail').val($.cookie('email'));
            if ($.cookie('password'))
                $('#password').val($.cookie('password'));
        }
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

        $("#login_form").validate({
            rules:
                {
                    login_mail: {required: true, email: true,},
                    password: {required: true,},
                    captcha: {required: true, regex: "^[a-zA-Z0-9]+$"}
                },
            messages:
                {
                    login_mail: {required: 'Email is required', email: 'Enter valid email address',},
                    password: {required: 'Password is required',},
                    captcha: {required: 'Captcha is required', regex: "No special characters or spaces"}
                },
        });
    </script>
    <script type="text/javascript">
        // $('form').on('submit', function(e) {
        //     if(grecaptcha.getResponse() == "") {
        //         e.preventDefault();
        //         $('#grecaptcha_error').show();
        //     } else {
        //         $('#grecaptcha_error').hide();
        //         $('form').submit;
        //     }
        // });
    </script>
@endsection