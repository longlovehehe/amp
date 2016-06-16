{strip}
<ul>
    {foreach name=list item=item from=$list}
    <li>
        <a title='{$item.ug_name}' class="title {if $item.child > 0}child{/if}"  onclick="get_ug_id({$item.ug_id});" ug_parent_id="{$item.ug_id}" ug_weight="{$item.ug_weight}" ug_path="{$item.ug_path}" data="{$item.ug_name}">{if mb_strlen($item.ug_name)<=5}{$item.ug_name}{else}{$item.ug_name|truncate: 5:''}... {/if}{*({$item.ug_id|modCountUserGroupsTotal}人)*}</a>
    </li>
    {/foreach}
</ul>
{/strip}