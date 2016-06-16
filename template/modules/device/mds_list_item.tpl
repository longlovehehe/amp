{strip}
<table class="base full">
    <tr class='head'>
        <th width="50px">{"企业ID"|L}</th>
        <th width="50px">{"企业名称"|L}</th>
        <th width="100px">{"企业总用户数"|L}</th>
        {*<th width="100px">{"企业并发数"|L}</th>*}
        <th width="100px">{"手机用户数"|L}</th>
        <th width="100px">{"调度台用户数"|L}</th>
        <th width="100px">{"GVS用户数"|L}</th>
    </tr>
    {foreach name=list item=item from=$list}
    <tr>
        <td>{$item.e_id}</td>
        <td>{$item.e_name}</td>
        <td>{$item.phone_num+$item.dispatch_num+$item.gvs_num}/{$item.e_mds_users}</td>
        <td>{$item.phone_num}/{$item.e_mds_phone}</td>
        <td>{$item.dispatch_num}/{$item.e_mds_dispatch}</td>
        <td>{$item.gvs_num}/{$item.e_mds_gvs}</td>
    </tr>
    {/foreach}
</table>
<div class="page none_select">
    <div class="num">{$page.numinfo}</div>
    <div class="turn">
        <a page="{$page.prev}" class="prev">{"上一页"|L}</a>
        <a page="{$page.next}" class="next">{"下一页"|L}</a>
    </div>
</div>
{/strip}