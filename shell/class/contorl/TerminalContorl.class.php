<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TerminalContorl
 *
 * @author zed
 */
class TerminalContorl extends contorl{
    /**
     * 终端模板下载
     */
    public function terminal_export ()
    {
        $data = array ();
        //$data['e_id'] = filter_input ( INPUT_GET , 'e_id' );
        //var_dump($ug_list);
        //获得类型参数
        $res=$this->tem->getList();
        
        $type_model="";
        foreach ($res as $key => $value) {
            $type_model.=$value['tt_type'].",";
        }
       $type_model= trim($type_model,",");
        $excel = new PHPExcel();
         $excel->getActiveSheet()->getColumnDimension(1)->setAutoSize(true);
         $excel->getActiveSheet()->getColumnDimension(2)->setAutoSize(true);
         $excel->getActiveSheet()->getColumnDimension(3)->setAutoSize(true);
        /** 设置表头 */
        $excel->getActiveSheet ()->setCellValue ( 'A1' , 'IMEI' );
        $excel->getActiveSheet ()->setCellValue ( 'B1' , L('终端型号') );
        $excel->getActiveSheet ()->setCellValue ( 'C1' , L('序列号') );
        //$excel->getActiveSheet ()->getStyle("A" . 2)->getNumberFormat()->setFormatCode("000000");
        $excel->getActiveSheet ()->setCellValueExplicit ( "A" . 2 , '123456789012345' , PHPExcel_Cell_DataType::TYPE_STRING );

        $excel->getActiveSheet ()->getCell("B". 2)->getDataValidation()-> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
           -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
           -> setAllowBlank(false)
           -> setShowInputMessage(true)
           -> setShowErrorMessage(true)
           -> setShowDropDown(true)
           -> setErrorTitle(L('输入的值有误'))
           -> setError(L('您输入的值不在下拉框列表内'))
           -> setPromptTitle(L('设备类型'))
           -> setFormula1('"'.$type_model.'"');
        $excel->getActiveSheet ()->setCellValueExplicit ( "C" . 2 , "123asd" , PHPExcel_Cell_DataType::TYPE_STRING2 );
        
        $excel->getActiveSheet ()->setCellValueExplicit ( "A" . 3 , '543210987654321' , PHPExcel_Cell_DataType::TYPE_STRING );
        $excel->getActiveSheet ()->getCell("B". 3)->getDataValidation()-> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
           -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
           -> setAllowBlank(true)
           -> setShowInputMessage(true)
           -> setShowErrorMessage(true)
           -> setShowDropDown(true)
           -> setErrorTitle(L('输入的值有误'))
           -> setError(L('您输入的值不在下拉框列表内'))
           -> setPromptTitle(L('设备类型'))
           -> setFormula1('"'.$type_model.'"');
        $excel->getActiveSheet ()->setCellValueExplicit ( "C" . 3 , "321asd" , PHPExcel_Cell_DataType::TYPE_STRING2 );

            for($i=4;$i<202;$i++){
                //$excel->getActiveSheet ()->getStyle("A" . $i)->getNumberFormat()->setFormatCode("000000");
                $excel->getActiveSheet ()->getCell("B". $i)->getDataValidation()-> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
                   -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
                   -> setAllowBlank(true)
                   -> setShowInputMessage(true)
                   -> setShowErrorMessage(true)
                   -> setShowDropDown(true)
                   -> setErrorTitle(L('输入的值有误'))
                   -> setError(L('您输入的值不在下拉框列表内'))
                   -> setPromptTitle(L('设备类型'))
                   -> setFormula1('"'.$type_model.'"');
            }
        /* 导出 */
        coms::head ( 'excel' , $excel );

}

