<?php

/**
 * 企业用户控制器
 * @category OMP
 * @package OMP_Enterprise_contorl
 * @require {@see contorl} {@see page} {@see enterprise} {@see users}
 */
class EnterpriseUsersContorl extends contorl {

	public $enterprise;
	public $user;
	public $tools;
	public $groups;
	public $pttmember;
	public $warn;
	public $error;

	/**
	 * 用户导入
	 */
	public function importShellUser() {
		$step = is_string($_REQUEST['step']) ? $_REQUEST['step'] : '';
		if ($step === 'if') {
			$msg = $this->importUserFile();
			print "<script>parent.u_if_callback(" . $msg . ")</script>";
			exit;
		}
		if ($step === 'ic') {
			try
			{
				$f = $this->importUserCheck();
				if (count($this->error) > 0) {
					$json['status'] = -1;
					$json['msg'] = '存在错误无法导入<br />';
				} else {
					$json['status'] = 0;
					$json['msg'] = '无严重错误<br />';
				}
				$json['msg'] .= '<div class="show">';
				$json['msg'] .= implode('<br />', $this->error);
				$json['msg'] .= "<hr />";
				$json['msg'] .= implode('<br />', $this->warn);
				$json['msg'] .= '</div>';

				$json['data'] = $f;
				$msg = json_encode($json);
			} catch (Exception $ex) {
				$json['status'] = -1;
				$json['msg'] = $ex->getMessage();
				$msg = json_encode($json);
			}
			print "<script>parent.u_ic_callback(" . $msg . ")</script>";
			exit;
		}
		if ($step === 'i') {
			try
			{
				$this->importUser();

				if (count($this->error) > 0) {
					$json['status'] = -1;
					$json['msg'] = '存在错误';
					$json['msg'] .= '<div class="show">';
					$json['msg'] .= implode('<br />', $this->error);
					$json['msg'] .= '</div>';
				} else {
					$json['status'] = 0;
					$json['msg'] = '没有发现错误，导入完成';
				}

				$msg = json_encode($json);
			} catch (Exception $ex) {
				$json['status'] = -1;
				$json['data'] = $ex->getMessage();
				$msg = json_encode($json);
			}
			print "<script>parent.u_i_callback(" . $msg . ")</script>";
			exit;
		}
	}

	/**
	 * 用户导入检查
	 * @return string
	 * @throws Exception
	 */
	private function importUserCheck() {
		$f = filter_input(INPUT_GET, 'f');
		$e_id = filter_input(INPUT_GET, 'e_id');
		$file = $f . '.xls';
		$config = Cof::config();
		$filePath = $config['system']['webroot'] . DIRECTORY_SEPARATOR . "runtime" . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . $file;
		$objReader = PHPExcel_IOFactory::createReader('Excel5');

		$objPHPExcel = $objReader->load($filePath);
		$objWorksheet = $objPHPExcel->getSheet(0);

		$highestColumn = $objWorksheet->getHighestColumn();
		$highestRow = $objWorksheet->getHighestRow(); //取得总行数
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); //总列数

		if ($highestColumnIndex > 26) {
			throw new Exception("总列数超出", -1);
		}

		$productXLS = array();
		$userGroupXLS = array();
		// 彩信号码，调度台号码错误检测
		$userNumberAlarmXLS = array();
		$userNumber = array();

