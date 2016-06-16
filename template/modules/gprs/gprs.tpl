{strip}

<h2 class="title">{$title}</h2>

<div class="toptoolbar">
    <a href="?m=gprs&a=gprs_add" class="button orange">{"办理入库"|L}</a>
    <a href="?m=gprs&a=gprs_out" class="button orange">{"办理出库"|L}</a>
</div>
<div class="toolbar">
    <form action="?m=gprs&a=gprs_item" id="form" method="post">
        <input autocomplete="off"  name="modules" value="gprs" type="hidden" />
        <input autocomplete="off"  name="action" value="gprs_item" type="hidden" />
        <input autocomplete="off"  name="page" value="0" type="hidden" />
        <div class="line">
            <label>{"状态"|L}：</label>
            <select name="g_agents_id">
                <option value="">{"全部"|L}</option>
                <option value="1">{"出库"|L}</option>
                <option value="{}">{"未出库"|L}</option>
            </select>
        </div>
        <div class="line">
            <label>{"套餐"|L}：</label>
            <select name="g_packages">
                <option value=''>{"全部"|L}</option>
                <option value='1'>{"1.2G"|L}</option>
                <option value='2'>{"3.6G"|L}</option>
            </select>
        </div>
        <div class="line">
            <label>{"ICCID"|L}：</label>
            <input autocomplete="off"  class="autosend" name="g_iccid" type="text" />
        </div>
        <div class="line">
            <label>{"归属地"|L}：</label>
            <input autocomplete="off"  class="autosend" name="g_belong" type="text" />
        </div>
        <div class="line">
            <label>{"当前位置"|L}：</label>
            <input autocomplete="off"  class="autosend" name="ag_name" type="text" />
        </div>
        <div class="line">
            <label>{"最后编辑人"|L}：</label>
            <input autocomplete="off"  class="autosend" name="g_final_user" type="text" />
        </div>



        <div class="buttons right">
            <a form="form" class="button submit">{"查询"|L}</a>
        </div>
    </form>
</div>
{*
<div class="toolbar">
    <a id="delall" class="button">{"批量删除"|L}</a>
    <a id="refreshall" data="?m=device&a=refresh"  class="button">{"批量状态刷新"|L}</a>
</div>
*}
<div class="content"></div>

<form id="form" class="base mrbt10" >
    <input autocomplete="off"  name="d_id" class="d_id" value="{$data.d_id}" type="hidden" />
    <input autocomplete="off"  name="d_area1" class="d_area" value="{$data.d_area}" type="hidden" />
    <input autocomplete="off"  name="d_ip1" id="d_ip1" value="{$data.d_ip1}" type="hidden" />
    <input autocomplete="off"  name="d_port1" value="{$data.d_port1}" type="hidden" />
    <input autocomplete="off"  name="d_ip2" value="{$data.d_ip2}" type="hidden" />
    <input autocomplete="off"  name="d_port2" value="{$data.d_port2}" type="hidden" />
    <input autocomplete="off"  name="d_type" value="{$data.d_type}" type="hidden" />
    <div  id="light" class="white_content" style="height: 320px;">
        <div style="background-color:#DCE0E1;"><div style="float:left;width: 20px;">&nbsp;</div><div class="c_dir">新建目录</div></div>
        <br />
        <div class="block">
            设备名称：
            <input readonly autocomplete="off"  style="width: 150px;"  maxlength="32"   name="d_name" value="" type="text" />
        </div>
        <br />
        <div class="block">
            已有区域：
            <span class="d_area"></span>
        </div>
        <div class="block">
            <label>增加区域：</label>
            <select name="d_area[]" class="moreselect" size="5" multiple="true">

            </select>
        </div>

        <div class="buttons mrtop40" style="float: right;">
            <a class="button normal" onclick="do_set();">保存</a>
            <a class=" button" onclick="closed();">取消</a>
        </div>
    </div>
</form>

<div id="dialog-confirm" class="hide" title="{"删除选中项？"|L}">
    <p>{"确定要删除选中的设备吗？"|L}</p>
</div>
{/strip}