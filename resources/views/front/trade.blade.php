@extends('front.layout.front')
@section('css')
    <style>
        body {
            font-size: 11px !important;
        }

        .modal_table td, .modal_table th {
            border: 1px solid #dddddd;
            /*text-align: left;*/
            padding: 8px;
        }

        .form-group {
            margin-bottom: 0px !important;
        }
    </style>
@endsection
@section('content')
    <div class="clearfix"></div>
    <!--<div class="main-flex">-->

    <div class="main-content">
        <!-- <div id="chartdiv"></div>     -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 padding-left-right-0">
                    <div class="col-md-6 mb-15">
                        <div class="shadow1 min-height-367 chart">
                            <div id="high_chartdiv"
                                 style="height: 367px !important; width: auto !important; margin-bottom: 10px"></div>
                        </div>
                    </div>

                    <div class="col-md-3 padding-left-0 mb-15">
                        <div class="shadow1 min-height-367">
                            <div class="panel panel-default order-book">
                                <div class="panel-heading">Order Book</div>
                                <div class="panel-body">
                                    <div class="table-responsive order-book-table">
                                        <table id="limit_sell_table" class="table no-space">
                                            <thead>
                                            <tr>
                                                <th class="text-center">Price</th>
                                                <th class="text-center">Volume</th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center">USD</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(isset($buy_order_list[0]))
                                                @foreach($buy_order_list as $buy_list)
                                                    <tr>
                                                        <td>{{number_format($buy_list->price,3,'.','')}}</td>
                                                        <td>{{number_format($buy_list->updated_qty,3,'.','')}}</td>
                                                        <td>{{number_format($buy_list->total,2,'.','')}}</td>
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                        <span class="divide-table"><span id="last_divide">0.12000000 ETH</span></span>
                                        <table id="limit_buy_table" class="table no-space">
                                            <thead>
                                            <tr>
                                                <th class="invisible">Price</th>
                                                <th class="invisible">Volume</th>
                                                <th class="invisible">Total</th>
                                                <th class="invisible">USD</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(isset($sell_order_list[0]))
                                                @foreach($sell_order_list as $sell_list)
                                                    <tr>
                                                        <td>{{number_format($sell_list->price,3,'.','')}}</td>
                                                        <td>{{number_format($sell_list->updated_qty,3,'.','')}}</td>
                                                        <td>{{number_format($sell_list->total,2,'.','')}}</td>
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 padding-left-0 mb-15">
                        <div class="shadow1 background-white min-height-367">
                            <div class="trading">
                                @foreach($pairs as $val)
                                    @if($pair == $val['Pair'])
                                        <div class="flex-bit" id="{{$val['Pair']}}" style="display:">
                                            <div class="bit-icon">
                                                <img src="{{URL::asset('front')}}/assets/imgs/{{$val['first_currency']}}.png"/>
                                                <div><span>{{$val['Pair']}}</span>
                                                    <p><span class="semi-black">VOL </span><span
                                                                id="{{$val['Pair']}}_volume"
                                                                class="{{$val['Colour']}}">{{$val['Volume']}} {{$val['first_currency']}}</span>
                                                        <br><span class="semi-black">LOW </span><span class="semi-black"
                                                                                                      id="{{$val['Pair']}}_low">{{$val['Low']}}</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="rate">
                                                <p class="semi-black"><span
                                                            id="{{$val['Pair']}}_last">{{$val['Last']}}</span> $<span
                                                            id="{{$val['Pair']}}_USD">0.000000</span></p>
                                                <p><span id="{{$val['Pair']}}_colour" class="{{$val['Colour']}}"><span
                                                                id="{{$val['Pair']}}_change">{{$val['Change']}}</span>    (<span
                                                                id="{{$val['Pair']}}_percent">{{$val['Percentage']}}</span>)</span>
                                                    <br><span class="semi-black">HIGH </span><span class="semi-black"
                                                                                                   id="{{$val['Pair']}}_high">{{$val['High']}}</span>
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex-bit" id="{{$val['Pair']}}" style="display:none">
                                            <div class="bit-icon">
                                                <img src="{{URL::asset('front')}}/assets/imgs/{{$val['first_currency']}}.png"/>
                                                <div><span>{{$val['Pair']}}</span>
                                                    <p><span class="semi-black">VOL </span><span
                                                                id="{{$val['Pair']}}_volume"
                                                                class="{{$val['Colour']}}">{{$val['Volume']}} {{$val['first_currency']}}</span>
                                                        <br><span class="semi-black">LOW </span><span class="semi-black"
                                                                                                      id="{{$val['Pair']}}_low">{{$val['Low']}}</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="rate">
                                                <p class="semi-black"><span
                                                            id="{{$val['Pair']}}_last">{{$val['Last']}}</span> $<span
                                                            id="{{$val['Pair']}}_USD">0.000000</span></p>
                                                <p><span id="{{$val['Pair']}}_colour" class="{{$val['Colour']}}"><span
                                                                id="{{$val['Pair']}}_change">{{$val['Change']}}</span>    (<span
                                                                id="{{$val['Pair']}}_percent">{{$val['Percentage']}}</span>)</span>
                                                    <br><span class="semi-black">HIGH  </span><span class="semi-black"
                                                                                                    id="{{$val['Pair']}}_high">{{$val['High']}}</span>
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="search-head">
                                <img src="{{URL::asset('front')}}/assets/imgs/search.png">
                                <input id="search_input" class="form-control" type="text" placeholder="Search by name"
                                       onkeyup="search()">
                            </div>
                            <div class="table-responsive all-coins">
                                <table id="currencytable" class="table">
                                    <thead>
                                    <tr style="padding: 5px;">
                                        <th><span class="star"><img src="{{URL::asset('front')}}/assets/imgs/star3.png"></span>
                                        </th>
                                        <th>Pair</th>
                                        <th>Price</th>

                                        {{--<th>fav</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody id="currencyTable">
                                    @if(isset($fav_pairs[0]))
                                        @foreach($fav_pairs as $key=>$val)
                                            <tr id="{{$val['Pair']}}">
                                                {{--<td><strong class="icon-style"><a href="{{url('/trade')}}/{{$val['Pair']}}" style="color: #ADADAD">{{$val['Pair']}}</a></strong></td>--}}
                                                <td><span class="star"><a href="#" id="{{$key}}r" class="fav active"
                                                                          title="[-] Remove from favorites"
                                                                          data-pair="{{$val['pair_id']}}"
                                                                          data-id="{{$key}}r">&nbsp;</a></span></td>
                                                <td><strong class="icon-style"><a href="#" style="color: #adadad;"
                                                                                  onclick="pair_change('{{$val['Pair']}}')">{{$val['Pair']}}</a></strong>
                                                </td>
                                                <td><span id="{{$val['Pair']}}_td_last"
                                                          class="{{$val['Colour']}}">{{$val['Last']}}</span></td>

                                                {{--<td><span id="{{$val['pair_id']}}">1</span></td>--}}
                                            </tr>
                                        @endforeach
                                    @endif
                                    @if(isset($remain_pairs))
                                        @foreach($remain_pairs as $key=>$val)
                                            <tr id="{{$val['Pair']}}">
                                                {{--<td><strong class="icon-style"><a href="{{url('/trade')}}/{{$val['Pair']}}" style="color: #ADADAD">{{$val['Pair']}}</a></strong></td>--}}
                                                <td><span class="star"><a href="#" id="{{$key}}a" class="fav"
                                                                          title="[+] Add as favorite"
                                                                          data-pair="{{$val['pair_id']}}"
                                                                          data-id="{{$key}}a">&nbsp;</a></span></td>
                                                <td><strong class="icon-style"><a href="#" style="color: #adadad;"
                                                                                  onclick="pair_change('{{$val['Pair']}}')">{{$val['Pair']}}</a></strong>
                                                </td>
                                                <td><span id="{{$val['Pair']}}_td_last"
                                                          class="{{$val['Colour']}}">{{$val['Last']}}</span></td>

                                                {{--<td><span id="{{$val['pair_id']}}">0</span></td>--}}
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 padding-left-right-0">
                    <div class="col-md-4">
                        <div class="block-radius-1 mb-15 shadow1 min-height-435">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#funds">Funds</a></li>
                                <li><a data-toggle="tab" href="#trade-history">My History</a></li>
                                <li><a data-toggle="tab" href="#open-order">Open Order</a></li>
                            </ul>

                            <div class="tab-content">
                                <div id="open-order" class="tab-pane fade">


                                    @if(!isset($user_id))
                                        <div class="flex-row relative">
                                            <div class="login-popup">
                                                <div class="login-wrap">
                                                    <a href="{{url('/login')}}" class="btn btn-lg yellow-btn-outline">Login</a>
                                                    <span>Or</span>
                                                    <a href="{{url('/register')}}"
                                                       class="btn btn-lg yellow-btn">Signup</a>
                                                    <div class="buy-sell">To Buy / Sell</div>
                                                </div>
                                            </div>
                                        </div>
                                    @else

                                        <div class="panel panel-default">
                                            <div class="panel-body">

                                                <div class="table-responsive">
                                                    <table id="open_order_table" class="table no-space">
                                                        <thead>
                                                        <div>
                                                            <button class="btn btn-danger pull-right btn-xs"
                                                                    id="cancel_button" style="display:none;"
                                                                    data-toggle="modal" data-target="#cancel_multiple">
                                                                Cancel Trades
                                                            </button>
                                                            <button class="btn btn-info pull-right btn-xs"
                                                                    id="checknone"
                                                                    style="margin-right:5px; display:none;"
                                                                    onclick="check_none()">None
                                                            </button>
                                                            <button class="btn btn-info pull-right btn-xs" id="checkall"
                                                                    style="margin-right:5px; display:none;"
                                                                    onclick="check_all()">All
                                                            </button>
                                                        </div>
                                                        <tr>
                                                            <th>Pair</th>
                                                            <th>Type</th>
                                                            <th>Volume</th>
                                                            {{--<th>{{$second_currency}}</th>--}}
                                                            <th>Price</th>
                                                            <th>Total</th>
                                                            <th></th>
                                                            <th>id</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @if(isset($active_orders[0]))
                                                            @foreach($active_orders as $key=>$active_ord)
                                                                <tr>
                                                                    <td>{{$active_ord->pair}}</td>
                                                                    @if($active_ord->type=='Buy')
                                                                        <td>
                                                                            <span class="green">{{$active_ord->type}}</span>
                                                                        </td>
                                                                    @else
                                                                        <td>
                                                                            <span class="red">{{$active_ord->type}}</span>
                                                                        </td>
                                                                    @endif
                                                                    <td>{{number_format($active_ord->updated_qty,3,'.','')}}</td>
                                                                    <td>{{number_format($active_ord->price,3,'.','')}}</td>
                                                                    <td>{{number_format($active_ord->total,2,'.','')}}</td>
                                                                    <td></td>
                                                                    <td>{{base64_encode($active_ord->id)}}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    @endif

                                </div>
                                <div id="trade-history" class="tab-pane fade">

                                    @if(!isset($user_id))
                                        <div class="flex-row relative">
                                            <div class="login-popup">
                                                <div class="login-wrap">
                                                    <a href="{{url('/login')}}" class="btn btn-lg yellow-btn-outline">Login</a>
                                                    <span>Or</span>
                                                    <a href="{{url('/register')}}"
                                                       class="btn btn-lg yellow-btn">Signup</a>
                                                    <div class="buy-sell">To Buy / Sell</div>
                                                </div>
                                            </div>
                                        </div>
                                    @else

                                        <div class="panel panel-default">
                                            <div class="panel-body">

                                                <div class="table-responsive">
                                                    <table id="my_trade_order_table" class="table no-space">
                                                        <thead>
                                                        <tr>
                                                            <th>Time</th>
                                                            <th>Type</th>
                                                            <th>Volume</th>
                                                            <th>Price</th>
                                                            <th>Total</th>
                                                            {{--<th>Status</th>--}}
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @if(isset($user_trade[0]))
                                                            @foreach($user_trade as $usertrade)
                                                                @if($usertrade->type=='Buy')
                                                                    <tr class="green">
                                                                @else
                                                                    <tr class="red">
                                                                        @endif
                                                                        <td>{{$usertrade->updated_at}}</td>
                                                                        @if($usertrade->type=='Buy')
                                                                            <td>
                                                                                <span class="green">{{$usertrade->type}}</span>
                                                                            </td>
                                                                        @else
                                                                            <td>
                                                                                <span class="red">{{$usertrade->type}}</span>
                                                                            </td>
                                                                        @endif
                                                                        {{--@if($usertrade->Type=='Buy')--}}
                                                                        {{--<td><span class="green">{{sprintf('%.8f',$usertrade->opt_price ? $usertrade->opt_price : $tradehis->Price)}}</span></td>--}}
                                                                        {{--@else--}}
                                                                        {{--<td><span class="green">{{sprintf('%.8f',$usertrade->opt_price ? $usertrade->opt_price : $usertrade->Price)}}</span></td>--}}
                                                                        {{--@endif--}}
                                                                        @if($usertrade->status=='partially')
                                                                            <td>{{number_format(($usertrade->original_qty-$usertrade->updated_qty),3,'.','')}}</td>
                                                                        @else
                                                                            <td>{{number_format($usertrade->original_qty,3,'.','')}}</td>
                                                                        @endif
                                                                        <td>{{number_format($usertrade->price,3,'.','')}}</td>
                                                                        <td>{{number_format($usertrade->updated_total,2,'.','')}}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div id="funds" class="tab-pane fade in active">

                                    @if(!isset($user_id))
                                        <div class="flex-row relative">
                                            <div class="login-popup">
                                                <div class="login-wrap">
                                                    <a href="{{url('/login')}}" class="btn btn-lg yellow-btn-outline">Login</a>
                                                    <span>Or</span>
                                                    <a href="{{url('/register')}}"
                                                       class="btn btn-lg yellow-btn">Signup</a>
                                                    <div class="buy-sell">To Buy / Sell</div>
                                                </div>
                                            </div>
                                        </div>
                                    @else

                                        <div class="panel panel-default">
                                            <div class="panel-body">

                                                <div class="table-responsive">
                                                    @if(isset($user_id))
                                                        <table class="table">
                                                            <thead>
                                                            <tr>
                                                                <th>Coin</th>
                                                                <th></th>
                                                                <th></th>
                                                                <th class="text-right">Balance</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($currency as $val)
                                                                <tr>
                                                                    <td><strong class="icon-style"><img
                                                                                    src="{{URL::asset('front')}}/assets/imgs/{{$val->currency_symbol}}.png"> {{$val->currency_symbol}}
                                                                        </strong></td>
                                                                    <td><a href="{{url('/wallet')}}">Deposit</a></td>
                                                                    <td><a href="{{url('/wallet')}}">Withdraw</a></td>
                                                                    <td class="text-right"><span
                                                                                id="{{$val->currency_symbol}}_bal">{{number_format(get_userbalance($user_id,$val->currency_symbol),4,'.','')}}</span>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-5 padding-left-0">
                        <div id="trade_tabs" class="block-radius-1 mb-15 shadow1 min-height-435">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#limit-order">Limit Order</a></li>
                                <li><a data-toggle="tab" href="#market-order">Market Order</a></li>
                            </ul>

                            <div class="tab-content">
                                <div id="limit-order" class="tab-pane fade in active">

                                    <div class="flex-row relative">
                                        @if(!isset($user_id))
                                            <div class="login-popup">
                                                <div class="login-wrap">
                                                    <a href="{{url('/login')}}" class="btn btn-lg yellow-btn-outline">Login</a>
                                                    <span>Or</span>
                                                    <a href="{{url('/register')}}"
                                                       class="btn btn-lg yellow-btn">Signup</a>
                                                    <div class="buy-sell">To Buy / Sell</div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-6 form-small border-right">
                                                <form id="limit_buy_order">
                                                    {{csrf_field()}}
                                                    <div class="heading flex-bit">
                                                        <input type="hidden" id="tradetype" name="tradetype"
                                                               value="limit_order">
                                                        <input type="hidden" id="type" name="type" value="Buy">
                                                        <input type="hidden" id="pair-buy" name="pair-buy"
                                                               value="{{$pair}}">
                                                        <input type="hidden" id="buy_rate" name="buy_rate"
                                                               value="{{number_format($buy_rate,'3','.','')}}">
                                                        <h3 class="green">Buy <span
                                                                    id="first_curr_buy">{{$first_currency}}</span></h3>
                                                        <span class="amount"><span
                                                                    id="second_curr_bal">{{number_format($second_cur_balance,4,'.','')}}</span> <span
                                                                    id="second_curr">{{$second_currency}}</span></span>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label class="control-label col-xs-3"
                                                                   for="email">Price</label>
                                                            <div class="col-xs-9">
                                                                <div class="flex-bit input-desc">
                                                                    <input id="buy_price" name="buy_price"
                                                                           onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                                           onkeyup="calculate_total('buy')"
                                                                           class="custom-input-text"
                                                                           value="{{sprintf('%.3f',$buy_rate)}}">
                                                                    <span class="opacity-down"
                                                                          id="second_curr_input">{{$second_currency}}</span>
                                                                </div>
                                                                <div class="error_gap">
                                                                    <label for="buy_price" generated="true"
                                                                           style="display: none;" class="error"></label>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-xs-3"
                                                                   for="email">Amount</label>
                                                            <div class="col-xs-9">
                                                                <div class="flex-bit input-desc">
                                                                    <input id="buy_amount" name="buy_amount"
                                                                           onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                                           onkeyup="calculate_total('buy')"
                                                                           class="custom-input-text"
                                                                           value="{{$min_trade}}">
                                                                    <span class="opacity-down"
                                                                          id="first_curr_input">{{$first_currency}}</span>
                                                                </div>
                                                                <div class="error_gap">
                                                                    <label for="buy_amount" generated="true"
                                                                           style="display: none;" class="error"></label>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="form-group col-xs-12">
                                                            <p class="pull-left"><span id="buy_usd">0.0000</span> USD
                                                            </p>
                                                            <a class="pull-right" style="cursor: pointer;"
                                                               onclick="multiple_modal('buy')">Buy Multiple</a>
                                                            <div class="error_gap"></div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="form-group col-xs-12">
                                                            {{--<div class="slidecontainer">--}}
                                                            <input type="text" min="0" max="100" value=""
                                                                   id="progress-bar-buy">
                                                            <br>
                                                            {{--<div class="error_gap"></div>--}}
                                                            <div class="clearfix"></div>
                                                            {{--</div>--}}
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-xs-3"
                                                                   for="email">Fees</label>
                                                            <div class="col-xs-9">
                                                                <div class="flex-bit input-desc">
                                                                    <input id="buy_fees" class="custom-input-text"
                                                                           value="" disabled>
                                                                    <span class="opacity-down"
                                                                          id="fees_input_buy">{{$second_currency}}</span>
                                                                </div>
                                                                <div class="error_gap"></div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-xs-3"
                                                                   for="email">Total</label>
                                                            <div class="col-xs-9">
                                                                <div class="flex-bit input-desc">
                                                                    <input id="buy_total" class="custom-input-text"
                                                                           value="{{($min_trade*(sprintf('%.3f',$buy_rate)))+(($min_trade*(sprintf('%.3f',$buy_rate))))*0.005}}"
                                                                           disabled>
                                                                    <span class="opacity-down"
                                                                          id="total_input_buy">{{$second_currency}}</span>
                                                                </div>
                                                                <div class="error_gap"></div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="form-group">

                                                            <div class="col-xs-12">
                                                                <p class="opacity-down fs pull-left">Note - Total
                                                                    includes 0.1% Fees.</p>
                                                                <div class="box-hover">
                                                                    <button class="btn btn-success btn-click btn-block"
                                                                            type="submit"
                                                                            onclick="submitModal('buy',event)">Buy
                                                                    </button>
                                                                    {{--<div class="box-open">--}}
                                                                    {{--<p>Are you sure want to buy the Bitcoin 1 BTC for 10000 ETH</p>--}}
                                                                    {{--<div class="row">--}}
                                                                    {{--<div class="col-xs-6">--}}
                                                                    {{--<button class="btn btn-success btn-cancel btn-sm btn-block">Cancel</button>--}}
                                                                    {{--</div>--}}
                                                                    {{--<div class="col-xs-6">--}}
                                                                    {{--<button class="btn btn-primary btn-confirm btn-sm btn-block" data-toggle="modal" data-target="#myModal">Confirm</button>--}}
                                                                    {{--</div>--}}
                                                                    {{--</div>--}}
                                                                    {{--</div>--}}
                                                                </div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-md-6 form-small">
                                                <form id="limit_sell_order">
                                                    {{csrf_field()}}
                                                    <div class=" col-md-12 heading flex-bit">
                                                        <input type="hidden" id="tradetype" name="tradetype"
                                                               value="limit_order">
                                                        <input type="hidden" id="type" name="type" value="Sell">
                                                        <input type="hidden" id="pair-sell" name="pair-sell"
                                                               value="{{$pair}}">
                                                        <input type="hidden" id="sell_rate" name="sell_rate"
                                                               value="{{number_format($sell_rate,'3','.','')}}">
                                                        <h3 class="red">Sell <span
                                                                    id="first_curr_sell">{{$first_currency}}</span></h3>
                                                        <span class="amount"><span
                                                                    id="first_curr_bal">{{number_format($first_cur_balance,4,'.','')}}</span> <span
                                                                    id="first_curr">{{$first_currency}}</span></span>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label class="control-label col-xs-3"
                                                                   for="email">Price</label>
                                                            <div class="col-xs-9">
                                                                <div class="flex-bit input-desc">
                                                                    <input id="sell_price" name="sell_price"
                                                                           onkeypress='return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                                           onkeyup="calculate_total('sell')"
                                                                           class="custom-input-text"
                                                                           value="{{sprintf('%.3f',$sell_rate)}}">
                                                                    <span class="opacity-down"
                                                                          id="second_curr_input_1">{{$second_currency}}</span>
                                                                </div>
                                                                <div class="error_gap">
                                                                    <label for="sell_price" generated="true"
                                                                           style="display: none;" class="error"></label>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-xs-3"
                                                                   for="email">Amount</label>
                                                            <div class="col-xs-9">
                                                                <div class="flex-bit input-desc">
                                                                    <input id="sell_amount" name="sell_amount"
                                                                           onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                                           onkeyup="calculate_total('sell')"
                                                                           class="custom-input-text"
                                                                           value="{{$min_trade}}">
                                                                    <span class="opacity-down"
                                                                          id="first_curr_input_1">{{$first_currency}}</span>
                                                                </div>
                                                                <div class="error_gap">
                                                                    <label for="sell_amount" generated="true"
                                                                           style="display: none;" class="error"></label>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>

                                                        <div class="form-group col-xs-12">
                                                            <p class="pull-left"><span id="sell_usd">0.0000</span> USD
                                                            </p>
                                                            <a class="pull-right" style="cursor: pointer;"
                                                               onclick="multiple_modal('sell')">Sell Multiple</a>
                                                            <div class="error_gap"></div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="form- col-xs-12">
                                                            {{--<div class="slidecontainer">--}}
                                                            <input type="text" min="0" max="100" id="progress-bar-sell"
                                                                   value="">
                                                            <br>
                                                            {{--<div class="error_gap"></div>--}}
                                                            <div class="clearfix"></div>
                                                            {{--</div>--}}
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-xs-3"
                                                                   for="email">Fees</label>
                                                            <div class="col-xs-9">
                                                                <div class="flex-bit input-desc">
                                                                    <input id="sell_fees" name="sell_fees"
                                                                           class="custom-input-text"
                                                                           value="{{($min_trade*(sprintf('%.3f',$sell_rate)))-(($min_trade*(sprintf('%.3f',$sell_rate))))*0.017}}"
                                                                           disabled>
                                                                    <span class="opacity-down"
                                                                          id="fees_input_sell">{{$second_currency}}</span>
                                                                </div>
                                                                <div class="error_gap"></div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-xs-3"
                                                                   for="email">Total</label>
                                                            <div class="col-xs-9">
                                                                <div class="flex-bit input-desc">
                                                                    <input id="sell_total" name="sell_total"
                                                                           class="custom-input-text"
                                                                           value="{{($min_trade*(sprintf('%.3f',$sell_rate)))-(($min_trade*(sprintf('%.3f',$sell_rate))))*0.017}}"
                                                                           disabled>
                                                                    <span class="opacity-down"
                                                                          id="total_input_sell">{{$second_currency}}</span>
                                                                </div>
                                                                <div class="error_gap"></div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="form-group">

                                                            <div class="col-xs-12">
                                                                <p class="opacity-down fs pull-left">Note - Total
                                                                    includes 0.1% Fees.</p>
                                                                <div class="box-hover">
                                                                    <button class="btn btn-danger btn-click btn-block"
                                                                            onclick="submitModal('sell',event)">Sell
                                                                    </button>
                                                                    {{--<div class="box-open">--}}
                                                                    {{--<p>Are you sure want to buy the Bitcoin 1 BTC for 10000 ETH</p>--}}
                                                                    {{--<div class="row">--}}
                                                                    {{--<div class="col-xs-6">--}}
                                                                    {{--<button class="btn btn-success btn-cancel btn-sm btn-block">Cancel</button>--}}
                                                                    {{--</div>--}}
                                                                    {{--<div class="col-xs-6">--}}
                                                                    {{--<button class="btn btn-primary btn-confirm btn-sm btn-block" >Confirm</button>--}}
                                                                    {{--</div>--}}
                                                                    {{--</div>--}}
                                                                    {{--</div>--}}
                                                                </div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div id="market-order" class="tab-pane fade">
                                    <div class="flex-row relative">
                                        @if(!isset($user_id))
                                            <div class="login-popup">
                                                <div class="login-wrap">
                                                    <a href="{{url('/login')}}" class="btn btn-lg yellow-btn-outline">Login</a>
                                                    <span>Or</span>
                                                    <a href="{{url('/register')}}"
                                                       class="btn btn-lg yellow-btn">Signup</a>
                                                    <div class="buy-sell">To Buy / Sell</div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-6 form-small border-right">
                                                <form id="market_buy_order">
                                                    {{csrf_field()}}
                                                    <div class="heading flex-bit">
                                                        <input type="hidden" id="tradetype" name="tradetype"
                                                               value="market_order">
                                                        <input type="hidden" id="type" name="type" value="Buy">
                                                        <input type="hidden" id="pair-buy_market" name="pair-buy_market"
                                                               value="{{$pair}}">
                                                        <input type="hidden" id="buy_rate_market" name="buy_rate_market"
                                                               value="{{number_format($buy_rate,'3','.','')}}">
                                                        <h3 class="green">Buy <span
                                                                    id="first_curr_buy_market">{{$first_currency}}</span>
                                                        </h3>
                                                        <span class="amount"><span
                                                                    id="second_curr_bal_market">{{number_format($second_cur_balance,4,'.','')}}</span> <span
                                                                    id="second_curr_market">{{$second_currency}}</span></span>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label class="control-label col-xs-3"
                                                                   for="email">Price</label>
                                                            <div class="col-xs-9">
                                                                <div class="flex-bit input-desc">
                                                                    <span>Market Price</span>
                                                                    <span class="opacity-down"
                                                                          id="second_curr_input_market">{{$second_currency}}</span>
                                                                </div>
                                                                <div class="error_gap"
                                                                     style="margin-bottom: 10px;"></div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-xs-3"
                                                                   for="email">Amount</label>
                                                            <div class="col-xs-9">
                                                                <div class="flex-bit input-desc">
                                                                    <input id="buy_amount_market"
                                                                           name="buy_amount_market"
                                                                           onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                                           class="custom-input-text"
                                                                           value="{{$min_trade}}">
                                                                    <span class="opacity-down"
                                                                          id="first_curr_input_market">{{$first_currency}}</span>
                                                                </div>
                                                                <div class="error_gap" style="margin-bottom: 10px;">
                                                                    <label for="buy_amount_market" generated="true"
                                                                           style="display: none;" class="error"></label>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <p class="opacity-down fs">Note - Total includes 0.1%
                                                                    Fees.</p>
                                                                <button class="btn btn-success btn-block"
                                                                        onclick="submitFormMarket('market_buy_order',event)">
                                                                    Buy
                                                                </button>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-md-6 form-small">
                                                <form id="market_sell_order">
                                                    {{csrf_field()}}
                                                    <div class=" col-md-12 heading flex-bit">
                                                        <input type="hidden" id="tradetype" name="tradetype"
                                                               value="market_order">
                                                        <input type="hidden" id="type" name="type" value="Sell">
                                                        <input type="hidden" id="pair-sell_market"
                                                               name="pair-sell_market" value="{{$pair}}">
                                                        <input type="hidden" id="sell_rate_market"
                                                               name="sell_rate_market"
                                                               value="{{number_format($sell_rate,'3','.','')}}">
                                                        <h3 class="red">Sell <span
                                                                    id="first_curr_sell_market">{{$first_currency}}</span>
                                                        </h3>
                                                        <span class="amount"><span
                                                                    id="first_curr_bal_market">{{number_format($first_cur_balance,4,'.','')}}</span> <span
                                                                    id="first_curr_market">{{$first_currency}}</span></span>
                                                    </div>
                                                    <div class="row">

                                                        <div class="form-group">
                                                            <label class="control-label col-xs-3"
                                                                   for="email">Price</label>
                                                            <div class="col-xs-9">
                                                                <div class="flex-bit input-desc">
                                                                    <span>Market Price</span>
                                                                    <span class="opacity-down"
                                                                          id="second_curr_input_1_market">{{$second_currency}}</span>
                                                                </div>
                                                                <div class="error_gap"
                                                                     style="margin-bottom: 10px;"></div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-xs-3"
                                                                   for="email">Amount</label>
                                                            <div class="col-xs-9">
                                                                <div class="flex-bit input-desc">
                                                                    <input id="sell_amount_market"
                                                                           name="sell_amount_market"
                                                                           onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                                           onkeyup="calculate_total('sell')"
                                                                           class="custom-input-text"
                                                                           value="{{$min_trade}}">
                                                                    <span class="opacity-down"
                                                                          id="first_curr_input_1_market">{{$first_currency}}</span>
                                                                </div>
                                                                <div class="error_gap" style="margin-bottom: 10px;">
                                                                    <label for="sell_amount_market" generated="true"
                                                                           style="display: none;" class="error"></label>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>

                                                        <div class="form-group">

                                                            <div class="col-xs-12">
                                                                <p class="opacity-down fs">Note - Total includes 0.1%
                                                                    Fees.</p>
                                                                <button class="btn btn-danger btn-block"
                                                                        onclick="submitFormMarket('market_sell_order',event)">
                                                                    Sell
                                                                </button>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>


                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-3 padding-left-0">
                        <div class="block-radius-1 mb-15 shadow1 min-height-435">

                            <div class="panel panel-default order-book">
                                <div class="panel-heading">Trade History</div>
                                <div class="panel-body">
                                    <div class="table-responsive order-book-table">
                                        <table id="trade_table" class="table no-space">
                                            <thead>
                                            <tr>
                                                <th>Time</th>
                                                {{--<th>Type</th>--}}
                                                <th>Volume</th>
                                                <th>Price</th>
                                                <th></th>
                                                {{--<th>Total</th>--}}
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(isset($trade_history[0]))
                                                @foreach($trade_history as $tradehis)
                                                    <tr>
                                                        <td>{{$tradehis->updated_at}}</td>
                                                        {{--@if($tradehis->type=='Buy')--}}
                                                        {{--<td><span class="green">{{$tradehis->type}}</span></td>--}}
                                                        {{--@else--}}
                                                        {{--<td><span class="red">{{$tradehis->type}}</span></td>--}}
                                                        {{--@endif--}}
                                                        <td>{{number_format($tradehis->triggered_qty,3,'.','')}}</td>
                                                        @if($tradehis->type=='Buy')
                                                            <td>
                                                                <span class="green">{{number_format($tradehis->triggered_price,3,'.','')}}</span>
                                                            </td>
                                                        @else
                                                            <td>
                                                                <span class="red">{{number_format($tradehis->triggered_price,3,'.','')}}</span>
                                                            </td>
                                                        @endif
                                                        <td></td>
                                                        {{--<td>{{number_format($tradehis->total,8,'.','')}}</td>--}}
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{--<ul class="nav nav-tabs">--}}
                            {{--<li class="active"><a data-toggle="tab" href="#trade-history-1">Trade History</a></li>--}}
                            {{--<li><a data-toggle="tab" href="#market-history">Market History</a></li>--}}
                            {{--</ul>--}}

                            {{--<div class="tab-content">--}}
                            {{--<div id="trade-history-1" class="tab-pane fade in active">--}}

                            {{--<div class="panel panel-default">--}}
                            {{--<div class="panel-body">--}}

                            {{--<div class="table-responsive">--}}

                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}

                            {{--</div>--}}
                            {{--<div id="market-history" class="tab-pane fade">--}}
                            {{--<div class="panel panel-default">--}}
                            {{--<div class="panel-body">--}}

                            {{--<div class="table-responsive">--}}
                            {{--<table class="table no-space">--}}
                            {{--<thead>--}}
                            {{--<tr>--}}
                            {{--<th>Time</th>--}}
                            {{--<th>Action</th>--}}
                            {{--<th>Price </th>--}}
                            {{--<th>DASH </th>--}}
                            {{--</tr>--}}
                            {{--</thead>--}}
                            {{--<tbody>--}}
                            {{--<tr>--}}
                            {{--<td>09:45:32 </td>--}}
                            {{--<td><span class="green"><a href="" class="green">Buy</a></span></td>--}}
                            {{--<td><span class="green">0.05059954</span></td>--}}
                            {{--<td><span class="green">11.6047120</span></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                            {{--<td>09:45:32 </td>--}}
                            {{--<td><span class="green"><a href="" class="green">Buy</a></span></td>--}}
                            {{--<td><span class="green">0.05059954</span></td>--}}
                            {{--<td><span class="green">11.6047120</span></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                            {{--<td>09:45:32 </td>--}}
                            {{--<td><span class="green"><a href="" class="green">Buy</a></span></td>--}}
                            {{--<td><span class="green">0.05059954</span></td>--}}
                            {{--<td><span class="green">11.6047120</span></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                            {{--<td>09:45:32 </td>--}}
                            {{--<td><span class="red"><a href="" class="red">Sell</a></span></td>--}}
                            {{--<td><span class="red">0.05059954</span></td>--}}
                            {{--<td><span class="red">11.6047120</span></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                            {{--<td>09:45:32 </td>--}}
                            {{--<td><span class="red"><a href="" class="red">Sell</a></span></td>--}}
                            {{--<td><span class="red">0.05059954</span></td>--}}
                            {{--<td><span class="red">11.6047120</span></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                            {{--<td>09:45:32 </td>--}}
                            {{--<td><span class="green"><a href="" class="green">Buy</a></span></td>--}}
                            {{--<td><span class="green">0.05059954</span></td>--}}
                            {{--<td><span class="green">11.6047120</span></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                            {{--<td>09:45:32 </td>--}}
                            {{--<td><span class="green"><a href="" class="green">Buy</a></span></td>--}}
                            {{--<td><span class="green">0.05059954</span></td>--}}
                            {{--<td><span class="green">11.6047120</span></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                            {{--<td>09:45:32 </td>--}}
                            {{--<td><span class="green"><a href="" class="green">Buy</a></span></td>--}}
                            {{--<td><span class="green">0.05059954</span></td>--}}
                            {{--<td><span class="green">11.6047120</span></td>--}}
                            {{--</tr>--}}
                            {{--<!--                                                            <tr>--}}
                            {{--<td>09:45:32 </td>--}}
                            {{--<td><span class="green"><a href="" class="green">Buy</a></span></td>--}}
                            {{--<td><span class="green">0.05059954</span></td>--}}
                            {{--<td><span class="green">11.6047120</span></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                            {{--<td>09:45:32 </td>--}}
                            {{--<td><span class="green"><a href="" class="green">Buy</a></span></td>--}}
                            {{--<td><span class="green">0.05059954</span></td>--}}
                            {{--<td><span class="green">11.6047120</span></td>--}}
                            {{--</tr>-->--}}


                            {{--</tbody>--}}
                            {{--</table>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--</div>-->
    <div class="clearfix"></div><br>
    <div id="cancel_single" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content custom-modal-background text-center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" style="color: #000;">&times;</button>
                    <h4 class="modal-title">Cancel Trade</h4>
                </div>
                <input value="" id="cancelsingle" hidden>
                <div class="modal-body">
                    <p>Are you sure you want to cancel the trade?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel min-width-btn" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn yellow-btn min-width-btn" onclick="cancel_single()">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancel_multiple" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content custom-modal-background text-center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" style="color: #000;">&times;</button>
                    <h4 class="modal-title">Cancel Multiple Trades</h4>
                </div>
                <div class="modal-body">
                    Are you sure you want to cancel the selected trades?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel min-width-btn" data-dismiss="modal">No</button>
                    <button id="confirmed" class="btn yellow-btn min-width-btn" onclick="cancel_multiple()">Yes</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="buy_multiple" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content custom-modal-background text-center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" style="color: #000;">&times;</button>
                    <h4>Multiple Buy</h4>
                </div>
                <div class="modal-body">
                    @if(isset($user_id))
                        <p><strong>Please enter 5 Buy Price and Amount.</strong></p>
                        <form id="limit_buy_order-m">
                            {{csrf_field()}}
                            <div class="heading flex-bit">
                                <input type="hidden" id="tradetype-m" name="tradetype-m" value="limit_order">
                                <input type="hidden" id="type-m" name="type-m" value="Buy">
                                <input type="hidden" id="pair-buy-m" name="pair-buy-m" value="{{$pair}}">
                                <input type="hidden" id="buy_rate-m" name="buy_rate-m"
                                       value="{{number_format($buy_rate,'3','.','')}}">
                                <h3 class="green">Buy <span id="first_curr_buy-m">{{$first_currency}}</span></h3>
                                <span class="amount"><span
                                            id="second_curr_bal-m">{{number_format($second_cur_balance,4,'.','')}}</span> <span
                                            id="second_curr-m">{{$second_currency}}</span></span>
                            </div>
                            <div class="row">
                                <h4>1st</h4>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Price</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="buy_price_1" name="buy_price_1"
                                                   onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('buy')" class="custom-input-text"
                                                   value="{{sprintf('%.3f',$buy_rate)}}">
                                            <span class="opacity-down"
                                                  id="second_curr_input-m-1">{{$second_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="buy_price_1" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Amount</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="buy_amount_1" name="buy_amount_1"
                                                   onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('buy')" class="custom-input-text"
                                                   value="{{$min_trade}}">
                                            <span class="opacity-down"
                                                  id="first_curr_input-m-1">{{$first_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="buy_amount_1" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix">

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Total</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="buy_total_1" class="custom-input-text"
                                                   value="{{($min_trade*(sprintf('%.3f',$buy_rate)))+(($min_trade*(sprintf('%.3f',$buy_rate))))*0.005}}"
                                                   disabled>
                                            <span class="opacity-down">Total</span>
                                        </div>
                                        <div class="error_gap"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                            <div class="row">
                                <h4>2nd</h4>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Price</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="buy_price_2" name="buy_price_2"
                                                   onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('buy')" class="custom-input-text"
                                                   value="{{sprintf('%.3f',$buy_rate)}}">
                                            <span class="opacity-down"
                                                  id="second_curr_input-m-2">{{$second_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="buy_price_2" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Amount</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="buy_amount_2" name="buy_amount_2"
                                                   onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('buy')" class="custom-input-text"
                                                   value="{{$min_trade}}">
                                            <span class="opacity-down"
                                                  id="first_curr_input-m-2">{{$first_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="buy_amount_2" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix">

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Total</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="buy_total_2" class="custom-input-text"
                                                   value="{{($min_trade*(sprintf('%.3f',$buy_rate)))+(($min_trade*(sprintf('%.3f',$buy_rate))))*0.005}}"
                                                   disabled>
                                            <span class="opacity-down">Total</span>
                                        </div>
                                        <div class="error_gap"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                            <div class="row">
                                <h4>3rd</h4>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Price</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="buy_price_3" name="buy_price_3"
                                                   onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('buy')" class="custom-input-text"
                                                   value="{{sprintf('%.3f',$buy_rate)}}">
                                            <span class="opacity-down"
                                                  id="second_curr_input-m-3">{{$second_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="buy_price_3" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Amount</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="buy_amount_3" name="buy_amount_3"
                                                   onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('buy')" class="custom-input-text"
                                                   value="{{$min_trade}}">
                                            <span class="opacity-down"
                                                  id="first_curr_input-m-3">{{$first_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="buy_amount_3" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix">

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Total</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="buy_total_3" class="custom-input-text"
                                                   value="{{($min_trade*(sprintf('%.3f',$buy_rate)))+(($min_trade*(sprintf('%.3f',$buy_rate))))*0.005}}"
                                                   disabled>
                                            <span class="opacity-down">Total</span>
                                        </div>
                                        <div class="error_gap"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                            <div class="row">
                                <h4>4th</h4>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Price</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="buy_price_4" name="buy_price_4"
                                                   onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('buy')" class="custom-input-text"
                                                   value="{{sprintf('%.3f',$buy_rate)}}">
                                            <span class="opacity-down"
                                                  id="second_curr_input-m-4">{{$second_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="buy_price_4" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Amount</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="buy_amount_4" name="buy_amount_4"
                                                   onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('buy')" class="custom-input-text"
                                                   value="{{$min_trade}}">
                                            <span class="opacity-down"
                                                  id="first_curr_input-m-4">{{$first_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="buy_amount_4" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix">

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Total</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="buy_total_4" class="custom-input-text"
                                                   value="{{($min_trade*(sprintf('%.3f',$buy_rate)))+(($min_trade*(sprintf('%.3f',$buy_rate))))*0.005}}"
                                                   disabled>
                                            <span class="opacity-down">Total</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                            <div class="row">
                                <h4>5th</h4>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Price</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="buy_price_5" name="buy_price_5"
                                                   onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('buy')" class="custom-input-text"
                                                   value="{{sprintf('%.3f',$buy_rate)}}">
                                            <span class="opacity-down"
                                                  id="second_curr_input-m-5">{{$second_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="buy_price_5" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Amount</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="buy_amount_5" name="buy_amount_5"
                                                   onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('buy')" class="custom-input-text"
                                                   value="{{$min_trade}}">
                                            <span class="opacity-down"
                                                  id="first_curr_input-m-5">{{$first_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="buy_amount_5" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Total</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="buy_total_5" class="custom-input-text"
                                                   value="{{($min_trade*(sprintf('%.3f',$buy_rate)))+(($min_trade*(sprintf('%.3f',$buy_rate))))*0.005}}"
                                                   disabled>
                                            <span class="opacity-down">Total</span>
                                        </div>
                                        <div class="error_gap"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                        </form>
                    @endif
                </div>
                <div class="modal-footer">
                    <span>
                        <p class="opacity-down fs">Note - All the 5 totals include 0.1% Fees.</p>
                        <p class="opacity-down fs">Note - Clicking on submit will place 5 buy orders with the above entered 5 prices and amounts.</p><br>
                    </span>
                    <button type="button" class="btn btn-cancel min-width-btn" data-dismiss="modal">Cancel</button>
                    <button id="buy_confirmed" class="btn yellow-btn min-width-btn"
                            onclick="submit_multiple_buy('buy',event)">Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sell_multiple" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content custom-modal-background text-center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" style="color: #000;">&times;</button>
                    <h4>Multiple Sell</h4>
                </div>
                <div class="modal-body">
                    @if(isset($user_id))
                        <p><strong>Please enter 5 Sell Price and Amount.</strong></p>
                        <form id="limit_sell_order-m">
                            {{csrf_field()}}
                            <div class=" col-md-12 heading flex-bit">
                                <input type="hidden" id="tradetype-m" name="tradetype-m" value="limit_order">
                                <input type="hidden" id="type-m" name="type-m" value="Sell">
                                <input type="hidden" id="pair-sell-m" name="pair-sell-m" value="{{$pair}}">
                                <input type="hidden" id="sell_rate-m" name="sell_rate-m"
                                       value="{{number_format($sell_rate,'3','.','')}}">
                                <h3 class="red">Sell <span id="first_curr_sell-m">{{$first_currency}}</span></h3>
                                <span class="amount"><span
                                            id="first_curr_bal-m">{{number_format($first_cur_balance,4,'.','')}}</span> <span
                                            id="first_curr-m">{{$first_currency}}</span></span>
                            </div>
                            <div class="row">
                                <h4>1st</h4>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Price</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="sell_price_1" name="sell_price_1"
                                                   onkeypress='return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('sell')" class="custom-input-text"
                                                   value="{{sprintf('%.3f',$sell_rate)}}">
                                            <span class="opacity-down"
                                                  id="second_curr_input_1-m-1">{{$second_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="sell_price_1" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Amount</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="sell_amount_1" name="sell_amount_1"
                                                   onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('sell')" class="custom-input-text"
                                                   value="{{$min_trade}}">
                                            <span class="opacity-down"
                                                  id="first_curr_input_1-m-1">{{$first_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="sell_amount_1" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Total</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="sell_total_1" name="sell_total_1" class="custom-input-text"
                                                   value="{{($min_trade*(sprintf('%.3f',$sell_rate)))-(($min_trade*(sprintf('%.3f',$sell_rate))))*0.017}}"
                                                   disabled>
                                            <span class="opacity-down">Total</span>
                                        </div>
                                        <div class="error_gap"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                            <div class="row">
                                <h4>2nd</h4>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Price</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="sell_price_2" name="sell_price_2"
                                                   onkeypress='return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('sell')" class="custom-input-text"
                                                   value="{{sprintf('%.3f',$sell_rate)}}">
                                            <span class="opacity-down"
                                                  id="second_curr_input_1-m-2">{{$second_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="sell_price_2" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Amount</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="sell_amount_2" name="sell_amount_2"
                                                   onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('sell')" class="custom-input-text"
                                                   value="{{$min_trade}}">
                                            <span class="opacity-down"
                                                  id="first_curr_input_1-m-2">{{$first_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="sell_amount_2" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Total</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="sell_total_2" name="sell_total_2" class="custom-input-text"
                                                   value="{{($min_trade*(sprintf('%.3f',$sell_rate)))-(($min_trade*(sprintf('%.3f',$sell_rate))))*0.017}}"
                                                   disabled>
                                            <span class="opacity-down">Total</span>
                                        </div>
                                        <div class="error_gap"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                            <div class="row">
                                <h4>3rd</h4>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Price</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="sell_price_3" name="sell_price_3"
                                                   onkeypress='return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('sell')" class="custom-input-text"
                                                   value="{{sprintf('%.3f',$sell_rate)}}">
                                            <span class="opacity-down"
                                                  id="second_curr_input_1-m-3">{{$second_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="sell_price_3" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Amount</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="sell_amount_3" name="sell_amount_3"
                                                   onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('sell')" class="custom-input-text"
                                                   value="{{$min_trade}}">
                                            <span class="opacity-down"
                                                  id="first_curr_input_1-m-3">{{$first_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="sell_amount_3" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Total</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="sell_total_3" name="sell_total_3" class="custom-input-text"
                                                   value="{{($min_trade*(sprintf('%.3f',$sell_rate)))-(($min_trade*(sprintf('%.3f',$sell_rate))))*0.017}}"
                                                   disabled>
                                            <span class="opacity-down">Total</span>
                                        </div>
                                        <div class="error_gap"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                            <div class="row">
                                <h4>4th</h4>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Price</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="sell_price_4" name="sell_price_4"
                                                   onkeypress='return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('sell')" class="custom-input-text"
                                                   value="{{sprintf('%.3f',$sell_rate)}}">
                                            <span class="opacity-down"
                                                  id="second_curr_input_1-m-4">{{$second_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="sell_price_4" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Amount</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="sell_amount_4" name="sell_amount_4"
                                                   onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('sell')" class="custom-input-text"
                                                   value="{{$min_trade}}">
                                            <span class="opacity-down"
                                                  id="first_curr_input_1-m-4">{{$first_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="sell_amount_4" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Total</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="sell_total_4" name="sell_total_4" class="custom-input-text"
                                                   value="{{($min_trade*(sprintf('%.3f',$sell_rate)))-(($min_trade*(sprintf('%.3f',$sell_rate))))*0.017}}"
                                                   disabled>
                                            <span class="opacity-down">Total</span>
                                        </div>
                                        <div class="error_gap"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                            <div class="row">
                                <h4>5th</h4>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Price</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="sell_price_5" name="sell_price_5"
                                                   onkeypress='return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('sell')" class="custom-input-text"
                                                   value="{{sprintf('%.3f',$sell_rate)}}">
                                            <span class="opacity-down"
                                                  id="second_curr_input_1-m-5">{{$second_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="sell_price_5" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Amount</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="sell_amount_5" name="sell_amount_5"
                                                   onkeypress='return event.charCode==46 || event.charCode >= 48 && event.charCode <= 57 || event.key === "Backspace"'
                                                   onkeyup="calculate_total_m('sell')" class="custom-input-text"
                                                   value="{{$min_trade}}">
                                            <span class="opacity-down"
                                                  id="first_curr_input_1-m-5">{{$first_currency}}</span>
                                        </div>
                                        <div class="error_gap">
                                            <label for="sell_amount_5" generated="true" style="display: none;"
                                                   class="error"></label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-xs-3" for="email">Total</label>
                                    <div class="col-xs-9">
                                        <div class="flex-bit input-desc">
                                            <input id="sell_total_5" name="sell_total_5" class="custom-input-text"
                                                   value="{{($min_trade*(sprintf('%.3f',$sell_rate)))-(($min_trade*(sprintf('%.3f',$sell_rate))))*0.017}}"
                                                   disabled>
                                            <span class="opacity-down">Total</span>
                                        </div>
                                        <div class="error_gap"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
                <div class="modal-footer">
                    <span>
                        <p class="opacity-down fs">Note - All the 5 totals include 0.1% Fees.</p>
                        <p class="opacity-down fs">Note - Clicking on submit will place 5 sell orders with the above entered 5 prices and amounts.</p><br>
                    </span>
                    <button type="button" class="btn btn-cancel min-width-btn" data-dismiss="modal">Cancel</button>
                    <button id="sell_confirmed" class="btn yellow-btn min-width-btn"
                            onclick="submit_multiple_sell('sell',event)">Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="market-order-info" class="col-md-2 market-order-info" style="display:none">
        <div class="market-order-info-text">
            <span id="market_info_text"></span>
        </div>
    </div>

    <div id="confirm_modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content custom-modal-background text-center"
                 style="padding: 0px; margin: 0px; text-align: -webkit-center;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" style="color: #000;">&times;</button>
                    <h4 class="modal-title">Confirm Order</h4>
                </div>
                <div class="modal-body" style="padding-bottom:0px;">
                    <div class="table-responsive">
                        <table class="modal_table">
                            <tbody>
                            <tr>
                                <td class="text-left">Type :</td>
                                <td class="text-uppercase text-center"><span id="modal_ord_type"></span> Order</td>
                            </tr>
                            <tr>
                                <td class="text-left"><span id="modal_first_curr">{{$first_currency}} :</span></td>
                                <td id="modal_first_curr_amount" class="text-center"></td>
                            </tr>
                            {{--<tr>--}}
                            {{--<td class="text-right"><label class="control-label" id="modal_second_curr">{{$second_currency}}</label></td>--}}
                            {{--<td class="text-left" id="modal_second_curr_price"></td>--}}
                            {{--</tr>--}}
                            <tr>
                                <td class="text-left">Price ( <span id="modal_price">{{$second_currency}}</span> ) :
                                </td>
                                <td id="modal_price_amt" class="text-center"></td>
                            </tr>
                            <tr>
                                <td class="text-left">Fee ( <span id="modal_fee">{{$second_currency}}</span> ) :</td>
                                <td id="modal_fee_amt" class="text-center"></td>
                            </tr>
                            <tr>
                                <td class="text-left">Total ( <span id="modal_total">{{$second_currency}}</span> ) :
                                </td>
                                <td id="modal_total_amt" class="text-center"></td>
                            </tr>
                            <tr>
                                <td class="text-left">Est. price (USD $) :</td>
                                <td id="modal_est_usd_price" class="text-center"></td>
                            </tr>
                            <tr>
                                <td class="text-left">Date &amp; time :</td>
                                <td class="text-center">{{date('Y-m-d H:i:s')}}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Disclaimer : Please verify the details of the order before confirming.
                                    All orders are final once submitted and we will not be able to issue you a refund.
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer" style="padding-top: 8px;">
                    <button type="button" class="btn yellow-btn min-width-btn" style="padding: 5px;"
                            onclick="submitForm(event)">Confirm
                    </button>
                    <button type="button" class="btn btn-cancel min-width-btn" style="padding: 5px;"
                            data-dismiss="modal">Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('xscript')

    <script type="text/javascript">

        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-center",
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
    </script>


    <script type="text/javascript"
            src="{{URL::asset('charting_library')}}/charting_library/charting_library.min.js"></script>
    <script type="text/javascript" src="{{URL::asset('charting_library')}}/datafeeds/udf/dist/polyfills.js"></script>
    <script type="text/javascript" src="{{URL::asset('charting_library')}}/datafeeds/udf/dist/bundle.js"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        $("#progress-bar-buy").ionRangeSlider({
            hide_min_max: true,
            min: 0,
            max: 100,
            grid: true,
            postfix: "%"
        });
        $("#progress-bar-sell").ionRangeSlider({
            hide_min_max: true,
            min: 0,
            max: 100,
            grid: true,
            postfix: "%"
        });
    </script>

    <script>
        var Pair = '{{$pair}}';
        var First_currency = '{{$first_currency}}';
        var Second_currency = '{{$second_currency}}';
        var BuyFee = 0;
        var SellFee = 0;
        var market_sell = 0;
        var market_buy = 0;
        var USD;
    </script>

    <script type="text/javascript">
        function pair_change(pair) {
            Pair = pair;
            BuyFee = get_trade_fee('Buy', Pair);
            SellFee = get_trade_fee('Sell', Pair);
            var array = Pair.split("-");
            First_currency = array[0];
            Second_currency = array[1];
            $('#pair-buy').val(Pair);
            $('#pair-sell').val(Pair);
            $('#first_curr').html(First_currency);
            $('#second_curr').html(Second_currency);
            $('#first_curr_buy').html(First_currency);
            $('#first_curr_sell').html(First_currency);
            $('#first_curr_input').html(First_currency);
            $('#first_curr_input_1').html(First_currency);
            $('#second_curr_input').html(Second_currency);
            $('#second_curr_input_1').html(Second_currency);
            $('#fees_input_buy').html(Second_currency);
            $('#fees_input_sell').html(Second_currency);
            $('#total_input_buy').html(Second_currency);
            $('#total_input_sell').html(Second_currency);
            $('#pair-buy_market').val(Pair);
            $('#pair-sell_market').val(Pair);
            $('#first_curr_market').html(First_currency);
            $('#second_curr_market').html(Second_currency);
            $('#first_curr_buy_market').html(First_currency);
            $('#first_curr_sell_market').html(First_currency);
            $('#first_curr_input_market').html(First_currency);
            $('#first_curr_input_1_market').html(First_currency);
            $('#second_curr_input_market').html(Second_currency);
            $('#second_curr_input_1_market').html(Second_currency);
            $('#pair-buy-m').val(Pair);
            $('#pair-sell-m').val(Pair);
            $('#first_curr-m').html(First_currency);
            $('#second_curr-m').html(Second_currency);
            $('#first_curr_buy-m').html(First_currency);
            $('#first_curr_sell-m').html(First_currency);
            $('#first_curr_input-m-1').html(First_currency);
            $('#first_curr_input-m-2').html(First_currency);
            $('#first_curr_input-m-3').html(First_currency);
            $('#first_curr_input-m-4').html(First_currency);
            $('#first_curr_input-m-5').html(First_currency);
            $('#first_curr_input_1-m-1').html(First_currency);
            $('#first_curr_input_1-m-2').html(First_currency);
            $('#first_curr_input_1-m-3').html(First_currency);
            $('#first_curr_input_1-m-4').html(First_currency);
            $('#first_curr_input_1-m-5').html(First_currency);
            $('#second_curr_input-m-1').html(Second_currency);
            $('#second_curr_input-m-2').html(Second_currency);
            $('#second_curr_input-m-3').html(Second_currency);
            $('#second_curr_input-m-4').html(Second_currency);
            $('#second_curr_input-m-5').html(Second_currency);
            $('#second_curr_input_1-m-1').html(Second_currency);
            $('#second_curr_input_1-m-2').html(Second_currency);
            $('#second_curr_input_1-m-3').html(Second_currency);
            $('#second_curr_input_1-m-4').html(Second_currency);
            $('#second_curr_input_1-m-5').html(Second_currency);
            $('#modal_first_curr').html(First_currency);
            $('#modal_price').html(Second_currency);
            $('#modal_fee').html(Second_currency);
            $('#modal_total').html(Second_currency);
            tradingview();
            set_usd(Pair);
            buy_order_list();
            sell_order_list();
            history_table();
            if ('{{Session::get('alphauserid')}}' != '') {
                open_orders(parseInt('{{$user_id}}'), Pair);
                trade_history(parseInt('{{$user_id}}'), Pair);
            }
            updatebalance();
            updateprice();
            updaterate();
            update_usdprice();
            update_min_trade();
            var buy_form = $('#limit_buy_order').validate();
            buy_form.resetForm();
            var sell_form = $('#limit_sell_order').validate();
            sell_form.resetForm();
            var buy_form_m = $('#limit_buy_order-m').validate();
            buy_form_m.resetForm();
            var sell_form_m = $('#limit_sell_order-m').validate();
            sell_form_m.resetForm();
            var market_buy_form = $('#market_buy_order').validate();
            market_buy_form.resetForm();
            var market_sell_form = $('#market_sell_order').validate();
            market_sell_form.resetForm();
        }
    </script>

    <script type="text/javascript">
        function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
        }

        function tradingview() {
            var widget = window.tvWidget = new TradingView.widget({
                // debug: true, // uncomment this line to see Library errors and warnings in the console
                fullscreen: false,
                autosize: true,
                hide_side_toolbar: true,
                hide_top_toolbar: true,
                symbol: Pair,
                allow_symbol_change: true,
                interval: 'D',
                timezone: "Asia/Singapore",
                container_id: "high_chartdiv",
                //	BEWARE: no trailing slash is expected in feed URL
                datafeed: new Datafeeds.UDFCompatibleDatafeed("/charts/" + Pair),
                library_path: "/charting_library/charting_library/",
                locale: getParameterByName('lang') || "en",
                //	Regression Trend-related functionality is not implemented yet, so it's hidden for a while
                drawings_access: {type: 'black', tools: [{name: "Regression Trend"}]},
                disabled_features: ["use_localstorage_for_settings", "left_toolbar", "top_toolbar"],
                // enabled_features: ["study_templates"],
                // charts_storage_url: 'http://saveload.tradingview.com',
                // charts_storage_api_version: "1.1",
                client_id: 'exblock.co',
                user_id: 'exblock'
            });
        }
    </script>

    <script src="https://js.pusher.com/4.2/pusher.min.js"></script>

    <style>
        td.highlight {
            color: #909699;
        }

        @-webkit-keyframes invalid {
            from {
                background-color: red;
            }
            to {
                background-color: inherit;
            }
        }

        @-moz-keyframes invalid {
            from {
                background-color: red;
            }
            to {
                background-color: inherit;
            }
        }

        @-o-keyframes invalid {
            from {
                background-color: red;
            }
            to {
                background-color: inherit;
            }
        }

        @keyframes invalid {
            from {
                background-color: red;
            }
            to {
                background-color: inherit;
            }
        }

        .invalid {
            -webkit-animation: invalid 5s; /* Safari 4+ */
            -moz-animation: invalid 5s; /* Fx 5+ */
            -o-animation: invalid 5s; /* Opera 12+ */
            animation: invalid 5s; /* IE 10+ */
        }

        td {
            padding: 1em;
        }

        td.buyhistory {
            color: green;
        }

        td.sellhistory {
            color: red;
        }
    </style>

    <script type="text/javascript">
        jQuery.validator.addMethod("deviation_buy", function (value, element, params) {
                var buy_rate = parseFloat($('#buy_rate').val());
                var upper_limit = parseFloat(buy_rate * 1.3).toFixed(3);
                var lower_limit = parseFloat(buy_rate * 0.7).toFixed(3);
                if (lower_limit <= value && value <= upper_limit) {
                    return true;
                }
                else {
                    return false;
                }
            },
            function () {
                return "Maximum deviation is 30% +/- from the buy rate.";
            });
        jQuery.validator.addMethod("deviation_sell", function (value, element, params) {
                var sell_rate = parseFloat($('#sell_rate').val());
                var upper_limit = parseFloat(sell_rate * 1.3).toFixed(3);
                var lower_limit = parseFloat(sell_rate * 0.7).toFixed(3);
                if (lower_limit <= value && value <= upper_limit) {
                    return true;
                }
                else {
                    return false;
                }
            },
            function () {
                return "Maximum deviation is 30% +/- from the sell rate.";
            });

        jQuery.validator.addMethod("maximum_buy", function (value, element, params) {
                if (market_buy >= value) {
                    return true;
                }
                else {
                    return false;
                }
            },
            function () {
                if (market_buy > 0)
                    return "You can place a market buy order of maximum " + market_buy + " " + First_currency;
                else
                    return "No sell orders available to fulfill your market order.";
            });

        jQuery.validator.addMethod("maximum_sell", function (value, element, params) {
                if (market_sell >= value) {
                    return true;
                }
                else {
                    return false;
                }
            },
            function () {
                if (market_sell > 0)
                    return "You can place a market sell order of maximum " + market_sell + " " + First_currency;
                else
                    return "No buy orders available to fulfill your market order.";
            });

        jQuery.validator.addMethod("min_trade", function (value, element, params) {
                var min = 0;
                $.ajax({
                    async: false,
                    url: '/ajax/min_trade',
                    method: 'get',
                    data: {'currency': First_currency},
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
                return "Minimum " + amount + " " + First_currency;
            });

        $('#limit_buy_order').validate({
            rules:
                {
                    buy_amount: {required: true, min_trade: true, number: true},
                    buy_price: {required: true, number: true},
                },
            messages:
                {
                    buy_amount: {
                        required: 'Amount is required',
                        number: 'Enter valid price format',
                    },
                    buy_price: {
                        required: 'Price required',
                        number: 'Enter valid price format',
                        min: 'Minimum price 0.0000500'
                    },
                }
        });

        $('#market_buy_order').validate({
            rules:
                {
                    buy_amount_market: {required: true, min_trade: true, number: true, maximum_buy: true},
                },
            messages:
                {
                    buy_amount_market: {
                        required: 'Amount is required',
                        number: 'Enter valid price format',
                    },
                }
        });

        $('#limit_buy_order-m').validate({
            rules:
                {
                    buy_amount_1: {required: true, min_trade: true, number: true},
                    buy_price_1: {required: true, number: true,},
                    buy_amount_2: {required: true, min_trade: true, number: true},
                    buy_price_2: {required: true, number: true,},
                    buy_amount_3: {required: true, min_trade: true, number: true},
                    buy_price_3: {required: true, number: true,},
                    buy_amount_4: {required: true, min_trade: true, number: true},
                    buy_price_4: {required: true, number: true,},
                    buy_amount_5: {required: true, min_trade: true, number: true},
                    buy_price_5: {required: true, number: true,},
                },
            messages:
                {
                    buy_amount_1: {
                        required: 'Amount is required',
                        number: 'Enter valid price format',
                    },
                    buy_price_1: {
                        required: 'Price required',
                        number: 'Enter valid price format',
                        min: 'Minimum price 0.0000500'
                    },
                    buy_amount_2: {
                        required: 'Amount is required',
                        number: 'Enter valid price format',
                    },
                    buy_price_2: {
                        required: 'Price required',
                        number: 'Enter valid price format',
                        min: 'Minimum price 0.0000500'
                    },
                    buy_amount_3: {
                        required: 'Amount is required',
                        number: 'Enter valid price format',
                    },
                    buy_price_3: {
                        required: 'Price required',
                        number: 'Enter valid price format',
                        min: 'Minimum price 0.0000500'
                    },
                    buy_amount_4: {
                        required: 'Amount is required',
                        number: 'Enter valid price format',
                    },
                    buy_price_4: {
                        required: 'Price required',
                        number: 'Enter valid price format',
                        min: 'Minimum price 0.0000500'
                    },
                    buy_amount_5: {
                        required: 'Amount is required',
                        number: 'Enter valid price format',
                    },
                    buy_price_5: {
                        required: 'Price required',
                        number: 'Enter valid price format',
                        min: 'Minimum price 0.0000500'
                    },
                }
        });

        $('#limit_sell_order').validate({
            rules:
                {
                    sell_amount: {required: true, min_trade: true, number: true},
                    sell_price: {required: true, number: true,},
                },
            messages:
                {
                    sell_amount: {
                        required: 'Amount is required',
                        number: 'Enter valid price format',
                    },
                    sell_price: {
                        required: 'Price required',
                        number: 'Enter valid price format',
                        min: 'Minimum price 0.0000500'
                    },
                }
        });

        $('#market_sell_order').validate({
            rules:
                {
                    sell_amount_market: {required: true, min_trade: true, number: true, maximum_sell: true,},
                },
            messages:
                {
                    sell_amount_market: {
                        required: 'Amount is required',
                        number: 'Enter valid price format',
                    },
                }
        });

        $('#limit_sell_order-m').validate({
            rules:
                {
                    sell_amount_1: {required: true, min_trade: true, number: true},
                    sell_price_1: {required: true, number: true,},
                    sell_amount_2: {required: true, min_trade: true, number: true},
                    sell_price_2: {required: true, number: true,},
                    sell_amount_3: {required: true, min_trade: true, number: true},
                    sell_price_3: {required: true, number: true,},
                    sell_amount_4: {required: true, min_trade: true, number: true},
                    sell_price_4: {required: true, number: true,},
                    sell_amount_5: {required: true, min_trade: true, number: true},
                    sell_price_5: {required: true, number: true,},
                },
            messages:
                {
                    sell_amount_1: {
                        required: 'Amount is required',
                        number: 'Enter valid price format',
                    },
                    sell_price_1: {
                        required: 'Price required',
                        number: 'Enter valid price format',
                        min: 'Minimum price 0.0000500'
                    },
                    sell_amount_2: {
                        required: 'Amount is required',
                        number: 'Enter valid price format',
                    },
                    sell_price_2: {
                        required: 'Price required',
                        number: 'Enter valid price format',
                        min: 'Minimum price 0.0000500'
                    },
                    sell_amount_3: {
                        required: 'Amount is required',
                        number: 'Enter valid price format',
                    },
                    sell_price_3: {
                        required: 'Price required',
                        number: 'Enter valid price format',
                        min: 'Minimum price 0.0000500'
                    },
                    sell_amount_4: {
                        required: 'Amount is required',
                        number: 'Enter valid price format',
                    },
                    sell_price_4: {
                        required: 'Price required',
                        number: 'Enter valid price format',
                        min: 'Minimum price 0.0000500'
                    },
                    sell_amount_5: {
                        required: 'Amount is required',
                        number: 'Enter valid price format',
                    },
                    sell_price_5: {
                        required: 'Price required',
                        number: 'Enter valid price format',
                        min: 'Minimum price 0.0000500'
                    },
                }
        });

        jQuery.validator.addMethod("minprice", function (value, element, params) {
                var min = 0;
                $.ajax({
                    async: false,
                    url: '/ticker/price_usd',
                    method: 'get',
                    success: function (data) {
                        // if (Pair == 'ETH-USDT') {
                            if (Pair == 'ABC-USD') {   
                            min = parseFloat(data);
                            value = parseFloat(value);
                        }
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
                return "Minimum " + amount;
            });

    </script>

    <script type="text/javascript">
        var timeout;

        function submitModal(type, event) {
            event.preventDefault();
            clearTimeout(timeout);
            if ($('#limit_' + type + '_order').valid()) {
                $('#modal_ord_type').text(type);
                $('#modal_first_curr_amount').text($('#' + type + '_amount').val());
                $('#modal_price_amt').text($('#' + type + '_price').val());
                $('#modal_fee_amt').text($('#' + type + '_fees').val());
                $('#modal_total_amt').text($('#' + type + '_total').val());
                $('#modal_est_usd_price').text($('#' + type + '_usd').text());
                $('#confirm_modal').modal('show');
                timeout = setTimeout(function () {
                    $('#confirm_modal').modal('hide');
                }, 10000);
            }
        }

        function submitForm(event) {
            event.preventDefault();
            $('#confirm_modal').modal('hide');
            clearTimeout(timeout);
            var id = 'limit_' + $('#modal_ord_type').text().toLowerCase() + '_order';
            if ($('#' + id).valid()) {
                $.ajax({
                    type: 'POST',
                    url: '{{url('/test_trade')}}',
                    data: $('#' + id).serialize(),
                    success: function (response) {
                        update_usdprice();
                        updatebalance();
                        updateprice();
                        var data = JSON.parse(response);
                        if (data.status == '1') {
                            toastr.success(data.message);
                            trade_history(data.id, Pair);
                            var buy_form = $('#limit_buy_order').validate();
                            buy_form.resetForm();
                            var sell_form = $('#limit_sell_order').validate();
                            sell_form.resetForm();
                            var buy_form_m = $('#limit_buy_order-m').validate();
                            buy_form_m.resetForm();
                            var sell_form_m = $('#limit_sell_order-m').validate();
                            sell_form_m.resetForm();
                        }
                        else if (data.status == '2') {
                            toastr.info(data.message);
                        }
                        else if (data.status == '3') {
                            toastr.info(data.message);
                        }
                        else if (data.status == '4') {
                            toastr.error(data.message);
                        }
                        else {
                            toastr.error(data.message);
                        }
                    }
                });
            }
            else {
                return false;
            }
        }

        function submitFormMarket(id, event) {
            event.preventDefault();
            if ($('#' + id).valid()) {
                $.ajax({
                    type: 'POST',
                    url: '{{url('/market_order')}}',
                    data: $('#' + id).serialize(),
                    success: function (response) {
                        updatebalance();
                        updateprice();
                        var data = JSON.parse(response);
                        if (data.status === '1') {
                            toastr.success(data.message);
                            trade_history(data.id, Pair);
                            var market_buy_form = $('#market_buy_order').validate();
                            market_buy_form.resetForm();
                            var market_sell_form = $('#market_sell_order').validate();
                            market_sell_form.resetForm();
                        }
                        else if (data.status === '2') {
                            toastr.info(data.message);
                        }
                        else if (data.status === '3') {
                            toastr.info(data.message);
                        }
                        else if (data.status === '4') {
                            toastr.error(data.message);
                        }
                        else {
                            toastr.error(data.message);
                        }
                    }
                });
            }
            else {
                return false;
            }
        }
    </script>

    <script type="text/javascript">
        var table;
        var total;
        var sell_table;
        var trade_history_table;
        //for pusher and datatable intialization
        table = $('#limit_buy_table').DataTable({
            "searching": false,
            "paging": false,
            "ordering": false,
            "info": false,
            "bAutoWidth": false,
            rowCallback: function (row, data, index) {
                $(row).addClass("green");
            }
        });
        sell_table = $('#limit_sell_table').DataTable(
            {
                "searching": false,
                "paging": false,
                "ordering": false,
                "info": false,
                "bAutoWidth": false,
                rowCallback: function (row, data, index) {
                    $(row).addClass("red");
                }
            });
        //trade history
        trade_history_table = $('#trade_table').DataTable({
            "searching": false,
            "paging": false,
            "ordering": false,
            "info": false,
            "bAutoWidth": false,
            "columnDefs": [
                {
                    "targets": [3],
                    "visible": false,
                    "searchable": false
                }],
            rowCallback: function (row, data, index) {
                if (data[3] == 'Buy') {
                    $(row).css('color', '#76C9B1');
                }
                else {
                    $(row).css('color', '#F56F70');
                }
            },
        });
        $(document).ready(function () {
            BuyFee = get_trade_fee('Buy', Pair);
            SellFee = get_trade_fee('Sell', Pair);
            set_usd(Pair);
            update_usdprice();
            tradingview();
            buy_order_list();
            sell_order_list();
            history_table();
            if ('{{Session::get('alphauserid')}}' != '') {
                open_orders(parseInt('{{$user_id}}'), Pair);
                trade_history(parseInt('{{$user_id}}'), Pair);
            }
            $("#limit_buy_table tbody").on("click", "tr", function (event) {
                var price = $(this).closest('tr').find('td:eq(0)').text();
                var amount = $(this).closest('tr').find('td:eq(1)').text();
                price = parseFloat(price).toFixed(3);
                amount = parseFloat(amount).toFixed(3);
                if (price > 0 && amount > 0) {
                    sell_click_value(price, amount);
                }
            });
            $("#limit_sell_table tbody").on("click", "tr", function (event) {
                var price = $(this).closest('tr').find('td:eq(0)').text();
                var amount = $(this).closest('tr').find('td:eq(1)').text();
                price = parseFloat(price).toFixed(3);
                amount = parseFloat(amount).toFixed(3);
                if (price > 0 && amount > 0) {
                    buy_click_value(price, amount);
                }
            });
            var id = '';
            $('#currencyTable tr').hover(function () {
                id = this.id;
                $(".flex-bit").each(function () {
                    if ((this.id) != id) {
                        $(".flex-bit" + '#' + this.id).css('display', 'none');
                    }
                    else {
                        $(".flex-bit" + '#' + this.id).css('display', '');
                    }
                });
            }, function () {
                $(".flex-bit" + '#' + id).css('display', 'none');
                $(".flex-bit" + '#' + Pair).css('display', '');
            });
            var pusher = new Pusher('{{env('PUSHER_APP_KEY')}}',
                {
                    cluster: 'ap1'
                });
            var channel = pusher.subscribe('trade');
            channel.bind('trade-event', function (data) {
                total = data.Total;
                amount = data.Amount;
                price = data.Price;
                var type = data.Type;
                pair = data.Pair;
                user_id = data.User_id;
                var usd = parseFloat(USD * price).toFixed(3);
                var update = 0;
                if (pair === Pair) {
                    if (type === 'Buy') {
                        var filteredData = table
                            .column(0)
                            .data()
                            .filter(function (value, index) {
                                if (value === price && update === 0) {
                                    var data = table.row(index).data();
                                    data[1] = parseFloat(data[1]) + parseFloat(amount);
                                    data[2] = parseFloat(data[2]) + parseFloat(total);
                                    var rownode3 = table.row(index).data(data).draw().node();
                                    update = 1;
                                    $(rownode3).css('color', '#76C9B1').animate({color: '#76C9B1'});
                                    setTimeout(function () {
                                        $(rownode3).css('color', '#76C9B1')
                                    }, 550);
                                }
                                else if (value < price && update === 0) {
                                    var rownode1 = [price, amount, total, usd];
                                    var currentrows = table.data().toArray();
                                    currentrows.splice(index, 0, rownode1);
                                    table.clear();
                                    table.rows.add(currentrows).draw();
                                    var rownode = table.row(index).node();
                                    $(rownode).css('color', '#76C9B1').animate({color: '#76C9B1'});
                                    setTimeout(function () {
                                        $(rownode).css('color', '#76C9B1')
                                    }, 550);
                                    update = 1;
                                }
                            });
                        if (update === 0) {
                            rownode = table.row.add([price, amount, total, usd]).draw().node();
                            $(rownode).css('color', '#76C9B1').animate({color: '#76C9B1'});
                            setTimeout(function () {
                                $(rownode).css('color', '#76C9B1')
                            }, 550);
                        }
                        update = 0;
                    }
                    else {
                        var filteredData = sell_table
                            .column(0)
                            .data()
                            .filter(function (value, index) {
                                if (value === price && update === 0) {
                                    var data = sell_table.row(index).data();
                                    data[1] = parseFloat(data[1]) + parseFloat(amount);
                                    data[2] = parseFloat(data[2]) + parseFloat(total);
                                    var rownode3 = sell_table.row(index).data(data).draw().node();
                                    update = 1;
                                    $(rownode3).css('color', '#F56F70').animate({color: '#F56F70'});
                                    setTimeout(function () {
                                        $(rownode3).css('color', '#F56F70')
                                    }, 550);
                                }
                                else if (value < price && update === 0) {
                                    var rownode1 = [price, amount, total, usd];
                                    var currentrows = sell_table.data().toArray();
                                    currentrows.splice(index, 0, rownode1);
                                    sell_table.clear();
                                    sell_table.rows.add(currentrows).draw();
                                    var rownode = sell_table.row(index).node();
                                    $(rownode).css('color', '#F56F70').animate({color: '#F56F70'});
                                    setTimeout(function () {
                                        $(rownode).css('color', '#F56F70')
                                    }, 550);
                                    update = 1;
                                }
                            });
                        if (update === 0) {
                            rownode = sell_table.row.add([price, amount, total, usd]).draw().node();
                            $(rownode).css('color', '#F56F70').animate({color: '#F56F70'});
                            setTimeout(function () {
                                $(rownode).css('color', '#F56F70')
                            }, 550);
                        }
                        update = 0;
                    }
                    open_orders(user_id, pair);
                    trade_history(user_id, pair);
                    update_usdprice();
                    updaterate();
                    updatebalance();
                    updateprice();
                    sell_order_list();
                    buy_order_list();
                }
            });
            //completed order history
            var completed_order = pusher.subscribe('order');
            completed_order.bind('completed-history', function (data) {
                    var pair = data.Pair;
                    if (pair === Pair) {
                        var type = data.Type;
                        var updated_table;
                        var update_color = '';
                        var executed_price = data.Price;
                        amount = data.Amount;
                        var fee = data.Fee;
                        var user_id = data.user_id;
                        var trade_id = data.trade_id;
                        var trader_status = data.trade_status;
                        var user_status = data.user_status;
                        var up_total;
                        if (type === 'Buy') {
                            updated_table = sell_table;
                            update_color = '#F56F70';
                        }
                        else {
                            updated_table = table;
                            update_color = '#76C9B1';
                        }
                        filterdata = updated_table.column(0)
                            .data()
                            .filter(function (value, index) {
                                if (value === executed_price) {
                                    var data = updated_table.row(index).data();
                                    if (parseFloat(data[1]) <= parseFloat(amount)) {
                                        var rownode3 = updated_table.row(index).node();
                                        if (type === 'Buy') {
                                            $(rownode3).css('color', update_color).animate({color: update_color});
                                        }
                                        else {
                                            $(rownode3).css('color', update_color).animate({color: update_color});
                                        }
                                        setTimeout(function () {
                                            if (type === 'Buy') {
                                                $(rownode3).css('color', update_color);
                                            }
                                            else {
                                                $(rownode3).css('color', update_color);
                                            }
                                            updated_table.row(index).remove().draw();
                                        }, 550);
                                    }
                                    else {
                                        var up_amt = parseFloat(data[1]) - parseFloat(amount);
                                        if (type === 'Buy') {
                                            up_total = (up_amt * parseFloat(executed_price)) - (up_amt * parseFloat(executed_price) * fee);
                                        }
                                        else {
                                            up_total = (up_amt * parseFloat(executed_price)) + (up_amt * parseFloat(executed_price) * fee);
                                        }
                                        data[1] = parseFloat(data[1]) - parseFloat(amount);
                                        data[2] = up_total.toFixed(4);
                                        var rownode3 = updated_table.row(index).data(data).draw().node();
                                        if (type === 'Buy') {
                                            $(rownode3).css('color', update_color).animate({color: update_color});
                                        }
                                        else {
                                            $(rownode3).css('color', update_color).animate({color: update_color});
                                        }
                                        setTimeout(function () {
                                            if (type === 'Buy') {
                                                $(rownode3).css('color', update_color);
                                            }
                                            else {
                                                $(rownode3).css('color', update_color);
                                            }
                                        }, 550);
                                    }
                                }
                            });
                        //for user open orders
                        open_orders(user_id, pair);
                        trade_history(user_id, pair);
                        update_usdprice();
                        updaterate();
                        updatebalance();
                        updateprice();
                        buy_order_list();
                        sell_order_list();
                        //for trader
                        open_orders(trade_id, pair);
                        trade_history(trade_id, pair);
                        if (parseInt('{{Session::get('alphauserid')}}') === trade_id) {
                            if (trader_status === 'partially') {
                                toastr.info('Your order is being partially executed');
                            }
                            else {
                                toastr.success('Your order is been completed');
                            }
                        }
                        if (parseInt('{{Session::get('alphauserid')}}') === user_id) {
                            if (user_status === 'partially') {
                                toastr.info('Your order is being partially executed');
                            }
                            else {
                                toastr.success('Your order is been completed');
                            }
                        }
                    }
                }
            );
            //pusher for cancelled order
            var cancel_order = pusher.subscribe('order');
            cancel_order.bind('order-cancelled', function (data) {
                var pair = data.Pair;
                if (pair === Pair) {
                    var type = data.Type;
                    var updated_table;
                    var executed_price = data.Price;
                    amount = data.Amount;
                    var fee = data.Fee;
                    var user_id = data.user_id;
                    var trade_id = data.trade_id;
                    var trader_status = data.trade_status;
                    var up_total;
                    if (type === 'Buy') {
                        updated_table = table;
                    }
                    else {
                        updated_table = sell_table;
                    }
                    filterdata = updated_table.column(0)
                        .data()
                        .filter(function (value, index) {
                            if (value === executed_price) {
                                var data = updated_table.row(index).data();
                                if (parseFloat(data[1]) <= parseFloat(amount)) {
                                    var rownode3 = updated_table.row(index).node();
                                    if (type === 'Buy') {
                                        $(rownode3).css('color', '#76C9B1').animate({color: '#76C9B1'});
                                    }
                                    else {
                                        $(rownode3).css('color', '#F56F70').animate({color: '#F56F70'});
                                    }
                                    setTimeout(function () {
                                        if (type === 'Buy') {
                                            $(rownode3).css('color', '#76C9B1');
                                        }
                                        else {
                                            $(rownode3).css('color', '#F56F70');
                                        }
                                        updated_table.row(index).remove().draw();
                                    }, 550);
                                }
                                else {
                                    var up_amt = parseFloat(data[1]) - parseFloat(amount);
                                    if (type === 'Buy') {
                                        up_total = (up_amt * executed_price) - (up_amt * executed_price * fee);
                                    }
                                    else {
                                        up_total = (up_amt * executed_price) + (up_amt * executed_price * fee);
                                    }
                                    data[1] = parseFloat(parseFloat(data[1]) - parseFloat(amount)).toFixed(0);
                                    data[2] = parseFloat(up_total).toFixed(4);
                                    var rownode3 = updated_table.row(index).data(data).draw().node();
                                    if (type === 'Buy') {
                                        $(rownode3).css('color', '#76C9B1').animate({color: '#76C9B1'});
                                    }
                                    else {
                                        $(rownode3).css('color', '#F56F70').animate({color: '#F56F70'});
                                    }
                                    setTimeout(function () {
                                        if (type === 'Buy') {
                                            $(rownode3).css('color', '#76C9B1');
                                        }
                                        else {
                                            $(rownode3).css('color', '#F56F70');
                                        }
                                    }, 550);
                                }
                            }
                        });
                }
                open_orders(parseInt('{{$user_id}}'), pair);
                update_usdprice();
                updaterate();
                updatebalance();
                updateprice();
                buy_order_list();
                sell_order_list();
            });
            //trade history pusher
            var trade = pusher.subscribe('trade1');
            trade.bind('history-trade', function (tradedata) {
                if (tradedata.Pair == Pair) {
                    var trade_type = tradedata.Type;
                    var trade_total = tradedata.Total;
                    var trade_price = tradedata.Price;
                    var trade_volume = tradedata.Amount;
                    var time = tradedata.Time;
                    var rownode1 = [time, trade_volume, trade_price, trade_type];
                    var currentrows = trade_history_table.data().toArray();
                    currentrows.splice(0, 0, rownode1);
                    trade_history_table.clear();
                    var rownode3 = trade_history_table.rows.add(currentrows).draw();
                }
            });
        });

        function buy_order_list() {
            var pair = Pair;
            var total;
            var price;
            var qty;
            var usd;
            $.ajax({
                url: '/ajax/buy_order_list',
                method: 'get',
                data: {'pair': pair},
                success: function (data) {
                    data = JSON.parse(data);
                    sell_table.clear();
                    $.each(data, function (index, value) {
                        total = parseFloat(data[index].total).toFixed(2);
                        price = parseFloat(data[index].price).toFixed(3);
                        qty = parseFloat(data[index].updated_qty).toFixed(3);
                        usd = parseFloat(USD * price).toFixed(3);
                        rownodel = [price, qty, total, usd];
                        sell_table.row.add(rownodel);
                    });
                    sell_table.draw();
                }
            });
        }

        function sell_order_list() {
            var pair = Pair;
            var total;
            var price;
            var qty;
            var usd;
            $.ajax({
                url: '/ajax/sell_order_list',
                method: 'get',
                data: {'pair': pair},
                success: function (data) {
                    data = JSON.parse(data);
                    table.clear();
                    $.each(data, function (index, value) {
                        total = parseFloat(data[index].total).toFixed(2);
                        price = parseFloat(data[index].price).toFixed(3);
                        qty = parseFloat(data[index].updated_qty).toFixed(3);
                        usd = parseFloat(USD * price).toFixed(3);
                        rownodel = [price, qty, total, usd];
                        table.row.add(rownodel);
                    });
                    table.draw();
                }
            });
        }

        function history_table() {
            var pair = Pair;
            var type;
            var total;
            var time;
            var price;
            $.ajax({
                url: '/ajax/trade_history_table',
                method: 'get',
                data: {'pair': pair},
                success: function (data) {
                    data = JSON.parse(data);
                    trade_history_table.clear();
                    $.each(data, function (index, value) {
                        volume = parseFloat(data[index].triggered_qty).toFixed(3);
                        time = data[index].updated_at;
                        type = data[index].type;
                        price = parseFloat(data[index].triggered_price).toFixed(3);
                        rownodel = [time, volume, price, type];
                        trade_history_table.row.add(rownodel);
                    });
                    trade_history_table.draw();
                }
            });
        }
    </script>

    <script type="text/javascript">
        var fee;

        function search() {
            var input = $('#search_input').val();
            input = input.toUpperCase();
            var table = document.getElementById("currencyTable");
            var tr = table.getElementsByTagName("tr");
            for (var i = 0; i < tr.length; i++) {
                td = tr[i].id;
                if (td) {
                    var td1 = td.split("-");
                    if (td1[0].indexOf(input) > -1 || td1[1].indexOf(input) > -1)
                        tr[i].style.display = "";
                    else
                        tr[i].style.display = "none";
                }
            }
        }

        function calculate_total(type) {
            var fee = 0;
            var amount = document.getElementById(type + '_amount').value;
            var price = document.getElementById(type + '_price').value;
            // price = parseFloat(price).toFixed(8);
            // document.getElementById(type+'_price').value = price;
            var total = 0;
            var totalfees = 0;
            // var percent = 0;
            // var max = 0;
            // var validator;
            // var currency;

            if (amount != '' && price != '') {
                if (type == 'buy') {
                    fee = parseFloat(BuyFee);
                    total = (amount * price) + (amount * price * fee);
                    totalfees = amount * price * fee;

                    // percent =  parseFloat((total/$('#second_curr_bal').text())*100).toFixed(0);
                    // max = parseFloat($('#second_curr_bal').text()).toFixed(8);
                    // currency = First_currency;
                    // validator  = $('#limit_buy_order').validate();
                    // if(parseFloat(total).toFixed(8) > max)
                    // {
                    //     max = parseFloat(max/price).toFixed(0);
                    //     errors = {buy_amount : "Max " + max + " " + currency};
                    //     validator.showErrors(errors);
                    // }
                }
                else {
                    fee = parseFloat(SellFee);
                    total = (amount * price) - (amount * price * fee);
                    totalfees = amount * price * fee;

                    // percent =  parseFloat((amount/$('#first_curr_bal').text())*100).toFixed(0);
                    // max = parseFloat($('#first_curr_bal').text()).toFixed(0);
                    // currency = First_currency;
                    // validator  = $('#limit_sell_order').validate();
                    // if(parseFloat(amount).toFixed(0) > max)
                    // {
                    //     errors = {sell_amount: "Max " + max + " " + currency};
                    //     validator.showErrors(errors);
                    // }
                }

                document.getElementById(type + '_total').value = total.toFixed(3);
                document.getElementById(type + '_fees').value = totalfees.toFixed(5);
            }
            else {
                document.getElementById(type + '_total').value = 0;
            }
        }

        function calculate_total_m(type) {
            var fee = 0;
            var amount1 = document.getElementById(type + '_amount_1').value;
            var price1 = document.getElementById(type + '_price_1').value;
            var amount2 = document.getElementById(type + '_amount_2').value;
            var price2 = document.getElementById(type + '_price_2').value;
            var amount3 = document.getElementById(type + '_amount_3').value;
            var price3 = document.getElementById(type + '_price_3').value;
            var amount4 = document.getElementById(type + '_amount_4').value;
            var price4 = document.getElementById(type + '_price_4').value;
            var amount5 = document.getElementById(type + '_amount_5').value;
            var price5 = document.getElementById(type + '_price_5').value;
            // price1 = parseFloat(price1).toFixed(8);
            // document.getElementById(type+'_price_1').value = price1;
            // price2 = parseFloat(price2).toFixed(8);
            // document.getElementById(type+'_price_2').value = price2;
            // price3 = parseFloat(price3).toFixed(8);
            // document.getElementById(type+'_price_3').value = price3;
            // price4 = parseFloat(price4).toFixed(8);
            // document.getElementById(type+'_price_4').value = price4;
            // price5 = parseFloat(price5).toFixed(8);
            // document.getElementById(type+'_price_5').value = price5;
            var total1 = 0;
            var total2 = 0;
            var total3 = 0;
            var total4 = 0;
            var total5 = 0;

            if (amount1 != '' && price1 != '') {
                if (type == 'buy') {
                    fee = parseFloat(BuyFee);
                    total1 = (amount1 * price1) + (amount1 * price1 * fee);
                }
                else {
                    fee = parseFloat(SellFee);
                    total1 = (amount1 * price1) - (amount1 * price1 * fee);
                }
                document.getElementById(type + '_total_1').value = total1.toFixed(4);
            }
            else {
                document.getElementById(type + '_total_1').value = 0;
            }

            if (amount2 != '' && price2 != '') {
                if (type == 'buy') {
                    fee = parseFloat(BuyFee);
                    total2 = (amount2 * price2) + (amount2 * price2 * fee);
                }
                else {
                    fee = parseFloat(SellFee);
                    total2 = (amount2 * price2) - (amount2 * price2 * fee);
                }
                document.getElementById(type + '_total_2').value = total2.toFixed(4);
            }
            else {
                document.getElementById(type + '_total_2').value = 0;
            }

            if (amount3 != '' && price3 != '') {
                if (type == 'buy') {
                    fee = parseFloat(BuyFee);
                    total3 = (amount3 * price3) + (amount3 * price3 * fee);
                }
                else {
                    fee = parseFloat(SellFee);
                    total3 = (amount3 * price3) - (amount3 * price3 * fee);
                }
                document.getElementById(type + '_total_3').value = total3.toFixed(4);
            }
            else {
                document.getElementById(type + '_total_3').value = 0;
            }

            if (amount4 != '' && price4 != '') {
                if (type == 'buy') {
                    fee = parseFloat(BuyFee);
                    total4 = (amount4 * price4) + (amount4 * price4 * fee);
                }
                else {
                    fee = parseFloat(SellFee);
                    total4 = (amount4 * price4) - (amount4 * price4 * fee);
                }
                document.getElementById(type + '_total_4').value = total4.toFixed(4);
            }
            else {
                document.getElementById(type + '_total_4').value = 0;
            }

            if (amount5 != '' && price5 != '') {
                if (type == 'buy') {
                    fee = parseFloat(BuyFee);
                    total5 = (amount5 * price5) + (amount5 * price5 * fee);
                }
                else {
                    fee = parseFloat(SellFee);
                    total5 = (amount5 * price5) - (amount5 * price5 * fee);
                }
                document.getElementById(type + '_total_5').value = total5.toFixed(4);
            }
            else {
                document.getElementById(type + '_total_5').value = 0;
            }
        }

        function get_trade_fee(type, pair) {
            var fee = 0;
            $.ajax({
                async: false,
                url: '/ajax/get_trading_fee',
                method: 'get',
                data: {'type': type, 'pair': pair},
                success: function (data) {
                    fee = parseFloat(JSON.parse(data));
                }
            });
            return fee;
        }

        function buy_click_value(price, amount) {
            if (price > 0 && amount > 0) {
                $('#buy_price').val(price);
                $('#buy_amount').val(amount);
                calculate_total('buy');
            }
        }

        function sell_click_value(price, amount) {
            if (price > 0 && amount > 0) {
                $('#sell_price').val(price);
                $('#sell_amount').val(amount);
                calculate_total('sell');
            }
        }

        function cancel_single() {
            var id = $('#cancelsingle').val();
            var result;
            $.ajax({
                url: "/ajax/cancel_order",
                method: 'post',
                data: {'id': id},
                success: function (data) {
                    result = JSON.parse(data);
                    $('#cancel_single').modal('hide');
                    if (result.status === '200') {
                        toastr.success(result.message);
                        open_orders(parseInt('{{$user_id}}'), Pair);
                        trade_history(parseInt('{{$user_id}}'), Pair);
                        updatebalance();
                        updateprice();
                    }
                    else {
                        toastr.error(result.message);
                        open_orders(parseInt('{{$user_id}}'), Pair);
                        trade_history(parseInt('{{$user_id}}'), Pair);
                    }
                }
            });
        }
    </script>

    <script type="text/javascript">
        function updateprice() {
            var pair;
            $.ajax({
                url: "/ajax/updateprice",
                method: 'get',
                data: {},
                success: function (data) {
                    $.each(data, function (index) {
                        pair = data[index].Pair;
                        var explode = pair.split("-");
                        var second_currency = explode[1];
                        $('#' + pair + '_volume').text(data[index].Volume + ' ' + data[index].first_currency).attr('class', data[index].Colour);
                        $('#' + pair + '_low').text(data[index].Low);
                        $('#' + pair + '_colour').attr('class', data[index].Colour);
                        $('#' + pair + '_change').text(data[index].Change);
                        $('#' + pair + '_percent').text(data[index].Percentage);
                        $('#' + pair + '_high').text(data[index].High);
                        $('#' + pair + '_last').text(data[index].Last);
                        $('#' + pair + '_td_change').text(parseFloat(data[index].Change).toFixed(3)).attr('class', data[index].Colour);
                        $('#' + pair + '_td_last').text(data[index].Last).attr('class', data[index].Colour);
                        {{--$.get("{{url('/')}}/ajax/get_estimatme_usdbalance?currency="+second_currency+"&price="+data[index].Last, function(response){--}}
                        {{--$('#'+pair+'_USD').text(parseFloat(response).toFixed(6));--}}
                        {{--console.log($('#'+pair+'_USD').text());--}}
                        {{--});--}}
                        update_usd(pair, data[index].Last);
                        if (pair == Pair)
                            $('#last_divide').text(data[index].Last + ' ' + data[index].first_currency);
                    });
                }
            });
        }

        function updaterate() {
            var pair = Pair;
            $.ajax({
                url: "/ajax/updaterate",
                method: "get",
                data: {'pair': pair},
                success: function (data) {
                    $('#buy_rate_market').val(parseFloat(data.buy_rate).toFixed(3));
                    $('#buy_rate').val(parseFloat(data.buy_rate).toFixed(3));
                    $('#sell_rate_market').val(parseFloat(data.sell_rate).toFixed(3));
                    $('#sell_rate').val(parseFloat(data.sell_rate).toFixed(3));
                    $('#buy_price').val(parseFloat(data.buy_rate).toFixed(3));
                    $('#sell_price').val(parseFloat(data.sell_rate).toFixed(3));
                    $('#buy_rate-m').val(parseFloat(data.buy_rate).toFixed(3));
                    $('#sell_rate-m').val(parseFloat(data.sell_rate).toFixed(3));
                    // $('#buy_price_1').val(parseFloat(data.buy_rate).toFixed(8));
                    // $('#buy_price_2').val(parseFloat(data.buy_rate).toFixed(8));
                    // $('#buy_price_3').val(parseFloat(data.buy_rate).toFixed(8));
                    // $('#buy_price_4').val(parseFloat(data.buy_rate).toFixed(8));
                    // $('#buy_price_5').val(parseFloat(data.buy_rate).toFixed(8));
                    // $('#sell_price_1').val(parseFloat(data.sell_rate).toFixed(8));
                    // $('#sell_price_2').val(parseFloat(data.sell_rate).toFixed(8));
                    // $('#sell_price_3').val(parseFloat(data.sell_rate).toFixed(8));
                    // $('#sell_price_4').val(parseFloat(data.sell_rate).toFixed(8));
                    // $('#sell_price_5').val(parseFloat(data.sell_rate).toFixed(8));
                    calculate_total('buy');
                    calculate_total('sell');
                    calculate_total_m('buy');
                    calculate_total_m('sell');
                }
            });
        }

        function update_min_trade() {
            $.ajax({
                url: '/ajax/min_trade',
                method: 'get',
                data: {'currency': First_currency},
                success: function (data) {
                    $('#buy_amount').val(parseFloat(JSON.parse(data)).toFixed(3));
                    $('#buy_amount_market').val(parseFloat(JSON.parse(data)).toFixed(3));
                    $('#buy_amount_1').val(parseFloat(JSON.parse(data)).toFixed(3));
                    $('#buy_amount_2').val(parseFloat(JSON.parse(data)).toFixed(3));
                    $('#buy_amount_3').val(parseFloat(JSON.parse(data)).toFixed(3));
                    $('#buy_amount_4').val(parseFloat(JSON.parse(data)).toFixed(3));
                    $('#buy_amount_5').val(parseFloat(JSON.parse(data)).toFixed(3));
                    $('#sell_amount').val(parseFloat(JSON.parse(data)).toFixed(3));
                    $('#sell_amount_market').val(parseFloat(JSON.parse(data)).toFixed(3));
                    $('#sell_amount_1').val(parseFloat(JSON.parse(data)).toFixed(3));
                    $('#sell_amount_2').val(parseFloat(JSON.parse(data)).toFixed(3));
                    $('#sell_amount_3').val(parseFloat(JSON.parse(data)).toFixed(3));
                    $('#sell_amount_4').val(parseFloat(JSON.parse(data)).toFixed(3));
                    $('#sell_amount_5').val(parseFloat(JSON.parse(data)).toFixed(3));
                }
            });
        }

        function updatebalance() {
            var userid = '{{$user_id}}';
            var first_curr = First_currency;
            var second_curr = Second_currency;
            userid = btoa(userid);
            $.ajax({
                url: "/ajax/updatebalance",
                method: 'get',
                data: {'userid': userid},
                success: function (data) {
                    $.each(data, function (index) {
                        $('#' + data[index].curr + '_bal').text(parseFloat(data[index].bal).toFixed(4));
                        if (data[index].curr == first_curr) {
                            $('#first_curr_bal').text(parseFloat(data[index].bal).toFixed(4));
                            $('#first_curr_bal_market').text(parseFloat(data[index].bal).toFixed(4));
                            $('#first_curr_bal-m').text(parseFloat(data[index].bal).toFixed(4));
                        }
                        else if (data[index].curr == second_curr) {
                            $('#second_curr_bal').text(parseFloat(data[index].bal).toFixed(4));
                            $('#second_curr_bal_market').text(parseFloat(data[index].bal).toFixed(4));
                            $('#second_curr_bal-m').text(parseFloat(data[index].bal).toFixed(4));
                        }
                    });
                }
            });
        }

        updateprice();
        updaterate();
        setInterval(function () {
            updateprice();
        }, 10000);
    </script>

    <script type="text/javascript">
        // var currency_table =  $('#currencytable').DataTable({
        //     "searching": false,
        //     "paging": false,
        //     "ordering": false,
        //     "info": false,
        //     // "columnDefs":[
        //     //     {
        //     //         "targets":[4],
        //     //         "visible": false,
        //     //         "searchable": false
        //     //     }],
        // });
        function addFav() {
            var user_id = {{$user_id}};
            var pair_id = $(this).data("pair");
            var key = $(this).data("id");
            $.ajax({
                url: "/ajax/favorites/add",
                method: 'get',
                data: {'user_id': user_id, 'pair_id': pair_id},
                success: function (data) {
                    obj = JSON.parse(data);
                    if (obj == '1') {
                        $('a#' + key)
                            .addClass('active')
                            .attr('title', '[-] Remove from favorites')
                            .unbind('click')
                            .bind('click', removeFav)
                        ;
                        // $('#'+pair_id).text('1');
                        //
                        //
                        // var row_node = currency_table.row(tr).node();
                        // currency_table.row(tr).remove().draw();
                        //
                        // var filteredData = currency_table
                        //     .column(5)
                        //     .data()
                        //     .filter( function ( value, index ) {
                        //         if(value != 1)
                        //         {
                        //             console.log(index);
                        //             var currentrows = table.data().toArray();
                        //             currentrows.splice(index,0,row_node);
                        //             table.clear();
                        //             table.rows.add(currentrows).draw();
                        //         }
                        //
                        //     } );
                    }
                }
            });
        }

        function removeFav() {
            var user_id = {{$user_id}};
            var pair_id = $(this).data("pair");
            var key = $(this).data("id");
            $.ajax({
                url: "/ajax/favorites/remove",
                method: 'get',
                data: {'user_id': user_id, 'pair_id': pair_id},
                success: function (data) {
                    obj = JSON.parse(data);
                    if (obj == '1') {
                        $('a#' + key)
                            .removeClass('active')
                            .attr('title', '[+] Add as favorite')
                            .unbind('click')
                            .bind('click', addFav)
                        ;
                        $('#' + pair_id).text('0');
                    }
                    // currency_table.order([4,'desc']).draw();
                }
            });
        }

        //this will make the link listen to function addFav (you might know this already)
        $('.fav').bind('click', addFav);
        $('.fav.active').bind('click', removeFav);
    </script>

    <script type="text/javascript">
        var open_order = $('#open_order_table').DataTable(
            {
                "searching": false,
                "paging": false,
                "ordering": false,
                "info": false,
                "bAutoWidth": false,
                "columnDefs": [
                    {
                        "targets": [6],
                        "visible": false,
                        "searchable": false
                    }],
                rowCallback: function (row, data, index) {
                    if (data[1] == 'Buy') {
                        $(row).find('td:eq(1)').css('color', '#76C9B1');
                    }
                    else {
                        $(row).find('td:eq(1)').css('color', '#F56F70');
                    }
                },
                fnCreatedRow: function (row, data, index) {
                    $('td:eq(5)', row).append('<button class="btn btn-danger btn-xs" style="padding:1px 5px; background-color: rgba(0,0,0,0); border-color: rgba(0,0,0,0);"><span style="color:black;">X</button> <input type="checkbox" name="checked[]" style="padding:1px 5px;" id=' + index + ' value=' + data[6] + ' onclick="check_selected()">')
                }
            });
        $('#open_order_table tbody').on('click', 'button', function () {
            var data = open_order.row($(this).parents('tr')).data();
            $('#cancel_single').modal('show');
            $('#cancelsingle').val(data[6]);
        });
        // trade_hist
        var time;
        var type;
        var volume;
        var price;
        var total;
        var rownodel;
        var id;
        var abc;

        function open_orders(userid, pair) {
            var user_id = '{{Session::get('alphauserid')}}';
            if (userid === parseInt(user_id)) {
                $.ajax({
                    url: "/ajax/openorders",
                    method: 'post',
                    data: {'user_id': user_id},
                    success: function (data) {
                        data = JSON.parse(data);
                        open_order.clear();
                        $.each(data, function (index, value) {
                            pair = data[index].pair;
                            type = data[index].type;
                            volume = parseFloat(data[index].updated_qty).toFixed(2);
                            price = parseFloat(data[index].price).toFixed(3);
                            total = parseFloat(data[index].total).toFixed(4);
                            abc = "";
                            id = btoa(data[index].id);
                            rownodel = [pair, type, volume, price, total, abc, id];
                            open_order.row.add(rownodel);
                        });
                        open_order.draw();
                    }
                });
            }
        }
    </script>

    <script type="text/javascript">
        var time;
        var type;
        var volume;
        var price;
        var total;
        var rownodel;
        var my_trade_order = $('#my_trade_order_table').DataTable(
            {
                "searching": false,
                "paging": false,
                "ordering": false,
                "info": false,
                "bAutoWidth": false,
                "columnDefs": [
                    {
                        "targets": [1],
                        "visible": false,
                        "searchable": false
                    }],
                rowCallback: function (row, data, index) {
                    if (data[1] == 'Buy') {
                        $(row).addClass("green");
                    }
                    else {
                        $(row).addClass("red");
                    }
                },
            });

        function trade_history(userid, pair) {
            var user_id = '{{Session::get('alphauserid')}}';
            if (userid === parseInt(user_id)) {
                $.ajax({
                    url: "/ajax/mytradehistory",
                    method: 'post',
                    data: {'user_id': user_id, 'pair': pair},
                    success: function (data) {
                        data = JSON.parse(data);
                        my_trade_order.clear();
                        $.each(data, function (index, value) {
                            time = data[index].created_at;
                            type = data[index].type;
                            volume = parseFloat(data[index].triggered_qty).toFixed(2);
                            price = parseFloat(data[index].triggered_price).toFixed(3);
                            total = parseFloat(data[index].total).toFixed(4);
                            rownodel = [time, type, volume, price, total];
                            my_trade_order.row.add(rownodel);
                        });
                        my_trade_order.draw();
                    }
                });
            }
        }
    </script>

    <script>
        $('#progress-bar-buy').on("input", function () {
            var percent = parseFloat($('#progress-bar-buy').val());
            var bal = $('#second_curr_bal').html();
            bal = parseFloat(bal);
            var total = bal * (percent / 100);
            var price = $('#buy_price').val();
            var amount = total * (1 - BuyFee);
            amount = parseFloat(amount / price).toFixed(0);
            $('#buy_amount').val(amount);
            calculate_total('buy');
        });
        $('#progress-bar-sell').on("input", function () {
            var percent = parseFloat($('#progress-bar-sell').val());
            var bal = $('#first_curr_bal').html();
            bal = parseFloat(bal);
            var total = parseFloat(bal * (percent / 100)).toFixed(0);
            $('#sell_amount').val(total);
            calculate_total('sell');
        });
    </script>

    <script type="text/javascript">
        function check_all() {
            var chk_arr = document.getElementsByName("checked[]");
            for (k = 0; k < chk_arr.length; k++) {
                chk_arr[k].checked = true;
            }
            check_selected();
        }

        function check_none() {
            var chk_arr = document.getElementsByName("checked[]");
            for (k = 0; k < chk_arr.length; k++) {
                chk_arr[k].checked = false;
            }
            check_selected();
        }

        function checkany() {
            var chk_arr = document.getElementsByName("checked[]");
            for (k = 0; k < chk_arr.length; k++) {
                if (chk_arr[k].checked == true) {
                    return true;
                }
            }
            return false;
        }

        function show() {
            document.getElementById('cancel_button').style.display = "block";
            document.getElementById('checkall').style.display = "block";
            document.getElementById('checknone').style.display = "block";
        }

        function hide() {
            document.getElementById('cancel_button').style.display = "none";
            document.getElementById('checkall').style.display = "none";
            document.getElementById('checknone').style.display = "none";
        }

        function check_selected() {
            checkany() ? show() : hide();
        }

        function cancel_multiple() {
            var chk_arr = document.getElementsByName("checked[]");
            var id = new Array();
            var i = 0;
            for (var k = 0; k < chk_arr.length; k++) {
                if (chk_arr[k].checked == true) {
                    id[i] = chk_arr[k].value;
                    i++;
                }
            }
            $.ajax({
                url: '/ajax/cancel_multiple',
                method: 'post',
                data: {'orders': id},
                success: function (data) {
                    result = JSON.parse(data);
                    $('#cancel_multiple').modal('hide');
                    if (result.status === '200') {
                        toastr.success(result.message);
                        open_orders(parseInt('{{$user_id}}'), Pair);
                        trade_history(parseInt('{{$user_id}}'), Pair);
                        update_usdprice();
                        updatebalance();
                        updateprice();
                    }
                    else {
                        toastr.error(result.message);
                        open_orders(parseInt('{{$user_id}}'), Pair);
                        trade_history(parseInt('{{$user_id}}'), Pair);
                    }
                }
            });
            hide();
        }
    </script>

    <script type="text/javascript">
        function sleep(milliseconds) {
            var start = new Date().getTime();
            for (var i = 0; i < 1e7; i++) {
                if ((new Date().getTime() - start) > milliseconds) {
                    break;
                }
            }
        }

        function multiple_modal(type) {
            var validator = $('#limit_' + type + '_order-m').validate();
            validator.resetForm();
            $('#' + type + '_multiple').modal('show');
        }

        function submit_multiple_buy(type, event) {
            event.preventDefault();
            if ($('#limit_' + type + '_order-m').valid()) {
                document.getElementById(type + "_confirmed").disabled = true;
                var i;
                $('#' + type + '_multiple').modal('hide');
                for (i = 1; i <= 5; i++) {
                    // $('#pair-' + type).val($('#pair-' + type + '-m').val());
                    // $('#' + type + '_amount').val($('#' + type + '_amount_' + i).val());
                    // $('#' + type + '_price').val($('#' + type + '_price_' + i).val());
                    // $('#' + type + '_rate').val($('#' + type + '_rate-m').val());
                    // calculate_total(type);
                    // submitForm('limit_' + type + '_order', event);
                    // sleep(1000);

                    $.ajax({
                        // async:false,
                        type: 'POST',
                        url: '{{url('/test_trade')}}',
                        data: {
                            'type': 'Buy',
                            'pair-buy': $('#pair-' + type + '-m').val(),
                            'buy_amount': $('#' + type + '_amount_' + i).val(),
                            'buy_price': $('#' + type + '_price_' + i).val(),
                            'buy_rate': $('#' + type + '_rate-m').val(),
                            'tradetype': 'limit_order'
                        },
                        success: function (response) {
                            updatebalance();
                            // updateprice();
                            var data = JSON.parse(response);
                            if (data.status === '1') {
                                toastr.success(data.message);
                                trade_history(data.id, Pair);
                                var buy_form = $('#limit_buy_order').validate();
                                buy_form.resetForm();
                                var sell_form = $('#limit_sell_order').validate();
                                sell_form.resetForm();
                                var buy_form_m = $('#limit_buy_order-m').validate();
                                buy_form_m.resetForm();
                                var sell_form_m = $('#limit_sell_order-m').validate();
                                sell_form_m.resetForm();
                            }
                            else if (data.status === '2') {
                                toastr.info(data.message);
                            }
                            else if (data.status === '3') {
                                toastr.info(data.message);
                            }
                            else if (data.status === '4') {
                                toastr.error(data.message);
                            }
                            else {
                                toastr.error(data.message);
                            }
                        }
                    });
                }
                updateprice();
                update_usdprice();
                document.getElementById(type + "_confirmed").disabled = false;
            }
        }

        function submit_multiple_sell(type, event) {
            event.preventDefault();
            if ($('#limit_' + type + '_order-m').valid()) {
                document.getElementById(type + "_confirmed").disabled = true;
                var i;
                $('#' + type + '_multiple').modal('hide');
                for (i = 1; i <= 5; i++) {
                    // $('#pair-' + type).val($('#pair-' + type + '-m').val());
                    // $('#' + type + '_amount').val($('#' + type + '_amount_' + i).val());
                    // $('#' + type + '_price').val($('#' + type + '_price_' + i).val());
                    // $('#' + type + '_rate').val($('#' + type + '_rate-m').val());
                    // calculate_total(type);
                    // submitForm('limit_' + type + '_order', event);
                    // sleep(1000);

                    $.ajax({
                        // async:false,
                        type: 'POST',
                        url: '{{url('/test_trade')}}',
                        data: {
                            'type': 'Sell',
                            'pair-sell': $('#pair-' + type + '-m').val(),
                            'sell_amount': $('#' + type + '_amount_' + i).val(),
                            'sell_price': $('#' + type + '_price_' + i).val(),
                            'sell_rate': $('#' + type + '_rate-m').val(),
                            'tradetype': 'limit_order'
                        },
                        success: function (response) {
                            updatebalance();
                            // updateprice();
                            var data = JSON.parse(response);
                            if (data.status === '1') {
                                toastr.success(data.message);
                                trade_history(data.id, Pair);
                                var buy_form = $('#limit_buy_order').validate();
                                buy_form.resetForm();
                                var sell_form = $('#limit_sell_order').validate();
                                sell_form.resetForm();
                                var buy_form_m = $('#limit_buy_order-m').validate();
                                buy_form_m.resetForm();
                                var sell_form_m = $('#limit_sell_order-m').validate();
                                sell_form_m.resetForm();
                            }
                            else if (data.status === '2') {
                                toastr.info(data.message);
                            }
                            else if (data.status === '3') {
                                toastr.info(data.message);
                            }
                            else if (data.status === '4') {
                                toastr.error(data.message);
                            }
                            else {
                                toastr.error(data.message);
                            }
                        }
                    });
                }
                updateprice();
                document.getElementById(type + "_confirmed").disabled = false;
            }
        }

    </script>

    {{--for avialable market order--}}
    <script type="text/javascript">
        var active_tabs = 0;
        $("#trade_tabs").tabs(
            {
                activate: function (event, ui) {
                    var id = ui.newPanel.attr('id');
                    if (id === 'market-order') {
                        active_tabs = 1;
                        $('#market-order-info').css('display', 'block');
                        available_market_data();
                    }
                    else {
                        active_tabs = 0;
                        $('#market-order-info').css('display', 'none');
                    }
                    setInterval(function () {
                        if (active_tabs == 1) {
                            available_market_data();
                        }
                        else {
                            $('#market-order-info').css('display', 'none');
                        }
                        //code goes here that will be run every 5 seconds.
                    }, 5000);
                }
            });

        function available_market_data() {
            $.ajax({
                url: "/ajax/available_market_data",
                method: 'post',
                data: {'pair': Pair},
                success: function (data) {
                    result = JSON.parse(data);

                    if (result.status === 200 || result.status === 422) {
                        $('#market-order-info').css('display', 'block');
                        $('#market_info_text').css('color', '#76C9B1').html("Maximum " + First_currency + " Market Sell: " + result.amount + "<br><br> Maximum " + First_currency + " Market Buy: " + result.total);
                        market_sell = parseFloat(result.amount);
                        market_buy = parseFloat(result.total);
                    }
                    else if (result.status === 500 || result.status === 401) {
                        $('#market-order-info').css('display', 'block');
                        $('#market_info_text').css('color', '#F56F70').text(result.message);
                        market_sell = 0;
                        market_buy = 0;
                    }
                }
            });
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#buy_price').change(function () {
                update_usdprice();
            });
            $('#sell_price').change(function () {
                update_usdprice();
            });
        });

        function update_usdprice() {
            var buy_price = $('#buy_price').val();
            var sell_price = $('#sell_price').val();
            $.get("{{url('/')}}/ajax/get_estimatme_usdbalance?currency=" + Second_currency + "&price=" + buy_price, function (data) {
                $("#buy_usd").html(parseFloat(data).toFixed(3));
            });
            $.get("{{url('/')}}/ajax/get_estimatme_usdbalance?currency=" + Second_currency + "&price=" + sell_price, function (data) {
                $("#sell_usd").html(parseFloat(data).toFixed(3));
            });
        }

        update_usdprice();
        setInterval(function () {
            update_usdprice();
            set_usd(Pair);
        }, 10000);

        function update_usd(pair, price) {
            var explode = pair.split("-");
            var second_currency = explode[1];
            $.get("{{url('/')}}/ajax/get_estimatme_usdbalance?currency=" + second_currency + "&price=" + price, function (response) {
                $('#' + pair + '_USD').text(parseFloat(response).toFixed(3));
            });
        }

        function set_usd(pair) {
            var explode = pair.split("-");
            var second_currency = explode[1];
            var price = 1;
            $.ajax({
                url: "{{url('/')}}/ajax/get_estimatme_usdbalance",
                type: 'GET',
                async: false,
                data: {'currency': second_currency, 'price': price},
                success: function (data) {
                    USD = data;
                }
            });
        }
    </script>

@endsection