{strip}

<style>

    form.base label.title, .form label.title{

        width: 250px;

    }

     form.base label.title1, .form label.title1{

        width: 100px;

    }

</style>

<h2 class="title">{"{$title}"|L}</h2>

<form id="form" class="base mrbt10" action="?m=enterprise&a=users_save_shell">

    <input autocomplete="off"  value="{$data.e_id}" name="e_id" type="hidden" />

    <input autocomplete="off"  value="{$data.do}" name="do" type="hidden">
    <input autocomplete="off"  value="{$item.u_product_id_new}" name="u_product_id_new" type="hidden">
    <input autocomplete="off"  value="" name="imei_stat" type="hidden">
    <input autocomplete="off"  value="" name="number_stat" type="hidden">
    <input autocomplete="off"  value="" name="imsi_stat" type="hidden">
    <input autocomplete="off"  value="" name="iccid_stat" type="hidden">
    <input autocomplete="off"  value="" name="meid_stat" type="hidden">
    <input autocomplete="off"  value="OK" name="flag" type="hidden">
    <input autocomplete="off"  value="OK" name="imei_flag" type="hidden">
    <input autocomplete="off"  value="OK" name="meid_flag" type="hidden">
    <input autocomplete="off"  value="{$item.u_terminal_type}" name="md_type" type="hidden">
    <input type="hidden" name="auto_config" value="{$item.u_auto_config}">
    <div class="block {if $data.do eq edit}none{/if}">

        <div class="radioset" id="radioset" value="{$item.u_sub_type|default: 1}">

            <input autocomplete="off"  value="1" type="radio" id="radio_user" name="u_sub_type"  checked="checked" /><label for="radio_user">{"手机用户"|L}</label>

            <input autocomplete="off"  value="2" type="radio" id="radio_shelluser" name="u_sub_type" /><label for="radio_shelluser">{"调度台用户"|L}</label>

            <input autocomplete="off"  value="3" type="radio" id="radio_gvsuser" name="u_sub_type" /><label for="radio_gvsuser">{"GVS用户"|L}</label>

        </div>

    </div>



    <h3 class="title">{"基本属性"|L}</h3>

    <hr />

    <div class="block">

        <label class="title">{"用户号码"|L}：</label>

        {if $data.do != 'edit'}
        <input autocomplete="off"   maxlength="32" value="" name="u_number"u_number="true" type="text" required="true" />
        {else}
        <span class="gradient_light">{$item.u_number}</span>
        <input autocomplete="off"  value="{$item.u_number}" name="u_number" type="hidden">
        {/if}

    </div>

    <div class="block">
<div style="margin:10px 10px 10px 0px;float:left;">
        <label class="title">{"用户密码"|L}：</label>
        
        <input autocomplete="off"   maxlength="32" value="{$item.u_passwd}" pwd="true" name="u_passwd" type="text"  required="true" />
        <a href="javascript:void(0);" class="get_passwd" style="margin-left:5px;">{"使用随机密码"|L}</a>
        </div>
        <div style="clear:both;"></div>
    </div>



    <div class="block">

        <label class="title">{"姓名"|L}：</label>

        <input autocomplete="off"  u_name='true'  maxlength="32" value="{$item.u_name}" name="u_name" type="text" required="true" />

    </div>

    <div class="block">

        <label class="title">{"手机号"|L}：</label>

        {*<input class="mobile-number" mobile1="true" type="text" style="height: 28px;width: 245px;border:1px solid #ccc;" name="u_mobile_phone" value="{$item.u_mobile_phone}" />*}

        <input autocomplete="off" maxlength="32" value="{$item.u_mobile_phone}" name="u_mobile_phone" type="text" />

    </div>
    <div class="block">
        <label class="title" style="float:left;">{"备注"|L}：</label>
        <textarea autocomplete="off" maxlength="100" name="u_remark" remark="true" style="width:240px;height:100px;padding:5px;">{$item.u_remark}</textarea>
    </div>
    <div class="block radio" value="{$item.u_active_state}">
            <div class="line">
                <label class="title">{"用户状态"|L}：</label>
                <label class="radiotext">
                    <input autocomplete="off"  value="1" name="u_active_state" type="radio"  checked="checked" />
                    <span>{"启用"|L}</span>
                </label>
                <label class="radiotext">
                    <input autocomplete="off"  value="0" name="u_active_state" type="radio" />
                    <span>{"停用"|L}</span>
                </label>
            </div>
    </div>
    <div id="u_only_show_my_grp" class="sw user  shelluser block radio" value="{$item.u_only_show_my_grp|default:0}">

        <label class="title">{"只显示本部门"|L}：</label>

        <div class="line">

            <label class="radiotext"><input autocomplete="off"  value="1" class="u_only_show_my_grp" name="u_only_show_my_grp" type="radio"   />{"启用"|L}</label>

        </div>

        <div class="line">

            <label class="radiotext"><input autocomplete="off"  value="0" class="u_only_show_my_grp" name="u_only_show_my_grp" type="radio" checked="checked"  />{"停用"|L}</label>
        </div>
    </div>
    <div class="block sw user shelluser">

        <label class="title">{"默认群组"|L}：</label>

        <select value="{$item.u_default_pg}" name="u_default_pg" class="autofix autoedit" action="?m=enterprise&a=groups_option&safe=true&e_id={$data.e_id}" >

            <option value="">{"无"|L}</option>

        </select>

    </div>
