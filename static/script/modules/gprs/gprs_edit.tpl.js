function getintime() {
    var now = new Date();
    var monthn = parseInt(now.getMonth()) + 1;
    var yearn = now.getFullYear();
    var daten = now.getDate();
    var dtime = yearn + "-" + monthn + "-" + daten;
    $("input.intime").val(dtime);
}