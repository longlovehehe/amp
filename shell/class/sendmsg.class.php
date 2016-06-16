<?php

/**
 * 后台消息发送器
 * @category OMP
 * @package OMP_Common
 */
class sendmsg extends db
{

    const REQUEST = 1;
    const OPT_PLATFORM_FIRST_AUTH = 1;
    const OPT_PLATFORM_SECOND_AUTH = 2;
    const OPT_PLATFORM_ASSGIN_ID = 3;
    const OPT_PLATFORM_E_DELETE = 4;
    const OPT_PLATFORM_MDS_XML_UPDATE = 5;
    const OPT_PLATFORM_GET_MDS_INFO = 6;
    const OPT_PLATFORM_E_VCR_MIGRATION = 7;
    const OPT_PLATFORM_VCRS_INFO = 8;
    const OPT_PLATFORM_E_RECORD_INFO = 9;
    const OPT_PLATFORM_GET_VCR_INFO = 10;
    const OPT_PLATFORM_NOTIFY_ASSGIN_ID = 11;
    const OPT_PLATFORM_NOTIFY_DEV_INFO_UPDATE = 12;
    const OPT_PLATFORM_NOTIFY_E_VCR_MIGRATION = 13;
    const OPT_PLATFORM_NOTIFY_E_DELETE_FROM_MANAGE = 14;
    const OPT_PLATFORM_NOTIFY_E_INFO_UPDATE = 15;
    const OPT_PLATFORM_NOTIFY_E_CREATE_FROM_BSS = 16;
    const OPT_PLATFORM_NOTIFY_E_DELETE_FROM_BSS = 17;
    const OPT_PLATFORM_NOTIFY_USER_CREATE_FROM_BSS = 18;
    const OPT_PLATFORM_NOTIFY_USER_DELETE_FROM_BSS = 19;
    const OPT_PLATFORM_MDS_LICENSE_UPDATE = 20;
    const OPT_PLATFORM_VCR_LICENSE_UPDATE = 21;
    const OPT_PLATFORM_NOTIFY_E_MDS_MIGRATION = 22;
    const OPT_PLATFORM_NOTIFY_E_CREATE_FROM_MANAGE = 27;
    const  OPT_PLATFORM_NOTIFY_E_PAUSE_FROM_MANAGE = 34;
    const  OPT_PLATFORM_NOTIFY_E_RESTORE_FROM_MANAGE = 35;
    const OPT_PLATFORM_E_INFO_REFRESH = 38;
    const E_INFO_FLAVOR = 0;
    const E_INFO_VCR_FLAVOR = 1;
    const E_INFO_MDS_CORP = 2;
    const E_INFO_MDS_SUBSCRIBERS = 4;
    const E_INFO_MDS_TEAMS = 8;
    const E_INFO_MDS_PTTGROUPS = 16;
    const OPT_PLATFORM_NOTIFY_DEV_MIGRATION = 90;

    public function __construct()
    {
        parent::__construct();
        $def = parse_ini_file('../private/config/config.ini', TRUE);
        $this->data = json_decode(file_get_contents("../private/config/db.json"), true);
        $this->data['PATH'] = $def['sendmsg']['path'];
        $this->data['PORT'] = $def['sendmsg']['port'];
    }

    public function send($type, $cmd, $parame)
    {
        $shell['PATH'] = $this->data['PATH'];

        if ($this->data['data_base']['db_host'] == 'localhost')
        {
            $shell['IP'] = $_SERVER['SERVER_ADDR'];
        } else
        {
            $shell['IP'] = $this->data['data_base']['db_host'];
        }
        $shell['PORT'] = $this->data['PORT'];
        $shell['AUTH_CODE'] = $_SERVER['SERVER_ADDR'];
        $shell['TYPE'] = $type;
        $shell['CMD'] = $cmd;
        $shell['PARAM'] = $parame;

        $result['shell'] = implode(' ', $shell);
        // echo $result['shell'];die;
        $result["status"] = exec($result['shell']);
        if ($result["status"] == "")
        {
            $result["status"] = -1;
        }

        if ($result["status"] != 200)
        {
            $m = "";
            foreach ($shell as $key => $value)
            {
                $m .= $key . " => " . $value . " , ";
            }

            //$msg = '[与服务器通信失败，通信讯息：' . $m . '][' . $result["status"] . '][' . $result['shell'] . ']';
            $this->log('['.DL('与服务器通信失败，通信讯息').'：' . $m . '][' . $result["status"] . '][' . $result['shell'] . ']', 1, 2);
            $msg = L ( '与设备通信失败，请联系管理员检查系统执行日志' );
            $msg = str_replace(PHP_EOL, '', $msg);
            throw new Exception($msg, $result["status"]);
        }

        return $result;
    }

    /*
      // 启用
     *
     */

    public function ExStart($e_id)
    {
        // $type = self::E_INFO_MDS_CORP | self::E_INFO_MDS_SUBSCRIBERS | self::E_INFO_MDS_TEAMS | self::E_INFO_MDS_PTTGROUPS;
        return $this->send ( self::REQUEST , self::OPT_PLATFORM_NOTIFY_E_RESTORE_FROM_MANAGE , $e_id. ' 0' );
    }
    /*
    * 删除企业
    */
    public function DelEx($e_id)
    {
        return $this->send(self::REQUEST, self::OPT_PLATFORM_NOTIFY_E_DELETE_FROM_MANAGE, $e_id);
    }
    /*
      // 启用具有VCR
     *
     */

