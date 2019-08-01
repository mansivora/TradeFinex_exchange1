<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="TradeFinex Exchange" />
    <meta name="keywords" content="TradeFinex Exchange" />
    <meta name="author" content="exchange.tradefinex.org" />

    <!-- Site Title -->
    <title>TradeFinex Exchange</title>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{URL::asset('front')}}/home/assets/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{URL::asset('front')}}/home/assets/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{URL::asset('front')}}/home/assets/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{URL::asset('front')}}/home/assets/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{URL::asset('front')}}/home/assets/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{URL::asset('front')}}/home/assets/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{URL::asset('front')}}/home/assets/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{URL::asset('front')}}/home/assets/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{URL::asset('front')}}/home/assets/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="{{URL::asset('front')}}/home/assets/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{URL::asset('front')}}/home/assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{URL::asset('front')}}/home/assets/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{URL::asset('front')}}/home/assets/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="{{URL::asset('front')}}/assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{URL::asset('front')}}/home/assets/images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{URL::asset('front')}}/home/assets/css/bootstrap.min.css" type="text/css">

    <!-- Custom Css -->
    <link rel="stylesheet" type="text/css" href="{{URL::asset('front')}}/home/assets/css/style.css" />

    <!-- {{--css file for toastr notifications--}} -->
    <link href="{{URL::asset('front')}}/home/assets/css/toastr-2.1.3.css" rel="stylesheet" type="text/css">


    <!-- Bxslider Css -->
    <link rel="stylesheet" type="text/css" href="{{URL::asset('front')}}/home/assets/css/animate.css" />

    <!-- Fontawesome Css -->
    <link rel="stylesheet" type="text/css" href="{{URL::asset('front')}}/home/assets/css/fontawesome.min.css" />

    
</head>

