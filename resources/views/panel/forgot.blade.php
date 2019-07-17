<!DOCTYPE html>
<html lang="en">
<head><title>Control Panel - Alphaex</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Loading bootstrap css-->
    <link type="text/css"
          href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,800italic,400,700,800">
    <link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,700,300">

    <link type="text/css" rel="stylesheet"
          href="{{ URL::asset('control/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ URL::asset('control/vendors/bootstrap/css/bootstrap.min.css') }}">
    <!--Loading style vendors-->
    <link type="text/css" rel="stylesheet" href="{{ URL::asset('control/vendors/animate.css/animate.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ URL::asset('control/vendors/iCheck/skins/all.css') }}">
    <!--Loading style-->
    <link type="text/css" rel="stylesheet" href="{{ URL::asset('control/css/themes/style3/pink-violet.css') }}"
          id="theme-change" class="style-change color-change">
    <link type="text/css" rel="stylesheet" href="{{ URL::asset('control/css/style-responsive.css') }}">
    <link href="{{ URL::asset('control/css/patternLock.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="{{ URL::asset('control/images/favicon.ico') }}">
</head>
<body id="signin-page">
<div class="page-form">
    <form action="{{url('/check_admin/forgot')}}" class="form" method="post">
        <div class="header-content"><h1>Forgot Password</h1></div>
        @if(count($errors) > 0)
            <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                @foreach($errors->all() as $er)
                    {{$er}}<br/>
                @endforeach
            </div>
        @endif
        @if(Session::has('error'))
            <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ Session('error') }}</div>
        @endif
        @if(Session::has('success'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ Session('success') }}</div>
        @endif
        <div class="body-content">
            {{ csrf_field() }}

            <div class="form-group">
                <div class="input-icon right"><i class="fa fa-user"></i><input type="email" placeholder="Email"
                                                                               name="forgot_username"
                                                                               class="form-control"></div>
            </div>


            <div class="form-group pull-right">
                <button type="submit" class="btn btn-success">Submit
                    &nbsp;<i class="fa fa-chevron-circle-right"></i></button>
            </div>
            <div class="clearfix"></div>

            <hr>
            <p>Back to <a id="btn-register" href="{{url('check_admin')}}">Login</a></p>
        </div>
    </form>
</div>
<script src="{{ URL::asset('control/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ URL::asset('control/js/jquery-migrate-1.2.1.min.js') }}"></script>
<script src="{{ URL::asset('control/js/jquery-ui.js') }}"></script>
<!--loading bootstrap js-->
<script src="{{ URL::asset('control/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('control/vendors/bootstrap-hover-dropdown/bootstrap-hover-dropdown.js') }}"></script>
<script src="{{ URL::asset('control/js/html5shiv.js') }}"></script>
<script src="{{ URL::asset('control/js/respond.min.js') }}"></script>
<script src="{{ URL::asset('control/vendors/iCheck/icheck.min.js') }}"></script>
<script src="{{ URL::asset('control/vendors/iCheck/custom.min.js') }}"></script>

<script src="{{ URL::asset('control/js/patternLock.js') }}"></script>
<script>//BEGIN CHECKBOX & RADIO
    $('input[type="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_minimal-grey',
        increaseArea: '20%' // optional
    });
    $('input[type="radio"]').iCheck({
        radioClass: 'iradio_minimal-grey',
        increaseArea: '20%' // optional
    });
    //END CHECKBOX & RADIO</script>

</body>
</html>