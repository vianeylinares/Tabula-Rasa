<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}

function arrivedGuestRegister(){

	$guest_id = (int)$_POST['data']['guest_id'];

	update_post_meta($guest_id, 'arrived', 1);

	$guest_data = array(
        "guest_id" => $guest_id,
    );

	echo json_encode($guest_data);

	die();

}
add_action('wp_ajax_arrivedGuest', 'arrivedGuestRegister');


function leftGuestRegister(){

	$guest_id = (int)$_POST['data']['guest_id'];

	update_post_meta($guest_id, 'arrived', 0);

	$guest_data = array(
        "guest_id" => $guest_id,
    );

	echo json_encode($guest_data);

	die();

}
add_action('wp_ajax_leftGuest', 'leftGuestRegister');