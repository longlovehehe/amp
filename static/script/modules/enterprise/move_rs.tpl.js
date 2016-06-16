$("select#e_vcr_id").bind("change", function () {
	var e_mds_phone = $(".cur_e_mds_phone").html();
    var e_mds_dispatch = $(".cur_e_mds_dispatch").html();
    var e_mds_gvs = $(".cur_e_mds_gvs").html();
    sum1 = Number(e_mds_phone)*2 +Number(e_mds_dispatch) + Number(e_mds_gvs);
    if (isNaN(sum1)) {
        $(".cur_e_rs_rec").html('N/A');
        // $("input[name=e_rs_rec]").val('N/A');
    } else {
        $(".cur_e_rs_rec").html(sum1);
        // $("input[name=e_rs_rec]").val(sum1);
    }
});
