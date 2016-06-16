<?php

/**
 * 企业群组实体类
 * @category OMP
 * @package OMP_Enterprise_dao
 * @require {@see db}
 */
class groups extends db {

	public function groups($data) {
		parent::__construct();
		$this->data = $data;
	}

	public function get() {
		return $this->data;
	}

	public function set($data) {
		$this->data = $data;
	}

	public function getMaxId() {
		$e_id = $this->data['e_id'];
		$db = Cof::db();
		$sql = "SELECT MAX (pg_number) AS num FROM \"T_PttGroup_$e_id\"";
		$sth = $db->query($sql);
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		$num = $result['num'];
		$num = str_replace($e_id, '', $num);
		return $num;
	}

	public function checkAddUser($u_number) {
		$user = new users(array("u_number" => $u_number));
		$data = $user->getById();
		if ($data['u_sub_type'] == 3) {
			return FALSE;
		}
		return TRUE;
	}

	public function checkUser($num1, $num2) {
		$sql1 = "SELECT * FROM \"T_PttMember_" . $this->data["e_id"] . "\" WHERE pm_number= '$num1' AND pm_pgnumber= '$num2'";
		$sth = $this->pdo->query($sql1);
		$result = $sth->fetchAll();
		if (count($result) >= 1) {
			return FALSE;
		}
		return TRUE;
	}

    public function addUser() {
            $user = new users($this->data);
            $pg_name = $user->getPGinfo($this->data['move_u_default_pg']);
            $tablename = "\"T_PttMember_" . $this->data["e_id"] . "\"";

            if ($this->data['pm_hangup'] == '') {
                    $this->data['pm_hangup'] = 0;
            } else {
                    $this->data['pm_hangup'] = 1;
            }
            $flag=$this->data["move_u_level"];
            foreach ($this->data['checkbox'] as $item) {
                    $user_name = $user->hasUser($item);
                    if (!$this->checkUser($item, $this->data['move_u_default_pg'])) {
                        if($flag == ''){
                             $sql = "UPDATE " . $tablename . "SET pm_hangup = :pm_hangup WHERE pm_number = :pm_number AND pm_pgnumber = :pm_pgnumber";
                            $sth = $this->pdo->prepare($sql);
                             $log = DL("修改企业用户") . " :【%s】(%s)" . DL("群组信息成功") . "<br /> " . DL("企 业 群 组") . " :【%s】(%s)<br />" . DL("默 认 群 组") . " :【%s】";
                            $log = sprintf($log
                                    , $user_name['u_name']
                                    , $item
                                    , $pg_name[0]['pg_name']
                                    , $pg_name[0]['pg_number']
                                   // , $this->data['move_u_level']
                                    , DL('否')
                                   // , $this->data['pm_hangup'] == 1 ? DL('是') : DL('否')
                            );
                        }else{
                             $sql = "UPDATE " . $tablename . "SET pm_level = :pm_level,pm_hangup = :pm_hangup WHERE pm_number = :pm_number AND pm_pgnumber = :pm_pgnumber";
                            $sth = $this->pdo->prepare($sql);
                            $sth->bindValue(':pm_level', $this->data["move_u_level"], PDO::PARAM_INT);
                            $log = DL("修改企业用户") . " :【%s】(%s)" . DL("群组信息成功") . "<br /> " . DL("企 业 群 组") . " :【%s】(%s)<br />" . DL("用 户 级 别") . " :【%s】<br />" . DL("默 认 群 组") . " :【%s】";
                            $log = sprintf($log
                                    , $user_name['u_name']
                                    , $item
                                    , $pg_name[0]['pg_name']
                                    , $pg_name[0]['pg_number']
                                    , $this->data['move_u_level']
                                    , DL('否')
                                   // , $this->data['pm_hangup'] == 1 ? DL('是') : DL('否')
                            );
                           
                        }
                            $sth->bindValue(':pm_number', $item, PDO::PARAM_INT);
                            $sth->bindValue(':pm_pgnumber', $this->data["move_u_default_pg"], PDO::PARAM_INT);
                            $sth->bindValue(':pm_hangup', $this->data["pm_hangup"], PDO::PARAM_INT);
                            try
                            {
                                    $sth->execute();
                                    $this->log($log, 1, 0);
                            } catch (Exception $ex) {
                                    $eventlog = $this->eventlog($ex, $log);
                                    $this->log($eventlog['msg'], 1, 2, $eventlog['event']);
                            }
                            if($this->data['move_u_default_pg']==$user_name['u_default_pg']){
                                $user->up_group_default($item);
                            }
                    } else {
                                if ($flag == '') {
                                   if($user_name['u_sub_type']=="1"){
                                       $this->data["move_u_level"] = 255;
                                   }else if($user_name['u_sub_type']=="2"){
                                       $this->data["move_u_level"] = 254;
                                   }
                           }
                            $user_name = $user->hasUser($item);
                            $sql = 'INSERT INTO ' . $tablename . ' ("pm_number", "pm_level", "pm_pgnumber","pm_hangup") VALUES (:pm_number, :pm_level, :pm_pgnumber,:pm_hangup)';
                            $sth = $this->pdo->prepare($sql);
                            if (!$this->checkAddUser($item)) {
                                    $log = sprintf(DL('用户【%s】为GVS用户，无法加入群组'), $user_name['u_name']);
                                    $this->log($log, 1, 2);
                                    throw new Exception($log, -1);
                            }

                            $log = DL('企 业 群 组') . ' :【%s】(%s) <br />' . DL('新 增 用 户') . ' :【%s】(%s)<br />' . DL('用 户 级 别') . ' :【%s】';
                            $log = sprintf($log
                                    , $pg_name[0]['pg_name']
                                    , $pg_name[0]['pg_number']
                                    , $user_name['u_name']
                                    , $user_name['u_number']
                                    , $this->data['move_u_level']
                                   // , $this->data['pm_hangup'] == 1 ? DL('是') : DL('否')
                            );

                            $sth->bindValue(':pm_number', $item, PDO::PARAM_INT);
                            $sth->bindValue(':pm_level', $this->data["move_u_level"], PDO::PARAM_INT);
                            $sth->bindValue(':pm_pgnumber', $this->data["move_u_default_pg"], PDO::PARAM_INT);
                            $sth->bindValue(':pm_hangup', $this->data["pm_hangup"], PDO::PARAM_INT);
                            try
                            {
                                    $sth->execute();
                            } catch (Exception $ex) {
                                    $eventlog = $this->eventlog($ex, $log);
                                    $this->log($eventlog['msg'], 1, 2, $eventlog['event']);
                            }

                            $this->log($log, 1, 0);
                    }
            }
    }

