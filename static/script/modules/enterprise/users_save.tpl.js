var request = eval($("span.request").text());
var request = request[0];
var e_id = request.e_id;

jQuery.validator.addMethod("u_number", function (value, element) {
    var length = value.length;
    var flag = false;
    /*var mob = /^(13[0-9]|15[0|3|6|7|8|9]|18[6|8|9])\d{8}$/;*/
    var mob = /^1\d{10}$/;
    if (length == 11 && mob.test(value)) {
        flag = true;
    } else if (value >= 20000 && value <= 69999) {
        flag = true;
    }
    return flag;
}, "<%'用户号码格式错误【填写手机号或者20000 至 69999之间的数字】'|L%>");
jQuery.validator.addMethod("u_number_shell", function (value, element) {
    var flag = false;
    if (value >= 20000 && value <= 69999) {
        flag = true;
    }
    return flag;
}, "<%'用户号码格式错误【填写20000 至 69999之间的数字】'|L%>");

jQuery.validator.addMethod("u_number1", function (value, element) {
    var length = value.length;
    var flag = false;
    /*var mob = /^(13[0-9]|15[0|3|6|7|8|9]|18[6|8|9])\d{8}$/;*/
    var mob = /^1\d{10}$/;
    if (length == 11 && mob.test(value)) {
        flag = true;
    } else if (value >= parseInt(e_id+"20000") && value <= parseInt(e_id+"69999")) {
        flag = true;
    }
    return flag;
}, "<%'用户号码格式错误'|L%>");
jQuery.validator.addMethod("u_number_shell1", function (value, element) {
    var flag = false;
    if (value >= parseInt(e_id+"20000") && value <= parseInt(e_id+"69999")) {
        flag = true;
    }
    return flag;
}, "<%'用户号码格式错误'|L%>");



jQuery.validator.addMethod("u_name", function (value, element) {
    var chinese = /^([\u4e00-\u9fa5]|[a-zA-Z0-9\.])+$/;
    return this.optional(element) || (chinese.test(value));
}, "<%'名称中包含不可用字符'|L%>");


function callback(result) {
    if (result.status == 0) {
        notice("<%'上传成功'|L%>");
        $("img.face").attr("src", '?m=enterprise&a=users_face_item&pid=' + result.msg);
        $("input[name=u_pic]").val(result.msg);
        $("#fileToUpload").val("");
        $("#file_name_text").text("");
    } else {
        notice(result.msg);
    }
}

(function () {
    $("#fileToUploadT").click(function () {
        $("#fileToUpload").trigger("click");
    });
    $("#upload").click(function () {
        if ($("#fileToUpload").val() == "") {
            notice("<%'请选择文件'|L%>");
        } else {
            $("#uppic").trigger('click');
            notice("<%'上传中'|L%>");
        }
    });
})();

(function () {
    var request = eval($("span.request").text());
    var request = request[0];
    if (request.d == 'edit') {
        function utypeedit(cur) {
            $('div.sw').hide();
            if (cur == "<%'手机用户'|L%>") {
                $("input[name=u_number]").removeAttr("u_number_shell1").attr("u_number1", "TRUE");
                $('div.user').show();
            }
            if (cur == "<%'调度台用户'|L%>") {
                $("input[name=u_number]").removeAttr("u_number1").attr("u_number_shell1", "TRUE");
                $('div.shelluser').show();
                $("input[name=u_auto_config][value=0]").trigger("click");
            }
            if (cur == "<%'GVS用户'|L%>") {
                $("input[name=u_number]").removeAttr("u_number1").attr("u_number_shell1", "TRUE");
                $('div.gvsuser').show();
            }
        }
        $("#radioset>label").bind('click', function () {
            utypeedit($(this).text());
        });
        var ctypearr = Array();
        ctypearr[1] = "<%'手机用户'|L%>";
        ctypearr[2] = "<%'调度台用户'|L%>";
        ctypearr[3] = "<%'GVS用户'|L%>";
        utypeedit(ctypearr[$("#radioset").attr("value")]);
    } else {
        function utype(cur) {
            $('div.sw').hide();
            if (cur == "<%'手机用户'|L%>") {
                $("input[name=u_number]").removeAttr("u_number_shell").attr("u_number", "TRUE");
                $('div.user').show();
            }
            if (cur == "<%'调度台用户'|L%>") {
                $("input[name=u_number]").removeAttr("u_number").attr("u_number_shell", "TRUE");
                $('div.shelluser').show();
                $("input[name=u_auto_config][value=0]").trigger("click");
            }
            if (cur == "<%'GVS用户'|L%>") {
                $("input[name=u_number]").removeAttr("u_number").attr("u_number_shell", "TRUE");
                $('div.gvsuser').show();
            }
        }
        $("#radioset>label").bind('click', function () {
            utype($(this).text());
        });
        var ctypearr = Array();
        ctypearr[1] = "<%'手机用户'|L%>";
        ctypearr[2] = "<%'调度台用户'|L%>";
        ctypearr[3] = "<%'GVS用户'|L%>";
        utype(ctypearr[$("#radioset").attr("value")]);
    }
})();
(function () {
   $("input[name=u_auto_config]").bind("click", function () {
        var autoc = $(this).val();
        $("input[name=auto_config]").val(autoc);
        if (autoc == 1) {
            u_auto_config.eq(0).attr("checked","checked");
            u_auto_config.eq(1).attr("checked",false);
            $('div.auto_config').show();
        } else {
            u_auto_config.eq(1).attr("checked","checked");
            u_auto_config.eq(0).attr("checked",false);
            layer.closeAll("tips");
            $('div.auto_config').hide();
        }
    });
})();

