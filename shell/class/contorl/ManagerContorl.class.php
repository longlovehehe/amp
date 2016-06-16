<?php

/**
 * 运营管理员控制器
 * @package OMP_Manager_contorl
 * @require {@see contorl} {@see page} {@see sendmsg} {@see enterprise} {@see admins} {@see manager} {@see area} {@see page} {@see admins}
 */
class ManagerContorl extends contorl {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 角色管理显示层
	 */
	public function index() {
		$this->smarty->assign('title', "角色管理");
		$this->render('modules/manager/index.tpl', L('角色管理'));
	}

	/**
	 * 运营管理员编辑显示层
	 */
	public function om_edit() {
		$mininav = array(
			array(
				"url" => "?m=manager&a=index",
				"name" => "角色管理",
				"next" => ">>",
			),
			array(
				"url" => "#",
				"name" => "编辑运营管理员",
			),
		);
		$this->smarty->assign('mininav', $mininav);
		$manager = new manager($_REQUEST);
		$list = $manager->getById();
		$this->smarty->assign('list', $list);
		$this->render('modules/manager/om_add.tpl', L('编辑运营管理员'));
	}

	/**
	 * 运营管理员新增显示层
	 */
	public function om_add() {
		$mininav = array(
			array(
				"url" => "?m=manager&a=index",
				"name" => "角色管理",
				"next" => ">>",
			),
			array(
				"url" => "#",
				"name" => "新增运营管理员",
			),
		);
		$this->smarty->assign('mininav', $mininav);
		$this->render('modules/manager/om_add.tpl', L('新增运营管理员'));
	}

	/**
	 * 运营管理员列表后台接口
	 */
	public function index_item() {
		$manager = new manager($_REQUEST);
		$page = new page($_REQUEST);
		$page->setTotal($manager->getTotal());
		$list = $manager->getList($page->getLimit());
		$numinfo = $page->getNumInfo();
		$prev = $page->getPrev();
		$next = $page->getNext();
		$this->smarty->assign('list', $list);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->htmlrender('modules/manager/index_item.tpl');
		exit();
	}

	/**
	 * 运营管理员保存后台接口
	 */
	public function om_save() {
		$manager = new manager($_REQUEST);
		$msg = $manager->save();
		echo json_encode($msg);
		exit();
	}

	/**
	 * 运营管理员删除
	 * @return int 删除数目
	 */
	public function om_del() {
		$manager = new manager($_REQUEST);
		$list = explode(',', trim($this->tools->get("list"), ','));
		$result["count"] = $manager->del($list);
		echo $result["count"];
		if ($result["count"] == 1) {
			$log = DL('删除运营管理员：【%s】 成功');
			$log = sprintf($log
				, $this->tools->get("list")
			);
			$manager->log($log, 3, 1);
		}
		exit();
	}

	/**
	 * 运营管理员密码重置后台接口
	 */
	public function om_reset() {
		$manager = new manager($_REQUEST);
		$msg = $manager->reset();
		echo json_encode($msg);
		exit();
	}

}
