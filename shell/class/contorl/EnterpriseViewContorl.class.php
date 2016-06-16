<?php
/**
 * 企业主控制器
 * @category OMP
 * @package OMP_Enterprise_contorl
 * @require {@see contorl} {@see page} {@see sendmsg} {@see enterprise} {@see admins}
 */
class EnterpriseViewContorl extends contorl {
        public function __construct() {
                parent::__construct();
                $this->page = new page($_REQUEST);
        }

        /**
         * 企业迁移GQT-Server
         */
        function move_mds_item() {
                $ep = new enterprise($_REQUEST);
                $ep_data = $ep->getByid(true);

                // 如果企业mds ID等于迁移的mds ID，进行虚假迁移
                if ($ep_data['e_mds_id'] == $_REQUEST['new_mds_id']) {
                        //未在区间范围内禁止修改。在区间内发送企业信息变更消息
                        $device = new device(array("d_id" => $ep_data['e_mds_id']));
                        $device_data = $device->getByid();
                        $area = new area();
                        $flag = $area->isdiff(json_decode($_REQUEST['e_area'], true), json_decode($device_data['d_area'], true));

                        // 区间范围内
                        if (!$flag) {
                                $ep_data['e_area'] = $_REQUEST['e_area'];
                                $ep->set($ep_data);
                                $msg = $ep->saveMDS();
                                $msg['msg'] = L("区域修改成功");
                                $this->save_message($ep_data);
                                echo json_encode($msg);
                                exit();
                        }
                        //区间范围外
                        $msg['status'] = -1;
                        $msg['msg'] = L("选择的企业的区域不在设备区域内！");
                        echo json_encode($msg);
                        exit;
                } else {
                        // 真实迁移
                        $this->move_mds_item_real();
                }
                exit;
        }

        function save_message($data) {
                try
                {
                        if ($data["e_id"] == "") {
                                if ($data["e_has_vcr"] == "") {
                                        $this->tools->send("ExCreate", $data["e_id"]);
                                } else {
                                        $this->tools->send("ExCreateVCR", $data["e_id"]);
                                }
                        } else {
                                if ($data["e_has_vcr"] == "") {
                                        $this->tools->send("ExEdit", $data["e_id"]);
                                } else {
                                        $this->tools->send("ExEditVCR", $data["e_id"]);
                                }
                        }
                } catch (Exception $ex) {
                        $enterprise->updateStatus(array(3, $data['e_id']));
                        throw new Exception($ex->getMessage(), 0);
                }
        }

    function move_mds_item_real() {
            $enterprise = new enterprise($_REQUEST);
            $data = $enterprise->getByid(TRUE);
            /*try {
                $this->checksure();
            } catch (Exception $exc) {
                $this->tools->call($exc->getMessage(), -1, TRUE);
            }*/
            if ($data['e_status'] == 0) {
                    //停用状态改ID不发包
                    $msg = $enterprise->moveMDS();
                    $newdata = $enterprise->getByid(TRUE);
                    $log = DL('停用状态 迁移'.$_SESSION['ident'].'-Server成功， 企业ID：【%s】 【%s】->【%s】，【%s】->【%s】');
                    $log = sprintf($log
                            , $data['e_id']
                            , mod_area_name($data['e_area'])
                            , mod_area_name($newdata['e_area'])
                            , $data['mds_d_ip1']
                            , $newdata['mds_d_ip1']
                    );

                        $enterprise->log($log, 1, 0);
                        $msg["status"] = 0;
                        $msg["msg"] = L('停用状态 迁移'.$_SESSION['ident'].'-Server成功， 企业ID：【%s】 【%s】->【%s】，【%s】->【%s】');
                        $msg["msg"] = sprintf($msg["msg"]
                                , $data['e_id']
                                , mod_area_name($data['e_area'])
                                , mod_area_name($newdata['e_area'])
                                , $data['mds_d_ip1']
                                , $newdata['mds_d_ip1']
                        );
                } else {
                        //启用状态不改ID只发包
                        $enterprise->updateStatus(array(2, $data['e_id']));
                        try
                        {
                                $this->tools->send("ExMoveMds", $data["e_id"] . ' ' . $_REQUEST['new_mds_id'] . ' ' . $_REQUEST['e_area']);
                                $log = DL('启用状态 迁移'.$_SESSION['ident'].'-Server成功， 企业ID：【%s】');
                                $log = sprintf($log
                                        , $data['e_id']
                                );
                                $enterprise->log($log, 1, 0);
                                $msg["msg"] = L('启用状态 迁移'.$_SESSION['ident'].'-Server成功， 企业ID：【%s】');
                                $msg["status"] = 0;
                                $msg["msg"] = sprintf($msg["msg"]
                                        , $data['e_id']
                                );
                        } catch (Exception $ex) {
                                $enterprise->updateStatus(array(3, $data['e_id']));
                                $log = DL('迁移'.$_SESSION['ident'].'-Server失败， 企业ID：【%s】');
                                $log = sprintf($log
                                        , $data['e_id']
                                );

                                $event['id'] = $enterprise->md5r();
                                $event['msg'] = $ex->getMessage();
                                $enterprise->log($log, 1, 2, $event);
                                $msg["msg"] = L('迁移'.$_SESSION['ident'].'-Server失败， 企业ID：【%s】');
                                $msg["status"] = -1;
                                $msg["msg"] = sprintf($msg["msg"]
                                        , $data['e_id']
                                );
                        }
                }

                echo json_encode($msg);
                exit();
        }

