$("#delall").click(function () {
    var checkd = "";
    $("input.cb:checkbox:checked").each(function () {
        checkd += $(this).val() + ",";
    });
    if (checkd == "") {
        notice(L("未选中任何代理商"));
    } else {
        $("#dialog-confirm").dialog({
            resizable: false,
            height: 180,
            modal: true,
            buttons: {
                "<%'删除'|L%>": function () {
                    $(this).dialog("close");
                    notice(L("正在删除"));
                    $.ajax({
                        url: "?modules=agents&action=batchdel",
                        data: $("form.data").serialize(),
                        success: function (result) {
                            /**
                             if (result == 0) {
                             notice("没有记录被删除。非停用状态企业无法直接删除");
                             } else {
                             notice("成功删除" + result + "记录");
                             }*/
                            notice("<%'成功删除'|L%> " + result + " <%'个代理商'|L%>");
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

