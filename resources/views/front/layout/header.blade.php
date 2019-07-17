<head>
    <meta charset="utf-8">
    {{--<meta http-equiv="X-UA-Compatible" content="IE=edge">--}}
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1">
    <meta name="keywords" content="{{get_meta_keywords()}}">
    <meta name="description" content="{{get_meta_description()}}">
    <meta name="author" content="indsoft">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{get_meta_title()}}</title>
    <link rel="icon" type="image/png" sizes="192x192" href="{{URL::asset('front')}}/assets/favicon/fav.png">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="{{URL::asset('front')}}/assets/css/bootstrap-select.min.css" rel="stylesheet" type="text/css">
    {{--css file for toastr notifications--}}
    <link href="{{URL::asset('front')}}/assets/css/toastr-2.1.3.css" rel="stylesheet" type="text/css">

    {{--<link href="{{URL::asset('front')}}/assets/css/bootstrap.min.css" rel="stylesheet">--}}

<!-- Custom CSS -->
    <!-- Custom Fonts -->
    <link href="{{URL::asset('front')}}/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet"
          type="text/css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://ajax.microsoft.com/ajax/jquery.ui/1.8.6/jquery-ui.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src="{{URL::asset('front')}}/assets/js/jquery-ui.min.js"></script>

    <link rel="stylesheet" href="{{URL::asset('front')}}/assets/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="{{URL::asset('front')}}/assets/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="{{URL::asset('front')}}/assets/css/ion.rangeSlider.css">
    <link rel="stylesheet" href="{{URL::asset('front')}}/assets/css/ion.rangeSlider.skinFlat.css">
    <link rel="stylesheet" href="{{URL::asset('front')}}/assets/css/normalize.css">

    <link rel="stylesheet" href="{{URL::asset('front')}}/assets/mCustomScrollbar/mCustomScrollbar.css">
    <link rel="stylesheet" href="{{URL::asset('front')}}/assets/mCustomScrollbar/jquery.mCustomScrollbar.css">

    <script src="{{URL::asset('front')}}/assets/js/jquery.dataTables.min.js"></script>
    <script src="{{URL::asset('front')}}/assets/js/dataTables.bootstrap.min.js"></script>
    <script src="{{URL::asset('front')}}/assets/js/dataTables.responsive.min.js"></script>
    <script src="{{URL::asset('front')}}/assets/js/responsive.bootstrap.min.js"></script>
    <script src="{{URL::asset('front')}}/assets/js/ion.rangeSlider.js"></script>
    <script src="{{URL::asset('front')}}/assets/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js"></script>

    {{--for toastr notifications--}}
    <script src="{{URL::asset('front')}}/assets/js/toastr-2.1.3.js"></script>

    {{--<link href="{{URL::asset('front')}}/assets/css/main.css" rel="stylesheet">--}}
    <link type="text/css" href="{{URL::asset('front')}}/assets/css/style.css" rel="stylesheet">
    <link href="{{URL::asset('front')}}/assets/css/responsive.css" rel="stylesheet">
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">--}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">

</head>