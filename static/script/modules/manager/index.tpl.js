(function () {
    var resethand = function () {
        var url = $(this).attr("data");
        $("#dialog-confirm-reset").dialog({
            resizable: false,
            height: 180,
            modal: true,
            buttons: {
                "<%'重置'|L%>": function () {
                    $(this).dialog("close");
                    notice("<%'正在重置'|L%>");
                    $.ajax({
                        type: "post",
                        url: url,
                        dataType: "json",
                        success: function (result) {
                            notice(result.msg);
                        }
                    });
                },
                "<%'取消'|L%>": function () {
                    $(this).dialog("close");
                }
            }
        });
        return false;
    };

    $("div.content").delegate("a.reset", "click", resethand);
})();
$("#delall").click(function () {
    var checkd = "";

    $("input.cb:checkbox:checked").each(function () {
        checkd += $(this).val() + ",";
    });

    if (checkd === "") {
        notice("<%'未选中任何管理员'|L%>");
    } else {
        $("#dialog-confirm").dialog({
            resizable: false,
            height: 180,
            modal: true,
            buttons: {
                "<%'删除'|L%>": function () {
                    $(this).dialog("close");
                    notice("<%'正在删除'|L%>");
                    $.ajax({
                        url: "?modules=manager&action=om_del",
                        data: "list=" + checkd,
                        success: function (result) {
                            if (result.indexOf("<%'该资源需要超级管理员才可以使用'|L%>") > 0) {
                                $("html").html("");
                                location.reload();
                            }
                            if (result == 0) {
                                notice("<%'没有管理员被删除'|L%>。");
                            } else {
                                notice("<%'成功删除'|L%> " + result + " <%'个管理员'|L%>");
                            }
                            setTimeout(function () {
                                send("prev");
                            }, 888);
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