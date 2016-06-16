{strip}
<!--代理商企业三级联动-->
<h2 class="title"><span class='ellipsis2' style='max-width: 350px;height: 20px;'>{"计费报表"|L}</h2>
<div class="toptoolbar"> <a class="export button">{"导出"|L}</a></div>
{*<div class="block">
    <a class="export button">{"导出"|L}</a>
</div>*}
<form id="form" action="?m=account&a=account_item" method="post">
    <input autocomplete="off"  name="modules" value="account" type="hidden" />
    <input autocomplete="off"  name="action" value="account_item" type="hidden" />
    <input autocomplete="off"  name="page" value="0" type="hidden" />
    <input autocomplete="off"  name="ep_id" value="{if $smarty.request.ep_id neq ""}{$smarty.request.ep_id}{else}0{/if}" type="hidden" />
    <input autocomplete="off"  name="ep_id1" value="{$ep_id}" type="hidden" />
    <input autocomplete="off"  name="ep_id2" value="{$ep_id}" type="hidden" />
    <input autocomplete="off"  name="ep_name" value="" type="hidden" />
    <input autocomplete="off"  name="type" value="{$type}" type="hidden" />
{*    <input autocomplete="off"  name="er_id" value="amp" type="hidden" />*}
    <div class="toolbar mactoolbar ">   
        {*<div id="main">
            <div class="demo">
                <div style="float:left;">
                    <a style="min-width:80px;" class="button amp active">AMP</a>
                    <a style="min-width:80px;" class="button emp">EMP</a>
                </div>
                    <div id="city_5">

                            <select name="lv1" class="prov" id="one1" style="width:100px;height:24px;margin:5px 10px"></select>
                            <select name="lv2" class="city" style="width:100px;height:24px;margin:5px 10px" disabled="disabled"></select>
                            <select name="lv3" class="dist" style="width:100px;height:24px;margin:5px 10px" disabled="disabled"></select>
                    </div>
            </div>
        </div>*}
        <div  class="line" style="float: left;">
            <label>{"选择时间"|L}：</label>
            <input autocomplete="off" style="height:24px;" class="datepickeraccount start" name="start" value="{if $smarty.request.start neq ""}{$smarty.request.start}{else}{$date}{/if}" type="text"/>
{*            <span>-</span>
            <input autocomplete="off" style="height:24px;" class="datepicker end" name="end" value="{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}" type="text" datatime="true" />*}
        </div>
        <div class="line" >
            <label>{"选择代理商"|L}：</label>
                       <select name="ag_number" id='select_ag' style="width:200px;height:28px;margin:5px 10px" class="autofix autoedit" value="{if $smarty.request.ep_id neq ""}{$smarty.request.ep_id}{else}0{/if}" action="?m=account&a=option&start={if $smarty.request.start eq ""}{$date}{else}{$smarty.request.start}{/if}" data='[{ "to": "select_ag","field": "ag_number","view":"false" }]'>
                <option value="0">{"直属企业"|L}</option>
            </select>
        </div>

   </div>
            <style>
                .ui-datepicker-calendar { 
                    display: none; 
                }  
            </style>
	<!--选择条件-->
        
                        <div class="buttons right none">
                                            <a form="form" class="button submit" >{"查询"|L}</a>
                                        </div>
</form>
<hr />
<div class="content"></div>



<!--输出台-->
<iframe id="ifr" name="hidden_frame"></iframe>
{/strip}