<?php

/**
 * GPRS控制器类
 * @category OMP
 * @package OMP_Device_contorl
 * @require {@see device} {@see enterprise} {@see area} {@see contorl} {@see page}
 */
class GprsContorl extends contorl
{
    /**
     * 流量卡模板下载
     */
    public function gprs_export ()
    {
        $data = array ();
        //$data['e_id'] = filter_input ( INPUT_GET , 'e_id' );
        //var_dump($ug_list);

        $excel = new PHPExcel();
        /** 设置表头 */
        $excel->getActiveSheet ()->setCellValue ( 'A1' , '号码' );
        $excel->getActiveSheet ()->setCellValue ( 'B1' , 'ICCID' );
        $excel->getActiveSheet ()->setCellValue ( 'C1' , '归属地' );
        $excel->getActiveSheet ()->setCellValue ( 'D1' , '套餐' );
        $excel->getActiveSheet ()->setCellValue ( 'E1' , '开卡日' );

        $excel->getActiveSheet ()->setCellValueExplicit ( "A" . 2 , 14542004362 , PHPExcel_Cell_DataType::TYPE_STRING );
        $excel->getActiveSheet ()->setCellValueExplicit ( "B" . 2 , "8986011472760006570" , PHPExcel_Cell_DataType::TYPE_STRING );
        $excel->getActiveSheet ()->setCellValueExplicit ( "C" . 2 , "省联通" , PHPExcel_Cell_DataType::TYPE_STRING );
        $excel->getActiveSheet ()->setCellValueExplicit ( "D" . 2 , "1.2G" , PHPExcel_Cell_DataType::TYPE_STRING );
        $excel->getActiveSheet ()->setCellValueExplicit ( "E" . 2 , 20150115 , PHPExcel_Cell_DataType::TYPE_STRING );
        $excel->getActiveSheet ()->setCellValueExplicit ( "A" . 3 , 14542004357 , PHPExcel_Cell_DataType::TYPE_STRING );
        $excel->getActiveSheet ()->setCellValueExplicit ( "B" . 3 , "8986011472760006571" , PHPExcel_Cell_DataType::TYPE_STRING );
        $excel->getActiveSheet ()->setCellValueExplicit ( "C" . 3 , "省联通" , PHPExcel_Cell_DataType::TYPE_STRING );
        $excel->getActiveSheet ()->setCellValueExplicit ( "D" . 3 , "3.6G" , PHPExcel_Cell_DataType::TYPE_STRING );
        $excel->getActiveSheet ()->setCellValueExplicit ( "E" . 3 , 20150115 , PHPExcel_Cell_DataType::TYPE_STRING );

        /* 导出 */
        coms::head ( 'excel' , $excel );

}

    /**
     * 流量卡导入
     */
    public function importShellICCID ()
    {
        $step = is_string ( $_REQUEST['step'] ) ? $_REQUEST['step'] : '';
        if ( $step === 'if' )
        {
            $msg = $this->importPTFile ();
            print "<script>parent.pt_if_callback(" . $msg . ")</script>";
            exit;
        }
        if ( $step === 'ic' )
        {
            try
            {
                $f = $this->importICCIDCheck ();
                if ( count ( $this->error ) > 0 )
                {
                    $json['status'] = -1;
                    $json['msg'] = '存在错误无法导入<br />';
                }
                else
                {
                    $json['status'] = 0;
                    $json['msg'] = '无严重错误<br />';
                }
                $json['msg'].='<div class="show">';
                $json['msg'] .= implode ( '<br />' , $this->error );
                $json['msg'] .= "<hr />";
                $json['msg'] .= implode ( '<br />' , $this->warn );
                $json['msg'].='</div>';

                $json['data'] = $f;
                $msg = json_encode ( $json );
            }
            catch ( Exception $ex )
            {
                $json['status'] = -1;
                $json['msg'] = $ex->getMessage ();
                $msg = json_encode ( $json );
            }
            print "<script>parent.pt_ic_callback(" . $msg . ")</script>";
            exit;
        }
        if ( $step === 'i' )
        {
            try
            {
                $this->importPT ();

                if ( count ( $this->error ) > 0 )
                {
                    $json['status'] = -1;
                    $json['msg'] = '存在错误';
                    $json['msg'].='<div class="show">';
                    $json['msg'] .= implode ( '<br />' , $this->error );
                    $json['msg'].='</div>';
                }
                else
                {
                    $json['status'] = 0;
                    $json['msg'] = '没有发现错误，导入完成';
                }

                $msg = json_encode ( $json );
            }
            catch ( Exception $ex )
            {
                $json['status'] = -1;
                $json['msg'] = $ex->getMessage ();
                $msg = json_encode ( $json );
            }
            print "<script>parent.pt_i_callback(" . $msg . ")</script>";
            exit;
        }
    }

