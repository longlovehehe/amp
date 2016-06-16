$("input[name=dbtype]").bind("click", function() {
    var dbtype = $(this).val();
    if (dbtype == 'remote') {
        $(".dbaddr").show();
        $("input[name=dbhost]").attr("disabled", false);
    }
    if (dbtype == 'localhost')
    {
        $(".dbaddr").hide();
        $("input[name=dbhost]").attr("disabled", true);
    }
});
function info(str) {
    var h1 = $("<h1 class='animated zoomIn'></h1>");
    h1.html(str);
    $("section[data=3]").html("");
    $("section[data=3]").append(h1);
}
$("#page1").bind("click", function() {
    $("section.page").hide();
    $("section[data=2]").show();
});

$("#page2").bind("click", function() {
    if ($("form").valid()) {
        $("section.page").hide();
        $("section[data=3]").show();

        var ifr = $("<iframe></iframe>");
        ifr.attr("name", "hiddenframe");
        ifr.addClass("hiddenframe");
        $("section[data=3]").append(ifr);
        $("form").submit();
    }
});
