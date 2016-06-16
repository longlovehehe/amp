<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of basic
 *
 * @author zed
 */
class basic extends db{
    //put your code here
    public function __construct($data) {
        parent::__construct();
        $this->data = $data;
    }
    
    public function get(){
        return $this->data;
    }
    public function set($data){
        $this->data = $data;
    }
    /**
     * 获得价格列表
     */
    public function getList($limit=""){
       $sql='SELECT * FROM "T_Price"';
       $sql.=$this->getwhere();
       $sql.=$limit;
       $sth=$this->pdo->prepare($sql);
       $sth->execute();
       $res=$sth->fetchAll();
       return $res;
        
    }
    public function getwhere($order=false){
        $where=" WHERE 1=1 AND id='{$_SESSION['ag']['ag_number']}'";
         if($this->data['console_price']!=""){
            $where .= " AND console_price LIKE E'%" . addslashes($this->data["console_price"]) . "%'";
        }
         if($this->data['basic_price']!=""){
            $where .= " AND basic_price LIKE E'%" . addslashes($this->data["basic_price"]) . "%'";
        }
         if($this->data['id']!=""){
            $where .= " AND id LIKE E'%" . addslashes($this->data["id"]) . "%'";
        }
        if($order){
            $where .=" ORDER BY id";
        }
        return $where;
    }
    /**
     * 查询获取某一条价格
     */
    public function getByid(){
        $sql="SELECT * FROM \"T_Price\" WHERE id=:id";
        $sth=$this->pdo->prepare($sql);
        $sth->bindValue(":id",$this->data['id']);
        $sth->execute();
        return $sth->fetch();
    }
    /**
     * 设置基础功能价格
     */
    public function save_price(){
        $data=$this->getByid();
         $edit=true;
        if($data==false){
            $edit=false;
        }
        if($edit){
            $sql='UPDATE "T_Price" SET console_price=:console_price,basic_price=:basic_price,gn_dxx=:gn_dxx,gn_yythkt=:gn_yythkt,gn_yyhy=:gn_yyhy,gn_tppch=:gn_tppch,gn_gps=:gn_gps,gn_djdtmsh=:gn_djdtmsh,gn_shpyw=:gn_shpyw,gn_vas=:gn_vas,units_price=:units_price WHERE id=:id';
        }else{
            $sql='INSERT INTO "T_Price" ("id","console_price","basic_price","gn_dxx","gn_yythkt","gn_yyhy","gn_tppch","gn_gps","gn_djdtmsh","gn_shpyw","gn_vas","units_price") VALUES(:id,:console_price,:basic_price,:gn_dxx,:gn_yythkt,:gn_yyhy,:gn_tppch,:gn_gps,:gn_djdtmsh,:gn_shpyw,:gn_vas,:units_price)';   
        }
         $sth=$this->pdo->prepare($sql);
         $sth->bindValue(':id',  $this->data['id']);
         $sth->bindValue(':console_price',  $this->data['console_price']);
         $sth->bindValue(':basic_price',  $this->data['basic_price']);
         $sth->bindValue(':gn_dxx',  $this->data['gn_dxx']);
         $sth->bindValue(':gn_yythkt',  $this->data['gn_yythkt']);
         $sth->bindValue(':gn_yyhy',  $this->data['gn_yyhy']);
         $sth->bindValue(':gn_tppch',  $this->data['gn_tppch']);
         $sth->bindValue(':gn_gps',  $this->data['gn_gps']);
         $sth->bindValue(':gn_djdtmsh',  $this->data['gn_djdtmsh']);
         $sth->bindValue(':gn_shpyw',  $this->data['gn_shpyw']);
         $sth->bindValue(':gn_vas',  $this->data['gn_vas']);
         $sth->bindValue(':units_price',  $this->data['units_price']);
         try {
             $sth->execute();
             $msg['status']=0;
             $msg['msg'] = L('操作成功');
        } catch (Exception $exc) {
            $msg['status']=-1;
            $msg['msg'] = L('操作失败');
         }
         return $msg;
    }
    
    /**
     * 设置基础功能价格
     */
    public function save_price_ag(){
        $data=$this->getByid();
         $edit=true;
        if($data==false){
            $edit=false;
        }
        if($edit){
            $sql='UPDATE "T_Price" SET console_price_amp=:console_price_amp,basic_price_amp=:basic_price_amp,units_price=:units_price WHERE id=:id';
            $sth=$this->pdo->prepare($sql);
        }else{
            $sql='INSERT INTO "T_Price" ("id","console_price_amp","basic_price_amp","gn_dxx","gn_yythkt","gn_yyhy","gn_tppch","gn_gps","gn_djdtmsh","gn_shpyw","gn_vas","units_price") VALUES(:id,:console_price_amp,:basic_price_amp,:gn_dxx,:gn_yythkt,:gn_yyhy,:gn_tppch,:gn_gps,:gn_djdtmsh,:gn_shpyw,:gn_vas,:units_price)';   
            $sth=$this->pdo->prepare($sql);
            $sth->bindValue(':gn_dxx',  $this->data['gn_dxx']);
            $sth->bindValue(':gn_yythkt',  $this->data['gn_yythkt']);
            $sth->bindValue(':gn_yyhy',  $this->data['gn_yyhy']);
            $sth->bindValue(':gn_tppch',  $this->data['gn_tppch']);
            $sth->bindValue(':gn_gps',  $this->data['gn_gps']);
            $sth->bindValue(':gn_djdtmsh',  $this->data['gn_djdtmsh']);
            $sth->bindValue(':gn_shpyw',  $this->data['gn_shpyw']);
            $sth->bindValue(':gn_vas',  $this->data['gn_vas']);
        }
        
         $sth->bindValue(':id',  $this->data['id']);
         $sth->bindValue(':console_price_amp',  $this->data['console_price_amp']);
         $sth->bindValue(':basic_price_amp',  $this->data['basic_price_amp']);
         $sth->bindValue(':units_price',  $this->data['units_price']);
         try {
             $sth->execute();
             $msg['status']=0;
             $msg['msg'] = L('操作成功');
        } catch (Exception $exc) {
            $msg['status']=-1;
            $msg['msg'] = L('操作失败');
         }
         return $msg;
    }
    
    function set_units(){
        $sql="UPDATE \"T_Price\" SET units_price=:units_price WHERE id='{$this->data['id']}'";
        $sth=$this->pdo->prepare($sql);
        $sth->bindValue(':units_price',  $this->data['units_price']);
        try {
            $sth->execute();
            $msg['status']=0;
            $msg['msg']=L('设置成功');
            return $msg;
        } catch (Exception $exc) {
            $msg['status']=-1;
            $msg['msg']=L('设置失败');
            return $msg;
        }

        
    }
    
    /**
     * 获得当前增值功能价格
     */
    public function get_price($id="0"){
         $sql="SELECT * FROM \"T_Price\" WHERE id=:id";
        $sth=$this->pdo->prepare($sql);
        $sth->bindValue(":id",$id);
        $sth->execute();
        return $sth->fetch();
        
    }
}
