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

					<div id="draggable-1" class="guests-group draggable">
						<input type="checkbox" checked /> <b>Name 1</b><br/>
						<input type="checkbox" checked /> Name 2<br/>
						<input type="checkbox" checked /> Name 3<br/>
						<input type="checkbox" checked /> Name 4
						<div class="separate disable">Separar</div>
					</div>

					<div id="draggable-2" class="guests-group draggable">
						<input type="checkbox" checked /> <b>Name 1</b><br/>
						<input type="checkbox" checked /> Name 2<br/>
						<input type="checkbox" checked /> Name 3
						<div class="separate disable">Separar</div>
					</div>

					<div id="draggable-3" class="guests-group draggable">
						<input type="checkbox" checked /> <b>Name 1</b><br/>
						<input type="checkbox" checked /> Name 2<br/>
						<input type="checkbox" checked /> Name 3<br/>
						<input type="checkbox" checked /> Name 4<br/>
						<input type="checkbox" checked /> Name 5
						<div class="separate disable">Separar</div>
					</div>

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