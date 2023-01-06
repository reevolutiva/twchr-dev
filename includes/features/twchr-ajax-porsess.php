<?php

add_action( 'wp_ajax_twchr_taxonomy_update', 'twchr_taxonomy_update_twchr_aja_callback' );

function twchr_taxonomy_update_twchr_aja_callback() {
	if ( ! check_ajax_referer( 'twchr_taxonomy_update', 'nonce', false ) ) {
		wp_die( 'Invalid security token' );
	}

	$schedules_twitch  = $_POST['segment'];

	// FROM WP.
	$schedules_wp = get_terms(
		array(
			'taxonomy' => 'serie',
			'hide_empty' => false,
		)
	);

	$response = '';

	try {
		foreach ( $schedules_wp as $item ) {

			$wp_id = $item->term_id;
			$wp_title = $item->{'name'};

			foreach ( $schedules_twitch as $key => $schedule ) {

				$tw_title = $schedule->{'title'};
				if ( $tw_title == $wp_title ) {
				} else {
					$title = empty( $schedule->title ) ? __( 'No title', 'twitcher' ) : $schedule->title;
					$new_term = wp_insert_term( $title, 'serie' );

					if ( isset( $new_term->errors['term_exists'] ) ) {
					} else {
						$new_term_id = $new_term['term_id'];

						$date_time = $schedule->start_time;
						add_term_meta( $new_term_id, 'twchr_toApi_dateTime', $date_time );
						$select_value = $schedule->category->id;
						add_term_meta( $new_term_id, 'twchr_toApi_category_value', $select_value );
						$select_name = $schedule->category->name;
						add_term_meta( $new_term_id, 'twchr_toApi_category_name', $select_name );
						$schedule_segment_id = $schedule->id;
						add_term_meta( $new_term_id, 'twchr_toApi_schedule_segment_id', $schedule_segment_id );
						$allData = json_encode( $schedule );
						add_term_meta( $new_term_id, 'twchr_fromApi_allData', $allData );

						$schedule_segments = array();
						foreach ( $schedules_twitch as $segment ) {
							if ( $segment->{'title'} === $schedule->{'title'} ) {
									array_push( $schedule_segments, $segment );
							}
						}

						add_term_meta( $new_term_id, 'twchr_schdules_chapters', json_encode( $schedule_segments ) );

						// Convertir las fechas a timestamp
						$start_time = $schedule->start_time;
						$end_time = $schedule->end_time;
						$minutos = twchr_twitch_video_duration_calculator( $start_time, $end_time );
						add_term_meta( $new_term_id, 'twchr_toApi_duration', $minutos );
					}
				}
			}
		}

		$response = 200;

	} catch ( Exception $e ) {
		$response = $e;
	}
	wp_send_json_success( $response );

}


// Registra la acción AJAX para actualizar el campo personalizado
add_action( 'wp_ajax_twchr_ajax_recive', 'twchr_ajax_recive_callback' );

// Define la función que maneja la solicitud AJAX para actualizar el campo personalizado
function twchr_ajax_recive_callback() {
	// Recupera los datos enviados con la solicitud AJAX

	$twchr_action = sanitize_text_field( $_POST['twchr_action'] );
	$target = sanitize_text_field( $_POST['twchr_target'] );
	$body = $_POST['body'];
	$response = 'LE NO';

	if ( ! check_ajax_referer( 'twchr_ajax_recive', 'nonce', false ) ) {
		wp_die( 'Invalid security token' );
	}

	switch ( $twchr_action ) {
		case 'create':
			// code...
			break;
		case 'delete':
			// code...
			break;
		case 'update':
			$post_id = (int) $body['post_id'];
			if ( $target == 'slide-1' ) {
				$response = twchr_save_cf_slide_1( $post_id, $body );
				
			}
			break;
		case 'asing':
			$post_id = (int) $body['post_id'];
			if ( $target == 'slide-1' ) {
				$response = twchr_asign_chapter_by_cf( $post_id, $body );
			}
		default:
			// code...
			break;
	}

	// Actualiza el campo personalizado
	// update_post_meta( $post_id, 'mi_campo_personalizado', $valor_del_campo );

	// Envía una respuesta al navegador
	wp_send_json_success( $response );
}

