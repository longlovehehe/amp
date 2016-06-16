<?php

/**
 * 多语言切换器
 * @category OMP
 * @package OMP_Common
 * @deprecated 被{@see coms::lang}取代
 */
class permit
{

    private $lang;
    private $path;

    public function __construct ( )
    {
        
    }
    
/**
 * 
 * @param type $ep_arr array 代理商ID
 * @param type $ag_arr array 代理商ID 级别
 */
    public function get_ag_permit($ep_arr,$ag_arr){
            $ep = new enterprise ( $ep_arr );
            $info = $ep->getList_permit ();

            //获取当前代理商的子代理商和所属企业

            $phone = 0; //企业所分配手机数
            $dispatch = 0;//企业所分配调度台数
            $gvs = 0;//企业所分配GVS数
            foreach ( $info as $key => $value )
            {
                $phone += $value['e_mds_phone'];
                $dispatch += $value['e_mds_dispatch'];
                $gvs += $value['e_mds_gvs'];
            }
            $ag = new agents ( $ag_arr );  
            $ag_info = $ag->getList ();
            foreach ( $ag_info as $key => $value )
            {
                $phone += $value['ag_phone_num'];
                $dispatch += $value['ag_dispatch_num'];
                $gvs += $value['ag_gvs_num'];
            }
            $ag->set($ep_arr);
            $res=$ag->getByid();
            $two_ceng=$this->get_ag_permit1($ep_arr, $ag_arr);
            $c_phone = $res['ag_phone_num'] - $two_ceng['phone'];
            $c_dispatch = $res['ag_dispatch_num'] - $two_ceng['dispatch'];
            $c_gvs = $res['ag_gvs_num'] - $two_ceng['gvs'];
            //当前代理已分配许可
            return array('phone'=>$phone,'dispatch'=>$dispatch,'gvs'=>$gvs,'c_phone'=>$c_phone,'c_dispatch'=>$c_dispatch,'c_gvs'=>$c_gvs,'d_phone'=>$two_ceng['phone'],'d_dispatch'=>$two_ceng['dispatch'],'d_gvs'=>$two_ceng['gvs']);
    }
    /**
 * 
 * @param type $ep_arr array 代理商ID
 * @param type $ag_arr array 代理商ID 级别
 */
    public function get_ag_permit1($ep_arr,$ag_arr){
        $ep = new enterprise ( $ep_arr );
            $info = $ep->getList_permit ();

            //获取当前代理商的子代理商和所属企业

            $phone = 0; //企业所分配手机数
            $dispatch = 0;//企业所分配调度台数
            $gvs = 0;//企业所分配GVS数
            foreach ( $info as $key => $value )
            {
                $phone += $value['e_mds_phone'];
                $dispatch += $value['e_mds_dispatch'];
                $gvs += $value['e_mds_gvs'];
            }
            $ag = new agents ( $ag_arr );
            $ag_info = $ag->getList ();
            foreach ( $ag_info as $key => $value )
            {
                $phone += $value['ag_phone_num'];
                $dispatch += $value['ag_dispatch_num'];
                $gvs += $value['ag_gvs_num'];
            }
            
            //当前代理已分配许可
            return array('phone'=>$phone,'dispatch'=>$dispatch,'gvs'=>$gvs);
    }
    public function getText ()
    {
        $lang = $this->path . '/' . $this->lang . '.ini';
        return parse_ini_file ( $lang , true );
    }

    public function en_US ()
    {
        $this->lang = 'en_US';
    }

}
