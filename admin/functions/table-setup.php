<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}

function getLeftTopPositions(){

	$xPos = ($_POST['data']['x']/$_POST['data']['length']) * 100;
	$yPos = ($_POST['data']['y']/$_POST['data']['height']) * 100;
	$length = $_POST['data']['length'];
	$height = $_POST['data']['height'];
	$table_id = (int)$_POST['data']['item'];

	update_post_meta($table_id, 'left', $xPos);
	update_post_meta($table_id, 'top', $yPos);

	die();

}
add_action('wp_ajax_getLeftTop', 'getLeftTopPositions');