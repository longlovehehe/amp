
$("input[required]").focus(function () {
    $("#form").valid();
});

$("a.toggle").click(function () {
    var owner = $(this);
    var toggle = $("." + owner.attr('data'));
    if (owner.text() == "<%'收缩'|L%>↑") {
        owner.text("<%'展开'|L%>↓");
        toggle.addClass('none');
    } else {
        owner.text("<%'收缩'|L%>↑");
        toggle.removeClass('none');
    }
});

$('div.content').delegate('select.only_show', 'change', function () {
    $(this).val(1);
});
/**
*调度台号码选择其他号码
*/
$("select[name=u_alarm_inform_svp_num]").change(function(){
       $("input[name=u_alarm_inform_svp_num]").val($(this).val());
        if($("select[name=u_alarm_inform_svp_num] option:selected").val()=="@"){
               $("input[name=u_alarm_inform_svp_num]").removeClass("none");
               $("input[name=u_alarm_inform_svp_num]").val("");
            }else{
                $("input[name=u_alarm_inform_svp_num]").addClass("none");
            }
});
