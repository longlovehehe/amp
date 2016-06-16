<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AgentsContorl extends contorl
{

    public $agents;
    public $page;
    public $basic;

    public function __construct ()
    {
        parent::__construct ();
        $this->agents = new agents ( $_REQUEST );
        $this->page = new page ( $_REQUEST );
        $this->basic=new basic($_REQUEST);
    }

    /**
     * 代理商首页
     */
    public function index ()
    {
        $this->render ( 'modules/agents/agents.tpl' , L('代理商管理') );
    }

    /**
     * 添加或编辑代理商页面
     */
    public function agents_save ()
    {
        
        //获得当前代理商编号
        $ag_number = $this->agents->getagNum ();
        $data = "";
        $parent_id= $ag_number[0]['ag_level']==0?substr($_SESSION['ag']['ag_number'],0,3):substr($_SESSION['ag']['ag_number'],3,3);
        $date=  date(ymd);
        if ( $_REQUEST["do"] != 'edit' )
        {
            $mininav = array (
            array (
                "url" => "?m=agents&a=index" ,
                "name" => L("代理商管理") ,
                "next" => ">>"
            ) ,
            array (
                "url" => "#" ,
                "name" => L("新增代理商") ,
            )
        );
            if ( count ( $ag_number ) == 0 )
            {
                $id="001";
                $data=$parent_id.$id.$date;
            }
            else
            {
               
               $id=substr($ag_number[0]['ag_number'],3,3);
               $id=autoInc($id);
               $data = $parent_id.$id.$date;
            }

            $permit=new permit();
            $dd=$permit->get_ag_permit(array ( 'ag_number' => $_SESSION['ag']['ag_number'] ),array ( 'aggents_number' => $_SESSION['ag']['ag_number'] , 'ag_level' => $_SESSION['ag']['ag_level'] ) );
            $agents = new agents ( array ( 'ag_number' => $_SESSION['ag']['ag_number']) );
            $info = $agents->getByid ();
            $this->smarty->assign ( "phone" , $dd['c_phone'] );
            $this->smarty->assign ( "dispatch" , $dd['c_dispatch'] );
            $this->smarty->assign ( "gvs" , $dd['c_gvs'] );


            $this->smarty->assign ( 'mininav' , $mininav );
            $this->smarty->assign ( 'data' , $data );
            $this->smarty->assign ( 'res' , $_REQUEST );
            //$this->smarty->assign ( 'info' , $info );
            $this->smarty->assign ( 'se' , $_SESSION['ag'] );
            if($_SESSION['ident']=="VT"){
                $this->render ( 'modules/agents/agents_save_vt.tpl' , L('新增代理商') );
            }else if($_SESSION['ident']=="GQT"){
                $this->render ( 'modules/agents/agents_save.tpl' , L('新增代理商') );
            }else{
                $this->render ( 'modules/agents/agents_save_vt.tpl' , L('新增代理商') );
            }
            
        }
        else
        {
            $mininav = array (
            array (
                "url" => "?m=agents&a=index" ,
                "name" => L("代理商管理") ,
                "next" => ">>"
            ) ,
            array (
                "url" => "#" ,
                "name" =>L("编辑代理商") ,
            )
        );
            $permit=new permit();
            $dd=$permit->get_ag_permit(array ( 'ag_number' => $_SESSION['ag']['ag_number'] ),array ( 'aggents_number' => $_SESSION['ag']['ag_number'] , 'ag_level' => $_SESSION['ag']['ag_level'] ) );
            $agents = new agents ( array ( 'ag_number' => $_REQUEST['ag_number']) );
            $info = $agents->getByid ();
            $_REQUEST['id']=$_REQUEST['ag_number'];
            $basic=new basic($_REQUEST);
            $res=$basic->getByid();
            $ddd=$permit->get_ag_permit(array ( 'ag_number' => $_REQUEST['ag_number'] ),array ( 'aggents_number' => $_REQUEST['ag_number'] , 'ag_level' => $_REQUEST['ag_level']+1 ) );
            
            $this->smarty->assign ( "result" , $res );
            $this->smarty->assign ( "phone" , $dd['c_phone']+$info['ag_phone_num'] );
            $this->smarty->assign ( "dispatch" ,$dd['c_dispatch']+$info['ag_dispatch_num'] );
            $this->smarty->assign ( "gvs" , $dd['c_gvs']+$info['ag_gvs_num'] );
            $this->smarty->assign ( "a_phone" , $ddd['phone'] );
            $this->smarty->assign ( "a_dispatch" , $ddd['dispatch'] );
            $this->smarty->assign ( "a_gvs" , $ddd['gvs']);  
            $this->smarty->assign ( 'mininav' , $mininav );
            $this->smarty->assign ( 'data' , $data );
            $this->smarty->assign ( 'res' , $_REQUEST );
            $this->smarty->assign ( 'info' , $info );
            $this->smarty->assign ( 'se' , $_SESSION['ag'] );
            if($_SESSION['ident']=="VT"){
                $this->render ( 'modules/agents/agents_save_vt.tpl' , L('编辑代理商') );
            }else if($_SESSION['ident']=="GQT"){
                $this->render ( 'modules/agents/agents_save.tpl' , L('编辑代理商') );
            }else{
                $this->render ( 'modules/agents/agents_save_vt.tpl' , L('编辑代理商') );
            }
            
        }
    }

    /**
     * 代理商列表
     */
    public function agents_item ()
    {
        //var_dump($_SESSION);die;
        $_REQUEST['aggents_number']=$_SESSION['ag']['ag_number'];
        $_REQUEST['ag_level']=$_SESSION['ag']['ag_level'];
        $ag = new agents ( $_REQUEST );
        $total = $ag->getTotal ();
        $this->page->setTotal ( $total );
        $numinfo = $this->page->getNumInfo ();
        $prev = $this->page->getPrev ();
        $next = $this->page->getNext ();
        
        $list = $ag->getList ( $this->page->getLimit () );
        foreach ( $list as $k => $v )
        {
            foreach ( $list as $key => $value )
            {
                if ( strpos ( $value['ag_path'] , $v['ag_number'] ) > 0 )
                {
                    $str_path = trim ( $value['ag_path'] , "|" );
                    $arr_path = explode ( "||" , $str_path );
                    $arr_num = count ( $arr_path );
                    $arr_key = array_search ( $v['ag_number'] , $arr_path );
                    if ( $arr_num - 1 != $arr_key )
                    {
                        $list[$k]['stat'] = 1;
                        break;
                    }
                }
            }

            $ep = new enterprise ( array ( "ag_number" => $v['ag_number'] ) );
            $res = $ep->getList ();
            if ( count ( $res ) > 0 )
            {
                $list[$k]['stat'] = 1;
            }
            $info = $ep->getepList ();
            //获取当前代理商的子代理商和所属企业
            $phone = 0; //企业所分配手机数
            $dispatch = 0;//企业所分配调度台数
            $gvs = 0;//企业所分配GVS数
            foreach ( $info as $key => $value )
            {
                $phone += $value['e_mds_phone'];
                $dispatch += $value['e_mds_dispatch'];
                $gvs += $value['e_mds_gvs'];
            }
            //var_dump($info);
            $ag = new agents ( array ( 'aggents_number' => $val['ag_number'] , 'ag_level' => $val['ag_level'] ) );
            $ag_info = $ag->getagList();
            foreach ( $ag_info as $key => $value )
            {
                $phone += $value['ag_phone_num'];
                $dispatch += $value['ag_dispatch_num'];
                $gvs += $value['ag_gvs_num'];
            }
            $list[$k]['diff_phone']=$phone;
            $list[$k]['diff_dispatch']=$dispatch;
            $list[$k]['diff_gvs']=$gvs;
        }

        $this->smarty->assign ( 'numinfo' , $numinfo );
        $this->smarty->assign ( 'prev' , $prev );
        $this->smarty->assign ( 'next' , $next );

        $this->smarty->assign ( "list" , $list );
        $this->htmlrender ( 'modules/agents/agents_item.tpl' );
    }

    /**
     * 代理商保存
     * @throws Exception
     */
    public function save ()
    {
        //$this->agent_license ();
        $this->check_agents_license();
        try
        {
            
            $msg=$this->agents->save ();
            $_REQUEST['id']=$_REQUEST['ag_number'];
            $basic=new basic($_REQUEST);
            $basic->save_price_ag();      
        }
        catch ( Exception $exc )
        {
            throw new Exception ( $exc->getMessage () );
        }
        $this->tools->call ( $msg['msg'] , 0 , true );
    }

    public function batchdel ()
    {
        $list = $_REQUEST['checkbox'];
        $count = $this->agents->delList ( $list );
        echo $count;
        exit ();
    }

    public function option ()
    {
        $list = $this->agents->getoptionlist ();
        $this->smarty->assign ( "list" , $list );
        $this->htmlrender ( 'modules/agents/agents_option.tpl' );
    }
    /**
     * 代理商许可
     */
    public function agent_license ()
    {
        //获得当前登录代理商的手机,调度台,GVS最大允许数
        $agent = new agents ( $_REQUEST );
        $info = $agent->getByid ();
        var_dump ( $info );
        die;
    }

    /**
     * 检查代理商许可
     */
    public function check_agents_license(){
        //获得当前登录代理商的手机,调度台,GVS最大允许数
        $permit=new permit();
        $dd=$permit->get_ag_permit(array ( 'ag_number' => $_SESSION['ag']['ag_number'] ),array ( 'aggents_number' => $_SESSION['ag']['ag_number'] , 'ag_level' => $_SESSION['ag']['ag_level'] ));
        $ddd=$permit->get_ag_permit(array ( 'ag_number' => $_REQUEST['ag_number'] ),array ( 'aggents_number' => $_REQUEST['ag_number'] , 'ag_level' => $_REQUEST['ag_level']+1 ));
        $phone=$_REQUEST['ag_phone_num'];
        $dispatch=$_REQUEST['ag_dispatch_num'];
        $gvs=$_REQUEST['ag_gvs_num'];
        if($_REQUEST['do']=="edit"){
             if (0 > $dd['c_phone']) {
                        throw new Exception(L($_SESSION['ident']."-Server剩余可用许可不足"), -1);
                } else if ($phone < $ddd['phone']) {
                        throw new Exception(L("手机用户数小于已存在手机用户数,最小应为").":" .$ddd['phone'], -1);
                } else if (0 > $dd['c_dispatch']) {
                        throw new Exception(L($_SESSION['ident']."-Server剩余可用许可不足"), -1);
                } else if ($dispatch <  $ddd['dispatch']) {
                        throw new Exception(L("调度台用户数小于已存在调度台用户数,最小应为").":" . $ddd['dispatch'], -1);
                } else if (0 > $dd['c_gvs']) {
                        throw new Exception(L($_SESSION['ident']."-Server剩余可用许可不足"), -1);
                } else if ($gvs <  $ddd['gvs']) {
                        throw new Exception(L("GVS用户数小于已存在GVS用户数,最小应为").":" . $ddd['gvs'], -1);
                }
        }else{
                if ($phone > $dd['c_phone']) {
                        throw new Exception(L("剩余用户许可不足"), -1);
                } else if ($dispatch > $dd['c_dispatch']) {
                        throw new Exception(L("剩余用户许可不足"), -1);
                } else if ($gvs > $dd['c_gvs']) {
                        throw new Exception(L("剩余用户许可不足"), -1);
                }
        }      
    }
    
    function check_name(){
        $res=$this->agents->get_can_name();
        if($res==true){
            echo "1";
        }else{
            echo "2";
        }
    }
}
