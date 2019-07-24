@extends('front.layout.front')
@section('css')
    <style>
        ::-webkit-input-placeholder { /* WebKit browsers */
            opacity: 0.4 !important;
        }
    </style>
@endsection
@section('content')
    <div class="clearfix"></div>
    <div class="main-flex">
        <div class="main-content inner_content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default panel-heading-space">
                            <div class="panel-heading text-center">Create Asset Listing Request</div>
                            <div class="panel-body">

                                <p class="text-center">TradeFinex supports fully complaint companies, Looking for
                                    Tokenization. We have a team of dedicated professional who can help you plan.</p>
                                <br>
                                <h4 class="text-center"><span class="yellow">Proposed Token Specifications.</span></h4>

                                <div class="col-sm-12">

                                    <div class="form-group">
                                        <label>Token Type :</label>
                                        <select name="token_type" id="token_type" class="form-control">
                                            <option value="">Select Options</option>
                                            <option value="add_erc20">ERC20 Token</option>
                                            <option value="add_xinfin">XinFin Bond Token</option>
                                        </select>
                                    </div>
                                </div>

                                <form action="{{url('/add_erc20')}}" method="post" id="add_erc20">
                                    {{--<h3>Add ERC20 Request</h3>--}}
                                    {{ csrf_field() }}

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="coinname">Token Name</label>
                                            <input type="text" value="{{old('name')}}" name="name" id="name"
                                                   class="form-control" placeholder="Token Name"/>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="contract_address">Contract Address</label>
                                            <input type="text" value="{{old('contract_address')}}"
                                                   name="contract_address" id="contract_address" class="form-control"
                                                   placeholder="Contract Address"/>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="symbol">Token Symbol </label>
                                            <input type="text" value="{{old('symbol')}}"
                                                   style="text-transform: uppercase;" name="symbol" id="symbol"
                                                   class="form-control" placeholder="Token Symbol"/>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="token_decimals">Token Decimals </label>
                                            <input type="text" value="{{old('token_decimals')}}" name="token_decimals"
                                                   id="token_decimals" class="form-control"
                                                   placeholder="Enter Decimals"/>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="requested_by">Requested By </label>
                                            <input type="text" value="{{old('requested_by')}}" name="requested_by"
                                                   id="requested_by" class="form-control" placeholder="Requested By"/>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="logo_url">Logo URL </label>
                                            <input type="url" value="{{old('logo_url')}}" name="logo_url" id="logo_url"
                                                   class="form-control" placeholder="Logo URL"/>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="text" id="email_id" class="form-control"
                                                   placeholder="Enter Email" name="email_id">
                                        </div>
                                    </div>

                                    <hr/>

                                    <div class="col-sm-12">
                                        <div class="form-group text-right">
                                            <button type="submit" class="btn yellow-btn min-width-btn">Create Request
                                            </button>
                                        </div>
                                    </div>

                                </form>

                                <form action="{{url('/add_xinfin_bond')}}" method="post" id="add_xinfin">
                                    {{--<div class="col-sm-12"><p class="text-center">Coming Soon</p></div>--}}

                                    {{ csrf_field() }}

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="bond_name">Bond Name</label>
                                                <input type="text" value="{{old('bond_name')}}" name="bond_name"
                                                       id="bond_name" class="form-control" placeholder="Bond Name"/>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="issuer_name">Issuer Name</label>
                                                <input type="text" value="{{old('issuer_name')}}" name="issuer_name"
                                                       id="issuer_name" class="form-control" placeholder="Issuer Name"/>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="contract_address">Bond Contract Address</label>
                                                <input type="text" value="{{old('bond_contract_address')}}"
                                                       name="bond_contract_address" id="bond_contract_address"
                                                       class="form-control"
                                                       placeholder="Contract Address"/>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="bond_symbol">Bond Short Name/Symbol </label>
                                                <input type="text" value="{{old('bond_symbol')}}"
                                                       style="text-transform: uppercase;" name="bond_symbol"
                                                       id="bond_symbol" class="form-control"
                                                       placeholder="Bond Short Name/Symbol"/>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="coupon">Coupon (%) </label>
                                                <input type="text" value="{{old('coupon')}}" name="coupon" id="coupon"
                                                       onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                       class="form-control" placeholder="Coupon (%)"/>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="maturity_date">Maturity Date </label>

                                                <div class="input-group date">
                                                    <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span><input
                                                            id="maturity_date" name="maturity_date" type="text"
                                                            onkeydown="return false;"
                                                            class="form-control" value="{{ old('maturity_date') }}"
                                                            placeholder="Maturity Date">
                                                </div>
                                                <label for="maturity_date" generated="true" style="display: none;"
                                                       class="error"></label>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="payment_frequency">Payment Frequency</label>
                                                <select name="payment_frequency" id="payment_frequency"
                                                        class="form-control">
                                                    <option value="">Select Options</option>
                                                    <option value="daily">Daily</option>
                                                    <option value="weekly">Weekly</option>
                                                    <option value="biweekly">Biweekly</option>
                                                    <option value="monthly">Monthly</option>
                                                    <option value="quarterly">Quarterly</option>
                                                    <option value="yearly">Yearly</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="interest_date">Next Interest Payment Date </label>

                                                <div class="input-group date">
                                                    <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span><input
                                                            id="interest_date" name="interest_date" type="text"
                                                            onkeydown="return false;"
                                                            class="form-control" value="{{ old('interest_date') }}"
                                                            placeholder="Interest Payment Date">
                                                </div>
                                                <label for="interest_date" generated="true" style="display: none;"
                                                       class="error"></label>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="listing_date">Listing Date </label>

                                                <div class="input-group date">
                                                    <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span><input
                                                            id="listing_date" name="listing_date" type="text"
                                                            onkeydown="return false;" class="form-control"
                                                            value="{{old('listing_date')}}" placeholder="Listing Date">
                                                </div>
                                                <label for="listing_date" generated="true" style="display: none;"
                                                       class="error"></label>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="listing_price">Listing Price </label>
                                                <input type="text" value="{{old('listing_price')}}" name="listing_price"
                                                       onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                       id="lisitng_price" class="form-control"
                                                       placeholder="Listing Price"/>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" value="{{old('email_id')}}" id="email_id" class="form-control"
                                                       placeholder="Enter Email" name="email_id">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="requested_by">Requested By </label>
                                                <input type="text" value="{{old('requested_by')}}" name="requested_by"
                                                       id="requested_by" class="form-control"
                                                       placeholder="Requested By"/>
                                            </div>
                                        </div>

                                        <hr/>

                                        <div class="col-sm-12">
                                            <div class="form-group text-right">
                                                <button type="submit" class="btn yellow-btn min-width-btn">Create
                                                    Request
                                                </button>
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

    <link rel="stylesheet" href="{{URL::asset('datepicker/jquery-ui.css')}}">
    <script src="{{URL::asset('datepicker/jquery-ui.js')}}"></script>
    {{--<script src="{{URL::asset('front')}}/assets/js/bootstrap-datepicker.js"></script>--}}

    <script type="text/javascript">
        $("#add_erc20").validate({
            rules:
                {
                    name: {required: true},
                    contract_address: {required: true, alphanumer: true},
                    symbol: {required: true},
                    token_decimals: {required: true, regex: '^[0-9]+$'},
                    requested_by: {required: true},
                    email_id: {required: true, email: true},
                },
            messages:
                {
                    name: {required: 'Token name is required'},
                    contract_address: {required: 'Contract Address is required'},
                    symbol: {required: 'Token Symbol is required'},
                    token_decimals: {required: 'Token Decimals is required', regex: 'Only numbers allowed'},
                    requested_by: {required: 'Requested by is required'},
                    email_id: {required: 'Email id is required', email: 'Enter valid email id'},
                },
        });

        $("#add_xinfin").validate({
            rules:
                {
                    bond_name: {required: true},
                    issuer_name: {required: true},
                    bond_contract_address: {required: true, alphanumer: true},
                    bond_symbol: {required: true},
                    coupon: {required: true, number: true},
                    maturity_date: {required: true},
                    interest_date: {required: true},
                    payment_frequency: {required: true},
                    listing_date: {required: true},
                    listing_price: {required: true, number: true},
                    requested_by: {required: true},
                    email_id: {required: true, email: true},
                },
            messages:
                {
                    bond_name: {required: 'Bond name is required'},
                    issuer_name: {required: 'Issuer name is required'},
                    bond_contract_address: {required: 'Bond Contract Address is required'},
                    bond_symbol: {required: 'Bond short name/symbol is required'},
                    coupon: {required: 'Coupon (%) is required', number: 'Enter valid number format'},
                    maturity_date: {required: 'Maturity date is required'},
                    interest_date: {required: 'Interest date is required'},
                    listing_date: {required: 'Listing date is required'},
                    listing_price: {required: 'Listing price is required', number: 'Enter valid number format'},
                    payment_frequency: {required: 'Payment frequency is required'},
                    requested_by: {required: 'Requested by is required'},
                    email_id: {required: 'Email id is required', email: 'Enter valid email id'},
                },
        });

        jQuery.validator.addMethod("alphanumer", function (value, element) {
            return this.optional(element) || /^([a-zA-Z0-9 _-]+)$/.test(value);
        }, 'Does not allow any grammatical connotation, like " : ./');

        $(document).ready(function () {
            $('#add_erc20').hide();
            $('#add_xinfin').hide();

            $(function () {
                $("#maturity_date,#interest_date,#listing_date").datepicker({
                    todayBtn: "linked",
                    keyboardNavigation: false,
                    forceParse: false,
                    calendarWeeks: true,
                    autoclose: true,
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd/mm/yy',
                });
            });

            $('#token_type').change(function () {
                var value = $('#token_type').val();
                if (value == 'add_erc20') {
                    $('#add_xinfin').hide();
                    $('#add_erc20').show();
                }
                else if (value == 'add_xinfin') {
                    $('#add_erc20').hide();
                    $('#add_xinfin').show();
                }
                else {
                    $('#add_erc20').hide();
                    $('#add_xinfin').hide();
                }
            })
        });

    </script>

@endsection