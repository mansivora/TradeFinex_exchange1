<p class="text-center">Balance : {{$second_cur_balance}} {{$second_currency}}</p>
                                           <form class="tradeinput form-horizontal" data-tradedirection="buy" role="form" autocomplete="off" id="buy_ins_form">
                                                <div>
                                                    {{csrf_field()}}
                                        <div class="input-group">
                                                        <span class="input-group-addon" style="padding-right:10px;"><i class="fa"><img src="{{URL::asset('front')}}/assets/icons/{{$first_currency}}.png" width="16px" height="16px"></i></span>
                                                        <input class="form-control block_non_numbers" id="buy_ins_{{$first_currency}}" name="buy_ins_{{$first_currency}}" onkeyup="instant_order('buy');" placeholder="{{$first_currency}}" title="Amount spend" value="0">

                                                         <input type="hidden" name="buy_fee_amount" id="buy_fee_amount" value="0">
                                    <input type="hidden" name="buy_price_amount" id="buy_price_amount" value="0">
                                                    </div>



                                                </div>
                                                <div class="qb-button">
                                                    <button type="button" class="btn btn-green btn-action" id="btn-buy" onclick="trade_popup_ins('buy','buy_ins_form')" >Buy</button>

                                                </div>
                                            </form>


