{{--<div class="scroll-top page-scroll visible-xs visible-sm"> <a class="btn btn-primary" href="#page-top"> <i class="fa fa-chevron-up"></i> </a> </div>--}}
{{--<!-- jQuery -->--}}
{{--<script src="{{URL::asset('front')}}/assets/js/jquery-3.3.1.js"></script>--}}
{{--<!-- Bootstrap Core JavaScript -->--}}
{{--<script src="{{URL::asset('front')}}/assets/js/bootstrap.min.js"></script>--}}
{{--<!-- Plugin JavaScript -->--}}
{{--<script src="{{URL::asset('front')}}/assets/js/jquery.easing.min.js"></script>--}}
{{--<script src="{{URL::asset('front')}}/assets/js/classie.js"></script>--}}
{{--<script src="{{URL::asset('front')}}/assets/js/cbpAnimatedHeader.js"></script>--}}
{{--<!-- Custom Theme JavaScript -->--}}
{{--<script src="{{URL::asset('front')}}/assets/js/main.js"></script>--}}

<script src="{{URL::asset('front')}}/assets/js/bootstrap-select.min.js" type="text/javascript"></script>
<script src="{{URL::asset('front')}}/assets/js/jquery.cookie.js" type="text/javascript"></script>

<script type="text/javascript">
    $(window).scroll(function () {
        if ($(this).scrollTop()) {
            $('#toTop').fadeIn();
        } else {
            $('#toTop').fadeOut();
        }
    });
    $("#toTop").click(function () {
        $("html, body").animate({scrollTop: 0}, 1000);
    });
    $(document).ready(function () {
        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOfflineStatus);

        function updateOnlineStatus() {
            $('#connection-error').css('display', 'none');
        }

        function updateOfflineStatus() {
            $('#connection-error').css('display', 'block');
        }

        setoffset();
    });
</script>

<script type="text/javascript">

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "25000",
        "hideDuration": "1000",
        "timeOut": "25000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    @if(isset($errors))
    @if(count($errors)>0)
    @foreach($errors->all() as $er)
    toastr.error("{{$er}}");
    @endforeach
    @endif
    @endif
    @if(Session::has('info'))//this line works as expected
    toastr.info("{{ Session::get('info') }}");
    @elseif(Session::has('error'))
    toastr.error("{{Session::get('error')}}");
    @elseif(Session::has('warning'))
    toastr.warning("{{Session::get('warning')}}");
    @elseif(Session::has('success'))
    toastr.success("{{Session::get('success')}}");

    @endif

    function setoffset() {
        // var dateVar = new Date()
        // var offset = dateVar.getTimezoneOffset();
        // document.cookie = "offset="+offset;
        var tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
        document.cookie = "tz=" + tz;
    }

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.js"></script>

<script>
    (function ($) {
        $(window).on("load", function () {
            $("#content-1").mCustomScrollbar({
                setTop: "5000px"
            });
        });
    })(jQuery);
</script>