
(function () {
        valid();
        var submitpost = function () {

                if ($("#form").valid()) {
                        var form = $("a.ajaxpost").attr("form");
                        var goto = $("a.ajaxpost1").attr("goto");
                        var url = $("#form").attr("action");
                        var i = $(".ke-edit-iframe").contents().find(".ke-content");
                        $(".ke-edit-iframe").contents().find(".ke-content");
                         $("#content").val( i[0].innerHTML);
                       
                        var data = $("#form").serialize() + "&an_status=0";
                        $.ajax({
                                url: url,
                                method: "POST",
                                dataType: "json",
                                data: data,
                                success: function (result) {

                                        if (result.status == 0) {
                                                notice(result.msg, goto);
                                        } else {
                                                notice(result.msg);
                                        }
                                }
                        });
                }
        };
        $("a.ajaxpost1").bind("click", submitpost);
})();

(function () {
        valid();
        var submitpost = function () {
                if ($("#form").valid()) {
                        var goto = $("a.ajaxpost2").attr("goto");
                        var url = $("#form").attr("action");
                        var i = $(".ke-edit-iframe").contents().find(".ke-content");
                        $(".ke-edit-iframe").contents().find(".ke-content");
                         $("#content").val( i[0].innerHTML);
                        var data = $("#form").serialize() + "&an_status=1";
                        $.ajax({
                                url: url,
                                method: "POST",
                                dataType: "json",
                                data: data,
                                success: function (result) {
                                        if (result.status == 0) {
                                                notice(result.msg, goto);
                                        } else {
                                                notice(result.msg);
                                        }
                                }
                        });
                }
        };
        $("a.ajaxpost2").bind("click", submitpost);
})();