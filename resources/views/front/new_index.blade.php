<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>tradefinex</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet"
          href="{{URL::asset('front')}}/assets/landing_page/assets/css/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link href="{{URL::asset('front')}}/assets/landing_page/assets/fonts/font-awesome/css/font-awesome.min.css"
          rel="stylesheet">
    <!-- Owl Carousel -->
    <link href="{{URL::asset('front')}}/assets/landing_page/assets/css/owl-carousel/css/owl.carousel.min.css"
          rel="stylesheet">
    <!-- Custom styles -->
    <link href="{{URL::asset('front')}}/assets/landing_page/assets/css/custom.css" rel="stylesheet">
</head>

<body>

<div id="loader">
    <div id="element">
        <div class="circ-one"></div>
        <div class="circ-two"></div>
    </div>
</div>


<!-- Header -->
<header class="opt5">
    <!-- Start Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.html"><img
                        src="{{URL::asset('front')}}/assets/landing_page/assets/images/logo.png" class="img-fluid logo1"
                        alt=""><img src="{{URL::asset('front')}}/assets/landing_page/assets/images/logo2.png"
                                    class="img-fluid logo2" alt=""></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
                    aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation"><span
                        class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a href="#" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Contact</a></li>
                </ul>
                <ul class="navbar-right d-flex">
                    <li><a href="login.html">Request a Demo <i class="fa fa-angle-right"></i></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navigation -->
</header>
<!-- End Header -->

<!-- Banner -->
<div class="banner slide3 banner5">
    <div class="container">
        <div class="row cnt-block">
            <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 ">
                <div class="left">
                    <h1>Making Illiquid Assets Liquid</h1>
                    <p>tradefinex is the only enterprise-grade software that enables institutions to both tokenize illiquid
                        assets and trade those assets on an exchange.</p>
                    <!--<a href="#" class="get-started">Request a Demo</a>-->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Banner -->

<!-- Tokens section-->
<section id="securityTokens-exchangeSoftware" class="">
    <section class="tokens-software-row">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="security-tokens-bg">
                        <h1 class="title">Admin</h1>
                        <p>Utilize distributed ledger technology to digitize assets with tradefinex's Asset Digitization
                            software.</p>
                        <a class="btn border white" href="{{url('/check_admin')}}" target="_blank">Check Admin Site <i class="fa fa-angle-right"></i></a>
                    </div>

                    <div class="exchange-software-bg">
                        <h1 class="title">Exchange Software</h1>
                        <p>Launch a digital asset or cryptocurrency exchange quickly and securely with tradefinex's full
                            stack exchange technology.</p>
                        <a class="btn border white" href="#">Launch an Exchange <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
<!-- End Tokens section-->

<!-- Features Block-->
<section class="features-block our-features padding-lg">
    <div class="container">
        <div class="row justify-content-center head-block">
            <div class="col-md-10">
                <h2>Software Features</h2>
                <p>tradefinex Digital Asset Exchange Software delivers:</p>
            </div>
        </div>
        <ul class="row flex-row features-listing">
            <li class="col-md-6 col-lg-3">
                <div class="features-listing-box">
                    <div class="inner"><span class="icon"><img
                                    src="{{URL::asset('front')}}/assets/landing_page/assets/images/feature-icons/tested-infrastructure.png"
                                    alt=""></span>
                        <h3>Battle-Tested Infrastructure</h3>
                        <p>A secure, stable white label backend solution that safeguards all digital exchange data by
                            layered architecture providing enhanced security protocol for all customer assets.</p>
                    </div>
                </div>
            </li>
            <li class="col-md-6 col-lg-3">
                <div class="features-listing-box">
                    <div class="inner"><span class="icon"><img
                                    src="{{URL::asset('front')}}/assets/landing_page/assets/images/feature-icons/powerful-api.png"
                                    alt=""></span>
                        <h3>Powerful APIs</h3>
                        <p>tradefinex's ready-made UI/UX tool set and full stack technology suite for easy integration
                            across the organization.</p>
                    </div>
                </div>
            </li>
            <li class="col-md-6 col-lg-3">
                <div class="features-listing-box">
                    <div class="inner"><span class="icon"><img
                                    src="{{URL::asset('front')}}/assets/landing_page/assets/images/feature-icons/fast-time.png"
                                    alt=""></span>
                        <h3>Fast Time to Market</h3>
                        <p>Robust risk management with real-time error checking and support for KYC, AML, and 2FA.</p>
                    </div>
                </div>
            </li>
            <li class="col-md-6 col-lg-3">
                <div class="features-listing-box">
                    <div class="inner"><span class="icon"><img
                                    src="{{URL::asset('front')}}/assets/landing_page/assets/images/feature-icons/configurable-system.png"
                                    alt=""></span>
                        <h3>Configurable System</h3>
                        <p>Configurable order management system, matching engine, and order routing, with integration
                            options for custody, KYC/AML, and settlement.</p>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</section>
