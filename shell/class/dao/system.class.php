<?php

/**
 * 系统实体类，包含 登录，检查异地登录，注销，检查管理员权限，修改密码，显示公告
 * @package OMP_Common_dao
 * @require {@see db}
 */
class system extends db {

    public function __construct($data = NULL) {
            parent::__construct();
            $this->data = $data;
    }

    public function login() {
            if (isset($_SESSION['ag_number'])) {
                    echo $_SESSION['ag_number'];
                    return 1;
            } else {
                    return 0;
            }
    }

    public function checkOtherLogin ( $own )
    {
        $sql = 'SELECT * FROM "T_Agents" WHERE ag_number = :ag_number';
        $sth = $this->pdo->prepare($sql);
        $sth->bindValue ( ':ag_number' , $own["ag_number"] , PDO::PARAM_STR );
        $sth->execute();
        $result = $sth->fetch();
        if ( $result === false )
        {
            //$this->log ( sprintf ( '该帐户不存在或者已被删除,请联系管理员'  ) , 7 , 1 );
            $info['id'] = TRUE;
        } else {
            $info['db_ag_lastlogin_ip'] = $result['ag_lastlogin_ip'];
            $info['ag_lastlogin_ip'] = $own['ag_lastlogin_ip'];
            if ( $info['ag_lastlogin_ip'] == $info['db_ag_lastlogin_ip'] &&$result['ag_session_id'] ==  session_id())
            {
                    $info['status'] = FALSE;
            } else {

                    $info['msg'] = L('您的帐号已在别处登录');
                    $this->log(sprintf(DL('该帐户已在其他地方登录 本地IP： 【%s】 异地IP： 【%s】'), $info['ag_lastlogin_ip'], $info['db_ag_lastlogin_ip']), 7, 1);
                    $info['status'] = TRUE;
            }
        }
        return $info;
}

/**
 * 验证代理商子帐户是否异地登录
 * @param type $own
 * @return boolean
 */
public function checkOtherLogin_as ( $own )
    {
        $sql = 'SELECT * FROM "T_Agentsub" WHERE as_account_id = :as_account_id';
        $sth = $this->pdo->prepare($sql);
        $sth->bindValue ( ':as_account_id' , $own["as_account_id"] , PDO::PARAM_STR );
        $sth->execute();
        $result = $sth->fetch();
        if ( $result === false )
        {
            //$this->log ( sprintf ( '该帐户不存在或者已被删除,请联系管理员'  ) , 7 , 1 );
            $info['id'] = TRUE;
        } else {
            
            $info['db_as_lastlogin_ip'] = $result['as_lastlogin_ip'];
            $info['as_lastlogin_ip'] = $own['as_lastlogin_ip'];

            if ( $info['as_lastlogin_ip'] == $info['db_as_lastlogin_ip'] &&$result['as_session_id'] ==  session_id())
            {
                    $info['status'] = FALSE;
            } else {

                    $info['msg'] = L('您的帐号已在别处登录');
                    $this->log(sprintf(DL('该帐户已在其他地方登录 本地IP： 【%s】 异地IP： 【%s】'), $info['as_lastlogin_ip'], $info['db_as_lastlogin_ip']), 7, 1);
                    $info['status'] = TRUE;
            }
        }
        return $info;
}
/**
 * 验证密码是否被修改
 * @return boolean
 */
    public function check() {
        $sql = 'SELECT * FROM "T_Agents" WHERE ag_number = :username';
        $sth = $this->pdo->prepare ( $sql );
        $sth->bindValue ( ':username' , $_SESSION['ag']['ag_number'] , PDO::PARAM_STR );
        $sth->execute ();
        $result = $sth->fetch ( PDO::FETCH_ASSOC );
        $info['status'] = FALSE;
        if($_SESSION['as_account_id']==""){
            if($result['ag_pswd']!= $_SESSION['ag']['ag_pswd']){
                $info['msg'] = L('您的密码已被修改,请重新登陆');
                $info['status'] = TRUE;
            }
        }else{
                $sql1 = 'SELECT * FROM "T_Agentsub" WHERE as_account_id = :username';
                $sth1 = $this->pdo->prepare ( $sql1 );
                $sth1->bindValue ( ':username' , $_SESSION['ag_as']['as_account_id'] , PDO::PARAM_STR );
                $sth1->execute ();
                $result1 = $sth1->fetch ( PDO::FETCH_ASSOC );
                if($result1['as_passwd']!= $_SESSION['ag_as']['as_passwd']){
                    $info['msg'] = L('您的密码已被修改,请重新登陆');
                    $info['status'] = TRUE;
                }
        }
        return $info;
    }
//验证登陆
    public function checkLogin ()
    {
        $sql = 'SELECT * FROM "T_Agents" WHERE ag_number = :username OR ag_name=:ag_name ';
        $sth = $this->pdo->prepare ( $sql );
        
        $sth->bindValue ( ':username' , $this->data["username"] , PDO::PARAM_STR );
        $sth->bindValue ( ':ag_name' , $this->data["username"], PDO::PARAM_STR );
        $sth->execute ();
        $result = $sth->fetch ( PDO::FETCH_ASSOC );
        if ($result) {
                $_SESSION['as_account_id']="";
                if ($this->data["password"] !== $result['ag_pswd']) {
                        //$this->log(DL('密码错误'), 7, 2, $result);
                        return -2;
                }
                else
                {
                        $_SESSION['ag_number'] = $result["ag_number"];
                        $_SESSION['login_number'] = $result["ag_number"];
                        $result['ag_lastlogin_ip'] = $_SERVER["REMOTE_ADDR"];
                        $session=session_id();
                        $_SESSION['ag'] = $result;
                        $_SESSION['ag']['ag_lastlogin_time']=date("Y-m-d H:i:s",  time());
                        $data['ag_lastlogin_time'] = date ( 'Y-m-d H:i:s' );
                        $data['ag_lastlogin_ip'] = $_SERVER["REMOTE_ADDR"];

                        $sql_upd = 'UPDATE "T_Agents" SET ag_lastlogin_time = :lastlogintime,ag_lastlogin_ip=:ag_lastlogin_ip,ag_session_id=:ag_session_id WHERE ag_number = :ag_number OR ag_name=:ag_name';
                        $sth = $this->pdo->prepare($sql_upd);
                        $sth->bindValue(':ag_number', $this->data["username"], PDO::PARAM_STR);
                        $sth->bindValue(':ag_name', $this->data["username"], PDO::PARAM_STR);
                        $sth->bindValue ( ':lastlogintime' , $data['ag_lastlogin_time'] , PDO::PARAM_STR );
                        $sth->bindValue ( ':ag_lastlogin_ip' , $data['ag_lastlogin_ip'] , PDO::PARAM_STR );
                        $sth->bindValue ( ':ag_session_id' , $session , PDO::PARAM_STR );
                        $data = $sth->execute();
                        $this->log(DL('登录成功') . '。 IP：' . $_SERVER["REMOTE_ADDR"], 7);
                        return 0;
                }
        } else {
            
                $sql1 = 'SELECT * FROM "T_Agentsub" WHERE as_account_id = :username';
                $sth1 = $this->pdo->prepare ( $sql1 );
                $sth1->bindValue ( ':username' , $this->data["username"] , PDO::PARAM_STR );
                $sth1->execute ();
                $result1 = $sth1->fetch ( PDO::FETCH_ASSOC );
                if($result1){
                        if ($this->data["password"] !== $result1['as_passwd']) {
                                //$this->log(DL('密码错误'), 7, 2, $result);
                                return -2;
                        }
                        else
                        {
                                $_SESSION['as_account_id'] = $result1["as_account_id"];
                                $_SESSION['login_number'] = $result1["as_account_id"];
                                $result1['as_lastlogin_ip'] = $_SERVER["REMOTE_ADDR"];
                                $session=session_id();

                                $_SESSION['ag_as'] = $result1;

                                $sql2 = 'SELECT * FROM "T_Agents" WHERE ag_number = :ag_number';
                                $sth2 = $this->pdo->prepare ( $sql2 );

                                $sth2->bindValue ( ':ag_number' ,$result1["as_parent_id"] , PDO::PARAM_STR );
                                $sth2->execute ();
                                $result2 = $sth2->fetch ( PDO::FETCH_ASSOC );
                                $_SESSION['ag']=$result2;
                                $_SESSION['ag']['ag_lastlogin_time']=date("Y-m-d H:i:s",  time());
                                $data['as_lastlogin_time'] = date ( 'Y-m-d H:i:s' );
                                $data['as_lastlogin_ip'] = $_SERVER["REMOTE_ADDR"];
                                //$sql_upd = 'UPDATE "T_Agents" SET ag_lastlogin_time = :lastlogintime,ag_lastlogin_ip=:ag_lastlogin_ip,ag_session_id=:ag_session_id WHERE ag_number = :ag_number OR ag_name=:ag_name';
                                $sql_upd = 'UPDATE "T_Agentsub" SET as_lastlogin_time = :as_lastlogin_time,as_lastlogin_ip=:as_lastlogin_ip,as_session_id=:as_session_id WHERE as_account_id = :as_account_id';
                                $sth = $this->pdo->prepare($sql_upd);
                                $sth->bindValue(':as_account_id', $this->data["username"], PDO::PARAM_STR);
                                $sth->bindValue ( ':as_lastlogin_time' , $data['as_lastlogin_time'] , PDO::PARAM_STR );
                                $sth->bindValue ( ':as_lastlogin_ip' , $data['as_lastlogin_ip'] , PDO::PARAM_STR );
                                $sth->bindValue ( ':as_session_id' , $session , PDO::PARAM_STR );
                                $data = $sth->execute();
                                $this->log(DL('登录成功') . '。 IP：' . $_SERVER["REMOTE_ADDR"], 7);
                                return 0;
                        }
               }else{
                    return -1;
               }
            //return -1;
        }
    }

//配置管理员信息
	public function superAdmin() {
		if ($this->data["pwd"] != $this->data["rpwd"]) {
			return "pwd";
		} else {
			$sql = 'INSERT INTO "public"."T_OperationManager" ("om_id", "om_pswd") VALUES (:user, :pwd)';
			$sth = $this->pdo->prepare($sql);
			$sth->bindValue(':user', $this->data["user"], PDO::PARAM_INT);
			$sth->bindValue(':pwd', $this->data["pwd"], PDO::PARAM_STR);
			$data = $sth->execute();
			if ($data) {
				return $this->data["user"];
			}
		}
	}

