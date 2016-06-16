$(".refreshall").click(function () {
    var checkd = "";
    var url = $(this).attr("data");
    $("input.cb:checkbox:checked").each(function () {
        checkd += $(this).val() + ",";
    });
    if (checkd == "") {
        notice("<%'未选中任何企业项'|L%>");
    } else {
        $.ajax({
            url: url,
            dataType: "JSON",
            data: $("form.data").serialize(),
            success: function (result) {
                notice(result.msg);
                setTimeout(function () {
                    send();
                }, 888);
            }
        });
    }
});
$("#delall").click(function () {
    var checkd = "";
    $("input.cb:checkbox:checked").each(function () {
        checkd += $(this).val() + ",";
    });
    if (checkd == "") {
        notice("<%'未选中任何企业项'|L%>");
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
                        url: "?modules=enterprise&action=index_del",
                        data: $("form.data").serialize(),
                        success: function (result) {
                            if (result == 0) {
                                notice("<%'没有企业被删除。非停用状态企业或特殊企业无法直接删除'|L%>");
                            } else {
                                notice("<%'成功删除'|L%> " + result + " <%'个企业'|L%>");
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