		for ($row = 2; $row <= $highestRow; $row++) {
			// 如果产品或部门名称不为空则将产品部门名称加入数组进行计算
			$tmpProductname = trim($objWorksheet->getCellByColumnAndRow(4, $row)->getValue());
			if ($tmpProductname != '') {
				$productXLS[] = $tmpProductname;
			}
			$tmpUserGroupname = trim($objWorksheet->getCellByColumnAndRow(3, $row)->getValue());
			if ($tmpUserGroupname != '') {
				$userGroupXLS[] = $tmpUserGroupname;
			}
			$tmpUserNumber = trim($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
			if ($tmpUserNumber != '') {
				$userNumber[] = $tmpUserNumber;
			}

			// 彩信号码，与调度台号码
			$userNumberAlarmA = trim($objWorksheet->getCellByColumnAndRow(7, $row)->getValue());
			if ($userNumberAlarmA != '') {
				$userNumberAlarmXLS[] = $userNumberAlarmA;
			}
			$userNumberAlarmB = trim($objWorksheet->getCellByColumnAndRow(8, $row)->getValue());
			if ($userNumberAlarmB != '') {
				$userNumberAlarmXLS[] = $userNumberAlarmB;
			}
		}
		/** 产品的数据计算* */
		$productXSL = array_unique($productXSL);
		$product = new product();
		$tmpProductName = $product->getByProductName($productXSL);

		$productName = array(); // 产品名称
		foreach ($tmpProductName as $value) {
			$productName[] = $value['p_name'];
		}
		$productNameDiff = array_diff($productXSL, $productName);
		if (count($productNameDiff) > 0) {
			throw new Exception("以下产品名称系统中不存在。" . implode(',', $productNameDiff), -1);
		}

		/** 部门的数据计算* */
		$userGroupXSL = array_unique($userGroupXSL);
		$userGroup = new usergroup(array("e_id" => $e_id));
		$tmpUserGroup = $userGroup->selectlist();
		$userGroupName = array(); // 部门名称
		foreach ($tmpUserGroup as $value) {
			$userGroupName[] = $value['ug_name'];
		}
		$userGroupNameDiff = array_diff($userGroupXSL, $userGroupName);
		if (count($userGroupNameDiff) > 0) {
			throw new Exception("以下部门名称系统中不存在。" . implode(',', $userGroupNameDiff), -1);
		}
		/** 用户号码不能重复 */
		$userNumberUnique = array_unique($userNumber);

		if (count($userNumberUnique) != count($userNumber)) {
			throw new Exception('用户号码存在重复', -1);
		}
		/** 彩信报警号码存在检测 */
		$userNumberAlarmXLS = array_unique($userNumberAlarmXLS);
		$user = new users(array("e_id" => $e_id));
		$tmpUserNumberAlarmReal = $user->shelluser();
		$userNumberAlarmReal = array();
		// 从结果集中获取号码
		foreach ($tmpUserNumberAlarmReal as $value) {
			$userNumberAlarmReal[] = $value['u_number'];
		}
		//过滤数据
		$userNumberAlarmRealDiff = array_diff($userNumberAlarmXLS, $userNumberAlarmReal);
		if (count($userNumberAlarmRealDiff) > 0) {
			throw new Exception("调度台告警号码或彩信号码不存在。" . implode(',', $userNumberAlarmRealDiff), -1);
		}

		// 开始进行数据格式校验
		// 警告
		$warn = array();
		// 错误
		$error = array();

		for ($row = 2; $row <= $highestRow; $row++) {

			$u_name = trim($objWorksheet->getCellByColumnAndRow(1, $row)->getValue());
			$u_passwd = trim($objWorksheet->getCellByColumnAndRow(2, $row)->getValue());
			$u_audio_rec = trim($objWorksheet->getCellByColumnAndRow(5, $row)->getValue());
			$u_video_rec = trim($objWorksheet->getCellByColumnAndRow(6, $row)->getValue());
			$u_auto_config = trim($objWorksheet->getCellByColumnAndRow(9, $row)->getValue());
			$u_udid = trim($objWorksheet->getCellByColumnAndRow(10, $row)->getValue());
			$u_imsi = trim($objWorksheet->getCellByColumnAndRow(11, $row)->getValue());
			$u_imei = trim($objWorksheet->getCellByColumnAndRow(12, $row)->getValue());
			$u_iccid = trim($objWorksheet->getCellByColumnAndRow(13, $row)->getValue());
			$u_mac = trim($objWorksheet->getCellByColumnAndRow(14, $row)->getValue());
			$u_auto_run = trim($objWorksheet->getCellByColumnAndRow(15, $row)->getValue());
			$u_checkup_grade = trim($objWorksheet->getCellByColumnAndRow(16, $row)->getValue());
			$u_encrypt = trim($objWorksheet->getCellByColumnAndRow(17, $row)->getValue());
			$u_audio_mode = trim($objWorksheet->getCellByColumnAndRow(18, $row)->getValue());
			$u_gis_mode = trim($objWorksheet->getCellByColumnAndRow(19, $row)->getValue());
			$u_sex = trim($objWorksheet->getCellByColumnAndRow(20, $row)->getValue());
			$u_position = trim($objWorksheet->getCellByColumnAndRow(21, $row)->getValue());
			$u_terminal_type = trim($objWorksheet->getCellByColumnAndRow(22, $row)->getValue());
			$u_terminal_model = trim($objWorksheet->getCellByColumnAndRow(23, $row)->getValue());
			$u_zm = trim($objWorksheet->getCellByColumnAndRow(24, $row)->getValue());

			$u_numbe = trim($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
			if (!isPhone($u_numbe)) {
				$error[] = "第 $row 行 用户号码 $u_numbe 不是手机号";
			}

			if (!Cof::isChinese($u_name, 128)) {
				$error[] = "第 $row 行 用户名称 $u_name 不是中文英文数字组成的";
			}
			if (Cof::re('/^([a-zA-Z0-9]+)$/', $u_passwd, 64) === 0) {
				$error[] = "第 $row 行 用户密码 $u_passwd 不是字母数字组成的";
			}
			if (Cof::re('/[01]/', $u_audio_rec) === 0) {
				if ($u_audio_rec == '') {
					$warn[] = "第 $row 行 第 $row 行 录音为空";
				} else {
					$error[] = "第 $row 行 录音 $u_audio_rec 不正确，不是1，0组成的";
				}
			}
			if (Cof::re('/[01]/', $u_video_rec) === 0) {
				if ($u_video_rec == '') {
					$warn[] = "第 $row 行 第 $row 行 录像为空";
				} else {
					$error[] = "第 $row 行 录像 $u_video_rec 不正确，不是1，0组成的";
				}
			}
			if (Cof::re('/[01]/', $u_auto_config) === 0) {
				if ($u_auto_config == '') {
					$warn[] = "第 $row 行 自动登录开关为空";
				} else {
					$error[] = "第 $row 行 自动登录开关 $u_auto_config 不正确，不是1，0组成的";
				}
			}

			if ($u_auto_config === '1') {
				// UDID
				if (Cof::re('/^\s*$|^(?!(?:\d+|[a-zA-Z]+)$)[\da-zA-Z]{40}$/i', $u_udid, 40) === 0) {
					if ($u_udid == '') {
						$warn[] = "第 $row 行 UDID 为空";
					} else {
						$error[] = "第 $row 行 UDID $u_udid 不正确";
					}
				}
				// IMSI
				if (Cof::re('/^\s*$|^[0-9]{15}$/i', $u_imsi, 15) === 0) {
					if ($u_imsi == '') {
						$warn[] = "第 $row 行 IMSI 为空";
					} else {
						$error[] = "第 $row 行 IMSI $u_imsi 不正确";
					}
				}
				// IMEI
				if (Cof::re('/^\s*$|^[0-9]{15}$/i', $u_imei, 15) === 0) {
					if ($u_imei == '') {
						$warn[] = "第 $row 行 IMEI 为空";
					} else {
						$error[] = "第 $row 行 IMEI $u_imei 不正确";
					}
				}
				// ICCID
				if (Cof::re('/^\s*$|^\d{19}$|^\d{20}$/i', $u_iccid, 20) === 0) {
					if ($u_iccid == '') {
						$warn[] = "第 $row 行 ICCID 为空";
					} else {
						$error[] = "第 $row 行 ICCID $u_iccid 不正确";
					}
				}

				//验证iccid、imsi、手机号  开始
                $u_number = $u_numbe;
                $this->data['u_iccid'] = $u_iccid;
                $this->data['u_mobile_phone'] = $u_number;
                $this->data['u_imsi'] = $u_imsi;

                $this->enterprise->set($this->data);

                $iccid_res = $this->gprs->check_iccid();
                $imsi_res = $this->gprs->check_u_imsi();
                $number_res = $this->gprs->check_u_mobile();
                
                /*验证iccid*/
                if($iccid_res['status']==2){
                    $error[] = "第 $row 行 ICCID $u_iccid 此ICCID已被绑定"; 
                }else if($iccid_res['status']==4){
                //如果填写的iccid存在，并适用,自动填充imsi,手机号 以及对应判断
                    if($u_imsi==''){
                        $u_imsi = $iccid_res['info']['g_imsi'];
                    }else{
                        if($iccid_res['info']['g_imsi']!='' && $iccid_res['info']['g_imsi']!=$u_imsi){
                            $error[] = "第 $row 行 ICCID $u_iccid IMSI与ICCID不匹配"; 
                        }
                    }

                    if($u_number==''){
                        $u_number = $iccid_res['info']['g_number'];
                    }else{
                        if($iccid_res['info']['g_number']!='' && $iccid_res['info']['g_number']!=$u_number){
                            $error[] = "第 $row 行 ICCID $u_iccid 手机号与ICCID不匹配";
                        }
                    }
                }else if($iccid_res['status']==3){
                    $error[] = "第 $row 行 ICCID $u_iccid 该ICCID所绑代理不是父级代理";
                }

                /*验证imsi*/
                if($imsi_res['status']==2){
                    $error[] = "第 $row 行 IMSI $u_imsi 此IMSI已被绑定"; 
                }else if($imsi_res['status']==4){
                //如果填写的iccid存在，并适用,自动填充imsi,手机号 以及对应判断
                    if($u_iccid==''){
                        $u_iccid = $imsi_res['info']['g_iccid'];
                    }else{
                        if($imsi_res['info']['g_iccid']!='' && $imsi_res['info']['g_iccid']!=$u_iccid){
                            $error[] = "第 $row 行 IMSI $u_imsi ICCID与IMSI不匹配"; 
                        }
                    }

                    if($u_number==''){
                        $u_number = $imsi_res['info']['g_number'];
                    }else{
                        if($imsi_res['info']['g_number']!='' && $imsi_res['info']['g_number']!=$u_number){
                            $error[] = "第 $row 行 IMSI $u_imsi 手机号与IMSI不匹配";
                        }
                    }
                }else if($iccid_res['status']==3){
                    $error[] = "第 $row 行 IMSI $u_imsi 该IMSI所绑代理不是父级代理";
                }

                /*验证手机号u_mobile_phone*/
                if($number_res['status']==2){
                    $error[] = "第 $row 行 手机号 $u_number 此手机号已被绑定"; 
                }else if($number_res['status']==4){
                //如果填写的iccid存在，并适用,自动填充imsi,手机号 以及对应判断
                    if($u_iccid==''){
                        $u_iccid = $number_res['info']['g_iccid'];
                    }else{
                        if($number_res['info']['g_iccid']!='' && $number_res['info']['g_iccid']!=$u_iccid){
                            $error[] = "第 $row 行 手机号 $u_number ICCID与手机号不匹配"; 
                        }
                    }

                    if($u_imsi==''){
                        $u_imsi = $number_res['info']['g_imsi'];
                    }else{
                        if($number_res['info']['g_imsi']!='' && $number_res['info']['g_imsi']!=$u_imsi){
                            $error[] = "第 $row 行 手机号 $u_number IMSI与手机号不匹配"; 
                        }
                    }
                }else if($number_res['status']==3){
                    $error[] = "第 $row 行 手机号 $u_number 该手机号所属流量卡绑的代理不是父级代理";
                }
                //----------END----------
				// MAC
				if (Cof::re('/^\s*$|[A-F\d]{2}[A-F\d]{2}[A-F\d]{2}[A-F\d]{2}[A-F\d]{2}[A-F\d]{2}/i', $u_mac, 20) === 0) {
					if ($u_mac == '') {
						$warn[] = "第 $row 行 MAC 为空";
					} else {
						$error[] = "第 $row 行 MAC $u_mac 不正确";
					}
				}
				// 开机启动
				if (Cof::re('/[01\s]/', $u_auto_run, 20) === 0) {
					if ($u_auto_run == '') {
						$warn[] = "第 $row 行 开机启动为空";
					} else {
						$error[] = "第 $row 行 开机启动 $u_auto_run 不正确";
					}
				}
				// 检查更新
				if (Cof::re('/[\s01]/', $u_checkup_grade, 20) === 0) {
					if ($u_checkup_grade == '') {
						$warn[] = "第 $row 行 检查更新为空";
					} else {
						$error[] = "第 $row 行 检查更新 $u_checkup_grade 不正确";
					}
				}
				// 信令加密
				if (Cof::re('/[01\s]/', $u_encrypt, 20) === 0) {
					if ($u_encrypt == '') {
						$warn[] = "第 $row 行 信令加密 为空";
					} else {
						$error[] = "第 $row 行 信令加密 $u_encrypt 不正确";
					}
				}
				// 语音通话方式
				if (Cof::re('/[01\s]/', $u_audio_mode, 20) === 0) {
					if ($u_audio_mode == '') {
						$warn[] = "第 $row 行 语音通话 为空";
					} else {
						$error[] = "第 $row 行 语音通话 $u_audio_mode 不正确";
					}
				}
				// GPS定位上报方式
				if (Cof::re('/[01234\s]/', $u_gis_mode, 20) === 0) {
					if ($u_gis_mode == '') {
						$warn[] = "第 $row 行 GPS定位方式 为空";
					} else {
						$error[] = "第 $row 行 GPS定位方式 $u_gis_mode 不正确";
					}
				}
			}
			// 性别
			if ($u_sex !== 'M' && $u_sex !== 'F') {
				$warn[] = "第 $row 行 性别 $u_sex 为空或者值不正确";
			}
			//职位
			if (Cof::re('/^([\u4e00-\u9fa5]|[a-zA-Z0-9\#\-\.\(\)\（\） \_\.])+$/', $u_position, 64) === 0) {
				$error[] = "第 $row 行 职位 $u_position 不正确";
			}
			//终端类型
			if (Cof::re('/[\sA-Za-z0-9]/', $u_terminal_type, 32) === 0) {
				$error[] = "第 $row 行 终端类型 $u_terminal_type 不正确";
			}
			//机型
			if (Cof::re('/[\sA-Za-z0-9]/', $u_terminal_model, 32) === 0) {
				$error[] = "第 $row 行 机型 $u_terminal_model 不正确";
			}
			//蓝牙标示符
			if (Cof::re('/[\sA-Za-z0-9]/', $u_zm, 64) === 0) {
				$error[] = "第 $row 行 蓝牙标示符 $u_zm 不正确";
			}
		}
		$this->warn = $warn;
		$this->error = $error;
		return $f;
	}

	// 导入文件
	private function importUserFile() {
		$json = array();
		try
		{
			$file = Cof::upload();
			$json['status'] = 0;
			$json['data'] = str_replace('.xls', '', $file); //清除后缀信息
		} catch (Exception $ex) {
			$json['status'] = -1;
			$json['msg'] = $ex->getMessage();
		}
		return json_encode($json);
                  }   

	// 数据导入
	private function importUser() {
		$e_id = filter_input(INPUT_GET, 'e_id');
		$f = filter_input(INPUT_GET, 'f');

		//$f = '1CDF3367-0CCD-B1BA-9C24-0F29164726F9';
		$data['e_id'] = $e_id;

		$users = new users($data);

		$file = $f . '.xls';
		$config = Cof::config();
		$filePath = $config['system']['webroot'] . DIRECTORY_SEPARATOR . "runtime" . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . $file;
		$objReader = PHPExcel_IOFactory::createReader('Excel5');

		$objPHPExcel = $objReader->load($filePath);
		$objWorksheet = $objPHPExcel->getSheet(0);

		//$highestColumn = $objWorksheet->getHighestColumn();
		$highestRow = $objWorksheet->getHighestRow(); //取得总行数
		//$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); //总列数
		// 实际数据读取，数据导入

		$error = array();
		for ($row = 2; $row <= $highestRow; $row++) {
			//用户数据体
			$userData = array();
			$userData['e_id'] = $e_id;
			$userData['u_sub_type'] = 1;
			$userData['u_name'] = trim($objWorksheet->getCellByColumnAndRow(1, $row)->getValue());
			$userData['u_passwd'] = trim($objWorksheet->getCellByColumnAndRow(2, $row)->getValue());
			$userData['u_audio_rec'] = trim($objWorksheet->getCellByColumnAndRow(5, $row)->getValue());
			if ($userData['u_audio_rec'] == '') {
				$userData['u_audio_rec'] = 0;
			}
			$userData['u_video_rec'] = trim($objWorksheet->getCellByColumnAndRow(6, $row)->getValue());
			$userData['u_auto_config'] = trim($objWorksheet->getCellByColumnAndRow(9, $row)->getValue());
			$userData['u_udid'] = trim($objWorksheet->getCellByColumnAndRow(10, $row)->getValue());
			$userData['u_imsi'] = trim($objWorksheet->getCellByColumnAndRow(11, $row)->getValue());
			$userData['u_imei'] = trim($objWorksheet->getCellByColumnAndRow(12, $row)->getValue());
			$userData['u_iccid'] = trim($objWorksheet->getCellByColumnAndRow(13, $row)->getValue());
			$userData['u_mac'] = trim($objWorksheet->getCellByColumnAndRow(14, $row)->getValue());
			$userData['u_auto_run'] = trim($objWorksheet->getCellByColumnAndRow(15, $row)->getValue());
			if ($userData['u_auto_run'] == '') {
				$userData['u_auto_run'] = 0;
			}

			$userData['u_checkup_grade'] = trim($objWorksheet->getCellByColumnAndRow(16, $row)->getValue());
			if ($userData['u_checkup_grade'] == '') {
				$userData['u_checkup_grade'] = 1;
			}

			$userData['u_encrypt'] = trim($objWorksheet->getCellByColumnAndRow(17, $row)->getValue());
			if ($userData['u_encrypt'] == '') {
				$userData['u_encrypt'] = 0;
			}

			$userData['u_audio_mode'] = trim($objWorksheet->getCellByColumnAndRow(18, $row)->getValue());

			if ($userData['u_audio_mode'] == '') {
				$userData['u_audio_mode'] = 0;
			}

			$userData['u_gis_mode'] = trim($objWorksheet->getCellByColumnAndRow(19, $row)->getValue());
			if ($userData['u_gis_mode'] == '') {
				$userData['u_gis_mode'] = 0;
			}

			$userData['u_sex'] = trim($objWorksheet->getCellByColumnAndRow(20, $row)->getValue());
			$userData['u_position'] = trim($objWorksheet->getCellByColumnAndRow(21, $row)->getValue());
			$userData['u_terminal_type'] = trim($objWorksheet->getCellByColumnAndRow(22, $row)->getValue());
			$userData['u_terminal_model'] = trim($objWorksheet->getCellByColumnAndRow(23, $row)->getValue());
			$userData['u_zm'] = trim($objWorksheet->getCellByColumnAndRow(24, $row)->getValue());
			$userData['u_number'] = trim($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());

			$userData['u_ug_id'] = 0;
			$userData['u_product_id'] = 0;
			$userData['status'] = 0;
			$users->set($userData);
			try
			{
				$users->save();
			} catch (Exception $ex) {
				$error[] = $ex->getMessage();
			}
		}

		$this->error = $error;
	}

	public function __construct() {
		parent::__construct();
		$this->enterprise = new enterprise($_REQUEST);
		$this->tools = new tools();
		$this->groups = new groups($_REQUEST);
		$this->gprs = new gprs($_REQUEST);
		$this->pttmember = new pttmember($_REQUEST);
        //列表页分条数显示
        if($_REQUEST['user_num']){
            $_SESSION['user_page_num'] = $_REQUEST['user_num'];
        }
        if($_SESSION['user_page_num']){
            $_REQUEST['num'] = $_SESSION['user_page_num'];
            
        }
        $this->user = new users($_REQUEST);
        $this->page = new page($_REQUEST);
	}

	function users_batch_ug() {
		if (!empty($_REQUEST['checkbox'])) {
			$this->enterprise->changeSync(true, 28);
		}
		$users = new users($_REQUEST);
		$usergroup = new usergroup($_REQUEST);
		$ug_name = $usergroup->getselectinfo($_REQUEST['u_ug_id']);
		$ug_name = $ug_name[0]['ug_name'];
		foreach ($_REQUEST['checkbox'] as $value) {
			$data['e_id'] = $_REQUEST['e_id'];
			$data['u_ug_id'] = $_REQUEST['u_ug_id'];
			$data['u_number'] = $value;
			$data['ug_name'] = $ug_name;
			$user_name = $users->hasUser($value);

			$data['u_name'] = $user_name['u_name'];
			$this->user->set($data);
			$this->user->batchUser_ug();
		}
	}

	/**
	 * 企业用户显示
	 */
	public function shelluser() {
		$result = $this->user->shelluser();
		$this->smarty->assign("list", $result);
		$this->htmlrender('modules/enterprise/shelluser.tpl');
	}

	/**
	 * 全部企业用户搜索后台接口
	 * @return type
	 */
	public function all_user_item() {
		$data = $_REQUEST;
                                   $this->page->setTotal($this->user->getTotal());
		$alllist['list'] = $this->user->getUserList($this->page->getLimit());
                                   $numinfo = $this->page->getNumInfo();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		foreach ($alllist['list'] as $key => $value) {
			$data['e_id'] = $value['u_e_id'];
			$this->enterprise->set($data);
			$data = $this->enterprise->getByid();
			$alllist['list'][$key]['ep'] = $data;
		}
                                   $this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->smarty->assign('list', $alllist['list']);
		$this->htmlrender('modules/enterprise/allusers_item.tpl');
	}

    /**
     * 全部企业用户搜索显示层
     */
    public function allusers() {
         if($_SESSION['ident']=='VT'){
             $this->render('modules/enterprise/allusers_vt.tpl', L('用户搜索'));
        }else if($_SESSION['ident']=='GQT'){
             $this->render('modules/enterprise/allusers.tpl', L('用户搜索'));
        }else{
             $this->render('modules/enterprise/allusers_vt.tpl', L('用户搜索'));
        }
           
    }

	/**
	 * 企业用户保存用户数检查
	 * @param type $num
	 * @throws Exception
	 */
	public function saveUserVerify($num = 1) {
		$edit = FALSE;
		$item_e = $this->enterprise->getByid();
		$info = $this->user->getById();
		$usernum = $this->user->getTotal(FALSE);
		$phone_num = $this->user->getusertotal(1);
		$dispatch_num = $this->user->getusertotal(2);
		$gvs_num = $this->user->getusertotal(3);
		$flag = $item_e['e_mds_users'] - ($usernum + $num);
		if ($_REQUEST['do'] == 'edit') {
			$edit = TRUE;
			if ($_REQUEST['u_sub_type'] == 1 && $info['u_sub_type'] != 1) {
				if ($item_e['e_mds_phone'] - ($phone_num + $num) < 0) {
					throw new Exception(L('手机用户数超过该企业手机用户总数'), 0);
				}
			}
			if ($_REQUEST['u_sub_type'] == 2 && $info['u_sub_type'] != 2) {
				if ($item_e['e_mds_dispatch'] - ($dispatch_num + $num) < 0) {
					throw new Exception(L('调度台用户数超过该企业调度台用户总数'), 0);
				}
			}
			if ($_REQUEST['u_sub_type'] == 3 && $info['u_sub_type'] != 3) {
				if ($item_e['e_mds_gvs'] - ($gvs_num + $num) < 0) {
					throw new Exception(L('GVS用户数超过该企业GVS用户总数'), 0);
				}
			}
		} else {
			if ($_REQUEST['u_sub_type'] == 1) {
				if ($item_e['e_mds_phone'] - ($phone_num + $num) < 0) {
					throw new Exception(L('手机用户数超过该企业手机用户总数'), 0);
				}
			}
			if ($_REQUEST['u_sub_type'] == 2) {
				if ($item_e['e_mds_dispatch'] - ($dispatch_num + $num) < 0) {
					throw new Exception(L('调度台用户数超过该企业调度台用户总数'), 0);
				}
			}
			if ($_REQUEST['u_sub_type'] == 3) {
				if ($item_e['e_mds_gvs'] - ($gvs_num + $num) < 0) {
					throw new Exception(L('GVS用户数超过该企业GVS用户总数'), 0);
				}
			}
		}

		if (!$edit) {
			if ($flag < 0) {
				throw new Exception(L('企业用户数超过'.$_SESSION['ident'].'-Server用户数'), 0);
			}
		}
	}

	/**
	 * 用户头像显示
	 */
	function users_face_item() {
		$pic = new pic($_REQUEST);
		$pic->show();
	}

	/**
	 * 用户头像保存
	 */
	function users_face() {
		$pic = new pic($_REQUEST);
		try
		{
			$result['msg'] = $pic->getId();
			$result['status'] = 0;
		} catch (Exception $ex) {
			$result['msg'] = $ex->getMessage();
			$result['status'] = -1;
		}
		$result = json_encode($result);
		print <<<RESULT
                <script>parent.callback($result);</script>
RESULT;
	}

/**
 * 企业用户列表
 */
function users() {
        $data = $this->enterprise->getByid();
        $mininav = array(
                array(
                        "url" => "?m=enterprise&a=index",
                        "name" => "企业管理",
                        "next" => ">>",
                ),
                array(
                        "url" => "?m=enterprise&a=view&e_id=" . $_REQUEST["e_id"],
                        "name" => $data["e_name"] . " - " . L("企业用户"),
                        "next" => "",
                ),
        );
        //列表页分条数 选中的显示相应颜色
        if($_REQUEST['num']){
            unset($_SESSION['color']);
            $_SESSION['color'][$_REQUEST['num']] = 'style="background:#E5E5E5"';
        }elseif($_SESSION['user_page_num']){
            unset($_SESSION['color']);
            $_SESSION['color'][$_SESSION['user_page_num']] = 'style="background:#E5E5E5"';
        }else{
            unset($_SESSION['color']);
            $_SESSION['color'][10] = 'style="background:#E5E5E5"';
        }
        $lists = $this->user->getList();
        //$list = $this->user->getList ( $this->page->getLimit () );

		$product = new product($_REQUEST);
		$result = $product->getList();

		foreach ($lists as $value) {

			if ($value["p_area"] != NULL) {
				if (!strpos($value["p_area"], "#")) {
					if (!strpos($value["p_area"], $data['e_area'])) {

						try
						{
							$this->user->update_pid($value['u_number'], $value['u_e_id']);
						} catch (Exception $exc) {
							echo $exc->getCode();
						}
					}
				}
			}
		}
		$this->smarty->assign('data', $data);
		$this->smarty->assign('ep', $data);
		$this->smarty->assign('page', $_REQUEST['page']);
		$this->smarty->assign('mininav', $mininav);
                
		 if($_SESSION['ident']=='VT'){
                                        $this->render('modules/enterprise/users_vt.tpl', L('企业用户'));
                                    }else if($_SESSION['ident']=='GQT'){
                                        $this->render('modules/enterprise/users.tpl', L('企业用户'));
                                    }else{
                                        $this->render('modules/enterprise/users_vt.tpl', L('企业用户'));
                                    }
	}

/**
 * 企业用户编辑保存
 */
function users_save() {
        $data = $this->enterprise->getByid();
        if ($_REQUEST['do'] != "edit") {
                $mininav = array(
                        array(
                                "url" => "?m=enterprise&a=index",
                                "name" => "企业管理",
                                "next" => ">>",
                        ),
                        array(
                                "url" => "?m=enterprise&a=users&e_id=" . $_REQUEST["e_id"],
                                "name" => $data["e_name"] . " - " . L("企业用户"),
                                "next" => ">>",
                        ),
                        array(
                                "url" => "#",
                                "name" => "新增企业用户",
                                "next" => "",
                        ),
                );
        } else {
                $mininav = array(
                        array(
                                "url" => "?m=enterprise&a=index",
                                "name" => "企业管理",
                                "next" => ">>",
                        ),
                        array(
                                "url" => "?m=enterprise&a=users&e_id=" . $_REQUEST["e_id"],
                                "name" => $data["e_name"] . " - " . L("企业用户"),
                                "next" => ">>",
                        ),
                        array(
                                "url" => "#",
                                "name" => "编辑企业用户",
                                "next" => "",
                        ),
                );
        }
        $this->smarty->assign('mininav', $mininav);
        if ($_REQUEST['do'] == 'edit') {
                $item = $this->user->getById($_REQUEST);
                 if($item["u_p_function_new"]!="noselected"){
                    $u_p_function_new=implode(",",json_decode($item["u_p_function_new"]));
                }else{
                    $u_p_function_new=$item["u_p_function_new"];
                }
                $this->smarty->assign('item', $item);
                $this->smarty->assign('u_p_function_new', $u_p_function_new);
                $this->smarty->assign('data', $_REQUEST);
                $this->smarty->assign('page', $_REQUEST['page']);
                if($_SESSION['ident']=='VT'){
                   $this->render('modules/enterprise/users_save_vt.tpl', L('编辑企业用户'));
                }else if($_SESSION['ident']=='GQT'){
                    $this->render('modules/enterprise/users_save.tpl', L('编辑企业用户'));
                }else{
                    $this->render('modules/enterprise/users_save_vt.tpl', L('编辑企业用户'));
                }
                
        } else {
                $this->smarty->assign('data', $_REQUEST);
                 if($_SESSION['ident']=='VT'){
                    $this->render('modules/enterprise/users_save_vt.tpl', L('新增企业用户'));
                 }else if($_SESSION['ident']=='GQT'){
                     $this->render('modules/enterprise/users_save.tpl', L('新增企业用户'));
                 }else{
                     $this->render('modules/enterprise/users_save_vt.tpl', L('新增企业用户'));
                 }
        }
}

	/**
	 * 批量新增企业用户显示层
	 */
	function users_auto_save() {
		$data = $this->enterprise->getByid();
		$phone_num = $this->user->getusertotal(1);
		$dispatch_num = $this->user->getusertotal(2);
		$gvs_num = $this->user->getusertotal(3);
		$this->smarty->assign('phone_num', $phone_num);
		$this->smarty->assign('dispatch_num', $dispatch_num);
		$this->smarty->assign('gvs_num', $gvs_num);
		$mininav = array(
			array(
				"url" => "?m=enterprise&a=index",
				"name" => "企业管理",
				"next" => ">>",
			),
			array(
				"url" => "?m=enterprise&a=users&e_id=" . $_REQUEST["e_id"],
				"name" => $data['e_name'] . " - " . L("企业用户"),
				"next" => ">>",
			),
			array(
				"url" => "#",
				"name" => "批量新增企业用户",
				"next" => "",
			),
		);

		$this->smarty->assign('mininav', $mininav);
		$this->smarty->assign('data', $_REQUEST);
		$this->smarty->assign('list', $data);
                                if($_SESSION['ident']=='VT'){
                                    $this->render('modules/enterprise/users_auto_save_vt.tpl', L('批量新增企业用户'));
                                }else if($_SESSION['ident']=='GQT'){
                                    $this->render('modules/enterprise/users_auto_save.tpl', L('批量新增企业用户'));
                                }else{
                                    $this->render('modules/enterprise/users_auto_save_vt.tpl', L('批量新增企业用户'));
                                }
	}

	/**
	 * 企业用户列表后台接口
	 * @return html_table 企业用户列表
	 */
	function users_item() {
		$item_e = $this->enterprise->getByid();
		$this->page->setTotal($this->user->getTotal());
		$list = $this->user->getList($this->page->getLimit());
		$maxpage = $this->page->getPages();
		foreach ($list as $val) {
			$pg_list[$val['u_number']] = $this->groups->getuserPgname($val['u_number'],$val['u_default_pg']);
		}

		$numinfo = $this->page->getNumInfo();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		$this->smarty->assign('list', $list);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('pg_list', $pg_list);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->smarty->assign('maxpage', $maxpage);
		$this->smarty->assign('data', $_REQUEST);
		$this->smarty->assign('session', $_SESSION);
		$this->smarty->assign('item_e', $item_e);
		$this->smarty->assign('page', $_REQUEST['page']);
		if ($_REQUEST['type'] == 'append') {
			$this->htmlrender('modules/enterprise/users_item.append.tpl');
		} else {
			 if($_SESSION['ident']=='VT'){
                                               $this->htmlrender('modules/enterprise/users_item_vt.tpl');
                                           }else if($_SESSION['ident']=='GQT'){
                                               $this->htmlrender('modules/enterprise/users_item.tpl');
                                           }else{
                                               $this->htmlrender('modules/enterprise/users_item_vt.tpl');
                                           }
		}
	}

        public function users_history(){
                $_REQUEST['u_number']=$_REQUEST['uh_u_number'];
                $this->user->set($_REQUEST);
                $info=$this->user->getById_history();
                $mininav = array(
                    array(
                        "url" => "?m=enterprise&a=index",
                        "name" => "企业管理",
                        "next" => ">>",
                    ),
                    array(
                        "url" => "?m=enterprise&a=users&e_id=" . $_REQUEST["e_id"],
                        "name" => $info["e_name"] . " - " . L("企业用户"),
                        "next" => ">>",
                    ),
                    array(
                        "url" => "#",
                        "name" => "历史记录",
                        "next" => "",
                    ),
                );
                $this->smarty->assign('mininav', $mininav);
                $this->smarty->assign('data', $info);
                $this->render('modules/enterprise/users_history.tpl',L("历史记录"));
        }
        /**
         * 用户历史记录列表页
         */
        public function users_history_item(){
                $this->page->setTotal($this->user->getTotal_users_history(false));
                $list = $this->user->getList_users_history($this->page->getLimit());
                $_REQUEST['u_number']=$_REQUEST['uh_u_number'];
                $this->user->set($_REQUEST);
                $info=$this->user->getById_history();
                $numinfo = $this->page->getNumInfo();
                $prev = $this->page->getPrev();
                $next = $this->page->getNext();
                $this->smarty->assign('list', $list);
                $this->smarty->assign('info', $info);
                $this->smarty->assign('numinfo', $numinfo);
                $this->smarty->assign('prev', $prev);
                $this->smarty->assign('next', $next);
                $this->htmlrender("modules/enterprise/users_history_item.tpl");
        }

	/**
	 * 自建组企业用户列表后台接口
	 * @return html_table 企业用户列表
	 */
	function users_item_cust() {
		$item_e = $this->enterprise->getByid();
		$this->page->setTotal($this->user->getTotal());
		$list = $this->user->getList_cust($this->page->getLimit());
		$maxpage = $this->page->getPages();
		foreach ($list as $val) {
			$pg_list[$val['u_number']] = $this->groups->getuserPgname($val['u_number'],$val['u_default_pg']);
		}

		$numinfo = $this->page->getNumInfo();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		$this->smarty->assign('list', $list);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('pg_list', $pg_list);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->smarty->assign('maxpage', $maxpage);
		$this->smarty->assign('data', $_REQUEST);
		$this->smarty->assign('session', $_SESSION);
		$this->smarty->assign('item_e', $item_e);

		if ($_REQUEST['type'] == 'append') {
			$this->htmlrender('modules/enterprise/users_item.append.tpl');
		} else {
			$this->htmlrender('modules/enterprise/users_item.tpl');
		}
	}

    /**
     * 企业用户删除
     */
    function users_del() {
            $this->enterprise->changeSync(true, 28);
            $list = $this->tools->get('list');
            $result = $this->user->delList($list);
            if($result['c']>0){
		$this->user->add_users($_REQUEST['e_id'], -$result['c']);
		$this->user->sum_add_users($_REQUEST['e_id'], -$result['c']);
		if($result['r']-$result['c']!=0){
			$this->user->sum_delete_users($_REQUEST['e_id'], $result['r']-$result['c']);
			$this->user->delete_users($_REQUEST['e_id'], $result['r']-$result['c']);
		}
            }else{
                $this->user->sum_delete_users($_REQUEST['e_id'], $result['r']);
                $this->user->delete_users($_REQUEST['e_id'], $result['r']);
             }
            echo $result['r'];
        die;
    }

    /**
     * 企业用户保存接口
     */
    function users_save_shell() {
            try
            {
                    $this->saveUserVerify();
                    $msg = $this->user->save();
            } catch (Exception $ex) {
                    //$this->user->log($ex->getMessage(), 1, 2);
                    $this->tools->call($ex->getMessage(), -1, TRUE);
            }
            
            echo json_encode($msg);
            $this->enterprise->changeSync(true, 28);
            exit();
    }

	/**
	 *获取手机号是否存在？
	 */
	function getmob() {
		$user = new users(array("u_number" => $_REQUEST['u_number']));
		$flag = $user->getById();
		if ($flag['u_mobile_phone'] != $_REQUEST['u_mobile_phone'] && $_REQUEST['u_mobile_phone'] != "") {
			$res = $this->user->getmobile($_REQUEST['u_mobile_phone']);

			if ($res == false) {
				echo "1";
			} else if (count($res) >= 1) {
				echo "2";
			}
		}
	}
	/**
	 *获取u_udid是否存在？
	 */
	function getudid() {
		$user = new users(array("u_number" => $_REQUEST['u_number']));
		$flag = $user->getById();
		if ($flag['u_udid'] != $_REQUEST['u_udid'] && $_REQUEST['u_udid'] != "") {
			$res = $this->user->getudid($_REQUEST['u_udid']);

			if ($res == false) {
				echo "1";
			} else if (count($res) >= 1) {
				echo "2";
			}
		}
	}
	/**
	 *获取u_imsi是否存在？
	 */
	function getimsi() {
		$user = new users(array("u_number" => $_REQUEST['u_number']));
		$flag = $user->getById();
		if ($flag['u_imsi'] != $_REQUEST['u_imsi'] && $_REQUEST['u_imsi'] != "") {
			$res = $this->user->getimsi($_REQUEST['u_imsi']);

			if ($res == false) {
				echo "1";
			} else if (count($res) >= 1) {
				echo "2";
			}
		}
	}
/**
        *获取u_imei是否存在？
        */
        function getimei() {
                $user = new users(array("u_number" => $_REQUEST['u_number']));
                //去库里查询imei信息
                $term = new terminal($_REQUEST);
                $termInfo = $term->checkexcel_imei($_REQUEST['u_imei']);

                $flag = $user->getById();
                if ($flag['u_imei'] != $_REQUEST['u_imei'] && $_REQUEST['u_imei'] != "") {
                    $back = $this->getAjaxreturn($_REQUEST['u_imei'],$_REQUEST['u_bind_phone'],$_REQUEST['u_terminal_type'],$_REQUEST['e_id']);
                    echo json_encode(array('status'=>$back,'res'=>$termInfo));
                }else if($_REQUEST['u_imei'] == ""){
                    echo json_encode(array('status'=>'isnull','res'=>$termInfo));
                }else if($flag['u_imei']==$_REQUEST['u_imei']){
                    if($flag['u_terminal_type']==$_REQUEST['u_terminal_type']){
                        echo json_encode(array('status'=>'issame','res'=>$termInfo));
                    }else{
                        $back = $this->getAjaxreturn($_REQUEST['u_imei'],$_REQUEST['u_bind_phone'],$_REQUEST['u_terminal_type'],$_REQUEST['e_id']);
                        echo json_encode(array('status'=>$back,'res'=>$termInfo));
                    }
                }
        }
        public function getAjaxreturn($u_imei,$u_bind_phone,$u_terminal_type,$e_id){
            $res = $this->user->getimei($_REQUEST['u_imei']);//不在用户中也不再终端中
                    if ($res == false) {
                        if($_REQUEST['u_bind_phone']=="0"){
                             if($_REQUEST['u_terminal_type']==""){
                                return "1";//不在库中可以正常保存
                            }else{
                                return "8";//非其他选项 必须是库里的
                            }
                        }else{
                                return "8";//必须是库里的
                        }
                    }else{
                        $info=check_md_imei($_REQUEST['u_imei'],$_REQUEST['e_id']);
                        if(is_array($info)){
                            if($info['res']===TRUE){
                               if($info['md_type']==$_REQUEST['u_terminal_type']){
                                   return "5";//需要绑定
                               }else{
                                   return "7";//在库中 符合所属 但类型不同
                               }
                           }
                        }else if($info==="Binding"){
                        return "3";//已被绑定 不能保存
                        }else if($info==="Not Belong"){
                        return "4";//在库中但不属于所属代理商,不能保存
                        }else if($info==="isnull"){
                        return "6";//传过来的imei为空 可以保存
                        }else if($info==="Not in the library"){
                        return "2";//不在库中可以正常保存
                        }else{
                        return "2";//已存在IMEI
                        }
                    }
        }

    /**
    *获取u_meid是否存在？
    */
    function getmeid() {
        $user = new users(array("u_number" => $_REQUEST['u_number']));
        //去库里查询imei信息
        $term = new terminal($_REQUEST);
        $termInfo = $term->checkexcel_meid($_REQUEST['u_meid']);
        $flag = $user->getById();
        if ($flag['u_meid'] != $_REQUEST['u_meid'] && $_REQUEST['u_meid'] != "") {
            $back = $this->getAjaxreturn_meid($_REQUEST['u_meid'],$_REQUEST['u_bind_phone'],$_REQUEST['u_terminal_type'],$_REQUEST['e_id']);

            echo json_encode(array('status'=>$back,'res'=>$termInfo));

        }else if($_REQUEST['u_meid'] == ""){

            echo json_encode(array('status'=>'isnull','res'=>$termInfo));

        }else if($flag['u_meid']==$_REQUEST['u_meid']){

            if($flag['u_terminal_type']==$_REQUEST['u_terminal_type']){

                echo json_encode(array('status'=>'issame','res'=>$termInfo));

            }else{

                $back = $this->getAjaxreturn_meid($_REQUEST['u_meid'],$_REQUEST['u_bind_phone'],$_REQUEST['u_terminal_type'],$_REQUEST['e_id']);

                echo json_encode(array('status'=>$back,'res'=>$termInfo));

            }

        }
    }

    public function getAjaxreturn_meid($u_meid,$u_bind_phone,$u_terminal_type,$e_id)
    {
        $res = $this->user->getmeid($_REQUEST['u_meid']);//不在用户中也不再终端中
        if ($res == false) {
            if($_REQUEST['u_bind_phone']=="0"){
                if($_REQUEST['u_terminal_type']==""){
                    return "1";//不在库中可以正常保存
                }else{
                    return "8";//非其他选项 必须是库里的
                }
            }else{
                return "8";//必须是库里的
            }
        }else{
            $info=check_md_meid($_REQUEST['u_meid'],$_REQUEST['e_id']);
            if(is_array($info)){
                if($info['res']===TRUE){
                   if($info['md_type']==$_REQUEST['u_terminal_type']){
                       return "5";//需要绑定
                   }else{
                       return "7";//在库中 符合所属 但类型不同
                   }
               }
            }else if($info==="Binding"){
                return "3";//已被绑定 不能保存
            }else if($info==="Not Belong"){
                return "4";//在库中但不属于所属代理商,不能保存
            }else if($info==="isnull"){
                return "6";//传过来的imei为空 可以保存
            }else if($info==="Not in the library"){
                return "2";//不在库中可以正常保存
            }else{
                return "2";//已存在MEID
            }
        }
    }

/**
*获取u_iccid是否存在？
*/
function geticcid() {
       /*$user = new users(array("u_number" => $_REQUEST['u_number']));
       $flag = $user->getById();
       if ($flag['u_iccid'] != $_REQUEST['u_iccid'] && $_REQUEST['u_iccid'] != "") {
               $res = $this->user->geticcid($_REQUEST['u_iccid']);

               if ($res == false) {
                       echo "1";
               } else if (count($res) >= 1) {
                       echo "2";
               }
       }*/
       $this->enterprise->set($_REQUEST);
        if($_REQUEST['type']=='imsi'){
            $res =  $this->gprs->check_u_imsi();
        }elseif($_REQUEST['type']=='number'){
            $res =  $this->gprs->check_u_mobile();
        }else{
            $res =  $this->gprs->check_iccid();
        }
        
        echo json_encode($res);
	}
/**
	 *获取u_mac是否存在？
	 */
	function getmac() {
		$user = new users(array("u_number" => $_REQUEST['u_number']));
		$flag = $user->getById();
		if ($flag['u_mac'] != $_REQUEST['u_mac'] && $_REQUEST['u_mac'] != "") {
			$res = $this->user->getmac($_REQUEST['u_mac']);

			if ($res == false) {
				echo "1";
			} else if (count($res) >= 1) {
				echo "2";
			}
		}
	}

	/**
	 * ajax调用users中的saveGvsUser()方法
	 */
	function saveGVS() {
		try
		{
			$result = $this->user->saveGvsUser();
			$this->pttmember->delGVSptt();
		} catch (Exception $ex) {

		}

		echo json_encode($result);
	}

	/**
	 * 企业用户批量生成保存接口
	 */
	function users_auto_save_shell() {
		try
		{
			$this->saveUserVerify($_REQUEST['u_auto_number']);
		} catch (Exception $ex) {
			$goto = '?m=enterprise&a=users&e_id=' . $_REQUEST['e_id'];
			print('<script>parent.notice("' . $ex->getMessage() . '","' . $goto . '");</script>');
			exit();
		}

		print str_repeat(" ", 4096);
		$this->user->createUsers();
		$this->enterprise->changeSync(true, 28);
	}

    /**
     * 批量修改企业用户
     */
    function users_batch() {
            if (!empty($_REQUEST['checkbox'])) {
                    $this->enterprise->changeSync(true, 28);
            }
            foreach ($_REQUEST['checkbox'] as $value) {
                    $data['e_id'] = $_REQUEST['e_id'];
                    $data['u_product_id'] = $_REQUEST['u_product_id'];
                    $data['isused'] = $_REQUEST['isused'];
                    //$data['u_p_function'] = $_REQUEST['u_p_function'];
                    //$data['u_default_pg'] = $_REQUEST['u_default_pg'];
                    $data['u_ug_id'] = $_REQUEST['u_ug_id'];
                    $data['u_p_function_new'] = "%";
                    $data['u_number'] = $value;
                    $data['u_gis_mode'] = $_REQUEST['u_gis_mode'];
                    $data['u_mms_default_rec_num'] = $_REQUEST['u_mms_default_rec_num'];
                    $data['u_alarm_inform_svp_num'] = $_REQUEST['u_alarm_inform_svp_num'];
                    $data['u_only_show_my_grp'] =  $_REQUEST['u_only_show_my_grp'];
                    $data['submit_type'] = $_REQUEST['submit_type'];
                    $this->user->set($data);
                    $this->user->batchUser();
            }
            exit();
    }
        /**
	 * 批量修改企业用户产品功能
	 */
	function users_batch_p() {
                                
		if (!empty($_REQUEST['checkbox'])) {
			$this->enterprise->changeSync(true, 28);
		}
		foreach ($_REQUEST['checkbox'] as $value) {
			$data['e_id'] = $_REQUEST['e_id'];
                                                     $data['checkbox1'] = $_REQUEST['checkbox1'];
                                                    // $data['u_product_id'] = $_REQUEST['u_product_id'];
			//$data['u_default_pg'] = $_REQUEST['u_default_pg'];
			$data['u_ug_id'] = "%";
			$data['u_number'] = $value;
			$this->user->set($data);
			$this->user->batchUser();
		}
		exit();
	}
        /**
        * 批量修改企业用户产品功能
        */
       function change_function() {
                                
            if (!empty($_REQUEST['checkbox'])) {
                    $this->enterprise->changeSync(true, 28);
            }
            foreach ($_REQUEST['checkbox'] as $value) {
                        $data['u_number'] = $value;
                        $data['u_p_function_new'] = json_encode($_REQUEST['checkbox1']);
                        $data['isused'] = $_REQUEST['isused'];
                        $data['u_number'] = $value;
                        $this->user->set($data);
                        $this->user->change_function();
            }
            exit();
    }

    /**
     * 批量移动企业用户
     */
    function users_move() {
            try
            {
                    if (!empty($_REQUEST['checkbox'])) {
                            //当前企业状态需要同步
                            $this->enterprise->changeSync(true, 28);
                            //接收用户企业状态需要同步
                            $ep = new enterprise(array("e_id" => $_REQUEST['to_e_id']));
                            $ep->changeSync(true, 28);
                            $checknum = count($_REQUEST['checkbox']); //企业选中用户数
                            $ep = new enterprise(array("e_id" => $_REQUEST['to_e_id']));
                            $result = $ep->getByid(); //目标企业信息
                            $user = new users(array("e_id" => $_REQUEST['to_e_id']));
                            $phone_num = $user->getusertotal(1); //目标企业目前用户数
                            $en_num = $result['e_mds_phone'] - $phone_num; //目标企业可用手机用户数
                            if ($en_num >= $checknum) {
                                    foreach ($_REQUEST['checkbox'] as $value) {
                                            $data['e_id'] = $_REQUEST['e_id'];
                                            $data['to_e_id'] = $_REQUEST['to_e_id'];
                                            $data['u_number'] = $value;

                                            $this->user->set($data);
                                            $this->user->moveUsers();
                                            //$this->groups->deluserpg ( $value );
                                    }
                            } else {
                                    $this->tools->call(L('目标企业没有足够的剩余手机用户数，移动失败'), -1, true);
                            }
                    }
            } catch (Exception $ex) {
                    if ($ex->getCode() == 23505) {
                            $this->tools->call(L('被移动用户号码在目标企业已存在，移动失败'), -1, true);
                    }
                    $log = DL("移动用户到其它企业出现失败") . "：" . $ex->getMessage();
                    $this->user->log($log, 1, 2);
                    $this->tools->call(L("移动用户到其它企业出现失败"), -1, true);
            }
            $this->tools->call(L('操作完成'), 0, true);
    }

	function users_item_v2() {
		$item_e = $this->enterprise->getByid();
		$total = $this->user->getTotal();
		$this->page->setTotal($total);
		$list = $this->user->getListV2();
		$numinfo = $this->page->getNumInfo();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		$this->smarty->assign('list', $list);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->smarty->assign('data', $_REQUEST);
		$this->smarty->assign('item_e', $item_e);
		$this->smarty->assign('total', $total);
		if ($_REQUEST['type'] == 'append') {
			if (count($list) != 0) {
				$html = $this->htmlrender('modules/enterprise/users_item.append.tpl', true);
			} else {
				$html = "";
			}
			echo $html;
		} else {
			$this->htmlrender('modules/enterprise/users_item.tpl');
		}
	}

	/**
	 * 获取当前页 用户列表个数
	 */
	public function getusernum() {
		$limt = " LIMIT 10 OFFSET " . $_REQUEST['page'] * 10;

		$result = $this->user->getList($limt);

		echo count($result);
	}
        /**
         * 判断该用户号码是否为该企业用户
         */
        function check_number(){
            $res=$this->user->getById();
            if($res){
                echo  "1";
            }else{
                echo "2";
            }
        }
        public function get_random_passwd(){
            /*$passwd = str_replace("0", "2", uniqid());
            $passwd = str_replace("1", "2", $passwd);
            $passwd = str_replace("l", "m", $passwd);
            $passwd = str_replace("o", "p", $passwd);
            $passwd = str_replace("O", "p", $passwd);*/
            $passwd = $this->user->random_str(8);
            echo $passwd;
        }

}