        /**
         * 企业迁移VCR
         * @deprecated 停用
         */
        function move_vcr_item() {
                $enterprise = new enterprise($_REQUEST);
                $aEn = $enterprise->getByid();
                $state = 1;
                if(!$aEn['e_vcr_id'])
                {
                    $state = 0;
                }
                $msg = $enterprise->moveVCR();

                $data = $enterprise->getByid(TRUE);
                if($state == 1)
                {
                    $this->tools->send("ExMoveVcr", $data["e_id"] . ' ' . $_REQUEST['new_vcr_id']);
                    $log = DL('启用状态 迁移'.$_SESSION['ident'].'-RS成功， 企业ID：【%s】');
                    $log = sprintf($log
                            , $data['e_id']
                    );
                    $enterprise->log($log, 1, 0);
                    $msg["msg"] = L('启用状态 迁移'.$_SESSION['ident'].'-RS成功， 企业ID：【%s】');
                    $msg["status"] = 0;
                    $msg["msg"] = sprintf($msg["msg"]
                            , $data['e_id']
                    );
                }
                else//新建企业消息
                {
                    $this->tools->send("ExCreateVCR", $data["e_id"]);
                    $log = DL('启用状态 迁移'.$_SESSION['ident'].'-RS成功， 企业ID：【%s】');
                    $log = sprintf($log
                            , $data['e_id']
                    );
                    $enterprise->log($log, 1, 0);
                    $msg["msg"] = L('启用状态 迁移'.$_SESSION['ident'].'-RS成功， 企业ID：【%s】');
                    $msg["status"] = 0;
                    $msg["msg"] = sprintf($msg["msg"]
                            , $data['e_id']
                    );
                }
                echo json_encode($msg);
                exit();
        }

        /**
         * 企业列表显示
         * @return html_table 企业列表
         */
        function index_item() {
                //列表页分条数显示
                if($_REQUEST['ent_num']){
                    $_SESSION['enterprise_page_num'] = $_REQUEST['ent_num'];
                }
                if($_SESSION['enterprise_page_num']){
                    $_REQUEST['num'] = $_SESSION['enterprise_page_num'];
                    
                }
                //列表页分条数 选中的显示相应颜色
                if($_REQUEST['num']){
                    unset($_SESSION['color']);
                    $_SESSION['color'][$_REQUEST['num']] = 'style="background:#E5E5E5"';
                }else{
                    unset($_SESSION['color']);
                    $_SESSION['color'][10] = 'style="background:#E5E5E5"';
                }
		$enterprise = new enterprise($_REQUEST);
		$page = new page($_REQUEST);
		$user = new users($_REQUEST);
		if ($_REQUEST['do'] == 'console') {
			$list = $enterprise->getList();
			$this->smarty->assign('list', $list);
			$this->htmlrender('api/get_enterprise_list.tpl');
		} else {
			$page->setTotal($enterprise->getTotal());
			$list = $enterprise->getList($page->getLimit());
			$numinfo = $page->getNumInfo();
			$prev = $page->getPrev();
			$next = $page->getNext();
			//$phone_user = $users->getusertotal ( 1 );
			//$dispatch_user = $users->getusertotal ( 2 );
			//$gvs_user = $users->getusertotal ( 3 );
			//$this->smarty->assign ( 'phone_user' , $phone_user );
			//$this->smarty->assign ( 'dispatch_user' , $dispatch_user );
			//$this->smarty->assign ( 'gvs_user' , $gvs_user );
			$this->smarty->assign('list', $list);
			$this->smarty->assign('numinfo', $numinfo);
			$this->smarty->assign('prev', $prev);
			$this->smarty->assign('next', $next);
			$this->smarty->assign('lang', $_COOKIE['lang']);
			$this->htmlrender('modules/enterprise/index_item.tpl');
		}
		exit();
	}

	/**
	 * 企业删除
	 * @return int 删除成功的企业用户数
	 */
	function index_del() {
		$enterprise = new enterprise($_REQUEST);
		$result = $enterprise->delList();
		if(!empty($result['list']))
                {
                    $list = explode(',', $result['list']);
                    foreach ($list as $key => $value) {
                        $value = str_replace("'","",$value);
                        $this->tools->send("DelEx", $value);
                    }
                    echo $result['count'];
                }
                else
                    echo 0;
		 
		exit();
	}