$("input[name=u_mobile_phone]").attr("u_mobile", "TRUE");
$("input[name=u_mobile_phone]").attr("u_mobile_phone", "TRUE");
var u_number=$("input[name=u_number]").val();
/*jQuery.validator.addMethod("u_mobile", function (value, element) {
    var length = value.length;
    var flag = false;
    var mob =  /^\d{7,11}$/;
    if ( mob.test(value) || length == 0) {
          $.ajax({
            url:'?m=enterprise&a=getmob&u_number='+u_number,
            data:{u_mobile_phone:value,e_id:e_id},
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
}, "<%'此手机号已经存在'|L%>");*/
$("input[name=u_udid]").attr("udid", "TRUE");
$("input[name=u_udid]").attr("u_udid", "TRUE");
jQuery.validator.addMethod("udid", function (value, element) {
    var length = value.length;
    var flag = false;
    var mob = /^\s*$|^(?!(?:\d+|[a-zA-Z]+)$)[\da-zA-Z]{40}$/i;
    if ((length == 0 && mob.test(value)) || (length == 40 && mob.test(value))) {
         $.ajax({
            url:'?m=enterprise&a=getudid&u_number='+u_number,
            data:{u_udid:value},
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
}, "<%'此UDID已存在'|L%>");
$("input[name=u_imsi]").attr("imsi", "TRUE");
$("input[name=u_imsi]").attr("u_imsi", "TRUE");
/*jQuery.validator.addMethod("imsi", function (value, element) {
    var length = value.length;
    var flag = false;
    var mob = /^\s*$|^[0-9]{15}$/i;
    if ((length == 0 && mob.test(value)) || (length == 15 && mob.test(value))) {
         $.ajax({
            url:'?m=enterprise&a=getimsi&u_number='+u_number,
            data:{u_imsi:value,e_id:e_id},
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
}, "<%'此IMSI已存在'|L%>");*/
//$("input[name=u_imei]").attr("imei", "TRUE");
$("input[name=u_imei]").attr("u_imei", "TRUE");
//jQuery.validator.addMethod("imei", function (value, element) {
//    var length = value.length;
//    var flag = false;
//    var mob = /^\s*$|^[0-9]{15}$/i;
//    if ((length == 0 && mob.test(value)) || (length == 15 && mob.test(value))) {
//        flag = true;
//    }
//    return flag;
//}, "<%'此IMEI已存在'|L%>");
//$("input[name=u_iccid]").attr("iccid", "TRUE");
$("input[name=u_iccid]").attr("u_iccid", "TRUE");
/*jQuery.validator.addMethod("iccid", function (value, element) {
    var length = value.length;
    var flag = false;

    var mob = /^\s*$|^\d{19}$|^\d{20}$/i;
    if (mob.test(value)) {
         $.ajax({
            url:'?m=enterprise&a=geticcid&u_number='+u_number,
            data:{u_iccid:value,e_id:e_id},
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
}, "<%'此ICCID已存在'|L%>");*/
//-----------------
$("input[name=u_mac]").attr("mac", "TRUE");
$("input[name=u_mac]").attr("u_mac", "TRUE");
jQuery.validator.addMethod("mac", function (value, element) {
    var length = value.length;
    var flag = false;
    var mob = /^\s*$|[A-F\d]{2}[A-F\d]{2}[A-F\d]{2}[A-F\d]{2}[A-F\d]{2}[A-F\d]{2}/i;
    if (length == 12 && mob.test(value) || length == 0) {
        $.ajax({
            url:'?m=enterprise&a=getmac&u_number='+u_number,
            data:{u_mac:value},
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
}, "<%'此MAC已存在'|L%>");

function confirm2(notice) {
    var id = "notice_" + new Date().getTime();
    $("div.notice_mask").remove();
    $notice_mask = $("<div class='notice_mask sync '></div>");
    $notice_content = $("<div class='notice_content animated fadeIn'></div>");
    $notice = $("<div class='notice'></div>");
    $notice_mask.attr("id", id);
    $notice.html(notice);
    $notice_content.append($notice);
    $toolbar1 = $("<div style='float:right' class='toolbar'><a class='button cancel notransition'><%'取消'|L%></a></div>");
    $toolbar = $("<div  class='toolbar'><a class='button determine notransition'><%'确定'|L%></a></div>");
    $notice_content.append($toolbar1);
    $notice_content.append($toolbar);
    $notice_mask.append($notice_content);
    $("body").append($notice_mask);
    $("#" + id + " div.notice_content").draggable({containment: "parent"});


    $("#" + id + " a.determine").bind("click", function () {
        con = $("a.determine").html();
        $("#" + id).remove();
        saveGVSuser();

    });
    $("#" + id + " a.cancel").bind("click", function () {
        con = $("a.cancel").html();
        $("#" + id).remove();
    });

    return con;
}
/**
 * Comment
 */
function saveGVSuser() {
    var data = $("#form").serialize();
    $.ajax({
        url: '?modules=enterprise&action=saveGVS&e_id=' + e_id,
        data: data,
        dataType: 'json',
        success: function (result) {
            notice(result.msg,$("a.ajaxpost_u").attr("goto"));
        }
    });
}

function getFiles(obj) {
    document.fileupdate.path.value = obj.value;
}
/*
$("input[name=u_mobile_phone]").on("input",function(){
    var mob=$("input[name=u_mobile_phone]").val();
    var mobile = /^1\d{10}$/;
    var length = mob.length;
    if(length == 11 && mobile.test(mob)){
           $.ajax({
            url:'?m=enterprise&a=getmob',
            data:{u_mobile_phone:mob},
            success:function(res){
                if(res=="nnn"){
                    notice("此手机号已经存在");
                }
            }
        });
    }
});
*/



/**
 * 是否绑定手机
 * 
 * 
 */

var u_bind_phone=$("input[name=u_bind_phone]:checked").val();
if(u_bind_phone==1){
        $("input[name=u_terminal_number]").attr("readonly","readonly");
        $("input[name=u_iccid]").attr("required", "true");
        $("input[name=u_imei]").attr("required", "true");
        $("input[name=u_iccid]").focus();
        $("input[name=u_imei]").focus();
}else{
        $("input[name=u_iccid]").removeAttr("required");
        $("input[name=u_imei]").removeAttr("required");
}
$("input[name=u_bind_phone]").bind('change',function(){
    u_bind_phone=$("input[name=u_bind_phone]:checked").val();
    if(u_bind_phone==1){
        $("input[name=u_terminal_number]").attr("readonly","readonly");
        $("input[name=u_iccid]").attr("required", "true");
        $("input[name=u_imei]").attr("required", "true");
        $("input[name=u_iccid]").focus();
        $("input[name=u_imei]").focus();
        valid();
    }else{
        if( $("select[name=u_terminal_type]").val()==""){
            $("input[name=u_terminal_number]").removeAttr("readonly");
            $("input[name=u_imei]").removeAttr("required");
        }
       $("input[name=u_iccid]").removeAttr("required");
        valid();

    }
});
/**
 * 计算选中功能价格
 */

$("#selectprice option:selected").each(function(){
        var price=0; 
        price += parseFloat($(this).attr("title")); 
        $("#price").html(price);
});
$("#selectprice").change(function(){
     var price=0;
    $("#selectprice option:selected").each(function(){
        price += parseFloat($(this).attr("title")); 
        $("#price").html(price);
    });
});
/**
 * 填写号码不能为自身号码
 */

jQuery.validator.addMethod("u_alarm_inform_svp_num", function (value, element) {
    var flag = true;
    var length=value.length;
    if(length==11){
    if($("input[name=do]").val()=="edit"){
        if (value ==$("input[name=u_number]").val()&&value!="") {
            flag = false;
        }
    }else{
        if (value ==(e_id+$("input[name=u_number]").val())&&value!="") {
            flag = false;
        }
    }
}
    return flag;
}, "<%'所填号码不能是自己'|L%>");
/**
 * 填写号码不能为自身号码
 */

jQuery.validator.addMethod("check_number", function (value, element) {
    var flag = false;
    var length=value.length;
    $.ajax({
        url:'?modules=enterprise&action=check_number',
        data:{e_id:e_id,u_number:value},
        success:function(res){
            if(res=="1"&&length==11||value==""){
                flag=true;
            }
        }
    });
    return flag;
}, "<%'该号码不存在'|L%>");
/*
    var product=$("div.autocheck").attr("value");
    product =eval('(' + product + ')');
    for(var i=0;i<product.length;i++){
        $("div.autocheck label.autocheck div input").each(function () {
                    var val = $(this).attr("value");
                    if(val==product[i]){
                    $(this).attr("checked", "checked");
                    }
                //$(this).buttonset();
            });
    }*/
var globals="selected";
$("select[name=u_alarm_inform_svp_num] option").each(function(){
    if($(this).val()==$("select[name=u_alarm_inform_svp_num]").val()){
        globals=$(this).val();
    }
});
if(globals=="selected"){
    //$("select[name=u_alarm_inform_svp_num]").val()="@";
    $("select[name=u_alarm_inform_svp_num] option").each(function(){
    if($(this).val()=="@"){
        $(this).attr("selected","selected");
        $("input[name=u_alarm_inform_svp_num]").removeClass('none');
    }
});
}else{
    $("input[name=u_alarm_inform_svp_num]").val(globals);
}
if($("input[name=do]").val()=="edit"){
var u_product_id = $("select[name=u_product_id]").val();
var u_product_id_new = $("input[name=u_product_id_new]").val();
    if(u_product_id_new!=""){
        $("input[name=isused]").attr("checked","checked");
        $("div.show_info").addClass("none");
    }else{
        $("input[name=isused]").removeAttr("checked");
         $("div.show_info").addClass("none");
    }
    $("input[name=isused]").on("click",function(){
        if($("input[name=isused]").is(":checked")){
                if(u_product_id_new!=""){
                    $("div.show_info").removeClass("none");
                }
                u_product_id_new=$("select[name=u_product_id] option:selected").val();
                $("input[name=u_product_id_new]").val(u_product_id_new);
                //$("select[name=u_product_id]").val(u_product_id);
        }else{
                $("div.show_info").addClass("none");
                $("input[name=u_product_id_new]").val("");
        }
    });
$("select[name=u_product_id]").on("change",function(){
    var select_val=$(this).val();
    if(select_val==u_product_id){
        $("div.show_info").addClass("none");
    }else{
        if(!$("input[name=isused]").is(":checked")){
            $("input[name=u_product_id_new]").val("");
    }else{
      var index=layer.confirm("产品被修改，次月生效",{btn: ["<%'确定'|L%>", "<%'取消'|L%>"],title:"<%'产品修改'|L%>"},function(){
            $.ajax({
                url:"?m=product&a=get_product_name",
                data:{u_product_id_new:select_val},
                success:function(res){
                    if(res=="noselected"){
                        $("a.show_product_new").html("<%'暂无选择'|L%>");
                        $("input[name=u_product_id_new]").val("noselected");
                        $("div.show_info").removeClass("none");
                        $("span.product_new").removeClass("none");
                        $("select[name=u_product_id]").val(u_product_id);
                        //$("input[name=u_product_id_new]").val(select_val);
                    }else{
                        $("a.show_product_new").html(res);
                        $("div.show_info").removeClass("none");
                        $("span.product_new").removeClass("none");
                        $("select[name=u_product_id]").val(u_product_id);
                        $("input[name=u_product_id_new]").val(select_val);
                    }
                }
            });
                        layer.close(index);
       }); 
                    $("select[name=u_product_id]").val(u_product_id);
    }
    }

});

$("a.delete").on('click',function(){
    $("div.show_info").addClass("none");
    $("span.product_new").addClass("none");
    $("input[name=u_product_id_new]").val("");
});
 if(u_product_id_new==""){
        $("div.show_info").addClass("none");
        $("span.product_new").addClass("none");
        }else{
            $.ajax({
                url:"?m=product&a=get_product_name",
                data:{u_product_id_new:u_product_id_new},
                success:function(res){
                if(res=="noselected"){
                    $("a.show_product_new").html("<%'暂无选择'|L%>");
                    //$("input[name=u_product_id_new]").val("noselected");
                    $("div.show_info").removeClass("none");
                    $("span.product_new").removeClass("none");
                    $("select[name=u_product_id]").val(u_product_id);
                }else{
                    $("a.show_product_new").html(res);
                    $("div.show_info").removeClass("none");
                    $("span.product_new").removeClass("none");
                    $("select[name=u_product_id]").val(u_product_id);
                }
            }
        });
    }
}
/**
 * 选择终端类型 来判断是否打开自动登录开关
 */
$("select[name=u_terminal_type]").on("change",function(){
    var u_terminal_type=$("select[name=u_terminal_type]").val();
    if(u_terminal_type!=""){
        $("input[name=u_terminal_number]").attr("readonly","readonly");
        $("input[name=md_type]").val(u_terminal_type);
        $("input[name=u_auto_config][value=1]").trigger("click");
        $("input[name=u_auto_config][value=0]").attr("disabled","");
        $("input[name=u_imei]").blur();
        check_imei();
        $("input[name=u_imei]").attr("required","true");
        $("input[name=u_imei]").focus();
        valid();
        //valid();
    }else{
        if($("input[name=u_bind_phone]:checked").val()=="0"){
            $("input[name=u_terminal_number]").removeAttr("readonly");
        }
        $("input[name=md_type]").val("");
        $("input[name=u_imei]").removeAttr("required");
        $("input[name=u_auto_config][value=0]").removeAttr("disabled","");
        $("input[name=u_imei]").blur();
        //$("input[name=u_auto_config][value=0]").prop("checked","checked");
        $("input[name=u_auto_config][value=0]").trigger("click");
        layer.closeAll("tips");
    }
});
if($("input[name=md_type]").val()!=""){
     $("input[name=u_auto_config][value=1]").trigger("click");
        $("input[name=u_auto_config][value=0]").attr("disabled","");
        $("input[name=u_imei]").attr("required","true");
        $("input[name=u_imei]").focus();
        valid();
}
/**
 * 验证imei 是否符合规则
 * @returns {undefined}
 */
function check_imei(){
            var u_imei=$("input[name=u_imei]").val();
            var md_type=$("input[name=md_type]").val();
            var meid = arg_meid.val();
            if(md_type!=""){
                var u_terminal_type="&u_terminal_type="+md_type;
                $("input[name=u_imei]").attr("required","true");
            }else{
                var u_terminal_type="";
            }
            if(md_type!=""&&u_imei==""){
                $("input[name=u_terminal_number]").val("");
            }
                $.ajax({
                    url:'?m=enterprise&a=getimei&e_id='+$("input[name=e_id]").val()+'&u_number='+$("input[name=u_number]").val()+u_terminal_type,
                    data:{u_imei:$("input[name=u_imei]").val(),e_id:e_id,u_bind_phone:$("input[name=u_bind_phone]:checked").val()},
                    dataType:'json',
                    success:function(res){
                        if(res.status==2){
                            $("input[name=imei_flag]").val("Error");
                            $("input[name=imei_stat]").val(res.status);
                            $("input[name=u_imei]").attr("aria-required","true");
                            $("input[name=u_imei]").attr("aria-invalid","true");
                            layer.tips("<%'此IMEI已存在'|L%>",$("input[name=u_imei]"),{
                                tips:[1, '#A83A3A'],
                                time:600000
                            });
                            $("input[name=u_imei]").focus();
                            
                        }else if(res.status==1){
                            $("input[name=imei_flag]").val("OK");
                            $("input[name=u_imei]").removeClass("error");
                            $("input[name=imei_stat]").val(res.status);
                            layer.closeAll('tips');
                        }else if(res.status==3){
                            $("input[name=imei_flag]").val("Error");
                            $("input[name=imei_stat]").val(res.status);
                            $("input[name=u_imei]").addClass("error");
                            $("input[name=u_imei]").attr("aria-required","true");
                            $("input[name=u_imei]").attr("aria-invalid","true");
                            $("input[name=u_imei]").focus();
                            layer.tips("<%'IMEI已绑定， 请确认后重新输入'|L%>",$("input[name=u_imei]"),{
                                tips:[1, '#A83A3A'],
                                time:600000
                            });
                            exit();
                        }else if(res.status==4){
                            $("input[name=imei_flag]").val("Error");
                            $("input[name=imei_stat]").val(res.status);
                            $("input[name=u_imei]").addClass("error");
                            $("input[name=u_imei]").attr("aria-required","true");
                            $("input[name=u_imei]").attr("aria-invalid","true");
                            $("input[name=u_imei]").focus();
                            layer.tips("<%'所填IMEI不正确，请检查所选终端类型与IMEI后重新输入'|L%>"+",code 102",$("input[name=u_imei]"),{
                                tips:[1, '#A83A3A'],
                                time:600000
                            });
                            exit();
                        }else if(res.status==5){
                            $("input[name=imei_stat]").val(res.status);
                            $("input[name=u_imei]").removeClass("error");
                            $("input[name=imei_flag]").val("OK");
                            //获取u_meid 不为空时判断 imei与meid 是否匹配
                            if(meid!=''){
                                if(res.res.md_meid!=meid){
                                    $("input[name=imei_flag]").val("Error");
                                    $("input[name=u_imei]").addClass("error");
                                    $("input[name=u_imei]").attr("aria-required","true");
                                    $("input[name=u_imei]").attr("aria-invalid","true");
                                    layer.tips("<%'所填IMEI与MEID不匹配，请检查后重新填写'|L%>",arg_imei,{
                                        tips:[1, '#A83A3A'],
                                        time:600000
                                    });
                                    exit();
                                }else{
                                    $("input[name=u_imei]").removeClass("error");
                                    $("input[name=imei_flag]").val("OK");
                                }
                            }else{
                                arg_meid.val(res.res.md_meid);
                            }
                            layer.closeAll('tips');
                        }else if(res.status=="isnull"){
                            $("input[name=imei_stat]").val(res.status);
                            $("input[name=u_imei]").removeClass("error");
                            $("input[name=imei_flag]").val("OK");
                            layer.closeAll('tips');
                        }else if(res.status=="issame"){
                            $("input[name=imei_stat]").val(res.status);
                            $("input[name=u_imei]").removeClass("error");
                            $("input[name=imei_flag]").val("OK");
                            //获取u_meid 不为空时判断 imei与meid 是否匹配
                            if(meid!=''){
                                if(res.res.md_meid!=meid){
                                    $("input[name=imei_flag]").val("Error");
                                    $("input[name=u_imei]").addClass("error");
                                    $("input[name=u_imei]").attr("aria-required","true");
                                    $("input[name=u_imei]").attr("aria-invalid","true");
                                    layer.tips("<%'所填IMEI与MEID不匹配，请检查后重新填写'|L%>",arg_imei,{
                                        tips:[1, '#A83A3A'],
                                        time:600000
                                    });
                                    exit();
                                }else{
                                    $("input[name=u_imei]").removeClass("error");
                                    $("input[name=imei_flag]").val("OK");
                                }
                            }else{
                                arg_meid.val(res.res.md_meid);
                            }
                            layer.closeAll('tips');
                        }else if(res.status==7){
                            $("input[name=imei_flag]").val("Error");
                            $("input[name=imei_stat]").val(res.status);
                            $("input[name=u_imei]").addClass("error");
                            $("input[name=u_imei]").attr("aria-required","true");
                            $("input[name=u_imei]").attr("aria-invalid","true");
                            $("input[name=u_imei]").focus();
                            layer.tips("<%'所填IMEI不正确，请检查所选终端类型与IMEI后重新输入'|L%>"+",code 103",$("input[name=u_imei]"),{
                                tips:[1, '#A83A3A'],
                                time:600000
                            });
                            exit();
                        }else if(res.status==8){
                            $("input[name=imei_flag]").val("Error");
                            $("input[name=imei_stat]").val(res.status);
                            $("input[name=u_imei]").addClass("error");
                            $("input[name=u_imei]").attr("aria-required","true");
                            $("input[name=u_imei]").attr("aria-invalid","true");
                            $("input[name=u_imei]").focus();
                            layer.tips("<%'所填IMEI不正确，请检查所选终端类型与IMEI后重新输入'|L%>"+",code 101",$("input[name=u_imei]"),{
                                tips:[1, '#A83A3A'],
                                time:600000
                            });
                            exit();
                        }else{
                            $("input[name=imei_stat]").val(res.status);
                            $("input[name=u_imei]").addClass("error");
                            $("input[name=imei_flag]").val("Error");
                        }
                    }
                });
         if(md_type!=""){
             $.ajax({
                         url:"?m=terminal&a=getById_foruser",
                         data:{md_imei:u_imei},
                         success:function(result){
                             //var result=eval(res);
                             var res = eval("("+result+")");
                            $("input[name=u_terminal_number]").val(res.md_serial_number);
                            $("input[name=u_terminal_number]").blur();
                         }
                    });
//            if($("input[name=u_terminal_number]").val()==""&&u_imei!=""){
//                     $.ajax({
//                       url:"?m=terminal&a=getById_foruser",
//                       data:{md_imei:u_imei},
//                       success:function(result){
//                           //var result=eval(res);
//                           var res = eval("("+result+")");
//                            if($("input[name=u_terminal_number]").val()!=res.md_serial_number&&res.md_serial_number!=""){
//                                $("input[name=imei_flag]").val("Error");
//                                $("input[name=u_terminal_number]").removeClass("valid");
//                                $("input[name=u_terminal_number]").addClass("error");
//                                $("input[name=u_terminal_number]").attr("aria-required","true");
//                                $("input[name=u_terminal_number]").attr("aria-invalid","true");
//                               layer.tips("<%'终端序列号与IMEI不符'|L%>",$("input[name=u_terminal_number]"),{
//                                       tips:[1, '#A83A3A'],
//                                       time:600000
//                                   });
//                               exit();
//                           }
//                       }
//                   });
//                }else{
//                     
//                }
            }
        }
/**
 * 失去焦点 验证imei 是否符合规则
 * @returns {undefined}
 */
function check_imei_blur(){
            var u_imei=$("input[name=u_imei]").val();
            var md_type=$("input[name=md_type]").val();
            var meid = arg_meid.val();
            if(md_type!=""){
                var u_terminal_type="&u_terminal_type="+md_type;
                $("input[name=u_imei]").attr("required","true");
                valid();
            }else{
                var u_terminal_type="";
            }
            if(md_type!=""&&u_imei==""){
                $("input[name=u_terminal_number]").val("");
            }
                $.ajax({
                    url:'?m=enterprise&a=getimei&e_id='+$("input[name=e_id]").val()+'&u_number='+$("input[name=u_number]").val()+u_terminal_type,
                    data:{u_imei:$("input[name=u_imei]").val(),e_id:e_id,u_bind_phone:$("input[name=u_bind_phone]:checked").val()},
                    dataType:'json',
                    success:function(res){
                        if(res.status==2){
                            $("input[name=imei_flag]").val("Error");
                            $("input[name=imei_stat]").val(res.status);
                            $("input[name=u_imei]").attr("aria-required","true");
                            $("input[name=u_imei]").attr("aria-invalid","true");
                            layer.tips("<%'此IMEI已存在'|L%>",$("input[name=u_imei]"),{
                                tips:[1, '#A83A3A'],
                                time:600000
                            });
                            
                        }else if(res.status==1){
                            $("input[name=imei_flag]").val("OK");
                            $("input[name=u_imei]").removeClass("error");
                            $("input[name=imei_stat]").val(res.status);
                            layer.closeAll('tips');
                        }else if(res.status==3){
                            $("input[name=imei_flag]").val("Error");
                            $("input[name=imei_stat]").val(res.status);
                            $("input[name=u_imei]").addClass("error");
                            $("input[name=u_imei]").attr("aria-required","true");
                            $("input[name=u_imei]").attr("aria-invalid","true");
                            layer.tips("<%'IMEI已绑定， 请确认后重新输入'|L%>",$("input[name=u_imei]"),{
                                tips:[1, '#A83A3A'],
                                time:600000
                            });
                            exit();
                        }else if(res.status==4){
                            $("input[name=imei_flag]").val("Error");
                            $("input[name=imei_stat]").val(res.status);
                            $("input[name=u_imei]").addClass("error");
                            $("input[name=u_imei]").attr("aria-required","true");
                            $("input[name=u_imei]").attr("aria-invalid","true");
                            layer.tips("<%'所填IMEI不正确，请检查所选终端类型与IMEI后重新输入'|L%>"+",code 102",$("input[name=u_imei]"),{
                                tips:[1, '#A83A3A'],
                                time:600000
                            });
                            exit();
                        }else if(res.status==5){
                            $("input[name=imei_stat]").val(res.status);
                            $("input[name=u_imei]").removeClass("error");
                            //获取u_meid 不为空时判断 imei与meid 是否匹配
                            if(meid!=''){
                                if(res.res.md_meid!=meid){
                                    $("input[name=imei_flag]").val("Error");
                                    $("input[name=u_imei]").addClass("error");
                                    $("input[name=u_imei]").attr("aria-required","true");
                                    $("input[name=u_imei]").attr("aria-invalid","true");
                                    layer.tips("<%'所填IMEI与MEID不匹配，请检查后重新填写'|L%>",arg_imei,{
                                        tips:[1, '#A83A3A'],
                                        time:600000
                                    });
                                    exit();
                                }else{
                                    $("input[name=u_imei]").removeClass("error");
                                    $("input[name=imei_flag]").val("OK");
                                }
                            }else{
                                arg_meid.val(res.res.md_meid);
                            }
                            layer.closeAll('tips');
                        }else if(res.status=="isnull"){
                            $("input[name=imei_stat]").val(res.status);
                            $("input[name=u_imei]").removeClass("error");
                            $("input[name=imei_flag]").val("OK");
                            layer.closeAll('tips');
                        }else if(res.status=="issame"){
                            $("input[name=imei_stat]").val(res.status);
                            $("input[name=u_imei]").removeClass("error");
                            //获取u_meid 不为空时判断 imei与meid 是否匹配
                            if(meid!=''){
                                if(res.res.md_meid!=meid){
                                    $("input[name=imei_flag]").val("Error");
                                    $("input[name=u_imei]").addClass("error");
                                    $("input[name=u_imei]").attr("aria-required","true");
                                    $("input[name=u_imei]").attr("aria-invalid","true");
                                    layer.tips("<%'所填IMEI与MEID不匹配，请检查后重新填写'|L%>",arg_imei,{
                                        tips:[1, '#A83A3A'],
                                        time:600000
                                    });
                                    exit();
                                }else{
                                    $("input[name=u_imei]").removeClass("error");
                                    $("input[name=imei_flag]").val("OK");
                                }
                            }else{
                                arg_meid.val(res.res.md_meid);
                            }
                            layer.closeAll('tips');
                        }else if(res.status==7){
                            $("input[name=imei_flag]").val("Error");
                            $("input[name=imei_stat]").val(res.status);
                            $("input[name=u_imei]").addClass("error");
                            $("input[name=u_imei]").attr("aria-required","true");
                            $("input[name=u_imei]").attr("aria-invalid","true");
                            layer.tips("<%'所填IMEI不正确，请检查所选终端类型与IMEI后重新输入'|L%>"+",code 103",$("input[name=u_imei]"),{
                                tips:[1, '#A83A3A'],
                                time:600000
                            });
                            exit();
                        }else if(res.status==8){
                            $("input[name=imei_flag]").val("Error");
                            $("input[name=imei_stat]").val(res.status);
                            $("input[name=u_imei]").addClass("error");
                            $("input[name=u_imei]").attr("aria-required","true");
                            $("input[name=u_imei]").attr("aria-invalid","true");
                            layer.tips("<%'所填IMEI不正确，请检查所选终端类型与IMEI后重新输入'|L%>"+",code 101",$("input[name=u_imei]"),{
                                tips:[1, '#A83A3A'],
                                time:600000
                            });
                            exit();
                        }else{
                            $("input[name=imei_stat]").val(res.status);
                            $("input[name=u_imei]").addClass("error");
                            $("input[name=imei_flag]").val("Error");
                        }
                    }
                });
        if(md_type!=""){
             $.ajax({
                         url:"?m=terminal&a=getById_foruser",
                         data:{md_imei:u_imei},
                         success:function(result){
                             //var result=eval(res);
                             var res = eval("("+result+")");
                            $("input[name=u_terminal_number]").val(res.md_serial_number);
                            $("input[name=u_terminal_number]").blur();
        }
                    });
//            if($("input[name=u_terminal_number]").val()==""&&u_imei!=""){
//                     $.ajax({
//                       url:"?m=terminal&a=getById_foruser",
//                       data:{md_imei:u_imei},
//                       success:function(result){
//                           //var result=eval(res);
//                           var res = eval("("+result+")");
//                            if($("input[name=u_terminal_number]").val()!=res.md_serial_number&&res.md_serial_number!=""){
//                                $("input[name=imei_flag]").val("Error");
//                                $("input[name=u_terminal_number]").removeClass("valid");
//                                $("input[name=u_terminal_number]").addClass("error");
//                                $("input[name=u_terminal_number]").attr("aria-required","true");
//                                $("input[name=u_terminal_number]").attr("aria-invalid","true");
//                               layer.tips("<%'终端序列号与IMEI不符'|L%>",$("input[name=u_terminal_number]"),{
//                                       tips:[1, '#A83A3A'],
//                                       time:600000
//                                   });
//                               exit();
//                           }
//                       }
//                   });
//                }else{
//                     
//                }
            }
}

/**
 * 失去焦点 验证meid 是否符合规则
 * @returns {undefined}
 */
function check_meid_blur(){
    var u_meid=arg_meid.val();
    var md_type=$("input[name=md_type]").val();
    var imei = arg_imei.val();
    //当meid 为空时 则不进行下面的判断
    if(u_meid==''){
        return false;
    }

    //当meid不为空时进行对应的判断
    if(md_type!=""){
        var u_terminal_type="&u_terminal_type="+md_type;
    }else{
        var u_terminal_type="";
    }
    if(md_type!=""&&u_meid==""){
        $("input[name=u_terminal_number]").val("");
    }
    $.ajax({
        url:'?m=enterprise&a=getmeid&e_id='+$("input[name=e_id]").val()+'&u_number='+$("input[name=u_number]").val()+u_terminal_type,
        data:{u_meid:arg_meid.val(),e_id:e_id,u_bind_phone:$("input[name=u_bind_phone]:checked").val()},
        dataType:'json',
        success:function(res){
            if(res.status==2){
                $("input[name=meid_flag]").val("Error");
                $("input[name=meid_stat]").val(res.status);
                $("input[name=u_meid]").attr("aria-required","true");
                $("input[name=u_meid]").attr("aria-invalid","true");
                layer.tips("<%'此MEID已存在'|L%>",arg_meid,{
                    tips:[1, '#A83A3A'],
                    time:600000
                });
                
            }else if(res.status==1){
                $("input[name=meid_flag]").val("OK");
                $("input[name=u_meid]").removeClass("error");
                $("input[name=meid_stat]").val(res.status);
                layer.closeAll('tips');
            }else if(res.status==3){
                $("input[name=meid_flag]").val("Error");
                $("input[name=meid_stat]").val(res.status);
                $("input[name=u_meid]").addClass("error");
                $("input[name=u_meid]").attr("aria-required","true");
                $("input[name=u_meid]").attr("aria-invalid","true");
                layer.tips("<%'MEID已绑定， 请确认后重新输入'|L%>",arg_meid,{
                    tips:[1, '#A83A3A'],
                    time:600000
                });
                exit();
            }else if(res.status==4){
                $("input[name=meid_flag]").val("Error");
                $("input[name=meid_stat]").val(res.status);
                $("input[name=u_meid]").addClass("error");
                $("input[name=u_meid]").attr("aria-required","true");
                $("input[name=u_meid]").attr("aria-invalid","true");
                layer.tips("<%'所填MEID不正确，请检查所选终端类型与MEID后重新输入'|L%>"+",code 102",arg_meid,{
                    tips:[1, '#A83A3A'],
                    time:600000
                });
                exit();
            }else if(res.status==5){
                $("input[name=imei_stat]").val(res.status);
                $("input[name=u_meid]").removeClass("error");
                //获取u_meid 不为空时判断 imei与meid 是否匹配
                if(imei!=''){
                    if(res.res.md_imei!=imei){
                        $("input[name=meid_flag]").val("Error");
                        $("input[name=u_meid]").addClass("error");
                        $("input[name=u_meid]").attr("aria-required","true");
                        $("input[name=u_meid]").attr("aria-invalid","true");
                        layer.tips("<%'所填MEID与IMEI不匹配，请检查后重新填写'|L%>",arg_meid,{
                            tips:[1, '#A83A3A'],
                            time:600000
                        });
                        exit();
                    }else{
                        $("input[name=u_meid]").removeClass("error");
                        $("input[name=meid_flag]").val("OK");
                    }
                }else{
                    //当imei没填时 如果库中imei为空则补填为meid+0,库中有则填入库中的
                    if(res.res.md_imei!=''){
                        arg_imei.val(res.res.md_imei);
                    }else{
                        arg_imei.val(u_meid+'0');
                    }
                    //$("input[name=u_imei]").removeClass("error");
                    $("input[name=meid_flag]").val("OK");
                }
                layer.closeAll('tips');
            }else if(res=="isnull"){
                $("input[name=imei_stat]").val(res);
                $("input[name=u_meid]").removeClass("error");
                $("input[name=meid_flag]").val("OK");
                layer.closeAll('tips');
            }else if(res.status=="issame"){
                $("input[name=imei_stat]").val(res.status);
                $("input[name=u_meid]").removeClass("error");
                //获取u_meid 不为空时判断 imei与meid 是否匹配
                if(imei!=''){
                    if(res.res.md_imei!=imei){
                        $("input[name=meid_flag]").val("Error");
                        $("input[name=u_meid]").addClass("error");
                        $("input[name=u_meid]").attr("aria-required","true");
                        $("input[name=u_meid]").attr("aria-invalid","true");
                        layer.tips("<%'所填MEID与IMEI不匹配，请检查后重新填写'|L%>",arg_meid,{
                            tips:[1, '#A83A3A'],
                            time:600000
                        });
                        exit();
                    }else{
                        $("input[name=u_meid]").removeClass("error");
                        $("input[name=meid_flag]").val("OK");
                    }
                }else{
                    //当imei没填时 如果库中imei为空则补填为meid+0,库中有则填入库中的
                    if(res.res.md_imei!=''){
                        arg_imei.val(res.res.md_imei);
                    }else{
                        arg_imei.val(u_meid+'0');
                    }
                    //$("input[name=u_imei]").removeClass("error");
                    $("input[name=meid_flag]").val("OK");
                }
                layer.closeAll('tips');
            }else if(res.status==7){
                $("input[name=meid_flag]").val("Error");
                $("input[name=imei_stat]").val(res.status);
                $("input[name=u_meid]").addClass("error");
                $("input[name=u_meid]").attr("aria-required","true");
                $("input[name=u_meid]").attr("aria-invalid","true");
                layer.tips("<%'所填MEID不正确，请检查所选终端类型与MEID后重新输入'|L%>"+",code 103",$("input[name=u_meid]"),{
                    tips:[1, '#A83A3A'],
                    time:600000
                });
                exit();
            }else if(res.status==8){
                $("input[name=meid_flag]").val("Error");
                $("input[name=imei_stat]").val(res.status);
                $("input[name=u_meid]").addClass("error");
                $("input[name=u_meid]").attr("aria-required","true");
                $("input[name=u_meid]").attr("aria-invalid","true");
                layer.tips("<%'所填MEID不正确，请检查所选终端类型与MEID后重新输入'|L%>"+",code 101",arg_meid,{
                    tips:[1, '#A83A3A'],
                    time:600000
                });
                exit();
            }else{
                $("input[name=imei_stat]").val(res.status);
                $("input[name=u_meid]").addClass("error");
                $("input[name=meid_flag]").val("Error");
            }
        }
    });
    
    //当imei为空时 则通过meid 查询终端的terminal_number
    if(imei==''){
        if(md_type!=""){
             $.ajax({
                url:"?m=terminal&a=getById_foruser_meid",
                data:{md_meid:u_meid},
                success:function(result){
                    var res = eval("("+result+")");
                    $("input[name=u_terminal_number]").val(res.md_serial_number);
                    $("input[name=u_terminal_number]").blur();
                }
            });
        }
    }
    //alert($("input[name=meid_flag]").val());
}

/**
 * 验证meid 是否符合规则
 * @returns {undefined}
 */
function check_meid(){
    var u_meid=arg_meid.val();
    var md_type=$("input[name=md_type]").val();
    var imei = arg_imei.val();
    //当meid 为空时 则不进行下面的判断
    if(u_meid==''){
        return false;
    }

    //当meid不为空时进行对应的判断
    if(md_type!=""){
        var u_terminal_type="&u_terminal_type="+md_type;
    }else{
        var u_terminal_type="";
    }
    if(md_type!=""&&u_meid==""){
        $("input[name=u_terminal_number]").val("");
    }
    $.ajax({
        url:'?m=enterprise&a=getmeid&e_id='+$("input[name=e_id]").val()+'&u_number='+$("input[name=u_number]").val()+u_terminal_type,
        data:{u_meid:arg_meid.val(),e_id:e_id,u_bind_phone:$("input[name=u_bind_phone]:checked").val()},
        dataType:'json',
        success:function(res){
            if(res.status==2){
                $("input[name=meid_flag]").val("Error");
                $("input[name=meid_stat]").val(res.status);
                $("input[name=u_meid]").attr("aria-required","true");
                $("input[name=u_meid]").attr("aria-invalid","true");
                layer.tips("<%'此MEID已存在'|L%>",arg_meid,{
                    tips:[1, '#A83A3A'],
                    time:600000
                });
                $("input[name=u_meid]").focus();

            }else if(res.status==1){
                $("input[name=meid_flag]").val("OK");
                $("input[name=u_meid]").removeClass("error");
                $("input[name=meid_stat]").val(res.status);
                layer.closeAll('tips');
            }else if(res.status==3){
                $("input[name=meid_flag]").val("Error");
                $("input[name=meid_stat]").val(res.status);
                $("input[name=u_meid]").addClass("error");
                $("input[name=u_meid]").attr("aria-required","true");
                $("input[name=u_meid]").attr("aria-invalid","true");
                $("input[name=u_meid]").focus();
                layer.tips("<%'MEID已绑定， 请确认后重新输入'|L%>",arg_meid,{
                    tips:[1, '#A83A3A'],
                    time:600000
                });
                exit();
            }else if(res.status==4){
                $("input[name=meid_flag]").val("Error");
                $("input[name=meid_stat]").val(res.status);
                $("input[name=u_meid]").addClass("error");
                $("input[name=u_meid]").attr("aria-required","true");
                $("input[name=u_meid]").attr("aria-invalid","true");
                $("input[name=u_meid]").focus();
                layer.tips("<%'所填MEID不正确，请检查所选终端类型与MEID后重新输入'|L%>"+",code 102",arg_meid,{
                    tips:[1, '#A83A3A'],
                    time:600000
                });
                exit();
            }else if(res.status==5){
                $("input[name=imei_stat]").val(res.status);
                $("input[name=u_meid]").removeClass("error");
                $("input[name=meid_flag]").val("OK");
                //获取u_meid 不为空时判断 imei与meid 是否匹配
                if(imei!=''){
                    if(res.res.md_imei!=imei){
                        $("input[name=meid_flag]").val("Error");
                        $("input[name=u_meid]").addClass("error");
                        $("input[name=u_meid]").attr("aria-required","true");
                        $("input[name=u_meid]").attr("aria-invalid","true");
                        layer.tips("<%'所填MEID与IMEI不匹配，请检查后重新填写'|L%>",arg_meid,{
                            tips:[1, '#A83A3A'],
                            time:600000
                        });
                        exit();
                    }else{
                        $("input[name=u_meid]").removeClass("error");
                        $("input[name=meid_flag]").val("OK");
                    }
                }else{
                    //当imei没填时 如果库中imei为空则补填为meid+0,库中有则填入库中的
                    if(res.res.md_imei!=''){
                        arg_imei.val(res.res.md_imei);
                    }else{
                        arg_imei.val(u_meid+'0');
                    }
                    //$("input[name=u_imei]").removeClass("error");
                    $("input[name=meid_flag]").val("OK");
                }
                layer.closeAll('tips');
            }else if(res=="isnull"){
                $("input[name=imei_stat]").val(res);
                $("input[name=u_meid]").removeClass("error");
                $("input[name=meid_flag]").val("OK");
                layer.closeAll('tips');
            }else if(res.status=="issame"){
                $("input[name=imei_stat]").val(res.status);
                $("input[name=u_meid]").removeClass("error");
                $("input[name=meid_flag]").val("OK");
                //获取u_meid 不为空时判断 imei与meid 是否匹配
                if(imei!=''){
                    if(res.res.md_imei!=imei){
                        $("input[name=meid_flag]").val("Error");
                        $("input[name=u_meid]").addClass("error");
                        $("input[name=u_meid]").attr("aria-required","true");
                        $("input[name=u_meid]").attr("aria-invalid","true");
                        layer.tips("<%'所填MEID与IMEI不匹配，请检查后重新填写'|L%>",arg_meid,{
                            tips:[1, '#A83A3A'],
                            time:600000
                        });
                        exit();
                    }else{
                        $("input[name=u_meid]").removeClass("error");
                        $("input[name=meid_flag]").val("OK");
                    }
                }else{
                    //当imei没填时 如果库中imei为空则补填为meid+0,库中有则填入库中的
                    if(res.res.md_imei!=''){
                        arg_imei.val(res.res.md_imei);
                    }else{
                        arg_imei.val(u_meid+'0');
                    }
                    //$("input[name=u_imei]").removeClass("error");
                    $("input[name=meid_flag]").val("OK");
                }
                layer.closeAll('tips');
            }else if(res.status==7){
                $("input[name=meid_flag]").val("Error");
                $("input[name=imei_stat]").val(res.status);
                $("input[name=u_meid]").addClass("error");
                $("input[name=u_meid]").attr("aria-required","true");
                $("input[name=u_meid]").attr("aria-invalid","true");
                $("input[name=u_meid]").focus();
                layer.tips("<%'所填MEID不正确，请检查所选终端类型与MEID后重新输入'|L%>"+",code 103",$("input[name=u_meid]"),{
                    tips:[1, '#A83A3A'],
                    time:600000
                });
                exit();
            }else if(res.status==8){
                $("input[name=meid_flag]").val("Error");
                $("input[name=imei_stat]").val(res.status);
                $("input[name=u_meid]").addClass("error");
                $("input[name=u_meid]").attr("aria-required","true");
                $("input[name=u_meid]").attr("aria-invalid","true");
                $("input[name=u_meid]").focus();
                layer.tips("<%'所填MEID不正确，请检查所选终端类型与MEID后重新输入'|L%>"+",code 101",arg_meid,{
                    tips:[1, '#A83A3A'],
                    time:600000
                });
                exit();
            }else{
                $("input[name=imei_stat]").val(res.status);
                $("input[name=u_meid]").addClass("error");
                $("input[name=meid_flag]").val("Error");
            }
        }
    });
    
    //当imei为空时 则通过meid 查询终端的terminal_number
    if(imei==''){
        if(md_type!=""){
             $.ajax({
                url:"?m=terminal&a=getById_foruser_meid",
                data:{md_meid:u_meid},
                success:function(result){
                    var res = eval("("+result+")");
                    $("input[name=u_terminal_number]").val(res.md_serial_number);
                    $("input[name=u_terminal_number]").blur();
                }
            });
        }
    }
    //alert($("input[name=meid_flag]").val());
}

//验证iccid
function check_iccid(){
     var isbind = $("input[name=u_bind_phone]:checked").val();
            var ciccid = arg_iccid.val();
            if(isbind=='1'){
                $("input[name=u_iccid]").attr("required", "true");
//                valid();
//                if(ciccid==''){
//                    $("input[name=u_iccid]").focus();
//                    exit();
//                }
            }
            $.ajax({
                url:'?m=enterprise&a=geticcid&u_number='+$("input[name=u_number]").val(),
                data:{
                    u_iccid:arg_iccid.val(),
                    e_id:e_id,
                    type:'iccid'
                },
                dataType:'json',
                success:function(res){
                    if(res.status==2){
                        flag.val("Error");
                        iccid_stat.val(res.status);
                        layer.tips("<%'ICCID已绑定，请确认后重新输入'|L%>",arg_iccid,{
                            tips:[1, '#A83A3A'],
                            time:600000
                        });
                        exit();
                    }else if(res.status==1){
                        flag.val("OK");
                        iccid_stat.val(res.status);
                    }else if(res.status==5){
                        var u_bind_phone = $("input[name=u_bind_phone]:checked").val();
                        iccid_stat.val(res.status);
                        var iccid = arg_iccid.val();
                        if(u_bind_phone=='1'){
                            if(iccid!=''){
                                flag.val("Error");
                                layer.tips("<%'此ICCID库中不存在，请检查后重新填写'|L%>",arg_iccid,{
                                    tips:[1, '#A83A3A'],
                                    time:600000
                                });
                                exit();
                            }
                        }else{
                           flag.val("OK");
                        }
                    }else if(res.status==4){
                        var check = true;
                        var check1 = true;
                        //如果填写的iccid存在，并适用,自动填充imsi,手机号 以及对应判断
                        if(arg_imsi.val()==''){
                            arg_imsi.val(res.info.g_imsi);
                        }else{
                            //res.info.g_imsi!='' && 
                            if(res.info.g_imsi!=arg_imsi.val()){
                                flag.val("Error");
                                imsi_stat.val(res.status);
                                layer.tips("<%'所填写的IMSI不正确，请检查后重新填写'|L%>",arg_imsi,{
                                    tips:[1, '#A83A3A'],
                                    time:600000
                                });
                                check = false;
                            }else{
                                check = true;
                            }
                        }
                        if(arg_number.val()==''){
                            arg_number.val(res.info.g_number);
                        }else{
                            if(res.info.g_number!='' && res.info.g_number!=arg_number.val()){
                                flag.val("Error");
                                number_stat.val(res.status);
                                layer.tips("<%'所填写的手机号不正确，请检查后重新填写'|L%>",arg_number,{
                                    tips:[1, '#A83A3A'],
                                    time:600000
                                });
                                check1 = false;
                            }else{
                                check1 = true;
                            }
                        }
                        if(check==false || check1==false){
                            flag.val("Error");
                            exit();
                        }else{
                            flag.val("OK");
                            iccid_stat.val(res.status);
                        }
                    }else if(res.status==3){
                        flag.val("Error");
                        iccid_stat.val(res.status);
                        layer.tips("<%'所填写的ICCID不正确，请检查后重新填写'|L%>",arg_iccid,{
                            tips:[1, '#A83A3A'],
                            time:600000
                        });
                        exit();
                    }
                    //做到提示信息 不正确 还有 自动填充
                    layer.closeAll('tips');
                }
            });
}
$("a.get_passwd").on("click",function(){
    $.ajax({
        url:"?m=enterprise&a=get_random_passwd",
        success:function(pswd){
            $("input[name=u_passwd]").val(pswd);
        }
    });
});