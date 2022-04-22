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

																//$with_main_guest = get_post_custom(get_the_ID());

																//echo (!isset( $with_main_guest['with_main_guest'][0] ))? "<div class='guester guest-id_" . get_the_ID() . "'><input type='checkbox' value='" . get_the_ID() ."' checked disabled /> " : "<div class='guester guest-id_" . get_the_ID() . "'>&nbsp;&nbsp;&nbsp;<input type='checkbox' value='" . get_the_ID() ."' checked /> "; echo get_the_title(); echo '</div>';

																?>

																	<div class='guester guest-id_<?php echo get_the_ID(); ?>'>
																		<input type='checkbox' value='<?php echo get_the_ID(); ?>' checked /> <?php echo get_the_title(); ?>
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

									//$draggers.= ".draggable-" . $count_draggs;

									$count_draggs++;

									//echo "<br/>";

								}

							endwhile;

						else:

						    echo 'no posts found';

						endif; wp_reset_postdata();

						if ( $query->have_posts() ) :
						
							while($query -> have_posts()) : $query -> the_post();

								//$with_main_guest = get_post_custom(get_the_ID());

								$count_guest = 1;	

								/* $with_main_guest['with_main_guest'][0] not set means this is "main guest" */

								//if( !isset( $with_main_guest['with_main_guest'][0] ) ){
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

				jQuery('body').on( 'click', '.guester input[type=checkbox]', function($) {

					//jQuery(".guester input[type=checkbox]").click(function(){

						//alert("Here");

						which_guest_id = jQuery(this).val();

						console.log(which_guest_id);

						which_table = jQuery(".guest-id_" + which_guest_id).parent().parent().attr('id');

						which_table_num = which_table.substr(which_table.indexOf('-') + 8);

						jQuery(".guest-id_" + jQuery(this).val()).remove();

						console.log(which_guest_id);

						jQuery.post(

							home_url + '/wp-admin/admin-ajax.php', {

								action: 'removeGuestFromTable',

									data: { guest_id: which_guest_id },
									dataType: "json"

							}, function(datas){

								datas = jQuery.parseJSON(datas);

								console.log(datas);

								removed_guest_from_table = "<div id='draggable-" + which_guest_id + "' class='guests-group draggable returned'>";
								removed_guest_from_table+= datas.guest_name;
								removed_guest_from_table+= "<div num_guests='1' class='num-guests'></div>";
								removed_guest_from_table+= "<div group_array='" + which_guest_id + "' class='group-array'></div>";
								removed_guest_from_table+= "</div>";

								jQuery(".guests-box").append(removed_guest_from_table);

								set_draggable();

								seats_taken_display = "<div class='table-id' table-id='" + datas.table_id + "'></div>";
								seats_taken_display+= "<div class='seats-taken' seats-taken='" + datas.table_count + "'></div>";
								seats_taken_display+= "<style>#table-" + which_table_num + " .table-visual img{ display: none; } #table-" + which_table_num + " .table-visual img:nth-child(" + (datas.table_count + 1) + "){ display: block; }</style>";

								console.log(seats_taken_display);

								jQuery("#table-" + which_table_num + " .table-data").html(seats_taken_display);

								if(datas.table_pre_count == datas.table_max_seats){
									//jQuery('#table-' + which_table_num ).droppable("enable");
								}

								//location.reload();

							}

						);

					//});




				});

				jQuery(document).ready(function($){

					home_url = "<?php echo home_url(); ?>";

					set_draggable();

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

			        jQuery( ".droppable" ).droppable({
						drop: function( event, ui ) {
							jQuery("#" + which_guest_group).css({
								"display": "none",
							});

							which_table = jQuery(this).attr('id');
							which_table_num = which_table.substr(which_table.indexOf('-') + 1);
							table_seats_taken =  jQuery("#" + which_table + " div.seats-taken").attr('seats-taken');

							numbers = jQuery("#" + which_guest_group + " div.num-guests").attr("num_guests");
							table = jQuery("#" + which_table + " div.table-id").attr("table-id");
							with_main_guest = jQuery("#" + which_guest_group + " div.with-main-guest").attr("with_main_guest");

							which_guest_group_num = which_guest_group.substr(which_guest_group.indexOf('-') + 1);

							guests_group = jQuery("#" + which_guest_group + " div.group-array").attr("group_array");

							console.log(guests_group);

							console.log("Which table: " + which_table + ", Table num: " + which_table_num + ", Seats taken: " + table_seats_taken + ", Guests: " + numbers + ", Table id: " + table, "guests_group: " + guests_group);

							jQuery.post(

								home_url + '/wp-admin/admin-ajax.php', {

									action: 'addGuestsToTable',

										data: { table_id: table, table_num: which_table_num, guests_number: numbers, seats_taken: table_seats_taken, main_guest: with_main_guest, guests_group: guests_group },
										dataType: "json"

								}, function(datas){

									datas = $.parseJSON(datas);

									console.log(datas);

									if(datas.none == undefined){

										seats_taken_display = "<div class='table-id' table-id='" + table + "'></div>";
										seats_taken_display+= "<div class='seats-taken' seats-taken='" + datas.seats_total + "'></div>";
										seats_taken_display+= "<style>#table-" + which_table_num + " .table-visual img{ display: none; } #table-" + which_table_num + " .table-visual img:nth-child(" + (datas.seats_total + 1) + "){ display: block; }</style>";

										jQuery("#"+ which_table + " .table-data").html(seats_taken_display);

										if(datas.seats_total == datas.max_seats){
											jQuery('#table-' + which_table_num ).droppable("disable");
										}

										new_seats_display = "";

										for( i=1; i<=datas.new_guests; i++ ){

											//if(i!=1){
												//new_seats_display+= "<div class='guester guest-id_" +  datas['guest_'+i+'_ID'] + "'>&nbsp;&nbsp;&nbsp;<input type='checkbox' value='" +  datas['guest_'+i+'_ID'] + "' checked /> ";
											//}else{
												//new_seats_display+= "<div class='guester guest-id_" +  datas['guest_'+i+'_ID'] + "'><input type='checkbox' value='" +  datas['guest_'+i+'_ID'] + "' checked disabled /> ";
											//}

											new_seats_display+= "<div class='guester guest-id_" +  datas['guest_'+i+'_ID'] + "'>";
											new_seats_display+= "<input type='checkbox' value='" +  datas['guest_'+i+'_ID'] + "' checked/> ";
											new_seats_display+= datas["guest_"+i];
											new_seats_display+= "</div>";

										}

										jQuery("#small-dialog-"+ which_table_num + " .table-guests-list").append(new_seats_display);

										//jQuery("body").prepend("<div class='mfp-bg my-mfp-zoom-in mfp-ready'></div>");

										//location.reload();

									}

									if(datas.none == 0 ){

										alert("No hay asientos suficientes en esta mesa");

										//return false;
										jQuery("#" + which_guest_group).css({
											"display": "block",
											"top": "auto",
											"left": "auto",
											"width": "100%",
											"height": "auto"
										});

										jQuery("#" + which_guest_group).draggable({revert:"invalid"});

									}


								}

							);

						}

			        });


					jQuery(".guests-group input[type=checkbox]").click(function(){

						which_guest_id = jQuery(this).val();
						which_guest_group = jQuery(this).parent().parent().attr('id');

						jQuery(this).parent().remove();

						new_num_guests = parseInt(jQuery("#" + which_guest_group + " .num-guests").attr("num_guests")) - 1;

						jQuery("#" + which_guest_group + " .num-guests").attr("num_guests", new_num_guests);

						group_array = jQuery("#" + which_guest_group + " .group-array").attr("group_array");
						new_group_array = group_array.replace(which_guest_id,'');
						new_group_array = new_group_array.replace(',,',',');

						jQuery("#" + which_guest_group + " .group-array").attr("group_array", new_group_array);

						jQuery.post(

							home_url + '/wp-admin/admin-ajax.php', {

								action: 'removeGuestFromGroup',

									data: { guest_id: jQuery(this).val() },
									dataType: "json"

							}, function(datas){

								datas = $.parseJSON(datas);

								removed_guest_from_table = "<div id='draggable-" + which_guest_id + "' class='guests-group draggable returned'>";
								removed_guest_from_table+= datas.guest_name;
								removed_guest_from_table+= "<div num_guests='1' class='num-guests'></div>";
								removed_guest_from_table+= "<div group_array='" + which_guest_id + "' class='group-array'></div>";
								removed_guest_from_table+= "</div>";

								jQuery(".guests-box").append(removed_guest_from_table);

								set_draggable();

							}

						);

					});

				});

				function set_draggable(){

					jQuery( ".draggable" ).draggable({
						revert: "invalid",
						drag: function(event, ui){
							which_guest_group = jQuery(this).attr('id');
						}
			        });

				}

			</script>

		<?php

	}

}
add_action( 'wp_footer', 'tabula_rasa_seats_assignment_js', 999 );


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

		$seats_taken_display_additionals = array(
			"guest_1" => get_the_title($main_guest),
			"guest_1_ID" => $main_guest
		);

		if ( $query->have_posts() ) :

			$seats_counter = 1;

			while($query -> have_posts()) : $query -> the_post();

				update_post_meta(get_the_ID(), 'assigned_table', $table_id);

				$seats_taken_display_additionals["guest_" . $seats_counter] = get_the_title(get_the_ID());
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


function removeGuestFromGroup(){

	$guest_id = (int)$_POST['data']['guest_id'];

	update_post_meta($guest_id, 'separated_from_group_of_guests', 1);

	$guest_data = array(
        "guest_name" => get_the_title($guest_id),
    );

	echo json_encode($guest_data);

	die();

}
add_action('wp_ajax_removeGuestFromGroup', 'removeGuestFromGroup');