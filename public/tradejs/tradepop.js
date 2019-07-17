 

    function trade_popup_limit(type,frmid)
    {
         var frmvalid=$("#"+frmid).valid();
        var priceamt=$("#"+type+"_price_amount").val();
        var feeamt=$("#"+type+"_fee_amount").val();
        var famount=$("#"+type+"_limit_XDC").val();
        var buy_rate=$("#sell_limit_price").val();
        var sell_rate=$("#buy_limit_price").val();
         if(frmvalid==true)
         {
        $("#trade_frm_id").val(frmid);
        $("#myModaltrade").modal('show');
        $("#modal_ord_type").html(type);
        $("#modal_price_amt").html(priceamt);
        $("#modal_fee_amt").html(feeamt);
        $("#modal_first_curr").html(famount);
         if(type=='buy'){
        $("#modal_second_curr").html(sell_rate);
        var bprice=parseFloat(sell_rate) * parseFloat(famount);
        $("#sum_first_second").html(bprice.toFixed(8));
         var sendprice=sell_rate;
        }
        else
        {
        var sprice=parseFloat(buy_rate) * parseFloat(famount);
        $("#modal_second_curr").html(buy_rate);
        $("#sum_first_second").html(sprice.toFixed(8));
        var sendprice=buy_rate;
        }
        var secondcur=$("#what_cur").val();
         $.get("https://alphaex.net/ajax/get_estimatme_usdbalance?currency="+secondcur+"&price="+sendprice, function(data){
            $("#modal_est_usd_price").html(data);
         });
         }
    }

    function trade_popup_stop(type,frmid)
    {
        var frmvalid=$("#"+frmid).valid();
        var priceamt=$("#"+type+"_price_amount").val();
        var feeamt=$("#"+type+"_fee_amount").val();
        var famount=$("#"+type+"_stop_XDC").val();
        var buy_rate=$("#sell_stop_price").val();
        var sell_rate=$("#buy_stop_price").val();
         if(frmvalid==true)
         {
        $("#trade_frm_id").val(frmid);
        $("#myModaltrade").modal('show');
        $("#modal_ord_type").html(type);
        $("#modal_price_amt").html(priceamt);
        $("#modal_fee_amt").html(feeamt);
        $("#modal_first_curr").html(famount);
         if(type=='buy'){
        $("#modal_second_curr").html(sell_rate);
        var bprice=parseFloat(sell_rate) * parseFloat(famount);
        $("#sum_first_second").html(bprice.toFixed(8));
        }
        else
        {
        var sprice=parseFloat(buy_rate) * parseFloat(famount);
        $("#modal_second_curr").html(buy_rate);
        $("#sum_first_second").html(sprice.toFixed(8));
        }
         }
    }
    function trade_modal_submit()
    {
        var subfrm=$("#trade_frm_id").val();
        console.log(subfrm);
        $("#myModaltrade").modal('hide');
        $("#"+subfrm).submit();

    }