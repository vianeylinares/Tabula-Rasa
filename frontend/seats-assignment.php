<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}


function tabula_rasa_frontend_css_and_js() {

	$plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'style', $plugin_url . 'css/style.css' );

    wp_enqueue_script( 'jquery' );

    /* jQuery Draggable and Droppable */
    wp_enqueue_script( 'jquery-ui-draggable' );
    wp_enqueue_script( 'jquery-ui-droppable' );

    /* Magnific Popup */
    wp_enqueue_style( 'magnific-popup-css', $plugin_url . 'magnific-popup/magnific-popup.css' );
    wp_enqueue_script( 'magnific-popup-js', $plugin_url . 'magnific-popup/jquery.magnific-popup.js', array(), "", false );

    /* Custom JS functions files */
    wp_enqueue_script( 'guests-groups-setup-js', $plugin_url . 'functions/guests-groups-setup.js', array(), "", true );
    wp_enqueue_script( 'guests-add-table-js', $plugin_url . 'functions/guests-add-table.js', array(), "", true );
    wp_enqueue_script( 'guest-remove-table-js', $plugin_url . 'functions/guest-remove-table.js', array(), "", true );
    wp_enqueue_script( 'guest-remove-group-js', $plugin_url . 'functions/guest-remove-group.js', array(), "", true );
    wp_enqueue_script( 'table-popup-setup-js', $plugin_url . 'functions/table-popup-setup.js', array(), "", true );

}
add_action( 'wp_enqueue_scripts', 'tabula_rasa_frontend_css_and_js' );