    /**
     * 流量卡导入检查
     * @return string
     * @throws Exception
     */
    private function importICCIDCheck ()
    {
        $f = filter_input ( INPUT_GET , 'f' );
        $e_id = filter_input ( INPUT_GET , 'e_id' );
        $file = $f . '.xls';
        $config = Cof::config ();
        $filePath = $config['system']['webroot'] . DIRECTORY_SEPARATOR . "runtime" . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . $file;
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel5' );

        $objPHPExcel = $objReader->load ( $filePath );
        $objWorksheet = $objPHPExcel->getSheet ( 0 );

        //$highestColumn = $objWorksheet->getHighestColumn();
        $highestRow = $objWorksheet->getHighestRow ();    //取得总行数
        $pttm = array ();
        $error = array ();
        $warn = array ();
        $ptnumber = array ();
        $wz = "";
        for ( $row = 2; $row <= $highestRow; $row ++ )
        {
            $tmpName = trim ( $objWorksheet->getCellByColumnAndRow ( 0 , $row )->getValue () );
            if ( $tmpName !== '' )
            {
                $wz = $tmpName;

                if ( ! Cof::re ( ' /^1\d{10}$/' , $tmpName , 64 ) )
                {
                    $error[] = "第 $row 行，$tmpName 不是手机号";
                }
            }
            $tmpuser = array ();
            $tmpuser['g_iccid'] = trim ( $objWorksheet->getCellByColumnAndRow ( 1 , $row )->getValue () );
            $tmpuser['g_belong'] = trim ( $objWorksheet->getCellByColumnAndRow ( 2 , $row )->getValue () );
            $tmpuser['g_packages'] = trim ( $objWorksheet->getCellByColumnAndRow ( 3 , $row )->getValue () );
            $tmpuser['g_start_time'] = trim ( $objWorksheet->getCellByColumnAndRow ( 4 , $row )->getValue () );
            //var_dump ( Cof::re ( '/[0-9]/' , $tmpuser['g_iccid'] ) );

            if ( ! Cof::re ( '/^[\d]+$/' , $tmpuser['g_iccid'] ) )
            {
                $error[] = "第 $row 行，" . $tmpuser['g_iccid'] . "iccid不是数字";
            }
            if ( ! Cof::re ( '/^[\d]+$/' , $tmpuser['g_start_time'] ) )
            {
                $warn[] = "警告 第 $row 行，开卡日期" . $tmpuser['g_start_time'] . " 不符合规范。（如:20150203）";
            }
            $pttm[$wz][] = $tmpuser;
        }

        $this->warn = $warn;
        $this->error = $error;
        return $f;
    }

    // 导入文件
    private function importPTFile ()
    {
        $json = array ();
        try
        {
            $file = Cof::upload ();
            $json['status'] = 0;
            $json['data'] = str_replace ( '.xls' , '' , $file ); //清除后缀信息
        }
        catch ( Exception $ex )
        {
            $json['status'] = -1;
            $json['msg'] = $ex->getMessage ();
        }
        return json_encode ( $json );
    }