    /**
     * 终端入库导入
     */
    public function importShellIMEI ()
    {
        $step = is_string ( $_REQUEST['step'] ) ? $_REQUEST['step'] : '';
        if ( $step === 'if' )
        {
            $msg = $this->importMTFile ();
            print "<script>parent.tm_if_callback(" . $msg . ")</script>";
            exit;
        }
        if ( $step === 'ic' )
        {
            try
            {
                $f = $this->importIMEICheck ();
                if ( count ( $this->error ) > 0 ||count ( $this->warn ) > 0 )
                {
                    $json['status'] = -1;
                    $json['msg'] = L('存在错误无法导入').'<br />';
                }
                else
                {
                    $json['status'] = 0;
                    $json['msg'] = L('无严重错误').'<br />';
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
            print "<script>parent.tm_ic_callback(" . $msg . ")</script>";
            exit;
        }
        if ( $step === 'i' )
        {
            try
            {
                $this->importMT ();

                if ( count ( $this->error ) > 0 )
                {
                    $json['status'] = -1;
                    $json['msg'] = L('存在错误');
                    $json['msg'].='<div class="show">';
                    $json['msg'] .= implode ( '<br />' , $this->error );
                    $json['msg'].='</div>';
                }
                else
                {
                    $json['status'] = 0;
                    $json['msg'] = L('没有发现错误，导入完成');
                }

                $msg = json_encode ( $json );
            }
            catch ( Exception $ex )
            {
                $json['status'] = -1;
                $json['msg'] = $ex->getMessage ();
                $msg = json_encode ( $json );
            }
            print "<script>parent.tm_i_callback(" . $msg . ")</script>";
            exit;
        }
    }

    /**
     * 流量卡导入检查
     * @return string
     * @throws Exception
     */
    private function importIMEICheck ()
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
        $highestRow = $objWorksheet->getHighestRow ("A");//取得总行数
        $mttm = array ();
        $error = array ();
        $warn = array ();
        $mtnumber = array ();
        $wz = "";
        for ( $row = 2; $row <= $highestRow; $row ++ )
        {
            //$tmpName = trim ( $objWorksheet->getCellByColumnAndRow ( 0 , $row )->getValue () );
           /*
            if ( $tmpName !== '' )
            {
                $wz = $tmpName;

                if ( ! Cof::re ( ' /^\d{19,20}$/' , $tmpName , 64 ) )
                {
                    $error[] = "第 $row 行，$tmpName 不符合规则";
                }
            }*/
            $tmpuser = array ();
            $tmpuser['md_imei'] = trim ( $objWorksheet->getCellByColumnAndRow ( 0 , $row )->getValue () );
            $mtnumber[$row]=$tmpuser['md_imei'];
             if ( $tmpuser['md_imei'] !== '' )
            {
                $wz = $tmpuser['md_imei'];
                if ( ! Cof::re ( ' /^\d{15}$/' , $tmpuser['md_imei'] , 64 ) )
                {
                    //$error[] = "第 %d 行，%d 不是IMEI号";
                    $error[] = sprintf(L("第 %d 行，%d 不是IMEI号"),
                            $row,
                            $tmpuser['md_imei']
                            );
                }
                if($this->check_imei_im($tmpuser['md_imei'])==false){
                //$error[] = "第 %d 行，%d 已存在";
                $error[] = sprintf(L("第 %d 行，%s 已存在"),
                            $row,
                            $tmpuser['md_imei']
                            );
                }
            }
           
            $tmpuser['md_type'] = trim ( $objWorksheet->getCellByColumnAndRow ( 1 , $row )->getValue () );
            $tmpuser['md_serial_number'] = trim ( $objWorksheet->getCellByColumnAndRow ( 2 , $row )->getValue () );
            //var_dump ( Cof::re ( '/[0-9]/' , $tmpuser['g_iccid'] ) );
             /*
            if ( ! Cof::re ( '/^[\d]+$/' , $tmpuser['md_imei'] ) )
            {
                $error[] = "第 $row 行，" . $tmpuser['md_imei'] . "IMEI不是数字";
            }
           
            if ( ! Cof::re ( '/^[\d]+$/' , $tmpuser['g_start_time'] ) )
            {
                $warn[] = "警告 第 $row 行，开卡日期" . $tmpuser['g_start_time'] . " 不符合规范。（如:20150203）";
            }
             */
            $mttm[$wz][] = $tmpuser;
        }
        $res=array_unique($mtnumber);
        $res_diff=array_diff_assoc($mtnumber,$res);
        
        $resuslt=array_intersect($res,$res_diff);
        $arr_final=array();
        foreach ($resuslt as $key => $value) {
            foreach ($res_diff as $k => $val) {
                if($value==$val){
                     $arr_final[]= sprintf(L("第 %d 行 与 第 %d 行 IMEI相同"),
                            $key,
                            $k
                            );
                }
            }
        }
        if(count($arr_final)>=1){
            $this->warn = $arr_final;
        }else{
            $this->warn = $warn;
        }
        $this->error = $error;
        return $f;
    }

    // 导入文件
    private function importMTFile ()
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
    private function importMT ()
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
        $mttm = array ();
        $error = array ();
        $warn = array ();
        $mtnumber = array ();
        $wz = "";
        for ( $row = 2; $row <= $highestRow; $row ++ )
        {
            //$tmpName = trim ( $objWorksheet->getCellByColumnAndRow ( 0 , $row )->getValue () );
            /*
            if ( $tmpName !== '' )
            {
                $wz = $tmpName;

                if ( ! Cof::re ( ' /^1\d{10}$/' , $tmpName , 64 ) )
                {
                    $error[] = "第 $row 行，$tmpName 不是手机号";
                }
            }*/
            $tmpuser = array ();
            $tmpuser['md_imei'] = trim ( $objWorksheet->getCellByColumnAndRow ( 0 , $row )->getValue () );
            $mtnumber[$row]=$tmpuser['md_imei'];
            if ( $tmpuser['md_imei'] !== '' )
            {
                $wz = $tmpuser['md_imei'];

                if ( ! Cof::re ( ' /^\d{15}$/' , $tmpuser['md_imei'] , 64 ) )
                {
                    $error[] = sprintf(L("第 %d 行，%d 不是IMEI号"),
                            $row,
                            $tmpuser['md_imei']
                            );
                }
            }
            $tmpuser['md_type'] = trim ( $objWorksheet->getCellByColumnAndRow ( 1 , $row )->getValue () );
            $tmpuser['md_serial_number'] = trim ( $objWorksheet->getCellByColumnAndRow ( 2 , $row )->getValue () );

            $mttm[$wz][] = $tmpuser;
            $res=array_unique($mtnumber);
        $res_diff=array_diff_assoc($mtnumber,$res);
        
        $resuslt=array_intersect($res,$res_diff);
        $arr_final=array();
        foreach ($resuslt as $key => $value) {
            foreach ($res_diff as $k => $val) {
                if($value==$val){
                     $arr_final[]= sprintf(L("第 %d 行 与 第 %d 行 IMEI相同"),
                            $key,
                            $k
                            );
                }
            }
        }
        if(count($arr_final)>=1){
            $this->warn = $arr_final;
        }else{
            $this->warn = $warn;
        }
            
            $this->error = $error;
        }

        // 导入终端设备
        $term = new terminal ();
        foreach ( $mttm as $key => $value )
        {
            $data = array ();
            $data['do'] = 'add';
            //$data['tl_id']=$term->get_tlid();
            $data['md_imei'] = $value[0]['md_imei'];
            $data['md_type'] = $value[0]['md_type'];
            $data['md_serial_number'] = $value[0]['md_serial_number'];
            $term->set ( $data );
            try
            {
                $term->batch_save ();
            }
            catch ( Exception $exc )
            {
                if ( $exc->getCode () == 23505 )
                {
                    throw new Exception ( L("所导入的IMEI已经存在,请检查") );
                }
            }
        //if ( $msg['status'] == '0' )
            // {
            //    $pgnumber[$key] = $e_id . sprintf ( "%05d" , $tmppgnumber );
            // }
        }

        $error = array ();
    }
    //put your code here
    public $tem;
    public $page;
    public $groups;
    public $ag;
    public function __construct() {
        parent::__construct();
        $this->groups=new groups($_REQUEST);
        $this->ag=new agents($_REQUEST);
        //列表页分条数显示
        if($_REQUEST['ter_num']){
            $_SESSION['ter_page_num'] = $_REQUEST['ter_num'];
        }
        if($_SESSION['ter_page_num']){
            $_REQUEST['num'] = $_SESSION['ter_page_num'];
        }
        $this->tem=new terminal($_REQUEST);
        $this->page=new page($_REQUEST);
        $this->groups=new groups($_REQUEST);
        $this->ag=new agents($_REQUEST);
    }
    
