@extends('front.layout.front')
@section('content')
    <!-- login -->
    <section style="margin-top:50px; min-height:740px;">
        <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <h3>Forgot password</h3>
                    <form action="{{url('/forgotpass')}}" method="POST">
                        <!--<div class="row">-->
                        {{ csrf_field() }}
                        <div class="">
                            <hr>
                            <div class="form-group">
                                <input type="email" class="form-control input-lg" name="forgot_mail"
                                       placeholder="Email">
                            </div>


                            <hr>
                            <button type="submit" class="btn btn-primary pull-left" name="ec_login"><i
                                        class="fa fa-sign-in"></i> &nbsp; Submit
                            </button>
                            <span class="pull-left log_tx" style="margin-left:20px;
                            margin-top:12px;"> Already have an Account? <a href="{{url('/login')}}">Login</a>

							</span>
                        </div>
                    </form>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </section>
    <!-- / login -->
@endsection
{{--@section('xscript')--}}
{{--<script >--}}

{{--toastr.options = {--}}
{{--"closeButton": true,--}}
{{--"debug": false,--}}
{{--"newestOnTop": false,--}}
{{--"progressBar": true,--}}
{{--"positionClass": "toast-bottom-right",--}}
{{--"preventDuplicates": false,--}}
{{--"onclick": null,--}}
{{--"showDuration": "300",--}}
{{--"hideDuration": "1000",--}}
{{--"timeOut": "5000",--}}
{{--"extendedTimeOut": "1000",--}}
{{--"showEasing": "swing",--}}
{{--"hideEasing": "linear",--}}
{{--"showMethod": "fadeIn",--}}
{{--"hideMethod": "fadeOut"--}}
{{--}--}}
{{--@if(Session::has('info'))//this line works as expected--}}
{{--toastr.info("{{ Session::get('info') }}");--}}
{{--@elseif(Session::has('error'))--}}
{{--toastr.error("{{Session::get('error')}}");--}}
{{--@elseif(Session::has('warning'))--}}
{{--toastr.warning("{{Session::get('warning')}}");--}}
{{--@elseif(Session::has('success'))--}}
{{--toastr.success("{{Session::get('success')}}");--}}
{{--@endif--}}
{{--</script>--}}
{{--@endsection--}}