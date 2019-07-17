@extends('front.layout.front')

@section('content')
    <div class="clearfix"></div>
    <div class="main-flex">
        <div class="main-content inner_content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default panel-heading-space">
                            <div class="panel-heading">ICO
                                <a class="add-ico" href="{{url('/addico')}}">Add ICO</a>
                            </div>

                            <div class="panel-body">
                                <table id="ico" class="table table-striped table-bordered dt-responsive nowrap"
                                       style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Full Name</th>
                                        <th>ICO Coins</th>
                                        <th>ICO Price</th>
                                        <th>ICO Start</th>
                                        <th>Days</th>
                                        <th>Status</th>
                                        <th>Buy</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($result)
                                        @foreach($result as $singleico)
                                            <tr>
                                                <td>{{$singleico->ticker}}</td>
                                                <td>{{$singleico->name}}</td>
                                                <td>{{$singleico->ico_supply}}</td>
                                                <td>{{$singleico->ico_price}}</td>
                                                <td>{{$singleico->start_date}}</td>
                                                <td>{{$singleico->max_days}}</td>
                                                <td>{{$singleico->status}}</td>
                                                <td class="green withdraw"><a
                                                            onclick="buyico('{{$singleico->ticker}}')">Buy Coins</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>No icos found</td>
                                        </tr>
                                    @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="withdraw-overlay"></div>
    <div class="withdraw-block">
        <h3 class="withdraw-title"><img src="{{URL::asset('front')}}/assets/imgs/withdraw.png"> <label
                    id="ico_header"></label> <a class="close" href="#"><img
                        src="{{URL::asset('front')}}/assets/imgs/close.png"></a></h3>
        <form id="buyico" action="{{url('/buyico')}}" method="post">
            {{csrf_field()}}
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="graph-copy">
                            <p id="coin_name" name="coin_name">Coin Name</p>
                            <input type="text" id="first_currency" name="first_currency" value="" hidden>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <select id="second_currency" type="text" onchange="" name="second_currency"
                                class="form-control">
                            <option value="">Currency</option>
                            <option value="CMB">CMB</option>
                            <option value="ETH">ETH</option>
                            <option value="BTC">BTC</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="graph-copy">
                            <p id="currency" hidden>1 CMB = 1 XDCE</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" id="second_currency_amount"
                               onkeypress='return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                               class="form-control" placeholder="Amount against which purchase is to be made."
                               name="second_currency_amount">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" id="totalamount" class="form-control"
                               placeholder="Total coins that will be credited." value="" name="totalamount" disabled>
                    </div>
                </div>
                <div class="col-sm-12 otp text-right">
                    <div class="form-group">
                        <button type="submit" class="btn yellow-btn min-width-btn">Buy</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('xscript')
    <style>
        a.disabled {
            pointer-events: none;
            cursor: default;
        }
    </style>

    <script type="text/javascript">
        $('#buyico').validate({
            rules:
                {
                    second_currency_amount: {required: true, number: true,},
                },
            messages:
                {
                    second_currency_amount: {required: 'Buy Amount is required', number: 'Enter valid price.',},
                }
        })
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.withdraw').on('click', function () {
                $('.withdraw-block, .withdraw-overlay').addClass('is-active');
            })
            $('.close, .deposit-overlay').on('click', function () {
                $('.withdraw-block, .withdraw-overlay').removeClass('is-active');
            })
        })
    </script>

    <script type="text/javascript">
        var val1 = '';
        var value = '';
        var price = 0;
        var amount = 0;

        function buyico(val) {
            document.getElementById('buyico').reset();
            var validator = $("#buyico").validate();
            validator.resetForm();
            $('#ico_header').html('Buy ' + val + ' Coins via ICO.');
            $('#coin_name').html(val);
            $('#first_currency').val(val);
            val1 = val;
        }

        $(document).ready(function () {
            var currency = $('select[name=second_currency]');
            currency.change(function () {
                var $this = $(this).find(':selected');
                value = $this.attr('value');
                var first_currency = $('#first_currency').val();
                $.ajax({
                    url: "{{url('/ajax/geticorate')}}",
                    method: 'get',
                    data: {'first_currency': first_currency, 'second_currency': value},
                    success: function (data) {
                        obj = JSON.parse(data);
                        price = obj;
                        var validator = $("#buyico").validate();
                        validator.resetForm();
                        $('#second_currency_amount').val("");
                        $('#totalamount').val("");
                        $('#currency').text("1 " + value + " = " + price + " " + first_currency);
                        $('#currency').show();
                    }
                });
            });
        });
        $('#second_currency_amount').on('input', function (e) {
            amount = $('#second_currency_amount').val();
            var total = amount * price;
            var total1 = total.toString();
            total1 = total1 + " " + val1;
            $('#totalamount').val(total1);
        });
    </script>
@endsection