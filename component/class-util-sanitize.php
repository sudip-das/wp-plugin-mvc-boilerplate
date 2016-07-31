<?php
namespace company_plugin\component;

class Util_Sanitize {

    public static function sanitize_field($field_type,$field_name, $new_values, $old_value){
        $sanitize_value='';

//        var_dump( $field_name );
//        var_dump( $new_values );
//        die(' sd');

        switch($field_type){
            case 'text':
            case 'attr':
            case 'color_picker':
            case 'slider':
            case 'select':
            case 'class_switcher':
            case 'font':
            case 'upload':
            case 'revealer_group':
                if ( isset( $new_values[$field_name] ) ) {
                    $sanitize_value = self::sanitize_text($new_values[$field_name]);
                } else {
                    $sanitize_value = self::sanitize_text($old_value);
                }
                break;

            case 'checkbox':
            case 'remover_checkbox':
                if ( isset( $new_values[$field_name] ) ) {
                    $sanitize_value = 'on';
                } else {
                    $sanitize_value = '';
                }
                break;

            case 'editor':
                if ( isset( $new_values[$field_name] ) ) {
                    $sanitize_value = self::sanitize_editor($new_values[$field_name]);
                } else {
                    $sanitize_value = self::sanitize_editor($old_value);
                }
                break;
        }

        return $sanitize_value;
    }


    public static function sanitize_text($new_value){
        return sanitize_text_field($new_value);
    }

    public static function sanitize_editor($new_value){
        return wp_kses_post($new_value);
    }
}