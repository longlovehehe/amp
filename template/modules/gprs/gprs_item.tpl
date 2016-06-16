

<form class="data">
    <table class="base full">
        <tr class='head'>
            {*<th class="" width="20px"><input autocomplete="off"  type="checkbox" id="checkall" /></th>*}
            <th width="40px"></th>
            <th width="60px">{"状态"|L}</th>
            <th width="40px">{"套餐"|L}</th>
            <th width="140px">{"ICCID"|L}</th>
            <th width="50px">{"归属地"|L}</th>
            <th width="80px">{"入库日期"|L}</th>
            <th width="80px">{"出库日期"|L}</th>
            <th width="80px">{"起始日期"|L}</th>
            <th width="70px">{"当前位置"|L}</th>
            <th width="70px">{"最后编辑人"|L}</th>
            <th class="none" width="50px">{"详情"|L}</th>
            <th width="40px">{"操作"|L}</th>
        </tr>
        {foreach name=list item=item from=$list}
        <tr>
            {*<td class="none"><input autocomplete="off"  type="checkbox" name="checkbox[]" value="{if $item.status eq 'no'}{$item.d_id}{else}0{/if}" class="cb" {if $item.status eq 'yes'}disabled{/if} /></td>*}
            <td></td>
            <td>{if $item.g_agents_id eq 0 && $item.g_e_id eq null}未出库{else}已出库{/if}</td>
            <td>{$item.g_packages|gprs_pack}</td>
            <td>{$item.g_iccid}</td>
            <td><span class="ellipsis" style="width: 60px">{if mb_strlen($item.g_belong)<=7}{$item.g_belong}{else}{$item.g_belong|truncate: 12:''}... {/if}</span></td>
            <td><span class="ellipsis" style="width: 60px">{$item.g_intime}</span></td>
            <td>{$item.g_outtime}</td>
            <td>{$item.g_start_time}</td>
            <td>{if $item.g_agents_id eq 0 && $item.g_e_id eq null}omp{else}{if $item.g_agents_id != 0 && $item.g_e_id eq null }{$item.ag_name}{else}{$item.g_e_id|getenname}{/if}{/if}</td>
            <td>{$item.g_final_user}</td>
            <td class="rich none"><a  title="设备ID: 【{$item.d_id}】<br />外网地址: 【{$item.d_ip1}】<br />设备名称: 【{$item.d_name}】<br />区域: 【{$item.d_area|mod_area_name}】<br />用户总数: 【{$item.d_user}】<br />可用手机用户数: 【{$item.diff_phone|default:0} | {$item.d_phone_user|default:0}】<br />可用调度台用户数: 【{$item.diff_dispatch|default:0} | {$item.d_dispatch_user|default:0}】<br />可用GVS用户数: 【{$item.diff_gvs|default:0} | {$item.d_gvs_user|default:0}】<br />状态: 【{$item.d_status|modDeviceStatus}】<br />SIP端口: 【{$item.d_sip_port}】" class="link tips_title"><span class="icon hand"></span></a></td>
            <td>
                <a href="?m=gprs&a=gprs_edit&g_iccid={$item.g_iccid}&do=edit" class="link">{"编辑"|L}</a>
            </td>
        </tr>
        {/foreach}
    </table>


    {if $list!=NULL}
    <div class="page none_select">
        <div class="num">{$numinfo}</div>
        <div class="turn">
            <a page="{$prev}" class="prev">{"上一页"|L}</a>
            <a page="{$next}" class="next">{"下一页"|L}</a>
        </div>
    </div>
</form>
{/if}
