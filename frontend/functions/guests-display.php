<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}

function guests_display(){

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
	
}