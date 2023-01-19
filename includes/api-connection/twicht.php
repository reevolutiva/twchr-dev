<?php
// twtchr_twitch_schedule.
// Actualiza los schedules segment.
function twtchr_twitch_schedule_segment_update( $post_id, $twchr_titulo, $stream_id, $twchr_category, $twchr_duration, $start_time ) {
	// Credentials.
	$twch_data_prime = get_option( 'twchr_keys' ) == false ? false : json_decode( get_option( 'twchr_keys' ) );
	$client_id = $twch_data_prime->{'client-id'};
	$user_token = $twch_data_prime->{'user_token'};
	$data_broadcaster_raw = get_option( 'twchr_data_broadcaster', false ) == false ? false : json_decode( get_option( 'twchr_data_broadcaster' ) );
	$broadcaster_id = $data_broadcaster_raw->{'data'}[0]->{'id'};

	$body = array(
		'start_time' => $start_time,
		'duration' => $twchr_duration,
		'category_id' => $twchr_category,
		'title' => $twchr_titulo,
	);

	$args = array(
		'headers' => array(
			'authorization' => 'Bearer ' . $user_token,
			'client-id' => $client_id,
			'Content-Type' => 'application/json',
		),
		'body' => json_encode($body),
		'method' => 'PATCH'
	);


	$data_broadcaster_raw = get_option( 'twchr_data_broadcaster', false ) == false ? false : json_decode( get_option( 'twchr_data_broadcaster' ) );
	$broadcaster_id = $data_broadcaster_raw->{'data'}[0]->{'id'};

	$url = 'https://api.twitch.tv/helix/schedule/segment/?broadcaster_id=' . $broadcaster_id.'&id='.$stream_id;

	$res = wp_remote_request( $url, $args );
	$response_body = json_decode( wp_remote_retrieve_body( $res ) );
	$response_response = $res['response'];

	
	//var_dump($response_body);

	// codigo para accionar segun la respuesta de la api.
	switch ( $response_response['code'] ) {
		case 200:
			$all_data = $response_body->{'data'};
			return array(
				'allData' => $all_data,
				'status' => 200,
				'message' => __( 'Successfully updated serie.', 'twitcher' ),
			);

		break;

		case 401:
			return array(
				'message' => __( 'USER TOKEN is invalid, wait a moment, in a few moments you will be redirected to a place where you can get an updated USER TOKEN', 'twitcher' ),
				'status' => 401,
				'url_redirect' => 'https://' . TWCHR_HOME_URL . '/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true',
				'post-id' => $post_id,
			);

		break;

		case 400:
			$glosa = str_replace( '"', '`', $response_body->{'message'} );
			return array(
				'error' => $response_body->{'error'},
				'status' => $response_body->{'status'},
				'message' => $glosa,
				'title' => $twchr_titulo,
			);

		break;
		default:
			break;
	}
}
// twtchr_twitch_schedule.
// eliminar los schedules segment.
/**
 * Eliminar un schedule segment
 *
 * @param [type]  $schedule_id
 * @param boolean $twchr_titulo
 * @param boolean $post_id
 * @return void
 */
