<?php

require_once ("../shell/class/db.class.php");
require_once ("../private/libs/PHPExcel/PHPExcel.php");

/**
 * 通用接口
 * @deprecated 避免功能接口碎片化，该接口逐渐停用
 * @package OMP_Common
 */
class api extends db {

    public function __construct() {
        parent::__construct();
        $this->data = '';
    }

    public function shelluser() {

        return NULL;
        //@废弃功能 2014-09-24
        /*
          if ($_REQUEST['e_id'] == "") {
          throw new Exception("e_id is null");
          }
          $tablename = 'T_User_' . $_REQUEST['e_id'];
          $sql = 'SELECT
          u_number
          FROM
          ":tablename"
          WHERE
          u_sub_type = 2';

          $sql = str_replace(':tablename', $tablename, $sql);
          $sth = $this->pdo->query($sql);

          $result = $sth->fetchAll();
          return $result;
         *
         */
    }

    public function superconsole() {
        $handle = $_REQUEST['handle'];
        $msg['status'] = 0;
        $msg['msg'] = '操作结束';
        switch ($handle) {
            case 1:
                $sql = 'UPDATE "T_Device"
                                        SET
                                         d_user = FLOOR (RANDOM() * 999),
                                         d_call = FLOOR (RANDOM() * 999),
                                         d_space = FLOOR (RANDOM() * 999),
                                         d_space_free = FLOOR (RANDOM() * 999),
                                         d_audiorec = FLOOR (RANDOM() * 999),
                                         d_videorec = FLOOR (RANDOM() * 999),
                                         d_max_rec_files = FLOOR (RANDOM() * 999),
                                         d_status = 1
                                         WHERE
                                "T_Device".d_name != \'授权设备\'
                                AND
                                "T_Device".d_user = 0
                                ';
                $this->pdo->exec($sql);

                break;
            case 2:
                $sql = 'UPDATE "T_Device"
                                SET d_status = - 1';
                $this->pdo->exec($sql);
                break;
            case 3:
                $sql = 'UPDATE "T_Enterprise"
                                SET
                                e_status = 0';
                $this->pdo->exec($sql);
                break;
            case 4:
                $sql = 'UPDATE "T_Enterprise"
                                SET
                                e_status = 3';
                $this->pdo->exec($sql);
                break;
            case 5:
                $sql = 'INSERT INTO "public"."T_Device" ("d_type","d_ip1", "d_name") VALUES (:d_type,:ip, :d_name)';
                $sth = $this->pdo->prepare($sql);
                $sth->bindValue(':d_type', 'mds');
                $sth->bindValue(':ip', $_SERVER['HTTP_HOST']);
                $sth->bindValue(':d_name', '授权设备');
                $sth->execute();
                break;
            case 6:
                break;
        }
        echo json_encode($msg);
    }