<!-- End Features Block -->

<!-- tradefinex System -->
<section class="tradefinex-system padding-lg">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 cnt-block">
                <h2>The tradefinex System</h2>
                <p>tradefinexs's blockchain software suite powers secure, stable, institutional-grade security token and
                    cryptocurrency marketplace solutions.</p>
                <a class="btn blue-gradient" href="#">Request a Demo <i class="fa fa-angle-right"></i></a>
            </div>
            <div class="col-lg-7 right">
                <figure class="img"><img
                            src="{{URL::asset('front')}}/assets/landing_page/assets/images/tradefinex-system.jpg"
                            class="img-fluid" alt=""></figure>
            </div>
        </div>
    </div>
</section>
<!-- End tradefinex System -->

<!-- Overview -->
<section class="system-overview grey-bg padding-lg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h2>Get an overview on tradefinex System</h2>
                <p class="padd-sm">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                    Ipsum has been the industry's standard dummy text ever since beenLorem Ipsum is simply dummy text of
                    the printing and typesetting industry. </p>
            </div>
        </div>
        <!-- Start: Caurosel -->
        <div class="features-carousel-sec">
            <div class="owl-carousel owl-feature">
                <div class="item"><img
                            src="{{URL::asset('front')}}/assets/landing_page/assets/images/overview-img-1.png" alt="">
                </div>
                <div class="item"><img
                            src="{{URL::asset('front')}}/assets/landing_page/assets/images/overview-img-2.png" alt="">
                </div>
                <div class="item"><img
                            src="{{URL::asset('front')}}/assets/landing_page/assets/images/overview-img-3.png" alt="">
                </div>
                <div class="item"><img
                            src="{{URL::asset('front')}}/assets/landing_page/assets/images/overview-img-4.png" alt="">
                </div>
                <div class="item"><img
                            src="{{URL::asset('front')}}/assets/landing_page/assets/images/overview-img-5.png" alt="">
                </div>
            </div>
        </div>
        <!-- End: Caurosel -->
    </div>
</section>
<!-- End Overview -->

<!-- Remarketer -->
<section class="call-action-bar bg">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 cnt-block">
                <h2>Remarketer. Liquidity at Launch</h2>
                <p>tradefinex Remarketer software delivers orders from a menu of leading exchanges to the customer's own
                    tradefinex technology powered exchange. This out-of-the-box software feature instantly adds liquidity.
                    The result?</p>
            </div>
            <div class="col-lg-5 right">
                <ul>
                    <li>Instant access to a global network of liquidity</li>
                    <li>Built-in FX conversion feature provides seamless multicurrency conversion for operators to work
                        with clients across local exchanges
                    </li>
                    <li>Scalable technology with modular architecture that supports all performance levels</li>
                    <li>Fully customizable remarket depth, thresholds, and equations to independently price orders</li>
                    <li>Fully supports both sides of the market; exchanges leveraging the Remarketer demonstrate higher
                        order profitability
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- End Remarketer -->

<!-- TextBlock -->
<section class="call-action-bar white">
    <div class="container">
        <h2>This secure, trusted platform allows <span>Exchange Operators</span>, <span>Asset Managers</span>, <span>Broker-Dealers</span>,
            <span>Token Issuers</span>, and <span>Custodians</span> to develop new revenue streams, maximize liquidity,
            and transact their businesses in the most efficient way possible.</h2>
    </div>
</section>
<!-- End TextBlock -->

<!-- Demo -->
<section class="call-action-bar gradient">
    <div class="container">
        <h2>Interested in learning more about tradefinex's Digital Asset Exchange Software?</h2>
        <a class="btn border white" href="#">Request a Demo <i class="fa fa-angle-right"></i></a>
    </div>
</section>
<!-- End Demo -->

<!-- Download -->
<section class="download-guide white-bg">
    <div class="sided-item-wrapper">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-md-7">
                    <div class="content-area padding-lg">
                        <h2>Launching a Digital Asset Exchange</h2>
                        <p>The demand to issue, acquire, and trade digital assets – proprietary tokens representing
                            loyalty points, financial instruments, or natively digital currencies such as bitcoin – has
                            grown exponentially over the past several years, creating a set of dynamic, overlapping
                            exchange ecosystems. This guide offers an introductory guide for enterprises interested in
                            understanding how to plan, launch and operate a successful exchange.</p>
                        <p class="mt-3 font-weight-medium">This Guide Covers:</p>
                        <ul class="icon-tik-list row">
                            <li class="col-md-6">
                                <p>How digital asset markets works</p>
                            </li>
                            <li class="col-md-6">
                                <p>What technology powers exchanges</p>
                            </li>
                            <li class="col-md-6">
                                <p>What business leaders, entrepreneurs, or established firms need to get started</p>
                            </li>
                            <li class="col-md-6">
                                <p>Which factors contribute to an exchange's success</p>
                            </li>
                        </ul>
                        <a class="btn blue-gradient" href="#">Download Now <i class="fa fa-download"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="left-sided-full-image">
            <figure><img
                        src="{{URL::asset('front')}}/assets/landing_page/assets/images/launching-digital-asset-exchange.png"
                        alt=""></figure>
        </div>
    </div>
