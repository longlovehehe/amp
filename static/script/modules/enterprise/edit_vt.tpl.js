jQuery.validator.addMethod("e_pwd", function (value, element) {
    var length = value.length;
    var flag = true;
    /*        var mob = /^[0-9]{19}}$/i ;*/
    /*        var mob1 = /^[0-9]{20}$/i ;*/
    if (/[\u4E00-\u9FA5]/i.test(value)) {
        flag = false;
    }
    return flag;
}, "<%'密码不能为中文字符'|L%>");

jQuery.validator.addMethod("resource_less", function (value, element) {
    var flag = false;
    if (value == 0) {
        flag = true;
    }
    return flag;
}, "<%'该资源已用完，只能输入0'|L%>");
jQuery.validator.addMethod("resource_less1", function (value, element) {
    var flag = false;
    if (value == 0) {
        flag = true;
    }
    return flag;
}, "<%'可用并发数超过rs设备最大并发数'|L%>");
/*
 function getphone() {
 var e_mds_users = $("input[name=e_mds_users]").val();
 var e_mds_call = $("input[name=e_mds_call]").val();
 // var e_mds_phone = $("input[name=e_mds_phone]").val();
 var e_mds_dispatch = $("input[name=e_mds_dispatch]").val();
 var e_mds_gvs = $("input[name=e_mds_gvs]").val();
 var presidue = e_mds_users - e_mds_dispatch - e_mds_gvs;
 if (presidue <= 0) {
 $("input[name=e_mds_phone]").attr("resource_less", 'TRUE');
 } else {
 prange = "[0," + presidue + "]";
 $("input[name=e_mds_phone]").attr("range", prange);
 }
 }
 function getdispatch() {
 var e_mds_users = $("input[name=e_mds_users]").val();
 var e_mds_call = $("input[name=e_mds_call]").val();
 var e_mds_phone = $("input[name=e_mds_phone]").val();
 //var e_mds_dispatch = $("input[name=e_mds_dispatch]").val();
 var e_mds_gvs = $("input[name=e_mds_gvs]").val();
 var dresidue = e_mds_users - e_mds_phone - e_mds_gvs;
 if (dresidue <= 0) {
 $("input[name=e_mds_dispatch]").attr("resource_less", 'TRUE');
 } else {
 drange = "[0," + dresidue + "]";
 $("input[name=e_mds_dispatch]").attr("range", drange);
 }
 }
 function getgvs() {
 var e_mds_users = $("input[name=e_mds_users]").val();
 var e_mds_call = $("input[name=e_mds_call]").val();
 var e_mds_phone = $("input[name=e_mds_phone]").val();
 var e_mds_dispatch = $("input[name=e_mds_dispatch]").val();
 var e_mds_gvs = $("input[name=e_mds_gvs]").val();
 var gresidue = e_mds_users - e_mds_dispatch - e_mds_phone;
 if (gresidue <= 0) {
 $("input[name=e_mds_gvs]").attr("resource_less", 'TRUE');
 } else {
 grange = "[0," + gresidue + "]";
 $("input[name=e_mds_gvs]").attr("range", grange);
 }
 }
 */
