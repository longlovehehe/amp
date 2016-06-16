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
var sum = 0;
var d_phone_user = 0;
var d_dispatch_user = 0;
var d_gvs_user =0;
$("select#e_mds_id").bind("change", function () {
    var d_user = $(this).children('option:selected').attr("d_user");
    var d_call = $(this).children('option:selected').attr("d_call");
    d_phone_user = $(this).children('option:selected').attr("diff_phone");
    d_dispatch_user = $(this).children('option:selected').attr("diff_dispatch");
    d_gvs_user = $(this).children('option:selected').attr("diff_gvs");

    var diff_phone = $("input[name=diff_phone]").val();
    var diff_dispatch = $("input[name=diff_dispatch]").val();
    var diff_gvs = $("input[name=diff_gvs]").val();
    $("input[name=e_mds_users]").removeAttr('resource_less').removeAttr('range');
    $("input[name=e_mds_call]").removeAttr('resource_less').removeAttr('range');
    $("input[name=e_mds_phone]").removeAttr('resource_less').removeAttr('range');
    $("input[name=e_mds_dispatch]").removeAttr('resource_less').removeAttr('range');
    $("input[name=e_mds_gvs]").removeAttr('resource_less').removeAttr('range');
    if((d_phone_user-diff_phone)<0){
           diff_phone=d_phone_user;
       }
       if((d_dispatch_user-diff_dispatch)<0){
           diff_dispatch=d_dispatch_user;
       }
       if((d_gvs_user-diff_gvs)<0){
           diff_gvs=d_gvs_user;
       }

    if (diff_phone == 'undefined' || diff_phone == "" || diff_phone == 0) {
        $("input[name=e_mds_phone]").attr("resource_less", 'TRUE');
    } else {
        prange = "[0," + diff_phone + "]";
        $("input[name=e_mds_phone]").attr("range", prange);
    }
    if (diff_dispatch == 'undefined' || diff_dispatch == "" || diff_dispatch == 0) {
        $("input[name=e_mds_dispatch]").attr("resource_less", 'TRUE');
    } else {
        drange = "[0," + diff_dispatch + "]";
        $("input[name=e_mds_dispatch]").attr("range", drange);
    }
    if (diff_gvs == 'undefined' || diff_gvs == "" || diff_gvs == 0) {
        $("input[name=e_mds_gvs]").attr("resource_less", 'TRUE');
    } else {
        grange = "[0," + diff_gvs + "]";
        $("input[name=e_mds_gvs]").attr("range", grange);
    }
    if (d_call == 'undefined' || d_user == "" || d_call == 0) {
        $("input[name=e_mds_call]").attr("resource_less", 'TRUE');
    } else {
        crange = "[0," + d_call + "]";
        $("input[name=e_mds_call]").attr("range", crange);
    }
    $("#form").valid();
});

