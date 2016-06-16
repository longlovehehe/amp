$("a.ajaxpost_a").bind("click",function(){
   if ($("#form").valid()) {
       check_name();
         var form = $("a.ajaxpost_a").attr("form");
        var url = $("#" + form).attr("action");
        $.ajax({
            url: url,
            method: "POST",
            dataType: "json",
            data: $("#form").serialize(),
            success: function (result) {
                    notice(result.msg, $("a.ajaxpost_a").attr("goto"));
            }
        });
       
    }else{
        $("input.error:first").focus();
    } 
    
});
jQuery.validator.addMethod("e_pwd", function (value, element) {
    var length = value.length;
    var flag = true;
    /*        var mob = /^[0-9]{19}}$/i ;*/
    /*        var mob1 = /^[0-9]{20}$/i ;*/
    if (/[\u4E00-\u9FA5]/i.test(value)) {
        flag = false;
    }
    return flag;
}, L("密码不能为中文字符"));

jQuery.validator.addMethod("resource_less", function (value, element) {
    var flag = false;
    if (value == 0) {
        flag = true;
    }
    return flag;
}, L("该资源已用完，只能输入0"));
var sum = 0;
/*
$("select#e_mds_id").bind("change", function () {
    var d_user = $(this).children('option:selected').attr("d_user");
    var d_call = $(this).children('option:selected').attr("d_call");
    var d_phone_user = $(this).children('option:selected').attr("d_phone_user");
    var d_dispatch_user = $(this).children('option:selected').attr("d_dispatch_user");
    var d_gvs_user = $(this).children('option:selected').attr("d_gvs_user");
    var diff_phone = $(this).children('option:selected').attr("diff_phone");
    var diff_dispatch = $(this).children('option:selected').attr("diff_dispatch");
    var diff_gvs = $(this).children('option:selected').attr("diff_gvs");
    $("input[name=ag_user_num]").removeAttr('resource_less').removeAttr('range');
    $("input[name=e_mds_call]").removeAttr('resource_less').removeAttr('range');
    $("input[name=ag_phone_num]").removeAttr('resource_less').removeAttr('range');
    $("input[name=ag_dispatch_num]").removeAttr('resource_less').removeAttr('range');
    $("input[name=ag_gvs_num]").removeAttr('resource_less').removeAttr('range');

    $("input").bind("change", function () {

        var e_mds_call = $("input[name=e_mds_call]").val();
        var ag_phone_num = $("input[name=ag_phone_num]").val();
        var ag_dispatch_num = $("input[name=ag_dispatch_num]").val();
        var ag_gvs_num = $("input[name=ag_gvs_num]").val();
        $("input[name=ag_user_num]").removeAttr('resource_less').removeAttr('range');
        $("input[name=e_mds_call]").removeAttr('resource_less').removeAttr('range');
        $("input[name=ag_phone_num]").removeAttr('resource_less').removeAttr('range');
        $("input[name=ag_dispatch_num]").removeAttr('resource_less').removeAttr('range');
        $("input[name=ag_gvs_num]").removeAttr('resource_less').removeAttr('range');
        if (ag_phone_num == "") {
            $("input[name=ag_phone_num]").val(0);
        } else if (ag_dispatch_num == "") {
            $("input[name=ag_dispatch_num]").val(0);
        } else if (ag_gvs_num == "") {
            $("input[name=ag_gvs_num]").val(0);
        }

        sum = Number(ag_phone_num) + Number(ag_dispatch_num) + Number(ag_gvs_num);
        if (isNaN(sum)) {
            $("input[name=ag_user_num]").val('N/A');
        } else {
            $("input[name=ag_user_num]").val(sum);
        }

        if (diff_phone == 'undefined' || diff_phone == "" || diff_phone == 0) {
            $("input[name=ag_phone_num]").attr("resource_less", 'TRUE');
        } else {
            prange = "[0," + diff_phone + "]";
            $("input[name=ag_phone_num]").attr("range", prange);
        }
        if (diff_dispatch == 'undefined' || diff_dispatch == "" || diff_dispatch == 0) {
            $("input[name=ag_dispatch_num]").attr("resource_less", 'TRUE');
        } else {
            drange = "[0," + diff_dispatch + "]";
            $("input[name=ag_dispatch_num]").attr("range", drange);
        }
        if (diff_gvs == 'undefined' || diff_gvs == "" || diff_gvs == 0) {
            $("input[name=ag_gvs_num]").attr("resource_less", 'TRUE');
        } else {
            grange = "[0," + diff_gvs + "]";
            $("input[name=ag_gvs_num]").attr("range", grange);
        }
    });

    /*
     if (d_user == 'undefined' || d_user == "" || d_user == 0) {
     $("input[name=ag_user_num]").attr("resource_less", 'TRUE');
     } else {
     urange = "[0," + d_user + "]";
     $("input[name=ag_user_num]").attr("range", urange);
     }
    if (diff_phone == 'undefined' || diff_phone == "" || diff_phone == 0) {
        $("input[name=ag_phone_num]").attr("resource_less", 'TRUE');
    } else {
        prange = "[0," + diff_phone + "]";
        $("input[name=ag_phone_num]").attr("range", prange);
    }
    if (diff_dispatch == 'undefined' || diff_dispatch == "" || diff_dispatch == 0) {
        $("input[name=ag_dispatch_num]").attr("resource_less", 'TRUE');
    } else {
        drange = "[0," + diff_dispatch + "]";
        $("input[name=ag_dispatch_num]").attr("range", drange);
    }
    if (diff_gvs == 'undefined' || diff_gvs == "" || diff_gvs == 0) {
        $("input[name=ag_gvs_num]").attr("resource_less", 'TRUE');
    } else {
        grange = "[0," + diff_gvs + "]";
        $("input[name=ag_gvs_num]").attr("range", grange);
    }
    if (d_call == 'undefined' || d_user == "" || d_call == 0) {
        $("input[name=e_mds_call]").attr("resource_less", 'TRUE');
    } else {
        crange = "[0," + d_call + "]";
        $("input[name=e_mds_call]").attr("range", crange);
    }
    $("#form").valid();
});
*/
//(function () {
//    var url = $("select#e_mds_id").attr("action");
//    url += "&d_area=@";
//    $.ajax({
//        url: url,
//        success: function (result) {
//            $("select#e_mds_id").html(result);
//        }
//    });
//})();
if($("input[name=do]").val()=="edit"){
    $("input.ag_allow").bind("change", function () {

    var e_mds_call = $("input[name=e_mds_call]").val();
    var ag_phone_num = $("input[name=ag_phone_num]").val();
    var ag_dispatch_num = $("input[name=ag_dispatch_num]").val();
    var ag_gvs_num = $("input[name=ag_gvs_num]").val();
    var diff_phone = $("input[name=diff_phone]").val();
    var diff_dispatch = $("input[name=diff_dispatch]").val();
    var diff_gvs = $("input[name=diff_gvs]").val();
    var a_diff_phone = $("input[name=a_diff_phone]").val();
    var a_diff_dispatch = $("input[name=a_diff_dispatch]").val();
    var a_diff_gvs = $("input[name=a_diff_gvs]").val();
    $("input[name=ag_user_num]").removeAttr('resource_less').removeAttr('range');
    $("input[name=e_mds_call]").removeAttr('resource_less').removeAttr('range');
    $("input[name=ag_phone_num]").removeAttr('resource_less').removeAttr('range');
    $("input[name=ag_dispatch_num]").removeAttr('resource_less').removeAttr('range');
    $("input[name=ag_gvs_num]").removeAttr('resource_less').removeAttr('range');
    if (ag_phone_num == "") {
        $("input[name=ag_phone_num]").val(0);
    } else if (ag_dispatch_num == "") {
        $("input[name=ag_dispatch_num]").val(0);
    } else if (ag_gvs_num == "") {
        $("input[name=ag_gvs_num]").val(0);
    }

    sum = Number(ag_phone_num) + Number(ag_dispatch_num) + Number(ag_gvs_num);
    if (isNaN(sum)) {
        $("input[name=ag_user_num]").val('N/A');
    } else {
        $("input[name=ag_user_num]").val(sum);
    }

    if (diff_phone == 'undefined' || diff_phone == "" || diff_phone == 0) {
        $("input[name=ag_phone_num]").attr("resource_less", 'TRUE');
    } else {
        prange = "[" + a_diff_phone + "," + diff_phone + "]";
        $("input[name=ag_phone_num]").attr("range", prange);
    }
    if (diff_dispatch == 'undefined' || diff_dispatch == "" || diff_dispatch == 0) {
        $("input[name=ag_dispatch_num]").attr("resource_less", 'TRUE');
    } else {
        drange = "[" + a_diff_dispatch + "," + diff_dispatch + "]";
        $("input[name=ag_dispatch_num]").attr("range", drange);
    }
    if (diff_gvs == 'undefined' || diff_gvs == "" || diff_gvs == 0) {
        $("input[name=ag_gvs_num]").attr("resource_less", 'TRUE');
    } else {
        grange = "[" + a_diff_gvs + "," + diff_gvs + "]";
        $("input[name=ag_gvs_num]").attr("range", grange);
    }
});
}else{
$("input.ag_allow").bind("change", function () {

    var e_mds_call = $("input[name=e_mds_call]").val();
    var ag_phone_num = $("input[name=ag_phone_num]").val();
    var ag_dispatch_num = $("input[name=ag_dispatch_num]").val();
    var ag_gvs_num = $("input[name=ag_gvs_num]").val();
    var diff_phone = $("input[name=diff_phone]").val();
    var diff_dispatch = $("input[name=diff_dispatch]").val();
    var diff_gvs = $("input[name=diff_gvs]").val();
    var a_diff_phone = $("input[name=a_diff_phone]").val();
    var a_diff_dispatch = $("input[name=a_diff_dispatch]").val();
    var a_diff_gvs = $("input[name=a_diff_gvs]").val();
    $("input[name=ag_user_num]").removeAttr('resource_less').removeAttr('range');
    $("input[name=e_mds_call]").removeAttr('resource_less').removeAttr('range');
    $("input[name=ag_phone_num]").removeAttr('resource_less').removeAttr('range');
    $("input[name=ag_dispatch_num]").removeAttr('resource_less').removeAttr('range');
    $("input[name=ag_gvs_num]").removeAttr('resource_less').removeAttr('range');
    if (ag_phone_num == "") {
        $("input[name=ag_phone_num]").val(0);
    } else if (ag_dispatch_num == "") {
        $("input[name=ag_dispatch_num]").val(0);
    } else if (ag_gvs_num == "") {
        $("input[name=ag_gvs_num]").val(0);
    }

    sum = Number(ag_phone_num) + Number(ag_dispatch_num) + Number(ag_gvs_num);
    if (isNaN(sum)) {
        $("input[name=ag_user_num]").val('N/A');
    } else {
        $("input[name=ag_user_num]").val(sum);
    }

    if (diff_phone == 'undefined' || diff_phone == "" || diff_phone == 0) {
        $("input[name=ag_phone_num]").attr("resource_less", 'TRUE');
    } else {
        prange = "[" + a_diff_phone + "," + diff_phone + "]";
        $("input[name=ag_phone_num]").attr("range", prange);
    }
    if (diff_dispatch == 'undefined' || diff_dispatch == "" || diff_dispatch == 0) {
        $("input[name=ag_dispatch_num]").attr("resource_less", 'TRUE');
    } else {
        drange = "[" + a_diff_dispatch + "," + diff_dispatch + "]";
        $("input[name=ag_dispatch_num]").attr("range", drange);
    }
    if (diff_gvs == 'undefined' || diff_gvs == "" || diff_gvs == 0) {
        $("input[name=ag_gvs_num]").attr("resource_less", 'TRUE');
    } else {
        grange = "[" + a_diff_gvs + "," + diff_gvs + "]";
        $("input[name=ag_gvs_num]").attr("range", grange);
    }
});
}
$("input").bind("change", function () {
    var e_mds_call = $("input[name=e_mds_call]").val();
    var ag_phone_num = $("input[name=ag_phone_num]").val();
    var ag_dispatch_num = $("input[name=ag_dispatch_num]").val();
    var ag_gvs_num = $("input[name=ag_gvs_num]").val();
    if (ag_phone_num == "") {
        $("input[name=ag_phone_num]").val(0);
        ag_phone_num = 0;
    } else if (ag_dispatch_num == "") {
        $("input[name=ag_dispatch_num]").val(0);
        ag_dispatch_num = 0;
    } else if (ag_gvs_num == "") {
        $("input[name=ag_gvs_num]").val(0);
        ag_gvs_num = 0;
    }
    sum = parseInt(ag_phone_num) + parseInt(ag_dispatch_num) + parseInt(ag_gvs_num);
    if (isNaN(sum)) {
        $("input[name=ag_user_num]").val('N/A');
    } else {
        $("input[name=ag_user_num]").val(sum);
    }

});

