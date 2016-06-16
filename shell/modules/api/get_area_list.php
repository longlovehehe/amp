<?php

require_once "../private/init.php";
require_once '../shell/class/api.class.php';

$mask = $mask . "get_area_list";
$api = new api();
$result = $api->get_area_list();
$smarty->assign("list", $result);
$smarty->display('api/get_area_list.tpl', md5($mask));

