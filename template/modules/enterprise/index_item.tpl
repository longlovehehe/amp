
<form class="data">
    <table class="base full">
        <tr class='head'>
            <th width="20px"><input autocomplete="off"  type="checkbox" id="checkall" /></th>
            {if $lang=='en_US'}
                <th width="60px">E.ID</th>
            {else}
                <th width="60px">{"编号"|L}</th>
            {/if}
            <th width="190px">{"企业名称"|L}</th>
            <th width="60px">{"用户总数"|L}</th>
            <th width="60px">{"手机"|L}</th>
            <th width="60px">{"调度台"|L}</th>
            <th width="60px">{"GVS"|L}</th>
            <th class="rich " width="100px">{"区域"|L}</th>
            <th class="rich " width="80px">{"状态"|L}</th>
            <th class="rich none" width="120px">{$smarty.session.ident}-Server</th>
            <th class="rich none" width="120px">VCR</th>
            <th width="50px">{"操作"|L}</th>
        </tr>
        {foreach name=list item=item from=$list}
        <tr title="{'企业编号'|L}: 【{$item.e_id}】<br />{'企业名称'|L}: 【{$item.e_name}】<br />{'区域'|L}: 【{$item.e_area|mod_area_name}】<br />{'状态'|L}: 【{$item.e_status|modifierStatus}】 <br />{$smarty.session.ident}-Server: 【{$item.mds_d_name}】<br/>{$smarty.session.ident}-RS: 【{if $item.rs_d_name}{$item.rs_d_name}{else}{'无'|L}{/if}】<br/>{$smarty.session.ident}-SS: 【{$item.ss_d_name}】<br/>{'创建者'|L}:【{$item.e_create_name|getompman}】<br/>{'企业创建时间'|L}:【{$item.e_create_time}】">
            <td><input autocomplete="off"  type="checkbox" name="checkbox[]" value="{$item.e_id}" class="cb" /></td>
            <td>{$item.e_id}</td>
            <td><span class='ellipsis' style='width: 430px'>{$item.e_name|mbsubstr:24}</span></td>
            <td>{$item.e_mds_users}</td>
            <td>{$item.e_mds_phone}</td>
            <td>{$item.e_mds_dispatch}</td>
            <td>{$item.e_mds_gvs}</td>
            <td class="rich ">{$item.e_area|mod_area_name|mbsubstr:5}</td>
            <td class="rich ">{$item.e_status|modifierStatus}</td>
            <td class="rich none">{$item.mds_d_ip1}</td>
            <td class="rich none">{$item.vcr_d_ip1}</td>
            <td><a href="?m=enterprise&a=view&e_id={$item.e_id}" class="link">{"管理"|L}</a></td>
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