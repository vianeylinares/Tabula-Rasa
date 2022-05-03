<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}

function tr_login_form(){

    $args = array(
    	'echo' => true,
    	'redirect' => home_url(),
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