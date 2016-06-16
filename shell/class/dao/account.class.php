<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account
 *
 * @author zed
 */
class account extends db {

    function __construct($data){
    	parent::__construct(); 
    	$this->data = $data;
    }

/**
 * 递归代理商层级数组组合
 */
function get_array($id=0){ 
    $sql = "SELECT ag_number,ag_name,ag_level FROM \"T_Agents\" WHERE ag_parent_id= '{$id}'"; 
 	$sth = $this->pdo->query ( $sql );
 	$sth->execute ();
    $arr = array(); 
    if($sth->execute () && $sth->rowCount()){//如果有子类 
        while($rows=$sth->fetch(PDO::FETCH_ASSOC)){ //循环记录集 
            $rows['list'] = $this->get_array($rows['ag_number']); //调用函数，传入参数，继续查询下级 
            $arr[]= $rows; //组合数组 
        } 
        return $arr; 
    } 
} 

   
}