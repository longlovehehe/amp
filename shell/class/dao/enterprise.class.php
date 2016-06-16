<?php

/**
 * 企业管理类
 * @category OMP
 * @package OMP_Enterprise_dao
 * @require {@see db} {@see device} {@see area} {@see users}
 */
class enterprise extends db {

	public $page;
	public $e_status;

/**
 * 企业构造函数，传递数据源给数据
 * @param type $data
 */
public function __construct($data) {
	parent::__construct();
	$this->data = $data;
	$this->page = new page();
	//企业状态
	$this->e_status = array(
	0=>'不启用',
	1=>'启用',
	2=>'发布处理中',
	3=>'发布失败',
	5=>'企业创建中',
	6=>'企业删除中',
	7=>'企业迁移中',
	8=>'企业迁移失败',
	9=>'企业创建失败'
	);
}

	/**
	 *
	 * 更新企业状态
	 * @param type $data
	 * @throws Exception
	 */
	public function updateStatus($data) {
		if ($data[0] == '' || $data[1] == '') {
			throw new Exception('enterprise_id is null or enterprise_status is null', -1);
		}
		//企业操作历史记录
		$filed = $this->getEnterpriseFields('e_status',$data[1]);

		$sql = 'UPDATE "T_Enterprise" SET e_status=? WHERE e_id=?';
		$sth = $this->pdo->prepare($sql);
		try
		{
			$sth->execute($data);
			$this->make_history_args($filed,array('e_id'=>$data[1],'e_status'=>$data[0]));
		} catch (Exception $ex) {
			// throw new Exception($ex->getMessage(), -1);
			$this->log(DL("同步数据失败"), 1, 1);
		}
	}

	/**
	 * 获取设备列表
	 * @return type
	 */
	public function getDeviceList() {
		if ($this->data["do"] == "mds") {
			$pagesql = "SELECT count(e_mds_id) as total FROM \"T_Enterprise\" WHERE \"e_mds_id\"=" . $this->data["device_id"];
		} else {
			$pagesql = "SELECT count(e_vcr_id) as total FROM \"T_Enterprise\" WHERE \"e_vcr_id\"=" . $this->data["device_id"];
		}

		$page = $this->page->fastGetPage($pagesql, $this->pdo, $this->data);
		$result["page"] = $page;

		switch ($this->data["do"]) {
			case "mds":
				$sql = "SELECT e_id,e_name,e_mds_users,e_mds_call,e_mds_phone,e_mds_dispatch,e_mds_gvs FROM \"T_Enterprise\"  WHERE e_mds_id=:e_mds_id ORDER BY e_id";
				break;
			case "vcr":
				$sql = "SELECT e_id,e_name,e_vcr_audiorec,e_vcr_videorec,e_vcr_space () FROM \"T_Enterprise\" WHERE e_vcr_id=:e_vcr_id";
				break;
		}

		$sql = $sql . $page["limit"];

		$sth = $this->pdo->prepare($sql);
		switch ($this->data["do"]) {
			case "mds":
				$sth->bindValue(':e_mds_id', $this->data["device_id"], PDO::PARAM_INT);
				break;
			case "vcr":
				$sth->bindValue(':e_vcr_id', $this->data["device_id"], PDO::PARAM_INT);
				break;
		}

		$sth->execute();
		$result["fetchall"] = $sth->fetchAll(PDO::FETCH_ASSOC);
		$users = new users($_REQUEST);
		$usernum = $users->getTotal(FALSE);
		$array = array();
		foreach ($result["fetchall"] as $key => $value) {

			$phone_num = $users->getetotal(1, $value['e_id']);
			$dispatch_num = $users->getetotal(2, $value['e_id']);
			$gvs_num = $users->getetotal(3, $value['e_id']);
			$result["fetchall"][$key]['phone_num'] = $phone_num;
			$result["fetchall"][$key]['dispatch_num'] = $dispatch_num;
			$result["fetchall"][$key]['gvs_num'] = $gvs_num;
		}
		return $result;
	}

	/**
	 * 保存企业
	 * @return string
	 */
	function save() {
		$this->data["e_create_time"] = date("Y-m-d H:i:s", time());

		//检查并发数
		if ($this->data["e_id"] != "") {
			$sql = <<<SQL
                        SELECT count(*) as total FROM "T_User" WHERE u_e_id ={$this->data["e_id"]}
SQL;
			$total = $this->total($sql);

			if ($total > $this->data["e_mds_users"]) {
				$msg["status"] = -1;
				$log = DL("保存失败，企业的用户数多于编辑的用户数 现在用户数%s保存的用户数%s");
				$log = sprintf($log
					, $total
					, $this->data["e_mds_users"]
				);
				$this->log($log, 1, 2);
				$msg["msg"] = L('保存失败，企业的用户数多于编辑的用户数');
				return $msg;
			}
		}

		// 勾选了录制功能的企业
		// if ($this->data["e_has_vcr"] == "") {
			return $this->saveMDS();
		// } else {
			// return $this->saveVCR();
		// }
	}

	/**
	 * 保存具有GQT-Server的企业
	 * @return string
	 * @throws ErrorException
	 */
	function saveMDS() {
		$edit = false;
		$tmpe_id = $this->getSEQ();

		if ($this->data["e_id"] != "") {
			$edit = true;
		}
		if($this->data['e_vcr_id'] != null && $this->data['e_rs_rec'] == '0')
        {
            $this->data['e_rs_rec'] = $this->data['e_mds_phone']*2 +$this->data['e_mds_dispatch'] + $this->data['e_mds_gvs'];    
        }
		if ($edit) {
			$sql = 'UPDATE "T_Enterprise"SET '
			. 'e_area=:e_area,'
			. 'e_status=:e_status,e_name =:e_name,e_regis_code=:e_regis_code,e_mds_users =:e_mds_users,e_mds_call =:e_mds_call,e_has_vcr = :e_has_vcr,e_vcr_audiorec=:e_vcr_audiorec,e_vcr_videorec=:e_vcr_videorec,e_vcr_space=:e_vcr_space,e_storage_function=:e_storage_function,e_vcr_id=:e_vcr_id,e_pwd=:e_pwd,e_mds_phone=:e_mds_phone,e_mds_dispatch=:e_mds_dispatch,e_mds_gvs=:e_mds_gvs,e_contact_surname=:e_contact_surname,e_contact_name=:e_contact_name,e_contact_phone=:e_contact_phone,e_contact_fox=:e_contact_fox,e_addr=:e_addr,e_industry=:e_industry,e_contact_mail=:e_contact_mail,e_ss_id=:e_ss_id,e_rs_rec=:e_rs_rec,e_location=:e_location,e_remark=:e_remark  WHERE e_id =:e_id';
			//企业操作历史记录---获取修改前的企业信息
			$oldinfo = $this->getEnterpriseFields('*',$this->data["e_id"]);
		} else {
			$sql = 'INSERT INTO "public"."T_Enterprise" ("e_id", "e_name","e_regis_code", "e_area", "e_create_time", "e_mds_users", "e_mds_call","e_has_vcr","e_mds_id","e_vcr_audiorec","e_vcr_videorec","e_vcr_space","e_storage_function","e_vcr_id","e_pwd","e_status","e_mds_phone","e_mds_dispatch","e_mds_gvs","e_agents_id","e_contact_surname","e_contact_name","e_contact_phone","e_contact_fox","e_addr","e_industry","e_contact_mail","e_create_name","e_ag_path","e_ss_id","e_rs_rec","e_location","e_remark") VALUES (:e_id, :e_name,:e_regis_code, :e_area, :e_create_time, :e_mds_users, :e_mds_call,:e_has_vcr,:e_mds_id,:e_vcr_audiorec,:e_vcr_videorec,:e_vcr_space,:e_storage_function,:e_vcr_id,:e_pwd,:e_status,:e_mds_phone,:e_mds_dispatch,:e_mds_gvs,:e_agents_id,:e_contact_surname,:e_contact_name,:e_contact_phone,:e_contact_fox,:e_addr,:e_industry,:e_contact_mail,:e_create_name,:e_ag_path,:e_ss_id,:e_rs_rec,:e_location,:e_remark)';
		}

		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':e_name', $this->data["e_name"], PDO::PARAM_STR);
		$sth->bindValue(':e_regis_code', $this->data["e_regis_code"], PDO::PARAM_STR);
		$sth->bindValue(':e_pwd', $this->data["e_pwd"], PDO::PARAM_STR);

		$sth->bindValue(':e_mds_users', $this->data["e_mds_users"], PDO::PARAM_INT);
		$sth->bindValue(':e_mds_call', $this->data["e_mds_call"], PDO::PARAM_INT);
		$sth->bindValue(':e_has_vcr', $this->data["e_has_vcr"], PDO::PARAM_INT);

		$sth->bindValue(':e_vcr_audiorec', 0, PDO::PARAM_INT);
		$sth->bindValue(':e_vcr_videorec', 0, PDO::PARAM_INT);
		$sth->bindValue(':e_vcr_space', 0, PDO::PARAM_INT);
		$sth->bindValue(':e_storage_function', 0, PDO::PARAM_INT);
		if($this->data["e_status"] == '5' || $this->data["e_status"] == '0')
		{
			$sth->bindValue(':e_status', $this->data["e_status"], PDO::PARAM_INT);
		}
		else
		{
			$sth->bindValue(':e_status', 2, PDO::PARAM_INT);
		}
		$sth->bindValue(':e_vcr_id', $this->data["e_vcr_id"], PDO::PARAM_INT);

