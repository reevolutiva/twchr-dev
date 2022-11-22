<?php

add_action('init', 'twchr_taxonomy_cat_twcht');
function twchr_taxonomy_cat_twcht() {
    $labels = array(
        'name'              => _x( 'Category Twitch', 'taxonomy general name' , 'twitcher'),
        'singular_name'     => _x( 'Category Twitch', 'taxonomy singular name' , 'twitcher'),
        'search_items'      => __( 'Search Categorys Twitch' , 'twitcher'),
        'all_items'         => __( 'All Categorys Twitch' , 'twitcher'),
        'parent_item'       => __( 'Parent Category Twitch' , 'twitcher'),
        'parent_item_colon' => __( 'Parent Category Twitch:' , 'twitcher'),
        'edit_item'         => __( 'Edit Category Twitch' , 'twitcher'),
        'update_item'       => __( 'Update Category Twitch' , 'twitcher'),
        'add_new_item'      => __( 'Add New Category Twitch' , 'twitcher'),
        'new_item_name'     => __( 'New Category Twitch Name' , 'twitcher'),
        'menu_name'         => __( 'Category Twitch' , 'twitcher'),
    );
    $args = array( 
        'hierarchical'      => false, 
		 'labels'            => $labels,
		 'show_ui'           => true,
		 'show_admin_column' => true,
		 'query_var'         => true,
		 'rewrite'           => [ 'slug' => 'cat_twcht' ],
    );
    register_taxonomy( 'cat_twcht', array( 'post', 'twchr_streams' ), $args );
}