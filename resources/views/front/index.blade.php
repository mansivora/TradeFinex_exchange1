@extends('front.layout.front')
{{--@section('css')--}}
{{--<!-- Owl Carousel -->--}}
{{--<link href="{{URL::asset('front')}}/assets/landing_page/assets/css/owl-carousel/css/owl.carousel.min.css"--}}
      {{--rel="stylesheet">--}}
{{--<!-- Custom styles -->--}}
{{--<link href="{{URL::asset('front')}}/assets/landing_page/assets/css/custom.css" rel="stylesheet">--}}
{{--@endsection--}}
@section('content')
    <div class="banner-box">
        <div class="container">

        <div class="row">
                        <div class="col-lg-12">
                            <div class="text-white text-center">
                                <h1 class="header_title mx-auto mt-4 mb-0 font-weight-normal">Marketplace for Trade Finance Assets</h1>
                                <p class="header_subtitle mx-auto pt-4 mb-0 pb-2 text-white">Tokenize and Trade Cross border Trade Finance assets for Greater and Competitive Liquidity</p>
                               
                            </div>
                        </div>
                    </div>

            <!-- <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="banner-img">
                        <a href="#" target="_blank"><img
                                    src="{{URL::asset('front')}}/assets/imgs/banner_1.jpg" class=""/>Marketplace for Trade Finance Assets</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="banner-img">
                        <a href="#"><img src="{{URL::asset('front')}}/assets/imgs/banner_2.jpg" class=""/></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="banner-img">
                        <a href="#"><img src="{{URL::asset('front')}}/assets/imgs/banner_3.jpg" class=""/></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="banner-img">
                        <a href="#"><img src="{{URL::asset('front')}}/assets/imgs/banner_4.jpg" class=""/></a>
                    </div>
                </div>
            </div> -->


        </div>
    </div>
    <div class="banner-box">
        <div class="container">

        <div class="row">
                        <div class="col-lg-12">
                            <div class="text-white text-center">
                                <h1 class="header_title mx-auto mt-4 mb-0 font-weight-normal">Interoperable and Portable with Leading organisations like:</h1>
                                <p class="header_subtitle mx-auto pt-4 mb-0 pb-2 text-white">Connect with Corda, IBM Hyperledger, Oracle Blockchain</p>
                               
                            </div>
                        </div>
                    </div>

        


        </div>
    </div>
    <section class="home-full-gradient h-100vh" id="">
        <div class="bg-overlay-home" style="background-image: url(public/front/assets/landing_page/assets/images/demo-bg.jpg);"></div>
        <div class="home-table">
            <div class="home-table-center">
                <div class="container">
                    
                </div>
            </div>
        </div>
    </section>
        {{--<div class="banner-box_footer">--}}
        {{--<div class="container">--}}
        {{--<div class="row">--}}
        {{--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">--}}
        {{--<p style="text-align: center;"><a href="#">Security Check<span></span></a></p>--}}
        {{--</div>--}}
        {{--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">--}}
        {{--<p style="text-align: center;"><a href="#">Fintech - Conference<span></span></a></p>--}}
        {{--</div>--}}
        {{--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">--}}
        {{--<p style="text-align: center;"><a href="#" target="_blank">XDC Token Sale<span></span></a></p>--}}
        {{--</div>--}}
        {{--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">--}}
        {{--<p style="text-align: center;"><a href="#">Community Event<span></span></a></p>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
    </div>

    <div class="main-content padding-top-xs padding-bottom-xs">
        <div class="container">
            {{--<div class="row">--}}
            {{--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
            {{--<div class="searchbox search-head">--}}
            {{--<img src="{{URL::asset('front')}}/assets/imgs/search.png">--}}
            {{--<input id="search_input" class="form-control" type="text" placeholder="Search by name"--}}
            {{--onkeyup="search()">--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            <div class="row">
                <div class="col-md-12">
                    <div class="shadow">
                        <div class="table-responsive front-table">
                            <table class="table" id="currencyTable">
                                <thead>
                                <tr>
                                    <th class="text-center">Currency</th>
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
                                                        src="{{URL::asset('front')}}/assets/imgs/{{$result['first_currency']}}.png"><a
                                                        href="{{url('/trade/'. $result['Pair'])}}">{{$result['Pair']}}</a></strong>
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
                {{--<div class="col-md-3">--}}
                {{--<div class="login-bar">--}}
                {{--<form action="{{url('/login')}}" method="POST" id="login_form">--}}
                {{--{{ csrf_field() }}--}}
                {{--<h3>LOGIN</h3>--}}
                {{--<div class="form-group">--}}
                {{--<input type="text" class="form-control" id="login_mail" name="login_mail" placeholder="Email" required="required">--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                {{--<input type="password" class="form-control" id="password" name="password" placeholder="Password" required="required">--}}
                {{--</div>--}}
                {{--<div class="logincapcha">--}}
                {{--<div class="g-recaptcha" data-sitekey="6Lfbu1IUAAAAAAblvqe08OxhlaFwHu2-uD2mYHbO" data-callback="recaptchaCallback"></div>--}}
                {{--{!! NoCaptcha::display() !!}--}}
                {{--<p id="grecaptcha_error" class="error" hidden>Please verify that you are human.</p>--}}
                {{--<div class="col-md-10">--}}
                {{--<span id="capimg" style="margin-bottom: 20px; display: inline-block;">{!! captcha_img() !!}</span>--}}
                {{--</div>--}}
                {{--<div class="col-md-2">--}}
                {{--<a href="javascript:;" onclick="change_captcha()" class="m_tl">--}}
                {{--<i class="fa fa-refresh"></i></a>--}}
                {{--</div>--}}
                {{--<input type="text" class="form-control input-lg" name="captcha" placeholder="Captcha code">--}}
                {{--</div>--}}
                {{--<div class="clearfix">--}}
                {{--<label class="pull-left checkbox-inline"><input type="checkbox" name="remember_me"> Remember me</label>--}}
                {{--</div>--}}
                {{--<div class="">--}}
                {{--<button type="submit" class="btn btn-primary yellow-btn btn-block btn-lg" onclick="">Log in</button>--}}
                {{--</div>--}}
                {{--<div class="form-group forgot-password">--}}
                {{--<a href="{{url('/forgotpass')}}" class="already-have" style="color:#337ab7 !important">Forgot your Password?</a>--}}
                {{--</div>--}}
                {{--<div class="already-have">--}}
                {{--<div class="">--}}
                {{--<p>Don't have an Account? <a href="{{url('/register')}}">Create Now</a></p>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</form>--}}
                {{--</div>--}}
                {{--</div>--}}
            </div>

        </div>
    </div>


    {{--<div class="social-box">--}}
        {{--<div class="container">--}}
            {{--<div class="row">--}}
                {{--<div class="col-md-12 text-center">--}}
                    {{--<h3 class="h3-heading">Join US On</h3>--}}
                    {{--<div class="social-box-links">--}}
                        {{--<a href="#"><i class="fab fa-telegram"--}}
                                       {{--style="font-size: 2em"--}}
                                       {{--aria-hidden="true"></i></a>--}}
                        {{--<a href="#"><i--}}
                                    {{--class="fab fa-facebook-square" style="font-size: 2em"--}}
                                    {{--aria-hidden="true"></i></a>--}}
                        {{--<a href="#"><i class="fab fa-instagram" style="font-size: 2em" aria-hidden="true"></i></a>--}}
                        {{--<a href="#"><i class="fab fa-twitter-square"--}}
                                       {{--style="font-size: 2em"--}}
                                       {{--aria-hidden="true"></i></a>--}}
                        {{--<a href="#"><i class="fab fa-medium" style="font-size: 2em" aria-hidden="true"></i></a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<!-- Download -->--}}
    {{--<section class="download-guide white-bg">--}}
        {{--<div class="sided-item-wrapper">--}}
            {{--<div class="container">--}}
                {{--<div class="row justify-content-end">--}}
                    {{--<div class="col-md-7">--}}
                        {{--<div class="content-area padding-lg">--}}
                            {{--<h2>Launching a Digital Asset Exchange</h2>--}}
                            {{--<p>The demand to issue, acquire, and trade digital assets – proprietary tokens representing--}}
                                {{--loyalty points, financial instruments, or natively digital currencies such as bitcoin – has--}}
                                {{--grown exponentially over the past several years, creating a set of dynamic, overlapping--}}
                                {{--exchange ecosystems. This guide offers an introductory guide for enterprises interested in--}}
                                {{--understanding how to plan, launch and operate a successful exchange.</p>--}}
                            {{--<p class="mt-3 font-weight-medium">This Guide Covers:</p>--}}
                            {{--<ul class="icon-tik-list row">--}}
                                {{--<li class="col-md-6">--}}
                                    {{--<p>How digital asset markets works</p>--}}
                                {{--</li>--}}
                                {{--<li class="col-md-6">--}}
                                    {{--<p>What technology powers exchanges</p>--}}
                                {{--</li>--}}
                                {{--<li class="col-md-6">--}}
                                    {{--<p>What business leaders, entrepreneurs, or established firms need to get started</p>--}}
                                {{--</li>--}}
                                {{--<li class="col-md-6">--}}
                                    {{--<p>Which factors contribute to an exchange's success</p>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                            {{--<a class="btn blue-gradient" href="#">Download Now <i class="fa fa-download"></i></a>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="left-sided-full-image">--}}
                {{--<figure><img--}}
                            {{--src="{{URL::asset('front')}}/assets/landing_page/assets/images/launching-digital-asset-exchange.png"--}}
                            {{--alt=""></figure>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</section>--}}
    {{--<!-- End Download -->--}}

    <div class="feaured-box">
        <div class="container">
           <div class="row"> 
                <div class="col-md-8">
                    <h2 class="h2-heading">Assets Currently Supported</h2>
                   
                    <!-- <div class="col-md-6 mb-30">
                                <img src="{{URL::asset('front')}}/assets/imgs/featured.jpg" class="shadow_img">
                            </div> -->
                        <div class="col-md-6 mb-30">
                            <div class="row">
                                    <div class="col-md-12">
                                        <div class="shadow">
                                            <div class="table-responsive front-table">
                                                <table class="table" id="currencyTable">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">Receivables</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center">Letters of Credit</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center">Bank Guarantees</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center">Bill of Lading</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center">Warehousing receipts</th>
                                                    </tr>
                                                    </thead>
                                                   
                                                </table>
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
    </div>

    <div class="feaured-box">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="h3-heading">Feature for {{get_config('site_name')}} </h3>
                    <div class="row">
                    <!-- <div class="col-md-6 mb-30">
                                <img src="{{URL::asset('front')}}/assets/imgs/featured.jpg" class="shadow_img">
                            </div> -->
                        <div class="col-md-6 mb-30">
                            <ul class="icon-list">
                                <li><i><img src="{{URL::asset('front')}}/assets/imgs/time.png"></i> Lightning Real
                                    Time Speed
                                </li>
                                <li><i><img src="{{URL::asset('front')}}/assets/imgs/web.png"></i> Intuitive
                                    Interface
                                </li>
                                <li><i><img src="{{URL::asset('front')}}/assets/imgs/major.png"></i> Supports Major
                                    Crypto Pairs
                                </li>
                                <li><i><img src="{{URL::asset('front')}}/assets/imgs/coin.png"></i> Transparent Fee
                                    Structure
                                </li>
                                <li><i><img src="{{URL::asset('front')}}/assets/imgs/support.png"></i> Responsive
                                    Customer Support
                                </li>
                                <li><i><img src="{{URL::asset('front')}}/assets/imgs/responsive.png"></i> Mobile
                                    Responsive
                                </li>

                                {{--<li> Lighting Real Time Speed </li>--}}
                                {{--<li> Intuitive Interface </li>--}}
                                {{--<li> Supports Major Crypto Pairs </li>--}}
                                {{--<li> Transparent Fee Structure </li>--}}
                                {{--<li> Responisve Customer Support </li>--}}
                                {{--<li> Mobile Responsive </li>--}}
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 border-left-eee">
                    <h3 class="h3-heading">ANNOUNCEMENTS </h3>
                    <div class="announcements">
                        <div class="annunce-item">
                            <h4>Version 1.0 Live</h4>
                            <p>Beta Version 1 is currently Live, New update comes with host of features, security
                                enhancements and simpler navigation.</p>
                            {{--<span class="date">03 September, 2018 </span>--}}
                        </div>

                        <div class="annunce-item">
                            <h4>KYC Compliant</h4>
                            <p>Registered users can now complete their KYC in User Profile section. All users are
                                now requested to complete their KYC formalities.</p>
                            {{--<span class="date">03 September, 2018</span>--}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section2">
        <div class="container">
            <div class="hading-text">Powered by 
Exchange Infinite (XinFin) Hybrid Blockchain and XDC Protocol

            </div>
       
        </div>
    </div>
    
    </div>

    {{--<div class="clearfix"></div>--}}
@endsection
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