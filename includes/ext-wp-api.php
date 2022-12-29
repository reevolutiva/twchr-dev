<?php
/**
 * En este archivo esta todas las funciones que extienden la API REST de WordPress
 */

/**
 * Haciendo visible en el Enpoint
 * Taxonomía Series
 * twchr_endpoint_tax
 * twchr_endpoint_tax_register_serie
**/
 function twchr_endpoint_tax_register_serie() {
	register_rest_route(
		'twchr/v1',
		'twchr_get_serie',
		array(
			'methods'  => WP_REST_Server::READABLE,
			'callback' => 'twchr_endpoint_tax_register_callback_serie',
		/** 
		'permission_callback' => function(){
			return current_user_can( 'read' );
		}
		*/

		)
	);
}
add_action( 'rest_api_init', 'twchr_endpoint_tax_register_serie' );// twchr_endpoint_tax_register_serie

/**
 * Recopila las taxonomías y las pasa al Endpoint de Wordpress
 *
 * @param [type] $request
 * @return void
 */
function twchr_endpoint_tax_register_callback_serie( $request ) {
	$args = array(
		'taxonomy' => 'serie',
		'hide_empty' => false,
	);
	$request = get_terms( $args );
	$response = array();
	foreach ( $request as $term ) {
		$term_id = $term->{'term_id'};
		$array_rest = array(
			'term_id' => $term_id,
			'name' => $term->{'name'},
			'taxonomy' => $term->{'taxonomy'},
			'chapters' => json_decode( get_term_meta( $term_id, 'twchr_schdules_chapters' )[0] ),
		);

		array_push( $response, $array_rest );
	}

	return $response;
}
/**
 *  Fin Taxonomía Series 
 */

/**
 * Hago visible a los CPT Streamins en la API REST de WordPress
 *
 * @return void
 */
function twchr_endpoint_cpt_register_streaming() {
	register_rest_route(
		'twchr/v1',
		'twchr_get_streaming',
		array(
			'methods'  => 'GET',
			'callback' => 'twchr_endpoint_cpt_register_callback_streaming',
		/**
		'permission_callback' => function(){
			return current_user_can( 'read' );
		}
		*/
		)
	);
}
add_action( 'rest_api_init', 'twchr_endpoint_cpt_register_streaming' );

/**
 * Recopilo los CPT Streamings
 *
 * @param [type] $request
 * @return void
 */
function twchr_endpoint_cpt_register_callback_streaming( $request ) {
	// Solicita a BDD todos los post-type = twchr_streams que esten plubicados.
	$posts = get_posts(
		array(
			'post_type'  => 'twchr_streams',
			'post_status' => 'publish',
		)
	);

	// Inicializo un array vacio.
	$array_response = array();

	// Itero la List post-type.
	foreach ( $posts as $key => $value ) {
			$id = $value->{'ID'}; // guardo su id
			$title = $value->{'post_title'}; // guardo su title.
			$stream_id = get_post_meta( $id, 'twchr-from-api_id', true ); // guardo el custom-field steram_id.

			// Guardo los datos anteriores en un array.
			$post_for_api = array(
				'wordpress_id' => $id,
				'title' => $title,
				'twchr_id' => (int) $stream_id, // Convierto stream_id a numero entero.

			);

			// guardo $post_for_api en array_response.
			array_push( $array_response, $post_for_api );
	}
	// retorno array_response.
	return $array_response;

} //twchr_endpoint_cpt_register_callback_streaming fin.

// Fin cpt streamings.


/**
 * Hago visibles las categorias de twich en la API REST de wordpress
 *
 * @return void
 */
function twchr_cat_twcht_endpoint() {
	register_rest_route(
		'twchr/v1',
		'twchr_get_cat_twcht',
		array(
			'methods'  => WP_REST_Server::READABLE,
			'callback' => 'twchr_api_get_cat_twcht',
			'permission_callback' => function() {
				return current_user_can( 'read' );
			},
		)
	);
}

add_action( 'rest_api_init', 'twchr_cat_twcht_endpoint' );