function twtchr_twitch_schedule_segment_delete( $schedule_id, $twchr_titulo = false, $post_id = false ) {

	// Credentials.
	$twch_data_prime = get_option( 'twchr_keys' ) == false ? false : json_decode( get_option( 'twchr_keys' ) );
	$client_id = $twch_data_prime->{'client-id'};
	$user_token = $twch_data_prime->{'user_token'};
	$data_broadcaster_raw = get_option( 'twchr_data_broadcaster', false ) == false ? false : json_decode( get_option( 'twchr_data_broadcaster' ) );
	$broadcaster_id = $data_broadcaster_raw->{'data'}[0]->{'id'};

	$args = array(
		'headers' => array(
			'authorization' => 'Bearer ' . $user_token,
			'client-id' => $client_id,
			'Content-Type' => 'application/json',
		),
		'method' => 'DELETE',
	);

	$url = 'https://api.twitch.tv/helix/schedule/segment?broadcaster_id=' . $broadcaster_id . '&id=' . $schedule_id;

	$res = wp_remote_post( $url, $args );
	$response_body = json_decode( wp_remote_retrieve_body( $res ) );
	$response_response = $res['response'];

	if ( $post_id != false && $twchr_titulo != false ) {
		// codigo para accionar segun la respuesta de la api.
		switch ( $response_response['code'] ) {
			case 204:
				$all_data = $response_body->{'data'};
				return array(
					'allData' => $all_data,
					'status' => 204,
					'message' => __( 'Successfully updated serie.', 'twitcher' ),
				);

			break;

			case 401:
				return array(
					'message' => __( 'USER TOKEN is invalid, wait a moment, in a few moments you will be redirected to a place where you can get an updated USER TOKEN', 'twitcher' ),
					'status' => 401,
					'url_redirect' => 'https://' . TWCHR_HOME_URL . '/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true',
					'post-id' => $post_id,
				);

			break;

			case 400:
				$glosa = str_replace( '"', '`', $response_body->{'message'} );
				return array(
					'error' => $response_body->{'error'},
					'status' => $response_body->{'status'},
					'message' => $glosa,
					'title' => $twchr_titulo,
				);

			break;
			default:
				break;
		}
	} else {
		return $response_body;
	}
}

/**
 * Obetiene un schedule segment por su id
 * y si no pasamos id los tre todos
 *
 * @param boolean $schedule_id.
 * @return void
 */
function twtchr_twitch_schedule_segment_get( $schedule_id = null ) {
	$twch_data_prime = get_option( 'twchr_keys' ) == false ? false : json_decode( get_option( 'twchr_keys' ) );
	$client_id = $twch_data_prime->{'client-id'};
	$user_token = $twch_data_prime->{'user_token'};

	//var_dump($twch_data_prime);

	$args = array(
		'headers' => array(
			'authorization' => 'Bearer ' . $user_token,
			'client-id' => $client_id,
		),
	);

	$data_broadcaster_raw = get_option( 'twchr_data_broadcaster', false ) == false ? false : json_decode( get_option( 'twchr_data_broadcaster' ) );
	$broadcaster_id = $data_broadcaster_raw->{'data'}[0]->{'id'};

	if ( $schedule_id == null ) {
		$url = 'https://api.twitch.tv/helix/schedule?broadcaster_id=' . $broadcaster_id;
	} else {
		$url = 'https://api.twitch.tv/helix/schedule?broadcaster_id=' . $broadcaster_id . '&id=' . $schedule_id;
	}

	$res = wp_remote_get( $url, $args );
	$response_body = json_decode( wp_remote_retrieve_body( $res ) );



	if ( isset( $response_body->{'data'}->{'segments'} ) ) {
		$data = $response_body->{'data'}->{'segments'};
		return $data;
	} else {

		if ( isset( $response_body->{'error'} ) ) {
			if($response_body->{'message'} == 'segments were not found'){
				return $response_body->{'message'};
			}else{
				return $response_body;
				//twchr_twitch_autentication_error_handdler( $response_body->{'error'}, $response_body->{'message'} );
			}
		}
	}
}
/**
 * Crea un schedule segment
 *
 * @param [type]  $post_id
 * @param [type]  $twchr_titulo
 * @param [type]  $twchr_start_time
 * @param [type]  $twchr_category
 * @param [type]  $twchr_duration
 * @param boolean $is_recurring
 * @return void
 */
