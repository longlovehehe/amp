var request = eval($("span.request").text());
var request = request[0];
var e_id = request.e_id;
var ug_id = 0;

/**
 * init u_ug_id
 */
function init_u_ug_id() {
    $("select[name=u_ug_id]").html("<option value='0'><%'清除部门信息'|L%></option>").addClass("autofix");
    initFix();
}

/**
 * init talbe
 */
function init_table() {
    $("input#checkall").removeAttr("checked");
    $("#num").text($("input.cb:checkbox:checked").length);
    var tot = Number($(".total").first().text());
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
        /*  if ((numtotal - $("input.cb").length) <= 0) {
         $("a.addmore").addClass("none");
         } else if ($("input[name=page]").val() <= Math.floor(numtotal / 10)) {
         $("a.addmore").removeClass("none");
         }*/
        if (numtotal < 10) {
            $("a.addmore").addClass("none");
            $("a.getall").addClass("none");
        } else if (numtotal > 10) {
            $("a.addmore").removeClass("none");
            $("a.getall").removeClass("none");
        }
    } else {
        $("#ninfo").text(0);
        $("a.getall").addClass("none");
    }
});
var buttonshow = function (own) {
    if (own.attr("lock") != "true") {
        $("#create_peer").show();
        $("#edit").show();
        $("#del").show();
    } else {
        $("#create_peer").hide();
        $("#edit").hide();
        $("#del").hide();
    }
};
$("div.autoactive[action=usergroup]").addClass("active");
(function () {
    $("div#tree").delegate("a.title", "click", function () {
        $("table.content").html("");
        $("input[name=u_name]").val("");
        $("input[name=u_number]").val("");
        $("div#tree .title").removeClass("checkd");
        var owner = $(this);
        var ug_parent_id = owner.attr('ug_parent_id');

        $("input[name=ug_name]").val(owner.attr("data"));
        $('input[name=u_ug_id]').val(ug_parent_id);
        $('input[name=page]').val(0);
        $('a.userform').trigger('click');
        owner.addClass("checkd");
        buttonshow(owner);
    });
    $("div#tree>a.title").trigger("click");
    $("div#tree").delegate("a.child,a.childed", "dblclick", function () {
        var owner = $(this);
        $("a.getall").removeClass("none");
        owner.addClass("childed").removeClass("child");
        owner.toggleClass("close")
                .next().toggle();
    });
    $("div#tree").delegate("a.child", "dblclick", function () {
        var ug_parent_id = $(this).attr("ug_parent_id");
        $("a.getall").removeClass("none");
        var url = "?modules=enterprise&action=usergroup_item&e_id=" + e_id + "&ug_parent_id=" + ug_parent_id;
        var owner = $(this);
        if (owner.hasClass('close')) {
            owner.next().remove();
        }
        owner.addClass("childed").removeClass("child");
        $.ajax({
            url: url,
            method: "POST",
            success: function (result) {
                owner.after(result);
            }
        });
    });
    $("div#tree>a.title").trigger("dblclick");
})();
(function () {
    function node_submit() {
        $("input[name=ug_parent_id]").val($("div#tree a.checkd").attr("ug_parent_id"));
        var ug_path = $("div#tree a.checkd").attr("ug_path");
        $("input[name=ug_path]").val(ug_path);
        $("#dialog-edit").dialog({
            resizable: false,
            hide: false,
            width: 400,
            height: 280,
            modal: true,
            close: function () {
                $("#dialog-edit").dialog("destroy");
            },
            buttons: {
                "<%'提交'|L%>": function () {
                    var owner = $(this);
                    if ($("form#form").valid()) {
                        var url = $("form#form").attr("action");

                        $.ajax({
                            url: url,
                            method: "POST",
                            dataType: 'json',
                            data: $("form#form").serialize(),
                            success: function (result) {
                                if (result.status == '0') {
                                    if (result.result == "edit") {
                                        $("div#tree a.checkd").parent().parent().prev().removeClass("childed close").addClass("child").trigger("dblclick");
                                        $("div#tree>a.title").addClass("checkd");
                                    } else {
                                        $("div#tree a.checkd").removeClass("childed close").addClass("child").trigger("dblclick");
                                    }
                                    owner.dialog("destroy");
                                } else {
                                    notice(result.msg);
                                    owner.dialog("destroy");
                                }
                                init_u_ug_id();
                            }
                        });
                    }
                },
                "<%'取消'|L%>": function () {
                    $(this).dialog("destroy");
                }
            }
        });
    }

    function node_del(action) {
        var ug_id = $("div#tree a.checkd").attr("ug_parent_id");
        var url = "?modules=enterprise&action=usergroup_del&e_id=" + e_id + "&do=" + action + "&ug_id=" + ug_id;
        $.ajax({
            url: url,
            success: function () {
                $("div#tree a.title").each(function () {
                    if ($(this).attr("ug_parent_id") == ug_id) {
                        var val = $(this).attr("class");
                        
                        if (!(val.indexOf("checkd") > 0 && ($(this).parent().prev().length > 0 || $(this).parent().next().length > 0))) {
                            $(this).parent().parent().prev().removeClass("childed close").addClass("checkd").trigger("click");
                        }else{
                            $(this).parent().parent().prev().removeClass("childed close").addClass("checkd").trigger("click");
                        }
                        $(this).next().remove();
                        $(this).parent().remove();
                    }
                });
                init_u_ug_id();
            }
        });
    }

    /* trigger */
    $("#create").click(function () {
        $("form#form input").val("");
        $("form#form input[name=ug_weight]").val("0");
        $("input[name=do]").val("add");
        $("#dialog-edit").attr("title", "<%'添加部门'|L%>");
        node_submit();
    }
    );
    $("#create_peer").click(function () {
        $("form#form input").val("");
        $("form#form input[name=ug_weight]").val("0");
        $("input[name=do]").val("add");
        $("#dialog-edit").attr("title", "<%'创建平行部门'|L%>");
        var flag = $("div#tree a.checkd").parent().parent().parent().parent().prev("a.title");
        if (flag.length == 0) {
            $("div#tree a.checkd").removeClass("checkd");
            $("div#tree>a").addClass("checkd");
        } else {
            $("div#tree a.checkd").removeClass("checkd").parent().parent().prev("a.title").addClass("checkd");
        }
        node_submit();
    });
    $("#edit").click(function () {
        if ($("div#tree a.checkd").attr("lock") != "true") {
            $("form#form input").val("");
            $("form#form input[name=ug_weight]").val("0");
            $("input[name=do]").val("edit");
            $("#dialog-edit").attr("title", "<%'编辑部门'|L%>");
            var ug_name = $("div#tree a.checkd").attr('data');
            var ug_parent_id = $("div#tree a.checkd").attr("ug_parent_id");
            var ug_weight = $("div#tree a.checkd").attr("ug_weight");
            var ug_path = $("div#tree a.checkd").attr("ug_path");
            var ug_id = $("div#tree a.checkd").attr("ug_id");
            $("input[name=ug_name]").val(ug_name);
            $("input[name=ug_parent_id]").val(ug_parent_id);
            $("input[name=ug_weight]").val(ug_weight);
            $("input[name=ug_path]").val(ug_path);
            $("input[name=ug_id]").val(ug_id);
            node_submit();
        } else {
            alert("<%'该部门已锁定，无法操作'|L%>");
        }
    });
    $("#del").click(function () {
        if ($("div#tree a.checkd").attr("lock") != "true") {
            $("#dialog-confirm").dialog({
                resizable: false,
                width: 480,
                height: 250,
                modal: true,
                buttons: {
                    "<%'删除且丢失下级部门'|L%>": function () {
                        node_del("del");
                        $(this).dialog("destroy");
                    },
                    "<%'取消'|L%>": function () {
                        $(this).dialog("destroy");
                    }
                }
            });
        } else {
            alert("<%'该部门已锁定，无法操作'|L%>");
        }
    });
})();
$("#batch_submit").click(function () {
    var checkd = "";
    $("input.cb:checkbox:checked").each(function () {
        checkd += $(this).val() + ",";
    });
    if (checkd === "") {
        notice("<%'未选中任何企业用户'|L%>");
    } else {
        var data = $("form#batch").serialize() + "&" + $("form.data").serialize();
        $("#dialog-confirm-batch").dialog({
            resizable: false,
            height: 180,
            modal: true,
            buttons: {
                "<%'更新'|L%>": function () {
                    $(this).dialog("close");
                    var u_ug_id = $("div#tree a.checkd").attr("ug_parent_id");
                    $.ajax({
                        url: "?modules=enterprise&action=users_batch_ug&e_id=" + e_id + "&u_ug_id=" + u_ug_id,
                        data: data,
                        success: function () {
                            /*$('input[name=page]').val(0);
                             $('a.userform').trigger('click');
                             $("#checkall").removeAttr("checked");*/
                            window.location.reload();
                        }
                    });
                },
                "<%'取消'|L%>": function () {
                    $(this).dialog("close");
                }
            }
        });
    }
});
$("#export").on("click", function () {
    var url = "?m=enterprise&a=usergroup_item_export&u_ug_id=" + $("input[name=u_ug_id]").val();
    url += "&e_id=" + e_id;
    var ifr = $("<iframe></iframe>");
    ifr.attr("src", url);
    ifr.addClass("none");
    $("body").append(ifr);
});
function getalllist() {
    var url = "?m=enterprise&a=getalluser&e_id=" + e_id;
    //$("input[name=action]").val('getalluser');
    $("div.content").addClass("loading _301_1_gif");
    $.ajax({
        url: url,
        data: {u_name: $("input[name=u_name]").val(), u_number: $("input[name=u_number]").val(), ug_name: $("input[name=ug_name]").val(), u_sub_type: $("select[name=u_sub_type]").val(), does: "usergroup"},
        success: function (result) {
            // $("form").attr('action', url);
            $("table.base.content.two").empty();
            $("table.base.content.two").html(result);
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
/**
 * Comment
 */
function get_ug_id(obj) {
    ug_id = obj;
}
