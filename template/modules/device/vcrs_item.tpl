{strip}
        <div class="page none_select">
                <div class="num">{$numinfo}</div>
                <div class="turn">
                        <a page="{$prev}" class="prev">上一页</a>
                        <a page="{$next}" class="next">下一页</a>
                </div>
        </div>
        <form class="data">
                <table class="base full">
                        <tr class='head'>
                                <th width="50px"><input autocomplete="off"  type="checkbox" id="checkall" />全选</th>
                                <th width="100px">设备ID号</th>
                                <th width="150px">外网地址</th>
                                <th width="150px">内网地址</th>
                                <th>设备名称</th>
                                <th width="100px">设备所属区域</th>
                                <th width="100px">设备类型</th>
                                <th width="150px">存储总空间</th>
                                <th width="150px">设备状态</th>
                                <th width="100px">操作</th>
                        </tr>
                        {foreach name=list item=item from=$list}
                                <tr>
                                        <td><input autocomplete="off"  type="checkbox" name="checkbox[]" value="{$item.d_id}" class="cb" /></td>
                                        <td>{$item.d_id}</td>
                                        <td>{$item.d_ip1}</td>
                                        <td>{$item.d_ip2}</td>
                                        <td>{$item.d_name}</td>
                                        <td>{$item.am_name}</td>
                                        <td>{$item.d_type}</td>
                                        <td>{$item.d_space} MB</td>
                                        <td>{$item.d_status|modifierStatus}</td>

                                        <td>
                                                <a href="?modules=device&action=device_edit&d_id={$item.d_id}" class="link">编辑</a>
                                                {*<a href="?modules=device&action=device_list&device_id={$item.d_id}&do=vcr&d_ip1={$item.d_ip1}" class="link mrlf5">使用详情</a>*}
                                        </td>
                                </tr>
                        {/foreach}
                </table>
        </form>
{/strip}