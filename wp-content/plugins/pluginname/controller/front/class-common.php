<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2015-05-25
 * Time: 01:00 AM
 */

namespace plugin_namespace\controller\front;

use plugin_namespace\App;
use plugin_namespace\component\Util;
use plugin_namespace\controller\Base;


class Common extends Base {

    function deregister_styles(){


    }

    protected function init(){


        $this->rewrite_rules();

        $this->add_shortcodes();



    }

    function rewrite_rules(){

    }

    function add_shortcodes(){

        add_shortcode( 'tllb_list_videos', function( $atts , $content = null ) {


        });



    }


}