    //修改密码
    public function chgPwd() {
        if($this->data['type']=="ag_sub"){
            if ($_SESSION['ag_as']['as_passwd'] != $this->data['old_pwd']) {
                    $msg['status'] = -1;
                    $msg['msg'] = L('原密码不正确');
                    $this->log(DL($msg['msg']), 7, 0);
            } else if ($this->data['new_pwd'] !== $this->data['new_rpwd']) {
                    $msg['status'] = -1;
                    $msg['msg'] = L('新密码两次输入不一致');
                    $this->log(DL($msg['msg']), 7, 0);
            } else {
                    $as_id = $_SESSION['ag_as']['as_account_id'];
                    $sql = 'UPDATE "T_Agentsub" SET as_passwd = :as_passwd WHERE as_account_id = :username';
                    $sth = $this->pdo->prepare($sql);
                    $sth->bindValue(':as_passwd', $this->data['new_pwd']);
                    $sth->bindValue(':username', $as_id);
                    $sth->execute();
                    $msg['status'] = 1;
                    $msg['msg'] = L('密码修改成功');
                    $this->log(DL($msg['msg']), 7, 0);
            }
        }else{
            if ($_SESSION['ag']['ag_pswd'] != $this->data['old_pwd']) {
                    $msg['status'] = -1;
                    $msg['msg'] = L('原密码不正确');
                    $this->log(DL($msg['msg']), 7, 0);
            } else if ($this->data['new_pwd'] !== $this->data['new_rpwd']) {
                    $msg['status'] = -1;
                    $msg['msg'] = L('新密码两次输入不一致');
                    $this->log(DL($msg['msg']), 7, 0);
            } else {
                    $ag_id = $_SESSION['ag']['ag_number'];
                    $sql = 'UPDATE "T_Agents" SET ag_pswd = :ag_pwd WHERE ag_number = :username';
                    $sth = $this->pdo->prepare($sql);
                    $sth->bindValue(':ag_pwd', $this->data['new_pwd']);
                    $sth->bindValue(':username', $ag_id);
                    $sth->execute();
                    $msg['status'] = 1;
                    $msg['msg'] = L('密码修改成功');
                    $this->log(DL($msg['msg']), 7, 0);
            }
        }
            

            return $msg;
            /*
    $om_id = $_SESSION['own']['om_id'];
    $sql = 'SELECT* FROM "T_OperationManager" WHERE om_id = :username ';
    $sth = $this->pdo->prepare($sql);
    $sth->bindValue(':username', $om_id, PDO::PARAM_STR);
    $sth->execute();
    $result = $sth->fetch();
    if ($result) {
    if ($this->data["old_pwd"] != $result['om_pswd']) {
    $this->log($_SESSION['own']['om_id'] . '修改密码失败', 7, 1);
    return 0;
    } else {
    if ($this->data['new_pwd'] == $this->data['new_rpwd']) {
    $sql_upd = 'UPDATE "T_OperationManager"SET om_pswd = :om_pwd WHERE om_id = :username';
    $sth = $this->pdo->prepare($sql_upd);
    $sth->bindValue(':om_pwd', $this->data['new_pwd']);
    $sth->bindValue(':username', $om_id);
    $sth->execute();

    $this->log($_SESSION['own']['om_id'] . '修改密码成功', 7);
    return 1;
    } else {
    return 2;
    }
    }
    } else {

    }
     *
     */
    }

