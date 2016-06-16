<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-04-17 10:26:22
 * @version $Id: ReportContorl.class.php,v 1.2 2015/05/04 03:54:07 jran Exp $
 */

class ReportContorl extends contorl {
    
    function __construct($data){
    	parent::__construct(); 
        $this->data=$data;
    }

    //默认首页
    public function index(){
        $this->render("modules/report/index.tpl");
    }
    

    function getjson(){
    	$report=new report();
    	$result=$report->get_array();
    	$res['citylist']=$result;
    	echo json_encode($res);
    }

    function report_item(){
        //var_dump($_REQUEST);die;
        $this->htmlrender("modules/report/index_item.tpl");
    }
}