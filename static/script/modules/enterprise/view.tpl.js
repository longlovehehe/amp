var request = eval($("span.request").text());
var request = request[0];
var e_id = request.e_id;
$("div.autoactive[action=view]").addClass("active");
$("#stop_status").click(function () {
        notice("<%'操作进行中'|L%>");
        $.ajax({
                url: "?modules=enterprise&action=stop&e_id=" + e_id,
                dataType: "json",
                success: function (result) {
                        notice(result.msg, "?m=enterprise&a=view&e_id=" + e_id);
                }
        });
});
$("#start_status").click(function () {
        notice("<%'操作进行中'|L%>");
        $.ajax({
                url: "?modules=enterprise&action=start&e_id=" + e_id,
                dataType: "json",
                success: function (result) {
                        notice(result.msg, "?m=enterprise&a=view&e_id=" + e_id);
                }
        });
});
$("#initdb").click(function () {
        $("#dialog-confirm-warn").dialog({
                resizable: false,
                width: 440,
                height: 240,
                modal: true,
                buttons: {
                        "重建": function () {
                                notice("<%'正在重建中，请稍候'|L%>");
                                $(this).dialog("close");
                                $.ajax({
                                        url: "?modules=enterprise&action=initdb&e_id=" + e_id,
                                        dataType: "json",
                                        success: function (result) {
                                                notice(result.msg);
                                        }
                                });
                        },
                        "取消": function () {
                                $(this).dialog("close");
                        }
                }
        });
});

//企业迁移操作按钮点击事件
$("#move_enterprise").click(function(){
    $("span[name=notice]").remove();
    var e_id = $("#e_id").val();
    //获取符合迁移条件的代理
    $.ajax({
        url: "?modules=enterprise&action=move_enterprise",
        dataType: "json",
        data: {e_id:e_id},
        success: function (data) {
            if(data.omp==true){
                //判断是否获取到符合条件的代理
                var html = '<input type="hidden" id="e_id" value="'+e_id+'" />';
                html+='<input type="radio" onclick="removeNotice()" name="e_agents_id" value="0" ag_name="OMP" />OMP&nbsp;&nbsp;&nbsp;<br /><br /><p style="width:360px;height:2px;background:green;"></p><br />';
                html+='<div style="height:130px;overflow-x:hidden;overflow:auto;">';
                for(var i=0; i<data.list.length; i++){
                    html+='<div>';
                    html+='<input type="radio" onclick="removeNotice()" name="e_agents_id" value="'+data.list[i].ag_number+'" ag_name="'+data.list[i].ag_name+'" />'+data.list[i].ag_name+'&nbsp;&nbsp;&nbsp;';
                    html+='</div>';
                }
                html+="</div>";
                $("#dialog-move").html(html); 
            }else{
                //判断是否获取到符合条件的代理
                if(data.list.length!=0){
                    var html = '<input type="hidden" id="e_id" value="'+e_id+'" />';
                    html+='<div style="height:130px;overflow-x:hidden;overflow:auto;">';
                    for(var i=0; i<data.list.length; i++){
                        html+='<div>';
                        html+='<input type="radio" onclick="removeNotice()" name="e_agents_id" value="'+data.list[i].ag_number+'" ag_name="'+data.list[i].ag_name+'" />'+data.list[i].ag_name+'&nbsp;&nbsp;&nbsp;';
                        html+='</div>';
                    }
                    html+="</div>";
                    $("#dialog-move").html(html); 
                }else if(data.omp==false){
                    notice("<%'未获取到符合迁移条件的代理'|L%>");
                    exit();
                }else{
                    /*notice("<%'未获取到符合迁移条件的代理'|L%>");
                    exit();*/
                    var html = '<input type="hidden" id="e_id" value="'+e_id+'" /><input type="radio" onclick="removeNotice()" name="e_agents_id" value="0" ag_name="OMP" />OMP&nbsp;&nbsp;&nbsp;<br /><br /><p style="width:370px;height:2px;background:green;"></p><br />';
                    $("#dialog-move").html(html);
                }
            }
        }
    });

    //显示符合迁移条件的代理弹出框
    $("#dialog-move").dialog({
        resizable: false,
        width: 400,
        height: 250,
        modal: true,
        buttons: {
        "<%'确认'|L%>": function () {
            //获取选中的代理商的id 
            var  e_agents_id= $("input[name=e_agents_id]:radio:checked").val();
            if(!e_agents_id){
                if($("span[name=notice]").length<1){
                    var notice = "<%'请选择要迁移到的代理'|L%>";
                    $("<span name='notice' style='color:red;'>*"+notice+"</span>").insertBefore(".ui-dialog-buttonset");
                }
                exit();
            }
            var ag_name = $("input[name=e_agents_id]:radio:checked").attr("ag_name");
            //点击确认弹出确认迁移的弹出层
            $(this).dialog("close");
            $("#notice").html("<%'迁移目标为'|L%>"+"：【"+ag_name+"】");
            $("#dialog-notice").dialog({
                resizable: false,
                width: 400,
                height: 180,
                modal: true,
                buttons: {
                //进行迁移操作
                "<%'迁移'|L%>": function () {
                    //点击确认迁移进行的操作
                    $(this).dialog("close");
                    $.ajax({
                        url: "?modules=enterprise&action=change_enterprise",
                        data: {e_id:e_id,e_agents_id:e_agents_id},
                        success: function (result) {
                            if(result=='yes'){
                               layer.msg("<%'迁移成功'|L%> ",{
                                    icon: 1,
                                    time: 2000, //2秒关闭（如果不配置，默认是3秒）
                                },function(){
                                    location.replace("?m=enterprise&a=index");
                                }); 
                            }else{
                                notice("<%'迁移失败请检查后重试'|L%>");
                                exit();
                            }
                        }
                    });
                },
                    "<%'取消'|L%>": function () {
                        $(this).dialog("close");
                    }
                }
            });
        },
            "<%'取消'|L%>": function () {
                $(this).dialog("close");
            }
        }
    });
})

//分配代理时的提示信息清除
function removeNotice(){
    $("span[name=notice]").remove();
}