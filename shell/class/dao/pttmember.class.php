<?php

/**
 * 群组成员实体类
 * @package OMP_Enterprise_dao
 * @require {@see db} {@see device}
 */
class pttmember extends db {

	public function __construct($data) {
		parent::__construct();
		$this->data = $data;
	}

	public function set($data) {
		$this->data = $data;
	}
	public function get() {
		return $this->data;
	}

	public function del() {
		$data['u_number'] = $this->data['pm_number'];
		$data['u_e_id'] = $this->data['e_id'];
		$data['e_id'] = $this->data['e_id'];

		$users = new users($data);
		$userinfo = $users->getuser($this->data['pm_number']);
		if ($userinfo["u_default_pg"] == $this->data['pg_number']) {
			$users->delForPtm();
		}

		$u_name = $users->hasUser($this->data['pm_number']);
		$pg_name = $users->getPGinfo($this->data['pg_number']);
		$tablename = sprintf("T_PttMember_%s", $this->data["e_id"]);
		$sql = 'DELETE
                FROM
                        ":tablename"
                WHERE
                        pm_number = :pm_number
                AND pm_pgnumber = :pm_pgnumber';
		$sql = str_replace(":tablename", $tablename, $sql);
		$sth = $this->pdo->prepare($sql);

		$sth->bindValue(':pm_number', $this->data['pm_number']);
		$sth->bindValue(':pm_pgnumber', $this->data['pg_number']);
		$sth->execute();
		$log = DL('企业群组') . ':【%s】(%s) ' . DL('删除用户') . ':【%s】(%s)';
		$log = sprintf($log
			, $pg_name[0]['pg_name']
			, $pg_name[0]['pg_number']
			, $u_name['u_name']
			, $u_name['u_number']
		);
		$this->log($log, 2, 1);
	}

	/**
	 * 修改为GVS用户时清除用户所有所在群组
	 *
	 */
	function delGVSptt() {
		$tablename = sprintf("T_PttMember_%s", $this->data["e_id"]);
		$sql = 'DELETE
                FROM
                        ":tablename"
                WHERE
                        pm_number = :pm_number';
		$sql = str_replace(":tablename", $tablename, $sql);
		$sth = $this->pdo->prepare($sql);

		$sth->bindValue(':pm_number', $this->data['u_number']);
		$sth->execute();
		$log = DL('删除用户【%s】所属群组');
		$log = sprintf($log
			, $this->data['u_number']
		);
		$this->log($log, 1, 1);
	}

	/* 检测用户是否存在 */

	public function checkUser($num1, $num2) {
		$sql1 = "SELECT * FROM \"T_PttMember_" . $this->data["e_id"] . "\" WHERE pm_number= '$num1' AND pm_pgnumber= '$num2'";
		$sth = $this->pdo->query($sql1);
		$result = $sth->fetchAll();
		if (count($result) >= 1) {
			return FALSE;
		}
		return TRUE;
	}

	public function delAllUser() {
		$users = new users($data);
		$data['u_number'] = $this->data['pm_number'];
		$u_name = $users->hasUser($this->data['pm_number']);
		$data['u_e_id'] = $this->data['e_id'];

		$userinfo = $users->getuser($this->data['pm_number']);
		if ($userinfo["u_default_pg"] == $this->data['pg_number']) {
			$users->delForPtm();
		}

		$tablename = sprintf("T_PttMember_%s", $this->data["e_id"]);
		$sql = 'DELETE
                FROM
                        ":tablename"
                WHERE
                        pm_number = :pm_number';
		$sql = str_replace(":tablename", $tablename, $sql);
		$sth = $this->pdo->prepare($sql);

		$sth->bindValue(':pm_number', $this->data['pm_number']);
		$sth->execute();
		$log = DL('清除了群组成员：【%s】%s');
		$log = sprintf($log
			, $u_name['u_name']
			, $this->data['pm_number']
		);
		$this->log($log, 1, 1);
	}

	public function delGroupsUser() {
		foreach ($this->data["checkbox"] as $value) {
			$this->data['pm_number'] = $value;
			$this->del();
		}
		return count($this->data["checkbox"]);
	}

