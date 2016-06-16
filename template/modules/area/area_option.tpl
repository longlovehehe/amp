{foreach name=list item=item from=$list}
<option value="{$item.am_id}" {if $e_area == $item.am_id} selected="selected" {/if}>{$item.am_name}</option>
{/foreach}