<table class="base full content">
    <tr class="head">
        <th width="100px">{"发布日期"|L}</th>
        <th>{"公告标题"|L}</th>
        <th width="100px">{"发布区域"|L}</th>
    </tr>

    {foreach name=getAnList item=item from=$getAnList}
    <tr>
        <td>{$item.an_time|date_format:"%Y-%m-%d"}</td>
        <td>
            <span class="ellipsis" style="width: 450px">
                <a href="?m=system&a=pro_details&an_id={$item.an_id}" class="alink">{$item.an_title}</a>
            </span>
        </td>

        <td><span class="ellipsis" style="width: 80px">{$item.an_area|mod_area_name:option} </span> </td>
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