function twtchr_twitch_schedule_segment_create( $post_id, $twchr_titulo, $twchr_start_time, $twchr_category, $twchr_duration, $is_recurring = true ) {

	// GET CREDENTIALS.
	$twch_data_prime = json_decode( get_option( 'twchr_keys', false ) );
	$token_validate = $twch_data_prime->{'user_token'};
	$client_id = $twch_data_prime->{'client-id'};

	$data_broadcaster_raw = get_option( 'twchr_data_broadcaster', false ) == false ? false : json_decode( get_option( 'twchr_data_broadcaster' ) );
	$broadcaster_id = $data_broadcaster_raw->{'data'}[0]->{'id'};

	$body = array(
		'start_time' => $twchr_start_time,
		'title' => $twchr_titulo,
		'timezone' => 'America/New_York',
		'is_recurring' => $is_recurring,
		'duration' => $twchr_duration,
		'category_id' => $twchr_category,
	);

	$args = array(
		'headers' => array(
			'authorization' => 'Bearer ' . $token_validate,
			'client-id' => $client_id,
		),
		'body' => $body,
	);

	$url = 'https://api.twitch.tv/helix/schedule/segment?broadcaster_id=' . $broadcaster_id;

	$res = wp_remote_post( $url, $args );
	$response_body = json_decode( wp_remote_retrieve_body( $res ) );
	$response_response = $res['response'];

	// codigo para accionar segun la respuesta de la api.
	switch ( $response_response['code'] ) {
		case 200:
			$all_data = $response_body->{'data'};

			return array(
				'allData' => $all_data,
				'status' => 200,
				'message' => __( 'successfully created series', 'twitcher' ),
			);

		break;

		case 401:
			return array(
				'message' => __( 'USER TOKEN is invalid, wait a moment, in a few moments you will be redirected to a place where you can get an updated USER TOKEN', 'twitcher' ),
				'status' => 401,
				'url_redirect' => TWCHR_HOME_URL . '/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true',
				'post-id' => $post_id,
			);

		break;

		case 400:
			$glosa = str_replace( '"', '`', $response_body->{'message'} );
			return array(
				'error' => $response_body->{'error'},
				'status' => $response_body->{'status'},
				'message' => $glosa,
				'title' => $twchr_titulo,
			);

		break;
		default:
			return $response_body;
		break;
	}

}


/**
 * Obtiene un array de videos
 *
 * @param [type] $app_token
 * @param [type] $client_id
 * @param [type] $user_id
 * @return void
 */
function twchr_twitch_video_get() {

	// GET CREDENTIALS.
	$twch_data_prime = json_decode( get_option( 'twchr_keys', false ) );
	$app_token = get_option( 'twchr_app_token' );
	$client_id = $twch_data_prime->{'client-id'};

	$data_broadcaster_raw = get_option( 'twchr_data_broadcaster', false ) == false ? false : json_decode( get_option( 'twchr_data_broadcaster' ) );
	$broadcaster_id = $data_broadcaster_raw->{'data'}[0]->{'id'};

	$args = array(
		'headers' => array(
			'Authorization' => "Bearer $app_token",
			'client-id' => $client_id,
		),
	);

	$url = "https://api.twitch.tv/helix/videos?user_id=$broadcaster_id";

	$data = wp_remote_get( $url, $args );

	$response = json_decode( wp_remote_retrieve_body( $data ) );

	return $response;
}

/**
 * Pregunta a Twitcht api por un video ID especifico si ese video existe retonra 200 y si no existe 404
 *
 * @param [type] $video_id
 * @param [type] $token
 * @param [type] $client_id
 * @return void
 */
function twchr_twitch_video_exist( $video_id, $token, $client_id ) {
	if ( isset( $video_id ) && isset( $token ) && isset( $client_id ) ) {
		$url = 'https://api.twitch.tv/helix/videos?id=' . $video_id;
		$args = array(
			'headers' => array(
				'Authorization' => "Bearer $token",
				'client-id' => $client_id,
			),
		);
		$get = wp_remote_get( $url, $args );
		$response = wp_remote_retrieve_body( $get );
		$response = json_decode( $response );

		if ( $response->data ) {
			return 200;
		} else {
			return 404;
		}
	}
}

/**
 * Trae una lista de subcriptors
 * twtchr_twitch_subscribers
 * twtchr_twitch_subscribers_get
 *
 * @param [type] $user_token
 * @param [type] $client_id
 * @return void
 */