<div class="block sw user">
        <label class="title">{"订购产品"|L}：</label>
        <select value="{$item.u_product_id}" name="u_product_id" class="autofix autoedit" action="?m=product&a=option&e_id={$data.e_id}" >
            <option value="">{"无"|L}</option>
        </select>
         <div  style="width:220px;padding:5px 0px 5px 250px;">
        <input name="isused" type="checkbox" value="on"/>
        <label>{"次月生效"|L}</label>
                <div style="color:#8B2929;padding-top: 10px;" class="show_info none">{"次月生效"|L}：<span class="product_new none"><a class="show_product_new"></a><a class="delete " href="javascript:void(0);">×</a></span></div>
        </div>
    </div>
    <div class="block">

        <label class="title">{"部门"|L}：</label>

        <select value="{$item.u_ug_id}" name="u_ug_id" class="autofix autoedit" action="?modules=api&action=get_groups_list&e_id={$data.e_id}" >

            <option value="">{"无"|L}</option>

        </select>

    </div>

    <div class="sw user block" value="{$item.u_alarm_inform_svp_num}">

        <label class="title">{"一键告警号码"|L}：</label>

        <select value="{$item.u_alarm_inform_svp_num}" action="?m=enterprise&a=shelluser&e_id={$data.e_id}" class="autofix autoedit" name="u_alarm_inform_svp_num">

            <option value="">{"无"|L}</option>
            <option value="@">{"自定义"|L}</option>
        </select>
            <input class="none" style="margin-left:10px;width:120px;" maxlength="11" type="text" check_number="true" u_alarm_inform_svp_num="true" name="u_alarm_inform_svp_num" value="{$item.u_alarm_inform_svp_num}">
    </div>

    <div class="sw user block">

        <label class="title">{"拍传接收号码"|L}：</label>

        <select value="{$item.u_mms_default_rec_num}" action="?m=enterprise&a=shelluser&e_id={$data.e_id}" class="autofix autoedit"  name="u_mms_default_rec_num">

            <option value="">{"无"|L}</option>

        </select>

    </div>
        <div class="block radio none" value="{$item.u_audio_rec}">
        <div class="line">
            <label class="title">{"录音"|L}：</label>
            <label class="radiotext">
                <input autocomplete="off"  value="1" name="u_audio_rec" type="radio"  />
                <span>{"启用"|L}</span>
            </label>
            <label class="radiotext">
                <input autocomplete="off"  value="0" name="u_audio_rec" type="radio" checked="checked"  />
                <span>{"停用"|L}</span>
            </label>
        </div>
    </div>
    <div class="block radio" value="{$item.u_attr_type}">
        <div class="line">
            <label class="title">{"用户分类"|L}：</label>
            <label class="radiotext">
                <input autocomplete="off"  value="1" name="u_attr_type" type="radio"  {if $data.do eq edit}disabled="disabled"{if $item.u_attr_type eq 1}checked="checked"{/if}{/if} />
                <span>{"测试"|L}</span>
            </label>
            <label class="radiotext">
                <input autocomplete="off"  value="0" name="u_attr_type" type="radio"   {if $data.do eq edit}disabled="disabled"{if $item.u_attr_type eq 0}checked="checked"{/if}{else}checked="checked"{/if}/>
                <span>{"商用"|L}</span>
            </label>
            {if $data.do eq edit}<input type="hidden" name="u_attr_type" {if $item.u_attr_type eq 0}value="0" {else}value="1"{/if}/>{/if}
        </div>
    </div>

    <div class="block radio none" value="{$item.u_video_rec}">

        <div class="line">

            <label class="title">{"录像"|L}：</label>

            <label class="radiotext">

                <input autocomplete="off"  value="1" name="u_video_rec" type="radio"  />

                <span>{"启用"|L}</span>

            </label>

            <label class="radiotext">

                <input autocomplete="off"  value="0" name="u_video_rec" type="radio" checked="checked"  />

                <span>{"停用"|L}</span>

            </label>

        </div>

    </div>
    <div class="sw user block">
        <label class="title">{"GPS定位上报方式"|L}：</label>
        <select name="u_gis_mode" class="autoedit" value="{$item.u_gis_mode|default:3}">
            <option value="0">{"不上报"|L}</option>
            <option value="1">{"强制百度智能定位"|L}</option>
            <option value="3">{"强制百度GPS定位"|L}</option>
            <option value="4">{"强制GPS定位"|L}</option>
            <option value="2">{"客户端设置"|L}</option>
{*            <option value="5">{"Google Map定位"|L}</option>*}
        </select>
    </div>
     <div class="sw user block">
            <label class="title">{"终端型号"|L}：</label>
{*            <input autocomplete="off"   maxlength="32" value="{$item.u_terminal_type}" name="u_terminal_type" type="text"  />*}
            <select  name="u_terminal_type" value="{$item.u_terminal_type}" class="autoedit autofix" action="?m=terminal&a=option">
                <option value="">{"其他"|L}</option>
            </select>
    </div>

    <div class="block radio sw user" value="{$item.u_auto_config}">

        <label class="title">{"自动登录开关"|L}：</label>

        <div class="line">

            <label class="radiotext"><input autocomplete="off"  value="1" name="u_auto_config" type="radio" />{"开"|L}</label>

        </div>

        <div class="line">

            <label class="radiotext"><input autocomplete="off"  value="0" name="u_auto_config" type="radio" checked="checked" />{"关"|L}</label>

        </div>

    </div>

    <div class="auto_config {if $item.u_auto_config == 0}hide{/if} ">

        <div class="sw user block none">

            <label class="title">UDID：</label>

            <input autocomplete="off"   maxlength="40" value="{$item.u_udid}" name="u_udid" u_udid="true" type="text" req />

        </div>

        <div class="sw user block">

            <label class="title">IMSI：</label>

            <input autocomplete="off"   maxlength="15" value="{$item.u_imsi}" name="u_imsi" u_imsi="true" type="text"  />

        </div>



        <div class="sw user block">

            <label class="title">IMEI：</label>

            <input autocomplete="off"   maxlength="15" value="{$item.u_imei}" name="u_imei" u_imei="true" type="text"  />
        </div>
        <div class="sw user block">
            <label class="title">MEID：</label>
            <input autocomplete="off"   maxlength="14" value="{$item.u_meid}" name="u_meid" u_meid="true" type="text" placeholder="{"当使用电信卡时,请务必填写该项"|L}" />
        </div>

        <div class="sw user block">

            <label class="title">ICCID：</label>

            <input autocomplete="off"   maxlength="20" value="{$item.u_iccid}" name="u_iccid" u_iccid="true" type="text"  />

        </div>

        <div class="sw user block">

            <label class="title">MAC：</label>

            <input autocomplete="off"   maxlength="12" value="{$item.u_mac}" name="u_mac" u_mac="true" type="text" />

        </div>

        <div class="block user radio" value="{$item.u_bind_phone|default:0}">

            <label class="title">{"机卡绑定"|L}：</label>

            <div class="line">

                <label class="radiotext"><input autocomplete="off"  value="1" name="u_bind_phone" type="radio" />{"是"|L}</label>

            </div>

            <div class="line">

                <label class="radiotext"><input autocomplete="off"  value="0" name="u_bind_phone" type="radio" checked="checked" />{"否"|L}</label>

            </div>

        </div>

        <div class="block user radio" value="{$item.u_gprs_genus}">

            <label class="title">{"流量卡所属"|L}：</label>

            <div class="line">

                <label class="radiotext"><input autocomplete="off"  value="1" name="u_gprs_genus" type="radio" />{"用户自有"|L}</label>

            </div>

            <div class="line">

                <label class="radiotext"><input autocomplete="off"  value="0" name="u_gprs_genus" type="radio" checked="checked" />{"运营商提供"|L}</label>

            </div>

        </div>

        <div class="block user radio" value="{$item.u_auto_run}">

            <label class="title">{"强制开机启动"|L}({"仅限App"|L})：</label>

            <div class="line">

                <label class="radiotext"><input autocomplete="off"  value="1" name="u_auto_run" type="radio" />{"启用"|L}</label>

            </div>

            <div class="line">

                <label class="radiotext"><input autocomplete="off"  value="0" name="u_auto_run" type="radio" checked="checked" />{"停用"|L}</label>

            </div>

        </div>



        <div class="sw block user radio" value="{$item.u_checkup_grade|default: 1}">

            <label class="title">{"程序检查更新"|L}({"仅限App"|L})：</label>

            <div class="line">

                <label class="radiotext"><input autocomplete="off"  value="1" name="u_checkup_grade" type="radio" />{"启用"|L}</label>

            </div>

            <div class="line">

                <label class="radiotext"><input autocomplete="off"  value="0" name="u_checkup_grade" type="radio" checked="checked" />{"停用"|L}</label>

            </div>

        </div>

        <div class="sw block user radio" value="{$item.u_encrypt}">

            <label class="title">{"信令加密"|L}：</label>

            <div class="line">

                <label class="radiotext"><input autocomplete="off"  value="1" name="u_encrypt" type="radio" />{"启用"|L}</label>

            </div>

            <div class="line">

                <label class="radiotext"><input autocomplete="off"  value="0" name="u_encrypt" type="radio" checked="checked" />{"停用"|L}</label>

            </div>

        </div>



        <div class="sw user block radio" value="{$item.u_audio_mode}">

            <div class="line radio" value="{$item.u_audio_mode}">

                <label class="title">{"语音通话方式"|L}：</label>

                <label class="radiotext">

                    <input autocomplete="off"  value="0" name="u_audio_mode" type="radio"  />

                    <span>{"移动电话"|L}</span>

                </label>

                <label class="radiotext">

                    <input autocomplete="off"  value="1" name="u_audio_mode" type="radio" checked="checked"  />

                    <span>{"VoIP电话"|L}</span>

                </label>

            </div>

        </div>







    </div>



    <div class="sw user">

        <h3 class="title">{"详细属性"|L}</h3>

        <hr />

        <!-- <div class="block radio" value="{$item.u_sex}">
            <label class="title">{"性别"|L}：</label>

            <div class="line">

                <label><input autocomplete="off"  value="M" name="u_sex" type="radio" checked="checked" />&nbsp;{"男"|L}&nbsp;&nbsp;&nbsp;</label>

            </div>

            <div class="line">

                <label><input autocomplete="off"  value="F" name="u_sex" type="radio" />&nbsp;{"女"|L}</label>

            </div>
        </div> -->
        <div class="block">

            <label class="title">{"职位"|L}：</label>

            <input autocomplete="off"   maxlength="32" value="{$item.u_position}" name="u_position" type="text"  />

        </div>

        <div class="block">

            <label class="title">{"购买日期"|L}：</label>

            <input autocomplete="off"   maxlength="32" class="datepickers start" value="{$item.u_purch_date}" name="u_purch_date" type="text"  />

        </div>

        <div class="block">

            <label class="title">{"终端序列号"|L}：</label>

            <input autocomplete="off" chrnum="true"  maxlength="32" value="{$item.u_terminal_number}" name="u_terminal_number" type="text"  />

        </div>

        <div class="block none">

            <label class="title">{"机型"|L}：</label>

            <input autocomplete="off"   maxlength="32" value="{$item.u_terminal_model}" name="u_terminal_model" type="text"  />

        </div>



        <div class="block none">

            <label class="title">{"蓝牙标识号"|L}：</label>

            <input autocomplete="off"   maxlength="32" value="{$item.u_zm}" name="u_zm" type="text"  />

        </div>

    </div>



    <div class="block sw user none">

        <label class="title">{"头像"|L}：</label>

