<?php

/**
 * 日志管理控制器
 * @package OMP_Log_contorl
 * @require {@see contorl} {@see area} {@see page}
 */
class LogContorl extends contorl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 日志管理显示层
     */
    public function index() {
       
        $this->render('modules/log/index.tpl', L('代理商日志'));
    }

    /**
     * 日志列表后台接口
     */
    public function index_item() {
        $smarty = $this->smarty;
        $page = new page($_REQUEST);
        $log = new log($_REQUEST);
        $page->setTotal($log->getTotal());
        $list = $log->getList($page->getLimit());
        $numinfo = $page->getNumInfo();
        $prev = $page->getPrev();
        $next = $page->getNext();
        $smarty->assign('list', $list);
        $smarty->assign('numinfo', $numinfo);
        $smarty->assign('prev', $prev);
        $smarty->assign('next', $next);
        $smarty->display('modules/log/index_item.tpl');
    }

}