function twtchr_twitch_subscribers_get( $user_token, $client_id ) {
	$args = array(
		'headers' => array(
			'Authorization' => 'Bearer ' . $user_token,
			'client-id' => $client_id,
		),
	);
	$data_broadcaster_raw = get_option( 'twchr_data_broadcaster', false ) == false ? false : json_decode( get_option( 'twchr_data_broadcaster' ) );
	$broadcaster_id = $data_broadcaster_raw->{'data'}[0]->{'id'};

	$get = wp_remote_get( 'https://api.twitch.tv/helix/subscriptions?broadcaster_id=' . $broadcaster_id, $args );

	$response = wp_remote_retrieve_body( $get );

	return json_decode( $response );
}

/**
 * Trae una lista de categorias
 *
 * @param [type] $app_token
 * @param [type] $client_id
 * @param [type] $query
 * @return void
 */
function twtchr_twitch_categories_get( $app_token, $client_id, $query ) {
	$url = "https://api.twitch.tv/helix/search/categories?query=$query";
	$args = array(
		'headers' => array(
			'Authorization' => "Bearer $app_token",
			'client-id' => $client_id,
		),
	);

	$data = wp_remote_get( $url, $args );

	$response = json_decode( wp_remote_retrieve_body( $data ) );

	return $response;
}

/**
 * Trae unaa lista de los seguidores de un broadcaster
 *
 * @param [type] $app_token
 * @param [type] $client_id
 * @param [type] $user_id
 * @return void
 */
function twtchr_twitch_users_get_followers() {
	// Credentials.
	$data_broadcaster_raw = get_option( 'twchr_data_broadcaster', false ) == false ? false : json_decode( get_option( 'twchr_data_broadcaster' ) );
	$twch_data_prime = get_option( 'twchr_keys' ) == false ? false : json_decode( get_option( 'twchr_keys' ) );
	$twch_data_app_token = get_option( 'twchr_app_token' );

	
	$app_token = $twch_data_app_token; 
	$client_id = $twch_data_prime == false ? false : $twch_data_prime->{'client-id'};
	$user_id = $data_broadcaster_raw == false ? false : $data_broadcaster_raw->{'data'}[0]->{'id'};

	$url = 'https://api.twitch.tv/helix/users/follows?to_id=' . $user_id.'&first=100';

	$args = array(
		'headers' => array(
			'Authorization' => "Bearer $app_token",
			'client-id' => $client_id,
		),
	);

	$get = wp_remote_get( $url, $args );
	$response = wp_remote_retrieve_body( $get );
	$object = json_decode( $response );

	if(isset($object->{'c'})){

	}

	

	return $object;

}
/**
 * Trae una list de los moderadores
 *
 * @param [type] $app_token
 * @param [type] $client_id
 * @param [type] $user_id
 * @return void
 */
function twtchr_twitch_moderators_get( $app_token, $client_id, $user_id ) {
	$url = 'https://api.twitch.tv/helix/moderation/moderators?broadcaster_id=' . $user_id;
	$args = array(
		'headers' => array(
			'Authorization' => "Bearer $app_token",
			'client-id' => $client_id,
		),
	);

	$get = wp_remote_get( $url, $args );

	$response = wp_remote_retrieve_body( $get );
	var_dump( $response );

	$object = json_decode( $response );

	return $object;

}

/**
 * Trae una lista de los clips de un broadcaster
 *
 * @param [type] $app_token
 * @param [type] $client_id
 * @param [type] $user_id
 * @return void
 */
function twtchr_twitch_clips_get( $app_token, $client_id, $user_id ) {
	$url = 'https://api.twitch.tv/helix/clips?broadcaster_id=' . $user_id;

	$args = array(
		'headers' => array(
			'Authorization' => "Bearer $app_token",
			'client-id' => $client_id,
		),
	);

	$get = wp_remote_get( $url, $args );
	$response = wp_remote_retrieve_body( $get );
	$object = json_decode( $response );

	return $object;

}


// Funciones de autenticación!
/**
 * Obtener app token
 * twtchr_twitch_autenticate_apptoken_get
 *
 * @param [type] $client_id
 * @param [type] $client_secret
 * @return void
 */