		$sth->bindValue(':e_mds_phone', $this->data['e_mds_phone'], PDO::PARAM_INT);
		$sth->bindValue(':e_mds_dispatch', $this->data['e_mds_dispatch'], PDO::PARAM_INT);
		$sth->bindValue(':e_mds_gvs', $this->data['e_mds_gvs'], PDO::PARAM_INT);
	        $sth->bindValue(':e_contact_surname', $this->data["e_contact_surname"], PDO::PARAM_STR);
	        $sth->bindValue(':e_contact_name', $this->data["e_contact_name"], PDO::PARAM_STR);
	        $sth->bindValue(':e_contact_phone', $this->data["e_contact_phone"], PDO::PARAM_STR);
	        $sth->bindValue(':e_contact_fox', $this->data["e_contact_fox"], PDO::PARAM_STR);
	        $sth->bindValue(':e_addr', $this->data["e_addr"], PDO::PARAM_STR);
	        $sth->bindValue(':e_industry', $this->data["e_industry"], PDO::PARAM_STR);
	        $sth->bindValue(':e_contact_mail', $this->data["e_contact_mail"], PDO::PARAM_STR);
	        $sth->bindValue(':e_ss_id', $this->data["e_ss_id"], PDO::PARAM_STR);                       
	        $sth->bindValue(':e_rs_rec', $this->data["e_rs_rec"], PDO::PARAM_STR);                             
		$sth->bindValue(':e_location', $this->data["e_location"], PDO::PARAM_STR);
        	$sth->bindValue(':e_remark', $this->data["e_remark"], PDO::PARAM_STR);                           
		if ($edit) {
			$sth->bindValue(':e_id', $this->data["e_id"], PDO::PARAM_INT);
			$sth->bindValue(':e_area', json_encode($this->data["e_area"]));
		} else {
			$sth->bindValue(':e_id', $tmpe_id, PDO::PARAM_INT);
			$sth->bindValue(':e_area', json_encode($this->data["e_area"]));
			$sth->bindValue(':e_mds_id', $this->data["e_mds_id"], PDO::PARAM_INT);
			$sth->bindValue(':e_create_time', $this->data["e_create_time"], PDO::PARAM_STR);
			
                                                     $sth->bindValue(':e_agents_id' ,  $this->data['e_agents_id'] );
                                                     $sth->bindValue(':e_create_name' ,  $_SESSION['ag']['ag_number'] );
                                                     $sth->bindValue(':e_ag_path' ,  $_SESSION['ag']['ag_path'] );
		}
		try
		{
                                            
            $sth->execute();
            //企业历史记录
            if($edit){
            	$newdata = $this->data;
            	if($oldinfo['e_status']!=1){
            		$newdata['e_status']=1;
            	}
            	$this->make_history_args($oldinfo,$newdata);
            }

		} catch (Exception $ex) {
			if ($ex->getCode() == 23505) {
				throw new ErrorException(L('企业名称已存在'), -1);
			}
			$event['id'] = $this->md5r();
			$event['msg'] = $ex->getCode() . $ex->getMessage();
			$msg["status"] = -1;
			$log = DL('企业 %s 失败，企业ID：%s 事件ID：%s');
			$log = sprintf($log
				, $this->data['e_name']
				, $this->data['e_id']
				, $event['id']
			);
			if ($edit) {
				$log = DL("修改")." ".$log;
				$msg["msg"] = L("修改")." " . L('企业 %s 失败，企业ID：%s 事件ID：%s');
				$msg["msg"] = sprintf($msg["msg"]
					, $this->data['e_name']
					, $this->data['e_id']
					, $event['id']
				);
			} else {
				$log = DL("创建") ." ". $log;
				$msg["msg"] = L("创建") ." ". L('企业 %s 失败，企业ID：%s 事件ID：%s');
				$msg["msg"] = sprintf($msg["msg"]
					, $this->data['e_name']
					, $this->data['e_id']
					, $event['id']
				);
			}

			$this->log($log, 0, 1, $event);

			return $msg;
		}
                          if ( ! $edit ){               
                            $data['em_id'] = $tmpe_id;
                            $data['em_name'] = $this->data['em_name'];
                            $data['em_ent_id'] = $tmpe_id;
                            $data['em_pswd'] = $this->data['em_pswd'];
                            $data['em_phone'] = $this->data['em_phone'];
                            $data['em_mail'] = $this->data['em_mail'];
                            $data['em_desc'] =  $this->data['em_desc'];
                            $data['em_safe_login'] = "";
                            $data['em_surname'] = $this->data['em_surname'];
                            $data['em_admin_name'] = $this->data['em_admin_name'];
                            $admin = new admins ( $data );
                            try {
                            $res = $admin->save ();
                            } catch (Exception $exc) {
                            	return $res;
                            }
                          }

		if (!$edit) {
			$this->data["e_id"] = $tmpe_id;
			$this->initDB();
		}
		$log = DL('企业【%s】成功，企业ID：【%s】，名称【%s】，区域【%s】，所属'.$_SESSION['ident'].'-Server【%s  %s】，企业用户数【%s】，手机用户数【%s】,调度台用户数【%s】，GVS用户数【%s】');
		$device = new device(array('d_id' => $this->data['e_mds_id']));
		$device_item = $device->getByid();
		$log = sprintf($log
			, $this->data['e_name']
			, $this->data['e_id']
			, $this->data['e_name']
			, mod_area_name(json_encode($this->data['e_area']))
			, $this->data['e_mds_id']
			, $device_item['d_ip1']
			, $this->data['e_mds_users']
			, $this->data['e_mds_phone']
			, $this->data['e_mds_dispatch']
			, $this->data['e_mds_gvs']
		);
		if ($edit) {
			$log = DL("修改") . " " . $log;
			$msg["msg"] = L("修改") . " " . L('企业【%s】成功，企业ID：【%s】，名称【%s】，区域【%s】，所属'.$_SESSION['ident'].'-Server【%s  %s】，企业用户数【%s】，手机用户数【%s】,调度台用户数【%s】，GVS用户数【%s】');
			$msg["msg"] = sprintf($msg["msg"]
				, $this->data['e_name']
				, $this->data['e_id']
				, $this->data['e_name']
				, mod_area_name(json_encode($this->data['e_area']))
				, $this->data['e_mds_id']
				, $device_item['d_ip1']
				, $this->data['e_mds_users']
				, $this->data['e_mds_phone']
				, $this->data['e_mds_dispatch']
				, $this->data['e_mds_gvs']
			);
		} else {
			$log = DL("创建") . " " . $log;
			$msg["msg"] = L("创建") . " " . L('企业【%s】成功，企业ID：【%s】，名称【%s】，区域【%s】，所属'.$_SESSION['ident'].'-Server【%s  %s】，企业用户数【%s】，手机用户数【%s】,调度台用户数【%s】，GVS用户数【%s】');
			$msg["msg"] = sprintf($msg["msg"]
				, $this->data['e_name']
				, $this->data['e_id']
				, $this->data['e_name']
				, mod_area_name(json_encode($this->data['e_area']))
				, $this->data['e_mds_id']
				, $device_item['d_ip1']
				, $this->data['e_mds_users']
				, $this->data['e_mds_phone']
				, $this->data['e_mds_dispatch']
				, $this->data['e_mds_gvs']
			);
		}
		$this->log($log, 1, 0);
		$msg["status"] = 0;
		