<body>

    <!--Navbar Start-->
    <nav class="navbar navbar-expand-lg fixed-top custom-nav sticky">
        <div class="container">
            <!-- LOGO -->
            <a class="logo navbar-brand" href="index.html">
                <img src="{{URL::asset('front')}}/home/assets/images/logo.png" alt="" class="img-fluid">
            </a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav ml-auto navbar-center" id="mySidenav">
                @if(Session::has('alphauserid'))
                        <li class="nav-item"><a class="nav-link" href="{{url('/trade')}}">Trade</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{url('/wallet')}}">Wallets</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{url('/history')}}">Orders</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{url('/contact_us')}}" target="_blank">Support</a></li>
                        <!-- <li class="nav-item"><a class="nav-link" href="{{url('/news')}}">News</a></li> -->
                        <li>
                            <a class="dropdown dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="true" href="#">
                                <i class="fas fa-user"></i>
                                <ul class="dropdown-menu" style="width: auto;">
                                    <li><a class="dropdown-item" href="{{url('/profile')}}"
                                           class="user-name">@if(get_user_details(Session::get('alphauserid'),'profile_image') != 'noimage.png')
                                                <span class=""><img
                                                            src="{{URL::asset('uploads/users/profileimg')}}/{{get_user_details(Session::get('alphauserid'), 'profile_image')}}"></span>@endif @if(get_user_details(Session::get('alphauserid'), 'first_name') != '' ) {{get_user_details(Session::get('alphauserid'), 'first_name')}} @else
                                                User @endif</a></li>
                                    <li><a class="dropdown-item" href="{{url('/logout')}}">Logout&nbsp;&nbsp;<i
                                                    class="fa  fa fa-sign-out-alt"></i></a></li>
                                </ul>
                            </a>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{url('/home')}}">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{url('/trade')}}">Trade</a></li>
                        {{--<li><a href="{{url('/howtostart')}}">How to start</a></li>--}}
                        <li class="nav-item"><a class="nav-link" href="{{url('/contact_us')}}" target="_blank">Support</a></li>
                        <!-- <li class="nav-item"><a class="nav-link" href="{{url('/news')}}">News</a></li> -->
                        <li class="nav-item"><a class="nav-link" href="{{url('/login')}}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{url('/register')}}">Register</a></li>
                        {{--<li><a href="#" class=""><span class=""> <img src="{{URL::asset('front')}}/assets/imgs/noti.png"> </span></a></li>--}}
                    @endif
              
            </ul>
        </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Starts Inner Banner Slide -->
    <section class="inner-banner">
        <div class="container inner pt-100 pb-100">
            <div class="banner-description">
                <h1 class="animated fadeInDown delay-02s">For the Investor</h1>
                <p class="mx-auto animated fadeInDown delay-03s">Institutional, family office & accredited individual investors can Earn high yield with low risk assets class</p>
            </div>
        </div>
        <!-- /.container -->
    </section>
    <!-- Ends Inner Banner Slide -->

    <!-- Starts Benefits -->
    <section class="bg-white" id="benefits">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 padding-seven-half-tb padding-eight-lr">
                    <div class="section_title">
                        <h3 class="text-capitalize font-weight-bold">Benefits for Investors</h3>
                    </div>
                    <ul class="benefits-list">
                        <li class="">Improve cash conversion cycle by extending payment terms</li>
                        <li class="">Enable payment to the supplier without bank loan</li>
                        <li class="">Provide leverage to negotiate favorable price and conditions with suppliers</li>
                        <li class="">Strengthens relationships with supplier by offering favorable financing options</li>
                    </ul>
                    <a href="#contact" class="btn btn-custom btn-rounded internalLink">Register</a>
                </div>
                <div class="col-lg-6 position-relative md-height-500 sm-height-300 cover-background investor slide1">
            </div>

        </div>
    </section>
    <!-- End Benefits -->

    <!-- Start Contact -->
    <section class="section bg-light destination" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4 padding-ten-tb">
                    <div class="inner-page-bodyTitle">Get in touch</div>
                    <h5>Learn more about Investors Benefit</h5>
                </div>
                    <div class="col-md-6">
                        <div class="contact-form-wrap">                    
                            <form class="contact-form" id="investorForm" action="{{url('investors')}}" name="iform2" method="post">
                            {{csrf_field()}}
                                <div class="form-group">                                    
                                    <input type="text" class="form-control" id="name" name="name"placeholder="Name">
                                </div>
                                
                                <div class="form-group">
                                    <input type="email" class="form-control" id="customerEmail" name="customerEmail" placeholder="Customer email">
                                </div>

                                <div class="form-group">                                    
                                    <input type="text" class="form-control" id="organization" name="organization" placeholder="Organization">
                                </div>
                                
                                

                                <div class="form-group">
                                    <select id="ticketType" type="text"  name="ticketType" class="form-control" aria-invalid="false">
                                        <option selected="">Type</option>
                                        <option value="Institutional Investor">Institutional Investor</option>
                                        <option value="Accredited Individual Investor">Accredited Individual Investor</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            
                                <div class="form-group">
                                    <div class="captcha">
                                        {{--{!! NoCaptcha::display(['data-theme' => 'dark'])  !!}--}}
                                        {{--<p id="grecaptcha_error" class="error" hidden>Please verify that you are human.</p>--}}
                                        <span id="capimg" style="margin-bottom: 20px; display: inline-block;">{!! captcha_img() !!}
                                        </span>
                                        <a href="javascript:;" onclick="change_captcha()" class="m_tl">
                                            <i class="fa fa-refresh fa-2x"></i></a>
                                        <input type="text" class="form-control" autocomplete="off"
                                                name="captcha" placeholder="Captcha code">
                                        <div class="error_gap">
                                            <label for="captcha" generated="true" style="display: none;"
                                                    class="error"></label>
                                        </div>
                                    </div>
                                </div>
                                        
                                <div class="btn-blockTB">
                                    <button type="submit" class="btn btn-custom btn-rounded">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                
            </div>

        </div>
    </section>
    <!-- End Contact -->

    <!-- Start Footer -->
    <footer class="footer pt-1 text-center">
    <div class="footer-upper-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="footer-info-list">
                        <ul>
                            {{--<li><a href="{{url('/api')}}">API</a></li>--}}
                            <li><a href="{{url('/aboutus')}}">About us</a></li>
                            <li><a href="{{url('/faq')}}" target="_blank">FAQs</a></li>
                            <li><a href="{{url('/terms')}}">Terms & Conditions</a></li>
                            <li><a href="{{url('/privacy')}}">Privacy Policy</a></li>
                        <!-- <li><a href="{{url('/applytolist')}}">Apply to List</a></li> -->
                            <li><a href="{{url('/contact_us')}}">Contact us</a></li>
                            <!-- <li><a href="{{url('/add_asset')}}">Add Asset</a></li> -->

                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="footer-info-list">
                        <ul class="social-style">
                            <li><a href="https://www.linkedin.com/in/tradefinex/" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                            <li><a href="https://twitter.com/TradeFinex" target="_blank"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="https://www.youtube.com/channel/UCKzL0MI7gS_vlEKsUfiWuvA?view_as=subscriber" target="_blank"><i class="fa fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-wrap">
                <div class="copyright-text">
                    Copyright © 2019 TRADEFINEX TECH LTD (ADGM RegLab Participant), All rights reserved.
                </div>

            </div>
        </div>
    </div>
