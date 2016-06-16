<?php

require_once "../private/init.php";
require_once '../shell/class/api.class.php';

$mask = $mask . "get_vcr_list";
$api = new api();
$result = $api->get_vcr_list();
$smarty->assign("list", $result);
$smarty->display('api/get_vcr_list.tpl', md5($mask));