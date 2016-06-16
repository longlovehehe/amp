{foreach name=list item=item from=$list}
<option value="{$item.id}" title="{$item.price}">{$item.name}</option>
{/foreach}