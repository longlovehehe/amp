<?php

require_once "../private/init.php";
require_once '../shell/class/api.class.php';

$api = new api();
$result = $api->get_mds_list_item();
$smarty->assign("list", $result);
$smarty->display('api/get_mds_list_item.tpl');
