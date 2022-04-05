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
    wp_enqueue_style( 'magnific-popup-css', $plugin_url . '/magnific-popup/magnific-popup.css' );
    wp_enqueue_script( 'magnific-popup-js', $plugin_url . '/magnific-popup/jquery.magnific-popup.js', false );

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
												<img src="<?php echo home_url(); ?>/wp-content/plugins/tabula-rasa/images/table-<?php echo get_post_meta(get_the_ID(), 'shape', true ); ?>-<?php echo get_post_meta(get_the_ID(), 'max_seats', true ); ?>-seat-0.png" />
											</div>

											<div class="table-visual-ref"><?php echo get_post_meta(get_the_ID(), 'table_num', true); ?></div>

											<a id="table-popup-<?php echo get_the_ID(); ?>" class="popup-with-zoom-anim" style="display: none !important;" href="#small-dialog-<?php echo get_the_ID(); ?>">View table assigned guests...</a>

											<div id="small-dialog-<?php echo get_the_ID(); ?>" class="zoom-anim-dialog mfp-hide" style="min-height: 300px;">

												<h2>Table #<?php echo get_post_meta(get_the_ID(), 'table_num', true); ?> Guests:</h2>

												<div class="table-guests-list">

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

						/*echo "<pre>";
							print_r( $submissions );
						echo "</pre>";*/

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
				
								//while($query2 -> have_posts()) : $query2 -> the_post();
									
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
											    'comment_status' => 'closed',   // if you prefer
											    'ping_status' => 'closed',      // if you prefer
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

								//endwhile;				

							endif; wp_reset_postdata();

						}

						$args = array(
							'post_type' => 'guest',
							'order' => 'ASC',
							'posts_per_page' => -1,
							//'meta_key' => 'with_main_guest',
					    	//'meta_value' =>  0,
						);

						$query = new WP_Query($args);

						$count_draggs = 1;

						//$draggers = "";

						if ( $query->have_posts() ) :
						
							while($query -> have_posts()) : $query -> the_post();

								//$with_main_guest = get_post_custom(get_the_ID());

								$count_guest = 1;	

								/* $with_main_guest['with_main_guest'][0] not set means this is "main guest" */

								//if( !isset( $with_main_guest['with_main_guest'][0] ) ){
								if( get_post_meta(get_the_ID(), 'status', true) == 1 && get_post_meta(get_the_ID(), 'assigned_table', true) == 0 ){

									?>

										<div id="draggable-<?php echo $count_draggs; ?>" class="guests-group draggable">

											<input type="checkbox" value="<?php echo get_the_ID(); ?>" checked /> <b><?php echo get_the_title(); ?></b><br/>

											<?php 

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

														?>

															<input type="checkbox" value="<?php echo get_the_ID(); ?>" checked /> <?php echo get_the_title() ?><br/>

														<?php

														$count_guest++;

													endwhile;				

												endif; wp_reset_postdata();

											?>

											<div class="separate disable">Separar</div>

										</div>

									<?php						

									//$draggers.= ".draggable-" . $count_draggs;

									$count_draggs++;

									//echo "<br/>";

								}

							endwhile;

						else:

						    echo 'no posts found';

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


function tabula_rasa_seats_assignment_js(){

	global $post;

	if(has_shortcode( $post->post_content, 'seats_assignment' )){

		?>

			<script type="text/javascript">

				jQuery(document).ready(function($){

					home_url = "<?php echo home_url(); ?>";

					jQuery( ".popup-with-zoom-anim" ).magnificPopup({

					    type: 'inline',

					    fixedContentPos: false,
					    fixedBgPos: true,

					    overflowY: 'auto',

					    closeBtnInside: true,
					    preloader: false,

					    midClick: true,
					    removalDelay: 300,
					    mainClass: 'my-mfp-zoom-in'

					});

					jQuery( ".table" ).click(function(){

						which_table = jQuery(this).attr('id');
						which_table_num = which_table.substr(which_table.indexOf('-') + 1);

						jQuery("#table-popup-" + which_table_num + ".popup-with-zoom-anim").click();

					});

					jQuery( ".separate" ).click(function(){

						which_separator = jQuery(this).parent().attr('id');
						which_separator_num = which_separator.substr(which_separator.indexOf('-') + 1);

						if( jQuery(this).hasClass('disable') ){

							/* Disable other active separators and checkboxes */
							jQuery( ".separate" ).removeClass("active");
							jQuery( ".separate" ).addClass("disable");
							jQuery( ".guests-group input" ).css({
								"display": "none",
							});

							jQuery(this).removeClass("disable");
							jQuery(this).addClass("active");

							jQuery( "#" + which_separator + " input" ).css({
								"display": "inline-block",
							});

							jQuery(this).parent().draggable( "disable" );
							jQuery(this).parent().css({"cursor":"auto"});

							return false;

						}

						if( jQuery(this).hasClass('active') ){

							jQuery(this).removeClass("active");
							jQuery(this).addClass("disable");

							jQuery( "#" + which_separator + " input" ).css({
								"display": "none",
							});

							jQuery(this).parent().draggable( "enable" );
							jQuery(this).parent().css({"cursor":"move"});

							return false;

						}

					});

					jQuery( ".draggable" ).draggable({
						revert: "invalid",
						drag: function(event, ui){
							which_guest_group = jQuery(this).attr('id');
						}
			        });

			        jQuery( ".droppable" ).droppable({
						drop: function( event, ui ) {
							jQuery("#" + which_guest_group).css({
								"display": "none",
							});
						}
			        });


				});

			</script>

		<?php

	}

}
add_action( 'wp_footer', 'tabula_rasa_seats_assignment_js', 999 );