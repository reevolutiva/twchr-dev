<?php

// Funcion para redirección a pagina de instalacion
// TODO:Mover a la característica correspondiente (¿features/twchr_setup.php?)
function twchr_redirect_setUp() {
	// Si la url contiene 'plugins' retorna true
	$dataUrl1 = str_contains( $_SERVER['REQUEST_URI'], 'post_type=twchr_streams&page=twchr-dashboard' );
	// show_dump(twittcher_data_exist('twchr_setInstaled'));
	// add_option('twchr_setInstaled',0,'',true );
	// ¿Eciste dato en wp_options twchr_setInstaled?
	$setInstaled = get_option( 'twchr_setInstaled' );
	// show_dump($setInstaled);
	// echo "dentro de la funcion redirect_setUp";
	if ( $setInstaled <= 1 || $setInstaled == false ) {
		if ( $dataUrl1 ) {
			$url = TWCHR_ADMIN_URL . 'edit.php?post_type=twchr_streams&page=twchr_help&setUpPage=true';
			echo "<script>location.href='$url'</script>";
			// exit;

		}
	}
}

add_action( 'shutdown', 'twchr_redirect_setUp' );
