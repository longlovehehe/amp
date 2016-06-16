<?php

/**
 * 产品管理控制器
 * @package OMP_Product_contorl
 * @require {@see contorl} {@see area} {@see page} {@see product}
 */
class ProductContorl extends contorl
{

    public function __construct ()
    {
        parent::__construct ();
    }

    /**
     * 产品功能清单
     * @param type $pid
     * @return type
     */
    public function getEListBypid ( $pid )
    {
        // 获取指定ID的产品功能
        $data = array ();
        $data['id'] = $pid;
        $product = new product ( $data );
        $product_item = $product->getbyid ();

        // 从产品中获取具有的功能名称
        $p_item = $product_item['p_items'];
        $p_item_array = explode ( '|' , $p_item );
        $list = array ();
        foreach ( $p_item_array as $item )
        {
            $item_array = explode ( ',' , $item );
            if ( $item_array[1] === '1' )
            {
                array_push ( $list , $item_array[0] );
            }
        }

        //查询对应功能名称
        $where = "pi_code IN ('" . implode ( "','" , $list ) . "')";
        $functionlist = $product->getFunctionList ( $where );

        //功能列表
        $function_str = L ( "功能列表" ) . "：<br />";
        $function_arr = array ();
        foreach ( $functionlist as $item )
        {
            array_push ( $function_arr , L($item['pi_name']) );
        }
        $function_str .= implode ( '<br />' , $function_arr );
        return $function_str;
    }

    /**
     * 产品功能清单2
     * @param type $pid
     * @return type
     */
    public function getEListBypjson ( $pjson )
    {
        // 获取指定ID的产品功能
        $data = array ();
        $data = json_decode($pjson);
        foreach ($data as $key => $value) {
            if($value=='gn_yyhy'){
                unset($data[$key]);
            }
        }
        $product = new product ( );
        //查询对应功能名称
        $where = "pi_code IN ('" . implode ( "','" , $data ) . "')";
        $functionlist = $product->getFunctionList ( $where );

        //功能列表
        $function_str = L ( "功能列表" ) . "：<br />";
        $function_arr = array ();
        foreach ( $functionlist as $item )
        {
            array_push ( $function_arr , L($item['pi_name']) );
        }
        $function_str .= implode ( '<br />' , $function_arr );
        return $function_str;
    }
    /**
     * 产品功能清单3
     * @param type $pid
     * @return type
     */
    public function getEListByfunc ( $pjson )
    {
        // 获取指定ID的产品功能
        $data = array ();
        $data = json_decode($pjson);
        foreach ($data as $key => $value) {
            if($value=='gn_yyhy'){
                unset($data[$key]);
            }
        }
        $product = new product ( );
        //查询对应功能名称
        $where = "pi_code IN ('" . implode ( "','" , $data ) . "')";
        $functionlist = $product->getFunctionList ( $where );
        //功能列表
        $function_str ="";
        $function_arr = array ();
        foreach ( $functionlist as $item )
        {
            array_push ( $function_arr , L($item['pi_name']) );
        }
        $function_str .= implode ( ',' , $function_arr );
        return $function_str;
    }
    /**
     * 产品列表获取接口
     * @return html_option 产品列表
     */
    public function option ()
    {
        $product = new product ( $_REQUEST );
        $result = $product->getList ();

        foreach ( $result as $key => $value )
        {
            $result[$key]['id'] = &$value['p_id'];
            $result[$key]['name'] = &$value['p_name'];
            $result[$key]['area'] = &$value['p_area'];
        }
        $this->smarty->assign ( "list" , $result );
        $this->htmlrender ( 'viewer/option.tpl' );
    }
    
