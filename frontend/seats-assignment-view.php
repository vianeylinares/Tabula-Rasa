<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}


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

							tables_display();

						?>

					</div>

				</div>

				<div class="guests-box">

					<p>Guests</p>

					<?php
					
					    get_guests_from_ninja_forms();

						guests_display();

					?>

				</div>

			</div>

		<?php

	} else {

		tr_login_form();

	}

	return ob_get_clean();

}
add_shortcode( 'seats_assignment', 'tabula_rasa_seats_assignment_shortcode' );