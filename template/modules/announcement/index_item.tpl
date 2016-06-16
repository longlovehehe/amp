{if $smarty.session.own.om_id != 'admin'}
<p style="margin-bottom: 10px;">{"提示讯息：你在这里只能看到你自己发布的公告,以及草稿"|L}</p>
{/if}

<form class="data">
    <table class="base full">
        <tr class='head'>
            <th width="170px">{"公告标题"|L}</th>
            <th width="50px">{"可见区域"|L}</th>
            <th width="50px">{"状态"|L}</th>
            <th width="100px">{"发布时间"|L}</th>
            {if $smarty.session.own.om_id eq admin}<th width="50px">{"发布人"|L}</th>{/if}
            <th width="50px">{"操作"|L}</th>
        </tr>
        {foreach name=list item=item from=$list}
        <tr title="{'公告标题'|L}: 【{$item.an_title}】<br />{'可见区域'|L}: 【{$item.an_area|mod_area_name}】<br />{'状态'|L}: 【{$item.an_status|an_status}】<br />{'发布时间'|L}: 【{$item.an_time}】">
            <td>
                <input autocomplete="off"  type="hidden" value="{$item.an_area_id}" name="an_area_id">
                <span class="ellipsis" style="width: 280px">
                    <a class="alink" href="?m=announcement&a=an_details&an_id={$item.an_id}">{$item.an_title}</a>
                </span>
            </td>
            <td>
                <span class="ellipsis" style="width: 50px">{$item.an_area|mod_area_name:option}</span>
            </td>
            <td>{$item.an_status|an_status}</td>
            <td>{$item.an_time}</td>
            <td class='{"none"|notadmin}'>{$item.an_user}</td>
            <td>
                <a class="link" href="?m=announcement&a=an_edit&an_id={$item.an_id}">{"编辑"|L}</a>
                &nbsp;
                <a id="del" class="link" data="{$item.an_id}">{"删除"|L}</a>
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