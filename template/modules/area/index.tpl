{strip}
<h2 class="title">{"{$title}"|L}</h2>
<div class="toptoolbar">
    <a href="?m=area&a=area_add" class="button orange">{"新增区域"|L}</a>
</div>

<form id="form" action="?modules=area&action=index_item" method="post">
    <div class="toolbar">
        <input autocomplete="off"  name="page" value="0" type="hidden" />
        <input autocomplete="off"  name="num" value="10" type="hidden" />
        <input autocomplete="off"  form="form" class="button submit" type="hidden"/>
    </div>

</form>
<div class="content"></div>
<div id="dialog-confirm" class="hide" title="{'删除选中项'|L}？">
    <p>{"确定要删除该区域吗"|L}？</p>
</div>
<script  {"type='ready'"}>
    $(document).ready(function () {
    $("div.content").delegate("#del", "click", function () {
    var id = $(this).attr("data");
            $("#dialog-confirm").dialog({
    resizable: false,
            height: 180,
            modal: true,
            buttons: {
            "{"删除"|L}": function () {
            $(this).dialog("close");
                    notice("{'正在删除'|L}");
                    $.ajax({
                    url: "?modules=area&action=area_del",
                            data: "id=" + id,
                            dataType: "json",
                            success: function (result) {
                            if (result.status == 0) {
                            notice(result.msg);
                                    send("prev");
                            } else {
                            notice(result.msg);
                                    send("prev");
                            }
                            }
                    });
            },
                    "{"取消"|L}": function () {
                    $(this).dialog("close");
                    }
            }
    });
    });
    })
</script>
{/strip}