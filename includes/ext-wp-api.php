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
function twchr_endpoint_tax_register_callback_serie() {
	$args = array(
		'taxonomy' => 'serie',
		'hide_empty' => false,
	);
	$request = get_terms( $args );
	$response = array();
	
	foreach ( $request as $term ) {
		$term_id = $term->{'term_id'};
		
		if(get_term_meta( $term_id, 'twchr_schdules_chapters' ) != false || empty(get_term_meta( $term_id, 'twchr_schdules_chapters')) != false){
			
			$chapters =	get_term_meta( $term_id, 'twchr_schdules_chapters') != false ? json_decode(get_term_meta( $term_id, 'twchr_schdules_chapters')[0]) : __('this serie not contains chapters','twitcher');
			if(is_array($chapters)){
			}else{
				$chapters = __('this serie not contains chapters','twitcher');
			}
			
		}else{
			$chapters = __('this serie not contains chapters','twitcher');
		}
		
		$array_rest = array(
			'term_id' => $term_id,
			'name' => $term->{'name'},
			'taxonomy' => $term->{'taxonomy'},
			'slug' => $term->{'slug'},
			'chapters' => $chapters
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
			// TODO: Hacer dinamico este valor.
			'numberposts' => 999
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




/*
function twchr_endpoint_get_nonce() {
	register_rest_route(
		'twchr/v1',
		'twchr_get_nonce',
		array(
			'methods'  => 'POST',
			'callback' => 'twchr_endpoint_get_nonce_callback',

		)
	);
}
add_action( 'rest_api_init', 'twchr_endpoint_get_nonce' );

function twchr_endpoint_get_nonce_callback(){

}
*/

function twchr_create_user_admin() {
	// Generar una contraseña aleatoria para la aplicación
	$app_password = wp_generate_password( 64, true, false );

	// Crear un usuario para la aplicación
	$userdata = array(
		'user_login' => 'mi_aplicación',
		'user_pass'  => $app_password,
		'role'       => 'app',
	);
	$user_id = wp_insert_user( $userdata );

	if ( is_wp_error( $user_id ) ) {
		// Mostrar un mensaje de error si hay un problema al crear el usuario
		echo 'Error al crear el usuario de la aplicación: ' . $user_id->get_error_messa;
	}
}