    // 数据导入
    private function importPT ()
    {
        $e_id = filter_input ( INPUT_GET , 'e_id' );
        $f = filter_input ( INPUT_GET , 'f' );
        $file = $f . '.xls';
        $config = Cof::config ();
        $filePath = $config['system']['webroot'] . DIRECTORY_SEPARATOR . "runtime" . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . $file;
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel5' );

        $objPHPExcel = $objReader->load ( $filePath );
        $objWorksheet = $objPHPExcel->getSheet ( 0 );

        $highestRow = $objWorksheet->getHighestRow ();    //取得总行数
        // 实际数据读取，数据导入
        $pttm = array ();
        $error = array ();
        $warn = array ();
        $ptnumber = array ();
        $wz = "";
        for ( $row = 2; $row <= $highestRow; $row ++ )
        {
            $tmpName = trim ( $objWorksheet->getCellByColumnAndRow ( 0 , $row )->getValue () );
            if ( $tmpName !== '' )
            {
                $wz = $tmpName;

                if ( ! Cof::re ( ' /^1\d{10}$/' , $tmpName , 64 ) )
                {
                    $error[] = "第 $row 行，$tmpName 不是手机号";
                }
            }
            $tmpuser = array ();
            $tmpuser['g_iccid'] = trim ( $objWorksheet->getCellByColumnAndRow ( 1 , $row )->getValue () );
            $tmpuser['g_belong'] = trim ( $objWorksheet->getCellByColumnAndRow ( 2 , $row )->getValue () );
            $tmpuser['g_packages'] = trim ( $objWorksheet->getCellByColumnAndRow ( 3 , $row )->getValue () ) == "1.2G" ? 1 : 2;
            $tmpuser['g_start_time'] = trim ( $objWorksheet->getCellByColumnAndRow ( 4 , $row )->getValue () );

            $pttm[$wz][] = $tmpuser;
            $this->warn = $warn;
            $this->error = $error;
        }

        // 导入流量卡
        $gprs = new gprs ();
        $pgnumber = array ();
        foreach ( $pttm as $key => $value )
        {
            $data = array ();
            $data['do'] = 'add';
            $data['g_iccid'] = $value[0]['g_iccid'];
            $data['g_belong'] = $value[0]['g_belong'];
            $data['g_packages'] = $value[0]['g_packages'];
            $data['g_start_time'] = $value[0]['g_start_time'];
            $data['g_intime'] = date ( "Y-m-d" , time ());
            $data['g_final_user'] = $_SESSION['own']['om_id'];
            $data['g_agents_id'] = "0";
            $data['g_stock_status'] = 1;
            $gprs->set ( $data );
            try
            {
                $gprs->save_gprs ();
            }
            catch ( Exception $exc )
            {
                if ( $exc->getCode () == 23505 )
                {
                    throw new Exception ( "所导入的ICCID已经存在,请检查" );
                }
            }
        //if ( $msg['status'] == '0' )
            // {
            //    $pgnumber[$key] = $e_id . sprintf ( "%05d" , $tmppgnumber );
            // }
        }

        $error = array ();
    }

    public $gprs;
    public $page;

    /**
     * 构造器，继承至contorl
     */
    public function __construct ()
    {
        parent::__construct ();
        $this->gprs = new gprs ( $_REQUEST );
        $this->page = new page ( $_REQUEST );
    }

    public function index ()
    {
        //列表页分条数 选中的显示相应颜色
        if($_REQUEST['gprs_num']){
            unset($_SESSION['color']);
            $_SESSION['color'][$_REQUEST['gprs_num']] = 'style="background:#E5E5E5"';
        }elseif($_SESSION['gprs_page_num']){
            unset($_SESSION['color']);
            $_SESSION['color'][$_SESSION['gprs_page_num']] = 'style="background:#E5E5E5"';
        }else{
            unset($_SESSION['color']);
            $_SESSION['color'][10] = 'style="background:#E5E5E5"';
        }
        $this->render ( 'modules/gprs/gprs.tpl' , '流量卡管理' );
    }

