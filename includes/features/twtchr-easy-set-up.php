<?php

/**
 *  Funcion para redirección a pagina de instalacion
 * TODO:Mover a la característica correspondiente (¿features/twchr_setup.php?)
 *
 * @return void
 */
function twchr_redirect_setUp() {
	// Si la url contiene 'plugins' retorna true.
	$data_url_1 = str_contains( $_SERVER['REQUEST_URI'], 'post_type=twchr_streams&page=twchr-dashboard' );

	// ¿Eciste dato en wp_options twchr_set_instaled?.
	$set_instaled = get_option( 'twchr_set_instaled' );

	// echo "dentro de la funcion redirect_setUp";
	if ( $set_instaled <= 1 || $set_instaled == false ) {
		if ( $data_url_1 ) {
			$url = TWCHR_ADMIN_URL . 'edit.php?post_type=twchr_streams&page=twchr_help&setUpPage=true';
			echo "<script>location.href='$url'</script>";
			// exit;

		}
	}
}

add_action( 'shutdown', 'twchr_redirect_setUp' );
