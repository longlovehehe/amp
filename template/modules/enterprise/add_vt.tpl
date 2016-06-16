{strip}
<h2 class="title">{"{$title}"|L}</h2>
<form id="form" class="base mrbt10" action="?modules=enterprise&action=save_shell">
    <input type="hidden" name="e_agents_id" value="{$smarty.session.ag.ag_number}">
    <input type="hidden" name="diff_phone" value="{$phone}">
    <input type="hidden" name="diff_dispatch" value="{$dispatch}">
    <input type="hidden" name="diff_gvs" value="{$gvs}">
   <div class="block">
        <label class="title">{"企业名称"|L}：</label>
        <input maxlength="64" autocomplete="off"  ep_name="true" name="e_name" type="text" required="true" />
    </div>
    <div class="block">
        <label class="title">{"企业注册号"|L}：</label>
        <input maxlength="64" autocomplete="off"  name="e_regis_code" type="text" />
    </div>
    <div class="block">
        <label class="title">{"企业地址"|L}：</label>
        <input autocomplete="off" {*addr="true"*}  maxlength="64" name="e_addr" type="text" required="true" />
    </div>
    <div class="block">
        <label class="title">{"行业"|L}：</label>
        <input  maxlength="64" autocomplete="off"   name="e_industry" type="text" />
    </div>
    <div class="block">
        <label class="title">{"名字"|L}：</label>
        <input  maxlength="32" autocomplete="off" placeholder="{'名字'|L}"  value='' chinese="true" name="e_contact_name" type="text"  required="true" />
    </div>
    <div class="block">
        <label class="title">{"姓氏"|L}：</label>
        <input  maxlength="32" autocomplete="off" placeholder="{'姓氏'|L}"  value='' chinese="true" name="e_contact_surname" type="text"  required="true" />
    </div>
    <div class="block">
        <label class="title">{"联系电话"|L}：</label>
        <input class="mobile-number" mobile1="true" type="text" style="height: 28px;width: 245px;border:1px solid #ccc;" name="e_contact_phone" value="{$item.e_contact_phone}" required="true"/>
        {*
        <input   maxlength="64" autocomplete="off" placeholder="{'国家代码'|L}" style="width: 60px;"  maxlength="4" name="e_contry_num" type="text" required="true" /> + 
        <input mobile="true"  maxlength="64" autocomplete="off" placeholder="{'手机号码'|L}"  maxlength="32" name="e_contact_phone" type="text" required="true" />*}
    </div>
    <div class="block">
        <label class="title">{"联系传真"|L}：</label>
        <input fox="true"  maxlength="32" autocomplete="off"   name="e_contact_fox" type="text" />
    </div>
    <div class="block">
        <label class="title">{"邮箱"|L}：</label>
        <input  maxlength="32" autocomplete="off"   value='' email="true" name="e_contact_mail" type="text" required="true"  />
    </div>   
    <div class="block">
        <label class="title" style="float:left;">{"备注"|L}：</label>
        <textarea autocomplete="off" maxlength="100" name="e_remark" remark="true" style="width:240px;height:100px;padding:5px;"></textarea>
    </div>
    <div class="block">
        <label class="title">{"区域"|L}：</label>
        <select name="e_area" class="autofix autoselect" action="?m=area&a=option" selected="true" data='[{ "to": "e_mds_id","field": "d_area","view":"false" }]'>
            <option value='@'>{"未选择"|L}</option>
        </select>
    </div>
    <input autocomplete="off"  value="0" name="e_status" type="hidden" checked="checked" />
    <div class="block none">
        <label class="title">{"企业密码"|L}：</label>
        <input  maxlength="32" autocomplete="off"  onpaste="return false"  e_pwd="true" name="e_pwd" type="text"/>
    </div>
    <div class="block">
        <label class="title">{"请选择所属"|L} {$smarty.session.ident}-Server：</label>
        <select value="" id="e_mds_id" name="e_mds_id" size="10"  class=" long" action="?m=device&action=mds_option" selected="true" data='[{ "to": "e_vcr_id","field": "d_deployment_id","view":"false" }]'></select>
    </div>
    <div class="block ">
        <label  class="title">{"企业用户数"|L}：</label>
        <input  maxlength="32" autocomplete="off"  value='0' name="e_mds_users" type="text"  readonly />
    </div>
    <div class="block none">
        <label class="title">{"企业并发数"|L}：</label>
        <input  maxlength="32" autocomplete="off"   value='0' name="e_mds_call" type="text" required="true" digits ="true" />
    </div>
    <div class="block">
        <label class="title">{"分配手机用户数"|L}：</label>
        <input  maxlength="32"  autocomplete="off"   value='0' name="e_mds_phone" type="text" required="true" digits ="true" />
    </div>
    <div class="block">
        <label class="title">{"分配调度台用户数"|L}：</label>
        <input  maxlength="32"  autocomplete="off"   value='0' name="e_mds_dispatch" type="text"  digits ="true" />
    </div>
    <div class="block">
        <label class="title">{"分配GVS用户数"|L}：</label>
        <input  maxlength="32" autocomplete="off"   value='0' name="e_mds_gvs" type="text"  digits ="true" />
    </div>
    <div class="block">
        <label class="title">{"预计并发数"|L}：</label>
        <input  maxlength="32" autocomplete="off"  value='0' id="e_rs_rec" name="e_rs_rec" type="text"  readonly />
        <input id="e_has_vcr" name="e_has_vcr" type="hidden" value="0"/>
    </div>
    <div class="block">
        <label class="title">{"请选择所属"|L} {$smarty.session.ident}-RS：</label>
        <select value="" id="e_vcr_id" name="e_vcr_id" size="10"  class=" long" action="?m=device&action=rs_option" ></select>
    </div>
    <div class="block">
        <label class="title">{"请选择所属"|L} {$smarty.session.ident}-SS：</label>
        <select value="" id="e_ss_id" name="e_ss_id" size="10"  class=" long" action="?m=device&action=ss_option" selected="true"></select>
    </div>
    
