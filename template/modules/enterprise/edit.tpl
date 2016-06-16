{strip}
<h2 class="title">{"{$title}"|L}</h2>
<form id="form" class="base mrbt10" action="?modules=enterprise&action=save_shell">
    <input autocomplete="off"  value="{$data.e_id}" name="e_id" type="hidden" />
    <input autocomplete="off"  value="{$data.e_area|json_decode}" name="e_area" type="hidden" />
    <input autocomplete="off"  value="{$data.e_mds_id}" name="e_mds_id" type="hidden" />
    <input autocomplete="off"  value="{$data.e_mds_phone}" name="mds_phone" type="hidden" />
    <input autocomplete="off"  value="{$data.e_mds_dispatch}" name="mds_dispatch" type="hidden" />
    <input autocomplete="off"  value="{$data.e_mds_gvs}" name="mds_gvs" type="hidden" />
    <input autocomplete="off"  value="{$phone_num}" name="phone_num" type="hidden" />
    <input autocomplete="off"  value="{$dispatch_num}" name="dispatch_num" type="hidden" />
    <input autocomplete="off"  value="{$gvs_num}" name="gvs_num" type="hidden" />
    <input autocomplete="off"  value="{$phone+$data.e_mds_phone}" name="d_phone_num" type="hidden" />
    <input autocomplete="off"  value="{$dispatch+$data.e_mds_dispatch}" name="d_dispatch_num" type="hidden" />
    <input autocomplete="off"  value="{$gvs+$data.e_mds_gvs}" name="d_gvs_num" type="hidden" />
    <input autocomplete="off"  value="edit" name="did" type="hidden" />
    <input autocomplete="off"  value="{$data.e_has_vcr}" name="e_has_vcr" type="hidden" />
    <input autocomplete="off"  value="{$data.e_status}" name="e_status" type="hidden" />
  <div class="block">
        <label class="title">{"企业名称"|L}：</label>
        <input  maxlength="64" autocomplete="off" value="{$data.e_name}" ep_name="true"   name="e_name" type="text" required="true" />
    </div>
    <div class="block">
        <label class="title">{"企业注册号"|L}：</label>
        <input maxlength="64" autocomplete="off"  name="e_regis_code" value="{$data.e_regis_code}" type="text" />
    </div>
    <div class="block">
        <label class="title">{"企业地址"|L}：</label>
        <input  maxlength="64" autocomplete="off" value="{$data.e_addr}" {*addr="true"*}   name="e_addr" type="text" required="true" />
    </div>
    <div class="block">
        <label class="title">{"企业位置"|L}：</label>
        <input    autocomplete="off" value="{$data.e_location}" maxlength="64" name="e_location" type="text"  />
    </div>
    <div class="block">
        <label class="title">{"行业"|L}：</label>
        <input  maxlength="64" autocomplete="off"   value="{$data.e_industry}" name="e_industry" type="text" />
    </div>
   <div class="block">
        <label class="title">{"名字"|L}：</label>
        <input  maxlength="32" autocomplete="off" placeholder="{'名字'|L}"  value="{$data.e_contact_name}" chinese="true" name="e_contact_name" type="text"  required="true" />
    </div>
    <div class="block">
        <label class="title">{"姓氏"|L}：</label>
         <input  maxlength="32" autocomplete="off" placeholder="{'姓氏'|L}"  value="{$data.e_contact_surname}" chinese="true" name="e_contact_surname" type="text"  required="true" />
    </div>
    <div class="block">
        <label class="title">{"联系电话"|L}：</label>
         <input class="mobile-number" mobile1="true" type="text" style="height: 28px;width: 245px;border:1px solid #ccc;" name="e_contact_phone" value="{$data.e_contact_phone}" required="true"/>
        {*<input   maxlength="64" autocomplete="off" placeholder="{'国家代码'|L}" style="width: 60px;" value="{$data.e_contry_num}" maxlength="4" name="e_contry_num" type="text" required="true" /> + 
        <input mobile="true"  maxlength="64" autocomplete="off" placeholder="{'手机号码'|L}" value="{$data.e_contact_phone}"  maxlength="32" name="e_contact_phone" type="text" required="true" />*}
    </div>
    <div class="block">
        <label class="title">{"联系传真"|L}：</label>
        <input fox="true"  maxlength="32" autocomplete="off"   value="{$data.e_contact_fox}" name="e_contact_fox" type="text" />
    </div>
    <div class="block">
        <label class="title">{"邮箱"|L}：</label>
        <input  maxlength="32" autocomplete="off"   value="{$data.e_contact_mail}" email="true" name="e_contact_mail" type="text" required="true"  />
    </div>
    <div class="block">
        <label class="title" style="float:left;">{"备注"|L}：</label>
        <textarea autocomplete="off" maxlength="100" name="e_remark" remark="true" style="width:240px;height:100px;padding:5px;">{$data.e_remark}</textarea>
    </div>
    <div class="block">
        <label class="title">{"区域"|L}：</label>
        <label>{$data.e_area|mod_area_name}</label>
        <!--<select value="{$data.e_area}" name="e_area" class="autofix autoedit" action="?m=area&a=option" required="true" readonly></select>-->
    </div>
    <div class="block radio" value="{$data.e_status}">
        <label class="title">{"企业状态"|L}：</label>
        <label>{$data.e_status|modifierStatus}</label>
    </div>
    <div class="block">
        <label class="title" style="float: left;">{"所属{$smarty.session.ident}-Server"|L}：</label>
        <div style=""><label id="mds_id" class="renderjson" style="margin-bottom: 10px;">{$data.e_mds_id|modmdsid}</label></div>
        {*
        {if $data.e_mds_id > 0}
        <input autocomplete="off"  class='block' id="mds_limit"  type="text" disabled="true" />
        <input autocomplete="off"  value="{$data.e_mds_id}"  name="e_mds_id" type="hidden"/>
        {/if}
        <select {if $data.e_mds_id > 0}disabled="true"{/if} id='mds' value="{$data.e_mds_id}" size="10" name="e_mds_id" class="autofix autoedit long" action="?modules=api&action=get_mds_list" required="true"  ></select>
        *}
    </div>
    <div class="block none">
        <label class="title">{"企业密码"|L}：</label>
        <input maxlength="32" autocomplete="off" e_pwd="true" name="e_pwd" onpaste="return false" value="{$data.e_pwd|escape}" type="text" />
    </div>
    <div class="block">
        <label class="title">{"企业用户数"|L}：</label>
        <input maxlength="32" autocomplete="off"  value="{$data.e_mds_users}" name="e_mds_users" type="text" required="true" digits ="true" readonly/>
    </div>
    <div class="block none">
        <label class="title">{"企业并发数"|L}：</label>
        <input maxlength="32" autocomplete="off"  value="{$data.e_mds_call}" name="e_mds_call" type="text" required="true" digits ="true" />
    </div>
    <div class="block">
        <label class="title">{"分配手机用户数"|L}：</label>
        <input  maxlength="32"  autocomplete="off"   value='{$data.e_mds_phone}' name="e_mds_phone" type="text" required="true" digits ="true" {if $data.e_agents_id !=$smarty.session.ag.ag_number}readonly{/if}/>
    </div>
    <div class="block">
        <label class="title">{"分配调度台用户数"|L}：</label>
        <input  maxlength="32"  autocomplete="off"   value='{$data.e_mds_dispatch}' name="e_mds_dispatch" type="text"  digits ="true" {if $data.e_agents_id !=$smarty.session.ag.ag_number}readonly{/if}/>
    </div>
    <div class="block">
        <label class="title">{"分配GVS用户数"|L}：</label>
        <input  maxlength="32"  autocomplete="off"   value='{$data.e_mds_gvs}' name="e_mds_gvs" type="text"  digits ="true" {if $data.e_agents_id !=$smarty.session.ag.ag_number}readonly{/if}/>
    </div>
{if $data.e_vcr_id > 0}
    <div class="block">
        <label class="title" style="float: left;">{"所属{$smarty.session.ident}-RS"|L}：</label>
        <div style=""><label id="mds_id" class="renderjson" style="margin-bottom: 10px;">{$data.e_vcr_id|modvcrid}</label></div>
        <input autocomplete="off"  value="{$data.e_vcr_id}" id="e_vcr_id" name="e_vcr_id" type="hidden"/>
        <input autocomplete="off"  value="{$aRs.d_recnum}" id="d_recnum" name="d_recnum" type="hidden"/>
        <input autocomplete="off"  value="{$aRs.d_recnum-$aRs.sum_recnum}" id="d_have"  name="d_have" type="hidden"/>
        <input  value="{$data.e_rs_rec}" id="e_rs_rec" name="e_rs_rec" type="hidden" />
    </div>
    {/if}
     {if $data.e_ss_id > 0}
    <div class="block">
        <label class="title" style="float: left;">{"所属{$smarty.session.ident}-SS"|L}：</label>
        <div style=""><label id="mds_id" class="renderjson" style="margin-bottom: 10px;">{$data.e_ss_id|modssid}</label></div>
        <input autocomplete="off"  value="{$data.e_ss_id}"  name="e_ss_id" type="hidden"/>
    </div>
    {/if}
    <!--
    {*
    <hr class="none"/>
    <div class="block checkbox_defined none" value="{$data.e_has_vcr}">
    <label class="title">录制功能</label>
    <input autocomplete="off"  name="e_has_vcr" class="auto_toggle_defined" action="d_rec_toggle" type="checkbox" />
    </div>
    <div class="d_rec_toggle hide none">
    <div class="block">
    <label class="title">所属VCR</label>
    <input autocomplete="off"  class='block' id="vcr_limit" d_space_free="{$data.d_space_free}" d_audiorec="{$data.d_audiorec}" d_videorec="{$data.d_videorec}" value="{$data.vcr_d_ip1}" type="text" disabled="true" />
    {if $data.e_has_vcr == 1}
    <input autocomplete="off"  value="{$data.e_vcr_id}"  name="e_vcr_id" type="hidden"/>
    {/if}
    <select disabled="true" id='vcr' size='10' value="{$data.e_vcr_id}" name="e_vcr_id" class="autofix auto_toggle_open autoedit long" action="?modules=api&action=get_vcr_list"  required="true" ></select>
    </div>
    <div class="block">
    <label class="title">录音并发数</label>
    <input autocomplete="off"  value="{$data.e_vcr_audiorec}" name="e_vcr_audiorec" id="d_audiorec" type="text" required="true" digits ="true" />
    </div>
    <div class="block">
    <label class="title">录像并发数</label>
    <input autocomplete="off"  value="{$data.e_vcr_videorec}" name="e_vcr_videorec" type="text" required="true" digits ="true" />
    </div>
    <div class="block">
    <label class="title">存储空间（单位MB）</label>
    <input autocomplete="off"  value="{$data.e_vcr_space}" name="e_vcr_space" type="text"  required="true" digits ="true" />
    </div>
    <div class="block radio" value="{$data.e_storage_function}">
    <label class="title">存储功能</label>
    <div class="line">
    <input autocomplete="off"  name="e_storage_function" value="1" name="type" type="radio">
    <label for="radio_synchronous">同步</label>
    </div>
    <div class="line">
    <input autocomplete="off"  name="e_storage_function" value="2" name="type" type="radio" checked="checked">
    <label for="radio_storage">存储</label>
    </div>
    </div>
    </div> *}
    -->
    <div class="buttons mrtop40">
        <a goto="?m=enterprise&a=view&e_id={$data.e_id}" form="form" class="ajaxpost button normal">{"保存"|L}</a>
        <a class="goback button">{"取消"|L}</a>
    </div>
