<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}


function tabularasa_add_settings_page() {

    add_menu_page( 'Tabula Rasa - Table settings', 'Tabula Rasa', 'manage_options', 'tabula-rasa-settings', 'tabularasa_render_plugin_settings_page', 'dashicons-art', 3 );

}
add_action( 'admin_menu', 'tabularasa_add_settings_page' );


function tabularasa_render_plugin_settings_page() {

    ?>

    <h1>Tabula Rasa - Settings</h1>

    <h2>Table setup</h2>

    

	<?php

}