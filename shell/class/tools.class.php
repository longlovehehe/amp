<?php

/**
 * 工具类
 * @category OMP
 * @package OMP_Common
 * @require {@see sendmsg} {@see lang}
 */
class tools
{

    public $config;
    public $lang;
    public static $configStaic;
    public static $langStaic;
    public $smarty;
    public $sendmsg;

    public function __construct ()
    {
        if ( ! isset ( self::$configStaic ) )
        {
            //$this->log('1'.$_SERVER['QUERY_STRING'], 'static');
            self::$configStaic = parse_ini_file ( '../private/config/config.ini' , true );
        }
        else
        {
            //$this->log('2'.$_SERVER['QUERY_STRING'], 'static');
        }
        if ( ! isset ( self::$langStaic ) )
        {
            //$this->log('1'.$_SERVER['QUERY_STRING'], 'static');
            self::$langStaic = parse_ini_file ( '../private/config/language.ini' , true );
        }
        else
        {
            //$this->log('2'.$_SERVER['QUERY_STRING'], 'static');
        }

        $this->config = self::$configStaic;
        $this->lang = self::$langStaic;
        $this->smarty = new smartyex();
    }

    // 消息名称，参数
    public function send ( $name , $parame )
    {
        $sendmsg = new sendmsg();
        try
        {
            if ( method_exists ( $sendmsg , $name ) )
            {
                $result = $sendmsg->$name ( $parame );
                $this->log ( '发送了' . $name . '消息。命令：' . $result['shell'] . "。结果：" . $result['status'] , 'shell' );
            }
            else
            {
                $this->log ( '使用了未定义的消息名称' . $name , 'shell_error' );
            }
        }
        catch ( Exception $ex )
        {
            $this->log ( '发送了' . $name . '消息。命令：' . $ex->getMessage () . "。结果：" . $ex->getCode () , 'shell_error' );
            $this->call ( $ex->getMessage () , $ex->getCode () , true );
        }
    }

    public function getconfig ()
    {
        return $this->config;
    }
    public function getlangconfig ()
    {
        return $this->lang;
    }

    public function setlangconfig ( $lang = "cn_ZH" )
    {
        $_COOKIE['lang'] = $lang;
        $this->ini_file ( '../private/config/language.ini' , "language" , "lang" , $lang );
    }

    public function init ()
    {
        $this->isdebug ();
        $this->repair ();
        $this->dispatcher ();
        //$this->lang ();
    }

    /**
     *
     * @deprecated since version number
     * @global type $res
     * @return type
     */
    function lang ()
    {
        return null;
        global $res;

        $lang = new lang ( ".." );
        if ( method_exists ( $lang , $_COOKIE['lang'] ) )
        {
            $res = $lang->$_COOKIE['lang'] ();
            $res = $lang->getText ();
        }
    }

    function dispatcher ()
    {
        if ( isset ( $_REQUEST['p'] ) )
        {
            $paths = explode ( "/" , $_REQUEST['p'] );
            $var = array ();
            preg_replace_callback ( '/(\w+)\/([^\/]+)/' , function($match) use(&$var)
            {
                $var[$match[1]] = strip_tags ( $match[2] );
            } , implode ( '/' , $paths ) );
            $_REQUEST = array_merge ( $_REQUEST , $var );
            $this->smarty->assign ( 'request' , $_REQUEST );
        }
        $this->smarty->assign ( 'request' , $_REQUEST );
    }

    function log ( $msg , $prefix = "" )
    {
        if ( $prefix != "" )
        {
            $prefix .= "_";
        }

        $dir = "../runtime/log/" . Date ( "Ym" ) . "/";
        if ( ! is_dir ( $dir ) )
        {
            mkdir ( $dir );
        }

        $path = $dir . $prefix . date ( "Ymd" ) . ".log";
        $handle = fopen ( $path , "a" );
        fwrite ( $handle , date ( "Y-m-d H:i:s" , time () ) . "\t" . $_SERVER["REMOTE_ADDR"] . "\t" . $msg . "\n" );
        fclose ( $handle );
        return str_replace ( '../' , '' , $path );
    }

    function show ( $msg )
    {
        echo json_encode ( $msg );
        exit ();
    }

    function get ( $str )
    {
        if ( isset ( $_REQUEST[$str] ) )
        {
            return $_REQUEST[$str];
        }
        return "";
    }

    function getModule ( $modulename )
    {
        return '../shell/modules/' . $modulename . '.php';
    }

    function notfound ( $msg = "0x000000" , $title = "" )
    {
        if ( $msg == "0x000000" )
        {
            $msg = print_r ( get_declared_classes () , true );
        }

        $this->smarty->assign ( "msg" , $msg );
        $this->smarty->assign ( "title" , $title );
        $this->smarty->display ( 'viewer/404.tpl' );
        exit ();
    }

    function msg ( $status = 0 , $message = "null" )
    {
        $msg["status"] = $status;
        $msg["msg"] = $message;
        json_encode ( $msg );
        exit ();
    }

