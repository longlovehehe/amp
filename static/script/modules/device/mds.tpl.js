$("input[name=d_sip_port]").on("input",function(){
    var flag = false;
    var mob = /^\d+$/;
    if (mob.test($("input[name=d_sip_port]").val())||$("input[name=d_sip_port]").val()=="") {
        flag = true;
    }
    if(flag==false){
        notice("<%'设备PORT只能为数字'|L%>");
        $("input[name=d_sip_port]").val("");
    }
});

$("#refreshall").click(function () {
    var checkd = "";
    var url = $(this).attr("data");
    $("input.cb:checkbox:checked").each(function () {
        checkd += $(this).val() + ",";
    });
    if (checkd === "") {
        notice("<%'未选中任何项'|L%>");
    } else {
        $.ajax({
            url: url,
            dataType: "JSON",
            data: $("form.data").serialize(),
            success: function (result) {
                notice(result.msg);
                setTimeout(function () {
                    send();
                }, 888);
            }
        });
    }
});
$("#delall").click(function () {
    var checkd = "";
    $("input.cb:checkbox:checked").each(function () {
        checkd += $(this).val() + ",";
    });
    if (checkd === "") {
        notice("<%'未选中任何项'|L%>");
    } else {
        $("#dialog-confirm").dialog({
            resizable: false,
            height: 180,
            modal: true,
            buttons: {
                "<%'删除'|L%>": function () {
                    $(this).dialog("close");
                    notice("<%'正在删除'|L%>");
                    $.ajax({
                        url: "?modules=device&action=mds_del",
                        data: $("form.data").serialize(),
                        success: function (result) {
                            notice("<%'成功删除'|L%> " + result + " <%'台设备'|L%>");
                            setTimeout(function () {
                                send("prev");
                            }, 888);
                        }
                    });
                },
                "<%'取消'|L%>": function () {
                    $(this).dialog("close");
                }
            }
        });
    }
});
function new_creat(d_id, d_areas) {
    $(".c_dir").html("<%'新增区域'|L%>");
    /*$("input[name='d_id']").val(d_id);*/
    $.ajax({
        type: 'get',
        cache: false,
        url: '?modules=device&action=get_device&d_id=' + d_id,
        dataType: 'json',
        success: function (result) {

            console.log(result);
            $("input[name='d_name']").val(result.d_name);
            $("#d_ip1").val(result.d_ip1);
            $("input[name='d_port1']").val(result.d_port1);
            $("input[name='d_ip2']").val(result.d_ip2);
            $("input[name='d_port2']").val(result.d_port2);
            $("input.d_id").val(result.d_id);
            var d_area = result.d_area;
            d_area = d_area.replace('[\"', "");
            d_area = d_area.replace(/\",\"/g, ",");
            d_area = d_area.replace('\"]', "");
            $("input[name='d_area1']").val(d_area);
            $.ajax({
                url: '?modules=device&action=get_area&d_id=' + d_id,
                data: "d_area=" + d_area,
                dataType: "html",
                success: function (result) {
                    $("span.d_area").html(result);
                    $.ajax({
                        url: '?modules=device&action=get_diff_area&d_id=' + d_id,
                        data: "d_area=" + d_area,
                        dataType: "json",
                        success: function (res1) {
                            var str = "<option value='#'><%'全部'|L%></option>";
                            for (var i in res1) {
                                $.ajax({
                                    url: '?modules=device&action=get_area_name',
                                    data: "am_id=" + res1[i],
                                    success: function (res) {
                                        str += "<option value=" + res1[i] + ">" + res + "</option>";
                                    }
                                });
                            }
                            $("select.moreselect").html(str);
                        }
                    });
                }
            });
        }
    });
    document.getElementById('light').style.display = 'block';
    document.getElementById('fade').style.display = 'block';
}
function closed() {
    document.getElementById('light').style.display = 'none';
    document.getElementById('fade').style.display = 'none';
}
function do_set() {
    var d_area = new Array();
    var morearea = $("select.moreselect").val();
    var area = $("input[name='d_area1']").val();
    area = area.split(",");
    console.log(morearea);
    if (morearea == "") {
        d_area = area;
    } else if (area == "") {
        d_area = morearea;
    } else {
        d_area = area.concat(morearea);
    }
    $.ajax({
        url: "?modules=device&action=add_d_area",
        method: "GET",
        dataType: 'json',
        data: {d_id: $("input.d_id").val(), d_area: d_area, d_area_diff: morearea},
        success: function (result) {

            /*$("div.autoactive[action=groups]").addClass("active");*/
            notice(result.msg, "?m=device&a=index");
            /*location.reload();*/
            document.getElementById('light').style.display = 'none';
            document.getElementById('fade').style.display = 'none';
        }
    });
}
/**
 * Comment
 */
function title_notice() {
    notice("<%'该设备区域为【全部】不能添加'|L%>");
}