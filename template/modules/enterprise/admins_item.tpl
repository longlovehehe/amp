
<table class="base full">
    <tr class='head'>
        <th width="2%"><input autocomplete="off"  type="checkbox" id="checkall" /></th>
        <th width="5%">{"管理员姓名"|L}</th>
        <th width="5%">{"管理员帐号"|L}</th>
        <th class="rich none">{"描述"|L}</th>
        <th class="rich" width="10%">{"手机号"|L}</th>
        <th class="rich none" width="10%">{"安全登录"|L}</th>
        <th class="rich" width="11%">{"邮箱"|L}</th>
        <th class="rich none" width="10%">{"所属企业"|L}</th>
        <th class="rich" width="17%">{"最后登录时间"|L}</th>
        <th class="rich" width="13%">{"最后登录IP"|L}</th>
        <th width="5%">{"操作"|L}</th>
    </tr>
    {foreach name=list item=item from=$list}
    <tr title="{'管理员帐号'|L}:【{$item.em_id}】<br />{'手机号'|L}:【{$item.em_phone}】<br />{'邮箱'|L}: 【{$item.em_mail}】<br />{'所属企业'|L}: 【{$item.e_name}】<br />{'最后登录时间'|L}: 【{$item.em_lastlogin_time}】<br />{'最后登录IP'|L}: 【{$item.em_lastlogin_ip}】<br />{'描述'|L}: 【{$item.em_desc}】">
        <td><input autocomplete="off"  type="checkbox" name="checkbox" value="{$item.em_id}" class="cb" /></td>
        <td>{$item.em_name|mbsubstr: 4}</td>
        <td>{$item.em_id|mbsubstr: 4}</td>
        <td class="rich none">{$item.em_desc}</td>
        <td class="rich">{$item.em_phone}</td>
        <td class="rich none">{$item.em_safe_login|modifierSafeLogin}</td>
        <td class="rich"><span class='ellipsis' >{$item.em_mail|mbsubstr: 10}</span></td>
        <td class="rich none">{$item.e_name}</td>
        <td class="rich">{$item.em_lastlogin_time}</td>
        <td class="rich">{$item.em_lastlogin_ip}</td>
        <td><a href="?m=enterprise&a=admins_edit&e_id={$data.e_id}&em_id={$item.em_id}" class="link">{"编辑"|L}</a></td>
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
{/if}