var sum = 0;
var    d_phone_user =  0;
var    d_dispatch_user =  0;
var    d_gvs_user =  0;
$("input").bind("change", function () {

    var e_mds_call = $("input[name=e_mds_call]").val();
    var e_mds_phone = $("input[name=e_mds_phone]").val();
    var e_mds_dispatch = $("input[name=e_mds_dispatch]").val();
    var e_mds_gvs = $("input[name=e_mds_gvs]").val();
    var phone_num = $("input[name=phone_num]").val();
    var dispatch_num = $("input[name=dispatch_num]").val();
    var gvs_num = $("input[name=gvs_num]").val();
    d_phone_user =  $("input[name=d_phone_num]").val();
    d_dispatch_user =  $("input[name=d_dispatch_num]").val();
    d_gvs_user =  $("input[name=d_gvs_num]").val();
    var diff_phone = parseInt(eval($("#mds_id span").attr("data"))[0].diff_phone);
    var diff_dispatch = parseInt(eval($("#mds_id span").attr("data"))[0].diff_dispatch);
    var diff_gvs = parseInt(eval($("#mds_id span").attr("data"))[0].diff_gvs);
    var d_user = eval($("#mds_id span").attr("data"))[0].diff_user;
    if (e_mds_phone == "") {
        $("input[name=e_mds_phone]").val(0);
        e_mds_phone = 0;
    } else if (e_mds_dispatch == "") {
        $("input[name=e_mds_dispatch]").val(0);
        e_mds_dispatch = 0;
    } else if (e_mds_gvs == "") {
        $("input[name=e_mds_gvs]").val(0);
        e_mds_gvs = 0;
    }
    if((d_phone_user-diff_phone)<0){
           diff_phone=d_phone_user;
       }
       if((d_dispatch_user-diff_dispatch)<0){
           diff_dispatch=d_dispatch_user;
       }
       if((d_gvs_user-diff_gvs)<0){
           diff_gvs=d_gvs_user;
       }
    sum = parseInt(e_mds_phone) + parseInt(e_mds_dispatch) + parseInt(e_mds_gvs);
    $("input[name=e_mds_users]").val(sum);
    var d_have = $("#d_have").val();
    sum1 = parseInt(e_mds_phone)*2 + parseInt(e_mds_dispatch) + parseInt(e_mds_gvs);
    var e_vcr_id = $("#e_vcr_id").val();
    if(e_vcr_id != 'undefined' && e_vcr_id != 0)
    {
        if (isNaN(sum1)) {
            $("input[name=e_rs_rec]").val('N/A');
        } else {
            $("input[name=e_rs_rec]").val(sum1);
        }
    }
    if (diff_phone == 'undefined' || diff_phone == "" || diff_phone == 0) {
        $("input[name=e_mds_phone]").attr("resource_less", 'TRUE');
    } else {
        prange = "[" + phone_num + "," + diff_phone + "]";
        $("input[name=e_mds_phone]").attr("range", prange);
    }
   if(e_vcr_id == 'undefined' && e_vcr_id == 0){
        if(d_have < sum1){
            $("input[name=e_mds_phone]").attr("resource_less1", 'TRUE');
        }
        else
        {
            $("input[name=e_mds_phone]").removeAttr('resource_less1').removeAttr('range');
        }
    }
    if (diff_dispatch == 'undefined' || diff_dispatch == "" || diff_dispatch == 0) {
        $("input[name=e_mds_dispatch]").attr("resource_less", 'TRUE');
    } else {
        drange = "[" + dispatch_num + "," + diff_dispatch + "]";
        $("input[name=e_mds_dispatch]").attr("range", drange);
    }
    if (diff_gvs == 'undefined' || diff_gvs == "" || diff_gvs == 0) {
        $("input[name=e_mds_gvs]").attr("resource_less", 'TRUE');
    } else {
        grange = "[" + gvs_num + "," + diff_gvs + "]";
        $("input[name=e_mds_gvs]").attr("range", grange);
    }
    /*
     if (d_user == 'undefined' || d_user == "" || d_user == 0) {
     $("input[name=e_mds_users]").attr("resource_less", 'TRUE');
     } else {
     urange = "[0," + d_user + "]";
     $("input[name=e_mds_users]").attr("range", urange);
     }
     if (d_call == 'undefined' || d_user == "" || d_call == 0) {
     $("input[name=e_mds_call]").attr("resource_less", 'TRUE');
     } else {
     crange = "[0," + d_call + "]";
     $("input[name=e_mds_call]").attr("range", crange);
     }*/
    $("#form").valid();
});


(function () {
    var url = $("select#e_mds_id").attr("action");
    url += "&d_area=@";
    $.ajax({
        url: url,
        success: function (result) {
            $("select#e_mds_id").html(result);
        }
    });
})();