	/**
	 * 设备许可
	 */
	public function checksure() {
		$device = new device($_REQUEST);
		$item_e = $_REQUEST;
		$result = $device->getMDSListOption();
		foreach ($result as $value) {
			if ($value["d_id"] == $_REQUEST["e_mds_id"]) {
				$array = $value;
			}
		}
                    $permit=new permit();
                    $dd=$permit->get_ag_permit(array ( 'ag_number' => $_SESSION['ag']['ag_number'] ),array ( 'aggents_number' => $_SESSION['ag']['ag_number'] , 'ag_level' => $_SESSION['ag']['ag_level'] ));
                    $phone=$_REQUEST['ag_phone_num'];
                    $dispatch=$_REQUEST['ag_dispatch_num'];
                    $gvs=$_REQUEST['ag_gvs_num'];
//                $phone_num = $array['diff_phone'] + $array['sum_phone'];
//                $dispatch_num = $array['diff_dispatch'] + $array['sum_dispatch'];
//                $gvs_num = $array['diff_gvs'] + $array['sum_gvs'];

                if($_REQUEST['did']=="edit"){
//                    $phone_num = $array['diff_phone'] + $array['sum_phone'];
//                    $dispatch_num = $array['diff_dispatch'] + $array['sum_dispatch'];
//                    $gvs_num = $array['diff_gvs'] + $array['sum_gvs']; 
                    
                   if($dd['c_phone']<$array['diff_phone']){
                        $phone_num = $dd['c_phone']+$dd['phone'] ;
                    }else{
                        $phone_num = $array['diff_phone']+$dd['phone'] ;
                    }
                    if($dd['c_dispatch']<$array['diff_dispatch']){
                        $dispatch_num = $dd['c_dispatch']+$dd['dispatch'] ;
                    }else{
                        $dispatch_num = $array['diff_dispatch']+$dd['dispatch'] ;
                    }
                    if($dd['c_gvs']<$array['diff_gvs']){
                        $gvs_num = $dd['c_gvs']+$dd['gvs'] ;
                    }else{
                        $gvs_num = $array['diff_gvs']+$dd['gvs'];
                    }
                    
                }else{
                   if($dd['c_phone']<$array['diff_phone']){
                        $phone_num = $dd['c_phone'] ;
                    }else{
                        $phone_num = $array['diff_phone'] ;
                    }
                    if($dd['c_dispatch']<$array['diff_dispatch']){
                        $dispatch_num = $dd['c_dispatch'] ;
                    }else{
                        $dispatch_num = $array['diff_dispatch'] ;
                    }
                    if($dd['c_gvs']<$array['diff_gvs']){
                        $gvs_num = $dd['c_gvs'] ;
                    }else{
                        $gvs_num = $array['diff_gvs'];
                    }
                }
                $users = new users($_REQUEST);
                if ($_REQUEST['e_id'] != null) {
                        $phone_user = $users->getusertotal(1);
                        $dispatch_user = $users->getusertotal(2);
                        $gvs_user = $users->getusertotal(3);
                }

                if($_REQUEST['e_vcr_id'] != null)
                {
                    $_REQUEST['e_rs_rec'] = $_REQUEST['e_mds_phone']*2 +$_REQUEST['e_mds_dispatch'] + $_REQUEST['e_mds_gvs'];
                    if($_REQUEST['e_rs_rec'] == 0)
                    {
                        throw new Exception(L("用户数不能为0"), -1);
                    }
                    if($_REQUEST['e_id'])
                    {
                        $edit = $_REQUEST['e_id'];
                    }
                    else
                    {
                        $edit = '0';
                    }
                    $aRs = $device->getRsDevice($_REQUEST['e_vcr_id'],$edit);
                    
                    $Available = $aRs[0]['d_recnum'] - $aRs[0]['sum_rec'];
                    
                    if($_REQUEST['e_rs_rec'] > $Available)
                    {
                        throw new Exception(L("可用并发数超过rs设备最大并发数"), -1);
                    }
                }
                if ($item_e['e_mds_phone'] > $phone_num) {
                        throw new Exception(L("剩余用户许可不足"), -1);
                } else if ($item_e["e_mds_phone"] < $phone_user) {
                        throw new Exception(L("手机用户数小于已存在手机用户数,最小应为").":" . $phone_user, -1);
                } else if ($item_e["e_mds_dispatch"] > $dispatch_num) {
                        throw new Exception(L("剩余用户许可不足"), -1);
                } else if ($item_e["e_mds_dispatch"] < $dispatch_user) {
                        throw new Exception(L("调度台用户数小于已存在调度台用户数,最小应为").":" . $dispatch_user, -1);
                } else if ($item_e["e_mds_gvs"] > $gvs_num) {
                        throw new Exception(L("剩余用户许可不足"), -1);
                } else if ($item_e["e_mds_gvs"] < $gvs_user) {
                        throw new Exception(L("GVS用户数小于已存在GVS用户数,最小应为").":" . $gvs_user, -1);
                }
        }

