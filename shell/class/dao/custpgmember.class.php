<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of custpgmember
 *
 * @author zed
 */
class custpgmember extends db
{
    public function __construct ( $data )
    {
        parent::__construct ();
        $this->data = $data;
    }

    public function getbyid ()
    {
        $e_id = $this->data["e_id"];
        $table = "T_Custom_PttGrp_$e_id";
        $sql = "SELECT * FROM \"$table\" WHERE c_pg_number = :c_pg_number";
        $sth = $this->pdo->prepare ( $sql );
        $sth->bindValue ( ':c_pg_number' , $this->data["c_pg_number"] , PDO::PARAM_INT );
        $sth->execute ();
        $data = $sth->fetch ();
        return $data;
    }

    public function getcustPgname ( $number )
    {
        $e_id = $this->data["e_id"];
        $cmtable = sprintf ( '"T_Custom_PttGrp_%s"' , $e_id );
        $sql = "SELECT
                        *
                FROM
                        :cmtable
        ";

        $sql = str_replace ( ":cmtable" , $cmtable , $sql );
        //$sql = $sql . $this->getWhere(true);

        $sth = $this->pdo->query ( $sql );
        $result = $sth->fetchAll ();
        foreach ( $result as $key => $val )
        {
            if ( strpos ( $val['c_pg_mem_list'] , $number ) !== false )
            {
                $cust_pgname[] = $val['c_pg_name'];
            }
        }
        return $cust_pgname;
    }

}
