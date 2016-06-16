<h2 class="title">{"{$title}"|L}</h2>
<div class="toolbar">
    <form action="?modules=enterprise&action=groups_view_item" id="form" method="post">
        <input autocomplete="off"  name="e_id" value="{$data.e_id}" type="hidden" />
        <input autocomplete="off"  name="pg_number" value="{$data.pg_number}" type="hidden" />
        <input autocomplete="off"  name="page" value="0" type="hidden" />
        <a form="form" class="button submit none">{"查询"|L}</a>

    </form>
    <div class="toolbar">
        <a id="delall" class="button">{"批量删除"|L}</a>
        <a href="?m=enterprise&a=groups&e_id={$data.e_id}" class="button">{"返回"|L}</a>
    </div>
    <div class="toptoolbar">
        <a href="?m=enterprise&amp;a=groups_view_add&e_id={$data.e_id}&pg_number={$data.pg_number}&do=add" class="button orange">{"新增企业群组成员"|L}</a>
    </div>
</div>
<div class="content"></div>

<script  {'type="ready"'}>
    $("#delall").click(function () {
        var checkd = "";

        $("input.cb:checkbox:checked").each(function () {
            checkd += $(this).val() + ",";
        });

        if (checkd === "") {
            notice("未选择任何项");
        } else {
            notice("正在删除");
            $.ajax({
                url: "?modules=enterprise&action=groups_view_del",
                data: $("#form").serialize() + "&" + $("form.table").serialize(),
                type: "POST",
                success: function (result) {
                    if (result == 0) {
                        notice("没有记录被删除");
                    } else {
                        notice("成功删除" + result + "条记录");
                    }
                    setTimeout(function () {
                        send();
                    }, 888);
                }
            });
        }
    });
</script>