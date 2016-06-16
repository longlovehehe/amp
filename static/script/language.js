/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(".language").bind("click", function () {
    var lang = $(this).attr("name");
    $.cookie('lang', lang);
});