</form>
<script {'type="ready"'}>
    (function () {
        /* var json = eval($("#mds_id").text());
         var d_user = json[0]['diff_user'];
         var d_call = json[0]['diff_call'];
         range = "[0," + d_user + "]";
         $("input[name=e_mds_users]").attr("range", range);
         range = "[0," + d_call + "]";
         $("input[name=e_mds_call]").attr("range", range);*/
    })();</script>
{*
<script  {'type="ready"'}>
    $.ajaxSetup({
    async: false
    });
    {if $data.e_mds_id > 0}
    $("select#mds").bind("change", function () {
        var d_user = $(this).children('option:selected').attr("d_user");
        var d_call = $(this).children('option:selected').attr("d_call");
        $("#mds_limit").val($(this).children('option:selected').text());
        if (d_user != "") {
            range = "[0," + d_user + "]";
            $("input[name=e_mds_users]").attr("range", range);
        }
        if (d_call != "") {
            range = "[0," + d_call + "]";
            $("input[name=e_mds_call]").attr("range", range);
        }
        $("select#mds").hide();
    });
    { else}
    $("select#mds").bind("change", function () {
        var d_user = $(this).children('option:selected').attr("d_user");
        var d_call = $(this).children('option:selected').attr("d_call");
        if (d_user != "") {
            range = "[0," + d_user + "]";
            $("input[name=e_mds_users]").attr("range", range);
        }
        if (d_call != "") {
            range = "[0," + d_call + "]";
            $("input[name=e_mds_call]").attr("range", range);
        }
    });
    {/if}
            $("select#vcr").bind("change", function () {
        var d_space = $(this).children('option:selected').attr("d_space");
        var d_audiorec = $(this).children('option:selected').attr("d_audiorec");
        var d_videorec = $(this).children('option:selected').attr("d_videorec");
        $("#vcr_limit").val($(this).children('option:selected').text());
        if (d_space != "") {
            range = "[0," + d_space + "]";
            $("input[name=e_vcr_space]").attr("range", range);
        }
        if (d_audiorec != "") {
            range = "[0," + d_audiorec + "]";
            $("input[name=e_vcr_audiorec]").attr("range", range);
        }
        if (d_videorec != "") {
        range = "[0," + d_videorec + "]";
                $("input[name=e_vcr_videorec]").attr("range", range);
        }
        {if $data.e_has_vcr == 1}
        $("select#vcr").hide();
        {/if}
    });
    $("select#mds").trigger('change');
    $("select#vcr").trigger('change');
    (function () {
        $("input.auto_toggle_defined").bind("click", function () {
            var url = $(this).attr("action");
            var owner = $("." + url);
            if ($(this).is(":checked")) {
                owner.show();
                /*$(".auto_toggle_open").attr("disabled", false);*/
            } else {
                owner.hide();
                /*$(".auto_toggle_open").attr("disabled", true);*/
            }
        });
        var val = $("div.checkbox_defined").attr("value");
        if (val == "1") {
            $("input.auto_toggle_defined").trigger("click");
        }
    })();
    {if $data.e_has_vcr == 0}
    $("#vcr_limit").hide();
    $(".auto_toggle_open").attr("disabled", false).show();
    {/if}
</script>
*}
{/strip}