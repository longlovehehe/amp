
<div class="toolbar">
    <a href="?modules=system&action=person" class="button active">个人信息</button>
        <a href="?modules=system&action=resetpassword" class="button">密码修改</a>
        <a href="?modules=system&action=resetphone" class="button">手机修改</a>
        <a href="?modules=system&action=face" class="button">头像修改</a>
</div>

<div class="face">
    <img src="static/style/img/base/cover.png" />
    <a href="?modules=system&action=face" class="link">修改头像</a>
</div>
<!-- 基本信息 -->
<form>
    <ul class="list">
        <li>
            <span>姓名：</span>
            <input autocomplete="off"  />
        </li>
        <li>
            <span>性别：</span>
            <input autocomplete="off"  value="男" name="sex" type="radio"/>男
            <input autocomplete="off"  value="女" name="sex" type="radio" />女
        </li>
    </ul>
    <a class="button">保存</a>
</form>