	/**
	 * 企业保存后台接口
	 * @throws Exception
	 */
	function save_shell() {
		if(isset($_REQUEST['e_vcr_id']) && $_REQUEST['e_vcr_id'] == "")
	            {
	                unset($_REQUEST['e_vcr_id']);
	            }
		$enterprise = new enterprise($_REQUEST);
		try
		{

                        try
                        {
                                $this->checksure();
                        } catch (Exception $ex) {
                                //$this->user->log ( $ex->getMessage () , 1 , 2 );
                                $this->tools->call($ex->getMessage(), -1, TRUE);
                        }
                        $msg = $enterprise->save();
                } catch (ErrorException $ex) {
                        $msg = array();
                        $msg['status'] == -1;
                        $msg['msg'] = $ex->getMessage();
                        echo json_encode($msg);
                        exit();
                }
                if ($msg['status'] == -1) {
                        echo json_encode($msg);
                        exit();
                }

		$data = $enterprise->get();
		$aE = $enterprise->getByid();
		try
		{
			if ($_REQUEST["e_id"] == "") {
				// 不具有VCR
				if ($_REQUEST["e_has_vcr"] == "") {
					$this->tools->send("ExCreate", $msg["e_id"]);
				} else {
					//具有VCR
					$this->tools->send("ExCreateVCR", $msg["e_id"]);
				}
			} else {
				// 编辑不具有VCR
				if ($_REQUEST["e_has_vcr"] == "") {
					if($aE['e_status'] != '5')
                    {
						$this->tools->send("ExEdit", $msg["e_id"]);
					}
				} else {
					//编辑具有VCR
					if($aE['e_status'] != '5')
                    {
						$this->tools->send("ExEditVCR", $msg["e_id"]);
					}
				}
			}
		} catch (Exception $ex) {
			$enterprise->updateStatus(array(3, $data['e_id']));
			throw new Exception($ex->getMessage(), 0);
		}
		echo json_encode($msg);
		exit();
	}

    /**
     * 停用企业后台接口
     * @throws Exception
     */
    function stop() {
            $_REQUEST['eh_status']=0;
            $enterprise = new enterprise($_REQUEST);
            $msg = $enterprise->changeStatus(3);
//            $date=date("Y-m-d",time());
//            $enterprise->changeStopTime($date);
            $data = $enterprise->getByid();
            $data_item = $enterprise->get();

                $log = DL('停用了企业【%s】， 企业ID：【%s】');
                $log = sprintf($log
                        , $data['e_name']
                        , $data['e_id']
                );
                $enterprise->log($log, 1, 0);

                try
                {
                        // if ($data["e_has_vcr"] == 0) {
                                $this->tools->send("ExStop", $data["e_id"]);
                        // } else {
                                // $this->tools->send("ExStopVCR", $data["e_id"]);
                        // }
                } catch (Exception $ex) {
                        $enterprise->updateStatus(array(3, $data_item['e_id']));
                        throw new Exception($ex->getMessage(), -1);
                }

                echo json_encode($msg);
                exit();
        }

	/**
	 * 启用企业后台接口
	 * @throws Exception
	 */
	function start() {
        $_REQUEST['eh_status']=1;
		$enterprise = new enterprise($_REQUEST);
		$msg = $enterprise->changeStatus(3);
                                   //$enterprise->changeStopTime();
		$data = $enterprise->getByid();
		$data_item = $enterprise->get();
		try
		{
            //启用发送创建消息
//            $this->tools->send("ExCreate", $data["e_id"]);
                   $this->tools->send("ExStart", $data["e_id"]);             
            /*
			if ($data["e_has_vcr"] == 0) {
				$this->tools->send("ExStart", $data["e_id"]);
			} else {
				$this->tools->send("ExStartVCR", $data["e_id"]);
			}
            */
		} catch (Exception $ex) {
			$enterprise->updateStatus(array(3, $data_item['e_id']));
			throw new Exception($ex->getMessage(), -1);
		}
		$log = DL('启用了企业【%s】， 企业ID：【%s】');
		$log = sprintf($log
			, $data['e_name']
			, $data['e_id']
		);
		$enterprise->log($log, 1, 0);
		echo json_encode($msg);
		exit();
	}

    /**
     * 企业数据同步接口
     * @throws Exception
    */
   function sync() {
           $enterprise = new enterprise($_REQUEST);

           $data = $enterprise->getByid();
           $data_item = $enterprise->get();
           //$msg = $enterprise->changeSync(FALSE, 0);
           try
           {
                   if ($data["e_has_vcr"] == 0) {

                           $this->tools->send("ExSync", $data["e_id"] . " " . $data["e_sync"]);
                   } else {
                          $this->tools->send("ExSyncVCR", $data["e_id"]);
                   }
           } catch (Exception $ex) {
                   $enterprise->updateStatus(array(3, $data_item['e_id']));
                   //$enterprise->changeStatus(3);
                   throw new Exception($ex->getMessage(), -1);
           }
          // echo json_encode(array("msg"=>"12313asdsad"));
           sleep(1);
           $msg = $enterprise->changeSync(FALSE, 0);
           echo json_encode($msg);

           // 日志记录
           $log = DL('企业【%s】 同步了数据 企业ID：【%s】');
           $log = sprintf($log
                   , $data['e_name']
                   , $data['e_id']
           );
           $enterprise->log($log, 1, 0);
           exit();
   }

