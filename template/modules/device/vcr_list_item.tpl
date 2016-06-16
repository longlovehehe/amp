{strip}
    <div class="page none_select">
        <div class="num">{$page.numinfo}</div>
        <div class="turn">
            <a page="{$page.prev}" class="prev">上一页</a>
            <a page="{$page.next}" class="next">下一页</a>
        </div>
    </div>
    
    <table class="base full">
        <tr class='head'>
            <th width="50px">企业名称</th>
            <th width="50px">录音并发数</th>
            <th width="100px">录像并发数</th>
            <th width="100px">存储使用空间</th>
        </tr>
        {foreach name=list item=item from=$list}
            <tr>
                <td>{$item.e_name}</td>
                <td>{$item.e_vcr_audiorec}</td>
                <td>{$item.e_vcr_videorec}</td>
                <td>{$item.e_vcr_space}</td>
            </tr>
        {/foreach}
    </table>

{/strip}