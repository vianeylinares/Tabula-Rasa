<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}

function removeGuestFromGroup(){

	$guest_id = (int)$_POST['data']['guest_id'];

	update_post_meta($guest_id, 'separated_from_group_of_guests', 1);

	$guest_name = (get_post_meta($guest_id, 'status', true))? '<b>' : '';
	$guest_name .= get_the_title($guest_id);
	$guest_name .= (get_post_meta($guest_id, 'status', true))? '</b>' : '';

	$guest_data = array(
        "guest_name" => $guest_name,
    );

	echo json_encode($guest_data);

	die();

}
add_action('wp_ajax_removeGuestFromGroup', 'removeGuestFromGroup');