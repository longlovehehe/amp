{foreach name=list item=item from=$list}
<option value="{$item.e_id}">{$item.e_name}</option>
{/foreach}