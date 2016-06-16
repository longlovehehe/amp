<?php
/**
 * 代理商管理平台
 * @package Agents
 * @author zed
 */
class agents extends db
{

    public function __construct ( $data )
    {
        parent::__construct ();
        $this->data = $data;
    }

    public function get ()
    {
        return $this->data;
    }

    public function set ( $data )
    {
        $this->data = $data;
    }

    /**
     * 返回某一代理商的详细信息
     * @param type $ag_number 代理商ID
     * @return array 某一代理商信息数组
     */
    public function getByid ($ag_number="")
    {
        if($ag_number==""){
            $ag_number=$this->data['ag_number'];
        }
        $sql = 'SELECT * FROM "T_Agents" WHERE ag_number=:ag_number';
        $sth = $this->pdo->prepare ( $sql );
        $sth->bindValue ( ':ag_number' , $ag_number);
        $sth->execute ();
        $result = $sth->fetch ();
        return $result;
    }
    /**
     * 返回某月某一代理商的详细信息（计费相关信息）
     * @param type $ag_number 代理商ID
     * @return array 某一代理商信息数组
     */
    public function getByid_Record ($ar_number="")
    {
        if($ar_number==""){
            if($this->data['ar_number']=="0"){
                
            }else{
                $ar_number=$this->data['ar_number'];
                $sql = 'SELECT * FROM "T_Agents_Record_'.$this->data['start'].'" WHERE ar_number=:ar_number';
                $sth = $this->pdo->prepare ( $sql );
                $sth->bindValue ( ':ar_number' , $ar_number);
                try{
                    $sth->execute ();
                } catch (Exception $exc) {
                    if($exc->getCode()=="42P01"){
                       $result['msg']=L("该月份报表不存在")."。。。(；′⌒`)";
                       $result['status']=-1;
                       return $result;
                    }
                }
                $result = $sth->fetch();
                return $result;
            }
        }else{
            var_dump($this->data);die;
        }
    }

    public function getList ( $limit = "" )
    {
        $sql = 'SELECT * FROM "T_Agents"';
        $sql = $sql . $this->getwhere ( true );
        $sql = $sql . $limit;
        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ( PDO::FETCH_ASSOC );
        return $result;
    }
    public function getList_one ( $limit = "" )
    {
        $sql = "SELECT * FROM \"T_Agents_Record_{$this->data['start']}\" WHERE ar_parent_id='".$_SESSION['ag']['ag_number']."' AND ar_level='1'";
        //$sql = $sql . $this->getwhere ( true );
        //$sql = $sql . $limit;
        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ( PDO::FETCH_ASSOC );
        return $result;
    }
    /**
     * 获取当前代理商下的所属下级代理
     * @param type $limit
     * @return type
     */
    public function getList_ag ( $limit = "" )
    {
        $sql = 'SELECT * FROM "T_Agents"';
        $sql = $sql . $this->getagwhere ( true );
        $sql = $sql . $limit;
        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ( PDO::FETCH_ASSOC );
        return $result;
    }
    public function getagList ( $limit = "" )
    {
        $sql = 'SELECT * FROM "T_Agents"';
        $sql = $sql . $this->getagwhere ( true );
        $sql = $sql . $limit;
        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ( PDO::FETCH_ASSOC );
        return $result;
    }