    public function ExStartVCR($e_id)
    {
        $type = self::E_INFO_VCR_FLAVOR | self::E_INFO_MDS_CORP | self::E_INFO_MDS_SUBSCRIBERS | self::E_INFO_MDS_TEAMS | self::E_INFO_MDS_PTTGROUPS;
        return $this->send(self::REQUEST, self::OPT_PLATFORM_NOTIFY_E_INFO_UPDATE, $e_id . ' ' . $type);
    }

    /*
      // 停用
     *
     */

    public function ExStop($e_id)
    {
        // $type = self::E_INFO_MDS_CORP | self::E_INFO_MDS_SUBSCRIBERS | self::E_INFO_MDS_TEAMS | self::E_INFO_MDS_PTTGROUPS | self::OPT_PLATFORM_NOTIFY_E_PAUSE_FROM_MANAGE;
        return $this->send(self::REQUEST, self::OPT_PLATFORM_NOTIFY_E_PAUSE_FROM_MANAGE, $e_id);
    }

    /*
      // 停用具有VCR
     *
     */

    public function ExStopVCR($e_id)
    {
        $type = self::E_INFO_VCR_FLAVOR | self::E_INFO_MDS_CORP | self::E_INFO_MDS_SUBSCRIBERS | self::E_INFO_MDS_TEAMS | self::E_INFO_MDS_PTTGROUPS;
        return $this->send(self::REQUEST, self::OPT_PLATFORM_NOTIFY_E_DELETE_FROM_MANAGE, $e_id . ' ' . $type);
    }

    public function ExCreate($e_id)
    {
        $type = intval(E_INFO_MDS_CORP) | intval(E_INFO_MDS_SUBSCRIBERS) | intval(E_INFO_MDS_TEAMS) | intval(E_INFO_MDS_PTTGROUPS);
        return $this->send(self::REQUEST, self::OPT_PLATFORM_NOTIFY_E_CREATE_FROM_MANAGE, $e_id. ' 0');
    }

    public function ExCreateVCR($e_id)
    {
        $type = self::E_INFO_VCR_FLAVOR | self::E_INFO_MDS_CORP | self::E_INFO_MDS_SUBSCRIBERS | self::E_INFO_MDS_TEAMS | self::E_INFO_MDS_PTTGROUPS;
        return $this->send(self::REQUEST, self::OPT_PLATFORM_NOTIFY_E_CREATE_FROM_MANAGE, $e_id. ' 0');
    }

    public function ExEdit($e_id)
    {
        $type = self::E_INFO_MDS_CORP;
        return $this->send(self::REQUEST, self::OPT_PLATFORM_NOTIFY_E_INFO_UPDATE, $e_id . ' ' . $type);
    }

    public function ExEditVCR($e_id)
    {
        $type = self::E_INFO_VCR_FLAVOR | self::E_INFO_MDS_CORP;
        return $this->send(self::REQUEST, self::OPT_PLATFORM_NOTIFY_E_INFO_UPDATE, $e_id . ' ' . $type);
    }

    public function ExSync($e_id)
    {
        // 1
        // 1|8
        $type = sendmsg::E_INFO_FLAVOR | 8;
        return $this->send ( self::REQUEST , self::OPT_PLATFORM_NOTIFY_E_INFO_UPDATE , $e_id );
    }

    public function ExSyncVCR($e_id)
    {
        // 1
        // 1|8
        $type = sendmsg::E_INFO_VCR_FLAVOR | 8;
        return $this->send(self::REQUEST, self::OPT_PLATFORM_NOTIFY_E_INFO_UPDATE, $e_id.' '.$type);
    }

    public function ExMoveMds($param)
    {
        /*
          //PARAM
          //EID :       企业ID
          //DstDevID :  目的MDS_ID
          //DstAREA :  目的企业区域ID
         *
         */
        return $this->send(self::REQUEST, self::OPT_PLATFORM_NOTIFY_E_MDS_MIGRATION, $param);
    }

    public function ExMoveVcr($param)
    {
        /*
          //PARAM
          //EID :       企业ID
          //DstDevID :  目的VCR_ID
         *
         */
        return $this->send(self::REQUEST, self::OPT_PLATFORM_NOTIFY_E_VCR_MIGRATION, $param);
    }
    /**
     *  迁移 刷新接口
     * @param type $param
     * @return type
     */
    public function ExTransfer($param){
       return $this->send(self::REQUEST, self::OPT_PLATFORM_E_INFO_REFRESH, $param ." 0"); 
    }

    public function ExMoveDevice($param)
    {
        /*
         * 迁移设备
         */
        return $this->send(self::REQUEST, self::OPT_PLATFORM_NOTIFY_DEV_MIGRATION, $param);
    }
    public function DevSave($param)
    {
        /*
         * DevID :  分配的设备ID
         * ip： 设备ip
         * port: 设备与数据管理中心交换监听的port
         */
        return $this->send(self::REQUEST, self::OPT_PLATFORM_NOTIFY_ASSGIN_ID, $param);
    }

    public function   DevVcrs($param)
    {
        //DevID :  分配的设备ID
        return $this->send(self::REQUEST, self::OPT_PLATFORM_NOTIFY_DEV_INFO_UPDATE, $param);
    }

}
