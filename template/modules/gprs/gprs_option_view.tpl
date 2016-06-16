{foreach name=list item=item from=$list}
<option value="{$item.gp_id}" >{$item.gp_name}</option>
{/foreach}