    public function getwhere ( $order = false )
    {
        $where = " WHERE 1=1 AND ag_path LIKE E'%" . addslashes($this->data['aggents_number']) . "%' AND ag_level > '" . addslashes($this->data['ag_level']) . "'";
        if ( $this->data["ag_number"] != "" )
        {
            $where .= " AND ag_number LIKE E'%" . addslashes(trim ( $this->data["ag_number"] )) . "%'";
        }
        if ( $this->data["ag_name"] != "" )
        {
            $where .= " AND ag_name LIKE E'%" . addslashes(trim ( $this->data["ag_name"] )) . "%'";
        }
        if ( $this->data["ag_id"] != "" )
        {
            $where .= " AND ag_id LIKE E'%" . addslashes(trim ( $this->data["ag_id"] )) . "%'";
        }
        if ( $this->data["ag_phone"] != "" )
        {
            $where .= " AND ag_phone LIKE E'%" . addslashes(trim ( $this->data["ag_phone"] )) . "%'";
        }
        if ( $this->data["ag_mail"] != "" )
        {
            $where .= " AND ag_mail LIKE E'%" . addslashes(trim ( $this->data["ag_mail"] )) . "%'";
        }
        if ( $this->data["ag_admin_name"] != "" )
        {
            $where .= " AND ag_admin_name  LIKE E'%" . addslashes(trim ( $this->data["ag_admin_name"] )) . "%'";
        }
        $area = new area ( $_REQUEST );
        $where .= $area->getAcl ( 'ag_area' , $_SESSION['ag']['ag_area'] );
        if ( $order )
        {
            $where .= ' ORDER BY ag_number';
        }
        return $where;
    }
    public function getagwhere ( $order = false )
    {
        //$where = " WHERE 1=1 AND ag_parent_id LIKE E'%" . $this->data['aggents_number'] . "%'";
        $where = " WHERE 1=1 AND ag_path LIKE E'%|{$this->data['aggents_number']}|%' AND ag_level > '{$this->data['ag_level']}'";
        if ( $this->data["ag_number"] != "" )
        {
            $where .= " AND ag_number LIKE E'%" . addslashes(trim ( $this->data["ag_number"] )) . "%'";
        }

        if ( $this->data["ag_name"] != "" )
        {
            $where .= " AND ag_name LIKE E'%" . addslashes(trim ( $this->data["ag_name"] )) . "%'";
        }
        if ( $this->data["ag_id"] != "" )
        {
            $where .= " AND ag_id LIKE E'%" . addslashes(trim ( $this->data["ag_id"] )) . "%'";
        }
        if ( $this->data["ag_phone"] != "" )
        {
            $where .= " AND ag_phone LIKE E'%" . addslashes(trim ( $this->data["ag_phone"] )) . "%'";
        }
        if ( $this->data["ag_mail"] != "" )
        {
            $where .= " AND ag_mail LIKE E'%" . addslashes(trim ( $this->data["ag_mail"] )) . "%'";
        }
        if ( $this->data["ag_admin_name"] != "" )
        {
            $where .= " AND ag_admin_name = '" . addslashes($this->data["ag_admin_name"]) . "'";
        }
        $area = new area ( $_REQUEST );
        $where .= $area->getAcl ( 'ag_area' , $_SESSION['ag']['ag_area'] );
        if ( $order )
        {
            $where .= ' ORDER BY ag_number';
        }

        return $where;
    }

    public function getTotal ()
    {
        $sql = "SELECT COUNT(ag_number) AS total FROM \"T_Agents\"";

        $sql = $sql . $this->getWhere ();

        $pdoStatement = $this->pdo->query ( $sql );
        $result = $pdoStatement->fetch ();

        return $result["total"];
    }

    /**
      public function getagNum ()
      {
      $sql = 'SELECT nextval(\'"T_Agents_ag_number_seq"\'::regclass)';
      $sth = $this->pdo->query ( $sql );
      $result = $sth->fetch ();
      return $result["nextval"];
      }
     */
    public function getagNum ()
    {
        $ag_parents_id= $_SESSION['ag']['ag_number'];
        $ag_level= $_SESSION['ag']['ag_level']+1;
        $sql = "SELECT ag_number FROM \"T_Agents\" WHERE ag_parent_id='{$ag_parents_id}' AND ag_level='{$ag_level}' ORDER BY  ag_number desc";
        $sth = $this->pdo->query ( $sql );
        $result = $sth->fetchALL ( PDO::FETCH_ASSOC );
        return $result;
    }