/*代理商平台创建企业时限制*/
$("input").bind("change", function () {
    var e_mds_call = $("input[name=e_mds_call]").val();
    var e_mds_phone = $("input[name=e_mds_phone]").val();
    var e_mds_dispatch = $("input[name=e_mds_dispatch]").val();
    var e_mds_gvs = $("input[name=e_mds_gvs]").val();

    var diff_phone = $("input[name=diff_phone]").val();
    var diff_dispatch = $("input[name=diff_dispatch]").val();
    var diff_gvs = $("input[name=diff_gvs]").val();
    $("input[name=e_mds_users]").removeAttr('resource_less').removeAttr('range');
    $("input[name=e_mds_call]").removeAttr('resource_less').removeAttr('range');
    $("input[name=e_mds_phone]").removeAttr('resource_less').removeAttr('range');
    $("input[name=e_mds_dispatch]").removeAttr('resource_less').removeAttr('range');
    $("input[name=e_mds_gvs]").removeAttr('resource_less').removeAttr('range');
    if (e_mds_phone == "") {
        $("input[name=e_mds_phone]").val(0);
    } else if (e_mds_dispatch == "") {
        $("input[name=e_mds_dispatch]").val(0);
    } else if (e_mds_gvs == "") {
        $("input[name=e_mds_gvs]").val(0);
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

    sum = Number(e_mds_phone) + Number(e_mds_dispatch) + Number(e_mds_gvs);
    if (isNaN(sum)) {
        $("input[name=e_mds_users]").val('N/A');
    } else {
        $("input[name=e_mds_users]").val(sum);
    }

    if (diff_phone == 'undefined' || diff_phone == "" || diff_phone == 0) {
        $("input[name=e_mds_phone]").attr("resource_less", 'TRUE');
    } else {
        prange = "[0," + diff_phone + "]";
        $("input[name=e_mds_phone]").attr("range", prange);
    }
    if (diff_dispatch == 'undefined' || diff_dispatch == "" || diff_dispatch == 0) {
        $("input[name=e_mds_dispatch]").attr("resource_less", 'TRUE');
    } else {
        drange = "[0," + diff_dispatch + "]";
        $("input[name=e_mds_dispatch]").attr("range", drange);
    }
    if (diff_gvs == 'undefined' || diff_gvs == "" || diff_gvs == 0) {
        $("input[name=e_mds_gvs]").attr("resource_less", 'TRUE');
    } else {
        grange = "[0," + diff_gvs + "]";
        $("input[name=e_mds_gvs]").attr("range", grange);
    }
});

$("select#e_vcr_id").bind("change", function () {
    // $("#e_has_vcr").val('1');
    // $("input[name=e_rs_rec]").removeAttr('resource_less').removeAttr('range');
    
    
    var e_vcr_id = $(this).children('option:selected').val();

    if(e_vcr_id != '')
    {
        $("#e_has_vcr").val('1');        
        
    }
    else
    {
        $("#e_has_vcr").val('0');
    }

        $("#e_rs_rec").removeAttr('resource_less').removeAttr('range');
        var e_mds_phone = $("input[name=e_mds_phone]").val();
        var e_mds_dispatch = $("input[name=e_mds_dispatch]").val();
        var e_mds_gvs = $("input[name=e_mds_gvs]").val();
        sum1 = Number(e_mds_phone)*2 +Number(e_mds_dispatch) + Number(e_mds_gvs);
        if (isNaN(sum1)) {
            $("input[name=e_rs_rec]").val('N/A');
        } else {
            $("input[name=e_rs_rec]").val(sum1);
        }
        var diff_rec = $("#e_vcr_id").children('option:selected').attr("diff_rec");
        var e_rs_rec = $("#e_rs_rec").val();

        if((diff_rec-e_rs_rec) < 0)
        {
            grange = "[0," + diff_rec + "]";
            $("#e_rs_rec").attr("range", grange); 

        }
        else
        {
            $('#e_vcr_id').click(function(){
                var diff_rec1 = $("#e_vcr_id").children('option:selected').attr("diff_rec");
                var e_rs_rec1 = $("#e_rs_rec").val();

                if((diff_rec1-e_rs_rec1) >= 0)
                {
                    $('#e_rs_rec-error').remove();
                }
            });
        }
        // else
        // {alert(4);
        //     $('#e_vcr_id').click(function(){
        //         alert(1);
        //         $('#e_rs_rec-error').remove();
        //     })
        //     // $("#e_rs_rec-error").detach();
        //     // $("#e_rs_rec").attr("resource_less", 'TRUE');
        // }

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

$("input").bind("change", function () {
    var e_mds_call = $("input[name=e_mds_call]").val();
    var e_mds_phone = $("input[name=e_mds_phone]").val();
    var e_mds_dispatch = $("input[name=e_mds_dispatch]").val();
    var e_mds_gvs = $("input[name=e_mds_gvs]").val();
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
    sum = parseInt(e_mds_phone) + parseInt(e_mds_dispatch) + parseInt(e_mds_gvs);
    if (isNaN(sum)) {
        $("input[name=e_mds_users]").val('N/A');
    } else {
        $("input[name=e_mds_users]").val(sum);
    }

    sum1 = parseInt(e_mds_phone)*2 + parseInt(e_mds_dispatch) + parseInt(e_mds_gvs);
    var e_has_vcr = $("#e_has_vcr").val();
    if(e_has_vcr == "1")
    {
        if (isNaN(sum1)) {
            $("input[name=e_rs_rec]").val('N/A');
        } else {
            $("input[name=e_rs_rec]").val(sum1);
        }
    }
    else
    {
        $("#e_rs_rec").val('0');
    }
    var diff_rec = $("#e_vcr_id").children('option:selected').attr("diff_rec");
    var e_rs_rec = $("#e_rs_rec").val();
    $("#e_rs_rec-error").remove();
    if((diff_rec-e_rs_rec) < 0)
    {
        grange = '<label id="e_rs_rec-error" class="error" for="e_rs_rec"><%"请输入 0 至"|L%> '+diff_rec+' <%"之间的数字"|L%></label>';
        $("#e_rs_rec").after(grange);    
    }
    else
    {
        $("#e_rs_rec-error").remove();
    }
});
$("#e_mds_id").bind('change', function () {
        var d_deployment_id = $(this).children('option:selected').attr("d_deployment_id");
        var tdata = eval($(this).attr('data'));
        var data = tdata[0];
        var to = $("#e_vcr_id");
        var url = to.attr("action") + "&" + data.field + "=" + d_deployment_id;
        //获取同一部署ID下的rs设备
        var owner = to;       
        $.ajax({
            url: url,
            success: function (result) {
                if (data.view == "true") {
                    owner.html("<option value=''><%'全部'|L%></option>" + result);
                } else {
                    owner.html(result);
                }
            }
        });
        //获取同一部署ID下的ss设备
        var to1 = $("#e_ss_id");
        var url1 = to1.attr("action") + "&" + data.field + "=" + d_deployment_id;

        var owner1 = to1;       
        $.ajax({
            url: url1,
            success: function (result) {
                if (data.view == "true") {
                    owner1.html("<option value=''><%'全部'|L%></option>" + result);
                } else {
                    owner1.html(result);
                }
            }
        });
    });
/**
 * 验证邮箱或电话号码是否已存在
 */
/*
$("input[name=em_phone]").attr("em_phone", "TRUE");
$("input[name=em_phone]").attr("mobile1", "TRUE");
jQuery.validator.addMethod("em_phone", function (value, element) {
    var length = value.length;
    var flag = false;
    var mob = /^\s*\+?\s*(\(\s*\d+\s*\)|\d+)(\s*-?\s*(\(\s*\d+\s*\)|\s*\d+\s*))*\s*$/;
    if ( mob.test(value) || length == 0) {
          $.ajax({
            url:'?m=enterprise&a=get_em_mob',
            data:{em_phone:value},
            success:function(res){
                if(res==2){
                    flag = false;
                }else{
                    flag = true;
                }
            }
        });
    }
    return flag;
}, "<%'此手机号已经存在'|L%>");
$("input[name=em_mail]").attr("em_mail", "TRUE");
$("input[name=em_mail]").attr("email", "TRUE");
jQuery.validator.addMethod("em_mail", function (value, element) {
    var length = value.length;
    var flag = false;
    var mob = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
    if ( mob.test(value) || length == 0) {
          $.ajax({
            url:'?m=enterprise&a=get_em_mail',
            data:{em_mail:value},
            success:function(res){
                if(res==2){
                    flag = false;
                }else{
                    flag = true;
                }
            }
        });
    }
    return flag;
}, "<%'此邮箱已经存在'|L%>");
*/