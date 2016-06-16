var request = eval($("span.request").text());
var request = request[0];
var e_id = request.e_id;
var flag = 'enterprise';
var groups_num = e_id;
$("div.autoactive[action=groups]").addClass("active");
function confirm1(notice) {
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
        del_pg();
    });
    $("#" + id + " a.cancel").bind("click", function () {
        con = $("a.cancel").html();
        $("#" + id).remove();
    });
    return con;
}


$("li").click(function () {
    $(this).addClass("selecthover").siblings().removeClass("selecthover");
    var pg_num = $(".selecthover a").attr("pg_number");
    var url = "?m=enterprise&a=groups_edit&e_id=" + e_id + "&pg_number=" + pg_num;
    $("#edit_pg").attr("href", url);
    $("div.autoactive[action=groups]").addClass("active");
}).hover(function () {
    var val = $(this).attr("class");
    if (val.indexOf("selecthover") >= 0) {
        return false;
    }
    $(this).addClass("lihover");
}, function () {
    $(this).removeClass("lihover");
});
function getinfo(obj) {
    $("input[name=u_name]").val("");
    $("input[name=u_number]").val("");
    $("select[name=u_ug_id]").val("");
    $("select[name=u_sub_type]").val("");
    $("a.getall").attr("onclick", "").click(eval(function () {
        getalllist_v2();
    }));
    $("a.addmore").bind("click");
    flag = 'group';
    groups_num = obj;
    $("#num").html(0);
    $("ul.user-right-list li.indexcheck").removeClass("selecthover");
    $("a.addmore").removeClass("none");
    $(".parent_node").removeClass("selecthover");
    var url = "?m=enterprise&a=groups_item_pguser&pg_number=" + obj + "&e_id=" + e_id;
    $("input[name=pg_number]").val(obj);
    $("input[name=page]").val(0);
    $("input[name=action]").val('groups_item_pguser');
    /*
     if ($("input[name=move_u_default]").is(":checked")) {
     var url1 = '?m=enterprise&a=groups_option&safe=true&e_id=' + e_id;
     } else {
     var url1 = "?m=enterprise&a=groups_option&e_id=" + e_id;
     }
     */
    var url1 = "?m=enterprise&a=groups_option&e_id=" + e_id;
    $.ajax({
        url: url1,
        success: function (result) {
            $("#e_select").empty();
            var option = "<option id='clear_pg'  value='0'><%'从当前组移除'|L%></option>";
            option += "<option id = 'save_pg'  value = " + obj + " selected = 'selected' > <%'保留群组信息'|L%> </option>";
            option += result;
            $("#e_select").html(option);
            /*$("#e_select").prepend("<option id='clear_pg'  value='0'>从当前组移除</option>");
             $("#e_select").prepend("<option id='save_pg'  value=" + obj + " selected='selected'>保留群组信息</option>");*/
        }
    });
    $("tr.head").html("<th style='padding: 0px ;'><div style='width:30px;'><input style='margin-left: 3px;' autocomplete='off'  type='checkbox' id='checkall' /></div></th> <th><div style='width:80px;'><%'姓名'|L%></div></th><th><div style='width:40px;'><%'类型'|L%></div></th> <th><div style='width:105px;'><%'号码'|L%></div></th> <th><div style='width:68px;'><%'级别'|L%></div></th> <th><div style='width:102px;'><%'默认组'|L%></div></th> <th><div style='width:95px;'><%'部门'|L%></div></th>");
    $("#edit_pg").removeClass("none");
    $("#del_pg").removeClass("none");
    $.ajax({
        url: url,
        method: "GET",
        dataType: 'html',
        success: function (result) {
            $.ajax({
                url: "?m=enterprise&a=getgpnum&pg_number=" + obj + "&e_id=" + e_id,
                method: "GET",
                dataType: 'html',
                success: function (result) {
                    $("#ninfo").html(result);
                    if (result - 10 > 0) {
                        $("a.addmore").removeClass("none");
                        $("a.getall").removeClass("none");
                    } else {
                        $("a.addmore").addClass("none");
                        $("a.getall").addClass("none");
                    }
                }
            });
            $("input[name=pg_number]").val(obj);
            $("#gettrig").empty();
            $("form").attr('action', url);
            send();
        }
    });
}
function del_pg() {
    confirm1("<%'确认要删除此群组'|L%>?");
    if (con == "<%'取消'|L%>") {
        return false;
    } else {

        var pg_num = $(".selecthover a").attr("pg_number");
        $.ajax({
            url: "?modules=enterprise&action=groups_del&e_id=" + e_id + "&list=" + pg_num,
            dataType: "json",
            method: "POST",
            success: function () {
                notice("<%'群组删除成功'|L%>", "?m=enterprise&a=groups&e_id=" + e_id);
                send();
            }
        });
    }
}