    public function getfile() {
        header("Content-type:text/html;charset=utf-8");
        $filetype = array("xlsx");
        // 生成文件名掩码
        $extension = pathinfo($_FILES['fileToUpload']['name']);


        if ($_FILES['fileToUpload']['error'] != "") {
            switch ($_FILES['fileToUpload']['error']) {
                case UPLOAD_ERR_NO_FILE:
                    throw new Exception("没有文件被选择", -1);
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new Exception("超出了大小限制", -2);
                default:
                    throw new Exception("未知的上传错误", -3);
            }
        }
        if ($_FILES['fileToUpload']['size'] > 999999999) {
            throw new Exception("文件太大，异常中断了", -4);
        }

        //echo !in_array($extension['extension'], $filetype);die();
        if (!in_array($extension['extension'], $filetype)) {
            throw new Exception("未被允许的文件格式", -5);
        }
        // 检查目录
        $dir = "/static/files/doc/" . Date("Ym") . "/";
        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $dir)) {
            if (!mkdir($_SERVER['DOCUMENT_ROOT'] . $dir)) {
                throw new Exception("文件夹创建失败" . $_SERVER['DOCUMENT_ROOT'] . $dir, -5);
            }
        }


        $dir .= uniqid() . "." . $extension['extension'];
        //$dir .= 111 . "." . $extension['extension'];
        if (!move_uploaded_file($_FILES['fileToUpload']["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $dir)) {
            throw new Exception("文件创建失败，请系统管理员检查文件权限或磁盘空间", -6);
        }
        //$result = $tools->call($dir);
        return $dir;
    }

    public function inputfile($path, $sql) {
        // 处理SQL
        $sth = $this->pdo->prepare($sql);

        $objReader = PHPExcel_IOFactory::createReader('Excel2007'); //use excel2007 for 2007 format
        //echo $_SERVER["DOCUMENT_ROOT"].$path;
        $objPHPExcel = $objReader->load($_SERVER["DOCUMENT_ROOT"] . $path);
        $objWorksheet = $objPHPExcel->getSheet(0);

        $highestColumn = $objWorksheet->getHighestColumn();
        $highestRow = $objWorksheet->getHighestRow();    //取得总行数
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); //总列数

        for ($row = 2; $row <= $highestRow; $row++) {
            $strs = array();
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $strs[$col] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
            try {
                $sth->execute($strs);
            } catch (Exception $e) {
                throw new Exception("存在异常导致部分导入失败。" . $e->getMessage(), -11);
            }
        }
    }

    // sql,表头 - 通用导出器
    public function export($header, $filename = "resume", $sql = "") {

        $excel = new PHPExcel();
        foreach ($header as $key => $value) {
            $col = PHPExcel_Cell::stringFromColumnIndex($key);
            $excel->getActiveSheet()->setCellValue($col . 1, $value);
        }

        if ($sql != "") {
            $stat = $this->pdo->query($sql);
            $result = $stat->fetchAll(PDO::FETCH_ASSOC);
            $n = 2;
            foreach ($result as $key => $value) {
                $i = 0;
                foreach ($value as $item) {
                    $col = PHPExcel_Cell::stringFromColumnIndex($i);
                    $excel->getActiveSheet()->setCellValue($col . $n, $item);
                    $i++;
                }
                $n++;
            }
        }
        $output = new PHPExcel_Writer_Excel2007($excel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check = 0, pre-check = 0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="' . $filename . '.xlsx"');
        header("Content-Transfer-Encoding:binary");
        $output->save('php://output');
    }

    function getAcl() {
        if ($_SESSION["own"]["om_area"] == "0" || $_SESSION["own"]["om_area"] == "") {
            return "";
        } else {
            $where = "AND am_id IN (%s)";
            $area = str_replace("|", ",", $_SESSION["own"]["om_area"]);
            $where = sprintf($where, $area);
            return $where;
        }
    }

    public function get_area_list() {
        /*
          //@过期
          exit();
          $sql = 'SELECT"public"."T_AreaManage".am_id,"public"."T_AreaManage".am_name FROM "public"."T_AreaManage" WHERE 1=1 ' . $this->getAcl();

          $stat = $this->pdo->query($sql);
          $result = $stat->fetchAll();
          return $result;
         *
         */
    }

    /*
     * 获取MDS列表
     */

    public function get_mds_list_item() {
        throw new Exception('该函数已经废弃', -1);
        /*
          $sql = 'SELECT
          d_id,
          d_name,
          d_ip1
          FROM
          "T_Enterprise"
          JOIN "T_Device" ON d_id = e_mds_id
          AND d_status = 1 ';
          if ($_SESSION["own"]["om_area"] == "0" || $_SESSION["own"]["om_area"] == "") {

          } else {
          $sql .= " AND e_area IN (%s)";
          $area = str_replace("|", ",", $_SESSION["own"]["om_area"]);
          $sql = sprintf($sql, $area);
          }

          $stat = $this->pdo->query($sql);
          $result = $stat->fetchAll();

          return $result;
          }

          public function get_mds_list() {
          $sql = "SELECT
          d_name,
          d_id,
          d_ip1,
          d_user,
          d_call,
          d_user - sum_user as diff_user,
          d_call - sum_call as diff_call
          FROM
          \"T_Device\"
          LEFT JOIN (
          SELECT
          e_mds_id,
          SUM (e_mds_users) AS sum_user,
          SUM (e_mds_call) AS sum_call
          FROM
          \"T_Enterprise\"
          GROUP BY
          e_mds_id
          ) AS t2 ON e_mds_id = d_id WHERE d_type='mds' and d_status = 1";
          if ($_SESSION["own"]["om_area"] == "0" || $_SESSION["own"]["om_area"] == "") {

          } else {
          $sql .= " AND d_area IN (%s)";
          $area = str_replace("|", ",", $_SESSION["own"]["om_area"]);
          $sql = sprintf($sql, $area);
          }

          $stat = $this->pdo->query($sql);
          $result = $stat->fetchAll();

          foreach ($result as $key => $value) {
          $result[$key]["diff_user"] = ($value["diff_user"] == NULL ) ? $value["d_user"] : $value["diff_user"];
          $result[$key]["diff_call"] = ($value["diff_call"] == NULL ) ? $value["d_call"] : $value["diff_call"];
          }
          return $result;
         *
         */
    }

    /*
     * 获取VCR列表
     */

    public function get_vcr_list() {
        $sql = "SELECT
	d_id,
	d_ip1,
	d_audiorec,
	d_videorec,
        d_space,
	d_audiorec - sum_audiorec AS diff_audiorec,
	d_videorec - sum_videorec AS diff_videorec,
	d_space - sum_space AS diff_space
FROM
	\"T_Device\"
LEFT JOIN (
	SELECT
		e_vcr_id,
		SUM (e_vcr_audiorec) AS sum_audiorec,
		SUM (e_vcr_videorec) AS sum_videorec,
		SUM (e_vcr_space) AS sum_space
	FROM
		\"T_Enterprise\"
	GROUP BY
		e_vcr_id
) AS t2 ON e_vcr_id = d_id WHERE d_type='vcr' and d_status = 1";
        if ($_SESSION["own"]["om_area"] == "0" || $_SESSION["own"]["om_area"] == "") {

        } else {
            $sql .= " AND d_area IN (%s)";
            $area = str_replace("|", ",", $_SESSION["own"]["om_area"]);
            $sql = sprintf($sql, $area);
        }

        $stat = $this->pdo->query($sql);
        $result = $stat->fetchAll();

        foreach ($result as $key => $value) {
            $result[$key]["diff_audiorec"] = ($value["diff_audiorec"] == NULL ) ? $value["d_audiorec"] : $value["diff_audiorec"];
            $result[$key]["diff_videorec"] = ($value["diff_videorec"] == NULL ) ? $value["d_videorec"] : $value["diff_videorec"];
            $result[$key]["diff_space"] = ($value["diff_space"] == NULL ) ? $value["d_space"] : $value["diff_space"];
        }

        return $result;
    }

    /*
     * 获取企业列表
     */

    public function get_enterprise_list() {
        $sql = "SELECT\"public\".\"T_Enterprise\".e_id as id,\"public\".\"T_Enterprise\".e_name as name FROM \"public\".\"T_Enterprise\"";
        $stat = $this->pdo->query($sql);
        $result = $stat->fetchAll();
        return $result;
    }

    /*
     * 获取企业部门列表
     */

    public function get_ug_list($e_id) {
        $sql = 'SELECT ug_id,ug_name,ug_parent_id,ug_path FROM "T_UserGroup_:e_id" ORDER BY ug_path ';
        $sql = str_replace(":e_id", $e_id, $sql);

        $sth = $this->pdo->query($sql);
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get_groups_list($e_id) {
        if ($e_id == "") {
            exit();
        }

        $result[0]['ug_id'] = 0;
        $result = $this->get_ug_list($e_id);

        return $result;
        /*
          print_r($this->data);
          foreach ($this->data[0] as $item) {
          print_r($this->data[0][$item['ug_id']]);
          }
          $linkresult = array();

          foreach ($result as $key => $value) {
          $linkresult[$value['ug_parent_id']] = $value['id'];
          }

          print_r($linkresult);
          die();
         *

          $sql = "SELECT\"public\".\"T_UserGroup_$e_id\".ug_id as id,\"public\".\"T_UserGroup_$e_id\".ug_name as name,ug_parent_id FROM \"public\".\"T_UserGroup_$e_id\"";
          $stat = $this->pdo->query($sql);
          $result = $stat->fetchAll();

          return $result;
         * */
    }

    /*
     * 获取企业群组列表
     */

    public function get_ptt_member_list($e_id) {
        if ($e_id == "") {
            exit();
        }

        $sql = "SELECT \"public\".\"T_PttGroup_$e_id\".pg_number as id,\"public\".\"T_PttGroup_$e_id\".pg_name as name FROM \"public\".\"T_PttGroup_$e_id\"";
        $stat = $this->pdo->query($sql);
        $result = $stat->fetchAll();
        return $result;
    }

    /*
     * 获取产品列表
     */

    public function get_product_list() {
        $sql = "SELECT\"public\".\"T_Product\".p_id as id,\"public\".\"T_Product\".p_name as name FROM \"public\".\"T_Product\"";
        $stat = $this->pdo->query($sql);
        $result = $stat->fetchAll();
        return $result;
    }

}
