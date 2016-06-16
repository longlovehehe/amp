<?php

/**
 * 计算部门人总数
 */
function modCountUserGroupsTotal($ug_id) {
	$data = array();
	$data['u_ug_id'] = $ug_id;
	$user = new users($data);
	$total = $user->getTotal(TRUE);
	return $total;
}

function getEListBypid($pid) {
	$product_contorl = new ProductContorl();
	return $product_contorl->getEListBypid($pid);
}
function getEListBypjson($productjson) {
	$product_contorl = new ProductContorl();
	return $product_contorl->getEListBypjson($productjson);
}
function getEListByfunc($productjson) {
	$product_contorl = new ProductContorl();
	return $product_contorl->getEListByfunc($productjson);
}
/**
 * 获得增值功能
 */
function get_func_str($arr){
    $product = new product();
    return $product->getByProductName($arr);
    
}

/**
 * 返回格式化处于过的时间字符串，格式：年-月-日 小时：分钟：秒
 * @package OMP_Common_Function_is
 * @param String $str
 * @return String 格式化时间
 */
function isDate($str) {
	return date('Y-m-d H:i:s', strtotime($str));
}

/**
 * 通过开始/结束时间，获取判断这两个时间区间的SQL
 * @package OMP_Common_Function
 * @param type $start
 * @param type $end
 * @return type
 */
function getDateRange($start, $end) {
	$start = isDate($start);
	$end = isDate($end);
	$where = <<<SQL
                BETWEEN to_timestamp('{$start}', 'yyyy-mm-dd HH24:MI:SS') AND to_timestamp('{$end}', 'yyyy-mm-dd HH24:MI:SS')
SQL;
	return $where;
}
/**
 * 通过开始/结束时间，获取判断这两个时间区间的SQL
 * @package OMP_Common_Function
 * @param type $start
 * @param type $end
 * @return type
 */
function getDateRange_string($start, $end) {
	$start = isDate($start);
	$end = isDate($end);
	$where = <<<SQL
                BETWEEN'{$start}' AND '{$end}'
SQL;
	return $where;
}

/**
 * 判断参数1是否为手机格式。判断规则：1开始的字符串
 * @package OMP_Common_Function_is
 * @param type $phone
 * @return type
 */
function isPhone($phone) {
	$isPhone = "/^1/";
	return preg_match($isPhone, $phone);
}

/**
 * 多语言切换
 * @package OMP_Common_Function_mod
 * @return string
 * @deprecated 停用
 * @todo 多语言功能设计
 */
function modlang() {
	return "1";
}

/**
 * 录音模式修正器
 * @package OMP_Common_Function_mod
 * @param type $code 不录音|对讲频道全程录音|根据话权方的录音标志录音
 * @return string
 */
function modpg_record_mode($code) {
	switch ($code) {
		case 2:
			return '不录音';
		case 0:
			return '对讲频道全程录音';
		case 1:
			return '根据话权方的录音标志录音';
	}
}

/**
 * 头像修正器，显示头像，或显示“无头像”
 * @package OMP_Common_Function_mod
 * @param type $img
 * @return string
 */
function modface($img) {
	if ($img == "") {
		return L("无头像");
	} else {
		return "<img class=\"face\" src=\"?m=enterprise&a=users_face_item&pid=$img\" />";
	}
}

/**
 * 性别修正器，F显示女。M显示男
 * @package OMP_Common_Function_mod
 * @param type $status
 * @return string
 */
function modsex($status) {
	if ($status == 'F') {
		return L("女");
	} else if ($status == 'M') {
		return L("男");
	}
}

/**
 * 用户类别修正器
 * @package OMP_Common_Function_mod
 * @param type $str
 * @return string 手机用户|调度台用户|GVS用户|未知
 */
function modtype($str) {
	switch ($str) {
		case "1":
			return L("手机用户");
		case "2":
			return L("调度台用户");
		case "3":
			return L("GVS用户");
		case "4":
			return L("流量卡用户");
		default:
			returnL("未知");
	}
}

/**
 * 企业状态修正器
 * @package OMP_Common_Function_mod
 * @param type $status
 * @return string 不启用|启用|发布处理中|发布失败
 */
