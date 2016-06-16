$(function ($) {
    $(".autoselect").bind('change', function () {
        var tdata = eval($(this).attr('data'));
        var data = tdata[0];
        var to = $("#" + data.to);
        var url = to.attr("action") + "&" + data.field + "=" + $(this).val();

        var owner = to;
        $.ajax({
            url: url,
            success: function (result) {
                if (data.view == "true") {
                    owner.html("<option value=''><%'全部'|L%></option>" + result);
                } else {
                    owner.html(result);
                }
            }
        });
    });
});
