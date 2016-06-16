<?php

/**
 * 通用函数器
 */
class Cof
{

    static $db = null;
    static $config = null;

    public static function re($re, $str, $max = 32)
    {
        $reg = $re;
        $flag = preg_match($reg, $str);
        if (strlen($str) > $max)
        {
            $flag = 0;
        }
        return $flag;
    }

    public static function isChinese($str, $max = 32)
    {
        $reg = "/^[\x{4e00}-\x{9fa5}A-Za-z0-9\#\-\.\(\)\（\） \_\.]+$/u";
        $flag = preg_match($reg, $str);
        if (strlen($str) > $max)
        {
            $flag = 0;
        }
        return $flag;
    }

    /**
     * 获取配置文件
     * @param type $str
     * @return type
     */
    public static function config($str = '')
    {
        if ($str === 'clear')
        {
            Cof::$config = NULL;
        }
        if (Cof::$config === NULL)
        {
            Cof::$config = parse_ini_file('../private/config/config.ini', true);
        }
        return Cof::$config;
    }

    public static function upload()
    {
        $filetype = array("xls");
        $extension = pathinfo($_FILES['fileToUpload']['name']);

        if ($_FILES['fileToUpload']['error'] != "")
        {
            switch ($_FILES['fileToUpload']['error'])
            {
                case UPLOAD_ERR_NO_FILE:
                    throw new Exception("没有文件被选择", -1);
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new Exception("超出了大小限制", -2);
                default:
                    throw new Exception("未知的上传错误", -3);
            }
        }
        if ($_FILES['fileToUpload']['size'] > 999999999)
        {
            throw new Exception("文件太大，异常中断了", -4);
        }

        if (!in_array($extension['extension'], $filetype))
        {
            throw new Exception("未被允许的文件格式", -5);
        }
        $dir = "../runtime/tmp/";

        $file = Cof::guid() . "." . $extension['extension'];
        $dir .= $file;
        if (!move_uploaded_file($_FILES['fileToUpload']["tmp_name"], $dir))
        {
            throw new Exception("文件创建失败，请系统管理员检查文件权限或磁盘空间", -6);
        }
        return $file;
    }

    /**
     * 产生一个不重复的GUID
     * @param type $namespace
     * @return string
     */
    public static function guid($namespace = '')
    {
        $guid = '';
        $uid = uniqid("", true);
        $data = $namespace;
        $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
        $guid = substr($hash, 0, 8) . '-' . substr($hash, 8, 4) . '-' . substr($hash, 12, 4) . '-' . substr($hash, 16, 4) . '-' . substr($hash, 20, 12);
        return $guid;
    }