   public function index_type(){
       $this->render("modules/terminal/index_type.tpl",L('终端类型'));
   }
    public function index_list(){
	//列表页分条数 选中的显示相应颜色
        if($_REQUEST['ter_num']){
            unset($_SESSION['color']);
            $_SESSION['color'][$_REQUEST['ter_num']] = 'style="background:#E5E5E5"';
        }elseif($_SESSION['ter_page_num']){
            unset($_SESSION['color']);
            $_SESSION['color'][$_SESSION['ter_page_num']] = 'style="background:#E5E5E5"';
        }else{
            unset($_SESSION['color']);
            $_SESSION['color'][10] = 'style="background:#E5E5E5"';
        }
       $this->render("modules/terminal/index_list.tpl",L('终端管理'));
   }
   public function terminal_in(){
        $mininav = array(
                         array(
                                 "url" => "?m=terminal&a=index_list",
                                 "name" => "终端管理",
                                 "next" => ">>",
                         ),
                         array(
                                 "url" => "?m=terminal&a=index_list",
                                 "name" => "终端列表",
                                 "next" => ">>",
                         ),
                         array(
                                 "url" => "#",
                                 "name" => "终端入库",
                                 "next" => "",
                         ),
                 );
        $this->smarty->assign('mininav', $mininav);
        $this->render("modules/terminal/terminal_in.tpl",L('终端入库'));
   }

