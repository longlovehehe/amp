$("select[name=d_network_type]").click(function () {
    var d_network_type = $("select[name=d_network_type]").val();
    if (d_network_type == 0) {
        $("div.Upnp").addClass("none");
    } else if (d_network_type == 1) {
        $("div.Upnp").removeClass("none");
    }
});
