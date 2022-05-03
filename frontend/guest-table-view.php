<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}

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
                                
                                				    <div id="user-<?php echo get_the_ID(); ?>" class="table-list">
                                				        <input type="checkbox" value="<?php echo get_the_ID(); ?>" <?php echo (get_post_meta(get_the_ID(), 'arrived', true))? "checked" : "" ; ?> > 
                                				        <span <?php echo (get_post_meta(get_the_ID(), 'arrived', true))? "class='arrived'" : "" ; ?>>
                                				            <?php echo get_the_title(); ?>
                                				        </span>
                                				    </div>
                                				    
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

		tr_login_form();

	}

	return ob_get_clean();

}
add_shortcode( 'table_view', 'tabula_rasa_table_view_shortcode' );