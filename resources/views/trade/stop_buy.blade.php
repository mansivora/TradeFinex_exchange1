<p class="text-center">Balance : {{$second_cur_balance}} {{$second_currency}}</p>
                                           <form class="tradeinput form-horizontal" data-tradedirection="buy" role="form" autocomplete="off" id="buy_stop_form">
                                                <div>
                                                    {{csrf_field()}}
                                        <div class="input-group">
                                                        <span class="input-group-addon" style="padding-right:10px;"><i class="fa"><img src="{{URL::asset('front')}}/assets/icons/{{$first_currency}}.png" width="16px" height="16px"></i></span>
                                                        <input class="form-control block_non_numbers" id="buy_stop_{{$first_currency}}" name="buy_stop_{{$first_currency}}" onkeyup="stop_order('buy');" placeholder="{{$first_currency}}" title="Amount spend" value="0">

                                                         <input type="hidden" name="buy_fee_amount" id="buy_fee_amount" value="0">
                                    <input type="hidden" name="buy_price_amount" id="buy_price_amount" value="0">
                                                    </div>

                                                     <div class="input-group">
                                                        <span class="input-group-addon" style="padding-right:10px;"><i class="fa"><img src="{{URL::asset('front')}}/assets/icons/{{$second_currency}}.png" width="16px" height="16px"></i></span>
                                                        <input class="form-control block_non_numbers" id="buy_stop_price" name="buy_stop_price" onkeyup="stop_order('buy');" placeholder="Price" title="Enter price" value="{{sprintf('%.8f',$sell_rate)}}">


                                                    </div>



                                                </div>
                                                <div class="qb-button">
                                                    <button type="button" class="btn btn-green btn-action" id="btn-buy" onclick="trade_popup_stop('buy','buy_stop_form')">Buy</button>

                                                </div>
                                            </form>


