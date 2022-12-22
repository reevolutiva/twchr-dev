<?php
// Menu consola de administracion en Dashboard WP
function twchr_main_menu() {

	add_submenu_page(
		'edit.php?post_type=twchr_streams', // $parent_slug
		'Twitcher Dashboard',  // $page_title
		'Twitcher Dashboard',        // $menu_title
		'manage_options',           // $capability
		'twchr-dashboard', // $menu_slug
		'twchr_main_page', // $function
		0
	);

	add_submenu_page(
		'edit.php?post_type=twchr_streams',
		__( 'Help', 'twitcher' ),
		__( 'Help', 'twitcher' ),
		'manage_options',
		'twchr_help',
		'twchr_menu_help',
	);

}

add_action( 'admin_menu', 'twchr_main_menu' );


// Template de menu prinicpal de plugin
function twchr_main_page() {
	require_once 'main-page.php';
}

// Template de menu secudario de plugin
function twchr_menu_help() {
	require_once 'submenu_menu_help.php';
}
