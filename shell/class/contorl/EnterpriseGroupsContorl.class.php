<?php

/**
 * 企业群组控制器
 * @category EMP
 * @package EMP_Enterprise_contorl
 * @require {@see contorl} {@see page} {@see enterprise} {@see users} {@see groups} {@see pttmember}
 */
class EnterpriseGroupsContorl extends contorl {

	public $enterprise;
	public $groups;
	public $tools;
	public $users;
	public $pttmember;
	public $custpgmember;
	public $usergroup;
	public $api;

	/**
	 * 群组导入
	 */
	public function importShellPT() {
		$step = is_string($_REQUEST['step']) ? $_REQUEST['step'] : '';
		if ($step === 'if') {
			$msg = $this->importPTFile();
			print "<script>parent.pt_if_callback(" . $msg . ")</script>";
			exit;
		}
		if ($step === 'ic') {
			try
			{
				$f = $this->importPTCheck();
				if (count($this->error) > 0) {
					$json['status'] = -1;
					$json['msg'] = '存在错误无法导入<br />';
				} else {
					$json['status'] = 0;
					$json['msg'] = '无严重错误<br />';
				}
				$json['msg'] .= '<div class="show">';
				$json['msg'] .= implode('<br />', $this->error);
				$json['msg'] .= "<hr />";
				$json['msg'] .= implode('<br />', $this->warn);
				$json['msg'] .= '</div>';

				$json['data'] = $f;
				$msg = json_encode($json);
			} catch (Exception $ex) {
				$json['status'] = -1;
				$json['msg'] = $ex->getMessage();
				$msg = json_encode($json);
			}
			print "<script>parent.pt_ic_callback(" . $msg . ")</script>";
			exit;
		}
		if ($step === 'i') {
			try
			{
				$this->importPT();

				if (count($this->error) > 0) {
					$json['status'] = -1;
					$json['msg'] = '存在错误';
					$json['msg'] .= '<div class="show">';
					$json['msg'] .= implode('<br />', $this->error);
					$json['msg'] .= '</div>';
				} else {
					$json['status'] = 0;
					$json['msg'] = '没有发现错误，导入完成';
				}

				$msg = json_encode($json);
			} catch (Exception $ex) {
				$json['status'] = -1;
				$json['msg'] = $ex->getMessage();
				$msg = json_encode($json);
			}
			print "<script>parent.pt_i_callback(" . $msg . ")</script>";
			exit;
		}
	}

