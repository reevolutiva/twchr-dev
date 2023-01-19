<?php

// Register custom taxonomy for Streamings
function register_streaming_status_taxonomy() {
    $args = array(
        'labels' => array(
            'name' => 'Streaming States',
            'singular_name' => 'Streaming State',
            'menu_name' => 'Streaming States',
            'all_items' => 'All Streaming States',
            'edit_item' => 'Edit Streaming State',
            'view_item' => 'View Streaming State',
            'update_item' => 'Update Streaming State',
            'add_new_item' => 'Add New Streaming State',
            'new_item_name' => 'New Streaming State Name',
            'parent_item' => 'Parent Streaming State',
            'parent_item_colon' => 'Parent Streaming State:',
            'search_items' => 'Search Streaming States',
            'popular_items' => 'Popular Streaming States',
            'separate_items_with_commas' => 'Separate streaming states with commas',
            'add_or_remove_items' => 'Add or remove streaming states',
            'choose_from_most_used' => 'Choose from the most used streaming states',
            'not_found' => 'No streaming states found'
            ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
        'hierarchical' => false,
        'query_var' => true,
        'rewrite' => array('slug' => 'twchr-streaming-states'),
        'default_term' => 'Future'
        );
        
    register_taxonomy( 'twchr_streaming_states', 'twchr_streams', $args );

    // Creo los 3 estados.

    // Si no existe lo creo.
    if(get_term('Future','twchr_streaming_states') == null){
        wp_insert_term('Future','twchr_streaming_states');
    }

    // Si no existe lo creo.
    if(get_term('Live','twchr_streaming_states') == null){
        wp_insert_term('Live','twchr_streaming_states');
    }

    // Si no existe lo creo.
    if(get_term('Past','twchr_streaming_states') == null){
        wp_insert_term('Past','twchr_streaming_states');
    }
    
}

add_action( 'init', 'register_streaming_status_taxonomy' );


// ALGORITMO.
/*
    IF CREATE FROM TWCJER CARD -> STATUS FUTURE
    ELSE IF SCHEDULE START TIME == NOW TIME -> STATUS LIVE
    ELSE IF SCHEDULE END TIME < NOW TIME -> STATUS PASSED
    ELSE IF SCHEDULE EMBED TRUE -> STATUS EMBED
*/

