<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die();
}
if ( get_option( 'twchr_delete_all' ) == 1 ) {
	delete_option( 'twchr_keys' );
	delete_option( 'twchr_app_token' );
	delete_option( 'twchr_setInstaled' );
	delete_option( 'twchr_data_broadcaster' );
	delete_option( 'twchr_delete_all' );
	delete_option( 'twchr_share_permissions' );
	delete_option( 'twchr_installation_date' );
	delete_option( 'twchr_log' );

	$allposts = get_posts(
		array(
			'post_type' => 'twchr_streams',
			'numberposts' => -1,
		)
	);
	foreach ( $allposts as $eachpost ) {
		wp_delete_post( $eachpost->ID, true );
	}
}



