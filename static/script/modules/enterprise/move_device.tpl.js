(function () {
        $("#e_area").bind('change', function () {
            var tdata = eval($(this).attr('data'));
            var data = tdata[0];
            var to = $("#" + data.to);
            if($(this).val() == data.e_area)
            {
                var url = to.attr("action") + "&" + data.field + "=" + $(this).val() + "&e_id=" + data.e_id;
            }
            else
            {
                var url = to.attr("action") + "&" + data.field + "=" + $(this).val();
            }
            var owner = to;
            var val = $(this).val();
            $.ajax({
                url: url,
                success: function (result) {
                    if (data.view == "true") {
                        owner.html("<option value=''><%'全部'|L%></option>" + result);
                    } else {
                        owner.html(result);
                        if(val != data.e_area)
                        {
                            $("#e_vcr_id").html('');
                            $("#e_ss_id").html('');
                        }
                        else
                        {
                            if($("#e_area").val() == data.e_area)
                            {
                                var url1 = $("select#e_vcr_id").attr("action") + "&d_deployment_id=" + data.d_deployment_id + "&e_id=" + data.e_id;
                            }
                            else
                            {
                                var url1 = $("select#e_vcr_id").attr("action");
                            }
                            $.ajax({
                                url: url1,
                                success: function (result) {
                                    $("select#e_vcr_id").html(result);
                                }
                            });

                            if($("#e_area").val() == data.e_area)
                            {
                                var url2 = $("select#e_ss_id").attr("action") + "&d_deployment_id=" + data.d_deployment_id + "&e_id=" + data.e_id;
                            }
                            else
                            {
                                var url2 = $("select#e_ss_id").attr("action");
                            }
                            
                            $.ajax({
                                url: url2,
                                success: function (result) {
                                    $("select#e_ss_id").html(result);
                                }
                            });
                        }
                        
                    }
                }
            });
        });

        var url = $("select#e_mds_id").attr("action");
        var tdata = eval($("#e_area").attr('data'));
        var data = tdata[0];
        url += "&d_area=" + data.e_area + "&e_id=" + data.e_id ;
        $.ajax({
            url: url,
            success: function (result) {
                $("select#e_mds_id").html(result);
            }
        });

        var url1 = $("select#e_vcr_id").attr("action");
        url1 += "&e_id=" + data.e_id + "&d_deployment_id=" + data.d_deployment_id;
        $.ajax({
            url: url1,
            success: function (result) {
                $("select#e_vcr_id").html(result);
            }
        });

        var url2 = $("select#e_ss_id").attr("action");
        url2 += "&e_id=" + data.e_id + "&d_deployment_id=" + data.d_deployment_id;
        $.ajax({
            url: url2,
            success: function (result) {
                $("select#e_ss_id").html(result);
            }
        });


    })();

    var phone_num = $("div.block span.cur_e_mds_phone").text();
    var dispatch_num = $("div.block span.cur_e_mds_dispatch").text();
    var gvs_num = $("div.block span.cur_e_mds_gvs").text();
    var sub = true;
    $("select#e_mds_id").bind("change", function () {
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

        /*联动*/
        var d_deployment_id = $(this).children('option:selected').attr("d_deployment_id");
        var tdata = eval($(this).attr('data'));
        var data = tdata[0];
        var str = $("#e_vcr_id").attr("action").split("&");
        var to = str[0] + "&" + str[1];
        if(d_deployment_id == data.d_deployment_id)
        {
            var url = to + "&" + data.field + "=" + d_deployment_id + "&e_id=" + data.e_id;
        }
        else
        {
            var url = to + "&" + data.field + "=" + d_deployment_id;
        }
        /*获取同一部署ID下的rs设备*/
        var owner = to;       
        $.ajax({
            url: url,
            success: function (result) {
                if (data.view == "true") {
                    $("#e_vcr_id").html("<option value=''><%'全部'|L%></option>" + result);
                } else {
                    $("#e_vcr_id").html(result);
                }
            }
        });
        /*获取同一部署ID下的ss设备*/
        var to1 = $("#e_ss_id");
        if(d_deployment_id == data.d_deployment_id)
        {
            var url1 = to1.attr("action") + "&" + data.field + "=" + d_deployment_id + "&e_id=" + data.e_id;
        }
        else
        {
            var url1 = to1.attr("action") + "&" + data.field + "=" + d_deployment_id;
        }

        var owner1 = to1;       
        $.ajax({
            url: url1,
            success: function (result) {
                if (data.view == "true") {
                    owner1.html("<option value=''><%'全部'|L%></option>" + result);
                } else {
                    owner1.html(result);
                }
            }
        });
        /*联动结束*/
        $("#form").valid();
    });
    
    $("select#e_vcr_id").bind("change", function () {
        var e_vcr_id = $(this).children('option:selected').val();
        if(e_vcr_id != '')
        {
            sum = Number(phone_num)*2 +Number(dispatch_num) + Number(gvs_num);
            $("#e_rs_rec").val(sum);
            $("#e_has_vcr").val('1');
            var select_e_vcr_id = $("#cur_e_vcr_id").html();
            var d_have = $(this).children('option:selected').attr("d_have");

            if (e_vcr_id != select_e_vcr_id) {
                if (d_have - sum < 0) {
                    $("div.block span.vcr_error").removeClass("none");
                    sub = false;
                } else {
                    $("div.block span.vcr_error").addClass("none");
                    sub = true;
                }
            }else{
                    $("div.block span.vcr_error").addClass("none");
                    sub = true;
            }
        }
        else
        {
            $("div.block span.vcr_error").addClass("none");
            sub = true;
        }
        
        $("#form").valid();
    });


 