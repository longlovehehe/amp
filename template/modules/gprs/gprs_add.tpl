{strip}
<h2 class="title">{"办理入库"|L}</h2>
<div class="block">
    <a id="ptimport"  class=" button " action="ptt_group">{"批量入库"|L}</a>
    <a class="export button" action="ptt_group">{"下载模板"|L}</a>
</div>
<form id="form" class="base mrbt10" action="?modules=gprs&action=gprs_save">
    <div class="block" style="text-align:center;margin-left:auto;margin-right:auto;">
        <label>{"记录人"|L}：</label>
        <input autocomplete="off" value="{$smarty.session.own.om_id}"  maxlength="32" name="g_final_user" type="text" />
    </div>
    <div id="gprs_in" >
        <div class="block add_gprs start" style="float: left;width: 350px;border-right: 1px solid #CCCCCC;border-bottom: 1px solid #CCCCCC">
            <a type="button"  class="add_button"><div  style="background: url('images/add.png') no-repeat ;background-size:18px;width:25px;height:25px; float: left;"></div></a>
            <a type="button"  class="del_button" id="gprs"><div  style="background: url('images/del.png') no-repeat ;background-size:18px;width:20px;height:20px;margin-left: 30px;"></div></a>
            <div class="block">
                <label class="title1">ICCID：</label>
                <input autocomplete="off"  style="width: 140px;"  maxlength="32" name="g_iccid0" type="text" required="true" />
            </div>
            <div class="block">
                <label class="title1">{"套餐"|L}：</label>
                {*  <select class="autofix" name="g_packages0" action="?modules=gprs&action=gprs_option" style="width: 60px;">*}
                    <select  name="g_packages" style="width: 140px;">
                        <option value="1">1.2G</option>
                        <option value="2">3.6G</option>
                    </select>
            </div>
            <div class="block">
                <label class="title1">{"开卡日期"|L}：</label>
                <input autocomplete="off"  class="selecttime gprs_attr datepicker0"  maxlength="32" name="g_start_time0" type="text" required="true"/>
            </div>
            <div class="block">
                <label class="title1">{"入库日期"|L}：</label>
                <input autocomplete="off"  class="selecttime gprs_attr intime datepicker0" {*onblur="getintime();"*} maxlength="32" name="g_intime0" type="text" required="true"/>
            </div>
            <div class="block">
                <label class="title1">{"归属地 "|L}：</label>
                <input autocomplete="off"style="width: 120px;"  maxlength="32" name="g_belong0" type="text" required="true"/>
            </div>
        </div>
    </div>
    <div class="buttons mrtop40" style="clear: both;">
        <a goto="?m=gprs&a=index" form="form" class="ajaxpost button normal">{"保存"|L}</a>
        <a class="goback button">{"取消"|L}</a>
    </div>
</form>

<!--导入流量卡-->
<form class="hide" id="pt_import" name="fileupdate" method="post" action="?"  enctype="multipart/form-data" target="hidden_frame">
    <input name="m" value="gprs" />
    <input name="a" value="importShellICCID" />
    <input name="e_id" value="{$data.e_id}" />
    <input autocomplete="off"  name="step" type="text" value="if" />
    <input autocomplete="off"  id="pt_import_up" name="fileToUpload" type="file"  />
</form>

<!-- 流量卡数据检查 -->
<form class="hide" id="pt_ic" method="get" action="?"  target="hidden_frame">
    <input name="m" value="gprs" />
    <input name="a" value="importShellICCID" />
    <input name="e_id" value="{$data.e_id}" />
    <input autocomplete="off"  name="step" type="text" value="ic" />
    <input name="f" type="hidden" />
</form>

<!-- 流量卡数据导入 -->
<form class="hide" id="pt_i" method="get" action="?"  target="hidden_frame">
    <input name="m" value="gprs" />
    <input name="a" value="importShellICCID" />
    <input name="e_id" value="{$data.e_id}" />
    <input autocomplete="off"  name="step" type="text" value="i" />
    <input name="f" type="hidden" />
</form>
<!--/群组导入结束-->

<!--输出台-->
<iframe id="ifr" name="hidden_frame"></iframe>
{/strip}