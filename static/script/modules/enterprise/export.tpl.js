var request = eval($("span.request").text());
var request = request[0];
var e_id = request.e_id;
$("div.autoactive[action=export]").addClass("active");

/****************************************************************************************
 * 导出模块
 */
$(".export").click(function () {
    var action = $(this).attr("action");
    var url = '';
    if (action === 'user') {
        url = "?m=enterprise&a=users_export_ag&e_id=" + e_id;
    }
    if (action === 'ptt_group') {
        url = "?m=enterprise&a=groups_export&e_id=" + e_id;
    }
    if (action === 'user_group') {
        url = "?m=enterprise&a=ug_export&e_id=" + e_id;
    }

    $("#ifr").attr("src", url);
});

/** 企业群组导入 **/
$("#ptimport").click(function () {
    $("#pt_import_up").trigger("click");
});
$("#pt_import").bind("change", function () {
    if ($("#pt_import_up").val() !== "") {
        notice("上传中");
        $("#pt_import").submit();
    }
});

/** 企业用户导入 **/
$("#uimport").click(function () {
    $("#user_import_up").trigger("click");
});
$("#user_import").bind("change", function () {
    if ($("#user_import_up").val() !== "") {
        notice("上传中");
        $("#user_import").submit();
    }
});

/** 企业部门导入 **/
$("#ugimport").click(function () {
    $("#user_group_import_up").trigger("click");
});
$("#user_group_import").bind("change", function () {
    if ($("#user_group_import_up").val() !== "") {
        notice("上传中");
        $("#user_group_import").submit();
    }
});


/***********************************************
 * 群组回调部分
 */
/**文件上传*/
function pt_if_callback(result) {
    if (result.status !== 0) {
        notice(result.msg);
    } else {
        notice('上传成功，正在解析');
        $("form#pt_ic input[name=f]").val(result.data);
        $("form#pt_ic").submit();
    }
}

/**内容检查*/
function pt_ic_callback(result) {
    if (result.status !== 0) {
        notice(result.msg);
    } else {
        notice(result.msg, undefined, function () {
            $("form#pt_i input[name=f]").val(result.data);
            $("form#pt_i").submit();
        }, "导入");
    }
}
/**数据导入*/
function pt_i_callback(result) {
    if (result.status !== 0) {
        notice(result.msg);
    } else {
        notice(result.msg, undefined, function () {
            window.location.reload();
        });
    }
}


/***********************************************
 * 用户回调部分
 */
/**文件上传*/
function u_if_callback(result) {
    if (result.status !== 0) {
        notice(result.msg);
    } else {
        /** 文件上传成功*/
        notice('上传成功，正在解析');
        $("form#u_ic input[name=f]").val(result.data);
        $("form#u_ic").submit();
    }
}

/**内容检查*/
function u_ic_callback(result) {
    if (result.status !== 0) {
        notice(result.msg);
    } else {
        notice(result.msg, undefined, function () {
            $("form#u_i input[name=f]").val(result.data);
            $("form#u_i").submit();
        });
    }
}
/**数据导入*/
function u_i_callback(result) {
    if (result.status !== 0) {
        notice(result.msg);
    } else {
        notice(result.msg, undefined, function () {
            window.location.reload();
        });
    }
}

/***********************************************
 * 部门回调部分
 */
/**文件上传*/
function ug_if_callback(result) {
    if (result.status !== 0) {
        notice(result.msg);
    } else {
        /** 文件上传成功*/
        notice('上传成功，正在解析');
        $("form#ug_ic input[name=f]").val(result.data);
        $("form#ug_ic").submit();
    }
}
/**内容检查*/
function ug_ic_callback(result) {
    if (result.status !== 0) {
        notice(result.msg);
    } else {
        notice(result.msg, undefined, function () {
            $("form#ug_i input[name=f]").val(result.data);
            $("form#ug_i").submit();
        }, '导入');
    }
}
/**数据导入*/
function ug_i_callback(result) {
    if (result.status !== 0) {
        notice(result.msg, undefined, undefined, '导入');
    } else {
        notice(result.msg, undefined, function () {
            window.location.reload();
        }, '关闭');
    }
}

