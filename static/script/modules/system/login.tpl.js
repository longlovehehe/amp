$("a.submit").click(function () {
    $("#login1").submit();
});
(function () {
    $("input.autosend").keydown(function (e) {
        var key = e.keyCode;
        if (key === 13) {
            $("#login1").submit();
        }
    });
})();
$("a.lang").bind('click', function () {
    var lang = $(this).attr('data');
    $.cookie('lang', lang, {expires: 999999});
    window.location.reload();
});
