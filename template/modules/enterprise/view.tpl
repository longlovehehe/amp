{strip}
{include file="modules/enterprise/nav.tpl" }
<h2 class="title"><span title="{$data.e_name}" class='ellipsis2 ctips' style='max-width: 350px;height: 20px;'>{$data.e_name|mbsubstr:20}</span> - {"企业信息"|L}</h2>
{if $data.e_sync != "0"}
{*<div class="info big center animated nonselect">
    <p>{"编辑了用户，但是没有同步至设备"|L}。状态码：{$data.e_sync}</p>
</div>
*}
{/if}
<div class="form mrbt20">
    <div class="block ">
        <label class="title">{"企业编号"|L}：</label>
        <span>{$data.e_id}</span>
    </div>
    <div class="block ">
        <label class="title">{"企业名称"|L}：</label>
        <span title='{$data.e_name}' class='ellipsis2 ctips' style='max-width: 350px;height: 20px;'>{$data.e_name|mbsubstr:20}</span>
    </div>
    <div class="block ">
        <label class="title">{"企业注册号"|L}：</label>
        <span title='{$data.e_regis_code}' class='ellipsis2 ctips' style='max-width: 350px;height: 20px;'>{$data.e_regis_code|mbsubstr:20}</span>
    </div>
    <div class="block">
        <label class="title">{"企业地址"|L}：</label>
        <span title='{$data.e_addr}' class='ellipsis2 ctips' style='max-width: 350px;height: 20px;'>{$data.e_addr|mbsubstr:20}</span>
    </div>
    <div class="block ">
        <label class="title">{"企业位置"|L}：</label>
        <span>{$data.e_location}</span>
    </div>
    <div class="block">
        <label class="title">{"行业"|L}：</label>
        <span>{$data.e_industry}</span>
    </div>
    <div class="block">
        <label class="title">{"联系人"|L}：</label>
       <span>{$data.e_contact_name}</span>&nbsp;
       <span>{$data.e_contact_surname}</span>
    </div>
    <div class="block">
        <label class="title">{"电话"|L}：</label>
        <span>{$data.e_contact_phone}</span>
    </div>
    <div class="block">
        <label class="title">{"传真"|L}：</label>
        <span>{$data.e_contact_fox}</span>
    </div>
    <div class="block">
        <label class="title">{"邮箱"|L}：</label>
        <span>{$data.e_contact_mail}</span>
    </div>
    <div class="block ">
        <label class="title">{"区域"|L}：</label>
        <span>{$data.e_area|mod_area_name}</span>
    </div>
    <div class="block ">
        <label class="title">{"状态"|L}：</label>
        <span title='{"不启用"|L}|{"启用"|L}|{"处理中"|L}|{"发布失败"|L}，{"启用时不能迁移{$smarty.session.ident}-Server,只有具有录制功能才能迁移VCR。处于处理中时无法编辑企业"|L}。{"当前状态"|L}{$data.e_status|modifierStatus}'>{$data.e_status|modifierStatus} <span style="font-size: 16px;color: red;">{if $data.e_status eq 3}({"错误码"|L}:403){else if $data.e_status eq 4}({"错误码"|L}:404){/if}</span></span>
    </div>
    <div class="block ">
        <label class="title">{"{$smarty.session.ident}-Server"|L}：</label>
        <span>{$data.mds_d_name}<!-- 【{$data.mds_d_ip1}】 --></span>
    </div>
    <div class="block ">
        <label class="title">{$smarty.session.ident}-RS：</label>
        <span>{$data.rs_name}</span>
    </div>
    <div class="block ">
        <label class="title">{$smarty.session.ident}-SS：</label>
        <span>{$data.ss_name}</span>
    </div>
    <div class="block ">
        <label class="title">{"企业用户数"|L}：</label>
        <span>{$phone_num+$dispatch_num+$gvs_num}/{$data.e_mds_users}</span>
    </div>
    <div class="block none">
        <label class="title">{"并发数"|L}：</label>
        <span>{$data.e_mds_call}</span>
    </div>
    <div class="block ">
        <label class="title">{"手机用户数"|L}：</label>
        <span>{$phone_num}/{$data.e_mds_phone}</span>
    </div>
    <div class="block ">
        <label class="title">{"调度台用户数"|L}：</label>
        <span>{$dispatch_num}/{$data.e_mds_dispatch}</span>
    </div>
    <div class="block ">
        <label class="title">{"GVS用户数"|L}：</label>
        <span>{$gvs_num}/{$data.e_mds_gvs}</span>
    </div>
