<?php
/**
 * Plugin .
 *
 * DESCRIPTION.
 *
 * @link              http://www.teleioolab.com/
 * @since             1.0.0
 * @package           teleioolab
 *
 * @wordpress-plugin
 * Plugin Name:       Plugin Name ....
 * Plugin URI:        http://www.teleioolab.com/
 * Description:       DESC
 * Version:           1.0.0
 * Author:            My Name
 * Author URI:        teleioolab.com
 * License:           GPL-2.0+
 * License URI:       http://teleioolab.com
 * Text Domain:       plugin_namespace
 * Domain Path:       /asset/lang
 */
?>
<?php

include dirname(__FILE__) . '/class-app.php';


\plugin_namespace\App::init();


?>
<?php
/*
 * Replace the followings :
 * 1. Root namespace is "plugin_namespace" . Replace it with your plugin namespace
 */
?>