{*        <img src="?m=enterprise&a=users_face_item&pid={$item.u_pic}" class="face">*}

        <input autocomplete="off"  value="{$item.u_pic}" name="u_pic" type="hidden"  />



        <a id="fileToUploadT" class="button normal small none">{"浏览"|L}</a>

        <a id="upload" class="button normal small none">{"上传"|L}</a>

        <span id="file_name_text"></span>

        <div class="info">{"仅支持jpg格式，2M以下"|L}</div>

    </div>

</form>

<div class="sw user none">

    <form class="" id="fileupdate" name="fileupdate" method="post" action="?m=enterprise&a=users_face"  enctype="multipart/form-data" target="hidden_frame">

        



             &nbsp;&nbsp;&nbsp;<input type="text" name="path" readonly style="width: 130px;">

                <a id="zdll" href="javascript:void(0);" >{"浏览"|L}

                    

                    <input id="fileToUpload" name="fileToUpload" type="file" style="position:absolute;

    left:0;

    top:0;

    width:80px;

    height:35px;

    z-index:999;

    background-color:transparent ;

    filter:alpha(opacity=0);

    -moz-opacity:0;

    opacity:0;

    clear: both;" onchange="getFiles(this);"/>

                </a>&nbsp;&nbsp;&nbsp;



        <input id='uppic' type="submit" value="{'上传'|L}" />

    </form>

