<?php

/**
 * 终端实体类
 * @category OMP
 * @package OMP_terminal_dao
 * @require {@see db}
 */
class terminal extends db {

    public function __construct($data) {
            parent::__construct();
            $this->data = $data;
    }

    //判断meid 是否已存在
    public function getById_list_meid(){
        $sql = <<<SQL
SELECT * FROM
        "T_MobileDevice"
                WHERE md_meid='{$this->data['md_meid']}'
SQL;
$sql1="SELECT * FROM \"T_User\" WHERE u_meid='{$this->data['md_meid']}'";
        $sth = $this->pdo->query($sql);
        $sth1 = $this->pdo->query($sql1);
        $result = $sth->fetch();
        $result1 = $sth1->fetch();

        if($result==false&&$result1==false){
            return true;
        }else{
            if($result1!=false){
                return false;
            }else{
                if($result!=false&&$result['md_id']==$this->data['md_id']||$result==false){
                    return true;
                }else{
                   return false; 
                }
            }
            
        }
    }
    
    public function getselect_list(){
        $sql = <<<SQL
SELECT * FROM
        "T_MobileDevice"
                WHERE md_imei='{$this->data['md_imei']}'
SQL;
        $sth = $this->pdo->query($sql);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //批量导入时验证IMEI是否存在
    public function checkexcel_imei($imei){
        $sql = 'SELECT * FROM "T_MobileDevice" WHERE md_imei=\'' . $imei . '\'';
        $sth = $this->pdo->query ( $sql );
        return $sth->fetch ( PDO::FETCH_ASSOC );
    }

    //批量导入时验证MEID是否存在
    public function checkexcel_meid($meid){
        $sql = 'SELECT * FROM "T_MobileDevice" WHERE md_meid=\'' . $meid . '\'';
        $sth = $this->pdo->query ( $sql );
        return $sth->fetch ( PDO::FETCH_ASSOC );
    }

    public function getList($limit=""){
       $sql = <<<SQL
SELECT * FROM
        "T_TerminalType"
         ORDER BY tt_type
SQL;
       $sql.=$limit;
       $sth = $this->pdo->query($sql);
        $result = $sth->fetchAll();
        return $result;
    }
  
    public function get_thid(){
        $sql = 'SELECT nextval(\'"T_TerminalHistory_th_id_seq"\'::regclass)';
        $sth = $this->pdo->query($sql);
        $result = $sth->fetch();
        return $result["nextval"];
    }
    
    public function term_del(){
        $sql="DELETE FROM \"T_MobileDevice\" WHERE md_imei='{$this->data['md_imei']}'";
        $res=$this->pdo->exec($sql);
        return $res;
    }
     
    public function batch_del_term(){
        $sql="DELETE FROM \"T_MobileDevice\" WHERE md_imei='{$this->data['md_imei']}'";
        $res=$this->pdo->exec($sql);
        return $res;
    }
    public function term_del_type(){
        $sql="DELETE FROM \"T_TerminalType\" WHERE tt_type='{$this->data['tt_type']}'";
        $res=$this->pdo->exec($sql);
        return $res;
    }
    
    /**
     * 判断终端类型是否被使用
     * @return type
     */
    public function get_type_used($md_type){
        $sql="SELECT COUNT(md_id) as total FROM \"T_MobileDevice\" WHERE md_type=:md_type";
        $sth=$this->pdo->prepare($sql);
        $sth->bindValue(':md_type',  $md_type);
        $sth->execute();
        $list=$sth->fetch(PDO::FETCH_ASSOC);
        return $list['total'];
    }
    /**
     * 批量修改终端类型接口
     * @return type
     */
    public function save_term_type(){
        $sql=<<<SQL
UPDATE "T_MobileDevice" SET "md_type"=:md_type,"md_parent_ag"=:md_parent_ag WHERE md_imei=:md_imei
SQL;
        $sth=$this->pdo->prepare($sql);
        $sth->bindValue(':md_type',  $this->data['md_type']);
        $sth->bindValue(':md_parent_ag',  $this->data['md_parent_ag']);
        $sth->bindValue(':md_imei',  $this->data['md_imei']);
        $sth->execute();
    }

    //迁移企业所属时更改终端绑定的代理商
    public function move_enterprise_term_bind(){
        $imei = $this->data['md_imei'];
        $agent_id = $this->data['md_parent_ag'];
        $sql="UPDATE \"T_MobileDevice\" SET md_parent_ag='{$agent_id}'  WHERE md_imei='{$imei}'";
        $res=$this->pdo->exec($sql);
    }
    
    /**
     * 检查终端类型名称是否重复
     * @return type
     */
    public function check_type_name(){
        $sql="SELECT * FROM \"T_TerminalType\" WHERE tt_type=:tt_type";
        $sth=$this->pdo->prepare($sql);
        $sth->bindValue(':tt_type', $this->data['tt_type']);
        $sth->execute();
        $list=$sth->fetch(PDO::FETCH_ASSOC);
        return $list;
    }
    
    public function get() {
            return $this->data;
    }
    public function set($data) {
            $this->data = $data;
    }
    /**
     * 终端解除绑定
     * @return type
     */
    public function releaseBound(){
        $sql=<<<SQL
            UPDATE "T_MobileDevice" SET
                "md_binding"=:md_binding,
                "md_binding_user"=:md_binding_user,
                "md_ent_id"=:md_ent_id,
                "md_status"=:md_status,
                "md_gis_mode"=:md_gis_mode,
                "md_binding_time"=:md_binding_time
            WHERE
                    md_imei=:md_imei
SQL;
        $sth=$this->pdo->prepare($sql);
        $sth->bindValue(':md_binding', 0,PDO::PARAM_INT);
        $sth->bindValue(':md_binding_user', NULL);
        $sth->bindValue(':md_ent_id', NULL);
        $sth->bindValue(':md_status', 0,PDO::PARAM_INT);
        $sth->bindValue(':md_gis_mode', 0,PDO::PARAM_INT);
        $sth->bindValue(':md_binding_time', NULL);
        $sth->bindValue(':md_imei', $this->data['md_imei']);

        try{
            $sth->execute();
            $msg['status']=0;
            $msg['msg']=L('终端解除绑定 , 成功');
        }  catch (Exception $ex){
            $msg['status']=-1;
            $msg['msg']=L('终端解除绑定 , 失败');
            echo $ex->getMessage();
        }

        return $msg;
    }
    /**
     * 终端绑定
     * @return type
     */
    public function terminalBound(){
        $sql=<<<SQL
            UPDATE "T_MobileDevice" SET
                "md_binding"=:md_binding,
                "md_binding_user"=:md_binding_user,
                "md_ent_id"=:md_ent_id,
                "md_status"=:md_status,
                "md_gis_mode"=:md_gis_mode,
                "md_binding_time"=:md_binding_time
            WHERE
                    md_imei=:md_imei
SQL;
        $sth=$this->pdo->prepare($sql);
        $sth->bindValue(':md_binding', 1 ,PDO::PARAM_INT);
        $sth->bindValue(':md_binding_user', $this->data['md_binding_user']);
        $sth->bindValue(':md_ent_id', $this->data['md_ent_id'],PDO::PARAM_INT);
        $sth->bindValue(':md_gis_mode',  $this->data['md_gis_mode'],PDO::PARAM_INT);
        $sth->bindValue(':md_binding_time',  date('Y-m-d',time()),PDO::PARAM_INT);
        $sth->bindValue(':md_status', 1,PDO::PARAM_INT);
        $sth->bindValue(':md_imei', $this->data['md_imei']);

        try{
            $sth->execute();
            $msg['status']=0;
            $msg['msg']=L('终端绑定 , 成功');
        }  catch (Exception $ex){
            $msg['status']=-1;
            $msg['msg']=L('终端绑定 , 失败');
        }

        return $msg;
    }

    /**
     * 生成终端历史记录
     * @param $info
     */
    public function create_terminal_history($info,$stat){
        $th_id=$this->get_thid();
        $sql=<<<echo
        INSERT INTO "T_TerminalHistory" (
            "th_id",
            "th_imei",
            "th_e_id",
            "th_e_name",
            "th_u_number",
            "th_u_name",
            "th_u_iccid",
            "th_u_imsi",
            "th_status",
            "th_change_time",
            "th_md_type",
            "th_md_serial_number",
            "th_stat",
            "th_u_mobile_phone",
            "th_meid"
        ) VALUES(
            :th_id,
            :th_imei,
            :th_e_id,
            :th_e_name,
            :th_u_number,
            :th_u_name,
            :th_u_iccid,
            :th_u_imsi,
            :th_status,
            :th_change_time,
            :th_md_type,
            :th_md_serial_number,
            :th_stat,
            :th_u_mobile_phone,
            :th_meid
        )
echo;
        $sth=$this->pdo->prepare($sql);
        $sth->bindValue(":th_id",$th_id,PDO::PARAM_INT);
        $sth->bindValue(":th_imei",$this->data['th_imei']);
        $sth->bindValue(":th_e_id",$this->data['th_e_id'],PDO::PARAM_INT);
        $sth->bindValue(":th_e_name",$this->data['th_e_name']);
        $sth->bindValue(":th_u_number",$this->data['th_u_number']);
        $sth->bindValue(":th_u_name",$this->data['th_u_name']);
        $sth->bindValue(":th_u_iccid",$this->data['th_u_iccid']);
        $sth->bindValue(":th_u_imsi",$this->data['th_u_imsi']);
        $sth->bindValue(":th_status",$info);
        $sth->bindValue(":th_change_time",time());
        $sth->bindValue(":th_md_type",$this->data['th_md_type']);
        $sth->bindValue(":th_md_serial_number",$this->data['th_md_serial_number']);
        $sth->bindValue(":th_stat",$stat,PDO::PARAM_INT);
        $sth->bindValue(":th_u_mobile_phone",$this->data['th_u_mobile_phone']);
        $sth->bindValue(":th_meid",$this->data['th_meid']);
        $sth->execute();
    }
    /**
     * 终端历史记录 ID
     * @param $imei
     * @return mixed
     */
    public function ter_historyById($imei){
        $sql=<<<echo
       SELECT * FROM "T_MobileDevice" WHERE md_imei='$imei'
echo;
        $sth=$this->pdo->query($sql);
        $res=$sth->fetch();
        $user_id=$res['md_binding_user'];
        $users=new users(array('u_number'=>$user_id));
        $info=$users->getById_history();

        return $info;
    }
    /**
     * 删除终端历史记录
     */
    public function delete_term_history(){
        $sql="DELETE FROM \"T_TerminalHistory\" WHERE th_imei='{$this->data['th_imei']}'";
        $sth=$this->pdo->prepare($sql);
        $sth->execute();
    }
    /**
     * 生成历史纪录通用接口
     * @param type $res
     * @param type $info
     */
     public function set_term_history($res,$info=""){
            $data['th_imei']=$res['md_imei'];
            $data['th_e_id']=$res['e_id'];
            $data['th_e_name']=$res['e_name'];
            $data['th_u_number']=$res['u_number'];
            $data['th_u_name']=$res['u_name'];
            $data['th_u_iccid']=$res['g_iccid'];
            $data['th_u_imsi']=$res['g_imsi'];
            $data['th_md_type']=$res['md_type'];
            $data['th_md_serial_number']=$res['md_serial_number'];
            $data['th_u_mobile_phone']=$res['g_number'];
            $data['th_meid']=$res['th_meid'];
            if($info==""){
                $info=$res['md_status']=="0"?"stop":"start";
            }
            $this->set($data);
            $this->create_terminal_history($info,1);
    }

}
