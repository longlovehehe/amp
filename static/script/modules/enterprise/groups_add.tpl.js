/*
 var request = eval($("span.request").text());
 var request = request[0];
 var e_id = request.e_id;
 $("#save").click(function () {
 $.ajax({
 url: '?modules=enterprise&action=get0groups&e_id=' + e_id,
 type: "post",
 datatype: "json",
 success: function (result) {
 var res = eval("(" + result + ")");
 if (result.status == -1) {
 notice(result.msg);
 }
 }
 });
 });
 */