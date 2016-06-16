
var pid = $("input[name='pid']").val();
var ptype = $("input[name='ptype']").val();

function notice1(notice, url) {
    var id = "notice_" + new Date().getTime();
    $("div.notice_mask").remove();
    $notice_mask = $("<div class='notice_mask sync '></div>");
    $notice_content = $("<div class='notice_content animated fadeIn'></div>");
    $notice = $("<div class='notice'></div>");
    $notice_mask.attr("id", id);
    $notice.html(notice);
    $notice_content.append($notice);
    $toolbar = $("<div class='toolbar'></div>");
    $notice_content.append($toolbar);
    $notice_mask.append($notice_content);
    $("body").append($notice_mask);
    $("#" + id + "div.notice_content").draggable({containment: "parent"});

    if (typeof (url) != 'undefined') {
        $("#" + id + " a.close").bind("click", function () {
            window.location.href = url;
        });
    } else {
        $("#" + id + " a.close").bind("click", function () {
            $("#" + id).remove();
        });
    }
    return id;
}
var t = document.getElementById("android-table");
for (var j = 0; j < t.rows.length; j++) {
    if ((j % 2) == 0) {
        t.rows[j].style.backgroundColor = "#ffffff";
    } else {
        t.rows[j].style.backgroundColor = "#f1f1f1";
    }

}
var t = document.getElementById("ios-table");
for (var j = 0; j < t.rows.length; j++) {
    if ((j % 2) == 0) {
        t.rows[j].style.backgroundColor = "#ffffff";
    } else {
        t.rows[j].style.backgroundColor = "#f1f1f1";
    }

}

function do_select(obj, num) {
    var t = document.getElementById("android-table");
    for (var i = 0; i < t.rows.length; i++) {
        if ((i % 2) == 0) {
            t.rows[i].style.backgroundColor = "#ffffff";
        } else {
            t.rows[i].style.backgroundColor = "#f1f1f1";
        }

    }
    obj.style.backgroundColor = "#CCCCCC";
    var t = document.getElementById("ios-table");
    for (var i = 0; i < t.rows.length; i++) {
        if ((i % 2) == 0) {
            t.rows[i].style.backgroundColor = "#ffffff";
        } else {
            t.rows[i].style.backgroundColor = "#f1f1f1";
        }

    }
    obj.style.backgroundColor = "#CCCCCC";
    pid = obj.getAttribute("p_id");
    $("input[name='pid']").val(pid);
    ptype = obj.getAttribute("p_type");
    $("input[name='ptype']").val(ptype);
}

function closed() {
    document.getElementById('light').style.display = 'none';
    document.getElementById('fade').style.display = 'none';
    $("input[name='dir_name']").val("");
    $("input[name='soft_name']").val("");
    $("input[name='ptt_version']").val("");
}
function new_creat() {
    $(".c_dir").html("<%'新建目录'|L%>");
    document.getElementById('light').style.display = 'block';
    document.getElementById('fade').style.display = 'block';
    $("input[name='dir_name']").removeAttr('readOnly');
    $("input[name='ptype']").attr("value", "android").val();
    $("input[name='flag']").attr('value', 'save');
}
function new_creat1() {
    $(".c_dir").html("<%'新建目录'|L%>");
    document.getElementById('light').style.display = 'block';
    document.getElementById('fade').style.display = 'block';
    $("input[name='dir_name']").removeAttr('readOnly');
    $("input[name='ptype']").attr('value', "ios").val();
    $("input[name='flag']").attr('value', 'save');
}
function edit_ios_dir() {
    if (pid == '' || pid == null || ptype != "ios") {
        notice("<%'请选中相对应文件再修改'|L%>");
        return false;
    } else {
        $("input[name='dir_name']").attr('readOnly', 'true');
        $("input[name='flag']").attr('value', 'edit');
        $(".c_dir").html("<%'修改目录'|L%>");
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';
        $.ajax({
            type: "post",
            url: "?m=cms&a=getinfo",
            data: {flag: $("input[name='flag']").attr('value', 'edit').val(), p_id: pid},
            async: false,
            datatype: 'json',
            success: function (data) {
                var j = eval('(' + data + ')');
                $("input[name='dir_name']").val(j.p_dir);
                $("input[name='ptt_version']").val(j.p_version);
                /*$("input[name='path']").val(j.p_file);*/
            }

        });
    }
}