    public function save ()
    {
        if ( $this->data["do"] == 'edit' )
        {
            $sql = <<<ECHO
                    UPDATE "T_Agents"
                        SET
                        ag_name=:ag_name,
                        ag_pswd=:ag_pswd,
                        ag_user_num=:ag_user_num,
                        ag_phone_num=:ag_phone_num,
                        ag_dispatch_num=:ag_dispatch_num,
                        ag_gvs_num=:ag_gvs_num,
                        ag_parent_id=:ag_parent_id,
                        ag_path=:ag_path,
                        ag_area=:ag_area,
                        ag_phone=:ag_phone,
                        ag_admin_name=:ag_admin_name,
                        ag_mail=:ag_mail,
                        ag_lastlogin_time=:ag_lastlogin_time,
                        ag_logo=:ag_logo,
                        ag_addr=:ag_addr,
                        ag_username=:ag_username,
                        ag_conname=:ag_conname,
                        ag_fox=:ag_fox
                    WHERE ag_number=:ag_number AND ag_id=:ag_id
ECHO;
            $sth = $this->pdo->prepare ( $sql );
            //$this->data['ag_area'] = "[\"" . $this->data['ag_area'] . "\"]";
            $sth->bindValue ( ':ag_number' , $this->data['ag_number'] );
            $sth->bindValue ( ':ag_id' , $this->data['ag_id'] , PDO::PARAM_STR );
            $sth->bindValue ( ':ag_name' , $this->data['ag_name'] );
            $sth->bindValue ( ':ag_pswd' , $this->data['ag_pswd'] );
            $sth->bindValue ( ':ag_user_num' , $this->data['ag_user_num'] , PDO::PARAM_INT );
            $sth->bindValue ( ':ag_phone_num' , $this->data['ag_phone_num'] , PDO::PARAM_INT );
            $sth->bindValue ( ':ag_dispatch_num' , $this->data['ag_dispatch_num'] , PDO::PARAM_INT );
            $sth->bindValue ( ':ag_gvs_num' , $this->data['ag_gvs_num'] , PDO::PARAM_INT );
            $sth->bindValue ( ':ag_parent_id' , $this->data['ag_parent_id'] , PDO::PARAM_STR );
            $sth->bindValue ( ':ag_path' , $this->data['ag_path'] , PDO::PARAM_STR );
            $sth->bindValue ( ':ag_area' , $this->data['ag_area'] );
            $sth->bindValue ( ':ag_phone' , $this->data['ag_phone'] );
            $sth->bindValue ( ':ag_admin_name' , $this->data['ag_username'].$this->data['ag_conname'] );
            $sth->bindValue ( ':ag_mail' , $this->data['ag_mail'] );
            $sth->bindValue ( ':ag_lastlogin_time' , $this->data['ag_lastlogin_time'] );
            $sth->bindValue ( ':ag_logo' , $this->data['ag_logo'] );
            //$sth->bindValue ( ':ag_level' , $this->data['ag_level'] , PDO::PARAM_STR );
            $sth->bindValue ( ':ag_addr' , $this->data['ag_addr'] , PDO::PARAM_STR );
            $sth->bindValue ( ':ag_username' , $this->data['ag_username'] , PDO::PARAM_STR );
            $sth->bindValue ( ':ag_conname' , $this->data['ag_conname'] , PDO::PARAM_STR );
            $sth->bindValue ( ':ag_fox' , $this->data['ag_fox'] , PDO::PARAM_STR );
             try {
             $sth->execute ();
            } catch (Exception $exc) {
                $log = DL('编辑代理商【%s】%s 失败');
                $log = sprintf ( $log
                          ,$this->data['ag_name']
                          , $this->data['ag_number']
                  );
                  $this->log ( $log , 9 , 0 );
                   $msg['msg'] = L('编辑代理商【%s】%s 失败');
                    $msg['msg'] = sprintf ( $msg['msg']
                           ,$this->data['ag_name']
                           , $this->data['ag_number']
                   );
                    if($exc->getCode()==23505){
                        $msg['msg'] .= " ".L('原因:帐号重复');
                    }
                    $msg['status']=-1;
                    return $msg;
            }
            $log = DL('编辑代理商【%s】%s 成功');
            $log = sprintf ( $log
                      ,$this->data['ag_name']
                      , $this->data['ag_number']
              );
          $this->log ( $log , 9 , 0 );
           $msg['msg'] = L('编辑代理商【%s】%s 成功');
                    $msg['msg'] = sprintf ( $msg['msg']
                           ,$this->data['ag_name']
                           , $this->data['ag_number']
                   );
           $msg['status']=0;
                    return $msg;
        }
        else
        {
            $sql = <<<ECHO
INSERT INTO "T_Agents" (
        "ag_number",
        "ag_id",
        "ag_name",
        "ag_pswd",
        "ag_user_num",
        "ag_phone_num",
        "ag_dispatch_num",
        "ag_gvs_num",
        "ag_parent_id",
        "ag_path",
        "ag_area",
        "ag_status",
        "ag_phone",
        "ag_admin_name",
        "ag_mail",
        "ag_lastlogin_time",
        "ag_logo",
        "ag_level",
        "ag_addr",
        "ag_username",
        "ag_conname",
        "ag_fox",
        "ag_log_stat",
        "ag_create_time"
            )
VALUES (
    :ag_number,
                :ag_id,
                :ag_name,
                :ag_pswd,
                :ag_user_num,
                :ag_phone_num,
                :ag_dispatch_num,
                :ag_gvs_num,
                :ag_parent_id,
                :ag_path,
                :ag_area,
                :ag_status,
                :ag_phone,
                :ag_admin_name,
                :ag_mail,
                :ag_lastlogin_time,
                :ag_logo,
                :ag_level,
                :ag_addr,
                :ag_username,
                :ag_conname,
                :ag_fox,
                :ag_log_stat,
                :ag_create_time
                    );
ECHO;
            if ( $this->data['ag_path'] == "" )
            {
                $this->data['ag_path'] = $this->data['ag_number'];
            }
            else
            {
                $this->data['ag_path'] = $this->data['ag_path'] . "|" . $this->data['ag_number'] . "|";
            }

            $sth = $this->pdo->prepare ( $sql );
            $this->data['ag_area'] = "[\"" . $this->data['ag_area'] . "\"]";
            $sth->bindValue ( ':ag_number' , $this->data['ag_number'] );
            $sth->bindValue ( ':ag_id' , $this->data['ag_id'] , PDO::PARAM_STR );
            $sth->bindValue ( ':ag_name' , $this->data['ag_name'] );
            $sth->bindValue ( ':ag_pswd' , $this->data['ag_pswd'] );
            $sth->bindValue ( ':ag_user_num' , $this->data['ag_user_num'] , PDO::PARAM_INT );
            $sth->bindValue ( ':ag_phone_num' , $this->data['ag_phone_num'] , PDO::PARAM_INT );
            $sth->bindValue ( ':ag_dispatch_num' , $this->data['ag_dispatch_num'] , PDO::PARAM_INT );
            $sth->bindValue ( ':ag_gvs_num' , $this->data['ag_gvs_num'] , PDO::PARAM_INT );
            $sth->bindValue ( ':ag_parent_id' , $this->data['ag_parent_id'] , PDO::PARAM_STR );
            $sth->bindValue ( ':ag_path' , $this->data['ag_path'] , PDO::PARAM_STR );
            $sth->bindValue ( ':ag_area' , $this->data['ag_area'] );
            $sth->bindValue ( ':ag_status' , "0" , PDO::PARAM_STR );
            $sth->bindValue ( ':ag_phone' , $this->data['ag_phone'] );
            $sth->bindValue ( ':ag_admin_name' , $this->data['ag_username'].$this->data['ag_conname'] );
            $sth->bindValue ( ':ag_mail' , $this->data['ag_mail'] );
            $sth->bindValue ( ':ag_lastlogin_time' , date ( "Y-m-d H:i:s" , time () ) );
            $sth->bindValue ( ':ag_logo' , $this->data['ag_logo'] );
            $sth->bindValue ( ':ag_level' , $this->data['ag_level']+1 , PDO::PARAM_STR );
            $sth->bindValue ( ':ag_addr' , $this->data['ag_addr'] , PDO::PARAM_STR );
            $sth->bindValue ( ':ag_username' , $this->data['ag_username'] , PDO::PARAM_STR );
            $sth->bindValue ( ':ag_conname' , $this->data['ag_conname'] , PDO::PARAM_STR );
            $sth->bindValue ( ':ag_fox' , $this->data['ag_fox'] , PDO::PARAM_STR );
            $sth->bindValue ( ':ag_log_stat' , 1 , PDO::PARAM_INT );
            $sth->bindValue ( ':ag_create_time' , date ( "Y-m-d" , time () ) );
          try {
                    $sth->execute ();
                } catch (Exception $exc) {
                    $log = DL('新增代理商【%s】%s 失败');
                                        $log = sprintf ( $log
                              ,$this->data['ag_name']
                              , $this->data['ag_number']
                      );
                    $this->log ( $log , 9 , 0 );
                    $msg['msg'] = L('新增代理商【%s】%s 失败');
                    $msg['msg'] = sprintf ( $msg['msg']
                           ,$this->data['ag_name']
                           , $this->data['ag_number']
                   );
                    if($exc->getCode()==23505){
                        $msg['msg'] .= " ".L('原因:帐号重复');
                    }
                    $msg['status']=-1;
                    return $msg;
                }
                $this->initlog();
                $log = DL('新增代理商【%s】%s 成功');
                $log = sprintf ( $log
                          ,$this->data['ag_name']
                          , $this->data['ag_number']
                  );
          $this->log ( $log , 9 , 0 );
          $msg['msg'] = L('新增代理商【%s】%s 成功');
                    $msg['msg'] = sprintf ( $msg['msg']
                           ,$this->data['ag_name']
                           , $this->data['ag_number']
                   );
           $msg['status']=0;
                    return $msg;
        }
    }

