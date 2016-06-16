<h2 class="title">{"{$title}"|L}</h2>
<form id="form" class="base mrbt10" action="?m=enterprise&a=groups_view_edit_save">
    <input autocomplete="off"  type="hidden" name="pm_pgnumber" value="{$smarty.request.pm_pgnumber}{$smarty.request.pg_number}"/>
    <input autocomplete="off"  type="hidden" name="do" value="{$smarty.request.do}"/>
    <input autocomplete="off"  type="hidden" name="e_id" value="{$smarty.request.e_id}"/>

    <div class="block">
        <label class="title">{"成员号码"|L}：</label>
        <span><input autocomplete="off"  type="text" value="{$data.pm_number}" name="pm_number" {if $smarty.request.do eq edit}readonly{/if} required="true" /></span>
    </div>
    <div class="block">
        <label class="title">{"成员级别"|L}：</label>
        <input autocomplete="off"  type="text" value="{$data.pm_level}" name="pm_level" required="true" digits="true" range="[0,255]" />
    </div>
    <div class="block checkbox"  value="{$data.pm_hangup|default:0}">
        <label class="title"></label>
        <label class="title">
            <input maxlength="32" name="pm_hangup" type="checkbox"/>
            <span>{"被叫挂断对讲组权限"|L}</span>
        </label>
    </div>

    <div class="buttons mrtop40">
        <a goto="?m=enterprise&a=groups_view&e_id={$smarty.request.e_id}&pg_number={$smarty.request.pg_number}{$smarty.request.pm_pgnumber}" form="form" class="ajaxpost button normal">{"保存"|L}</a>
        <a class="goback button">{"取消"|L}</a>
    </div>
</form>