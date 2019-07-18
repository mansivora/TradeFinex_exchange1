@extends('front.layout.front')
@section('content')
    <div class="clearfix"></div>
    <div class="main-flex">
        <div class="main-content inner_content">
            <div class="container">
                <div class="col-md-12">
                    <div class="panel panel-default panel-heading-space">
                        <div class="panel-heading">My Wallet
                            <span class="wallet_balance">Wallet Balance : <span>$ {{number_format(get_total_usdbalance($userid),'4','.',',')}}</span></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table my-wallet-table">
                                    <thead>
                                    <tr>
                                        <th>Coin</th>
                                        <th>Balance</th>
                                        <th>In Orders</th>
                                        <th>Total</th>
                                        <th>Estimated US $</th>
                                        <th>Deposit</th>
                                        <th>Withdraw</th>
                                        <th>History</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($currencies as $val)
                                        <tr>
                                            <td class="title">
                                                <div class="item"><img
                                                            src="{{URL::asset('front')}}/assets/imgs/{{$val['currency']}}.png"> {{$val['currency']}}
                                                </div>
                                            </th>
                                            {{--<td><div class="item">{{number_format($val->balance,'8','.','')}}</div></td>--}}
                                            <td>
                                                <div class="item">{{number_format(get_userbalance($userid,$val['currency']),'4','.',',')}}</div>
                                            </td>
                                            <td>
                                                <div class="item">{{number_format(get_user_intradebalance($userid,$val['currency']),'4','.',',')}}</div>
                                            </td>
                                            <td>
                                                <div class="item">{{number_format((get_userbalance($userid,$val['currency'])+get_user_intradebalance($userid,$val['currency'])),'4','.',',')}}</div>
                                            </td>
                                            <td>
                                                <div class="item">
                                                    $ {{number_format((get_estimate_usd($val['currency'],get_userbalance($userid,$val['currency']))),'4','.',',')}}</div>
                                            </td>
                                            <td>
                                                <div class="item deposit"><a
                                                            onclick="depositmodal('{{$val['currency']}}')"
                                                            style="cursor:pointer"><img
                                                                src="{{URL::asset('front')}}/assets/imgs/deposit.png">
                                                        Deposit</a></div>
                                            </td>
                                            <td>
                                                <div class="item withdraw"><a id="withdrawal" style="cursor:pointer"
                                                                              onclick="withdrawalmodal('{{$val['currency']}}','{{$val['address']}}','{{get_userbalance($userid,$val['currency'])}}')"
                                                                              data-toggle="popover" title="Withdraw"
                                                                              data-content="Some content inside the popover"><img
                                                                src="{{URL::asset('front')}}/assets/imgs/withdraw.png">
                                                        Withdraw</a></div>
                                            </td>
                                            {{--<td><div class="item deposit"><img src="{{URL::asset('front')}}/assets/imgs/deposit.png"> Deposit</div></td>--}}
                                            {{--<td><div class="item deposit"><img src="{{URL::asset('front')}}/assets/imgs/withdraw.png"> Withdraw</div></td>--}}
                                            <td>
                                                <div class="item"><img
                                                            src="{{URL::asset('front')}}/assets/imgs/history.png"><a
                                                            href="{{url('/history')}}/{{$val['currency']}}"
                                                            style="cursor:pointer"> History</a></div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
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

    <div class="deposit-overlay"></div>
    <div class="deposit-block">
        <h3 class="deposit-title"><img src="{{URL::asset('front')}}/assets/imgs/deposit.png"><label
                    id="deposit-header"></label> <a class="close" href="#"><img
                        src="{{URL::asset('front')}}/assets/imgs/close.png"></a></h3>
        <div class="text-center">
            <div class="download-graph">
                <img id="deposit" name='deposit' src="https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=">
            </div>
        </div>
        <div class="graph-copy">
            <p id="deposit_add" name="deposit_add"></p>
            <div id="mess"></div>
            <a href="#" onclick="copyToClipboard('deposit_add')"><img
                        src="{{URL::asset('front')}}/assets/imgs/copy.png"> Copy</a>

        </div>
        <div class="destination" id="xrp_rule" style="display: none">
            <h3><span>Destination Tag :</span> <span id="destination_tag"> Tag</span></h3>
            <h4>Note</h4>
            <p>1. Please only transfer ripple tokens to this wallet address. Sending any others token may result into a
                loss and no wallet credit.</p>
            <p>2. Enter your destination tag in the source wallet (from where you are sending XRP tokens
                to {{get_config('site_name')}}
                wallet) along with the wallet address as mentioned above. If your wallet doesn’t ask for a destination
                tag, please transfer your XRP tokens to a local wallet first and then transfer it
                to {{get_config('site_name')}} using your
                destination tag as a unique and compulsory identifier.</p>
            <p>3. If you send XRP tokens without mentioning your destination tag, your deposit will not be processed and
                {{get_config('site_name')}} will not be responsible for the loss of tokens in that case.</p>
        </div>

    </div>
    <div class="withdraw-overlay"></div>

    {{--btc withdrawal--}}
    <div class="withdraw-block" id="btc_withdraw">
        <h3 class="withdraw-title"><img src="{{URL::asset('front')}}/assets/imgs/withdraw.png"> <label
                    id="withdraw_name"> </label>&nbsp;Withdraw <a class="close" href="#"><img
                        src="{{URL::asset('front')}}/assets/imgs/close.png"></a></h3>

        <h4>Note</h4>
        <p>Withdrawal request are being processed manually. Kindly expect a delay of 1-2 working days for its
            processing. Appreciate your patience.</p>
        <form id="fund_transfer" action="" method="post">
            {{csrf_field()}}
            <div class="row">
                {{--<div class="col-sm-12">--}}
                {{--<div class="form-group">--}}
                {{--<div class="form-group">--}}
                {{--<input type="text" class="form-control" placeholder="Your Address" id="your_addr" name="your_addr" value="" disabled>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="To Address" id="to_addr" name="to_addr">
                        <label id="error_addr" style="color: red" hidden>Address is invalid.</label>
                    </div>
                </div>
                <div id="xrp_tag" class="col-sm-12" style="display: none;">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="XRP Destination Tag" id="xrp_desttag"
                               name="xrp_desttag">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="graph-copy">
                            <p id="withdraw_balance">0 currency</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Transfer Amount" id="to_amount"
                               name="to_amount">
                        <label id="error_withdraw_amount" style="color: red" hidden>Insufficient Balance.</label>
                        <label id="error_val" style="display: none; color: red">*Minimum Withdraw limit: 5000</label>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Fees" id="fees" name="fees"
                               readonly="readonly">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Total Amount" id="total_amount"
                               name="total_amount" readonly="readonly">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="OTP" id="otp_code" name="otp_code">
                        <span class="send-otp" id="gen_otp"><a href="#" onclick="genotp();">Generate OTP</a></span>
                    </div>
                </div>
                {{--<div class="col-sm-12">--}}
                {{--<div id="countdown" style="display: none">--}}
                {{--<span style="float:left"> OTP sent to your mobile number. Resend Link:&nbsp;</span>--}}
                {{--<div id="minutes" style="float:left;color: red">00</div>--}}
                {{--<div style="float:left">:</div>--}}
                {{--<div id="seconds" style="float:left;color: red">00</div>--}}
                {{--</div>--}}
                {{--<div id="aftercount" style="display:none">OTP via call:&nbsp;<a href="#" onclick="otp_call()" style="color: lightblue">Click Here</a></div>--}}
                {{--<div id="aftercount_msg" style="display:none">*If you do not recieve OTP within 15 minutes please contact support</div>--}}
                {{--</div>--}}
                <div class="col-sm-12 otp text-right">
                    <div class="form-group">
                        <button class="btn yellow-btn min-width-btn" onclick="Submitform(event)">Withdraw</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="withdraw-overlay"></div>

@endsection
@section('xscript')

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script type="text/javascript">
        var amount = '';
        var currency = '';

        function withdrawalmodal(curr, addr, bal) {
            toastr.info('<div>Withdrawals are under maintenance.</div>');
            {{--if ({{$result->document_status}}!=1)--}}
            {{--{--}}
            {{--toastr.warning('<div>Please Complete your KYC to avoid any withdrawal issues. To Complete your KYC : <a href="/profile">Click Here.</a></div>')--}}
            {{--}--}}
            {{--else--}}
            {{--{--}}
            {{--document.getElementById('fund_transfer').reset();--}}
            {{--var validator = $("#fund_transfer").validate();--}}
            {{--validator.resetForm();--}}
            {{--$('#countdown').hide();--}}
            {{--$('#aftercount').hide();--}}
            {{--$('#aftercount_msg').hide();--}}
            {{--$('#error_withdraw_amount').hide();--}}
            {{--document.getElementById('error_val').style.display = 'none';--}}
            {{--document.getElementById('withdraw_name').innerHTML = curr;--}}
            {{--currency = curr;--}}
            {{--$('#gen_otp').html('<a href="#" onclick="genotp();">Generate OTP</a>');--}}

            {{--$('#withdraw_address').text(addr);--}}
            {{--$('#your_addr').val(addr);--}}
            {{--$('#withdraw_balance').text(parseFloat(bal).toFixed(8));--}}
            {{--if (curr == 'XRP') {--}}
            {{--$('#xrp_tag').css('display', 'block');--}}
            {{--}--}}
            {{--else {--}}
            {{--$('#xrp_tag').css('display', 'none');--}}
            {{--}--}}
            {{--$('.withdraw-block, .withdraw-overlay').addClass('is-active');--}}
            {{--}--}}
        }

        jQuery.validator.addMethod("minbal", function (value, element, params) {
                var min = 0;
                $.ajax({
                    async: false,
                    url: '/ajax/min_withdrawal',
                    method: 'get',
                    data: {'currency': currency},
                    success: function (data) {
                        min = parseFloat(JSON.parse(data));
                        value = parseFloat(value);
                    }
                });
                if (value < min) {
                    amount = min;
                    return false;
                }
                else {
                    amount = '0';
                    return true;
                }
            },
            function () {
                return "Minimum withdrawal amount for " + currency + " is " + " " + amount;
            });

        jQuery.validator.addMethod("notEqual", function (value, element, param) {
            return this.optional(element) || value != $(param).val();
        }, "This has to be different...");

        var valid = $("#fund_transfer").validate({
            rules:
                {
                    to_addr: {required: true, notEqual: '#your_addr'},
                    to_amount: {required: true, minbal: true},
                    otp_code: {required: true, number: true,},
                },
            messages:
                {
                    to_addr: {
                        required: 'To  Address is required',
                        notEqual: 'To address can not be same as your deposit address'
                    },
                    to_amount: {required: 'Transfer Amount is required'},
                    otp_code: {required: 'OTP Code is required', number: 'Enter Digit only'}
                }
        });

        $("#to_amount").keydown(function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 32 && (charCode < 46 || charCode > 57) && (charCode < 90 || charCode > 107) && (charCode < 109 || charCode > 111) && (charCode < 189 || charCode > 191))
                return false;
            return true;
        });

        function minAmount(curr) {
            if (curr == 'XDCE') {
                var amount = document.getElementById('to_amount').value;
                if (amount >= 5000) {
                    document.getElementById('error_val').style.display = 'none';
                    if (valid.form()) {
                        // $('#withdraw_bal').modal('show');
                        return true;
                    }
                }
                else {
                    document.getElementById('error_val').style.display = 'block';
                    return false;
                }
            }
            else {
                return true;
            }
        }

        function genotp() {
            var isdcode = '{{get_user_details($userid,'mob_isd')}}';
            var mobile = '{{owndecrypt(get_user_details($userid,'mobile_no'))}}';
            document.getElementById("gen_otp").disabled = true;
            var bal = document.getElementById('to_amount').value;
            if (bal < 5000 && currency == 'XDCE') {
                document.getElementById('error_val').style.display = 'block';
            }
            else {
                document.getElementById('error_val').style.display = 'none';
                {{--$.ajax({--}}
                {{--url: '{{url("ajax/verifyotp")}}',--}}
                {{--method: 'post',--}}
                {{--data: {'isdcode': isdcode, 'phone': mobile, 'type': 'Withdraw'},--}}
                {{--success: function (output) {--}}
                {{--obj = JSON.parse(output);--}}
                {{--if (obj.status == '1') {--}}
                {{--$("#gen_otp").html('<a href="#">Sent</a>');--}}
                {{--toastr.success(obj.message);--}}
                {{--$('#countdown').show();--}}
                {{--linkactivate();--}}
                {{--}--}}
                {{--}--}}
                {{--});--}}
                $.ajax({
                    url: '{{url("ajax/generate_email_otp")}}',
                    type: 'post',
                    data: 'key={{time()}}&type=Withdraw&_token={{ csrf_token() }}',
                    success: function (data) {
                        $("#gen_otp").html('<a href="#">Sent</a>');
                        // $('#message_modal').modal('show');
                        toastr.info('Please check the mail for the OTP.');
                    }
                });
            }
        }

        var valid1 = 'false';
        $("#to_amount").bind("input", function () {
            var amt = this.value;
            var feeper = "";
            var total;
            var trans;
            $.ajax({
                url: '{{url("ajax/getfee")}}',
                method: 'post',
                data: {'curr': currency},
                success: function (data) {
                    feeper = data;
                    if (currency == 'XDCE') {
                        total = parseFloat(amt) * (parseFloat(feeper) / 100);
                    }
                    else {
                        total = feeper;
                    }
                    $('#fees').val(parseFloat(total).toFixed(8));
                    trans = parseFloat(amt) - parseFloat(total);
                    if (trans > 0) {
                        $("#total_amount").val(trans);
                    }
                    else {
                        $('#total_amount').val(0);
                    }
                }
            });
            $.ajax({
                url: '{{url("ajax/limit_balance")}}',
                type: 'post',
                data: {'curr': currency, 'to_amount': amt},
                success: function (data) {
                    if (data == 'false') {
                        $('#error_withdraw_amount').show();
                        valid1 = 'false';
                    }
                    else {
                        $('#error_withdraw_amount').hide();
                        valid1 = 'true';
                    }
                }
            });
        });

        var valid3 = 'false';
        var valid4 = 'false';

        function Submitform(event) {
            event.preventDefault();
            if (currency != 'XDCE') {
                document.getElementById('error_val').style.display = 'none';
            }
            $('#fund_transfer').attr('action', "/transferverify/" + currency);
            var to_addr = $('#to_addr').val();
            var to_amount = $('#to_amount').val();
            var total = $('#total_amount').val();
            $.ajax({
                url: '{{url("ajax/address_validation")}}',
                type: 'post',
                data: {'curr': currency, 'to_addr': to_addr},
                success: function (data) {
                    if (data == 'false') {
                        $('#error_addr').show();
                        valid3 = 'false';
                    }
                    else {
                        $('#error_addr').hide();
                        valid3 = 'true';
                    }
                }
            });
            $.ajax({
                url: '{{url("ajax/limit_balance")}}',
                type: 'post',
                data: {'curr': currency, 'to_amount': to_amount},
                success: function (data) {
                    if (data == 'false') {
                        $('#error_withdraw_amount').show();
                        valid1 = 'false';
                    }
                    else {
                        $('#error_withdraw_amount').hide();
                        valid1 = 'true';
                    }
                }
            });
            $.ajax({
                url: '{{url("ajax/limit_balance")}}',
                type: 'post',
                data: {'curr': currency, 'to_amount': total},
                success: function (data) {
                    if (data == 'false') {
                        $('#error_withdraw_amount').show();
                        valid4 = 'false';
                    }
                    else {
                        $('#error_withdraw_amount').hide();
                        valid4 = 'true';
                    }
                }
            });
            // var valid2 = minAmount(currency);
            if (valid1 == 'true' && valid3 == 'true' && valid4 == 'true') {
                $('#fund_transfer').submit();
            }
        }

    </script>

    {{--<script type="text/javascript">--}}
    {{--function otp_call()--}}
    {{--{--}}
    {{--var isdcode = '{{get_user_details($userid,'mob_isd')}}';--}}
    {{--var mobile = '{{owndecrypt(get_user_details($userid,'mobile_no'))}}';--}}
    {{--$.ajax({--}}
    {{--url:'{{url("ajax/otpcall")}}',--}}
    {{--method:'post',--}}
    {{--data:{'mobile':mobile,'isdcode':isdcode},--}}
    {{--success : function(data)--}}
    {{--{--}}
    {{--document.getElementById('aftercount').innerHTML = 'Call request have been placed';--}}
    {{--}--}}
    {{--});--}}
    {{--}--}}
    {{--</script>--}}

    {{--for countdown timer--}}
    {{--<script>--}}
    {{--function linkactivate()--}}
    {{--{--}}
    {{--try--}}
    {{--{--}}
    {{--var sTime = new Date().getTime();--}}
    {{--var countDown = 30;--}}
    {{--function UpdateTime() {--}}
    {{--var cTime = new Date().getTime();--}}
    {{--var diff = cTime - sTime;--}}
    {{--var seconds = countDown - Math.floor(diff / 1000);--}}
    {{--if (seconds >= 0) {--}}
    {{--var minutes = Math.floor(seconds / 60);--}}
    {{--seconds -= minutes * 60;--}}
    {{--$("#minutes").text(minutes < 10 ? "0" + minutes : minutes);--}}
    {{--$("#seconds").text(seconds < 10 ? "0" + seconds : seconds);--}}
    {{--} else {--}}
    {{--$("#countdown").hide();--}}
    {{--$("#aftercount").show();--}}
    {{--$('#aftercount_msg').show();--}}
    {{--clearInterval(counter);--}}
    {{--}--}}
    {{--}--}}
    {{--UpdateTime();--}}
    {{--var counter = setInterval(UpdateTime, 500);--}}
    {{--}--}}
    {{--catch(e)--}}
    {{--{--}}
    {{--console.log(e);--}}
    {{--}--}}
    {{--}--}}
    {{--</script>--}}


    <script type="text/javascript">
        $(document).ready(function () {
            $('.bar-toggle').on('click', function () {
                $('.leftbar').toggleClass('open');
            })
            // $('.deposit').on('click', function () {
            // $('.deposit-block, .deposit-overlay').addClass('is-active');
            // })
            $('.close, .deposit-overlay').on('click', function () {
                $('.deposit-block, .deposit-overlay').removeClass('is-active');
            })
            {{--$('.withdraw').on('click', function (e)--}}
            {{--{--}}
            {{--$('.withdraw-block, .withdraw-overlay').addClass('is-active');--}}
            {{--if({{$result->document_status}}!=1)--}}
            {{--{--}}
            {{--toastr.error('<div>Please Complete your KYC before placing any withdrawal request. To Complete your KYC : <a href="/profile">Click Here.</a></div>')--}}
            {{--}--}}
            {{--else--}}
            {{--{--}}
            {{----}}
            {{--}--}}
            {{--});--}}
            $('.close, .deposit-overlay').on('click', function () {
                $('.withdraw-block, .withdraw-overlay').removeClass('is-active');
            });
        });

        function depositmodal(value) {
            // toastr.info('<div>Deposit and Withdrawals are under maintenance.</div>');
            $('.deposit-block, .deposit-overlay').addClass('is-active');
            document.getElementById('deposit-header').innerHTML = value + ' Deposit';
            $.ajax({
                url: '{{url("ajax/get_currency_address")}}',
                async: false,
                method: 'post',
                data: {'currency': value},
                success: function (data) {
                    if (value != 'XRP') {
                        document.getElementById('deposit').src = 'https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=' + data;
                        document.getElementById('deposit_add').innerHTML = data;
                        document.getElementById('xrp_rule').style.display = 'none';
                    }
                    else {
                        if (value == 'XRP') {
                            document.getElementById('xrp_rule').style.display = 'block';
                            document.getElementById('deposit').src = 'https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=rhfzdZgZPTSqGVW41cwdfG4uudEhMwnd22';
                            document.getElementById('deposit_add').innerHTML = 'r9TojxGYTM9T2FZT7xJrkoKXyypMGHfYy7';
                            document.getElementById('destination_tag').innerHTML = data;
                        }

                    }
                }
            });
            $('.deposit-block, .deposit-overlay').addClass('is-active');
        }

        function copyToClipboard(id) {
            var aux = document.createElement("input");
            aux.setAttribute("value", document.getElementById(id).innerHTML);
            document.body.appendChild(aux);
            aux.select();
            document.execCommand("copy");

            document.body.removeChild(aux);

            toastr.success('<div>Address Copied</div>');
        }
    </script>
@endsection