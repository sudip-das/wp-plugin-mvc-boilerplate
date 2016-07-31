<?php
namespace company_plugin\component;

class Util_Meta {

    public static function save_meta( $meta_name, $meta_value, $id ) {
        $meta_value = sanitize_text_field( $meta_value );
        update_post_meta( $id, $meta_name, $meta_value );
    }


    static function get_post_meta_all( $post_id_arr ){
        global $wpdb;

        $data = array();

        $in_exp = '';



            foreach( $post_id_arr as $p){
                $in_exp .= $p . ',';

            }
            $in_exp = preg_replace( '/,$/','', $in_exp );



        $res = $wpdb->get_results( "
			SELECT post_id, `meta_key`, `meta_value`
			FROM $wpdb->postmeta
			WHERE `post_id` in ($in_exp)", ARRAY_A);

//        var_dump( $wpdb->last_error );

//        var_dump( $in_exp);
//
//        var_dump( $res );die();

        $ret = array();

        foreach( $res as $r ){
            //$data[$v->meta_key] =   $v->meta_value;

            if( !isset( $ret[$r['post_id']]))
                $ret[$r['post_id']] = array();

            $ret[$r['post_id']][$r['meta_key']] = $r['meta_value'];




        };
        return $ret;
    }

}