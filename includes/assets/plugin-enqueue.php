<?php
/**
 *  En este archivo declaro todas las funciones que encolan archivos css,js,ttf, etc.
 **/


/**
 * Llamo  a todas las variates de fuente comfortaa que seran utilizadas en el plugin.
 *
 * @return void
 */
function twchr_fonts() {
	?>
	<style>
		@font-face {
			font-family: 'Comfortaa';
			src: url(<?php echo esc_url( TWCHR_URL_FONTS . '/Comfortaa-Regular.ttf' ); ?>);
			font-weight: normal;
		}
		@font-face {
			font-family: 'Comfortaa';
			src: url(<?php echo esc_url( TWCHR_URL_FONTS . '/Comfortaa-Bold.ttf' ); ?>);
			font-weight: bold;
		}
		@font-face {
			font-family: 'Comfortaa';
			src: url(<?php echo esc_url( TWCHR_URL_FONTS . '/Comfortaa-Light.ttf' ); ?>);
			font-weight: light;
		}
	</style>
	<?php
}

add_action( 'wp_after_admin_bar_render', 'twchr_fonts' );


/**
 * Encolo los archivos JavaScript y CSS que necesitara el plugin para funcionar.
 *
 * @return void
 */
function twchr_admin_js() {
	$version = 'beta.4.17';

	// Estilos.
	wp_enqueue_style( 'admin-styles', TWCHR_URL . 'includes/css/admin.css', array(), $version, 'all' );

	// Scripts.
	wp_enqueue_script( 'twchr_gscjs', TWCHR_URL . 'includes/js/gscjs.js', array(), $version, false );

	$set_instaled = get_option( 'twchr_set_instaled' );
	// Usamos esta función para que coloque los valores inline
	if ( $set_instaled >= 3 ) {
		// Definimos las variables WordPress a enviar dentro de un array.
		$params = array(
			'twchr_keys' => json_decode( get_option( 'twchr_keys' ) ),
			'twchr_app_token' => get_option( 'twchr_app_token' ),
			'twitcher_data_broadcaster' => json_decode( get_option( 'twchr_data_broadcaster' ) )->{'data'}[0],
			'twitcher_data_clear_all' => get_option( 'twchr_delete_all' ),
			'site_url' => site_url(),
			'wp_api_route' => rest_url(),
		);
		wp_register_script( 'twchr_custom_script', TWCHR_URL . 'includes/js/admin.js', array(), $version, true );
		wp_enqueue_script( 'twchr_custom_script' );
		wp_localize_script( 'twchr_custom_script', 'tchr_vars_admin', $params );
	}

}

add_action( 'admin_enqueue_scripts', 'twchr_admin_js' );

function twchr_front_scripts() {
	wp_enqueue_script( 'twchr_clipboard-cdn', 'https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js', array(), $version, false );
}

add_action( 'wp_enqueue_scripts', 'twchr_front_scripts' );