   public function index_list_item(){
       $this->page->setTotal($this->tem->getlistTotal());
       $list = $this->tem->getlistList($this->page->getLimit());
       $numinfo = $this->page->getNumInfo();
        $prev = $this->page->getPrev();
        $next = $this->page->getNext();
        $this->smarty->assign('list', $list);
        $this->smarty->assign('numinfo', $numinfo);
        $this->smarty->assign('prev', $prev);
        $this->smarty->assign('next', $next);
       $this->smarty->assign('title', "终端类型列表");
       $this->smarty->assign('list', $list);
       $this->htmlrender("modules/terminal/index_list_item.tpl");
   }
   public function index_type_item(){
       $this->page->setTotal($this->tem->getTotal());
       $list = $this->tem->getList($this->page->getLimit());
       $numinfo = $this->page->getNumInfo();
        $prev = $this->page->getPrev();
        $next = $this->page->getNext();
        $this->smarty->assign('list', $list);
        $this->smarty->assign('numinfo', $numinfo);
        $this->smarty->assign('prev', $prev);
        $this->smarty->assign('next', $next);
       $this->smarty->assign('title', "终端类型列表");
       $this->smarty->assign('list', $list);
       $this->htmlrender("modules/terminal/index_type_item.tpl");
   }
   /**
    * frame 页面加载
    */
   public function terminal_add(){
       if($_REQUEST['do']=="replace"){
            $info=$this->tem->getById_type();
            $this->smarty->assign('list',$info);
            $this->smarty->assign('data',$_REQUEST);
       }
       $this->htmlrender("modules/terminal/terminal_add.tpl");
   }
   public function terminal_replace(){
       $this->smarty->assign("list",$_REQUEST);
       $this->htmlrender("modules/terminal/terminal_replace.tpl");
   }
    /**
     * 终端页面
     */
    public function history(){
        $mininav = array(
            array(
                "url" => "?m=terminal&a=index_list",
                "name" => "终端管理",
                "next" => ">>",
            ),
            array(
                "url" => "?m=terminal&a=index_list",
                "name" => "终端列表",
                "next" => ">>",
            ),
            array(
                "url" => "#",
                "name" => "历史纪录",
                "next" => "",
            ),
        );
        //获取终端信息
        $this->tem->set(array('md_imei'=>$_REQUEST['th_imei']));
        $info=$this->tem->getselect_list();
        $this->smarty->assign('mininav', $mininav);
        $this->smarty->assign('data', $info);
        $this->render("modules/terminal/terminal_history.tpl",L('历史纪录'));
    }
    /**
     * 终端历史记录列表页
     */
    public function history_item(){
        $this->page->setTotal($this->tem->getTotal_history());
        $list = $this->tem->getList_term_history($this->page->getLimit());
        //var_dump($list);die;
        $numinfo = $this->page->getNumInfo();
        $prev = $this->page->getPrev();
        $next = $this->page->getNext();
        $this->smarty->assign('list', $list);
        $this->smarty->assign('numinfo', $numinfo);
        $this->smarty->assign('prev', $prev);
        $this->smarty->assign('next', $next);
        $this->htmlrender("modules/terminal/terminal_history_item.tpl");
    }
   public function terminal_upload(){
       $tem=new terminal($_REQUEST);
       $res=$tem->term_upload();
       if($res['status']===0){
            echo 1;
        }else{
            echo 2;
        }
   }
   public function check_type_name(){
       $res=$this->tem->check_type_name();
       if($res){
           echo 0;
       }else{
           echo 1;
       }
   }
           function show_pic() {
          $pic = new pic($_REQUEST);
          $pic->show_terminal();
  }
  public function option(){
      $res=$this->tem->getList();
      foreach ($res as $key => $value) {
          $list[$key]['id']=$value['tt_type'];
          $list[$key]['name']=$value['tt_type'];
      }
      $this->smarty->assign('list',$list);
      $this->htmlrender("viewer/option.tpl");
  }
  public function batch_terminal(){
      foreach ($_REQUEST["md_imei"] as $key => $value) {
          $data['md_imei']=$value;
          $data['md_type']=$_REQUEST['md_type'][$key];
          $data['md_serial_number']=$_REQUEST['md_serial_number'][$key];
          //$data['tl_system_num']=$_REQUEST['tl_system_num'][$key];
          $data['md_time']=$_REQUEST['md_time'][$key];
          $data['md_parent_ag']=$_REQUEST['md_parent_ag'][$key];
          $this->tem->set($data);
          $msg=$this->tem->batch_save();
      }
      
      $this->tools->call($msg['msg'], $msg['status'], true);
  }
  public function save_terminal(){
      $aRes=$this->tem->getById_list();
      if($aRes['md_binding'] == '1')
      {
          $msg['status']=-1;
          $msg['msg']=L("终端被绑定不能修改");
          echo json_encode($msg);
      }
      else 
      {
          $msg=$this->tem->batch_save();
          echo json_encode($msg);
      }
      
  }
  public function del_term(){
     $aRes=$this->tem->getById_list();
     if($aRes['md_binding'] == '1')
     {
         echo 2;
     }
     else 
     {
        $res=$this->tem->term_del();
        echo $res;
     }
     
  }
  public function batch_del_term(){
      $res=0;
      foreach ($_REQUEST['checkbox'] as $key => $value) {
          $data['md_imei']=$value;
          $this->tem->set($data);
          $aRes=$this->tem->getselect_list();
          if($aRes['md_binding'] == '0') //未绑定 删除
          {
            $this->tem->term_del();
            $res++;
          }
          
      }
        
        echo $res;
    }
    /**
     * 批量启用终端
     */
  public function term_allstart(){
      $res=0;
      foreach ($_REQUEST['checkbox'] as $key => $value) {
          $data['md_imei']=$value;
          $this->tem->set($data);
          $aRes=$this->tem->getselect_list();
          $data['md_status']=1;
          if($aRes['md_binding'] == '1' && $aRes['md_status'] == '0') // 绑定且状态为0（停用）
          {
              $this->tem->set($data);
              $this->tem->set_stat();
              $res++;
          }

      }

      echo $res;
  }
    /**
     * 批量停用终端
     */
    public function term_allstop(){
        $res=0;
        foreach ($_REQUEST['checkbox'] as $key => $value) {
            $data['md_imei']=$value;
            $this->tem->set($data);
            $aRes=$this->tem->getselect_list();
            $data['md_status']=0;
            if($aRes['md_binding'] == '1' && $aRes['md_status'] == '1') // 绑定且状态为1（启用）
            {
                $this->tem->set($data);
                $this->tem->set_stat();
                $res++;
            }

        }

        echo $res;
    }
  public function del_term_type(){
      $res=$this->tem->term_del_type();
      if($res!=0){
          $pic=new pic(array('p_file'=>$_REQUEST['tt_type']));
          $info=$pic->del_pic();
      }
      echo $res;
  }
  