	function getWhere($order = false) {
		$where = " WHERE 1=1 ";
		$where .= "AND am_id  = " . "'" . $var . "'";
		if ($order) {
			$where .= ' ORDER BY am_id desc ';
		}

		return $where;
	}

	//获取信息
	public function getList() {
		//@ 该函数即将过时 2014-09-16 10:47:19
		//return NULL;
		$om_id = $_SESSION['own']['om_id'];
		$sql = 'SELECT* FROM "T_OperationManager" WHERE om_id = :username ';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':username', $om_id, PDO::PARAM_STR);
		$sth->execute();
		$result = $sth->fetch();
		return $result;
	}

	private function getByArea($str) {
		// @即将过时 2014-09-16
		if (!empty($str)) {
			$sql = 'SELECT "am_name" FROM "T_AreaManage" WHERE am_id in(' . $str . ')';
			$sth = $this->pdo->query($sql);
			$data = $sth->fetchAll(PDO::FETCH_ASSOC);
			$area = '';
			foreach ($data as $item) {
				$area .= $item['am_name'] . " ";
			}
			return $area;
		}
	}

	//公告列表
	public function getAnWhere($order = false) {
		$area = new area($_REQUEST);
		$where = " WHERE an_status = 1";
		$where .= $area->getAcl('an_area', $_SESSION['own']['om_area']);
		if ($order) {
			$where .= "ORDER BY an_time DESC";
		}
		return $where;
	}

	public function getAnList($limit) {
		$sql = 'SELECT * FROM "T_Announcement"';
		$sql .= $this->getAnWhere(TRUE);
		$sql .= $limit;
		$sth = $this->pdo->query($sql);
		$result = $sth->fetchAll();

		return $result;
		// @以下将过时
		$om_id = $_SESSION['own']['om_id'];
		$sql = 'SELECT* FROM "T_OperationManager" WHERE om_id = :username ';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':username', $om_id, PDO::PARAM_STR);
		$sth->execute();
		$result = $sth->fetchAll();

		foreach ($result as &$item) {
			$areaid = str_replace('|', ',', trim($item['om_area'], '|'));
			$item['om_area'] = $item['om_area'] == 0 ? 全部 : $areaid;
		}
		if ($result[0]['om_area'] == "全部") {
			$sql = 'SELECT * FROM"public"."T_Announcement" WHERE  an_status = 1';
		} else {
			$sql = 'SELECT * FROM "T_Announcement" WHERE an_area_id in(' . $areaid . ') AND an_status = 1';
		}

		$sql = $sql . $limit;
		$pdoStatement = $this->pdo->query($sql);
		$result = $pdoStatement->fetchAll();
		return $result;
	}

	public function getAnTotal() {
		$sql = 'SELECT COUNT(an_id) AS total FROM "T_Announcement"';
		$sql .= $this->getAnWhere();

		$sth = $this->pdo->query($sql);
		$result = $sth->fetch();

		return $result["total"];
	}

