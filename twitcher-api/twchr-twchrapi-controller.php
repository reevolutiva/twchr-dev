<?php
/**
 * Aqui ejecuto la comunicacion con el servidor
 **/

/**
 * Enviar las estadisticas de wordpress
 * en el caso en que  la url contenga los siguientes strings
 *  - post_type=twchr_streams
 *  - post_type=twchr_streams
 *
 * @return void
 */
function twchr_form_plugin_footer() {
	$data_url1 = str_contains( $_SERVER['REQUEST_URI'], 'post_type=twchr_streams' );
	$data_url2 = str_contains( $_SERVER['REQUEST_URI'], 'plugins.php' );
	if ( get_option( 'twchr_set_instaled' ) >= 3 && ( $data_url1 || $data_url2 ) ) {
		instanse_comunicate_server();
	}
}

add_action( 'shutdown', 'twchr_form_plugin_footer' );