	function getWhere($order = false) {
		$where = " WHERE 1=1 ";
		if ($this->data["pg_number"] != "") {
			$where .= "AND pg_number LIKE E'%" . addslashes($this->data["pg_number"]) . "%'";
		}
		if ($this->data["pg_name"] != "") {
			$where .= "AND pg_name LIKE E'%" . addslashes(trim($this->data["pg_name"])) . "%'";
		}
		if ($this->data['safe'] == 'true') {
			$where .= "AND pg_level != 0";
		}
		if ($order) {
			$where .= ' ORDER BY pg_number ASC';
		}

		return $where;
	}
	function getcustWhere($order = false) {
		$where = " WHERE 1=1 ";
		if ($this->data["c_pg_number"] != "") {
			$where .= "AND c_pg_number LIKE E'%" . addslashes($this->data["c_pg_number"]) . "%'";
		}
		if ($this->data["u_name"] != "") {
			$where .= "AND u_name LIKE E'%" . addslashes($this->data["u_name"]) . "%'";
		}
		if ($this->data["c_pg_creater_num"] != "") {
			$where .= "AND c_pg_creater_num LIKE E'%" . addslashes($this->data["c_pg_creater_num"]) . "%'";
		}
		if ($this->data["c_pg_name"] != "") {
			$where .= "AND c_pg_name LIKE E'%" . addslashes($this->data["c_pg_name"]) . "%'";
		}
		if ($this->data['safe'] == 'true') {
			$where .= "AND pg_level != 0";
		}
		if ($order) {
			$where .= ' ORDER BY c_pg_number ASC';
		}

		return $where;
	}

