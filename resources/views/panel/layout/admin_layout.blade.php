<!DOCTYPE html>
<html lang="en">
@include("panel.layout.header")
<body>
<div>
@include("panel.layout.header_bar")
<!--END TOPBAR-->
    <div id="wrapper"><!--BEGIN SIDEBAR MENU-->
    @include("panel.layout.sidebar")
    <!--END SIDEBAR MENU-->

        <!--BEGIN PAGE WRAPPER-->
        <div id="page-wrapper"><!--BEGIN TITLE & BREADCRUMB PAGE-->
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