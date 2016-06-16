<?php

/**
 * 企业用户实体类
 * @package OMP_Enterprise_dao
 * @require {@see db} {@see pttmember}
 */
class users extends db {

	public $groups;
	public $enterprise;
	public $term;
	public $gprs;

	public function __construct($data) {
		parent::__construct();
		$this->groups = new groups($data);
		$this->enterprise = new enterprise($data);
		$this->term = new terminal($data);
		$this->gprs = new gprs($data);
		$this->data = $data;
	}

	public function delUgId() {
		$sql = <<<SQL
        UPDATE "T_User"
SET
        "u_ug_id" = NULL
WHERE
        u_ug_id = :u_ug_id
                        AND
                        u_e_id = :u_e_id
SQL;
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':u_ug_id', $this->data['u_ug_id'], PDO::PARAM_INT);
		$sth->bindValue(':u_e_id', $this->data['u_e_id'], PDO::PARAM_INT);
		$sth->execute();
	}

	public function delForPtm() {
		$sql = <<<SQL
        UPDATE "T_User"
SET
        "u_default_pg"=''
WHERE
        u_number = :u_number
                        AND
                        u_e_id = :u_e_id
SQL;
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':u_number', $this->data['u_number']);
		$sth->bindValue(':u_e_id', $this->data['u_e_id'], PDO::PARAM_INT);
		$sth->execute();
	}

	public function update_pid($number, $e_id) {

		$sql = <<<SQL
        UPDATE "T_User"
SET
        "u_product_id"=''
WHERE
        u_number = :u_number
                        AND
                        u_e_id = :e_id
SQL;
		$sth = $this->pdo->prepare($sql);

		$sth->bindValue(':u_number', $number);
		$sth->bindValue(':e_id', $e_id, PDO::PARAM_INT);
		try
		{
			$sth->execute();
		} catch (Exception $exc) {
			echo $exc->getCode();
		}
	}

	function vaildAutoConfigRepeat($item) {
		if ($this->data[$item] != '') {
			$sql = <<<SQL
                                SELECT COUNT(*) AS total FROM "T_User" WHERE $item='{$this->data[$item]}' AND u_number != '{$this->data['u_number']}'
SQL;
			$total = $this->total($sql);
			if ($total > 0) {
				$itemstr = strtoupper(str_replace('u_', '', $item));
				throw new Exception("{$itemstr} ".L('已存在'));
			}
		}
	}

	public function vaildAutoConfig() {
		$this->vaildAutoConfigRepeat('u_udid');
		$this->vaildAutoConfigRepeat('u_imsi');
		$this->vaildAutoConfigRepeat('u_imei');
		$this->vaildAutoConfigRepeat('u_iccid');
		$this->vaildAutoConfigRepeat('u_mac');
	}

	public function shelluser() {
		if ($this->data['e_id'] == "") {
			throw new Exception("e_id is null");
		}
		$sql = 'SELECT
	u_number
                        FROM
                                "T_User"
                        WHERE
                                u_sub_type = 2
                         AND u_e_id=:e_id
                ';

		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':e_id', $this->data['e_id'], PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

    /**
     * 添加用户
     */
    public function insert() {

        if ($this->data['u_name'] == "") {
                $this->data['u_name'] = str_replace($this->data['e_id'], '', $this->data['u_number']);
        }

            $sql = <<<SQL
INSERT INTO "T_User" (
    "u_number",
    "u_passwd",
    "u_status",
    "u_type",
    "u_sub_type",
    "u_level",
    "u_allow_login",
    "u_gvs_user",
    "u_only_show_my_grp",
    "u_product_id",
    "u_audio_rec",
    "u_video_rec",
    "u_alarm_inform_svp_num",
    "u_mms_default_rec_num",
    "u_auto_config",
    "u_audio_mode",
    "u_gis_mode",
    "u_auto_run",
    "u_checkup_grade",
    "u_encrypt",
    "u_name",
    "u_pic",
    "u_sex",
    "u_position",
    "u_ug_id",
    "u_default_pg",
    "u_pg_number",
    "u_mobile_phone",
    "u_terminal_type",
    "u_terminal_model",
    "u_zm",
    "u_imsi",
    "u_imei",
    "u_iccid",
    "u_mac",
    "u_udid",
    "u_e_id",
    "u_bind_phone",
    "u_gprs_genus",
    "u_terminal_number",
    "u_purch_date",
    "u_active_state",
    "u_attr_type",
    "u_p_function",
    "u_create_time",
    "u_start_time",
    "u_stop_time",
    "u_commercial_time",
    "u_remark",
    "u_meid"
)
VALUES
    (
    :u_number,
    :u_passwd,
    :u_status,
    :u_type,
    :u_sub_type,
    :u_level,
    :u_allow_login,
    :u_gvs_user,
    :u_only_show_my_grp,
    :u_product_id,
    :u_audio_rec,
    :u_video_rec,
    :u_alarm_inform_svp_num,
    :u_mms_default_rec_num,
    :u_auto_config,
    :u_audio_mode,
    :u_gis_mode,
    :u_auto_run,
    :u_checkup_grade,
    :u_encrypt,
    :u_name,
    :u_pic,
    :u_sex,
    :u_position,
    :u_ug_id,
    :u_default_pg,
    :u_pg_number,
    :u_mobile_phone,
    :u_terminal_type,
    :u_terminal_model,
    :u_zm,
    :u_imsi,
    :u_imei,
    :u_iccid,
    :u_mac,
    :u_udid,
    :u_e_id,
    :u_bind_phone,
    :u_gprs_genus,
    :u_terminal_number,
    :u_purch_date,
    :u_active_state,
    :u_attr_type,
    :u_p_function,
    :u_create_time,
    :u_start_time,
    :u_stop_time,
    :u_commercial_time,
    :u_remark,
    :u_meid
)
SQL;
            $sth = $this->pdo->prepare($sql);
            $sth->bindValue(':u_number', $this->data["u_number"]);
            $sth->bindValue(':u_passwd', $this->data["u_passwd"]);
            $sth->bindValue(':u_status', $this->data["u_status"], PDO::PARAM_INT);
            $sth->bindValue(':u_type', 'SIP');
            $sth->bindValue(':u_sub_type', $this->data["u_sub_type"], PDO::PARAM_INT);
            $sth->bindValue(':u_level', $this->data["u_level"], PDO::PARAM_INT);
            $sth->bindValue(':u_allow_login', $this->data["u_allow_login"], PDO::PARAM_INT);
            $sth->bindValue(':u_gvs_user', $this->data["u_gvs_user"], PDO::PARAM_INT);
            $sth->bindValue(':u_only_show_my_grp', $this->data["u_only_show_my_grp"], PDO::PARAM_INT);
            $sth->bindValue(':u_product_id', $this->data["u_product_id"]);
            $sth->bindValue(':u_audio_rec', $this->data["u_audio_rec"], PDO::PARAM_INT);
            $sth->bindValue(':u_video_rec', $this->data["u_video_rec"], PDO::PARAM_INT);
            $sth->bindValue(':u_alarm_inform_svp_num', $this->data["u_alarm_inform_svp_num"]);
            $sth->bindValue(':u_mms_default_rec_num', $this->data["u_mms_default_rec_num"]);
            $sth->bindValue(':u_auto_config', $this->data["u_auto_config"], PDO::PARAM_INT);
            $sth->bindValue(':u_audio_mode', $this->data["u_audio_mode"], PDO::PARAM_INT);
            $sth->bindValue(':u_gis_mode', $this->data["u_gis_mode"], PDO::PARAM_INT);
            $sth->bindValue(':u_auto_run', $this->data["u_auto_run"], PDO::PARAM_INT);
            $sth->bindValue(':u_checkup_grade', $this->data["u_checkup_grade"], PDO::PARAM_INT);
            $sth->bindValue(':u_encrypt', $this->data["u_encrypt"], PDO::PARAM_INT);
            $sth->bindValue(':u_name', $this->data["u_name"]);
            $sth->bindValue(':u_pic', $this->data["u_pic"]);
            $sth->bindValue(':u_sex', $this->data["u_sex"]);
            $sth->bindValue(':u_position', $this->data["u_position"]);
            $sth->bindValue(':u_ug_id', $this->data["u_ug_id"], PDO::PARAM_INT);
            $sth->bindValue(':u_default_pg', $this->data["u_default_pg"]);
            $sth->bindValue(':u_pg_number', $this->data["u_pg_number"]);
            $sth->bindValue(':u_mobile_phone', $this->data["u_mobile_phone"]);
            $sth->bindValue(':u_terminal_type', $this->data["u_terminal_type"]);
            $sth->bindValue(':u_terminal_model', $this->data["u_terminal_model"]);
            $sth->bindValue(':u_zm', $this->data["u_zm"]);
            $sth->bindValue(':u_imsi', strtoupper($this->data["u_imsi"]));
            /*$sth->bindValue(':u_imei', strtoupper($this->data["u_imei"]));
            $sth->bindValue(':u_iccid', strtoupper($this->data["u_iccid"]));*/
            $sth->bindValue(':u_imei', trim($this->data["u_imei"]));
            $sth->bindValue(':u_iccid', trim($this->data["u_iccid"]));
            $sth->bindValue(':u_mac', strtoupper($this->data["u_mac"]));
            $sth->bindValue(':u_udid', strtoupper($this->data["u_udid"]));
            $sth->bindValue(':u_e_id', $this->data["e_id"]);
            $sth->bindValue(':u_bind_phone', $this->data["u_bind_phone"]);
            $sth->bindValue(':u_gprs_genus', $this->data["u_gprs_genus"]);
            $sth->bindValue(':u_terminal_number', $this->data["u_terminal_number"]);
            $sth->bindValue(':u_purch_date', $this->data["u_purch_date"]);
            $sth->bindValue(':u_active_state', $this->data["u_active_state"]);
            $sth->bindValue(':u_attr_type', $this->data["u_attr_type"]);
            $sth->bindValue(':u_p_function', $this->data["u_p_function"]);
            $sth->bindValue(':u_create_time', date("Y-m-d",time()));
            $sth->bindValue(':u_start_time', $this->data["u_active_state"]==1?date("Y-m-d",time()):"");
            $sth->bindValue(':u_stop_time',$this->data["u_active_state"]==0?date("Y-m-d",time()):"");
            $sth->bindValue(':u_commercial_time', $this->data["u_attr_type"]=="0"?date('Y-m-d',time()):"");
            $sth->bindValue(':u_remark', $this->data["u_remark"], PDO::PARAM_STR);
            $sth->bindValue(':u_meid', trim($this->data["u_meid"]));
            $sth->execute();
    }

    /**
     * 修改用户
     * @return array $changeinfo
     */
    public function update() {
            $sql = <<<SQL
UPDATE "T_User"
SET
	"u_passwd"=:u_passwd,
	"u_status"=:u_status,
	"u_type"=:u_type,
	"u_sub_type"=:u_sub_type,
	"u_level"=:u_level,
	"u_allow_login"=:u_allow_login,
	"u_gvs_user"=:u_gvs_user,
	"u_only_show_my_grp"=:u_only_show_my_grp,
	"u_audio_rec"=:u_audio_rec,
	"u_video_rec"=:u_video_rec,
	"u_alarm_inform_svp_num"=:u_alarm_inform_svp_num,
	"u_mms_default_rec_num"=:u_mms_default_rec_num,
	"u_auto_config"=:u_auto_config,
	"u_audio_mode"=:u_audio_mode,
	"u_gis_mode"=:u_gis_mode,
	"u_auto_run"=:u_auto_run,
	"u_checkup_grade"=:u_checkup_grade,
	"u_encrypt"=:u_encrypt,
	"u_name"=:u_name,
	"u_pic"=:u_pic,
	"u_sex"=:u_sex,
	"u_position"=:u_position,
	"u_ug_id"=:u_ug_id,
	"u_default_pg"=:u_default_pg,
	"u_pg_number"=:u_pg_number,
	"u_mobile_phone"=:u_mobile_phone,
	"u_terminal_type"=:u_terminal_type,
	"u_terminal_model"=:u_terminal_model,
	"u_zm"=:u_zm,
	"u_imsi"=:u_imsi,
	"u_imei"=:u_imei,
	"u_iccid"=:u_iccid,
	"u_mac"=:u_mac,
	"u_udid"=:u_udid,
	"u_e_id"=:u_e_id,
                  "u_bind_phone"=:u_bind_phone,
                  "u_gprs_genus"=:u_gprs_genus,
                  "u_terminal_number"=:u_terminal_number,
                  "u_purch_date"=:u_purch_date,
                  "u_attr_type"=:u_attr_type,
    "u_start_time"=:u_start_time,
    "u_stop_time"=:u_stop_time,
    "u_p_function"=:u_p_function,
    "u_product_id"=:u_product_id,
    "u_active_state"=:u_active_state,
    "u_p_function_new"=:u_p_function_new,
    "u_commercial_time"=:u_commercial_time,
    "u_product_id_new"=:u_product_id_new,
    "u_remark"=:u_remark,
    "u_meid"=:u_meid
WHERE
"u_number"=:u_number
SQL;

            $sql1 = "SELECT * FROM \"T_User\" WHERE u_number='{$this->data['u_number']}'";

            $sth1 = $this->pdo->query($sql1);
            $info = $sth1->fetch(PDO::FETCH_ASSOC);
            $changeinfo = array();
            foreach ($info as $k => $v) {
                    if ($info[$k] != $this->data[$k]) {
                            $changeinfo[$k] = $this->data[$k];
                    }
            }

            $sth = $this->pdo->prepare($sql);
            $sth->bindValue(':u_number', $this->data["u_number"], PDO::PARAM_STR);
            $sth->bindValue(':u_passwd', $this->data["u_passwd"], PDO::PARAM_STR);
            $sth->bindValue(':u_status', $this->data["u_status"], PDO::PARAM_INT);
            $sth->bindValue(':u_type', 'SIP', PDO::PARAM_STR);
            $sth->bindValue(':u_sub_type', $this->data["u_sub_type"], PDO::PARAM_INT);
            $sth->bindValue(':u_level', $this->data["u_level"], PDO::PARAM_INT);
            $sth->bindValue(':u_allow_login', $this->data["u_allow_login"], PDO::PARAM_INT);
            $sth->bindValue(':u_gvs_user', $this->data["u_gvs_user"], PDO::PARAM_INT);
            $sth->bindValue(':u_only_show_my_grp', $this->data["u_only_show_my_grp"], PDO::PARAM_INT);
            $sth->bindValue(':u_audio_rec', $this->data["u_audio_rec"], PDO::PARAM_INT);
            $sth->bindValue(':u_video_rec', $this->data["u_video_rec"], PDO::PARAM_INT);
            $sth->bindValue(':u_alarm_inform_svp_num', $this->data["u_alarm_inform_svp_num"]);
            $sth->bindValue(':u_mms_default_rec_num', $this->data["u_mms_default_rec_num"]);
            $sth->bindValue(':u_auto_config', $this->data["u_auto_config"], PDO::PARAM_INT);
            $sth->bindValue(':u_audio_mode', $this->data["u_audio_mode"], PDO::PARAM_INT);
            $sth->bindValue(':u_gis_mode', $this->data["u_gis_mode"], PDO::PARAM_INT);
            $sth->bindValue(':u_auto_run', $this->data["u_auto_run"], PDO::PARAM_INT);
            $sth->bindValue(':u_checkup_grade', $this->data["u_checkup_grade"], PDO::PARAM_INT);
            $sth->bindValue(':u_encrypt', $this->data["u_encrypt"], PDO::PARAM_INT);
            $sth->bindValue(':u_name', $this->data["u_name"]);
            $sth->bindValue(':u_pic', $this->data["u_pic"]);
            $sth->bindValue(':u_sex', $this->data["u_sex"]);
            $sth->bindValue(':u_position', $this->data["u_position"]);
            $sth->bindValue(':u_ug_id', $this->data["u_ug_id"]);
            $sth->bindValue(':u_default_pg', $this->data["u_default_pg"], PDO::PARAM_STR);
            $sth->bindValue(':u_pg_number', $this->data["u_pg_number"]);
            $sth->bindValue(':u_mobile_phone', $this->data["u_mobile_phone"]);
            $sth->bindValue(':u_terminal_type', $this->data["u_terminal_type"]);
            $sth->bindValue(':u_terminal_model', $this->data["u_terminal_model"]);
            $sth->bindValue(':u_zm', $this->data["u_zm"]);
            $sth->bindValue(':u_imsi', strtoupper($this->data["u_imsi"]));
            /*$sth->bindValue(':u_imei', strtoupper($this->data["u_imei"]));
            $sth->bindValue(':u_iccid', strtoupper($this->data["u_iccid"]));*/
            $sth->bindValue(':u_imei', trim($this->data["u_imei"]));
            $sth->bindValue(':u_iccid', trim($this->data["u_iccid"]));
            $sth->bindValue(':u_mac', strtoupper($this->data["u_mac"]));
            $sth->bindValue(':u_udid', strtoupper($this->data["u_udid"]));
            $sth->bindValue(':u_e_id', $this->data["e_id"], PDO::PARAM_INT);
            $sth->bindValue(':u_bind_phone', $this->data["u_bind_phone"]);
            $sth->bindValue(':u_gprs_genus', $this->data["u_gprs_genus"]);
            $sth->bindValue(':u_terminal_number', $this->data["u_terminal_number"]);
            $sth->bindValue(':u_purch_date', $this->data["u_purch_date"]);
            $sth->bindValue(':u_attr_type', $this->data["u_attr_type"]);
            $sth->bindValue(':u_start_time', $this->data["u_active_state"]=="1"?date("Y-m-d",time()):$info['u_start_time']);
            $sth->bindValue(':u_stop_time', $this->data["u_active_state"]=="0"?date("Y-m-d",time()):$info['u_stop_time']);
            $sth->bindValue(':u_p_function', $this->data["u_p_function"]);
            $sth->bindValue(':u_product_id', $this->data["u_product_id"]);
            $sth->bindValue(':u_active_state', $this->data["u_active_state"]);
            $sth->bindValue(':u_p_function_new', $this->data["u_p_function_new"]);
            $sth->bindValue(':u_commercial_time', $this->data["u_attr_type"]=="0"?date("Y-m-d",time()):$info['u_commercial_time']);
            $sth->bindValue(':u_product_id_new', $this->data["u_product_id_new"]);
            $sth->bindValue(':u_remark', $this->data["u_remark"], PDO::PARAM_STR);
            $sth->bindValue(':u_meid', trim($this->data["u_meid"]));
            $sth->execute();

            return $changeinfo;
    }

	/**
	 * 获取群组信息
	 * @param type $param
	 */
	public function getPGinfo($number) {
		$e_id = $this->data["e_id"];
		$sql = "SELECT * FROM \"T_PttGroup_{$e_id}\" WHERE pg_number='{$number}'";
		$sth = $this->pdo->query($sql);
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	public function delete() {
		$sql = <<<SQL
DELETE FROM "T_User" WHERE u_number = :u_number
SQL;
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':u_number', $this->data["u_number"]);
		$sth->execute();
	}

	public function deleteAll() {
		$sql = <<<SQL
DELETE FROM "T_User" WHERE u_e_id = :u_e_id
SQL;
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':u_e_id', $this->data["u_e_id"]);
		try {
                 $num=$this->getTotal(false);
                 $count=$sth->execute();
                 $sum=0;
                 if($count==1){
                    $sum=$num;
                 }
            } catch (Exception $exc) {
            }
            return $sum;
    }

	public function hasUser($u_number) {
		$tablename = '"T_User"';
		$u_number = $this->pdo->quote($u_number);
		$sql = "SELECT
                        *
                FROM
                        %s
                WHERE
                        u_number = %s";
		$sql = sprintf($sql, $tablename, $u_number);

		$sth = $this->pdo->query($sql);
		$result = $sth->fetch();
		return $result;
	}

	public function moveUsers() {
		$e_id = $this->data['e_id'];
		$to_e_id = $this->data["to_e_id"];
		$data = $this->getById();
		if (!isPhone($data['u_number'])) {
			throw new Exception(L('号码不是手机号'), -1);
		}

		$this->set($data);
		$this->data['do'] = 'edit';
		$this->data['u_product_id'] = '';
		$this->data['u_default_pg'] = '';
		$this->data['u_ug_id'] = '';
		$this->data['u_alarm_inform_svp_num'] = '';
		$this->data['u_mms_default_rec_num'] = '';
		$this->data['e_id'] = $to_e_id;

		$this->save();

		$ptdata['e_id'] = $e_id;
		$ptdata['pg_number'] = $data['u_default_pg'];
		$ptdata['pm_number'] = $data['u_number'];
		$ptm = new pttmember($ptdata);
		$ptm->delAllUser();

		$log = DL('移动企业用户【%s】%s从【%s】至【%s】企业');
		$log = sprintf($log
			, $data['u_name']
			, $this->data['u_number']
			, $data['u_e_id']
			, $to_e_id
		);
		$this->log($log, 1, 0);
	}

	public function getwhere($order = false) {
		$where = " WHERE 1=1 ";

		if ($this->data['u_sub_type'] != "") {
			$where .= " AND u_sub_type = " . $this->data["u_sub_type"];
		}
		if ($this->data['u_attr_type'] != "") {
			$where .= " AND u_attr_type = '" . $this->data["u_attr_type"]."'";
		}

		if ($this->data["u_product_id"] != "") {
			$where .= " AND u_product_id = '" . $this->data["u_product_id"] . "'";
		}
		if ($this->data["u_default_pg"] != "") {
			$where .= " AND u_default_pg = '" . $this->data["u_default_pg"] . "'";
		}
		if ($this->data["u_ug_id"] != "" && $this->data["u_ug_id"] != "0") {
			$where .= " AND u_ug_id = " . $this->data["u_ug_id"];
		}
		if ($this->data["u_pic"] != "") {
			if ($this->data["u_pic"] == "0") {
				$where .= " AND (u_pic = '' OR u_pic IS NULL) ";
			}
			if ($this->data["u_pic"] == "1") {
				$where .= " AND u_pic != '' ";
			}
		}
		if ($this->data["u_sex"] != "") {
			$where .= " AND u_sex = '" . $this->data["u_sex"] . "'";
		}

		if ($this->data["u_number"] != "") {
			$where .= " AND u_number LIKE E'%" . addslashes(trim($this->data["u_number"])) . "%'";
		}
		if ($this->data["u_name"] != "") {
			$where .= " AND u_name LIKE E'%" . addslashes(trim($this->data["u_name"])) . "%'";
		}
		if ($this->data["u_mobile_phone"] != "") {
			$where .= " AND u_mobile_phone LIKE E'%" . addslashes($this->data["u_mobile_phone"]) . "%'";
		}
		/*
		if ($this->data["u_number"] != "") {
		$where .= "AND u_number = '" . $this->data["u_number"] . "'";
		}
		 *
		 */
		if ($this->data["u_terminal_type"] != "") {
			$where .= " AND u_terminal_type LIKE E'%" . addslashes($this->data["u_terminal_type"]) . "%'";
		}
		if ($this->data["u_terminal_model"] != "") {
			$where .= " AND u_terminal_model LIKE E'%" . addslashes($this->data["u_terminal_model"]) . "%'";
		}
		if ($this->data["u_imsi"] != "") {
			$where .= " AND u_imsi LIKE E'%" . addslashes($this->data["u_imsi"]) . "%'";
		}
		if ($this->data["u_imei"] != "") {
			$where .= " AND u_imei LIKE E'%" . addslashes($this->data["u_imei"]) . "%'";
		}
        if ($this->data["u_meid"] != "") {
            $where .= " AND u_meid LIKE E'%" . addslashes($this->data["u_meid"]) . "%'";
        }
		if ($this->data["u_iccid"] != "") {
			$where .= " AND u_iccid LIKE E'%" . addslashes($this->data["u_iccid"]) . "%'";
		}
		if ($this->data["u_mac"] != "") {
			$where .= " AND u_mac ILIKE E'%" . addslashes($this->data["u_mac"]) . "%'";
		}
		if ($this->data["u_udid"] != "") {
			$where .= " AND u_udid ILIKE E'%" . addslashes($this->data["u_udid"]) . "%'";
		}
		if ($this->data["u_zm"] != "") {
			$where .= " AND u_zm LIKE E'%" . addslashes($this->data["u_zm"]) . "%'";
		}
                                    if ($this->data["checkbox1"] == "") {
                                            $this->data["checkbox1"] = NULL;
                                   }else{
	                  //$str= implode($this->data["checkbox1"]);
	                  //$this->data["u_p_function"]=$str;
	                  foreach ($this->data["checkbox1"] as $key => $value) {
                                        $where .= " AND u_p_function LIKE E'%". $value."%'";
	                  }
	           }

		if ($this->data["e_id"] != "") {
			$where .= " AND u_e_id =" . $this->data["e_id"];
		}
                                 $where .= " AND u_e_id IN ( " . $this->getallep() .")";
		if ($order) {
			//$where .= ' ORDER BY ug_path,ug_weight,u_number';
			$where .= ' ORDER BY u_number';
		}
		return $where;
	}
	public function getcustwhere($order = false) {

		if ($this->data["e_id"] != "") {
			$where .= " AND u_e_id = " . $this->data["e_id"];
		}
		if ($this->data['u_sub_type'] != "") {
			$where .= " AND u_sub_type = " . $this->data["u_sub_type"];
		}
		if ($this->data['u_attr_type'] != "") {
			$where .= " AND u_attr_type = '" . $this->data["u_attr_type"]."'";
		}

		if ($this->data["u_product_id"] != "") {
			$where .= " AND u_product_id = '" . $this->data["u_product_id"] . "'";
		}
		if ($this->data["u_default_pg"] != "") {
			$where .= " AND u_default_pg = '" . $this->data["u_default_pg"] . "'";
		}
		if ($this->data["u_ug_id"] != "" && $this->data["u_ug_id"] != "0") {
			$where .= " AND u_ug_id = " . $this->data["u_ug_id"];
		}
		if ($this->data["u_pic"] != "") {
			if ($this->data["u_pic"] == "0") {
				$where .= " AND (u_pic = '' OR u_pic IS NULL) ";
			}
			if ($this->data["u_pic"] == "1") {
				$where .= " AND u_pic != '' ";
			}
		}
		if ($this->data["u_sex"] != "") {
			$where .= " AND u_sex = '" . $this->data["u_sex"] . "'";
		}

		if ($this->data["u_number"] != "") {
			$where .= " AND u_number LIKE E'%" . addslashes(trim($this->data["u_number"])) . "%'";
		}
		if ($this->data["u_name"] != "") {
			$where .= " AND u_name LIKE E'%" . addslashes(trim($this->data["u_name"])) . "%'";
		}
		if ($this->data["u_mobile_phone"] != "") {
			$where .= " AND u_mobile_phone '%" . addslashes($this->data["u_mobile_phone"]) . "%'";
		}
		/*
		if ($this->data["u_number"] != "") {
		$where .= "AND u_number = '" . $this->data["u_number"] . "'";
		}
		 *
		 */
		if ($this->data["u_terminal_type"] != "") {
			$where .= " AND u_terminal_type LIKE E'%" . addslashes($this->data["u_terminal_type"]) . "%'";
		}
		if ($this->data["u_terminal_model"] != "") {
			$where .= " AND u_terminal_model LIKE E'%" . addslashes($this->data["u_terminal_model"]) . "%'";
		}
		if ($this->data["u_imsi"] != "") {
			$where .= " AND u_imsi LIKE E'%" . addslashes($this->data["u_imsi"]) . "%'";
		}
		if ($this->data["u_imei"] != "") {
			$where .= " AND u_imei LIKE E'%" . addslashes($this->data["u_imei"]) . "%'";
		}
        if ($this->data["u_meid"] != "") {
            $where .= " AND u_meid LIKE E'%" . addslashes($this->data["u_meid"]) . "%'";
        }
		if ($this->data["u_iccid"] != "") {
			$where .= " AND u_iccid LIKE E'%" . addslashes($this->data["u_iccid"]) . "%'";
		}
		if ($this->data["u_mac"] != "") {
			$where .= " AND u_mac ILIKE E'%" . addslashes($this->data["u_mac"]) . "%'";
		}
		if ($this->data["u_udid"] != "") {
			$where .= " AND u_udid ILIKE E'%" . addslashes($this->data["u_udid"]) . "%'";
		}
		if ($this->data["u_zm"] != "") {
			$where .= " AND u_zm LIKE E'%" . addslashes($this->data["u_zm"]) . "%'";
		}

		if ($order) {
			$where .= ' ORDER BY ug_path,ug_weight,u_number';
		}

		return $where;
	}

	public function getwhere_alluser($order = false) {
		$where = " WHERE 1=1 ";

		if ($this->data["e_id"] != "") {
			$where .= " AND u_e_id = " . $this->data["e_id"];
		}
		if ($this->data['u_sub_type'] != "") {
			$where .= " AND u_sub_type = " . $this->data["u_sub_type"];
		}
		if ($this->data['u_attr_type'] != "") {
			$where .= " AND u_attr_type = '" . $this->data["u_attr_type"]."'";
		}
		if ($this->data['ug_name'] != "") {
			$where .= " AND ug_name = '" . $this->data["ug_name"] . "'";
		}
		if ($this->data["u_product_id"] != "") {
			$where .= " AND u_product_id = '" . $this->data["u_product_id"] . "'";
		}
		if ($this->data["u_default_pg"] != "") {
			$where .= " AND u_default_pg = '" . $this->data["u_default_pg"] . "'";
		}
		if ($this->data["u_ug_id"] != "" && $this->data["u_ug_id"] != "0") {
			$where .= " AND u_ug_id = " . $this->data["u_ug_id"];
		}
		if ($this->data["u_pic"] != "") {
			if ($this->data["u_pic"] == "0") {
				$where .= " AND (u_pic = '' OR u_pic IS NULL) ";
			}
			if ($this->data["u_pic"] == "1") {
				$where .= " AND u_pic != '' ";
			}
		}
		if ($this->data["u_sex"] != "") {
			$where .= " AND u_sex = '" . $this->data["u_sex"] . "'";
		}

		if ($this->data["u_number"] != "") {
			$where .= " AND u_number LIKE E'%" . addslashes($this->data["u_number"]) . "%'";
		}
		if ($this->data["u_name"] != "") {
			$where .= " AND u_name LIKE E'%" . addslashes($this->data["u_name"]) . "%'";
		}
		if ($this->data["u_mobile_phone"] != "") {
			$where .= " AND u_mobile_phone = '" . $this->data["u_mobile_phone"] . "'";
		}
		/*
		if ($this->data["u_number"] != "") {
		$where .= "AND u_number = '" . $this->data["u_number"] . "'";
		}
		 *
		 */
		if ($this->data["u_terminal_type"] != "") {
			$where .= " AND u_terminal_type LIKE E'%" . addslashes($this->data["u_terminal_type"]) . "%'";
		}
		if ($this->data["u_terminal_model"] != "") {
			$where .= " AND u_terminal_model LIKE E'%" . addslashes($this->data["u_terminal_model"]) . "%'";
		}
		if ($this->data["u_imsi"] != "") {
			$where .= " AND u_imsi LIKE E'%" . addslashes($this->data["u_imsi"]) . "%'";
		}
		if ($this->data["u_imei"] != "") {
			$where .= " AND u_imei LIKE E'%" . addslashes($this->data["u_imei"]) . "%'";
		}
        if ($this->data["u_meid"] != "") {
            $where .= " AND u_meid LIKE E'%" . addslashes($this->data["u_meid"]) . "%'";
        }
		if ($this->data["u_iccid"] != "") {
			$where .= " AND u_iccid LIKE E'%" . addslashes($this->data["u_iccid"]) . "%'";
		}
		if ($this->data["u_mac"] != "") {
			$where .= " AND u_mac LIKE E'%" . addslashes($this->data["u_mac"]) . "%'";
		}
		if ($this->data["u_zm"] != "") {
			$where .= " AND u_zm LIKE E'%" . addslashes($this->data["u_zm"]) . "%'";
		}

		if ($this->data["e_id"] != "") {
			$where .= " AND u_e_id =" . $this->data["e_id"];
		}
		if ($order) {
			$where .= ' ORDER BY u_ug_id,ug_path,ug_weight,u_number';
		}

		return $where;
	}

	/**
	 * @param $order
	 * @return string
	 * 群组用户筛选条件设置
	 */
	public function getpgWhere($order = flase) {
		$where = " WHERE 1=1 ";
		if ($this->data["pg_number"] != "") {
			$where .= "AND pm_pgnumber = '" . $this->data["pg_number"] . "'";
		}
		if ($this->data["u_name"] != "") {
			$where .= " AND u_name LIKE E'%" . addslashes(trim($this->data["u_name"])) . "%'";
		}
		if ($this->data["u_ug_id"] != "") {
			$where .= " AND u_ug_id = '" . $this->data["u_ug_id"] . "'";
		}
		if ($this->data["u_attr_type"] != "") {
			$where .= " AND u_attr_type = '" . $this->data["u_attr_type"] . "'";
		}
		if ($this->data["u_sub_type"] != "") {
			$where .= " AND u_sub_type = '" . $this->data["u_sub_type"] . "'";
		}
		if ($this->data["u_number"] != "") {
			$where .= "AND u_number LIKE E'%" . addslashes(trim($this->data["u_number"])) . "%'";
		}
		if ($order) {
			$where .= ' ORDER BY ug_path,ug_weight,u_number';
		}

		return $where;
	}

	public function getWhereV2() {
		if ($this->data["e_id"] != "") {
			$this->where(" u_e_id = " . $this->data["e_id"]);
		}
		if ($this->data['u_sub_type'] != "") {
			$this->where(" u_sub_type = " . $this->data["u_sub_type"]);
		}
		if ($this->data['u_attr_type'] != "") {
			$this->where(" u_attr_type = '" . $this->data["u_attr_type"]."'");
		}

		if ($this->data["u_product_id"] != "") {
			$this->where(" u_product_id = '" . $this->data["u_product_id"] . "'");
		}
		if ($this->data["u_default_pg"] != "") {
			$this->where(" u_default_pg = '" . $this->data["u_default_pg"] . "'");
		}
		if ($this->data["u_ug_id"] != "" && $this->data["u_ug_id"] != "0") {
			$this->where(" u_ug_id = " . $this->data["u_ug_id"]);
		}
		if ($this->data["u_pic"] != "") {

			if ($this->data["u_pic"] == 0) {
				$this->where(" u_pic = '' OR u_pic IS NULL ");
			}
			if ($this->data["u_pic"] == 1) {
				$this->where(" u_pic != '' ");
			}
		}
		if ($this->data["u_sex"] != "") {
			$this->where(" u_sex = '" . $this->data["u_sex"] . "'");
		}

		if ($this->data["u_number"] != "") {
			$this->where(" u_number LIKE E'%" . addslashes(trim($this->data["u_number"])) . "%'");
		}
		if ($this->data["u_name"] != "") {
			$this->where(" u_name LIKE E'%" . addslashes(trim($this->data["u_name"])) . "%'");
		}
		if ($this->data["u_terminal_type"] != "") {
			$this->where(" u_terminal_type LIKE E'%" . addslashes($this->data["u_terminal_type"]) . "%'");
		}
		if ($this->data["u_terminal_model"] != "") {
			$this->where(" u_terminal_model LIKE E'%" . addslashes($this->data["u_terminal_model"]) . "%'");
		}
		if ($this->data["u_imsi"] != "") {
			$this->where(" u_imsi LIKE E'%" . addslashes($this->data["u_imsi"]) . "%'");
		}
        if ($this->data["u_meid"] != "") {
            $this->where(" u_meid LIKE E'%" . addslashes($this->data["u_meid"]) . "%'");
        }
		if ($this->data["u_imei"] != "") {
			$this->where(" u_imei LIKE E'%" . addslashes($this->data["u_imei"]) . "%'");
		}
		if ($this->data["u_iccid"] != "") {
			$this->where(" u_iccid LIKE E'%" . addslashes($this->data["u_iccid"]) . "%'");
		}
		if ($this->data["u_mac"] != "") {
			$this->where(" u_mac LIKE E'%" . addslashes($this->data["u_mac"]) . "%'");
		}
		if ($this->data["u_zm"] != "") {
			$this->where(" u_zm LIKE E'%" . addslashes($this->data["u_zm"]) . "%'");
		}

		if ($this->data["e_id"] != "") {
			$this->where(" u_e_id =" . $this->data["e_id"]);
		}

		return $this;
	}

    public function delUser() {
            $log = DL('删除企业用户【%s】成功');
            $log = sprintf($log
                    , $this->data['u_number']
            );
            $this->log($log, db::USER, db::INFO);
            /*
            $cust_pg = new groups ( $this->data );
            $result = $cust_pg->getList_cust ();
            foreach ( $result as $key => $value )
            {
            $mem_arr = explode ( ";" , $value['c_pg_mem_list'] );
            if ( in_array ( $this->data['u_number'] , $mem_arr ) )
            {
            $k = array_search ( $this->data['u_number'] , $mem_arr );

            array_splice ( $mem_arr , $k , 1 );
            $mem_str = implode ( ";" , $mem_arr );
            $sql = "UPDATE \"%s\" SET c_pg_mem_list=%s WHERE c_pg_number='{$value['c_pg_number']}'";
            $sql = sprintf ( $sql , 'T_Custom_PttGrp_' . $this->data['e_id'] , sprintf ( "'%s'" , $mem_str ) );
            $this->pdo->exec ( $sql );
            }
            }
             */
            $count = array();
            $sql = 'DELETE FROM "%s" WHERE u_number = %s';
            $sql = sprintf($sql, $this->getTableName(), sprintf("'%s'", $this->data['u_number']));
            $data=$this->getById();
            $user_info=$this->getById_history();
                                    //该用户是否被绑定 YES 解绑
            if($data['u_imei']!=""){
                    $res = check_md_imei($data['u_imei'],$data['u_e_id']);
                    if($res=="Binding"){//判断是否已绑定,
                            //解绑

                            $data['md_imei']=$data['u_imei'];
                            $this->term->set($data);
                            $info=$this->term->releaseBound();
                             if(strtotime($user_info['md_binding_time'])==strtotime(date('Y-m-d',time()))){
                                if($data['u_attr_type']=="0"){
                                    $this->add_commercial_term($data['u_e_id'], -1);
                                }else{
                                    $this->add_test_term($data['u_e_id'], -1);
                                }
                                $this->add_terminal($data['u_e_id'], -1);
                             }
                            if($info['status']==0){
                                    $this->term->set_term_history($user_info,"unbind");
                            }
                    }
            }
                                if($data['u_iccid']!=""){
                                     //删除企业用户时流量卡的对应操作
                                    $this->gprs->delusergprs($this->data['u_number']);
                                     if(strtotime($user_info['g_binding_time'])==strtotime(date('Y-m-d',time()))){
                                        if($data['u_attr_type']=="0"){
                                           $this->add_commercial_gprs($data['u_e_id'], -1);
                                       }else{
                                           $this->add_test_gprs($data['u_e_id'], -1);
                                       }
                                       $this->add_gprs($data['u_e_id'], -1);
                                     }
                                    $this->gprs->gprsreleaseBound_history($user_info);
                                }
                        $this->set_user_history($user_info, 0);
            try{
                                    $count['u'] = $this->pdo->exec($sql);
            }catch (Exception $ex){
            }
            $sql = 'DELETE FROM "%s" WHERE pm_number = %s';
            $sql = sprintf($sql, 'T_PttMember_' . $this->data['e_id'], sprintf("'%s'", $this->data['u_number']));
            $count['p'] = $this->pdo->exec($sql);
            return $count;
    }

	public function delList() {
		$count['r'] = 0;
		$count['c'] = 0;
		foreach ($this->data['checkbox'] as $value) {
			$this->data['u_number'] = $value;
			$info=$this->getById();
			$result = $this->delUser();
			$count['r'] += $result['u'];
			if($info['u_create_time']==date('Y-m-d',time())){
				$count['c'] += $result['u'];
			}
		}
		return $count;
	}

    /**
     * 批量修改订购产品，默认群组，部门信息
     * @data u_product_id % 保留该信息
     * @data u_default_pg % 保留该信息
     * @data u_ug_id % 保留该信息
     */
    public function batchUser() {

//$this->data["u_product_id"] = "%";
            //$this->data["u_default_pg"] = "%";

            $data = $this->getById();
            $data['e_id'] = $data['u_e_id'];
            $data['isused'] = $this->data['isused'];
            if($this->data["isused"]=="on"){
                if($data['u_sub_type']==1){
                     if ($this->data["u_product_id"] != "%") {
                            $data['u_product_id_new'] = $this->data["u_product_id"];
                    }
                    if ($this->data["u_p_function_new"] != "%") {
                            $data['u_p_function_new'] = $this->data["u_p_function_new"];
                    }
                }else{
                    $data['u_product_id_new'] = NULL;
                    $data['u_p_function_new'] = NULL;
                }
            }else{
               if ($this->data["u_product_id"] != "%") {
                        $data['u_product_id'] = $this->data["u_product_id"];
                }
                $data['u_product_id_new'] = NULL;
                if ($this->data["u_p_function_new"] != "%") {
                        $data['u_p_function'] = $this->data["u_p_function_new"];
                }
            }
            if ($this->data["u_default_pg"] != "%"&&$this->data["u_default_pg"] != null) {
                $data['u_default_pg'] = $this->data["u_default_pg"];
            }
            if ($this->data["u_gis_mode"] != "%"&&$this->data["u_gis_mode"] != null) {
                $data['u_gis_mode'] = $this->data["u_gis_mode"];
            }
            if ($this->data["u_mms_default_rec_num"] != "%") {
                $data['u_mms_default_rec_num'] = $this->data["u_mms_default_rec_num"];
            }
            if ($this->data["u_alarm_inform_svp_num"] != "%") {
                $sql = "SELECT * FROM \"T_User\" WHERE u_number = '".$this->data["u_alarm_inform_svp_num"]."'";
                $sth = $this->pdo->prepare($sql);
                $sth->execute();
                $aNum = $sth->fetch(PDO::FETCH_ASSOC);
                if($aNum)
                {
                    $data['u_alarm_inform_svp_num'] = $this->data["u_alarm_inform_svp_num"];
                }
                if($this->data["u_alarm_inform_svp_num"]==""){
                    $data['u_alarm_inform_svp_num'] = NULL;
                }
            }
            if ($this->data["u_only_show_my_grp"] != "%"&&$this->data["u_only_show_my_grp"] != null) {
                $data['u_only_show_my_grp'] = $this->data["u_only_show_my_grp"];
            }
           if($_SESSION['ident']=="GQT"){
               if ($this->data["u_ug_id"] != "%"||$this->data["u_ug_id"] == null) {
                    $data['u_ug_id'] = $this->data["u_ug_id"];
                }
           }else{
               if ($this->data["u_ug_id"] != "%"&&$this->data["u_ug_id"] != null) {
                    $data['u_ug_id'] = $this->data["u_ug_id"];
                }else if($this->data["u_ug_id"] == ""){
                    $data['u_ug_id'] = $this->data["u_ug_id"];
                }
           }
            $data['pm_hangup'] = $this->data['pm_hangup'];
            //$data['u_p_function'] = $this->data['u_p_function'];
            $data['checkbox1'] = $this->data['checkbox1'];
            $data['pm_level'] = $this->data['pm_level'];
            $data['u_number'] = $this->data['u_number'];
//$data['u_name'] = $this->data['u_name'];
            $data['pg_name'] = $this->data['pg_name'];
            $data['submit_type'] = $this->data['submit_type'];
            $data['do'] = 'edit';
            $this->set($data);               
            $this->save();
    }

