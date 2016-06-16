
<form class="data">
    <table class="base full" >
        <tr class='head' id="user_list">
            <th width="20px"><input autocomplete="off"  type="checkbox" id="checkall" /></th>

            <th width="130px">{"姓名"|L}</th>
            <th width="120px">{"号码"|L}</th>
            <th class="rich none" width="100px">{"类型"|L}</th>
            <th class="rich" width="110px">{"增值功能"|L}</th>
            <th class="rich" width="120px">{"所属群组"|L}</th>
            <th class="rich" width="110px">{"部门"|L}</th>
            <th class="rich" width="100px">{"类型"|L}</th>

            <th class="rich none" width="100px">{"性别"|L}</th>
            <th class="rich none" width="100px">{"职位"|L}</th>
            <th class="rich group none" width="100px">{"部门"|L}</th>
            <th class="rich none" width="100px">{"终端类型"|L}</th>
            <th class="rich none" width="100px">{"机型"|L}</th>
            <th class="rich none" width="100px">{"IMSI"|L}</th>
            <th class="rich none" width="100px">{"IMEI"|L}</th>
            <th class="rich none" width="100px">{"ICCID"|L}</th>
            <th class="rich none" width="100px">{"MAC"|L}</th>
            <th class="rich none" width="100px">{"蓝牙标识号"|L}</th>
            <th class="rich" width="60px">{"详情"|L}</th>
            <th class="rich" width="60px">{"操作"|L}</th>
            <th class="rich" width="70px">{"历史记录"|L}</th>
        </tr>

        {foreach name=list item=item from=$list}
        <tr>
            <td><input onclick="getnum();" autocomplete="off"  type="checkbox" name="checkbox[]" value="{$item.u_number}" class="cb" /></td>
            <td title="{$item.u_name}"><em class="ellipsis" style="width: 265px">{$item.u_name|mbsubstr:5}</em></td>
            <td>{$item.u_number}</td>
            <td class="rich none">{$item.u_sub_type|modtype}</td>
            <td title='{$item.u_p_function|getEListBypjson}' class="rich">{"功能列表"|L}</td>
            <td class="rich">
                <select style="width:100px;" class="only_show">
                     <option value="1">{"点击查看"|L}</option>
                     {if $item.pg_name neq ""}
                            <option style="color:#b81900;">*{$item.pg_name}</option>
                    {/if}
                    {foreach $pg_list as $key=>$val} 
                    {if $item.u_number eq $key  }
                    {foreach $val as $v}
                    <option>{$v.pg_name}</option>
                    {/foreach}
                    {/if}
                    {/foreach}
                </select>
            </td>
            <td class="rich">{$item.ug_name|mbsubstr:3}</td>
            <td class="rich">{if substr($item.u_number,6,1) ==7}{4|modwordtype}{else}{$item.u_sub_type|modwordtype}{/if}{*{1|modwordstatus}{1|modwordprewarning}{1|modwordpreTraffic}*}</td>

            <td class="rich none">{$item.u_sex|modsex}</td>
            <td class="rich none">{$item.u_position}</td>
            <td class="rich none ">{$item.ug_name}</td>
            <td class="rich none">{$item.u_terminal_type}</td>
            <td class="rich none">{$item.u_terminal_model}</td>
            <td class="rich none">{$item.u_imsi}</td>
            <td class="rich none">{$item.u_imei}</td>
            <td class="rich none">{$item.u_iccid}</td>
            <td class="rich none">{$item.u_mac}</td>
            <td class="rich none">{$item.u_zm}</td>
            <td class="rich"><a  {if $item.u_sub_type eq 1}title="{'号码'|L}:【{$item.u_number}】<br />{'姓名'|L}:【{$item.u_name}】<br />{'类型'|L}:【{$item.u_sub_type|modtype}】<br />{'默认群组'|L}:【{$item.pg_name}】<br />{'部门'|L}:【{$item.ug_name}】<br />{'终端类型'|L}:【{$item.u_terminal_type}】<br />{'机型'|L}:【{$item.u_terminal_model}】<br />{*{'UDID'|L}:【{$item.u_udid}】<br />*}{'IMSI'|L}:【{$item.u_imsi}】<br />{'IMEI'|L}:【{$item.u_imei}】<br />{'ICCID'|L}:【{$item.u_iccid}】<br />{'MAC'|L}:【{$item.u_mac}】<br />{*{'蓝牙标识号'|L}:【{$item.u_zm}】<br />*}{'购买日期'|L}:【{$item.u_purch_date}】<br />{'终端序列号'|L}:【{$item.u_terminal_number}】"{elseif $item.u_sub_type eq 2}title="{'号码'|L}:【{$item.u_number}】<br />{'姓名'|L}:【{$item.u_name}】<br />{'类型'|L}:【{$item.u_sub_type|modtype}】<br />{'默认群组'|L}:【{$item.pg_name}】<br />{'部门'|L}:【{$item.ug_name}】"{else}title="{'号码'|L}:【{$item.u_number}】<br />{'姓名'|L}:【{$item.u_name}】<br />{'类型'|L}:【{$item.u_sub_type|modtype}】<br />{'部门'|L}:【{$item.ug_name}】"{/if} class="link tips_title"><span class="icon hand"></span></a></td>
            <td class="rich"><a href="?m=enterprise&a=users_save&e_id={$data.e_id}&u_number={$item.u_number}&do=edit&page={$page}" class="link">{"编辑"|L}</a></td>
            <td class="rich">
                <a href="?m=enterprise&a=users_history&e_id={$data.e_id}&uh_u_number={$item.u_number}&do=edit&page={$page}"class="link view"></a>
            </td>
        </tr>
        {/foreach}
    </table>
    {if $list!=NULL}
    <div class="page none_select rich">
        <div class="num">{$numinfo}</div>
        <div class="turn">
            <a page="{$prev}" class="prev">{"上一页"|L}</a>
            <a page="{$next}" class="next">{"下一页"|L}</a>
        </div>
    </div>
    {/if}
</form>