    public function gprs_item ()
    {
        //列表页分条数显示
        if($_REQUEST['gprs_num']){
            $_SESSION['gprs_page_num'] = $_REQUEST['gprs_num'];
        }
        if($_SESSION['gprs_page_num']){
            $_REQUEST['num'] = $_SESSION['gprs_page_num'];
            
        }
        //列表页分条数 选中的显示相应颜色
        if($_REQUEST['num']){
            unset($_SESSION['color']);
            $_SESSION['color'][$_REQUEST['num']] = 'style="background:#E5E5E5"';
        }else{
            unset($_SESSION['color']);
            $_SESSION['color'][10] = 'style="background:#E5E5E5"';
        }
        $page = new page ( $_REQUEST );
        $this->page = $page;
        $this->page->setTotal ( $this->gprs->getGprsTotal () );
        $numinfo = $this->page->getNumInfo ();
        $prev = $this->page->getPrev ();
        $next = $this->page->getNext ();
        $this->smarty->assign ( 'numinfo' , $numinfo );
        $this->smarty->assign ( 'prev' , $prev );
        $this->smarty->assign ( 'next' , $next );


        $list = $this->gprs->getList ( $this->page->getLimit () );
        $agent = new agents ( $_REQUEST );
        $this->smarty->assign ( 'list' , $list );
        $this->htmlrender ( 'modules/gprs/gprs_item.tpl' );
    }

    public function gprs_item_v2 ()
    {
        $page = new page ( $_REQUEST );
        $this->page = $page;
        $this->page->setTotal ( $this->gprs->getGprsTotal () );
        $numinfo = $this->page->getNumInfo ();
        $prev = $this->page->getPrev ();
        $next = $this->page->getNext ();
        $this->smarty->assign ( 'numinfo' , $numinfo );
        $this->smarty->assign ( 'prev' , $prev );
        $this->smarty->assign ( 'next' , $next );


        $list = $this->gprs->getList_v2 ();
        $agent = new agents ( $_REQUEST );
        $aginfo = $agents->get;
        $this->smarty->assign ( 'list' , $list );
        $this->htmlrender ( 'modules/gprs/gprs_item_v2.tpl' );
    }

    /**
     * 入库页面
     */
    public function gprs_add ()
    {
        $mininav = array (
            array (
                "url" => "?m=gprs&a=index" ,
                "name" => "企业管理" ,
                "next" => ">>"
            ) ,
            array (
                "url" => "?m=gprs&a=gprs_add" ,
                "name" => "流量卡入库" ,
                "next" => ""
            )
        );

        $this->smarty->assign ( 'mininav' , $mininav );
        $this->render ( 'modules/gprs/gprs_add.tpl' , '办理入库' );
    }