  /**
   * 检测imei是否已存在
   */
  public function check_imei(){
      $res = $this->tem->getById_list();
      if($res==false){
          echo 1;
      }else{
          echo 0;
      }
  }
  /**
   * 检测meid是否已存在
   */
  public function check_meid(){
      if($_REQUEST['md_meid']!=''){
          $res = $this->tem->getById_list_meid();
          if($res==false){
              echo 1;
          }else{
              echo 0;
          }
      }
  }
  /**
   * 检测imei是否已存在
   */
  public function check_imei_im($imei){
      $this->tem->set(array('md_imei'=>$imei));
      $res=  $this->tem->getById_list();
      if($res==false){
          return false;
      }else{
          return true;
      }
  }
  
  /**
   * 批量修改类型接口
   */
  public function term_batch(){
      foreach ($_REQUEST['checkbox'] as $k=>$v){
          $data['md_type']=$_REQUEST['md_type'];
          $data['md_imei']=$v;
          $this->tem->set($data);
          $aRes=$this->tem->getselect_list();
          if($_REQUEST['md_type']=="%"){
              $data['md_type']=$aRes['md_type'];
              $this->tem->set($data);
          }
          if($aRes['md_binding'] == '0')
          {
              $this->tem->save_term_type();
          }
          
      }
  }
  
