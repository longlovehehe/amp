<?php

/**
 * 系统控制器，包含 系统首页，修改密码等
 * @package OMP_Common_contorl
 * @require {@see contorl} {@see area} {@see enterprise}
 */
class SystemContorl extends contorl
{

    public function __construct ()
    {
        parent::__construct ();
    }

    /**
     * 登录后的首页
     */
    public function index ()
    {
        $_REQUEST['d_area'] = '#';
        $_REQUEST['aggents_number'] = $_SESSION['ag']['ag_number'];
        $_REQUEST['ag_level'] = $_SESSION['ag']['ag_level'];
        $agents = new agents ( array('ag_number'=>$_SESSION['ag']['ag_number']) );
        $area = new area ( $_REQUEST );
        $list = $agents->getByid ();
        //下级代理
        $agents = new agents (  $_REQUEST );
        $ag_list=$agents->getagList();
        //所属区域
        $ag_area = json_decode ( $list['ag_area'] );
        $area_str = "";
        foreach ( $ag_area as $value )
        {
            $area_str .= $area->getareaname ( $value ) . " ";
        }
         //产品价格
        $pri=new basic(array('id'=>$_SESSION['ag']['ag_number']) );
        $pri_info=$pri->getByid();
        //流量卡数
        $gprs = new gprs ( $_REQUEST );
        $gprnum = $gprs->getGprsTotal ();
        $ep = new enterprise ( array ( 'ag_number' => $_SESSION['ag']['ag_number'],'e_create_name'=>$_SESSION['ag']['ag_number'] ) );
        $info = $ep->getList ();
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
        $ag = new agents ( array ( 'aggents_number' => $_SESSION['ag']['ag_number'] , 'ag_level' => $_SESSION['ag']['ag_level'] ) );
        $ag_info = $ag->getList ();
        foreach ( $ag_info as $key => $value )
        {
            $phone += $value['ag_phone_num'];
            $dispatch += $value['ag_dispatch_num'];
            $gvs += $value['ag_gvs_num'];
        }
       // session_start ();
        $_SESSION['ag']['diff_phone'] = $phone;
        $_SESSION['ag']['diff_dispatch'] = $dispatch;
        $_SESSION['ag']['diff_gvs'] = $gvs;
        //企业数
        $ep_num = count ( $info );
        /**
        $ep = new enterprise ( $_REQUEST );
        $device = new device ( $_REQUEST );
        $this->smarty->assign ( "en" , $ep->getTotal () );
        $this->smarty->assign ( "device" , $device->getMDSTotal () );
         *
         */
        $this->smarty->assign ( "list" , $list );
        $this->smarty->assign ( "phone" , $phone );
        $this->smarty->assign ( "dispatch" , $dispatch );
        $this->smarty->assign ( "gvs" , $gvs );
        $this->smarty->assign ( "ep_num" , $ep_num );
        $this->smarty->assign ( "area_str" , $area_str );
        $this->smarty->assign ( "ag_list_num" , count($ag_list) );
        $this->smarty->assign ( "gprnum" , $gprnum );
        $this->smarty->assign ( "pri_info" , $pri_info );
        if($_SESSION['ident']=="VT"){
            $this->render ( 'modules/system/index_vt.tpl' , L("首页") );
        }else if($_SESSION['ident']=="GQT"){
             $this->render ( 'modules/system/index.tpl' , L("首页") );
        }else{
             $this->render ( 'modules/system/index_vt.tpl' , L("首页") );  
        }
    }

    /**
     * 个人信息查看显示层
     * @deprecated 未使用
     * @category view
     */
    public function person ()
    {
        $user = $_SESSION['om_id'];
        $this->smarty->assign ( "username" , $user );
        $this->render ( 'modules/system/person.tpl' , L("个人信息查看") );
    }

    /**
     * 个人信息查看
     * @deprecated 未使用
     */
    public function person_edit ()
    {
        $this->render ( 'modules/system/person_edit.tpl' , L("个人信息查看") );
    }

    /**
     * 修改密码显示层
     */
    public function resetpassword ()
    {
        $this->render ( 'modules/system/resetpassword.tpl' , L("修改密码") );
    }

    /**
     * 首页公告详细内容
     */
    public function pro_details ()
    {
        $system = new system ( $_REQUEST );
        $data = $system->pro_details ();
        $this->smarty->assign ( "data" , $data );
        $this->render ( 'modules/system/pro_details.tpl' );
    }

    /**
     * 首页公告显示层
     */
    public function announcement ()
    {
        $this->render ( 'modules/system/announcement.tpl' , L("标题") );
    }

    /**
     * 首页公告列表后台接口
     */
    public function index_item ()
    {
        $system = new system ( $_REQUEST );
        $page = new page ( $_REQUEST );
        $list = $system->getList ();
        $page->setTotal ( $system->getAnTotal () );
        $getAnList = $system->getAnList ( $page->getLimit () );
        $this->smarty->assign ( 'getAnList' , $getAnList );
        $numinfo = $page->getNumInfo ();
        $prev = $page->getPrev ();
        $next = $page->getNext ();
        $this->smarty->assign ( "list" , $list );
        $this->smarty->assign ( 'numinfo' , $numinfo );
        $this->smarty->assign ( 'prev' , $prev );
        $this->smarty->assign ( 'next' , $next );
        $this->htmlrender ( 'modules/system/index_item.tpl' );
    }

    /**
     * 修改密码后台接口
     */
    public function changepassword ()
    {
        $system = new system ( $_REQUEST );
        $data = $system->chgPwd ();
        echo json_encode ( $data );
    }

}