		$msg["e_id"] = $this->data["e_id"];
		return $msg;
	}

	/**
	 * 保存具有VCR的企业
	 * @return type
	 */
	function saveVCR() {
		$edit = false;
		$tmpe_id = $this->getSEQ();
		if ($this->data["e_id"] != "") {
			$edit = true;
		}

		if ($edit) {
			$sql = 'UPDATE "T_Enterprise"SET e_name =:e_name,e_area =:e_area,e_remark=:e_remark,e_mds_users =:e_mds_users,e_mds_call =:e_mds_call,e_vcr_audiorec=:e_vcr_audiorec,e_vcr_videorec=:e_vcr_videorec,e_vcr_space=:e_vcr_space,e_storage_function=:e_storage_function,e_has_vcr = :e_has_vcr,e_pwd=:e_pwd,e_status=:e_status ,e_vcr_id=:e_vcr_id WHERE e_id =:e_id';
				//企业操作历史记录---获取修改前的企业信息
				$oldinfo = $this->getEnterpriseFields('*',$this->data["e_id"]);
		} else {
			$sql = 'INSERT INTO "public"."T_Enterprise" ("e_id", "e_name", "e_area", "e_remark","e_create_time", "e_mds_id", "e_mds_users", "e_mds_call", "e_vcr_id", "e_vcr_audiorec", "e_vcr_videorec", "e_vcr_space", "e_storage_function","e_has_vcr","e_pwd","e_status") VALUES (:e_id, :e_name, :e_area, :e_remark,:e_create_time, :e_mds_id, :e_mds_users, :e_mds_call,:e_vcr_id, :e_vcr_audiorec, :e_vcr_videorec, :e_vcr_space, :e_storage_function,:e_has_vcr,:e_pwd,:e_status)';
		}

		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':e_name', $this->data["e_name"], PDO::PARAM_STR);
		$sth->bindValue(':e_area', $this->data["e_area"], PDO::PARAM_INT);
		$sth->bindValue(':e_remark', $this->data["e_remark"], PDO::PARAM_STR);
		$sth->bindValue(':e_pwd', $this->data["e_pwd"], PDO::PARAM_STR);

		$sth->bindValue(':e_mds_users', $this->data["e_mds_users"], PDO::PARAM_INT);
		$sth->bindValue(':e_mds_call', $this->data["e_mds_call"], PDO::PARAM_INT);
		$sth->bindValue(':e_vcr_audiorec', $this->data["e_vcr_audiorec"], PDO::PARAM_INT);
		$sth->bindValue(':e_vcr_videorec', $this->data["e_vcr_videorec"], PDO::PARAM_INT);
		$sth->bindValue(':e_vcr_space', $this->data["e_vcr_space"], PDO::PARAM_INT);
		$sth->bindValue(':e_storage_function', $this->data["e_storage_function"], PDO::PARAM_INT);
		$sth->bindValue(':e_has_vcr', 1, PDO::PARAM_INT);
		$sth->bindValue(':e_status', 2, PDO::PARAM_INT);

		if ($edit) {
			$sth->bindValue(':e_id', $this->data["e_id"], PDO::PARAM_INT);
			$sth->bindValue(':e_vcr_id', $this->data["e_vcr_id"], PDO::PARAM_INT);
		} else {
			$sth->bindValue(':e_id', $tmpe_id, PDO::PARAM_INT);
			$sth->bindValue(':e_create_time', $this->data["e_create_time"], PDO::PARAM_STR);
			$sth->bindValue(':e_mds_id', $this->data["e_mds_id"], PDO::PARAM_INT);
			$sth->bindValue(':e_vcr_id', $this->data["e_vcr_id"], PDO::PARAM_INT);
		}
		try
		{
			$sth->execute();
			if (!$edit) {
				$this->data["e_id"] = $tmpe_id;
				$this->initDB();
			}else{
		            //企业历史记录
		            $this->make_history_args($oldinfo,$this->data);
			}
		} catch (Exception $e) {
			$msg["status"] = -1;
			$msg["msg"] = $e->getMessage();
			return $msg;
		}
		$msg["status"] = 0;
		if ($edit) {

			$msg["msg"] = L("企业修改成功【具有VCR功能】");
		} else {

			$msg["msg"] = L("企业添加成功【具有VCR功能】");
		}
		$msg["e_id"] = $this->data["e_id"];
		return $msg;
	}

	/**
	 * 权限检查器 - 停用
	 */
	function getAcl() {

	}

	/**
	 * 条件检查器
	 * @param type $order
	 * @return string
	 */
    function getWhere ( $order = false )
    {
        $e_agents_id = $this->data['ag_number'];
        $where = " WHERE 1=1 AND e_ag_path LIKE E'%".$e_agents_id."%' AND e_id!=999999 AND e_status !=6";
        if ( trim ( ( int ) $this->data["e_id"] ) > 0 )
        {
                    $where .= "AND TEXT(e_id) LIKE E'%" . (int) $this->data["e_id"] . "%'";
        }
        if ( $this->data["e_name"] != "" )
        {
            $where .= "AND e_name LIKE E'%" . addslashes($this->data["e_name"]) . "%'";
        }
        
        if ( $this->data["e_create_name"] != "" )
        {
           $where .= "AND e_create_name = '" . $this->data["e_create_name"]."'";
        }
        if ( $this->data["e_status"] != "" )
        {
            if($this->data["e_status"] == "3")
        	{
        		$where .= "AND (e_status = 3 or e_status =4)" ;
        	}
        	else
        	{
            	$where .= "AND e_status = " . $this->data["e_status"];
        	}
        }
        if ( $this->data["e_mds_id"] != "" )
        {
            $where .= "AND e_mds_id = " . $this->data["e_mds_id"];
        }
        if ( $this->data["e_vcr_id"] != "" )
        {
            $where .= "AND e_vcr_id = " . $this->data["e_vcr_id"];
        }
        if ( $this->data["do"] == "console" )
        {
            $where .= "AND e_id != " . $this->data["ec_id"];
        }
        if ( $this->data["e_area"] == "" )
        {
            $this->data["e_area"] = "#";
        }
        $area = new area ( $_REQUEST );
        $where .= $area->getAcl ( 'e_area' , $this->data["e_area"] );
        if ( $order )
        {
            $where .= ' ORDER BY e_id';
        }
        return $where;
    }
     function getWhere_permit ( $order = false )
    {
        $e_agents_id = $this->data['ag_number'];
        $where = " WHERE 1=1 AND e_ag_path LIKE E'%".$e_agents_id."%' AND e_create_name= '".$this->data['ag_number']."'";
        if ( trim ( ( int ) $this->data["e_id"] ) > 0 )
        {
                    $where .= "AND TEXT(e_id) LIKE E'%" . addslashes((int) $this->data["e_id"]) . "%'";
        }
        if ( $this->data["e_name"] != "" )
        {
            $where .= "AND e_name LIKE E'%" . addslashes($this->data["e_name"]) . "%'";
        }
        
        if ( $this->data["e_create_name"] != "" )
        {
           $where .= "AND e_create_name = '" . $this->data["e_create_name"]."'";
        }
        if ( $this->data["e_status"] != "" )
        {
            $where .= "AND e_status = " . $this->data["e_status"];
        }
        if ( $this->data["e_mds_id"] != "" )
        {
            $where .= "AND e_mds_id = " . $this->data["e_mds_id"];
        }
        if ( $this->data["e_vcr_id"] != "" )
        {
            $where .= "AND e_vcr_id = " . $this->data["e_vcr_id"];
        }
        if ( $this->data["do"] == "console" )
        {
            $where .= "AND e_id != " . $this->data["ec_id"];
        }
        if ( $this->data["e_area"] == "" )
        {
            $this->data["e_area"] = "#";
        }
        $area = new area ( $_REQUEST );
        $where .= $area->getAcl ( 'e_area' , $this->data["e_area"] );
        if ( $order )
        {
            $where .= ' ORDER BY e_id';
        }
        return $where;
    }
    /**
     * 条件检查器
     * @param type $order
     * @return string
     */
        function getWhere_record ( $order = false )
        {
            if($this->data['ag_number']=="0"){
                $where = " WHERE 1=1 AND er_agents_id ='{$_SESSION['ag']['ag_number']}'";
            }else{
                $where = " WHERE 1=1 AND er_ag_path like '%|0||{$_SESSION['ag']['ag_number']}||{$this->data['ag_number']}|%'";
            }
            if ( trim ( ( int ) $this->data["er_id"] ) > 0 )
            {
                $where .= "AND TEXT(er_id) LIKE '%" . ( int ) $this->data["er_id"] . "%'";
            }
            if ( $this->data["e_name"] != "" )
            {
                    $where .= "AND e_name LIKE '%" . $this->data["e_name"] . "%'";
            }
            if ($this->data["e_status"] != "") {
                    $where .= "AND e_status = " . $this->data["e_status"];
            }
            if ($this->data["e_mds_id"] != "") {
                    $where .= "AND e_mds_id = " . $this->data["e_mds_id"];
            }
            if ($this->data["e_vcr_id"] != "") {
                    $where .= "AND e_vcr_id = " . $this->data["e_vcr_id"];
            }
            if ($this->data["do"] == "console") {
                    $where .= "AND e_id != " . $this->data["ec_id"];
            }
            if ($this->data["e_area"] == "") {
                    $this->data["e_area"] = "#";
            }
            if ( $this->data["e_create_name"] != "" )
            {
               $where .= "AND e_create_name = '" . $this->data["e_create_name"]."'";
            }
            $area = new area($_REQUEST);
            $where .= $area->getAcl('er_area', $this->data["e_area"]);
            if ($order) {
                    $where .= ' ORDER BY er_id';
            }
            return $where;
        }
        /**
     * 条件检查器
     * @param type $order
     * @return string
     */
    function getWhere_ag ( $order = false )
    {
        $where = " WHERE 1=1 AND e_agents_id='{$this->data['ag_number']}'";
        if ( trim ( ( int ) $this->data["e_id"] ) > 0 )
        {
            $where .= "AND TEXT(e_id) LIKE E'%" . ( int ) $this->data["e_id"] . "%'";
        }
        if ( $this->data["e_name"] != "" )
        {
                $where .= "AND e_name LIKE '%" . $this->data["e_name"] . "%'";
        }

        if ($this->data["e_status"] != "") {
                $where .= "AND e_status = " . $this->data["e_status"];
        }

        if ($this->data["e_mds_id"] != "") {
                $where .= "AND e_mds_id = " . $this->data["e_mds_id"];
        }

        if ($this->data["e_vcr_id"] != "") {
                $where .= "AND e_vcr_id = " . $this->data["e_vcr_id"];
        }
        if ($this->data["do"] == "console") {
                $where .= "AND e_id != " . $this->data["ec_id"];
        }

        if ($this->data["e_area"] == "") {
                $this->data["e_area"] = "#";
        }
        $area = new area($_REQUEST);
        $where .= $area->getAcl('e_area', $this->data["e_area"]);

            if ($order) {
                    $where .= ' ORDER BY e_id';
            }

            return $where;
        }

	/**
	 * 获取企业列表
	 * @param type $limit 限制长度
	 * @return type
	 */
	public function getList($limit = '') {
		$sql = <<<ECHO
SELECT
	e_id,
	e_bss_number,
	e_status,
	e_name,
	e_create_time,
	e_mds_id,
	e_mds_users,
	e_mds_call,
	e_vcr_id,
	e_vcr_audiorec,
	e_vcr_videorec,
	e_vcr_space,
	e_storage_function,
        e_addr,
        e_contact_fox,
        e_contact_phone,
        e_contact_name,
        e_contact_surname,
        e_industry,
        e_create_name,
	e_ag_path,
	e_remark,
	(
		SELECT
			"T_Device".d_ip1
		FROM
			"T_Device"
		WHERE
			(
				("T_Device".d_id = e_mds_id)
				AND "T_Device".d_type = 'mds'
			)
	) AS mds_d_ip1,
	(
		SELECT
			"T_Device".d_ip1
		FROM
			"T_Device"
		WHERE
			(
				("T_Device".d_id = e_vcr_id)
				AND "T_Device".d_type = 'rs'
			)
	) AS vcr_d_ip1,
	e_area,
	e_has_vcr,
	e_sync,
	e_pwd,
	e_mds_phone,
	e_mds_dispatch,
	e_mds_gvs,
	am_name,
	(
		SELECT
			"T_Device".d_name
		FROM
			"T_Device"
		WHERE
			(
				("T_Device".d_id = e_mds_id)
				AND "T_Device".d_type = 'mds'
			)
	) AS mds_d_name,
	(
		SELECT
			"T_Device".d_name
		FROM
			"T_Device"
		WHERE
			(
				("T_Device".d_id = e_vcr_id)
				AND "T_Device".d_type = 'rs'
			)
	) AS rs_d_name,
	(
		SELECT
			"T_Device".d_name
		FROM
			"T_Device"
		WHERE
			(
				("T_Device".d_id = e_ss_id)
				AND "T_Device".d_type = 'ss'
			)
	) AS ss_d_name
FROM
	(
		"T_Enterprise"
		LEFT JOIN "T_AreaManage" ON (
			(
				"T_AreaManage".am_id = "T_Enterprise".e_area
			)
		)
	)
ECHO;
		$sql = $sql . $this->getWhere(true);
		$sql = $sql . $limit;

		$stat = $this->pdo->query($sql);
		$result = $stat->fetchAll();
		return $result;
    }
    /**
     * 获取企业列表
     * @param type $limit 限制长度
     * @return type
     */
    public function getList_permit($limit = '') {
        $sql = <<<ECHO
SELECT
	e_id,
	e_bss_number,
	e_status,
	e_name,
	e_create_time,
	e_mds_id,
	e_mds_users,
	e_mds_call,
	e_vcr_id,
	e_vcr_audiorec,
	e_vcr_videorec,
	e_vcr_space,
	e_storage_function,
        e_addr,
        e_contact_fox,
        e_contact_phone,
        e_contact_name,
        e_contact_surname,
        e_industry,
        e_create_name,
        e_ag_path,
	e_remark,
	e_area,
	e_has_vcr,
	e_sync,
	e_pwd,
	e_mds_phone,
	e_mds_dispatch,
	e_mds_gvs,
	am_name
FROM
	(
		"T_Enterprise"
		LEFT JOIN "T_AreaManage" ON (
			(
				"T_AreaManage".am_id = "T_Enterprise".e_area
			)
		)
	)
ECHO;
            $sql = $sql . $this->getWhere_permit(true);
        $sql = $sql . $limit;

        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ();
        return $result;
    }
    /**
     * 获取企业列表--------Record表
     * @param type $limit 限制长度
     * @return type
     */
    public function getList_record ( $limit = '' )
    {
        $date=$this->data['start'];
        $sql = <<<ECHO
SELECT
                er_id,
                er_bss_number,
                er_status,
                er_name,
                er_area,
                er_create_time,
                er_agents_id,
                er_contact_surname,
                er_contact_name,
                er_contact_phone,
                er_contact_fox,
                er_addr,
                er_industry,
                er_contact_mail,
                er_create_name,
                er_ag_path,
                er_regis_code,
                er_sum_money,
                er_sum_money_amp,
                er_sum_money_p_function,
                er_sum_money_p_function_amp,
                er_price,
                pre_count_number,
                am_name
FROM
	(
		"T_Enterprise_Record_$date"
		LEFT JOIN "T_AreaManage" ON (
			(
				"T_AreaManage".am_id = "T_Enterprise_Record_$date".er_area
			)
		)
	)
ECHO;
        $sql = $sql . $this->getWhere_record ( true );
        $sql = $sql . $limit;
        try {
            $stat = $this->pdo->query ( $sql );
        } catch (Exception $exc) {
            if($exc->getCode()=="42P01"){
               $result['msg']=L("该月份报表不存在")."。。。(；′⌒`)";
               $result['status']=-1;
               return $result;
            }
        }
        $result = $stat->fetchAll ();
        return $result;
    }
    /**
     * 用户许可权限 获取企业列表
     * @param type $limit 限制长度
     * @return type
     */
    public function getListag ( $limit = '' )
    {
        $sql = <<<ECHO
SELECT
	e_id,
	e_bss_number,
	e_status,
	e_name,
	e_create_time,
	e_mds_id,
	e_mds_users,
	e_mds_call,
	e_vcr_id,
	e_vcr_audiorec,
	e_vcr_videorec,
	e_vcr_space,
	e_storage_function,
        e_addr,
        e_contact_fox,
        e_contact_phone,
        e_contact_name,
        e_contact_surname,
        e_industry,
        e_create_name,
        e_ag_path,
	e_remark,
	(
		SELECT
			"T_Device".d_ip1
		FROM
			"T_Device"
		WHERE
			(
				("T_Device".d_id = e_mds_id)
				AND "T_Device".d_type = 'mds'
			)
	) AS mds_d_ip1,
	(
		SELECT
			"T_Device".d_ip1
		FROM
			"T_Device"
		WHERE
			(
				("T_Device".d_id = e_vcr_id)
				AND "T_Device".d_type = 'vcr'
			)
	) AS vcr_d_ip1,
	e_area,
	e_has_vcr,
	e_sync,
	e_pwd,
	e_mds_phone,
	e_mds_dispatch,
	e_mds_gvs,
	am_name
FROM
	(
		"T_Enterprise"
		LEFT JOIN "T_AreaManage" ON (
			(
				"T_AreaManage".am_id = "T_Enterprise".e_area
			)
		)
	)
ECHO;
		$sql = $sql . $this->getWhere_permit(true);
		$sql = $sql . $limit;
		$stat = $this->pdo->query($sql);
		$result = $stat->fetchAll();
		return $result;
	}
    /**
     * 获取企业列表
     * @param type $limit 限制长度
     * @return type
     */
    public function getList_ag ( $limit = '' )
    {
        $sql = <<<ECHO
SELECT
	e_id,
	e_bss_number,
	e_status,
	e_name,
	e_create_time,
	e_mds_id,
	e_mds_users,
	e_mds_call,
	e_vcr_id,
	e_vcr_audiorec,
	e_vcr_videorec,
	e_vcr_space,
	e_storage_function,
	(
		SELECT
			"T_Device".d_ip1
		FROM
			"T_Device"
		WHERE
			(
				("T_Device".d_id = e_mds_id)
				AND "T_Device".d_type = 'mds'
			)
	) AS mds_d_ip1,
	(
		SELECT
			"T_Device".d_ip1
		FROM
			"T_Device"
		WHERE
			(
				("T_Device".d_id = e_vcr_id)
				AND "T_Device".d_type = 'vcr'
			)
	) AS vcr_d_ip1,
	e_area,
	e_has_vcr,
	e_sync,
	e_pwd,
	e_mds_phone,
	e_mds_dispatch,
	e_mds_gvs,
	e_agents_id,
	e_create_name,
	am_name
FROM
	(
		"T_Enterprise"
		LEFT JOIN "T_AreaManage" ON (
			(
				"T_AreaManage".am_id = "T_Enterprise".e_area
			)
		)
	)
ECHO;
        $sql = $sql . $this->getWhere ( true );
        $sql = $sql . $limit;

        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ();
        return $result;
    }
	/**
	 * 刷新企业列表状态
	 * @return type
	 * @throws Exception
	 */
	public function refreshList() {
		$list = implode(", ", $this->data["checkbox"]);
		$sql = 'SELECT e_id,e_status FROM "T_Enterprise" WHERE e_id IN (:list) AND (e_status != 0 and e_status != 1)';
		$sql = str_replace(':list', $list, $sql);

		$sth = $this->pdo->query($sql);
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
        $resultlistall = array();
		foreach ($result as $value) {
            $resultlistall[] = $value['e_id'];
		}

        $resultliststrall = implode(',', $resultlistall);

		if ($resultliststrall == "") {
			throw new Exception(L("没有一项状态为处理失败或处理中的项"), -1);
		}
                
                	
    	//企业操作历史记录
    	foreach ($resultlist as $key => $value) {
    		$old_status = $this->getEnterpriseFields('e_status',$value);
    		$this->make_history_args(array('e_id'=>$value,'e_status'=>$old_status['e_status']),array('e_status'=>2));
    	}
                    

		foreach ($resultlistall as $list) {
			$log = DL('刷新了企业%s ID：%s的状态');
			$log = sprintf($log
				, ''
				, $list
			);
			$this->log($log, 1, 0);
		}

		return $resultlistall;
	}

	/**
	 * 获得企业id序列号
	 * @return type
	 */
	public function getSEQ() {
		$sql = 'SELECT nextval(\'"T_Enterprise_e_id_seq"\'::regclass)';
		$sth = $this->pdo->query($sql);
		$result = $sth->fetch();
		return $result["nextval"];
	}

    /**
     * 批量删除企业
     * @return type
     */
    public function delList() {
            $list = implode(", ", $this->data["checkbox"]);
// 安全删除
		$sql = "SELECT e_id FROM \"T_Enterprise\" WHERE e_id IN($list) AND \"T_Enterprise\".e_status != 1 AND e_id != 999999";

            $sth = $this->pdo->query($sql);
            $result = $sth->fetchAll();
            $list = "";
            foreach ($result as $value) {
                    $list .= "'".$value["e_id"]."',";
                    //企业操作历史记录
                    $old_status = $this->getEnterpriseFields('e_status',$value["e_id"]);
                	$this->make_history_args(array('e_id'=>$value['e_id'],'e_status'=>$old_status['e_status']),array('e_status'=>6));
            }

            $list = rtrim($list, ", ");
            if ($list != "") {
                    //$sql = 'DELETE FROM "T_Enterprise"WHERE"T_Enterprise".e_id IN (' . $list . ') AND "T_Enterprise".e_status != 1 AND e_id != 999999';
                    $sql = 'UPDATE "T_Enterprise" SET e_status = 6 ,e_mds_phone = 0,e_mds_dispatch=0,e_mds_gvs=0,e_name=NULL WHERE "T_Enterprise".e_id IN (' . $list . ') AND "T_Enterprise".e_status != 1 AND e_id != 999999';
                    $count = $this->pdo->exec($sql);
                    $sql = 'DELETE FROM "T_EnterpriseManager" WHERE "T_EnterpriseManager".em_ent_id IN (' . $list . ') OR em_id IN(' . $list . ')';
                    $this->pdo->exec($sql);
                    $listarr = explode(",", $list);
                    foreach ($listarr as $value) {
                                $log = DL('删除企业%s成功，企业ID：%s');
                                $log = sprintf($log
                                        , ''
                                        , trim($value,"'")
                                );
                                $this->log($log, 1, 1);
                                $this->delDB(trim($value,"'"));
                    }
            }
            $result['list'] = $list;
            $result['count'] = $count;
            return $result;
    }

	/**
	 * 返回当前企业总数
	 * @return type
	 */
	public function getTotal() {
		$sql = 'SELECT COUNT("public"."T_Enterprise".e_id)AS total FROM"public"."T_Enterprise"';
		$sql = $sql . $this->getWhere();
		$pdoStatement = $this->pdo->query($sql);
		$result = $pdoStatement->fetch();
		return $result["total"];
	}
        public function getByid_Record(){
            $sql='SELECT * FROM
                     "T_Enterprise_Record_'.$this->data["start"].'" WHERE er_id=:er_id';
            $sth = $this->pdo->prepare($sql);
            $sth->bindValue(':er_id', $this->data["er_id"], PDO::PARAM_INT);
            $sth->execute();
            $data = $sth->fetch(PDO::FETCH_ASSOC);
            return $data;
        }

	/**
	 * 通过企业ID获取企业详细信息
	 * @param type $deviceflag
	 * @return type
	 * @throws Exception
	 */
	public function getByid($deviceflag = false) {
		if ($this->data["e_id"] == "") {
			throw new Exception("Incorrect enterprise Numbers", -1);
		}

		$sql = 'SELECT
                    "T_Enterprise".e_id,
                    "T_Enterprise".e_bss_number,
                    "T_Enterprise".e_status,
                    "T_Enterprise".e_name,
                    "T_Enterprise".e_regis_code,
                    "T_Enterprise".e_create_time,
                    "T_Enterprise".e_mds_id,
                    "T_Enterprise".e_mds_users,
                    "T_Enterprise".e_mds_call,
                    "T_Enterprise".e_vcr_id,
                    "T_Enterprise".e_vcr_audiorec,
                    "T_Enterprise".e_vcr_videorec,
                    "T_Enterprise".e_vcr_space,
                    "T_Enterprise".e_storage_function,
                    "T_Enterprise".e_mds_phone,
                    "T_Enterprise".e_mds_dispatch,
                    "T_Enterprise".e_mds_gvs,
                    "T_Enterprise".e_addr,
                    "T_Enterprise".e_contact_fox,
                    "T_Enterprise".e_contact_phone,
                    "T_Enterprise".e_contact_name,
                    "T_Enterprise".e_contact_surname,
                    "T_Enterprise".e_industry,
                    "T_Enterprise".e_contact_mail,
                    "T_Enterprise".e_create_name,
                    "T_Enterprise".e_agents_id,
                    "T_Enterprise".e_ag_path,
                    "T_Enterprise".e_remark,
		    "T_Enterprise".e_ss_id,
                    "T_Enterprise".e_rs_rec,
                    "T_Enterprise".e_location,
                     (
                            SELECT
                                    "T_Device".d_name
                            FROM
                                    "T_Device"
                            WHERE
                                    (
                                            (
                                                    "T_Device".d_id = "T_Enterprise".e_mds_id
                                            )
                                            AND
                                            "T_Device".d_type = \'mds\'
                                    )
                    ) AS mds_d_name,
                    (
                            SELECT
                                    "T_Device".d_ip1
                            FROM
                                    "T_Device"
                            WHERE
                                    (
                                            (
                                                    "T_Device".d_id = "T_Enterprise".e_mds_id
                                            )
                                            AND
                                            "T_Device".d_type = \'mds\'
                                    )
                    ) AS mds_d_ip1,
                    (
                            SELECT
                                    "T_Device".d_ip1
                            FROM
                                    "T_Device"
                            WHERE
                                    (
                                            (
                                                    "T_Device".d_id = "T_Enterprise".e_vcr_id
                                            )
                                            AND
                                            "T_Device".d_type = \'rs\'
                                    )
                    ) AS vcr_d_ip1,
					(
                            SELECT
                                    "T_Device".d_name
                            FROM
                                    "T_Device"
                            WHERE
                                    (
                                            (
                                                    "T_Device".d_id = "T_Enterprise".e_vcr_id
                                            )
                                            AND
                                            "T_Device".d_type = \'rs\'
                                    )
                    ) AS rs_name,
					(
                            SELECT
                                    "T_Device".d_name
                            FROM
                                    "T_Device"
                            WHERE
                                    (
                                            (
                                                    "T_Device".d_id = "T_Enterprise".e_ss_id
                                            )
                                            AND
                                            "T_Device".d_type = \'ss\'
                                    )
                    ) AS ss_name,
					(
                            SELECT
                                    "T_Device".d_deployment_id
                            FROM
                                    "T_Device"
                            WHERE
                                    (
                                            (
                                                    "T_Device".d_id = "T_Enterprise".e_mds_id
                                            )
                                            AND
                                            "T_Device".d_type = \'mds\'
                                    )
                    ) AS d_deployment_id,
                    "T_AreaManage".am_name,
                    "T_Enterprise".e_area,
                    "T_Enterprise".e_has_vcr,
                    "T_Enterprise".e_sync,
                    "T_Enterprise".e_pwd
            FROM
                    (
                            "T_Enterprise"
                            LEFT JOIN "T_AreaManage" ON (
                                    (
                                            "T_AreaManage".am_id = "T_Enterprise".e_area
                                    )
                            )
                    )
            WHERE e_id = :e_id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':e_id', $this->data["e_id"], PDO::PARAM_INT);
		$sth->execute();
		$data = $sth->fetch(PDO::FETCH_ASSOC);

