@extends('front.layout.front')
@section('content')

    <!-- login -->
    <section style="margin-top:50px; min-height:740px;">
        <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <h3>2FA Authentication</h3>
                    <form action="{{url('/logindo')}}" method="POST" id="tfa_form">
                        <!--<div class="row">-->
                        {{ csrf_field() }}
                        <div class="">
                            <hr>
                            <input type="hidden" name="tfa_key" value="{{Session::get('tfa_key')}}">
                            <div class="form-group">
                                <input type="text" class="form-control input-lg" name="tfa_code" placeholder="2FA Code">
                            </div>


                            <hr>
                            <button type="submit" class="btn btn-primary pull-left" name="ec_login"><i
                                        class="fa fa-sign-in"></i> &nbsp; Submit
                            </button>

                        </div>
                    </form>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </section>
    <!-- / login -->
@endsection

@section('xscript')

    <script type="text/javascript">
        $("#tfa_form").validate({
            rules:
                {
                    tfa_code: {required: true, number: true,},
                },
            messages:
                {
                    tfa_code: {required: 'Google Authenticator code is required', email: 'Digit only allowed',},
                },
        });
    </script>


@endsection