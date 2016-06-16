

<table class="base full">
    <tr class='head'>
        <th class='' width="110px">{"产品代码"|L}</th>
        <th>{"产品名称"|L}</th>
        <th width="100px">{"运营区域"|L}</th>
        <th width="50px">{"产品价格"|L}</th>
        <th class="none" width="50px">{"产品描述"|L}</th>
        <th width="100px">{"操作"|L}</th>
    </tr>
    {foreach name=list item=item from=$list}
    <tr title="{'产品代码'|L}: 【{$item.p_id}】<br />{'产品名称'|L}: 【{$item.p_name}】<br />{'运营区域'|L}: 【{$item.p_area|mod_area_name}】<br />{'产品价格'|L}: 【¥ {$item.p_price}】<br />{'产品描述'|L}: 【{$item.p_desc}】">
        <td class=''><input autocomplete="off"  type="hidden" value="{$item.p_id}" name="p_id">{$item.p_id}</td>
        <td><span class="ellipsis" style="width: 310px">{$item.p_name}</span></td>
        <td><span class="ellipsis" style="width: 90px">{$item.p_area|mod_area_name:option}</span></td>
        <td>{$item.p_price}</td>
        <td class="none">{$item.p_desc}</td>
        <td>
            {if $item.res==1}
            <a class="link" href="?m=product&a=p_edit&p_id={$item.p_id}">{"编辑"|L}</a>
            {if $item.is_used==0 }
            <a id="del" class="mrlf5 link" data="{$item.p_id}" >{"删除"|L}</a>
            {else}
            <a  title="{'此产品有用户在用无法删除'|L}" class="link mrlf5 dis" >{"删除"|L}</a>
            {/if}
            {else}
            <a title="{'本产品区域有不被包含的区域,无法编辑'|L}" class="link dis" >{"编辑"|L}</a>
            <a title="{'本产品区域有不被包含的区域,无法删除'|L}"  class="link mrlf5 dis " >{"删除"|L}</a>
            {/if}
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
{/if}