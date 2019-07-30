<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="TradeFinex Exchange" />
    <meta name="keywords" content="TradeFinex Exchange" />
    <meta name="author" content="exchange.tradefinex.org" />

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
        <a class="logo navbar-brand" href="{{url('/home')}}">
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
<!-- Navbar End --->

<!-- Banner Slider -->
<section class="banner-outer">
    <div class="banner-slider">

        <div class="banner slide1 h-100vh">

            <div class="home-table">
                <div class="home-table-center">
                    <div class="container">
                        <div class="row cnt-block">
                            <div class="col-md-12">
                                <div class="banner-description">
                                    <h1 class="animated fadeInDown delay-02s">Marketplace For Trade Finance Assets</h1>
                                    <p class="mx-auto animated fadeInDown delay-03s">Tokenize and Trade Cross border Trade Finance assets for greater and competitive liquidity</p>
                                    <a href="{{url('/register')}}" class="btn btn-custom btn-rounded animated fadeInDown ">Register Now</a> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="banner slide2 h-100vh">
            <div class="home-table">
                <div class="home-table-center">
                    <div class="container">
                        <div class="row cnt-block">
                            <div class="col-md-12">
                                <div class="banner-description">
                                    <h1 class="animated fadeInDown delay-02s">For the Investor</h1>
                                    <p class="mx-auto animated fadeInDown delay-03s">Institutional, family office & accredited individual investors can Earn high yield with low risk assets class</p>
                                    <a href="{{url('/investors')}}" class="btn btn-custom btn-rounded animated fadeInDown delay-04s">Get Started</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="banner slide3 h-100vh">
            <div class="home-table">
                <div class="home-table-center">
                    <div class="container">
                        <div class="row cnt-block">
                            <div class="col-md-12">
                                <div class="banner-description">
                                    <h1 class="animated fadeInDown delay-02s">For the Buyer</h1>
                                    <p class="mx-auto animated fadeInDown delay-03s">Corporate & Governments can strengthen relationships with suppliers by offering favorable financing options</p>
                                    <a href="#" disable="true" class="btn btn-custom btn-rounded animated fadeInDown delay-04s">Get Started</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Ends Banner Slider -->

