<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}


function tabularasa_add_settings_page() {

    add_menu_page( 'Tabula Rasa - Table settings', 'Tabula Rasa', 'manage_options', 'tabula-rasa-settings', 'tabularasa_render_plugin_settings_page', 'dashicons-art', 3 );

}
add_action( 'admin_menu', 'tabularasa_add_settings_page' );


function tabularasa_admin_css_and_js(){

	$plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'admin-style', $plugin_url . '../admin/css/style.css' );

}
add_action( 'admin_enqueue_scripts', 'tabularasa_admin_css_and_js' );


function tabularasa_render_plugin_settings_page() {

    ?>

    <h1>Tabula Rasa - Settings</h1>

    <div class="table-types-box">

		<h2>Table types</h2>

		<div class="table-types-space clearfix">

			<div class="table-box" id="table-rectangular-10-seat">
				<img src="<?php echo plugin_dir_url( __FILE__ ); ?>../images/table-rectangular-10-seat-0.png" />
			</div>

			<div class="table-box" id="table-round-10-seat">
				<img src="<?php echo plugin_dir_url( __FILE__ ); ?>../images/table-round-10-seat-0.png" />
			</div>

			<div class="table-box" id="table-round-8-seat">
				<img src="<?php echo plugin_dir_url( __FILE__ ); ?>../images/table-round-8-seat-0.png" />
			</div>

			<div class="table-box" id="table-round-9-seat">
				<img src="<?php echo plugin_dir_url( __FILE__ ); ?>../images/table-round-9-seat-0.png" />
			</div>

			<div class="table-box" id="table-squared-4-seat">
				<img src="<?php echo plugin_dir_url( __FILE__ ); ?>../images/table-squared-4-seat-0.png" />
			</div>

		</div>

	</div>

	<?php

}