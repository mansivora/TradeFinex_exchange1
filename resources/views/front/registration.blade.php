@extends('front.layout.front')
@section('content')
    <div class="clearfix"></div>
    <div class="main-flex">
        <div class="main-content inner_content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="panel panel-default panel-heading-space">
                            <div class="panel-heading text-center" style="color: black !important;">Registration Request
                                Sent
                            </div>
                            <div class="panel-body text-center">
                                <p>Thank you for registering with {{get_config('site_name')}}.</p>
                                <p><br> You will be sent an activation link within the next 5 minutes.</p>
                                <p><br>If you did not receive an email with the activation link, please check that you
                                    have input the correct email address, if not please re-register with the intended
                                    email account.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection