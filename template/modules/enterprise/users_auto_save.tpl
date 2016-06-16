{strip}

<h2 class="title">{"批量新增企业用户"|L}</h2>

<form id="form" class="base mrbt10" target="ifr">

    <input autocomplete="off"  value="enterprise" name="modules" type="hidden" />

    <input autocomplete="off"  value="users_auto_save_shell" name="action" type="hidden" />

    <input autocomplete="off"  value="{$data.e_id}" name="e_id" type="hidden" />

    <input autocomplete="off"  value="0" name="step" type="hidden" />

    <input autocomplete="off"  value="{$list.e_mds_phone-$phone_num}" name="e_mds_phone" type="hidden" />

    <input autocomplete="off"  value="{$list.e_mds_dispatch-$dispatch_num}" name="e_mds_dispatch" type="hidden" />

    <input autocomplete="off"  value="{$list.e_mds_gvs-$gvs_num}" name="e_mds_gvs" type="hidden" />

    <div class="block">

        <div class="radioset" id="radioset" value="{$item.u_sub_type}">

            <input autocomplete="off"  class="checked_radio" value="1" type="radio" id="radio_user" name="u_sub_type"  checked="checked" /><label for="radio_user">{"手机用户"|L}</label>

            <input autocomplete="off" class="checked_radio"  value="2" type="radio" id="radio_shelluser" name="u_sub_type" /><label for="radio_shelluser">{"调度台用户"|L}</label>

            <input autocomplete="off" class="checked_radio"  value="3" type="radio" id="radio_gvsuser" name="u_sub_type" /><label for="radio_gvsuser">{"GVS用户"|L}</label>

        </div>

    </div>



    <h3 class="title">{"基本属性"|L}</h3>

    <hr />

    <div class="block">

        <label class="title">{"起始帐号"|L}：</label>

        <input autocomplete="off"   maxlength="32" name="u_auto_pre" type="text" required="true" digits="true" u_number="true" />

    </div>

    <div class="block">

        <label class="title">{"数量"|L}：</label>

        <input autocomplete="off"   maxlength="32" name="u_auto_number" u_auto_number="true" type="text"  digits="true" {*min="1"range="[1,799999]"*} />

        <span id="num_sure" style="color:#A43838;" class="surenum "> {"手机用户当前最大可输入"|L}{$list.e_mds_phone-$phone_num}</span>

    </div>

    <div class="block">

        <label class="title">{"密码"|L}：</label>

        <div class="line">

            <label><input autocomplete="off"  value="1" name="u_auto_pwd" type="radio" />{"与帐号相同"|L}</label>

        </div>

        <div class="line">

            <label><input autocomplete="off"  value="0" name="u_auto_pwd" type="radio" checked="checked" />{"随机生成"|L}</label>

        </div>

    </div>



    <div class="block sw user shelluser">

        <label class="title">{"默认群组"|L}：</label>

        <select value="{$item.u_default_pg}" name="u_default_pg" class="autofix autoedit" action="?m=enterprise&a=groups_option&safe=true&e_id={$data.e_id}">

            <option value="">{"未指定"|L}</option>

        </select>

    </div>
<div class="block sw user">
        <label class="title">{"订购产品"|L}：</label>
        <select value="{$item.u_product_id}" name="u_product_id" class="autofix autoedit" action="?m=product&a=option&e_id={$data.e_id}" >
            <option value="">{"无"|L}</option>
        </select>
    </div>
    <div class="block sw user none">

        <div class="block" style="float:left"><label class="title" style="">{"增值功能"|L}：</label></div>

         <div class="title" style="width:220px; border:1px solid #ccc; padding: 10px;"><div id="product_select" class="autofix  autocheck"  value="{$item.u_p_function|escape:"html"}" action="?m=product&a=ip_option&e_id={$data.e_id}"></div></div>

        {*<label class="title">{"订购产品"|L}：</label>

        <select value="{$item.u_product_id}" name="u_product_id" class="autofix autoedit" action="?m=product&a=option&e_id={$data.e_id}">

            <option value="">{"未指定"|L}</option>

        </select>*}

    </div>

    <div class="block">

        <label class="title">{"部门"|L}：</label>

        <select value="{$item.u_ug_id}" name="u_ug_id" class="autofix autoedit" action="?modules=api&action=get_groups_list&e_id={$data.e_id}">

            <option value="">{"未指定"|L}</option>

        </select>

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
    <div class="block radio" value="{$item.u_attr_type}">
        <div class="line">
            <label class="title">{"用户分类"|L}：</label>
            <label class="radiotext">
                <input autocomplete="off"  value="1" name="u_attr_type" type="radio"  />
                <span>{"测试"|L}</span>
            </label>
            <label class="radiotext">
                <input autocomplete="off"  value="0" name="u_attr_type" type="radio" checked="checked"  />
                <span>{"商用"|L}</span>
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
                {*<option value="5">{"Google Map定位"|L}</option>*}
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
         <div class="block radio" value="{$item.u_gprs_genus}">
            <label class="title">{"流量卡所属"|L}：</label>
            <div class="line">
                <label class="radiotext"><input autocomplete="off"  value="1" name="u_gprs_genus" type="radio" />{"用户自有"|L}</label>
            </div>
            <div class="line">
                <label class="radiotext"><input autocomplete="off"  value="0" name="u_gprs_genus" type="radio" checked="checked" />{"运营商提供"|L}</label>
            </div>
        </div>

        
        <div class="block radio" value="{$item.u_auto_run}">

            <label class="title">{"强制开机启动"|L}：</label>

            <div class="line">

                <label class="radiotext"><input autocomplete="off"  value="1" name="u_auto_run" type="radio" />{"启用"|L}</label>

            </div>

            <div class="line">

                <label class="radiotext"><input autocomplete="off"  value="0" name="u_auto_run" type="radio" checked="checked" />{"停用"|L}</label>

            </div>

        </div>



        <div class="block radio" value="{$item.u_checkup_grade|default: 1}">

            <label class="title">{"程序检查更新"|L}：</label>

            <div class="line">

                <label class="radiotext"><input autocomplete="off"  value="1" name="u_checkup_grade" type="radio" />{"启用"|L}</label>

            </div>

            <div class="line">

                <label class="radiotext"><input autocomplete="off"  value="0" name="u_checkup_grade" type="radio" checked="checked" />{"停用"|L}</label>

            </div>

        </div>

        <div class="block radio" value="{$item.u_encrypt}">

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



    <div class="buttons mrtop40">

        <a id="create" class="button normal">{"生成"|L}</a>

        <a class="goback button" action="?m=enterprise&a=users&e_id={$data.e_id}">{"取消"|L}</a>

    </div>

</form>

<div class="makeing info_text hide">

    <h2 class="title ">{"正在生成中，目前已处理"|L} <span id="u_step_text"></span> {"个，还差"|L} <span id="u_step_number_text"></span> {"个"|L}</h2>

    <progress max="{$data.max}" value="{$data.value}" class="progress"></progress>

</div>



<iframe id="iframe" name="ifr" class="display_box hide"></iframe>

{/strip}

