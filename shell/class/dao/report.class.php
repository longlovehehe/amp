<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-04-17 10:26:22
 * @version $Id: report.class.php,v 1.3 2015/05/26 07:26:55 lfwang Exp $
 */

class report extends db {

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
/**
 * 获取某日新增人员个数
 */
function getListnum(){
    $sql='SELECT
	*, (
		SELECT
			COUNT (u_number)
		FROM
			"T_User"
		WHERE
                        u_e_id = e_id '.$this->childwhere().' 
	) AS user_num

FROM
	"T_Agents"
LEFT JOIN "T_Enterprise" ON ag_number = e_agents_id';
    $sql.=$sql.$this->getwhere();
}
function childwhere(){
    $where='';
    if($this->data["u_create_time"]){
         $where .="AND u_create_time=".$this->data['u_create_time'];
    }
    if($this->data["u_active_state"]){
        $where .=" AND u_active_state=".$this->data['u_active_state'];
    }
    return $where;
}
function getwhere($order=false){
    $where="WHERE 1=1 ";
    if($this->data['id']!=""){
        $where .="AND e_id=".$this->data['e_id'];
    }
    if($this->data['ag_number']){
        $where .=" AND ag_number=".$this->data['ag_number'];
    }
    return $where;
}
   
}