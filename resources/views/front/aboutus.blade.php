@extends('front.layout.front')
@section('content')
    <div class="clearfix"></div>
    <div class="main-flex">
        <div class="main-content inner_content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default panel-heading-space">
                            <div class="panel-heading fs-24 text-center">About {{get_config('site_name')}}</div>
                            <div class="panel-body">
                                <div class="col-sm-10 col-sm-offset-1 feature-content">
                                    <p class="fs-14 text-center">A decentralized exchange for
                                        cryptocurrency, {{get_config('site_name')}} is a platform operated & maintained
                                        completely by software. The exchange enables the participants to trade directly
                                        with each other in the market without involvement of any trusted 3rd party that
                                        processes trades. Its simple and easy. </p>
                                </div>

                                <div class="col-sm-10 col-sm-offset-1 feature-content">
                                    <h4 class="panel-heading blue fs-24 mt-30 mb-15 text-center">Features</h4>
                                    <div class="row row-flex-small">
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-30">
                                            <div class="feature-box">
                                                <img src="{{URL::asset('front')}}/assets/imgs/features1.png">
                                                <h4>Aggregated Liquidity</h4>
                                                <p>{{get_config('site_name')}} offers a number of cryptocurrencies for
                                                    high trading opportunities.</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-30">
                                            <div class="feature-box">
                                                <img src="{{URL::asset('front')}}/assets/imgs/features2.png">
                                                <h4>High Security</h4>
                                                <p>High grade user security including 2 factor authentications.</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-30">
                                            <div class="feature-box">
                                                <img src="{{URL::asset('front')}}/assets/imgs/features3.png">
                                                <h4>User Interface</h4>
                                                <p>Simple and intuitive in its interface, designed for the best trading
                                                    experience.</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-30">
                                            <div class="feature-box">
                                                <img src="{{URL::asset('front')}}/assets/imgs/features4.png">
                                                <h4>Click Usability</h4>
                                                <p>One click buy and sell options.</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-30">
                                            <div class="feature-box">
                                                <img src="{{URL::asset('front')}}/assets/imgs/features5.png">
                                                <h4>Low Fees</h4>
                                                <p>Trading starts at the low default fee of 0.1%.</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-30">
                                            <div class="feature-box">
                                                <img src="{{URL::asset('front')}}/assets/imgs/features6.png">
                                                <h4>Extensive Customer Support</h4>
                                                <p>A comprehensive data Bank for the common FAQs in addition to a
                                                    responsive customer support team. </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="clearfix"></div>
@endsection