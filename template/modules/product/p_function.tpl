
<style type="text/css">
    .tablebox tbody
    {
        font-family: Arial,Helvetica,sans-serif;
        background-color:#FFFFFF;
        overflow:auto;
    }
    .tablescr{
        overflow-y:auto;
        border:1px solid ;
    }

</style>
{strip}
<div class="toolbar">
    <!-- <a href="?m=product&a=index" class="button">{"产品管理"|L}</a> -->
    <a href="?m=product&a=p_function" class="button active">{"产品功能库"|L}</a>
</div>
<h2 class="title">{"产品功能库"|L}</h2>

{if $smarty.session.own.om_id eq admin1}
{*<form id="form" action="?modules=product&action=p_save" method="post" class="base mrbt10">
    <div class="toolbar">
        <input autocomplete="off"  name="page" value="0" type="hidden" />
        <input autocomplete="off"  type="hidden" id="sel">
        <div class="line">
            <label>{"功能名称"|L}：</label>
            <input autocomplete="off"  class="autosend" name="pi_name" maxlength="32" type="text" required="TRUE" />
        </div>
        <div class="line">
            <label>{"功能编号"|L}：</label>
            <input value="gn_" autocomplete="off" class="autosend" pi_code="true" name="pi_code" type="text" required="TRUE"  />
        </div>
        <div class="line">
            <label>{"功能状态"|L}：</label>
            <input autocomplete="off" maxlength="128" class="autosend" pi_status="true" name="pi_status" type="text" required="TRUE"  />
        </div>
        <a form="form" class="ajaxpostr button normal">{"新增功能"|L}</a>
    </div>
</form>*}
{/if}
<div class="content">
    <table class="base full" id="tablebox111">
        <tr class='head'>
            <th width="100px">{"功能名称"|L}</th>
            {*<th width="100px">{"功能编号"|L}</th>*}
            <th width="100px">{"功能状态"|L}</th>
            {*<th width="100px">{"功能价格"|L}</th>*}
        </tr>
        {foreach name=list item=item from=$list}
        {if $item['pi_name']!='VAS'}
        <tr id="{$item.pi_id}">
            <td style="height: 16px">{"{$item.pi_name}"|L}</td>
            {*<td style="height: 16px">{$item.pi_code}</td>*}
            <td style="height: 16px">{$item.pi_status|trsanlang}</td>
            {*<td style="height: 16px"><input type="text" rmb readonly="ture" style="width:100px;" name="pi_price" value="{$item.pi_price|default:0}" pi_id="{$item.pi_id}" class="{$item.pi_price}" /></td>*}
        </tr>
        {/if}
        {/foreach}
    </table>
</div>

<p class="info" style="margin-top: 10px;">{"产品功能规则，功能状态使用格式【值,描述】，每一项之间分隔使用"|L}|</p>
{if $smarty.session.own.om_id eq admin}
{*<div id="dialog-confirm" class="hide" title="{'删除选中项'|L}？">
    <p>{"确定要删除该项吗"|L}？</p>
</div>
<div id="dialog-confirm-clearall" class="hide" title="{'清空全部'}？">
    <p>{"确定要清空全部吗"|L}？</p>
</div>

<div class="buttons mrtop40">
    <a class="button" onclick="del();">{"选中删除"|L}</a>
    <a class="button none" onclick="delAll()">{"清空全部"|L}</a>
</div>*}
{/if}
<script>
            /*
             function sel(obj) {
             var t = document.getElementById("tablebox111");
             for (var i = 0; i < t.rows.length; i++) {
             if (i % 2 == 0) {
             t.rows[i].style.backgroundColor = "#f1f1f1";
             } else {
             t.rows[i].style.backgroundColor = "#fff";
             }
             }
             obj.style.backgroundColor = "#a9a9a9";
             $("#sel").val(obj.id);
             }
             */
            $("#tablebox111 tr").on("click", function () {
    $("#tablebox111 tr").removeClass("sel");
            $(this).addClass("sel");
            $("#sel").val($(this).attr('id'));
    });
            function del() {
            var id = $("#sel").val();
                    $("#dialog-confirm").dialog({
            resizable: false,
                    height: 180,
                    modal: true,
                    buttons: {
                    "{"清空"|L}": function () {
                    $(this).dialog("close");
                            notice("{"正在删除"|L}");
                            $.ajax({
                            url: "?modules=product&action=pro_del",
                                    data: "id=" + id,
                                    dataType: "json",
                                    success: function (result1) {
                                    if (result1.status == 0) {
                                    window.location.reload();
                                    } else {
                                    notice(result1.msg, true);
                                            setTimeout(function () {
                                            window.location.reload();
                                            }, 5);
                                    }
                                    }
                            });
                    },
                            "{"取消"|L}": function () {
                            $(this).dialog("close");
                            }
                    }
            });
            }
    function delAll() {
    $("#dialog-confirm-clearall").dialog({
    resizable: false,
            height: 180,
            modal: true,
            buttons: {
            "{"删除"|L}": function () {
            $(this).dialog("close");
                    notice("{"正在删除"|L}");
                    $.ajax({
                    url: "?modules=product&action=del_all",
                            dataType: "json",
                            success: function (result1) {
                            if (result1.status == 0) {
                            window.location.reload();
                            } else {
                            window.location.reload();
                            }
                            }
                    });
            },
                    "{"取消"|L}": function () {
                    $(this).dialog("close");
                    }
            }
    });
    }
</script>
<script  {"type='ready'"}>
    $(document).ready(function () {
    $("a.ajaxpostr").click(function () {
    if ($("#form").valid()) {
    var form = $("a.ajaxpostr").attr("form");
            var url = $("#" + form).attr("action");
            $.ajax({
            url: url,
                    method: "POST",
                    dataType: "json",
                    data: $("#form").serialize(),
                    success: function (result) {
                    result;
                            window.location.reload();
                    }
            });
    }
    });
    });
</script>
{/strip}
