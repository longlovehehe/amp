$("div.content").delegate("#del", "click", function () {
    var id = $(this).attr("data");
    $("#dialog-confirm").dialog({
        resizable: false,
        height: 180,
        modal: true,
        buttons: {
            "<%'删除'|L%>": function () {
                $(this).dialog("close");
                notice("<%'正在删除'|L%>");
                $.ajax({
                    url: "?modules=product&action=p_del",
                    data: "id=" + id,
                    dataType: "json",
                    success: function (result) {
                        notice(result.msg);
                        send("prev");
                    }
                });
            },
            "<%'取消'|L%>": function () {
                $(this).dialog("close");
            }
        }
    });
});