	public function getwhere($order = false) {
		$where = " WHERE 1=1 ";
		if ($this->data["pg_number"] != "") {
			$where .= "AND pm_pgnumber LIKE E'%" . addslashes($this->data["pg_number"]) . "%'";
		}
		if ($this->data["pm_number"] != "") {
			$where .= "AND pm_number = '" . $this->data["pm_number"] . "'";
		}
		if ($order) {
			$where .= ' ORDER BY pm_number ASC';
		}
		return $where;
	}

	public function getbyid() {
		if (!isset($this->data['pm_number']) || !isset($this->data['pm_pgnumber']) || !isset($this->data['e_id'])) {
			$msg = <<<MSG
                                关键值未设置。 pm_number{$this->data['pm_number']}。pm_pgnumber{$this->data['pm_pgnumber']}。e_id{$this->data['e_id']}
MSG;
			throw new Exception($msg);
		}

		$sql = <<<SQL
SELECT
	*
FROM
	"T_PttMember_:e_id"
WHERE
	pm_number = :pm_number
AND pm_pgnumber = :pm_pgnumber
SQL;
		$sql = str_replace(':e_id', $this->data['e_id'], $sql);
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':pm_number', $this->data["pm_number"]);
		$sth->bindValue(':pm_pgnumber', $this->data["pm_pgnumber"]);
		$sth->execute();
		return $sth->fetch();
	}

	public function getList($limit) {
		$e_id = $this->data["e_id"];
		$sql = "SELECT * FROM \"public\".\"T_PttMember_$e_id\"";
		$sql = $sql . $this->getWhere(TRUE);
		$sql = $sql . $limit;
		$stat = $this->pdo->query($sql);
		$result = $stat->fetchAll();
		return $result;
	}

	public function getTotal() {
		$e_id = $this->data["e_id"];
		$sql = "SELECT COUNT(\"pm_number\")AS total FROM\"public\".\"T_PttMember_$e_id\"";
		$sql = $sql . $this->getWhere();
		$sth = $this->pdo->query($sql);

		$result = $sth->fetch();
		return $result["total"];
	}

    public function save() {

            if ($this->data['pm_hangup'] == "") {
                    $this->data['pm_hangup'] = 0;
            } else {
                    $this->data['pm_hangup'] = 1;
            }
           
            if ($this->data["do"] == "edit" && !$this->checkUser($this->data['pm_number'], $this->data['pm_pgnumber'])) {
                    $tablename = "T_PttMember_" . $this->data["e_id"];
                    if($this->data['pm_level']==""){
                        $sql = 'UPDATE "' . $tablename . '" SET pm_hangup=? WHERE pm_number = ? AND pm_pgnumber = ?';
                        $sth = $this->pdo->prepare($sql);
                        $sth->execute(array($this->data["pm_hangup"], $this->data["pm_number"], $this->data["pm_pgnumber"]));
                         $log = DL("修改企业用户") . ":【%s】(%s)" . DL("群组信息成功") . "<br /> " . DL("企 业 群 组") . " :【%s】(%s)<br />" . DL("默 认 群 组") . " :【%s】";
                    $log = sprintf($log
                            , $this->data['u_name']
                            , $this->data['pm_number']
                            , $this->data["pg_name"]
                            , $this->data['pm_pgnumber']
                            //, $this->data['pm_level']
                            , DL('是')
                            //, $this->data['pm_hangup'] == 1 ? DL('是') : DL('否')
                    );
                    }else{
                        $sql = 'UPDATE "' . $tablename . '" SET pm_level = ?,pm_hangup=? WHERE pm_number = ? AND pm_pgnumber = ?';
                        $sth = $this->pdo->prepare($sql);
                        $sth->execute(array($this->data["pm_level"], $this->data["pm_hangup"], $this->data["pm_number"], $this->data["pm_pgnumber"]));
                        $log = DL("修改企业用户") . ":【%s】(%s)" . DL("群组信息成功") . "<br /> " . DL("企 业 群 组") . " :【%s】(%s)<br />" . DL("用 户 级 别") . " :【%s】<br />" . DL("默 认 群 组") . " :【%s】";
                    $log = sprintf($log
                            , $this->data['u_name']
                            , $this->data['pm_number']
                            , $this->data["pg_name"]
                            , $this->data['pm_pgnumber']
                            , $this->data['pm_level']
                            , DL('是')
                           // , $this->data['pm_hangup'] == 1 ? DL('是') : DL('否')
                    );
                    }        
                    $this->log($log, 1, 0);
            } else {
                    $pm_number = $this->data["pm_number"];
                    $pm_level = $this->data["pm_level"];
                    $pm_pgnumber = $this->data["pm_pgnumber"];
                    $pm_hangup = $this->data["pm_hangup"];
                    if($this->data['pm_level']==""){
                        $user=new users($this->data);
                        $user_name=$user->hasUser($this->data['pm_number']);
                            if($user_name['u_sub_type']=="1"){
                                $this->data['pm_level'] = 255;
                            }else if($user_name['u_sub_type']=="2"){
                               $this->data['pm_level'] = 254;
                            }
                    }

                    $tablename = sprintf("T_PttMember_%s", $this->data["e_id"]);
                    $sql = "INSERT INTO \"$tablename\" (\"pm_number\", \"pm_level\", \"pm_pgnumber\",\"pm_hangup\") VALUES ('{$pm_number}', {$this->data['pm_level']}, '{$pm_pgnumber}',$pm_hangup)";
                    $sth = $this->pdo->prepare($sql);

                    $log = DL('企业群组') . ':【%s】(%s)' . DL('新增用户') . ':【%s】(%s) <br />' . DL('用户级别') . ':【%s】';
                    $log = sprintf($log
                            , $this->data["pg_name"]
                            , $this->data["pm_pgnumber"]
                            , $this->data["u_name"]
                            , $this->data['pm_number']
                            , $this->data['pm_level']
                           // , $this->data['pm_hangup'] == 1 ? DL('是') : DL('否')
                    );
                    try
                    {
                            $sth->execute();
                    } catch (Exception $ex) {
                        echo $ex->getMessage();
                    }

                    $this->log($log, 1, 0);
            }
    }

	public function save_v2() {
//var_dump($this->data);die;
		if ($this->data['pm_hangup'] == "") {
			$this->data['pm_hangup'] = 0;
		} else {
			$this->data['pm_hangup'] = 1;
		}
		$users = new users($this->data);
		$user_name = $users->hasUser($this->data['pm_number']);
		$user_name = $user_name['u_name'];
		$pg_name = $users->getPGinfo($this->data['pm_pgnumber']);
		$pg_name = $pg_name[0];
		if ($this->data["do"] == "edit") {
			$tablename = "T_PttMember_" . $this->data["e_id"];
			$sql = 'UPDATE "' . $tablename . '" SET pm_level = ?,pm_hangup=? WHERE pm_number = ? AND pm_pgnumber = ?';
			$sth = $this->pdo->prepare($sql);
			$sth->execute(array($this->data["pm_level"], $this->data["pm_hangup"], $this->data["pm_number"], $this->data["pm_pgnumber"]));

			$log = DL("修改企业用户") . ":【%s】(%s)" . DL("群组信息成功") . "<br />" . DL("用 户 级 别") . " :【%s】";
			$log = sprintf($log
				, $user_name
				, $this->data['pm_number']
				, $this->data['pm_level']
				//, $this->data['pm_hangup'] == 1 ? DL('是') : DL('否')
			);
			$this->log($log, 1, 0);
		} else {
			$tablename = sprintf("T_PttMember_%s", $this->data["e_id"]);
			$sql = 'INSERT INTO "%s" (
                            pm_number,
                            pm_level,
                            pm_hangup,
                            pm_pgnumber
                    )
                    VALUES
                            (?,?,?,?)';
			$sql = sprintf($sql, $tablename);
			$sth = $this->pdo->prepare($sql);
			try
			{
				$sth->execute(array($this->data["pm_number"], $this->data["pm_level"], $this->data["pm_hangup"], $this->data["pm_pgnumber"]));
			} catch (Exception $ex) {

			}
			$log = DL('企业群组') . ':【%s】(%s)<br />' . DL('新增用户') . ':【%s】(%s) <br />' . DL('群组级别') . ':【%s】';
			$log = sprintf($log
				, $pg_name['pg_name']
				, $pg_name['pg_number']
				, $user_name
				, $this->data['pm_number']
				, $this->data['pm_level']
				//, $this->data['pm_hangup'] == 1 ? DL('是') : DL('否')
			);
			$this->log($log, 1, 0);
		}
	}

}