    function repair ()
    {
        if ( $this->config['config']['repair'] )
        {
            $mask = md5 ( "repair" );
            $this->smarty->display ( 'viewer/repair.tpl' , $mask );
            exit ();
        }
    }

    function isdebug ()
    {
        $this->smarty->caching = FALSE;
        $this->smarty->cache_lifetime = 0;
        $this->smarty->force_compile = TRUE;
    }

    public function call ( $msgtext , $status = 0 , $end = false )
    {
        $msg["msg"] = $msgtext;
        $msg["status"] = $status;
        if ( $end )
        {

            echo json_encode ( $msg );
            exit ();
        }
        return json_encode ( $msg );
    }

    function StopAttack ( $StrFiltKey , $StrFiltValue , $ArrFiltReq )
    {
        if ( is_array ( $StrFiltValue ) )
        {
            $StrFiltValue = implode ( $StrFiltValue );
        }
        if ( preg_match ( "/" . $ArrFiltReq . "/is" , $StrFiltValue ) == 1 )
        {
            $msg = "操作IP: " . $_SERVER["REMOTE_ADDR"] . "<br>操作时间: " . strftime ( "%Y-%m-%d %H:%M:%S" ) . "<br>操作页面:" .
                    $_SERVER["PHP_SELF"] . "<br>提交方式: " . $_SERVER["REQUEST_METHOD"] . "<br>提交参数: " . $StrFiltKey . "<br>提交数据: " .
                    $StrFiltValue;
            $this->log ( $msg , "filter" );
            $this->notfound ( "0_0，提交的值违法被过滤了" );
        }
    }

    public function safe360 ()
    {
        $getfilter = "'|(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s
+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
        $postfilter = "\\b(and|or)\\b\S{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|
UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
        $cookiefilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|
UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";

        foreach ( $_GET as $key => $value )
        {
            $this->StopAttack ( $key , $value , $getfilter );
        }
        foreach ( $_POST as $key => $value )
        {
            $this->StopAttack ( $key , $value , $postfilter );
        }
        foreach ( $_COOKIE as $key => $value )
        {
           $this->StopAttack ( $key , $value , $cookiefilter );
        }
    }

    //用法 ini_file(文件名,ini节名,键名key , 键值)
//查询时 键值 留空或设为null,函数返回键值
//若无ini节名，则ini节名设为null  ini节名 不包含[ ]
//查询
//echo ini_file('abc.ini','sectionA','key1');
//输出对应的键值 如123ds
//添加或更改
    /*
      if(ini_file('abc.ini','sectionA','key1','bnmv')
      echo "成功添加或修改";
      else
      echo "操作失败";
     *     ini_file('你的文件名' , '123' , 'qew' , '456');
     */

    function ini_file ( $inifilename , $mode = null , $key , $value = null )
    {
//传入参数为null时的默认值
        $inifilename = $inifilename == null ? 'Application.ini' : $inifilename;
        $key = $key == null ? 'user' : $key;
        if ( ! file_exists ( $inifilename ) )
            return null;
//读取
        $confarr = parse_ini_file ( $inifilename , true );
        $newini = "";
        if ( $mode != null )
        {
//节名不为空
            if ( $value == null )
            {
                return @$confarr[$mode][$key] == null ? null : $confarr[$mode][$key];
            }
            else
            {
                $YNedit = @$confarr[$mode][$key] == $value ? false : true;//若传入的值和原来的一样，则不更改
                @$confarr[$mode][$key] = $value;
            }
        }
        else
        {//节名为空
            if ( $value == null )
            {
                return @$confarr[$key] == null ? null : $confarr[$key];
            }
            else
            {
                $YNedit = @$confarr[$key] == $value ? false : true;//若传入的值和原来的一样，则不更改
                @$confarr[$key] == $value;
                $newini = $newini . $key . "=" . $value . "\r\n";
            }
        }
        if ( ! $YNedit )
            return true;

//更改

        $Mname = array_keys ( $confarr );
        $jshu = 0;

        foreach ( $confarr as $k => $v )
        {
            if ( ! is_array ( $v ) )
            {
                $newini = $newini . $Mname[$jshu] . "=" . $v . "\r\n";
                $jshu += 1;
            }
            else
            {
                $newini = $newini . '[' . $Mname[$jshu] . "]\r\n";//节名
                $jshu += 1;
                $jieM = array_keys ( $v );
                $jieS = 0;
                foreach ( $v as $k2 => $v2 )
                {
                    $newini = $newini . $jieM[$jieS] . "=" . $v2 . "\r\n";
                    $jieS += 1;
                }
            }
        }
        if ( ($fi = fopen ( $inifilename , "w" ) ) )
        {
            flock ( $fi , LOCK_EX );//排它锁
            fwrite ( $fi , $newini );
            flock ( $fi , LOCK_UN );
            fclose ( $fi );
            return true;
        }
        return false;//写文件失败
    }

}