/**
 *
 * @returns {undefined}
 */
var init = close;
function aseffects() {
    /*
     if (init == open) {
     $("#child_node").removeClass("none");
     $(".parent_node img").attr("src", "images/close.png");
     init = close;
     } else {
     $("#child_node").addClass("none");
     $(".parent_node img").attr("src", "images/open.png");
     init = open;
     }
     */
    if (init == open) {
        $("#child_node").removeClass("none");
        $(".parent_node").css("background", "url(images/close.png) 4px 8px no-repeat");
        init = close;
    } else {
        $("#child_node").addClass("none");
        $(".parent_node").css("background", "url(images/open.png) 4px 8px no-repeat");
        init = open;
    }
}

function getindex(obj) {
//$("a.getall").removeClass("none");
    $("input[name=u_name]").val("");
    $("input[name=u_number]").val("");
    $("select[name=u_ug_id]").val("");
    $("select[name=u_sub_type]").val("");
    $("a.getall").attr("onclick", "").click(eval(function () {
        getalllist();
    }));
    $("a.addmore").bind("click");
    //$("a.addmore").bind("click");
    flag = 'enterprise';
    $("#gettrig").empty();
    $("#num").html(0);
    $("#clear_pg").remove();
    $("#save_pg").remove();
    $("a.addmore").removeClass("none");
    $(".li_select").removeClass("selecthover");
    /*
     if ($("input[name=move_u_default]").is(":checked")) {
     var url1 = '?m=enterprise&a=groups_option&safe=true&e_id=' + e_id;
     } else {
     var url1 = "?m=enterprise&a=groups_option&e_id=" + e_id;
     }
     */
    var url1 = "?m=enterprise&a=groups_option&e_id=" + e_id;
    $.ajax({
        url: url1,
        success: function (result) {
            var option = "<option value='' selected='selected'><%'请选择群组'|L%></option>";
            option += result;
            $("#e_select").empty();
            $("#e_select").html(option);
        }
    });
    var url = "?m=enterprise&a=groups_item&e_id=" + e_id;
    $("#edit_pg").addClass("none");
    $("#del_pg").addClass("none");
    $("input[name=page]").val(0);
    $("tr.head").html("<th style='padding: 0px ;'><div style='width:30px;'><input style='margin-left: 3px;' autocomplete='off'  type='checkbox' id='checkall' /></div></th> <th><div style='width:115px;'><%'姓名'|L%></div></th><th><div style='width:40px;'><%'类型'|L%></div></th> <th><div style='width:105px;'><%'号码'|L%></div></th> <th><div style='width:130px;'><%'所属群组'|L%></div></th> <th><div style='width:105px;'><%'部门'|L%></div></th>");
    $.ajax({
        url: url,
        method: "GET",
        dataType: 'html',
        success: function (result) {

            $.ajax({
                url: "?m=enterprise&a=getugnum",
                method: "GET",
                dataType: 'html',
                data: {e_id: e_id},
                success: function (result) {
                    $("#ninfo").html(result);
                    if (result - 10 > 0) {
                        $("a.addmore").removeClass("none");
                        $("a.getall").removeClass("none");
                    } else {
                        $("a.addmore").addClass("none");
                        $("a.getall").addClass("none");
                    }
                }
            });
            $("input[name=action]").val('groups_item');
            $("form").attr('action', url);
            send();
        }
    });
}

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
$("#groups_move_all").click(function () {
    var checkd = "";
    var move_u_default_pg = $("select[name=move_u_default_pg]").val();
    var pg_num = $("input[name=pg_number]").val();
    if ($("input[name=move_u_default]").is(":checked")) {
        $.ajax({
            url: "?m=enterprise&a=getimpgroups&pg_number=" + move_u_default_pg + "&e_id=" + e_id,
            method: "GET",
            dataType: 'json',
            success: function (result) {
                if (result.status == "-1") {
                    notice(result.msg);
                    exit();
                }
            }
        });
    }


    $("input.cb:checkbox:checked").each(function () {
        checkd += $(this).val() + ",";
    });
    if (checkd === "") {
        notice("<%'未选中任何企业用户'|L%>");
    } else if (move_u_default_pg == "") {
        notice("<%'未选中转移群组'|L%>");
    } else if ($("form.move_u_default_pg").valid()) {
        var data = $("form.move_u_default_pg").serialize() + "&" + $("form.data").serialize();
        $("input[name=u_number]").val("");
        $("select[name=u_product_id]").val("");
        $("select[name=u_default_pg]").val("");
        $("select[name=u_ug_id]").val("");
        $("select[name=u_pic]").val("");
        $("select[name=u_sex]").val("");
        $("input[name=u_sex]").val("");
        $("input[name=u_sex]").val("");
        $("input[name=u_terminal_type]").val("");
        $("input[name=u_terminal_model]").val("");
        $("input[name=u_imsi]").val("");
        $("input[name=u_imei]").val("");
        $("input[name=u_iccid]").val("");
        $("input[name=u_mac]").val("");
        $("input[name=u_zm]").val("");
        $.ajax({
            url: "?m=enterprise&a=groups_users_move&e_id=" + e_id,
            data: data,
            dataType: "json",
            success: function (result) {
                notice(result.msg);
                $(".submit").trigger('click');
                var num = -1;
                $("ul li.li_select a").each(function () {
                    var pgnum = $(this).attr("pg_number");
                    /*
                    if ($("ul li.li_select a").attr("pg_number") == $("select[name=move_u_default_pg]").val()) {
                        pgnum = $("select[name=move_u_default_pg]").val();
                    }*/
                    num++;
                    $.ajax({
                        url: "?m=enterprise&a=getgpnum&pg_number=" + pgnum + "&e_id=" + e_id,
                        method: "GET",
                        dataType: 'html',
                        success: function (result) {
                            var a = new Array();
                            if ($("select[name=move_u_default_pg]").val() == 0) {
                                if (pgnum == pg_num) {
                                    $("ul li.selecthover a div span.getnum").html(result);
                                }
                            } else {
                                if (pgnum == $("select[name=move_u_default_pg]").val()) {
                                    $("ul li.li_select a div span.getnum").eq(num).html(result);
                                }
                            }
                        }
                    });
                });
                send();
            }
        });
    }
});
function new_creat() {
    $(".c_dir").html("<%'新建群组'|L%>");
    $("input[name='pg_name']").val('');
    $("input[name='pg_number']").val('');
    document.getElementById('light').style.display = 'block';
    document.getElementById('fade').style.display = 'block';
}
/**
 * Comment
 */