function modifierStatus($status) {
	switch ($status) {
		case 0:
			return L("不启用");
		case 1:
			return L("启用");
		case 2:
			return L("发布处理中");
		case 3:
			return L("发布失败");
        case 4:
            return L("发布失败");
        case 5:
			return L("企业创建中");
        case 6:
			return L("企业删除中");
		case 7:
			return L("企业迁移中");
		case 8:
			return L("企业迁移失败");
		case 9:
			return L("企业创建失败");
	}
}

/**
 * 设备状态修正器
 * @package OMP_Common_Function_mod
 * @param type $str
 * @return string 处理中|正常|异常
 */
function modDeviceStatus($str) {
	switch ($str) {
		case 0:
			return L("处理中");
		case 1:
			return L("正常");
		case 2:
			return L("异常");
                case 3:
			return L("异常");
	}
}

/**
 * 存储修正器
 * @package OMP_Common_Function_mod
 * @param type $falg
 * @return string 同步|存储|无存储功能
 */
function modifierStorage($falg) {
	switch ($falg) {
		case 1:
			return L("同步");
		case 2:
			return L("存储");
		default:
			return L("无存储功能");
	}
}

/**
 * 安全登录修正器
 * @package OMP_Common_Function_mod
 * @param type $safelogin
 * @return string 不需要|需要安全登录
 */
function modifierSafeLogin($safelogin) {
	if ($safelogin != 1) {
		return L("不需要");
	} else {
		return L("需要安全登录");
	}
}

/**
 * 通过给定的路径，创建多层文件夹
 * @package OMP_Common_Function
 * @param type $dirName
 * @param type $rights
 */
function mkdir_r($dirName, $rights = 0777) {
	$dirs = explode('/', $dirName);
	$dir = '';
	foreach ($dirs as $part) {
		$dir .= $part . '/';
		if (!is_dir($dir) && strlen($dir) > 0) {
			mkdir($dir, $rights);
		}
	}
}

/**
 * 根据模式名称，返回对应的类别id
 * @package OMP_Common_Function_mod
 * @param type $action
 * @return int
 */
function modActionNameLog($action) {
	switch ($action) {
		case 'enterprise':
			return 1;
		case 'device':
			return 2;
		case 'manager':
			return 3;
		case 'area':
			return 4;
		case 'product':
			return 5;
		case 'logout':
		case 'login_check':
		case 'login':
			return 7;
		case 'announcement':
			return 8;
		case 'agents':
			return 9;
		default:
			return 6;
	}
}

/**
 * 返回JSON格式的MDS详细信息
 * @package OMP_Common_Function_mod
 * @param type $str
 * @return type
 */
function modmdsid($str) {
	$data['d_id'] = $str;
	$device = new device($data);
	$data['e_id'] = $_REQUEST['e_id'];
	$ep = new enterprise($data);
	$epdata = $ep->getByid();
	$data = $device->GetJsonByMDSId();

	$data['diff_user'] += $epdata['e_mds_users'];
	$data['diff_call'] += $epdata['e_mds_call'];
	$data['diff_phone'] += $epdata['e_mds_phone'];
	$data['diff_dispatch'] += $epdata['e_mds_dispatch'];
	$data['diff_gvs'] += $epdata['e_mds_gvs'];
	$data['name'] = $data['d_name'] . '【' . $data['d_ip2'] . '】' . L('可用用户数') . '：' . $data['diff_user'] . '|' . L('可用手机用户数') . '：' . $data['diff_phone'] . '|' . L('可用调度台用户数') . '：' . $data['diff_dispatch'] . '|' . L('可用GVS用户数') . '：' . $data['diff_gvs'];
	return '[' . json_encode($data) . ']';
}

/**
 * 返回JSON格式的RS详细信息
 * @package 
 * @param type $str
 * @return type
 */
