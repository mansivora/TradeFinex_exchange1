<!DOCTYPE html>
<html lang="en">
@include("panel.layout.header")
<body>
<div>
@include("panel.layout.trade_header")
<!--END TOPBAR-->
    <div>
        <!--BEGIN PAGE WRAPPER-->
        <div><!--BEGIN TITLE & BREADCRUMB PAGE-->
        @yield("content")
        <!--END CONTENT--><!--BEGIN FOOTER-->
        @include('panel.layout.footer')
        <!--END FOOTER--></div>
        <!--END PAGE WRAPPER--></div>
</div>
@include('panel.layout.footer_script')

@yield("script")
<!--LOADING SCRIPTS FOR PAGE-->


</body>
</html>