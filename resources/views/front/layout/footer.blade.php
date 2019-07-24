<footer class="footer">
    <div class="footer-wrap">
        <div class="container">
            <div class="flexbox">
                <div class="footer-logo">
                    @if(Session::has('alphauserid'))
                        <a href="{{url('/trade')}}"><img src="{{URL::asset('front')}}/assets/imgs/footer-logo.png"/></a>
                    @else
                        <a href="{{url('/home')}}"><img src="{{URL::asset('front')}}/assets/imgs/footer-logo.png"/></a>
                    @endif
                </div>
                <div class="footer-menu">
                    <ul>
                        {{--<li><a href="{{url('/api')}}">API</a></li>--}}
                        <li><a href="{{url('/aboutus')}}">About us</a></li>
                        <li><a href="{{url('/faq')}}" target="_blank">FAQs</a></li>
                        <li><a href="{{url('/terms')}}">Terms & Conditions</a></li>
                        <li><a href="{{url('/privacy')}}">Privacy Policy</a></li>
                    <!-- <li><a href="{{url('/applytolist')}}">Apply to List</a></li> -->
                        <li><a href="{{url('/contact_us')}}">Contact us</a></li>
                        <li><a href="{{url('/add_asset')}}">Add Asset</a></li>

                    </ul>
                </div>
                <div class="footer-social">
                    <a href="#"><i class="fab fa-telegram" style="font-size: 2em"
                                   aria-hidden="true"></i></a>
                    <a href="#"><i class="fab fa-facebook-square"
                                   style="font-size: 2em"
                                   aria-hidden="true"></i></a>
                    <!-- <a href="https://www.instagram.com/explore/locations/396106724139605/plmp-fintech/?hl=en" target="_blank"><i class="fab fa-instagram" style="font-size: 2em" aria-hidden="true"></i></a> -->
                    <a href="#"><i class="fab fa-twitter-square"
                                   style="font-size: 2em"
                                   aria-hidden="true"></i></a>


                </div>
            </div>
        </div>
    </div>
    <div class="copy-right">
        <p>Copyright © 2019, {{get_config('site_name')}} All Rights reserved. </p>
    </div>
    <div id="toTop"><img src="{{URL::asset('front')}}/assets/imgs/go_to_top.png"></div>
</footer>