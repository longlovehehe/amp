<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class gprs extends db
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

    public function getgprsList ()
    {
        $sql = 'SELECT * FROM "T_GprsPackages"';
        $sth = $this->pdo->query ( $sql );
        $result = $sth->fetchAll ( PDO::FETCH_ASSOC );
        return $result;
    }

    /**
     * 获取流量卡信息
     */
    public function getByid ()
    {

        $sql = 'SELECT * FROM "T_Gprs" WHERE g_iccid=\'' . $this->data['g_iccid'] . '\'';
        $sth = $this->pdo->query ( $sql );
        return $sth->fetch ( PDO::FETCH_ASSOC );
    }

    /**
    *
    */
    public function checkexcel($iccid='',$imsi='',$number=''){
        if($number != ''){
            $sql = 'SELECT * FROM "T_Gprs" WHERE g_number=\'' . $number . '\'';
            $sth = $this->pdo->query ( $sql );
            return $sth->fetch ( PDO::FETCH_ASSOC );
        }elseif($imsi != ''){
            $sql = 'SELECT * FROM "T_Gprs" WHERE g_imsi=\'' . $imsi . '\'';
            $sth = $this->pdo->query ( $sql );
            return $sth->fetch ( PDO::FETCH_ASSOC );
        }else{
            $sql = 'SELECT * FROM "T_Gprs" WHERE g_iccid=\'' . $iccid . '\'';
            $sth = $this->pdo->query ( $sql );
            return $sth->fetch ( PDO::FETCH_ASSOC );
        }
    }

    /**
     * 获取流量卡总个数
     *
     */
    public function getGprsTotal ()
    {
        $sql = 'SELECT COUNT(g_iccid) AS total FROM "T_Gprs"';
        $sql = $sql . $this->getwhere ();
        $sth = $this->pdo->query ( $sql );
        $count = $sth->fetch ();
        return $count['total'];
    }

    /**
     * 获取流量卡列表
     */
    //,g_outtime
    public function getList ( $limit = "" )
    {
        $sql = <<<SQL
    SELECT 
        g_id,
        g_iccid,
        g_imsi,
        g_u_number,
        g_number,
        g_agents_id,
        g_stock_status,
        g_binding,g_e_id,
        g_agents_assign,
        g_name,
        g_batch,
        g_a_date,
        g_in_number,
        g_one_remarks,
        g_two_remarks,
        g_status,
        g_delete,
        g_intime,
        ag_name,
        ag_phone,
        e_name
    FROM "T_Gprs" 
        LEFT JOIN "T_Agents" ON ag_number=g_agents_id 
        LEFT JOIN "T_Enterprise" ON e_id=g_e_id 
SQL;
        $sql = $sql . $this->getwhere ( true );
        $sql = $sql . $limit;
        $sth = $this->pdo->query ( $sql );
        return $sth->fetchAll ( PDO::FETCH_ASSOC );
    }

    //获取单条流量卡的详细信息
    public function getOne($id){
        $sql = <<<SQL
SELECT g_id,g_iccid,g_imsi,g_number,g_agents_id,g_binding,g_e_id,g_status,g_intime,ag_name,ag_phone,e_name FROM "T_Gprs" 
LEFT JOIN "T_Agents" ON ag_number=g_agents_id 
LEFT JOIN "T_Enterprise" ON e_id=g_e_id 
SQL;
        $sql = $sql ."WHERE g_iccid ='{$id}'";
        $sth = $this->pdo->query ( $sql );
        $info = $sth->fetch ( PDO::FETCH_ASSOC );
        foreach ($info as $key => $value) {
            if($value==null || $value==''){
               $info[$key] = '';
            }
        }
        /*if($info['g_agents_id']=='0' || $info['g_agents_id']==null || $info['g_agents_id']==''){
            $info['ag_name'] = "OMP";
        }else{
            $agent = getAgents($info['g_agents_id']);
            $info['ag_name'] = $agent['ag_name'];
        }*/
        return $info;
    }
    //获取代理商的信息 T_Agents
    public function getAgents($id){
        $sql = "SELECT * FROM \"T_Agents\" WHERE ag_number='{$id}'";
        $sth = $this->pdo->query ( $sql );
        return $sth->fetch ( PDO::FETCH_ASSOC );
    }

    /**
     * 获取流量卡列表
     */
    public function getList_v2 ( $limit = "" )
    {
        $agent_id = $_SESSION['ag']['ag_number'];
        $sql = 'SELECT * FROM "T_Gprs" LEFT JOIN "T_Agents" ON g_agents_id = ag_number WHERE g_agents_id=\'0\' ORDER BY g_stock_status ASC';
        $sth = $this->pdo->query ( $sql );
        return $sth->fetchAll ( PDO::FETCH_ASSOC );
    }

    /**
     * gprs流量卡入库
     */
    public function save_gprs ()
    {
        if ( $this->data['do'] == 'edit' )
        {   
           /* g_packages=:g_packages,g_start_time=:g_start_time,
                    g_outtime=:g_outtime,g_outtime0=:g_outtime_0,
                    g_intime0=:g_intime_0,
                    g_outtime1=:g_outtime_1,
                    g_intime1=:g_intime_1,
                    g_belong=:g_belong,
                    g_final_user=:g_final_user,
                    g_gprs_status=:g_gprs_status,
                    g_surplus_flow=:g_surplus_flow,
                    g_agents_id=:g_agents_id,
                    g_e_id=:g_e_id,*/
            $sql = <<<SQL
               UPDATE "T_Gprs"
                   SET
                    g_iccid = :g_iccid,
                    g_imsi = :g_imsi,
                    g_number = :g_number,
                    g_intime=:g_intime,
                    g_stock_status=:g_stock_status,
                    g_end_time=:g_end_time,
                    g_agents_assign=:g_agents_assign,
                    g_final_user=:g_final_user,
                    g_edit_time=:g_edit_time,
                    g_name=:g_name,
                    g_batch=:g_batch,
                    g_a_date=:g_a_date,
                    g_in_number=:g_in_number,
                    g_one_remarks=:g_one_remarks,
                    g_two_remarks=:g_two_remarks,
                    g_activation_time=:g_activation_time
            WHERE
                        g_id = :g_id
SQL;
            $sth = $this->pdo->prepare ( $sql );
            $sth->bindValue ( ':g_id' , $this->data['g_id'] );
            $sth->bindValue ( ':g_iccid' , $this->data['g_iccid'] );
            $sth->bindValue ( ':g_imsi' , $this->data['g_imsi'] );
            $sth->bindValue ( ':g_number' , $this->data['g_number'] );
            $sth->bindValue ( ':g_intime' , $this->data['g_intime'] );
            $sth->bindValue ( ':g_stock_status' , $this->data['g_stock_status'] );
            $sth->bindValue ( ':g_end_time' , $this->data['g_end_time'] );
            $sth->bindValue ( ':g_agents_assign' , $this->data['g_agents_assign'] );
            $sth->bindValue ( ':g_final_user' , $_SESSION['own']['om_id'] );
            $sth->bindValue ( ':g_edit_time' , date("Y-m-d H:i:s",time()) );
            $sth->bindValue ( ':g_name' , $this->data['g_name']  );
            $sth->bindValue ( ':g_batch' , $this->data['g_batch'] );
            $sth->bindValue ( ':g_a_date' , $this->data['g_a_date'] );
            $sth->bindValue ( ':g_in_number' , $this->data['g_in_number'] );
            $sth->bindValue ( ':g_one_remarks' , $this->data['g_one_remarks']  );
            $sth->bindValue ( ':g_two_remarks' , $this->data['g_two_remarks']  );
            $sth->bindValue ( ':g_activation_time' , $this->data['g_activation_time'] );
            try {
                $sth->execute();
            } catch (Exception $exc) {
               if($exc->getCode()==23505){
                   $msg['status']=-1;
                   $msg['msg']=L("ICCID已存在");
                   return $msg;
               }
            }
            $msg['status']=0;
            $msg['msg']=L("操作成功");
            $msg['info']=$this->getOne($this->data['g_iccid']);
            return $msg;
        }
        else
        {
            /*"g_start_time",
                    "g_outtime0",
                    "g_outtime",
                    "g_intime0",
                    "g_outtime1",
                    "g_intime1",
                    "g_belong",
                    
                    "g_gprs_status",
                    "g_packages",
                    "g_surplus_flow",*/
                    //var_dump($this->data);die;
            $sql = <<<SQL
                INSERT INTO "T_Gprs" (
                    "g_iccid",
                    "g_imsi",
                    "g_number",
                    "g_agents_id",
                    "g_intime",
                    "g_binding",
                    "g_status",
                    "g_name",
                    "g_batch",
                    "g_a_date",
                    "g_in_number",
                    "g_one_remarks",
                    "g_two_remarks",
                    "g_add_user"
                    )
                VALUES (
                    :g_iccid,
                    :g_imsi,
                    :g_number,
                    :g_agents_id,
                    :g_intime,
                    :g_binding,
                    :g_status,
                    :g_name,
                    :g_batch,
                    :g_a_date,
                    :g_in_number,
                    :g_one_remarks,
                    :g_two_remarks,
                    :g_add_user
                    )
SQL;
            $sth = $this->pdo->prepare ( $sql );
            //$this->data['g_agents_assign'] = "|" . $this->data['g_agents_assign'] . "|";
            $sth->bindValue ( ':g_iccid' , $this->data['g_iccid'] );
            $sth->bindValue ( ':g_imsi' , $this->data['g_imsi'] );
            $sth->bindValue ( ':g_number' , $this->data['g_number'] );
            $sth->bindValue ( ':g_agents_id' , $this->data['g_agents_id'] );
            $sth->bindValue ( ':g_intime' , $this->data['g_intime'] );
            $sth->bindValue ( ':g_binding' , "0" );
            $sth->bindValue ( ':g_status' , "2" );
            $sth->bindValue ( ':g_name' , $this->data['g_name'] );
            $sth->bindValue ( ':g_batch' , $this->data['g_batch'] );
            $sth->bindValue ( ':g_a_date' , $this->data['g_a_date'] );
            $sth->bindValue ( ':g_in_number' , $this->data['g_in_number'] );
            $sth->bindValue ( ':g_one_remarks' , $this->data['g_one_remarks'] );
            $sth->bindValue ( ':g_two_remarks' , $this->data['g_two_remarks'] );
            $sth->bindValue ( ':g_add_user' , $this->data['g_add_user'] );
            try{
                $sth->execute ();
            }catch ( Exception $exc ){
                if ( $exc->getCode () == 23505 )
                {
                    $msg['status']=-1;
                    $msg['msg']=L("ICCID已存在");
                    return $msg;
                }
                echo $exc->getMessage();die;
            }
            $msg['status']=0;
            $msg['msg']=L("添加成功");
            return $msg;
        }
    }

    /**
     * gprs出库
     *
     */
    public function gprsshellout ()
    {

        $sql = <<<SQL
               UPDATE "T_Gprs"
                   SET
                    g_agents_id=:g_agents_id,
                    g_e_id=:g_e_id,
                    g_agents_assign=:g_agents_assign,
                    g_final_user=:g_final_user,
                    g_stock_status = :g_stock_status
            WHERE
                        g_iccid = :g_iccid
SQL;
        $sth = $this->pdo->prepare ( $sql );
        $sth->bindValue ( ':g_iccid' , $this->data['g_iccid'] );
        //$sth->bindValue ( ':g_outtime' , $this->data['g_outtime'] );
        //$sth->bindValue ( ':g_intime_0' , $this->data['g_intime0'] );
        $sth->bindValue ( ':g_agents_id' , $this->data['g_agents_id'] );
        $sth->bindValue ( ':g_e_id' , $this->data['g_e_id'] );
        $sth->bindValue ( ':g_agents_assign' , $this->data['g_agents_assign'] );
        $sth->bindValue ( ':g_final_user' , $this->data['g_final_user'] );
        $sth->bindValue ( ':g_stock_status' , $this->data['g_stock_status'] , PDO::PARAM_INT );

        $sth->execute ();
    }

    public function getwhere ( $order = false )
    { 
        $where = " WHERE 1=1";
        //筛选 g_iccid
        if ( $this->data['g_iccid'] != "" )
        {
            $where.=" AND g_iccid LIKE E'%" . addslashes($this->data['g_iccid']) . "%'";
        }
        //筛选 g_imsi
        if ( $this->data['g_imsi'] != "" )
        {  
            $where.=" AND g_imsi LIKE E'%" . addslashes($this->data['g_imsi']) . "%'";
        }
        //筛选 g_number
        if ( $this->data['g_number'] != "" )
        {
            $where.=" AND g_number LIKE E'%" . addslashes($this->data['g_number']) . "%'";
        }

        if ( $this->data['g_name'] != "" )
        {
            $where.=" AND g_name LIKE E'%" . addslashes($this->data['g_name']) . "%'";
        }
 
        if ( $this->data['g_batch'] != "" )
        {
            $where.=" AND g_batch LIKE E'%" . addslashes($this->data['g_batch']) . "%'";
        }

        if ( $this->data['g_a_date'] != "" )
        {
            $where.=" AND g_a_date LIKE E'%" . addslashes($this->data['g_a_date']) . "%'";
        }

        if ( $this->data['g_in_number'] != "" )
        {
            $where.=" AND g_in_number LIKE E'%" . addslashes($this->data['g_in_number']) . "%'";
        }
        if ( $this->data['g_intime'] != "" )
        {
            $where.=" AND g_intime LIKE E'%" . addslashes($this->data['g_intime']) . "%'";
        }
        //筛选所属企业id
        if($this->data['g_e_id']!=""){
            $where.="AND g_e_id =".$this->data['g_e_id'];
        }
        //筛选所属代理商id
        if($this->data['g_agents_id']!=""){
            $where.="AND g_agents_id ='".$this->data['g_agents_id']."'";
        }
        //筛选 g_status
        if ( $this->data['g_status'] != "" )
        {
            if($this->data['g_status']=="2"){
                $where.=" AND g_binding = '0' ";
            }else{
                $where.=" AND g_status ='".$this->data['g_status']."'";
            }
        }
//        //筛选入库时间
//        if ( $this->data["start"] != "" || $this->data["end"] != "" )
//        {
//            $where .= ' AND g_intime ' . getDateRange ( $this->data["start"] , $this->data["end"] );
//        }
        //筛选 g_u_number
        if ( $this->data['g_u_number'] != "" )
        {
            $where.=" AND g_u_number LIKE E'%" . addslashes($this->data['g_u_number']) . "%'";
        }
        if ( $order )
        {
            $where .= ' ORDER BY g_id DESC';
        }
        return $where;
    }

    //获取流量卡代理商及企业列表
    public function get_alllist(){
        $sql="SELECT g_agents_id,ag_name,g_e_id,e_name FROM \"T_Gprs\" LEFT JOIN \"T_Agents\" ON ag_number=g_agents_id LEFT JOIN \"T_Enterprise\" ON e_id=g_e_id WHERE g_agents_id is not null";
        $sth=$this->pdo->prepare($sql);
        $sth->execute();
        $list=$sth->fetchAll(PDO::FETCH_ASSOC);
        return $list;
    }

    //验证修改的iccid，imsi,number 是否已存在
    public function getById_list(){
        if(isset($this->data['blur']) && $this->data['blur']=='blur'){
            if($this->data['g_iccid'] != ''){
                $sql= "SELECT * FROM \"T_Gprs\" WHERE g_iccid='{$this->data['g_iccid']}'";
                $sth = $this->pdo->query($sql);
                $result = $sth->fetch();
            }else{
                $result==false;
            }
        }else{
            if($this->data['type']=='add'){
                $sql = "SELECT * FROM \"T_Gprs\" WHERE g_iccid='{$this->data['g_iccid']}'";
            }else{
                $sql = "SELECT * FROM \"T_Gprs\" WHERE g_iccid='{$this->data['g_iccid']}' AND g_id!='{$this->data['g_id']}'";
            }
            $sth = $this->pdo->query($sql);        
            $result = $sth->fetch();
        }
        
        

        if($this->data['g_imsi'] != ''){
            if(isset($this->data['type']) && $this->data['type']=='add'){
                $sql1= "SELECT * FROM \"T_Gprs\" WHERE g_imsi='{$this->data['g_imsi']}'";
            }else{
                $sql1= "SELECT * FROM \"T_Gprs\" WHERE g_imsi='{$this->data['g_imsi']}' AND g_id!='{$this->data['g_id']}'"; 
            }
            
            $sth1 = $this->pdo->query($sql1);
            $result1 = $sth1->fetch();
        }else{
            $result1==false;
        }
        
        if($this->data['g_number'] != ''){
            if(isset($this->data['type']) && $this->data['type']=='add'){
                $sql2= "SELECT * FROM \"T_Gprs\" WHERE g_number='{$this->data['g_number']}'";
            }else{
                $sql2= "SELECT * FROM \"T_Gprs\" WHERE g_number='{$this->data['g_number']}' AND g_id!='{$this->data['g_id']}'"; 
            }
            
            $sth2 = $this->pdo->query($sql2);
            $result2 = $sth2->fetch();
        }else{
            $result2==false;
        }

        if($result==false&&$result1==false&&$result2==false){
            return '0';
        }else{
            if($result!=false){
                return 1;
            }elseif($result1!=false){
                return 2;
            }else{
                return 3;
            }  
        }
    }

    //保存流量卡的修改
    public function batch_save() {

        /*$md_id=$this->get_tlid();
        if($this->data['do']=="edit"){  
           $sql=<<<SQL
UPDATE "T_MobileDevice" SET "md_imei"=:md_imei,"md_type"=:md_type,"md_serial_number"=:md_serial_number,"md_parent_ag"=:md_parent_ag WHERE
md_id=:md_id
SQL;
            $sth=$this->pdo->prepare($sql);
            $sth->bindValue(':md_id',  $this->data['md_id'],PDO::PARAM_INT);
        }else{
        $sql=<<<SQL
INSERT INTO "T_MobileDevice" ("md_id","md_imei","md_type","md_time","md_serial_number","md_parent_ag","md_status")
  VALUES(:md_id,:md_imei,:md_type,:md_time,:md_serial_number,:md_parent_ag,:md_status)
SQL;
        $sth=$this->pdo->prepare($sql);
        $sth->bindValue(':md_id',$md_id,PDO::PARAM_INT);
        $sth->bindValue(':md_time',date("Y-m-d H:i:s",time()),PDO::PARAM_INT);
        $sth->bindValue(':md_status',0,PDO::PARAM_INT);
        $sth->bindValue(':md_parent_ag',$this->data['md_parent_ag']);
        }
        //var_dump($this->data);die;
        $sth->bindValue(':md_imei',  $this->data['md_imei']);
        $sth->bindValue(':md_type',  $this->data['md_type']);
        $sth->bindValue(':md_serial_number', $this->data['md_serial_number']);

        //$sth->bindValue(':tl_system_num',$this->data['tl_system_num']);
        try {
            $sth->execute();
        } catch (Exception $exc) {
           if($exc->getCode()==23505){
               $msg['status']=-1;
               $msg['msg']=L("IMEI已存在");
                return $msg;
           }
        }
        $msg['status']=0;
        $msg['msg']=L("操作成功");

        return $msg;*/
    }
