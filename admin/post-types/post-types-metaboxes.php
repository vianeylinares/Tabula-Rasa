<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}


function tabula_rasa_table_metabox(){

    add_meta_box(

        'tabula_rasa_table_metabox',
        'Table details',
        'tabula_rasa_table_callback',
        'table'

    );

}
add_action('add_meta_boxes', 'tabula_rasa_table_metabox');


function tabula_rasa_table_callback($post){

    $table_id = ( isset($_GET['post']) )? $_GET['post'] : 0 ;

    echo "Max seats: " . get_post_meta($table_id, 'max_seats', true); echo "<br/>";
    echo "Seats taken: " . get_post_meta($table_id, 'seats_taken', true); echo "<br/>";
    echo "Position - Top: " . get_post_meta($table_id, 'top', true); echo "<br/>";
    echo "Position - Left: " . get_post_meta($table_id, 'left', true); echo "<br/>";
    echo "Table number: " . get_post_meta($table_id, 'table_num', true); echo "<br/>";
    echo "Shape: " . get_post_meta($table_id, 'shape', true);
        
}