function do_set() {
    if (getnumval() === false) {
        return false;
    }
    if (getnamval() === false) {
        return false;
    } else {
        $.ajax({
            url: "?modules=enterprise&action=groups_save_v2&e_id=" + e_id,
            method: "GET",
            dataType: 'json',
            data: {pg_number: $("input.get_pg_number").val(), pg_name: $("input[name='pg_name']").val(), pg_level: $("input[name='pg_level']").val(), pg_grp_idle: $("input[name='pg_grp_idle']").val(), pg_speak_idle: $("input[name='pg_speak_idle']").val(), pg_speak_total: $("input[name='pg_speak_total']").val(), pg_queue_len: $("input[name='pg_queue_len']").val(), pg_chk_stat_int: $("input[name='pg_chk_stat_int']").val(), pg_buf_size: $("input[name='pg_buf_size']").val(), pg_record_mode: $("input[name='pg_record_mode']").val()},
            success: function (result) {

                $("div.autoactive[action=groups]").addClass("active");
                if (result.msg == "<%'群组号码已存在'|L%>") {
                    $("#pg_num_title").html("<%'群组号码已存在'|L%>");
                } else if (result.msg == "<%'群组名称已存在'|L%>") {
                    $("#pg_name_title").html("<%'群组名称已存在'|L%>");
                } else {
                    notice(result.msg, '?m=enterprise&a=groups&e_id=' + e_id);
                    $("div.autoactive[action=groups]").addClass("active");
                    document.getElementById('light').style.display = 'none';
                    document.getElementById('fade').style.display = 'none';
                }
            }
        });
    }
}
function closed() {
    document.getElementById('light').style.display = 'none';
    document.getElementById('fade').style.display = 'none';
}
function init_table() {
    $("input#checkall").removeAttr("checked");
    //$("a.getall").removeClass("none");
    $("#num").text($("input.cb:checkbox:checked").length);
    var tot = $("div.total").html();
    /* if ($("input[name=page]").val() < Math.floor(Number($(".total").first().text()) / 10)) {
     $("a.addmore").removeClass("none");
     $("a.getall").removeClass("none");
     } else {
     $("a.addmore").addClass("none");
     $("a.getall").addClass("none");
     }*/
    if (tot > 10) {
        $("a.addmore").removeClass("none");
        $("a.getall").removeClass("none");
    } else {
        $("a.addmore").addClass("none");
        $("a.getall").addClass("none");
    }
}
$("a.init_button").on("click", function () {
    init_table();
    var numtotal = Number($(".total").first().text());
    if (numtotal > 0) {
        $("#ninfo").text(numtotal);
        if (numtotal < 10) {
            $("a.addmore").addClass("none");
            $("a.getall").addClass("none");
        } else if (numtotal < 10) {
            $("a.addmore").removeClass("none");
            $("a.getall").removeClass("none");
        }
    } else {
        $("#ninfo").text(0);
        $("a.addmore").addClass("none");
        $("a.getall").addClass("none");
    }
});
/**
 *
 */
