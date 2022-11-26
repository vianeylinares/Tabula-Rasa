<?php

//Exit if accessed directly
if(!defined('ABSPATH')){
       exit;
}


class Guest {

    public function __construct() {

        add_action( 'init', array( $this, 'guest_register_post_type' ) );

        add_action( 'add_meta_boxes', array( $this, 'tabula_rasa_guest_metabox' ) );

    }

    // Register 'guest' post type
    public function guest_register_post_type(){

        $singular = 'Guest';
        $plural = 'Guests';

        $labels = array(
                'all_items' => 'Guests',
                'menu_name' => 'Guests',
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
                        'slug' => 'guests',
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
                //'taxonomies'          => array( 'guest-category' ),

        );

        register_post_type('guest', $args); 

    }

    public function tabula_rasa_guest_metabox(){

        add_meta_box(

            'tabula_rasa_guest_metabox',
            'Guest details',
            array( $this, 'tabula_rasa_guest_callback' ),
            'guest'

        );

    }

    public function tabula_rasa_guest_callback($post){

        $guest_id = ( isset($_GET['post']) )? $_GET['post'] : 0 ;

        echo "Status: " . get_post_meta($guest_id, 'status', true); echo "<br/>";
        echo "Assigned table: " . get_post_meta($guest_id, 'assigned_table', true); echo "<br/>";
        echo "Separated from group of guests: " . get_post_meta($guest_id, 'separated_from_group_of_guests', true);
            
    }
    
}

$guest = new Guest();