    /**
      public function delList ( $list )
      {

      $list = str_replace ( "," , "','" , "'" . $list );
      $list = rtrim ( $list , ",'" );
      $list .= "'";
      $sql = 'DELETE FROM "T_Agents" WHERE "T_Agents".ag_number IN (' . $list . ')';
      $count = $this->pdo->exec ( $sql );
      $log = '删除了企业管理员【%s】企业ID【%s】';
      $listarr = explode ( $log , $list );
      foreach ( $listarr as $value )
      {
      $log = sprintf ( $log
      , str_replace ( "'" , '' , $value )
      , $_REQUEST['e_id']
      );
      }
      $this->log ( $log , 1 , 1 );
      return $count;
      }
     */
    public function delList ( $list )
    {

        $count = 0;
        foreach ( $list as $value )
        {
            $this->data['ag_number'] = $value;
            $result = $this->delAgents ();
            $count += $result['u'];
        }
        return $count;
    }

    public function delAgents ()
    {
        /**
          $log = '删除企业用户【%s】成功';
          $log = sprintf ( $log
          , $this->data['u_number']
          );
          $this->log ( $log , db::USER , db::INFO );
         */
        $ag_name=$this->getByid($this->data['ag_number']);
        $count = array ();
        $sql = 'DELETE FROM "%s" WHERE ag_number = %s';
        $sql = sprintf ( $sql , 'T_Agents' , sprintf ( "'%s'" , $this->data['ag_number'] ) );
        // $sql = sprintf ( "'%s'" , $this->data['ag_number'] );
        
        try {
           $count['u'] = $this->pdo->exec ( $sql ); 
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        
        $log = DL('删除代理商【%s】%s成功');
        $log = sprintf ( $log
                  ,$ag_name['ag_name']
                  , $this->data['ag_number']
          );
          $this->log ( $log , db::USER , db::INFO );
        /*
          $sql = 'DELETE FROM "%s" WHERE pm_number = %s';
          $sql = sprintf ( $sql , 'T_PttMember_' . $this->data['e_id'] , sprintf ( "'%s'" , $this->data['u_number'] ) );
          $count['p'] = $this->pdo->exec ( $sql );
         *
         */
        return $count;
    }

    public function getoptionlist ()
    {
        $this->data['ag_number'] = $_SESSION['ag']['ag_number'];
        $sql = 'SELECT * FROM "T_Agents" WHERE ag_parent_id=\'' . $this->data['ag_number'] . '\'';
        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ( PDO::FETCH_ASSOC );
        foreach ( $result as $key => $val )
        {
            //获取当前代理商所有企业对应的用户数
            $ep = new enterprise ( array ( 'ag_number' => $val['ag_number'] ) );
            $info = $ep->getList ();
            $phone_num = 0;
            $dispatch_num = 0;
            $gvs_num = 0;
            foreach ( $info as $k => $v )
            {
                $phone_num +=$v['e_mds_phone'];
                $dispatch_num +=$v['e_mds_dispatch'];
                $gvs_num +=$v['e_mds_gvs'];
            }
            //获取当前代理商所有下级代理对应的用户数
            $agents = new agents ( array ( 'aggents_number' => $val['ag_number'] , 'ag_level' => $val['ag_level'] ) );
            $info_ag = $agents->getList ();
            foreach ($info_ag as $kk => $vv )
            {
                $phone_num +=$vv['ag_phone_num'];
                $dispatch_num +=$vv['ag_dispatch_num'];
                $gvs_num +=$vv['ag_gvs_num'];
            }
            $result[$key]['ag_e_phone'] = $phone_num;
            $result[$key]['ag_e_dispatch'] = $dispatch_num;
            $result[$key]['ag_e_gvs'] = $gvs_num;
        }
        return $result;
    }
    /*
      public function getoptionlist ()
      {
        $this->data['ag_number'] = 0;
        $sql = 'SELECT * FROM "T_Agents" WHERE ag_parent_id = \'' . $this->data['ag_number'] . '\'';
        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ( PDO::FETCH_ASSOC );
        foreach ( $result as $key => $val )
        {
            //获取当前代理商所有企业对应的用户数
            $ep = new enterprise ( array ( 'ag_number' => $val['ag_number'] ) );
            $info = $ep->getList_ag ();
            $phone_num = 0;
            $dispatch_num = 0;
            $gvs_num = 0;
            foreach ( $info as $k => $v )
            {
                $phone_num +=$v['e_mds_phone'];
                $dispatch_num +=$v['e_mds_dispatch'];
                $gvs_num +=$v['e_mds_gvs'];
            }
            //获取当前代理商所有下级代理对应的用户数
      $agents = new agents ( array ( 'aggents_number' => $val['ag_number'] ) );
      $info_ag = $agents->getList ();

            foreach ( $info_ag as $kk => $vv )
            {
                $phone_num +=$vv['ag_phone_num'];
                $dispatch_num +=$vv['ag_dispatch_num'];
                $gvs_num +=$vv['ag_gvs_num'];
            }


            $result[$key]['ag_e_phone'] = $phone_num;
            $result[$key]['ag_e_dispatch'] = $dispatch_num;
            $result[$key]['ag_e_gvs'] = $gvs_num;
        }

        return $result;
    }
     */
    
     /**
     * 创建代理商副表 代理商日志
     */
    public function initlog($ag_number=""){
        if ($ag_number == "") {
               $ag_number=$this->data['ag_number'];
        }
        if ($ag_number == "") {
                throw new Exception("Incorrect agents Numbers", -1);
        }
        
        $dc_elsql = '
            DROP TABLE
            IF EXISTS "public"."T_EventLog_:ag_number";

            CREATE TABLE "public"."T_EventLog_:ag_number" (
            "el_id" serial NOT NULL,
            "el_type" varchar(16),
            "el_level" int4,
            "el_time" timestamp(6),
            "el_content" varchar(1024),
            "el_user" varchar(64)
            )
            WITH (OIDS=FALSE)
            ;';
        $dc_elsql = str_replace(":ag_number", $ag_number, $dc_elsql);
        try
            {
//开启一个事务
                    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $this->pdo->beginTransaction();
                    //$this->pdo->exec($dc_usql);
                    $this->pdo->exec($dc_elsql);
                    $this->pdo->commit();
            } catch (Exception $ex) {
                    $this->pdo->rollBack();
                    throw new Exception("Create failure, data rollback" . $ex->getMessage(), -2);
            }

            $msg["status"] = 0;
            $msg["msg"] = L("初始化成功");
            return $msg;
}
public function get_can_name(){
            $sql="SELECT * FROM \"T_Agents\" WHERE ag_number = '{$this->data['name']}' OR ag_name='{$this->data['name']}'";
            $sql1="SELECT * FROM \"T_Agentsub\" WHERE as_account_id = '{$this->data['name']}'";
            $stat=$this->pdo->query($sql);
            $stat1=$this->pdo->query($sql1);
            $result = $stat->fetchAll ();
            $result1 = $stat1->fetchAll ();
            if(count($result)==0&&count($result1)==0 || $this->data['ag_number']==$result[0]['ag_number']){
                return true;
            }else{
                return FALSE;
            }
//            if($result)
        }
}