function getnumval() {

    var match = /^[\d]+$/;
    var a = $("input.get_pg_number").val();
    if (a == "") {
        $("#pg_num_title").html("<%'*'|L%>");
        return false;
    }
    else if (!match.test(a)) {
        $("#pg_num_title").html("<%'只可输入数字'|L%>");
        return false;
    }
    else if (a < 0 || a > 9999) {
        $("#pg_num_title").html("<%'请输入0-9999之间的数字'|L%>");
        return false;
    } else {
        $("#pg_num_title").html("");
        return true;
    }
}
/**
 * getnamval
 */
function getnamval() {
    var a = $("input.get_pg_name").val();
    var match = /^([\u4e00-\u9fa5]|[a-zA-Z0-9\#\-\.\(\)\（\） \_\.])+$/;
    var match1 = /^[ ]+$/g;
    if (a == "" || match1.test(a)) {
        $("#pg_name_title").html("<%'必须填写'|L%>");
        return false;
    } else if (!match.test(a)) {
        $("#pg_name_title").html("<%'名称中包含不可用字符'|L%>");
        return false;
    } else {
        $("#pg_name_title").html("");
        return true;
    }
}
/*
 $("input[name=move_u_default]").on('click', function () {
 var own = $(this);
 $('select[name=move_u_default_pg]>option').remove();
 if (own.is(":checked")) {
 if (flag == 'enterprise') {
 $.ajax({
 url: "?m=enterprise&a=groups_option&safe=true&e_id=" + e_id,
 success: function (result) {
 var option = "<option value='' selected='selected'>请选择群组</option>";
 option += result;
 $("#e_select").empty();
 $("#e_select").html(option);
 }
 });
 } else {
 $.ajax({
 url: "?m=enterprise&a=groups_option&safe=true&e_id=" + e_id,
 success: function (result) {

 $("#e_select").empty();
 var option = "<option id='clear_pg'  value='0'>从当前组移除</option>";
 option += "<option id = 'save_pg'  value = " + groups_num + " selected = 'selected' > 保留群组信息 </option>";
 option += result;
 $("#e_select").html(option);
 }
 });
 }
 /*$('select[name=move_u_default_pg]').addClass('autofix').attr('action', '?m=enterprise&a=groups_option&safe=true&e_id=' + e_id);*/
/*       } else {
 if (flag == 'enterprise') {
 $.ajax({
 url: "?m=enterprise&a=groups_option&e_id=" + e_id,
 success: function (result) {
 var option = "<option value='' selected='selected'>请选择群组</option>";
 option += result;
 $("#e_select").empty();
 $("#e_select").html(option);
 }
 });
 } else {
 $.ajax({
 url: "?m=enterprise&a=groups_option&e_id=" + e_id,
 success: function (result) {

 $("#e_select").empty();
 var option = "<option id='clear_pg'  value='0'>从当前组移除</option>";
 option += "<option id = 'save_pg'  value = " + groups_num + " selected = 'selected' > 保留群组信息 </option>";
 option += result;
 $("#e_select").html(option);
 }
 });
 }
 /* $('select[name=move_u_default_pg]').addClass('autofix').attr('action', '?m=enterprise&a=groups_option&e_id=' + e_id);*/
/*}
 /* initFix();*/
/*});*/

function getalllist() {
    var url = "?m=enterprise&a=getalluser&e_id=" + e_id;
    //$("input[name=action]").val('getalluser');
    $("div.content").addClass("loading _301_1_gif");
    $.ajax({
        url: url,
        data: {u_name: $("input[name=u_name]").val(), u_number: $("input[name=u_number]").val(), u_ug_id: $("select[name=u_ug_id]").val(), u_sub_type: $("select[name=u_sub_type]").val(), does: "groups"},
        success: function (result) {
            // $("form").attr('action', url);
            $("#gettrig").empty();
            $("#gettrig").html(result);
            if (result == "") {
                $("#ninfo").html(0);
            } else {
                $("#ninfo").html($("div.total").html());
            }
            //send();
        }
    });
    init_table();
    $("div.content").removeClass("loading _301_1_gif");
    $("a.getall").addClass("none");
    $("a.addmore").addClass("none");
    $("div.newtable").unbind("scroll");
}

function getalllist_v2() {
    var url = "?m=enterprise&a=getalluser_v2&e_id=" + e_id + "&pg_number=" + groups_num;
    //$("input[name=action]").val('getalluser_v2');
    $("div.content").addClass("loading _301_1_gif");
    $.ajax({
        url: url,
        data: {u_name: $("input[name=u_name]").val(), u_number: $("input[name=u_number]").val(), u_ug_id: $("select[name=u_ug_id]").val(), u_sub_type: $("select[name=u_sub_type]").val()},
        success: function (result) {
            //$("form").attr('action', url);
            //$("div.content").removeClass("loading _301_1_gif");
            $("#gettrig").empty();
            $("#gettrig").html(result);
            if (result == "") {
                $("#ninfo").html(0);
            } else {
                $("#ninfo").html($("div.total").html());
            }
        }
    });
    init_table();
    $("div.content").removeClass("loading _301_1_gif");
    $("a.getall").addClass("none");
    $("a.addmore").addClass("none");
    $("div.newtable").unbind("scroll");
}