{strip}
{include file="modules/enterprise/nav.tpl" }
<h2 class="title" style="padding-bottom: 25px;"><span class='ellipsis2' style='max-width: 350px;height: 20px;'>{$data.e_name|mbsubstr:20}</span> - {"企业用户"|L}</h2>
<div class="toptoolbar  p20">
    <a href="?m=enterprise&a=users_save&e_id={$data.e_id}" class="button orange">{"新增企业用户"|L}</a>
    <a href="?m=enterprise&a=users_auto_save&e_id={$data.e_id}" class="button orange">{"批量新增企业用户"|L}</a>
</div>
<div class="toolbar">
    <form action="?m=enterprise&a=users_item&e_id={$data.e_id}" id="form" method="post">
        <input autocomplete="off"  name="modules" value="enterprise" type="hidden" />
        <input autocomplete="off"  name="action" value="users_item" type="hidden" />
        <input autocomplete="off"  name="e_id" value="{$data.e_id}" type="hidden" />
        <input autocomplete="off"  name="page" value="{$page}" type="hidden" />
        <h3 class="title">{"基本属性"|L}</h3>
        <div class="line">
            <label>{"姓名"|L}：</label>
            <input value='{$smarty.request.u_name}' autocomplete="off"  class="autosend" name="u_name" type="text" />
        </div>
        <div class="line">
            <label>{"号码"|L}：</label>
            <input value='{$smarty.request.u_number}' autocomplete="off"  class="autosend" name="u_number" type="text" />
        </div>
        <div class="line">
            <label>{"用户类型"|L}：</label>
            <select name="u_sub_type">
                <option value="">{"全部"|L}</option>
                <option value="1">{"手机用户"|L}</option>
                <option value="2">{"调度台用户"|L}</option>
                <option value="3">{"GVS用户"|L}</option>
            </select>
        </div>
        <div class="line">
            <label>{"订购产品"|L}：</label>
            <select name="u_product_id" class="autofix" action="?m=product&a=option&e_id={$data.e_id}" >
                <option value="">{"全部"|L}</option>
            </select>
        </div>
        <div class="line none">
            <label>{"所属群组"|L}：</label>
            <select name="u_default_pg" class="autofix" action="?m=enterprise&a=groups_option&e_id={$data.e_id}">
                <option value="">{"全部"|L}</option>
            </select>
        </div>
        <div class="line">
            <label>{"部门"|L}：</label>
            <select name="u_ug_id" class="autofix" action="?modules=api&action=get_groups_list&e_id={$data.e_id}" >
                <option value="">{"全部"|L}</option>
            </select>
        </div>
        <div class="line">
            <label>{"用户分类"|L}：</label>
            <select name="u_attr_type">
                <option value="">{"全部"|L}</option>
                <option value="1">{"测试"|L}</option>
                <option value="0">{"商用"|L}</option>
            </select>
        </div>
        <div class="line none">
        <div class="line" style="float:left;width: 50px;"><label class="title" style="">{"增值功能"|L}：</label></div>
        <div class="line" style="width:640px;"><div id="product_select" class="autofix  autocheck"  value="{$item.u_p_function|escape:"html"}" action="?m=product&a=ip_option&e_id={$data.e_id}"></div></div>
    </div>
        <h3 class="title">{"详细属性"|L}<a class="toggle alink" data="detailed">{"展开"|L}↓</a></h3>
        <div class="detailed none">
            <div class="line none">
                <label>{"头像"|L}：</label>
                <select name="u_pic">
                    <option value="">{"全部"|L}</option>
                    <option value="1">{"有头像"|L}</option>
                    <option value="0">{"无头像"|L}</option>
                </select>
            </div>
            <div class="line none">
                <label>{"性别"|L}：</label>
                <select name="u_sex">
                    <option value="">{"全部"|L}</option>
                    <option value="M">{"男"|L}</option>
                    <option value="F">{"女"|L}</option>
                </select>
            </div>
            <div class="line none">
                <label>{"手机号"|L}：</label>
                <input autocomplete="off"  class="autosend" name="u_mobile_phone" type="text" />
            </div>
            <div class="line none">
                <label>UDID：</label>
                <input autocomplete="off"  class="autosend" name="u_udid" type="text" />
            </div>
            <div class="line">
                <label>IMSI：</label>
                <input autocomplete="off"  class="autosend" name="u_imsi" type="text" />
            </div>
            <div class="line">
                <label>IMEI：</label>
                <input autocomplete="off"  class="autosend" name="u_imei" type="text" />
            </div>
            <div class="line">
                <label>ICCID：</label>
                <input autocomplete="off"  class="autosend" name="u_iccid" type="text" />
            </div>
            <div class="line">
                <label>MAC：</label>
                <input autocomplete="off"  class="autosend" name="u_mac" type="text" />
            </div>
            <div class="line">
                <label>{"终端类型"|L}：</label>
                <input autocomplete="off"  class="autosend" name="u_terminal_type" type="text" />
            </div>
        </div>
        <div class="buttons right">
            <a form="form" class="button submit" style="margin-right: 60px"><i class="icon-search"></i>{"查询"|L}</a>
        </div>
    </form>