	public function getList($limit = '') {
		$e_id = $this->data["e_id"];
		$pmtable = sprintf('"T_PttMember_%s"', $e_id);
		$pgtable = sprintf('"T_PttGroup_%s"', $e_id);
		$sql = 'SELECT
                        pg_number,
                        pg_name,
                        pg_level,
                        pg_grp_idle,
                        pg_speak_idle,
                        pg_speak_total,
                        pg_record_mode,
                        (
                                SELECT
                                        COUNT (*)
                                FROM
                                        :pmtable
                                WHERE
                                        pg_number = pm_pgnumber
                        ) AS total
                FROM
                        :pgtable
        ';

		$sql = str_replace(":pmtable", $pmtable, $sql);
		$sql = str_replace(":pgtable", $pgtable, $sql);
		$sql = $sql . $this->getWhere(true);

		$sql = $sql . $limit;
		$sth = $this->pdo->query($sql);
		$result = $sth->fetchAll();
		return $result;
	}

    public function getList_cust($limit = '') {

            $e_id = $this->data["e_id"];
            // $pmtable = sprintf ( '"T_PttMember_%s"' , $e_id );
            $cpgtable = sprintf('"T_Custom_PttGrp_%s"', $e_id);
            $sql = 'SELECT
                    c_pg_number,
                    c_pg_name,
                    c_pg_creater_num,
                    c_pg_level,
                    c_pg_grp_idle,
                    c_pg_speak_idle,
                    c_pg_speak_total,
                    c_pg_record_mode,
                    c_pg_chk_stat_int,
                    c_pg_mem_list,
                    u_name
            FROM
                    :cpgtable
                    LEFT JOIN "T_User" ON c_pg_creater_num = u_number
    ';

            //$sql = str_replace ( ":pmtable" , $pmtable , $sql );
            $sql = str_replace(":cpgtable", $cpgtable, $sql);
            $sql = $sql . $this->getcustWhere(true);
            $sth = $this->pdo->query($sql);
            $result = $sth->fetchAll();

            foreach ($result as $key => $value) {
                    $arr = explode(";", $value['c_pg_mem_list']);
                    $user_arr=array();
                    foreach ($arr as $k => $v) {
                        if($this->getcust_total($v)!==false){
                            $user_arr[$k]=$v;
                        }
                    }
            $result[$key]['total'] = count($user_arr);
    }
    return $result;
}
    
    public function getcust_total($num){
        $sql="SELECT * FROM \"T_User\" WHERE u_number='$num'";
        $sth = $this->pdo->query($sql);
         $result = $sth->fetch();
         return $result;
    }


	public function getPGname($number) {
		$e_id = $this->data["e_id"];
		$sql = "SELECT pg_name FROM \"T_PttGroup_$e_id\" WHERE pg_number = $number";
		$sth = $this->pdo->prepare($sql);
		$sth->execute();
		$count = $sth->fetch(PDO::FETCH_ASSOC);
		return $count['pg_name'];
	}

	public function deluserpg($num) {
		$e_id = $this->data["e_id"];
		$sql1 = "SELECT * FROM \"T_PttMember_$e_id\" WHERE pm_pgnumber = $num";
		$result = $this->pdo->query($sql1);
		if (count($result) > 0) {
			$sql = "DELETE FROM \"T_PttMember_$e_id\" WHERE pm_pgnumber = $num";
			$this->pdo->exec($sql);
		}
	}

	public function delList($list) {

		$e_id = $this->data["e_id"];
		$list = str_replace(",", "', '", "'" . $list);
		$list = rtrim($list, ", '");
		$list .= "'";
		//$list = str_replace("'", "", $list);
		$arr = explode(",", $list);
		$num = 0;

		foreach ($arr as $key => $value) {

			$pg_name = $this->getPGname($value);

			$log = DL('删除企业群组【%s】%s成功');

			$log = sprintf($log
				, $pg_name
				, str_replace("'", "", $value)
			);
			$sql = "DELETE FROM \"T_PttGroup_$e_id\" WHERE pg_number=$value";

			$sql2 = "DELETE FROM \"T_PttMember_$e_id\" WHERE pm_pgnumber = $value";
			$this->pdo->exec($sql2);
			$sql1 = "UPDATE \"T_User\" SET \"u_default_pg\"=null WHERE u_e_id=$e_id AND u_default_pg=$value";
			$this->pdo->exec($sql1);
			$this->log($log, 1, 1);
			$this->pdo->exec($sql);
			$num++;
		}
		return $num;
	}

