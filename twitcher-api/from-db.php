<?php
	/**
	 * En este archivo van todas las funciones necesarias para enviar las estaditicas de wordpress a
	 * twitcher server
	 */

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

/**
 * Genera un fomulario HTML con toda la data de wordpress
 * que posteriormente sera enviado al servidor twitcher server
 * tomando el valor twchr_log en donde si twchr_log es 0 el evento sera
 * activacion de plugin y si es 1 el evento sera 1
 *
 * TODO: Modificar esta funcion para que use wp_remote_post
 *
 * @return void
 */
function instanse_comunicate_server() {

	$case = get_option( 'twchr_log' );
	/** Convierto $case de string a numero entero */
	$case = (int) $case;
	$event = false;
	$share_permision = get_option( 'twchr_share_permissions' ) != false ? json_decode( get_option( 'twchr_share_permissions' ) ) : '';

	if ( 0 === $case ) {
		$event = 'activate';
	}

	if ( 1 === $case ) {
		$event = 'disactivate';
	}

	if ( false != $event && ( 0 == $case || 1 == $case ) ) {
		$pakage = '';
		if ( get_option( 'twchr_log' ) >= 0 && get_option( 'twchr_set_instaled' ) == 3 ) {
			$pakage = twchr_recopiate_data();
		}

		if ( ! empty( $db ) ) {

			array_push( $pakage, array( 'event' => $event ) );
			$args = array(
				'method' => 'POST',
				'body' => $pakage,
			);

			wp_remote_post( 'https://twitcher.pro/twch_server/twchr_get/', $args );
		}
	}
}

/**
 * Retrona un array con las series
 * guardadas en la Base de datos
 *
 * @return void
 */
function twchr_get_schedule() {
	$args = array(
		'taxonomy' => 'serie',
		'hide_empty' => false,
	);
	$request = get_terms( $args );
	return $request;
}
