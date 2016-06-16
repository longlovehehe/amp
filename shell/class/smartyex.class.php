<?php

require_once ( '../private/libs/Smarty/Smarty.class.php' );

/**
 * 模版引擎
 * @package OMP_Common
 */
class smartyex extends Smarty
{

    public function __construct ()
    {
        parent::__construct ();
        $this->template_dir = "../template";
        $this->cache_dir = "../runtime/cache";
        $this->compile_dir = "../runtime/template_c";
        $this->smarty->caching = FALSE;
        $this->smarty->cache_lifetime = 0;
        $this->smarty->force_compile = TRUE;
    }

}
