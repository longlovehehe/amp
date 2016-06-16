{foreach name=list item=item from=$list}

<option value="{$item.d_id}" >{$item.d_name}【{$item.d_ip2}】</option>

{/foreach}