    /**
     * 通用数据库
     * @return PDO OMP数据库
     */
    public static function db()
    {
        if (Cof::$db === null)
        {
            $config = json_decode(file_get_contents("../private/config/db.json"), true);

            $host = $config["data_base"]["db_host"];
            $dbname = $config["data_base"]["db_name"];
            $port = $config["data_base"]["db_port"];
            $username = $config["data_base"]["db_user"];
            $password = $config["data_base"]["db_pwd"];

            if ($config["data_base"]["db_host"] != 'localhost')
            {
                $hosturl = "host=$host;";
            }

            try
            {
                Cof::$db = new PDO("pgsql:"
                        . $hosturl
                        . "port=$port;"
                        . "dbname=$dbname;"
                        , $username
                        , $password
                        , array(
                    PDO::ATTR_PERSISTENT => true
                        )
                );
            } catch (Exception$ex)
            {
                header("Content-type:text/html;charset=utf-8");
                echo "数据库初始化失败";
                exit();
            }
            Cof::$db->query("SET client_encoding='UTF-8';");
            Cof::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            Cof::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return Cof::$db;
    }

    /**
     * 通用导出器
     * @param type $header
     * @param type $filename
     * @param type $sql
     */
    public static function export($header, $filename = "resume", $sql = "")
    {
        $pdo = Cof::db();
        $excel = new PHPExcel();
        foreach ($header as $key => $value)
        {
            $col = PHPExcel_Cell::stringFromColumnIndex($key);
            $excel->getActiveSheet()->setCellValue($col . 1, $value);
        }

        if ($sql != "")
        {
            $stat = $pdo->query($sql);
            $result = $stat->fetchAll(PDO::FETCH_ASSOC);
            $n = 2;
            foreach ($result as $key => $value)
            {
                $i = 0;
                foreach ($value as $item)
                {
                    $col = PHPExcel_Cell::stringFromColumnIndex($i);
                    $excel->getActiveSheet()->setCellValueExplicit($col . $n, $item, PHPExcel_Cell_DataType::TYPE_STRING);
                    $i++;
                }
                $n++;
            }
        }
        $output = new PHPExcel_Writer_Excel5($excel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check = 0, pre-check = 0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="' . $filename . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $output->save('php://output');
    }
    /**
     * 新加坡版导出适配
     * @param type $header
     * @param type $filename
     * @param type $sql
     */
    public static function export_ag($header, $filename = "resume", $sql = "")
    {
        if ($sql != ""){
            $styleArray2 = array(
                'font' => array(
                  'bold' => true,
                  'size'=>12,
                  'color'=>array(
                    'argb' => '00000000',//黑色
                  ),
                ),
                'alignment' => array(
                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                ),
              );
            
            $pdo = Cof::db();
            $excel = new PHPExcel();
            $sql2="SELECT pg_number,pg_level FROM \"T_PttGroup_{$_REQUEST['e_id']}\"";
            $stat2 = $pdo->query($sql2);
            $result2 = $stat2->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result2 as $k=>$v) {
                array_push($header, "Group ".($k+1)."(".$v['pg_level'].")");
            }
            foreach ($header as $key => $value){
                $col = PHPExcel_Cell::stringFromColumnIndex($key);
                $excel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                $excel->getActiveSheet()->getStyle($col . 6)->applyFromArray($styleArray2);
                $excel->getActiveSheet()->setCellValue($col . 6, $value);
            }
            $stat = $pdo->query($sql);
            $result = $stat->fetchAll(PDO::FETCH_ASSOC);
            
             //获取企业信息
             $sql3="SELECT * FROM \"T_Enterprise\" WHERE e_id={$_REQUEST['e_id']}";
             $stat3 = $pdo->query($sql3);
             $sql4="SELECT * FROM \"T_EnterpriseManager\" WHERE em_ent_id='{$_REQUEST['e_id']}'";
             $stat4 = $pdo->query($sql4);
            $result3 = $stat3->fetch(PDO::FETCH_ASSOC);//企业详细信息
            $result4=$stat4->fetch(PDO::FETCH_ASSOC);//企业详细信息
            $n = 2;
            $j=1;
            $excel->getActiveSheet()->setCellValueExplicit('B' . $n, L("企业名称:"), PHPExcel_Cell_DataType::TYPE_STRING);
            $excel->getActiveSheet()->setCellValueExplicit('C' . $n, $result3['e_name'], PHPExcel_Cell_DataType::TYPE_STRING);
            $excel->getActiveSheet()->setCellValueExplicit('D' . $n, L("企业ID:"), PHPExcel_Cell_DataType::TYPE_STRING);
            $excel->getActiveSheet()->setCellValueExplicit('E' . $n, $result3['e_id'], PHPExcel_Cell_DataType::TYPE_STRING);
            $n++;
            $excel->getActiveSheet()->setCellValueExplicit('B' . $n, L("创建时间:"), PHPExcel_Cell_DataType::TYPE_STRING);
            $excel->getActiveSheet()->setCellValueExplicit('C' . $n, $result3['e_create_time'], PHPExcel_Cell_DataType::TYPE_STRING);
            $excel->getActiveSheet()->setCellValueExplicit('D' . $n, L("地址:"), PHPExcel_Cell_DataType::TYPE_STRING);
            $excel->getActiveSheet()->setCellValueExplicit('E' . $n, $result3['e_addr'], PHPExcel_Cell_DataType::TYPE_STRING);
            $n++;
            $excel->getActiveSheet()->setCellValueExplicit('B' . $n, L("管理员姓名:"), PHPExcel_Cell_DataType::TYPE_STRING);
            $excel->getActiveSheet()->setCellValueExplicit('C' . $n, $result4['em_name'], PHPExcel_Cell_DataType::TYPE_STRING);
            $excel->getActiveSheet()->setCellValueExplicit('D' . $n, L("电话:"), PHPExcel_Cell_DataType::TYPE_STRING);
            $excel->getActiveSheet()->setCellValueExplicit('E' . $n, $result4['em_phone'], PHPExcel_Cell_DataType::TYPE_STRING);
            $excel->getActiveSheet()->setCellValueExplicit('F' . $n, L("邮箱:"), PHPExcel_Cell_DataType::TYPE_STRING);
            $excel->getActiveSheet()->setCellValueExplicit('G' . $n, $result3['e_mail'], PHPExcel_Cell_DataType::TYPE_STRING);
            $n++;
            $n++;
            $n++;
            foreach ($result as $key => $value)
            {
                 $sql1="SELECT pm_number,pm_level,pm_pgnumber,pg_name FROM \"T_PttMember_{$_REQUEST['e_id']}\" "
                 . "LEFT JOIN \"T_PttGroup_{$_REQUEST['e_id']}\" ON pg_number=pm_pgnumber WHERE pm_number='{$value['u_number']}' ORDER BY pg_name";
                $stat1 = $pdo->query($sql1);
                $result1 = $stat1->fetchAll(PDO::FETCH_ASSOC);
                $i = 0;//第几列
//                foreach ($value as $k=>$item){
//                    $col = PHPExcel_Cell::stringFromColumnIndex($i);
                    $excel->getActiveSheet()->setCellValueExplicit('A' . $n, $j, PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("C" . $n, $value['u_terminal_type'], PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("D" . $n, $value['u_imei'], PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("E" . $n, $value['u_iccid'], PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("G" . $n, $value['u_terminal_number'], PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("H" . $n, $value['u_mobile_phone'], PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("J" . $n, $value['u_number'], PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("L" . $n, $j, PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("M" . $n, $value['u_number'], PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("N" . $n, $value['u_passwd'], PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("Q" . $n, $value['u_imei'], PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("R" . $n, $value['u_iccid'], PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("T" . $n, $value['u_terminal_number'], PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("U" . $n, $value['u_commercial_time'], PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("AA" . $n, $value['u_name'], PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("AB" . $n, $value['u_ug_id'], PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("AC" . $n, $value['u_gprs_genus']==1?"Customer":"Carrier", PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("AD" . $n, $value['u_audio_mode']=="0"?"Mobile":"VoIP", PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("AE" . $n, $value['u_create_time'], PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("AF" . $n, $value['u_purch_date'], PHPExcel_Cell_DataType::TYPE_STRING);
                     $excel->getActiveSheet()->setCellValueExplicit("AG" . $n, $value['u_sub_type']==2?"1":"0", PHPExcel_Cell_DataType::TYPE_STRING);
//                     $excel->getActiveSheet()->setCellValueExplicit("AG" . $n, $value['u_passwd']==2?"1":"0", PHPExcel_Cell_DataType::TYPE_STRING);
//                     $excel->getActiveSheet()->setCellValueExplicit("AH" . $n, $value['u_sub_type']==2?"1":"0", PHPExcel_Cell_DataType::TYPE_STRING);
                     
//                    if($k!="u_p_function"){
//                        if($k=="u_gprs_genus"){
//                            $item=$item===0?"Carrier":"Customer";
//                        }
//                        if($k=="u_audio_mode"){
//                            $item=($item===1||$item===NULL)?"VoIP":"Mobile";
//                        }
//                         if($k=="u_sub_type"){
//                            $item=($item==2)?"Y":"";
//                        }
//                        if($k=="u_audio_mode"){
//                            $item="";
//                        }
//                        if($k=="u_zm"){
//                            $item="";
//                        }
//                        
//                        $excel->getActiveSheet()->setCellValueExplicit("D" . $n, $item, PHPExcel_Cell_DataType::TYPE_STRING);
//                    }else{
                        $str='["gn_shpyw","gn_yythkt","gn_tppch","gn_gps","gn_djdtmsh","gn_dxx"]';
                        $col_num = PHPExcel_Cell::columnIndexFromString("AH");
                        $a=$col_num;
                        foreach (json_decode($str,true) as $val) {
                            $col = PHPExcel_Cell::stringFromColumnIndex($col_num);
                            if(strstr($value['u_p_function'],$val)){
                                $excel->getActiveSheet()->setCellValueExplicit($col . $n, "Y", PHPExcel_Cell_DataType::TYPE_STRING);
                            }
                            $col_num++;
                            $a++;
                        }
                        foreach ($result2 as $kk=>$vv){
                          $col = PHPExcel_Cell::stringFromColumnIndex($a);
                          foreach ($result1 as $value) {
                                if($value['pm_pgnumber']==$vv['pg_number']){
                                    $excel->getActiveSheet()->setCellValueExplicit($col . $n,$value['pg_name']."(".$value['pm_level'].")" , PHPExcel_Cell_DataType::TYPE_STRING);
                                }
                          }
                         $a++;
                        }
//                       $col_num++; 
//                    }
                   $i++; 
//                }
                $n++;
                $j++;
            }
            //设置新的sheet
            //$excel = new PHPExcel();
            $excel->createSheet();
            $excel->setActiveSheetIndex(1);
            $excel->getActiveSheet(1)->setTitle('User List');
            $excel->getActiveSheet(1)->getColumnDimension('A')->setWidth(17);
            $excel->getActiveSheet(1)->getColumnDimension('B')->setWidth(13.5);
            $excel->getActiveSheet(1)->getColumnDimension('C')->setWidth(7.7);
            $excel->getActiveSheet(1)->getColumnDimension('D')->setWidth(7.7);
            $excel->getActiveSheet(1)->getColumnDimension('E')->setWidth(8.5);
            $excel->getActiveSheet(1)->getColumnDimension('F')->setWidth(8.5);
            $excel->getActiveSheet(1)->getColumnDimension('G')->setWidth(6.5);
            $excel->getActiveSheet(1)->getColumnDimension('H')->setWidth(14.2);
            $excel->getActiveSheet(1)->getColumnDimension('I')->setWidth(15.6);
            $excel->getActiveSheet(1)->getColumnDimension('J')->setWidth(9);
            $excel->getActiveSheet(1)->getColumnDimension('K')->setWidth(6.2);
            $excel->getActiveSheet(1)->getColumnDimension('L')->setWidth(14.7);
            $excel->getActiveSheet(1)->getColumnDimension('M')->setWidth(13.3);
            $excel->getActiveSheet(1)->getColumnDimension('N')->setWidth(6.8);
            $excel->getActiveSheet(1)->getColumnDimension('O')->setWidth(5.7);
            $excel->getActiveSheet(1)->getColumnDimension('P')->setWidth(6.1);
            $excel->getActiveSheet(1)->getColumnDimension('Q')->setWidth(5.7);
            $excel->getActiveSheet(1)->getColumnDimension('R')->setWidth(5.4);
            $excel->getActiveSheet(1)->getColumnDimension('S')->setWidth(5.7);
            $excel->getActiveSheet(1)->getRowDimension(1)->setRowHeight(22);
            //$excel->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            //$excel->getStyle('A1:S1')->getFill()->getStartColor()->setARGB("#0cedffb");
            $excel->getActiveSheet(1)->getStyle('A1')->getFont()->setName('Candara');
            $excel->getActiveSheet(1)->getStyle('A1')->getFont()->setSize(20);
            $excel->getActiveSheet(1)->getStyle('A1')->getFont()->setBold(true);
            //$excel->getActiveSheet(1)->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
            $excel->getActiveSheet(1)->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
            $excel->getActiveSheet(1)->mergeCells('A1:S1');
            $excel->getActiveSheet(1)->setCellValue("A1", "CUSTOMER DETAILS");//合并单元格
            $excel->getActiveSheet(1)->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $excel->getActiveSheet(1)->getStyle('A1')->getFill()->getStartColor()->setARGB('FF808080');
            //$excel->getActiveSheet(1)->getStyle('A1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $styleArray1 = array(
                'font' => array(
                  'bold' => true,
                  'size'=>20,
                  'color'=>array(
                    'argb' => '00000000',
                  ),
                ),
                'alignment' => array(
                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                ),
              );
            $styleArray2 = array(
                'font' => array(
                  'bold' => true,
                  'size'=>12,
                  'color'=>array(
                    'argb' => '00000000',//黑色
                  ),
                ),
                'alignment' => array(
                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                ),
              );
            $styleArrayred = array(
                'font' => array(
                  'bold' => true,
                  'size'=>12,
                  'color'=>array(
                    'argb' => 'FFFF0000',//红色
                  ),
                ),
                'alignment' => array(
                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                ),
              );
            $styleArrayblue = array(
                'font' => array(
                  'bold' => true,
                  'size'=>12,
                  'color'=>array(
                    'argb' => 'FF008EFF',//蓝色
                  ),
                ),
                'alignment' => array(
                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                ),
              );
               //$phpExcel->getActiveSheet()->getCell('A1')->setValue('Some text');
               //$phpExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
                // 将A1单元格设置为加粗
               // $excel->getActiveSheet(1)->getStyle('A1')->applyFromArray($styleArray1);
               // $excel->getActiveSheet(1)->getStyle('B1')->getFont()->setBold(true);
            $styleThinBlackBorderOutline = array(
                    'borders' => array (
                       'outline' => array (
                          'style' => PHPExcel_Style_Border::BORDER_THIN,  //设置border样式
                          //'style' => PHPExcel_Style_Border::BORDER_THICK, 另一种样式
                          'color' => array ('argb' => 'FF000000'),     //设置border颜色
                      ),
                   ),
                );
            //企业详细信息 $result3
            $excel->getActiveSheet(1)->getStyle( 'A1:S1')->applyFromArray($styleThinBlackBorderOutline);
            $excel->getActiveSheet(1)->getRowDimension(2)->setRowHeight(5);
            $excel->getActiveSheet(1)->getRowDimension(3)->setRowHeight(22);
            $excel->getActiveSheet(1)->setCellValue("A3", "Customer");
            
            $excel->getActiveSheet(1)->setCellValue("E3", "New");
            $excel->getActiveSheet(1)->getStyle( 'F3')->applyFromArray($styleThinBlackBorderOutline);
            $excel->getActiveSheet(1)->setCellValue("H3", "Existing");
            $excel->getActiveSheet(1)->getStyle( 'I3')->applyFromArray($styleThinBlackBorderOutline);
            $excel->getActiveSheet(1)->mergeCells('K3:L3');
            $excel->getActiveSheet(1)->setCellValue("K3", "Customer ID");
            $excel->getActiveSheet(1)->setCellValueExplicit("M3", $result3['e_agents_id']==0?"000000":substr($result3['e_agents_id'],0, 6).$result3['e_id'],PHPExcel_Cell_DataType::TYPE_STRING);//代理商ID前6位+企业ID
            $excel->getActiveSheet(1)->getStyle( 'M3:S3')->applyFromArray($styleThinBlackBorderOutline);
            //设置有效行行高
            $excel->getActiveSheet(1)->getRowDimension(4)->setRowHeight(5);
            $excel->getActiveSheet(1)->getRowDimension(5)->setRowHeight(22);
            
            $excel->getActiveSheet(1)->setCellValue("A5", "Name of Business/ Company/Person Name");
            $excel->getActiveSheet(1)->setCellValue("F5", $result3['e_name']);//企业名称
            $excel->getActiveSheet(1)->getStyle( 'F5:S5')->applyFromArray($styleThinBlackBorderOutline);
            $excel->getActiveSheet(1)->getRowDimension(6)->setRowHeight(5);
            $excel->getActiveSheet(1)->getRowDimension(7)->setRowHeight(22);
            
            $excel->getActiveSheet(1)->setCellValue("A7", "Authorised Person Name");
            $excel->getActiveSheet(1)->setCellValue("F7", $result3['e_contact_name']." ".$result3['e_contact_surname']);
            $excel->getActiveSheet(1)->getStyle( 'F7:J7')->applyFromArray($styleThinBlackBorderOutline);
            $excel->getActiveSheet(1)->setCellValue("K7", "Email Address");
            $excel->getActiveSheet(1)->setCellValue("M7", $result3['e_contact_mail']);
            $excel->getActiveSheet(1)->getStyle( 'M7:S7')->applyFromArray($styleThinBlackBorderOutline);
            
            $excel->getActiveSheet(1)->getRowDimension(8)->setRowHeight(5);
            $excel->getActiveSheet(1)->getRowDimension(9)->setRowHeight(22);
            $excel->getActiveSheet(1)->setCellValue("A9", "Office Telephone No.");
            $excel->getActiveSheet(1)->setCellValue("F9", $result3['e_contact_phone']);
            $excel->getActiveSheet(1)->getStyle( 'F9:J9')->applyFromArray($styleThinBlackBorderOutline);
            $excel->getActiveSheet(1)->setCellValue("K9", "Office Fax Number");
            $excel->getActiveSheet(1)->setCellValue("M9", $result3['e_contact_fox']);
            $excel->getActiveSheet(1)->getStyle( 'M9:S9')->applyFromArray($styleThinBlackBorderOutline);
            
            $excel->getActiveSheet(1)->getRowDimension(10)->setRowHeight(12);
            $excel->getActiveSheet(1)->setCellValue("A10", "** For registration Under Individual, NRIC is required");
            $excel->getActiveSheet(1)->getRowDimension(11)->setRowHeight(22);
            $excel->getActiveSheet(1)->setCellValue("A11", "NRIC Number");
            
            //用户列表循环输出
            $ii=12;
            $nn=1;
            foreach ($result as $key => $value) {//企业用户详细信息list
                $excel->getActiveSheet(1)->getRowDimension($ii++)->setRowHeight(5);
                //第一行
                $excel->getActiveSheet(1)->getRowDimension($ii)->setRowHeight(22);
                $excel->getActiveSheet(1)->setCellValue("A".($ii), "NO.");
                $excel->getActiveSheet(1)->getStyle("A".($ii))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $excel->getActiveSheet(1)->getStyle("A".($ii))->getFill()->getStartColor()->setARGB('FF808080');
                $excel->getActiveSheet(1)->getStyle( "A".($ii))->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->getStyle("A".($ii))->applyFromArray($styleArray1);
                $excel->getActiveSheet(1)->getStyle("A".($ii))->getFont()->setBold(true);
                
                $excel->getActiveSheet(1)->setCellValue("C".($ii), "MODEL");
                $excel->getActiveSheet(1)->getStyle( 'C'.$ii.':E'.$ii)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $excel->getActiveSheet(1)->getStyle( 'C'.$ii.':E'.$ii)->getFill()->getStartColor()->setARGB('FF808080');
                $excel->getActiveSheet(1)->getStyle( 'C'.$ii.':E'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->getStyle('C'.($ii))->applyFromArray($styleArray1);
                $excel->getActiveSheet(1)->getStyle('C'.($ii))->getFont()->setBold(true);
                
                $excel->getActiveSheet(1)->setCellValue("H".($ii), "IMEI");
                $excel->getActiveSheet(1)->getStyle( 'H'.$ii.':I'.$ii)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $excel->getActiveSheet(1)->getStyle( 'H'.$ii.':I'.$ii)->getFill()->getStartColor()->setARGB('FF808080');
                $excel->getActiveSheet(1)->getStyle( 'H'.$ii.':I'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->getStyle('H'.($ii))->applyFromArray($styleArray1);
                $excel->getActiveSheet(1)->getStyle('H'.($ii))->getFont()->setBold(true);
                
                $excel->getActiveSheet(1)->setCellValue("K".($ii), "MOBILE NO.");
                $excel->getActiveSheet(1)->getStyle( 'K'.$ii.':L'.$ii)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $excel->getActiveSheet(1)->getStyle( 'K'.$ii.':L'.$ii)->getFill()->getStartColor()->setARGB('FF808080');
                $excel->getActiveSheet(1)->getStyle( 'K'.$ii.':L'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->getStyle('K'.($ii))->applyFromArray($styleArray1);
                $excel->getActiveSheet(1)->getStyle('K'.($ii))->getFont()->setBold(true);
                
                $excel->getActiveSheet(1)->setCellValue("N".($ii), "ICCID");
                $excel->getActiveSheet(1)->getStyle( 'N'.$ii.':S'.$ii)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $excel->getActiveSheet(1)->getStyle( 'N'.$ii.':S'.$ii)->getFill()->getStartColor()->setARGB('FF808080');
                $excel->getActiveSheet(1)->getStyle( 'N'.$ii.':S'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->getStyle('N'.($ii))->applyFromArray($styleArray1);
                $excel->getActiveSheet(1)->getStyle('N'.($ii++))->getFont()->setBold(true);
                //第二行

                $excel->getActiveSheet(1)->getRowDimension($ii)->setRowHeight(22);
                $excel->getActiveSheet(1)->setCellValueExplicit("A".($ii), $nn,PHPExcel_Cell_DataType::TYPE_STRING);
                $excel->getActiveSheet(1)->getStyle("A".$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $excel->getActiveSheet(1)->getStyle( "A".($ii))->applyFromArray($styleThinBlackBorderOutline);
                
                $excel->getActiveSheet(1)->setCellValue("C".($ii), $value['u_terminal_type']);
                $excel->getActiveSheet(1)->getStyle( 'C'.$ii.':E'.$ii)->applyFromArray($styleThinBlackBorderOutline);

                $excel->getActiveSheet(1)->setCellValueExplicit("H".($ii), $value['u_imei'],PHPExcel_Cell_DataType::TYPE_STRING);
                $excel->getActiveSheet(1)->getStyle( 'H'.$ii.':I'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                
                $excel->getActiveSheet(1)->setCellValueExplicit("K".($ii), $value['u_mobile_phone'],PHPExcel_Cell_DataType::TYPE_STRING);
                $excel->getActiveSheet(1)->getStyle( 'K'.$ii.':L'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                
                $excel->getActiveSheet(1)->setCellValueExplicit("N".($ii), $value['u_iccid'],PHPExcel_Cell_DataType::TYPE_STRING);
                $excel->getActiveSheet(1)->getStyle( 'N'.$ii.':S'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                //第三行
                $ii++;
                $excel->getActiveSheet(1)->getRowDimension($ii++)->setRowHeight(5);  
                $excel->getActiveSheet(1)->getRowDimension($ii)->setRowHeight(22);
                $excel->getActiveSheet(1)->setCellValue("A".($ii), "User ID");
                $excel->getActiveSheet(1)->getStyle('A'.$ii)->applyFromArray($styleArray2);
                $excel->getActiveSheet(1)->getStyle('A'.$ii)->getFont()->setBold(true);
                $excel->getActiveSheet(1)->setCellValueExplicit("B".($ii), $value['u_number'],PHPExcel_Cell_DataType::TYPE_STRING);
                $excel->getActiveSheet(1)->getStyle( "B".$ii)->applyFromArray($styleThinBlackBorderOutline);
                
                $excel->getActiveSheet(1)->setCellValue("C".($ii), "User Name");
                $excel->getActiveSheet(1)->getStyle('C'.$ii)->applyFromArray($styleArray2);
                $excel->getActiveSheet(1)->getStyle('C'.$ii)->getFont()->setBold(true);
                $excel->getActiveSheet(1)->setCellValueExplicit("E".($ii), $value['u_name'],PHPExcel_Cell_DataType::TYPE_STRING);
                $excel->getActiveSheet(1)->getStyle( 'E'.$ii.':G'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                
                $excel->getActiveSheet(1)->setCellValue("I".$ii, "Package");
                $excel->getActiveSheet(1)->getStyle('I'.$ii)->applyFromArray($styleArrayred);
                $excel->getActiveSheet(1)->getStyle('I'.$ii)->getFont()->setBold(true);
                $excel->getActiveSheet(1)->setCellValue("J".$ii, "Full");
                $excel->getActiveSheet(1)->getStyle('J'.$ii)->applyFromArray($styleArrayblue);
                $excel->getActiveSheet(1)->getStyle('J'.$ii)->getFont()->setBold(true);
                $excel->getActiveSheet(1)->getStyle('K'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("M".$ii, "H/W only");
                $excel->getActiveSheet(1)->getStyle('M'.$ii)->applyFromArray($styleArrayblue);
                $excel->getActiveSheet(1)->getStyle('M'.$ii)->getFont()->setBold(true);
                $excel->getActiveSheet(1)->getStyle('N'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("P".$ii, "Apps. Only");
                $excel->getActiveSheet(1)->getStyle('P'.$ii)->applyFromArray($styleArrayblue);
                $excel->getActiveSheet(1)->getStyle('P'.$ii)->getFont()->setBold(true);
                $excel->getActiveSheet(1)->getStyle('S'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                
                //内置table
                $ii++;
                $excel->getActiveSheet(1)->getRowDimension($ii++)->setRowHeight(5);
                $excel->getActiveSheet(1)->setCellValue("A".$ii, "VALUE ADDED SERVICES (please tick)");
                $excel->getActiveSheet(1)->getStyle('A'.$ii.':I'.$ii)->applyFromArray($styleArrayred);
                $excel->getActiveSheet(1)->getStyle('A'.$ii.':I'.$ii)->getFont()->setBold(true);
                $excel->getActiveSheet(1)->getStyle('A'.$ii.':I'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("J".$ii, "SUBSCRIPTION PLAN");
                $excel->getActiveSheet(1)->getStyle('J'.$ii.':K'.$ii)->applyFromArray($styleArray2);
                $excel->getActiveSheet(1)->getStyle('J'.$ii.':K'.$ii)->getFont()->setBold(true);
                $excel->getActiveSheet(1)->getStyle('J'.$ii.':K'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("L".$ii, "GROUP");
                $excel->getActiveSheet(1)->getStyle('L'.$ii.":N".$ii)->applyFromArray($styleArray2);
                $excel->getActiveSheet(1)->getStyle('L'.$ii.":N".$ii)->getFont()->setBold(true);
                $excel->getActiveSheet(1)->getStyle('L'.$ii.":N".$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("O".$ii, "REMARKS");
                $excel->getActiveSheet(1)->getStyle('O'.$ii.":S".$ii)->applyFromArray($styleArray2);
                $excel->getActiveSheet(1)->getStyle('O'.$ii.":S".$ii)->getFont()->setBold(true);
                $excel->getActiveSheet(1)->getStyle('O'.$ii.":S".$ii)->applyFromArray($styleThinBlackBorderOutline);
                $ii++;
                $excel->getActiveSheet(1)->getRowDimension($ii)->setRowHeight(25);
                $excel->getActiveSheet(1)->setCellValue("A".$ii, "Priority Call");
                $excel->getActiveSheet(1)->getStyle('A'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("B".$ii, "Console");
                $excel->getActiveSheet(1)->getStyle('B'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("C".$ii, "Video ");
                $excel->getActiveSheet(1)->getStyle('C'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("D".$ii, "Voice Call");
                $excel->getActiveSheet(1)->getStyle('D'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("E".$ii, "Picture");
                $excel->getActiveSheet(1)->getStyle('E'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("F".$ii, "GPS");
                $excel->getActiveSheet(1)->getStyle('F'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("G".$ii, "Map ");
                $excel->getActiveSheet(1)->getStyle('G'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("H".$ii, "Message");
                $excel->getActiveSheet(1)->getStyle('H'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("I".$ii, "Event Logging");
                $excel->getActiveSheet(1)->getStyle('I'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("J".$ii, "Value/Power");
                $excel->getActiveSheet(1)->getStyle('J'.$ii.':K'.($ii+1))->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->mergeCells('J'.$ii.':K'.($ii+1));
                $excel->getActiveSheet(1)->setCellValue("L".$ii, "");
                $excel->getActiveSheet(1)->getStyle('L'.$ii.':N'.($ii+1))->applyFromArray($styleThinBlackBorderOutline);
                //$excel->getActiveSheet(1)->mergeCells('L'.$ii.':N'.($ii+1));
                $excel->getActiveSheet(1)->setCellValue("O".$ii, "");
                $excel->getActiveSheet(1)->getStyle('O'.$ii.':S'.($ii+1))->applyFromArray($styleThinBlackBorderOutline);
                //$excel->getActiveSheet(1)->mergeCells('O'.$ii.':S'.($ii+1));
                $ii++;
                $excel->getActiveSheet(1)->getRowDimension($ii)->setRowHeight(25);
                $excel->getActiveSheet(1)->setCellValue("A".$ii, "");
                $excel->getActiveSheet(1)->getStyle('A'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("B".$ii, $value['u_sub_type']==2?"Y":"");
                $excel->getActiveSheet(1)->getStyle('B'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("C".$ii, strstr($value["u_p_function"],"gn_shpyw")?"Y":"");
                $excel->getActiveSheet(1)->getStyle('C'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("D".$ii, strstr($value["u_p_function"],"gn_yythkt")?"Y":"");
                $excel->getActiveSheet(1)->getStyle('D'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("E".$ii, strstr($value["u_p_function"],"gn_tppch")?"Y":"");
                $excel->getActiveSheet(1)->getStyle('E'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("F".$ii, strstr($value["u_p_function"],"gn_gps")?"Y":"");
                $excel->getActiveSheet(1)->getStyle('F'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("G".$ii, strstr($value["u_p_function"],"gn_djdtmsh")?"Y":"");
                $excel->getActiveSheet(1)->getStyle('G'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("H".$ii, strstr($value["u_p_function"],"gn_dxx")?"Y":"");
                $excel->getActiveSheet(1)->getStyle('H'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("I".$ii, "");
                $excel->getActiveSheet(1)->getStyle('I'.$ii)->applyFromArray($styleThinBlackBorderOutline);
                $excel->getActiveSheet(1)->setCellValue("J".$ii, "");
                
                $ii++;
                $excel->getActiveSheet(1)->getRowDimension($ii)->setRowHeight(5);
                $ii++;
               $nn++; 
            }
            
            //$excel->getActiveSheet(1)->getStyle('A1')->getBorders()->getLeft()->getColor()->setARGB('FF993300');
            
            //增加一行空白行
        }
        $output = new PHPExcel_Writer_Excel5($excel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check = 0, pre-check = 0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="' . $filename . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $output->save('php://output');
    }

}
