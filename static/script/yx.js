$(function ($) {
    /*
     * 通用脚本加载器
     */
    "use strict";
    var yx = function yx() {
    };

    /*
     *
     * @param {type} factory
     * @returns {undefined}
     *  factory
     *  msg
     *  title
     *  type tips/loading/reload
     */
    yx.msg = function (factory) {
        var showtime = 71 * 1000;
        var msg = "";
        var title = "标题";
        var type = "tips";
        if (typeof (factory.msg) != "undefined") {
            msg = factory.msg;
        }
        if (typeof (factory.title) != "undefined") {
            title = factory.title;
        }
        if (typeof (factory.type) != "undefined") {
            type = factory.type;
        }
        var id = "notice_" + new Date().getTime();

        var _mask = $("<div class='mask'></div>");
        var _tips = $("<div class='tips animated'></div>");
        var _title = $("<h2 class='title'></div>");
        var _msg = $("<div class='msg'></div>");
        var _toolbar = $("<div class='toolbar'><a class='button close'><%'关闭'|L%></a></div>");

        _msg.html(msg);
        _title.html(title);

        _tips.append(_title);
        _tips.append(_msg);
        _tips.append(_toolbar);
        _mask.attr("id", id);
        _mask.append(_tips);
        $("body").append(_mask);
        $("#" + id + " a.close").bind("click", function () {
            $("#" + id).addClass("zoomOut")
            setTimeout(function () {
                $("#" + id).remove();
            }, 1000);
        });
        switch (type) {
            case 'tips':
                setTimeout(function () {
                    $("#" + id).addClass("zoomOut");
                    setTimeout(function () {
                        $("#" + id).remove();
                    }, 1000);
                }, showtime);
                break;
            case 'load':
                $("#" + id).addClass("load");
                break;
            case 'reload':
                break;
        }
    };
});