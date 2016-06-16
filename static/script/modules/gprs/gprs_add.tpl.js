/* The file is auto create */
/**
 *
 */
var len = 1;
$("input.add_button").click(function () {
    len++;
    $("#gprs_in").append($("div.block.add_gprs").eq(0).clone().addClass("number_" + len));

    $("div.block.add_gprs").each(function () {
        $("input.add_button").removeClass("add_button").addClass("del_button").val("删除").attr("onclick", "del_gprs(" + len + "); ").attr("id", "gprs" + len);
    });
    $("input.del_button").eq(0).removeClass("del_button").attr("onclick", "").attr("id", "").addClass("add_button").val("增加");
});

function del_gprs(num) {
    $("#gprs" + num).parent(".number_" + num).remove();
}

function getintime() {
    var now = new Date();
    var monthn = parseInt(now.getMonth()) + 1;
    var yearn = now.getFullYear();
    var daten = now.getDate();
    var dtime = yearn + "-" + monthn + "-" + daten;
    $("input.intime").val(dtime);
}
