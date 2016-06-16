<?php

/**
 * 公告控制器
 * @package OMP_Announcement_contorl
 * @require {@see contorl} {@see area} {@see page} {@see announcement}
 */
class AnnouncementContorl extends contorl
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 公告管理显示器
     * 显示公告列表<br />
     */
    public function index()
    {
        $this->render('modules/announcement/index.tpl', L('公告管理'));
    }

    /**
     * 发布公告显示器<br />
     * 显示发布公告
     */
    public function an_add()
    {
        $mininav = array(
            array(
                "url" => "?m=announcement&a=index",
                "name" => "公告管理",
                "next" => ">>"
            ),
            array(
                "url" => "#",
                "name" => "发布公告"
            )
        );
        $this->smarty->assign('mininav', $mininav);
        $this->render('modules/announcement/an_add.tpl', L('发布公告'));
    }

    /**
     * 公告编辑显示器 <br />
     * 编辑公告信息<br />
     * 通过 {@see announcement} 的 {@see announcement::an_details} 获取公告详细信息，并将数据传递给 an_edit.tpl 模版。显示
     * @param int $an_id 公告ID
     */
    public function an_edit()
    {
        $mininav = array(
            array(
                "url" => "?m=announcement&a=index",
                "name" => "公告管理",
                "next" => ">>"
            ),
            array(
                "url" => "#",
                "name" => "发布公告"
            )
        );
        $this->smarty->assign('mininav', $mininav);
        $announcement = new announcement($_REQUEST);
        $smarty = $this->smarty;
        $data = $announcement->an_details();
        $smarty->assign("data", $data);
        $this->render('modules/announcement/an_edit.tpl', L('编辑公告'));
    }

    /**
     * 显示公告详细信息<br />
     * 通过 {@see announcement} 的 {@see announcement::an_details} 获取公告详细信息，并将数据传递给 an_details.tpl 模版。显示
     * @param int $an_id 公告ID
     */
    public function an_details()
    {
        $announcement = new announcement($_REQUEST);
        $smarty = $this->smarty;
        $data = $announcement->an_details();
        $smarty->assign("data", $data);

        $this->render('modules/announcement/an_details.tpl');
    }

    // console
    /**
     * 输出表格形式的公告列表<br />
     * @return table 公告列表 具有分页
     */
    public function index_item()
    {
        $announcement = new announcement($_REQUEST);
        $smarty = $this->smarty;
        $page = new page($_REQUEST);

        $page->setTotal($announcement->getTotal());
        $list = $announcement->getList($page->getLimit());

        $numinfo = $page->getNumInfo();
        $prev = $page->getPrev();
        $next = $page->getNext();
        $smarty->assign('list', $list);
        $smarty->assign('numinfo', $numinfo);
        $smarty->assign('prev', $prev);
        $smarty->assign('next', $next);
        $smarty->display('modules/announcement/index_item.tpl');
        exit();
    }

    /**
     * 删除公告
     * 通过 {@see announcement} 的 {@see announcement::delList} 删除公告列表。并返回删除结果
     */
    public function an_del()
    {
        $announcement = new announcement($_REQUEST);

        $result = $announcement->delList();
        echo $result;
        exit();
    }

    /**
     * 保存公告<br />
     * 通过 {@see announcement} 的 {@see announcement::an_save} 删除公告列表。并返回删除结果
     */
    public function an_save()
    {
        $announcement = new announcement($_REQUEST);
        $msg = $announcement->an_save();
        echo json_encode($msg);
        exit();
    }

}
