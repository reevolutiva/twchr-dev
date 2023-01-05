<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die();
}
if ( get_option( 'twchr_delete_all' ) == 1 ) {
	global $wpdb;
	//Elimina todas las entradas en wp_options que comiencen con twchr
	$wpdb->query( "DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE 'twchr_%';");

	// Custom Post Types
	$allposts = get_posts(
		array(
			'post_type' => 'twchr_streams',
			'numberposts' => -1,
		)
	);
	foreach ( $allposts as $eachpost ) {
		wp_delete_post( $eachpost->ID, true );
	}

	//Terms
	$all_terms = get_terms([
		'taxonomy' => 'serie'
	]);

	foreach($all_terms as $term){
		wp_delete_term($term->term_id,[
			'taxonomy' => 'serie',
		]);
	}
}

// HOLA GIO :)


