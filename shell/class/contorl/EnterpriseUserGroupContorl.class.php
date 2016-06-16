<?php

/**
 * 企业部门控制器
 * @category OMP
 * @package OMP_Enterprise_contorl
 * @require {@see contorl} {@see enterprise} {@see usergroup}
 */
class EnterpriseUserGroupContorl extends contorl {

	public $enterprise;
	public $usergroup;
	public $users;
	public $groups;

	/**
	 * 部门数据导入
	 */
	public function importShellUserGroup() {
		$step = is_string($_REQUEST['step']) ? $_REQUEST['step'] : '';
		if ($step === 'if') {
			$msg = $this->importUserGroupFile();
			print "<script>parent.ug_if_callback(" . $msg . ")</script>";
			exit;
		}
		if ($step === 'ic') {
			try
			{
				$f = $this->importUserGroupCheck();
				$json['status'] = 0;
				$json['msg'] = '没有发现错误，即将开始导入';
				$json['data'] = $f;
				$msg = json_encode($json);
			} catch (Exception $ex) {
				$json['status'] = -1;
				$json['msg'] = $ex->getMessage();
				$msg = json_encode($json);
			}
			print "<script>parent.ug_ic_callback(" . $msg . ")</script>";
			exit;
		}
		if ($step === 'i') {
			try
			{
				$this->importUsergroup();
				$json['status'] = 0;
				$json['msg'] = '没有发现错误，导入完成';
				$msg = json_encode($json);
			} catch (Exception $ex) {
				$json['status'] = -1;
				$json['msg'] = $ex->getMessage();
				$msg = json_encode($json);
			}
			print "<script>parent.ug_i_callback(" . $msg . ")</script>";
			exit;
		}
	}

	// 数据导入
	private function importUsergroup() {
		$e_id = filter_input(INPUT_GET, 'e_id');
		$f = filter_input(INPUT_GET, 'f');

		$data['e_id'] = $e_id;
		$ug = new usergroup($data);
		//清空企业所有部门
		$ug->clearAllUserGroup();
		$file = $f . '.xls';
		$config = Cof::config();
		$filePath = $config['system']['webroot'] . DIRECTORY_SEPARATOR . "runtime" . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . $file;
		$objReader = PHPExcel_IOFactory::createReader('Excel5');

		$objPHPExcel = $objReader->load($filePath);
		$objWorksheet = $objPHPExcel->getSheet(0);

		$highestColumn = $objWorksheet->getHighestColumn();
		$highestRow = $objWorksheet->getHighestRow(); //取得总行数
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); //总列数
		// 实际数据读取，数据导入
		// 构造一个记忆体
		$fater = array();
		$fater['x'] = 2;
		$fater['y'] = 0;
		$fater['data'] = array();
		$fater['data']['e_id'] = filter_input(INPUT_GET, 'e_id');
		$fater['data']['ug_id'] = 0;
		$fater['data']['ug_weight'] = 0;
		$fater['data']['ug_path'] = '';

