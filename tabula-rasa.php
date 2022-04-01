<?php
/*
 * Plugin name: Tabula Rasa (Experimental)
 * Plugin URI: Nuestra-Boda.com.mx
 * Description: Wedding guests seat assignment
 * Author: Nuestra-Boda
 * Version: 1.0.0
 * License: Open
 * 
 */

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}

include( plugin_dir_path( __FILE__ ) . 'admin/post-types/post-types.php' );
include( plugin_dir_path( __FILE__ ) . 'admin/post-types/post-types-metaboxes.php' );
include( plugin_dir_path( __FILE__ ) . 'admin/background-functions.php' );
include( plugin_dir_path( __FILE__ ) . 'admin/admin-setup.php' );
include( plugin_dir_path( __FILE__ ) . 'frontend/seats-assignment.php' );