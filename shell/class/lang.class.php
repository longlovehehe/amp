<?php

/**
 * 多语言切换器
 * @category OMP
 * @package OMP_Common
 * @deprecated 被{@see coms::lang}取代
 */
class lang
{

    private $lang;
    private $path;

    public function __construct ( $path )
    {
        $this->path = $path . '/template/lang';
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
