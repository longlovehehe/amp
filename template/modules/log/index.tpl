<style>    .toolbar label{        margin-right: 5px;    }    .toolbar label input{        position: relative;        top: 2px;    }</style>{strip}<h2 class="title">{$title}</h2><script  {'type="ready"'}>    $('nav a.log').addClass('active');</script><form id="form" action="?modules=log&action=index_item" method="post">    <div class="toolbar">        <input autocomplete="off"  name="modules" value="log" type="hidden" />        <input autocomplete="off"  name="action" value="index_item" type="hidden" />        <input autocomplete="off"  name="page" value="0" type="hidden" />        <div class="line">            <label>{"日志级别"|L}：</label>            <select name="el_level" style="width:130px;" value="{$smarty.request.el_level}" class="autoedit">                <option value="">{"全部"|L}</option>                <option value="2">{"错误"|L}</option>                <option value="1">{"警告"|L}</option>                <option value="0">{"信息"|L}</option>            </select>        </div>        <div class="line none">            <label>{"日志编号"|L}：</label>            <input autocomplete="off"  class="autosend" el_id="true" name="el_id" type="text" />        </div>        {if $smarty.session.own.om_id == 'admin'}        <div class="line none">            <label>{"来源用户"|L}：</label>            <input autocomplete="off"  class="autosend" name="el_user" type="text" />        </div>        {/if}        <div class="line">            <label>{"日志内容"|L}：</label>            <input autocomplete="off"  class="autosend" name="el_content" type="text" />        </div>        <div class="line">            <label>{"创建时间"|L}：</label>            <input autocomplete="off"  class="datepicker start" name="start" type="text" datatime='true' />            <span>-</span>            <input autocomplete="off"  class="datepicker end" name="end" type="text" datatime="true" />        </div>    </div>    <div class="toolbar">        <label>{"来源模块"|L}：</label>        <label><input autocomplete="off"  type="checkbox" name="el_type[]" value="7"/>{"登录模块"|L}</label>        <label><input autocomplete="off"  type="checkbox" name="el_type[]" value="1"/>{"企业模块"|L}</label>        {*<label><input autocomplete="off"  type="checkbox" name="el_type[]" value="2"/>{"设备模块"|L}</label>        {if $smarty.session.own.om_id eq admin}<label><input autocomplete="off"  type="checkbox" name="el_type[]" value="3"/>{"角色模块"|L}</label>{/if}        {if $smarty.session.own.om_id eq admin}<label><input autocomplete="off"  type="checkbox" name="el_type[]" value="4"/>{"区域模块"|L}</label>{/if}        <label><input autocomplete="off"  type="checkbox" name="el_type[]" value="5"/>{"产品模块"|L}</label>        {*<label><input autocomplete="off"  type="checkbox" name="el_type[]" value="6"/>{"日志模块"|L}</label>*}         {*<label><input autocomplete="off"  type="checkbox" name="el_type[]" value="8"/>{"公告模块"|L}</label>*}         <label><input autocomplete="off"  type="checkbox" name="el_type[]" value="9"/>{"代理商模块"|L}</label>        <a form="form" class="button submit" style="margin-left: 10px">{"查询"|L}</a>    </div></form><div class="content"></div>{/strip}