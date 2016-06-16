<?php

/**
 * 控制器基类
 * @category OMP
 * @package OMP_Common
 * @require {@see area}
 */
class contorl
{

    public $smarty;
    public $tools;
    public $title;
    public $content;
    public $page;

    function __construct ()
    {
        $this->smarty = new smartyex();
        $this->tools = new tools();
    }

    public function permissions ( $own , $admin = false )
    {
        if($_SESSION['as_account_id']!="" ){
           
            if($_REQUEST['m']=="agentsub" || $_REQUEST['m']=="account" || $_REQUEST['m']=="agents" ){
                 $msg = L ( "该资源需要超级管理员才可以使用，请注销之后。使用超级管理员帐号登录后再访问" );
                //$this->smarty->clearCache ( $_SERVER['REQUEST_URI'] );
                $this->smarty->assign ( 'msg' , $msg );
                $this->smarty->display ( 'modules/system/login.tpl' );
                exit ();
            }else if($_REQUEST['m']=="product"&&($_REQUEST['a']!="option"&&$_REQUEST['a']!="p_option"&&$_REQUEST['a']!="ip_option"&&$_REQUEST['a']!="get_p_name")){
                 $msg = L ( "该资源需要超级管理员才可以使用，请注销之后。使用超级管理员帐号登录后再访问" );
                //$this->smarty->clearCache ( $_SERVER['REQUEST_URI'] );
                $this->smarty->assign ( 'msg' , $msg );
                $this->smarty->display ( 'modules/system/login.tpl' );
                exit ();
            }
            
        }else{
                    
             if ( $own["ag_number"] == "" )
            {
                $msg = L ( "尚未登录系统，无法访问" );
                $this->smarty->assign ( 'msg' , $msg );
                $this->smarty->display ( 'modules/system/login.tpl' );
                exit ();
            }
             if ( $admin && $own["ag_level"] !== '0')
            {
                $msg = L ( "该资源需要超级管理员才可以使用，请注销之后。使用超级管理员帐号登录后再访问" );
                //$this->smarty->clearCache ( $_SERVER['REQUEST_URI'] );
                $this->smarty->assign ( 'msg' , $msg );
                $this->smarty->display ( 'modules/system/login.tpl' );
                exit ();
            }
        }

    }
      

    function content ( $content )
    {
        //$content = preg_replace("/\s+/", " ", $content); //过滤多余回车
        //$content = preg_replace("/<[ ]+/si", "<", $content); //过滤<__("<"号后面带空格)
        //$content = preg_replace("/<\!--.*?-->/si", "", $content); //注释

        $this->content = $content;
    }

    function htmlrender ( $tpl , $flag = false )
    {
        $this->content ( $this->smarty->fetch ( $tpl ) );
        if ( $flag )
        {
            return $this->content;
        }
        $this->smarty->assign ( 'header' , $this->content );
        $this->smarty->display ( '_cache.tpl' );
    }

    function render ( $tpl , $title = "" , $script = array () , $style = array () )
    {
        $this->title = $title;
        $this->smarty->assign ( 'title' , $this->title );
        $this->content ( $this->smarty->fetch ( $tpl ) );
        $tplurl = str_replace ( '/' , '__' , $tpl );

        $this->smarty->assign ( 'content' , $this->content );
        $this->smarty->assign ( 'data' , $_REQUEST );

        $script = array_merge (
                $script
                , array ( "{$tplurl}" )
        );

        $style = array_merge (
                array (
            'reset'
            , 'jquery-ui'
            , 'common'
            , 'skin'
            , 'pic.min'
            , 'limit'
            , 'intlTelInput'
                )
                , $style
                , array ( 'color' )
                , array ( 'red.color' )
                , array ( "{$tplurl}" )
        );

        $script = implode ( '|' , $script );
        $style = implode ( '|' , $style );
        $this->smarty->assign ( 'script' , $script );
        $this->smarty->assign ( 'style' , $style );
        $this->smarty->display ( '_layout.tpl' );
    }

}
