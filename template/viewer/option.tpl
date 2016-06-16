{foreach name=list item=item from=$list}
<option value="{$item.id}">{$item.name|getompman}</option>
{/foreach}