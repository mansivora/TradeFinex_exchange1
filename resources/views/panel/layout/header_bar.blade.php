<!--END THEME SETTING--><!--BEGIN BACK TO TOP--><a id="totop" href="#"><i
            class="fa fa-angle-up"></i></a><!--END BACK TO TOP--><!--BEGIN TOPBAR-->
<div id="header-topbar-option-demo" class="page-header-topbar">
    <input type="hidden" id="datetime" value="{{url('/datetime')}}">
    <nav id="topbar" role="navigation" style="margin-bottom: 0;" data-step="3"
         data-intro="&lt;b&gt;Topbar&lt;/b&gt; has other styles with live demo. Go to &lt;b&gt;Layouts-&gt;Header&amp;Topbar&lt;/b&gt; and check it out."
         class="navbar navbar-default navbar-static-top">
        <div class="navbar-header">
            <button type="button" data-toggle="collapse" data-target=".sidebar-collapse" class="navbar-toggle"><span
                        class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span
                        class="icon-bar"></span><span class="icon-bar"></span></button>

            <a id="logo" href="{{url('check_admin/home')}}" class="navbar-brand"><span class="fa fa-rocket"></span><span
                        class="logo-text">{{get_config('site_name')}}</span><span style="display: none"
                                                                                  class="logo-text-icon">µ</span></a>
        </div>
        <div class="topbar-main"><a id="menu-toggle" href="#" class="hidden-xs"><i class="fa fa-bars"></i></a>


            <ul class="nav navbar navbar-top-links navbar-right mbn">
                <li class="topbar-user"><label style="color: whitesmoke" id="dateid"></label></li>&nbsp;
                <li class="dropdown topbar-user"><a data-hover="dropdown" href="#" class="dropdown-toggle"><img
                                src="https://cdn4.iconfinder.com/data/icons/general24/png/128/administrator.png" alt=""
                                class="img-responsive img-circle"/>&nbsp;<span
                                class="hidden-xs">{{get_adminprofile('CMB_username')}}</span>&nbsp;<span
                                class="caret"></span></a>
                    <ul class="dropdown-menu dropdown-user pull-right">
                        <li><a href="{{url('check_admin/profile')}}"><i class="fa fa-user"></i>My Profile</a></li>
                        <li><a href="{{url('check_admin/site_settings')}}"><i class="fa fa-cog"></i>Site
                                Configuration</a></li>
                        <li><a href="{{url('check_admin/change_pattern')}}"><i class="fa fa-lock"></i>Change Lock Pattern</a>
                        </li>
                        <li><a href="{{url('check_admin/logout')}}"><i class="fa fa-key"></i>Log Out</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>

</div>

<script src="{{ URL::asset('control/js/jquery-1.10.2.min.js') }}"></script>
<script>
    $(document).ready(function () {
        var datetimeurl = document.getElementById('datetime').value;
        var datetimeview = document.getElementById('dateid');
        $.getJSON(datetimeurl, function (result) {
            datetimeview.innerText = result.date;
        });
        setInterval(function () {  // set Interval function to carry out same operation in the time specified
            $.getJSON(datetimeurl, function (result) {
                datetimeview.innerText = result.date;
            });
        }, 6000);
    });
</script>