@extends('front.layout.front')
@section('css')
    <style>
        .form-group {
            margin-bottom: 0px !important;
        }
    </style>
@endsection

@section('content')
    <div class="clearfix"></div>
    <div class="main-flex">
        <div class="main-content inner_content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default panel-heading-space">
                            <div class="panel-heading">Support</div>
                            <div class="panel-body">
                                <form id="contactForm" action="{{url('contact_us')}}" name="cform2" method="post">
                                    {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="email">Name</label>
                                                <input id="enquiry_name" type="text" name="enquiry_name"
                                                       value="{{old('enquiry_name')}}" class="form-control"
                                                       placeholder="Your Name">
                                                <div class="error_gap">
                                                    <label for="enquiry_name" generated="true" style="display: none;"
                                                           class="error"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="email">Email Address</label>
                                                <input id="enquiry_email" type="email" name="enquiry_email"
                                                       value="{{old('enquiry_email')}}" class="form-control"
                                                       placeholder="Email address">
                                                <div class="error_gap">
                                                    <label for="enquiry_email" generated="true" style="display: none;"
                                                           class="error"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="email">Mobile Number</label>
                                                <input id="telephone" type="text" name="telephone"
                                                       onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                       value="{{old('telephone')}}" placeholder="Enter Mobile Number"
                                                       class="form-control">
                                                <small class="instraction">Enter your number with Countrycode Ex.
                                                    (015999999999)
                                                </small>
                                                <div class="error_gap">
                                                    <label for="telephone" generated="true" style="display: none;"
                                                           class="error"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="email">User type</label>
                                                <select id="user_type" type="text" onchange="" name="user_type"
                                                        class="form-control">
                                                    <option value="user" rel="user" selected>Existing User</option>
                                                    <option value="guest" rel="guest">New User</option>
                                                </select>
                                                <div class="error_gap">
                                                    <label for="user_type" generated="true" style="display: none;"
                                                           class="error"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="email">Subject</label>
                                                <select id="subject_type" type="text" name="subject_type"
                                                        class="form-control">
                                                    <option value="Otp" class="user" rel="else">OTP Issue</option>
                                                    <option value="Deposit" class="user" rel="sub_crypto">Crypto
                                                        Deposit
                                                    </option>
                                                    <option value="Withdrawal" class="user" rel="sub_crypto">Crypto
                                                        Withdrawal
                                                    </option>
                                                    <option value="Wallet Queries" class="user" rel="else">Wallet
                                                        Queries
                                                    </option>
                                                    <option value="Trade Issues" class="user" rel="else">Trade Issues
                                                    </option>
                                                    <option value="Others" class="guest" rel="else">Others</option>
                                                    <option value="Others" class="user" rel="else">Others</option>
                                                </select>
                                                <div class="error_gap">
                                                    <label for=subject_type" generated="true" style="display: none;"
                                                           class="error"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4" id="currency_div">
                                            <div class="form-group">
                                                <label>Currency:</label>
                                                <select id="currency" type="text" name="currency" class="form-control">
                                                    <option value="USDT" class="sub_crypto" rel="user">USDT</option>
                                                    <option value="XRP" class="sub_crypto" rel="user">XRP</option>
                                                    <option value="BTC" class="sub_crypto" rel="user">BTC</option>
                                                    <option value="ETH" class="sub_crypto" rel="user">ETH</option>
                                                </select>
                                            </div>
                                            <div class="error_gap">
                                                <label for="currency" generated="true" style="display: none;"
                                                       class="error"></label>
                                            </div>
                                        </div>
                                        <div id='trans_div'>
                                            <div class="col-sm-6 col-md-3" id="trans_div">
                                                <div class="form-group">
                                                    <label for="email">From (optional)</label>
                                                    <input id="from" type="text" name="from" class="form-control"
                                                           placeholder="From address">
                                                    <div class="error_gap">
                                                        <label for="from" generated="true" style="display: none;"
                                                               class="error"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label for="email">To (optional)</label>
                                                    <input id="to" type="text" name="to" class="form-control"
                                                           placeholder="To address">
                                                    <div class="error_gap">
                                                        <label for="to" generated="true" style="display: none;"
                                                               class="error"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label for="email">Transaction Id (optional)</label>
                                                    <input id="transaction" type="text" name="transaction"
                                                           class="form-control" placeholder="Enter transaction id">
                                                    <div class="error_gap">
                                                        <label for="transaction" generated="true" style="display: none;"
                                                               class="error"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label for="email">Amount (optional)</label>
                                                    <input id="amount" type="text" name="amount" class="form-control"
                                                           placeholder="Enter amount transferred">
                                                    <div class="error_gap">
                                                        <label for="amount" generated="true" style="display: none;"
                                                               class="error"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label for="email">Message</label>
                                                <textarea name="enquiry_message" id="enquiry_message"
                                                          class="form-control"
                                                          placeholder="Your message...">{{old('enquiry_message')}}</textarea>
                                                <div class="error_gap">
                                                    <label for="enquiry_message" generated="true" style="display: none;"
                                                           class="error"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="email">Captcha</label>
                                                <div class="captcha">
                                                    {{--<div class="g-recaptcha" data-theme="dark" data-sitekey="6Lfbu1IUAAAAAAblvqe08OxhlaFwHu2-uD2mYHbO"></div>--}}
                                                    {{--{!! NoCaptcha::display(['data-theme' => 'dark']) !!}--}}
                                                    {{--<p id="grecaptcha_error" class="error" hidden>Please verify that you are human.</p>--}}
                                                    <span id="capimg"
                                                          style="margin-bottom: 20px; display: inline-block;">{!! captcha_img() !!}
                                                    </span>
                                                    <a class="m_tl" href="javascript:;" onclick="change_captcha()"><i
                                                                class="fa fa-refresh fa-3x"></i></a>
                                                    <input type="text" class="form-control input-lg" name="captcha"
                                                           placeholder="Captcha code " id="captcha">
                                                    <div class="error_gap">
                                                        <label for="captcha" generated="true" style="display: none;"
                                                               class="error"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 col-sm-offset-8">
                                            <div class="form-group text-right">
                                                <button type="submit" id="contact_submit" name="ec_register"
                                                        class="btn yellow-btn min-width-btn">Send
                                                </button>
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

    {{--validate--}}
    <script type="text/javascript">
        $("#contactForm").validate({
            rules:
                {
                    enquiry_name: {required: true, minlength: 2, regex: "^(?!.*\\s{2,})(?!^ )[A-Za-z\\s]{1,50}$"},
                    enquiry_email: {required: true, email: true,},
                    enquiry_subject: {required: true, lettersonlys: true,},
                    telephone: {required: true, number: true},
                    enquiry_message: {required: true, alphanumer: true, regex: "(?!^ +$)^.+$"},
                    captcha: {required: true, regex: "^[a-zA-Z0-9]+$"},
                    from: {regex: "^[a-zA-Z0-9]+$"},
                    to: {regex: "^[a-zA-Z0-9]+$"},
                    transaction: {regex: "^[a-zA-Z0-9]+$"},
                    amount: {number: true}
                },
            messages:
                {
                    enquiry_name: {
                        required: 'Name is required',
                        minlength: 'Name should contain atleast two alphabets',
                        regex: 'Only alphabets allowed, it should not start with space and no more than one space consecutively'
                    },
                    enquiry_email: {required: 'Email is required', email: 'Enter valid email',},
                    enquiry_subject: {required: 'Subject is required',},
                    telephone: {
                        required: 'Mobile number is required',
                        number: 'Enter valid number',
                        minlength: 'Please enter 10 digit number'
                    },
                    enquiry_message: {required: 'Message content is required', regex: 'Enter valid message'},
                    captcha: {required: 'Captcha is required', regex: "No special characters or spaces"},
                    from: {regex: "No special characters or spaces"},
                    to: {regex: "No special characters or spaces"},
                    transaction: {regex: "No special characters or spaces"},
                    amount: {number: "Enter valid amount"}
                }
        });

        $("#telephone").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 32 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 107) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });
        jQuery.validator.addMethod("alphanumer", function (value, element) {
            return this.optional(element) || /^([a-zA-Z0-9 _-]+)$/.test(value);
        }, 'Does not allow any grammatical connotation, like " : ./');

        jQuery.validator.addMethod("lettersonlys", function (value, element) {
            return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
        }, "Letters only please");
        jQuery.validator.addMethod("noSpace", function (value, element) {
            return value.indexOf(" ") < 0 && value != "";
        }, "No space please and don't leave it empty");
        $.validator.addMethod(
            "regex",
            function (value, element, regexp) {
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            },
            "Number Not valid."
        );

    </script>

    {{--ready function--}}
    <script>
        $(document).ready(function () {
            var $user_type = $('select[name=user_type]'),
                $subject_type = $('select[name=subject_type]'),
                $currency = $('select[name=currency]');
            var e = document.getElementById("subject_type");

            var strUser = e.options[e.selectedIndex].value;
            if (strUser == 'Otp') {
                document.getElementById('trans_div').style.display = 'none';
                document.getElementById('currency_div').style.display = 'none';
            }
            console.log(strUser);
            //user type change
            $user_type.change(function () {
                var $this = $(this).find(':selected'),
                    value = $this.attr('value'),

                    rel = $this.attr('rel'),
                    $set = $subject_type.find('option.' + rel);

                if ($set.length < 0) {

                    $subject_type.hide();
                    return;
                }
                if (value == 'guest') {
                    document.getElementById('trans_div').style.display = 'none';
                    document.getElementById('currency_div').style.display = 'none';
                }
                else {
                    document.getElementById('trans_div').style.display = 'block';
                    document.getElementById('currency_div').style.display = 'block';
                }

                $subject_type.show().find('option').hide();

                $set.show().first().prop('selected', true);
            });

            //subject type change
            $subject_type.change(function () {
                var $this = $(this).find(':selected'),
                    rel = $this.attr('rel'),
                    $set = $currency.find('option.' + rel);

                if (rel == 'else') {

                    document.getElementById('trans_div').style.display = 'none';
                    document.getElementById('currency_div').style.display = 'none';
                    $currency.hide();
                    return;
                }
                else {
                    document.getElementById('trans_div').style.display = 'block';
                    document.getElementById('currency_div').style.display = 'block';
                }

                $currency.show().find('option').hide();

                $set.show().first().prop('selected', true);
            });

            onloadSelect();

        });

        function onloadSelect() {

            var $user_type = $('select[name=user_type]'),
                $subject_type = $('select[name=subject_type]'),
                $currency = $('select[name=currency]');

            var $user_selected = $user_type.find(':selected');

            var user = $user_selected.attr('rel');
            $set = $subject_type.find('option.' + user);

            $subject_type.show().find('option').hide();

            $set.show().first().prop('selected', true);

            var $sub_selected = $subject_type.find(':selected');

            var subject = $sub_selected.attr('rel');
            $set = $currency.find('option.' + subject);
            $currency.show().find('option').hide();
            $set.show().first().prop('selected', true);
        }

    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.bar-toggle').on('click', function () {
                $('.leftbar').toggleClass('open');
            })
        })
    </script>
@endsection