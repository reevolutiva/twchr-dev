<?php
// Register Custom Post Type
function register_twchr_stream() {

	$labels = array(
		'name'                  => _x( 'streamings or vod', 'Post Type General Name', 'twitcher' ),
		'singular_name'         => _x( 'streamings or vod', 'Post Type Singular Name', 'twitcher' ),
		'menu_name'             => __( 'Twitcher', 'twitcher' ),
		'name_admin_bar'        => __( 'streamings or vod', 'twitcher' ),
		'archives'              => __( 'Archive streamings or vod', 'twitcher' ),
		'attributes'            => __( 'Atributes', 'twitcher' ),
		'parent_item_colon'     => __( 'Parent', 'twitcher' ),
		'all_items'             => __( 'All streamings or vod', 'twitcher' ),
		'add_new_item'          => __( 'Add new streamings or Vod', 'twitcher' ),
		'add_new'               => __( 'Add new streamings or vod', 'twitcher' ),
		'new_item'              => __( 'New streamings or Vod', 'twitcher' ),
		'edit_item'             => __( 'Edit streamings or Vod', 'twitcher' ),
		'update_item'           => __( 'Update streamings or Vod', 'twitcher' ),
		'view_item'             => __( 'View streamings or Vod', 'twitcher' ),
		'view_items'            => __( 'View streamings or vod', 'twitcher' ),
		'search_items'          => __( 'Search streamings or vod', 'twitcher' ),
		'not_found'             => __( 'streamings or Vod not found', 'twitcher' ),
		'not_found_in_trash'    => __( 'streamings or Vod not found in trash', 'twitcher' ),
		'featured_image'        => __( 'Featured image', 'twitcher' ),
		'set_featured_image'    => __( 'Set featured image', 'twitcher' ),
		'remove_featured_image' => __( 'Remove featured image', 'twitcher' ),
		'use_featured_image'    => __( 'Use featured image', 'twitcher' ),
		'insert_into_item'      => __( 'Insert streamings or vod', 'twitcher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this streamings or vod', 'twitcher' ),
		'items_list'            => __( 'List streamings or vod', 'twitcher' ),
		'items_list_navigation' => __( 'List navigation', 'twitcher' ),
		'filter_items_list'     => __( 'Filter streamings or vod list', 'twitcher' ),
	);

	$args = array(
		'label'                 => __( 'streamings or Vod', 'twitcher' ),
		'description'           => __( 'streamings or Vod de strean', 'twitcher' ),
		'labels'                => $labels,
		'supports'              => array(
			'title',
			'thumbnail',
			'editor',
			'comments',
			'excerpt',
		),
		'taxonomies'            => array( 'schedule', 'cat_twcht' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'menu_icon'             => TWCHR_URL_ASSETS . 'logo_menu.svg',
		'show_in_rest'          => false,

	);
	register_post_type( 'twchr_streams', $args );

}
add_action( 'init', 'register_twchr_stream', 0 );


