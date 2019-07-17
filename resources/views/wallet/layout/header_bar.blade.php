 <!--END THEME SETTING--><!--BEGIN BACK TO TOP--><a id="totop" href="#"><i class="fa fa-angle-up"></i></a><!--END BACK TO TOP--><!--BEGIN TOPBAR-->
    <div id="header-topbar-option-demo" class="page-header-topbar">
        <nav id="topbar" role="navigation" style="margin-bottom: 0;" data-step="3" data-intro="&lt;b&gt;Topbar&lt;/b&gt; has other styles with live demo. Go to &lt;b&gt;Layouts-&gt;Header&amp;Topbar&lt;/b&gt; and check it out." class="navbar navbar-default navbar-static-top">
            <div class="navbar-header">
                <button type="button" data-toggle="collapse" data-target=".sidebar-collapse" class="navbar-toggle"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                <a id="logo" href="{{url('walletjey/home')}}" class="navbar-brand"><span class="fa fa-rocket"></span><span class="logo-text">{{get_config('site_name')}}</span><span style="display: none" class="logo-text-icon">µ</span></a></div>
            <div class="topbar-main"><a id="menu-toggle" href="#" class="hidden-xs"><i class="fa fa-bars"></i></a>


                <ul class="nav navbar navbar-top-links navbar-right mbn">

                    <li class="dropdown topbar-user"><a data-hover="dropdown" href="#" class="dropdown-toggle"><img src="https://cdn4.iconfinder.com/data/icons/general24/png/128/administrator.png" alt="" class="img-responsive img-circle"/>&nbsp;<span class="hidden-xs">Admin Wallet</span>&nbsp;<span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-user pull-right">
                            <li><a href="{{url('walletjey/profile')}}"><i class="fa fa-user"></i>My Profile</a></li>

                             <li><a href="{{url('walletjey/change_pattern')}}"><i class="fa fa-lock"></i>Change Lock Pattern</a></li>
                            <li><a href="{{url('walletjey/logout')}}"><i class="fa fa-key"></i>Log Out</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
        </nav>

        </div>