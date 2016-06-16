{foreach name=list item=item from=$list}
        <option value="{$item.d_id}" d_space="{$item.diff_space}" d_audiorec="{$item.diff_audiorec}" d_videorec="{$item.diff_videorec}">设备名称：{$item.d_name}【{$item.d_ip1}】 可用录音并发数：{$item.diff_audiorec} | 可用录像并发数{$item.diff_videorec}| 可用存储空间{$item.diff_space} MB</option>
{/foreach}