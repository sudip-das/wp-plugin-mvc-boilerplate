<?php
namespace company_plugin\component;

use company_plugin\App;

class Util {

    static function include_class_view( $class_with_ns,$view,$data = array(),$returnOutput = false ){


            $arr_class_ns = explode('\\', $class_with_ns);

            if (is_array($arr_class_ns) && $arr_class_ns[0] == '') {//Because user may pass "\ROOT_CLASS\Widget\Testimonial" or "ROOT_CLASS\Widget\Testimonial"
                array_shift($arr_class_ns);
            }

            $sz = count($arr_class_ns);

            $className = $arr_class_ns[$sz - 1];

            $path = '';
            for ($i = 1; $i < ($sz - 1); $i++) {

                $path .= DIRECTORY_SEPARATOR . lcfirst($arr_class_ns[$i]);
            }
            $viewPath = App::$dir_path . $path . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . lcfirst($className);




        extract( $data,EXTR_PREFIX_SAME,'data' );

        ob_start();
        ob_implicit_flush( false );


        include $viewPath . DIRECTORY_SEPARATOR . $view . '.php';


        $output = ob_get_clean();


        if( $returnOutput )
            return $output;
        echo $output;


    }

    static function include_file( $file_path, $data = array(), $prefix = '', $returnOutput = false ){


        if(empty($prefix)){
            extract( $data,EXTR_PREFIX_SAME,'data' );

        }
        else
            extract( $data,EXTR_PREFIX_SAME );


        ob_start();
        ob_implicit_flush( false );



        include $file_path;

        $output = ob_get_clean();


        if( $returnOutput )
            return $output;
        echo $output;


    }

    static function do_module_exist( $module_name ){

        if( file_exists( App::$module_path . '/' . $module_name ) ){

            return true;
        }


        return false;
    }


    static function get_admin_op_type_for_post( $post_type, $currentScreen ){

        $postType = $currentScreen->post_type;
        $currentScreenID = $currentScreen->id;

        $action = $currentScreen->action;

        $is_create_op = false;
        $is_update_op = false;
        $is_manage_op = false;
        $is_del_op = false;

//        var_dump( $postType == $post_type );
//        die('sdw');


        if( $postType == $post_type ){





            switch($currentScreenID){

                case 'edit-' . $post_type:

                    $is_manage_op = true;

                    break;

                case $post_type:


                    if($action == 'add'){
                        $is_create_op = true;
                    }
                    else {

                        $getAction = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

                        if ($getAction == 'trash')
                            $is_del_op = true;
                        else
                            $is_update_op = true;
                    }

                    break;

            }





        }

        return array(
          'is_create_op' => $is_create_op,
          'is_update_op' =>$is_update_op,
          'is_manage_op' => $is_manage_op,
          'is_del_op' => $is_del_op
        );

    }




    static function die_if_empty( $var ){

//        foreach( $var_arr as $v){

            if( empty($var) ){

                wp_die( json_encode($var) . ' is empty');

            }
        }
//    }




    /**
     * Return Byte count of $val
     *
     * @link        http://wordpress.org/support/topic/how-to-exporting-a-lot-of-data-out-of-memory-issue?replies=2
     * @since       0.9.6
     */
    static function return_bytes( $val )
    {

        $val = trim( $val );
        $last = strtolower($val[strlen($val)-1]);
        switch( $last ) {

            // The 'G' modifier is available since PHP 5.1.0
            case 'g':

                $val *= 1024;

            case 'm':

                $val *= 1024;

            case 'k':

                $val *= 1024;

        }

        return $val;
    }


}