function tabula_rasa_seats_assignment_shortcode(){

	ob_start();

	if ( is_user_logged_in() ) {

		?>

			<div class="assignment-wrapper clearfix">

				<div class="venue-space-box">

					<?php

						$args_venue = array(
								'post_type' => 'venue',
								'posts_per_page' => '1'
							);

						$query_venue = new WP_Query($args_venue);

						if ( $query_venue->have_posts() ) :

							while($query_venue -> have_posts()) : $query_venue -> the_post();

								$image_url = get_the_post_thumbnail_url(get_the_ID(),'full');

							endwhile; wp_reset_postdata();

						endif;

					?>

					<img src="<?php echo $image_url; ?>" class="source-image" />

					<div class="table-distribution-box">

						<?php

							$args_table = array(
									'post_type' => 'table',
									'order' => 'DESC',
									'posts_per_page' => '-1'
								);

							$query_table = new WP_Query($args_table);

							if ( $query_table->have_posts() ) :

								while($query_table -> have_posts()) : $query_table -> the_post();

									?>

										<div id="table-<?php echo get_the_ID(); ?>" class="table droppable" style="top: <?php echo get_post_meta(get_the_ID(), 'top', true) . '%' ?>; left: <?php echo get_post_meta(get_the_ID(), 'left', true) . '%' ?>;">

											<div class="table-visual">

												<?php

													$table_total_seats = (int)get_post_meta(get_the_ID(), 'max_seats', true);

													for( $i = 0; $i <= $table_total_seats; $i++ ) {

														?>

															<img src="<?php echo home_url(); ?>/wp-content/plugins/tabula-rasa/images/table-<?php echo get_post_meta(get_the_ID(), 'shape', true ); ?>-<?php echo get_post_meta(get_the_ID(), 'max_seats', true ); ?>-seat-<?php echo $i; ?>.png" />

														<?php

													}

												?>

											</div>

											<div class="table-data">
												<div class="table-id" table-id="<?php echo get_the_ID(); ?>"></div>
												<div class="seats-taken" seats-taken="<?php echo get_post_meta(get_the_ID(), 'seats_taken', true); ?>"></div>
												<style>#table-<?php echo get_the_ID(); ?> .table-visual img:nth-child(<?php echo get_post_meta(get_the_ID(), 'seats_taken', true) + 1; ?>){ display: block; }</style>
											</div>

											<div class="table-visual-ref"><?php echo get_post_meta(get_the_ID(), 'table_num', true); ?></div>

											<a id="table-popup-<?php echo get_the_ID(); ?>" class="popup-with-zoom-anim" style="display: none !important;" href="#small-dialog-<?php echo get_the_ID(); ?>">View table assigned guests...</a>

											<div id="small-dialog-<?php echo get_the_ID(); ?>" class="zoom-anim-dialog mfp-hide" style="min-height: 300px;">

												<h2>Table #<?php echo get_post_meta(get_the_ID(), 'table_num', true); ?> Guests:</h2>

												<div class="table-guests-list">

													<?php

														$args = array(
															'post_type' => 'guest',
															'order' => 'ASC',
															'posts_per_page' => -1,
															'meta_key' => 'assigned_table',
															'meta_value' =>  get_the_ID()
														);

														$query = new WP_Query($args);


														if ( $query->have_posts() ) :

															while($query -> have_posts()) : $query -> the_post();

																?>

																	<div class='guester guest-id_<?php echo get_the_ID(); ?>'>
																		<input type='checkbox' value='<?php echo get_the_ID(); ?>' checked /> 
																		<?php echo (get_post_meta(get_the_ID(), 'status', true))? "<b>" : "" ; ?><?php echo get_the_title(); ?>
																		<?php echo (get_post_meta(get_the_ID(), 'status', true))? "</b>" : "" ; ?>
																	</div>

																<?php

															endwhile;

														else:

														    //echo 'No guests assigned';

														endif; wp_reset_postdata();

													?>

												</div>

											</div>

										</div>

									<?php

								endwhile; wp_reset_postdata();

							endif;

						?>

					</div>

				</div>

				<div class="guests-box">

					<p>Guests</p>

					<?php

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

										}

									}

								}

							endif; wp_reset_postdata();

						}

						$args = array(
							'post_type' => 'guest',
							'order' => 'ASC',
							'posts_per_page' => -1,
						);

						$query = new WP_Query($args);

						$count_draggs = 1;

						if ( $query->have_posts() ) :
						
							while($query -> have_posts()) : $query -> the_post();

								$count_guest = 1;	

								if( get_post_meta(get_the_ID(), 'status', true) == 1 && get_post_meta(get_the_ID(), 'assigned_table', true) == 0 && get_post_meta(get_the_ID(), 'separated_from_group_of_guests', true) == 0){

									?>

										<div id="draggable-<?php echo $count_draggs; ?>" class="guests-group draggable">

											<div>
												<input type="checkbox" value="<?php echo get_the_ID(); ?>" checked /> <b><?php echo get_the_title(); ?></b>
											</div>

											<?php

												$user_group_array = strval(get_the_ID());

												$main_guest = get_the_ID();

												$args2 = array(
													'post_type' => 'guest',
													'order' => 'ASC',
													'posts_per_page' => '-1',
													'meta_key' => 'with_main_guest',
							    					'meta_value' =>  $main_guest,
												);

												$query2 = new WP_Query($args2);

												if ( $query2->have_posts() ) :
									
													while($query2 -> have_posts()) : $query2 -> the_post();

														if( get_post_meta(get_the_ID(), 'assigned_table', true) == 0 && get_post_meta(get_the_ID(), 'separated_from_group_of_guests', true) == 0){

															?>
																<div>
																	<input type="checkbox" value="<?php echo get_the_ID(); ?>" checked /> <?php echo get_the_title() ?>
																</div>

															<?php

															$count_guest++;

															$user_group_array.= "," . strval(get_the_ID());

														}

													endwhile;				

												endif; wp_reset_postdata();

											?>

											<div num_guests="<?php echo $count_guest; ?>" class='num-guests'></div>

											<div group_array="<?php echo $user_group_array; ?>" class="group-array"></div>

											<?php if( $count_guest > 1 ){ ?>

												<div class="separate disable">Separar</div>

											<?php } ?>

										</div>

									<?php

									$count_draggs++;

								}

							endwhile;

						else:

						    echo 'no guests found';

						endif; wp_reset_postdata();

						if ( $query->have_posts() ) :
						
							while($query -> have_posts()) : $query -> the_post();

								$count_guest = 1;	

								if( get_post_meta( get_the_ID(), 'separated_from_group_of_guests', true) == 1 && get_post_meta( get_the_ID(), 'assigned_table', true) == 0 ){

									?>

										<div id="draggable-<?php echo $count_draggs; ?>" class="guests-group draggable">

											<div>

												<input type="checkbox" value="<?php echo get_the_ID(); ?>" checked />

												<?php

													echo ( get_post_meta(get_the_ID(), 'status', true) == 1 )? " <b>" : "" ;
													echo get_the_title();
													echo ( get_post_meta(get_the_ID(), 'status', true) == 1 )? "</b>" : "" ;

												?>

											</div>

											<div num_guests="<?php echo $count_guest; ?>" class='num-guests'></div>

											<div group_array="<?php echo get_the_ID(); ?>" class="group-array"></div>

										</div>

									<?php

									$count_draggs++;

								}

							endwhile;

						else:

						    //echo 'no guests found';

						endif; wp_reset_postdata();

					?>

				</div>

			</div>

		<?php

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
add_shortcode( 'seats_assignment', 'tabula_rasa_seats_assignment_shortcode' );


