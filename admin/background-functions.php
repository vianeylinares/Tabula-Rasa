<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}

function createTable(){

	$table_shape = $_POST['data']['table_shape'];
	$table_seats = $_POST['data']['table_seats'];

	$args = array(
			'post_type' => 'table',
			'posts_per_page' => '-1'
		);

	$table_query = new WP_Query($args);

	$registered_tables_plus_one = $table_query->found_posts + 1;

	$table_id = wp_insert_post(array (
	    'post_type' => 'table',
	    'post_title' => 'Table ' . $registered_tables_plus_one,
	    'post_status' => 'publish',
	));

	if($table_id){
		update_post_meta($table_id, 'max_seats', $table_seats);
		update_post_meta($table_id, 'seats_taken', 0);
		update_post_meta($table_id, 'top', 0);
		update_post_meta($table_id, 'left', 0);
		update_post_meta($table_id, 'table_num', $registered_tables_plus_one);
	    update_post_meta($table_id, 'shape', $table_shape);
	}

	$table_data = array(
		"table_id" => $table_id,
		"table_shape" => $table_shape,
		"table_seats" => $table_seats,
		"total_tables" => $registered_tables_plus_one
	);

	echo json_encode($table_data);

	die();

}
add_action('wp_ajax_createTable', 'createTable');


function getLeftTopPositions(){

	$xPos = ($_POST['data']['x']/$_POST['data']['length']) * 100;
	$yPos = ($_POST['data']['y']/$_POST['data']['height']) * 100;
	$length = $_POST['data']['length'];
	$height = $_POST['data']['height'];
	$table_id = (int)$_POST['data']['item'];

	update_post_meta($table_id, 'left', $xPos);
	update_post_meta($table_id, 'top', $yPos);

	die();

}
add_action('wp_ajax_getLeftTop', 'getLeftTopPositions');