		for ($row = 2; $row <= $highestRow; $row++) {

			for ($col = 0; $col < $highestColumnIndex; $col++) {
				$cur = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
				$x = $col;
				$y = $row;
				// 一级部门特殊处理
				if ($x == 0 && $cur != '') {
					$fater['data']['ug_name'] = $cur;
					$fater['data']['ug_parent_id'] = 0;
					$fater['data']['ug_weight'] = 0;
					$fater['data']['ug_path'] = '';

					$ug->set($fater['data']);
					$ug->create();
				}

				// 子部门处理
				if ($x != 0 && $cur != '') {
					$x = $x - 1;
					for ($i = $y; $i > 0; $i--) {
						$tmp = $objWorksheet->getCellByColumnAndRow($x, $i)->getValue();
						if ($tmp != '') {
							$tmpdata = array();
							$tmpdata['ug_name'] = $tmp;
							$tmpdata['e_id'] = $e_id;
							$ug->set($tmpdata);
							$tp = $ug->getByName();

							$fater['data']['ug_name'] = $cur;
							$fater['data']['ug_parent_id'] = $tp['ug_id'];
							$fater['data']['ug_weight'] = 0;
							$fater['data']['ug_path'] = $tp['ug_path'];

							$ug->set($fater['data']);
							$ug->create();
							break;
						}
					}
				}
			}
		}
	}

	private function importUserGroupCheck() {
		$f = filter_input(INPUT_GET, 'f');
		$file = $f . '.xls';
		$config = Cof::config();
		$filePath = $config['system']['webroot'] . DIRECTORY_SEPARATOR . "runtime" . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . $file;
		$objReader = PHPExcel_IOFactory::createReader('Excel5');

		$objPHPExcel = $objReader->load($filePath);
		$objWorksheet = $objPHPExcel->getSheet(0);

		$highestColumn = $objWorksheet->getHighestColumn();
		$highestRow = $objWorksheet->getHighestRow(); //取得总行数
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); //总列数

		if ($highestColumnIndex > 6) {
			throw new Exception("总列数超出", -1);
		}

		// 实际数据读取，计算重复以及名称规范
		$ug = array();
		for ($row = 2; $row <= $highestRow; $row++) {
			$strs = array();
			for ($col = 0; $col < $highestColumnIndex; $col++) {
				$strs[$col] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
				if ($strs[$col] != '') {
					// 值是否合法检测
					if (Cof::isChinese($strs[$col]) === 0) {
						throw new Exception("名称不符合规范 [" . $strs[$col] . "]", -1);
					}
					$ug[] = $strs[$col];
				}
			}
		}
		$ug_unique = array_unique($ug);
		if (count($ug_unique) < count($ug)) {
			$ug_diff = array_diff_assoc($ug, $ug_unique);
			throw new Exception("部门名称重复[" . implode('], [', $ug_diff) . ']', -1);
		}
		return $f;
	}

	// 导入文件
	private function importUserGroupFile() {
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

	public function __construct() {
		parent::__construct();
		$this->enterprise = new enterprise($_REQUEST);
		$this->usergroup = new usergroup($_REQUEST);
		$this->users = new users($_REQUEST);
		$this->page = new page($_REQUEST);
	}

	/**
	 * 企业部门首页
	 */
	function usergroup() {
		$num = $this->users->getTotal();
		$this->enterprise_item = $this->enterprise->getByid();
		$data['e_id'] = $this->enterprise_item['e_id'];
		$data['e_name'] = $this->enterprise_item['e_name'];

		$mininav = array(
			array(
				"url" => "?m=enterprise&a=index",
				"name" => "企业管理",
				"next" => ">>",
			),
			array(
				"url" => "?m=enterprise&a=usergroup&e_id=" . $_REQUEST["e_id"],
				"name" => $data["e_name"] . " - " . L("企业部门"),
				"next" => "",
			),
		);
		$this->smarty->assign('mininav', $mininav);
		$this->smarty->assign('data', $data);
		$this->smarty->assign('num', $num);
		$this->smarty->assign('ep', $this->enterprise_item);
		$this->render('modules/enterprise/usergroup.tpl', L('企业部门'), array(), array('tree'));
	}

	/**
	 * 企业部门导出
	 * 导出选中部门的用户的全部成员表
	 * @todo 将header的内容提取到公共com的header里去
	 */
	function usergroup_item_export() {
		//set_time_limit ( 600 );
		$data = array();
		$data['u_ug_id'] = $_REQUEST['u_ug_id'];
		$data['e_id'] = $_REQUEST['e_id'];
		$users = new users($data);
		$list = $users->getList();

		$header = array();
		$header[] = DL("号码");
		$header[] = DL("名称");

		$data_groups = array();
		$pg_groups = array();
		$pg_array = array();
		$groups_list1 = array();
		$data_groups['e_id'] = $_REQUEST['e_id'];
		foreach ($list as $key => $value) {
			$ptdata = array();
			$ptdata['e_id'] = $_REQUEST['e_id'];
			$ptdata['pm_number'] = $value['u_number'];
			$pttm = new pttmember($ptdata);
			$pttmlist = $pttm->getList();
			foreach ($pttmlist as $k => $val) {
				$pg_groups[] = $val['pm_pgnumber'];
			}
		}
		$pg_array = array_unique($pg_groups);

		$groups = new groups($data);
		$groups_list = $groups->getList();

		foreach ($groups_list as $k2 => $v2) {
			foreach ($pg_array as $k1 => $v1) {
				if ($v1 == $v2['pg_number']) {
					$groups_list1[] = $v2;
				}
			}
		}
		$groups_list1 = array_slice($groups_list1, 0, 254);
		$headerlist = array();
		foreach ($groups_list1 as $key => $value) {
			$headerlist[$value['pg_number']] = array("id" => $key, "name" => $value['pg_name']);
		}

		$excel = new PHPExcel();
		/** 设置表头 */
		$excel->getActiveSheet()->setCellValue('A1', DL('号码'));
		$excel->getActiveSheet()->setCellValue('B1', DL('名称'));
		foreach ($groups_list1 as $key => $value) {
			$col = PHPExcel_Cell::stringFromColumnIndex($key + 2);
			$excel->getActiveSheet()->setCellValue($col . 1, DL("群组") . " " . ($key + 1));
		}

		/** 用户数据填充 */
		$n = 2;
		foreach ($list as $key => $value) {
			$ptdata = array();
			$ptdata['e_id'] = $_REQUEST['e_id'];
			$ptdata['pm_number'] = $value['u_number'];
			$pttm = new pttmember($ptdata);

			$pttmlist = $pttm->getList();
			$excel->getActiveSheet()->setCellValueExplicit('A' . $n, $value['u_number'], PHPExcel_Cell_DataType::TYPE_STRING);
			$excel->getActiveSheet()->setCellValueExplicit('B' . $n, $value['u_name'], PHPExcel_Cell_DataType::TYPE_STRING);
			$i = 0;
			foreach ($pttmlist as $key1 => $value1) {

				$col = PHPExcel_Cell::stringFromColumnIndex(($headerlist[$value1['pm_pgnumber']]['id'] + 2));
				$excel->getActiveSheet()->setCellValue($col . $n, $headerlist[$value1['pm_pgnumber']]['name'],PHPExcel_Cell_DataType::TYPE_STRING);
				$i++;
			}
			$n++;
		}
		$output = new PHPExcel_Writer_Excel5($excel);

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check = 0, pre-check = 0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename = "' . $data['e_id'] . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$output->save('php://output');
	}

	function usergroup_item() {
		$result = $this->usergroup->getlist();
		$this->smarty->assign('list', $result);
		$this->htmlrender('modules/enterprise/tree.tpl');
		exit();
	}

	/**
	 * 企业部门保存
	 */
	function usergroup_save() {
		$this->enterprise->changeSync(true, 8);
		try
		{
			$msg['result'] = $this->usergroup->save();
			$msg['status'] = 0;
		} catch (Exception $ex) {
			if ($ex->getCode() == 23505) {
				$msg['msg'] = L('部门名称已存在');
			} else {
				$msg['msg'] = $ex->getMessage();
			}
			$msg['status'] = -1;
		}
		$this->tools->show($msg);
	}

	/**
	 * 企业部门删除
	 */
	function usergroup_del() {
		$this->enterprise->changeSync(true, 8);
		echo json_encode($this->usergroup->del());
		exit();
	}

}

/**
 * $cur = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
if ($cur != '')
{

// 如果当前行不为空
$fater['x'] = $row;
$fater['y'] = $col;

if ($col == 0)
{
$fater['data']['ug_name'] = $cur;
$fater['data']['ug_parent_id'] = 0;
$fater['data']['ug_weight'] = 0;
$fater['data']['ug_path'] = '';

$ug->set($fater['data']);
$ug->create();
$fater['data'] = $ug->getByName();
$fater['data']['e_id'] = $e_id;
} else
{
$fater['data']['ug_name'] = $cur;

var_dump($col);
if ($fater['y'] == $col)
{
$fater['data']['ug_parent_id'] = $fater['data']['ug_parent_id'];
$fater['data']['ug_path'] = '0';
} else
{
$fater['data']['ug_parent_id'] = $fater['data']['ug_id'];
$fater['data']['ug_path'] = $fater['data']['ug_path'];
}

$fater['data']['ug_weight'] = 0;


$ug->set($fater['data']);
var_dump($fater['data']);
$ug->create();
$fater['data'] = $ug->getByName();
$fater['data']['e_id'] = $e_id;
}
}
 */
