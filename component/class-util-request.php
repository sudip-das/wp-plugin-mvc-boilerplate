<?php
namespace company_plugin\component;

class Util_Request {




//    static $_is_ajax;
    static function is_ajax(){


//        self::$_is_ajax = false;
        if( defined('DOING_AJAX') && DOING_AJAX ) {

//            self::$_is_ajax = true;
            return true;
        }
        return false;

//        return self::$_is_ajax;

    }


    static function is_frontend_ajax()
    {
        $script_filename = isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '';

        //Try to figure out if frontend AJAX request... If we are DOING_AJAX; let's look closer
        if( self::is_ajax() )
        {
            //From wp-includes/functions.php, wp_get_referer() function.
            //Required to fix: https://core.trac.wordpress.org/ticket/25294
            $ref = '';
            if ( ! empty( $_REQUEST['_wp_http_referer'] ) )
                $ref = wp_unslash( $_REQUEST['_wp_http_referer'] );
            elseif ( ! empty( $_SERVER['HTTP_REFERER'] ) )
                $ref = wp_unslash( $_SERVER['HTTP_REFERER'] );

            //If referer does not contain admin URL and we are using the admin-ajax.php endpoint, this is likely a frontend AJAX request
            if(((strpos($ref, admin_url()) === false) && (basename($script_filename) === 'admin-ajax.php')))
                return true;
        }

        //If no checks triggered, we end up here - not an AJAX request.
        return false;
    }


    static function is_admin(){

        $request_is_frontend_ajax = self::is_frontend_ajax();

        $request_is_admin = false;

//        var_dump( is_admin() );
//        die('skw');


        if(is_admin())
            $request_is_admin = true;

        if( $request_is_frontend_ajax )
            $request_is_admin = false;

        return $request_is_admin;

    }


    static function is_localhost(){

        $whitelist = array(
            '127.0.0.1',
            '::1'
        );

        if( in_array($_SERVER['REMOTE_ADDR'], $whitelist) ){
            // not valid
            return true;
        }
        return false;
    }


    static $_hostInfo = null;
    public function getHostInfo($schema='')
    {
        if(self::$_hostInfo===null)
        {
            if($secure=self::getIsSecureConnection())
                $http='https';
            else
                $http='http';
            if(isset($_SERVER['HTTP_HOST']))
                self::$_hostInfo=$http.'://'.$_SERVER['HTTP_HOST'];
            else
            {
                self::$_hostInfo=$http.'://'.$_SERVER['SERVER_NAME'];
                $port=$secure ? self::getSecurePort() : self::getPort();
                if(($port!==80 && !$secure) || ($port!==443 && $secure))
                    self::$_hostInfo.=':'.$port;
            }
        }
        if($schema!=='')
        {
            $secure=self::getIsSecureConnection();
            if($secure && $schema==='https' || !$secure && $schema==='http')
                return self::$_hostInfo;

            $port=$schema==='https' ? self::getSecurePort() : self::getPort();
            if($port!==80 && $schema==='http' || $port!==443 && $schema==='https')
                $port=':'.$port;
            else
                $port='';

            $pos=strpos(self::$_hostInfo,':');
            return $schema.substr(self::$_hostInfo,$pos,strcspn(self::$_hostInfo,':',$pos+1)+1).$port;
        }
        else
            return self::$_hostInfo;
    }



    static function getIsSecureConnection()
    {
        return !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'],'off');
    }

    static $_securePort = null;
    public function getSecurePort()
    {
        if(self::$_securePort===null)
            self::$_securePort=self::getIsSecureConnection() && isset($_SERVER['SERVER_PORT']) ? (int)$_SERVER['SERVER_PORT'] : 443;
        return self::$_securePort;
    }

    static $_port = null;
    static function getPort()
    {
        if(self::$_port===null)
            self::$_port=!self::getIsSecureConnection() && isset($_SERVER['SERVER_PORT']) ? (int)$_SERVER['SERVER_PORT'] : 80;
        return self::$_port;
    }

    static function get_the_user_ip() {
        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
//check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
//to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}