function modvcrid($str) {
	$data['device_id'] = $str;
	$device = new device($data);
	$data['e_id'] = $_REQUEST['e_id'];
	$ep = new enterprise($data);
	$epdata = $ep->getByid();
	$data = $device->getRec();

	$data['d_recnum'] += $epdata['d_recnum'];
	$data['sum_recnum'] += $epdata['sum_recnum'];
	// $data['d_ip1'] += $epdata['d_ip1'];
	$data['name'] = $data['d_name'] . '【' . $data['d_ip2'] . '】' . L('已用并发数') . '：' . $data['sum_recnum'] . '|' . L('总并发数') . '：' . $data['d_recnum'] ;
	return '[' . json_encode($data) . ']';
}
/**
 * 返回JSON格式的SS详细信息
 * @package 
 * @param type $str
 * @return type
 */
function modssid($str) {
	$data['device_id'] = $str;
	$device = new device($data);
	$data['e_id'] = $_REQUEST['e_id'];
	$ep = new enterprise($data);
	$epdata = $ep->getByid();
	$data = $device->getSpace();

	$data['d_space'] += $epdata['d_space'];
	$data['space'] += $epdata['space'];
	// $data['d_ip1'] += $epdata['d_ip1'];
	$data['name'] = $data['d_name'] . '【' . $data['d_ip2'] . '】' . L('已用空间') . '：' . $data['space'] . '|' . L('总空间') . '：' . $data['d_space'] ;
	return '[' . json_encode($data) . ']';
}

/**
 * 修正多级部门前缀符
 * @package OMP_Common_Function
 * @param type $str
 * @return type
 */
function modugpath($str) {
	$str = preg_replace("/[0-9]/", '', $str);
	$str = str_replace("||", '—', $str);
	return "|" . $str;
}

/**
 * 如果用户数小于0，或者完全超出许可数，则返回0。否则返回可用用户数
 * @package OMP_Common_Function
 * @param type $num
 * @return int
 */
function modusercall($num) {
	if ($num < 0) {
		return 0;
	} else {
		return $num;
	}
}

/**
 * 多语言修正器
 * @package OMP_Common_Function
 * @global type $res
 * @param type $node
 * @param type $flag
 * @return string
 */
function L($node, $flag = TRUE) {
	$res = coms::$res;
	$result = '';
	if ($res[$node] == '') {
		return $node;
	} else {
		return $res[$node];
	}
	if ($flag) {
		return $result;
	}
	echo $result;
}

/**
 * 默认语言修正器
 * @package OMP_Common_Function
 * @global type $diff_res
 * @param type $node
 * @param type $flag
 * @return string
 */
function DL($node, $flag = TRUE) {
	$diff_res = coms::$diff_res;
	$result = '';
	if ($diff_res[$node] == '') {
		return $node;
	} else {
		return $diff_res[$node];
	}
	if ($flag) {
		return $result;
	}
	echo $result;
}

/**
 * 产生一个大于1的指定最大值的随机数
 * @package OMP_Common_Function
 * @param type $max
 * @return type
 */
function modrand($max) {
	return rand(1, $max);
}

/**
 * 管理员则返回内容，非管理员则不返回
 * @package OMP_Common_Function
 * @param type $str
 * @return string
 */
function isadmin($str) {
	if ($_SESSION['own']['om_id'] == 'admin') {
		return $str;
	}
	return "";
}

/**
 * @package OMP_Common_Function
 * @param type $str
 * @return string
 */
function isallarea($str) {
	if ($_SESSION['own']['om_area'] == '["#"]') {
		return $str;
	}
	return "";
}

/**
 * @package OMP_Common_Function
 * @param type $str
 * @return string
 */
function notadmin($str) {
	if ($_SESSION['own']['om_id'] != 'admin') {
		return $str;
	}
	return "";
}

/**
 * @package OMP_Common_Function_mod
 * @param type $level
 * @return string
 */
function level($level) {
	switch ($level) {
		case 'admin':
			return L("超级管理员");
		default:
			return L("普通管理员");
	}
}

/**
 * @package OMP_Common_Function
 * @param type $falg
 * @return string
 */
