<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of agentsub
 *
 * @author zed
 */
class agentsub extends db{
    //put your code here
    public $sql='SELECT * FROM "T_Agentsub"';
    
    public function __construct($data) {
        parent::__construct();
        $this->data = $data;
    }
    /**
     * 获得某条帐户的信息
     * @return type
     */
    public function getById(){
        $sql=<<<ECHO
                SELECT * FROM "T_Agentsub" WHERE as_account_id='{$this->data['as_account_id']}'
ECHO;
         //$sth = $this->pdo->prepare($sql);
         $sth = $this->pdo->query($sql);
         $result = $sth->fetch();
         return $result;
    }
    /**
     * 获得符合筛选条件的所有帐户信息
     * @param type $limit
     * @return type
     */
    public function getList($limit=""){
        $sql =<<<SQL
              SELECT * FROM "T_Agentsub"
SQL;
        $sql=$sql.$this->getWhere();
        $sql = $sql.$limit;
        $sth=  $this->pdo->query($sql);
        //$sth->query($sql);
        $result= $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    /**
     * sql语句查询WHERE条件
     * @param boolean $order
     * @return string
     */
    public function getWhere($order=false){
        $where =" WHERE 1=1";
        if($this->data['as_account_id']!=""){
            $where.=" AND as_account_id LIKE '%{$this->data['as_account_id']}%'";
        }
        if($this->data['as_phone']!=""){
             $where.=" AND as_phone LIKE '%{$this->data['as_phone']}%'";
        }
        if($this->data['as_mail']!=""){
             $where.=" AND as_mail LIKE '%{$this->data['as_mail']}%'";
        }
        if ( $this->data["start"] != "" || $this->data["end"] != "" )
        {
            $where .= 'AND as_lastlogin_time ' . getDateRange_string ( $this->data["start"] , $this->data["end"] );
        }
        $where.=" AND as_parent_id='{$_SESSION['ag']['ag_number']}'";
        if($order){
            $where.="  ORDER BY as_account_id";
        }
        
        return $where;
    }
    
    public function save(){
        $edit=false;
        if($this->data['do']=="edit"){
            $edit=true;
        }
        if($edit){
            $log=DL("修改")." ";
            $msg['msg']=L("修改")." ";
            $sql=<<<SQL
                    UPDATE "T_Agentsub" SET
                        
                        as_passwd=:as_passwd,
                        as_lastname=:as_lastname,
                        as_username=:as_username,
                        as_mail=:as_mail,
                        as_phone=:as_phone,
                        as_desc=:as_desc
                    WHERE as_account_id=:as_account_id
SQL;
          $sth=$this->pdo->prepare($sql);
        }else{
            $log=DL("新增")." ";
            $msg['msg']=L("新增")." ";
             $sql = <<<ECHO
INSERT INTO "T_Agentsub" (
        "as_account_id",
        "as_passwd",
        "as_lastname",
        "as_username",
        "as_mail",
        "as_phone",
        "as_desc",
        "as_create_time",
        "as_parent_id",
        "as_login_type"
            )
VALUES (
    :as_account_id,
                :as_passwd,
                :as_lastname,
                :as_username,
                :as_mail,
                :as_phone,
                :as_desc,
                :as_create_time,
                :as_parent_id,
                :as_login_type
                    );
ECHO;
           $sth=$this->pdo->prepare($sql);
           $sth->bindValue(":as_create_time",  date ( "Y-m-d H:i:s" , time () ),PDO::PARAM_INT);
           $sth->bindValue(":as_parent_id",  $_SESSION['ag']['ag_number']);
           $sth->bindValue(":as_login_type",  'AG_SUB');
        }
          
           $sth->bindValue(":as_passwd",  $this->data['as_passwd']);
           $sth->bindValue(":as_lastname",  $this->data['as_lastname']);
           $sth->bindValue(":as_username",  $this->data['as_username']);
           $sth->bindValue(":as_mail",  $this->data['as_mail']);
           $sth->bindValue(":as_phone",  $this->data['as_phone']);
           $sth->bindValue(":as_desc",  $this->data['as_desc']);
           $sth->bindValue(":as_account_id",  $this->data['as_account_id']);
           
           
            try {
                $sth->execute ();
            } catch (Exception $exc) {
                $log = $log.DL('代理商子帐号【%s】 失败');
                        $log = sprintf ( $log
                                 ,$this->data['as_account_id']
                         );
                       $this->log ( $log , 9 , 0 );
                       $msg['msg'] = $msg['msg'].L('代理商子帐号【%s】失败').$exc->getMessage();
                       $msg['msg'] = sprintf ( $msg['msg']
                              ,$this->data['as_account_id']
                      );
                       if($exc->getCode()==23505){
                           $msg['msg'] .= " ".L('原因:帐号重复');
                       }
                       $msg['status']=-1;
                       return $msg;
            }
        $log = $log.DL('代理商子帐号【%s】成功');
                $log = sprintf ( $log
                          ,$this->data['as_account_id']
                  );
          $this->log ( $log , 9 , 0 );
          $msg['msg'] = $msg['msg'].L('代理商子帐号【%s】成功');
                    $msg['msg'] = sprintf ( $msg['msg']
                           ,$this->data['as_account_id']
                   );
           $msg['status']=0;
                    return $msg;
        }
        
        public function getTotal(){
        $sql = "SELECT COUNT(as_account_id) AS total FROM \"T_Agentsub\"";

        $sql = $sql . $this->getWhere ();

        $pdoStatement = $this->pdo->query ( $sql );
        $result = $pdoStatement->fetch ();

        return $result["total"];
        }
        
        public function delList($list){
             $count = 0;
            foreach ( $list as $value )
            {
                $this->data['as_account_id'] = $value;
                $result = $this->delSub ();
                $count += $result['u'];
            }
            return $count;
        }
        
        public function delSub(){
            //$sql="DELETE FROM \"T_Agentsub\" WHERE as_account_id='{$this->data['as_account_id']}'";
            $count = array ();
            $sql = 'DELETE FROM "%s" WHERE as_account_id = %s';
            $sql = sprintf ( $sql , 'T_Agentsub' , sprintf ( "'%s'" , $this->data['as_account_id'] ) );
            try {
               $count['u'] = $this->pdo->exec ( $sql ); 
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
             $log = DL('删除代理商子帐户【%s】成功');
            $log = sprintf ( $log
                      , $this->data['as_account_id']
              );
          $this->log ( $log , db::USER , db::INFO );

          return $count;
        }
        
        public function get_can_name(){
            $sql="SELECT * FROM \"T_Agents\" WHERE ag_number = '{$this->data['name']}' OR ag_name='{$this->data['name']}'";
            $sql1="SELECT * FROM \"T_Agentsub\" WHERE as_account_id = '{$this->data['name']}'";
            $stat=$this->pdo->query($sql);
            $stat1=$this->pdo->query($sql1);
            $result = $stat->fetchAll ();
            $result1 = $stat1->fetchAll ();
            if(count($result)==0&&count($result1)==0){
                return true;
            }else{
                return FALSE;
            }
//            if($result)
        }
}
