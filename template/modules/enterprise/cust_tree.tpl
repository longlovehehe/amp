{strip}

<div id="child_node">
    {foreach name=list item=item from=$list}
    <li class="li_select" id="{$item.c_pg_number}" onmouseover="getcolor({$item.c_pg_number});" onmouseout="dropcolor({$item.c_pg_number});" onclick="getinfo({$item.c_pg_number});"><a title="{$item.c_pg_name}" pg_number="{$item.c_pg_number}"  {if $item.c_pg_level eq 0}style="display: block;width: 160px;color:#A43838"{else}style="display: block;width: 160px;{/if} class="usergroup title" href="javascript:void(0);" ><div style="padding-left: 40px;">{if mb_strlen($item.c_pg_name)<=12}{$item.c_pg_name}{else}{$item.c_pg_name|truncate: 12:''}... {/if}(<span class="getnum">{$item.total}</span>人)</div></a></li>
    {/foreach}
</div>
{/strip}


