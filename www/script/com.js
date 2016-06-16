"use strict";
window.com = window.com || {};
window.com.fn = window.com.fn || {};
window.com.form = window.com.form || {};

/**
 *全选按钮取消选中
 */

window.com.form.autocheck = function ()
{
    $("body").delegate('input.cb[type=checkbox]', 'click', function ()
    {
        $("input#checkall").removeAttr("checked");
    });
};
/**
 * 展示型select
 * @returns {undefined}
 */
window.com.form.onlyshow = function () {
    $("select.only_show").on("change", function () {
        $(this).val(1);
    });
};


/**
 * 表单内容输入提示
 */
window.com.tips = function () {
    $(".ctips").tooltip({
        content: function () {
            return $(this).attr('title');
        }
        , track: true
        , show: true
        , hide: false
    });
};
window.com.form.autocheck();
window.com.form.onlyshow();
window.com.tips();