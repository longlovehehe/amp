<p class='info none'>{"需要输入完整的用户号码"|L}</p><p class='info none'>{"总共耗时 {$s} 毫秒"|L}</p><form class="data">    <table class="base full">        <tr class='head'>            <th width="100px">{"用户号码"|L}</th>            <th class="rich" width="100px">{"姓名"|L}</th>            <th class="rich" width="100px">{"企业ID"|L}</th>            <th class="rich" width="100px">{"企业名称"|L}</th>            <th class="rich" width="100px">{"用户类型"|L}</th>            <th class="rich" width="100px">{"用户详情"|L}</th>        </tr>        {foreach name=list item=item from=$list}        <tr>            <td>{$item.u_number}</td>            <td>{if mb_strlen($item.u_name)<=5}{$item.u_name}{else}{$item.u_name|truncate: 5:''}... {/if}</td>            <td>{$item.ep.e_id}</td>            <td>{$item.ep.e_name}</td>            <td class="rich">{$item.u_sub_type|modtype}</td>            <td><a href='?m=enterprise&a=users&e_id={$item.ep.e_id}&u_number={$item.u_number}' class='link blue'>{"用户详情"|L}</a></td>        </tr>        {/foreach}    </table></form>{if $list!=NULL}    <div class="page none_select">        <div class="num">{$numinfo}</div>        <div class="turn">            <a page="{$prev}" class="prev">{"上一页"|L}</a>            <a page="{$next}" class="next">{"下一页"|L}</a>        </div>    </div>{/if}