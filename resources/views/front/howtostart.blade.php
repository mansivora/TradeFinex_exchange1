@extends('front.layout.front')

@section('content')
    <div class="clearfix"></div>
    <div class="main-flex">
        <div class="main-content inner_content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">
                        <div class="panel panel-default panel-heading-space">
                            <div class="panel-heading">How to Start</div>
                            <p class="padding-left">Its very simple</p>
                            <div class="panel-body">
                                <div class="col-sm-6 col-md-4 text-center hts-icons">
                                    <div class="how-we-start-icon"><img
                                                src="{{URL::asset('front')}}/assets/imgs//account-icon.png"
                                                alt="account"></div>
                                    <div>
                                        <h4>Create Your Account</h4>

                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 text-center hts-icons">
                                    <div class="how-we-start-icon"><img
                                                src="{{URL::asset('front')}}/assets/imgs//mobile-icon.png" alt="mobile">
                                    </div>
                                    <div>
                                        <h4>Verify Your Email & Mobile No.</h4>

                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 text-center hts-icons">
                                    <div class="how-we-start-icon"><img
                                                src="{{URL::asset('front')}}/assets/imgs//trading-icon.png" alt="trade">
                                    </div>
                                    <div>
                                        <h4>Start Trading</h4>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="clearfix"></div>
    </div>

@endsection