function logLevel($falg) {
	switch ($falg) {
		case 1:
			if ($_COOKIE['lang'] == "cn_ZH") {
				return "<span class='warn log'><em></em><a href='?m=log&a=index&el_level=1'>警告</a></span>";
			} else {
				return "<span class='warn log'><em></em><a href='?m=log&a=index&el_level=1'>warn</a></span>";
			}
		case 2:

			if ($_COOKIE['lang'] == "cn_ZH") {
				return "<span class='error log'><em></em><a href='?m=log&a=index&el_level=2'>错误</a></span>";
			} else {
				return "<span class='error log'><em></em><a href='?m=log&a=index&el_level=2'>error</a></span>";
			}
		case 0;

			if ($_COOKIE['lang'] == "cn_ZH") {
				return "<span class='info log'><em></em><a href='?m=log&a=index&el_level=0'>信息</a></span>";
			} else {
				return "<span class='info log'><em></em><a href='?m=log&a=index&el_level=0'>info</a></span>";
			}
	}
}

/**
 * @package OMP_Common_Function_mod
 * @param type $falg
 * @return string
 */
function logType($falg) {
	switch ($falg) {
		case 1:
			return L("企业模块");
		case 2:
			return L("设备模块");
		case 3:
			return L("角色模块");
		case 4:
			return L("区域模块");
		case 5:
			return L("产品模块");
		case 6:
			return L("日志模块");
		case 7:
			return L("登录模块");
		case 8:
			return L("公告模块");
		case 9:
			return L("代理商模块");
		case 0:
			return L("未指定模块");
		default:
			return L("未定义模块");
	}
}

/**
 * @package OMP_Common_Function_mod
 * @param type $falg
 * @return string
 */
function an_status($falg) {
	switch ($falg) {
		case 1:
			return L("已发布");
		case 0:
			return L("草稿");
	}
}

/**
 * @package OMP_Common_Function
 * @param type $json
 * @param type $option
 * @return string
 */
function mod_area_name($json, $option = 'text') {
	require_once '../shell/class/dao/area.class.php';
	$data['am_id'] = $json;
	$area = new area($data);
	$result = json_decode($area->getbyjson());
	
	if ($option == 'option') {
		if (implode('', $result) == '全部') {
			return L('全部');
		}
		if ($_COOKIE['lang'] == "en_US") {
			$result_html = '<select class="only_show" style="width:80px;"><option value="1">View</option><option>' . implode('</option><option>', $result) . '</option></select>';
		} else {
			$result_html = '<select class="only_show" style="width:80px;"><option value="1">点击查看</option><option>' . implode('</option><option>', $result) . '</option></select>';
		}
	} else {
		$result_html = implode(' ', $result);
	}
	if($result_html=="全部"){
		$result_html=L("全部");
	}
	return $result_html;
}


/**
 * @package OMP_Common_Function
 * @param type $m
 * @return type
 */
function scriptmodule($m) {
	return <<<EOC
<script src="?m=loader&a=s&do=$m"></script>
EOC;
}

/**
 * @package OMP_Common_Function
 * @param type $src
 * @return type
 */
function scriptafter($src) {
	return <<<EOC
<script src="?m=loader&a=s&do=after&p={$src}"></script>
EOC;
}

/**
 * @package OMP_Common_Function
 * @param type $src
 * @return type
 */
function script($src) {
	return <<<EOC
<script src="?m=loader&a=s&p={$src}"></script>
EOC;
}

/**
 * @package OMP_Common_Function
 * @param type $src
 * @return type
 */
function scriptnocompile($src) {
	return <<<EOC
<script src="?m=loader&nocompile=true&a=s&p={$src}"></script>
EOC;
}

/**
 * @package OMP_Common_Function
 * @param type $src
 * @return type
 */
function style($src) {
	return <<<EOC
<link href="?m=loader&a=c&p={$src}" rel="stylesheet" type="text/css" />
EOC;
}

/**
 * 获取字符串长度，字符串，长度，如果超过长度显示的...
 */
function mbsubstr($str, $length = 10, $view = '...') {

	$s = mb_substr($str, 0, $length);
	if (mb_strlen($str) > $length) {
		$s .= $view;
	}
	return $s;
}

/**
 * 截取翻译
 */
function trsanlang($str) {
	$arr_str = explode("|", $str);
	foreach ($arr_str as $key => $value) {
		$arr_str1 = explode(",", $value);

		$str1 .= $arr_str1[0] . "," . L($arr_str1[1]) . "|";

	}

	$str1 = trim($str1, "|");
	return $str1;
}

