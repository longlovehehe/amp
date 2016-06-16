{strip}
        {literal}
                <style type="text/css">
                        #tooltip{position:absolute;width:100px;border:1px solid #000;background:#F9D17F;padding:1px;line-height:110%;color:#333;display:none;}
                </style>
        {/literal}
        <script  {'type="ready"'}>
                $(document).ready(function() {
                        var y = 30;
                        var x = 10;
                        $("a.msg").mouseover(function(e) {
                                var msg = '<div id="tooltip">使用中不能编辑</div>';
                                $("body").append(msg);
                                $("#tooltip").css({
                                        "top": e.pageY - y + "px",
                                        "left": e.pageX - x + "px"
                                }).show("fast");
                        }).mouseout(function() {
                                $("#tooltip").remove();
                        }).mousemove(function(e) {
                                $("#tooltip").css({
                                        "top": e.pageY - y + "px",
                                        "left": e.pageX - x + "px"
                                });
                        }).click(function() {
                                this.href = "";
                                return false;
                        });
                });
        </script>
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
                                <th width="50px">录音并发总数</th>
                                <th width="50px">录像并发总数</th>
                                <th width="50px">存储总空间</th>
                                <th width="100px">设备状态</th>
                                <th width="100px">操作</th>
                        </tr>
                        {foreach name=list item=item from=$list}
                                <tr>
                                        <td><input autocomplete="off"  type="checkbox" name="checkbox[]" value="{if $item.status eq 'no'}{$item.d_id}{else}0{/if}" class="cb" {if $item.status eq 'yes'}disabled{/if} /></td>
                                        <td>{$item.d_id}</td>
                                        <td>{$item.d_ip1}</td>
                                        <td>{$item.d_ip2}</td>
                                        <td>{$item.d_name}</td>
                                        <td>{$item.am_name}</td>
                                        <td>{$item.d_type}</td>
                                        <td>{$item.d_audiorec}</td>
                                        <td>{$item.d_videorec}</td>
                                        <td>{$item.d_space} MB</td>
                                        <td>{$item.d_status|modifierStatus}</td>
                                        <td>
                                                <a href="?m=device&a=device_edit&d_id={$item.d_id}" class="link {if $item.status eq 'yes'}msg{/if} ">编辑</a>
                                                <a href="?m=device&a=device_list&device_id={$item.d_id}&do=vcr&d_ip1={$item.d_ip1}" class="link mrlf5">使用详情</a>
                                        </td>
                                </tr>
                        {/foreach}
                </table>
        </form>
{/strip}