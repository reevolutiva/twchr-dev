<?php
	/**
	 *   Esta funcion debe ejecturase cuando se el plugin se activa y cuando se desactriva
	 *   ** DATA A RECOPILAR **
	 *   - url
	 *   - WORDPRESS Version
	 *   - PHP Version
	 *   - Plugins Instalados
	 *   - Cantidad de Usuarios
	 *   - Tema
	 **/
function twchr_recopiate_data() {

	// Keys.
	$data_broadcaster = get_option( 'twchr_data_broadcaster', false ) == false ? false : json_decode( get_option( 'twchr_data_broadcaster' ) );

	$broadcaster_id = $data_broadcaster->{'data'}[0]->{'id'};
	$twch_data_app_token = get_option( 'twchr_app_token' );
	$twch_data_prime = get_option( 'twchr_keys' ) == false ? false : json_decode( get_option( 'twchr_keys' ) );
	$user_token = $twch_data_prime->{'user_token'};
	$client_id = $twch_data_prime->{'client-id'};
	$subcriptores = twtchr_twitch_subscribers_get( $user_token, $client_id )->{'total'};
	$followers = twtchr_twitch_users_get_followers( $user_token, $client_id, $broadcaster_id )->{'total'};

	// videos.
	$list_videos = twchr_twitch_video_get( $twch_data_app_token, $twch_data_prime->{'client-id'}, $broadcaster_id )->{'data'};
	$videos = COUNT( $list_videos );

	$vistas = $data_broadcaster->{'data'}[0]->{'view_count'};

	$schedules = COUNT( twtchr_twitch_schedule_segment_get() );

	$clips = twtchr_twitch_clips_get( $twch_data_app_token, $client_id, $broadcaster_id )->{'data'};
	if ( ! is_wp_error( $clips ) ) {
		$clips = COUNT( $clips );
	}

	$user_data = get_option( 'twchr_data_broadcaster', false ) == false ? false : json_decode( get_option( 'twchr_data_broadcaster' ) );
	$user_login = $user_data->{'data'}[0]->{'login'};
	$list_old = get_plugins();
	$list_new = array();
	foreach ( $list_old as $item ) {
		array_push( $list_new, $item['Name'] );
	}

	$url = site_url();
	$wordpress_version = get_bloginfo( 'version' );
	$php_version = PHP_VERSION;

	$user_quantity = COUNT( get_users() );
	$template = get_option( 'template' );

	$pakage = array(
		'url' => $url,
		'wordpressversion' => $wordpress_version,
		'php_version' => $php_version,
		'plugins' => $list_new,
		'users_quantity' => $user_quantity,
		'template' => $template,
		'user_email' => wp_get_current_user()->{'user_email'},
		'share_options' => get_option( 'twchr_share_permissions' ),
		'subcriptores' => $subcriptores,
		'followers' => $followers,
		'videos' => $videos,
		'vistas' => $vistas,
		'schedules' => $schedules,
		'clips' => $clips,
		'user_login' => $user_login,
	);

	return $pakage;
}

function instanse_comunicate_server() {

	$case = get_option( 'twchr_log' );
	/** Convierto $case de string a numero entero */
	$case = (int) $case;
	$event = false;

	switch ( $case ) {
		case 0:
			$event = 'activate';

			break;
		case 1:
			$event = 'disactivate';

			break;

		default:
			break;
	}
	if ( $event != false && ( $case == 0 || $case == 1 ) ) :
		?>
	<form action="https://twitcher.pro/twch_server/twchr_get/" method="post" id="twchr-form-to-server">
		<?php
			$share_permision = get_option( 'twchr_share_permissions' ) != false ? json_decode( get_option( 'twchr_share_permissions' ) ) : '';
			$db = '';
		if ( get_option( 'twchr_log' ) >= 0 && get_option( 'twchr_setInstaled' ) == 3 ) {
			$db = twchr_recopiate_data();
		}

		if ( ! empty( $db ) ) :

			foreach ( $db as $key => $value ) {
				if ( is_array( $value ) || str_contains( $value, '{' ) || str_contains( $value, '}' ) ) {
					$json = json_encode( $value );
					$json = str_replace( '"', '\'', $json );
				} else {
					$json = $value;
				}
				?>
					<input type="hidden" name="to-twitcher-server-<?php echo esc_html( $key ); ?>" value ="<?php echo esc_html( $json ); ?>">
				<?
			}
			?>
			<input type="hidden" name="to-twitcher-server-event" value="<?php echo esc_html( $event ); ?>">
		<?php endif; ?>
		</form>
		<?php

	endif;
	if ( $case >= 0 ) {
		?>
		<script>
			const twchr_form_to_server = document.querySelector("#twchr-form-to-server");
			<?php
			if ( $case == 0 ) {
				update_option( 'twchr_log', 1 );
				echo 'twchr_form_to_server.submit();';
			}

			?>
			
			
					
		</script>
		<?php
	}

}

function twchr_get_schedule() {
	$args = array(
		'taxonomy' => 'serie',
		'hide_empty' => false,
	);
	$request = get_terms( $args );
	return $request;
}