  /*
   * 作者 hongyuan.li
   * 时间 2015.7.27
   * 功能 keeper管理页
   */
  public function keeper_list()
  {
      $this->render("modules/terminal/keeper_list.tpl",L('Keeper管理'));
  }
    /*
   * 作者 hongyuan.li
   * 时间 2015.7.27
   * 功能 keeper管理页列表
   */
  public function keeper_list_item()
  {
      $this->page->setTotal($this->tem->getKeeperTotal());
      $list = $this->tem->getkeeperList($this->page->getLimit());
      $numinfo = $this->page->getNumInfo();
      $prev = $this->page->getPrev();
      $next = $this->page->getNext();
      $page = $this->page->getPage();
      $this->smarty->assign('page', $page);
      $this->smarty->assign('list', $list);
      $this->smarty->assign('numinfo', $numinfo);
      $this->smarty->assign('prev', $prev);
      $this->smarty->assign('next', $next);
      $this->smarty->assign('title', "keeper列表");
      $this->htmlrender("modules/terminal/keeper_list_item.tpl");
  }
  /*
   * 作者 hongyuan.li
   * 时间 2015.7.28
   * 功能 keeper详情页
   */
  public function keeper_detail_list()
  {
      $aResult = $this->tem->getKeeper();
      $this->smarty->assign('aResult', $aResult);
      $this->render("modules/terminal/keeper_detail_list.tpl");
  }
    /*
   * 作者 hongyuan.li
   * 时间 2015.7.28
   * 功能 keeper详情页列表
   */
  public function keeper_detail_list_item()
  {
      $this->page->setTotal($this->tem->getKeeperDetailTotal());
      $list = $this->tem->getkeeperdetailList($this->page->getLimit());
      foreach ($list as $key => $value) {
          $aGroups = $this->groups->getuserPgname($value['u_number']);
          $group = array();
          if(!empty($aGroups))
          {
              foreach ($aGroups as $k => $val) {
                  array_push($group, $val['pg_name']);
              }
          }
          $list[$key]['groups']=$group;
          array_push($list[$key], $group);

      }
      $numinfo = $this->page->getNumInfo();
      $prev = $this->page->getPrev();
      $next = $this->page->getNext();
      $page = $this->page->getPage();
      $this->smarty->assign('page', $page);
      $this->smarty->assign('list', $list);
      $this->smarty->assign('numinfo', $numinfo);
      $this->smarty->assign('prev', $prev);
      $this->smarty->assign('next', $next);
      $this->smarty->assign('title', "keeper列表");
      $this->htmlrender("modules/terminal/keeper_detail_list_item.tpl");
  }
    /**
     * 所属代理商option生成
     * 所有终端设备中出现过的代理商ID
     */
    public function ag_option(){
        $list=$this->ag->getAllag();
        foreach($list as $key=>$value){
            $arr[$key]['id']=$value['ag_number'];
            $arr[$key]['name']=$value['ag_name'];
        }
        $arr=array_unique_fb($arr);
        $this->smarty->assign('list',$arr);
        $this->htmlrender("viewer/option.tpl");
    }
    /**
     * 所属企业option生成
     * 所有终端设备中出现过的代理商ID
     */
    public function e_option(){
        $list=$this->tem->get_md_alllist();
        foreach($list as $key=>$value){
            if($value['md_ent_id']!=""){
                $arr[$key]['id']=$value['md_ent_id'];
                $arr[$key]['name']=$value['e_name'];
            }
        }

        $arr=array_unique_fb($arr);
        $this->smarty->assign('list',$arr);
        $this->htmlrender("viewer/option.tpl");
    }
    /**
     * 状态设置
     */
    public function set_stat(){
        $res=$this->tem->set_stat();
        echo json_encode($res);
    }
    
     /**
     * 获取对应的imei信息
     */
    public function getById_foruser(){
        $Res=$this->tem->getselect_list();
        echo json_encode($Res);
    }
    /**
     * MEID
     */
    public function getById_foruser_meid(){
        $Res=$this->tem->checkexcel_meid($_REQUEST['md_meid']);
        echo json_encode($Res);
    }
}