        /**
         * 企业后台状态刷新接口
         * @throws Exception
         */
        function refresh() {
                $enterprise = new enterprise($_REQUEST);
                $tools = new tools();
                try
                {
                        $resultlist = $enterprise->refreshList();
                } catch (Exception $ex) {
                        $tools->call($ex->getMessage(), 0, true);
                }
                $data = $enterprise->get();
                foreach ($resultlist as $value) {
                        $data["e_id"] = $value;
                        $enterprise->set($data);
                        $item = $enterprise->getByid();
                        try
                        {
                            if($item['e_status'] == '5' || $item['e_status'] == '9')//状态为创建中时，或创建失败 发企业开户通知包
                            {
                                if ($_REQUEST["e_has_vcr"] == "") {
                                        $this->tools->send("ExCreate", $item["e_id"]);
                                } else {
                                        //具有VCR
                                        $this->tools->send("ExCreateVCR", $item["e_id"]);
                                }
                            }else if($item['e_status'] == '7' || $item['e_status'] == '8')//状态为迁移中时，或迁移失败 发企业开户通知包
                            {
                                        $this->tools->send("ExTransfer", $item["e_id"]);
                            }
                            else 
                            {
                				if ($item["e_has_vcr"] == 0) {
                					$this->tools->send("ExSync", $item["e_id"] . ' ' . (string) (sendmsg::E_INFO_FLAVOR | $item['e_sync']));
                				} else {
                					$this->tools->send("ExSyncVCR", $item["e_id"] . ' ' . (string) (sendmsg::E_INFO_VCR_FLAVOR | $item['e_sync']));
                				}
			                }
			} catch (Exception $ex) {
				//$enterprise->updateStatus(array(3, $value));
				$enterprise->changeStatus(3);
				throw new Exception($ex->getMessage(), -1);
			}
		}
		$tools->call(L("成功"), 0, true);
	}

	/**
	 * 企业数据库初始化
	 * @deprecated 停用
	 */
	function initdb() {
		$enterprise = new enterprise($_REQUEST);
		$tools = new tools();
		try
		{
			$result = $enterprise->initDB();
		} catch (Exception $ex) {
			$enterprise->log(DL("企业数据库初始化失败") . "：" . $ex->getMessage(), 0, 2);
			$msg[status] = -1;
			$msg[msg] = L('企业数据库初始化失败');
			$result = $msg;
		}
		$tools->show($result);
	}

        /**
         * 企业管理列表显示层
         */
        function index() {
                $mininav = array(
                        array(
                                "url" => "#",
                                "name" => "企业管理",
                                "next" => "",
                        ),
                );
                //列表页分条数 选中的显示相应颜色
                if($_REQUEST['ent_num']){
                    unset($_SESSION['color']);
                    $_SESSION['color'][$_REQUEST['ent_num']] = 'style="background:#E5E5E5"';
                }elseif($_SESSION['enterprise_page_num']){
                    unset($_SESSION['color']);
                    $_SESSION['color'][$_SESSION['enterprise_page_num']] = 'style="background:#E5E5E5"';
                }else{
                    unset($_SESSION['color']);
                    $_SESSION['color'][10] = 'style="background:#E5E5E5"';
                }
                /* $this->smarty->assign ( 'mininav' , $mininav ); */
                $this->render('modules/enterprise/index.tpl', L('企业管理'), array('autoselect'));
        }

    /**
     * 企业新增显示层
     */
    function add() {
            $mininav = array(
                    array(
                            "url" => "?m=enterprise&a=index",
                            "name" => "企业管理",
                            "next" => ">>",
                    ),
                    array(
                            "url" => "#",
                            "name" => "新增企业",
                            "next" => "",
                    ),
            );
        $permit=new permit();
        $dd=$permit->get_ag_permit(array ( 'ag_number' => $_SESSION['ag']['ag_number'] ),array ( 'aggents_number' => $_SESSION['ag']['ag_number'] , 'ag_level' => $_SESSION['ag']['ag_level'] ));	
        $this->smarty->assign('phone', $dd['c_phone']);
        $this->smarty->assign('dispatch', $dd['c_dispatch']);
        $this->smarty->assign('gvs', $dd['c_gvs']);
        $this->smarty->assign('mininav', $mininav);
        if($_SESSION['ident']=="VT"){
                    $this->render('modules/enterprise/add_vt.tpl', L('新增企业'), array('autoselect'));
                }else if($_SESSION['ident']=="GQT"){
                    $this->render('modules/enterprise/add.tpl', L('新增企业'), array('autoselect'));
                }else{
                    $this->render('modules/enterprise/add_vt.tpl', L('新增企业'), array('autoselect'));
                }
    }

