<?php

/**
 * 企业管理员控制器
 * @category OMP
 * @package OMP_Enterprise_contorl
 * @require {@see contorl} {@see page} {@see enterprise} {@see admins}
 */
class EnterpriseAdminContorl extends contorl
{

    public function __construct ()
    {
        parent::__construct ();
        $this->page = new page ( $_REQUEST );
    }

    /**
     * 企业管理员首页
     */
    function admins ()
    {
        $enterprise = new enterprise ( $_REQUEST );
        $data = $enterprise->getByid ();
        $data = array_merge ( $data , $_REQUEST );
        $mininav = array (
            array (
                "url" => "?m=enterprise&a=index" ,
                "name" => "企业管理" ,
                "next" => ">>"
            ) ,
            array (
                "url" => "?m=enterprise&a=view&e_id=" . $_REQUEST["e_id"] ,
                "name" => $data["e_name"] . " - " . L ( "企业管理员" ) ,
                "next" => ""
            )
        );
        $this->smarty->assign ( 'mininav' , $mininav );
        $this->smarty->assign ( 'data' , $data );
        $this->smarty->assign ( 'ep' , $data );
        $this->render ( 'modules/enterprise/admins.tpl' , L('企业管理员'));
    }

    /**
     * 新增企业管理员
     */
    function admins_add ()
    {

        $enterprise = new enterprise ( $_REQUEST );
        $data = $enterprise->getByid ();
        $data["do"] = "add";
        $mininav = array (
            array (
                "url" => "?m=enterprise&a=index" ,
                "name" => "企业管理" ,
                "next" => ">>"
            ) ,
            array (
                "url" => "?m=enterprise&a=admins&e_id=" . $_REQUEST["e_id"] ,
                "name" => $data["e_name"] . " - " . L ( "企业管理员" ) ,
                "next" => ">>"
            ) ,
            array (
                "url" => "?m=enterprise&a=admins_add&e_id=" . $_REQUEST["e_id"] ,
                "name" => "新增企业管理员" ,
                "next" => ""
            )
        );
        $this->smarty->assign ( 'mininav' , $mininav );
        $this->smarty->assign ( 'data' , $data );
        $this->render ( 'modules/enterprise/admins_add.tpl' , L('新增企业管理员'));
    }

    /**
     * 编辑企业管理员
     */
    function admins_edit ()
    {
        $enterprise = new enterprise ( $_REQUEST );
        $admins = new admins ( $_REQUEST );
        $data = $admins->getbyid ();
        $enterprise_item = $enterprise->getByid ();
        $data["e_id"] = $enterprise_item["e_id"];
        $data["e_name"] = $enterprise_item["e_name"];
        $data["do"] = "edit";
        //$data["em_id"] = $_REQUEST["em_id"];
        $mininav = array (
            array (
                "url" => "?m=enterprise&a=index" ,
                "name" => "企业管理" ,
                "next" => ">>"
            ) ,
//            array (
//                "url" => "?m=enterprise&a=admins&e_id=" . $_REQUEST["e_id"] ,
//                "name" => $data["e_name"] . " - " . L ( "企业管理员" ) ,
//                "next" => ">>"
//            ) ,
            array (
                "url" => "?m=enterprise&a=admins_edit&e_id=" . $_REQUEST["e_id"] ,
                "name" => "编辑企业管理员" ,
                "next" => ""
            )
        );
        $this->smarty->assign ( 'mininav' , $mininav );
        $this->smarty->assign ( 'data' , $data );
        $this->smarty->assign ( 'em_id' , $_REQUEST['e_id'] );
        $this->render ( 'modules/enterprise/admins_add.tpl' , L('编辑企业管理员'));
    }

    /**
     * 企业管理员列表
     */
    function admins_item ()
    {
        $admins = new admins ( $_REQUEST );
        //$data	=	$admins->getbyid();

        $this->page->setTotal ( $admins->getTotal () );
        $list = $admins->getList ( $this->page->getLimit () );
        $numinfo = $this->page->getNumInfo ();
        $prev = $this->page->getPrev ();
        $next = $this->page->getNext ();
        $this->smarty->assign ( 'list' , $list );
        $this->smarty->assign ( 'numinfo' , $numinfo );
        $this->smarty->assign ( 'prev' , $prev );
        $this->smarty->assign ( 'next' , $next );
        $this->smarty->assign ( 'data' , $_REQUEST );
        $this->htmlrender ( 'modules/enterprise/admins_item.tpl' );
        exit ();
    }

    /**
     * 删除企业管理员
     */
    function admins_del ()
    {
        $enterprise = new enterprise ( $_REQUEST );
        $tools = new tools();
        $admins = new admins ( $_REQUEST );

        $enterprise->changeSync ( true , 1 );
        $list = $tools->get ( "list" );
        $result["count"] = $admins->delList ( $list );
        echo $result["count"];
        exit ();
    }

    function admins_save ()
    {
        $enterprise = new enterprise ( $_REQUEST );
        $tools = new tools();
        $admins = new admins ( $_REQUEST );
      if(!$admins->getbyid()){
          $_REQUEST['do']='save';
      }
        $admins->set($_REQUEST);
        $enterprise->changeSync ( true , 1 );
        $this->smarty->assign ( 'title' , L("编辑管理员"));
        $tools->show ( $admins->save () );
    }

    public function get_em_mob(){
    $admins = new admins(array("em_phone" => $_REQUEST['em_phone']));
        $res = $admins->get_em_mob();
        if ($res == false) {
                echo "1";
        } else if (count($res) >= 1) {
                echo "2";
        }
    }
    public function get_em_mail(){
    $admins = new admins(array("em_mail" => $_REQUEST['em_mail']));
        $res = $admins->get_em_mail();
        if ($res == false) {
                echo "1";
        } else if (count($res) >= 1) {
                echo "2";
        }
    }
}