</div>
<div class="toolbar">
    <a id="delall" class="button">{"批量删除"|L}</a>
    <a id="batch_toggle" class="button green">{"选中项批量修改"|L}</a>
    <a id="move_user" class="button green">{"选中项移动到企业"|L}</a>
    <a id="move_u_default_pg" class="button green">{"选中项分配到群组"|L}</a>
    <form class="batch hide" id="batch" action="?modules=enterprise&action=users_item&e_id={$data.e_id}">
        <input type="hidden" name="submit_type" value="batch">
        <div class="line none">
            <label>{"默认群组"|L}：</label>
            <select name="u_default_pg" class="autofix" action="?m=enterprise&a=groups_option&safe=true&e_id={$data.e_id}" required="true">
                <option value="">{"清除群组信息"|L}</option>
                <option selected='selected' value="%">{"保留群组信息"|L}</option>
            </select>
        </div>
            <div class="line">
            <label style="width:100px;">{"订购产品"|L}：</label>
            <select name="u_product_id" class="autofix" action="?m=product&a=option&e_id={$data.e_id}" required="true">
                <option value="">{"清除产品信息"|L}</option>
                <option selected='selected' value="%">{"保留产品信息"|L}</option>
            </select>
        </div>
        <div class="line">
            <input name="isused" type="checkbox" value="on"/>
            <label>{"次月生效"|L}</label>
        </div>
            <br/>
        <div class="line">
            <label style="width:100px;">{"GPS上报方式"|L}：</label>
            <select name="u_gis_mode">
                <option selected='selected' value="%">{"保留上报方式"|L}</option>
                <option value="0">{"不上报"|L}</option>
                <option value="1">{"强制百度智能定位"|L}</option>
                <option value="3">{"强制百度GPS定位"|L}</option>
                <option value="4">{"强制GPS定位"|L}</option>
                <option value="2">{"客户端设置"|L}</option>
    {*            <option value="5">{"Google Map定位"|L}</option>*}
            </select>
        </div> 
        <div class="line">
            <label style="width:100px;">{"拍传接收号码"|L}：</label>
            <select name="u_mms_default_rec_num" class="autofix" action="?m=enterprise&a=shelluser&e_id={$data.e_id}" required="true">
                <option selected='selected' value="%">{"保留拍传接收号码"|L}</option>
                <option value="">{"无"|L}</option>
            </select>
        </div>
        <div class="line">
            <label  style="width:100px;">{"所属部门"|L}：</label>
            <select name="u_ug_id" class="autofix" action="?modules=api&action=get_groups_list&e_id={$data.e_id}" required="true">
                <option value="">{"清除部门信息"|L}</option>
                <option selected='selected' value="%">{"保留部门信息"|L}</option>
            </select>
        </div>
        <div class="line">
            <label style="width:100px;">{"一键告警号码"|L}：</label>
            <select name="u_alarm_inform_svp_num" class="autofix" action="?m=enterprise&a=shelluser&e_id={$data.e_id}" required="true">
                <option selected='selected' value="%">{"保留一键告警号码"|L}</option>
                <option value="">{"无"|L}</option>
                <option value="@">{"自定义"|L}</option>
            </select>
            <input class="none" style="margin-left:10px;width:120px;border-style: ridge;border-width:1px"maxlength="11" type="text" check_number="true" u_alarm_inform_svp_num="true" name="u_alarm_inform_svp_num" >
            <label id="u_alarm_inform_svp_num-error" class="error none" style="color:#a43838" for="u_alarm_inform_svp_num">{"该号码不存在"|L}</label>
        </div>
        <br/>
        <div class="line none">
            <label style="width:100px;">{"用户分类"|L}：</label>
            <select name="u_attr_type">
                <option selected='selected' value="%">{"保留用户分类"|L}</option>
                <option value="1">{"测试"|L}</option>
                <option value="0">{"商用"|L}</option>
            </select>
        </div>
        <div class="line">
            <label style="width:100px;">{"只显示本部门"|L}：</label>
            <select name="u_only_show_my_grp">
                <option selected='selected' value="%">{"保留当前选择"|L}</option>
                <option value="1">{"启用"|L}</option>
                <option value="0">{"停用"|L}</option>
            </select>
        </div>
        <div class="buttons right">
            <a id="batch_submit" class="button" style="margin-right: 60px">{"批量修改"|L}</a>
        </div>
    </form>
    
    <!--批量修改企业用户增值功能-->
   {* <form class="product hide">
         <input type="hidden" name="submit_type" value="product">
        <div class="line">
            <br />
            <br />
            <div name="product_select" id="product_select" class="autofix" action="?m=product&a=ip_option&e_id={$data.e_id}"></div>
        </div>
        <a id="batch_add" class="button">{"批量添加"|L}</a>
    </form>*}
    
    <form class="move_user hide">
        <input type="hidden" name="submit_type" value="move_user">
        <div class="line">
            <label>{"移动至该企业"|L}：</label>
            <select name="to_e_id" class="autofix" action="?modules=enterprise&action=index_item&do=console&ec_id={$data.e_id}&ag_number={$smarty.session.ag.ag_number}">
                <option value=''>{"未选择"|L}</option>
            </select>
        </div>
        <div class="buttons right">
            <a id="move_all" class="button" style="margin-right: 60px">{"批量移动"|L}</a>
        </div>
        
    </form>
    <form class="move_u_default_pg hide">
        <input type="hidden" name="submit_type" value="move_u_default_pg">
        <div class="line">
            <label>{"分配至该群组"|L}：</label>
            <select name="move_u_default_pg" class="autofix" action="?modules=api&action=get_ptt_member_list&e_id={$data.e_id}" required="true">
                <option value=''>{"未选择"|L}</option>
            </select>
        </div>
        <div class="line">
            <label>{"设置成员级别"|L}：</label>
            <input autocomplete="off"  name="move_u_level" value="" range='[0,255]' />
        </div>
        <div class="line none">
            <label><input autocomplete="off"  name="move_u_hangup" type="checkbox" />{"被叫挂断权限"|L}</label>
        </div>
        <div class="line">
            <label><input autocomplete="off"  name="move_u_default" type="checkbox" />{"设为默认组"|L}</label>
        </div>
        <div class="buttons right">
            <a id="groups_move_all" class="button" style="margin-right: 60px">{"批量分配"|L}</a>
        </div>
    </form>