	/**
	 * 企业编辑显示层
	 */
    function edit() {
            $enterprise = new enterprise($_REQUEST);
            $data = $enterprise->getByid(true);
            $users = new users($_REQUEST);
            $phone_num = $users->getusertotal(1);
            $dispatch_num = $users->getusertotal(2);
            $gvs_num = $users->getusertotal(3);
            $permit=new permit();
            $dd=$permit->get_ag_permit(array ( 'ag_number' => $_SESSION['ag']['ag_number'] ),array ( 'aggents_number' => $_SESSION['ag']['ag_number'] , 'ag_level' => $_SESSION['ag']['ag_level'] ));	

            $this->smarty->assign('phone', $dd['c_phone']);
            $this->smarty->assign('dispatch', $dd['c_dispatch']);
            $this->smarty->assign('gvs', $dd['c_gvs']);
            $mininav = array(
                    array(
                            "url" => "?m=enterprise&a=index",
                            "name" => "企业管理",
                            "next" => ">>",
                    ),
                    array(
                            "url" => "?m=enterprise&a=view&e_id=" . $_REQUEST["e_id"],
                            "name" => $data["e_name"] . " - " . L("企业信息"),
                            "next" => ">>",
                    ),
                    array(
                            "url" => "?m=enterprise&a=edit&e_id=" . $_REQUEST["e_id"],
                            "name" => "编辑企业信息",
                            "next" => "",
                    ),
            );
            $this->smarty->assign('mininav', $mininav);
            $this->smarty->assign('data', $data);
            $this->smarty->assign('phone_num', $phone_num);
            $this->smarty->assign('dispatch_num', $dispatch_num);
            $this->smarty->assign('gvs_num', $gvs_num);
             if($_SESSION['ident']=="VT"){
                     $this->render('modules/enterprise/edit_vt.tpl', L('编辑企业信息'));
                }else if($_SESSION['ident']=="GQT"){
                    $this->render('modules/enterprise/edit.tpl', L('编辑企业信息'));
                }else{
                     $this->render('modules/enterprise/edit_vt.tpl', L('编辑企业信息'));
                }
    }

	/**
	 * 企业信息显示层
	 */
function view() {
        $_REQUEST["do"] = "mds";
        $enterprise = new enterprise ( $_REQUEST );
        $users = new users ( $_REQUEST );
        $_REQUEST['em_id'] = $_REQUEST['e_id'];
        $admins = new admins ( $_REQUEST );
        $data = $enterprise->getByid();
        $info = $admins->getByid ();
        $usernum = $users->getTotal(FALSE);
        $phone_num = $users->getusertotal(1);
        $dispatch_num = $users->getusertotal(2);
        $gvs_num = $users->getusertotal(3);
        $this->smarty->assign('phone_num', $phone_num);
        $this->smarty->assign('dispatch_num', $dispatch_num);
        $this->smarty->assign('gvs_num', $gvs_num);
$e_info = $enterprise->get_history_list();
        $mininav = array(
                array(
                        "url" => "?m=enterprise&a=index",
                        "name" => "企业管理",
                        "next" => ">>",
                ),
                array(
                        "url" => "?m=enterprise&a=view&e_id=" . $_REQUEST["e_id"],
                        "name" => $data["e_name"] . " - " . L("企业信息"),
                        "next" => "",
                ),
        );
        $this->smarty->assign('mininav', $mininav);
        $this->smarty->assign('data', $data);
        $this->smarty->assign('info', $info);
        $this->smarty->assign('ep', $data);
        $this->render('modules/enterprise/view.tpl', L("企业信息"));
}

    /*
    * @author hongyuan.li@zed-3.com.cn 
    * @copyright 迁移设备 2015.12.14
    */
    function move_device()
    {
        $enterprise = new enterprise($_REQUEST);
        $data = $enterprise->getByid();
        $mininav = array(
                array(
                        "url" => "?m=enterprise&a=index",
                        "name" => "企业管理",
                        "next" => ">>",
                ),
                array(
                        "url" => "?m=enterprise&a=view&e_id=" . $_REQUEST["e_id"],
                        "name" => $data["e_name"] . " - " . L("企业信息"),
                        "next" => ">>",
                ),
                array(
                        "url" => "?m=enterprise&a=move_mds&e_id=" . $_REQUEST["e_id"],
                        "name" => "迁移设备",
                        "next" => "",
                ),
        );
        $this->smarty->assign('mininav', $mininav);
        $e_area = str_replace('"', '', $data['e_area']);
        $this->smarty->assign("e_area",$e_area);
        $this->smarty->assign('data', $data);
        $this->render('modules/enterprise/move_device.tpl', L('迁移设备'), array('autoselect'));
    }
	/**
	 * 迁移GQT-Server显示层
	 */
	function move_mds() {
		$enterprise = new enterprise($_REQUEST);
		$data = $enterprise->getByid();
		$mininav = array(
			array(
				"url" => "?m=enterprise&a=index",
				"name" => "企业管理",
				"next" => ">>",
			),
			array(
				"url" => "?m=enterprise&a=view&e_id=" . $_REQUEST["e_id"],
				"name" => $data["e_name"] . " - " . L("企业信息"),
				"next" => ">>",
			),
			array(
				"url" => "?m=enterprise&a=move_mds&e_id=" . $_REQUEST["e_id"],
				"name" => "迁移".$_SESSION['ident']."-Server",
				"next" => "",
			),
		);
		$this->smarty->assign('mininav', $mininav);

		$this->smarty->assign('data', $data);
		$this->render('modules/enterprise/move_mds.tpl', L('迁移'.$_SESSION['ident'].'-Server'), array('autoselect'));
	}

