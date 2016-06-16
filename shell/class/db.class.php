<?php

/**
 * 数据库基类
 * @category OMP
 * @package OMP_Common
 * @require {@see page} {@see tools}
 * @version 1.0
 */
class db {

	/** 数据库抽象对象 */
	public $pdo = NULL;

	/** 静态化数据库抽象对象 */
	public static $PDOInstance = NULL;

	/** 配置数据。数据来源：private/config/config.ini */
	public $config = array();

	/** 数据源 */
	public $data = array();

	/** 字段列表。使用,号分隔。示例：id,name */
	public $filed = '*';

	/** 表名。示例：T_Product */
	public $table = "";

	/** 数目限制。示例：Limit10offset0 */
	public $limit = "";

	/** 排序条件。示例：OrderByidasc,namedesc */
	public $order = "";

	/** 条件限制。示例：Where1=1AND2=2 */
	public $where = "";
	public $left;

	const LOGIN = 7;
	const USER = 1;
	const GROUP = 2;
	const USERGROUP = 3;
	const LOG = 6;
	const WARING = 1;
	const ERROR = 2;
	const INFO = 0;

	/**
	 * 数据库构造函数
	 */
	public function __construct() {

		/** 单一实例 PDO对象 */
		if (!self::$PDOInstance) {

			$this->config = json_decode(file_get_contents("../private/config/db.json"), true);

			$config = $this->config;
			$host = $config["data_base"]["db_host"];
			$dbname = $config["data_base"]["db_name"];
			$port = $config["data_base"]["db_port"];
			$username = $config["data_base"]["db_user"];
			$password = $config["data_base"]["db_pwd"];

			if ($config["data_base"]["db_host"] != 'localhost') {
				$hosturl = "host=$host;";
			}

			try
			{
				self::$PDOInstance = new PDO("pgsql:"
					. $hosturl
					. "port=$port;"
					. "dbname=$dbname;"
					, $username
					, $password
					, array(
						PDO::ATTR_PERSISTENT => true,
					)
				);
			} catch (Exception $ex) {
				$tools = new tools();
				$path = $tools->log("数据库初始化失败，已强制断开链接。<br/>抓取到的异常栈如下：<br/><pre>" . print_r($ex, true) . "</pre>", 'db');

				header("Content-type:text/html;charset=utf-8");
				if ($config["SYSTEM"]["DEBUG"]) {
					echo "数据库初始化失败，已强制断开链接。<br/>详细信息请访问{$path}文件日志";
				} else {
					echo "数据库初始化失败，请联系系统管理员。";
				}
				die();
			}
                    try {
                        self::$PDOInstance->query("SET client_encoding='UTF-8';");
                        self::$PDOInstance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                        self::$PDOInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                 } catch (Exception $exc) {
                    $this->pdo=NULL;
                    //$this->smarty->assign('msg', L("帐号长时间未操作,请重新登录"));
                //$this->smarty->assign('href', "?m=login");
                $doc = <<<DOC
            <!DOCTYPE html>
            <html>
                <head>
                        <meta charset="UTF-8">
                        <script src="layer/jquery-1.11.1.min.js"></script>
                        <script src="layer/layer.js"></script>
                <head>
            <body>
DOC;

                print $doc;
                $info=L('服务器变更请刷新');
                //print("<script>parent.confirm('见到你真的很高兴', {icon: 6});location.href='?m=login';</script>");
                print("<script>layer.msg('".$info."', {icon: 2,time: 30000},function(){location.reload();});</script>");
                print('</body></html>');
                //$_SESSION['own']['em_lastlogin_time']=NULL;
               // $this->render('modules/system/login.tpl');

                exit();
                
        }
    }
        $this->pdo = self::$PDOInstance;
}

	/**
	 * 通用查询器
	 */
	public function select() {
		$filed = $this->filed;
		$table = $this->table;
		$where = $this->where;
		$left = $this->left;
		$order = $this->order;
		$limit = $this->limit;
		$sql = <<<SQL
SELECT
{$filed}
FROM
                "{$table}"
                {$left}
                 {$where}
                {$order}
                {$limit}
SQL;
		$query = $this->pdo->query($sql);
		return $query->fetchAll();
	}

	/**
	 * 设置数据库表
	 * @paramtype$table
	 * @return db
	 */
	public function table($table) {
		$this->table = $table;
		return $this;
	}

	/**
	 * 设置数据库字段
	 * @paramtype$filed
	 * @paramtype$iskey
	 * @return db
	 */
	public function filed($filed, $iskey = TRUE) {
		if ($iskey) {
			$this->filed = implode(',', array_keys($filed));
		} else {
			$this->filed = implode(',', $filed);
		}
		return $this;
	}

