<?php

/**
 * 分页器
 * @category OMP
 * @package OMP_Common
 */
class page {

    private $page; // 页码
    private $num; // 显示数
    private $total; // 总数
    private $pages; //页数

    public function fastGetPage ( $sql , $pdo , $data )
    {
        $sth = $pdo->query ( $sql );
        $result = $sth->fetch ();

        $this->setData ( $data );
        $this->setTotal ( $result["total"] );
        $page["total"] = $result["total"];
        $page["limit"] = $this->getLimit ();
        $page["numinfo"] = $this->getNumInfo ();
        $page["prev"] = $this->getPrev ();
        $page["next"] = $this->getNext ();
        return $page;
    }

    public function page ( $data = null )
    {
        if ( ! is_null ( $data ) )
        {
            $this->init ( $data["page"] , $data["num"] , $data["total"] );
        }
    }

    public function setData ( $data )
    {
        $this->init ( $data["page"] , $data["num"] , $data["total"] );
    }

    private function init ( $page , $num , $total )
    {
        $this->page = $page == "" ? 0 : $page;
        $this->num = $num == "" ? 10 : $num;
        $this->total = $total == "" ? 1 : $num;
        $this->pages = ceil ( $this->total / $this->num );
    }

    public function getLimit ()
    {
        $sql = " LIMIT " . $this->num . " OFFSET " . $this->page * $this->num;
        return $sql;
    }

    public function setTotal ( $total )
    {
        $this->total = $total;
        $this->pages = ceil ( $this->total / $this->num );
    }

    public function getNumInfo ()
    {
        $page = $this->getPage () + 1;
        $pages = $this->getPages ();
        $total = $this->getTotal ();
        if ( $_COOKIE['lang'] == "en_US" )
        {
            $info = ' <span class="totalpages">%s</span> / <span class="pages">%s</span>  total  %s ';
        } else if($_COOKIE['lang'] == "zh_TW"){
           $info = '第<span class="totalpages">%s</span>頁，共<span class="pages">%s</span>頁，總數%s個';
        }
        else
        {
            $info = '第 <span class="totalpages">%s</span> 页，共 <span class="pages">%s</span> 页，总数 %s 个';

        }
        //$info = L ( '第 %s 页，共 %s 页，总数 %s 个' , FALSE );
        $info = sprintf ( $info , $page , $pages , $total );
        return $info;
    }

    public function total ()
    {
        $total = $this->getTotal ();
        $info = L ( '共 %s 个' , false );
        $info = sprintf ( $info , $total );
        return $info;
    }

    public function getPrev ()
    {
        $page = $this->getPage () - 1;
        if ( $page <= 0 )
        {
            return 0;
        }
        else
        {
            return $page;
        }
    }

    public function getNext ()
    {
        if ( $this->pages == 0 )
        {
            return 0;
        }

        $page = $this->getPage () + 1;
        $pages = $this->getPages ();
        if ( $page >= $pages )
        {
            return $pages - 1;
        }
        else
        {
            return $page;
        }
    }

    public function getPage ()
    {
        return $this->page;
    }

    public function getPages ()
    {
        return $this->pages;
    }

    public function getNum ()
    {
        return $this->num;
    }

    public function getTotal ()
    {
        return $this->total;
    }

}
