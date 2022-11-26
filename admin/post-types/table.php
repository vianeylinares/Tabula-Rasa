<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}


class Table {

    public function __construct() {

        add_action( 'init', array( $this, 'table_register_post_type' ) );    

        add_action( 'add_meta_boxes', array( $this, 'tabula_rasa_table_metabox' ) );    

    }

    // Register 'table' post type
    public function table_register_post_type(){

        $singular = 'Table';
        $plural = 'Tables';

        $labels = array(
                'all_items' => 'Tables',
                'menu_name' => 'Tables',
                'name' => $plural,
                'singular_name' => $singular,
                'add_name' => 'Add New ',
                'add_new_item' => 'Add New ' . $singular,
                'edit' => 'Edit ',
                'edit_item' => 'Edit ' . $singular,
                'new_item' => 'New ' . $singular,
                'view' => 'View ' . $singular,
                'view_item' => 'View ' . $singular,
                'search_term' => 'Search ' . $plural,
                'parent' => 'Parent ' . $singular,
                'not_found' => 'No ' . $plural . ' found',
                'not_found_in_trash' => 'No ' . $plural . ' in Trash',

        );


        $args = array (
                'labels' => $labels,
                'public' => true,
                'publicly_queryable' => true,
                'exclude_from_search' => false,
                'show_in_nac_menus' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_admin_bar' => false,
                'menu_position' => 10,
                'menu_icon' => 'dashicons-images-alt2',
                'can_export' => true,
                'delete_with_user' => false,
                'hierarchical' => false,
                'has_archive' => true,
                'query_var' => true,
                'capability_type' => 'post',
                'map_meta_cap' => true,
                'capabilities' => array(
                    //'create_posts' => 'do_not_allow',
                ),
                'rewrite' => array(
                        'slug' => 'tables',
                        'with_front' => true,
                        'pages' => true,
                        'feeds' =>  false,      
                ),
                'supports' => array(
                        'title',
                        //'editor',
                        //'author',
                        'custom_fields',
                        //'thumbnail'
                ),
                //'taxonomies'          => array( 'venues-category' ),

        );

        register_post_type('table', $args); 

    }

    public function tabula_rasa_table_metabox(){

        add_meta_box(

            'tabula_rasa_table_metabox',
            'Table details',
            array( $this, 'tabula_rasa_table_callback' ),
            'table'

        );

    }

    public function tabula_rasa_table_callback($post){

        $table_id = ( isset($_GET['post']) )? $_GET['post'] : 0 ;

        echo "Max seats: " . get_post_meta($table_id, 'max_seats', true); echo "<br/>";
        echo "Seats taken: " . get_post_meta($table_id, 'seats_taken', true); echo "<br/>";
        echo "Position - Top: " . get_post_meta($table_id, 'top', true); echo "<br/>";
        echo "Position - Left: " . get_post_meta($table_id, 'left', true); echo "<br/>";
        echo "Table number: " . get_post_meta($table_id, 'table_num', true); echo "<br/>";
        echo "Shape: " . get_post_meta($table_id, 'shape', true);
            
    }
    

}

$table = new Table();