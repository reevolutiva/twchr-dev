<?php 
// Register Custom Post Type
function register_twchr_stream() {

	$labels = array(
		'name'                  => _x( 'Streamings', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Streaming', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Streamings', 'text_domain' ),
		'name_admin_bar'        => __( 'Streamings', 'text_domain' ),
		'archives'              => __( 'Archivo de Streamings', 'text_domain' ),
		'attributes'            => __( 'Atrubutos', 'text_domain' ),
		'parent_item_colon'     => __( 'Padre', 'text_domain' ),
		'all_items'             => __( 'Todos los Streamings', 'text_domain' ),
		'add_new_item'          => __( 'Añadir nuevo Streaming', 'text_domain' ),
		'add_new'               => __( 'Añadir nuevo', 'text_domain' ),
		'new_item'              => __( 'Nuevo Streaming', 'text_domain' ),
		'edit_item'             => __( 'Editar Streaming', 'text_domain' ),
		'update_item'           => __( 'Actualizar Streaming', 'text_domain' ),
		'view_item'             => __( 'Ver Streaming', 'text_domain' ),
		'view_items'            => __( 'Ver Streamings', 'text_domain' ),
		'search_items'          => __( 'Buscar Streamings', 'text_domain' ),
		'not_found'             => __( 'Streaming no encontrado', 'text_domain' ),
		'not_found_in_trash'    => __( 'Streaming no encontrado en basura', 'text_domain' ),
		'featured_image'        => __( 'Imagen destacada', 'text_domain' ),
		'set_featured_image'    => __( 'Establecer imagen destacada', 'text_domain' ),
		'remove_featured_image' => __( 'Remover imagen destacada', 'text_domain' ),
		'use_featured_image'    => __( 'Usar imagen destacada', 'text_domain' ),
		'insert_into_item'      => __( 'Insertar en Streaming', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Subir a este Streaming', 'text_domain' ),
		'items_list'            => __( 'Lista de Streamings', 'text_domain' ),
		'items_list_navigation' => __( 'Lista de navegacion', 'text_domain' ),
		'filter_items_list'     => __( 'Filtro de lista', 'text_domain' ),
	);
	/*
	‘title’ *
	‘editor’ (content) *
	‘author’ *
	‘thumbnail’ (featured image, current theme must also support post-thumbnails) *
	‘excerpt’ *
	‘trackbacks’
	‘custom-fields’ *
	‘comments’ (also will see comment count balloon on edit screen) *
	‘revisions’ (will store revisions) *
	‘page-attributes’ (menu order, hierarchical must be true to show Parent option) *
	‘post-formats’ add post formats, see Post Formats *
	*/
	$args = array(
		'label'                 => __( 'Streaming', 'text_domain' ),
		'description'           => __( 'Streaming de strean', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 
											'title',
											'thumbnail',
											'editor',
											'comments',
											'excerpt' 
										),
		'taxonomies'            => array( 'schedule' ),
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
		'menu_icon' 			=> 'dashicons-rss',
		'show_in_rest'			=> false

	);
	register_post_type( 'twchr_streams', $args );

}
add_action( 'init', 'register_twchr_stream', 0 );
?>