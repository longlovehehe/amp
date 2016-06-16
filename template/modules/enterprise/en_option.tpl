{foreach name=list item=item from=$list}
<option value="{$item.e_id}" diff_phone="{$item.u_diff_phone}" diff_dispatch="{$item.u_diff_dispatch}" diff_gvs="{$item.u_diff_gvs}">{$item.e_name}</option>
{/foreach}