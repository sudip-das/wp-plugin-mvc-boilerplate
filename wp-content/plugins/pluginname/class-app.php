<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2015-08-18
 * Time: 11:57 AM
 */
namespace plugin_namespace;

use plugin_namespace\component\Util;
use plugin_namespace\component\Util_Request;
use plugin_namespace\controller\admin\Common as Admin_Common;
use plugin_namespace\controller\common\Common;
use plugin_namespace\controller\front\Common as Front_Common;

class App {

    static $version = '1.0.0';

    static $dir_path;
    static $dir_url;

    static $module_path;
    static $module_url;

    static $slug;

    static $file_path;

    static $asset_dir_url;
    static $asset_dir_path;


    static $css_dir_url;

    static $js_dir_url;

    static $image_dir_path;
    static $image_dir_url;

    static $lib_dir_path;

    static $component_dir_path;

    static $template_dir_path;
    static $template_dir_url;

    static $short_codes;
    static $widgets;

    static $root_namespace;


    static $configs;
    static $container;
    static $_loadedClasses = array();

    public static  function init(){



        self::$dir_path = dirname(__FILE__);
        self::$dir_url = untrailingslashit(plugin_dir_url(__FILE__));

        self::$module_path = self::$dir_path . '/modules';
        self::$module_url = self::$dir_url . '/modules';


        self::$slug = basename(self::$dir_path);

        self::$file_path = self::$dir_path . '/' . basename(self::$dir_path) . '.php';


        self::$asset_dir_url = self::$dir_url . '/asset';
        self::$asset_dir_path = self::$dir_path . '/asset';


        self::$css_dir_url = self::$dir_url . '/asset/css';
        self::$js_dir_url = self::$dir_url . '/asset/js';

        self::$image_dir_path = self::$dir_path . '/asset/image';
        self::$image_dir_url = self::$dir_url . '/asset/image';

        self::$lib_dir_path = self::$dir_path . '/lib';
        self::$component_dir_path = self::$dir_path . '/component';




        self::$root_namespace = 'plugin_namespace';






        $widget_ns = self::$root_namespace . '\widget';


        self::$widgets = array(

//            array(
//
//                'class'=>$widget_ns . '\Recently_Searched',
//                'id'=>'recently_searched',
//                'title'=>__( 'Recently Searched', 'woothemes-testimonials' ),
//                'description'=>__( 'Recent searched users on your site.', 'woothemes-testimonials' ),
//                'cssClass'=>'widget_recently_searched',
//                //'width'=>250,
//                //'height'=>350
//
//            ),

        );




        spl_autoload_register('self::class_autoload');



        self::$container = new Container();

        self::load_base_classes();




        self::$configs = include( self::$dir_path . '/config.php');



        self::tasks();


    }



    static function load_base_classes(){



        self::$container['common'] = new Common();

//        var_dump(Util_Request::is_admin());
//        die('fkw');


        if( Util_Request::is_admin() ){

//            Admin_Common::get_instance();

            self::$container['admin_common'] = new Admin_Common();

//            die('fkww');


        }
        else{



//            Front_Common::get_instance();
            self::$container['front_common'] = new Front_Common();


        }


    }





    /**
     * Run on activation.
     * @access public
     * @since 1.0.0
     * @return void
     */
    /*
    public function cb_registerActivationHook_activation () {
        //$this->register_plugin_version();
        //$this->flush_rewrite_rules();
    } // End activation()
*/
    static function tasks(){

        $tasks = isset(self::$configs['tasks'])?self::$configs['tasks']:array();


        if(!empty($tasks['common'])){


            self::resolve_tasks( 'common', $tasks['common'] );


        }


        if( Util_Request::is_admin() ){


            if(!empty($tasks['admin'])){


                self::resolve_tasks( 'admin', $tasks['admin'] );


            }


        }
        else{

            if(!empty($tasks['front'])){


                self::resolve_tasks( 'front', $tasks['front'] );


            }
        }



        if( Util::do_module_exist( 'sms' ) ){


        }
        if( Util::do_module_exist( 'subscriber' ) ){


        }

    }

    /**
     * @param $end either front or admin or common
     * @param $hook
     * @param $controller
     * @param $action
     * @param $condition_func
     */

