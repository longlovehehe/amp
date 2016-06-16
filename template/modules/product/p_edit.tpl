
{strip}
<h2 class="title">{"{$title}"|L}</h2>
<form id="form" class="base mrbt10" action="?modules=product&action=p_addData" method="post">

    <input autocomplete="off"  name="page" value="0" type="hidden" />
    <input autocomplete="off"  type="hidden" id="rad" class="radd">
    <input autocomplete="off"  type='hidden' value='{$product_info.p_id}' name='p_id'>
    <input autocomplete="off"  type='hidden' value='edit' name='do'>

    <div class="block">
        <label class="title">{"产品名称"|L}：</label>
        <input autocomplete="off"  maxlength="32" name="p_name" type="text" chinese="true" required="true"  value='{$product_info.p_name}'/>
    </div>
    <div class="block">
        <label class="title">{"区域"|L}：</label>
        <select value='{$product_info.p_area}' multiple="TRUE" name="p_area[]" class="autofix autoeditselect" action="?m=area&a=option" selected="true" >
            {"<option value='#'>{'全部'|L}</option>"|isallarea}
        </select>
    </div>
    <div class="block">
        <label class="title">{"产品价格"|L}：</label>
        <input autocomplete="off"  maxlength="32" name="p_price" type="text" required="true"  value='{$product_info.p_price}' digits="true" range="[0,2147483647]" />
    </div>
    <div class="block">
        <label class="title">{"产品描述"|L}：</label>
        <input autocomplete="off"  name="p_desc" type="text" required="true" chinese="true"  value='{$product_info.p_desc}'  maxlength="150" />
    </div>
    {*{/foreach}*}
    <div class="block">
        <label class="title">{"功能列表"|L}：</label>
    </div>
    {foreach key=key name=res_1 item =item_1 from=$data['status']}
    <div class="block radio {if $item_1['id'] eq gn_yyhy}none{/if}" value="{$item_1['value_o']}">
        <label class="title">{"{$key}"|L}:</label>
        {foreach name=res_2 item =item_2 from=$item_1 key=key_1}
        <input autocomplete="off"  type="hidden" name='rad_id' value="{$item_1['id']}" />
        {if $key_1  == "0"}
        <label>
            <input autocomplete="off"  type="radio" name="{$item_1['id']}" value="{$item_2[0]}" class='rad' />{"{$item_2[1]}"|L}
        </label>
        {/if}
        {if $key_1 != "id" && $key_1 !="value_o"}
        <label>
            <input autocomplete="off"  type="radio" name="{$item_1['id']}" value="{$item_2[0]}" class='rad' />{"{$item_2[1]}"|L}
        </label>
        {/if}

        {/foreach}<br>
    </div>
    {/foreach}
    <div class="block">
        <br />
        <br />
    </div>
    <a goto="?m=product&a=index" form="form" class="ajaxpost_s button normal" >{"保存"|L}</a>
    <a class="goback button">{"取消"|L}</a>
</form>
{/strip}
<script  {"type='ready'"}>
    $(document).ready(function () {
        $("a.ajaxpost_s").click(function () {
            var arrayObj = new Array();
            var temp = document.getElementsByName("rad_id");
            for (var i = 0; i < temp.length; i++)
            {
                var arr = $("input[name='rad_id']")[i].value;
                arrayObj[arr] = $("input[name=arr]").attr("checked", true);
            }

            if ($("#form").valid()) {
                var form = $("a.ajaxpost_s").attr("form");
                var url = $("#" + form).attr("action");
                var goto = $("a.ajaxpost_s").attr("goto");
                $.ajax({
                    url: url,
                    method: "POST",
                    dataType: "json",
                    data: $("#form").serialize() + "&arrayObj=" + arrayObj[arr],
                    success: function (result) {
                        if (result.status == 0) {
                            notice(result.msg, goto);
                        } else {
                            notice(result.msg);
                        }
                    }
                });
            }
        });

    })
</script>
