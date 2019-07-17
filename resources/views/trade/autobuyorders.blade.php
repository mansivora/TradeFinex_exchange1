@if(isset($buy_order_list[0]))
                                        @foreach($buy_order_list as $buy_list)
                                        <tr onclick="buy_click_value('{{$buy_list->Price}}','{{$buy_list->Amount}}')">
                                            <td class="text-center price">{{$buy_list->Price}}</td>
                                            <td class="text-center price">{{$buy_list->Amount}}</td>
                                            <td class="text-center price">{{($buy_list->Amount * $buy_list->Price)}}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                        <td colspan="3" class="text-center">No data availale in the table</td>
                                        </tr>
                                        @endif