    /**
     * 迁移VCR显示层
     * @deprecated 停用
     */
    function move_vcr() {
            if(isset($_REQUEST['e_vcr_id']) && $_REQUEST['e_vcr_id'] == "")
            {
                unset($_REQUEST['e_vcr_id']);
            }
            $enterprise = new enterprise($_REQUEST);
            $data = $enterprise->getByid();
            $this->smarty->assign('data', $data);
            $this->render('modules/enterprise/move_rs.tpl', L('迁移'.$_SESSION['ident'].'-RS'));
    }

    /**
     * @author yuejun.wang
     * @copyright 2015/12/10
     * 迁移企业(获取符合迁移条件的代理)
     * 
     */
    public function move_enterprise()
    {
        $enterprise = new enterprise($_REQUEST);
        $data = $enterprise->get_agents();
        echo json_encode($data);
    }

    /**
     * @author yuejun.wang
     * @copyright 2015/12/10
     * 迁移企业(改变企业代理操作)
     * 
     */
    public function change_enterprise()
    {
        $enterprise = new enterprise($_REQUEST);
        $data = $enterprise->change_enterprise();
        echo $data;
    }

    /**
     * 获得omp超级管理员下的企业列表
     */
    public function getagenlist ()
    {
        $_REQUEST['e_agents_id'] = 0;
        $enterprise = new enterprise ( $_REQUEST );
        $data = $enterprise->getoptionlist ();
        foreach ( $data as $key => $value )
        {
            $users = new users ( array ( "e_id" => $value['e_id'] ) );
            $phone_num = $users->getusertotal ( 1 );
            $dispatch_num = $users->getusertotal ( 2 );
            $gvs_num = $users->getusertotal ( 3 );
            $data[$key]['u_diff_phone'] = $value['e_mds_phone'] - $phone_num;
            $data[$key]['u_diff_dispatch'] = $value['e_mds_dispatch'] - $dispatch_num;
            $data[$key]['u_diff_gvs'] = $value['e_mds_gvs'] - $gvs_num;
        }
        $this->smarty->assign ( "list" , $data );
        $this->htmlrender ( 'modules/enterprise/en_option.tpl' );
    }
    