function start_session($expire=0){
    if($expire==0){
        $expire=ini_get('session.gc_maxlifetime');
    }else{
        ini_set('session.gc_maxlifetime',$expire);
    }
    if(empty($_COOKIE['PHPSESSID'])){
        session_set_cookie_params($expire);
        session_start();
    }else{
        session_start();
        setcookie('PHPSESSID',session_id(),time()+$expire);
    }
}

/**
 * 用户状态
 * @param type $str
 * @return string
 */
function modwordstatus ( $str )
{
    switch ( $str )
    {
        case "1":
            return "<img src='images/pic_07.png' / > ";
        case "2":
            return "<img src='images/pic_08.png' / > ";
        case "3":
            return "<img src='images/pic_09.png' / > ";
        default :
            return "未知";
    }
}

/**
 * 时间预警
 * @param type $str
 * @return string
 */
function modwordprewarning ( $str )
{
    switch ( $str )
    {
        case "1":
            return "<img src='images/pic_10.png' / > ";
        case "2":
            return "<img src='images/pic_11.png' / > ";
        case "3":
            return "<img src='images/pic_12.png' / > ";
        default :
            return "未知";
    }
}
/**
 * 时间预警
 * @param type $str
 * @return string
 */
function modwordpreTraffic ( $str )
{
    switch ( $str )
    {
        case "1":
            return "<img src='images/pic_13.png' / > ";
        case "2":
            return "<img src='images/pic_14.png' / > ";
        case "3":
            return "<img src='images/pic_15.png' / > ";
        default :
            return "未知";
    }
}

/* 获取代理商名称 */

function getagname ( $id )
{
    if ( $id == 0 )
    {
        return "运营管理平台";
    }
    else
    {
        $data = array ();
    $data['ag_number'] = $id;
    $ag = new agents ( $data );
    $agdata = $ag->getByid ();
    return $agdata['ag_name'];
    }
}

function getaglevel ( $level )
{
    switch ( $level )
    {
        case 0:
            return "Level 1";
            break;
         case 1:
            return "Level 2";
            break;
        case 2:
            return "Level 3";
            break;
        case 3:
            return "<img src='images/start.gif' />";
            return "<img src='images/start.gif' />";
            return "<img src='images/start.gif' />";
            break;
        case 4:
            return "<img src='images/start.gif' />";
            return "<img src='images/start.gif' />";
            return "<img src='images/start.gif' />";
            return "<img src='images/start.gif' />";
            break;

        default:
            break;
    }
}

/**
 * 用户类型
 * @param type $str
 * @return string
 */
function modwordtype ( $str )
{
    switch ( $str )
    {
        case "1":
            return "<img src='images/pic_02.png' / > ";
        case "2":
            return "<img src='images/pic_03.png' / > ";
        case "3":
            return "<img src='images/pic_04.png' / > ";
        case "4":
            return "<img src='images/pic_01.png' / > ";
        default :
            return L("未知");
    }
}
/**
 * 001 002 003 自增长
 * @param type $num
 * @param type $step
 * @return type
 */
//function autoInc($num,$step=1){
//        $arr=str_split($num);
//        $count=count($arr);
//        for($i=0,$zero_count=0,$num_new='',$flag=0;$i<$count;$i++){
//            if($arr[$i]=='0' and $flag==0){
//                $zero_count++;
//            }
//            elseif(is_numeric($arr[$i])){
//                $flag=1;
//                $num_new.=$arr[$i];
//            }
//            else{
//                exit('错误:含有非数字字符');
//            }
//        }
//        $num_new=intval($num_new)+$step;
//        if($num_new>pow(10,$count-1)){
//            return $num_new;
//        }
//        else{
//            return str_pad('',$count-count(str_split($num_new)),'0').($num_new);
//        }
//    }
function autoInc($num,$step=1){
        $count=count(str_split($num));
        $num_new=intval($num)+$step;
        if($num_new>pow(10,$count-1)){
            return $num_new;
        }
        else{
            return str_pad($num_new,$count,'0',STR_PAD_LEFT);
        }
    }
    
        /**
     *二维数组取出重复
     */
