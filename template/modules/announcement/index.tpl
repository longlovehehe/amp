
<h2 class="title">{"{$title}"|L}</h2>
<div class="toptoolbar">
    <a href="?m=announcement&a=an_add" class="button orange">{"发布公告"|L}</a>
</div>

<form id="form" action="?m=announcement&a=index_item" method="post">
    <div class="toolbar">
        <input autocomplete="off"  name="modules" value="announcement" type="hidden" />
        <input autocomplete="off"  name="action" value="index_item" type="hidden" />
        <input autocomplete="off"  name="page" value="0" type="hidden" />

        <div class="line">
            <label>{"公告标题"|L}：</label>
            <input autocomplete="off"  class="autosend" name="an_title" type="text" />
        </div>
        {if $smarty.session.own.om_id eq admin}<div class="line">
            <label>{"发布人"|L}：</label>
            <input autocomplete="off"  class="autosend" name="an_user" type="text" />
        </div>{/if}
        <div class="line">
            <label>{"发布状态"|L}：</label>
            <select name="an_status">
                <option value="">{"全部"|L}</option>
                <option value="1">{"已发布"|L}</option>
                <option value="0">{"草稿"|L}</option>
            </select>
        </div>
        <div class="line">
            <label>{"可见区域"|L}：</label>
            <select value='#' name="an_area" class="autofix" action="?m=area&a=option">
                <option value="#">{"全部"|L}</option>
            </select>
        </div>
        <div class="line">
            <label>{"发布时间"|L}：</label>
            <input autocomplete="off"  class="datepicker start" name="start" type="text" date="true" />
            <span>-</span>
            <input autocomplete="off"  class="datepicker end" name="end" type="text" date="true" />
        </div>
        <div class="buttons right">
            <a form="form" class="button submit">{"查询"|L}</a>
        </div>
    </div>
</form>

<div class="content"></div>
<div id="dialog-confirm" class="hide" title="{'删除选中项'|L}？">
    <p>{"确定要删除该公告吗"|L}？</p>
</div>
<script {"type='ready'"}>
    $('nav a.announcement').addClass('active');
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
                    notice("{"正在删除"|L}");
                    $.ajax({
                    url: "?modules=announcement&action=an_del",
                            data: "id=" + id,
                            success: function (result) {
                            if (result == 0) {
                            notice("{"没有记录被删除。非停用状态企业无法直接删除"|L}");
                            } else {
                            notice("{"成功删除"|L} " + result + " {"记录"|L}");
                            }
                            setTimeout(function () {
                            send("prev");
                            }, 888);
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
