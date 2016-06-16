<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AgentsubContorl
 *
 * @author zed
 */
class AgentsubContorl extends contorl {
    //put your code here
    public $as;
    public $page;
    function __construct() {
        parent::__construct();
        $this->as=new agentsub($_REQUEST);
        $this->page=new page($_REQUEST);
    }
    
    /**
     * 代理商多帐户管理首页
     */
    public function index(){
        
        $this->render("modules/agentsub/index.tpl",L("子帐号管理"));
    }
    /**
     * 帐户列表page
     */
    public function index_item(){
        $list = $this->as->getList ( $this->page->getLimit () );
        $total = $this->as->getTotal ();
        $this->page->setTotal ( $total );
        $numinfo = $this->page->getNumInfo ();
        $prev = $this->page->getPrev ();
        $next = $this->page->getNext ();
        $this->smarty->assign ( 'numinfo' , $numinfo );
        $this->smarty->assign ( 'prev' , $prev );
        $this->smarty->assign ( 'next' , $next );

        $this->smarty->assign ( "list" , $list );
       $this->htmlrender("modules/agentsub/index_item.tpl"); 
    }
    /**
     * 增加子帐户page
     */
    public function agentsub_add(){
        if($_REQUEST["do"]=="edit"){
            $info=$this->as->getById();
            $this->smarty->assign("data",$_REQUEST);
            $this->smarty->assign("info",$info);
        }
        $this->render("modules/agentsub/agentsub_add.tpl",L("子帐号管理"));
    }
    /**
     * 增加子帐户
     */
    public function sub_save(){
            $msg=$this->as->save();
            echo json_encode($msg);
    }
    
    /**
     * 删除代理商子帐户
     */
    public function batchdel(){
        $list = $_REQUEST['checkbox'];
        $count = $this->as->delList ( $list );
        echo $count;
        exit ();
    }
    
    function check_name(){
        $res=$this->as->get_can_name();
        if($res==true){
            echo "1";
        }else{
            echo "2";
        }
    }
}
