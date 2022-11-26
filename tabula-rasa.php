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


include( plugin_dir_path( __FILE__ ) . 'admin/post-types/table.php' );
include( plugin_dir_path( __FILE__ ) . 'admin/post-types/guest.php' );
include( plugin_dir_path( __FILE__ ) . 'admin/post-types/venue.php' );
include( plugin_dir_path( __FILE__ ) . 'admin/functions/table-creation.php' );
include( plugin_dir_path( __FILE__ ) . 'admin/functions/table-setup.php' );
include( plugin_dir_path( __FILE__ ) . 'admin/admin-setup.php' );
include( plugin_dir_path( __FILE__ ) . 'frontend/tr-enqueue.php' );
include( plugin_dir_path( __FILE__ ) . 'frontend/functions/tr-login-form.php' );
include( plugin_dir_path( __FILE__ ) . 'frontend/functions/tables-display.php' );
include( plugin_dir_path( __FILE__ ) . 'frontend/functions/guests-display.php' );
include( plugin_dir_path( __FILE__ ) . 'frontend/functions/guests-from-ninja-forms.php' );
include( plugin_dir_path( __FILE__ ) . 'frontend/functions/guests-add-table.php' );
include( plugin_dir_path( __FILE__ ) . 'frontend/functions/guest-remove-table.php' );
include( plugin_dir_path( __FILE__ ) . 'frontend/functions/guest-remove-group.php' );
include( plugin_dir_path( __FILE__ ) . 'frontend/functions/arrived-guest-register.php' );
include( plugin_dir_path( __FILE__ ) . 'frontend/seats-assignment-view.php' );
include( plugin_dir_path( __FILE__ ) . 'frontend/guest-table-view.php' );
include( plugin_dir_path( __FILE__ ) . 'frontend/guest-group-view.php' );