<?php
/**
 * En este archivo declaro las funciones que imprimiran en pantalla 
 * las twitcher alert
 * 
 * 1- Alerta para cuando aun no congiguras el plugin
 *		twchr_alert_setup
 * 2- Alerta para cuando no estas con protocolo SSL
 *		twchr_alert_ssl
 * 3- Alerta para cuando aun no has importado o creado ningun streaming
 *		twchr_alert_import
**/

/**
 * Cuenta la cantidad de custom-post-type twchr_streams
 * Si esa cantidad es 0 se muestra esta alera
 **/
function twchr_alert_import() {

	// Cuenta cuantos streamings han sido creados.

	$num_streamigs = COUNT(
		get_posts(
			array(
				'post_type'  => 'twchr_streams',
				'post_status' => 'publish',
			)
		)
	);

	if ( isset( $_POST['twchr-alert__anchor__null_videos_close'] ) && POST['twchr-alert__anchor__null_videos_close'] == true ) {
		update_option( 'twchr_set_instaled', 4, '' );
		twchr_javaScript_redirect( TWCHR_ADMIN_URL . 'edit.php?post_type=twchr_streams' );
	}

	// Si el numero de streamings creados es de 0.
	if ( $num_streamigs == 0 && get_option( 'twchr_set_instaled' ) == 3 && twchr_is_ssl_secure() && ($_GET['post_type'] == 'twchr_streams') || str_contains( $_SERVER['REQUEST_URI'], 'plugins.php' )) {
		?>
		<section class="twchr-alert">
			<h3 class="twchr-alert__title"><?php _e( 'It seems you havn’t imported or created any video already. ', 'twitcher' ); ?></h3>
			<div class="twchr-alert__row">
				<a class="twchr-alert__anchor twchr-btn-general" target="_blank" href="https://twitcher.pro/twitcher-first-steps-manage-twitch-account-from-wordpress-easy-api-integration/"><?php _e( 'Import or create streaming', 'twitcher' ); ?></a>
				<img class="twchr-alert__anchor__null_videos_close"  src="<?php echo TWCHR_URL_ASSETS; ?>close.png" alt="">
			</div>
		</section>
		<script>
			document.querySelector("section.twchr-alert .twchr-alert__row a").addEventListener('click', () => {
				location.href = tchr_vars_admin.site_url + "/wp-admin/edit.php?post_type=twchr_streams";
			});

			document.querySelector(".twchr-alert__anchor__null_videos_close").addEventListener('click', () => {
				document.querySelector(".twchr-alert").classList.add("alert-fade-out");
				setTimeout(()=>{
					location.href = location.href+"&twchr-alert__anchor__null_videos_close=true";
				},1000);
			});
		</script>
		<?php
	}
}

add_action( 'all_admin_notices', 'twchr_alert_import' );

/**
 * Pregunta si la url esta usando el protocolo https
 * Si no lo esta usando muestra la siguiente alerta
 **/
function twchr_alert_ssl() {
	// Si este wordpress no esta usando el protocolo SSL
	if ( ! twchr_is_ssl_secure() ) {
		?>
		<section class="twchr-alert alert-ssl">
			<img src="<?php echo TWCHR_URL_ASSETS; ?>warning.png" alt="">
			<h3 class="twchr-alert__title"><?php _e( 'Twitch.tv requires SSL https:// secure sites. ', 'twitcher' ); ?></h3>
			<div class="twchr-alert__row">
				<a class="twchr-alert__anchor twchr-btn-general ssl" target="_blank" href="<?php echo str_replace( site_url(), 'http', 'https' ); ?>"><?php _e( 'Force SSL', 'twitcher' ); ?></a>
				<a class="twchr-alert__anchor twchr-btn-general" target="_blank" href="https://dev.twitch.tv/docs/embed"><?php _e( 'Read More', 'twitcher' ); ?></a>
				<img src="<?php echo TWCHR_URL_ASSETS; ?>close.png" alt="">
			</div>
		</section>
		<script>
			document.querySelector(".twchr-alert.alert-ssl a.twchr-alert__anchor.ssl").addEventListener('click', (e)=>{
				e.preventDefault();
				const opt = confirm("<?php _e( 'If you do not have an ssl certificate installed on your domain this action may give you an error. Are you sure to continue?', 'twitcher' ); ?>");
				if(opt){
					alert("<?php _e( 'You will be redirected to this same website but with ssl protocol', 'twitcher' ); ?>");
					location.href = location.href.replace("http","https");
				}
			});
		</script>
		<?php
	}
}

add_action( 'all_admin_notices', 'twchr_alert_ssl' );

/**
 * Pregunta si estamos la url contiene alguno de los siguientes strings
 *  - post_type=twchr_streams
 *  - plugins.php
 *
 *  Si los contiene y setInstaled es igual o menos a 1 y aun no se ha completado
 * la instalacion no se ha realizadio es decir twchr_set_instaled
 **/
function twchr_alert_setup() {
	$data_url1 = str_contains( $_SERVER['REQUEST_URI'], 'post_type=twchr_streams' );
	$data_url2 = str_contains( $_SERVER['REQUEST_URI'], 'plugins.php' );

	if ( ! ( isset( $_GET['page'] ) && $_GET['page'] === 'twchr_help' && isset( $_GET['setUpPage'] ) && $_GET['setUpPage'] == true ) ) :
		if ( $data_url1 || $data_url2 ) :
			if ( get_option( 'twchr_set_instaled' ) <= 1 || get_option( 'twchr_set_instaled' ) == false ) :
				?>
					<section class="twchr-alert">
						<img src="<?php echo TWCHR_URL_ASSETS; ?>/warning.png" alt="">
						<h3 class="twchr-alert__title"><?php _e( 'Twitcher Manage Twitch Acount needs connection with Twitch. ', 'twitcher' ); ?></h3>
						<a class="twchr-alert__anchor twchr-btn-general" href="<?php echo TWCHR_ADMIN_URL . 'edit.php?post_type=twchr_streams&page=twchr-dashboard'; ?>"><?php _e( 'Setup', 'twitcher' ); ?></a>
					</section>
				<?php
				endif;
			endif;
	endif;

}

/**
 * Funcion que retorna true si la pagina actual esta corriendo con el protocolo SSL
 *
 * @return void
 */ 
function twchr_is_ssl_secure() {
	$res = $_SERVER['HTTPS'] == 'on';
	return $res;
}

add_action( 'all_admin_notices', 'twchr_alert_setup' );
