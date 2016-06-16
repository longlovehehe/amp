{strip}
<div class="toolbar mactoolbar">
    <a href="?m=enterprise&a=index" class="button active">{"企业管理"|L}</a>
    <a href="?m=enterprise&a=allusers" class="button ">{"用户搜索"|L}</a>
    <a href="?m=device&a=vcrs" class="button none">{"车辆管理"|L}</a>
</div>
<h2 class="title">{"企业管理"|L}</h2>
<div class="toptoolbar">
    <a href="?m=enterprise&a=add" class="button orange">{"新增企业"|L}</a>
</div>
<div class="toolbar">
    <form action="?m=enterprise&a=index_item" id="form" method="post">
        <input autocomplete="off"  name="modules" value="enterprise" type="hidden" />
        <input autocomplete="off"  name="action" value="index_item" type="hidden" />
        <input autocomplete="off"  name="page" value="0" type="hidden" />
        <input autocomplete="off"  name="ag_number" value="{$smarty.session.ag.ag_number}" type="hidden" />
        <div class="line">
            <label>{"企业编号"|L}：</label>
            <input autocomplete="off"  class="autosend" name="e_id" type="text" />
        </div>
        <div class="line">
            <label>{"企业名称"|L}：</label>
            <input autocomplete="off"  class="autosend" name="e_name" type="text" />
        </div>
        <div class="line">
            <label>{"区域"|L}：</label>
            <select value="" name="e_area" class="autofix autoselect" data='[{ "to": "e_mds_id","field": "d_area","view":"true" }]' action="?m=area&a=option">
                <option value="@">{"全部"|L}</option>
            </select>
        </div>
        <div class="line">
            <label>{"状态"|L}：</label>
            <select name="e_status">
                <option value="">{"全部"|L}</option>
                <option value="1">{"启用"|L}</option>
                <option value="0">{"不启用"|L}</option>
                <option value="2">{"发布处理中"|L}</option>
                <option value="3">{"发布失败"|L}</option>
                
                <option value="5">{"企业创建中"|L}</option>
                <option value="6">{"企业删除中"|L}</option>
            </select>
        </div>
        <div class="line">
            <label>{$smarty.session.ident}-Server：</label>
            <select value="" name="e_mds_id" id="e_mds_id" class="autofix"  data='' action="?m=device&action=mds_option&view=true">
                <option value="">{"全部"|L}</option>
            </select>
        </div>
        {if $smarty.session.ag.ag_level eq 1}{else}
            <div class="line">
            <label>{"创建者"|L}：</label>
            <select value="" name="e_create_name" class="autofix autoselect" action="?m=enterprise&a=create_option">
                <option value="">{"全部"|L}</option>
            </select>
        </div>
        {/if}
        
        {*
        <div class="line">
            <label>VCR：</label>
            <select name="e_vcr_id" class="autofix" action="?modules=api&action=get_vcr_list">
                <option value="">{"全部"|L}</option>
            </select>
        </div>
        *}
        <div class="buttons right">
            <a form="form" class="button submit">{"查询"|L}</a>
        </div>
    </form>
</div>

<div class="toolbar">
    <a id="delall" class="button">{"批量删除"|L}</a>
    <a id="refreshall" class="refreshall button" data="?m=enterprise&a=refresh" >{"批量状态刷新"|L}</a>
</div>
<div>
    <table class="full">
        <tr class='head' style="height: 35px;" type="ent" url="?m=enterprise&action=index_item">
            <td width="110px" class="clickPage">{"企业列表"|L}</td>
            <td width="490px" class="clickPage" style="text-align:right;">{"显示条数"|L}：</td>
            <td width="50px" onclick="clickPage(this)" class="clickPage" {$smarty.session.color.10} onmouseover="this.style.cursor='pointer'">10</td>
            <td width="50px" onclick="clickPage(this)" class="clickPage" {$smarty.session.color.20} onmouseover="this.style.cursor='pointer'">20</td>
            <td width="50px" onclick="clickPage(this)" class="clickPage" {$smarty.session.color.50} onmouseover="this.style.cursor='pointer'">50</td>
        </tr>
    </table>
</div>
<div class="content"></div>

<div id="dialog-confirm" class="hide" title="{'删除选中项'|L}?">
    <p>{"确定要删除选中的企业吗"|L}?</p>
</div>
{/strip}