function array_unique_fb($array2D){
    foreach ($array2D as $k=>$v){
        $v = join(",",$v);  //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
		$temp[$k] = $v;
    }
	$temp = array_unique($temp);    //去掉重复的字符串,也就是重复的一维数组
    foreach ($temp as $k => $v){
        $array=explode(",",$v);		//再将拆开的数组重新组装
		$temp2[$k]["id"] =$array[0];   
		$temp2[$k]["name"] =$array[1];
	}
    return $temp2;
}
/**
 * 获得运营管理创建者名称
 */
function getompman($type){
    switch ($type) {
        case '0':
            return L('运营管理员');
            break;
        
        default:
            $ag = new agents(array("ag_number"=>$type));
            $info=$ag->getByid();
            if($info!=false){
                return $info['ag_name']."(".$type.")";
            }else{
                return $type;
            }
            
            break;
    }
}
/**
 * @param $imei 用户所填IMEI
 * @param $u_e_id 用户所属代理商企业ID
 * @return bool|string TRUE->该终端可用于绑定|FALSE->终端不存在库中 不能绑定|Binding->终端已被绑定|Not Belong->不属于该用户所属代理商 不能绑定
 * @throws Exception
 */
function check_md_imei($imei,$u_e_id){
        $term=new terminal(array("md_imei"=>$imei));
        $ep=new enterprise(array("e_id"=>$u_e_id));
        $res=$term->getselect_list();
        $res_ep=$ep->getByid();
        if($imei!=""){
                if($res){//是否在库中?
                    if($res['md_parent_ag']=="0"){
                        $condition=$res_ep['e_ag_path']==="|".$res['md_parent_ag']."|";
                    }else{
                        $condition=strpos($res_ep['e_ag_path'],"|".$res['md_parent_ag']."|")!==false || $res_ep['e_ag_path']==="|".$res['md_parent_ag']."|";
                    }
                    if($condition){//是否属于该用户所属代理商或OMP
                            if($res['md_binding']===0){//是否绑定? 0 未绑定
                                    $info['res']=TRUE;
                                    $info['md_type']=$res['md_type'];
                                    return $info;
                            }else{
                                    return "Binding";//已经绑定
                            }
                        }else{
                            return "Not Belong";//不属于该用户所属代理商
                        }
                }else{
                        return "Not in the library";//不存在库中 即 in the user
                }
        }else{
                return "isnull";
        }
}

/**
 * @param $meid 用户所填MEID
 * @param $u_e_id 用户所属代理商企业ID
 * @return bool|string TRUE->该终端可用于绑定|FALSE->终端不存在库中 不能绑定|Binding->终端已被绑定|Not Belong->不属于该用户所属代理商 不能绑定
 * @throws Exception
 */
function check_md_meid($meid,$u_e_id){
    $term=new terminal(array("md_meid"=>$meid));
    $ep=new enterprise(array("e_id"=>$u_e_id));
    $res=$term->checkexcel_meid($meid);
    $res_ep=$ep->getByid();
    if($meid!=""){
        if($res){//是否在库中?
            if($res['md_parent_ag']=="0"){
                $condition=$res_ep['e_ag_path']==="|".$res['md_parent_ag']."|";
            }else{
                $condition=strpos($res_ep['e_ag_path'],"|".$res['md_parent_ag']."|")!==false || $res_ep['e_ag_path']==="|".$res['md_parent_ag']."|";
            }
            if($condition){//是否属于该用户所属代理商或OMP
            	if($res['md_binding']===0){//是否绑定? 0 未绑定
                    $info['res']=TRUE;
                    $info['md_type']=$res['md_type'];
                    return $info;
                }else{
                    return "Binding";//已经绑定
                }
            }else{
                return "Not Belong";//不属于该用户所属代理商
            }
        }else{
            return "Not in the library";//不存在库中 即 in the user
        }
    }else{
        return "isnull";
    }
}

/*
* 用户分类
*/
function attrType($str) {
	switch ($str) {
		case 0:
			return L("商用");
		case 1:
			return L("测试");
	}
}