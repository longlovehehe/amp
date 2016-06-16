jQuery.validator.addMethod("pi_code", function (value, element) {
    var flag = false;
    var mob = /^gn_[A-Za-z0-9_]+$/;
    if (mob.test(value)) {
        flag = true;
    }
    return flag;
}, "<%'功能编号以gn_开头,只能为字母、数字和 _'|L%>");
jQuery.validator.addMethod("pi_status", function (value, element) {
    var flag = false;
    var mob = /(^(\d[,][\u4E00-\u9FA5\w]+))/;
    var arr = value.split("|");
    for (var i = 0; i < arr.length; i++) {
        if (!mob.test(arr[i])) {
            flag = false;
        } else {
            flag = true;
        }
    }
    return flag;
}, "<%'功能状态使用格式【值,描述】，每一项之间分隔使用'|L%>|");
