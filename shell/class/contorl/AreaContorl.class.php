<?php

/**
 * 区域控制器
 * @package OMP_Area_contorl
 * @require {@see contorl} {@see area} {@see area} {@see page}
 */
class AreaContorl extends contorl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 区域列表
     */
    public function index() {
        $this->permissions($_SESSION['own'], TRUE);
        $this->render('modules/area/index.tpl', L('区域管理'));
    }

    /**
     * 区域编辑
     */
    public function area_edit() {
        $mininav = array(
            array(
                "url" => "?m=area&a=index",
                "name" => "区域管理",
                "next" => ">>"
            ),
            array(
                "url" => "#",
                "name" => "编辑区域"
            )
        );
        $this->smarty->assign('mininav', $mininav);
        $this->permissions($_SESSION['own'], TRUE);
        $area = new area($_REQUEST);
        $smarty = $this->smarty;
        $data = $area->getByid();
        $smarty->assign('data', $data);
        $this->render('modules/area/area_edit.tpl', L('编辑区域'));
    }

    /**
     * 区域添加
     */
    public function area_add() {
        $mininav = array(
            array(
                "url" => "?m=area&a=index",
                "name" => "区域管理",
                "next" => ">>"
            ),
            array(
                "url" => "#",
                "name" => "新增区域"
            )
        );
        $this->smarty->assign('mininav', $mininav);
        $this->permissions($_SESSION['own'], TRUE);
        $smarty = $this->smarty;
        $smarty->assign('data', $_REQUEST);
        $this->render('modules/area/area_add.tpl', L('新增区域'));
    }

    /**
     * 区域列表
     * @return option 区域列表
     */
    public function option() {
        $area = new area($_REQUEST);
        $smarty = $this->smarty;
        $smarty->assign("list", $area->getList());
        $smarty->display('modules/area/area_option.tpl');
    }
    /**
     * 创建者区域列表
     * @return option 区域列表
     */
    public function option_create() {
        $ep=new enterprise($_REQUEST);
        $info=$ep->getByid();
        if($info['e_create_name']!=""){
            $_REQUEST['ag_number']=$info['e_create_name'];
            $ag=new agents($_REQUEST);
            $aginfo=$ag->getByid();
            $_REQUEST['ag_area']=$aginfo['ag_area'];
        }
        $area = new area($_REQUEST);
        $smarty = $this->smarty;
        $e_area = str_replace('"', '', $info['e_area']);
        $smarty->assign("e_area",$e_area);
        //var_dump($area->getList_c());
        $smarty->assign("list", $area->getList_c());
        $smarty->display('modules/area/area_option.tpl');
    }

    /**
     * 区域列表
     */
    public function index_item() {

        $area = new area($_REQUEST);
        $page = new page($_REQUEST);
        $smarty = $this->smarty;

        $page->setTotal($area->getTotal());
        $list = $area->getList($page->getLimit());
        $numinfo = $page->getNumInfo();
        $prev = $page->getPrev();
        $next = $page->getNext();
        $smarty->assign('list', $list);
        $smarty->assign('numinfo', $numinfo);
        $smarty->assign('prev', $prev);
        $smarty->assign('next', $next);
        $smarty->display('modules/area/index_item.tpl');
    }

    /**
     * 删除区域
     */
    public function area_del() {
        $this->permissions($_SESSION['own'], TRUE);
        $area = new area($_REQUEST);
        $msg = $area->delList();
        echo json_encode($msg);
        exit();
    }

    /**
     * 保存区域
     */
    public function area_save() {
        $this->permissions($_SESSION['own'], TRUE);
        $area = new area($_REQUEST);
        $msg = $area->save();
        echo json_encode($msg);
        exit();
    }

}
