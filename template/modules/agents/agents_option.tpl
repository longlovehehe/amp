{foreach name=list item=item from=$list}
<option value="{$item.ag_number}"  diff_phone="{$item.ag_phone_num-$item.ag_e_phone}"diff_dispatch="{$item.ag_dispatch_num-$item.ag_e_dispatch}" diff_gvs="{$item.ag_gvs_num-$item.ag_e_gvs}">{$item.ag_name}</option>

{/foreach}