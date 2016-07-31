<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2015-03-28
 * Time: 02:28 PM
 */

namespace plugin_namespace\controller;


use plugin_namespace\App;


class Base {


    public  $view_path;





//    static $i_inst_no = 0;
//    static $_arr_instance;
//    public static function get_instance( $config = array() ){
//
//       // echo "instance no. = " . self::$instNo++;
//       // var_dump(self::$_instance);
//
//       // echo 'sd';
//
//        //var_dump(get_called_class());
//
//        //if(empty(self::$_instance)){
//
//            $class = get_called_class(); //get_class($this); //get_called_class();//get_called_class();
//
//           // echo 'class = ';
//            // var_dump($class);die('f');
//
//            //var_dump(get_called_class());die('s');
//
////            echo 'inside get_instance ';
////            var_dump($class);
//
//            if (!isset(self::$_arr_instance[$class])) {
//
//
//                //var_dump($class);
//
//
//                self::$_arr_instance[$class] = new $class( $class, $config );
//            }
//
//
//       //var_dump(self::$_instance);echo '<hr/>';//die('k');
//
//
//            return self::$_arr_instance[$class];
//        //}
//
//    }


//    public function __construct( $get_called_class, $config = array() ){// = null){//$childClassNameSpace){

    public function __construct( $config = array() ){// = null){//$childClassNameSpace){

        $get_called_class = get_called_class(); //get_class($this); //get_called_class();//get_called_class();

        $arr_class_ns = explode('\\', $get_called_class);


//        var_dump($arr_class_ns);
//        die('sd');

        $sz = count( $arr_class_ns );

        $class_name = $arr_class_ns[$sz-1];


        $path = '';
        for( $i=2; $i < ($sz - 1); $i++ ){

            $path .= DIRECTORY_SEPARATOR . $arr_class_ns[$i];
        }

//        var_dump( $arr_class_ns );
//        var_dump( $class_name );
//        var_dump( $path );
        //die('sdw');

        $c_name = '';

        $tmp_arr = explode( '_', $class_name );
        foreach( $tmp_arr as $tmp ){
            $c_name .= lcfirst($tmp) . '_';
        }
        $c_name = rtrim($c_name, '_');


        $this->view_path = App::$dir_path . DIRECTORY_SEPARATOR;

        if( $arr_class_ns[1] != 'controller' ){
            $this->view_path .= $arr_class_ns[1] . DIRECTORY_SEPARATOR;
        }

        $this->view_path .= 'view' . $path . DIRECTORY_SEPARATOR . $c_name;

//        echo 'view_path = ';
//        var_dump($this->view_path);


        $this->init( $config );



    }
    protected function init( ){ // $config = array() ){


    }


    public function render( $view,$data = array(), $return_output = false, $do_prefix = false, $prefix = 'data' ){

//        die('fkw');

        if( $do_prefix ){
            extract( $data, EXTR_PREFIX_SAME, $prefix );

        }
        else{

            extract( $data );
        }


        ob_start();
        ob_implicit_flush(false);


//        var_dump($this->viewPath . DIRECTORY_SEPARATOR . $view . '.php');
//        die('s');

//        var_dump($this->view_path);die('sd');

//        if( !file_exists($this->view_path . DIRECTORY_SEPARATOR . $view . '.php')){
//
//            var_dump($this->view_path . DIRECTORY_SEPARATOR . $view . '.php');
//            die('wtf');
//        }

//        var_dump( $this->view_path . DIRECTORY_SEPARATOR . $view . '.php' );
//        die('kkwww');

        require $this->view_path . DIRECTORY_SEPARATOR . $view . '.php';


        $output = ob_get_clean();

//        var_dump($output);
//        die('fk');


        if($return_output)
            return $output;
        echo $output;


    }

}