</section>
<!-- End Download -->

<!-- Contact Us -->
<section class="contact-wrapper-outer grey-bg padding-lg">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="contact-info-wrapper mb-4">
                    <div class="contact-info">
                        <h2>Get In Touch</h2>
                        <ul class="info-contact-box">
                            <li>
                                <h6>tradefinex</h6>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem</p>
                            </li>
                            <li>
                                <h6>000 0000 000</h6>
                            </li>
                            <li><a href="mailto:info@tradefinex.com">info@tradefinex.com</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-7 form-area">
                <div class="contact-form-wrapper">
                    <form name="contact-form" id="ContactForm" novalidate="novalidate">
                        <div class="row">
                            <div class="col-md-6 input-col">
                                <label>Your Name</label>
                                <input name="your_name" placeholder="" type="text">
                            </div>
                            <div class="col-md-6 input-col">
                                <label>Email Address</label>
                                <input name="business_email" placeholder="" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 input-col">
                                <label>Phone</label>
                                <input name="phone_number" placeholder="" type="text">
                            </div>
                            <div class="col-md-6 input-col">
                                <label>Company</label>
                                <input name="company" placeholder="" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Message</label>
                                <textarea name="message" placeholder=""></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn blue-gradient">Submit <i class="fa fa-paper-plane"></i></button>
                            </div>
                            <div class="col-md-12">
                                <div class="msg"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- End Contact Us -->

<!-- Footer -->
<footer class="footer dark-bg">
    <div class="top">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-3 mob-acco">
                    <div class="footer-info pr-3 p-right-30 mb-2">
                        <img src="{{URL::asset('front')}}/assets/landing_page/assets/images/logo2.png" width="150"
                             height="44" alt="footer-logo">
                        <p class="mt-3">tradefinex is the only enterprise-grade software that enables institutions to both
                            tokenize illiquid assets and trade those assets on an exchange.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="quick-links">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="#">Home</a></li>
                            <li><a href="#">About</a></li>
                            <li><a href="#">Contact</a></li>
                            <li><a href="#">Request a Demo</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="connect-outer">
                        <h4>Connect with Us</h4>
                        <ul class="connect-us">
                            <li><a href="#"><i class="fa fa-paper-plane"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="subscribe">
                        <h4>Subscribe with Us</h4>
                        <p class="hidden-xs">Lorem Ipsum is simply dummy text of the printing and typesetting
                            industry.</p>
                        <div class="input-outer clearfix">
                            <!-- Begin Signup Form -->
                            <div id="mc_embed_signup">
                                <form action="" method="post" id="" name="" class="validate" target="_blank" novalidate>
                                    <div id="mc_embed_signup_scroll">
                                        <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL"
                                               placeholder="email address" required>
                                        <div style="position: absolute; left: -5000px;" aria-hidden="true">
                                            <input type="text" name="" tabindex="-1" value="">
                                        </div>
                                        <div class="clear">
                                            <input type="submit" value="Subscribe" name="subscribe"
                                                   id="mc-embedded-subscribe" class="button">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!--End Signup Form -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom">
        <div class="container"> Copyright © 2019 tradefinex. All Rights Reserved.</div>
    </div>
</footer>
<!-- End Footer -->

<!-- Scroll to top -->
<a href="#" class="scroll-top"><i class="fa fa-angle-up" aria-hidden="true"></i></a>

<!-- jQuery -->
<script src="{{URL::asset('front')}}/assets/landing_page/assets/js/jquery.min.js"></script>
<!-- Popper JS -->
<script src="{{URL::asset('front')}}/assets/landing_page/assets/js/popper.min.js"></script>
<!-- Bootsrap JS -->
<script src="{{URL::asset('front')}}/assets/landing_page/assets/js/bootstrap/js/bootstrap.min.js"></script>
<!-- Owl Carousal JS -->
<script src="{{URL::asset('front')}}/assets/landing_page/assets/js/owl-carousel/js/owl.carousel.min.js"></script>
<!-- Custom JS -->
<script src="{{URL::asset('front')}}/assets/landing_page/assets/js/custom.js"></script>
</body>

</html>