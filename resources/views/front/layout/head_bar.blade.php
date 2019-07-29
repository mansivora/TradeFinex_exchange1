<header>
    <nav class="navbar navbar-inverse manu-row">
        <div class="container-fluid">
            <div class="navbar-header">
                {{--<button type="button" class="bar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>--}}
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar"><span
                            class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
                </button>
                @if(Session::has('alphauserid'))
                    <a class="navbar-brand" href="{{url('/trade')}}"><img
                                src="{{URL::asset('front')}}/assets/imgs/logo.png"/></a>

                @else
                    <a class="navbar-brand" href="{{url('/home')}}"><img
                                src="{{URL::asset('front')}}/assets/imgs/logo.png"/></a>

                @endif
                {{--<p class="navbar-brand" style="color: #FFF;font-size: 12px !important; height: 48px; line-height: 35px; padding-top: 15px; padding-bottom: 15px;"><strong> THE FIRST SME CRYPTO EXCHANGE FOR STABLE COINS TRADING</strong></p>--}}
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav navbar-right">
                    @if(Session::has('alphauserid'))
                        <li><a href="{{url('/trade')}}">Trade</a></li>
                        <li><a href="{{url('/wallet')}}">Wallets</a></li>
                        <li><a href="{{url('/history')}}">Orders</a></li>
                        <li><a href="{{url('/contact_us')}}" target="_blank">Support</a></li>
                        <!-- <li><a href="{{url('/news')}}">News</a></li> -->
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
                        <li><a href="{{url('/home')}}">Home</a></li>
                        <li><a href="{{url('/trade')}}">Trade</a></li>
                        {{--<li><a href="{{url('/howtostart')}}">How to start</a></li>--}}
                        <li><a href="{{url('/contact_us')}}" target="_blank">Support</a></li>
                        <!-- <li><a href="{{url('/news')}}">News</a></li> -->
                        <li><a href="{{url('/login')}}">Login</a></li>
                        <li><a href="{{url('/register')}}">Register</a></li>
                        {{--<li><a href="#" class=""><span class=""> <img src="{{URL::asset('front')}}/assets/imgs/noti.png"> </span></a></li>--}}
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>