<?php
namespace plugin_namespace\controller\admin;


use plugin_namespace\App;
use plugin_namespace\controller\Base;

class Common extends Base {


    function cb_register_activation_hook(){



    }

    function cb_register_deactivation_hook(){


    }

    protected function init(){


        register_activation_hook( App::$file_path, array($this,'cb_register_activation_hook') );
        register_deactivation_hook( App::$file_path , array($this,'cb_register_deactivation_hook') );




    }





}