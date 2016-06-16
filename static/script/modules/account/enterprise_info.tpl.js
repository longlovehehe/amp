$("a.export").click(function(){
    var ep_id = $("input[name=ep_id]").val();
    var start = $("input[name=start]").val();
    var er_id = $("input[name=er_id]").val();
    var end = $("input[name=end]").val();
    var type = $("input[name=type]").val();
    var open_bank = $("input[name=open_bank]").val();
    var open_bank_account = $("input[name=open_bank_account]").val();
    var other_price = $("input[name=other_price]").val();
    var pre_total = $("input[name=pre_total]").val();
    var remarks = $("input[name=remarks]").val();
    var pte = $("input[name=pte]").val();
    var total = $("input[name=total]").val();
    
    var src="?m=account&a=export_emp&er_id="+er_id+"&ep_id="+ep_id+"&start="+start+"&type="+type+"&open_bank="+open_bank+"&open_bank_account="+open_bank_account+"&other_price="+other_price+"&pre_total="+pre_total+"&remarks="+remarks+"&pte="+pte+"&total="+total;
    $("#ifr").attr("src",src);
});