<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}

function addGuests(){

	$table_id = (int)$_POST['data']['table_id'];
	$table_num = (int)$_POST['data']['table_num'];
	$guests_number = (int)$_POST['data']['guests_number'];
	$seats_taken = (int)$_POST['data']['seats_taken'];
	$main_guest = (int)$_POST['data']['main_guest'];
	$guests_group = explode(',', $_POST['data']['guests_group']);

	$seats_sum = $seats_taken + $guests_number;

	$max_seats = (int)get_post_meta($table_id, 'max_seats', true);

	if($seats_sum <= $max_seats){

		$seats_total = $seats_sum;

		update_post_meta($table_id, 'seats_taken', $seats_total);

		$seats_taken_display = array(
	        "seats_total" => $seats_total,
	        "max_seats" => $max_seats
	    );

		$args = array(
			'post_type' => 'guest',
			'order' => 'ASC',
			'posts_per_page' => '-1',
			'post__in' => $guests_group
		);

		$query = new WP_Query($args);

		/*$first_guest_name = (get_post_meta($main_guest, 'status', true))? '<b>' : '';
		$first_guest_name .= get_the_title($main_guest);
		$first_guest_name .= (get_post_meta($main_guest, 'status', true))? '</b>' : '';

		$seats_taken_display_additionals = array(
			"guest_1" => $first_guest_name,
			"guest_1_ID" => $main_guest
		);*/

		if ( $query->have_posts() ) :

			$seats_counter = 1;

			while($query -> have_posts()) : $query -> the_post();

				update_post_meta(get_the_ID(), 'assigned_table', $table_id);

				$guest_name = (get_post_meta(get_the_ID(), 'status', true))? '<b>' : '';
				$guest_name .= get_the_title(get_the_ID());
				$guest_name .= (get_post_meta(get_the_ID(), 'status', true))? '</b>' : '';

				$seats_taken_display_additionals["guest_" . $seats_counter] = $guest_name;
				$seats_taken_display_additionals["guest_" . $seats_counter . "_ID"] = get_the_ID();

				$seats_counter++;

			endwhile;

			$seats_taken_display_additionals["new_guests"] = $seats_counter - 1;

		endif; wp_reset_postdata();

		$seats_taken_display_additionals["new_guests"] = abs($seats_counter - 1);

		$seats_taken_display2 = array_merge($seats_taken_display, $seats_taken_display_additionals);

		echo json_encode($seats_taken_display2);

	}

	if($seats_sum > $max_seats){

		$seats_total = $seats_taken;

		$seats_available["none"] = 0;

		echo json_encode($seats_available);

	}

	die();

}
add_action('wp_ajax_addGuestsToTable', 'addGuests');