<?php

/**
 * CMS控制器
 * @package OMP_CMS_contorl
 */
class CmsContorl extends contorl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 版本控制首页
     */
    public function index() {
        $cms = new cms($_REQUEST);
        $page = new page($_REQUEST);
        $android_info = $cms->getandroidList($page->getLimit());
        $ios_info = $cms->getiosList($page->getLimit());
        $this->smarty->assign('android_info', $android_info);
        $this->smarty->assign('ios_info', $ios_info);
        $this->smarty->assign('title', "版本管理");
        $this->render('modules/cms/index.tpl', L('版本管理'));
    }

    public function upload_soft() {
        $cms = new cms($_REQUEST);
        $ptype = $_REQUEST['ptype'];
        $pdir = $_REQUEST['dir_name'];

        if ($cms->getfetchinfo($ptype, $pdir) && $_REQUEST['flag'] == 'save') {
            $this->smarty->assign('href', '?m=cms&a=index');
            $this->render('viewer/href.tpl', L('CMS管理'));
        } else {
            $res = $cms->upload_soft($_FILES);
            $this->smarty->assign('href', '?m=cms&a=index');
            $this->render('viewer/href.tpl', L('CMS管理'));
        }
    }

    public function getlist() {
        $cms = new cms($_REQUEST);
        $pid = $cms->getById();
        echo $pid;
    }

    public function getinfo() {
        $cms = new cms($_REQUEST);
        $res = $cms->getinfo();
        echo json_encode($res);
    }

    public function del_dir() {
        $cms = new cms($_REQUEST);
        $res = $cms->del_dir();
        echo $res;
    }

    public function empty_dir() {
        $cms = new cms($_REQUEST);
        $res = $cms->empty_dir();
        echo $res;
    }

    public function checkname() {
        $p_dir = $_REQUEST['p_dir'];
        $p_type = $_REQUEST['p_type'];
        $cms = new cms($_REQUEST);
//           var_dump($cms->getfetchinfo($p_type, $p_dir));
        if ($cms->getfetchinfo($p_type, $p_dir) != false) {
            echo "off"; //获取到
        } else {
            echo "on"; //未获取到
        }
    }

    public function checkfs() {

    }

}