<!-- Start Assets Supported -->
<section class="section" id="trade-table">
    <div class="container">
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="section_title text-center">
                    <h3 class="text-capitalize font-weight-bold">Assets Currently Supported</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="assets-table-area">
                    <div class="filters">
                        <div class="ticker-head">
                            <ul class="nav nav-tabs ticker-nav-3 form-tabs hidden-xs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active show" href="#tab1" role="tab" data-toggle="tab" aria-selected="true">Receivables</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab2" role="tab" data-toggle="tab" aria-selected="false">Letters of Credit</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab3" role="tab" data-toggle="tab" aria-selected="false">Bank Guarantees</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab4" role="tab" data-toggle="tab" aria-selected="false">Bill of Lading</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab5" role="tab" data-toggle="tab" aria-selected="false">Warehousing Receipts</a>
                                </li>
                            </ul>
                            <div class="d-sm-none">
                                <select class="mb10 form-control" id="tab_selector">
                                    <option value="0">Receivables</option>
                                    <option value="1">Letters of Credit</option>
                                    <option value="2">Bank Guarantees</option>
                                    <option value="3">Bill of Lading</option>
                                    <option value="4"> Warehousing Receipts</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="assets-table-block">
                        <div class="assets-table-block-inner">
                            <div class="tab-content">
                                <!-- Start Receivables Data -->
                                <div role="tabpanel" class="tab-pane fade in active show" id="tab1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center pairs-list">Instruments</th>
                                                            <th class="text-center">Last Price</th>
                                                            <th class="text-center">24H Low</th>
                                                            <th class="text-center">24H High</th>
                                                            <th class="text-center">Change(24H)</th>
                                                            <th class="text-center">Volume(24H)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                         @foreach($results as $result)
                                                            <tr id="{{$result['Pair']}}">
                                                                <td class="text-center" style="text-align: center"><strong
                                                                            class="icon-style"><img
                                                                                src="{{URL::asset('front')}}/assets/imgs/{{$result['second_currency']}}.png"><a
                                                                                href="{{url('/trade')}}">{{$result['Pair']}}</a></strong>
                                                                </td>
                                                                <td id="{{$result['Pair']}}_last"
                                                                    class="text-center"> {{$result['Last']}}</td>
                                                                <td id="{{$result['Pair']}}_low" class="text-center">
                                                                    <span>{{$result['Low']}}</span></td>
                                                                <td id="{{$result['Pair']}}_high" class="text-center">
                                                                    <span>{{$result['High']}}</span></td>
                                                                <td class="text-center"><span id="{{$result['Pair']}}_change"
                                                                                            class="{{$result['Colour']}}">{{$result['Percentage']}}</span>
                                                                </td>
                                                                <td id="{{$result['Pair']}}_volume"
                                                                    class="text-center">{{$result['Volume']}} {{$result['first_currency']}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Receivables Data -->

                                <!-- Start letters of credit Data -->
                                <div role="tabpanel" class="tab-pane fade" id="tab2">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center pairs-list">Instruments</th>
                                                            <th class="text-center">Last Price</th>
                                                            <th class="text-center">24H Low</th>
                                                            <th class="text-center">24H High</th>
                                                            <th class="text-center">Change(24H)</th>
                                                            <th class="text-center">Volume(24H)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($results as $result)
                                                            <tr id="{{$result['Pair']}}">
                                                                <td class="text-center" style="text-align: center"><strong
                                                                            class="icon-style"><img
                                                                                src="{{URL::asset('front')}}/assets/imgs/{{$result['second_currency']}}.png"><a
                                                                                href="{{url('/trade')}}">{{$result['Pair']}}</a></strong>
                                                                </td>
                                                                <td id="{{$result['Pair']}}_last"
                                                                    class="text-center"> {{$result['Last']}}</td>
                                                                <td id="{{$result['Pair']}}_low" class="text-center">
                                                                    <span>{{$result['Low']}}</span></td>
                                                                <td id="{{$result['Pair']}}_high" class="text-center">
                                                                    <span>{{$result['High']}}</span></td>
                                                                <td class="text-center"><span id="{{$result['Pair']}}_change"
                                                                                            class="{{$result['Colour']}}">{{$result['Percentage']}}</span>
                                                                </td>
                                                                <td id="{{$result['Pair']}}_volume"
                                                                    class="text-center">{{$result['Volume']}} {{$result['first_currency']}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End letters of credit Data -->

                                <!-- Start Bank Gaurantees Data -->
                                <div role="tabpanel" class="tab-pane fade" id="tab3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center pairs-list">Instruments</th>
                                                            <th class="text-center">Last Price</th>
                                                            <th class="text-center">24H Low</th>
                                                            <th class="text-center">24H High</th>
                                                            <th class="text-center">Change(24H)</th>
                                                            <th class="text-center">Volume(24H)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($results as $result)
                                                            <tr id="{{$result['Pair']}}">
                                                                <td class="text-center" style="text-align: center"><strong
                                                                            class="icon-style"><img
                                                                                src="{{URL::asset('front')}}/assets/imgs/{{$result['second_currency']}}.png"><a
                                                                                href="{{url('/trade')}}">{{$result['Pair']}}</a></strong>
                                                                </td>
                                                                <td id="{{$result['Pair']}}_last"
                                                                    class="text-center"> {{$result['Last']}}</td>
                                                                <td id="{{$result['Pair']}}_low" class="text-center">
                                                                    <span>{{$result['Low']}}</span></td>
                                                                <td id="{{$result['Pair']}}_high" class="text-center">
                                                                    <span>{{$result['High']}}</span></td>
                                                                <td class="text-center"><span id="{{$result['Pair']}}_change"
                                                                                            class="{{$result['Colour']}}">{{$result['Percentage']}}</span>
                                                                </td>
                                                                <td id="{{$result['Pair']}}_volume"
                                                                    class="text-center">{{$result['Volume']}} {{$result['first_currency']}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Bank Gaurantees Data -->

                                <!-- Start Bill of Lading Data -->
                                <div role="tabpanel" class="tab-pane fade" id="tab4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center pairs-list">Instruments</th>
                                                            <th class="text-center">Last Price</th>
                                                            <th class="text-center">24H Low</th>
                                                            <th class="text-center">24H High</th>
                                                            <th class="text-center">Change(24H)</th>
                                                            <th class="text-center">Volume(24H)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($results as $result)
                                                            <tr id="{{$result['Pair']}}">
                                                                <td class="text-center" style="text-align: center"><strong
                                                                            class="icon-style"><img
                                                                                src="{{URL::asset('front')}}/assets/imgs/{{$result['second_currency']}}.png"><a
                                                                                href="{{url('/trade')}}">{{$result['Pair']}}</a></strong>
                                                                </td>
                                                                <td id="{{$result['Pair']}}_last"
                                                                    class="text-center"> {{$result['Last']}}</td>
                                                                <td id="{{$result['Pair']}}_low" class="text-center">
                                                                    <span>{{$result['Low']}}</span></td>
                                                                <td id="{{$result['Pair']}}_high" class="text-center">
                                                                    <span>{{$result['High']}}</span></td>
                                                                <td class="text-center"><span id="{{$result['Pair']}}_change"
                                                                                            class="{{$result['Colour']}}">{{$result['Percentage']}}</span>
                                                                </td>
                                                                <td id="{{$result['Pair']}}_volume"
                                                                    class="text-center">{{$result['Volume']}} {{$result['first_currency']}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Bill of Lading Data -->

                                <!-- Start Warehousing Receipts Data -->
                                <div role="tabpanel" class="tab-pane fade" id="tab5">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center pairs-list">Instruments</th>
                                                            <th class="text-center">Last Price</th>
                                                            <th class="text-center">24H Low</th>
                                                            <th class="text-center">24H High</th>
                                                            <th class="text-center">Change(24H)</th>
                                                            <th class="text-center">Volume(24H)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($results as $result)
                                                            <tr id="{{$result['Pair']}}">
                                                                <td class="text-center" style="text-align: center"><strong
                                                                            class="icon-style"><img
                                                                                src="{{URL::asset('front')}}/assets/imgs/{{$result['second_currency']}}.png"><a
                                                                                href="{{url('/trade')}}">{{$result['Pair']}}</a></strong>
                                                                </td>
                                                                <td id="{{$result['Pair']}}_last"
                                                                    class="text-center"> {{$result['Last']}}</td>
                                                                <td id="{{$result['Pair']}}_low" class="text-center">
                                                                    <span>{{$result['Low']}}</span></td>
                                                                <td id="{{$result['Pair']}}_high" class="text-center">
                                                                    <span>{{$result['High']}}</span></td>
                                                                <td class="text-center"><span id="{{$result['Pair']}}_change"
                                                                                            class="{{$result['Colour']}}">{{$result['Percentage']}}</span>
                                                                </td>
                                                                <td id="{{$result['Pair']}}_volume"
                                                                    class="text-center">{{$result['Volume']}} {{$result['first_currency']}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Warehousing Receipts Data -->
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- End Assets Supported -->


<!-- Start Client Logo-->
<section class="section bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section_title text-center">
                    <h3 class="text-capitalize font-weight-bold">Interoperable and Portable with Leading Platforms</h3>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="logo_img mx-auto mt-3">
                    <img src="{{URL::asset('front')}}/home/assets/images/clients/corda.png" alt="logo-img" class="mx-auto img-fluid d-block">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="logo_img mx-auto mt-3">
                    <img src="{{URL::asset('front')}}/home/assets/images/clients/ibm.png" alt="logo-img" class="mx-auto img-fluid d-block">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="logo_img mx-auto mt-3">
                    <img src="{{URL::asset('front')}}/home/assets/images/clients/oracle.png" alt="logo-img" class="mx-auto img-fluid d-block">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Client Logo-->

<!-- Start Powered by -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section_title text-center">
                    <h3 class="text-capitalize font-weight-bold">Powered by</h3>
                </div>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-lg-4 col-md-4 offset-lg-4 offset-md-4">
                <div class="logo_img mx-auto mt-3">
                    <a href="https://xinfin.network/" target="_blank"><img src="{{URL::asset('front')}}/home/assets/images/xinfin.png" alt="logo-img" class="mx-auto img-fluid d-block"></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Powered by -->

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

<!-- Bxslider JS -->
<script src="{{URL::asset('front')}}/home/assets/js/bxslider.min.js"></script>

<!-- Custom Js   -->
<script src="{{URL::asset('front')}}/home/assets/js/custom.js"></script>

</body>

    

@section('xscript')
    {{--<script src="{{URL::asset('front')}}/assets/landing_page/assets/js/custom.js"></script>--}}
    <script>
        function change_captcha() {
            $("#capimg").html('Loading....');
            $.post('{{url("ajax/refresh_capcha")}}', function (data, result) {
                $("#capimg").html(data);
            });
        }
    </script>
    <script type="text/javascript">
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
        $(document).ready(function () {
            fillByMemory();
        });

        function fillByMemory() {
            if (!!$.cookie('email'))
                $('#login_mail').val($.cookie('email'));

            if (!!$.cookie('password'))
                $('#password').val($.cookie('password'));
        }
    </script>

    <script type="text/javascript">
        function updateprice() {
            var pair;
            $.ajax({
                url: "/ajax/updateprice",
                method: 'get',
                data: {},
                success: function (data) {
                    $.each(data, function (index) {
                        pair = data[index].Pair;
                        $('#' + pair + '_volume').text(data[index].Volume + ' ' + data[index].first_currency);
                        $('#' + pair + '_low').text(data[index].Low);
                        $('#' + pair + '_change').text(data[index].Percentage).attr('class', data[index].Colour);
                        $('#' + pair + '_high').text(data[index].High);
                        $('#' + pair + '_last').text(data[index].Last);
                    });
                }
            });
        }

        updateprice();
        setInterval(function () {
            updateprice();
        }, 10000);

        function search() {
            var input = $('#search_input').val();
            input = input.toUpperCase();
            var table = document.getElementById("currencyTable");
            var tr = table.getElementsByTagName("tr");
            for (var i = 0; i < tr.length; i++) {
                td = tr[i].id;
                if (td) {
                    var td1 = td.split("-");
                    if (td1[0].indexOf(input) > -1 || td1[1].indexOf(input) > -1)
                        tr[i].style.display = "";
                    else
                        tr[i].style.display = "none";
                }
            }
        }
    </script>
    <script>
var myIndex = 0;
carousel();

function carousel() {
  var i;
  var x = document.getElementsByClassName("banner-box");
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  myIndex++;
  if (myIndex > x.length) {myIndex = 1}    
  x[myIndex-1].style.display = "block";  
  setTimeout(carousel, 2000); // Change image every 2 seconds
}
</script>
@endsection