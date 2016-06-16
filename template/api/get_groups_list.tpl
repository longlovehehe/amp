{foreach name=list item=item from=$list}
<option value="{$item.ug_id}">{$item.ug_path|modugpath}{$item.ug_name}</option>
{/foreach}