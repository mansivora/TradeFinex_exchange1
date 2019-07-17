  @if(isset($sell_order_list[0]))
                                        @foreach($sell_order_list as $sell_list)
                                        <tr onclick="sell_click_value('{{$sell_list->Price}}','{{$sell_list->Amount}}')">
                                           <td class="text-center price">{{$sell_list->Price}}</td>
                                            <td class="text-center price">{{$sell_list->Amount}}</td>
                                            <td class="text-center price">{{($sell_list->Amount * $sell_list->Price)}}</td>
                                        </tr>
                                        @endforeach

                                         @else
                                        <tr>
                                        <td colspan="3" class="text-center">No data availale in the table</td>
                                        </tr>

                                        @endif