    public function create_option(){
        $_REQUEST['ag_number']=$_SESSION['ag']['ag_number'];
        $enterprise=new enterprise($_REQUEST);
        $data = $enterprise->getoptionlist_ag();
        foreach ($data as $key => $value) {
            if($value['e_create_name']!=null){
            $list[$key]['id']=$value['e_create_name'];
            $list[$key]['name']=$value['e_create_name'];
            }
        }
        $list=array_unique_fb($list);
        $this->smarty->assign('list', $list);
        $this->htmlrender ( 'viewer/option.tpl' );
    }
    /**
     * 企业迁移VCR
     * @deprecated 停用
     */
    function move_device_item() {
        $enterprise = new enterprise($_REQUEST);
        $aEn = $enterprise->getByid();
        try
        {
            if($_REQUEST['new_vcr_id'] && $_REQUEST['new_vcr_id'] != '0')
            {
                $e_rs_rec = $aEn['e_mds_phone']*2 + $aEn['e_mds_dispatch'] + $aEn['e_mds_gvs'];
                if($e_rs_rec == 0)
                {
                    throw new Exception(L("用户数不能为0"), -1);
                }
                
            }
            if(($aEn['e_mds_id'] == $_REQUEST['new_mds_id']) && ($aEn['e_vcr_id'] == $_REQUEST['new_vcr_id']) && ($aEn['e_ss_id'] == $_REQUEST['new_ss_id']) )
            {
                throw new Exception(L("没有迁移任何设备"), -1);
            }
        } catch (Exception $ex) {
            $this->tools->call($ex->getMessage(), -1, TRUE);
        }
        $aKey = array();
        if($_REQUEST['new_vcr_id'] != $aEn['e_vcr_id']) //修改企业状态，对应server、rs、ss设备id及e_has_vcr和e_rs_rec字段
        {
            if($aEn['e_status'] == '0')
            {
                $aKey['e_status'] = "0";
            }
            else
            {
                $aKey['e_status'] = "7";
            }
            $aKey['e_vcr_id'] = $_REQUEST['new_vcr_id'];
            $aKey['e_mds_id'] = $_REQUEST['new_mds_id'];
            $aKey['e_ss_id'] = $_REQUEST['new_ss_id'];
            $aKey['e_rs_rec'] = $_REQUEST['e_rs_rec"'];
            $aKey['e_has_vcr'] = $_REQUEST['e_has_vcr'];
            $aKey['e_id'] = $_REQUEST['e_id'];
            $msg = $enterprise->moveDevice($aKey);

        }
        else //修改修改企业状态，对应server、ss设备id
        {
            if($aEn['e_status'] == '0')
            {
                $aKey['e_status'] = "0";
            }
            else
            {
                $aKey['e_status'] = "7";
            }
            $aKey['e_mds_id'] = $_REQUEST['new_mds_id'];
            $aKey['e_vcr_id'] = $_REQUEST['new_vcr_id'];
            $aKey['e_ss_id'] = $_REQUEST['new_ss_id'];
            $aKey['e_id'] = $_REQUEST['e_id'];
            $msg = $enterprise->moveDevice($aKey);
        }

        $data = $enterprise->getByid(TRUE);
        //修改企业状态

        //发企业迁移消息
        $param = $data["e_id"];
        if($aEn['e_mds_id'] != $_REQUEST['new_mds_id'])
        {
            $ServerDev = "ServerDev:".$aEn['e_mds_id'].",".$_REQUEST['new_mds_id'];
        }
        else
        {
            $ServerDev = "ServerDev:null,null";
        }
        if(!$aEn['e_vcr_id'] && $_REQUEST['new_vcr_id'])
        {
            $RSDev = "RSDev:null,".$_REQUEST['new_vcr_id'];
        }
        else if($aEn['e_vcr_id'] && !$_REQUEST['new_vcr_id'])
        {
            $RSDev = "RSDev:".$aEn['e_vcr_id'].",null";
        }
        else if((!$aEn['e_vcr_id'] && !$_REQUEST['new_vcr_id']) || $aEn['e_vcr_id'] == $_REQUEST['new_vcr_id'] )
        {
            $RSDev = "RSDev:null,null";
        }
        else
        {
            $RSDev = "RSDev:".$aEn['e_vcr_id'].",".$_REQUEST['new_vcr_id'];;
        }
        if($aEn['e_ss_id'] != $_REQUEST['new_ss_id'])
        {
            $SSDev = "SSDev:".$aEn['e_ss_id'].",".$_REQUEST['new_ss_id'];
        }
        else
        {
            $SSDev = "SSDev:null,null";
        }
        $param .= " ".$ServerDev." ".$RSDev." ".$SSDev;
        if($aEn['e_status'] != '0')//停用企业只修改数据库不给dbm发消息
        {
            $this->tools->send("ExMoveDevice", $param);
        }
        $log = DL('启用状态 迁移设备成功， 企业ID：【%s】');
        $log = sprintf($log
                , $data['e_id']
        );
        $enterprise->log($log, 1, 0);
        $msg["msg"] = L('启用状态 迁移设备成功， 企业ID：【%s】');
        $msg["status"] = 0;
        $msg["msg"] = sprintf($msg["msg"]
                , $data['e_id']
        );
        
        echo json_encode($msg);
        exit();
    }

    /**
     * 企业操作历史记录主页面
     */
    public function enterprise_history(){
                $enterprise = new enterprise();
                $_REQUEST['e_id']=$_REQUEST['e_id'];
                $enterprise->set($_REQUEST);
                $info=$enterprise->getByid();
                $mininav = array(
                    array(
                        "url" => "?m=enterprise&a=index",
                        "name" => "企业管理",
                        "next" => ">>",
                    ),
                    array(
                        "url" => "?m=enterprise&a=view&e_id=" . $_REQUEST["e_id"],
                        "name" => $info["e_name"],
                        "next" => ">>",
                    ),
                    array(
                        "url" => "#",
                        "name" => "企业变更记录",
                        "next" => "",
                    ),
                );
                $this->smarty->assign('mininav', $mininav);
                $this->smarty->assign('data', $info);
                $this->render('modules/enterprise/enterprise_history.tpl',L("历史记录"));
        }
        /**
         * 企业操作历史记录列表页
         */
        public function enterprise_history_item(){
            $enterprise = new enterprise();
            $_REQUEST['e_id']=$_REQUEST['eh_e_id'];
            $enterprise->set($_REQUEST);
            $this->page->setTotal($enterprise->getTotal_enterprise_history());
            $list = $enterprise->get_history_list($this->page->getLimit());
            $info=$enterprise->getByid();
            $numinfo = $this->page->getNumInfo();
            $prev = $this->page->getPrev();
            $next = $this->page->getNext();
            $this->smarty->assign('list', $list);
            $this->smarty->assign('info', $info);
            $this->smarty->assign('numinfo', $numinfo);
            $this->smarty->assign('prev', $prev);
            $this->smarty->assign('next', $next);
            $this->htmlrender("modules/enterprise/enterprise_history_item.tpl");
        }
        
        public function set_ep_area(){
            $enterprise = new enterprise($_REQUEST);
            $msg=$enterprise->set_ep_area();
            
            echo json_encode($msg);
        }
}
