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
    wp_enqueue_style( 'magnific-popup-css', $plugin_url . 'magnific-popup/magnific-popup.css' );
    wp_enqueue_script( 'magnific-popup', $plugin_url . 'magnific-popup/jquery.magnific-popup.js', array(), "", false );

    /* Custom JS functions files */
    wp_enqueue_script( 'guests-groups-setup', $plugin_url . 'functions/guests-groups-setup.js', array(), "", true );
    wp_enqueue_script( 'guests-add-table', $plugin_url . 'functions/guests-add-table.js', array(), "", true );
    wp_enqueue_script( 'guest-remove-table', $plugin_url . 'functions/guest-remove-table.js', array(), "", true );
    wp_enqueue_script( 'guest-remove-group', $plugin_url . 'functions/guest-remove-group.js', array(), "", true );
    wp_enqueue_script( 'table-popup-setup', $plugin_url . 'functions/table-popup-setup.js', array(), "", true );
    wp_enqueue_script( 'arrived-guest-register', $plugin_url . 'functions/arrived-guest-register.js', array(), "", true );

}
add_action( 'wp_enqueue_scripts', 'tabula_rasa_frontend_css_and_js' );


function tabula_rasa_seats_assignment_js(){

	global $post;

	if(has_shortcode( $post->post_content, 'seats_assignment' ) || has_shortcode( $post->post_content, 'table_view' ) || has_shortcode( $post->post_content, 'group_view' )){

		?>

			<script type="text/javascript">

				jQuery(document).ready(function($){

					home_url = "<?php echo home_url(); ?>";

					set_draggable();

				});

			</script>

		<?php

	}

}
add_action( 'wp_footer', 'tabula_rasa_seats_assignment_js', 999 );