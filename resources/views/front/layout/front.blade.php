<!DOCTYPE html>
<html lang="en">
@include('front.layout.header')
<body class="home-bg front-side">


<!-- Navigation -->
@include('front.layout.head_bar')


@yield('css')
<!-- /.navbar -->

<!-- latest exchanges -->
@yield('content')
<div id="connection-error" class="col-md-3 connection-error" style="display:none">
    <div class="connection-error-image"><img src="{{URL::asset('/front')}}/assets/imgs/connection-error-image.png"
                                             alt="connection-image"/></div>
    <div class="connection-error-text">
        <h5>Connection error</h5>
        <p>Check your network connectivity and try again</p>
    </div>
</div>
<!-- / latest exchanges -->
<!-- footer -->
@include('front.layout.footer')
<!-- / footer -->
<!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
@include('front.layout.footer_script')
<!--selectpicker ends-->
@yield('xscript')
</body>
</html>