<!--管理员信息-->
    <h2 class="title"> {"管理员信息"|L}</h2>
    <div class="block ">
        <label class="title">{"管理员ID"|L}：</label>
        <span title='{$info.em_id}'  style='max-width: 350px;height: 20px;'>{$info.em_id|mbsubstr:20}</span>
    </div>
    <div class="block none">
        <label class="title">{"管理员密码"|L}：</label>
        <span style='max-width: 350px;height: 20px;'>{$info.em_pswd}</span>
    </div>
    <div class="block">
        <label class="title">{"管理员名称"|L}：</label>
        <span>{$info.em_admin_name}</span>&nbsp;
        <span>{$info.em_surname}</span>
    </div>
    <div class="block ">
        <label class="title">{"管理员电话"|L}：</label>
        <span title='{$info.em_phone}' style='max-width: 350px;height: 20px;'>{$info.em_phone|mbsubstr:20}</span>
    </div>
    <div class="block">
        <label class="title">{"管理员邮箱"|L}：</label>
         <span style='max-width: 350px;height: 20px;'>{$info.em_mail}</span>
    </div>

    {if $data.e_has_vcr eq "1"}
    {*具有VCR功能*}
    <div class="block none">
        <label class="title">{"VCR"|L}：</label>
        <span>{$data.vcr_d_ip1}</span>
    </div>
    <!-- <div class="block ">
        <label class="title">{"存储功能"|L}：</label>
        <span>{$data.e_storage_function|modifierStorage}</span>
    </div>
    <div class="block ">
        <label class="title">{"预计并发数"|L}：</label>
        <span>{$data.e_rs_rec}</span>
    </div>-->
    <!--<div class="block ">
        <label class="title">{"录像并发数"|L}：</label>
        <span>{$data.e_vcr_videorec}</span>
    </div>-->
   <!-- <div class="block ">
        <label class="title">{"存储空间"|L}：</label>
        <span>{$data.e_vcr_space} MB</span>
    </div>-->
    {/if}
    <div class="buttons mrtop40">
        {*{if $data.e_status != "2"}*}
        {*不等于处理中*}
            {* {if $data.e_status != "3"}*}
            <a href="?m=enterprise&a=edit&e_id={$data.e_id}" class="button " title="{'编辑企业名称状态功能等信息'|L}">{"编辑企业信息"|L}</a>
            <a href="?m=enterprise&a=admins_edit&e_id={$data.e_id}&em_id={$info.em_id}" class="button " title='{"编辑企业管理员信息"|L}'>{"编辑管理员信息"|L}</a>
            {if $data.e_mds_id >0}
                {if $data.e_status == "1"}
                    {*仅处于启用状态才可停用*}
                    <a id="stop_status" class="button red" title="{'停用该企业，销户'|L}">{"停用该企业"|L}</a>
                    {else if $data.e_status == "0" }
                    <a id="start_status" class="button normal" title="{'启用该企业，开户'|L}">{"启用该企业"|L}</a>
                {/if}
            {/if}
             {*{/if}*}
             {*<a href="?m=enterprise&a=move_mds&e_id={$data.e_id}" class="button " title='{"迁移{$smarty.session.ident}-Server的VCR服务器信息"|L}'>{"迁移所属"|L}</a>*}
            
            {*仅处于停用状态才可启用*}
            <a href="?m=enterprise&a=move_device&e_id={$data.e_id}" class="button " title='{"迁移{$smarty.session.ident}-设备信息"|L}'>{"迁移设备"|L}</a>
            {if $smarty.session.ag.ag_level neq '1'}
                <a href="javascript:void(0);" id="move_enterprise" class="button" title="{'迁移所属'|L}">{"迁移所属"|L}</a>
            {/if}
            <a href="?m=enterprise&a=enterprise_history&e_id={$data.e_id}" class="button" title="{'企业变更记录'|L}">{"企业变更记录"|L}</a>
            {if $data.e_status == "0" || $data.e_status =="1" || $data.e_status =="2"|| $data.e_status =="3"}
            <a id="initdb" class="button purple none " title="{'重新建立企业用户表'|L}">{"企业数据重建"|L}</a>
            {/if}
        
        {if $data.e_sync != "0" && $data.e_status != "3"}
        <a id="sync" class="button green none" title="{'企业数据下发至子设备'|L}">{"企业数据同步"|L}</a>
        {/if}
        {*{/if}*}
    </div>
</div>

<div id="dialog-confirm-warn" class="hide" title="{'重要操作确认'|L}？">
    <p>{"确认要重建该企业数据？该操作会导致该企业所有用户，企业群组，企业日志，企业通讯录数据丢失。"|L}<br />[{"一般在创建企业时，如果未能正常使用时，才考虑该项"|L}！]<br /><span class="red">如果您不知道此项是做什么用的，请不要点击！</span></p>
</div>
<div id="dialog-move" class="hide" title="{'区域以及许可符合迁移条件的目标'|L}：" style="overflow:auto">
    <input type="hidden" id="e_id" value="{$data.e_id}" />
</div>

<div id="dialog-notice" class="hide" title="{'确认迁移'|L}">
    <p id="notice" style="font-size:18px">{"确认要迁移该企业？该操作会改变该企业所属代理、企业创建者以及企业下的用户绑定的流量卡和终端的所属代理。"|L}</p>
    <br />
    <p class="red" style="font-size:15px">{"迁移后，企业内终端以及流量卡等信息的所属均会更改， 是否继续迁移"|L}？</p>
</div>
{/strip}