	public function getTotal() {
		$e_id = $this->data["e_id"];
		$sql = "SELECT COUNT(pg_number)AS total FROM\"public\".\"T_PttGroup_$e_id\"";
		$sql = $sql . $this->getWhere();
		try
		{
			$pdoStatement = $this->pdo->query($sql);
		} catch (Exception $e) {
			echo "数据表异常，请尝试重新建立企业表数据[企业信息->企业数据重建（请在明确该操作作用前，再进行操作）]" . $e->getMessage();
			die();
		}
		$result = $pdoStatement->fetch();
		return $result["total"];
	}

	public function getuserPgname($number,$default_pg) {
		$e_id = $this->data["e_id"];
		$pmtable = sprintf('"T_PttMember_%s"', $e_id);
		$pgtable = sprintf('"T_PttGroup_%s"', $e_id);
		$sql = "SELECT
                        pm_number,
                        pm_level,
                        pm_pgnumber,
                        pm_hangup,
                        pg_number,
                        pg_name
                FROM
                        :pmtable
                LEFT JOIN $pgtable ON pm_pgnumber = pg_number
                WHERE
                        pm_number = '{$number}' AND pg_number !='{$default_pg}'
        ";

		$sql = str_replace(":pmtable", $pmtable, $sql);
		$sql = str_replace(":pgtable", $pgtable, $sql);
		//$sql = $sql . $this->getWhere(true);
                                    $sql=$sql." ORDER BY pg_number";
		$sth = $this->pdo->query($sql);
		$result = $sth->fetchAll();
		return $result;
	}

