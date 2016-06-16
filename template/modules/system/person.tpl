
<div class="toolbar">
    <a href="?modules=system&action=resetpassword" class="button">密码修改</a>
</div>

<div class="toptoolbar">
    <a href="?modules=system&action=person&do=edit" class="button">修改个人信息</a>
</div>

<!-- 基本信息 -->
<ul class="list">
    <li>
        <span>姓名：</span>
        <span>{$own.om_id}</span>
    </li>
    <li>
        <span>管理员级别：</span>
        <span>{$own.om_type}</span>
    </li>
    <li>
        <span>管理区域：</span>
        <span>{$own.om_area}</span>
    </li>
    <li>
        <span>管辖企业：</span>
        <span></span>
    </li>
    <li>
        <span>管辖设备：</span>
        <span></span>
    </li>
</ul>
<ul class="list">
    <li>
        <span>上次登录地址：</span>
        <span>{$own.om_lastlogin_ip}</span>
    </li>
    <li>
        <span>上次登录时间：</span>
        <span>{$own.om_lastlogin_time}</span>
    </li>
</ul>
