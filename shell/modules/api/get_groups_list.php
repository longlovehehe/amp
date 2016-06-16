<?php
$tools =new tools();
$smarty = new smartyex();
require_once '../shell/class/api.class.php';

$mask = $mask . "get_groups_list";
$api = new api();
$result = $api->get_groups_list($_REQUEST["e_id"]);

$smarty->assign("list", $result);
$smarty->display('api/get_groups_list.tpl', md5($mask));