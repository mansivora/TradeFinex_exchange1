<!DOCTYPE html>
<html lang="en">
@include("wallet.layout.header")
<body>
<div>
   @include("wallet.layout.header_bar")
    <!--END TOPBAR-->
    <div id="wrapper"><!--BEGIN SIDEBAR MENU-->
        @include("wallet.layout.sidebar")
        <!--END SIDEBAR MENU-->

      <!--BEGIN PAGE WRAPPER-->
        <div id="page-wrapper"><!--BEGIN TITLE & BREADCRUMB PAGE-->
            @yield("content")
            <!--END CONTENT--><!--BEGIN FOOTER-->
            @include('wallet.layout.footer')
            <!--END FOOTER--></div>
        <!--END PAGE WRAPPER--></div>
</div>
 @include('wallet.layout.footer_script')

 @yield("script")
<!--LOADING SCRIPTS FOR PAGE-->






</body>
</html>