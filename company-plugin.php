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
 * Text Domain:       company_plugin
 * Domain Path:       /asset/lang
 */
?>
<?php

include dirname(__FILE__) . '/class-app.php';


\company_plugin\App::init();


?>
<?php
/*
 * Replace the followings :
 * 0. Replace "company-plugin" plugin directory name with your company name-plugin name
 * 1. Root namespace "company_plugin"  with your plugin namespace
 */
?>