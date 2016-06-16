{strip}
<h2 class="title">{"迁移设备"|L}</h2>
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
</div>

<form id="form" class="base mrbt10">
    <input autocomplete="off"  value="{$data.e_id}" name="e_id" type="hidden" />
    <input autocomplete="off"  value="{$data.e_create_name}" name="e_create_name" type="hidden" />
    <div class="block ">
        <label class="title">{"原{$smarty.session.ident}-Server"|L}</label>
        <span>{$data.mds_d_name}</span>
        <div class="data none" id="cur_e_mds_id">{$data.e_mds_id}</div>
    </div>
    <div class="block">
        <label class="title">{"企业区域"|L}：</label>
        <select id="e_area" name="e_area" class="autofix" action="?m=area&a=option_create&e_id={$data.e_id}" selected="true"  data='[{ "to": "e_mds_id","field": "d_area","view":"false","e_area":"{$e_area}","e_id":"{$data.e_id}","d_deployment_id":"{$data.d_deployment_id}"}]'>
            <option value='@'>{"未选择"|L}</option>
        </select>
    </div>
    <div class="block ">
        <label class="title">{"新的{$smarty.session.ident}-Server地址"|L}</label>
        <select id="e_mds_id" name="new_mds_id" new_mds_id="true" class=" long " size="10" action="?m=device&a=mds_option" selected="true"  data='[{ "to": "e_vcr_id","field": "d_deployment_id","view":"false","e_id":"{$data.e_id}","d_deployment_id":"{$data.d_deployment_id}" }]' digits ="true"></select>
        <span class="mds_error none">{"该设备可用用户数少于目前企业用户数，无法迁移，请选择其他设备"|L}</span>
    </div>
    <div class="block ">
        <label class="title">{"原{$smarty.session.ident}-RS"|L}:</label>
        <span>{$data.rs_name}</span>
        <input autocomplete="off"  value="{$data.e_vcr_id}" id="old_e_vcr_id" type="hidden" />
        <div class="data none" id="cur_e_vcr_id">{$data.e_vcr_id}</div>
        <input autocomplete="off"  value="{$data.e_rs_rec}" name="e_rs_rec" id="e_rs_rec" type="hidden" />
        <input autocomplete="off"  value="{$data.e_has_vcr}" name="e_has_vcr" id="e_has_vcr" type="hidden" />
    </div>
    <div class="block ">
        <label class="title">{"新的{$smarty.session.ident}-RS地址"|L}</label>
        <select id="e_vcr_id" name="new_vcr_id" new_vcr_id="true" class=" long " size="10" action="?m=device&a=rs_option"  digits ="true"></select>
        <span class="vcr_error none">{"该设备可用并发数少于目前企业并发数，无法迁移，请选择其他设备"|L}</span>
    </div>
    <div class="block ">
        <label class="title">{"原{$smarty.session.ident}-SS"|L}:</label>
        <span>{$data.rs_name}</span>
        <input autocomplete="off"  value="{$data.e_ss_id}" id="old_e_ss_id" type="hidden" />
        <div class="data none" id="cur_e_ss_id">{$data.e_ss_id}</div>
    </div>
    <div class="block ">
        <label class="title">{"新的{$smarty.session.ident}-SS地址"|L}</label>
        <select id="e_ss_id" name="new_ss_id" new_vcr_id="true" class=" long " size="10" action="?m=device&a=ss_option" selected="true" digits ="true"></select>
    </div>
    <div class="buttons mrtop40">
        <a id="move_device" class="button green">{"迁移设备"|L}</a>
        <a href="?m=enterprise&a=view&e_id={$data.e_id}" class="button">{"取消"|L}</a>
    </div>
</form>
<div id="dialog-confirm" class="hide" title="{'操作确认'|L}">
    <p>{"确定要迁移吗"|L}？</p>
</div>

<script>
    $("select#e_vcr_id").bind("change", function () {
        var e_vcr_id = $(this).children('option:selected').val();
        var cur_e_mds_users = parseInt($(".cur_e_mds_users").text());
        if(e_vcr_id > 0 && cur_e_mds_users == 0)
        {
            $("#e_vcr_id").val("");
            var length = $(this).length;
            for(i=0;i<length;i++)
            {
                if(i == 0)
                {
                    $('#e_vcr_id option:eq(i)').attr('selected','selected');
                }
                else
                {
                    $('#e_vcr_id option:eq(i)').attr('selected',false);
                }
            }
            
            notice("{"用户数不能为0"|L}");

        }
    });
       $("#move_device").click(function () {
        if ($("#form").valid()) {
       var flag = false;
            var old_mds_id = $("#cur_e_mds_id").html();
            var new_mds_id = $("#e_mds_id").val();
            var old_vcr_id = $("#old_e_vcr_id").val();
            var new_vcr_id = $("#e_vcr_id").val();
            var old_ss_id = $("#cur_e_ss_id").html();
            var new_ss_id = $("#e_ss_id").val();
            if(old_mds_id == new_mds_id && old_vcr_id == new_vcr_id && old_ss_id == new_ss_id)
            {
                flag = true;
                var tdata = eval('('+$("#e_area").attr('data')+')');
                var data = tdata[0];
                if($("#e_area").val() == data.e_area){
                    notice("{"没有迁移任何设备"|L}");
                }else{
                    $.ajax({
                        url:'?m=enterprise&a=set_ep_area',
                        dataType:"json",
                        data:{
                            e_area:$("#e_area").val(),
                            e_id:$("input[name=e_id]").val(),
                        },
                        success:function(res){
                            if(res.status==1){
                                notice(res.msg, '?m=enterprise&a=view&e_id={$data.e_id}');
                            }else{
                                notice(res.msg);
                            }
                        }
                    });

                }
            }
                var cur_e_mds_users = parseInt($(".cur_e_mds_users").text());
                var sel_e_mds_users = parseInt($("select[name=new_mds_id] option:selected").attr("d_user"));
                var cur_e_mds_call = parseInt($(".cur_e_mds_call").text());
                var sel_e_mds_call = parseInt($("select[name=new_mds_id] option:selected").attr("d_call"));
                var cur_e_mds_phone = parseInt($(".cur_e_mds_phone").text());
                var sel_e_mds_phone = parseInt($("select[name=new_mds_id] option:selected").attr("diff_phone"));
                var cur_e_mds_dispatch = parseInt($(".cur_e_mds_dispatch").text());
                var sel_e_mds_dispatch = parseInt($("select[name=new_mds_id] option:selected").attr("diff_dispatch"));
                var cur_e_mds_gvs = parseInt($(".cur_e_mds_gvs").text());
                var sel_e_mds_gvs = parseInt($("select[name=new_mds_id] option:selected").attr("diff_gvs"));
                if ($("#e_mds_id").val() != $("#cur_e_mds_id").text()) {
                if (cur_e_mds_users > sel_e_mds_users || cur_e_mds_phone>sel_e_mds_phone || cur_e_mds_dispatch>sel_e_mds_dispatch || cur_e_mds_gvs>sel_e_mds_gvs) {
                     notice("{"迁移到的{$smarty.session.ident}-Server可用用户数比当前企业用户数小，无法迁移，如果没有这么多用户，请尝试减少用户数"|L}");
                flag = true;
                }

                
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
                    url: "?modules=enterprise&action=move_device_item",
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