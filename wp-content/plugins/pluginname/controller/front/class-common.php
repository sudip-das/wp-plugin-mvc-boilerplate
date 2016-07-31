<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2015-05-25
 * Time: 01:00 AM
 */

namespace pluginname\controller\front;

use pluginname\App;
use pluginname\component\Util;
use pluginname\controller\Base;


class Common extends Base {

    protected function init(){


        $this->rewrite_rules();

        $this->add_shortcodes();



    }

    function add_shortcodes(){

        add_shortcode( 'tllb_list_videos', function( $atts , $content = null ) {


        });



    }


    function rewrite_rules(){

    }

    function deregister_styles(){


    }


}