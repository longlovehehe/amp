

<form class="data">
    <table class="base full">
        {foreach name=list item=item from=$list}
        <tr style="padding:0px;">
            <td><input autocomplete="off"  type="checkbox" name="checkbox[]" value="{$item.g_iccid}" class="cb" {if $item.status eq 'yes'}disabled{/if} /></td>
            <td><div style="width: 40px;">{if $item.g_agents_id eq 0}未出库{else}已出库{/if}</div></td>
            <td><div style="width: 30px;">{$item.g_packages|gprs_pack}</div></td>
            <td><div style="width: 85px;">{$item.g_iccid}</div></td>
            <td><div style="width: 50px;"><span class="ellipsis" style="width: 60px">{if mb_strlen($item.g_belong)<=7}{$item.g_belong}{else}{$item.g_belong|truncate: 12:''}... {/if}</span></div></td>
            <td><div style="width: 75px;"><span class="ellipsis" style="width: 60px">{$item.g_intime}</span></div></td>
            <td><div style="width: 80px;">{$item.g_outtime}</div></td>
            <td><div style="width: 80px;">{$item.g_start_time}</div></td>
            <td><div style="width: 60px;">{if $item.g_agents_id eq 0}omp{else}{$item.ag_name}{/if}</div></td>
            <td><div style="width:40px;">{$item.g_final_user}</div></td>
            <td class="rich none"><div style="width: 40px;"><a  title="设备ID: 【{$item.d_id}】<br />外网地址: 【{$item.d_ip1}】<br />设备名称: 【{$item.d_name}】<br />区域: 【{$item.d_area|mod_area_name}】<br />用户总数: 【{$item.d_user}】<br />可用手机用户数: 【{$item.diff_phone|default:0} | {$item.d_phone_user|default:0}】<br />可用调度台用户数: 【{$item.diff_dispatch|default:0} | {$item.d_dispatch_user|default:0}】<br />可用GVS用户数: 【{$item.diff_gvs|default:0} | {$item.d_gvs_user|default:0}】<br />状态: 【{$item.d_status|modDeviceStatus}】<br />SIP端口: 【{$item.d_sip_port}】" class="link tips_title"><span class="icon hand"></span></a></div></td>

        </tr>
        {/foreach}
    </table>
</form>
