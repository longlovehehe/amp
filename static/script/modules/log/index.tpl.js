
$("input[name=el_id]").on("input",function(){
	var flag = false;
	var mob = /^\d+$/;
    if (mob.test($("input[name=el_id]").val())||$("input[name=el_id]").val()=="") {
        flag = true;
    }
    if(flag==false){
    	notice("<%'日志ID只能为数字'|L%>");
    	$("input[name=el_id]").val("");
    }
});

/*
jQuery.validator.addMethod("el_id", function (value, element) {
    var flag = false;
    var mob = /^\d+$/;
    if (mob.test(value)||value=="") {
        flag = true;
    }
    if(flag==false){
    	notice("<%'日志ID只能为数字'|L%>");
    }
});
*/