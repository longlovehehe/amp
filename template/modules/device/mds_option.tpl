{if $smarty.request.d_area == '@'}
<option value="" d_user="0" disabled="disabled" style="color: #000" d_phone_user="0" d_dispatch_user="0" d_gvs_user="0" d_call="0" diff_phone="0" diff_dispatch="0" diff_gvs="0">{"请先选择一个区域"|L}</option>
{else}
{foreach name=list item=item from=$list}
<option {if $e_mds_id == $item.d_id} selected="selected" {/if} value="{$item.d_id}" d_deployment_id="{$item.d_deployment_id}" style="font-size:16px" d_user="{$item.diff_user|modusercall}" d_phone_user="{$item.d_phone_user}" d_dispatch_user="{$item.d_dispatch_user}" d_gvs_user="{$item.d_gvs_user}" d_call="{$item.diff_call}" diff_phone="{$item.diff_phone}" diff_dispatch="{$item.diff_dispatch}" diff_gvs="{$item.diff_gvs}">{"设备名称"|L}：{$item.d_name}【{$item.d_ip2}】</option>
<option value="" disabled="disabled" >【{"可用用户数"|L}：{$item.diff_user|modusercall} | {"可用手机用户数"|L}：{$item.diff_phone} | {"可用调度台用户数"|L}：{$item.diff_dispatch} | {"可用GVS用户数"|L}：{$item.diff_gvs}】</option>
{foreachelse}
<option value="" disabled="disabled" style="color: #000" d_user="0" d_phone_user="0" d_dispatch_user="0" d_gvs_user="0" d_call="0" diff_phone="0" diff_dispatch="0" diff_gvs="0">{"该区域下没有可使用设备"|L}</option>
{/foreach}
{/if}


