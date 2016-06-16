<?php

/**
 * 设备控制器类
 * @category OMP
 * @package OMP_Device_contorl
 * @require {@see device} {@see enterprise} {@see area} {@see contorl} {@see page}
 */
class DeviceContorl extends contorl {

	/**
	 * 构造器，继承至contorl
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * 刷新设备状态
	 * <pre>
	 * - 1、从用户选择的设备列表中筛选出设备状态为0（处理中），2（处理失败）的设备，将其状态设置为0（处理中）
	 * - 2、循环筛选出的设备ID，向数据中心发送DevSave消息，参数 设备id 设备ip1 设备ip1端口
	 * - 3、发送失败或数据中心返回非200状态码，修改该设备id状态为2（处理失败），并向上层抛出该异常
	 * - 4、上层截获异常，将异常消息输出至浏览器
	 * - 5、上层未截获异常，输出成功至浏览器，并中断当前程序执行
	 * </pre>
	 * @throws Exception
	 */
	function refresh() {
//实例化一个设备对象
		$device = new device($_REQUEST);
//实例化一个工具类
		$tools = new tools();
//获得当前设备所有数据
		$data = $device->get();
		$resultlist = array();
		try
		{
//刷新设备状态，并将处理了的设备id传给resultlist
			$resultlist = $device->refreshList();
//循环已处理设备id
			foreach ($resultlist as $value) {
				$data['d_id'] = $value;
				$device->set($data);
				$deviceitem = $device->getByid();

//发送消息到数据中心
				try
				{
					$tools->send("DevSave", $deviceitem["d_id"] . ' ' . $deviceitem['d_ip1'] . ' ' . $deviceitem['d_port1']);
				} catch (Exception $ex) {
					$device->updateStatus(array(2, $deviceitem['d_id']));
					throw new Exception($ex->getMessage(), 0);
				}
			}
		} catch (Exception $ex) {
			$tools->call($ex->getMessage(), 0, true);
		}
//操作结束，提示信息
		$tools->call(L("刷新成功"), 0, true);
	}

	/**
	 * 获得option格式的mds列表
	 * 根据view参数，获取不同类型的option片断，供前台select下拉框使用
	 * @return String HTML格式的设备列表
	 */
	function mds_option() {
		$device = new device($_REQUEST);
		$list = $device->getMDSListOption();
		if($_REQUEST['e_id'])
		{
			$ep=new enterprise($_REQUEST);
        	$info=$ep->getByid();
		}
		
		if ($_REQUEST['view'] != "") {
			$this->smarty->assign('list', $list);
			$this->htmlrender('modules/device/mds_option_view.tpl');
		} else {
			if($info['e_mds_id'])
			{
				$this->smarty->assign('e_mds_id',$info['e_mds_id']);
			}
			
			$this->smarty->assign('list', $list);
			$this->htmlrender('modules/device/mds_option.tpl');
		}
	}

	/**
	 * 获得option格式的RS列表
	 * 根据view参数，获取不同类型的option片断，供前台select下拉框使用
	 * @return String HTML格式的设备列表
	 */
	function rs_option() {
		$device = new device($_REQUEST);
		$list = $device->getRSListOption();
		if($_REQUEST['e_id'])
		{
			$ep=new enterprise($_REQUEST);
        	$info=$ep->getByid();
		}
		if ($_REQUEST['view'] != "") {
			$this->smarty->assign('list', $list);
			$this->htmlrender('modules/device/rs_option_view.tpl');
		} else {
			if($info['e_vcr_id'])
			{
				$this->smarty->assign('e_vcr_id',$info['e_vcr_id']);
			}
			
			$this->smarty->assign('list', $list);
			$this->htmlrender('modules/device/rs_option.tpl');
		}
	}
	/**
	 * 获得option格式的SS列表
	 * 根据view参数，获取不同类型的option片断，供前台select下拉框使用
	 * @return String HTML格式的设备列表
	 */
	function ss_option() {
		$device = new device($_REQUEST);
		$list = $device->getSSListOption();
		foreach ($list as $key => $value) {
			$list[$key]['d_space_free'] = floor($value['d_space_free']/1024);
			$list[$key]['d_space'] = floor($value['d_space']/1024);
		}
		if($_REQUEST['e_id'])
		{
			$ep=new enterprise($_REQUEST);
        	$info=$ep->getByid();
		}
		if ($_REQUEST['view'] != "") {
			$this->smarty->assign('list', $list);
			$this->htmlrender('modules/device/ss_option_view.tpl');
		} else {
			if($info['e_ss_id'])
			{
				$this->smarty->assign('e_ss_id',$info['e_ss_id']);
			}
			$this->smarty->assign('list', $list);
			$this->htmlrender('modules/device/ss_option.tpl');
		}
	}
	/**
	 * 获得mds列表表格形式
	 * 提供分页形式的设备列表，表格风格
	 */
	function mds_item() {
		$device = new device($_REQUEST);
		$page = new page($_REQUEST);
		$this->page = $page;
		$this->page->setTotal($device->getMDSTotal());
		$list = $device->getMDSList($this->page->getLimit());
		$numinfo = $this->page->getNumInfo();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		$this->smarty->assign('list', $list);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);

