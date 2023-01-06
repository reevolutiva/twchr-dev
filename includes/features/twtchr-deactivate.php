<?php
// Desactivación del plugin y eliminación de datos.
function twchr_desactivar() {
	// Eliminar datos en BDD correpondientes al pluigin al desactivar el plugin
	
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
		$all_terms_serie = get_terms('serie',[
			'hide_empty' => false
		]);
		$all_terms_cat_tw = get_terms('cat_twcht',[
			'hide_empty' => false
		]);
	
		foreach($all_terms_serie as $term){
			wp_delete_term($term->term_id,'serie');
		}
		
		foreach($all_terms_cat_tw as $term){
			wp_delete_term($term->term_id,'cat_twcht');
		}
	}
	
}


