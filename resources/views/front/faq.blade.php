{{--@extends('front.layout.front')--}}
{{--@section('content')--}}
{{--<div class="clearfix"></div>--}}
{{--<div class="main-flex">--}}
{{--<div class="main-content inner_content">--}}
{{--<div class="container-fluid">--}}
{{--<div class="row">--}}
{{--<div class="col-md-12">--}}
{{--<div class="panel panel-default panel-heading-space">--}}
{{--<div class="panel-heading">Frequently Asked Questions</div>--}}
{{--<div class="panel-body">--}}
{{--<div class="wrapper center-block">--}}
{{--<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">--}}
{{--@if($data)--}}
{{--@foreach($data as $val)--}}
{{--<div class="panel panel-default custom-bg">--}}
{{--<div class="panel-heading accordion-toggle question-toggle collapsed" role="tab" id="heading">--}}
{{--<h4 class="panel-title">--}}
{{--<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse" data-target="#question{{$val->id}}" aria-expanded="false" aria-controls="collapse">--}}
{{--Q : {{$val->question}}--}}
{{--</a>--}}
{{--</h4>--}}
{{--</div>--}}
{{--<div class="panel-collapse collapse" id="question{{$val->id}}" role="tabpanel">--}}
{{--<div class="panel-body custom-padding">--}}
{{--{!! $val->description !!}--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--@endforeach--}}
{{--@endif--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}

{{--</div>--}}

{{--</div>--}}
{{--</div>--}}
{{--<div class="clearfix"></div>--}}
{{--</div>--}}
{{--@endsection--}}
{{--@section('xscript')--}}
{{--<script type="text/javascript">--}}
{{--$(document).ready(function () {--}}
{{--$('.bar-toggle').on('click', function () {--}}
{{--$('.leftbar').toggleClass('open');--}}
{{--});--}}
{{--$('.panel-collapse').on('hide.bs.collapse', function () {--}}
{{--$(this).siblings('.panel-heading').removeClass('active');--}}
{{--});--}}
{{--$('.panel-collapse').on('show.bs.collapse', function () {--}}
{{--$(this).siblings('.panel-heading').addClass('active');--}}
{{--});--}}
{{--})--}}
{{--</script>--}}
{{--@endsection--}}

@extends('front.layout.front')
@section('content')
    <div class="clearfix"></div>
    <div class="main-flex">
        <div class="main-content inner_content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">
                        <div class="panel panel-default panel-heading-space">
                            <div class="panel-heading text-center">Frequently Asked Questions</div>
                            <div class="panel-body">

                                <!-- Trading & Deposit & Withdrawal FAQ Starts -->
                                <div class="row row-flex">
                                    <div class="col-md-6">
                                        <div class="panel-heading text-center">
                                            <h4 class="faq-heading yellow">Trading</h4></div>
                                        <div class="panel-group accordion-box" id="accordion" role="tablist"
                                             aria-multiselectable="true">
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingOne">
                                                    <h4 class="panel-title">
                                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                                           href="#collapseOne" aria-expanded="false"
                                                           aria-controls="collapseOne">Trading Rule</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingOne">
                                                    <div class="panel-body">
                                                        <p class="fw-6">USDT Market</p>
                                                        <div class="table-responsive faq-table">
                                                            <table class="table">
                                                                <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th>Pair</th>
                                                                    <th>Min Amount</th>
                                                                    <th>Unit</th>
                                                                    <th>Minimum Price</th>
                                                                    <th>Unit</th>
                                                                    <th>Minimum Order Value</th>
                                                                    <th>Unit</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td>1</td>
                                                                    <td>BTC/USDT</td>
                                                                    <td></td>
                                                                    <td>BTC</td>
                                                                    <td></td>
                                                                    <td>USDT</td>
                                                                    <td></td>
                                                                    <td>USDT</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2</td>
                                                                    <td>ETH/USDT</td>
                                                                    <td></td>
                                                                    <td>ETH</td>
                                                                    <td></td>
                                                                    <td>USDT</td>
                                                                    <td></td>
                                                                    <td>USDT</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>3</td>
                                                                    <td>XRP/USDT</td>
                                                                    <td></td>
                                                                    <td>XRP</td>
                                                                    <td></td>
                                                                    <td>USDT</td>
                                                                    <td></td>
                                                                    <td>USDT</td>
                                                                </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingTwo">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse"
                                                           data-parent="#accordion" href="#collapseTwo"
                                                           aria-expanded="false" aria-controls="collapseTwo">Fee
                                                            Structure</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingTwo">
                                                    <div class="panel-body">
                                                        <p class="fw-6">Overview of Fee Structure
                                                            on {{get_config('site_name')}}</p>
                                                        <div class="table-responsive faq-table">
                                                            <table class="table">
                                                                <tbody>
                                                                <tr>
                                                                    <td>Fee for Deposit</td>
                                                                    <td>Free</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Fee for Withdrawal</td>
                                                                    <td>Varies for different coin, Refer to table of
                                                                        fees
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Fee for Trading</td>
                                                                    <td>0.1%</td>
                                                                </tr>
                                                                {{--<tr>--}}
                                                                {{--<td>Fee for trading if using USDT coins as deductible</td>--}}
                                                                {{--<td>0.05</td>--}}
                                                                {{--</tr>--}}
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <p class="fw-6">Trading Fees</p>
                                                        <p>Trading fees are set at 0.1%</p>
                                                        {{--<p>If you enable "Use of USDT coins" as deductible for trading fees, your trading fees will be reduced to 0.05 and the deductible amount will be charged to your USDT balance.</p>--}}
                                                        <p class="fw-6">Withdrawal/Deposit Fees</p>
                                                        <p>At {{get_config('site_name')}}, there are no fees for the
                                                            deposit of
                                                            coins/tokens.</p>
                                                        <p>The withdrawal fees and minimum withdrawal amount varies for
                                                            different coins/tokens.</p>
                                                        <p>The withdrawal fees are subjected to adjustments in
                                                            accordance with blockchain conditions.</p>
                                                        <p>Table of Fees:</p>
                                                        <div class="table-responsive faq-table">
                                                            <table class="table">
                                                                <thead>
                                                                <tr>
                                                                    <th>Coin/Token</th>
                                                                    <th>Withdrawal Fee</th>
                                                                    <th>Minimum Withdrawal</th>
                                                                    <th>Deposit Fee</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td>BTC</td>
                                                                    <td>BTC</td>
                                                                    <td>BTC</td>
                                                                    <td>Free</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ETH</td>
                                                                    <td>ETH</td>
                                                                    <td>ETH</td>
                                                                    <td>Free</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>USDT</td>
                                                                    <td>USDT</td>
                                                                    <td>USDT</td>
                                                                    <td>Free</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>XRP</td>
                                                                    <td>XRP</td>
                                                                    <td>XRP</td>
                                                                    <td>Free</td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{--<div class="panel panel-default">--}}
                                            {{--<div class="panel-heading" role="tab" id="headingThree">--}}
                                            {{--<h4 class="panel-title">--}}
                                            {{--<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Deductible fees for USDT coins</a>--}}
                                            {{--</h4>--}}
                                            {{--</div>--}}
                                            {{--<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">--}}
                                            {{--<div class="panel-body">--}}
                                            {{--<p>When you trade on {{get_config('site_name')}} exchange, you will have the opportunity to reduce your trading fee from 0.1 to 0.05 if you have a balance of USDT coins. By enabling "Use of USDT coin" in the Accounts Section, the 0.05% fee will be automatically deducted from your USDT Balance.</p>--}}
                                            {{--<p>You may turn off this function at any time.</p>--}}
                                            {{--<p>This function automatically turns off if you do not have enough USDT balance to cover the 0.05% trading fee.</p>--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="panel-heading text-center">
                                            <h4 class="faq-heading yellow">Deposit & Withdrawal</h4></div>
                                        <div class="panel-group accordion-box" id="accordion2" role="tablist"
                                             aria-multiselectable="true">
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingOne2">
                                                    <h4 class="panel-title">
                                                        <a role="button" data-toggle="collapse"
                                                           data-parent="#accordion2" href="#collapseOne2"
                                                           aria-expanded="false" aria-controls="collapseOne2">How to
                                                            Withdraw</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseOne2" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingOne2">
                                                    <div class="panel-body">
                                                        <ol>
                                                            <li> Login to {{get_config('site_name')}}</li>
                                                            <li> Click on Wallet Tab</li>
                                                            <li> Click on withdrawal</li>
                                                            <li> Copy the deposit address where you want to withdraw
                                                                to
                                                            </li>
                                                            <li> Input the deposit address to withdraw to
                                                                on {{get_config('site_name')}}
                                                            </li>
                                                            <li> Check the status of the transaction</li>
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingTwo2">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse"
                                                           data-parent="#accordion2" href="#collapseTwo2"
                                                           aria-expanded="false" aria-controls="collapseTwo2">Withdraw
                                                            to Wrong Address</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseTwo2" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingTwo2">
                                                    <div class="panel-body">
                                                        <p>{{get_config('site_name')}} will automatically start the
                                                            withdrawal process when
                                                            you click on the confirmation button. Due to the nature of
                                                            the blockchain, we are unable to locate your funds and there
                                                            is no way to stop the withdrawal process once it has been
                                                            confirmed.</p>
                                                        <p>If you have sent the funds wrongly, please try other means to
                                                            locate and retrieve your funds from the recipient.</p>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingThree2">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse"
                                                           data-parent="#accordion2" href="#collapseThree2"
                                                           aria-expanded="false" aria-controls="collapseThree2">How to
                                                            Deposit to {{get_config('site_name')}}</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseThree2" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingThree2">
                                                    <div class="panel-body">
                                                        <p>The steps to deposit coins/tokens to
                                                            a {{get_config('site_name')}} is as
                                                            follows:</p>
                                                        <ol>
                                                            <li> Login to {{get_config('site_name')}}</li>
                                                            <li> Click on Wallet Tab</li>
                                                            <li> Click on Deposit</li>
                                                            <li> Copy the address to deposit to</li>
                                                            <li> Paste the address in the withdrawal field of the other
                                                                wallet
                                                            </li>
                                                            <li> Check the status of the transaction</li>
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingFour2">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse"
                                                           data-parent="#accordion2" href="#collapseFour2"
                                                           aria-expanded="false" aria-controls="collapseFour2">Deposit
                                                            Wrong Coins</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseFour2" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingFour2">
                                                    <div class="panel-body">
                                                        <p>If you have deposited the wrong coins
                                                            to {{get_config('site_name')}}, we
                                                            generally do not conduct coin/token recovery service. If you
                                                            have suffered significant losses as a result of incorrectly
                                                            deposited coins/tokens, {{get_config('site_name')}} might at
                                                            our own discretion
                                                            assist you in the recovering of coin/tokens. However, the
                                                            process is extremely complex and would incur significant
                                                            cost, time and risk.</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingFive2">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse"
                                                           data-parent="#accordion2" href="#collapseFive2"
                                                           aria-expanded="false" aria-controls="collapseFive2">Deposit
                                                            did not arrive</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseFive2" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingFive2">
                                                    <div class="panel-body">
                                                        <p>Transactions on the blockchain will take a varying amount of
                                                            time to be confirmed and posted to the designated point.
                                                            With different blockchains requiring different amounts of
                                                            confirmations before a transaction is verified.</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingSix2">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse"
                                                           data-parent="#accordion2" href="#collapseSix2"
                                                           aria-expanded="false" aria-controls="collapseSix2">Cannot
                                                            receive email</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseSix2" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingSix2">
                                                    <div class="panel-body">
                                                        <p>To ensure that the email was sent correctly.</p>
                                                        <p>Please verify the email address and make sure that it is
                                                            correct.</p>
                                                        <p>Check spam folder in your email account to search for the
                                                            email.</p>
                                                        <p>It is recommended to use Gmail or Outlook.</p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- Trading & Deposit & Withdrawal FAQ Ends -->

                                <div class="clearfix"></div>

                                <!-- Account Access & Security FAQ Starts -->
                                <div class="row row-flex">
                                    <div class="col-md-6">
                                        <div class="panel-heading text-center">
                                            <h4 class="faq-heading yellow">Account Access</h4></div>
                                        <div class="panel-group accordion-box" id="accordion3" role="tablist"
                                             aria-multiselectable="true">
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingOne3">
                                                    <h4 class="panel-title">
                                                        <a role="button" data-toggle="collapse"
                                                           data-parent="#accordion3" href="#collapseOne3"
                                                           aria-expanded="false" aria-controls="collapseOne3">How to
                                                            Unlock Account</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseOne3" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingOne3">
                                                    <div class="panel-body">
                                                        <p>Content will be placed here......</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingTwo3">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse"
                                                           data-parent="#accordion3" href="#collapseTwo3"
                                                           aria-expanded="false" aria-controls="collapseTwo3">ID
                                                            Verification Process</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseTwo3" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingTwo3">
                                                    <div class="panel-body">
                                                        <p>The ID verification process will take approximately 15mins
                                                            and please ensure that you do not refresh the browser at any
                                                            time during the application process.</p>
                                                        <p>You may attempt 3 ID verifications process within a day. If
                                                            your application is denied you have to wait 24 hours from
                                                            the last attempt in order to try again.</p>
                                                        <p>To ensure a smooth and quick ID verification process, it is
                                                            advisable to have the documents that meet the required
                                                            conditions as stated below.</p>
                                                        <ol>
                                                            <li> Photo must be in PNG or JPEG format</li>
                                                            <li>Photos must be of high-resolution and clear enough to
                                                                display all information visibly
                                                            </li>
                                                            <li>Photos and documents must not be edited or manipulated
                                                            </li>
                                                            <li>Make sure photos are in colour</li>
                                                            <li>All documents presented and submitted must be
                                                                originals
                                                            </li>
                                                            <li>The ID used must be valid, expired ID will not be
                                                                acceptable
                                                            </li>
                                                            <li>Your Face must be clearly visible</li>
                                                            <li>The note presented must have the words
                                                                "{{get_config('site_name')}}" and the
                                                                exact date clearly stated.
                                                            </li>
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingThree3">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse"
                                                           data-parent="#accordion3" href="#collapseThree3"
                                                           aria-expanded="false" aria-controls="collapseThree3">Terms of
                                                            Use</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseThree3" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingThree3">
                                                    <div class="panel-body">
                                                        <p>Content will be placed here......</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="panel-heading text-center">
                                            <h4 class="faq-heading yellow">Security</h4></div>
                                        <div class="panel-group accordion-box" id="accordion4" role="tablist"
                                             aria-multiselectable="true">
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingOne4">
                                                    <h4 class="panel-title">
                                                        <a role="button" data-toggle="collapse"
                                                           data-parent="#accordion4" href="#collapseOne4"
                                                           aria-expanded="false" aria-controls="collapseOne4">Email
                                                            Fraud - Phishing</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseOne4" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingOne4">
                                                    <div class="panel-body">
                                                        <p>There are malicious emails designed to steal funds from your
                                                            personal wallet. At no point should you give out your
                                                            private keys and phrases.</p>
                                                        <p>{{get_config('site_name')}} will never ask you for your
                                                            private keys or
                                                            phrases.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingTwo4">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse"
                                                           data-parent="#accordion4" href="#collapseTwo4"
                                                           aria-expanded="false" aria-controls="collapseTwo4">URL Spoof
                                                            – Phishing</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseTwo4" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingTwo4">
                                                    <div class="panel-body">
                                                        <p>There maybe sites attempting to pass off
                                                            as {{get_config('site_name')}}.
                                                            Individuals maybe attacked by phishing websites via search
                                                            engines, referral links, browser plugins, extensions, third
                                                            parties or invalid apps. Make sure the Spelling and URL is
                                                            correct before entering your login details.</p>
                                                        <p>Below is an illustration of what the Login site and URL
                                                            should resemble:</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingThree4">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse"
                                                           data-parent="#accordion4" href="#collapseThree4"
                                                           aria-expanded="false" aria-controls="collapseThree4">Telegram
                                                            Scammer</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseThree4" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingThree4">
                                                    <div class="panel-body">
                                                        <p>Our telegram channel is used for discussions only. While we
                                                            have an admin, we do not provide customer support over
                                                            telegram. If you are facing an issue, please contact
                                                            (Example) on our official website.</p>
                                                        <p>{{get_config('site_name')}} will not ask you to send money or
                                                            coin/tokens to any
                                                            address for whatever reason. A person claiming to be with
                                                            {{get_config('site_name')}} and requesting such actions
                                                            raises red flags.</p>
                                                        <p>Report these users to our administrator.</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingFour4">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse"
                                                           data-parent="#accordion4" href="#collapseFour4"
                                                           aria-expanded="false" aria-controls="collapseFour4">Twitter
                                                            Scammer</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseFour4" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingFour4">
                                                    <div class="panel-body">
                                                        <p>Scammers might use twitter to ask for deposits to certain
                                                            addresses in exchange for gifts. {{get_config('site_name')}}
                                                            will never ask you
                                                            for deposits to any address.</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingFive4">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse"
                                                           data-parent="#accordion4" href="#collapseFive4"
                                                           aria-expanded="false" aria-controls="collapseFive4">Security
                                                            Tips</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseFive4" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingFive4">
                                                    <div class="panel-body">
                                                        <ol>
                                                            <li> Do not give out your password</li>
                                                            <li> Do not call any number claiming to be
                                                                from {{get_config('site_name')}}</li>
                                                            <li> Do not send money or coins/tokens to anyone claiming to
                                                                be from {{get_config('site_name')}}
                                                            </li>
                                                            <li> Enable Google 2 Factor Authentication</li>
                                                            <li> Double check the URL of the {{get_config('site_name')}}
                                                                address
                                                            </li>
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Account Access & Security FAQ Ends -->

                                <div class="clearfix"></div>

                                <!-- 2FA & Others FAQ Starts -->
                                <div class="row row-flex">
                                    <div class="col-md-6">
                                        <div class="panel-heading text-center">
                                            <h4 class="faq-heading yellow">2FA</h4></div>
                                        <div class="panel-group accordion-box" id="accordion5" role="tablist"
                                             aria-multiselectable="true">
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingOne5">
                                                    <h4 class="panel-title">
                                                        <a role="button" data-toggle="collapse"
                                                           data-parent="#accordion5" href="#collapseOne5"
                                                           aria-expanded="false" aria-controls="collapseOne5">Resetting
                                                            SMS Authentication</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseOne5" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingOne5">
                                                    <div class="panel-body">
                                                        <p>Content will be placed here......</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingTwo5">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse"
                                                           data-parent="#accordion5" href="#collapseTwo5"
                                                           aria-expanded="false" aria-controls="collapseTwo5">Did not
                                                            receive SMS Code</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseTwo5" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingTwo5">
                                                    <div class="panel-body">
                                                        <p>Content will be placed here......</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingThree5">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse"
                                                           data-parent="#accordion5" href="#collapseThree5"
                                                           aria-expanded="false" aria-controls="collapseThree5">Setting
                                                            up Google Authenticator</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseThree5" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingThree5">
                                                    <div class="panel-body">
                                                        <p>Content will be placed here......</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingFour5">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse"
                                                           data-parent="#accordion5" href="#collapseFour5"
                                                           aria-expanded="false" aria-controls="collapseFour5">Disabling
                                                            Google Authenticator</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseFour5" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingFour5">
                                                    <div class="panel-body">
                                                        <p>Content will be placed here......</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="panel-heading text-center">
                                            <h4 class="faq-heading yellow">Others</h4></div>
                                        <div class="panel-group accordion-box" id="accordion6" role="tablist"
                                             aria-multiselectable="true">
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingOne6">
                                                    <h4 class="panel-title">
                                                        <a role="button" data-toggle="collapse"
                                                           data-parent="#accordion6" href="#collapseOne6"
                                                           aria-expanded="false" aria-controls="collapseOne6">API</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseOne6" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingOne6">
                                                    <div class="panel-body">
                                                        <p>Content will be placed here......</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingTwo6">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse"
                                                           data-parent="#accordion6" href="#collapseTwo6"
                                                           aria-expanded="false" aria-controls="collapseTwo6">How to
                                                            contact us</a>
                                                    </h4>
                                                </div>
                                                <div id="collapseTwo6" class="panel-collapse collapse" role="tabpanel"
                                                     aria-labelledby="headingTwo6">
                                                    <div class="panel-body">
                                                        <p>You can send us an email at (example) or leave a message in
                                                            the chat box and we will get back to you as soon as we
                                                            can.</p>
                                                        <p>Alternatively, you can visit our Data Bank at (Example) to
                                                            preview the commonly asked questions and answers there.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- 2FA & Others FAQ Ends -->

                                <div class="clearfix"></div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="clearfix"></div>
    </div>
@endsection