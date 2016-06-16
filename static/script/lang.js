$(function ($) {
        /*
        (function () {
                var quick = function () {
                        var u_number = $("div.quicktoolbar input").val();
                        var data = $("div.quicktoolbar a.search").attr('data');
                        window.location.href = data + u_number;
                };
                $("div.quicktoolbar input").keydown(function (e) {
                        var key = e.keyCode;
                        if (key === 13) {
                                quick();
                        }
                });
                $("div.quicktoolbar a.search").bind('click', quick);
        })();
        */
        $("a.lang").bind('click', function () {
                var lang = $(this).attr('data');
                $.cookie('lang', lang, {expires: 999999});
                window.location.reload();
        });
        (function () {
                var nav = $("body").attr("class");
                if (nav != "") {
                        $('nav a.' + nav).addClass('active');
                }
        })();
});