     /**
     * 产品功能获取接口
     * @return html_option 产品列表
     */
    public function p_option ()
    {
        $product = new product ( $_REQUEST );
        $result = $product->getPList ();
        foreach ( $result as $key => $value )
        {
            $result[$key]['id'] = &$value['pi_id'];
            $result[$key]['name'] = &$value['pi_name'];
            $result[$key]['price'] = &$value['pi_price'];
        }
        $this->smarty->assign ( "list" , $result );
        $this->htmlrender ( 'viewer/price_option.tpl' );
    }
     /**
     * 产品功能获取接口
     * @return html_option 产品列表
     */
    public function ip_option ()
    {
        $product = new product ( $_REQUEST );
        $result = $product->getPList ();
        foreach ( $result as $key => $value )
        {
             if($value['pi_code']!="gn_yyhy"){
                $result[$key]['id'] = &$value['pi_id'];
                $result[$key]['name'] = &$value['pi_name'];
                $result[$key]['code'] = &$value['pi_code'];
            }else{
                unset($result[$key]);
            }
        }
        $this->smarty->assign ( "list" , $result );
        $this->htmlrender ( 'viewer/input.tpl' );
    }
     /**
     * 产品功能获取接口
     * @return html_option 产品列表
     */
    public function ip_option_new ()
    {
        $product = new product ( $_REQUEST );
        $result = $product->getPList ();
        foreach ( $result as $key => $value )
        {
             if($value['pi_code']!="gn_yyhy"){
                $result[$key]['id'] = &$value['pi_id'];
                $result[$key]['name'] = &$value['pi_name'];
                $result[$key]['code'] = &$value['pi_code'];
            }else{
                unset($result[$key]);
            }
        }
        $this->smarty->assign ( "list" , $result );
        $this->htmlrender ( 'viewer/input.tpl' );
    }
    /**
     * 产品基础价格设置
     */
     public function p_basic ()
    {
        $basic=new basic($_REQUEST);
        $smarty = $this->smarty;
        $page = new page ( $_REQUEST );
        //$data = $basic->getList ( $page->getLimit () );
        $data = $basic->getList ();
        $smarty->assign ( "info" , $data[0]);
        $this->render ( 'modules/product/p_basic.tpl' , L('产品管理') );
    }
    /**
     * 增加基础价格页面
     */
    public function add_bprice(){
        $this->render ( 'modules/product/add_p_basic.tpl' , L('产品管理') );
    }
    /**
     * 后台增加价格基础
     */
    public function price_save(){
        $basic=new basic($_REQUEST);
        $msg=$basic->save_price();
        $this->tools->call(L($msg['msg']), 0, true);
    }

    /**
     * 产品功能首页显示层
     */
    public function index ()
    {
        //取消产品功能
        $product = new product ( $_REQUEST );
        $smarty = $this->smarty;
        $page = new page ( $_REQUEST );
        $data = $product->getPro ( $page->getLimit () );
        $smarty->assign ( "list" , $data );
        if($_SESSION['ident']=="VT"){
            $this->render ( 'modules/product/index.tpl' , L('产品管理') );
        }else if($_SESSION['ident']=="GQT"){
            $this->render ( 'modules/product/index.tpl' , L('产品管理') );
        }else{
            $this->render ( 'modules/product/p_function_vt.tpl' , L('产品管理') );
        }
    }

    /**
     * 产品新增显示层
     */
    public function p_add ()
    {
        $mininav = array (
            array (
                "url" => "?m=product&a=index" ,
                "name" => "产品管理" ,
                "next" => ">>"
            ) ,
            array (
                "url" => "#" ,
                "name" => "新增产品"
            )
        );
        $this->smarty->assign ( 'mininav' , $mininav );
        $product = new product ( $_REQUEST );
        $smarty = $this->smarty;
        $result = $product->function_list ();
        foreach ($result['status'] as $key => $value) {
            if($key=='VAS' && $_SESSION['ident']=='GQT'){
                unset($result['status'][$key]);
            }            
        }
        //生成ID
        $pid = time() . sprintf("%04d", rand(0, 9999));
        $smarty->assign ( "result" , $result );
        $smarty->assign("p_id",$pid);
        $this->render ( 'modules/product/p_add.tpl' , L('新增产品') );
    }

    /**
     * 产品编辑显示层
     */
    public function p_edit ()
    {
        $mininav = array (
            array (
                "url" => "?m=product&a=index" ,
                "name" => "产品管理" ,
                "next" => ">>"
            ) ,
            array (
                "url" => "#" ,
                "name" => "编辑产品"
            )
        );
        $this->smarty->assign ( 'mininav' , $mininav );
        $product = new product ( $_REQUEST );
        $smarty = $this->smarty;
        $data = $product->p_details ();
        foreach ($data['status'] as $key => $value) {
            if($key=='VAS' && $_SESSION['ident']=='GQT'){
                unset($data['status'][$key]);
            }            
        }
        $smarty->assign ( "product_info" , $data[0] );
        $smarty->assign ( "data" , $data );
        $this->render ( 'modules/product/p_edit.tpl' , L('编辑产品') );
    }

    /**
     * 产品功能库显示层
     */
    public function p_function ()
    {

        $product = new product ( $_REQUEST );
        $smarty = $this->smarty;
        $page = new page ( $_REQUEST );
        $data = $product->getPro ( $page->getLimit () );
        foreach ($data as $key => $value) {
             if($value['pi_code']=='gn_yyhy'){
                unset($data[$key]);
            }
        }
        $smarty->assign ( "list" , $data );
        if($_SESSION['ident']=="VT"){
             $this->render ( 'modules/product/p_function_vt.tpl' , L('产品功能库') );
        }else if($_SESSION['ident']=="GQT"){
             $this->render ( 'modules/product/p_function.tpl' , L('产品功能库') );
        }else{
             $this->render ( 'modules/product/p_function_vt.tpl' , L('产品功能库') );
        }
       
    }