</div>
<div>
    <table class="full">
        <tr class='head' style="height: 35px;" type="user" url="?m=enterprise&action=users">
            <td width="110px" class="clickPage">{"用户列表"|L}</td>
            <td width="490px" class="clickPage" style="text-align:right;">{"显示条数"|L}：</td>
            <td width="50px" onclick="clickPage(this)" class="clickPage" {$smarty.session.color.10} onmouseover="this.style.cursor='pointer'">10</td>
            <td width="50px" onclick="clickPage(this)" class="clickPage" {$smarty.session.color.20} onmouseover="this.style.cursor='pointer'">20</td>
            <td width="50px" onclick="clickPage(this)" class="clickPage" {$smarty.session.color.50} onmouseover="this.style.cursor='pointer'">50</td>
        </tr>
    </table>
</div>
<div class="content"></div>
<div id="dialog-confirm" class="hide" title="{'删除选中项'|L}？">
    <p>{"确定要删除选中的企业用户吗"|L}？</p>
</div>
<div id="dialog-confirm-batch" class="hide" title="{'更新选中项'|L}？">
    <p>{"确定要批量更新选中的企业用户吗"|L}？</p>
</div>
<script  {'type="ready"'}>
    $("input[name=u_alarm_inform_svp_num]").keydown(function(event){
        if(event.keyCode == 13){
                return false;
        }
    });
    $("input[name=move_u_level]").keydown(function(event){
        if(event.keyCode == 13){
                return false;
        }
    });
    $("#delall").click(function () {
    var checkd = "";
            $("input.cb:checkbox:checked").each(function () {
    checkd += $(this).val() + ",";
    });
            if (checkd === "") {
    notice("{"未选中任何企业用户"|L}");
    } else {
    $("#dialog-confirm").dialog({
    resizable: false,
            height: 180,
            modal: true,
            buttons: {
            "{"删除"|L}": function () {
            $(this).dialog("close");
                    $.ajax({
                    url: "?modules=enterprise&action=users_del&e_id=" + e_id,
                            data: $("form.data").serialize(),
                            success: function (result) {
                            notice("{"成功删除"|L} " + result + " {"个企业用户"|L}");
                                    var page = $("input[name=page]").val();
                                    $.ajax({
                                    url: "?modules=enterprise&action=getusernum&e_id=" + e_id + "&page=" + page,
                                            datatype: "html",
                                            success: function (result) {
                                            if (result == 0) {
                                            setTimeout(function () {
                                            send("prev");
                                            }, 888);
                                            } else {
                                            setTimeout(function () {
                                            send("stay");
                                            }, 888);
                                            }
                                            }
                                    });
                            }
                    });
            },
                    "{"取消"|L}": function () {
                    $(this).dialog("close");
                    }
            }
    });
    }
    });
            $("#move_all").click(function () {
    var checkd = "";
            var to_e_id = $("select[name=to_e_id]").val();
            $("input.cb:checkbox:checked").each(function () {
    checkd += $(this).val() + ",";
    });
            if (checkd === "") {
    notice("{'未选中任何企业用户'|L}");
    } else if (to_e_id == ""|| to_e_id == null) {
        notice("{'未选中转移企业'|L}");
    } else {
    var data = "to_e_id=" + to_e_id + '&';
            data += $("form.data").serialize();
            $.ajax({
            url: "?m=enterprise&a=users_move&e_id=" + e_id,
                    data: data,
                    dataType: "json",
                    success: function (result) {
                    if (result.status == 0) {
                    notice(result.msg);
                    } else {
                    notice(result.msg);
                    }
                    send();
                    }
            });
    }
    });
            $("#groups_move_all").click(function () {
    var checkd = "";
            var move_u_default_pg = $("select[name=move_u_default_pg]").val();
            if ($("input[name=move_u_default]").is(":checked")) {
    $.ajax({
    url: "?m=enterprise&a=getimpgroups&pg_number=" + move_u_default_pg + "&e_id=" + e_id,
            method: "GET",
            dataType: 'json',
            success: function (result) {
            if (result.status == "-1") {
            notice(result.msg);
                    exit();
            }
            }
    });
    }
    $("input.cb:checkbox:checked").each(function () {
    checkd += $(this).val() + ",";
    });
            if (checkd === "") {
    notice("{'未选中任何企业用户'|L}");
    } else if (move_u_default_pg == "") {
    notice("{'未选中转移群组'|L}");
    } else if ($("form.move_u_default_pg").valid()) {
    var data = $("form.move_u_default_pg").serialize() + "&" + $("form.data").serialize();
            $.ajax({
            url: "?m=enterprise&a=groups_users_move&e_id=" + e_id,
                    data: data,
                    dataType: "json",
                    success: function (result) {
                    notice(result.msg);
                            send();
                    }
            });
    }
    });
    $("#batch_submit").click(function () {
    var checkd = "";
            $("input.cb:checkbox:checked").each(function () {
    checkd += $(this).val() + ",";
    });
            if (checkd === "") {
    notice("{'未选中任何企业用户'|L}");
    } else {
    var data = $("form#batch").serialize() + "&" + $("form.data").serialize();
            $("#dialog-confirm-batch").dialog({
    resizable: false,
            height: 180,
            modal: true,
            buttons: {
            "{"更新"|L}": function () {
            $(this).dialog("close");
                    $.ajax({
                    url: "?modules=enterprise&action=users_batch&e_id=" + e_id,
                            data: data,
                            success: function () {
                            send();
                            }
                    });
            },
                    "{"取消"|L}": function () {
                    $(this).dialog("close");
                    }
            }
    });
    }
    });
   {* $("#batch_add").click(function () {
    var checkd = "";
            $("input.cb:checkbox:checked").each(function () {
    checkd += $(this).val() + ",";
    });
            if (checkd === "") {
    notice("{'未选中任何企业用户'|L}");
    } else {
    var data = $("form.product").serialize() + "&" + $("form.data").serialize();
            $("#dialog-confirm-batch").dialog({
    resizable: false,
            height: 180,
            modal: true,
            buttons: {
            "{"更新"|L}": function () {
            $(this).dialog("close");
                    $.ajax({
                    url: "?modules=enterprise&action=users_batch_p&e_id=" + e_id,
                            data: data,
                            success: function () {
                            send();
                            }
                    });
            },
                    "{"取消"|L}": function () {
                    $(this).dialog("close");
                    }
            }
    });
    }
    });*}
</script>
{/strip}
