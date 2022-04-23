<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}

function removeGuest(){
		
	$guest_id = (int)$_POST['data']['guest_id'];
	$table_id = (int)get_post_meta($guest_id, 'assigned_table', true);
	$table_pre_count = (int)get_post_meta($table_id, 'seats_taken', true);
	$table_count = $table_pre_count - 1;
	$max_seats = (int)get_post_meta($table_id, 'max_seats', true);

	update_post_meta($table_id, 'seats_taken', $table_count);

	update_post_meta($guest_id, 'assigned_table', 0);
	update_post_meta($guest_id, 'separated_from_group_of_guests', 1);
	//delete_post_meta($guest_id, 'with_main_guest');

	$guest_data = array(
        "guest_name" => get_the_title($guest_id),
        "guest_former_table" => $table_id,
        "table_count" => $table_count,
        "table_pre_count" => $table_pre_count,
        "table_max_seats" => $max_seats,
        "table_id" => $table_id,
    );		

	echo json_encode($guest_data);	

	die();

}
add_action('wp_ajax_removeGuestFromTable', 'removeGuest');