	public function getbyid() {
		$e_id = $this->data["e_id"];
		$table = "T_PttGroup_$e_id";
		$sql = "SELECT * FROM \"$table\" WHERE pg_number = :pg_number";
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':pg_number', $this->data["pg_number"], PDO::PARAM_INT);
		$sth->execute();
		$data = $sth->fetch();
		return $data;
	}

	public function getpg($num) {
		$e_id = $this->data["e_id"];
		$table = "T_PttGroup_$e_id";
		$sql = "SELECT * FROM \"$table\" WHERE pg_number = :pg_number";
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':pg_number', $num, PDO::PARAM_INT);
		$sth->execute();
		$data = $sth->fetch();
		return $data["pg_name"];
	}

	public function getbyselectid() {
		$e_id = $this->data["e_id"];
		$table = "T_PttGroup_$e_id";
		$sql = "SELECT * FROM \"$table\" WHERE pg_number = :pg_number";
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':pg_number', $this->data["move_u_default_pg"], PDO::PARAM_INT);
		$sth->execute();
		$data = $sth->fetch();
		return $data;
	}

	public function save() {
		if ($this->data["e_id"] == "") {
			echo L("错误的企业编号，程序已中断该线程");
			exit();
		}
		if ($this->data['pg_hangup'] == "") {
			$this->data['pg_hangup'] = 0;
		} else {
			$this->data['pg_hangup'] = 1;
		}
		$e_id = $this->data["e_id"];
		$table = "T_PttGroup_$e_id";

		if ($this->data["do"] == "add") {
			$sql = "INSERT INTO \"public\".\"$table\" (\"pg_number\", \"pg_name\", \"pg_level\", \"pg_grp_idle\", \"pg_speak_idle\", \"pg_speak_total\", \"pg_record_mode\", \"pg_queue_len\", \"pg_chk_stat_int\", \"pg_buf_size\", \"pg_hangup\") "
			. "VALUES (:pg_number, :pg_name, :pg_level, :pg_grp_idle, :pg_speak_idle, :pg_speak_total, :pg_record_mode,:pg_queue_len,:pg_chk_stat_int,:pg_buf_size,:pg_hangup)";
		} else {
			$sql = "UPDATE \"$table\" SET pg_name = :pg_name,pg_level = :pg_level,pg_grp_idle = :pg_grp_idle,pg_speak_idle = :pg_speak_idle,pg_speak_total = :pg_speak_total,pg_record_mode = :pg_record_mode"
			. ",pg_queue_len=:pg_queue_len"
			. ",pg_chk_stat_int=:pg_chk_stat_int"
			. ",pg_buf_size=:pg_buf_size"
			. ",pg_hangup=:pg_hangup"
			. " WHERE pg_number = :pg_number";
		}
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':pg_name', trim($this->data["pg_name"]), PDO::PARAM_STR);
		$sth->bindValue(':pg_level', $this->data["pg_level"], PDO::PARAM_INT);
		$sth->bindValue(':pg_grp_idle', $this->data["pg_grp_idle"], PDO::PARAM_INT);
		$sth->bindValue(':pg_speak_idle', $this->data["pg_speak_idle"], PDO::PARAM_INT);
		$sth->bindValue(':pg_speak_total', $this->data["pg_speak_total"], PDO::PARAM_INT);
		$sth->bindValue(':pg_record_mode', $this->data["pg_record_mode"], PDO::PARAM_INT);

		$sth->bindValue(':pg_queue_len', $this->data["pg_queue_len"], PDO::PARAM_INT);
		$sth->bindValue(':pg_chk_stat_int', $this->data["pg_chk_stat_int"], PDO::PARAM_INT);
		$sth->bindValue(':pg_buf_size', $this->data["pg_buf_size"], PDO::PARAM_INT);
		$sth->bindValue(':pg_hangup', $this->data["pg_hangup"], PDO::PARAM_INT);
		if ($this->data["do"] == "add") {
			$sth->bindValue(':pg_number', $this->data["e_id"] . sprintf("%05d", $this->data["pg_number"]), PDO::PARAM_STR);
		} else {
			$sth->bindValue(':pg_number', $this->data["pg_number"], PDO::PARAM_STR);
			$changeinfo = array();
			$result = $this->getbyid($this->data["pg_number"]);
			foreach ($result as $k => $v) {
				if ($v != $this->data[$k]) {
					$changeinfo[$k] = $this->data[$k];
				}
			}
		}
		try
		{
			$sth->execute();
		} catch (Exception $ex) {
			$log = DL('创建企业群组【%s】%s失败');
			$log = sprintf($log
				, trim($this->data["pg_name"])
				, $this->data["e_id"] . sprintf("%05d", $this->data["pg_number"])
			);
			$this->log($log, 1, 1);
			if ($ex->getCode() == 23505) {
				$exmsg = $ex->getMessage();
				if (preg_match('/pg_name_pkey/', $exmsg)) {
					$msg["msg"] = L("群组名称已存在");
				} else {
					$msg["msg"] = L("群组号码已存在");
				}
			}

			return $msg;
		}

		$msg["status"] = 0;
		if ($this->data["do"] == "add") {
			$log = DL('创建企业群组【%s】%s成功');
			$log = sprintf($log
				, trim($this->data["pg_name"])
				, $this->data["e_id"] . sprintf("%05d", $this->data["pg_number"])
			);
			$this->log($log, 1, db::INFO);
			$msg["msg"] = L("创建成功");
		} else {
			$info = array(
				'pg_number' => DL('群组号码'),
				'pg_name' => DL('群组名称'),
				'pg_level' => DL('群组级别'),
				'pg_grp_idle' => DL('组空闲超时'),
				'pg_speak_idle' => DL('话权空闲超时'),
				'pg_speak_total' => DL('话权总超时'),
				'pg_record_mode' => DL('录音模式'),
				'pg_queue_len' => DL('排队人数限制'),
				'pg_chk_stat_int' => DL('无线终端状态上报周期'),
				'pg_buf_size' => DL('缓冲区包个数'),
				'pg_hangup' => DL('主叫挂断对讲组权限'),
			);
			if (count($changeinfo) != 0) {
				$pg_hangup = $this->data['pg_hangup'];
				$u_n = trim($this->data['pg_name']);
				$u_number = $this->data['pg_number'];
				foreach ($changeinfo as $key => $value) {
					if ($info[$key] !== null && $changeinfo[$key] !== null) {
						if ($key == "pg_record_mode") {
							if ($this->data['pg_record_mode'] == 0) {
								$this->data['pg_record_mode'] = DL("对讲频道全程录音");
							} else if ($this->data['pg_record_mode'] == 1) {
								$this->data['pg_record_mode'] = DL("根据话权方的录音标志录音");
							} else if ($this->data['pg_record_mode'] == 2) {
								$this->data['pg_record_mode'] = DL("不录音");
							}
						}
						if ($key == "pg_hangup") {
							if ($pg_hangup == 0) {
								$this->data['pg_hangup'] = DL("否");
							} else {
								$this->data['pg_hangup'] = DL("是");
							}
						}
						$log = DL("修改企业群组【%s】%s %s->【%s】");
						$log = sprintf($log
							, $u_n
							, $u_number
							, $info[$key]
							, $this->data[$key]
						);

						$log .= DL("成功");
						$this->log($log, 1, 0);
					}
				}
			}
			$msg["msg"] = L("修改成功");
		}
		return $msg;
	}

	public function save_v2() {

		if ($this->data["e_id"] == "") {
			echo L("错误的企业编号，程序已中断该线程");
			exit();
		}
		if ($this->data['pg_hangup'] == "") {
			$this->data['pg_hangup'] = 0;
		} else {
			$this->data['pg_hangup'] = 1;
		}
		$e_id = $this->data["e_id"];
		$table = "T_PttGroup_$e_id";

		$sql = "INSERT INTO \"public\".\"$table\" (\"pg_number\", \"pg_name\", \"pg_level\", \"pg_grp_idle\", \"pg_speak_idle\", \"pg_speak_total\", \"pg_record_mode\", \"pg_queue_len\", \"pg_chk_stat_int\", \"pg_buf_size\", \"pg_hangup\") "
		. "VALUES (:pg_number, :pg_name, :pg_level, :pg_grp_idle, :pg_speak_idle, :pg_speak_total, :pg_record_mode,:pg_queue_len,:pg_chk_stat_int,:pg_buf_size,:pg_hangup)";

		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':pg_name', trim($this->data["pg_name"]), PDO::PARAM_STR);
		$sth->bindValue(':pg_level', $this->data["pg_level"], PDO::PARAM_INT);
		$sth->bindValue(':pg_grp_idle', $this->data["pg_grp_idle"], PDO::PARAM_INT);
		$sth->bindValue(':pg_speak_idle', $this->data["pg_speak_idle"], PDO::PARAM_INT);
		$sth->bindValue(':pg_speak_total', $this->data["pg_speak_total"], PDO::PARAM_INT);
		$sth->bindValue(':pg_record_mode', $this->data["pg_record_mode"], PDO::PARAM_INT);

		$sth->bindValue(':pg_queue_len', $this->data["pg_queue_len"], PDO::PARAM_INT);
		$sth->bindValue(':pg_chk_stat_int', $this->data["pg_chk_stat_int"], PDO::PARAM_INT);
		$sth->bindValue(':pg_buf_size', $this->data["pg_buf_size"], PDO::PARAM_INT);
		$sth->bindValue(':pg_hangup', $this->data["pg_hangup"], PDO::PARAM_INT);
		$sth->bindValue(':pg_number', $this->data["e_id"] . sprintf("%05d", $this->data["pg_number"]), PDO::PARAM_STR);
		try
		{
			$sth->execute();
		} catch (Exception $ex) {
			$log = DL('创建企业群组【%s】%s失败');
			$log = sprintf($log
				, trim($this->data["pg_name"])
				, $this->data["e_id"] . sprintf("%05d", $this->data["pg_number"])
			);
			$this->log($log, 1, 1);
			if ($ex->getCode() == 23505) {
				$exmsg = $ex->getMessage();
				if (preg_match('/pg_name_pkey/', $exmsg)) {
					$msg["msg"] = L("群组名称已存在");
				} else {
					$msg["msg"] = L("群组号码已存在");
				}
			}

			return $msg;
		}

		$msg["status"] = 0;

		$log = DL('创建企业群组【%s】%s成功');
		$log = sprintf($log
			, trim($this->data["pg_name"])
			, $this->data["e_id"] . sprintf("%05d", $this->data["pg_number"])
		);
		$this->log($log, 1, 0);
		$msg["msg"] = L("创建成功");

		return $msg;
	}

	/**
	 * 查询紧急对讲组
	 */
	public function get0groups() {
		$e_id = $this->data["e_id"];
		$table = "T_PttGroup_$e_id";
		$sql = "SELECT pg_level,pg_number FROM \"$table\" WHERE pg_level=:pg_level";
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':pg_level', 0, PDO::PARAM_INT);
		$sth->execute();
		$data = $sth->fetch();
		return $data;
	}

}