</div>

<iframe name="hidden_frame" id="hidden_frame" class="hidden_frame none"></iframe>

<div class="buttons mrtop40">

    <a goto="?m=enterprise&a=users&e_id={$data.e_id}&page={$page}" form="form" class="ajaxpost_u button normal">{"保存"|L}</a>

    <a class="goback button" action="?m=enterprise&a=users&e_id={$data.e_id}&page={$page}">{"取消"|L}</a>

</div>

{/strip}

<script>
    var e_id = '{$data.e_id}';
    var arg_iccid = $("input[name=u_iccid]");
    var arg_imsi = $("input[name=u_imsi]");
    var arg_number = $("input[name=u_mobile_phone]");
    var flag = $("input[name=flag]");
    var iccid_stat = $("input[name=iccid_stat]");
    var imsi_stat = $("input[name=imsi_stat]");
    var number_stat = $("input[name=number_stat]");
    var u_auto_config = $("input[name=u_auto_config]");
    var auto_config = '0';
    var md_type = $("input[name=md_type]").val();
    var arg_meid = $("input[name=u_meid]");
    var arg_imei = $("input[name=u_imei]");
    if(md_type!=""){
        $("input[name=u_terminal_number]").attr("readonly","readonly");
    }
    $(document).ready(function () {

        $("a.ajaxpost_u").click(function () {
             if($("input[name=md_type]").val()==""&&$("input[name=auto_config]").val()=="0"){
                $("input[name=imei_flag]").val("OK");
                $("input[name=meid_flag]").val("OK");
                $("input[name=u_imei]").removeClass("error");
                $("input[name=u_imei]").val("");
                $("input[name=u_iccid]").val("");
                $("input[name=u_imsi]").val("");
                $("input[name=u_meid]").val("");
            }
            //当所填meid不为空时 验证meid
            if(arg_meid.val()!=''){
                check_meid();
            }
            check_imei();
            if($("input[name=imei_flag]").val() == "OK"&&$("input[name=meid_flag]").val() == "OK"){
                 check_form();
                if ($("#form").valid()) {
                    var form = $("a.ajaxpost_u").attr("form");
                    var url = $("#" + form).attr("action");
                    $.ajax({
                        url: url,
                        method: "POST",
                        dataType: "json",
                        data: $("#form").serialize(),
                        success: function (result) {
                            if (result.msg == "{'更改为GVS用户会丢失群组信息，是否更改'|L}？") {
                                confirm2(result.msg);
                            } else {
                                notice(result.msg, $("a.ajaxpost_u").attr("goto"));
                            }
                        }
                    });
                }else{
                    $("input.error:first").focus();
                }
            }else{
                    $("input.error:first").focus();
                    $("input.error:first").blur();
            }
        });
              //验证终端序列号
        $("input[name=u_terminal_number]").on("blur",function(){
            var md_type=$("input[name=md_type]").val();
            if(md_type!=""&&$("input[name=u_imei]").val()!=""){
                 $.ajax({
                    url:"?m=terminal&a=getById_foruser",
                    data:{
                            md_imei:$("input[name=u_imei]").val()
                            },
                    success:function(result){
                        //var result=eval(res);
                        var res = eval("("+result+")");
                         if($("input[name=u_terminal_number]").val()!=res.md_serial_number&&res.md_serial_number!=""){
                                $("input[name=imei_flag]").val("Error");
                                $("input[name=u_terminal_number]").removeClass("valid");
                                $("input[name=u_terminal_number]").addClass("error");
                                $("input[name=u_terminal_number]").attr("aria-required","true");
                                $("input[name=u_terminal_number]").attr("aria-invalid","true");
                            layer.tips("{'终端序列号与IMEI不符'|L}",$("input[name=u_terminal_number]"),{
                                    tips:[1, '#A83A3A'],
                                    time:600000
                                });
                            exit();
                        }
                    }
                });
            }
        });
        //验证imei
        $("input[name=u_imei]").on("blur",function(){
            check_imei_blur();
        });
        //验证meid
        $("input[name=u_meid]").on("blur",function(){
            //当所填meid不为空时 验证meid
            if(arg_meid.val()!=''){
                check_meid_blur();
            }
        });
        //验证iccid
        $("input[name=u_iccid]").on("blur",function(){
           check_iccid();
        });
        //验证imsi
        $("input[name=u_imsi]").on("blur",function(){
            $.ajax({
                url:'?m=enterprise&a=geticcid&u_number='+$("input[name=u_number]").val(),
                data:{
                    u_imsi:arg_imsi.val(),
                    e_id:{$data.e_id},
                    type:'imsi'
                },
                dataType:'json',
                success:function(res){
                    if(res.status==2){
                        flag.val("Error");
                        imsi_stat.val(res.status);
                        arg_imsi.addClass("error");
                        arg_imsi.attr("aria-required","true");
                        arg_imsi.attr("aria-invalid","true");
                        layer.tips("{'IMSI已绑定，请确认后重新输入'|L}",arg_imsi,{
                            tips:[1, '#A83A3A'],
                            time:600000
                        });
                        exit();
                    }else if(res.status==1){
                        flag.val("OK");
                        imsi_stat.val(res.status);

                    }else if(res.status==4){
                        var check = true;
                        var check1 = true;
                        //如果填写的iccid存在，并适用,自动填充imsi,手机号 以及对应判断
                        if(arg_number.val()==''){
                            arg_number.val(res.info.g_number);
                        }else{
                            if(res.info.g_number!='' && res.info.g_number!=arg_number.val()){
                                flag.val("Error");
                                number_stat.val(res.status);
                                arg_number.addClass("error");
                                arg_number.attr("aria-required","true");
                                arg_number.attr("aria-invalid","true");
                                layer.tips("{'所填写的手机号不正确，请检查后重新填写'|L}",arg_number,{
                                    tips:[1, '#A83A3A'],
                                    time:600000
                                });
                                check = false;
                            }else{
                                check =true;
                            }
                        }

                        if(arg_iccid.val()==''){
                            arg_iccid.val(res.info.g_iccid);
                        }else{
                            if(res.info.g_iccid!='' && res.info.g_iccid!=arg_iccid.val()){
                                flag.val("Error");
                                iccid_stat.val(res.status);
                                arg_iccid.addClass("error");
                                arg_iccid.attr("aria-required","true");
                                arg_iccid.attr("aria-invalid","true");
                                layer.tips("{'所填写的ICCID不正确，请检查后重新填写'|L}",arg_iccid,{
                                    tips:[1, '#A83A3A'],
                                    time:600000
                                });
                                check1 = false;
                            }else{
                                check1=true;
                            }
                        }
                        if(check==false || check1==false){
                            flag.val("Error");
                            exit();
                        }else{
                            flag.val("OK");
                            number_stat.val(res.status);
                        }
                    }else if(res.status==3){
                        flag.val("Error");
                        imsi_stat.val(res.status);
                        arg_imsi.addClass("error");
                        arg_imsi.attr("aria-required","true");
                        arg_imsi.attr("aria-invalid","true");
                        layer.tips("{'所填写的IMSI不正确，请检查后重新填写'|L}",arg_imsi,{
                            tips:[1, '#A83A3A'],
                            time:600000
                        });
                        exit();
                    }
                    //做到提示信息 不正确 还有 自动填充
                    layer.closeAll('tips');
                }
            });

        });
        //验证u_mobile_number
        $("input[name=u_mobile_phone]").on("blur",function(){
            $.ajax({
                url:'?m=enterprise&a=geticcid&u_number='+$("input[name=u_number]").val(),
                data:{
                    u_mobile_phone:arg_number.val(),
                    e_id:{$data.e_id},
                    type:'number'
                },
                dataType:'json',
                success:function(res){
                    if(res.status==2){
                        flag.val("Error");
                        number_stat.val(res.status);
                         arg_number.addClass("error");
                        arg_number.attr("aria-required","true");
                        arg_number.attr("aria-invalid","true");
                        layer.tips("{'手机号已绑定，请确认后重新输入'|L}",arg_number,{
                            tips:[1, '#A83A3A'],
                            time:600000
                        });
                        exit();
                    }else if(res.status==1){
                        flag.val("OK");
                        number_stat.val(res.status);
                    }else if(res.status==5){
                        flag.val("OK");
                        number_stat.val(res.status);
                    }else if(res.status==4){
                        auto_config = $("input[name=u_auto_config]:checked").val();
                        if(auto_config=='1'){
                            var check = true;
                            var check1 = true;
                            //如果填写的iccid存在，并适用,自动填充imsi,手机号 以及对应判断
                            if(arg_imsi.val()==''){
                                arg_imsi.val(res.info.g_imsi);
                            }else{
                                if(res.info.g_imsi!='' && res.info.g_imsi!=arg_imsi.val()){
                                    flag.val("Error");
                                    imsi_stat.val(res.status);
                                    arg_imsi.addClass("error");
                                    arg_imsi.attr("aria-required","true");
                                    arg_imsi.attr("aria-invalid","true");
                                    layer.tips("{'所填写的IMSI不正确，请检查后重新填写'|L}",arg_imsi,{
                                        tips:[1, '#A83A3A'],
                                        time:600000
                                    });
                                    check = false;
                                }else{
                                    check=true;
                                }
                            }

                            if(arg_iccid.val()==''){
                                arg_iccid.val(res.info.g_iccid);
                            }else{
                                if(res.info.g_iccid!='' && res.info.g_iccid!=arg_iccid.val()){
                                    flag.val("Error");
                                    iccid_stat.val(res.status);
                                    arg_iccid.addClass("error");
                                    arg_iccid.attr("aria-required","true");
                                    arg_iccid.attr("aria-invalid","true");
                                    layer.tips("{'所填写的ICCID不正确，请检查后重新填写'|L}",arg_iccid,{
                                        tips:[1, '#A83A3A'],
                                        time:600000
                                    });
                                    check1 = false;
                                }else{
                                    check1=true;
                                }
                            }
                            if(check==false || check1==false){
                                flag.val("Error");
                                exit();
                            }else{
                                flag.val("OK");
                                number_stat.val(res.status);
                            }
                        }else{
                            if(arg_imsi.val()==''){
                                arg_imsi.val(res.info.g_imsi);
                            }
                            if(arg_iccid.val()==''){
                                arg_iccid.val(res.info.g_iccid);
                            }
                            flag.val("OK");
                        }
                    }else if(res.status==3){
                        flag.val("Error");
                        number_stat.val(res.status);
                         arg_number.addClass("error");
                        arg_number.attr("aria-required","true");
                        arg_number.attr("aria-invalid","true");
                        layer.tips("{'所填写的手机号不正确，请检查后重新填写'|L}",$("input[name=u_mobile_phone]"),{
                            tips:[1, '#A83A3A'],
                            time:600000
                        });
                        exit();
                    }
                    //做到提示信息 不正确 还有 自动填充
                    layer.closeAll('tips');
                }
            });
        });
        //提交表单时验证iccid imsi 手机号
        function check_form(){
            auto_config = $("input[name=u_auto_config]:checked").val();
            var u_type = $("#radioset").attr("value");
            if(auto_config=='1' || u_type=='2' || u_type=='3'){
                var isbind = $("input[name=u_bind_phone]:checked").val();
                var ciccid = arg_iccid.val();
                if(isbind=='1'){
                    $("input[name=u_iccid]").attr("required", "true");
{*                    valid();*}
                    if(ciccid==''){
                        $("input[name=u_iccid]").focus();
                        exit();
                    }
                }
                //验证iccid
                $.ajax({
                    url:'?m=enterprise&a=geticcid&u_number='+$("input[name=u_number]").val(),
                    data:{
                        u_iccid:arg_iccid.val(),
                        e_id:{$data.e_id},
                        type:'iccid'
                    },
                    dataType:'json',
                    success:function(res){
                        if(res.status==2){
                            flag.val("Error");
                            iccid_stat.val(res.status);
                            arg_iccid.addClass("error");
                            arg_iccid.attr("aria-required","true");
                            arg_iccid.attr("aria-invalid","true");
                            arg_iccid.focus();
                            layer.tips("{'ICCID已绑定，请确认后重新输入'|L}",arg_iccid,{
                                tips:[1, '#A83A3A'],
                                time:600000
                            });
                            exit();
                        }else if(res.status==1){
                            flag.val("OK");
                            iccid_stat.val(res.status);
                        }else if(res.status==5){
                            var u_bind_phone = $("input[name=u_bind_phone]:checked").val();
                            iccid_stat.val(res.status);
                            if(u_bind_phone=='1'){
                                flag.val("Error");
                                arg_iccid.addClass("error");
                                arg_iccid.attr("aria-required","true");
                                arg_iccid.attr("aria-invalid","true");
                                arg_iccid.focus();
                                layer.tips("{'此ICCID库中不存在，请检查后重新填写'|L}",arg_iccid,{
                                    tips:[1, '#A83A3A'],
                                    time:600000
                                });
                                exit();
                            }else{
                               flag.val("OK");
                            }
                        }else if(res.status==4){
                            var check = true;
                            var check1 = true;
                            //如果填写的iccid存在并适用，提交时验证imsi是否匹配
                            //res.info.g_imsi!='' && 
                            if(res.info.g_imsi!=arg_imsi.val()){
                                flag.val("Error");
                                imsi_stat.val(res.status);
                                arg_imsi.addClass("error");
                                arg_imsi.attr("aria-required","true");
                                arg_imsi.attr("aria-invalid","true");
                                arg_imsi.focus();
                                layer.tips("{'所填写的IMSI不正确，请检查后重新填写'|L}",arg_imsi,{
                                    tips:[1, '#A83A3A'],
                                    time:600000
                                });
                                check = false;
                            }else{
                                check = true;
                            }

                            //如果填写的iccid存在并适用，提交时验证手机号是否匹配
                            if(res.info.g_number!='' && res.info.g_number!=arg_number.val()){
                                flag.val("Error");
                                number_stat.val(res.status);
                                 arg_number.addClass("error");
                                arg_number.attr("aria-required","true");
                                arg_number.attr("aria-invalid","true");
                                arg_number.focus();
                                layer.tips("{'所填写的手机号不正确，请检查后重新填写'|L}",arg_number,{
                                    tips:[1, '#A83A3A'],
                                    time:600000
                                });
                                check1 = false;
                            }else{
                                check1 = true;
                            }
                            
                            if(check==false || check1==false){
                                flag.val("Error");
                                exit();
                            }else{
                                flag.val("OK");
                                iccid_stat.val(res.status);
                            }
                            
                        }else if(res.status==3){
                            flag.val("Error");
                            iccid_stat.val(res.status);
                            arg_iccid.addClass("error");
                            arg_iccid.attr("aria-required","true");
                            arg_iccid.attr("aria-invalid","true");
                            arg_iccid.focus();
                            layer.tips("{'所填写的ICCID不正确，请检查后重新填写'|L}",arg_iccid,{
                                tips:[1, '#A83A3A'],
                                time:600000
                            });
                            exit();
                        }
                        //做到提示信息 不正确 还有 自动填充
                        layer.closeAll('tips');
                    }
                });
                //验证手机号
                $.ajax({
                    url:'?m=enterprise&a=geticcid&u_number='+$("input[name=u_number]").val(),
                    data:{
                        u_mobile_phone:arg_number.val(),
                        e_id:{$data.e_id},
                        type:'number'
                    },
                    dataType:'json',
                    success:function(res){
                        if(res.status==2){
                            flag.val("Error");
                            number_stat.val(res.status);
                            arg_number.addClass("error");
                            arg_number.attr("aria-required","true");
                            arg_number.attr("aria-invalid","true");
                            arg_number.focus();
                            layer.tips("{'手机号已绑定，请确认后重新输入'|L}",arg_number,{
                                tips:[1, '#A83A3A'],
                                time:600000
                            });
                            exit();
                        }else if(res.status==1){
                            flag.val("OK");
                            number_stat.val(res.status);
                        }else if(res.status==4){
                            auto_config = $("input[name=u_auto_config]:checked").val();
                            if(auto_config=='1'){
                                var check = true;
                                var check1 = true;
                                //如果填写的手机号存在并适用，提交时验证IMSI是否匹配
                                if(res.info.g_imsi!='' && res.info.g_imsi!=arg_imsi.val()){
                                    flag.val("Error");
                                    imsi_stat.val(res.status);
                                    arg_imsi.addClass("error");
                                    arg_imsi.attr("aria-required","true");
                                    arg_imsi.attr("aria-invalid","true");
                                    arg_imsi.focus();
                                    layer.tips("{'所填写的IMSI不正确，请检查后重新填写'|L}",arg_imsi,{
                                        tips:[1, '#A83A3A'],
                                        time:600000
                                    });
                                    check = false;
                                }else{
                                    check=true;
                                }
                                
                                //如果填写的手机号存在并适用，提交时验证ICCID是否匹配
                                if(res.info.g_iccid!='' && res.info.g_iccid!=arg_iccid.val()){
                                    flag.val("Error");
                                    iccid_stat.val(res.status);
                                    arg_iccid.addClass("error");
                                    arg_iccid.attr("aria-required","true");
                                    arg_iccid.attr("aria-invalid","true");
                                    arg_iccid.focus();
                                    layer.tips("{'所填写的ICCID不正确，请检查后重新填写'|L}",arg_iccid,{
                                        tips:[1, '#A83A3A'],
                                        time:600000
                                    });
                                    check1 = false;
                                }else{
                                    check1=true;
                                }
                                
                                if(check==false || check1==false){
                                    flag.val("Error");
                                    exit();
                                }else{
                                    flag.val("OK");
                                    number_stat.val(res.status);
                                }
                            }else{
                                if(arg_imsi.val()==''){
                                    arg_imsi.val(res.info.g_imsi);
                                }
                                if(arg_iccid.val()==''){
                                    arg_iccid.val(res.info.g_iccid);
                                }
                                flag.val("OK");
                            }
                        }else if(res.status==3){
                            flag.val("Error");
                            number_stat.val(res.status);
                             arg_number.addClass("error");
                            arg_number.attr("aria-required","true");
                            arg_number.attr("aria-invalid","true");
                            arg_number.focus();
                            layer.tips("{'所填写的手机号不正确，请检查后重新填写'|L}",$("input[name=u_mobile_phone]"),{
                                tips:[1, '#A83A3A'],
                                time:600000
                            });
                            exit();
                        }
                        //做到提示信息 不正确 还有 自动填充
                        layer.closeAll('tips');
                    }
                });
                //验证imsi
                $.ajax({
                    url:'?m=enterprise&a=geticcid&u_number='+$("input[name=u_number]").val(),
                    data:{
                        u_imsi:arg_imsi.val(),
                        e_id:{$data.e_id},
                        type:'imsi'
                    },
                    dataType:'json',
                    success:function(res){
                        if(res.status==2){
                            flag.val("Error");
                            imsi_stat.val(res.status);
                            arg_imsi.addClass("error");
                            arg_imsi.attr("aria-required","true");
                            arg_imsi.attr("aria-invalid","true");
                            arg_imsi.focus();
                            layer.tips("{'IMSI已绑定，请确认后重新输入'|L}",arg_imsi,{
                                tips:[1, '#A83A3A'],
                                time:600000
                            });
                            exit();
                        }else if(res.status==1){
                            flag.val("OK");
                            imsi_stat.val(res.status);

                        }else if(res.status==4){
                            var check = true;
                            var check1 = true;
                            //如果填写的IMSI存在并适用，提交时验证手机号是否匹配
                            if(res.info.g_number!='' && res.info.g_number!=arg_number.val()){
                                flag.val("Error");
                                number_stat.val(res.status);
                                arg_number.addClass("error");
                                arg_number.attr("aria-required","true");
                                arg_number.attr("aria-invalid","true");
                                arg_number.focus();
                                layer.tips("{'所填写的手机号不正确，请检查后重新填写'|L}",arg_number,{
                                    tips:[1, '#A83A3A'],
                                    time:600000
                                });
                                check = false;
                            }else{
                                check =true;
                            }
                            
                            //如果填写的IMSI存在并适用，提交时验证ICCID是否匹配
                            if(res.info.g_iccid!='' && res.info.g_iccid!=arg_iccid.val()){
                                flag.val("Error");
                                iccid_stat.val(res.status);
                                arg_iccid.addClass("error");
                                arg_iccid.attr("aria-required","true");
                                arg_iccid.attr("aria-invalid","true");
                                arg_iccid.focus();
                                layer.tips("{'所填写的ICCID不正确，请检查后重新填写'|L}",arg_iccid,{
                                    tips:[1, '#A83A3A'],
                                    time:600000
                                });
                                check1 = false;
                            }else{
                                check1=true;
                            }
                            
                            if(check==false || check1==false){
                                flag.val("Error");
                                exit();
                            }else{
                                flag.val("OK");
                                number_stat.val(res.status);
                            }
                        }else if(res.status==3){
                            flag.val("Error");
                            imsi_stat.val(res.status);
                            arg_imsi.addClass("error");
                            arg_imsi.attr("aria-required","true");
                            arg_imsi.attr("aria-invalid","true");
                            arg_imsi.focus();
                            layer.tips("{'所填写的IMSI不正确，请检查后重新填写'|L}",arg_imsi,{
                                tips:[1, '#A83A3A'],
                                time:600000
                            });
                            exit();
                        }
                        //做到提示信息 不正确 还有 自动填充
                        layer.closeAll('tips');
                    }
                });
            }
        }
    });
</script>
