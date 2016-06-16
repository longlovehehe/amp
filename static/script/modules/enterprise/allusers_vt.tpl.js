$(".waitsubmit").click(function () {
    $(this).addClass("submit");
    $("input[name=page]").val(0);
    send();
});