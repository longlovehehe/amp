
/**
 *
 */
function L(str) {
    if (typeof (window.lang[str]) !== 'undefined') {
        str = window.lang[str];
    }
    return str;
}
jQuery.validator.addMethod("u_mobile_phone", function (value, element) {
    var length = value.length;
    var flag = false;
    //var mob = /^\d{7,11}$/;
    var mob = /^\d+$/;
    if (mob.test(value) || length == 0) {
        flag = true;
    }
    return flag;
}, "<%'手机号码格式错误'|L%>");

jQuery.validator.addMethod("p_desc", function (value, element) {
    var length = value.length;
    var flag = true;
    if (length >= 1000) {
        flag = false;
    }
    return flag;
}, "<%'已超出最大长度限制'|L%>");
jQuery.validator.addMethod("u_udid", function (value, element) {
    var length = value.length;
    var flag = false;
    var mob = /^\s*$|^(?!(?:\d+|[a-zA-Z]+)$)[\da-zA-Z]{40}$/i;
    if ((length == 0 && mob.test(value)) || (length == 40 && mob.test(value))) {
        flag = true;
    }
    return flag;
}, "<%'必须为40位的字母和数字'|L%>");
jQuery.validator.addMethod("u_imsi", function (value, element) {
    var length = value.length;
    var flag = false;
    var mob = /^\s*$|^[0-9]{15}$/i;
    if ((length == 0 && mob.test(value)) || (length == 15 && mob.test(value))) {
        flag = true;
    }
    return flag;
}, "<%'必须为15位数字'|L%>");
jQuery.validator.addMethod("u_imei", function (value, element) {
    var length = value.length;
    var flag = false;
    var mob = /^\s*$|^[0-9a-zA-Z]{15}$/i;
    if ((length == 0 && mob.test(value)) || (length == 15 && mob.test(value))) {
        flag = true;
    }
    return flag;
}, "<%'必须为15位数字或字母'|L%>");

jQuery.validator.addMethod("u_meid", function (value, element) {
    var length = value.length;
    var flag = false;
    var mob = /^\s*$|^[0-9a-zA-Z]{14}$/i;
    if ((length == 0 && mob.test(value)) || (length == 14 && mob.test(value))) {
        flag = true;
    }
    return flag;
}, "<%'必须为14位数字或字母'|L%>");

jQuery.validator.addMethod("u_iccid", function (value, element) {
    var length = value.length;
    var flag = false;

    var mob = /^\s*$|^[0-9a-zA-Z]{19}$|^[0-9a-zA-Z]{20}$/i;
    if (mob.test(value)) {
        flag = true;
    }
    return flag;
}, "<%'长度为19或20位的数字或字母'|L%>");

jQuery.validator.addMethod("u_iccid1", function (value, element) {
    var length = value.length;
    var flag = false;
    var mob = /^\S*$|^[0-9a-zA-Z]{19}$|^[0-9a-zA-Z]{20}$/i;
    if (mob.test(value)) {
        flag = true;
    }
    return flag;
}, "<%'长度为19或20位的数字或字母'|L%>");
jQuery.validator.addMethod("u_mac", function (value, element) {
    var length = value.length;
    var flag = false;
    var mob = /^\s*$|[A-F\d]{2}[A-F\d]{2}[A-F\d]{2}[A-F\d]{2}[A-F\d]{2}[A-F\d]{2}/i;
    if (length == 12 && mob.test(value) || length == 0) {
        flag = true;
    }
    return flag;
}, "<%'需按照002F163D2B60的形式，只包含数字和字母'|L%>");
function getnum() {
    var checkd = [];
    $("input.cb:checkbox:checked").each(function () {
        checkd.push($(this).val());
    });

    $("#num").html(checkd.length);
}