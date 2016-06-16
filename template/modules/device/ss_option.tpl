{if $smarty.request.d_deployment_id == ''}
<option value="" d_recnum="0" disabled="disabled" style="color: #000" diff_rec="0" >{"请先选择一个SS"|L}</option>
{else}
{foreach name=list item=item from=$list}
<option {if $e_ss_id == $item.d_id} selected="selected" {/if} value="{$item.d_id}" d_deployment_id="{$item.d_deployment_id}" style="font-size:16px" d_space_free="{$item.d_space_free}" d_space="{$item.d_space}" >{"设备名称"|L}：{$item.d_name} {"外网IP"|L}：【{$item.d_ip2}】{"内网IP"|L}：【{$item.d_ip1}】</option>
<option value="" disabled="disabled" >【{"可用/总空间"|L}：{$item.d_space_free} / {$item.d_space} M】</option>
{foreachelse}
<option value="" disabled="disabled" style="color: #000" d_recnum="0" diff_rec="0">{"该区域下没有可使用设备"|L}</option>
{/foreach}
{/if}


