<?php
function twchr_flush_cache_on_save_post( $post_id ) {
	// Comprueba si se ha guardado una entrada o una página
	if ( get_post_type( $post_id ) == 'post' || get_post_type( $post_id ) == 'page' ) {
		wp_cache_flush();
	}
}
  add_action( 'save_post', 'twchr_flush_cache_on_save_post' );

