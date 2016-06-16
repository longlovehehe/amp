<?php

$tools =new tools();
$smarty = new smartyex();
$action = $tools->get('action') != '' ? $tools->get('action') : $tools->get('a');
$do = $tools->get('do') != '' ? $tools->get('do') : $tools->get('a');

require_once '../shell/class/api.class.php';


/*
 * API接口
 */

$api = new api($_REQUEST);
$mask = "api";
switch ($action) {
        case "shelluser":
                $result = $api->shelluser();
                $smarty->assign("list", $result);
                $smarty->display('api/shelluser.tpl');
                break;

        case "superconsole":
                $api->superconsole();
                break;
        case "get_area_list":
                require_once ('get_area_list.php');
                break;

        case "get_mds_list":
                require_once ('get_mds_list.php');
                break;
        
        case "get_mds_list_item":
                require_once ('get_mds_list_item.php');
                break;

        case "get_vcr_list":
                require_once ('get_vcr_list.php');
                break;

        case "get_groups_list":
                require_once ('get_groups_list.php');
                break;

        case "fileupload":
                require_once ('fileupload.php');
                break;

        case "get_enterprise_list":
                $mask = $mask . "get_enterprise_list";
                $result = $api->get_enterprise_list();
                $smarty->assign("list", $result);
                $smarty->display('api/get_option_list.tpl', md5($mask));
                break;

        case "get_ptt_member_list":
                $mask = $mask . "get_ptt_member";
                $result = $api->get_ptt_member_list($_REQUEST["e_id"]);
                $smarty->assign("list", $result);
                $smarty->display('api/get_option_list.tpl', md5($mask));
                break;

        case "get_product_list":
                $mask = $mask . "get_product";
                $result = $api->get_product_list();
                $smarty->assign("list", $result);
                $smarty->display('api/get_option_list.tpl', md5($mask));
                break;

        case "export":
                ini_set("memory_limit", -1);
                set_time_limit(6000);
               
                $template = $_REQUEST["template"];
                if ($do == "user") {
                        $header = array(
                            "帐号",
                            "密码",
                            "是否已使用",
                            "用户类型",
                            "等级",
                            "允许调度台登录",
                            "是GVS用户",
                            "显示本组",
                            "与会者密码",
                            "终端琴键报警对应的调度台号码",
                            "彩信发送默认接受号码",
                            "语音通话方式",
                            "开机启动",
                            "信令加密",
                            "产品编号",
                            "部门编号",
                            "姓名",
                            "性别",
                            "职位",
                            "头像",
                            "默认群组",
                            "所属群组列表",
                            "终端类型",
                            "机型",
                            "IMSI",
                            "IMEI",
                            "SIM卡号",
                            "MAC地址",
                            "蓝牙标识号");
                        $tablename = "T_User_" . $_REQUEST["e_id"];
                        $sql = "SELECT * FROM \"$tablename\"";
                        
                        if ($template == "1") {
                                $api->export($header, $tablename);
                        } else {
                                $api->export($header, $tablename, $sql);
                        }
                }

                if ($do == "area") {
                        $header = array("编号", "区域名称");
                        $sql = "SELECT * FROM \"T_AreaManage\"";
                        $tablename = "area";
                        if ($template == "1") {
                                $api->export($header, $tablename);
                        } else {
                                $api->export($header, $tablename, $sql);
                        }
                }

                if ($do == "user_group") {
                        $header = array("节点编号", "节点名称", "节点父名称", "权重", "层级路径");
                        $tablename = "T_UserGroup_" . $_REQUEST["e_id"];
                        $sql = "SELECT * FROM \"$tablename\"";
                        if ($template == "1") {
                                $api->export($header, $tablename);
                        } else {
                                $api->export($header, $tablename, $sql);
                        }
                }

                if ($do == "ptt_group") {
                        $header = array("群组编号", "群组名称", "群组级别", "组空闲超时", "话权空闲超时", "话权总超时", "录音模式");
                        $tablename = "T_PttGroup_" . $_REQUEST["e_id"];
                        $sql = "SELECT * FROM \"$tablename\"";
                        if ($template == "1") {
                                $api->export($header, $tablename);
                        } else {
                                $api->export($header, $tablename, $sql);
                        }
                }

                break;

        case "exportfile":
                ini_set("memory_limit", "100M");
                set_time_limit(6000);
                try {
                        $path = $api->getfile();
                } catch (Exception $e) {
                        $result = $tools->call($e->getMessage(), 0);
                        echo "<script>parent.callback($result);</script>";
                        die();
                }
                $tablename = $_REQUEST["e_id"];
                if ($do == "user") {
                        $sql = 'INSERT INTO "T_User_' . $tablename . '" (
                "u_number",
                "u_passwd",
                "u_status",
                "u_type",
                "u_level",
                "u_allow_login",
                "u_gvs_user",
                "u_only_show_my_grp",
                "u_auth_conference",
                "u_alarm_inform_svp_num",
                "u_mms_default_rec_num",
                "u_audio_mode",
                "u_auto_run",
                "u_checkup_grade",
                "u_encrypt",
                "u_product_id",
                "u_ug_id",
                "u_name",
                "u_sex",
                "u_position",
                "u_pic",
                "u_default_pg",
                "u_pg_number",
                "u_terminal_type",
                "u_terminal_model",
                "u_imsi",
                "u_imei",
                "u_iccid",
                "u_mac",
                "u_zm"
                )VALUES
                (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
                }
                if ($do == "ptt_group") {
                        $sql = "INSERT INTO \"T_PttGroup_" . $tablename . "\" (
                    \"pg_number\",
                    \"pg_name\",
                    \"pg_level\",
                    \"pg_grp_idle\",
                    \"pg_speak_idle\",
                    \"pg_speak_total\",
                    \"pg_record_mode\"
            )
            VALUES
                    (?,?,?,?,?,?,?);";
                }
                if ($do == "user_group") {
                        $sql = "INSERT INTO \"T_UserGroup_" . $tablename . "\"(
                    \"ug_id\",
                    \"ug_name\",
                    \"ug_parent_id\",
                    \"ug_weight\",
                    \"ug_path\"
            )
            VALUES
                    (?,?,?,?,?)";
                }
                if ($do == "area") {
                        $sql = "INSERT INTO \"T_AreaManage\" (\"am_id\", \"am_name\") VALUES (?, ?)";
                }

                try {
                        $api->inputfile($path, $sql);
                        $result = $tools->call("上传导入成功", 0);
                } catch (Exception $e) {
                        $result = $tools->call($e->getMessage(), $e->getCode());
                }
                echo "<script>parent.callback($result);</script>";
                break;
        default:
                $tools->notfound("0x000002");
}