//修改流量卡的状态
public function set_stat(){
        //var_dump($this->data);die;
        if($this->data['g_status']=='3'){
            $aRes=$this->getOne($this->data['g_iccid']);
            if($aRes['g_binding'] == '1')
            {
                $msg['status']=-2;
                $msg['msg']=L("删除失败");
            }else{
                $sql="DELETE FROM \"T_Gprs\" WHERE g_iccid=:g_iccid";
                $sth=$this->pdo->prepare($sql);
                $sth->bindValue(":g_iccid",$this->data['g_iccid']);
                try{
                    $sth->execute();
                    $msg['status']=2;
                    $msg['msg']=L("删除成功");
                }catch (Exception $ex){
                    $msg['status']=-2;
                    $msg['msg']=L("删除失败");
                }
                $log="流量卡［ICCID:%s］删除".$msg['msg'];
                $log=sprintf($log,$this->data['g_iccid']);
                $this->log($log);
            }
        }else{
            //设置流量卡 停启用状态
            $sql="UPDATE \"T_Gprs\" SET g_status=:g_status WHERE g_iccid=:g_iccid";
            $sth=$this->pdo->prepare($sql);
            $sth->bindValue(":g_status",$this->data['g_status'],PDO::PARAM_INT);
            $sth->bindValue(":g_iccid",$this->data['g_iccid']);
            try{
                $sth->execute();
                $msg['status']=0;
                $msg['msg']=L("修改成功");
                //保存流量卡记录
                /*******************START***********************/
                $data['gh_iccid']=$this->data['g_iccid'];
                //$user = new users();
                $info=$this->gprs_historyById($this->data['g_iccid']);
                
                if($this->data['g_status']==0){
                    $g_status="stop";
                }else if($this->data['g_status']==1){
                    $g_status="start";
                }else{
                    $g_status="";
                }
                $data['gh_e_id']=$info['e_id'];
                $data['gh_e_name']=$info['e_name'];
                $data['gh_u_number']=$info['u_number'];
                $data['gh_u_name']=$info['u_name'];
                $data['gh_md_imei']=$info['md_imei'];
                $data['gh_md_type']=$info['md_type'];
                $this->set($data);
                $this->set_gprs_history($g_status,1);
                 /*******************END***********************/
                /**
                 * 用户记录保存
                 */
                 /*******************START***********************/
                $user=new users();
                if($info['md_status']==1){
                    $md_status="start";
                }else if($info['md_status']==0){
                    $md_status=DL("stop");
                }else{
                    $md_status="";
                }
                
                if($info['g_status']==1){
                    $g_status="start";
                }else if($info['g_status']==0){
                    $g_status="stop";
                }else{
                    $g_status="";
                }
                
                $user_data['uh_md_imei']=$info['md_imei'];
                $user_data['uh_md_type']=$info['md_type'];
                $user_data['uh_md_status']=$md_status;
                $user_data['uh_gp_iccid']=$info['u_iccid'];
                $user_data['uh_gp_imsi']=$info['u_imsi'];
                $user_data['uh_gp_mobile']=$info['u_mobile_phone'];
                $user_data['uh_gp_status']=$g_status;
                //$user_data['uh_user_status']=$info['u_active_state'];
                $user_data['uh_u_name']=$info['u_name'];
                $user_data['uh_u_number']=$info['u_number'];
               // var_dump($user_data);die;
                $user->set($user_data);
                $user->set_user_history($info['u_active_state']==0?"stop":"start", 1);
            
            }catch (Exception $ex){
                $msg['status']=-1;
                $msg['msg']=L("修改失败");
            }
            $log="流量卡［ICCID:%s］状态修改为 %s";
            $log=sprintf($log,$this->data['g_iccid'],$this->data['g_status']==0?DL("停用"):DL("启用"));
            $this->log($log);
        }
        return $msg;
    }
    //批量删除流量卡
    public function gprs_del(){
        $sql="DELETE FROM \"T_Gprs\" WHERE g_id='{$this->data['g_id']}'";
        $res=$this->pdo->exec($sql);
        return $res;
    }
    public function getselect_list(){
        $sql = <<<SQL
SELECT * FROM
        "T_Gprs"
                WHERE g_id='{$this->data['g_id']}'
SQL;
        $sth = $this->pdo->query($sql);
        $result = $sth->fetch();
        return $result;
    }

    //获取流量卡历史纪录总数
    public function getTotal_history($flag = TRUE) {
            $sql = "SELECT COUNT(gh_iccid) AS total FROM \"T_GprsHistory\" WHERE gh_iccid='{$this->data['gh_iccid']}'";

            $pdoStatement = $this->pdo->query($sql);
            $result = $pdoStatement->fetch();

            return $result["total"];
    }
    /**
     * 获得所有代理商
     * @param string $limit
     * @return array
     */
    public function getAllag ()
    {
        //$sql = 'SELECT * FROM "T_Agents" WHERE ag_level = "0"';
        $sql="SELECT * FROM \"T_Agents\" WHERE ag_level='0'";
        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ( PDO::FETCH_ASSOC );
        return $result;
    }
    //批量绑定流量卡
    public function gprs_binds(){
        //, g_binding='1', g_status='0'
        $sql="UPDATE \"T_Gprs\" SET g_agents_id='{$this->data['agents']}'  WHERE g_id='{$this->data['g_id']}'";
        $res=$this->pdo->exec($sql);
        return $res;
    }

    //迁移企业时流量卡的代理商变化
    public function move_enterprise_gprs_binds($iccid,$agent_id){
        $sql="UPDATE \"T_Gprs\" SET g_agents_id='{$agent_id}'  WHERE g_iccid='{$iccid}'";
        $res=$this->pdo->exec($sql);
    }

    //批量更新流量卡的状态
    public function change_gprs_status(){
        $ids = rtrim($this->data['ids'],',');
        $sql="SELECT * FROM \"T_Gprs\" WHERE g_id in({$ids})";
        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ( PDO::FETCH_ASSOC );
        $ids = '';
        $i = 0;
        $status=$this->data['status'];
        foreach ($result as $key => $value) {
           if($value['g_binding']=='1'){
            $i++;
                //$ids.=$value['g_id'].',';
            $sql="UPDATE \"T_Gprs\" SET g_status='$status'  WHERE g_id = {$value['g_id']}";
            try {
                    $this->pdo->exec($sql);
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }      
            //保存流量卡记录
                /*******************START***********************/
            if($status!=$value['g_status']){
                $data['gh_iccid']=$value['g_iccid'];
                //$user = new users();
                $info=$this->gprs_historyById($value['g_iccid']);

                if($status==0){
                    $g_status="stop";
                }else if($status==1){
                    $g_status="start";
                }else{
                    $g_status="";
                }
                $data['gh_e_id']=$info['e_id'];
                $data['gh_e_name']=$info['e_name'];
                $data['gh_u_number']=$info['u_number'];
                $data['gh_u_name']=$info['u_name'];
                $data['gh_md_imei']=$info['md_imei'];
                $data['gh_md_type']=$info['md_type'];
                $this->set($data);
                $this->set_gprs_history($g_status,1);
                 /*******************END***********************/
                /**
                 * 用户记录保存
                 */
                 /*******************START***********************/
                $user=new users();
                if($info['md_status']==1){
                    $md_status="start";
                }else if($info['md_status']==0){
                    $md_status=DL("stop");
                }else{
                    $md_status="";
                }
                
                if($info['g_status']==1){
                    $g_status="start";
                }else if($info['g_status']==0){
                    $g_status="stop";
                }else{
                    $g_status="";
                }
                $user_data['uh_md_imei']=$info['md_imei'];
                $user_data['uh_md_type']=$info['md_type'];
                $user_data['uh_md_status']=$md_status;
                $user_data['uh_gp_iccid']=$info['u_iccid'];
                $user_data['uh_gp_imsi']=$info['u_imsi'];
                $user_data['uh_gp_mobile']=$info['u_mobile_phone'];
                $user_data['uh_gp_status']=$g_status;
                //$user_data['uh_user_status']=$info['u_active_state'];
                $user_data['uh_u_name']=$info['u_name'];
                $user_data['uh_u_number']=$info['u_number'];
               // var_dump($user_data);die;
                $user->set($user_data);
                $user->set_user_history($info['u_active_state']==0?"stop":"start", 1);
            }
        }
    }
        return $i;
    }

    /**
     * 获取流量卡 排序ID
     * @return type
     */
    public function get_gh_id(){
                $sql = 'SELECT nextval(\'"T_GprsHistory_gh_id_seq"\'::regclass)';
                $sth = $this->pdo->query($sql);
                $result = $sth->fetch();
                return $result["nextval"];
        }
        
    //生成流量卡历史纪录
    public function set_gprs_history($info,$stat=1){
            $gh_id=$this->get_gh_id();
            $sql=<<<ECHO
            INSERT INTO "T_GprsHistory" (
                    "gh_id",
                    "gh_iccid",
                    "gh_e_id",
                    "gh_e_name",
                    "gh_u_number",
                    "gh_u_name",
                    "gh_md_imei",
                    "gh_md_type",
                    "gh_status",
                    "gh_change_time",
                    "gh_stat"
            ) VALUES(
                    :gh_id,
                    :gh_iccid,
                    :gh_e_id,
                    :gh_e_name,
                    :gh_u_number,
                    :gh_u_name,
                    :gh_md_imei,
                    :gh_md_type,
                    :gh_status,
                    :gh_change_time,
                    :gh_stat
            )
ECHO;
    $sth=$this->pdo->prepare($sql);
            $sth->bindValue(":gh_id",$gh_id);
            $sth->bindValue(":gh_iccid",$this->data['gh_iccid']);
            $sth->bindValue(":gh_e_id",$this->data['gh_e_id']);
            $sth->bindValue(":gh_e_name",$this->data['gh_e_name']);
            $sth->bindValue(":gh_u_number",$this->data['gh_u_number']);
            $sth->bindValue(":gh_u_name",$this->data['gh_u_name']);
            $sth->bindValue(":gh_md_imei",$this->data['gh_md_imei']);
            $sth->bindValue(":gh_md_type",$this->data['gh_md_type']);
            $sth->bindValue(":gh_status",$info);
            $sth->bindValue(":gh_change_time",time());
            $sth->bindValue(":gh_stat",$stat);
            try {
                $sth->execute();
            } catch (Exception $ex) {
                echo $ex->getMessage();die;
            }
            
    }
    
     /**
     * 流量卡信息历史记录 ID
     * @param $iccid
     * @return mixed
     */
    public function gprs_historyById($iccid){
        $sql=<<<echo
       SELECT * FROM "T_Gprs" WHERE g_iccid='$iccid'
echo;
        $sth=$this->pdo->query($sql);
        $res=$sth->fetch();
        $user_id=$res['g_u_number'];
        $users=new users(array('u_number'=>$user_id));
        $info=$users->getById_history();

        return $info;
    }
    
     public function getList_gprs_history($limit=""){
        $sql=<<<ECHO
    SELECT * FROM "T_GprsHistory"
ECHO;
        $sql.=$this->getWhere_gh();
        $sql.=$limit;
        //$sth=$this->pdo->prepare($sql);
        $sth=$this->pdo->query($sql);
        $res=$sth->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    
    public function getWhere_gh($order=true){
        $where=" WHERE 1=1";
        if($this->data['gh_iccid']!=""){
            $where.=" AND gh_iccid='".$this->data['gh_iccid']."'";
        }
        if($order){
            $where.=" ORDER BY gh_change_time DESC";
        }
        return $where;
    }
    
        public function gprsBound_history($info){
                $data['gh_e_id']=$info['e_id'];
                $data['gh_e_name']=$info['e_name'];
                $data['gh_u_number']=$info['u_number'];
                $data['gh_u_name']=$info['u_name'];
                $data['gh_md_imei']=$info['md_imei'];
                $data['gh_md_type']=$info['md_type'];
                $data['gh_iccid']=$info['g_iccid'];
                $this->set($data);
                    $this->set_gprs_history("start",1);
        }
        public function gprsreleaseBound_history($info){
            $data['gh_e_id']=$info['e_id'];
            $data['gh_e_name']=$info['e_name'];
            $data['gh_u_number']=$info['u_number'];
            $data['gh_u_name']=$info['u_name'];
            $data['gh_md_imei']=$info['md_imei'];
            $data['gh_md_type']=$info['md_type'];
            $data['gh_iccid']=$info['g_iccid'];
            $this->set($data);
                $this->set_gprs_history(DL("unbind"),1);
        }
	 /**
         * 流量卡历史纪录通用接口
         * @param type $res
         * @param type $info
         */        
        public function input_gprs_history($res,$info=""){
                $data['gh_e_id']=$res['e_id'];
                $data['gh_e_name']=$res['e_name'];
                $data['gh_u_number']=$res['u_number'];
                $data['gh_u_name']=$res['u_name'];
                $data['gh_md_imei']=$res['md_imei'];
                $data['gh_md_type']=$res['md_type'];
                $data['gh_iccid']=$res['g_iccid'];
                if($info==""){
                    $info=$res['g_status']=="0"?"stop":"start";
                }
                $this->set($data);
                    $this->set_gprs_history($info,1);
        }

    /**
     * 删除企业用户时流量卡的对应操作接口
     * @param type $g_u_number 企业用户的编号
     */ 
    public function delusergprs($g_u_number){
        $sql="UPDATE \"T_Gprs\" SET g_binding='0', g_u_number='', g_status='2', g_e_id=NULL,g_binding_time=NULL WHERE g_u_number='{$g_u_number}'";
        $sth = $this->pdo->query ( $sql );
        $sth->fetch ( PDO::FETCH_ASSOC );
    }

    /**
     * 获取单条流量卡的信息接口
     * @param type $iccid 流量卡id
     */ 
    public function getgprs($iccid){
        $sql = "SELECT * FROM \"T_Gprs\" WHERE g_iccid='{$iccid}'";
        $sth = $this->pdo->query ( $sql );
        $info = $sth->fetch ( PDO::FETCH_ASSOC );
        return $info;
    }

    /**
     * 修改添加用户时流量卡对应的修改操作接口
     * @param varchar $iccid 流量卡id
     * @param int $g_binding 流量卡绑定状态
     * @param varchar $g_u_number 流量卡绑定的用户编号
     * @param int $g_status 流量卡启用状态
     * @param int $e_id 流量卡所属企业id
     */ 
    public function editgprs($iccid,$g_binding,$g_u_number,$g_status='1',$e_id=''){
        $date=date('Y-m-d',time());
        $sql="UPDATE \"T_Gprs\" SET g_binding='{$g_binding}', g_u_number='{$g_u_number}',g_status='{$g_status}',g_binding_time='{$date}'  WHERE g_iccid='{$iccid}'";
        $sth = $this->pdo->query ( $sql );
        $sth->fetch ( PDO::FETCH_ASSOC );
        //流量卡对应企业id的变化
        $this->change_e_id($iccid,$e_id);
    }

    /**
     * 流量卡对应企业id的变化接口
     * @param type $iccid 流量卡id
     * @param type $e_id 流量卡所属企业id
     */ 
    public function change_e_id($iccid,$e_id=''){
        if($e_id==''){
            $sql="UPDATE \"T_Gprs\" SET g_e_id=NULL  WHERE g_iccid='{$iccid}'";
        }else{
           $sql="UPDATE \"T_Gprs\" SET g_e_id='{$e_id}'  WHERE g_iccid='{$iccid}'"; 
        }
        $sth = $this->pdo->query ( $sql );
        $sth->fetch ( PDO::FETCH_ASSOC );
    }


    /**
     * 验证iccid
     */ 
    public function check_iccid(){
        $sql = "SELECT * FROM \"T_Gprs\" WHERE g_iccid='{$this->data['u_iccid']}'";
        $sth = $this->pdo->query ( $sql );
        $info = $sth->fetch ( PDO::FETCH_ASSOC );  
        $res = array();
        if(!$info){
            $res['info'] = $info;
            $res['status'] = 5;
            return $res;
        }else{
            $res['info'] = $info;
            if($info['g_binding']=='1'){
                if($this->data['u_number']==''){
                    $sql1 = "SELECT * FROM \"T_User\" WHERE u_iccid='{$this->data['u_iccid']}'";
                }else{
                    $sql1 = "SELECT * FROM \"T_User\" WHERE u_iccid='{$this->data['u_iccid']}' AND u_number != '{$this->data['u_number']}'";
                }
                
                $sth = $this->pdo->query ( $sql1 );
                $info1 = $sth->fetch ( PDO::FETCH_ASSOC );
                if($info1){
                    $res['status'] = 2;
                    return $res;
                }else{
                    $sql2 = "SELECT e_agents_id,e_ag_path FROM \"T_Enterprise\" WHERE e_id='{$this->data['e_id']}'";
                    $sth = $this->pdo->query ( $sql2 );
                    $info2 = $sth->fetch ( PDO::FETCH_ASSOC );
                //通过企业的代理商关系(e_ag_path)判断当前企业用户与流量卡的所属代理是否匹配
                    if($info2['e_ag_path']=='|0|'){
                        if($info['g_agents_id']=='0' || $info['g_agents_id']==''){
                            //流量卡在库且可用
                            $res['status'] = 4;
                            return $res;
                        }else{
                            //流量卡绑定的代理商不是父级代理
                            $res['status'] = 3;
                            return $res;
                        }
                    }else{
                        $ag_id = substr($info2['e_ag_path'], 4,12);
                        if($ag_id==$info['g_agents_id']){
                            //流量卡在库且可用
                            $res['status'] = 4;
                            return $res;
                        }else{
                            //流量卡绑定的代理商不是父级代理
                            $res['status'] = 3;
                            return $res;
                        }
                    }
                }
            }else{
                $sql2 = "SELECT e_agents_id,e_ag_path FROM \"T_Enterprise\" WHERE e_id='{$this->data['e_id']}'";
                $sth = $this->pdo->query ( $sql2 );
                $info2 = $sth->fetch ( PDO::FETCH_ASSOC );
               //通过企业的代理商关系(e_ag_path)判断当前企业用户与流量卡的所属代理是否匹配
                if($info2['e_ag_path']=='|0|'){
                    if($info['g_agents_id']=='0' || $info['g_agents_id']==''){
                        //流量卡在库且可用
                        $res['status'] = 4;
                        return $res;
                    }else{
                        //流量卡绑定的代理商不是父级代理
                        $res['status'] = 3;
                        return $res;
                    }
                }else{
                    $ag_id = substr($info2['e_ag_path'], 4,12);
                    if($ag_id==$info['g_agents_id']){
                        //流量卡在库且可用
                        $res['status'] = 4;
                        return $res;
                    }else{
                        //流量卡绑定的代理商不是父级代理
                        $res['status'] = 3;
                        return $res;
                    }
                }
            }
        }
    }

    /**
     * 验证用户mobile
     */ 
    public function check_u_mobile(){
        if($this->data['u_mobile_phone']!='' || !empty($this->data['u_mobile_phone'])){
            if($this->data['u_number']==''){
                $sql = "SELECT * FROM \"T_User\" WHERE u_mobile_phone='{$this->data['u_mobile_phone']}'";
            }else{
                $sql = "SELECT * FROM \"T_User\" WHERE u_mobile_phone='{$this->data['u_mobile_phone']}' AND u_number != '{$this->data['u_number']}'";
            }
            $sth = $this->pdo->query ( $sql );
            $info = $sth->fetch ( PDO::FETCH_ASSOC );
            $res = array();
            if($info){
                //此号码已被用户绑定
                $res['status'] = 2;
                return $res;
            }else{ 
                $sql1 = "SELECT * FROM \"T_Gprs\" WHERE g_number='{$this->data['u_mobile_phone']}'";
                $sth1 = $this->pdo->query ( $sql1 );
                $info1 = $sth1->fetch ( PDO::FETCH_ASSOC );
                $res['info'] = $info1;
                if($info1){
                        $sql2 = "SELECT e_agents_id,e_ag_path FROM \"T_Enterprise\" WHERE e_id='{$this->data['e_id']}'";
                        $sth2 = $this->pdo->query ( $sql2 );
                        $info2 = $sth2->fetch ( PDO::FETCH_ASSOC );
                //通过企业的代理商关系(e_ag_path)判断当前企业用户与流量卡的所属代理是否匹配
                        if($info2['e_ag_path']=='|0|'){
                            if($info1['g_agents_id']=='0' || $info1['g_agents_id']==''){
                                //流量卡在库且可用
                                $res['status'] = 4;
                                return $res;
                            }else{
                                //流量卡绑定的代理商不是父级代理
                                $res['status'] = 3;
                                return $res;
                            }
                        }else{
                            $ag_id = substr($info2['e_ag_path'], 4,12);
                            if($ag_id==$info1['g_agents_id']){
                                //流量卡在库且可用
                                $res['status'] = 4;
                                return $res;
                            }else{
                                //流量卡绑定的代理商不是父级代理
                                $res['status'] = 3;
                                return $res;
                            }
                        }
                }else{
                    $res['status'] = 5;
                    return $res;
                }
            }
        }
        return $res['status'] = 1;
    }

    /**
     * 验证用户IMSI
     */ 
    public function check_u_imsi(){
        if($this->data['u_imsi']!='' || !empty($this->data['u_imsi'])){
            if($this->data['u_number']==''){
                    $sql = "SELECT * FROM \"T_User\" WHERE u_imsi='{$this->data['u_imsi']}'";
            }else{
                    $sql = "SELECT * FROM \"T_User\" WHERE u_imsi='{$this->data['u_imsi']}' AND u_number != '{$this->data['u_number']}'";
            }
            $sth = $this->pdo->query ( $sql );
            $info = $sth->fetch ( PDO::FETCH_ASSOC );
            $res = array();
            if($info){
                    //此IMSI已被用户绑定
                    $res['status'] = 2;
                    return $res;
            }else{ 
                $sql1 = "SELECT * FROM \"T_Gprs\" WHERE g_imsi='{$this->data['u_imsi']}'";
                $sth1 = $this->pdo->query ( $sql1 );
                $info1 = $sth1->fetch ( PDO::FETCH_ASSOC );
                $res['info'] = $info1;
                if($info1){
                        $sql2 = "SELECT e_agents_id,e_ag_path FROM \"T_Enterprise\" WHERE e_id='{$this->data['e_id']}'";
                        $sth2 = $this->pdo->query ( $sql2 );
                        $info2 = $sth2->fetch ( PDO::FETCH_ASSOC );
                //通过企业的代理商关系(e_ag_path)判断当前企业用户与流量卡的所属代理是否匹配
                        if($info2['e_ag_path']=='|0|'){
                            if($info1['g_agents_id']=='0' || $info1['g_agents_id']==''){
                                //流量卡在库且可用
                                $res['status'] = 4;
                                return $res;
                            }else{
                                //流量卡绑定的代理商不是父级代理
                                $res['status'] = 3;
                                return $res;
                            }
                        }else{
                            $ag_id = substr($info2['e_ag_path'], 4,12);
                            if($ag_id==$info1['g_agents_id']){
                                $res['status'] = 4;
                                return $res;
                            }else{
                                //流量卡绑定的代理商不是父级代理
                                $res['status'] = 3;
                                return $res;
                            }
                        }
                }else{
                    $res['status'] = 5;
                    return $res;
                }
            }
         }
         $res['status'] = 1;
         return $res;
    }

}
