var request = eval($("span.request").text());
var request = request[0];
var e_id = request.e_id;
$("div.autoactive[action=users]").addClass("active");
$("a#batch_toggle").click(function () {
    $("form.move_user").hide();
    $("form.product").hide();
    $("form.move_u_default_pg").hide();
    $("form.batch").toggle();
});
$("a#batch_product").click(function () {
    $("form.move_user").hide();
    $("form.move_u_default_pg").hide();
    $("form.batch").hide();
    $("form.product").toggle();
});
$("a#move_user").click(function () {
    $("form.batch").hide();
    $("form.product").hide();
    $("form.move_u_default_pg").hide();
    $("form.move_user").toggle();
});
$("a#move_u_default_pg").click(function () {
    $("form.move_user").hide();
    $("form.product").hide();
    $("form.batch").hide();
    $("form.move_u_default_pg").toggle();
});
$("input[name=u_alarm_inform_svp_num]").bind("change", function () {
    var length=$(this).val().length;
    var u_number = $(this).val();
    $.ajax({
        url:'?modules=enterprise&action=check_number',
        data:{e_id:e_id,u_number:u_number},
        success:function(res){
            if(res=="1"&&length==11||u_number==""){
                $("#u_alarm_inform_svp_num-error").attr('class','error none');
            }
            else{
                $("#u_alarm_inform_svp_num-error").attr('class','error');
            }
        }
    });
});

$("input.allcheckbox").on("change",function(){
    var check="noselected";
    $("input.allcheckbox").each(function(){
        if($(this).is(":checked")){
            $(this).val();
            check+=$(this).val()+",";
        }
    });
    $("input[name=u_p_function_new]").val(check);
});
$("select[name=u_alarm_inform_svp_num]").bind("change", function () {
    $("#u_alarm_inform_svp_num-error").attr('class','error none');
});
/**
 $("input[name=move_u_default]").on('click', function () {
 var own = $(this);
 $('select[name=move_u_default_pg]>option').remove();
 if (own.is(":checked")) {
 $('select[name=move_u_default_pg]').addClass('autofix').attr('action', '?m=enterprise&a=groups_option&safe=true&e_id=' + e_id);
 } else {
 $('select[name=move_u_default_pg]').addClass('autofix').attr('action', '?m=enterprise&a=groups_option&e_id=' + e_id);
 }
 initFix();
 });
 */
