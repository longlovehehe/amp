<style type="text/css">
    .tablebox tbody
    {
        font-family: Arial,Helvetica,sans-serif;
        background-color:#FFFFFF;
        overflow:auto;
    }
    .tablescr{
        overflow-y:auto;
        border:1px solid ;
    }

</style>
<script src="script/plugins/intlTelInput.js"></script>
{strip}
<div class="toolbar">
    {*<a href="?m=product&a=index" class="button none">{"产品管理"|L}</a>*}
    <a href="?m=product&a=p_function" class="button">{"产品功能库"|L}</a>
    <a href="?m=product&a=p_basic" class="button active">{"价格配置"|L}</a>
</div>
{*<a href="?m=product&a=add_bprice" style="float:right;" class="button">{"增加"|L}</a>*}
<h2 class="title">{"价格配置"|L}</h2>
<div class="">
<input type="text" class="currencycode" style="width: 50px;height: 24px;" readonly="true" name="units_price" value="{$info.units_price}"/>
<a id="units_set" class="button">{"设置价格单位"|L}</a>
</div>
{if $smarty.session.own.om_id eq admin1}
{*<form id="form" action="?modules=product&action=p_save" method="post" class="base mrbt10">
    <div class="toolbar">
        <input autocomplete="off"  name="page" value="0" type="hidden" />
        <input autocomplete="off"  type="hidden" id="sel">
        <div class="line">
            <label>{"功能名称"|L}：</label>
            <input autocomplete="off"  class="autosend" name="pi_name" maxlength="32" type="text" required="TRUE" />
        </div>
        <div class="line">
            <label>{"功能编号"|L}：</label>
            <input value="gn_" autocomplete="off" class="autosend" pi_code="true" name="pi_code" type="text" required="TRUE"  />
        </div>
        <div class="line">
            <label>{"功能状态"|L}：</label>
            <input autocomplete="off" maxlength="128" class="autosend" pi_status="true" name="pi_status" type="text" required="TRUE"  />
        </div>
        <a form="form" class="ajaxpostr button normal">{"新增功能"|L}</a>
    </div>
</form>*}
{/if}

<form id="form" class="base mrbt10" action="?modules=product&action=price_save">
    <input value="{$smarty.session.ag.ag_number}"  name="id" type="hidden" />
    <input type="hidden" style="width:100px;height: 24px;" name="units_price" value="SGD"/>
   <div class="block">
        <label class="title">{"基本功能价格"|L}：</label>
        {$info.units_price|default:"SGD"} &nbsp;
        <input rmb="true" autocomplete="off" style="width:100px;" value="{$info.basic_price}"  maxlength="16" chinese="true" name="basic_price" type="text" />
        
    </div>
    <div class="block">
        <label class="title">{"Console用户价格"|L}：</label>
                {$info.units_price|default:"SGD"} &nbsp;
        <input rmb="true" autocomplete="off" style="width:100px;" value="{$info.console_price}"  maxlength="16" chinese="true" name="console_price" type="text" />
    </div>
    <div class="block">
        <label class="title">{"短消息"|L}：</label>
                {$info.units_price|default:"SGD"} &nbsp;
        <input rmb="true" autocomplete="off" style="width:100px;" value="{$info.gn_dxx}"  maxlength="32" chinese="true" name="gn_dxx" type="text" />
    </div>
    <div class="block">
        <label class="title">{"语音通话"|L}：</label>
                {$info.units_price|default:"SGD"} &nbsp;
        <input rmb="true" autocomplete="off" style="width:100px;"  value="{$info.gn_yythkt}"  maxlength="32" chinese="true" name="gn_yythkt" type="text" />
    </div>
    <div class="block none">
        <label class="title">{"语音会议"|L}：</label>
                {$info.units_price|default:"SGD"} &nbsp;
        <input rmb="true" autocomplete="off" style="width:100px;" value="{$info.gn_yyhy}"  maxlength="32" chinese="true" name="gn_yyhy" type="text" />
    </div>
    <div class="block">
        <label class="title">{"图片拍传"|L}：</label>
                {$info.units_price|default:"SGD"} &nbsp;
        <input rmb="true" autocomplete="off" style="width:100px;" value="{$info.gn_tppch}"  maxlength="32" chinese="true" name="gn_tppch" type="text" />
    </div>
    <div class="block">
        <label class="title">{"GPS定位"|L}：</label>
                {$info.units_price|default:"SGD"} &nbsp;
        <input rmb="true" autocomplete="off" style="width:100px;" value="{$info.gn_gps}"  maxlength="32" chinese="true" name="gn_gps" type="text" />
    </div>
    <div class="block">
        <label class="title">{"对讲地图"|L}：</label>
                {$info.units_price|default:"SGD"} &nbsp;
        <input rmb="true" autocomplete="off" style="width:100px;"  value="{$info.gn_djdtmsh}"  maxlength="32" chinese="true" name="gn_djdtmsh" type="text" />
    </div>
    <div class="block">
        <label class="title">{"视频业务"|L}：</label>
                {$info.units_price|default:"SGD"} &nbsp;
        <input rmb="true" autocomplete="off" style="width:100px;" value="{$info.gn_shpyw}"  maxlength="32" chinese="true" name="gn_shpyw" type="text" />
    </div>
    {if $smarty.session.ident=='VT'}
    <div class="block">
        <label class="title">VAS：</label>
                {$info.units_price|default:"SGD"} &nbsp;
        <input rmb="true" autocomplete="off" style="width:100px;" value="{$info.gn_vas}"  maxlength="32" chinese="true" name="gn_vas" type="text" />
    </div>
    {/if}
    
    <div class="buttons mrtop40">
        <a goto="?m=product&a=p_basic" form="form" class="ajaxpost button normal">{"保存"|L}</a>
        <a class="goback button">{"取消"|L}</a>
    </div>
</form>
{/strip}   