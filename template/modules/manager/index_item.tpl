
<div id="dialog-confirm-reset" class="hide" title="{'重置密码'|L}?">
    <p>{"确定要重置管理员密吗"|L}?</p>
</div>

<form class="data">
    <table class="base full">
        <tr class='head'>
            <th width="25px"><input autocomplete="off"  type="checkbox" id="checkall" /></th>
            <th width="70px">{"管理员帐号"|L}</th>
            <th width="80px">{"管理区域"|L}</th>
            <th class='none' width="150px">{"描述"|L}</th>
            <th width="100px">{"手机号"|L}</th>
            <th class='none' width="30px">{"动态登陆"|L}</th>
            <th width="140px">{"邮箱"|L}</th>
            <th width="160px">{"最后登录时间"|L}</th>
            <th width="130px">{"最后登录IP"|L}</th>
            <th width="120px">{"操作"|L}</th>
        </tr>
        {foreach name=list item=item from=$list}
        <tr title="{'管理员帐号'|L}: 【{$item.om_id|escape:'html'}】<br />{'管理区域'|L}: 【{$item.om_area|mod_area_name}】<br />{'手机号'|L}: 【{$item.om_phone|default:{'未填写'|L}}】<br />{'邮箱'|L}: 【{$item.om_mail|default:{'未填写'|L}}】<br />{'最后登录时间'|L}: 【{$item.om_lastlogin_time|default:{'暂无记录'|L}}】<br />{'最后登录IP'|L}: 【{$item.om_lastlogin_ip|default:{'暂无记录'|L}}】<br />{'描述'|L}: 【{$item.om_desc|default:{'未填写'|L}}】">
            <td><input autocomplete="off"  type="checkbox" name="checkbox" value="{if $item.om_id  neq 'admin'}{$item.om_id}{/if}" class="cb" {if $item.om_id  eq 'admin'}disabled{/if}/></td>
            <td><span class="ellipsis" style="width: 60px">{$item.om_id|escape:"html"}</span></td>
            <td class="info"><span class="ellipsis" style="width: 30px">{$item.om_area|mod_area_name:option}</span></td>
            <td class="none info">{$item.om_desc|default:{'未填写'|L}}</td>
            <td>{$item.om_phone|default:{'未填写'|L}}</td>
            <td class='none'>{$item.om_safe_login}</td>
            <td><span class='ellipsis' >{$item.om_mail|default:{'未填写'|L}}</span></td>
            <td>{$item.om_lastlogin_time|default:{"暂无记录"|L}}</td>
            <td>{$item.om_lastlogin_ip|default:{'暂无记录'|L}}</td>
            <td>
                <a href="?m=manager&a=om_edit&om_id={$item.om_id}&flag=edit" class="link">{"编辑"|L}</a>
                <a data="?m=manager&a=om_reset&reset_id={$item.om_id}" class="link reset">{"重置密码"|L}</a>
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