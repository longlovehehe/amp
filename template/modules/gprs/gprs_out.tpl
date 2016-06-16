{strip}

<h2 class="title">{$title}</h2>

<div class="toptoolbar">
    <a  class="button orange active" id="gprs_agent">{"代理商"|L}</a>
    <a  class="button orange" id="gprs_enter">{"企业"|L}</a>
</div>
<div class="toolbar">
    <form action="?m=gprs&a=gprs_item_v2" id="form1" method="post">
        <input autocomplete="off"  name="modules" value="gprs" type="hidden" />
        <input autocomplete="off"  name="action" value="gprs_item_v2" type="hidden" />
        <input autocomplete="off"  name="page" value="0" type="hidden" />
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
            <input autocomplete="off"  class="autosend" name="g_iccid" type="text"/>
        </div>
        <div class="line">
            <label>{"归属地"|L}：</label>
            <input autocomplete="off"  class="autosend" name="g_belong" type="text" />
        </div>
        <div class="buttons right">
            <a form="form1" class="button submit">{"查询"|L}</a>
        </div>
    </form>
</div>


<form id="form" class="base mrbt10" >
    <input autocomplete="off"  name="modules" value="gprs" type="hidden" />
    <input autocomplete="off"  name="action" value="gprsshellout" type="hidden" />
    <input autocomplete="off"  name="page" value="0" type="hidden" />
    <input autocomplete="off"  name="create_type" value="agents" type="hidden" />
    <input autocomplete="off"  name="phone_num" value="{$smarty.session.ag.ag_phone_num-$phone}" type="hidden" />
    <input autocomplete="off"  name="dispatch_num" value="{$smarty.session.ag.ag_dispatch_num-$dispatch}" type="hidden" />
    <input autocomplete="off"  name="gvs_num" value="{$smarty.session.ag.ag_gvs_num-$gvs}" type="hidden" />
    <input autocomplete="off"  name="e_agents_id" value="{$smarty.session.ag.ag_number}" type="hidden" />
    <input autocomplete="off"  name="check_num" value="0" type="hidden" />
    <input autocomplete="off"  name="ag_diff_phone" value="{$diff_phone}" type="hidden" />
    <a class="button" id="seticcid">导入ICCID</a>
    <div class="block" style="text-align:center;margin-left:auto;margin-right:auto;">
        <label>{"记录人："|L}</label>
        <input autocomplete="off"   maxlength="32" value="{$smarty.session.own.om_id}" name="g_final_user" type="text"  required="true"/>
    </div>
    <table class="base full">
        <tr class='head'>
            <th class="" width="20px"><input autocomplete="off"  type="checkbox" id="checkall" /></th>
            <th><div style="width: 50px;">{"状态"|L}</div></th>
        <th><div style="width: 40px;">{"套餐"|L}</div></th>
        <th><div style="width: 90px;">{"ICCID"|L}</div></th>
        <th><div style="width: 40px;">{"归属地"|L}</div></th>
        <th><div style="width: 80px;">{"入库日期"|L}</div></th>
        <th><div style="width: 80px;">{"出库日期"|L}</div></th>
        <th><div style="width: 80px;">{"起始日期"|L}</div></th>
        <th><div style="width: 60px;">{"当前位置"|L}</div></th>
        <th><div style="width: 60px;">{"最后编辑人"|L}</div></th>
        <th class="none" width="50px"><div style="width: 40px;">{"详情"|L}</div></th>
        {*<th><div style="width: 60px;">{"操作"|L}</div></th>*}
        </tr>
    </table>
    <div class="content" style="height: 300px; overflow-x:hidden;">

    </div>
    <hr/>
    <div class="block">
        <label class="title">{"出库日期"|L}：</label>
        <input autocomplete="off"  class="autosend" id="dtime" onblur="getintime();" name="g_outtime" type="text" required="true" />
    </div>
    <div class="block" id="g_ag_id">
        <label class="title">{"选择代理"|L}：</label>
        <select name="g_ag_id" g_ag_id="true" action="?m=agents&a=option" required="true">
            <option value="">请选择</option>
        </select>
        {*<label class="agents_show none" style="color:#A43838;">所选手机用户数，多于代理商手机用户数</label>*}
    </div>
    <div class="block create_e none" >
        <label class="title">{"选择企业"|L}：</label>
        <select name="g_ag_en_id" g_ag_en_id="true" action="?m=enterprise&a=getagenlist" required="true">
            <option value="">请选择</option>
        </select>
        <label id="create_e"  style="padding-left: 20px;"><a  style="color: #A43838;" href="javascript:void(0);">{"创建企业"|L}</a></label>
    </div>
    <div style="color:#555;padding-left: 160px">*该<span id="used_name">代理商</span>可用手机用户数为<span id="ag_allow_num">0</span>个</div>
    <div class="gprs_enterprise none">
        <hr/>
        <div class="block">
            <label class="title">企业名称：</label>
            <input chinese="true"  maxlength="64" autocomplete="off" chinese="true"  maxlength="32" name="e_name" type="text" required="true" />
        </div>
        <div class="block">
            <label class="title">区域：</label>
            <select name="e_area" class="autofix autoselect" action="?m=area&a=option" selected="true" data='[{ "to": "e_mds_id","field": "d_area","view":"false" }]'>
                <option value='@'>未选择</option>
            </select>
        </div>
        <input autocomplete="off"  value="0" name="e_status" type="hidden" checked="checked" />
        <div class="block">
            <label class="title">企业密码：</label>
            <input  maxlength="32" autocomplete="off"  onpaste="return false" maxlength="32" e_pwd="true" name="e_pwd" type="text"/>
        </div>
        <div class="block">
            <label class="title">所属MDS：</label>
            <select value="" id="e_mds_id" name="e_mds_id" size="10"  class=" long" action="?m=device&action=mds_option" selected="true"></select>
        </div>

        <div class="block ">
            <label maxlength="32" class="title">企业用户数：</label>
            <input  maxlength="32" autocomplete="off"  value='0' name="e_mds_users" type="text"  readonly />
        </div>
        <div class="block none">
            <label class="title">企业并发数：</label>
            <input  maxlength="32" autocomplete="off"  maxlength="32" value='0' name="e_mds_call" type="text" required="true" digits ="true" />
        </div>
        <div class="allot_user none" >
            <div class="block">
                <label class="title">分配手机用户数：</label>
                <input  maxlength="32" e_mds_phone="true"  autocomplete="off" onpropertychange="getallow();" maxlength="32" value='0' name="e_mds_phone" type="text" required="true" digits ="true" />
                <label class="phone_show none" style="color:#A43838;">所选手机用户数，多于代理商手机用户数</label>
            </div>
            <div class="block">
                <label class="title">分配调度台用户数：</label>
                <input  maxlength="32"  autocomplete="off"  maxlength="32" value='0' name="e_mds_dispatch" type="text"  digits ="true" />
            </div>
            <div class="block">
                <label class="title">分配GVS用户数：</label>
                <input  maxlength="32" autocomplete="off"  maxlength="32" value='0' name="e_mds_gvs" type="text"  digits ="true" />
            </div>
        </div>

        <hr/>
        <div class="block">
            <label class="title">管理员姓名：</label>
            <input  maxlength="32" autocomplete="off"  maxlength="32"  name="em_name" em_name="true" type="text" required="true"  />
        </div>
        <div class="block">
            <label class="title">手机：</label>
            <input  maxlength="32" autocomplete="off"  maxlength="32" name="em_phone" type="text" mobile="true" required="true"  />
        </div>
        <div class="block">
            <label class="title">邮箱：</label>
            <input  maxlength="32" autocomplete="off"  maxlength="32"  name="em_mail" type="text" email="true" required="true" />
        </div>
    </div>



    <div class="buttons mrtop40">
        <a form="form" action="?modules=gprs&action=gprsshellout" class="ajaxpost button normal" id="sub_output">保存</a>
        <a class="goback button">取消</a>
    </div>