function edit_android_dir() {
    if (pid == '' || pid == null || ptype != 'android') {
        notice("<%'请选中相对应文件再修改'|L%>");
        return false;
    } else {
        $("input[name='dir_name']").attr('readOnly', 'true');
        $("input[name='flag']").attr('value', 'edit');
        $(".c_dir").html("<%'修改目录'|L%>");
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';
        $.ajax({
            type: "post",
            url: "?m=cms&a=getinfo",
            data: {flag: $("input[name='flag']").attr('value', 'edit').val(), p_id: pid},
            datatype: 'json',
            async: false,
            success: function (data) {
                var j = eval('(' + data + ')');
                $("input[name='dir_name']").val(j.p_dir);
                $("input[name='ptt_version']").val(j.p_version);
                /*$("input[name='path']").val(j.p_file);*/
            }

        });
    }
}

function do_set() {
    if ($("input[name='dir_name']").val() === "") {
        $("input.error").focus();
        return false;
    }
    if($("input[name='ptt_version']").val() === ""){
        $("input[name='ptt_version']").focus();
    }
    $.ajax({
        type: 'post',
        url: '?m=cms&a=checkname',
        datatype: 'json',
        data: {p_dir: $("input[name='dir_name']").val(), p_type: $("input[name='ptype']").val(), flag: $("input[name='flag']").val()},
        success: function (data) {
            var match_an = /.apk$/i;
            var match_ios = /.ipa$/i;
            var match_dir = /^[^\\/:*?"<>|]+$/;
            var match_version = /^(\d{1,})\.(\d{1,})\.(\d{1,})\.(\d){1,}$/;
            var flag = $("input[name='flag']").val();
            var v = $("input[name='ptt_version']").val();
            var bv = $("input[name='browsversion']").val();
            if ($("input[name='dir_name']").val() == "" || /[\u4E00-\u9FA5]/i.test($("input[name='dir_name']").val())) {
                return false;
            }

            if (!match_dir.test($("input[name='dir_name']").val())) {
                notice("<%'目录名称输入格式不正确,存在特殊字符'|L%>");
                document.getElementById('light').style.display = 'none';
                document.getElementById('fade').style.display = 'none';
                return false;
            }
            if ($("input[name='soft_name']").val() == "") {
                notice("<%'请选择文件'|L%>");
                document.getElementById('light').style.display = 'none';
                document.getElementById('fade').style.display = 'none';
                return false;
            }
            if (flag == 'edit') {
                if ($("input[name='ptt_version']").val() == "") {
                    return false;
                }
                if (!match_version.test(v)) {
                    notice("<%'版本格式错误,例如: xx.xx.xx.xx'|L%>");
                    document.getElementById('light').style.display = 'none';
                    document.getElementById('fade').style.display = 'none';
                    return false;
                }
                if ($("input[name='ptype']").val() == 'android') {
                    if (!match_an.test($("input[name='soft_name']").val())) {
                        notice("<%'上传文件应以【.apk】格式结尾'|L%>");
                        document.getElementById('light').style.display = 'none';
                        document.getElementById('fade').style.display = 'none';
                        return false;
                    }
                } else {
                    if (!match_ios.test($("input[name='soft_name']").val())) {
                        notice("<%'上传文件应以【.ipa】格式结尾'|L%>");
                        document.getElementById('light').style.display = 'none';
                        document.getElementById('fade').style.display = 'none';
                        return false;
                    }
                }

                document.work_form.submit();
                document.getElementById('light').style.display = 'none';
                document.getElementById('fade').style.display = 'none';
                notice1("<%'文件正在上传中请稍后'|L%>...");

            } else {
                if (data == "off") {
                    notice("<%'文件名已经存在'|L%>");
                    document.getElementById('light').style.display = 'none';
                    document.getElementById('fade').style.display = 'none';
                    return false;
                } else {
                    if ($("input[name='ptt_version']").val() == "") {
                        return false;
                    }

                    if (!match_version.test(v)) {
                        notice("<%'版本格式错误,例如: xx.xx.xx.xx'|L%>");
                        document.getElementById('light').style.display = 'none';
                        document.getElementById('fade').style.display = 'none';
                        return false;
                    }
                    if ($("input[name='ptype']").val() == 'android') {
                        if (!match_an.test($("input[name='soft_name']").val())) {
                            notice("<%'上传文件应以【.apk】格式结尾'|L%>");
                            document.getElementById('light').style.display = 'none';
                            document.getElementById('fade').style.display = 'none';
                            return false;
                        }
                    } else {
                        if (!match_ios.test($("input[name='soft_name']").val())) {
                            notice("<%'上传文件应以【.ipa】格式结尾'|L%>");
                            document.getElementById('light').style.display = 'none';
                            document.getElementById('fade').style.display = 'none';
                            return false;
                        }
                    }

                    document.work_form.submit();
                    document.getElementById('light').style.display = 'none';
                    document.getElementById('fade').style.display = 'none';
                    notice1("<%'文件正在上传中请稍后'|L%>...", '?m=cms&a=index');
                }

            }
            if (bv == "ie") {

            } else {
                var file = document.forms['work_form']['soft_name'].files[0];
                if (file.size >= 31457280 || file.size == 0) {
                    notice("<%'上传文件超出允许上传小于30M或文件大小为0'|L%>");
                    document.getElementById('light').style.display = 'none';
                    document.getElementById('fade').style.display = 'none';
                    return false;
                }
            }
        }
    });

}

function del_android_dir() {
    if (pid == '' || pid == null || ptype != 'android') {
        notice("<%'请先选择需要被删除的文件'|L%>");
        return false;
    } else {
        $("input[name='flag']").attr('value', 'del');
        confirm("<%'是否确认删除此文件'|L%>?");
        if (con == "<%'取消'|L%>") {
            return false;
        } else {
            $.ajax({
                type: "post",
                url: "?m=cms&a=del_dir",
                data: {p_id: pid},
                datatype: 'json',
                async: false,
                success: function (data) {
                    if (data) {
                        notice("<%'文件删除成功'|L%>", '?m=cms&a=index');
                    } else {
                        notice("<%'文件删除失败'|L%>");
                    }
                }
            });
        }
    }
}
function del_ios_dir() {
    if (pid == '' || pid == null || ptype != 'ios') {
        notice("<%'请先选择需要被删除的文件'|L%>");
        return false;
    } else {
        $("input[name='flag']").attr('value', 'del');
        confirm("<%'是否确认删除此文件'|L%>?");
        if (con == "取消") {
            return false;
        } else {
            $.ajax({
                type: "post",
                url: "?m=cms&a=del_dir",
                data: {p_id: pid},
                datatype: 'json',
                async: false,
                success: function (data) {
                    if (data) {
                        notice("<%'文件删除成功'|L%>", '?m=cms&a=index');
                    } else {
                        notice("<%'文件删除失败'|L%>");
                    }
                }
            });
        }
    }
}

function empty_android_dir() {

    if (pid == '' || pid == null || ptype != 'android') {
        notice("<%'请先选择需要被清空的文件'|L%>");
        return false;
    } else {
        $("input[name='flag']").attr('value', 'empty');
        confirm("<%'是否确认清空此文件夹'|L%>?");
        if (con == "<%'取消'|L%>") {
            return false;
        } else {
            $.ajax({
                type: "post",
                url: "?m=cms&a=empty_dir",
                data: {p_id: pid},
                datatype: 'json',
                async: false,
                success: function (data) {
                    if (data) {
                        notice("<%'文件夹已清空'|L%>", '?m=cms&a=index');
                    } else {
                        notice("<%'文件夹清空失败'|L%>", '?m=cms&a=index');
                    }
                }

            });
        }
    }
}
function empty_ios_dir() {
    if (pid == '' || pid == null || ptype != 'ios') {
        notice("<%'请先选择需要被清空的文件'|L%>");
        return false;
    } else {
        $("input[name='flag']").attr('value', 'empty');
        confirm("<%'是否确认清空此文件夹'|L%>?");
        if (con == "<%'取消'|L%>") {
            return false;
        } else {
            $.ajax({
                type: "post",
                url: "?m=cms&a=empty_dir",
                data: {p_id: pid},
                datatype: 'json',
                async: false,
                success: function (data) {
                    if (data) {
                        notice("<%'文件夹已清空'|L%>", '?m=cms&a=index');
                    } else {
                        notice("<%'文件夹清空失败'|L%>", '?m=cms&a=index');
                    }
                }

            });
        }
    }
}
jQuery.validator.addMethod("dir_name", function (value, element) {
    var flag = true;
    var match_dir = /^[^\\/:*?"<>|]+$/;
    if (/[\u4E00-\u9FA5]/i.test(value) && match_dir.test(value)) {
        flag = false;
    }
    return flag;
}, "<%'目录名称输入格式不正确,存在特殊字符'|L%>");

jQuery.validator.addMethod("ptt_version", function (value, element) {
   var match_version = /^(\d{1,})\.(\d{1,})\.(\d{1,})\.(\d){1,}$/;
    var flag = true;
    if (!match_version.test(value)) {
        flag = false;
    }
    return flag;
}, "<%'版本格式错误,例如: xx.xx.xx.xx'|L%>");
$("#zdll").mouseover(function () {
    $(this).css('backgroundColor', '#ddd');
    $(this).css('color', '#A43838');

});
$("#zdll").mouseout(function () {
    $(this).css('backgroundColor', '#848589');
    $(this).css('color', '#fff');
});
$("input[name=button]").mouseover(function () {
    $(this).css('backgroundColor', '#ddd');
    $(this).css('color', '#A43838');
});
$("input[name=button]").mouseout(function () {
    $(this).css('backgroundColor', '#848589');
    $(this).css('color', '#fff');
});

function getFiles(obj) {
    document.work_form.path.value = obj.value;

    var pt = $("input[name='ptype']").val();
    var match_an = /.apk$/i;
    var match_ios = /.ipa$/i;

    if (navigator.userAgent.indexOf("MSIE") != -1 && !obj.files) {
        $("input[name='browsversion']").val('ie');
        var filePath = obj.value;
        if (pt == 'android' && !match_an.test(filePath)) {
            notice("<%'上传文件应以【.apk】格式结尾'|L%>");
            document.getElementById('light').style.display = 'none';
            document.getElementById('fade').style.display = 'none';
        } else if (pt == 'ios' && !match_ios.test(filePath)) {
            notice("<%'上传文件应以【.ipa】格式结尾'|L%>");
            document.getElementById('light').style.display = 'none';
            document.getElementById('fade').style.display = 'none';
        }

    } else {
        var file = document.forms['work_form']['soft_name'].files[0];
        if (file.size >= 31457280 || file.size == 0) {
            notice("<%'上传文件超出允许上传限制30M或文件大小为0'|L%>");
            document.getElementById('light').style.display = 'none';
            document.getElementById('fade').style.display = 'none';
        }
        if (pt == 'android' && !match_an.test(file.name)) {
            notice("<%'上传文件应以【.apk】格式结尾'|L%>");
            document.getElementById('light').style.display = 'none';
            document.getElementById('fade').style.display = 'none';
        } else if (pt == 'ios' && !match_ios.test(file.name)) {
            notice("<%'上传文件应以【.ipa】格式结尾'|L%>");
            document.getElementById('light').style.display = 'none';
            document.getElementById('fade').style.display = 'none';
        }
    }
}

