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

    /* Table creation JS */
    wp_register_script( 'table-creation-js', plugin_dir_url( __FILE__ ) . '../admin/js/table-creation.js', array('jquery'), '1', true );
    wp_enqueue_script( 'table-creation-js' );

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

				<img src="<?php echo home_url(); ?>/wp-content/uploads/2021/09/distribution-tables-blank.jpg">

				<div class="table-distribution-box">

					<div id="table-1" class="table">

						<div class="table-visual">
							<img src="<?php echo home_url(); ?>/wp-content/plugins/tabula-rasa/images/table-rectangular-10-seat-0.png" />
						</div>

						<div class="table-visual-ref">1</div>

					</div>

					<div id="table-2" class="table">

						<div class="table-visual">
							<img src="<?php echo home_url(); ?>/wp-content/plugins/tabula-rasa/images/table-rectangular-10-seat-0.png" />
						</div>

						<div class="table-visual-ref">2</div>

					</div>

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