</footer>
    <!-- End Footer -->

    <!-- Back To Top -->
    <a href="#" class="back_top"><i class="fa fa-angle-up" aria-hidden="true"></i></a>

    <!-- Javascript -->
    <script src="{{URL::asset('front')}}/home/assets/js/jquery.min.js"></script>
    <script src="{{URL::asset('front')}}/home/assets/js/popper.min.js"></script>
    <script src="{{URL::asset('front')}}/home/assets/js/bootstrap.min.js"></script>
    <script src="{{URL::asset('front')}}/home/assets/js/jquery.easing.min.js"></script>
    <script src="{{URL::asset('front')}}/home/assets/js/toastr-2.1.3.js"></script>

    <!-- Bxslider JS -->
    <script src="{{URL::asset('front')}}/home/assets/js/bxslider.min.js"></script>

    <!-- Custom Js   -->
    <script src="{{URL::asset('front')}}/home/assets/js/custom.js"></script>
    <script>
    function change_captcha()
    {
        $("#capimg").html('Loading....');
        $.post('{{url("ajax/refresh_capcha")}}',function(data,result)
        {
            $("#capimg").html(data);
        });
    }
</script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script type="text/javascript">
        $("#investorForm").validate({
            rules:
                {
                    name: {required: true, minlength: 2, regex: "^(?!.*\\s{2,})(?!^ )[A-Za-z\\s]{1,50}$"},
                    customerEmail: {required: true, email: true,},
                    organization: {required: true, alphanumer: true, regex: "(?!^ +$)^.+$"},
                    mobileNumber: {required: true, number: true},
                    ticketType: {required: true, lettersonlys: true},
                    captcha: {required: true, regex: "^[a-zA-Z0-9]+$"},
                    from: {regex: "^[a-zA-Z0-9]+$"},
                    to: {regex: "^[a-zA-Z0-9]+$"},
                    
                },
            messages:
                {
                    name: {
                        required: 'Name is required',
                        minlength: 'Name should contain atleast two alphabets',
                        regex: 'Only alphabets allowed, it should not start with space and no more than one space consecutively'
                    },
                    customerEmail: {required: 'Email is required', email: 'Enter valid email',},
                    organization: {required: 'Organization is required',},
                    // mobileNumber: {
                    //     required: 'Mobile number is required',
                    //     number: 'Enter valid number',
                    //     minlength: 'Please enter 10 digit number'
                    // },
                    ticketType: {required: 'Type is required', regex: 'Choose valid type'},
                    captcha: {required: 'Captcha is required', regex: "No special characters or spaces"},
                    from: {regex: "No special characters or spaces"},
                    to: {regex: "No special characters or spaces"},
                   
                }
        });

        // $("#mobileNumber").keydown(function (evt) {
        //     var charCode = (evt.which) ? evt.which : evt.keyCode
        //     if (charCode > 32 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 107) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
        //         return false;
        //     return true;
        // });
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
<script type="text/javascript">

toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-bottom-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "25000",
    "hideDuration": "1000",
    "timeOut": "25000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}
@if(isset($errors))
@if(count($errors)>0)
@foreach($errors->all() as $er)
toastr.error("{{$er}}");
@endforeach
@endif
@endif
@if(Session::has('info'))//this line works as expected
toastr.info("{{ Session::get('info') }}");
@elseif(Session::has('error'))
toastr.error("{{Session::get('error')}}");
@elseif(Session::has('warning'))
toastr.warning("{{Session::get('warning')}}");
@elseif(Session::has('success'))
toastr.success("{{Session::get('success')}}");

@endif

</script>
</body>



</html>