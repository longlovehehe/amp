<form class="data">
    <table class="base full">
        <tr class='head'>
            {*<th width="25px"><input autocomplete="off"  type="checkbox" id="checkall" /></th>*}
            <th width="70px">日期</th>
            <th width="80px">个数</th>
        </tr>
        {foreach name=list item=item from=$list}
        <tr>
            {*<td><input autocomplete="off"  type="checkbox" name="checkbox" value="{if $item.om_id  neq 'admin'}{$item.om_id}{/if}" class="cb" {if $item.om_id  eq 'admin'}disabled{/if}/></td>*}
            <td>{$item.om_lastlogin_time|default:'暂无记录'}</td>
            <td>{$item.om_lastlogin_ip|default:'暂无记录'}</td>
        </tr>
        {/foreach}
    </table>
</form>