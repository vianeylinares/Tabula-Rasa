<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}

function get_guests_from_ninja_forms(){

    $form_id = 2;
	$submissions = Ninja_Forms()->form( $form_id )->get_subs();

	foreach ($submissions as $key => $submission) {

		$all_fields = $submission->get_field_values(); //echo $key . "<br/>";

		//print_r($all_fields['_sec_num']);

		$args2 = array(
			'post_type' => 'guest',
			'order' => 'ASC',
			'posts_per_page' => '-1',
			'meta_key' => 'main_guest_submission',
			'meta_value' => $key,
		);

		$query2 = new WP_Query($args2);

		//$findings = $query2->found_posts;

		//echo $findings;

		if ( !$query2->have_posts() ) :
				
		    // insert the new guest
			$main_guest_id = wp_insert_post(array (
			    'post_type' => 'guest',
			    'post_title' => $all_fields['name_field'],
			    'post_status' => 'publish',
			));

			update_post_meta($main_guest_id, 'main_guest_submission', $key);
			update_post_meta($main_guest_id, 'assigned_table', 0);
			update_post_meta($main_guest_id, 'status', 1);
			update_post_meta($main_guest_id, 'separated_from_group_of_guests', 0);
			update_post_meta($main_guest_id, 'arrived', 0);


		    if($all_fields['companions_field'] != ""){

				$companions = explode(' ', $all_fields['companions_field']);

				foreach ($companions as $key => $companion) {
					
					// insert the main guest companion
					$companion_id = wp_insert_post(array (
					    'post_type' => 'guest',
					    'post_title' => $companion,
					    'post_status' => 'publish',
					    'comment_status' => 'closed',
					    'ping_status' => 'closed',
					));

					if ($companion_id) {

					    // add companion bond to main guest
					    update_post_meta($companion_id, 'with_main_guest', $main_guest_id);
					    update_post_meta($companion_id, 'main_guest_submission', $all_fields['_sec_num']);
					    update_post_meta($companion_id, 'assigned_table', 0);
					    update_post_meta($companion_id, 'status', 0);
					    update_post_meta($companion_id, 'separated_from_group_of_guests', 0);
					    update_post_meta($companion_id, 'arrived', 0);

					}

				}

			}

		endif; wp_reset_postdata();

	}
	
}