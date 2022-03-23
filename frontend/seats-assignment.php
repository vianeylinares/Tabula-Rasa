<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}


function tabula_rasa_frontend_css_and_js() {

	$plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'style', $plugin_url . 'css/style.css' );

}
add_action( 'wp_enqueue_scripts', 'tabula_rasa_frontend_css_and_js' );


function tabula_rasa_seats_assignment_shortcode(){

	ob_start();

	if ( is_user_logged_in() ) {

		

	} else {

		$args = array(
			'echo' => true,
			'redirect' => '',
			'form_id' => 'seat-loginform',
			'label_username' => __('Username'),
			'label_password' => __('Password'),
			'label_remember' => __('Remember Me'),
			'label_log_in' => __('Log In'),
			'id_username' => 'user_login',
			'id_password' => 'user_pass',
			'id_remember' => 'rememberme',
			'id_submit' => 'wp-submit',
			'remember' => true,
			'value_username' => NULL,
			'value_remember' => true,
		);

	    wp_login_form($args);

	}

	return ob_get_clean();

}
add_shortcode('seats_assignment', 'tabula_rasa_seats_assignment_shortcode');