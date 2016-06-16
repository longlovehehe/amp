<?php

/*
 * 缓存头设置及时区
 */
error_reporting(E_ERROR);
ini_set('display_errors', '1');
date_default_timezone_set('PRC');
ini_set('date.timezone', 'Asia/Shanghai');

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, must-revalidate");
header("Pragma: no-cache");
extract($_COOKIE);
/*
 * 必要文件加载
 */

require_once '../shell/class/com.php';
require_once '../shell/class/Cof.cs.php';
require_once '../shell/class/function.class.php';

require_once '../shell/class/smartyex.class.php';

require_once "../private/libs/PHPExcel/PHPExcel.php";
require_once '../shell/class/tools.class.php';
/*
 * SPL autoload
 * 实体类自动加载
 *
 */

$tools = new tools();

$config = $tools->getconfig();
$lang = $tools->getlangconfig();
$webroot = $config['system']['webroot'];
$language = $lang['language']['lang'];
$_COOKIE['default_lang'] = $language;
$_COOKIE['lang'] == null ? $_COOKIE['lang'] = $language : $_COOKIE['lang'] = $_COOKIE['lang'];
session_start();
$_SESSION['ident'] = $lang['language']['ident'];
coms::lang();
coms::dlang();
$directory = array(
	$webroot
	, $webroot . DIRECTORY_SEPARATOR . 'shell'
	, $webroot . DIRECTORY_SEPARATOR . 'shell' . DIRECTORY_SEPARATOR . 'class'
	, $webroot . DIRECTORY_SEPARATOR . 'shell' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'dao'
	, $webroot . DIRECTORY_SEPARATOR . 'shell' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'contorl',
);
$include_path = implode(PATH_SEPARATOR, $directory);
set_include_path(get_include_path() . PATH_SEPARATOR . $include_path);

/**
 * 类自动加载器
 * @package OMP_Common_Function
 * @param type $classname
 */
function _autoload($classname) {
	require_once $classname . '.class.php';
	//include_once (strtolower($classname) . '.class.php' );
}

spl_autoload_register('_autoload');

/*
 * 程序初始化
 */

$smarty = new smartyex();

$modules = $tools->get('modules') != '' ? $tools->get('modules') : $tools->get('m');
$action = $tools->get('action') != '' ? $tools->get('action') : $tools->get('a');
$do = $tools->get('do') != '' ? $tools->get('do') : $tools->get('do');
$_REQUEST['d'] = $do;

$dispatcher = new DispatcherContorl();

if (method_exists($dispatcher, $modules)) {
	$dispatcher->$modules();
} else {
	$dispatcher->common();
	if ($_SERVER['QUERY_STRING'] == '') {
		$smarty->assign('title', '集群通 - 登录');
		$smarty->display('modules/system/login.tpl');
		exit();
	}
	$tools->notfound(L('不正确的控制器名称'));
}