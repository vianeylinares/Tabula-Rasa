<?php

function tables_display(){
    
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
    
}