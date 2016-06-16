// 电话号码验证
jQuery.validator.addMethod("phone", function (value, element) {
    var tel = /^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/;
    return this.optional(element) || (tel.test(value));
}, "<%'电话号码格式错误'|L%>");


// 邮政编码验证
jQuery.validator.addMethod("zipCode", function (value, element) {
    var tel = /^[0-9]{6}$/;
    return this.optional(element) || (tel.test(value));
}, "<%'邮政编码格式错误'|L%>");


// QQ号码验证
jQuery.validator.addMethod("qq", function (value, element) {
    var tel = /^[1-9]\d{4,9}$/;
    return this.optional(element) || (tel.test(value));
}, "<%'qq号码格式错误'|L%>");


// IP地址验证
jQuery.validator.addMethod("ip", function (value, element) {
    var ip = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
    return this.optional(element) || (ip.test(value) && (RegExp.$1 < 256 && RegExp.$2 < 256 && RegExp.$3 < 256 && RegExp.$4 < 256));
}, "<%'Ip地址格式错误'|L%>");


// 字母和数字的验证
jQuery.validator.addMethod("chrnum", function (value, element) {
    var chrnum = /^([a-zA-Z0-9]+)$/;
    return this.optional(element) || (chrnum.test(value));
}, "<%'只能输入数字和字母'|L%>");