    static function resolve_tasks( $end, $tasks  ){


        foreach( $tasks as $controller_class => $methods ){

            foreach( $methods as $method => $def_with_hooks ){





                if( !empty( $def_with_hooks['action'] ) ){
                    $hook = $def_with_hooks['action'];


                    self::_resolve_task( $end, $hook, $controller_class, $method, $def_with_hooks );


                }
                else if( !empty( $def_with_hooks['filter'] ) ){

                    $hook = $def_with_hooks['filter'];

                    self::_resolve_task( $end, $hook, $controller_class, $method, $def_with_hooks );

                }
                else{

                    if( is_array( $def_with_hooks ) ){

                        foreach( $def_with_hooks as $indx => $v_arr ){

                            if( is_integer( $indx ) ){


                                $hook = null;

                                if( !empty( $v_arr['action'] ) ){

                                    $hook = $v_arr['action'];

                                    self::_resolve_task( $end, $hook, $controller_class, $method, $v_arr );

                                }
                                else if( !empty( $v_arr['filter'] ) ){

                                    $hook = $v_arr['filter'];

                                    self::_resolve_task( $end, $hook, $controller_class, $method, $v_arr );

                                }

                            }
                        }

                    }

                }


            }




        }



    }

    static function _resolve_task( $end, $hook, $controller_class, $method, $def_with_hooks ){

        if( isset( $def_with_hooks['action']) ){


            self::_resolve_action_task( $end, $hook, $controller_class, $method , isset($def_with_hooks['accepted_args']) ? $def_with_hooks['accepted_args'] : 0, isset($def_with_hooks['priority']) ? $def_with_hooks['priority'] : 10, isset($def_with_hooks['condition']) ? $def_with_hooks['condition'] : null );
        }
        else if( isset( $def_with_hooks['filter']) ) { //filter

            self::_resolve_filter_task( $end, $hook, $controller_class, $method, isset($def_with_hooks['accepted_args']) ? $def_with_hooks['accepted_args'] : 0, isset($def_with_hooks['priority']) ? $def_with_hooks['priority'] : 10, isset($def_with_hooks['condition']) ? $def_with_hooks['condition'] : null );
        }

    }

    static function _resolve_action_task( $endn, $hook, $controller_class, $action,$accepted_args, $priority, $condition_func ){


            add_action($hook, function () use ($endn, $hook, $controller_class, $action, $accepted_args, $condition_func) {

                $return = self::_resolve_task_check_condition($condition_func);


                if ($return) {

                    $instance = self::_reslove_task_get_controller_instance($endn, $controller_class);

                    $args = func_get_args();
                    if (empty($args)) {

                        $args = array();

                    }
                    call_user_func_array(array($instance, $action), $args);
                }


            }, $priority, $accepted_args);

//        }
    }

    static function _resolve_task_check_condition($condition_func){




        $return = true;

        if( !empty($condition_func )){

                $return = call_user_func($condition_func);

        }

        if( $return === true || $return === false ){

        }
        else{
            $return = true;
        }

        return $return;
    }

    static function _reslove_task_get_controller_instance($endn, $controller_class){

        $key = $endn . '_' . strtolower($controller_class);
        if( empty(self::$container[$key]) ){

            $cls = self::$root_namespace . '\\controller\\' . $endn . '\\' . $controller_class;



            self::$container[$key] = new $cls();


        }

        return self::$container[$key];
    }

    static function _resolve_filter_task( $endn, $hook, $controller_class, $action,$accepted_args, $priority, $condition_func ){


            add_filter($hook, function () use ($endn, $hook, $controller_class, $action, $accepted_args, $condition_func) {

                $return = self::_resolve_task_check_condition($condition_func);


                if ($return) {

                    $instance = self::_reslove_task_get_controller_instance($endn, $controller_class);

                    $args = func_get_args();
                    if (empty($args)) {

                        $args = array();

                    }
                    return call_user_func_array(array($instance, $action), $args);

                }


            }, $priority, $accepted_args);

//        }
    }

    static function class_autoload($cls){



        $cls = ltrim($cls, '\\');
        if(strpos($cls, __NAMESPACE__) !== 0)
            return;



        $cls = str_replace(array(__NAMESPACE__), array(''), $cls);

        $cls = ltrim($cls,'\\');

        $arr = explode('\\',$cls);


        $p = '';
        $t = count($arr);
        $i = 0;
        foreach($arr as $i=>$a){

            if($i == ($t-1)) {

                $a = strtolower($a);

                $a = str_replace('_','-',$a);

                $p .= 'class-' . $a;
            }
            else
                $p .= $a . DIRECTORY_SEPARATOR;


        }





        $path = self::$dir_path . DIRECTORY_SEPARATOR . $p . '.php';


        $md5FileName = md5($path);
        if(!isset(self::$_loadedClasses[$md5FileName])) {

                include $path;

            self::$_loadedClasses[$md5FileName] = $path;



        }

    }


}