/**
 * 自动匹配单位
 */
   var i= $("input[name=basic_price]").val();
    var j= $("input[name=console_price]").val();
    
$("input[name=units_price]").on("input",function(){
    var units=$("input[name=units_price]").val();
    $("input[name=basic_price]").val(units+i);
    $("input[name=console_price]").val(units+j);
    valid();
});

$("input[name=ag_name]").on("blur",function(){
    $.ajax({
        url:"?m=agents&a=check_name",
        data:{name:$("input[name=ag_name]").val(),ag_number:$("input[name=ag_number]").val()},
        success:function(res){
                if(res=="1"){
                     layer.closeAll('tips');
                }else{
                    layer.tips("<%'名称已存在'|L%>",$("input[name=ag_name]"),
                    {
                        tips:[1, '#A83A3A']
                    }
                  );
                }
            }
        });
});

function check_name(){
    $.ajax({
        url:"?m=agents&a=check_name",
        data:{name:$("input[name=ag_name]").val(),ag_number:$("input[name=ag_number]").val()},
        success:function(res){
            if(res=="1"){
                     layer.closeAll('tips');
                }else{
                    layer.tips("<%'名称已存在'|L%>",$("input[name=ag_name]"),
                    {
                        tips:[1, '#A83A3A']
                    }
                  );
          $("input[name=ag_name]").focus();
          exit();
                }
        }
        });
}

