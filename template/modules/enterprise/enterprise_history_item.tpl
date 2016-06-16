{strip}
<form class="data">
    <table class="base full">
        <tr class='head'>
            <th width="10px">{"编号"|L}</th>
            <th class="" width="140px">{"变更时间"|L}</th>
            <th class="" width="220px">{"变更前"|L}</th>
            <th class="" width="220px">{"变更后"|L}</th>
            <th class="" width="80px">{"操作者"|L}</th>
        </tr>
        {foreach name=list item=item from=$list}
            <tr>
                <td class="">{$smarty.foreach.list.iteration}</td>
                <td>{$item.eh_change_time}</td>
                <!-- |date_format:"Y-m-d H:i:s" -->
                <td>
                    <!-- <div class="div" style="max-height:100px;overflow:auto;"> -->
                        {foreach from=$item.fileds item=fitem}
                            {$fitem.remark|L}：{$fitem.old_value|L}<br/>
                        {/foreach}
                    <!-- </div> -->
                </td>
                <td>
                    <!-- <div class="div" style="max-height:100px;overflow:auto;"> -->
                        {foreach from=$item.fileds item=fitem}
                            {$fitem.remark|L}：{$fitem.new_value|L}<br/>
                        {/foreach}
                    <!-- </div> -->
                </td>
                <td>{$item.eh_do_username}</td>
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
{/strip}