	/**
	 * 用户导入检查
	 * @return string
	 * @throws Exception
	 */
	private function importPTCheck() {
		$f = filter_input(INPUT_GET, 'f');
		$e_id = filter_input(INPUT_GET, 'e_id');
		$file = $f . '.xls';
		$config = Cof::config();
		$filePath = $config['system']['webroot'] . DIRECTORY_SEPARATOR . "runtime" . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . $file;
		$objReader = PHPExcel_IOFactory::createReader('Excel5');

		$objPHPExcel = $objReader->load($filePath);
		$objWorksheet = $objPHPExcel->getSheet(0);

		//$highestColumn = $objWorksheet->getHighestColumn();
		$highestRow = $objWorksheet->getHighestRow(); //取得总行数

		$pttm = array();
		$error = array();
		$warn = array();
		$ptnumber = array();
		$wz = "";
		for ($row = 2; $row <= $highestRow; $row++) {
			$tmpName = trim($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
			if ($tmpName !== '') {
				$wz = $tmpName;

				if (!Cof::re('/A-z0-9#.-_（）()/', $tmpName, 64)) {
					$error[] = "第 $row 行，$tmpName 包含 A-z0-9#.-_（）()以外的字符";
				}
			}
			$tmpuser = array();
			$tmpuser['pm_number'] = trim($objWorksheet->getCellByColumnAndRow(1, $row)->getValue());
			$tmpuser['pm_level'] = trim($objWorksheet->getCellByColumnAndRow(2, $row)->getValue());
			$tmpuser['pm_default'] = trim($objWorksheet->getCellByColumnAndRow(3, $row)->getValue());
			$tmpuser['pm_hangup'] = trim($objWorksheet->getCellByColumnAndRow(4, $row)->getValue());

			if (!Cof::re('/[0-9]/', $tmpuser['pm_number'])) {
				$error[] = "第 $row 行，" . $tmpuser['pm_number'] . " 帐号不是数字";
			} else {
				if ($tmpuser['pm_default'] == '1') {
					$ptnumber[] = $tmpuser['pm_number'];
				}
			}

			if (!($tmpuser['pm_level'] >= 1 && $tmpuser['pm_level'] <= 255)) {
				$error[] = "第 $row 行，" . $tmpuser['pm_level'] . " 不符合 大于1，小于255规定";
			}
			if (!Cof::re("/[01]/", $tmpuser['pm_default'])) {
				$warn[] = "警告 第 $row 行，是否默认组" . $tmpuser['pm_default'] . " 不符合1，0值规定。（默认值：0）";
			}
			if (!Cof::re("/[01]/", $tmpuser['pm_hangup'])) {
				$warn[] = "警告 第 $row 行，被叫挂断权限" . $tmpuser['pm_hangup'] . " 不符合1，0值规定。（默认值：0）";
			}
			$pttm[$wz][] = $tmpuser;
		}

		$ptnumber_unique = array_unique($ptnumber);
		if (count($ptnumber_unique) != count($ptnumber)) {
			$error[] = "警告 ，有用户同时具有多个默认组";
		}
		$this->warn = $warn;
		$this->error = $error;
		return $f;
	}

	// 导入文件
	private function importPTFile() {
		$json = array();
		try
		{
			$file = Cof::upload();
			$json['status'] = 0;
			$json['data'] = str_replace('.xls', '', $file); //清除后缀信息
		} catch (Exception $ex) {
			$json['status'] = -1;
			$json['msg'] = $ex->getMessage();
		}
		return json_encode($json);
	}

	// 数据导入
	private function importPT() {
		$e_id = filter_input(INPUT_GET, 'e_id');
		$f = filter_input(INPUT_GET, 'f');
		$file = $f . '.xls';
		$config = Cof::config();
		$filePath = $config['system']['webroot'] . DIRECTORY_SEPARATOR . "runtime" . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . $file;
		$objReader = PHPExcel_IOFactory::createReader('Excel5');

		$objPHPExcel = $objReader->load($filePath);
		$objWorksheet = $objPHPExcel->getSheet(0);

		$highestRow = $objWorksheet->getHighestRow(); //取得总行数
		// 实际数据读取，数据导入
		$pttm = array();
		$error = array();
		$warn = array();
		$ptnumber = array();
		$wz = "";
		for ($row = 2; $row <= $highestRow; $row++) {
			$tmpName = trim($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
			if ($tmpName !== '') {
				$wz = $tmpName;

				if (!Cof::re('/A-z0-9#.-_（）()/', $tmpName, 64)) {
					$error[] = "第 $row 行，$tmpName 包含 A-z0-9#.-_（）()以外的字符";
				}
			}
			$tmpuser = array();
			$tmpuser['pm_number'] = trim($objWorksheet->getCellByColumnAndRow(1, $row)->getValue());
			$tmpuser['pm_level'] = trim($objWorksheet->getCellByColumnAndRow(2, $row)->getValue());
			$tmpuser['pm_default'] = trim($objWorksheet->getCellByColumnAndRow(3, $row)->getValue());
			$tmpuser['pm_hangup'] = trim($objWorksheet->getCellByColumnAndRow(4, $row)->getValue());
			$pttm[$wz][] = $tmpuser;
			$this->warn = $warn;
			$this->error = $error;
		}

		// 导入群组
		$ptg = new groups(array("e_id" => $e_id));
		$pgnumber = array();
		foreach ($pttm as $key => $value) {
			$data = array();
			$data['do'] = 'add';
			$data['e_id'] = $e_id;
			$tmppgnumber = (int) $ptg->getMaxId() + 1;
			$data['pg_number'] = $tmppgnumber;

			$data['pg_name'] = $key;
			$data['pg_level'] = 7;
			$data['pg_grp_idle'] = 30;
			$data['pg_speak_idle'] = 10;
			$data['pg_speak_total'] = 120;
			$data['pg_queue_len'] = 5;
			$data['pg_chk_stat_int'] = 1800;
			$data['pg_buf_size'] = 0;
			$data['pg_record_mode'] = 0;
			$ptg->set($data);
			$msg = $ptg->save();
			if ($msg['status'] == '0') {
				$pgnumber[$key] = $e_id . sprintf("%05d", $tmppgnumber);
			}
		}

		// 导入用户
		$ptm = new pttmember();
		foreach ($pgnumber as $key => $value) {
			foreach ($pttm[$key] as $v) {
				$data = array();
				$data['e_id'] = $e_id;
				$data['pm_number'] = $v['pm_number'];
				$data['pm_level'] = $v['pm_level'];
				$data['pm_pgnumber'] = $pgnumber[$key];
				$data['pm_hangup'] = $v['pm_hangup'];
				$ptm->set($data);
				$ptm->save();
			}
		}

		$error = array();
	}

	public function __construct() {
		parent::__construct();
		$this->tools = new tools();
		$this->enterprise = new enterprise($_REQUEST);
		$this->groups = new groups($_REQUEST);
		$this->users = new users($_REQUEST);
		$this->pttmember = new pttmember($_REQUEST);
		$this->page = new page($_REQUEST);
		$this->usergroup = new usergroup($_REQUEST);
		$this->api = new api($_REQUEST);
		$this->custpgmember = new custpgmember($_REQUEST);
	}

	public function groups_option() {
		$result = $this->groups->getList();

		foreach ($result as $key => $value) {
			$result[$key]['id'] = &$value['pg_number'];
			$result[$key]['name'] = &$value['pg_name'];
		}
		$this->smarty->assign("list", $result);
		$this->htmlrender('viewer/option_group.tpl');
	}

	function groups() {
		$data = $this->enterprise->getByid();
		$data['do'] = 'add';
		$this->page->setTotal($this->groups->getTotal());
		$num = $this->users->getTotal();
		$numinfo = $this->page->total();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		$list = $this->groups->getList();
		$ug_list = $this->usergroup->selectlist();
		$mininav = array(
			array(
				"url" => "?m=enterprise&a=index",
				"name" => "企业管理",
				"next" => ">>",
			),
			array(
				"url" => "?m=enterprise&a=admins&e_id=" . $_REQUEST["e_id"],
				"name" => $data["e_name"] . " - " . L("企业群组"),
				"next" => "",
			),
		);
		$this->smarty->assign('mininav', $mininav);
		$this->smarty->assign('data', $_REQUEST);
		$this->smarty->assign('list', $list);
		$this->smarty->assign('ug_list', $ug_list);
		$this->smarty->assign('data', $data);
		$this->smarty->assign('ep', $data);
		$this->smarty->assign('num', $num);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->render('modules/enterprise/groups.tpl', L('企业群组'));
	}

	function groups_add() {
		$data = $this->enterprise->getByid();
		$data['do'] = 'add';
		$this->smarty->assign('data', $data);
		$this->render('modules/enterprise/groups_add.tpl', L('新增企业群组'));
	}

	function groups_edit() {
		$data = $this->groups->getbyid();
		$this->enterprise_item = $this->enterprise->getByid();
		$data['e_id'] = $this->enterprise_item['e_id'];
		$data['e_name'] = $this->enterprise_item['e_name'];
		$data['do'] = 'edit';
		$data['pg_number'] = $_REQUEST['pg_number'];
		$mininav = array(
			array(
				"url" => "?m=enterprise&a=index",
				"name" => "企业管理",
				"next" => ">>",
			),
			array(
				"url" => "?m=enterprise&a=groups&e_id=" . $_REQUEST["e_id"],
				"name" => $data["e_name"] . " - " . L("企业群组"),
				"next" => ">>",
			),
			array(
				"url" => "?m=enterprise&a=groups_edit&e_id=" . $_REQUEST["e_id"],
				"name" => "编辑企业群组",
				"next" => "",
			),
		);
		$this->smarty->assign('mininav', $mininav);
		$this->smarty->assign('data', $data);
		$this->render('modules/enterprise/groups_add.tpl', L('编辑企业群组'));
	}

	function groups_view() {
		$this->smarty->assign('data', $_REQUEST);
		$this->render('modules/enterprise/groups_view.tpl', L('企业群组成员'));
	}

	function groups_view_edit() {
		$data = $this->pttmember->getbyid();
		$this->smarty->assign('data', $data);
		$this->render('modules/enterprise/groups_view_edit.tpl', L('编辑企业群组成员'));
	}

	function groups_view_add() {
		$this->smarty->assign('data', $_REQUEST);
		$this->render('modules/enterprise/groups_view_edit.tpl', L('新增企业群组成员'));
	}

	function groups_save() {
		$this->enterprise->changeSync(true, 16);
		try
		{

			if ($_REQUEST["pg_level"] == 0) {
				$info = $this->getdefaultpg();
				if ($info["status"] == -1) {
					$msg = $info;
				} else {
					$res = $this->get0groups($_REQUEST["pg_number"]);
					if ($res["status"] == 1) {
						$msg = $this->groups->save();
					} else {
						$msg = $res;
					}
				}
			} else {
				$msg = $this->groups->save();
			}
		} catch (Exception $ex) {
			$msg["status"] = -1;
		}
		$this->tools->show($msg);
	}

	function groups_save_v2() {
		$this->enterprise->changeSync(true, 16);

		try
		{

			$msg = $this->groups->save_v2();
		} catch (Exception $ex) {
			$msg["status"] = -1;
		}
		echo json_encode($msg);
	}

	public function getgpnum() {
		$num = $this->users->getpguserTotal();
		echo $num;
	}

	public function getugnum() {
		$num = $this->users->getTotal();
		echo $num;
	}

	function groups_item() {
		$total = $this->users->getTotal();
		$item_e = $this->enterprise->getByid();
		$this->smarty->assign('title', '企业群组');
		$this->page->setTotal($this->users->getTotal());
		$numinfo = $this->page->getNumInfo();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		$list = $this->users->getList($this->page->getLimit());
		foreach ($list as $val) {
			$pg_list[$val['u_number']] = $this->groups->getuserPgname($val['u_number'],$val['u_default_pg']);
		}
		$this->smarty->assign('data', $_REQUEST);
		$this->smarty->assign('list', $list);

		$this->smarty->assign('pg_list', $pg_list);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('total', $total);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->smarty->assign('item_e', $item_e);
		$this->htmlrender('modules/enterprise/groups_item.tpl', $num);
		exit();
	}

	function groups_item_pguser() {
		$total = $this->users->getpguserTotal();
		$item_e = $this->enterprise->getByid();
//$this->smarty->assign('title', '企业群组');
		$this->page->setTotal($this->users->getpguserTotal());
		$numinfo = $this->page->getNumInfo();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		$list = $this->users->getpttmb($this->page->getLimit());

		$this->smarty->assign('data', $_REQUEST);
		$this->smarty->assign('list', $list);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('total', $total);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->smarty->assign('item_e', $item_e);
		$result = $this->htmlrender('modules/enterprise/groups_item_pguser.tpl');

		return $result;
	}
	function cust_groups_item() {
		$total = $this->users->getTotal();
		$item_e = $this->enterprise->getByid();
		//$info = $this->custpgmember->getbyid ();
		$list = $this->users->getList($this->page->getLimit());
		foreach ($list as $val) {
			$pg_list[$val['u_number']] = $this->groups->getuserPgname($val['u_number'],$val['u_default_pg']);
		}
		$this->smarty->assign('data', $_REQUEST);
		$this->smarty->assign('list', $list);

		$this->smarty->assign('pg_list', $pg_list);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('total', $total);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->smarty->assign('item_e', $item_e);
		$this->htmlrender('modules/enterprise/cust_groups_item.tpl', $num);
		exit();
	}

	function cust_groups_item_pguser() {

		$item_e = $this->enterprise->getByid();
//$this->smarty->assign('title', '企业群组');
		$this->page->setTotal($this->users->getpguserTotal());
		$numinfo = $this->page->getNumInfo();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		//$list = $this->users->getpttmb ( $this->page->getLimit () );
		$info = $this->custpgmember->getbyid();

		$u_number_arr = array();
		$u_number_arr = explode(";", $info['c_pg_mem_list']);
                                    $n=0;
		foreach ($u_number_arr as $key => $value) {
                                            if ($this->users->getinfo($value) !== false) {
                                                    $list[] = $this->users->getinfo($value);
                                                                                            $n++;
                                            }
		}
		$total = $n;
		$list = array_slice($list, 0, 10);

		$this->smarty->assign('data', $_REQUEST);
		$this->smarty->assign('list', $list);
		$this->smarty->assign('info', $info);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('total', $total);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->smarty->assign('item_e', $item_e);
		$result = $this->htmlrender('modules/enterprise/cust_groups_item_pguser.tpl');

		return $result;
	}

	public function groups_gettotal() {
		$total = $this->page->total();
		echo json_decode($total);
	}

	function groups_del() {

		$this->enterprise->changeSync(true, 16);
		$list = $this->tools->get('list');

		$result['count'] = $this->groups->delList($list);
		echo $result['count'];
		exit();
	}

	function groups_view_item() {
		$this->page->setTotal($this->pttmember->getTotal());
		$numinfo = $this->page->getNumInfo();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		$list = $this->pttmember->getList($this->page->getLimit());

		$this->smarty->assign('list', $list);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->smarty->assign('data', $_REQUEST);

		$this->htmlrender('modules/enterprise/groups_view_item.tpl');
		exit();
	}

	function groups_view_edit_save() {
		$this->enterprise->changeSync(true, 16);
		if ($this->users->hasUser($_REQUEST['pm_number'])) {
			try
			{
				$this->pttmember->save();
				$result = $this->tools->call(L('操作成功'), 0, true);
			} catch (Exception $ex) {
				$log = $this->users->eventlog($ex, '群组成员添加失败');
				$this->users->log($log['msg'], 1, 2, $log['event']);
				$result = $this->tools->call($log['msg'], -1, true);
			}
		} else {
			$result = $this->tools->call(L('输入的号码不是成员号码'), -1, true);
		}
		exit();
	}

	function groups_view_edit_save_v2() {
		$this->enterprise->changeSync(true, 16);
		if ($this->users->hasUser($_REQUEST['pm_number'])) {
			try
			{
				$this->pttmember->save_v2();
				$result = $this->tools->call(L('操作成功'), 0, true);
			} catch (Exception $ex) {
				$log = $this->users->eventlog($ex, '群组成员添加失败');
				$this->users->log($log['msg'], 1, 2, $log['event']);
				$result = $this->tools->call($log['msg'], -1, true);
			}
		} else {
			$result = $this->tools->call(L('输入的号码不是成员号码'), -1, true);
		}
		exit();
	}

	function groups_view_del() {
		$this->enterprise->changeSync(true, 16);
		$this->pttmember->delGroupsUser();
		echo $this->tools->call(L('操作成功'), 0);
	}

    function groups_users_move() {
            $this->enterprise->changeSync(true, 16);
            if ($_REQUEST['move_u_default_pg'] == "0") {
                    $this->groups_view_del();
            } else {
                    if ($_REQUEST['move_u_default'] == '' || $_REQUEST['move_u_default'] == NULL) {
                            $data = $this->groups->get();
                            $data['pm_hangup'] = $data['move_u_hangup'];
                            $this->groups->set($data);
                            try
                            {
                                    $this->groups->addUser();
                                    echo $this->tools->call(L('操作成功'), 0);
                            } catch (Exception $ex) {
                                    echo $this->tools->call($ex->getMessage(), 0);
                            }
                    } else {
//选中群组是否为紧急对讲组

                            $info = $this->groups->getbyselectid();

                            if ($info["pg_level"] == 0) {
                                    echo $this->tools->call(L('该群组为紧急对讲组，用户无法设置为默认组'), 0);
                            } else {
                                    $user = new users($_REQUEST);
                                    foreach ($_REQUEST['checkbox'] as $value) {
                                            $this->tools->log($value, '_debug');
                                            $data['e_id'] = $_REQUEST['e_id'];
                                            $data['u_product_id'] = '%';
                                            $data['u_p_function_new'] = '%';
                                            $data['u_default_pg'] = $_REQUEST['move_u_default_pg'];
                                            $data['pm_hangup'] = $_REQUEST['move_u_hangup'];
                                            $data['u_number'] = $value;
                                            $pginfo = $this->users->getPGinfo($_REQUEST['move_u_default_pg']);
                                            $user_name = $this->users->hasUser($value);
                                            $data['u_ug_id'] = $user_name['u_ug_id'];
                                            $data['pm_level'] = $_REQUEST['move_u_level'];
                                            $data['u_name'] = $user_name['u_name'];
                                            $data['pg_name'] = $pginfo[0]['pg_name'];
                                            $user->set($data);
                                            $user->batchUser();
                                    }

                                    echo $this->tools->call(L('操作成功'), 0);
                            }
                    }
            }
    }

	public function getalluser() {
		$user = new users($_REQUEST);
		//$total = $user->getTotal ( true );
		$item_e = $this->enterprise->getByid();
		$this->smarty->assign('title', '企业群组');
		$this->page->setTotal($user->getTotal());
		$numinfo = $this->page->getNumInfo();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		$list = $user->getalluser();
		foreach ($list as $val) {
			$pg_list[$val['u_number']] = $this->groups->getuserPgname($val['u_number'],$val['u_default_pg']);
		}
		$this->smarty->assign('data', $_REQUEST);
		$this->smarty->assign('list', $list);

		$this->smarty->assign('pg_list', $pg_list);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('total', count($list));
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->smarty->assign('item_e', $item_e);
		if ($_REQUEST['does'] == "groups") {
			if (count($list) != 0) {
				$result = $this->htmlrender('modules/enterprise/groups_item.tpl');
			} else {
				$result = "";
			}
		} else if ($_REQUEST['does'] == "usergroup") {
			if (count($list) != 0) {
				$result = $this->htmlrender('modules/enterprise/users_item.append.tpl');
			} else {
				$result = "";
			}
		}
		return $result;
	}
	public function getcustalluser() {
		$user = new users($_REQUEST);
		$total = $user->getTotal();
		$item_e = $this->enterprise->getByid();
		$this->smarty->assign('title', '企业群组');
		$this->page->setTotal($user->getTotal());
		$numinfo = $this->page->getNumInfo();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		$list = $user->getalluser();
		foreach ($list as $val) {
			$pg_list[$val['u_number']] = $this->groups->getuserPgname($val['u_number'],$val['u_default_pg']);
		}
		$this->smarty->assign('data', $_REQUEST);
		$this->smarty->assign('list', $list);

		$this->smarty->assign('pg_list', $pg_list);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('total', $total);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->smarty->assign('item_e', $item_e);
		if ($_REQUEST['does'] == "groups") {
			if (count($list) != 0) {
				$result = $this->htmlrender('modules/enterprise/cust_groups_item.tpl');
			} else {
				$result = "";
			}
		} else if ($_REQUEST['does'] == "usergroup") {
			if (count($list) != 0) {
				$result = $this->htmlrender('modules/enterprise/users_item.append.tpl');
			} else {
				$result = "";
			}
		}
		return $result;
	}

	function getalluser_v2() {
		$user = new users($_REQUEST);
		$total = $user->getpguserTotal();
		$item_e = $this->enterprise->getByid();
//$this->smarty->assign('title', '企业群组');
		$this->page->setTotal($this->users->getpguserTotal());
		$numinfo = $this->page->getNumInfo();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		$list = $user->getalluser_v2();

		$this->smarty->assign('data', $_REQUEST);
		$this->smarty->assign('list', $list);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('total', $total);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->smarty->assign('item_e', $item_e);
		if (count($list) != 0) {
			$result = $this->htmlrender('modules/enterprise/groups_item_pguser.tpl');
		} else {
			$result = "";
		}
		return $result;
	}
	function getcustalluser_v2() {
		$item_e = $this->enterprise->getByid();
//$this->smarty->assign('title', '企业群组');
		$this->page->setTotal($this->users->getpguserTotal());
		$numinfo = $this->page->getNumInfo();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		//$list = $this->users->getpttmb ( $this->page->getLimit () );
		$info = $this->custpgmember->getbyid();

		$u_number_arr = array();
		$u_number_arr = explode(";", $info['c_pg_mem_list']);

		foreach ($u_number_arr as $key => $value) {
			if ($this->users->getinfo($value) !== false) {
				$list[] = $this->users->getinfo($value);
			}
		}
		$total = count($list);

		$this->smarty->assign('data', $_REQUEST);
		$this->smarty->assign('list', $list);
		$this->smarty->assign('info', $info);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('total', $total);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->smarty->assign('item_e', $item_e);
		if (count($list) != 0) {
			$result = $this->htmlrender('modules/enterprise/cust_groups_item_pguser.tpl');
		} else {
			$result = "";
		}
		return $result;
	}

	/**
	 * 检查是否有紧急对讲组
	 */
	public function get0groups($pgnum) {
		$data = $this->groups->get0groups();

		if ($data == false) {
			$msg["status"] = 1;
			return $msg;
		} else if ($pgnum == $data['pg_number']) {
			$msg["status"] = 1;
			return $msg;
		} else {
			$msg["status"] = -1;
			$msg["msg"] = L("企业群组已存在紧急对讲组");
			return $msg;
		}
	}

	public function getdefaultpg() {
		$list = $this->users->getpttmb($this->page->getLimit());
		$arr = array();
		foreach ($list as $key => $value) {
			if ($value['u_default_pg'] == $value['pm_pgnumber']) {
				$arr[$key] = $value['u_default_pg'];
			}
		}

		if (count($arr) > 0) {
			$msg["status"] = -1;
			$msg["msg"] = L("有用户将该群组设置成默认组，无法创建紧急对讲组");
		} else {
			$msg["status"] = 1;
		}
		return $msg;
	}

//获得是否为紧急对讲组
	public function getimpgroups() {

		$this->groups->get();
		$info = $this->groups->getbyid();

		if ($info["pg_level"] == "0") {
			$msg["status"] = -1;
			$msg["msg"] = L("该群组为紧急对讲组，用户无法设置为默认组");
		} else {
			$msg["status"] = 1;
		}
		echo json_encode($msg);
	}

	/**
	 * 自建组
	 */
	public function cust_pggroup() {
		$data = $this->enterprise->getByid();
		$data['do'] = 'add';
		$this->page->setTotal($this->groups->getTotal());
		$num = $this->users->getTotal();
		$numinfo = $this->page->total();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		$list = $this->groups->getList_cust();
		$ug_list = $this->usergroup->selectlist();
		$total = count($list);
		$mininav = array(
			array(
				"url" => "?m=enterprise&a=index",
				"name" => "企业管理",
				"next" => ">>",
			),
			array(
				"url" => "?m=enterprise&a=admins&e_id=" . $_REQUEST["e_id"],
				"name" => $data["e_name"] . " - " . L("企业群组"),
				"next" => "",
			),
		);
		$this->smarty->assign('mininav', $mininav);
		$this->smarty->assign('data', $_REQUEST);
		$this->smarty->assign('list', $list);
		$this->smarty->assign('ug_list', $ug_list);
		$this->smarty->assign('data', $data);
		$this->smarty->assign('ep', $data);
		//$this->smarty->assign ( 'num' , $num );
		$this->smarty->assign('total', $total);
		//$this->smarty->assign ( 'prev' , $prev );
		//$this->smarty->assign ( 'next' , $next );
		$this->render('modules/enterprise/cust_groups.tpl', L('自建组'));
	}

	public function cust_tree() {
		$groups = new groups($_REQUEST);
		$data = $this->enterprise->getByid();
		$data['do'] = 'add';
		$this->page->setTotal($this->groups->getTotal());
		$num = $this->users->getTotal();
		$list = $groups->getList_cust();
		//$ug_list = $this->usergroup->selectlist ();

		$this->smarty->assign('data', $_REQUEST);
		$this->smarty->assign('list', $list);
		// $this->smarty->assign ( 'ug_list' , $ug_list );
		$this->smarty->assign('data', $data);
		$this->smarty->assign('ep', $data);
		$this->smarty->assign('num', $num);
		$result = $this->htmlrender('modules/enterprise/cust_tree.tpl');
		return $result;
	}

}
