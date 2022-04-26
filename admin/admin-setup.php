<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}


function tabularasa_add_settings_page() {

    add_menu_page( 'Tabula Rasa - Table settings', 'Tabula Rasa', 'manage_options', 'tabula-rasa-settings', 'tabularasa_render_plugin_settings_page', 'dashicons-art', 3 );

}
add_action( 'admin_menu', 'tabularasa_add_settings_page' );


function tabularasa_admin_css_and_js(){

	$plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'admin-style', $plugin_url . '../admin/css/style.css' );

    wp_enqueue_script( 'jquery' );

    /* jQuery Draggable and Droppable */
    wp_enqueue_script( 'jquery-ui-draggable' );

    /* Table creation JS */
    wp_register_script( 'table-creation-js', plugin_dir_url( __FILE__ ) . '../admin/functions/table-creation.js', array('jquery'), '1', true );
    wp_enqueue_script( 'table-creation-js' );

    /* Table setup JS */
    wp_register_script( 'table-setup-js', plugin_dir_url( __FILE__ ) . '../admin/functions/table-setup.js', array('jquery'), '1', true );
    wp_enqueue_script( 'table-setup-js' );

}
add_action( 'admin_enqueue_scripts', 'tabularasa_admin_css_and_js' );


function tabularasa_render_plugin_settings_page() {

    ?>

    <h1>Tabula Rasa - Settings</h1>

    <div class="table-types-box">

		<h2>Table types</h2>

		<div class="table-types-space clearfix">

			<div class="table-box" id="rectangular-10">
				<img src="<?php echo plugin_dir_url( __FILE__ ); ?>../images/table-rectangular-10-seat-0.png" />
			</div>

			<div class="table-box" id="round-10">
				<img src="<?php echo plugin_dir_url( __FILE__ ); ?>../images/table-round-10-seat-0.png" />
			</div>

			<div class="table-box" id="round-8">
				<img src="<?php echo plugin_dir_url( __FILE__ ); ?>../images/table-round-8-seat-0.png" />
			</div>

			<div class="table-box" id="round-9">
				<img src="<?php echo plugin_dir_url( __FILE__ ); ?>../images/table-round-9-seat-0.png" />
			</div>

			<div class="table-box" id="squared-4">
				<img src="<?php echo plugin_dir_url( __FILE__ ); ?>../images/table-squared-4-seat-0.png" />
			</div>

		</div>

	</div>

	<div class="venue-space-box">

		<?php

			$args = array(
				'post_type' => 'table',
				'posts_per_page' => '-1'
			);

			$table_query = new WP_Query($args);

		?>

		<h2>Registered tables: <span style="font-weight: normal;"><?php echo $table_query->found_posts; ?></span></h2>

		<div class="venue-spaces">

			<div class="venue">

				<?php

					$args_venue = array(
							'post_type' => 'venue',
							'posts_per_page' => '-1'
						);

					$query_venue = new WP_Query($args_venue);

					if ( $query_venue->have_posts() ) :

						while($query_venue -> have_posts()) : $query_venue -> the_post();

							$image_url = get_the_post_thumbnail_url(get_the_ID(),'full');

						endwhile;

					endif;

				?>

				<img src="<?php echo $image_url; ?>">

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

									<div id="table-<?php echo get_the_ID(); ?>" class="table draggable" style="top: <?php echo get_post_meta(get_the_ID(), 'top', true) . '%' ?>; left: <?php echo get_post_meta(get_the_ID(), 'left', true) . '%' ?>;">

										<div class="table-visual">
											<img src="<?php echo home_url(); ?>/wp-content/plugins/tabula-rasa/images/table-<?php echo get_post_meta(get_the_ID(), 'shape', true ); ?>-<?php echo get_post_meta(get_the_ID(), 'max_seats', true ); ?>-seat-0.png" />
										</div>

										<div class="table-visual-ref"><?php echo get_post_meta(get_the_ID(), 'table_num', true); ?></div>

									</div>

								<?php

							endwhile;

						endif;

					?>

				</div>

			</div>

		</div>

	</div>

	<?php

}


function tabularasa_custom_admin_js() {
    
	?>

		<script type="text/javascript">			

			jQuery(document).ready(function($){

				home_url = "<?php echo home_url(); ?>";
					
			});

		</script>

	<?php

}
add_action('admin_footer', 'tabularasa_custom_admin_js');