//用户批量更换部门
	public function batchUser_ug() {

//$this->data["u_product_id"] = "%";
		//$this->data["u_default_pg"] = "%";
		$data = $this->getById();

		$data['u_number'] = $this->data['u_number'];
		$data['u_name'] = $this->data['u_name'];
		$data['pg_name'] = $this->data['pg_name'];
		$data['u_ug_id'] = $this->data['u_ug_id'];
		$data['ug_name'] = $this->data['ug_name'];
//$data['do'] = 'move';

		$this->set($data);
		$result = $this->update_ug();

		if ($data['u_ug_id'] != 0) {
			if ($result !== false) {
				$log = DL("企业用户【%s】%s移动到部门【%s】成功");
				$log = sprintf($log
					, $data['u_name']
					, $data['u_number']
					, $data['ug_name']
					//, $data['u_ug_id']
				);

				$this->log($log, 3, 0);
			} else {
				$log = DL("企业用户【%s】%s移动到部门【%s】失败");
				$log = sprintf($log
					, $data['u_name']
					, $data['u_number']
					, $data['ug_name']
					//, $data['u_ug_id']
				);
				$this->log($log, 3, 1);
			}
		} else {
			if ($result !== false) {
				$log = DL("清除企业用户【%s】%s 部门成功");
				$log = sprintf($log
					, $data['u_name']
					, $data['u_number']
				);

				$this->log($log, 3, 0);
			} else {
				$log = DL("清除企业用户【%s】%s 部门失败");
				$log = sprintf($log
					, $data['u_name']
					, $data['u_number']
				);
				$this->log($log, 3, 1);
			}

		}
	}

	public function update_ug() {

		$sql = "UPDATE \"T_User\" SET u_ug_id = '{$this->data['u_ug_id']}' WHERE u_number = '{$this->data['u_number']}'";
		$sth = $this->pdo->query($sql);
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

	/**
	 *获得手机号用户信息
	 */
	public function getmobile($mb) {
		$sql = "SELECT * FROM \"T_User\" WHERE u_mobile_phone='$mb'";

		$sth = $this->pdo->query($sql);
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
	/**
	 *获得u_udid用户信息
	 */
	public function getudid($u_udid) {
		$sql = "SELECT * FROM \"T_User\" WHERE u_udid='$u_udid'";

		$sth = $this->pdo->query($sql);
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
	/**
	 *获得u_imsi用户信息
	 */
	public function getimsi($u_imsi) {
		$sql = "SELECT * FROM \"T_User\" WHERE u_imsi='$u_imsi'";

		$sth = $this->pdo->query($sql);
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
    /**
    *获得u_imei用户信息
    */
    public function getimei($u_imei) {
           $sql = "SELECT * FROM \"T_User\" WHERE u_imei='$u_imei'";
           $sql1 = "SELECT md_imei FROM \"T_MobileDevice\" WHERE md_imei='$u_imei'";

		$sth = $this->pdo->query($sql);
		$sth1 = $this->pdo->query($sql1);
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		$result1 = $sth1->fetchAll(PDO::FETCH_ASSOC);
		if($result==false&&$result1==false){
			return false;
		}else{
                                        if($result==true&&$result1==false){
                                                return false;
                                        }else{
                                                return true;
                                        }
		}
	}

    /**
     *获得u_meid用户信息
     */
    public function getmeid($u_meid) {
        $sql = "SELECT * FROM \"T_User\" WHERE u_meid='$u_meid'";
        $sql1 = "SELECT md_meid FROM \"T_MobileDevice\" WHERE md_meid='$u_meid'";

        $sth = $this->pdo->query($sql);
        $sth1 = $this->pdo->query($sql1);
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        $result1 = $sth1->fetchAll(PDO::FETCH_ASSOC);
        if($result==false&&$result1==false){
            return false;
        }else{
            if($result==true&&$result1==false){
                return false;
            }else{
                return true;
            }
        }
    }
        /**
         * 获得u_iccid用户信息
         * @param type $u_iccid
         * @return type
         */
    public function geticcid($u_iccid) {
            $sql = "SELECT * FROM \"T_User\" WHERE u_iccid='$u_iccid'";

		$sth = $this->pdo->query($sql);
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
/**
	 *获得u_iccid用户信息
	 */
	public function getmac($u_mac) {
		$sql = "SELECT * FROM \"T_User\" WHERE u_mac='$u_mac'";

		$sth = $this->pdo->query($sql);
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

// 保存当前用户

    public function save() {
            $this->vaildAutoConfig();
            $this->saveUserVerify();
            $res=$this->getByid();
            $this->data["old_u_info"] = $res;
            if($res['u_ug_id']!=$this->data['u_ug_id']){
                $this->enterprise->changeversion();
            }
            if(!isPhone($this->data['u_number'])){
                $str_num = (int) substr($this->data['u_number'], 6);
                if ($this->data["do"] != "edit") {
                    if ($str_num > 69999 ) {
                            $result = $this->msg(L('用户号码格式错误[填写手机号或者20000 至 69999之间的数字]'), -1);
                            return $result;
                    }
                }
            }
           if($this->data['u_alarm_inform_svp_num'] == $this->data['u_number'])
            {
                $this->data['u_alarm_inform_svp_num'] = $res['u_alarm_inform_svp_num'];
            }
            if ($this->data["u_level"] == "") {
                    $this->data["u_level"] = NULL;
            }
            if ($this->data["u_audio_mode"] == "") {
                    $this->data["u_audio_mode"] = NULL;
            }

            if ($this->data["u_default_pg"] == "") {
                    $this->data["u_default_pg"] = NULL;
            }

            if ($this->data["u_ug_id"] == "") {
                    $this->data["u_ug_id"] = NULL;
            }
            if ($this->data['u_only_show_my_grp'] === NULL || $this->data['u_only_show_my_grp'] === "") {
                    $this->data['u_only_show_my_grp'] = 0;
            }
            if($_SESSION['ident']=="VT"){
                if ($this->data["checkbox1"] == "") {
                        $this->data["checkbox1"] = NULL;
                }else{
                        $str= json_encode($this->data["checkbox1"]);
                        $this->data["u_p_function"]=$str;
                }
                if($this->data['isused']=="on"){
                    if($this->data['u_p_function_new']!=""&&$this->data['u_p_function_new']!="noselected"&&$this->data['u_p_function_new']!="%"){
                        $u_p_function_new=  explode(",", $this->data['u_p_function_new']);
                        $u_p_function_new= json_encode($u_p_function_new);
                        $this->data['u_p_function_new']=$u_p_function_new;
                        $this->data['u_p_function']=$res['u_p_function'];
                    }
                    if($this->data['u_p_function_new']=="%"){
                       $this->data['u_p_function_new']=$res['u_p_function_new'];
                   }
                }else{
                    $this->data['u_p_function_new']=NULL;
                }
            }else{
                    if($this->data['isused']=="on"){
                         $this->data["u_product_id"]=$res["u_product_id"];
                     }
                    if ($this->data["u_product_id"] == "") {
                            $this->data["u_product_id"] = NULL;
                    }
            }
            if($this->data['u_imei']==""){
                $this->data["u_terminal_type"] = NULL;
            }
            $u_default_pg = $this->data['u_default_pg'];
            $user_name = $this->hasUser($this->data['u_number']);
            $user_name = $user_name['u_name'];
            $result = array();

            switch ($this->data["u_sub_type"]) {
                    case 1:
                            $result = $this->saveUser();
                            break;
                    case 2:
                            $result = $this->saveShellUser();
                            break;
                    case 3:
                            $u_sub_type = $this->getById();
                            $u_sub_type = $u_sub_type['u_sub_type'];
                            if ($u_sub_type != 3 && $u_sub_type != "") {
                                $result = $this->msg(L("更改为GVS用户会丢失群组信息，是否更改")."？");
                            } else {
                                    $result = $this->saveGvsUser();
                            }
                            break;
            }
            if ($result['status'] == 0) {
                    $info = array(
                            'u_number' => DL('企业用户'),
                            'u_name' => DL('用户名'),
                            'u_passwd' => DL('密码'),
                            'u_mobile_phone' => DL('手机号'),
                            'u_default_pg_name' => DL('默认群组'),
                            'u_product_id' => DL('产品'),
                            'u_p_function' => DL('增值功能'),
                            'u_ug_id' => DL('部门'),
                            'u_sex' => DL('性别'),
                            'u_pic' => DL('头像'),
                            'u_position' => DL('职位'),
                            'u_terminal_type' => DL('终端类型'),
                            'u_terminal_model' => DL('机型'),
                            'u_imsi' => 'IMSI',
                            'u_imei' => 'IMEI',
                            'u_iccid' => 'ICCID',
                            'u_mac' => 'MAC',
                            'u_zm' => DL('蓝牙标识号'),
                            'u_e_id' => DL('企业ID'),
                            'u_alarm_inform_svp_num' => DL('一键告警号码'),
                            'u_mms_default_rec_num' => DL('拍传接收号码'),
                            'u_audio_rec' => DL('录像'),
                            'u_video_rec' => DL('录音'),
                            'u_auto_config' => DL('自动登录开关'),
                            'u_audio_mode' => DL('语音通话方式'),
                            'u_auto_run' => DL('开机启动'),
                            'u_checkup_grade' => DL('程序检查更新'),
                            'u_encrypt' => DL('信令加密'),
                            'u_gis_mode' => DL('GPS上报方式'),
                            'u_only_show_my_grp' => DL('只显示本部门'),
                            'u_remark' => DL('备注'),
                            'u_meid' => 'MEID'
                    );

                    if (count($result['arr']) != 0) {
                            $u_number = $this->data['u_number'];
                            $u_pg_number = $this->data['u_default_pg'];
                            $default_pg = $this->groups->getpg($u_pg_number);
                            foreach ($result['arr'] as $key => $value) {
                                    if ($info[$key] != null && $result['arr'][$key] != null) {
                                            if ($key == "u_default_pg" && $this->data['u_default_pg'] != null) {

                                                    $this->data['u_default_pg_name'] = $default_pg;
                                            }
                                            if ($key == "u_gis_mode" && $this->data['u_gis_mode'] != null) {
                                                    if ($this->data['u_gis_mode'] == 1) {
                                                            $this->data['u_gis_mode'] = DL("强制百度智能定位");
                                                    } else if ($this->data['u_gis_mode'] == 2) {
                                                            $this->data['u_gis_mode'] = DL("客户端设置");
                                                    } else if ($this->data['u_gis_mode'] == 3) {
                                                            $this->data['u_gis_mode'] = DL("强制百度GPS定位");
                                                    } else if ($this->data['u_gis_mode'] == 4) {
                                                            $this->data['u_gis_mode'] = DL("强制GPS定位");
                                                    }
                                            }
                                            if ($key == "u_audio_rec") {
                                                    if ($this->data['u_audio_rec'] == 0) {
                                                            $this->data['u_audio_rec'] = DL("停用");
                                                    } else {
                                                            $this->data['u_audio_rec'] = DL("启用");
                                                    }
                                            }
                                            if ($key == "u_only_show_my_grp") {
                                                    if ($this->data['u_only_show_my_grp'] == 0) {
                                                            $this->data['u_only_show_my_grp'] = DL("停用");
                                                    } else {
                                                            $this->data['u_only_show_my_grp'] = DL("启用");
                                                    }
                                            }
                                            if ($key == "u_video_rec") {
                                                    if ($this->data['u_video_rec'] == 0) {
                                                            $this->data['u_video_rec'] = DL("停用");
                                                    } else {
                                                            $this->data['u_video_rec'] = DL("启用");
                                                    }
                                            }
                                            if ($key == "u_auto_config") {
                                                    if ($this->data['u_auto_config'] == 0) {
                                                            $this->data['u_auto_config'] = DL("停用");
                                                    } else {
                                                            $this->data['u_auto_config'] = DL("启用");
                                                    }
                                            }
                                            if ($key == "u_auto_run") {
                                                    if ($this->data['u_auto_run'] == 0) {
                                                            $this->data['u_auto_run'] = DL("停用");
                                                    } else {
                                                            $this->data['u_auto_run'] = DL("启用");
                                                    }
                                            }
                                            if ($key == "u_checkup_grade") {
                                                    if ($this->data['u_checkup_grade'] == 0) {
                                                            $this->data['u_checkup_grade'] = DL("停用");
                                                    } else {
                                                            $this->data['u_checkup_grade'] = DL("启用");
                                                    }
                                            }
                                            if ($key == "u_encrypt") {
                                                    if ($this->data['u_encrypt'] == 0) {
                                                            $this->data['u_encrypt'] = DL("停用");
                                                    } else {
                                                            $this->data['u_encrypt'] = DL("启用");
                                                    }
                                            }
                                            if ($key == "u_audio_mode") {
                                                    if ($this->data['u_audio_mode'] == 0) {
                                                            $this->data['u_audio_mode'] = DL("停用");
                                                    } else {
                                                            $this->data['u_audio_mode'] = DL("启用");
                                                    }
                                            }
                                            if ($key == "u_ug_id") {
                                                    $ug_model = new usergroup($this->data);
                                                    $info1 = $ug_model->getselectinfo($this->data['u_ug_id']);
                                                    $this->data['u_ug_id'] = $info1[0]['ug_name'];
                                            }
                                            if ($key == "u_sex") {
                                                    if ($this->data['u_sex'] == "F") {
                                                            $this->data['u_sex'] = DL("女");
                                                    } else {
                                                            $this->data['u_sex'] = DL("男");
                                                    }
                                            }
                                             if ($key == "u_p_function") {

                                            $this->data["u_p_function"]=getEListByfunc($this->data['u_p_function']);
                                        }
//                                    if($key == "u_name"){
                                            //                                        $this->data['u_name'] = $user_name;
                                            //                                    }

                                            $log = DL("企业用户") . "【" . $user_name . "】(" . $u_number . ")" . $info[$key] . "【" . $this->data[$key] . "】";
                                            $log .= DL("成功");
//$log = '企业用户【%s】密码【%s】默认群组【%s】产品【%s】部门【%s】性别【%s】头像【%s】职位【%s】终端类型【%s】机型【%s】IMSI【%s】IMEI【%s】ICCID【%s】MAC【%s】蓝牙标识号【%s】 企业ID【%s】';

                                            if ($this->data["do"] == "edit") {
                                                    $log = DL('修改')." " . $log;
                                            } else {
                                                    $log = DL('添加') ." ". $log;
                                            }
                                            $this->log($log, 1, 0);
                                    }
                            }
                    }
            }else{
                 return $result;
            }
            $default_pg = $this->groups->getpg($this->data['u_default_pg']);
            if ($u_default_pg != ""&&$this->data['submit_type']!='batch') {
                    $data = array();
                    $data['e_id'] = $this->data['e_id'];
                    $data['pm_number'] = $this->data['u_number'];
                    $data['pm_level'] = $this->data['pm_level'];
                    $data['pm_hangup'] = $this->data['pm_hangup'];
                    $data['pm_pgname'] = $default_pg;
                    $data['pm_pgnumber'] = $u_default_pg;
                    $data['u_name'] = $this->data['u_name'];
                    $data['pg_name'] = $this->data['pg_name'];
                    $data['do'] = $this->data['do'];
                    $pttmember = new pttmember($data);
                    try
                    {
                            $pttmember->save();
                    } catch (Exception $ex) {

                    }
            }
            return $result;
    }
   public function change_function(){
       if($this->data['isused']=="on"){
       $sql=<<<echo
               UPDATE "T_User" SET u_p_function_new='{$this->data['u_p_function_new']}' WHERE u_number='{$this->data['u_number']}' AND u_sub_type=1
echo;
       }else{
            $sql=<<<echo
               UPDATE "T_User" SET u_p_function='{$this->data['u_p_function_new']}',u_p_function_new=NULL WHERE u_number='{$this->data['u_number']}' AND u_sub_type=1
echo;
       }
       $sth=$this->pdo->query($sql);
   }
   public function change_all(){

            $sql=<<<echo
               UPDATE "T_User" SET u_gis_mode='{$this->data['u_gis_mode']}',u_mms_default_rec_num='{$this->data['u_mms_default_rec_num']}',u_alarm_inform_svp_num='{$this->data['u_alarm_inform_svp_num']}',u_attr_type='{$this->data['u_attr_type']}' WHERE u_number='{$this->data['u_number']}' AND u_sub_type=1
echo;
       
       $sth=$this->pdo->query($sql);
   }
    public function saveUser() {

            $edit = false;
            if ($this->data["do"] == "edit") {
                    $edit = true;
            } else {
                    if (strlen(trim($this->data["u_number"])) < 11) {
                            $this->data["u_number"] = $this->data["e_id"] . trim($this->data["u_number"]);
                    }
            }
            if (isPhone($this->data["u_number"])) {
                    if ($this->data['u_mobile_phone'] == '') {
                            $this->data["u_mobile_phone"] = $this->data["u_number"];
                    }
            }

            $this->data['u_status'] = 1;
            $this->data['u_sub_type'] = 1;
            $this->data['u_level'] = 5;
            $this->data['u_allow_login'] = 0;
            $this->data['u_gvs_user'] = 0;
            $this->data['u_pg_number'] = '';

            try
            {
            $user_save_info=  $this->data;
                    if ($this->data["do"] != "edit") {
                    $this->data['u_audio_rec'] = 1;
                    $this->data['u_video_rec'] = 1;
                            $this->insert();
                            $this -> changeiccid();
                        //新增用户是否绑定终端
                        $enter_info=$this->enterprise->getByid();
                            if($this->data['imei_stat']==5){//新增用户时 imei状态码为5是 视为需绑定终端
                                    //绑定终端
                                    $data['md_imei']=$this->data['u_imei'];
                                    $data['md_meid']=$this->data['md_meid'];
                                    $data['md_binding_user']=$this->data['u_number'];
                                    $data['md_ent_id']=$this->data['e_id'];
                                    $data['md_gis_mode']=$this->data['u_gis_mode'];
                                    $this->term->set($data);
                                $info=$this->term->terminalBound();
                                $info_md=$this->term->getselect_list();
                                $info_gprs=$this->gprs->getByid();
                                //绑定终端成功则 生成记录
                                if($info['status']==0){
                                        //生成一条终端纪录
                                            $this->term->set_term_history($this->getById_history(),"start");
                                            /**
                                             * 新增终端用户个数
                                             */
                                            if($this->data['u_attr_type']!="1"){
                                                $this->add_commercial_term($this->data['e_id'], 1);
                                            }else{
                                                $this->add_test_term($this->data['e_id'], 1);
                            }
                                            $this->add_terminal($this->data['e_id'], 1);
                                    }
                        }else{//新增用户时 不绑定终端的（IMEI不是库里的）终端不记录
                        }
                            //用户记录新增用户个数(可优化)
                            $this->sum_add_users($this->data['e_id'], 1);
                            $this->add_users($this->data['e_id'], 1);
                        //记录用户历史记录
                            $user_info=$this->getById_history();
                            $this->user_history($user_info);
                } else {//编辑用户
                        /**
                         * ①如果用户保存时用户名称改变或者用户状态改变 则需要记录用户历史操作
                         */
                        $user_info=$this->getById_history();//获得 该用户在保存前的信息
                            $changeinfo = $this->update();
                            $this -> changeiccid();
                            if($user_info['u_name']!=$this->data['u_name']||$user_info['u_active_state']!=$this->data['u_active_state']||$user_info['u_imei']!=$this->data['u_imei']||$user_info['u_iccid']!=$this->data['u_iccid'] || $user_info['u_attr_type']!=$this->data['u_attr_type'] ||$user_info['u_meid']!=$this->data['u_meid']){
                                    /**
                                     * 终端绑定&历史纪录  START
                                     */
                                    if($user_info['u_imei'] != $this->data['u_imei']){
                                            $res_old=check_md_imei($user_info['u_imei'],$user_info['u_e_id']);
                                            $data['md_imei']=$this->data['u_imei'];
                                            $data['md_binding_user']=$this->data['u_number'];
                                            $data['md_ent_id']=$this->data['e_id'];
                                            $data['md_gis_mode']=$this->data['u_gis_mode'];
                                            $this->term->set($data);
                                            $info_md=$this->term->getselect_list();//获得 当前用户IMEI的终端信息
                                            if($res_old=="Binding"){//该用户已绑定终端 则解绑
                                                    //解绑终端
                                                    $data['md_imei']=$user_info['u_imei'];
                                                    $this->term->set($data);
                                                    $info=$this->term->releaseBound();
                                                    if($info['status']==0){
                                                        //解绑终端成功则 生成终端记录
                                                        $after_info=$this->getById_history();
                                                        $this->gprs->input_gprs_history($after_info);
                                                        $after_info['md_imei']=$user_info['u_imei'];
                                                        $this->term->set_term_history($after_info,"unbind");
                                                        /**
                                                         * 解绑终端需要删除de 个数
                                                         */
                                                        if(strtotime($user_info['md_binding_time'])==strtotime(date('Y-m-d',time()))){
                                                            if($this->data['u_attr_type']!="1"){
                                                                $this->add_commercial_term($this->data['e_id'], -1);
                                                            }else{
                                                                $this->add_test_term($this->data['e_id'], -1);
                                                             }
                                                            $this->add_terminal($this->data['e_id'], -1);
                                                        }
                                                    }
                                        }
                                            if($this->data['u_imei']!=""){//新增用户时 imei状态码为5是 视为需绑定终端
                                    //绑定终端
                                    $data['md_imei']=$this->data['u_imei'];
                                    $data['md_binding_user']=$this->data['u_number'];
                                    $data['md_ent_id']=$this->data['e_id'];
                                    $data['md_gis_mode']=$this->data['u_gis_mode'];
                                    $this->term->set($data);
                                                $info=$this->term->terminalBound();
                                                //绑定终端成功则 生成终端记录
                                                if($info['status']==0){
                                                        //生成一条终端纪录
                                                        $this->term->set_term_history($this->getById_history(),"start");
                                                        if($this->data['u_attr_type']!="1"){
                                                            $this->add_commercial_term($this->data['e_id'], 1);
                                                        }else{
                                                            $this->add_test_term($this->data['e_id'], 1);
                                                        }
                                                        $this->add_terminal($this->data['e_id'], 1);
                                                }
                                                if($user_info['u_iccid']==$this->data['u_iccid']&&$user_info['u_imei']!=$this->data['u_imei']){
                                                    $this->gprs->input_gprs_history($this->getById_history());
                            }
//                                                    if($this->data['u_iccid']!=""&&$this->data['md_type']!=""){
//                                                        $this->gprs->input_gprs_history($this->getById_history());
//                                                    }
                                                }
                                    }else if($user_info['u_name']!=$this->data['u_name']){//终端未改变 用户信息改变 
                                        $user_info=$this->getById_history();
                                        $this->term->set_term_history($user_info);
                                        $this->gprs->input_gprs_history($user_info);
                                }
                                $user_info_after=$this->getById_history();//获得 该用户在保存后的信息(包括 终端的一系列操作)
                             $this->user_history($user_info_after);
                                /**
                                 * 用户历史纪录生成  END
                                 */
                        }
                }
                /**
                 * 终端绑定&历史纪录  END
                 */
            } catch (Exception $ex) {
                    if ($ex->getCode() == 23505) {
//				$log = DL('添加企业用户失败，原因：用户号码重复 企业ID【%s】用户ID【%s】');
                            $log = DL('添加企业用户失败，原因：用户号码重复');
                            $log = sprintf($log
                                    , $this->data['e_id']
                                    , $this->data["u_number"]
                            );
                            $this->log($log, 1, 0);
//				$msg["msg"] = L('添加企业用户失败，原因：用户号码重复 企业ID【%s】用户ID【%s】');
                            $msg["msg"] = L('添加企业用户失败，原因：用户号码重复');
                            $msg["msg"] = sprintf($msg["msg"]
                                    , $this->data['e_id']
                                    , $this->data["u_number"]
                            );
                    } else {
                            $log = DL('添加企业用户失败 企业ID【%s】用户ID【%s】失败原因：') . $ex->getMessage();
                            $log = sprintf($log
                                    , $this->data['e_id']
                                    , $this->data["u_number"]
                            );
                            $this->log($log, 1, 0);
                            $msg["msg"] = L('添加企业用户失败 企业ID【%s】用户ID【%s】失败原因：') . $ex->getMessage();
                            $msg["msg"] = sprintf($msg["msg"]
                                    , $this->data['e_id']
                                    , $this->data["u_number"]
                            );
                    }
                    $msg["status"] = -1;
                                                return $msg;
            }
        $this->set($user_save_info);
            if ($this->data["do"] != "edit") {
                    $log = DL("添加企业用户【%s】%s成功");
                    $log = sprintf($log
                            , $this->data['u_name']
                            , $this->data["u_number"]
                    );
                    $this->log($log, 1, 0);
                    $msg["msg"] = L("添加企业用户【%s】%s成功");
                    $msg["msg"] = sprintf($msg["msg"]
                            , $this->data['u_name']
                            , $this->data["u_number"]
                    );
                    $msg["status"] = 0;
                    return $msg;
            }
            if ($edit) {
                    return $this->msg1(L("企业用户修改成功【手机用户】"), 0, $changeinfo);
            } else {
                    return $this->msg(L("企业用户添加成功【手机用户】"));
            }
    }

    //添加或编辑企业用户时iccid 的对应改变
    public function changeiccid(){
            $u_info = $this->data['old_u_info'];
            $u_number = $this->data['u_number'];
            $info = $this->gprs->getgprs($this->data['u_iccid']);
            $oldinfo = $this->gprs->getgprs($u_info['u_iccid']);
            $e_id = $this->data['e_id'];
            if ($this->data["do"] != "edit") {
                    if($this->data['u_iccid'] != ''){
                        if($info){
                                if($info['g_binding']!='1'){
                                        $this->gprs->editgprs($this->data['u_iccid'],'1',$u_number,'1',$e_id);//流量卡绑定
                                        //$this->data["old_u_info"] = $this->getById();
                                        $info=$this->getById_history();
                                        try {
                                            $this->gprs->gprsBound_history($info);
                                            //新增流量卡个数
                                            if($this->data['u_attr_type']=="0"){//商用个数
                                                $this->add_commercial_gprs($e_id, 1);
                                            }else{//测试个数
                                                $this->add_test_gprs($e_id, 1);
                                            }
                                            $this->add_gprs($e_id, 1);
                                        } catch (Exception $exc) {
                                            echo $exc->getTraceAsString();
                                        }
                                }
                        }
                    }
            }else{
                    if(trim($u_info['u_iccid']) != trim($this->data['u_iccid'])){
                            if($u_info['u_iccid'] != '' && $this->data['u_iccid'] != ''){
                                    if($info['g_binding']!='1'){
                                            $this->gprs->editgprs($this->data['u_iccid'],'1',$u_number,'1',$e_id);//流量卡绑定
                                            /**
                                             * 流量卡绑定 默认启用
                                             */
                                            $info=$this->getById_history();
                                            $this->gprs->gprsBound_history($info);
                                            $this->term->set_term_history($info);
                                            //解绑历史记录
                                            $this->gprs->editgprs($u_info['u_iccid'],'0','','2','');//流量卡解绑
                                            $info_un=$this->getById_history();
                                            $info_un['u_iccid']=$u_info['u_iccid'];
                                            $this->gprs->gprsreleaseBound_history($info_un);
                                            
                                    }
                            }elseif($u_info['u_iccid'] == '' && $this->data['u_iccid'] != ''){
                                    if($info['g_binding']!='1'){
                                            $this->gprs->editgprs($this->data['u_iccid'],'1',$u_number,'1',$e_id);//流量卡绑定
                                            $info=$this->getById_history();
                                            $this->gprs->gprsBound_history($info);
                                            if($this->data['u_attr_type']=="0"){//商用个数
                                                $this->add_commercial_gprs($e_id, 1);
                                            }else{//测试个数
                                                $this->add_test_gprs($e_id, 1);
                                            }
                                            $this->add_gprs($e_id, 1);
                                    }
                                if($u_info['u_imei']==$this->data['u_imei']){
                                $this->term->set_term_history($this->getById_history());
                                }
                            }elseif($u_info['u_iccid'] != '' && $this->data['u_iccid'] == ''){
                                    $this->gprs->editgprs($u_info['u_iccid'],'0','','2','');//流量卡解绑
                                     if(strtotime($oldinfo['g_binding_time'])==strtotime(date('Y-m-d',time()))){
                                        if($this->data['u_attr_type']=="0"){//商用个数
                                            $this->add_commercial_gprs($e_id, -1);
                                        }else{//测试个数
                                            $this->add_test_gprs($e_id, -1);
                                        }
                                        $this->add_gprs($e_id, -1);
                                     }
                                    $info=$this->getById_history();
                            $info['g_iccid']=$u_info['u_iccid'];
                                    $this->gprs->gprsreleaseBound_history($info);
                            $info['g_iccid']=$this->data['u_iccid'];
                                    $this->term->set_term_history($info);
                            }
                    }
            }
    }

    public function saveShellUser() {
            $edit = false;
            if ($this->data["do"] == "edit") {
                    $edit = true;
            } else {
                    if (strlen($this->data["u_number"]) < 11) {
                            $this->data["u_number"] = $this->data["e_id"] . $this->data["u_number"];
                    }
            }

// user data
            $this->data['u_status'] = 1;
            $this->data['u_sub_type'] = 2;
            $this->data['u_level'] = 5;
            $this->data['u_allow_login'] = 1;
            $this->data['u_gvs_user'] = 0;
            $this->data['u_product_id'] = '';
            $this->data['u_alarm_inform_svp_num'] = '';
            $this->data['u_mms_default_rec_num'] = '';
            $this->data['u_auto_config'] = 0;
            $this->data['u_audio_mode'] = 0;
            $this->data['u_gis_mode'] = 0;
            $this->data['u_auto_run'] = 0;
            $this->data['u_checkup_grade'] = 1;
            $this->data['u_encrypt'] = 0;
            $this->data['u_pic'] = '';
            $this->data['u_sex'] = 'M';
            $this->data['u_position'] = '';
            $this->data['u_pg_number'] = '';
            //$this->data['u_mobile_phone'] = '';
            $this->data['u_terminal_type'] = '';
            $this->data['u_terminal_model'] = '';
            $this->data['u_zm'] = '';
            $this->data['u_imsi'] = '';
            $this->data['u_imei'] = '';
            $this->data['u_iccid'] = '';
            $this->data['u_mac'] = '';
            $this->data['u_udid'] = '';
            $this->data['u_p_function'] = '';
            $this->data['u_meid'] = '';

            try
            {
                    if ($this->data["do"] != "edit") {
                            $this->data['u_audio_rec'] = 1;
                            $this->data['u_video_rec'] = 1;
                            $this->insert();
                            $this->user_history($this->getById_history());   
                            //用户记录新增用户个数(可优化)
                            $this->sum_add_users($this->data['e_id'], 1);
                            $this->add_users($this->data['e_id'], 1);
                    } else {
                            $user_info=$this->getById_history();
                            $changeinfo = $this->update();
                            if($this->data['u_name']!=$user_info['u_name']||$this->data['u_active_state']!=$user_info['u_active_state'] || $this->data['u_attr_type']!=$user_info['u_attr_type']){
                                   $this->user_history($this->getById_history());
                            }
                    }
            } catch (Exception $ex) {
                    if ($ex->getCode() == 23505) {
                            $log = DL('添加企业用户失败，原因：用户号码重复 企业ID【%s】用户ID【%s】');
                            $log = sprintf($log
                                    , $this->data['e_id']
                                    , $this->data["u_number"]
                            );
                            $this->log($log, db::USER, db::INFO);
                            $msg = L('添加企业用户失败，原因：用户号码重复 企业ID【%s】用户ID【%s】');
                            $msg = sprintf($msg
                                    , $this->data['e_id']
                                    , $this->data["u_number"]
                            );
                            return $this->msg($msg, -1);
                    }
                    $log = DL('添加企业用户失败 企业ID【%s】用户ID【%s】失败原因：') . $ex->getMessage();
                    $log = sprintf($log
                            , $this->data['e_id']
                            , $this->data["u_number"]
                    );
                    $this->log($log, db::USER, db::ERROR);
                    $msg = L('添加企业用户失败 企业ID【%s】用户ID【%s】失败原因：') . $ex->getMessage();
                    $msg = sprintf($msg
                            , $this->data['e_id']
                            , $this->data["u_number"]
                    );
                    return $this->msg($msg, -1);
            }

            if ($edit) {
                    return $this->msg1(L("企业用户修改成功【调度台用户】"), 0, $changeinfo);
            } else {
                    return $this->msg(L("企业用户添加成功【调度台用户】"), 0);
            }
    }

    public function saveGvsUser() {
            $edit = false;
            if ($this->data["do"] == "edit") {
                    $edit = true;
            } else {
                    if (strlen($this->data["u_number"]) < 11) {
                            $this->data["u_number"] = $this->data["e_id"] . $this->data["u_number"];
                    }
            }

            if ($this->data["u_ug_id"] == "") {
                    $this->data["u_ug_id"] = NULL;
            }
// user data
            $this->data['u_status'] = 1;
            $this->data['u_sub_type'] = 3;
            $this->data['u_level'] = 5;
            $this->data['u_allow_login'] = 0;
            $this->data['u_gvs_user'] = 1;
            $this->data['u_only_show_my_grp'] = 0;
            $this->data['u_product_id'] = '';
            $this->data['u_alarm_inform_svp_num'] = '';
            $this->data['u_mms_default_rec_num'] = '';
            $this->data['u_auto_config'] = 0;
            $this->data['u_audio_mode'] = 0;
            $this->data['u_gis_mode'] = 0;
            $this->data['u_auto_run'] = 0;
            $this->data['u_checkup_grade'] = 1;
            $this->data['u_encrypt'] = 0;
            $this->data['u_pic'] = '';
            $this->data['u_sex'] = 'M';
            $this->data['u_position'] = '';
            $this->data['u_default_pg'] = '';
            $this->data['u_pg_number'] = '';
            //$this->data['u_mobile_phone'] = '';
            $this->data['u_terminal_type'] = '';
            $this->data['u_terminal_model'] = '';
            $this->data['u_zm'] = '';
            $this->data['u_imsi'] = '';
            $this->data['u_imei'] = '';
            $this->data['u_iccid'] = '';
            $this->data['u_mac'] = '';
            $this->data['u_udid'] = '';
            $this->data['u_p_function'] = '';
            $this->data['u_meid'] = '';

            try
            {
                    if ($this->data["do"] != "edit") {
                            $this->data['u_audio_rec'] = 1;
                            $this->data['u_video_rec'] = 1;
                            $this->insert();
                            $this->user_history($this->getById_history());
                            //用户记录新增用户个数(可优化)
                            $this->sum_add_users($this->data['e_id'], 1);
                            $this->add_users($this->data['e_id'], 1);
                    } else {
                            $user_info=$this->getById_history();
                            $changeinfo = $this->update();
                            if($this->data['u_name']!=$user_info['u_name']||$this->data['u_active_state']!=$user_info['u_active_state'] || $this->data['u_attr_type']!=$user_info['u_attr_type']){
                                   $this->user_history($this->getById_history());
                            }
                    }
            } catch (Exception $ex) {
                    if ($ex->getCode() == 23505) {
                            $log = DL('添加企业用户失败，原因：用户号码重复 企业ID【%s】用户ID【%s】');
                            $log = sprintf($log
                                    , $this->data['e_id']
                                    , $this->data["u_number"]
                            );
                            $this->log($log, db::USER, db::INFO);
                            $msg = L('添加企业用户失败，原因：用户号码重复 企业ID【%s】用户ID【%s】');
                            $msg = sprintf($msg
                                    , $this->data['e_id']
                                    , $this->data["u_number"]
                            );
                            return $this->msg($msg, -1);
                    }
                    $log = DL('添加企业用户失败 企业ID【%s】用户ID【%s】失败原因：') . $ex->getMessage();
                    $log = sprintf($log
                            , $this->data['e_id']
                            , $this->data["u_number"]
                    );
                    $this->log($log, 1, 1);
                    $msg = L('添加企业用户失败 企业ID【%s】用户ID【%s】失败原因：') . $ex->getMessage();
                    $msg = sprintf($msg
                            , $this->data['e_id']
                            , $this->data["u_number"]
                    );
                    return $this->msg($msg, -1);
            }

            if ($edit) {
                    return $this->msg1(L("企业用户修改成功【GVS用户】"), 0, $changeinfo);
            } else {
                    return $this->msg(L("企业用户添加成功【GVS用户】"));
            }
    }

	public function createUsersVerify() {
		if ($this->data['u_auto_number'] == 0) {
			throw new Exception(L('至少大于0'), -1);
		}

		$list = array();
		$u_auto_pre = $this->data['u_auto_pre'];
		$u_auto_num = $this->data['u_auto_pre'] + $this->data['u_auto_number'];
		for ($i = $u_auto_pre; $i < $u_auto_num; $i++) {
			if(!isPhone($u_auto_pre)){
				$list[] = $this->data["e_id"] . $i;
			}else{
				$list[] = $i;
			}
			
		}

		$list_holders = implode(",", array_fill(0, count($list), '?'));

		$sql = '
                        SELECT
                                u_number
                        FROM
                                ":tablename"
                        WHERE
                                u_number IN (:list)';
		$sql = str_replace(':tablename', $this->getTableName(), $sql);
		$sql = str_replace(':list', $list_holders, $sql);
		$sth = $this->pdo->prepare($sql);
		$sth->execute($list);

		$result = $sth->fetchAll();
		$count = count($result);
		$str_number = array();
		foreach ($result as $value) {
			$str_number['num'][] .= $value['u_number'];
		}
		$str_number['count'] = $count;
		return $str_number;
		/* if ( $result['count'] > 0 )
	{
	throw new Exception ( '用户号码存在重复' , -1 );
	} */
	}

	/**
	 * 获取所有用户ID
	 * 暂停使用
	 */
	public function getallid($u_sub_type) {
		$sql = '
                        SELECT
                                u_number
                        FROM
                                ":tablename"
                        WHERE
                                u_sub_type= :list
                        AND
                                u_e_id=:e_id';
		$sql = str_replace(':tablename', $this->getTableName(), $sql);
		$sql = str_replace(':list', "$u_type", $sql);
		$sql = str_replace(':e_id', $this->data['e_id'], $sql);
		$sth = $this->pdo->prepare($sql);
		$sth->execute($list);
		$result = $sth->fetchAll();
		var_dump($result);
		die;
	}

	/*
	public function getnorepeat ( $num , $pre_number )
	{
	$result = $this->createUsersVerify ();
	$exists = $result['num'];
	$list = array ();
	$norepeat = array ();
	for ( $i = $pre_number; $i < $num; $i ++ )
	{
	$list[] = $i;
	}
	foreach ( $list as $value )
	{
	foreach ( $exists as $val )
	{
	if ( $value != $val )
	{
	$norepeat[] = $value;
	}
	}
	}

	var_dump ( $norepeat );
	die;
	$merger = array_merge ( $norepeat , $list );
	var_dump ( $exists );
	var_dump ( $list );
	var_dump ( $merger );
	die;
	}
	 */

//生成8为数字+字母随机密码
    function random_str($length)
    {   
        //英文数组
        $enArr = range('a', 'z');
        //数组数组
        $numArr = range(0, 9);
        //生成一个包含 英文字母, 数字 的数组
        $arr = array_merge(range(0, 9), range('a', 'z') , range('A', 'Z'));
     
        $str = '';
        $arr_len = count($arr);

        for ($i = 0; $i < $length; $i++)
        {
            $rand = mt_rand(0, $arr_len-1);
            $enRand = mt_rand(0, 25);
            $numRand = mt_rand(0, 9);
            //防止生成的全部是数字或者字母,在中间固定位置插入随机的一个字母和数字
            if($i==2){
                $str.=$enArr[$enRand];
            }elseif($i==1 || $i==3 || $i==5){
                $str.=$numArr[$numRand];
            }else{
                $str.=$arr[$rand];
            }
        }
        return $str;
    }

//批量生成多个用户
    public function createUsers() {
            $doc = <<<DOC
<!DOCTYPE html>
<html>
            <head>
                    <meta charset="UTF-8">
            <head>
<body>
DOC;

            print $doc;
            $goto = '?m=enterprise&a=users&e_id=' . $_REQUEST['e_id'];
            try
            {
//$this->getallid ( $this->data['u_sub_type'] );
                    $result = $this->createUsersVerify();
            } catch (Exception $ex) {
                    $this->log(DL("批量用户生成失败:") . $ex->getMessage(), 1, 2);
                    print('<script>parent.notice("' . $ex->getMessage() . '","' . $goto . '");</script>');
            }

            $u_auto_pre = $this->data['u_auto_pre'];

            $u_auto_num = $this->data['u_auto_pre'] + $this->data['u_auto_number'];
            if ($u_auto_num >= 70000 && !isPhone($u_auto_num)) {
                    print('<script>parent.notice("' . L('操作失败，所创建用户号超过69999') . '","' . $goto . '");</script>');
                    exit();
            }
            for ($i = $u_auto_pre; $i < $u_auto_num; $i++) {
                    $this->data["u_number"] = $i;
                    if ($this->data["u_auto_pwd"] == 0) {
                            /*$passwd = str_replace("0", "2", uniqid());
                            $passwd = str_replace("1", "2", $passwd);
                            $passwd = str_replace("l", "m", $passwd);
                            $passwd = str_replace("o", "p", $passwd);
                            $passwd = str_replace("O", "p", $passwd);*/
                            $this->data["u_passwd"] = $this->random_str(8);
                    } else {
                            $this->data["u_passwd"] = $this->data["u_number"];
                    }
                    if (isPhone($this->data["u_number"])) {
                            $this->data["u_mobile_phone"] = $i;
                    }
                    $this->data["u_sex"] = 'M';
//$this->data["auto_create"] = TRUE;
                    if ($this->data['u_auto_config'] == 0) {
                            $this->data["u_auto_run"] = null;
                            $this->data["u_checkup_grade"] = null;
                            $this->data["u_encrypt"] = null;
                            $this->data["u_audio_mode"] = null;
                            //$this->data["u_gis_mode"] = null;
                    }
                    $this->save();
                    $this->data['u_name'] = "";
                    print('<script>parent.next();</script>');
                    ob_flush();
                    flush();
            }
            $diff_count = $this->data['u_auto_number'] - $result['count'];
            if ($result['num'] != "" && $diff_count != 0) {
                    $info = L('操作结束,%s个号码重复,成功创建%s个用户');
                    $info = sprintf($info
                            , $result['count']
                            , $diff_count
                    );
                    print('<script>parent.notice("' . $info . '","' . $goto . '");</script>');
            } else if($diff_count == 0) {
                    print('<script>parent.notice("' . L('操作失败，没有用户创建成功') . '","' . $goto . '");</script>');
            } else {
                    print('<script>parent.notice("' . L('操作成功') . '","' . $goto . '");</script>');
            }
            print('</body></html>');
    }
                /**
             *获得当前登录用户所有的企业
             */
                  public function getallep(){
                      $ep=new enterprise($_SESSION['ag']);
                      $result=$ep->getepList();
                      foreach ($result as $value) {
                          $str .=$value['e_id'].',';
                      }
                      //var_dump(trim($str,","));die;
                      if(count($result)==0){
                          $str='000000';
                      }
                      //var_dump(count($result));die;
                      return trim($str,",");
                  }

                  public function getUserList($limit = "") {
		$sql = '
                        SELECT
                               *
                        FROM
                                "T_User"
                        LEFT JOIN "T_Product" ON u_product_id = p_id
                        ';
		$sql = $sql . $this->getWhere(false);
		$sql .= "ORDER BY u_number";
		if ($limit != "") {
			$sql = $sql . $limit;
		}
		$stat = $this->pdo->query($sql);
		$result = $stat->fetchAll();
		return $result;
	}

	/**
	 * 获得全部用户信息
	 */
	public function getalluser() {
		$e_id = $this->data["e_id"];
		$sql = "
                        SELECT
                                u_number,
                                u_passwd,
                                u_status,
                                u_type,
                                u_sub_type,
                                u_level,
                                u_allow_login,
                                u_gvs_user,
                                u_only_show_my_grp,
                                u_product_id,
                                u_audio_rec,
                                u_video_rec,
                                u_alarm_inform_svp_num,
                                u_mms_default_rec_num,
                                u_auto_config,
                                u_audio_mode,
                                u_gis_mode,
                                u_auto_run,
                                u_checkup_grade,
                                u_encrypt,
                                u_name,
                                u_pic,
                                u_sex,
                                u_position,
                                u_ug_id,
                                u_default_pg,
                                u_pg_number,
                                u_mobile_phone,
                                u_terminal_type,
                                u_terminal_model,
                                u_zm,
                                u_imsi,
                                u_imei,
                                u_meid,
                                u_iccid,
                                u_mac,
                                u_udid,
                                u_p_function,
				u_remark,
                                p_id,
                                p_name,
                                pg_name,
                                ug_name,
                                ug_weight,
                                ug_parent_id,
                                ug_path

        FROM
        \"T_User\"
                        LEFT JOIN \"T_Product\" ON u_product_id = p_id
                        LEFT JOIN \"T_PttGroup_{$e_id}\" ON u_default_pg = pg_number
                        LEFT JOIN \"T_UserGroup_{$e_id}\" ON u_ug_id = ug_id
                        ";
		$sql = $sql . $this->getwhere_alluser(true);
		$stat = $this->pdo->query($sql);
		$result = $stat->fetchAll();
		return $result;
	}

	public function getList_cust($limit = "") {
		$e_id = $this->data["e_id"];
		$sql = "
                        SELECT
                                u_number,
                                u_passwd,
                                u_status,
                                u_type,
                                u_sub_type,
                                u_level,
                                u_allow_login,
                                u_gvs_user,
                                u_only_show_my_grp,
                                u_product_id,
                                u_audio_rec,
                                u_video_rec,
                                u_alarm_inform_svp_num,
                                u_mms_default_rec_num,
                                u_auto_config,
                                u_audio_mode,
                                u_gis_mode,
                                u_auto_run,
                                u_checkup_grade,
                                u_encrypt,
                                u_name,
                                u_pic,
                                u_sex,
                                u_position,
                                u_ug_id,
                                u_default_pg,
                                u_pg_number,
                                u_mobile_phone,
                                u_terminal_type,
                                u_terminal_model,
                                u_zm,
                                u_imsi,
                                u_imei,
                                u_meid,
                                u_iccid,
                                u_mac,
                                u_udid,
                                u_e_id,
                                u_p_function,
				u_remark,
                                p_id,
                                p_name,
                                p_area,
                                ug_name,
                                ug_weight,
                                ug_path

                        FROM
                               \"T_User\"
                        LEFT JOIN \"T_Product\" ON u_product_id = p_id
                        LEFT JOIN \"T_UserGroup_{$e_id}\" ON u_ug_id = ug_id
                        ";

		/*
		 * ,
		p_name,
		ug_name,
		pg_name
		 */
//LEFT JOIN \"T_UserGroup_$e_id\" ON u_ug_id = ug_id
		//LEFT JOIN \"T_PttGroup_$e_id\" ON u_default_pg = pg_number
		$sql = $sql . $this->getwhere(true);
		$sql = $sql . $limit;
		$stat = $this->pdo->query($sql);
		$result = $stat->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
        /**
         * 获取企业用户列表
         * @return type
         */
        public function getList_Record(){
            $er_id = $this->data["er_id"];
            $sql = "SELECT *,("
                    . "SELECT COUNT(ur_number) FROM \"T_User_Record_{$this->data['start']}\" WHERE ur_e_id=$er_id AND ur_attr_type='0'  AND ur_charg_ratio_amp!='1'"
                            . ") AS count "
                            . "FROM \"T_User_Record_{$this->data['start']}\" "
                            . "WHERE ur_e_id=$er_id AND ur_attr_type='0'";
            $order=" ORDER BY ur_charg_ratio_amp,ur_sub_type,ur_name";
            $sql.=$order;
            $stat = $this->pdo->query($sql);
            $result = $stat->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
	public function getList($limit = "") {
		$e_id = $this->data["e_id"];
		$sql = "
                        SELECT
                                u_number,
                                u_passwd,
                                u_status,
                                u_type,
                                u_sub_type,
                                u_level,
                                u_allow_login,
                                u_gvs_user,
                                u_only_show_my_grp,
                                u_product_id,
                                u_audio_rec,
                                u_video_rec,
                                u_alarm_inform_svp_num,
                                u_mms_default_rec_num,
                                u_auto_config,
                                u_audio_mode,
                                u_gis_mode,
                                u_auto_run,
                                u_checkup_grade,
                                u_encrypt,
                                u_name,
                                u_pic,
                                u_sex,
                                u_position,
                                u_ug_id,
                                u_default_pg,
                                u_pg_number,
                                u_mobile_phone,
                                u_terminal_type,
                                u_terminal_model,
                                u_zm,
                                u_imsi,
                                u_imei,
                                u_meid,
                                u_iccid,
                                u_mac,
                                u_udid,
                                u_e_id,
                                u_p_function,
                                u_purch_date,
                                u_terminal_number,
				u_remark,
                                p_id,
                                p_name,
                                p_area,
                                pg_name,
                                ug_name,
                                ug_weight,
                                ug_path

                        FROM
                               \"T_User\"
                        LEFT JOIN \"T_Product\" ON u_product_id = p_id
                        LEFT JOIN \"T_PttGroup_{$e_id}\" ON u_default_pg = pg_number
                        LEFT JOIN \"T_UserGroup_{$e_id}\" ON u_ug_id = ug_id
                        ";

		/*
		 * ,
		p_name,
		ug_name,
		pg_name
		 */
//LEFT JOIN \"T_UserGroup_$e_id\" ON u_ug_id = ug_id
		//LEFT JOIN \"T_PttGroup_$e_id\" ON u_default_pg = pg_number
		$sql = $sql . $this->getwhere(true);
		$sql = $sql . $limit;
		$stat = $this->pdo->query($sql);
		$result = $stat->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

    /**
     * 获取pttmember所有用户
     */
    public function getalluser_v2($limit = "") {
            $e_id = $this->data["e_id"];
//$pg_number = $this->data["pg_number"];
            //
            $sql = "
                   SELECT

                        pm_number,
                        pm_level,
                        pm_pgnumber,
                        pm_hangup,
                        u_name,
                        u_default_pg,
                        u_ug_id,
                        u_sub_type,
                        ug_name
                FROM
            \"T_PttMember_{$e_id}\"
            LEFT JOIN \"T_User\" ON \"T_PttMember_{$e_id}\".pm_number = \"T_User\".u_number
            LEFT JOIN \"T_UserGroup_{$e_id}\" ON u_ug_id = ug_id
            ";
//                $sql = $sql . "WHERE pm_pgnumber = '{$pg_number}'";
            $sql = $sql . $this->getpgWhere(true);
            $stat = $this->pdo->query($sql);
            $result = $stat->fetchAll();

            return $result;
    }

	public function getpttmb($limit = "") {
		$e_id = $this->data["e_id"];
//$pg_number = $this->data["pg_number"];
		//
		$sql = "
                SELECT
                        pm_number,
                        pm_level,
                        pm_pgnumber,
                        pm_hangup,
                        u_name,
                        u_default_pg,
                        u_ug_id,
                        u_sub_type,
                        ug_name,
                        ug_weight,
                        ug_path
                FROM
                \"T_PttMember_{$e_id}\"
                LEFT JOIN \"T_User\" ON \"T_PttMember_{$e_id}\".pm_number = \"T_User\".u_number
                LEFT JOIN \"T_UserGroup_{$e_id}\" ON u_ug_id = ug_id
                ";
		$sql = $sql . $this->getpgWhere(true);
		$sql = $sql . $limit;
		$stat = $this->pdo->query($sql);
		$result = $stat->fetchAll();
		return $result;
	}

	public function getAllListMax() {
		if ($this->data['u_number'] == '') {
			return NULL;
		}

		$sql = 'SELECT
	e_id
                FROM
	"T_Enterprise" WHERE 1=1 ';
		$area = new area($_REQUEST);
		$sql .= $area->getAcl('e_area', '@');
		$sql .= ' ORDER BY e_id';

		$stat = $this->pdo->query($sql);
		$result = $stat->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result as $value) {
			$fsql = 'SELECT * FROM "T_User_:e_id"';
			$fsql = str_replace(':e_id', $value['e_id'], $fsql);
			$fsql .= $this->getwhere(TRUE);
			$fsql .= ' LIMIT 50 OFFSET 0';
			$fsth = $this->pdo->query($fsql);

			$list = $fsth->fetchAll(PDO::FETCH_ASSOC);

			if (count($list) > 0) {
				$tmp['e_id'] = $value['e_id'];
				$tmp['list'] = $list;
				return $tmp;
			}
		}
	}

	public function getAllList($limit) {
		if ($this->getAllListTable() == '') {
			return NULL;
		}
		$sql = 'SELECT * FROM(:alltable) AS tmp';

		$sql = $sql . $this->getWhere(true);
		$sql = $sql . $limit;
		$sql = str_replace(':alltable', $this->getAllListTable(), $sql);
		$stat = $this->pdo->query($sql);
		$result = $stat->fetchAll();
		return $result;
	}

	public function getAllTotal($flag = TRUE) {
		if ($this->getAllListTable() == '') {
			return 0;
		}

		$sql = 'SELECT COUNT(u_number)AS total FROM (:alltable) AS tmp';

		if ($flag) {
			$sql = $sql . $this->getWhere();
		}

		$sql = str_replace(':alltable', $this->getAllListTable(), $sql);
		$pdoStatement = $this->pdo->query($sql);
		$result = $pdoStatement->fetch();

		return $result["total"];
	}

	public function getAllListTable() {
		$sql = 'SELECT
	e_id
                FROM
	"T_Enterprise"';
		$stat = $this->pdo->query($sql);
		$result = $stat->fetchAll(PDO::FETCH_ASSOC);
		$e_id = array();
		foreach ($result as $value) {
			$tb = 'SELECT * FROM "T_User_:e_id"';
			$tb = str_replace(':e_id', $value['e_id'], $tb);
			$e_id[] = $tb;
		}

		$resultstr = implode('UNION ALL ', $e_id);
		return $resultstr;
	}

	public function getById() {
//$tablename = $this->getTableName();
		$sql = "SELECT * FROM \"T_User\" WHERE u_number = :u_number";
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':u_number', $this->data["u_number"], PDO::PARAM_STR);
		$sth->execute();
		$data = $sth->fetch(PDO::FETCH_ASSOC);
		return $data;
	}

	/**
	 * 获得用户信息 企业 终端状态 流量卡状态
	 * @return mixed
	 */
	public function getById_history() {
//$tablename = $this->getTableName();
		$sql =<<<ECHO
		SELECT
		 u_number,
		 u_name,
		 u_e_id,
		 u_iccid,
		 u_imsi,
		 u_imei,
         u_meid,
		 u_mobile_phone,
		 u_active_state,
		 u_sub_type,
		 u_create_time,
		 u_remark,
		 e_id,
		 e_name,
		 md_imei,
         md_meid,
		 md_serial_number,
		 md_type,
		 md_binding,
		 md_status,
		 md_binding_time,
		 g_status,
                                    g_number,
                                    g_iccid,
                                    g_imsi,
                                    g_binding_time
		 FROM "T_User"
		LEFT JOIN "T_Enterprise" ON u_e_id=e_id
		LEFT JOIN "T_MobileDevice" ON u_imei=md_imei
		LEFT JOIN "T_Gprs" ON u_iccid=g_iccid
		WHERE u_number = :u_number
ECHO;
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':u_number', $this->data["u_number"], PDO::PARAM_STR);
		$sth->execute();
		$data = $sth->fetch(PDO::FETCH_ASSOC);
		return $data;
	}
	
	public function getuser($num) {
//$tablename = $this->getTableName();
		$sql = "SELECT * FROM \"T_User\" WHERE u_number = :u_number";
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':u_number', $num, PDO::PARAM_STR);
		$sth->execute();
		$data = $sth->fetch();
		return $data;
	}

	public function getTableName() {
		return "T_User";

//return "T_User_" . $this->data["e_id"];
	}

//
	public function getusertotal($type) {
		$sql = "SELECT COUNT(u_number)AS total FROM \"T_User\"";
		$sql = $sql . " WHERE u_sub_type =" . $type . " AND u_e_id =" . $this->data["e_id"];
		$pdoStatement = $this->pdo->query($sql);
		$result = $pdoStatement->fetch();
		return $result["total"];
	}
	public function getusertotal_bao($type) {
		$sql = "SELECT COUNT(u_number)AS total FROM \"T_User\"";
		$sql = $sql . " WHERE u_sub_type =" . $type . " AND u_e_id =" . $this->data["e_id"]." AND u_purch_date BETWEEN '{$this->data["start"]}' AND '{$this->data["end"]}'" ;
		$pdoStatement = $this->pdo->query($sql);
		$result = $pdoStatement->fetch();
		return $result["total"];
	}

	public function getetotal($type, $e_id) {
		$sql = "SELECT COUNT(u_number)AS total FROM \"T_User\"";
		$sql = $sql . " WHERE u_sub_type = " . $type . " AND u_e_id = '" . $e_id . "'";
		$pdoStatement = $this->pdo->query($sql);
		$result = $pdoStatement->fetch();
		return $result["total"];
	}

//获取企业用户总数
    public function getTotal($flag = TRUE,$date="") {
            $sql = "SELECT COUNT(u_number) AS total FROM \"T_User\" ";

            if ($flag) {
                    $sql = $sql . $this->getWhere();
            } else {
                   $sql= $sql." WHERE 1=1 ";
                    if ($this->data["e_id"] != "") {
                             $sql = $sql . " AND u_e_id =" . $this->data["e_id"];
                     }else if($this->data['u_e_id']!=""){
                             $sql = $sql . " AND u_e_id =" . $this->data["u_e_id"];
                     }
                     if($date!=""){
                             $sql = $sql . " AND u_create_time ='" . $date ."'";
                     }

            }

            $pdoStatement = $this->pdo->query($sql);
            $result = $pdoStatement->fetch();

            return $result["total"];
    }

//获取当前群组用户个数
	public function getpguserTotal($flag = TRUE) {
		$e_id = $this->data["e_id"];
		$sql = "SELECT COUNT(u_number)AS total FROM
        	\"T_PttMember_{$e_id}\"
        	LEFT JOIN \"T_User\" ON \"T_PttMember_{$e_id}\".pm_number = \"T_User\".u_number
        	";

		if ($flag) {
			$sql = $sql . $this->getpgWhere(false);
		} else {
			if ($this->data["e_id"] != "") {
				$sql = $sql . "WHERE u_e_id =" . $this->data["e_id"];
			}
		}

		$pdoStatement = $this->pdo->query($sql);
		$result = $pdoStatement->fetch();

		return $result["total"];
	}

	public function saveUserVerify($num = 1) {
		$data['e_id'] = $this->data['e_id'];
		$enterprise = new enterprise($data);

		$edit = FALSE;
		if ($this->data['do'] == 'edit') {
			$edit = TRUE;
		}
		$item_e = $enterprise->getByid();
		$usernum = $this->getTotal(FALSE);
		$flag = $item_e['e_mds_users'] - ($usernum + $num);
//$log = sprintf('执行了用户数检查，企业用户数【%s】增加前用户数【%s】企业ID【%s', $item_e['e_mds_users'], $usernum, $this->data['e_id']);
		//$this->log($log, 6, 0);
		if (!$edit) {
			if ($flag < 0) {
				throw new Exception(L('企业用户数超过'.$_SESSION['ident'].'-Server用户数'), -1);
			}
		}
	}

	public function getListV2() {
//$table = "T_UserGroup_{$this->data['e_id']}";
		$e_id = $this->data['e_id'];
		$page = new page($this->data);
		$page->setTotal($this->getTotal());

		$result = $this
			->table('T_User')
			->filed(array('u_number', 'u_name', 'ug_name','u_sub_type'), FALSE)
			->getWhereV2()
			->limitstr($page->getLimit())
			->left("LEFT JOIN \"T_Product\" ON u_product_id = p_id")
			->left("LEFT JOIN \"T_PttGroup_{$e_id}\" ON u_default_pg = pg_number")
			->left("LEFT JOIN \"T_UserGroup_{$e_id}\" ON u_ug_id = ug_id")
			->order('ug_name IS NULL DESC,ug_path ASC,u_number')
			->select();

		return $result;
	}
        /**
         * 通过imei获取企业号码
         * @return type
         */
        public function getunum($imei){
            $sql="SELECT u_number FROM \"T_User\" WHERE u_imei='{$imei}'";
            $sth=$this->pdo->query($sql);
            $res= $sth->fetch(PDO::FETCH_ASSOC);
            return $res['u_number'];
        }

	public function get() {
		return $this->data;
	}

	public function set($data) {
		$this->data = $data;
	}

    /**
     * 获取起始id
     */
    public function getstartid ()
    {
        $sql = 'SELECT u_number FROM "T_User" WHERE u_e_id=\'' . $this->data['e_id'] . '\' ORDER BY u_number DESC';
        $sth = $this->pdo->query ( $sql );
        $result = $sth->fetchAll ();
        return $result[0];
    }
    public function getinfo ( $u_number )
    {
//$tablename = $this->getTableName();
        $sql = "SELECT * FROM \"T_User\" LEFT JOIN \"T_UserGroup_{$this->data['e_id']}\" ON u_ug_id=ug_id WHERE u_number = :u_number";
        $sql = $sql . $this->getcustwhere ();
        $sth = $this->pdo->prepare ( $sql );
        $sth->bindValue ( ':u_number' , $u_number , PDO::PARAM_STR );
        $sth->execute ();
        $data = $sth->fetch ();
        return $data;
    }
    
    /**
         * 获取当前代理商下的所有企业用户
         * 1 手机 2 调度台 3 GVS
         */
        public function getusernum_ag($num){
            $sql="SELECT
	COUNT (u_number)
FROM
	\"T_User\"
LEFT JOIN \"T_Enterprise\" ON e_id = u_e_id
WHERE
	e_agents_id = :ag_number AND u_sub_type=:u_sub_type";
            $sth = $this->pdo->prepare($sql);
            $sth->bindValue(':ag_number', $this->data['ag_number'], PDO::PARAM_STR);
            $sth->bindValue(':u_sub_type', $num, PDO::PARAM_INT);
            $sth->execute();
            $data = $sth->fetch();
            return $data['count'];
        }
        
        /**
         * 修改默认群组
         */
        public function up_group_default($u_number){
            $sql=<<<ECHO
                    UPDATE "T_User" SET u_default_pg=:u_default_pg WHERE u_number=:u_number
ECHO;
            $sth = $this->pdo->prepare($sql);
            $sth->bindValue(':u_default_pg', "", PDO::PARAM_STR);
            $sth->bindValue(':u_number', $u_number, PDO::PARAM_STR);
            $sth->execute();
        }
        public function get_p_function($pi_code,$e_id){
            $sql="SELECT count(u_number) FROM \"T_User\" WHERE u_p_function LIKE '%\"{$pi_code}\"%' AND u_e_id='$e_id'";
//$sql .="  u_e_id='$e_id'";
            $pdoStatement = $this->pdo->query($sql);
            $result = $pdoStatement->fetch();
            return $result['count'];
        }
        public function get_p_function_bao($pi_code,$e_id,$start,$end){
            $sql="SELECT count(u_number) FROM \"T_User\" WHERE u_p_function LIKE '%\"{$pi_code}\"%' AND u_e_id='$e_id' AND u_purch_date BETWEEN '{$start}' AND '{$end}'";
//$sql .="  u_e_id='$e_id'";
            $pdoStatement = $this->pdo->query($sql);
            $result = $pdoStatement->fetch();
            return $result['count'];
        }
        public function getUserType_record($type){
            $sql="SELECT COUNT(ur_number) FROM \"T_User_Record_{$this->data['start']}\" WHERE ur_sub_type= '%s' AND ur_e_id=%d";
                $sql=  sprintf($sql,$type,  $this->data['er_id']);
                $pdoStatement=$this->pdo->query($sql);
                $result = $pdoStatement->fetch();
            return $result['count'];
        }
	public function get_eq_user_list(){
		$sql = "SELECT u_number,u_e_id,u_imei,u_meid,u_imsi,u_mobile_phone,u_iccid,u_create_time FROM \"T_User\" WHERE u_e_id=:u_e_id";
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':u_e_id', $this->data['u_e_id']);
		$sth->execute();
		$data = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	public function get_uh_id(){
		$sql = 'SELECT nextval(\'"T_UserHistory_uh_id_seq"\'::regclass)';
		$sth = $this->pdo->query($sql);
		$result = $sth->fetch();
		return $result["nextval"];
	}
	public function set_user_history($info,$stat){
		$uh_id=$this->get_uh_id();
		$sql=<<<ECHO
		INSERT INTO "T_UserHistory" (
			"uh_id",
			"uh_md_imei",
            "uh_md_meid",
			"uh_md_type",
			"uh_md_status",
			"uh_gp_iccid",
			"uh_gp_imsi",
			"uh_gp_mobile",
			"uh_gp_status",
			"uh_user_status",
			"uh_change_time",
			"uh_u_name",
			"uh_u_number",
			"uh_stat",
			"uh_remark"
		) VALUES(
			:uh_id,
			:uh_md_imei,
            :uh_md_meid,
			:uh_md_type,
			:uh_md_status,
			:uh_gp_iccid,
			:uh_gp_imsi,
			:uh_gp_mobile,
			:uh_gp_status,
			:uh_user_status,
			:uh_change_time,
			:uh_u_name,
			:uh_u_number,
			:uh_stat,
			:uh_remark
		)
ECHO;
	$sth=$this->pdo->prepare($sql);
		$sth->bindValue(":uh_id",$uh_id);
		$sth->bindValue(":uh_md_imei",$this->data['uh_md_imei']);
        $sth->bindValue(":uh_md_meid",$this->data['uh_md_meid']);
		$sth->bindValue(":uh_md_type",$this->data['uh_md_type']);
		$sth->bindValue(":uh_md_status",$this->data['uh_md_status']);
		$sth->bindValue(":uh_gp_iccid",$this->data['uh_gp_iccid']);
		$sth->bindValue(":uh_gp_imsi",$this->data['uh_gp_imsi']);
		$sth->bindValue(":uh_gp_mobile",$this->data['uh_gp_mobile']);
		$sth->bindValue(":uh_gp_status",$this->data['uh_gp_status']);
		$sth->bindValue(":uh_user_status",$info);
		$sth->bindValue(":uh_change_time",time());
		$sth->bindValue(":uh_u_name",$this->data['uh_u_name']);
		$sth->bindValue(":uh_u_number",$this->data['uh_u_number']);
		$sth->bindValue(":uh_stat",$stat,PDO::PARAM_INT);
		$sth->bindValue(":uh_remark",$this->data['uh_remark']);
		$sth->execute();
	}
        public function user_history($info,$stat=1){
            if($info['md_binding']!=""||$info['md_binding']!="0"){
                if($info['md_status']=="0"){
                    $md_status="stop";
                }else if($info['md_status']=="1"){
                    $md_status="start";
                }else{
                    $md_status="";
                }  
            }else{
                    $md_status="";
            }
            if($info['u_active_state']=="1"){
                    $user_status='start';
            }else{
                    $user_status='stop';
            }
            if($info['g_status']=="0"){
                $g_status="stop";
            }else if($info['g_status']=="1"){
                $g_status="start";
            }else{
                $g_status="";
            }
            $this->data['uh_md_imei']=$info['u_imei'];
            $this->data['uh_md_meid']=$info['u_meid'];
            $this->data['uh_md_type']=$info['md_type'];
            $this->data['uh_md_status']=$md_status;
            $this->data['uh_gp_iccid']=$info['u_iccid'];
            $this->data['uh_gp_imsi']=$info['u_imsi'];
//            $data['uh_gp_mobile']=$info['u_mobile_phone'];
            $this->data['uh_gp_mobile']=$info['g_number'];
            $this->data['uh_gp_status']=$g_status;
            $this->data['uh_u_name']=$info['u_name'];
            $this->data['uh_u_number']=$info['u_number'];
            $this->data['u_number']=$info['u_number'];
            $this->data['u_name']=$info['u_name'];
	    $this->data['uh_remark'] = $info['uh_remark'];
            $this->data['e_id']=$info['e_id'];
            //$this->set($data);
            $this->set_user_history($user_status,$stat);
        }
        
        //获取企业用户历史纪录的总数
    public function getTotal_users_history($flag = TRUE) {
            $sql = "SELECT COUNT(uh_u_number) AS total FROM \"T_UserHistory\"";
            if ($flag) {
                    $sql = $sql . $this->getWhere();
            } else {
                    if ($this->data["uh_u_number"] != "") {
                            $sql = $sql . "WHERE uh_u_number ='" . $this->data["uh_u_number"]."'";
                    }
            }

            $pdoStatement = $this->pdo->query($sql);
            $result = $pdoStatement->fetch();

            return $result["total"];
    }
    
    /**
        * 获得用户历史纪录列表
        * @param string $limit
        * @return array
        */
       public function getList_users_history($limit=""){
               $sql=<<<ECHO
   SELECT * FROM "T_UserHistory"
ECHO;
               $sql.=$this->getWhere_h();
               $sql.=$limit;
               //$sth=$this->pdo->prepare($sql);
               $sth=$this->pdo->query($sql);
               $res=$sth->fetchAll(PDO::FETCH_ASSOC);
               return $res;
       }
    public function getWhere_h($order=true){
             $where=" WHERE 1=1";
             if($this->data['uh_u_number']!=""){
                     $where.=" AND uh_u_number='".$this->data['uh_u_number']."'";
             }
             if($order){
                     $where.="ORDER BY uh_change_time DESC";
             }
             return $where;
     }
     
      /**
         * 用户操作记录表
         * 1.累计新增用户数
         * 2.累计删除用户数
         * 3.新增用户数
         * 4.删除用户数
         * 5.新增终端
         * 6.新增商用终端
         * 7.新增测试终端
         * 8.新增流量卡
         * 9.新增商用流量卡
         * 10新增测试流量卡
         */
        
        /**
         * 检测是否存在该条记录
         * @param type $e_id
         * @param type $date
         * @return boolean
         */
        public function check_valid($e_id,$date){
            $sql="SELECT * FROM \"T_User_record\" WHERE record_eid=:record_eid AND record_time=:record_time";
            $sth=$this->pdo->prepare($sql);
            $sth->bindValue(":record_eid",$e_id);
            $sth->bindValue(":record_time",$date);
            try {
                $sth->execute();
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
            $res=$sth->fetchAll(PDO::FETCH_ASSOC);
            if(count($res)>=1){
                return true;//已存在
            }else{
                return false;//不存在
            }
        }
        /**
         * 获得某个企业最新的记录信息
         * @param type $e_id
         * @return type
         */
        public function get_last_record($e_id){
            $sql="SELECT * FROM \"T_User_record\" WHERE record_eid=:record_eid ORDER BY record_time DESC";
            $sth=$this->pdo->prepare($sql);
            $sth->bindValue(":record_eid",$e_id);
            try {
                $sth->execute();
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
            $res=$sth->fetchAll(PDO::FETCH_ASSOC);
            return $res[0];
        }
        
        
        /*
         * 1.累计新增用户数
         * ==========================start================================
         */
        public function sum_add_users($e_id,$num){
            $date=date("Y-m-d",time());
            if($this->check_valid($e_id,$date)){//如果该记录存在 update
                $sql=<<<ECHO
                        UPDATE "T_User_record" SET record_total_add_user=record_total_add_user+{$num} WHERE record_eid={$e_id} AND record_time='{$date}'
ECHO;
                $this->pdo->query($sql);
            }else{//如果该记录不存在存在 insert
                $info=$this->get_last_record($e_id);
                $num=$info['record_total_add_user']+$num;

                $sql=<<<ECHO
                INSERT INTO "T_User_record" (
                    "record_time",
                    "record_eid",
                    "record_total_add_user",
                    "record_total_del_users",
                    "record_add_user",
                    "record_del_user",
                    "record_add_tm",
                    "record_add_commercial_tm",
                    "record_add_test_tm",
                    "record_add_gprs",
                    "record_add_commercial_gprs",
                    "record_add_test_gprs"
                        )
                VALUES
                    (
                    :record_time,
                    $e_id,
                    $num,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0
                )
ECHO;
                $sth=$this->pdo->prepare($sql);
                $sth->bindValue(":record_time",$date,PDO::PARAM_INT);
                $sth->execute();
            }
        }
        //===========================end============================
        /*
         * 2.累计删除用户数
         * ==========================start================================
         */
        public function sum_delete_users($e_id,$num){
            $date=date("Y-m-d",time());
            if($this->check_valid($e_id,$date)){//如果该记录存在 update
                $sql=<<<ECHO
                        UPDATE "T_User_record" SET record_total_del_users=record_total_del_users+{$num} WHERE record_eid={$e_id} AND record_time='{$date}'
ECHO;
                $this->pdo->query($sql);
            }else{//如果该记录不存在存在 insert
                $info=$this->get_last_record($e_id);
                $num=$info['record_total_del_users']+$num;
                $sql=<<<ECHO
                INSERT INTO "T_User_record" (
                    "record_time",
                    "record_eid",
                    "record_total_add_user",
                    "record_total_del_users",
                    "record_add_user",
                    "record_del_user",
                    "record_add_tm",
                    "record_add_commercial_tm",
                    "record_add_test_tm",
                    "record_add_gprs",
                    "record_add_commercial_gprs",
                    "record_add_test_gprs"
                        )
                VALUES
                    (
                    :record_time,
                    $e_id,
                    0,
                    $num,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0
                )
ECHO;
                $sth=$this->pdo->prepare($sql);
                $sth->bindValue(":record_time",$date,PDO::PARAM_INT);
                $sth->execute();
            }
        }
        //===========================end============================
         /*
         * 3.新增用户数
         * ==========================start================================
         */
        public function add_users($e_id,$num){
            $date=date("Y-m-d",time());
            if($this->check_valid($e_id,$date)){//如果该记录存在 update
                $sql=<<<ECHO
                        UPDATE "T_User_record" SET record_add_user=record_add_user+{$num} WHERE record_eid={$e_id} AND record_time='{$date}'
ECHO;
                $this->pdo->query($sql);
            }else{//如果该记录不存在存在 insert
                $info=$this->get_last_record($e_id);
                $sum=$info['record_total_del_users'];
                $sql=<<<ECHO
                INSERT INTO "T_User_record" (
                    "record_time",
                    "record_eid",
                    "record_total_add_user",
                    "record_total_del_users",
                    "record_add_user",
                    "record_del_user",
                    "record_add_tm",
                    "record_add_commercial_tm",
                    "record_add_test_tm",
                    "record_add_gprs",
                    "record_add_commercial_gprs",
                    "record_add_test_gprs"
                        )
                VALUES
                    (
                    :record_time,
                    $e_id,
                    0,
                    $sum,
                    $num,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0
                )
ECHO;
                $sth=$this->pdo->prepare($sql);
                $sth->bindValue(":record_time",$date,PDO::PARAM_INT);
                $sth->execute();
            }
        }
        //===========================end============================
         /*
         * 4.删除用户数
         * ==========================start================================
         */
        public function delete_users($e_id,$num){
            $date=date("Y-m-d",time());
            if($this->check_valid($e_id,$date)){//如果该记录存在 update
                $sql=<<<ECHO
                        UPDATE "T_User_record" SET record_del_user=record_del_user+{$num} WHERE record_eid={$e_id} AND record_time='{$date}'
ECHO;
                $this->pdo->query($sql);
            }else{//如果该记录不存在存在 insert
                $info=$this->get_last_record($e_id);
                $sum=$info['record_total_add_user'];
                $sql=<<<ECHO
                INSERT INTO "T_User_record" (
                    "record_time",
                    "record_eid",
                    "record_total_add_user",
                    "record_total_del_users",
                    "record_add_user",
                    "record_del_user",
                    "record_add_tm",
                    "record_add_commercial_tm",
                    "record_add_test_tm",
                    "record_add_gprs",
                    "record_add_commercial_gprs",
                    "record_add_test_gprs"
                        )
                VALUES
                    (
                    :record_time,
                    $e_id,
                    $sum,
                    0,
                    0,
                    $num,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0
                )
ECHO;
                $sth=$this->pdo->prepare($sql);
                $sth->bindValue(":record_time",$date,PDO::PARAM_INT);
                $sth->execute();
            }
        }
        //===========================end============================
         /*
         * 5.新增终端
         * ==========================start================================
         */
        public function add_terminal($e_id,$num){
            if($num>=0){
                $sign="+";
            }else{
                $sign="";
            }
            $date=date("Y-m-d",time());
            if($this->check_valid($e_id,$date)){//如果该记录存在 update
                $sql=<<<ECHO
                        UPDATE "T_User_record" SET record_add_tm=record_add_tm {$sign} {$num} WHERE record_eid={$e_id} AND record_time='{$date}'
ECHO;
                $this->pdo->query($sql);
            }else{//如果该记录不存在存在 insert
                $sql=<<<ECHO
                INSERT INTO "T_User_record" (
                    "record_time",
                    "record_eid",
                    "record_total_add_user",
                    "record_total_del_users",
                    "record_add_user",
                    "record_del_user",
                    "record_add_tm",
                    "record_add_commercial_tm",
                    "record_add_test_tm",
                    "record_add_gprs",
                    "record_add_commercial_gprs",
                    "record_add_test_gprs"
                        )
                VALUES
                    (
                    :record_time,
                    $e_id,
                    0,
                    0,
                    0,
                    0,
                    $num,
                    0,
                    0,
                    0,
                    0,
                    0
                )
ECHO;
                $sth=$this->pdo->prepare($sql);
                $sth->bindValue(":record_time",$date,PDO::PARAM_INT);
                $sth->execute();
            }
        }
        //===========================end============================
         /*
         * 6.新增商用终端
         * ==========================start================================
         */
        public function add_commercial_term($e_id,$num){
             if($num>=0){
                $sign="+";
            }else{
                $sign="";
            }
            $date=date("Y-m-d",time());
            if($this->check_valid($e_id,$date)){//如果该记录存在 update
                $sql=<<<ECHO
                        UPDATE "T_User_record" SET record_add_commercial_tm=record_add_commercial_tm{$sign}{$num} WHERE record_eid={$e_id} AND record_time='{$date}'
ECHO;
//                var_dump($sql);die;
                $this->pdo->query($sql);
            }else{//如果该记录不存在存在 insert
                $sql=<<<ECHO
                INSERT INTO "T_User_record" (
                    "record_time",
                    "record_eid",
                    "record_total_add_user",
                    "record_total_del_users",
                    "record_add_user",
                    "record_del_user",
                    "record_add_tm",
                    "record_add_commercial_tm",
                    "record_add_test_tm",
                    "record_add_gprs",
                    "record_add_commercial_gprs",
                    "record_add_test_gprs"
                        )
                VALUES
                    (
                    :record_time,
                    $e_id,
                    0,
                    0,
                    0,
                    0,
                    0,
                    $num,
                    0,
                    0,
                    0,
                    0
                )
ECHO;
                $sth=$this->pdo->prepare($sql);
                $sth->bindValue(":record_time",$date,PDO::PARAM_INT);
                $sth->execute();
            }
        }
        //===========================end============================
         /*
         * 7.新增测试终端
         * ==========================start================================
         */
        public function add_test_term($e_id,$num){
             if($num>=0){
                $sign="+";
            }else{
                $sign="";
            }
            $date=date("Y-m-d",time());
            if($this->check_valid($e_id,$date)){//如果该记录存在 update
                $sql=<<<ECHO
                        UPDATE "T_User_record" SET record_add_test_tm=record_add_test_tm{$sign}{$num} WHERE record_eid={$e_id} AND record_time='{$date}'
ECHO;
                $this->pdo->query($sql);
            }else{//如果该记录不存在存在 insert
                $sql=<<<ECHO
                INSERT INTO "T_User_record" (
                    "record_time",
                    "record_eid",
                    "record_total_add_user",
                    "record_total_del_users",
                    "record_add_user",
                    "record_del_user",
                    "record_add_tm",
                    "record_add_commercial_tm",
                    "record_add_test_tm",
                    "record_add_gprs",
                    "record_add_commercial_gprs",
                    "record_add_test_gprs"
                        )
                VALUES
                    (
                    :record_time,
                    $e_id,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    $num,
                    0,
                    0,
                    0
                )
ECHO;
                $sth=$this->pdo->prepare($sql);
                $sth->bindValue(":record_time",$date,PDO::PARAM_INT);
                $sth->execute();
            }
        }
        //===========================end============================
         /*
         * 8.新增流量卡
         * ==========================start================================
         */
        public function add_gprs($e_id,$num){
             if($num>=0){
                $sign="+";
            }else{
                $sign="";
            }
            $date=date("Y-m-d",time());
            if($this->check_valid($e_id,$date)){//如果该记录存在 update
                $sql=<<<ECHO
                        UPDATE "T_User_record" SET record_add_gprs=record_add_gprs{$sign}{$num} WHERE record_eid={$e_id} AND record_time='{$date}'
ECHO;
                $this->pdo->query($sql);
            }else{//如果该记录不存在存在 insert
                $sql=<<<ECHO
                INSERT INTO "T_User_record" (
                    "record_time",
                    "record_eid",
                    "record_total_add_user",
                    "record_total_del_users",
                    "record_add_user",
                    "record_del_user",
                    "record_add_tm",
                    "record_add_commercial_tm",
                    "record_add_test_tm",
                    "record_add_gprs",
                    "record_add_commercial_gprs",
                    "record_add_test_gprs"
                        )
                VALUES
                    (
                    :record_time,
                    $e_id,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    $num,
                    0,
                    0
                )
ECHO;
                $sth=$this->pdo->prepare($sql);
                $sth->bindValue(":record_time",$date,PDO::PARAM_INT);
                $sth->execute();
            }
        }
        //===========================end============================
         /*
         * 9.新增用户数
         * ==========================start================================
         */
        public function add_commercial_gprs($e_id,$num){
             if($num>=0){
                $sign="+";
            }else{
                $sign="";
            }
            $date=date("Y-m-d",time());
            if($this->check_valid($e_id,$date)){//如果该记录存在 update
                $sql=<<<ECHO
                        UPDATE "T_User_record" SET record_add_commercial_gprs=record_add_commercial_gprs{$sign}{$num} WHERE record_eid={$e_id} AND record_time='{$date}'
ECHO;
                $this->pdo->query($sql);
            }else{//如果该记录不存在存在 insert
                $sql=<<<ECHO
                INSERT INTO "T_User_record" (
                    "record_time",
                    "record_eid",
                    "record_total_add_user",
                    "record_total_del_users",
                    "record_add_user",
                    "record_del_user",
                    "record_add_tm",
                    "record_add_commercial_tm",
                    "record_add_test_tm",
                    "record_add_gprs",
                    "record_add_commercial_gprs",
                    "record_add_test_gprs"
                        )
                VALUES
                    (
                    :record_time,
                    $e_id,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    $num,
                    0
                )
ECHO;
                $sth=$this->pdo->prepare($sql);
                $sth->bindValue(":record_time",$date,PDO::PARAM_INT);
                $sth->execute();
            }
        }
        //===========================end============================
         /*
         * 10.新增测试流量卡
         * ==========================start================================
         */
        public function add_test_gprs($e_id,$num){
             if($num>=0){
                $sign="+";
            }else{
                $sign="";
            }
           $date=date("Y-m-d",time());
            if($this->check_valid($e_id,$date)){//如果该记录存在 update
                $sql=<<<ECHO
                        UPDATE "T_User_record" SET record_add_test_gprs=record_add_test_gprs{$sign}{$num} WHERE record_eid={$e_id} AND record_time='{$date}'
ECHO;
                $this->pdo->query($sql);
            }else{//如果该记录不存在存在 insert
                $sql=<<<ECHO
                INSERT INTO "T_User_record" (
                    "record_time",
                    "record_eid",
                    "record_total_add_user",
                    "record_total_del_users",
                    "record_add_user",
                    "record_del_user",
                    "record_add_tm",
                    "record_add_commercial_tm",
                    "record_add_test_tm",
                    "record_add_gprs",
                    "record_add_commercial_gprs",
                    "record_add_test_gprs"
                        )
                VALUES
                    (
                    :record_time,
                    $e_id,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    $num
                )
ECHO;
                $sth=$this->pdo->prepare($sql);
                $sth->bindValue(":record_time",$date,PDO::PARAM_INT);
                $sth->execute();
            } 
        }
        //===========================end============================

}
