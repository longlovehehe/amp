<?php

/**
 * 企业管理员实体类
 * @category OMP
 * @package OMP_Enterprise_dao
 * @require {@see db}
 */
class admins extends db {

	public function __construct($data) {
		parent::__construct();
		$this->data = $data;
	}
        public function set($data){
            $this->data = $data;
        }
        public function get($data){
            return $this->data;
        }
	function getWhere($order = false) {
		$where = " WHERE 1=1 " . "AND em_ent_id =" . $this->data["e_id"];

		if ($this->data["em_id"] != "") {
			$where .= "AND em_id LIKE E'%" . addslashes($this->data["em_id"]) . "%'";
		}
		if ($this->data["em_name"] != "") {
			$where .= "AND em_name LIKE E'%" . addslashes($this->data["em_name"]) . "%'";
		}
		if ($this->data["em_admin_name"] != "") {
			$where .= "AND em_admin_name LIKE E'%" . addslashes($this->data["em_admin_name"]) . "%'";
		}

		if ($this->data["em_phone"] != "") {
			$where .= "AND em_phone LIKE E'%" . addslashes($this->data["em_phone"]) . "%'";
		}

		if ($this->data["em_mail"] != "") {
			$where .= "AND em_mail LIKE E'%" . addslashes($this->data["em_mail"]) . "%'";
		}
		if ($this->data["em_lastlogin_ip"] != "") {
			$where .= "AND em_lastlogin_ip LIKE E'%" . addslashes($this->data["em_lastlogin_ip"]) . "%'";
		}

		if ($this->data["em_safe_login"] != "") {
			$where .= "AND em_safe_login = " . $this->data["em_safe_login"];
		}
		if ($this->data["e_id"] != "") {
			$where .= "AND em_ent_id = " . $this->data["e_id"];
		}

		if ($this->data["start"] != "" || $this->data["end"] != "") {

			$where .= 'AND em_lastlogin_time ' . getDateRange($this->data["start"], $this->data["end"]);

			/*
		$start = $this->data["start"];
		$end = $this->data["end"];
		$start = $start != "" ? $start : "0000-00-00";
		$end = $end != "" ? $end : "9999-99-99";
		$where .= "AND em_lastlogin_time BETWEEN to_date('" . $start . "', 'yyyy-mm-dd') AND to_date('" . $end . "', 'yyyy-mm-dd')"; */
		}

		if ($order) {
			$where .= ' ORDER BY em_id';
		}
		return $where;
	}

	public function getList($limit) {
		$sql = 'SELECT
                        "T_EnterpriseManager".em_id,
                        "T_EnterpriseManager".em_name,
                        "T_EnterpriseManager".em_pswd,
                        "T_EnterpriseManager".em_desc,
                        "T_EnterpriseManager".em_phone,
                        "T_EnterpriseManager".em_safe_login,
                        "T_EnterpriseManager".em_mail,
                        "T_EnterpriseManager".em_ent_id,
                        "T_EnterpriseManager".em_lastlogin_time,
                        "T_EnterpriseManager".em_lastlogin_ip,
                        "T_Enterprise".e_name
                FROM
                        (
                                "T_EnterpriseManager"
                                LEFT JOIN "T_Enterprise" ON (
                                        (
                                                "T_Enterprise".e_id = "T_EnterpriseManager".em_ent_id
                                        )
                                )
                        )';
		$sql = $sql . $this->getWhere(true);
		$sql = $sql . $limit;

		$stat = $this->pdo->query($sql);
		$result = $stat->fetchAll();
		return $result;
	}

	public function delList($list) {
		$list = str_replace(",", "','", "'" . $list);
		$list = rtrim($list, ",'");
		$list .= "'";
		$sql = 'DELETE FROM "T_EnterpriseManager"WHERE"T_EnterpriseManager".em_id IN (' . $list . ')';
		$count = $this->pdo->exec($sql);
		$log = DL('删除了企业管理员【%s】企业ID【%s】');
		$listarr = explode($log, $list);
		foreach ($listarr as $value) {
			$log = sprintf($log
				, str_replace("'", '', $value)
				, $_REQUEST['e_id']
			);
		}
		$this->log($log, 1, 1);
		return $count;
	}
        public function get_em_mob(){
            $sql='SELECT * FROM "T_EnterpriseManager" WHERE em_phone=:em_phone';
            $sth = $this->pdo->prepare($sql);
            $sth->bindValue(':em_phone', $this->data["em_phone"], PDO::PARAM_STR);
            $sth->execute();
            return $sth->fetch(PDO::FETCH_ASSOC);
        }
        public function get_em_mail(){
            $sql='SELECT * FROM "T_EnterpriseManager" WHERE em_mail=:em_mail';
            $sth = $this->pdo->prepare($sql);
            $sth->bindValue(':em_mail', $this->data["em_mail"], PDO::PARAM_STR);
            $sth->execute();
            return $sth->fetch(PDO::FETCH_ASSOC);
        }

	public function getTotal() {
		$sql = 'SELECT COUNT(em_id)AS total FROM "public"."T_EnterpriseManager"';
		$sql = $sql . $this->getWhere();
		$pdoStatement = $this->pdo->query($sql);
		$result = $pdoStatement->fetch();
		return $result["total"];
	}