function twchr_save_cf_slide_1( $post_id, $body ) {
	$allowed = array();

	if ( isset( $body['is_recurring'] ) ) {
		$to_api_is_recurring = wp_kses( $body['is_recurring'], $allowed );
		update_post_meta( $post_id, 'twchr_schedule_card_input--is_recurrig', $body['is_recurring'] );
	}

	if ( isset( $body['streaming_title'] ) ) {
		$to_api_title = sanitize_text_field( $body['streaming_title'] );
		update_post_meta( $post_id, 'twchr_schedule_card_input--title', $to_api_title );
	} else {
		update_post_meta( $post_id, 'twchr_schedule_card_input--title', ' ' );
	}

	if ( isset( $body['date_time'] ) ) {
		update_post_meta( $post_id, 'twchr_schedule_card_input--dateTime', $body['date_time'] );
	}
	if ( isset( $body['streaming_duration'] ) ) {
		$to_api_duration = (int) wp_kses( $body['streaming_duration'], $allowed );
		update_post_meta( $post_id, 'twchr_schedule_card_input--duration', $to_api_duration );
	}

	if ( isset( $body['schedule_id'] ) ) {
		$schedule_segment_id = sanitize_text_field( $body['schedule_id'] );
		update_post_meta( $post_id, 'twchr_stream_twtich_schedule_id', $schedule_segment_id );
	}

	if ( isset( $body['twitch_category'] ) ) {
		$cat_twitch_id = (int) $body['twicth_category']['id'];
		$cat_twitch_name = $body['twicth_category']['name'];

		// Creo una taxonomia cat_twcht
		$response = wp_create_term( $cat_twitch_name, 'cat_twcht' );

		$id = (int) $response['term_id'];

		// Creo stream relacionado.
		wp_set_post_terms( $post_id, array( $id ), 'cat_twcht' );
	}

	return $response;
}


function twchr_asign_chapter_by_cf( $post_id, $body ) {
	$serie = $body['serie'];
	$twitch_category = $body['twitch_category'];
	$twchr_slot = $body['twchr_slot'];
	$stream = $body['stream'];
	try {
		if(!empty($serie['term_id']) && !empty($twchr_slot) && !empty($serie) && !empty($twitch_category['name']) && !empty($twitch_category['id']) && !empty($stream['title']) && !empty($stream['duration'])){
		update_post_meta( $post_id, 'twchr_dateTime_slot', json_encode( $twchr_slot ) );
		update_post_meta( $post_id, 'twchr_schedule_card_input--serie__name', json_encode( $serie ) );
		update_post_meta( $post_id, 'twchr_schedule_card_input--category__name', $twitch_category['name'] );
		update_post_meta( $post_id, 'twchr_schedule_card_input--category__value', $twitch_category['id'] );
		update_post_meta( $post_id, 'twchr_schedule_card_input--title', $stream['title'] );
		update_post_meta( $post_id, 'twchr_schedule_card_input--duration', $stream['duration'] );

		wp_set_post_terms( $post_id, array( (int) $serie['term_id'] ), 'serie' );

		// Creo una taxonomia cat_twcht
		$response = wp_create_term( $twitch_category['name'], 'cat_twcht' );

		$id = (int) $response['term_id'];

		// Creo stream relacionado.
		wp_set_post_terms( $post_id, array( $id ), 'cat_twcht' );

		$response = $body;
		}else{
			$response = __('Not was send all data');
		}

	} catch ( Exception $e ) {
		$response = $e;
	}
	return $response;
}


add_action( 'wp_ajax_twchr_delete_all', 'twchr_delete_all__callback' );

function twchr_delete_all__callback(){
	if(isset($_POST['twchr_delete_all'])){
		$twchr_delete_all = $_POST['twchr_delete_all'];
		update_option( 'twchr_delete_all', 1 );
	}
	
	wp_send_json_success(200);
}