//统计条数
	public function getTotal() {
		$om_id = $_SESSION['own']['om_id'];
		$sql = 'SELECT* FROM "T_OperationManager" WHERE om_id = :username';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':username', $om_id, PDO::PARAM_STR);
		$sth->execute();
		$result = $sth->fetchAll();
		//重组变量
		foreach ($result as &$item) {
			$areaid = str_replace('|', ',', trim($item['om_area'], '|'));
			$item['om_area'] = $item['om_area'] == 0 ? 全部 : $areaid;
		}
		if ($result[0]['om_area'] == "全部") {
			$sql = 'SELECT COUNT(an_id)AS total FROM"public"."T_Announcement" WHERE  an_status = 1';
		} else {
			$sql = 'SELECT COUNT(an_id)AS total FROM"public"."T_Announcement"WHERE an_area_id in(' . $areaid . ') AND an_status =1';
		}
		$pdoStatement = $this->pdo->query($sql);
		$result = $pdoStatement->fetch();
		return $result["total"];
	}

//查询有多少设备。
	public function getDevice() {
		$om_id = $_SESSION['own']['om_id'];
		$sql = 'SELECT* FROM "T_OperationManager" WHERE om_id = :username ';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':username', $om_id, PDO::PARAM_STR);
		$sth->execute();
		$result = $sth->fetchAll();
		//重组变量
		foreach ($result as &$item) {
			$areaid = str_replace('|', ',', trim($item['om_area'], '|'));
			$item['om_area'] = $item['om_area'] == 0 ? 全部 : $areaid;
		}
		if ($result[0]['om_area'] == "全部") {
			$sql = 'SELECT COUNT(d_id)AS total FROM"public"."T_Device"';
		} else {
			$sql = 'SELECT COUNT(d_id)AS total FROM "T_Device" WHERE d_area in(' . $areaid . ')';
		}
		//重组变量
		$pdoStatement = $this->pdo->query($sql);
		$result = $pdoStatement->fetch();
		return $result["total"];
	}

	//查询有多少企业。
	public function getEn() {
		$om_id = $_SESSION['own']['om_id'];
		$sql = 'SELECT* FROM "T_OperationManager" WHERE om_id = :username ';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':username', $om_id, PDO::PARAM_STR);
		$sth->execute();
		$result = $sth->fetchAll();
		//重组变量
		foreach ($result as &$item) {
			$areaid = str_replace('|', ',', trim($item['om_area'], '|'));
			$item['om_area'] = $item['om_area'] == 0 ? 全部 : $areaid;
		}
		if ($result[0]['om_area'] == "全部") {
			$sql = 'SELECT COUNT(e_id)AS total FROM"public"."T_Enterprise"';
		} else {
			$sql = 'SELECT COUNT(e_id)AS total FROM "T_Enterprise" WHERE e_area in(' . $areaid . ')';
		}
		//重组变量
		$pdoStatement = $this->pdo->query($sql);
		$result = $pdoStatement->fetch();
		return $result["total"];
	}

	//公告详情页
	public function pro_details() {
		$sql = 'SELECT* FROM "T_Announcement" WHERE an_id = :an_id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':an_id', $this->data["an_id"], PDO::PARAM_INT);
		$sth->execute();
		$data = $sth->fetch();

		return $data;
	}

}