// merge device
		if ($deviceflag) {
// 合并device MDS
			$devicedata["d_id"] = $data["e_mds_id"];
			$device = new device($devicedata);
			$item = $device->getByid();

			$data["d_user"] = $item["d_user"];
			$data["d_call"] = $item["d_call"];

// 合并device VCR
			$devicedata["d_id"] = $data["e_vcr_id"];
			$device->set($devicedata);
			$item = $device->getByid();
			$data["d_space_free"] = $item["d_space_free"];
			$data["d_audiorec"] = $item["d_audiorec"];
			$data["d_videorec"] = $item["d_videorec"];
		}

		return $data;
	}

	/**
	 * getter
	 * @return type
	 */
	public function get() {
		return $this->data;
	}

	/**
	 * setter
	 * @param type $data
	 */
	public function set($data) {
		$this->data = $data;
	}

	/**
	 * 企业迁移MDS
	 * @return string
	 */
	public function moveMDS() {
		//企业操作历史记录----》获取修改前的企业对应信息
		$old_info=$this->getEnterpriseFields('e_id,e_status,e_mds_id,e_area',$this->data['e_id']);

		$sql = 'UPDATE"T_Enterprise" SET e_status=:e_status,e_mds_id=:e_mds_id,e_area=:e_area WHERE e_id = :e_id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':e_status', 0, PDO::PARAM_INT);
		$sth->bindValue(':e_mds_id', $this->data["new_mds_id"], PDO::PARAM_INT);
		$sth->bindValue(':e_id', $this->data["e_id"], PDO::PARAM_INT);
		$sth->bindValue(':e_area', json_encode($this->data["e_area"]));
		$sth->execute();
		//记录企业历史记录操作
		$new_info=array();
		$new_info['e_status'] = 0;
		$new_info['e_mds_id'] = $this->data['new_mds_id'];
		$new_info['e_area'] = $this->data['e_area'];
        $this->make_history_args($old_info,$new_info);

		$msg["status"] = 0;
		$msg["msg"] = L('迁移'.$_SESSION['ident'].'-Server成功');
		return $msg;
	}

	/**
	 * 企业迁移VCR
	 * @return string
	 */
	public function moveVCR() {
		$new_vcr_id = $this->data["new_vcr_id"];
		$e_id = $this->data["e_id"];
		$sql = 'UPDATE "T_Enterprise" SET e_vcr_id = :e_vcr_id WHERE e_id = :e_id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':e_vcr_id', $new_vcr_id, PDO::PARAM_INT);
		$sth->bindValue(':e_id', $e_id, PDO::PARAM_INT);
		try
		{
			$sth->execute();
			//企业操作历史记录
			$this->make_history_args(array('e_id'=>$e_id,'e_vcr_id'=>$aEn['e_vcr_id']),array('e_vcr_id'=>$new_vcr_id));

		} catch (Exception $ex) {
			$msg["status"] = -1;
			$msg["msg"] = print_r($ex, true);
			return $msg;
		}
		$msg["status"] = 0;
		$msg["msg"] = L("迁移VCR成功");
		return $msg;
	}

	/**
	 * 企业更新状态
	 * @param type $status
	 * @return string
	 */
	public function changeStatus($status) {
		//企业操作历史记录
        $old_status = $this->getEnterpriseFields('e_status',$this->data['e_id']);
        
		$sql = 'UPDATE"T_Enterprise" SET e_status=:e_status WHERE e_id = :e_id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':e_status', $status, PDO::PARAM_INT);
		$sth->bindValue(':e_id', $this->data["e_id"], PDO::PARAM_INT);
		try
		{
			$sth->execute();
			//企业操作历史记录
             $this->make_history_args(array('e_id'=>$this->data['e_id'],'e_status'=>$old_status['e_status']),array('e_status'=>$this->data['eh_status']));
		} catch (Exception $ex) {
			$msg["status"] = -1;
			$msg["msg"] = print_r($ex, true);
			return $msg;
		}
		$msg["status"] = 0;
		$msg["msg"] = L('操作成功');
		return $msg;
	}
        /**
	 * 企业更新停用
	 * @param type $status
	 * @return string
	 */
	/*public function changeStopTime($date=NULL) {
		$sql = 'UPDATE"T_Enterprise" SET e_stop_time=:e_stop_time WHERE e_id = :e_id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':e_stop_time', $date, PDO::PARAM_INT);
		$sth->bindValue(':e_id', $this->data["e_id"], PDO::PARAM_INT);
		try
		{
			$sth->execute();
		} catch (Exception $ex) {
			$msg["status"] = -1;
			$msg["msg"] = print_r($ex, true);
			return $msg;
		}
	}*/

    /**
     * 企业数据同步
     * @param type $status
     * @param type $sync
     * @return string
     */
    public function changeSync($status, $sync) {
            $e_id = $this->data["e_id"];
            if ($e_id == "") {
                    $e_id = $this->data["em_ent_id"];
            }

            //获取当前状态值
            $sql = 'SELECT e_sync FROM "T_Enterprise" WHERE e_id = :e_id';
            $sth = $this->pdo->prepare($sql);
            $sth->bindValue(':e_id', $e_id, PDO::PARAM_INT);
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
//            if($result['e_status']=="3"){
//                $msg["status"] = 0;
//                $msg["msg"] = L('数据同步失败');//DBM返回错误
//                $msg['e_sync'] = $result["e_sync"];
//                return $msg;
//            }else if($result['e_status']=="4"){
//                $msg["status"] = 0;
//                $msg["msg"] = L('企业同步失败');//MDS返回错误
//                $msg['e_sync'] = $result["e_sync"];
//                return $msg; 
//            }
            // 设置当前状态值
            $sql = 'UPDATE"T_Enterprise" SET e_sync=:e_sync WHERE e_id = :e_id';
            $sth = $this->pdo->prepare($sql);
            if ($status) {
                    $sth->bindValue(':e_sync', $result["e_sync"] | $sync, PDO::PARAM_INT);
            } else {
                    $sth->bindValue(':e_sync', 0, PDO::PARAM_INT);
            }

            $sth->bindValue(':e_id', $e_id, PDO::PARAM_INT);
            try
            {
                    $sth->execute();
            } catch (Exception $ex) {
                    $msg["status"] = -1;
                    $msg["msg"] = L('企业同步失败');
                    return $msg;
            }
                $msg["status"] = 0;
                $msg["msg"] = L('同步完成');
                $msg['e_sync'] = $result["e_sync"];
                return $msg;
    }

    /**
     * 企业副表删除
     * @param type $e_id
     */
    public function delDB($e_id = "") {
            $dsql = "DROP TABLE IF EXISTS \"public\".\"T_UserGroup_$e_id\";"
            . "DROP TABLE IF EXISTS \"public\".\"T_PttGroup_$e_id\";"
            . "DROP TABLE IF EXISTS \"public\".\"T_EventLog_$e_id\";"
            . "DROP TABLE IF EXISTS \"public\".\"T_Custom_PttGrp_$e_id\";"
            . "DROP TABLE IF EXISTS \"public\".\"T_PttMember_$e_id\";";
            $this->pdo->exec($dsql);
            $data['u_e_id'] = $e_id;
            //删除用户 时解绑终端(先解绑再删除)
            $user = new users($data);
            $term=new terminal();
            $gprs=new gprs();
            //①终端解绑
            $list=$user->get_eq_user_list();
            foreach($list as $key=>$value){
                    $user->set($value);
                    $user_info=$user->getById_history();
                    if($value['u_imei']!=""){
                            $term->set(array("md_imei"=>$value['u_imei']));
                            $term->releaseBound();
                            $term->set_term_history($user_info,"unbind");
                            if(strtotime($user_info['md_binding_time'])== strtotime(date('Y-m-d',time()))){
                                if($value['u_attr_type']=="0"){
                                    $user->add_commercial_term($e_id, -1);
                                }else{
                                    $user->add_test_term($e_id, -1);
                                }
                                $user->add_terminal($e_id, -1);
                            }
                    }
                    if($value['u_iccid']!=""){
                           //删除企业用户时流量卡的对应操作
                            $gprs->delusergprs($value['u_number']);
                            $gprs->gprsreleaseBound_history($user_info);
                            if(strtotime($user_info['md_binding_time'])== strtotime(date('Y-m-d',time()))){
                                if($value['u_attr_type']=="0"){
                                    $user->add_commercial_gprs($e_id, -1);
                                }else{
                                    $user->add_test_gprs($e_id, -1);
                                }
                                $user->add_gprs($e_id, -1);
                            }
                    }
                    $user->set_user_history($user_info, 0);
            }
            //②用户删除
            $num=$user->getTotal(false);
            $dnum=$user->getTotal(false,date('Y-m-d',time()));
            $user->deleteAll();
            if($dnum>0){
                $user->add_users($e_id, -$dnum);
                $user->sum_add_users($e_id, -$dnum);
            }else{
                $user->sum_delete_users($e_id, $num);
                $user->delete_users($e_id, $num);
            }
    }

	/**
	 * 企业副表建立
	 * @param type $e_id
	 * @return string
	 * @throws Exception 企业ID不存在时，抛出企业ID错误异常
	 */
	public function initDB($e_id = "") {
		if ($e_id == "") {
			$e_id = $this->data["e_id"];
		}
		if ($e_id == "") {
			throw new Exception("Incorrect enterprise Numbers", -1);
		}

		$dc_ugsql = '
            DROP TABLE
            IF EXISTS "public"."T_UserGroup_:e_id";

            CREATE TABLE "public"."T_UserGroup_:e_id" (
                    "ug_id" serial NOT NULL,
                    "ug_name" VARCHAR (128),
                    "ug_parent_id" int4,
                    "ug_weight" int4,
                    "ug_path" VARCHAR,
                    CONSTRAINT "T_UserGroup_:e_id_pkey" PRIMARY KEY ("ug_id")
            ) WITH (OIDS = FALSE);
            CREATE UNIQUE INDEX "ug_name_pkey_:e_id" ON "public"."T_UserGroup_:e_id" USING btree (ug_name);
';

		$dc_pgsql = '
            DROP TABLE
            IF EXISTS "public"."T_PttGroup_:e_id";

            CREATE TABLE "public"."T_PttGroup_:e_id" (
                    "pg_number" VARCHAR (64),
                    "pg_name" VARCHAR (64),
                    "pg_level" int4,
                    "pg_grp_idle" int4,
                    "pg_speak_idle" int4,
                    "pg_speak_total" int4,
                    "pg_record_mode" int4,
                    "pg_queue_len" int4,
                    "pg_chk_stat_int" int4,
                    "pg_buf_size" int4,
                    "pg_hangup" int4,
                    CONSTRAINT "T_PttGroup_:e_id_pkey" PRIMARY KEY ("pg_number")
            ) WITH (OIDS = FALSE);
            CREATE UNIQUE INDEX "pg_name_pkey_:e_id" ON "public"."T_PttGroup_:e_id" USING btree (pg_name);
';

		$dc_elsql = '
            DROP TABLE
            IF EXISTS "public"."T_EventLog_:e_id";

            CREATE TABLE "public"."T_EventLog_:e_id" (
            "el_id" serial NOT NULL,
            "el_type" varchar(16),
            "el_level" int4,
            "el_time" timestamp(6),
            "el_content" varchar(1024),
            "el_user" varchar(64)
            )
            WITH (OIDS=FALSE)
            ;';
		$dc_ptmsql = '
            DROP TABLE
            IF EXISTS "public"."T_PttMember_:e_id";

            CREATE TABLE "public"."T_PttMember_:e_id" (
            "pm_number" varchar(64) NOT NULL,
            "pm_level" int4 DEFAULT 255,
            "pm_pgnumber" varchar(64),
            "pm_hangup" int4,
            CONSTRAINT "T_PttMember_:e_id_pkey" PRIMARY KEY ("pm_number", "pm_pgnumber")
            )
            WITH (OIDS=FALSE)
            ;';
		$dc_ctptgsql = '
            DROP TABLE IF EXISTS "public"."T_Custom_PttGrp_:e_id";

            CREATE TABLE "public"."T_Custom_PttGrp_:e_id" (
            "c_pg_number" varchar(64) NOT NULL,
            "c_pg_name" varchar(64),
            "c_pg_creater_num" varchar(64),
            "c_pg_level" int4,
            "c_pg_grp_idle" int4,
            "c_pg_speak_idle" int4,
            "c_pg_speak_total" int4,
            "c_pg_record_mode" int4,
            "c_pg_chk_stat_int" int4,
            "c_pg_mem_list" varchar
            )
            WITH (OIDS=FALSE)
            ;';
		$dc_ugsql = str_replace(":e_id", $e_id, $dc_ugsql);
		$dc_pgsql = str_replace(":e_id", $e_id, $dc_pgsql);
		$dc_elsql = str_replace(":e_id", $e_id, $dc_elsql);
		$dc_ptmsql = str_replace(":e_id", $e_id, $dc_ptmsql);
		$dc_ctptgsql = str_replace(":e_id", $e_id, $dc_ctptgsql);

		try
		{
//开启一个事务
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo->beginTransaction();
			//$this->pdo->exec($dc_usql);
			$this->pdo->exec($dc_ugsql);

			$this->pdo->exec($dc_pgsql);
			$this->pdo->exec($dc_elsql);
			$this->pdo->exec($dc_ptmsql);
			$this->pdo->exec($dc_ctptgsql);
			$this->pdo->commit();
		} catch (Exception $ex) {
			$this->pdo->rollBack();
			throw new Exception("Create failure, data rollback" . $ex->getMessage(), -2);
		}

		$msg["status"] = 0;
		$msg["msg"] = L("初始化成功");
		return $msg;
	}

	/**
	 * 获得当前企业用户总数
	 * @return type
	 */
	function getUserNum() {
		//得到目标企业目前用户数量;
		if ($this->data["e_id"] != "") {
			$sql = <<<SQL
                        SELECT count(*) as total  FROM "T_User" WHERE u_e_id ={$this->data["e_id"]}
SQL;
			$total = $this->total($sql);
		}
		return $total;
	}

	/*
	 *
	 */

	function getenNum() {
		//得到目标企业目前用户数量;
		if ($this->data["e_id"] != "") {
			$sql = <<<SQL
                        SELECT e_mds_users as total  FROM "T_Enterprise" WHERE e_id ={$this->data["e_id"]}
SQL;
			$total = $this->total($sql);
		}
		return $total;
	}

    public function getoptionlist ()
    {
        $sql = <<<ECHO
SELECT
	e_id,
	e_bss_number,
	e_status,
	e_name,
	e_create_time,
	e_mds_id,
	e_mds_users,
	e_mds_call,
	e_vcr_id,
	e_vcr_audiorec,
	e_vcr_videorec,
	e_vcr_space,
	e_storage_function,
	(
		SELECT
			"T_Device".d_ip1
		FROM
			"T_Device"
		WHERE
			(
				("T_Device".d_id = e_mds_id)
				AND "T_Device".d_type = 'mds'
			)
	) AS mds_d_ip1,
	(
		SELECT
			"T_Device".d_ip1
		FROM
			"T_Device"
		WHERE
			(
				("T_Device".d_id = e_vcr_id)
				AND "T_Device".d_type = 'vcr'
			)
	) AS vcr_d_ip1,
	e_area,
	e_has_vcr,
	e_sync,
	e_pwd,
	e_mds_phone,
	e_mds_dispatch,
	e_mds_gvs,
	e_remark,
	am_name
FROM
	(
		"T_Enterprise"
		LEFT JOIN "T_AreaManage" ON (
			(
				"T_AreaManage".am_id = "T_Enterprise".e_area
			)
		)
	)
ECHO;
        $sql = $sql . $this->getWhere ( true );
        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ();
        return $result;
    }

     /**
     * 获得代理商所有企业
     */
    public function getepList(){
        //$sql='SELECT * FROM "T_Enterprise" WHERE e_agents_id=:e_agents_id';
        $sql="SELECT e_id FROM \"T_Enterprise\" WHERE e_ag_path LIKE E'%".$this->data['ag_number']."%'";
        //$sth = $this->pdo->prepare ( $sql );
        //$sth->bindValue ( ':e_ag_path' , $this->data['ag_number'] , PDO::PARAM_STR );
        //$sth->execute ();
        $sth=$this->pdo->query($sql);
        $data = $sth->fetchAll( PDO::FETCH_ASSOC );
        return $data;
    }
    
    public function getoptionlist_ag ()
    {
        $sql = <<<ECHO
SELECT
	e_id,
	e_bss_number,
	e_status,
	e_name,
	e_create_time,
	e_mds_id,
	e_mds_users,
	e_mds_call,
	e_vcr_id,
	e_vcr_audiorec,
	e_vcr_videorec,
	e_vcr_space,
	e_storage_function,
                e_addr,
                e_contact_fox,
                e_contact_phone,
                e_contact_name,
                e_contact_surname,
                e_industry,
                e_create_name,
	(
		SELECT
			"T_Device".d_ip1
		FROM
			"T_Device"
		WHERE
			(
				("T_Device".d_id = e_mds_id)
				AND "T_Device".d_type = 'mds'
			)
	) AS mds_d_ip1,
	(
		SELECT
			"T_Device".d_ip1
		FROM
			"T_Device"
		WHERE
			(
				("T_Device".d_id = e_vcr_id)
				AND "T_Device".d_type = 'vcr'
			)
	) AS vcr_d_ip1,
	e_area,
	e_has_vcr,
	e_sync,
	e_pwd,
	e_mds_phone,
	e_mds_dispatch,
	e_mds_gvs,
	e_remark,
	am_name
FROM
	(
		"T_Enterprise"
		LEFT JOIN "T_AreaManage" ON (
			(
				"T_AreaManage".am_id = "T_Enterprise".e_area
			)
		)
	)
ECHO;
		$sql = $sql . $this->getWhere(true);
		$sql = $sql . $limit;
		$stat = $this->pdo->query($sql);
		$result = $stat->fetchAll();
		return $result;
    }
    
     /**
         * 修改部门时 更新版本号
         */
        public function changeversion(){
            
            $e_al_version=  $this->getversion();
            $sql=<<<echo
                    UPDATE "T_Enterprise" SET e_al_version=:e_al_version WHERE e_id=:e_id
echo;
            $sth = $this->pdo->prepare($sql);
            $sth->bindValue(':e_id', $this->data['e_id'], PDO::PARAM_STR);
            $sth->bindValue(':e_al_version', $e_al_version+1, PDO::PARAM_INT);
            $sth->execute();
        }
         /**
         * 获得版本号
         */
        public function getversion(){
            $sql="SELECT e_al_version FROM \"T_Enterprise\" WHERE e_id='{$this->data['e_id']}'";
            $sth=$this->pdo->query($sql);
            $result=$sth->fetch();
            return $result['e_al_version'];
        }
    /*
    * 编辑企业信息
    */
    public function moveDevice($aKey) {
    	if($aKey['e_vcr_id'] && $aKey['e_vcr_id'] != '0')
    	{
    		$aEn = $this->getByid();
    		$aKey['e_rs_rec'] = $aEn['e_mds_phone']*2 + $aEn['e_mds_dispatch'] + $aEn['e_mds_gvs'];
    	}
    	else
    	{
    		$aKey['e_rs_rec'] = '0';
    	}
    	$arr = array();
		if(!empty($aKey))
		{
			foreach ($aKey as $key => $value) {
				if($key == 'e_vcr_id' && $value == '')
				{
					array_push ( $arr ,  $key ." = NULL");
				}
				else
				{
					array_push ( $arr ,  $key ." = '".$value."'");
				}
			}

			$set = implode(",", $arr);
		}
		$sql = 'UPDATE "T_Enterprise" SET '.$set.' where e_id=\''.$aKey['e_id'].'\'';		
		//企业历史记录
        $oldinfo = $this->getEnterpriseFields('*',$this->data["e_id"]);
        
		// echo $sql;die;
		$sth = $this->pdo->prepare($sql);
		// echo $sql;die;
		try
		{
			$sth->execute();
			unset($aKey['e_status']);
			$this->make_history_args($oldinfo,$aKey);
			
		} catch (Exception $ex) {
			$msg["status"] = -1;
			$msg["msg"] = print_r($ex, true);
			return $msg;
		}
		$msg["status"] = 0;
		$msg["msg"] = L("迁移设备成功");
		return $msg;
	}
    /**
     * 迁移企业时获取符合条件的代理商
     */
	public function	get_agents()
	{
		$e_info = $this -> getByid();
		$agents = new agents();

		//获取企业所属代理商的信息
		$ag_info = $agents -> getByid($e_info['e_agents_id']);
		if($ag_info['ag_level'] == '1'){
			//amp平台下 企业所属代理商为二级代理时，则只能迁回自己下面
			$ag_parent_info[0] = $agents -> getByid($ag_info['ag_parent_id']);
           	$result['list'] = $this->checkAgents( $ag_parent_info );
			$result['omp'] = false;
		}else{
			//当企业所属代理为一级代理，在amp平台上只能迁移到自己的二级代理下或迁到OMP下
			$agents = new agents(array('aggents_number'=>$e_info['e_agents_id'],'ag_level'=>'0'));
			$ag_list = $agents -> getList_ag();
			if(empty($ag_list) || !$ag_list){
				$result['list']='';
			}else{
				$result['list'] = $this->checkAgents( $ag_list );
				$result['omp'] = true;
			}
		}
		return $result;
	}

	/**
     * 筛选符合迁移条件的代理商功能方法
     */
	public function checkAgents( $ag_list ){
		$e_info = $this -> getByid();
		$e_area = json_decode($e_info['e_area']);
		foreach ($ag_list as $key => $value) {
			$check_area = 'no';
			//代理所绑定区域
        	$ag_area = json_decode ( $value['ag_area'] );
        	foreach($ag_area as $ak => $aval){
		    	if($aval==$e_area){
		    		$check_area = 'yes';
		    	}
		    }
        	//当前代理三种用户剩余
        	//获取代理下面企业的信息
        	$ep = new enterprise ( array ( 'ag_number' => $value['ag_number'],'e_create_name'=>$value['ag_number'] ) );
		    $info = $ep->getList ();

		    //获取当前代理商的子代理商和所属企业
		    $phone = 0; //企业所分配手机数
		    $dispatch = 0;//企业所分配调度台数
		    $gvs = 0;//企业所分配GVS数
		    if(!empty($info)){
		    	foreach ( $info as $ek => $eval )
			    {
			        $phone += $eval['e_mds_phone'];
			        $dispatch += $eval['e_mds_dispatch'];
			        $gvs += $eval['e_mds_gvs'];
			    }
		    }

		    if($value['ag_level']=='0'){
		    	//下级代理的手机、调度台、调度台用户的和
			    $ag = new agents ( array ( 'aggents_number' => $value['ag_number'] , 'ag_level' => $value['ag_level'] ) );
			    $ag_info = $ag->getList_ag ();
			    if(!empty($ag_info)){
			    	foreach ( $ag_info as $k => $val )
				    {
				        $phone += $val['ag_phone_num'];
				        $dispatch += $val['ag_dispatch_num'];
				        $gvs += $val['ag_gvs_num'];
				    }
			    }
		    }

		    //手机用户剩余
		    $phone_now = $value['ag_phone_num']-$phone;
		    //调度台用户剩余
		    $dispatch_now = $value['ag_dispatch_num']-$dispatch;
		    //GVS用户剩余
		    $gvs_now = $value['ag_gvs_num']-$gvs;
		    //筛选合适的代理
		    if($phone_now < $e_info['e_mds_phone'] || $dispatch_now < $e_info['e_mds_dispatch'] || $gvs_now < $e_info['e_mds_gvs'] || $check_area=='no'){
		    	unset($ag_list[$key]);
		    }
		}
		sort($ag_list);
		return $ag_list;
	}

	/**
     * @author yuejun.wang
     * @copyright 2015/12/10
     * 迁移企业(改变企业代理操作)
     */
    public function change_enterprise()
    {
        //生成代理商关系
        if($this->data['e_agents_id']=='0'){
        	$path = '|0|';
        	//迁移企业后流量卡需要绑定的代理上的编号
        	$ag_number = '0';
        }else{
        	$agents = new agents();
			$ag_info = $agents -> getByid($this->data['e_agents_id']);
			if($ag_info['ag_level']=='1'){
				$ag_parent_info = $agents -> getByid($ag_info['ag_parent_id']);
				$path = "|0||{$ag_parent_info['ag_number']}||{$ag_info['ag_number']}|";
				//迁移企业后流量卡需要绑定的代理上的编号
				$ag_number = $ag_parent_info['ag_number'];
			}else{
				$path = "|0||{$ag_info['ag_number']}|";
				//迁移企业后流量卡需要绑定的代理上的编号
				$ag_number = $ag_info['ag_number'];
			}

        }
        //组合企业操作记录的修改前的数据
        $old_e_info = $this -> getByid();
        $old['e_id'] = $this->data['e_id'];
    	$old['e_agents_id'] = $old_e_info['e_agents_id'];
    	$old['e_create_name'] = $old_e_info['e_create_name'];
        //改变企业的代理商、创建者、代理商关系
        $sql="UPDATE \"T_Enterprise\" SET e_agents_id='{$this->data['e_agents_id']}',e_create_name='{$this->data['e_agents_id']}',e_ag_path='{$path}'  WHERE e_id='{$this->data['e_id']}'";
        $res=$this->pdo->exec($sql);
        if($res){
        	//企业操作历时记录
        	$new = array('e_agents_id'=>$this->data['e_agents_id'],'e_create_name'=>$this->data['e_agents_id']);
        	$this->make_history_args($old,$new);
        	//获取企业所有的手机用户
        	$users = new users( array( 'e_id'=>$this->data['e_id'] , 'u_sub_type'=>'1' ) );
    		$userList = $users->getalluser();
        	$gprs = new gprs();
        	if($userList){
        		foreach($userList as $key=>$value){
	        		//判断用户是否绑定了流量卡，绑定了则修改流量卡的所属代理
	        		if( $value['u_iccid']!='' ){
	        			$gprs->move_enterprise_gprs_binds( $value['u_iccid'] , $ag_number );
	        		}
	        		//判断用户是否绑定了终端，绑定了则修改终端的所属代理
	        		if( $value['u_imei']!='' ){
	        			$terminal = new terminal( array( 'md_parent_ag'=>$ag_number , 'md_imei'=>$value['u_imei'] ) );
	        			$terminal->move_enterprise_term_bind();
	        		}
	        	}
        	}
        	return 'yes';
        }else{
        	return 'no';
        }
    }

    /**
     * @author yuejun.wang
     * @copyright 2015/12/17
     * 生成企业浏览记录的数据
     */
    public function make_history_args($old,$new){
    	$oldArgs = array();
    	$oldArgs['e_id'] = $old['e_id'];
    	if(isset($old['e_area'])){
    		$old['e_area'] = trim($old['e_area'],'"');
    	}
    	if(isset($new['e_area'])){
    		$new['e_area'] = trim($new['e_area'],'"');
    	}
    	foreach ($new as $key => $value) {
    		$check = $this->getFieldName($key);
    		if($check==false){
    			unset($new[$key]);
    		}else{
    			if(isset($old[$key])){
	    			if($new[$key]==$old[$key]){
	    				unset($new[$key]);
	    			}else{
	    				$oldArgs[$key] = $old[$key];
	    			}
	    		}
    		}
    	}
    	//存储企业历史记录
    	$lastId = $this->set_enterprise_history($oldArgs,$new);
    }

    /**
     * @author yuejun.wang
     * @copyright 2015/12/16
     * 企业信息操作变化时的历史记录
     */
    public function set_enterprise_history($oldArg='',$newArg=''){
		if(!empty($newArg)){
			//老数据为空则销毁老数据的参数
			if(empty($oldArg)){
				unset($oldArg);
				$e_id = $newArg['e_id'];
			}
			if(!$oldArg['e_id']){
				$e_id = $newArg['e_id'];
			}else{
				$e_id = $oldArg['e_id'];
			}
			$sql=<<<ECHO
				INSERT INTO "T_EnterpriseHistory" (
					"eh_e_id",
					"eh_change_time",
					"eh_do_username"
				) VALUES(
					:eh_e_id,
					:eh_change_time,
					:eh_do_username
				)
ECHO;
			$sth=$this->pdo->prepare($sql);
			$sth->bindValue(":eh_e_id",$e_id);
			$time = date("Y-m-d H:i:s", time());
			$sth->bindValue(':eh_change_time', $time, PDO::PARAM_INT);
			if(isset($_SESSION['ag_as']) && !empty($_SESSION['ag_as'])){
				$eh_do_username = $_SESSION['ag_as']['as_account_id'];
			}else{
				$eh_do_username = $_SESSION['ag']['ag_name'];
			}
			$sth->bindValue(":eh_do_username",$eh_do_username);
			$sth->execute();
			//获取插入数据生成的id,用来T_EnterpriseField的数据存储
			$gsql = "SELECT eh_id FROM \"T_EnterpriseHistory\" WHERE eh_e_id='{$e_id}' ORDER BY eh_id DESC LIMIT 1";
			$stat = $this->pdo->query ( $gsql );
		    $res = $stat->fetch();
			$lastId = $res['eh_id'];
			//记录修改的字段及新旧字段值
			foreach ($newArg as $key => $value) {
                            if($key=="e_area"){
                                $oldArg[$key]=  trim($oldArg[$key], "\"");
                                $value=  trim($value, "\"");
                            }
				$fsql = "INSERT INTO \"T_EnterpriseHistoryField\" (
					\"eh_id\",
					\"field_name\",
					\"new_value\",
					\"old_value\",
					\"remark\"
				) VALUES(
					:eh_id,
					:field_name,
					:new_value,
					:old_value,
					:remark
				)";
				$fsth=$this->pdo->prepare($fsql);
				$fsth->bindValue(":eh_id",$lastId);
				$fsth->bindValue(":field_name",$key);
				$fsth->bindValue(":new_value",$value);
				$fsth->bindValue(":old_value",$oldArg[$key]);
				$fsth->bindValue(":remark",$this->getFieldName($key));
				$fsth->execute();
			}
		}
    }

    /**
     * @author yuejun.wang
     * @copyright 2015/12/21
     * 获取企业历史记录
     */
    public function get_history_list($limit=""){
    	$sql = "SELECT * FROM \"T_EnterpriseHistory\"";
    	$sql.=$this->getWhere_h(true);
		$sql.=$limit;
		$sth=$this->pdo->query($sql);
		$result=$sth->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $key => $value) {
        	$sql1 = "SELECT * FROM \"T_EnterpriseHistoryField\" WHERE eh_id ={$value['eh_id']}";
	    	$stat1 = $this->pdo->query ( $sql1 );
	        $result1 = $stat1->fetchAll(); 
	        foreach ($result1 as $k => $v) {
	        	//获取企业状态码对应企业状态的汉字解释
	        	if($v['field_name']=='e_status'){
	        		$result1[$k]['new_value'] = $this->e_status[$v['new_value']];
	        		$result1[$k]['old_value'] = $this->e_status[$v['old_value']];
	        	}
	        	//获取三种设备的名字
	        	if($v['field_name']=='e_mds_id' || $v['field_name']=='e_ss_id' || $v['field_name']=='e_vcr_id'){
	        		$result1[$k]['new_value'] = $this->get_device_name($v['new_value']);
	        		$result1[$k]['old_value'] = $this->get_device_name($v['old_value']);
	        	}
	        	//获取代理商和创建者的名称
	        	if($v['field_name']=='e_agents_id' || $v['field_name']=='e_create_name'){
	        		//修改后的代理商名称
	        		if($v['new_value']!='0'){
	        			$sql = 'SELECT ag_name FROM "T_Agents" WHERE ag_number=:ag_number';	
				        $sth = $this->pdo->prepare ( $sql );
				        $sth->bindValue ( ':ag_number' , $v['new_value']);
				        $sth->execute ();
				        $res = $sth->fetch ();
				        if($res){
				        	$result1[$k]['new_value']=$res['ag_name'];
				        }
	        		}else{
	        			$result1[$k]['new_value']='OMP';
	        		}
	        		
			        //修改前的代理商和创建者名称
			        if($v['old_value']!='0'){
				        $sql = 'SELECT ag_name FROM "T_Agents" WHERE ag_number=:ag_number';
				        $sth = $this->pdo->prepare ( $sql );
				        $sth->bindValue ( ':ag_number' , $v['old_value']);
				        $sth->execute ();
				        $res1 = $sth->fetch ();
				        if($res1){
				        	$result1[$k]['old_value']=$res1['ag_name'];
				        }
				    }else{
	        			$result1[$k]['old_value']='OMP';
	        		}
	        	}
	        	//获取区域名称
	        	if($v['field_name']=='e_area'){
	        		//修改后的区域名称
	        		$sql = 'SELECT am_name FROM "T_AreaManage" WHERE am_id=:am_id';
			        $sth = $this->pdo->prepare ( $sql );
			        $sth->bindValue ( ':am_id' , $v['new_value']);
			        $sth->execute ();
			        $res = $sth->fetch ();
			        if($res){
			        	$result1[$k]['new_value']=$res['am_name'];
			        }
			        //修改后的区域名称
	        		$sql = 'SELECT am_name FROM "T_AreaManage" WHERE am_id=:am_id';
			        $sth = $this->pdo->prepare ( $sql );
			        $sth->bindValue ( ':am_id' , $v['old_value']);
			        $sth->execute ();
			        $res1 = $sth->fetch ();
			        if($res1){
			        	$result1[$k]['old_value']=$res1['am_name'];
			        }
	        	}

	        }
	        $result[$key]['fileds'] = $result1;
        }
        return $result;
    }

    //获取企业历史纪录的总数
	public function getTotal_enterprise_history() {
		$sql = "SELECT COUNT(eh_e_id) AS total FROM \"T_EnterpriseHistory\"";
		$sql.=$this->getWhere_h(false);
		$sth = $this->pdo->query($sql);
		$result = $sth->fetch();
		return $result["total"];
	}

    /**
     * @author yuejun.wang
     * @copyright 2015/12/16
     * 企业历史记录筛选条件生成
     */
    public function getWhere_h($order=true){
		$where=" WHERE 1=1";
		//筛选企业的编号
		if($this->data['e_id']!=""){
			$where.=" AND eh_e_id='".$this->data['e_id']."'";
		}
		//筛选选企业更改的操作人
		if($this->data['eh_do_username']!=""){
			$where.=" AND eh_do_username LIKE E'%" . addslashes($this->data["eh_do_username"]) . "%'";
		}
		//筛选操作时间
        if ( $this->data["start"] != "" || $this->data["end"] != "" )
        {
        	/*if($this->data["start"] != "" && $this->data["end"] == ""){
        		$start = strtotime($this->data["start"]);
        		$where .= ' AND eh_change_time >=' . $start;
        	}
        	if($this->data["start"] == "" && $this->data["end"] != ""){
        		$end = strtotime($this->data["end"]);
        		$where .= ' AND eh_change_time <=' . $end;
        	}
        	if($this->data["start"] != "" && $this->data["end"] != ""){
        		$start = strtotime($this->data["start"]);
        		$end = strtotime($this->data["end"]);
        		$where .= ' AND eh_change_time >= '.$start.' AND eh_change_time <=' . $end;
        	}*/
        	$where .= 'AND eh_change_time ' . getDateRange ( $this->data["start"] , $this->data["end"] );
        }
		//排序
		if($order){
			$where.=" ORDER BY eh_change_time DESC";
		}
		return $where;
	}
	/**
	 * 企业操作记录的字段的表的字段的名称函数
	 * @package 
	 * @param string $field
	 * @return string
	 */
	public function getFieldName($field){
		$nameArray = array(
			'e_name' => '企业名称',
			'e_id' => '企业编号',
			'e_regis_code' => '企业注册号',
			'e_addr' => '企业地址',
			'e_industry' => '企业行业',
			'e_contact_name' => '企业联系人姓名',
			'e_contact_surname' => '企业联系人姓氏',
			'e_contact_phone' => '企业联系人电话',
			'e_contact_fox' => '企业联系人传真',
			'e_contact_mail' => '企业联系人邮箱',
			'e_area' => '企业所在区域',
			'e_remark' => '备注',
			'e_status' => '企业状态',
			'e_mds_phone' => '企业手机用户数',
			'e_mds_dispatch' => '企业调度台用户数',
			'e_mds_gvs' => '企业GVS用户数',
			'e_mds_id' => $_SESSION['ident'].'-Server',
			'e_mds_users' => '企业用户数',
			'e_vcr_id' => $_SESSION['ident'].'-RS',
			'e_agents_id' => '企业所属代理商',
			'e_create_name' => '创建者',
			'e_ss_id' => $_SESSION['ident'].'-SS',
			'e_location' => '企业位置',
			'em_pswd' => '管理员密码',
			'em_admin_name' => '管理员名称',
			'em_surname' => '管理员姓氏',
			'em_phone' => '管理员手机号',
			'em_mail' => '管理员邮箱',
			'em_desc' => '管理员描述'
		);
		if(isset($nameArray[$field])){
			return $nameArray[$field];
		}else{
			return false;
		}
	}

	/**
	 * 获取企业绑定的Device的名字
	 * @package 
	 * @param string $d_id
	 * @return string
	 */
	public function get_device_name($d_id){
		if(isset($_SESSION['dnameArr']) && !empty($_SESSION['dnameArr'])){
			if($_SESSION['dnameArr'][$d_id]){
				return $_SESSION['dnameArr'][$d_id];
			}else{
				return 'Not have';
			}
			
		}else{
			$sql = "SELECT d_id,d_name FROM \"T_Device\"";
			$sth = $this->pdo->query($sql);
			$res = $sth->fetchAll();
			if($res){
				$dnameArr = array();
				foreach ($res as $key => $value) {
					$dnameArr[$value['d_id']] = $value['d_name'];
				}
				$_SESSION['dnameArr'] = $dnameArr;
				if($dnameArr[$d_id]){
					return $dnameArr[$d_id];
				}else{
					return 'Not have';
				}
			}else{
				return 'Table T_Device was empty！';
			}
			
		}
	}

	/**
	 * 获取企业字段信息
	 * @package 
	 * @param $type true 获取全部  else 获取字段的字符串 field1,field2,field3....
	 * @param $e_id 企业的id  
	 * @return string
	 */
	public function getEnterpriseFields($fields,$e_id){
		if(!$e_id){
			return false;
		}
		$sql = "SELECT {$fields} FROM \"T_Enterprise\" WHERE e_id={$e_id}";
		$sth = $this->pdo->query($sql);
		$res = $sth->fetch();
		return $res;
	}
        /**
         * 更改企业区域
         * @return type
         */
        public function set_ep_area(){
        	//企业历史记录-获取企业原来的数据
        	$oldinfo = $this->getEnterpriseFields('*',$this->data["e_id"]);
            $sql=<<<ECHO
                    UPDATE "T_Enterprise" SET
                        e_area=:e_area
                    WHERE e_id=:e_id
ECHO;
            $sth=$this->pdo->prepare($sql);
            $sth->bindValue(":e_id",$this->data['e_id'],PDO::PARAM_INT);
            $sth->bindValue(":e_area",  json_encode($this->data['e_area']));
            try {
                    $sth->execute();
                    //企业操作历史记录
                    $this->make_history_args($oldinfo,$this->data);
                    $msg['status']=1;
                    $msg['msg']=L("区域修改成功");
            } catch (Exception $exc) {
                
                $msg['status']=-1;
                $msg['msg']=L("区域修改失败");
            }
            
            return $msg;
        }
}
