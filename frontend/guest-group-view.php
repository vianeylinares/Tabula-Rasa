<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}

function tabula_rasa_group_view_shortcode(){

	ob_start();

	if ( is_user_logged_in() ) {

		?>

			<div class="assignment-wrapper clearfix">
			    
			    <?php

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

								if( get_post_meta(get_the_ID(), 'status', true) == 1 && get_post_meta(get_the_ID(), 'assigned_table', true) != 0){

									?>

										<div class="report-group">
										    
										    <div class="table-title" style="margin-bottom: 30px;"><?php echo get_the_title(); ?>'s group</div>

											<div id="user-<?php echo get_the_ID(); ?>" class="table-list">
												<input type="checkbox" value="<?php echo get_the_ID(); ?>" <?php echo (get_post_meta(get_the_ID(), 'arrived', true))? "checked" : "" ; ?> /> 
												<span <?php echo (get_post_meta(get_the_ID(), 'arrived', true))? "class='arrived'" : "" ; ?>>
                        				            <b><?php echo get_the_title(); ?></b>
                        				        </span>
                        				        <?php $table_num = (int)get_post_meta(get_the_ID(), 'assigned_table', true); ?>
                        				        (Table: <?php echo get_post_meta($table_num, 'table_num', true); ?>)
											</div>

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

														if( get_post_meta(get_the_ID(), 'assigned_table', true) != 0 ){

															?>
																<div id="user-<?php echo get_the_ID(); ?>" class="table-list">
                    												<input type="checkbox" value="<?php echo get_the_ID(); ?>" <?php echo (get_post_meta(get_the_ID(), 'arrived', true))? "checked" : "" ; ?> /> 
                    												<span <?php echo (get_post_meta(get_the_ID(), 'arrived', true))? "class='arrived'" : "" ; ?>>
                                            				            <?php echo get_the_title(); ?>
                                            				        </span>
                                            				        <?php $table_num = (int)get_post_meta(get_the_ID(), 'assigned_table', true); ?>
                        				                            (Table: <?php echo get_post_meta($table_num, 'table_num', true); ?>)
                    											</div>

															<?php

														}

													endwhile;				

												endif; wp_reset_postdata();

											?>

										</div>

									<?php

								}

							endwhile;

						else:

						    echo 'no guests found';

						endif; wp_reset_postdata();

				?>
			    
			</div>

		<?php

	} else {

		tr_login_form();

	}

	return ob_get_clean();

}
add_shortcode( 'group_view', 'tabula_rasa_group_view_shortcode' );