    /**
     * 产品列表后台接口
     */
    public function index_item ()
    {
        $product = new product ( $_REQUEST );
        $smarty = $this->smarty;
        $page = new page ( $_REQUEST );
        $page->setTotal ( $product->getTotal () );
        $list = $product->getList ( $page->getLimit () );
        $numinfo = $page->getNumInfo ();
        $prev = $page->getPrev ();
        $next = $page->getNext ();
        $area = new area(); //获取所有区域
        $get_area = $area->getAllList (); //得到所有区域
        if ( count ( $get_area ) == 0 )
        {
            $all_area = array ();
        }
        else
        {
            foreach ( $get_area as $val )
            {
                $all_area[] = $val['am_id'];
            }
        }
        for ( $i = 0; $i < count ( $list ); $i ++ )
        {
            if ( $list[$i]['p_area'] == "[\"#\"]" )
            {
                $p_area = $all_area;
            }
            else
            {
                $p_area = json_decode ( $list[$i]['p_area'] );
            }
            $area = $_SESSION['own']['om_area'];
            //var_dump($area);
            if ( $area == "[\"#\"]" )
            {
                $area = $all_area;
            }
            else
            {
                $area = json_decode ( $area );
            }
            $res = $this->arr_get_diff ( $area , $p_area );
            $list[$i]['res'] = $res;
            $is_used = $product->getused ( $list[$i]['p_id'] );
            if ( $is_used !== false )
            {
                $list[$i]['is_used'] = 1;
            }
            else
            {
                $list[$i]['is_used'] = 0;
            }
        }
        $smarty->assign ( 'list' , $list );
        $smarty->assign ( 'numinfo' , $numinfo );
        $smarty->assign ( 'prev' , $prev );
        $smarty->assign ( 'next' , $next );
        $smarty->display ( 'modules/product/index_item.tpl' );
        exit ();
    }

    /**
     * 产品保存后台接口
     */
    public function p_save ()
    {
        $product = new product ( $_REQUEST );
        $msg = $product->p_save ();
        echo json_encode ( $msg );
        exit ();
    }
    /**
     * 功能价格保存
     */
    public function saveprice(){
        $product = new product ( $_REQUEST );
        $msg = $product->pice_save();
        echo json_encode ( $msg );
        exit ();
    }

    /**
     * 产品功能添加后台接口
     */
    public function p_addData ()
    {
        $product = new product ( $_REQUEST );
        $msg = $product->p_addData ();
        echo json_encode ( $msg );
        exit ();
    }

    /**
     * 产品删除？
     */
    public function pro_del ()
    {
        $product = new product ( $_REQUEST );
        $msg = $product->pro_del ();
        echo json_encode ( $msg );
        exit ();
    }

    public function del_all ()
    {
        $product = new product ( $_REQUEST );
        $msg = $product->delAll ();
        echo json_encode ( $msg );
        exit ();
    }

    public function p_del ()
    {
        $product = new product ( $_REQUEST );
        $msg = $product->p_del ();
        echo json_encode ( $msg );
        exit ();
    }

    public function arr_get_diff ( $arr1 , $arr2 )
    {
        if ( count ( $arr1 ) >= count ( $arr2 ) )
        {
            $arr = array_diff ( $arr1 , $arr2 );
            $resarr = array_intersect ( $arr , $arr2 );
            if ( $resarr == null || $arr == null )
            {
                $res = 1; //ok
            }
            else
            {
                $res = 2; //有没有包含的区域
            }
        }
        else
        {
            $res = 2;
        }
        return $res;
    }
    
     /**
     * 设置功能单位
     */
    public function p_units(){
        $basic=new basic($_REQUEST);
        $msg=$basic->set_units();
        echo  json_encode($msg);

    }
    
    public function get_p_name(){
        $product=new product();
        if(!is_array($_REQUEST['u_p_function_new'])&&$_REQUEST['u_p_function_new']!="noselected"){
            $str_arr=  explode(",", $_REQUEST['u_p_function_new']);
            $_REQUEST['u_p_function_new']=$str_arr;
        }
        echo $product->get_p_name($_REQUEST['u_p_function_new']);
    }
    
    public function get_product_name(){
        $product=new product();
        echo $product->get_product_name($_REQUEST['u_product_id_new']);
    }


}
