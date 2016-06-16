{strip}
<h2 class="title">{"流量卡编辑"|L}</h2>

<form id="form" class="base mrbt10" action="?modules=gprs&action=gprs_save">
    <input type="hidden" name="do" value="edit"/>
    <div class="block" style="text-align:center;margin-left:auto;margin-right:auto;">
        <label>记录人：</label>
        <input autocomplete="off" value="{$smarty.session.own.om_id}"  maxlength="32" name="g_final_user" type="text" />
    </div>
    <div id="gprs_in">
        <div class="block add_gprs">
            <label>ICCID：</label>
            <input autocomplete="off"  class="gprs_attr" value="{$list.g_iccid}" maxlength="32" name="g_iccid[]" type="text" />
            <label>流量套餐：</label>
            {*  <select class="autofix" name="g_packages[]" action="?modules=gprs&action=gprs_option" style="width: 100px;">*}
                <select  name="g_packages[]" style="width: 200px;" value="{$list.g_packages}" >
                    <option value="">请选择</option>
                    <option value="1" {if $list.g_packages eq 1}selected{else}{/if}>1.2G</option>
                    <option value="2" {if $list.g_packages eq 2}selected{else}{/if}>3.6G</option>
                </select>
                <label>起始日期：</label>
                <input autocomplete="off"  class="gprs_attr"  value="{$list.g_start_time}" maxlength="32" name="g_start_time[]" type="text" />
                <label>入库日期：</label>
                <input autocomplete="off"  class="gprs_attr intime"  maxlength="32" value="{$list.g_intime}" name="g_intime[]" type="text" readonly="readonly" />
                <label>归属地：</label>
                <input autocomplete="off"  class="gprs_attr" value="{$list.g_belong}" maxlength="32" name="g_belong[]" type="text" />
                <input type="button" value="增加" class="add_button none"/>
        </div>
    </div>
    <div class="buttons mrtop40">
        <a goto="?m=gprs&a=index" form="form" class="ajaxpost button normal">保存</a>
        <a class="goback button">取消</a>
    </div>
</form>
{/strip}