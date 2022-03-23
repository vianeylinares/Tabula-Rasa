<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}


function tabula_rasa_frontend_css_and_js() {

	$plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'style', $plugin_url . 'css/style.css' );

}
add_action( 'wp_enqueue_scripts', 'tabula_rasa_frontend_css_and_js' );


function tabula_rasa_seats_assignment_shortcode(){

	ob_start();

	if ( is_user_logged_in() ) {

		?>

			<div class="assignment-wrapper clearfix">

				<div class="venue-space-box">

					<img src="<?php echo home_url(); ?>/wp-content/uploads/2021/10/distribution-tables-blank.jpg" class="source-image" />

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

				<div class="guests-box">

					<p>Guests</p>

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
add_shortcode('seats_assignment', 'tabula_rasa_seats_assignment_shortcode');