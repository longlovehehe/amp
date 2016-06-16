{strip}
<h2 class="title">{"迁移{$smarty.session.ident}-RS"|L}</h2>
<div class="info lineheight25">
    <div class="block ">
        <label class="title">{"企业ID"|L}：</label>
        <span>{$data.e_id}</span>
    </div>

    <div class="block ">
        <label class="title">{"企业名称"|L}：</label>
        <span>{$data.e_name}</span>
    </div>

    <div class="block ">
        <label class="title">{"所属区域"|L}：</label>
        <span>{$data.e_area|mod_area_name}</span>
    </div>
    <div class="block ">
        <label class="title">{"当前企业分配的用户数"|L}：</label>
        <span class="cur_e_mds_users">{$data.e_mds_users|default: 0}</span>
    </div>
    <div class="block none">
        <label class="title">{"当前企业分配的并发数"|L}：</label>
        <span class="cur_e_mds_call">{$data.e_mds_call|default: 0}</span>
    </div>
    <div class="block ">
        <label class="title">{"当前企业分配的手机用户数"|L}：</label>
        <span class="cur_e_mds_phone">{$data.e_mds_phone|default: 0}</span>
    </div>
    <div class="block ">
        <label class="title">{"当前企业分配的调度台用户数"|L}：</label>
        <span class="cur_e_mds_dispatch">{$data.e_mds_dispatch|default: 0}</span>
    </div>
    <div class="block ">
        <label class="title">{"当前企业分配的GVS用户数"|L}：</label>
        <span class="cur_e_mds_gvs">{$data.e_mds_gvs|default: 0}</span>
    </div>
    <div class="block ">
        <label class="title">{"预计并发数"|L}：</label>
        <span class="cur_e_rs_rec">{$data.e_rs_rec|default: 0}</span>
    </div>
</div>

<form id="form" class="base mrbt10">
    <input autocomplete="off"  value="{$data.e_id}" name="e_id" type="hidden" />
    <input autocomplete="off"  value="{$data.e_create_name}" name="e_create_name" type="hidden" />
   
    <div class="block ">
        <label class="title">{"原{$smarty.session.ident}-RS"|L}:</label>
        <span>{$data.rs_name}</span>
        <input autocomplete="off"  value="{$data.e_vcr_id}" id="old_e_vcr_id" type="hidden" />
        <div class="data none" id="cur_e_vcr_id">{$data.e_vcr_id}</div>
    </div>
    <div class="block ">
        <label class="title">{"新的{$smarty.session.ident}-RS地址"|L}</label>
        <select id="e_vcr_id" name="new_vcr_id" new_vcr_id="true" class=" long " size="10" action="?m=device&a=rs_option&d_deployment_id={$data.d_deployment_id}" selected="true" digits ="true"></select>
        <span class="vcr_error none">{"该设备可用用户数少于目前企业用户数，无法迁移，请选择其他设备"|L}</span>
    </div>
    <div class="buttons mrtop40">
        <a id="move_rs" class="button green">{"迁移{$smarty.session.ident}-RS"|L}</a>
        <a href="?m=enterprise&a=view&e_id={$data.e_id}" class="button">{"取消"|L}</a>
    </div>
</form>
<div id="dialog-confirm" class="hide" title="{'操作确认'|L}">
    <p>{"确定要迁移吗"|L}？</p>
</div>

<script>
   
    (function () {
        var url = $("select#e_vcr_id").attr("action");
        $.ajax({
            url: url,
            success: function (result) {
                $("select#e_vcr_id").html(result);
            }
        });
    })();

    var phone_num = $("div.block span.cur_e_mds_phone").text();
    var dispatch_num = $("div.block span.cur_e_mds_dispatch").text();
    var gvs_num = $("div.block span.cur_e_mds_gvs").text();
    var sub = true;
    $("select#e_vcr_id").bind("change", function () {
        var e_mds_id = $(this).children('option:selected').val();
        var select_e_mds_id = $("#cur_e_mds_id").html();
        var d_user = $(this).children('option:selected').attr("d_user");
        /*var d_call = $(this).children('option:selected').attr("d_call");*/
        var diff_phone = $(this).children('option:selected').attr("diff_phone");
        var diff_dispatch = $(this).children('option:selected').attr("diff_dispatch");
        var diff_gvs = $(this).children('option:selected').attr("diff_gvs");
        if (e_mds_id != select_e_mds_id) {
            if (diff_phone - phone_num < 0) {
                $("div.block span.mds_error").removeClass("none");
                sub = false;
            } else if (diff_dispatch - dispatch_num < 0) {
                $("div.block span.mds_error").removeClass("none");
                sub = false;
            } else if (diff_gvs - gvs_num < 0) {
                $("div.block span.mds_error").removeClass("none");
                sub = false;
            } else {
                $("div.block span.mds_error").addClass("none");
                sub = true;
            }
        }else{
                $("div.block span.mds_error").addClass("none");
                sub = true;
        }
        $("#form").valid();
    });

    $("#move_rs").click(function () {
        if ($("#form").valid()) {
            var flag = false;
            var old_e_vcr_id = $("#old_e_vcr_id").val();
            var new_e_vcr_id = parseInt($("select[name=new_vcr_id] option:selected").val());
            if(old_e_vcr_id == new_e_vcr_id)
            {
                notice("{"您没有更改RS， 请重新选择或取消迁移"|L}");
                flag = true;
            }
            var e_rs_rec = parseInt($("select[name=new_vcr_id] option:selected").attr("d_have"));
            var cur_e_rs_rec = $('.cur_e_rs_rec').text();
            if(e_rs_rec < cur_e_rs_rec)
            {
                notice("{"迁移到的{$smarty.session.ident}-RS可用并发数比当前企业并发数小，无法迁移，请选择其他设备"|L}");
                flag = true;
            }
                
    if (!flag) {
    $("#dialog-confirm").dialog({
    resizable: false,
            height: 180,
            modal: true,
            buttons: {
            "{"迁移"|L}": function () {
            $(this).dialog("close");
                    if (sub == false) {
            return false;
            }
            notice("{"正在操作中"|L}");
                    $.ajax({
                    url: "?modules=enterprise&action=move_vcr_item",
                            data: $("#form").serialize(),
                            dataType: "json",
                            success: function (result) {
                            if (result.status == 0) {
                            notice(result.msg, '?m=enterprise&a=view&e_id={$data.e_id}');
                            } else {
                            notice(result.msg);
                            }

                            }
                    });
            },
                    "{"取消"|L}": function () {
                    $(this).dialog("close");
                    }
            }
    });
    }
    }
    });
</script>
{/strip}