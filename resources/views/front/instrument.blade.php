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
                            <div class="panel-heading">Asset Details</div>
                            <div class="panel-body">
                                <table id="customers">
                                    <tbody>
                                        <tr>
                                            <td class="text-center"><b>Asset RefNo.:</td>
                                            <td class="text-center">ABC009286</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Tran RefNo.:</td>
                                            <td class="text-center">LBC1234474e84994848HGBVJ</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Obligor Name:</td>
                                            <td class="text-center">Bank XYZ</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Obligor Country:</td>
                                            <td class="text-center">Singapore</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Underlying Instrument:</td>
                                            <td class="text-center">Letter of Credit Confirmation</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Currency:</td>
                                            <td class="text-center">{{$secondCurrency}}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Amount:</td>
                                            <td class="text-center">1,000,000.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Selldown Amount:</td>
                                            <td class="text-center">25000.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Asset RefNo.:</td>
                                            <td class="text-center">ABC009286</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Maturity/Expiry Date:</td>
                                            <td class="text-center">31-09-2019</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Documents:</td>
                                            <td class="text-center"><a href="https://gateway.ipfs.io/ipfs/QmY8X3jbvaP54P3q3LoXerf6aYhAtcZa1kRoeiMiJksQvq" target="_blank">QmY8X3jbvaP54P3q3LoXerf6aYhAtcZa1kRoeiMiJksQvq&nbsp;&nbsp;<i class="fa  fa fa-download"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Seller's Retention:</td>
                                            <td class="text-center">0 %</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Applicant/Importer:</td>
                                            <td class="text-center">FGH Motors</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Original Tenor:</td>
                                            <td class="text-center">360 days</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Note on Tenor/ Expiry/ Maturity:</td>
                                            <td class="text-center">expiry beyond tenor</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Type of sale:</td>
                                            <td class="text-center">Funded</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Goods:</td>
                                            <td class="text-center">Cars</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Beneficiary/ Exporter:</td>
                                            <td class="text-center">MNO Corporation</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Transaction Status:</td>
                                            <td class="text-center">Booked</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Port of Loading:</td>
                                            <td class="text-center">Dubai</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Port of Discharge:</td>
                                            <td class="text-center">Osaka</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Sale Price:</td>
                                            <td class="text-center">12 % PA</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Disclouser Status:</td>
                                            <td class="text-center">Undisclosed</td>
                                        </tr>
                                        
                                    </tbody>
                                </table>      
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