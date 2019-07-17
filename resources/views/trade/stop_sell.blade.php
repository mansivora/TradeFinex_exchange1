<p class="text-center">Balance : {{$first_cur_balance}} {{$first_currency}}</p>
                                           <form class="tradeinput form-horizontal" data-tradedirection="buy" role="form" autocomplete="off" id="sell_stop_form">
                                                <div>
                                                    {{csrf_field()}}
                                        <div class="input-group">
                                                        <span class="input-group-addon" style="padding-right:10px;"><i class="fa"><img src="{{URL::asset('front')}}/assets/icons/{{$first_currency}}.png" width="16px" height="16px"></i></span>
                                                        <input class="form-control block_non_numbers" id="sell_stop_{{$first_currency}}" name="sell_stop_{{$first_currency}}" onkeyup="stop_order('sell');" placeholder="{{$first_currency}}" title="Amount spend" value="0">

                                                         <input type="hidden" name="sell_fee_amount" id="sell_fee_amount" value="0">
                                    <input type="hidden" name="sell_price_amount" id="sell_price_amount" value="0">
                                                    </div>

                                                    <div class="input-group">
                                                        <span class="input-group-addon" style="padding-right:10px;"><i class="fa"><img src="{{URL::asset('front')}}/assets/icons/{{$second_currency}}.png" width="16px" height="16px"></i></span>
                                                        <input class="form-control block_non_numbers" id="sell_stop_price" name="sell_stop_price" onkeyup="stop_order('sell');" placeholder="Price" title="Enter price" value="{{sprintf('%.8f',$buy_rate)}}">


                                                    </div>



                                                </div>
                                                <div class="qb-button">
                                                    <button type="button" class="btn btn-warning btn-action" id="btn-sell" onclick="trade_popup_stop('sell','sell_stop_form')" >Sell</button>

                                                </div>
                                            </form>


