<?php

/**
 *  This file contains all functions that
 * prosess the request ajax from twitcher plugin to
 * same wordpress.
 */


add_action( 'wp_ajax_twchr_taxonomy_update', 'twchr_taxonomy_update_twchr_aja_callback' );

/**
 * Process ajax request with action: 'twchr_taxonomy_update'
 *
 * @return void
 */
function twchr_taxonomy_update_twchr_aja_callback() {
	if ( ! check_ajax_referer( 'twchr_taxonomy_update', 'nonce', false ) ) {
		wp_die( 'Invalid security token' );
	}

	$schedules_twitch  = $_POST['segment'];

	$response = twchr_tax_serie_update($schedules_twitch);
	
	wp_send_json_success( $response );

}


// Registra la acción AJAX para actualizar el campo personalizado
add_action( 'wp_ajax_twchr_ajax_recive', 'twchr_ajax_recive_callback' );

/**
 *  Define la función que maneja la solicitud AJAX para actualizar el campo personalizado.
 *
 * @return void
 */
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
				twchr_save_cf_slide_1( $post_id, $body );
				$response = ['status' => 200, 'post_id' => $post_id];
			}
			break;
		case 'asing':
			$post_id = (int) $body['post_id'];
			if ( $target == 'slide-1' ) {
				twchr_asign_chapter_by_cf( $post_id, $body );
				$response = ['status' => 200, 'post_id' => $post_id];
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

	wp_update_post(
		array(
			'ID' => $post_id,
			'post_status' => 'publish' 
		)
	);

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

	if ( isset( $body['twicth_category'] ) ) {
		$cat_twitch_id = (int) $body['twicth_category']['id'];
		$cat_twitch_name = $body['twicth_category']['name'];

		// Creo una taxonomia cat_twcht
		$response = wp_create_term( $cat_twitch_name, 'cat_twcht' );

		$id = (int) $response['term_id'];

		// Creo stream relacionado.
		wp_set_post_terms( $post_id, array( $id ), 'cat_twcht' );
	}

	return 200;
}


function twchr_asign_chapter_by_cf( $post_id, $body ) {
	$serie = $body['serie'];
	$twitch_category = $body['twitch_category'];
	$twchr_slot = $body['twchr_slot'];
	$stream = $body['stream'];
	$chapter_id = $body['twchr_slot']['chapter_id'];
	$post_title = empty($body['post_title']) ? $serie['name'] : $body['post_title'];
	$response = '';
	try {
		wp_update_post(array(
			'ID' => $post_id,
            'post_title' => $post_title,
			'post_status' => 'publish'

		));

		// verifico que el post exister.
			$post = get_post($post_id);
			if($post !== null):
		
				update_post_meta( $post_id, 'twchr_dateTime_slot', json_encode( $twchr_slot ) );
				update_post_meta( $post_id, 'twchr_schedule_card_input--serie__name', json_encode( $serie ) );
				update_post_meta( $post_id, 'twchr_stream_twtich_schedule_id', $chapter_id );

				
				$serie_id = (int) $serie['term_id'];
				$exist = '';
				$realation = '';

				if($exist = term_exists($serie_id,'serie')){
					$realation = wp_set_object_terms( $post_id, array( $serie_id ), 'serie', false);

					//$term_serie = wp_get_post_terms( $post_id, 'serie' );
					// RETUNR SERIE_ID
				}
					
				update_post_meta( $post_id, 'twchr_schedule_card_input--category__name', $twitch_category['name'] );
				update_post_meta( $post_id, 'twchr_schedule_card_input--category__value', $twitch_category['id'] );
			
				
				// CREO UN TERM CAT TWITCH.
				$cat_twitch = wp_create_term( $twitch_category['name'], 'cat_twcht');
				// CONVIERTO A INT TERM_ID
				$id = (int) $cat_twitch['term_id'];

				// CREO RELACION DE NUEVO TERM(ID) CON CPT(POST_ID).
				$twchr_cat = wp_set_post_terms( $post_id, array( $id ), 'cat_twcht', false);
				
				//$realation = wp_get_object_terms($post_id,'serie');
				
				
				update_post_meta( $post_id, 'twchr_schedule_card_input--title', empty($stream['title']) ? $post_title : $stream['title'] );
				update_post_meta( $post_id, 'twchr_schedule_card_input--duration', $stream['duration'] );
			endif;

		// DESPUES DE QUE ACTUALIZAS LOS CUSTOM FIELDS
		if(isset($serie['term_id'])){
			/*
			$date_time_slot = get_post_meta($post_id,'twchr_dateTime_slot',false) != false ? json_decode(get_post_meta(get_the_ID(),'twchr_dateTime_slot')[0]) : false;
			if(isset($date_time_slot->{'start_time'})){
				$date_time = $date_time_slot->{'start_time'};
			
				$fecha = strtotime($date_time);
				$fecha_actual = time();
			
				// Si la fecha es antigua
				if($fecha > $fecha_actual){
					$term_id = $serie['term_id'];
					update_term_meta($term_id, 'twchr_schdules_chapters','');
				}
			}
			*/
			
		}

		
		

		$response =$body;

	} catch ( Exception $e ) {
		$response = $e;
	}
	return $response;
}


add_action( 'wp_ajax_twchr_delete_all', 'twchr_delete_all_callack');
function twchr_delete_all_callack(){
    $twchr_delete_all = $_POST['twchr_delete_all'];
    update_option( 'twchr_delete_all', $twchr_delete_all );

    wp_send_json_success(wp_json_encode($_POST));
}

?>