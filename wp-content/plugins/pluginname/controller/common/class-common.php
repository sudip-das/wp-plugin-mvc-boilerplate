<?php
namespace plugin_namespace\controller\common;


use plugin_namespace\App;
use plugin_namespace\controller\Base;

class Common extends Base {

    function acb_widgets_init(){



    }

    protected function init(){

        $this->load_plugin_textdomain();



        $this->addWidgets();







    } // End load_plugin_textdomain()

public static function load_plugin_textdomain () {


    }

    function addWidgets(){


            add_action('widgets_init', function(){

//                register_widget( App::$root_namespace . '\widget\Featured_User');



            });


    }





}