// 中文的验证
jQuery.validator.addMethod("chinese", function (value, element) {
    var chinese = /^([\u4e00-\u9fa5]|[a-zA-Z0-9\#\-\.\(\)\（\） \_\.])+$/;
    return this.optional(element) || (chinese.test(value));
}, "<%'名称中包含不可用字符'|L%>");
// 密码的验证
jQuery.validator.addMethod("passwd", function (value, element) {
    var chinese = /[\u4e00-\u9fa5]+/;
    return this.optional(element) || (!chinese.test(value));
}, "<%'密码格式不正确'|L%>");
// 中文的验证
jQuery.validator.addMethod("chinese1", function (value, element) {
    var chinese = /^([\u4e00-\u9fa5]|[a-zA-Z0-9\#\-\.\(\)\（\） \_\.])+$/;
    return this.optional(element) || (chinese.test(value));
}, "<%'名称中包含不可用字符'|L%>");

jQuery.validator.addMethod("ep_name", function (value, element) {
    var chinese = /^([\u4e00-\u9fa5]|[a-zA-Z0-9\#\&\-\.\(\)\（\） \_\.])+$/;
    return this.optional(element) || (chinese.test(value));
}, "<%'名称中包含不可用字符'|L%>");
// 下拉框验证
$.validator.addMethod("selectNone", function (value, element) {
    return value == "<%'请选择'|L%>";
}, "<%'必须选择一项'|L%>");


// 字节长度验证
jQuery.validator.addMethod("byteRangeLength", function (value, element, param) {
    var length = value.length;
    for (var i = 0; i < value.length; i++) {
        if (value.charCodeAt(i) > 127) {
            length++;
        }
    }
    return this.optional(element) || (length >= param[0] && length <= param[1]);
}, $.validator.format("<%'请确保输入的值在{0}-{1}个字节之间(一个中文字算2个字节)'|L%>"));

//日期时间验证
jQuery.validator.addMethod("datatime", function (value, element) {
    return this.optional(element) || /(\d{2}|\d{4})(?:\-)?([0]{1}\d{1}|[1]{1}[0-2]{1})(?:\-)?([0-2]{1}\d{1}|[3]{1}[0-1]{1})(?:\s)?([0-1]{1}\d{1}|[2]{1}[0-3]{1})(?::)?([0-5]{1}\d{1})(?::)?([0-5]{1}\d{1})/.test(value);
}, "<%'无效时间'|L%>");



// 字符验证
jQuery.validator.addMethod("stringCheck", function (value, element) {
    return this.optional(element) || /^[\u0391-\uFFE5\w]+$/.test(value);
}, "<%'只能包括中文字、英文字母、数字和下划线'|L%>");

// 中文字两个字节
jQuery.validator.addMethod("byteRangeLength", function (value, element, param) {
    var length = value.length;
    for (var i = 0; i < value.length; i++) {
        if (value.charCodeAt(i) > 127) {
            length++;
        }
    }
    return this.optional(element) || (length >= param[0] && length <= param[1]);
}, "<%'请确保输入的值在3-15个字节之间(一个中文字算2个字节)'|L%>");

// 身份证号码验证
jQuery.validator.addMethod("isIdCardNo", function (value, element) {
    return this.optional(element) || isIdCardNo(value);
}, "<%'请正确输入您的身份证号码'|L%>");

// 手机号码验证
jQuery.validator.addMethod("isMobile", function (value, element) {
    var length = value.length;
    var mobile = /^(((13[0-9]{1})|(15[0-9]{1}))+\d{8})$/;
    return this.optional(element) || (length == 11 && mobile.test(value));
}, "<%'请正确填写您的手机号码'|L%>");

// 手机号码验证
jQuery.validator.addMethod("mobile", function (value, element) {
    var length = value.length;
    //var mobile = /^(((13[0-9]{1})|(15[0-9]{1}))+\d{8})$/;
    var mobile = /^1\d{10}$/;
    return this.optional(element) || (length == 11 && mobile.test(value));
}, "<%'手机号码格式错误'|L%>");
// 手机号码验证 不超过11位
jQuery.validator.addMethod("mobile2", function (value, element) {
    var length = value.length;
    //var mobile = /^(((13[0-9]{1})|(15[0-9]{1}))+\d{8})$/;
    var mobile = /^\d{7,11}$/;
    return this.optional(element) || mobile.test(value);
}, "<%'手机号码格式错误'|L%>");
// 手机号码验证
jQuery.validator.addMethod("mobile1", function (value, element) {
    var length = value.length;
    //var mobile = /^(((13[0-9]{1})|(15[0-9]{1}))+\d{8})$/;
    var mobile = /^\s*\+?\s*(\(\s*\d+\s*\)|\d+)(\s*-?\s*(\(\s*\d+\s*\)|\s*\d+\s*))*\s*$/;
    return this.optional(element) ||  mobile.test(value);
}, "<%'手机号码格式错误'|L%>");

// 电话号码验证
jQuery.validator.addMethod("isTel", function (value, element) {
    var tel = /^\d{3,4}-?\d{7,9}$/; //电话号码格式010-12345678
    return this.optional(element) || (tel.test(value));
}, "<%'请正确填写您的电话号码'|L%>");

// 联系电话(手机/电话皆可)验证
jQuery.validator.addMethod("isPhone", function (value, element) {
    var length = value.length;
    var mobile = /^(((13[0-9]{1})|(15[0-9]{1}))+\d{8})$/;
    var tel = /^\d{3,4}-?\d{7,9}$/;
    return this.optional(element) || (tel.test(value) || mobile.test(value));

}, "<%'请正确填写您的联系电话'|L%>");

// 邮政编码验证
jQuery.validator.addMethod("isZipCode", function (value, element) {
    var tel = /^[0-9]{6}$/;
    return this.optional(element) || (tel.test(value));
}, "<%'请正确填写您的邮政编码'|L%>");
// 货币类型验证
jQuery.validator.addMethod("rmb", function (value, element) {
    var tel = /^(0|^[1-9]([0-9]*)(\.\d+)?)$/i;
    return this.optional(element) || (tel.test(value));
}, "<%'请正确填写价格'|L%>");
jQuery.validator.addMethod("units", function (value, element) {
    var tel = /^[A-Z]{3} |^[A-Z]{3}$/;
    return this.optional(element) || (tel.test(value));
}, "<%'请正确填写货币代码'|L%>");
jQuery.validator.addMethod("addr", function (value, element) {
    var tel = /^[0-9a-zA-Z\u4e00-\u9fa5 .]+$/;
    return this.optional(element) || (tel.test(value));
}, "<%'名称中包含不可用字符'|L%>");
jQuery.validator.addMethod("fox", function (value, element) {
//    var tel = /^(\d{3,4}-)?\d{7,8}$/;
    var tel = /^[\d ]+$/;
    return this.optional(element) || (tel.test(value));
}, "<%'传真号格式不正确'|L%>");
jQuery.validator.addMethod("units", function (value, element) {
    var tel = /^[A-Z]{3}$/;
    return this.optional(element) || (tel.test(value));
}, "<%'请正确填写货币代码'|L%>");
jQuery.validator.addMethod("paswd", function (value, element) {
    var length = value.length;
    var flag = true;
    /*var mob = /^[0-9]{19}}$/i ;
     var mob1 = /^[0-9]{20}$/i ;*/
if(!/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,14}$/.test(value)){
         flag = false;
    }
    return flag;
}, "<%'密码为6-14位的数字和字母的组合'|L%>");
jQuery.validator.addMethod("pwd", function (value, element) {
    var length = value.length;
    var flag = true;
    /*var mob = /^[0-9]{19}}$/i ;
     var mob1 = /^[0-9]{20}$/i ;*/
if(!/^((?![0-9]+$)|(?![a-zA-Z]+$))[0-9A-Za-z]{5,14}$/.test(value)){
         flag = false;
    }
    return flag;
}, "<%'密码为5-14位的数字或字母'|L%>");


//企业 代理商 企业用户 增/改 备注验证
jQuery.validator.addMethod("remark", function (value, element) {
    var remark_match = /^[\u4e00-\u9fa5\uFE30-\uFFA0a-zA-Z0-9\w\W]{0,100}$/;
    return this.optional(element) || remark_match.test(value);
}, "<%'备注字数不能超过100个'|L%>");
