<?php
namespace company_plugin\controller\common;


use company_plugin\App;
use company_plugin\controller\Base;

class Common extends Base {

    protected function init(){

        $this->load_plugin_textdomain();



        $this->addWidgets();







    }




    public static function load_plugin_textdomain () {


    } // End load_plugin_textdomain()


    function addWidgets(){


            add_action('widgets_init', function(){

//                register_widget( App::$root_namespace . '\widget\Featured_User');



            });


    }

    function acb_widgets_init(){



    }





}