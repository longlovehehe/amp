<?php

/**
 * 企业导入导出控制器
 * @package 企业管理
 * @subpackage 控制器层
 * @require {@see contorl}
 */
class EnterpriseExportContorl extends contorl
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 导入导出设计
     */
    function export()
    {
        $enterprise = new enterprise($_REQUEST);
        $data = $enterprise->getByid();

        $mininav = array(
            array(
                "url" => "?m=enterprise&a=index",
                "name" => "企业管理",
                "next" => ">>"
            ),
            array(
                "url" => "?m=enterprise&a=view&e_id=" . $_REQUEST["e_id"],
                "name" => $data["e_name"] . " - ".L("导入导出"),
                "next" => ""
            )
        );
        $this->smarty->assign('mininav', $mininav);
        $this->smarty->assign('data', $_REQUEST);
        $this->smarty->assign('ep', $data);
        $this->render('modules/enterprise/export.tpl', L('导入导出'));
    }

   /**新加坡版
     * 用户表导出
     */
    function users_export_ag()
    {
        $header = array(
            L("序号 "),
             L("销售订单"),
            "MODEL/Item No.",
            "IMEI",
            "ICCID",
            "ICCID/ IMEI",
            L("终端序列号 "),
            L("手机号 "),
            "Mobile No./ Serial No.",
            L("用户ID "),
            " ",
             L("序号 "),
            L("用户ID "),
            L("用户密码 "),
            "Customer No.",
            "Subscription Item No./Plan No./VAS",
            "IMEI",
            "ICCID",
            "Mobile No./ Serial No.",
            L("终端序列号 "),
            "Actual Start",
            "Actual End",
//            "Priority(Add in)",
            "Valid Start Date",
            "Valid End Date",
            "Subscription Amount",
            " ",
            L("姓名 "),
            L("部门 "),
            L("流量卡所属 "),
            L("语音通话方式 "),
            L("创建时间 "),
            "Date of Purchase(commercial)",
            "Console",
            "Event Logging",
            L("视频业务 "),
            L("语音通话 "),
            L("图片拍传 "),
            L("GPS定位 "),
            L("地图对讲 "),
            L("短消息 ")
            /*L("号码"),
            L("姓名"),
            L("密码"),
            L("部门"),
            L("订购产品"),
            L("录音"),
            L("录像"),
            L("报警电话号码"),
            L("彩信接收号码"),
            L("自动登录开关"),
            L("UDID"),
            L("IMSI"),
            L("IMEI"),
            L("ICCID"),
            L("MAC"),
            L("开机启动"),
            L("机卡绑定"),
            L("流量卡所属"),
            L("程序检查更新"),
            L("信令加密"),
            L("语音通话方式"),
            L("GPS定位上报方式"),
            L("性别"),
            L("职位"),
            L("终端类型"),
            L("机型"),
            L("蓝牙标示号")*/
        );
        $sql = 'SELECT
                    u_number,
                    u_name,
                    u_passwd,
                    ug_name,
                    u_gprs_genus,
                    u_audio_mode,
                    u_mobile_phone,
                    u_imei,
                    u_iccid,
                    u_terminal_type,
                    u_terminal_number,
                    u_create_time,
                    u_purch_date,
                    u_sub_type,
                    u_zm,
                    u_p_function
FROM
	"T_User"
LEFT JOIN "T_Product" ON p_id = u_product_id
LEFT JOIN "T_UserGroup_:e_id" ON u_ug_id = ug_id
WHERE
	u_e_id = :e_id
        ORDER BY u_number';
        $sql = str_replace(':e_id', filter_input(INPUT_GET, 'e_id'), $sql);

        Cof::export_ag($header, 'T_User', $sql);
    }
    /**
     * 用户表导出
     */
    function users_export()
    {
        $header = array(
            L("号码"),
            L("姓名"),
            L("密码"),
            L("部门"),
            L("订购产品"),
            L("录音"),
            L("录像"),
            L("报警电话号码"),
            L("彩信接收号码"),
            L("自动登录开关"),
            L("UDID"),
            L("IMSI"),
            L("IMEI"),
            L("ICCID"),
            L("MAC"),
            L("开机启动"),
            L("机卡绑定"),
            L("流量卡所属"),
            L("程序检查更新"),
            L("信令加密"),
            L("语音通话方式"),
            L("GPS定位上报方式"),
            L("性别"),
            L("职位"),
            L("终端类型"),
            L("机型"),
            L("蓝牙标示号")
        );
        $sql = 'SELECT
	u_number,
	u_name,
	u_passwd,
	p_name,
	ug_name,
	u_audio_rec,
	u_video_rec,
	u_alarm_inform_svp_num,
	u_mms_default_rec_num,
	u_auto_config,
	u_bind_phone,
	u_gprs_genus,
	u_udid,
	u_imsi,
	u_imei,
	u_iccid,
	u_mac,
	u_auto_run,
	u_checkup_grade,
	u_encrypt,
	u_audio_mode,
	u_gis_mode,
	u_sex,
	u_position,
	u_terminal_type,
	u_terminal_model,
	u_zm
FROM
	"T_User"
LEFT JOIN "T_Product" ON p_id = u_product_id
LEFT JOIN "T_UserGroup_:e_id" ON u_ug_id = ug_id
WHERE
	u_e_id = :e_id';
        $sql = str_replace(':e_id', filter_input(INPUT_GET, 'e_id'), $sql);

        Cof::export($header, 'T_User', $sql);
    }

    /**
     * 群组成员导出
     */
    function groups_export()
    {
        $poi = '游标';
        $user = new users();

        $header = array(
            "对讲组名称",
            "号码",
            "成员级别",
            "是否默认组",
            "被叫挂断权限"
        );
        $sql = 'SELECT
	pg_name,
	pm_number,
	pm_level,
	pm_pgnumber,
	pm_hangup
                    
FROM
	"T_PttMember_:e_id"
LEFT JOIN "T_PttGroup_:e_id" ON pm_pgnumber = pg_number
WHERE
	pm_pgnumber != \'\'';
        $sql = str_replace(':e_id', filter_input(INPUT_GET, 'e_id'), $sql);

        $pdo = Cof::db();
        $excel = new PHPExcel();
        foreach ($header as $key => $value) {
            $col = PHPExcel_Cell::stringFromColumnIndex($key);
            $excel->getActiveSheet()->setCellValue($col . 1, $value);
        }

        if ($sql != "") {
            $stat = $pdo->query($sql);
            $result = $stat->fetchAll(PDO::FETCH_ASSOC);
            $n = 2;
            if (count($result) == 0) {
                $sql1 = 'SELECT pg_name FROM "T_PttGroup_:e_id"';
                $sql1 = str_replace(':e_id', filter_input(INPUT_GET, 'e_id'), $sql1);
                $stat1 = $pdo->query($sql1);
                $result1 = $stat1->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result1 as $k => $v) {
                    $i = 0;
                    if ($poi != $v['pg_name']) {
                        $poi = $v['pg_name'];
                        $excel->getActiveSheet()->setCellValueExplicit('A' . $n, $v['pg_name'], PHPExcel_Cell_DataType::TYPE_STRING);
                    }
                    $n++;
                }
            } else {
                foreach ($result as $key => $value) {
                    $i = 0;
                    // 对讲组名称
                    if ($poi != $value['pg_name']) {
                        $poi = $value['pg_name'];
                        $excel->getActiveSheet()->setCellValueExplicit('A' . $n, $value['pg_name'], PHPExcel_Cell_DataType::TYPE_STRING);
                    }
                    // 号码
                    $excel->getActiveSheet()->setCellValueExplicit('B' . $n, $value['pm_number'], PHPExcel_Cell_DataType::TYPE_STRING);
                    //成员级别
                    $excel->getActiveSheet()->setCellValueExplicit('C' . $n, $value['pm_level'], PHPExcel_Cell_DataType::TYPE_STRING);
                    //被叫挂断权限
                    $excel->getActiveSheet()->setCellValueExplicit('E' . $n, $value['pm_hangup'], PHPExcel_Cell_DataType::TYPE_STRING);
                    //是否默认组
                    $data = array("u_number" => $value['pm_number']);
                    $user->set($data);
                    $data = $user->getById();
                    if ($data['u_default_pg'] == $value['pm_pgnumber']) {
                        $default = '1';
                    } else {
                        $default = '0';
                    }
                    $excel->getActiveSheet()->setCellValueExplicit('D' . $n, $default, PHPExcel_Cell_DataType::TYPE_STRING);

                    $n++;
                }
            }
            $output = new PHPExcel_Writer_Excel5($excel);
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control:must-revalidate, post-check = 0, pre-check = 0");
            header("Content-Type:application/force-download");
            header("Content-Type:application/vnd.ms-execl");
            header("Content-Type:application/octet-stream");
            header("Content-Type:application/download");
            header('Content-Disposition:attachment;filename="' . 'groups' . '.xls"');
            header("Content-Transfer-Encoding:binary");
            $output->save('php://output');
        }
    }
    function ug_export()
    {
        $data = array();
        $data['e_id'] = filter_input(INPUT_GET, 'e_id');
        $ug = new usergroup($data);
        $ug_list = $ug->selectlist();
        //var_dump($ug_list);

        $excel = new PHPExcel();
        /** 设置表头 */
        $excel->getActiveSheet()->setCellValue('A1', '一级部门');
        $excel->getActiveSheet()->setCellValue('B1', '二级部门');
        $excel->getActiveSheet()->setCellValue('C1', '三级部门');
        $excel->getActiveSheet()->setCellValue('D1', '四级部门');
        $excel->getActiveSheet()->setCellValue('E1', '五级部门');
        $excel->getActiveSheet()->setCellValue('F1', '六级部门');

        $n = 2;
        foreach ($ug_list as $ug_item)
        {
            $x = substr_count($ug_item['ug_path'], "||");
            $col = PHPExcel_Cell::stringFromColumnIndex(($x));
            $excel->getActiveSheet()->setCellValueExplicit($col . $n, $ug_item['ug_name'], PHPExcel_Cell_DataType::TYPE_STRING);
            $n++;
        }

        /* 导出 */
        coms::head('excel', $excel);
    }

}