function twtchr_twitch_autenticate_apptoken_get( $client_id, $client_secret ) {
	$url = 'https://id.twitch.tv/oauth2/token?client_id=' . $client_id . '&client_secret=' . $client_secret . '&grant_type=client_credentials';
	$data = wp_remote_post( $url );
	$response = json_decode( wp_remote_retrieve_body( $data ) );
	return $response;
}

/**
 * validar user token
 * FIXME twtchr_twitch_autenticate_usertoken_validate
 *
 * @param [type] $client_id
 * @param [type] $client_secret
 * @param [type] $code
 * @param [type] $redirect
 * @return void
 */
function twchr_validate_token( $client_id, $client_secret, $code, $redirect ) {
	$url = 'https://id.twitch.tv/oauth2/token';
	$urlecode = 'client_id=' . $client_id . '&client_secret=' . $client_secret . '&code=' . $code . '&grant_type=authorization_code&redirect_uri=' . $redirect;

	$args = array(
		'body' => $urlecode,
	);

	$res = wp_remote_post( $url, $args );

	return $res;
}
/**
 * Obtener user token y broascaster data
 *
 * @param [type] $api_key
 * @param [type] $client_id
 * @param [type] $redirect
 * @param [type] $scope
 * @return void
 */
function twtchr_twitch_autenticate( $api_key, $client_id, $redirect, $scope ) {
	$twch_data_prime = get_option( 'twchr_keys' ) == false ? false : json_decode( get_option( 'twchr_keys' ) );
	$token = isset( $twch_data_prime->{'user_token'} ) ? $twch_data_prime->{'user_token'} : false;
	$token_validate = '';
	$token_status = '';
	$twch_data_app_token = '';

	if ( $token != false ) {
		$token_validate = twchr_twitch_token_validate( $token );
		$token_status = isset( $token_validate->{'status'} ) ? false : true;
		$twch_data_app_token = get_option( 'twchr_app_token' );
	} else {
		$token_status = false;
	}

	// IF endpoint validate
	if ( $token_status ) {
		$user_login = $token_validate->{'login'};

		$args = array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $twch_data_app_token,
				'client-id' => $twch_data_prime->{'client-id'},
			),
		);

		$url = 'https://api.twitch.tv/helix/users?login=' . $user_login;
		$response = wp_remote_get( $url, $args );
		$body = wp_remote_retrieve_body( $response );

		if ( get_option( 'twchr_data_broadcaster' ) == false ) {
			add_option( 'twchr_data_broadcaster', $body, '', true );
		} else {
			update_option( 'twchr_data_broadcaster', $body, true );
		}

		add_option( 'twchr_log', 0 );

		$url_redirection = TWCHR_HOME_URL . '/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard';
		echo "<script>location.href='$url_redirection'</script>";

	} else {

		$twitchtv = new TwitchTV( $api_key, $client_id, urlencode( $redirect ), $scope );
		$auth_url = $twitchtv->authenticate();

		$msg = '<h3>Usted sera redirigido a Twcht en unos segundos</h3>';
		$script = '<script>location.href ="' . $auth_url . '";</script>';

		echo esc_html( $msg );
		echo $script;

	}
}

/**
 * Validar code que responde twtich
 *
 * @param [type] $token
 * @return void
 */
function twchr_twitch_token_validate( $token ) {
	$url = 'https://id.twitch.tv/oauth2/validate';
	$args = array(
		'headers' => array(
			'Authorization' => 'Bearer ' . $token,
		),
	);

	$response = wp_remote_get( $url, $args );
	$body = wp_remote_retrieve_body( $response );
	return json_decode( $body );
}

function twchr_twitch_autentication_error_handdler( $error_code, $msg ) {
	if ( $error_code = 'Not Found' && $msg == 'segments were not found' ) {
		echo "<script>
    	alert('".$msg."'); 
		</script>"; 
	}else{
		echo "<script>
		alert('Error: " . $error_code . "'); 
		alert('message: " . $msg . "');
		alert('" . __( 'You will be redirected to the authentication page in a few seconds.', 'twitcher' ) . "');
		location.href = '" . TWCHR_ADMIN_URL . "edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true';
	</script>";
	}
	
}

