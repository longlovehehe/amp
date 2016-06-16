jQuery.validator.addMethod("e_pwd", function (value, element) {
    var length = value.length;
    var flag = true;
    /*        var mob = /^[0-9]{19}}$/i ;*/
    /*        var mob1 = /^[0-9]{20}$/i ;*/
    if (/[\u4E00-\u9FA5]/i.test(value)) {
        flag = false;
    }
    return flag;
}, "密码不能为中文字符");
jQuery.validator.addMethod("resource_less", function (value, element) {
    var flag = false;
    if (value == 0) {
        flag = true;
    }
    return flag;
}, "该资源已用完，只能输入0");
var sum = 0;
$("div.allot_user div.block input").bind("onpropertychange", function () {
    var usable_phone = $("input[name=phones_num]").val();
    var usable_dispatch = $("input[name=dispatch_num]").val();
    var usable_gvs = $("input[name=gvs_num]").val();
    var e_mds_call = $("input[name=e_mds_call]").val();
    var e_mds_phone = $("input[name=e_mds_phone]").val();
    var e_mds_dispatch = $("input[name=e_mds_dispatch]").val();
    var e_mds_gvs = $("input[name=e_mds_gvs]").val();
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
var diff_phone = 0;
var diff_dispatch = 0;
var diff_gvs = 0;
$("select#e_mds_id").bind("change", function () {
    var usable_phone = $("input[name=phones_num]").val();
    var usable_dispatch = $("input[name=dispatch_num]").val();
    var usable_gvs = $("input[name=gvs_num]").val();
    var d_user = $(this).children('option:selected').attr("d_user");
    var d_call = $(this).children('option:selected').attr("d_call");
    var d_phone_user = $(this).children('option:selected').attr("d_phone_user");
    var d_dispatch_user = $(this).children('option:selected').attr("d_dispatch_user");
    var d_gvs_user = $(this).children('option:selected').attr("d_gvs_user");
    if (usable_phone - $(this).children('option:selected').attr("diff_phone") > 0) {
        diff_phone = $(this).children('option:selected').attr("diff_phone");
    } else {
        diff_phone = usable_phone;
    }
    if (usable_dispatch - $(this).children('option:selected').attr("diff_dispatch") > 0) {
        diff_dispatch = $(this).children('option:selected').attr("diff_dispatch");
    } else {
        diff_dispatch = usable_dispatch;
    }
    if (usable_gvs - $(this).children('option:selected').attr("diff_gvs") > 0) {
        diff_gvs = $(this).children('option:selected').attr("diff_gvs");
    } else {
        diff_gvs = usable_gvs;
    }
    var e_mds_phone = $("input[name=e_mds_phone]").val();

    $("input[name=e_mds_users]").removeAttr('resource_less').removeAttr('range');
    $("input[name=e_mds_call]").removeAttr('resource_less').removeAttr('range');
    $("input[name=e_mds_phone]").removeAttr('resource_less').removeAttr('range');
    $("input[name=e_mds_dispatch]").removeAttr('resource_less').removeAttr('range');
    $("input[name=e_mds_gvs]").removeAttr('resource_less').removeAttr('range');

    $("input").bind("change", function () {
        var e_mds_call = $("input[name=e_mds_call]").val();
        var e_mds_phone = $("input[name=e_mds_phone]").val();
        var e_mds_dispatch = $("input[name=e_mds_dispatch]").val();
        var e_mds_gvs = $("input[name=e_mds_gvs]").val();
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
    /*
     if (d_user == 'undefined' || d_user == "" || d_user == 0) {
     $("input[name=e_mds_users]").attr("resource_less", 'TRUE');
     } else {
     urange = "[0," + d_user + "]";
     $("input[name=e_mds_users]").attr("range", urange);
     }*/
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
$("select[name=e_area]").change(function () {
    var vl = $("select[name=e_area]").val();
    var url = $("select#e_mds_id").attr("action");
    url += "&d_area=" + vl;
    $.ajax({
        url: url,
        success: function (result) {
            $("select#e_mds_id").html(result);
        }
    });
});
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

});
$("#gprs_agent").click(function () {
    $("div.gprs_enterprise").addClass("none");
    $("div.block.create_e").addClass("none");
    $("#gprs_agent").addClass("active");
    $("#g_ag_id").removeClass("none");
    $("#gprs_enter").removeClass("active");
    $("div.allot_user").addClass("none");
    $("input[name=create_type]").val("agents");
    $("#used_name").html("代理商");
});
$("#gprs_enter").click(function () {
//$("div.gprs_enterprise").removeClass("none");
    $("div.block.create_e").removeClass("none");
    $("#gprs_enter").addClass("active");
    $("#g_ag_id").addClass("none");
    $("#gprs_agent").removeClass("active");
    $("div.allot_user").removeClass("none");
    $("input[name=create_type]").val("enterprise");
    $("#used_name").html("企业");
});
$("#create_e").click(function () {
    if ($("select[name=g_ag_en_id]").val() == "") {
        $("div.gprs_enterprise").removeClass("none");
        $("select[name=g_ag_en_id]").removeAttr("required");
    } else {
        $("div.gprs_enterprise").addClass("none");
    }
});

function getintime() {
    var now = new Date();
    var monthn = parseInt(now.getMonth()) + 1;
    var yearn = now.getFullYear();
    var daten = now.getDate();
    var dtime = yearn + "-" + monthn + "-" + daten;
    $("#dtime").val(dtime);
}

function closed() {
    document.getElementById('light').style.display = 'none';
    document.getElementById('fade').style.display = 'none';
}

$("#seticcid").click(function () {
    document.getElementById('light').style.display = 'block';
    document.getElementById('fade').style.display = 'block';
});

function do_set() {
    var iccids = $("#iccid_list").val();
    var iccid_arr = iccids.split(",");
    var str1 = "";
    var str2 = new Array();
    console.log(iccid_arr);
    $("input.cb").each(function () {
        for (var i = 0; i < iccid_arr.length; i++) {
            if ($(this).val() == iccid_arr[i]) {
                $(this).prop("checked", "checked");
                str1 += $(this).val() + ",";
                iccid_arr.remove($(this).val());
            }
        }
    });
    iccid_arr.join(",");
    if (str1 == "") {
        notice("列表中不存在要选的ICCID");
        document.getElementById('light').style.display = 'none';
        document.getElementById('fade').style.display = 'none';
    } else if (iccid_arr != "") {
        notice("自动选择成功，选中项为：" + str1 + "列表中不存在项:" + iccid_arr);
        document.getElementById('light').style.display = 'none';
        document.getElementById('fade').style.display = 'none';
    } else if (iccid_arr == "") {
        notice("自动选择成功，选中项为：" + str1);
        document.getElementById('light').style.display = 'none';
        document.getElementById('fade').style.display = 'none';
    }
}
/**出库验证**/
jQuery.validator.addMethod("em_name", function (value, element) {
    var chinese = /^([\u4e00-\u9fa5]|[a-zA-Z0-9\.])+$/;
    return this.optional(element) || (chinese.test(value));
}, "名称中包含不可用字符");