{strip}

<h2 class="title">{"{$title}"|L}</h2>

<div class="toptoolbar">

    <a href="?m=agentsub&a=agentsub_add" class="button orange">{"新增代理商子帐号"|L}</a>

</div>

<div class="toolbar">

    <form action="?m=agentsub&a=index_item" id="form" method="post">

        <input autocomplete="off"  name="modules" value="agentsub" type="hidden" />

        <input autocomplete="off"  name="action" value="index_item" type="hidden" />

        <input autocomplete="off"  name="page" value="0" type="hidden" />

        <div class="line">

            <label>{"帐号"|L}：</label>

            <input autocomplete="off"  class="autosend" name="as_account_id" type="text" value="" style="width:110px"/>

        </div>

        {*<div class="line none">

            <label>{"动态登陆"|L}：</label>

            <select name="om_safe_login" style="width:120px">

                <option value="">{"全部"|L}</option>

                <option value="1">{"是"|L}</option>

                <option value="0">{"否"|L}</option>

            </select>

        </div>*}

        {*<div class="line none">

            <label>{"管理区域"|L}：</label>

            <select name="om_area" class="autofix" action="?m=area&a=option">

                <option value="#">{"全部"|L}</option>

            </select>

        </div>*}

        <div class="line">

            <label>{"手机号"|L}：</label>

            <input autocomplete="off"  class="autosend" name="as_phone" type="text" value="" style="width:110px"/>

        </div>

        <div class="line">

            <label>{"邮箱"|L}：</label>

            <input autocomplete="off"  class="autosend" name="as_mail" type="text" value="" style="width:110px"/>

        </div>



        <div class="line">

            <label>{"最后登录时间"|L}：</label>

            <input autocomplete="off"  class="datepicker start" name="start" type="text" date="true" />

            <span>-</span>

            <input autocomplete="off"  class="datepicker end" name="end" type="text" date="true" />

        </div>

        <div class="buttons right">

            <a form="form" class="button submit" >{"查询"|L}</a>

        </div>

    </form>

</div>



<div class="toolbar">

    <a id="delall" class="button">{"批量删除"|L}</a>

</div>

<div class="content"></div>

<div id="dialog-confirm" class="hide" title="{'删除选中项'|L}？">

    <p>{"确定要删除选中的管理员吗"|L}?</p>

</div>

{/strip}