function tabula_rasa_table_view_shortcode(){

	ob_start();

	if ( is_user_logged_in() ) {

		?>

			<div class="assignment-wrapper clearfix">
			    
			    <?php

					$args_table = array(
							'post_type' => 'table',
							'order' => 'ASC',
							'posts_per_page' => '-1'
						);

					$query_table = new WP_Query($args_table);

					if ( $query_table->have_posts() ) :

						while($query_table -> have_posts()) : $query_table -> the_post();
						
						    ?>
						    
    						    <div class="table-box">
    
        							<div class="table-title">Table #<?php echo get_post_meta(get_the_ID(), 'table_num', true); ?></div>
        							<div style="text-align: center; margin-bottom: 30px;">
        							    <b>Shape:</b> <?php echo get_post_meta(get_the_ID(), 'shape', true); ?> - 
        							    <b>Seats taken:</b> <?php echo get_post_meta(get_the_ID(), 'seats_taken', true); ?> - 
        							    <b>Max seats:</b> <?php echo get_post_meta(get_the_ID(), 'max_seats', true); ?>
        							</div>
        					        
        					        <?php
        							
            							$table_id = get_the_ID();
            							
            							$args = array(
                                			'post_type' => 'guest',
                                			'order' => 'ASC',
                                			'posts_per_page' => '-1',
                                			'meta_key' => 'assigned_table',
                                			'meta_value' => $table_id
                                		);
                                
                                		$query = new WP_Query($args);
                                		
                                		if ( $query->have_posts() ) :
            
                                			while($query -> have_posts()) : $query -> the_post();
                                			
                                			    ?>
                                
                                				    <div class="table-list"><input type="checkbox" value="<?php echo get_the_ID(); ?>" > <span><?php echo get_the_title(); ?></span></div>
                                				    
                                				<?php
                                
                                			endwhile;
                                
                                		endif; wp_reset_postdata();
                                		
                                	?>
                        		
                        		</div>
                        		
                    		<?php

						endwhile; wp_reset_postdata();

					endif;

				?>
			    
			</div>

		<?php

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
add_shortcode( 'table_view', 'tabula_rasa_table_view_shortcode' );


function tabula_rasa_seats_assignment_js(){

	global $post;

	if(has_shortcode( $post->post_content, 'seats_assignment' ) || has_shortcode( $post->post_content, 'table_view' )){

		?>

			<script type="text/javascript">

				jQuery(document).ready(function($){

					home_url = "<?php echo home_url(); ?>";

					set_draggable();

				});

			</script>

		<?php

	}

}
add_action( 'wp_footer', 'tabula_rasa_seats_assignment_js', 999 );