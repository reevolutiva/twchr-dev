<?php

/**Shortcode para mostrar el estado actual de la transmisión **/

function twitch_status_shortcode() {
	// Tu clave de cliente y secreto de desarrollador de Twitch.
	$client_id = 'TU_CLAVE_DE_CLIENTE';
	$client_secret = 'TU_SECRETO_DE_DESARROLLADOR';

	// Tu nombre de usuario de Twitch.
	$username = 'TU_NOMBRE_DE_USUARIO';

	// URL de la API de Twitch para obtener el estado de tu transmisión.
	$api_url = "https://api.twitch.tv/helix/streams?user_login=$username";

	// Agrega tu clave de cliente como cabecera de autorización.
	$headers = array(
		'Client-ID' => $client_id,
	);

	// Realiza la solicitud GET a la API.
	$response = wp_remote_get( $api_url, array( 'headers' => $headers ) );

	// Procesa la respuesta de la API.
	$body = json_decode( wp_remote_retrieve_body( $response ) );
	$status = $body->data[0]->type;

	// Muestra el estado de tu transmisión.
	if ( 'live' == $status ) {
		return 'Estoy transmitiendo en vivo ahora mismo';
	} else {
		return 'No estoy transmitiendo en vivo en este momento';
	}
}
add_shortcode( 'twitch_status', 'twitch_status_shortcode' );