	public function save() {
		$edit = false;
		if ($this->data["do"] == "edit") {
			$edit = true;
		}
		if ($this->data["em_safe_login"] != "") {
			$this->data["em_safe_login"] = 1;
		} else {
			$this->data["em_safe_login"] = 0;
		}

		if ($edit) {
			$sql = 'UPDATE "T_EnterpriseManager" SET "em_pswd"=:em_pswd,"em_desc"=:em_desc,"em_phone"=:em_phone,"em_safe_login"=:em_safe_login,"em_mail"=:em_mail,"em_ent_id"=:em_ent_id,"em_name"=:em_name,"em_surname"=:em_surname,"em_admin_name"=:em_admin_name WHERE "em_id" = :em_id';
			//企业操作历史记录---获取修改前的企业管理员信息
			$oldinfo = $this->getbyid();
		} else {
			$sql = 'INSERT INTO "public"."T_EnterpriseManager" ("em_id","em_pswd","em_desc","em_phone","em_safe_login","em_mail","em_ent_id","em_name","em_surname","em_admin_name")VALUES(:em_id,:em_pswd,:em_desc,:em_phone,:em_safe_login,:em_mail,:em_ent_id,:em_name,:em_surname,:em_admin_name)';
		}
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':em_pswd', $this->data["em_pswd"], PDO::PARAM_STR);
		$sth->bindValue(':em_desc', $this->data["em_desc"], PDO::PARAM_STR);
		$sth->bindValue(':em_phone', $this->data["em_phone"], PDO::PARAM_STR);
		$sth->bindValue(':em_safe_login', $this->data["em_safe_login"], PDO::PARAM_INT);
		$sth->bindValue(':em_mail', $this->data["em_mail"], PDO::PARAM_STR);
		$sth->bindValue(':em_ent_id', $this->data["em_ent_id"], PDO::PARAM_INT);
		$sth->bindValue(':em_id', $this->data["em_id"], PDO::PARAM_STR);
		$sth->bindValue(':em_name', $this->data["em_surname"]." ".$this->data["em_admin_name"], PDO::PARAM_STR);
		$sth->bindValue(':em_surname', $this->data["em_surname"], PDO::PARAM_STR);
		$sth->bindValue(':em_admin_name', $this->data["em_admin_name"], PDO::PARAM_STR);

		try
		{
			$sth->execute();
			//企业历史记录
            if($edit){
            	$enterprise = new enterprise();
            	$oldinfo['e_id'] = $this->data['e_id'];
            	$enterprise->make_history_args($oldinfo,$this->data);
            }
		} catch (Exception $ex) {
			if ($ex->getCode() == 23505) {
				if (preg_match('/EnterpriseManager/', $ex->getMessage())) {
					$log = DL('添加 企业管理员 失败，原因：帐号重复');
					$msg["msg"] = L('添加 企业管理员 失败，原因：帐号重复');
				}
				if (preg_match('/phone/', $ex->getMessage())) {
					$log = DL('添加 企业管理员 失败，原因：手机号重复');
					$msg["msg"] = L('添加 企业管理员 失败，原因：手机号重复');
				}
				if (preg_match('/mail/', $ex->getMessage())) {
					$log = DL('添加 企业管理员 失败，原因：邮箱重复');
					$msg["msg"] = L('添加 企业管理员 失败，原因：邮箱重复');
				}

			} else {
				$log = DL('添加 企业管理员 失败，原因') . '：' . $ex->getMessage();
				$msg["msg"] = L('添加 企业管理员 失败，原因') . '：' . $ex->getMessage();
			}
			$this->log($log, 1, 2);
			$msg["status"] = -1;
			return $msg;
		}
		$msg["status"] = 0;
		$log = DL('企业管理员 成功【%s】密码【%s】、手机号【%s】、邮箱【%s】、描述【%s】企业ID【%s】');
		$log = sprintf($log
			, $this->data["em_id"]
			, $this->data["em_pswd"]
			, $this->data["em_phone"]
			, $this->data["em_mail"]
			, $this->data["em_desc"]
			, $this->data["em_ent_id"]
		);

		if ($edit) {
			$log = DL('修改') ." ". $log;
			$msg["msg"] = L('修改') ." ". L('企业管理员 成功【%s】密码【%s】、手机号【%s】、邮箱【%s】、描述【%s】企业ID【%s】');
			$msg["msg"] = sprintf($msg["msg"]
				, $this->data["em_id"]
				, $this->data["em_pswd"]
				, $this->data["em_phone"]
				, $this->data["em_mail"]
				, $this->data["em_desc"]
				, $this->data["em_ent_id"]
			);
		} else {
			$log = DL('添加') ." ". $log;
			$msg["msg"] = L('添加') ." ". L('企业管理员 成功【%s】密码【%s】、手机号【%s】、邮箱【%s】、描述【%s】企业ID【%s】');
			$msg["msg"] = sprintf($msg["msg"]
				, $this->data["em_id"]
				, $this->data["em_pswd"]
				, $this->data["em_phone"]
				, $this->data["em_mail"]
				, $this->data["em_desc"]
				, $this->data["em_ent_id"]
			);
		}

		$this->log($log, 1, 0);
		return $msg;
	}

	public function getbyid() {
		$sql = 'SELECT * FROM "public"."T_EnterpriseManager" WHERE em_id=:em_id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':em_id', $this->data["e_id"], PDO::PARAM_STR);
		$sth->execute();
		return $sth->fetch();
	}

}
