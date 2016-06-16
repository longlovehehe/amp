

<form class="data">
    <table class="base full">
        <tr class='head'>
            <th width="20px"><input autocomplete="off"  type="checkbox" id="checkall" /></th>
            <th width="40px">{"ID"|L}</th>
            <th width="120px">{"设备地址"|L}</th>
            <th class='none' width="120px">{"内网地址"|L}</th>
            <th width="70px">{"设备名称"|L}</th>
            <th width="80px">{"区域"|L}</th>
            <th width="70px">{"用户总数"|L}</th>
            {*<th width="70px">{"并发总数"|L}</th>*}
            <th width="70px">{"SIP端口"|L}</th>
            <th width="70px">{"状态"|L}</th>
            <th width="50px">{"详情"|L}</th>
            <th width="150px">{"操作"|L}</th>
        </tr>
        {foreach name=list item=item from=$list}
        <tr>
            <td><input autocomplete="off"  type="checkbox" name="checkbox[]" value="{if $item.status eq 'no'}{$item.d_id}{else}0{/if}" class="cb" {if $item.status eq 'yes'}disabled{/if} /></td>
            <td>{$item.d_id}</td>
            <td>{$item.d_ip1}</td>
            <td class='none'>{$item.d_ip2}</td>
            <td><span class="ellipsis" style="width: 60px">{if mb_strlen($item.d_name)<=7}{$item.d_name}{else}{$item.d_name|truncate: 12:''}... {/if}</span></td>
            <td><span class="ellipsis" style="width: 60px">{$item.d_area|mod_area_name:option}</span></td>
            <td title="{'手机用户数'|L}: 【{$item.d_phone_user|default:0}】<br />{'调度台用户数'|L}: 【{$item.d_dispatch_user|default:0}】<br />{'GVS用户数'|L}: 【{$item.d_gvs_user|default:0}】">{$item.d_user}<a   class="link tips_title"><span class=" "></span></a></td>
            {*<td>{$item.d_call}</td>*}
            <td>{$item.d_sip_port}</td>
            <td>{$item.d_status|modDeviceStatus}</td>
            <td class="rich"><a  title="{'ID'|L}: 【{$item.d_id}】<br />{'设备地址'|L}: 【{$item.d_ip1}】<br />{'设备名称'|L}: 【{$item.d_name}】<br />{'区域'|L}: 【{$item.d_area|mod_area_name}】<br />{'用户总数'|L}: 【{$item.d_user}】<br />{'可用手机用户数'|L}: 【{$item.diff_phone|default:0} | {$item.d_phone_user|default:0}】<br />{'可用调度台用户数'|L}: 【{$item.diff_dispatch|default:0} | {$item.d_dispatch_user|default:0}】<br />{'可用GVS用户数'|L}: 【{$item.diff_gvs|default:0} | {$item.d_gvs_user|default:0}】<br />{'状态'|L}: 【{$item.d_status|modDeviceStatus}】<br />{'SIP端口'|L}: 【{$item.d_sip_port}】" class="link tips_title"><span class="icon hand"></span></a></td>
            <td>
                {if $item.status eq 'yes'}
                <a title="{'此设备下有企业在用不能编辑'|L}" class="link dis">{"编辑"|L}</a>
                {else}
                <a href="?m=device&a=device_edit&d_id={$item.d_id}" class="link">{"编辑"|L}</a>
                {/if}
                |<a href="javascript:void(0);" class="link" {if $item.d_area != '["#"]'}onclick="new_creat({$item.d_id});"{else}onclick="title_notice();"{/if}>{"区域"|L}</a>|<a href="?m=device&a=device_list&device_id={$item.d_id}&do=mds&d_ip1={$item.d_ip1}" class="link">{if $smarty.cookies.lang eq en_US}Info{else}详情{/if}</a>
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
