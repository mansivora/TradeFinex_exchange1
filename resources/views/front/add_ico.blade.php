@extends('front.layout.front')

@section('content')
    <div class="clearfix"></div>
    <div class="main-flex">
        <div class="main-content inner_content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">
                        <div class="panel panel-default panel-heading-space">
                            {{--<div class="panel-heading">Add ICO</div>--}}
                            <div class="panel-heading">Create Request to List Token.</div>
                            <div class="panel-body">
                                {{--<p>ICO rules (updated):</p>--}}
                                {{--<ul class="addico-main">--}}
                                    {{--<li>--}}
                                        {{--The coin team must provide the wallet source code so we can compile and test it and receive the ICO premine transfer.</li>--}}
                                    {{--<li>RocketIco: (10% fee + 1 btc prepay)--}}
                                        {{--<ul>--}}
                                            {{--<li>- 110%,109%,108%,107%,106%,105% price walls (each wall - 10% btc amount)</li>--}}
                                            {{--<li>- 20% of btc from ICO will go to Coin InvestBox Plan - 2% / daily</li>--}}
                                        {{--</ul>--}}
                                    {{--</li>--}}
                                    {{--<li>Normal ICO Pricing :</li>--}}
                                        {{--<ul>--}}
                                            {{--<li>- 3 BTC prepay + 5% of Amount Raised</li>--}}
                                            {{--<li>- 2 BTC prepay + 10% of Amount Raised</li>--}}
                                            {{--<li>- 1 BTC prepay + 15% of Amount Raised)</li>--}}
                                        {{--</ul>--}}
                                    {{--</li>--}}
                                    {{--<li>4 days ICO fund holding period</li>--}}
                                    {{--<li>If ICO Failed to reach Soft Cap, ExBlock will refund the investors using the ICO proceeds and consider the ICO failed and cancelled.</li>--}}
                                    {{--<li>Explorer URL should be announced in 4 days of holding period</li>--}}
                                    {{--<li>After the successful completion the above requirements, ExBlock will unlock the ICO account which holds the ICO proceeds</li>--}}
                                {{--</ul>--}}
                                {{--<br>--}}
                                {{--<h4>Create <span class="yellow">ExBlock ICO here.</span></h4>--}}

                                <p>CMBDEX supports fully compliant companies, Looking for Tokenization. We have a team of dedicated professional who can help you plan.</p>
                                <br>
                                <h4><span class="yellow">Proposed Token Specifications.</span></h4>

                                <form id="addico" action="{{url('/applytolist')}}" method="post">
                                    {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="coinname">Coin Name</label>
                                                <input id="coinname" type="text" class="form-control" placeholder="Enter Coin name" name="coinname" value="{{ old('coinname') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="ticker">Ticker</label>
                                                <input id="ticker" class="form-control" placeholder="Enter Ticker" name="ticker" value="{{ old('ticker') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="icotype">Token Type</label>

                                                <select id="icotype" class="form-control" name="icotype" value="{{ old('icotype') }}">
                                                    <option value="ERC20"> ERC20</option>
                                                    <option value="others"> others</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="contractaddress">Contract Address</label>
                                                <input id="contractaddress" type="text" class="form-control" placeholder="Eg: 0x41ab1b6fcbb2fa9dced81acbdec13ea6315f2bf2" name="contractaddress" value="{{ old('contractaddress') }}">
                                            </div>
                                        </div>


                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="decimals">Decimals</label>
                                                <input class="form-control" placeholder="Enter Decimals"  name="decimals" id="decimals" value="{{ old('decimals') }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="totalsupply">Total Supply</label>
                                                <input id="totalsupply" class="form-control" placeholder="Enter total supply"  name="totalsupply" value="{{ old('totalsupply') }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="circulatingsupply">Circulating Supply</label>
                                                <input id="circulatingsupply" class="form-control" placeholder="Enter circulating supply"  name="circulatingsupply" value="{{ old('circulatingsupply') }}">
                                            </div>
                                        </div>

                                        {{--<div class="col-sm-4">--}}
                                            {{--<div class="form-group">--}}
                                                {{--<label for="icosupply">ICO Supply</label>--}}
                                                {{--<input id="icosupply" class="form-control" placeholder="Enter ICO supply"  name="icosupply">--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="icoprice">Token Price(in USD)</label>
                                                <input id="icoprice" class="form-control" placeholder="Enter ICO price"  name="icoprice" value="{{ old('icoprice') }}">
                                            </div>
                                        </div>

                                        {{--<div class="col-sm-4">--}}
                                            {{--<div class="form-group">--}}
                                                {{--<label for="icoprice">ICO Price(in ETH)</label>--}}
                                                {{--<input id="ethicoprice" class="form-control" placeholder="Enter ICO price"  name="ethicoprice">--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                        {{--<div class="col-sm-4">--}}
                                            {{--<div class="form-group">--}}
                                                {{--<label for="icoprice">ICO Price(in BTC)</label>--}}
                                                {{--<input id="btcicoprice" class="form-control" placeholder="Enter ICO price"  name="btcicoprice">--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                        {{--<div class="col-sm-4">--}}
                                            {{--<div class="form-group">--}}
                                                {{--<label for="icoprice">ICO Price(in XDC)</label>--}}
                                                {{--<input id="xdcicoprice" class="form-control" placeholder="Enter ICO price"  name="xdcicoprice">--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                        {{--<div class="col-sm-4">--}}
                                            {{--<div class="form-group">--}}
                                                {{--<label for="icostartdate">ICO Start (date)</label>--}}
                                                {{--<input class="form-control" placeholder="Enter ICO start date"  name="icostartdate" id='icostartdate'>--}}
                                                {{--<span class="input-group-addon custom-calendar">--}}
                                                        {{--<span id="icostartdate" class="fa  fa-calendar"></span>--}}
                                                    {{--</span>--}}

                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-sm-4">--}}
                                            {{--<div class="form-group">--}}
                                                {{--<label for="icomaxdays">ICO max days</label>--}}
                                                {{--<input id="icomaxdays" class="form-control" placeholder="Enter ICO max days"  name="icomaxdays">--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="algo">Algorithm</label>
                                                <input id="algo" class="form-control" placeholder="Enter algo"  name="algo" value="{{ old('algo') }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="devlanguage">Dev language</label>
                                                <input id="devlanguage" class="form-control" placeholder="Enter dev language"  name="devlanguage" value="{{ old('devlanguage') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="verificationtype">Verification Type</label>

                                                <select id="verificationtype" class="form-control" name="verificationtype" value="{{ old('verificationtype') }}">
                                                    <option value="Others">Others</option>
                                                    <option value="POS">POS</option>
                                                    <option value="POW">POW</option>
                                                    <option value="POW-POS">POW-POS</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="git">Source link (Git)</label>
                                                <input id="git" class="form-control" placeholder="Enter source link"  name="git" value="{{ old('git') }}" >
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="explorer">Explorer</label>
                                                <input id="explorer" class="form-control" placeholder="Enter explorer"  name="explorer" value="{{ old('explorer') }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input id="email" class="form-control" placeholder="Enter email"  name="email" value="{{ old('email') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="skype">Skype (!!!) / Telegram</label>
                                                <input id="skype" class="form-control" placeholder="Enter skype"  name="skype" value="{{ old('skype') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="social">Social Medial Links</label>
                                                <textarea id="social" class="form-control" placeholder="Description"  name="social" value="{{ old('social') }}"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="comments">Comments/Additional Info</label>
                                                <textarea id="comments" class="form-control" placeholder="Comments"  name="comments" value="{{ old('comments') }}"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group text-right">
                                                <button type="submit" class="btn yellow-btn min-width-btn">Create Request</button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="clearfix"></div>
    </div>
@endsection
@section('xscript')

    <script src="{{URL::asset('front')}}/assets/js/bootstrap-datepicker.js"></script>

    {{--validate--}}
    <script type="text/javascript">
        $("#addico").validate({
            rules:
                {
                    coinname:{required:true,},
                    ticker:{required:true,},
                    icotype:{required:true,},
                    contractaddress:{required:true,},
                    decimals:{required:true,},
                    totalsupply:{required:true,},
                    circulatingsupply:{required:true,},
                    // icosupply:{required:true,},
                    icoprice:{required:true,},
                    // ethicoprice:{required:true,},
                    // btcicoprice:{required:true,},
                    // xdcicoprice:{required:true,},
                    // icostartdate:{required:true,},
                    // icomaxdays:{required:true,},
                    algo:{required:true,},
                    dev:{required:true,},
                    verificationtype:{required:true,},
                    source:{required:true,},
                    explorer:{required:true,},
                    email:{required:true,email:true},
                    skype:{required:true},
                    social:{required:true},
                    comments:{required:true,alphanumer:true},
                    enquiry_email:{required:true,},
                    enquiry_subject:{required:true,lettersonlys:true,},
                    telephone:{required:true,number:true},
                    enquiry_message:{required:true,alphanumer:true,}
                },
            messages:
                {
                    coinname:{required:'Name is required',},
                    ticker:{required:'Ticker is required',},
                    icotype:{required:'Coin type is required',},
                    contractaddress:{required:'Contract address is required',},
                    decimals:{required:'Decimals is required',},
                    totalsupply:{required:'Total supply is required',},
                    circulatingsupply:{required:'Circulating supply is required',},
                    // icosupply:{required:'Coin supply is required',},
                    icoprice:{required:'Coin price is required',},
                    // ethicoprice:{required:'Ico price in ETH is required',},
                    // btcicoprice:{required:'Ico price in BTC is required',},
                    // xdcicoprice:{required:'Ico price in XDC is required',},
                    // icostartdate:{required:'Start date is required',},
                    // icomaxdays:{required:'Maximum days is required',},
                    algo:{required:'Please mention which algorithm used ',},
                    dev:{required:'Please mention development language ',},
                    verificationtype:{required:'Please mention which verification type used ',},
                    source:{required:'Source code path required'},
                    explorer:{required:'Explorer path required'},
                    email:{required:'Email is required',email:'Enter valid email',},
                    skype:{required:'Please provide your skype/telegram id'},
                    social:{required:'Please provide social media links.',},
                    comments:{required:'Please provide comments/additional info if any, or else type N/A.',alphanumer:'No special charachters allowed.',},

                    enquiry_name:{required:'Name is required',},
                    enquiry_email:{required:'Email is required',email:'Enter valid email',},
                    enquiry_subject:{required:'Subject is required',},
                    telephone:{required:'Telephone number is required',number:'Enter valid number',minlength:'Please enter 10 digit number'},
                    enquiry_message:{required:'Message content is required',}
                }
        });

        $("#telephone").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 32 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 107) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });
        jQuery.validator.addMethod("alphanumer", function(value, element) {
            return this.optional(element) || /^([a-zA-Z0-9 _-]+)$/.test(value);
        }, 'Does not allow any grammatical connotation, like " : ./');

        jQuery.validator.addMethod("lettersonlys", function(value, element) {
            return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
        }, "Letters only please");

    </script>

    {{--ready function--}}
    <script>
        $(document).ready(function(){
            $(function () {
                $('#icostartdate').datepicker();

            });
            var $user_type = $('select[name=user_type]'),
                $subject_type = $('select[name=subject_type]'),
                $currency = $('select[name=currency]');
            var e = document.getElementById("subject_type");

            var strUser = e.options[e.selectedIndex].value;
            if(strUser == 'Otp')

            {
                document.getElementById('trans_div').style.display = 'none';
                document.getElementById('currency_div').style.display = 'none';
            }
            console.log(strUser);
            //user type change
            $user_type.change(function(){
                var $this = $(this).find(':selected'),
                    value = $this.attr('value'),

                    rel = $this.attr('rel'),
                    $set = $subject_type.find('option.' + rel);

                if ($set.length < 0) {

                    $subject_type.hide();
                    return;
                }
                if(value == 'guest')
                {
                    document.getElementById('trans_div').style.display = 'none';
                    document.getElementById('currency_div').style.display = 'none';
                }
                else
                {
                    document.getElementById('trans_div').style.display = 'block';
                    document.getElementById('currency_div').style.display = 'block';
                }

                $subject_type.show().find('option').hide();

                $set.show().first().prop('selected', true);
            });

            //subject type change
            $subject_type.change(function(){
                var $this = $(this).find(':selected'),
                    rel = $this.attr('rel'),
                    $set = $currency.find('option.' + rel);

                if (rel == 'else') {

                    document.getElementById('trans_div').style.display = 'none';
                    document.getElementById('currency_div').style.display = 'none';
                    $currency.hide();
                    return;
                }
                else
                {
                    document.getElementById('trans_div').style.display = 'block';
                    document.getElementById('currency_div').style.display = 'block';
                }

                $currency.show().find('option').hide();

                $set.show().first().prop('selected', true);
            });

            onloadSelect();

        });

        function onloadSelect()
        {

            var $user_type = $('select[name=user_type]'),
                $subject_type = $('select[name=subject_type]'),
                $currency = $('select[name=currency]');

            var  $user_selected = $user_type.find(':selected');

            var user = $user_selected.attr('rel');
            $set = $subject_type.find('option.' + user);

            $subject_type.show().find('option').hide();

            $set.show().first().prop('selected', true);

            var $sub_selected = $subject_type.find(':selected');

            var subject = $sub_selected.attr('rel');
            $set = $currency.find('option.'+subject);
            $currency.show().find('option').hide();
            $set.show().first().prop('selected', true);
        }

    </script>

    <script type="text/javascript">
        function recaptchaCallback() {
            $('#hiddenRecaptcha').valid();
        };
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.bar-toggle').on('click', function() {
                $('.leftbar').toggleClass('open');
            })
        })
    </script>
@endsection