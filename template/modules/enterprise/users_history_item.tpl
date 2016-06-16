{strip}
<form class="data">
    <table class="base full">
        <tr class='head'>
            <th width="10px">{"编号"|L}</th>
            <th class="" width="50px">{"名称"|L}</th>
            {*<th class="" width="50px">{"企业ID"|L}</th>*}
{*            <th class="" width="80px">{"IMEI"|L}</th>*}
            {if $info.u_sub_type eq 1}
            <th class="" width="200px">{"终端属性"|L}</th>
            <th class="" title="{"终端状态"|L}" width="40px">{"状态"|L}</th>
           {* <th class="" width="130px">ICCID</th>
            <th class="" width="80px">{"IMSI"|L}</th>*}
            <th class="" width="240px">{"流量卡属性"|L}</th>
            <th class="" title="{"流量卡状态"|L}" width="40px">{"状态"|L}</th>
            {/if}
            <th class="" width="60px">{"用户状态"|L}</th>
            <th class="" width="90px">{"变更时间"|L}</th>
            <th class="" width="20px">{"用户分类"|L}</th>
        </tr>
        {foreach name=list item=item from=$list}
            <tr>
                <td class="">{$smarty.foreach.list.iteration}</td>
                <td title="{$item.uh_u_name}">{$item.uh_u_name|mbsubstr:5}</td>
                {*<td>{$item.th_e_id}</td>*}
                {if $info.u_sub_type eq 1}
                <td>IMEI：{$item.uh_md_imei}<br/>
                {"终端类型"|L}：{$item.uh_md_type}</td>
                <td>{if $item.uh_md_status eq "start"}<span class="img_start"></span>{else if $item.uh_md_status eq "stop"}<span class="img_stop"></span>{else if $item.uh_md_status eq "unbind"}<span class="img_unbind"></span>{else}{/if}</td>
                <td>ICCID：{$item.uh_gp_iccid}<br />
                IMSI：{$item.uh_gp_imsi}<br />
                {"手机号"|L}：{$item.uh_gp_mobile}</td>
                <td>{if $item.uh_gp_status eq "start"}<span class="img_start"></span>{else if $item.uh_gp_status eq "stop"}<span class="img_stop"></span>{else if $item.uh_gp_status eq "unbind"}<span class="img_unbind"></span>{else}{/if}</td>
                {/if}
                <td>{if $item.uh_user_status eq "start"}<span class="img_start"></span>{else if $item.uh_user_status eq "stop"}<span class="img_stop"></span>{else if $item.uh_user_status eq "unbind"}<span class="img_unbind"></span>{else}{/if}</td>
                <td>{$item.uh_change_time|date_format:"Y-m-d"}</td>
                <td>{$item.uh_attr_type|attrType}</th>
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
<div class="buttom">
    <span class="img_start">{"启用"|L}</span>
    <span class="img_stop">{"停用"|L}</span>
    <span class="img_unbind">{"解绑"|L}</span>
</div>
{/strip}