    /**
     * 出库页面
     */
    public function gprs_out ()
    {
        $page = new page ( $_REQUEST );
        $this->page = $page;
        $this->page->setTotal ( $this->gprs->getGprsTotal () );
        $numinfo = $this->page->getNumInfo ();
        $prev = $this->page->getPrev ();
        $next = $this->page->getNext ();
        $this->smarty->assign ( 'numinfo' , $numinfo );
        $this->smarty->assign ( 'prev' , $prev );
        $this->smarty->assign ( 'next' , $next );


        $list = $this->gprs->getList ( $this->page->getLimit () );
        $this->smarty->assign ( 'list' , $list );
        $mininav = array (
            array (
                "url" => "?m=gprs&a=index" ,
                "name" => "企业管理" ,
                "next" => ">>"
            ) ,
            array (
                "url" => "?m=gprs&a=gprs_add" ,
                "name" => "流量卡出库" ,
                "next" => ""
            )
        );

        $this->smarty->assign ( 'mininav' , $mininav );
        $this->render ( 'modules/gprs/gprs_out.tpl' , '办理出库' );
    }

//流量卡出库{1.代理商2.企业}
    public function gprsshellout ()
    {
        if ( $_REQUEST['create_type'] == 'agents' )//1.代理商出库
        {
            foreach ( $_REQUEST["checkbox"] as $val )
            {
                $data['g_iccid'] = $val;
                $gprs = new gprs ( array ( 'g_iccid' => $val ) );
                //$agents = new agents ( array ( 'ag_number' => $_SESSION['ag']['ag_number'] ) );
                $info = $gprs->getByid ();
                // $ag_info = $agents->getByid ();
                $data['g_agents_id'] = $_REQUEST['g_ag_id'];
                $data['g_agents_assign'] = $info['g_agents_assign'] . "|" . $data['g_agents_id'] . "|";
                $data['g_outtime'] = date ( 'Y-m-d' , time () );
                $data['g_intime0'] = $data['g_outtime'];
                $data['g_final_user'] = $_REQUEST['g_final_user'];
                $data['g_stock_status'] = 2;
                $this->gprs->set ( $data );
                $this->gprs->gprsshellout ();
            }
        }
        else//2.企业出库
        {
            if ( $_REQUEST['g_ag_en_id'] == "" )
            {
                //1.创建企业,并设置管理员
                $ep = new enterprise ( $_REQUEST );
                $result = $ep->save ();
                $data['em_id'] = $result['e_id'];
                $data['em_pswd'] = $data['em_id'];
                $data['em_ent_id'] = $data['em_id'];
                $data['em_phone'] = $_REQUEST['em_phone'];
                $data['em_mail'] = $_REQUEST['em_mail'];
                $data['em_name'] = $_REQUEST['em_name'];
                $data['em_desc'] = "";
                $data['edit'] = '';
                $e_id = $result['e_id'];
                /*                 * **创建管理员*** */
                $admins = new admins ( $data );
                $admins->save ();

                $user = new users ( array ( 'e_id' => $result['e_id'] ) );
                $start_id = $user->getstartid ();
            }
            else//选择已有企业
            {
                /*                 * **批量创建手机用户并设置流量卡ICCID 自动登录*** */
                //①获得当前用户号码起始ID
                //初始化数据
                $user = new users ( array ( 'e_id' => $_REQUEST['g_ag_en_id'] ) );
                $start_id = $user->getstartid ();
                $e_id = $_REQUEST['g_ag_en_id'];
            }
            $start_num = substr ( $start_id[0] , 6 , 1 );
            $start_index = substr ( $start_id[0] , 0 , 1 );
            if ( $start_index == 1 )
            {
                $start_id = 70000;
            }
            else
            {
                if ( $start_num < 7 || count ( $start_id ) == 0 )
                {
                    $start_id = 70000;
                }
                else
                {
                    $start_id = $start_id[0] + 1;
                }
            }
            //②获取批量创建个数
            $sum = $_REQUEST['check_num'];
            //③创建用户,并分配流量卡ICCID 自动登录
            for ( $i = 0; $i < $sum; $i ++ )
            {
                $data['u_number'] = $start_id + $i;
                $data['u_passwd'] = $start_id + $i;
                $data['u_name'] = $start_id + $i;
                $data['u_iccid'] = $_REQUEST["checkbox"][$i];
                $data['u_auto_config'] = 1;
                $data['u_sex'] = "M";
                $data['e_id'] = $e_id;
                $data['u_sub_type'] = 1;
                $user->set ( $data );
                $user->save ();
            }
            /*             * ***** ********************************************** */
            //2.流量卡出库
            foreach ( $_REQUEST["checkbox"] as $val )
            {
                $data['g_iccid'] = $val;
                $gprs = new gprs ( array ( 'g_iccid' => $val ) );
                //$agents = new agents ( array ( 'ag_number' => $_SESSION['ag']['ag_number'] ) );
                $info = $gprs->getByid ();
                // $ag_info = $agents->getByid ();
                $data['g_agents_id'] = $_REQUEST['ag_number'];
                $data['g_agents_assign'] = $info['g_agents_assign'] . "|" . $data['g_agents_id'] . "|";
                $data['g_outtime'] = date ( 'Y-m-d' , time () );
                $data['g_intime0'] = $data['g_outtime'];
                $data['g_final_user'] = $_REQUEST['g_final_user'];
                $data['g_e_id'] = $e_id;
                $data['g_stock_status'] = 2;
                $this->gprs->set ( $data );
                $this->gprs->gprsshellout ();
            }
            /*             * ******************************** */
        }
        $this->tools->call ( "操作成功！" , 0 , true );
    }

//流量卡入库 OMP特有
    public function gprs_save ()
    {
        if ( $_REQUEST['do'] != "edit" )
        {
            //$_REQUEST['ag_number'] = $_REQUEST['g_final_user'];

            //$agents = new agents ( $_REQUEST );
            //$list = $agents->getByid ();
            $arr_iccid = array ();
            $arr_packages = array ();
            $arr_start_time = array ();
            $arr_intime = array ();
            $arr_belong = array ();
            foreach ( $_REQUEST as $key => $value )
            {
                if ( strstr ( $key , "g_iccid" ) )
                {
                   //$_REQUEST['g_iccid'] = array ();
                    array_push ( $arr_iccid , $value );
                }
                if ( strstr ( $key , "g_packages" ) )
                {
                    array_push ( $arr_packages , $value );
                }
                if ( strstr ( $key , "g_start_time" ) )
                {
                    //$_REQUEST['g_start_time'] = array ();
                    array_push ( $arr_start_time , $value );
                }
                if ( strstr ( $key , "g_intime" ) )
                {
                    //$_REQUEST['g_intime'] = array ();
                    array_push ( $arr_intime , $value );
                }
                if ( strstr ( $key , "g_belong" ) )
                {
                   //$_REQUEST['g_belong'] = array ();
                    array_push ( $arr_belong , $value );
                }
            }
            $_REQUEST['g_iccid'] = $arr_iccid;
            $_REQUEST['g_packages'] = $arr_packages;
            $_REQUEST['g_start_time'] = $arr_start_time;
            $_REQUEST['g_intime'] = $arr_intime;
            $_REQUEST['g_belong'] = $arr_belong;
            for ( $i = 0; $i < count ( $_REQUEST['g_iccid'] ); $i ++ )
            {
                $data['g_final_user'] = $_REQUEST['g_final_user'];
                $data['g_iccid'] = $_REQUEST['g_iccid'][$i];
                $data['g_packages'] = $_REQUEST['g_packages'][$i];
                $data['g_start_time'] = $_REQUEST['g_start_time'][$i];
                $data['g_intime'] = $_REQUEST['g_intime'][$i];
                $data['g_belong'] = $_REQUEST['g_belong'][$i];
                $data['g_agents_assign'] = "|0|";
                $data['g_agents_id'] = 0;
                $data['g_stock_status'] = 1;
                $data['g_recorder'] = $_REQUEST['g_final_user'];
                $data['do'] = $_REQUEST['do'];

                $this->gprs->set ( $data );
                if ( $data['g_iccid'] != "" && $data['g_belong'] != "" )
                {
                    $this->gprs->save_gprs ();
                }
            }
        }
        else
        {
            //$_REQUEST['ag_number'] = $_REQUEST['g_final_user'];
            $data['g_final_user'] = $_REQUEST['g_final_user'];
            $data['g_iccid'] = $_REQUEST['g_iccid'][0];
            $data['g_packages'] = $_REQUEST['g_packages'][0];
            $data['g_start_time'] = $_REQUEST['g_start_time'][0];
            $data['g_intime'] = $_REQUEST['g_intime'][0];
            $data['g_belong'] = $_REQUEST['g_belong'][0];
            $data['do'] = $_REQUEST['do'];
            $this->gprs->set ( $data );
            $this->gprs->save_gprs ();
        }
        $this->tools->call ( "操作成功" , 0 , true );
    }

    public function gprs_option ()
    {
        $gprs = new gprs ( $_REQUEST );
        $list = $gprs->getgprsList ();
        $this->smarty->assign ( 'list' , $list );
        $this->htmlrender ( 'modules/gprs/gprs_option_view.tpl' );
    }

    /**
     * 编辑页面
     */
    public function gprs_edit ()
    {
        $mininav = array (
            array (
                "url" => "?m=gprs&a=index" ,
                "name" => "流量卡管理" ,
                "next" => ">>"
            ) ,
            array (
                "url" => "#" ,
                "name" => "编辑" ,
            )
        );
        $list = $this->gprs->getByid ();
        $this->smarty->assign ( 'mininav' , $mininav );
        $this->smarty->assign ( 'list' , $list );

        $this->render ( 'modules/gprs/gprs_edit.tpl' , '编辑流量卡' );
    }

}