</form>

<div  id="light" class="white_content">
    <div style="background-color:#DCE0E1;"><div style="float:left;width: 20px;">&nbsp;</div><div class="c_dir">输入ICCID,并用,号分割</div></div>
    <div class="block">
        ICCID：
        <textarea name="iccid_list" id="iccid_list" style="width: 300px;height: 100px;overflow-y: auto;resize:none;"></textarea>
    </div>


    <div class="buttons mrtop40" style="float: right;">
        <a class="button normal" onclick="do_set();">保存</a>
        <a class=" button" onclick="closed();">取消</a>
    </div>
</div>

<div id="dialog-confirm" class="hide" title="{"删除选中项？"|L}">
    <p>{"确定要删除选中的设备吗？"|L}</p>
</div>
<script {'type="ready"'}>
    var phone_num = 0;
    (function () {
        var url = $("select[name=g_ag_id]").attr("action");
        var data = $("input[name=g_final_user]").val();
        $.ajax({
            url: url,
            data: data,
            success: function (result) {
                var hmtl = "<option value=''>请选择</option>";
                hmtl = hmtl + result;
                $("select[name=g_ag_id]").html(hmtl);
            }
        });
        var url1 = $("select[name=g_ag_en_id]").attr("action");
        var data1 = $("input[name=g_final_user]").val();
        $.ajax({
            url: url1,
            data: data1,
            success: function (result) {
                var hmtl = "<option value=''>请选择</option>";
                hmtl = hmtl + result;
                $("select[name=g_ag_en_id]").html(hmtl);
            }
        });
    })();
    initTable();
    $("select[name=g_ag_en_id]").change(function () {
        if ($("select[name=g_ag_en_id]").val() != "") {
            $("div.gprs_enterprise").addClass("none");
        }

    });

    /*js验证手机用户数的个数*/
    $("#checkall").click(function () {
        if ($("#checkall").is(":checked")) {
            $("input.cb:not([disabled])").prop("checked", "checked");
            var checkd = [];
            $("input.cb:checkbox:checked").each(function () {
                checkd.push($(this).val());
            });
            $("input[name=e_mds_phone]").val(checkd.length);
            $("input[name=check_num]").val(checkd.length);
        } else {
            var checkd = [];
            $("input.cb").removeAttr("checked");
            $("input.cb:checkbox:checked").each(function () {
                checkd.push($(this).val());
            });
            $("input[name=e_mds_phone]").val(checkd.length);
            $("input[name=check_num]").val(checkd.length);
        }
    });

    $("select[name=g_ag_id]").bind("change", function () {
        $("input[name=phone_num]").val($(this).children('option:selected').attr("diff_phone"));

        var diff_phone = $(this).children('option:selected').attr("diff_phone");
        phone_num = $(this).children('option:selected').attr("diff_phone");
        $("#ag_allow_num").html(phone_num);
        /*
         if ($(this).children('option:selected').attr("diff_phone") - $("input[name=e_mds_phone]").val() < 0) {
         notice("所选手机用户数，多于代理商手机用户数");
         if (diff_phone == 'undefined' || diff_phone == "" || diff_phone == 0) {
         $("select[name=g_ag_id]").attr("resource_less", 'TRUE');
         } else {
         prange = diff_phone;
         $("select[name=g_ag_id]").attr("max", prange);
         }

         } else {
         $("select[name=g_ag_id]").removeAttr('resource_less').removeAttr('max');
         }*/
    });
    $("select[name=g_ag_en_id]").bind("change", function () {
        $("input[name=phone_num]").val($(this).children('option:selected').attr("diff_phone"));
        $("select[name=g_ag_en_id]").attr("required", "true");
        var diff_phone = $(this).children('option:selected').attr("diff_phone");
        phone_num = $(this).children('option:selected').attr("diff_phone");
        $("#ag_allow_num").html(phone_num);
    });
    /*
     $("div.content").delegate("input.cb", "click", function () {
     var n = 0;
     $("input.cb:checkbox:checked").each(function () {
     n++;
     });
     $("input[name=e_mds_phone]").val(n);
     if ($("select[name=g_ag_id]").val() == "") {
     notice("请选择代理商");
     } else {

     }
     });
     */

    jQuery.validator.addMethod("g_ag_id", function (value, element) {
        var flag = false;
        $("select[name=g_ag_id]").each(function () {
            if ($(this).val() == value) {
                if ($("input[name=create_type]").val() == "agents") {
                    $("input[name=phone_num]").val($(this).children('option:selected').attr("diff_phone"));
                    phone_num = $(this).children('option:selected').attr("diff_phone");
                    if ($(this).children('option:selected').attr("diff_phone") - $("input[name=e_mds_phone]").val() < 0) {
                        flag = false;
                    } else {
                        flag = true;
                    }
                }
            }
        });
        $("div.content").delegate("input.cb", "click", function () {
            var n = 0;
            $("input.cb:checkbox:checked").each(function () {
                n++;
            });
            $("input[name=e_mds_phone]").val(n);
            if ($("input[name=create_type]").val() == "agents") {
                phone_num = $(this).children('option:selected').attr("diff_phone");
                if ($("input[name=phone_num]").val() - n < 0) {
                    flag = false;
                } else {
                    flag = true;
                }
            } else {

            }
        });

        return flag;
    }, "所选手机用户数，多于代理商手机用户数");
    jQuery.validator.addMethod("g_ag_en_id", function (value, element) {
        var flag = false;
        $("select[name=g_ag_en_id]").each(function () {
            if ($(this).val() == value) {
                if ($("input[name=create_type]").val() == "enterprise") {
                    $("input[name=phone_num]").val($(this).children('option:selected').attr("diff_phone"));
                    phone_num = $(this).children('option:selected').attr("diff_phone");
                    if ($(this).children('option:selected').attr("diff_phone") - $("input[name=e_mds_phone]").val() < 0) {
                        flag = false;
                    } else {
                        flag = true;
                    }
                }
            }
        });
        $("div.content").delegate("input.cb", "click", function () {
            var n = 0;
            $("input.cb:checkbox:checked").each(function () {
                n++;
            });
            $("input[name=e_mds_phone]").val(n);
            if ($("input[name=create_type]").val() == "enterprise") {
                phone_num = $(this).children('option:selected').attr("diff_phone");
                if ($("input[name=phone_num]").val() - n < 0) {
                    flag = false;
                } else {
                    flag = true;
                }
            } else {

            }
        });

        return flag;
    }, "所选手机用户数，多于代理商手机用户数");

    $("div.content").delegate("input.cb", "click", function () {
        var n = 0;
        $("input.cb:checkbox:checked").each(function () {
            n++;
        });
        $("input[name=e_mds_phone]").val(n);
        $("input[name=check_num]").val(n);

    });
    /**
     $("#sub_output").bind("click", function () {
     var url = $("#sub_output").attr("action");
     var dtime = $("#dtime").val();
     var ag_number = $("select[name=g_ag_id]").val();
     var g_final_user = $("input[name=g_final_user]").val();
     var dd = $("input[type=checkbox]").serialize();
     var data1 = $("#form").serialize();
     var type = $("input[name=create_type]").val();
     if (type == "agents") {
     var data = dd + "&ag_number=" + ag_number + "&g_final_user=" + g_final_user;
     if (dd == "") {
     notice("未选中选项");
     return false;
     }
     else if (dtime == "") {
     notice("未填写出库日期");
     return false;
     }
     else if (ag_number == "") {
     notice("未填写目标代理商");
     return false;
     }
     } else {
     var data = data1 + "&" + dd + "&ag_number=" + ag_number + "&g_final_user=" + g_final_user;
     }

     $.ajax({
     url: url,
     data: data,
     success: function (result) {
     notice(result, window.location.href);
     }
     });
     });
     */

</script>
{/strip}