	/**
	 * 设置数据库条件
	 * @paramtype$where
	 * @return db
	 */
	public function where($where = '') {
		if ($this->where != '' && $where != '') {
			$this->where .= ' AND  ' . $where;
			return $this;
		}
		if ($where == '') {
			$this->where = '';
		} else {
			$this->where = 'WHERE ' . $where;
		}
		return $this;
	}

	/**
	 * 设置数据库条数限制
	 * @paramtype$start
	 * @paramtype$num
	 * @return db
	 */
	public function limit($start, $num) {
		$this->limit = "LIMIT {$start} OFFSET {$num}";
		return $this;
	}

	/**
	 * 设置数据库排序条件
	 * @paramtype$order
	 * @return\db
	 */
	public function order($order = '') {
		if ($order == '') {
			$this->order = '';
		} else {
			$this->order = "ORDER BY {$order}";
		}
		return $this;
	}

	public function limitstr($limit) {
		$this->limit = $limit;
		return $this;
	}

	/**
	 * 通过一条SQL查询总数
	 * @paramtype$sql
	 * @returntype
	 */
	public function total($sql) {
		$sth = $this->pdo->query($sql);
		$result = $sth->fetch();
		return $result['total'];
	}

	/**
	 * 魔法引号
	 * @paramtype$arr
	 * @returntype
	 */
	public function quotes($arr) {
		if (!is_array($arr)) {
			return "'$arr'";
		} else {
			$tmp = array();
			foreach ($arr as $value) {
				$tmp[] = "'$value'";
			}
			return $tmp;
		}
	}

	public function left($left = '') {
		if ($left == '') {
			$this->left = '';
		} else {
			$this->left .= ' ' . $left . ' ';
		}
		return $this;
	}

	/**
	 * 获得数据库当前抽象层对象PDO
	 * @returntypepdo
	 */
	public function getpdo() {
		return $this->pdo;
	}

	/**
	 * 返回标准化消息
	 * @paramtype$msgtext消息内容
	 * @paramtype$status消息状态
	 * @returntype
	 */
	public function msg($msgtext, $status = 0) {
		$msg["msg"] = $msgtext;
		$msg["status"] = $status;
		return $msg;
	}

	public function msg1($msgtext, $status = 0, $arr) {
		$msg["msg"] = $msgtext;
		$msg["status"] = $status;
		$msg["arr"] = $arr;
		return $msg;
	}

	/**
	 * 获得一个不重复的大写的32位字符串
	 * @returntype
	 */
	public function md5r() {
		return strtoupper(md5(uniqid(rand(), true)));
	}

	/**
	 * 日志记录实现
	 * @paramstring$content日志内容
	 * @paramtype$type日志类别
	 * @paramtype$level日志等级
	 * @paramtype$data调试信息
	 */
	public function log($content, $type = 0, $level = 0, $data = array()) {
		$time = date("Y-m-d H:i:s", time());
		if (count($data) > 0) {
			$user = $data['ag_name'];
		} else {
                                                     if($_SESSION['as_account_id']!=""){
                                                         $user = $_SESSION['as_account_id'];
                                                     }else{
                                                        $user = $_SESSION['ag']['ag_name'];
                                                     }
		}

		if (!empty($data)) {
			$tools = new tools();
			$tools->log(implode('|', $data), 'error');
		}
                                   $table = 'T_EventLog_' . $_SESSION['ag']['ag_number'];
		$sql = 'INSERT INTO "' . $table . '" ("el_type","el_level","el_time","el_content","el_user") VALUES (:type,:level,:time,:content,:user);';

		$sth = $this->pdo->prepare($sql);

		if (mb_strlen($content) > 1000) {
			$content = mb_substr($content, 0, 800) . '（日志内容过长被截取）...';
		}

		$sth->bindValue(':type', $type);
		$sth->bindValue(':level', $level, PDO::PARAM_INT);
		$sth->bindValue(':time', $time, PDO::PARAM_INT);
		$sth->bindValue(':content', $content);
		$sth->bindValue(':user', $user);
		try
		{
			$sth->execute();
		} catch (Exception $ex) {
			$msg["msg"] = "日志记录信息无法处理，可能是当前并行插入的数据太多，稍等片刻，等待数据库将信息保存完毕。已被迫中断。" . $ex->getMessage();
			$msg["status"] = -1;
			echo json_encode($msg);
			exit();
		}
	}

	/**
	 * 事件日志
	 * @paramtype$ex
	 * @paramtype$str
	 * @returntype
	 */
	public function eventlog($ex, $str) {
		$event['id'] = $this->md5r();
		if ($ex->getCode() == "23505") {
			$log['msg'] = DL($str) . '。' . DL('原因') . '：' . DL('重复');
		} else {
			$log['msg'] = DL($str) . '。' . DL('事件ID') . $event['id'];
		}
		$event['msg'] = $ex->getMessage();
		$log['event'] = $event;
		return $log;
	}

}