		$this->htmlrender('modules/device/mds_item.tpl');
	}

	/**
	 * 删除mds设备
	 * 删除提交过来的设备id的设备，将返回删除总数
	 */
	function mds_del() {
		$device = new device($_REQUEST);
		$result[count] = $device->delMDSList();
		echo $result[count];
	}

	/**
	 * 通过mds id获得包含该mds详细信息的json格式的mds
	 * 由mds_id获得该设备的详细信息，表现形式为json格式
	 * @return json 单个mds详细信息
	 */
	function mds_id() {
		$device = new device($_REQUEST);
		echo json_encode($device->GetJsonByMDSId());
	}

	/**
	 * 保存mds设备
	 * 保存一个设备，如果保存的设备名称或外网地址重复，则抛出异常信息，并中断程序
	 * 如果保存成功，则获取保存成功的设备详细信息，根据设备的类型进行处理
	 * 如果是mds/vcr类型，则发送DevSave消息,参数为 设备id 设备ip1 设备端口1
	 * 发送失败，设置设备状态为2（发布失败），日志记录 设备保存失败，抛出 设备保存失败，请管理员检查日志 异常消息
	 * 如果是vcrs类型，则发送DevVcrs消息，参数为 设备id
	 * 处理完毕，显示json格式的消息结果
	 * @throws Exception
	 */
	function mds_save() {
		$device = new device($_REQUEST);
		try
		{
			$msg = $device->save();
		} catch (Exception $ex) {
			$this->tools->call(L("设备名称/设备外网地址重复"), -1, TRUE);
		}

		$data = $device->get();
		switch ($data["d_type"]) {
			case "mds":
			case "vcr":
				try
				{
					$this->tools->send("DevSave", $data["d_id"] . ' ' . $data['d_ip1'] . ' ' . $data['d_port1']);
				} catch (Exception $ex) {
					$device->updateStatus(array(2, $data['d_id']));

					$device->log(DL("设备保存失败") . "：" . $ex->getMessage(), 2, 2);
					throw new Exception(L('设备保存失败，请管理员检查日志'), 0);
				}
				break;
			case "vcrs":
				$this->tools->send("DevVcrs", $data["d_id"]);
				break;
		}
		echo json_encode($msg);
	}

	/**
	 * 通用设备列表
	 */
	function device_list_item() {
		$enterprise = new enterprise($_REQUEST);
		$result = $enterprise->getDeviceList();

		$this->smarty->assign('list', $result['fetchall']);
		$this->smarty->assign('page', $result['page']);

		if ($_REQUEST['do'] == 'mds') {
			$this->htmlrender('modules/device/mds_list_item.tpl');
		} else {
			$this->htmlrender('modules/device/vcr_list_item.tpl');
		}
	}

	/**
	 * VCR设备
	 */
	function vcr_item() {
		$device = new device($_REQUEST);
		$this->page->setTotal($device->getVCRTotal());
		$list = $device->getVCRList($this->page->getLimit());
		$numinfo = $this->page->getNumInfo();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		$this->smarty->assign('list', $list);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->htmlrender('modules/device/vcr_item.tpl');
	}

	/**
	 * VCR删除
	 */
	function vcr_del() {
		$device = new device($_REQUEST);
		$result[count] = $device->delVCRList($_REQUEST["list"]);
		$device->log(DL("VCR删除"), 2, 0, $_REQUEST);
		echo $result[count];
	}

	/**
	 * VCR保存
	 */
	function vcr_save() {
		$device = new device($_REQUEST);
		$msg = $device->save();
		echo json_encode($msg);
		exit();
	}

	/**
	 * VCR列表
	 */
	function vcrs_item() {
		$device = new device($_REQUEST);
		$this->page->setTotal($device->getVCRSTotal());
		$list = $device->getVCRSList($this->page->getLimit());
		$numinfo = $this->page->getNumInfo();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		$this->smarty->assign('list', $list);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->htmlrender('modules/device/vcrs_item.tpl');
	}

	/**
	 * VCR删除
	 */
	function vcrs_del() {
		$device = new device($_REQUEST);
		$result["count"] = $device->delVCRSList($_REQUEST["list"]);
		$device->log(DL("VCRS 删除了这些设备，数据流如下") . implode(",", $this->data), 2, 0);
		echo $result["count"];
	}

	/**
	 * 设备添加显示层
	 */
	function device_add() {
		$mininav = array(
			array(
				"url" => "?m=device&a=index",
				"name" => "设备管理",
				"next" => ">>",
			),
			array(
				"url" => "#",
				"name" => "新增设备",
			),
		);

		$this->smarty->assign('mininav', $mininav);
		$this->smarty->assign('data', $_REQUEST);
		$this->render('modules/device/device_add.tpl', L('新增设备'));
	}

	/**
	 * 设备编辑显示层
	 */
	function device_edit() {
		$mininav = array(
			array(
				"url" => "?m=device&a=index",
				"name" => "设备管理",
				"next" => ">>",
			),
			array(
				"url" => "#",
				"name" => "编辑设备",
			),
		);

		$this->smarty->assign('mininav', $mininav);
		$device = new device($_REQUEST);
		$data = $device->getByid();
		$this->smarty->assign('data', $data);
		$this->render('modules/device/device_edit.tpl', L('编辑设备'));
	}

	/**
	 * 设备信息获取
	 */
	function get_device() {
		$device = new device($_REQUEST);
		$data = $device->getByid();
		echo json_encode($data);
	}

	/**
	 * 获取区域名称
	 */
	public function get_area_name() {
		$area = new area($_REQUEST);
		$d_area_str = $area->getareaname($_REQUEST["am_id"]);
		echo $d_area_str["am_name"];
	}

	/**
	 * 获取当前设备的区域名称
	 */
	function get_area() {
		$area = new area($_REQUEST);
		$d_area = explode(",", $_REQUEST['d_area']);
		$d_area_str = "";

		foreach ($d_area as $value) {
			$d_area_str1 = $area->getareaname($value);
			$d_area_str2 = $d_area_str1["am_name"];
			$d_area_str .= $d_area_str2 . " ";
		}
		echo $d_area_str;
	}

	/**
	 * 获得区域差集
	 */
	public function get_diff_area() {
		$area = new area($_REQUEST);
		$d_area = explode(",", $_REQUEST['d_area']);
		$array = $area->getList();
		$arr = array();
		foreach ($array as $value) {
			$arr[] = $value["am_id"];
		}
		$d_area_str = array();
		foreach ($d_area as $value) {
			$d_area_str1 = $area->getareaname($value);
			$d_area_str[] = $d_area_str1['am_id'];
		}
		$diff_arr = array_diff($arr, $d_area_str);

		echo json_encode($diff_arr, true);
	}

	/**
	 *
	 */
	public function add_d_area() {
		$device = new device($_REQUEST);
		$result = $device->up_area();
		echo json_encode($result);
	}

	/**
	 * 设备使用详情显示层
	 */
	function device_list() {
		$mininav = array(
			array(
				"url" => "?m=device&a=index",
				"name" => "设备管理",
				"next" => ">>",
			),
			array(
				"url" => "#",
				"name" => "使用详情",
			),
		);

		$this->smarty->assign('mininav', $mininav);
		$this->smarty->assign('data', $_REQUEST);
		$this->render('modules/device/device_list.tpl', L('使用详情'));
	}

	/**
	 * VCR显示层
	 */
	function vcr() {
		$this->render('modules/device/vcr.tpl', L('VCR列表'));
	}

	/**
	 * VCRS显示层
	 */
	function vcrs() {
		$this->render('modules/device/vcrs.tpl', L('VCR-S列表'));
	}

	/**
	 * mds显示层
	 */
	function index() {

		$this->render('modules/device/mds.tpl', L($_SESSION['ident'].'-Server管理'));
	}

	/**
	 * mds显示层
	 */
	function mds() {
		$this->render('modules/device/mds.tpl', L($_SESSION['ident'].'-Server列表'));
	}

	/**
	 * 超级控制台
	 */
	function console() {
		$this->render('viewer/super_console.tpl', L('超级控制台'));
	}

}