<h2 class="title">{"企业管理员配置"|L}</h2>   
     <div class="block">
        <label class="title">{"名字"|L}：</label>
       <input  maxlength="32" autocomplete="off" placeholder="{'名字'|L}"  value='' chinese="true" name="em_admin_name" type="text"  required="true" />
    </div>
     <div class="block">
        <label class="title">{"姓氏"|L}：</label>
       <input  maxlength="32" autocomplete="off"  placeholder="{'姓氏'|L}"  value='' chinese="true" name="em_surname" type="text"  required="true" />
    </div>
    <div class="block">
        <label class="title">{"管理员密码"|L}：</label>
        <input  maxlength="32" autocomplete="off"   value='' id="password" paswd="true" name="em_pswd" type="password" required="true"  />
    </div>
    <div class="block">
        <label class="title">{"手机号"|L}：</label>
        <input class="mobile-number" mobile1="true" type="text" style="height: 28px;width: 245px;border:1px solid #ccc;" name="em_phone" value="{$item.em_phone}" required="true"/>
        {*
        <input   maxlength="64" autocomplete="off" placeholder="{'国家代码'|L}" style="width: 60px;"  maxlength="32" name="em_country_num" type="text" required="true" /> + 
        <input mobile="true"  maxlength="64" autocomplete="off" placeholder="{'手机号码'|L}"  maxlength="32" name="em_phone" type="text" required="true" />*}
    </div>
    <div class="block">
        <label class="title">{"邮箱"|L}：</label>
        <input  maxlength="32" autocomplete="off"   value='' email="true" name="em_mail" type="text" required="true"  />
    </div>
    <div class="block">
        <label class="title">{"描述"|L}：</label>
        <input chinese="true" maxlength="1024" autocomplete="off" value="{$data.em_desc}" name="em_desc" type="text" />
    </div>
    
   
    {*
    <hr class="none"/>
    <div class="block none">
        <label class="title">{"录制功能"|L}：</label>
        <input autocomplete="off"  name="e_has_vcr" class="auto_toggle" action="d_rec_toggle" type="checkbox" />
    </div>
    <div class="d_rec_toggle hide">
        <div class="block">
            <label class="title">{"所属VCR"|L}：</label>
            <select id="vcr" name="e_vcr_id" class="autofix1 auto_toggle_open long" size="10" action="?modules=api&action=get_vcr_list"  disabled="true" required="true">
            </select>
        </div>
        <div class="block">
            <label class="title">{"录音并发数"|L}：</label>
            <input autocomplete="off"  value="0" name="e_vcr_audiorec" id="d_audiorec" type="text" required="true" digits ="true" />
        </div>
        <div class="block">
            <label class="title">{"录像并发数"|L}：</label>
            <input autocomplete="off"  value="0" name="e_vcr_videorec" type="text" required="true" digits ="true" />
        </div>
        <div class="block">
            <label class="title">{"存储空间（单位MB）"|L}：</label>
            <input autocomplete="off"  value="0" name="e_vcr_space" type="text" />
        </div>
        <div class="block">
            <label class="title">{"存储功能"|L}：</label>
            <div class="line">
                <input autocomplete="off"  name="e_storage_function" value="1" name="type" type="radio">
                <label for="radio_synchronous">{"同步"|L}</label>
            </div>
            <div class="line">
                <input autocomplete="off"  name="e_storage_function" value="2" name="type" type="radio" checked="checked">
                <label for="radio_storage">{"存储"|L}</label>
            </div>
        </div>
    </div>
    *}
    <div class="buttons mrtop40">
        <a goto="?m=enterprise&a=index" form="form" class="ajaxpost button normal">{"保存"|L}</a>
        <a class="goback button">{"取消"|L}</a>
    </div>
</form>
<script {'type="ready"'}>
</script>
{/strip}
