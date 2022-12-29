<?php
// Registra la acción AJAX para actualizar el campo personalizado
add_action( 'wp_ajax_twchr_ajax_recive', 'twchr_ajax_recive_callback' );

// Define la función que maneja la solicitud AJAX para actualizar el campo personalizado
function twchr_ajax_recive_callback() {
  // Recupera los datos enviados con la solicitud AJAX

  $twchr_action = sanitize_text_field($_POST['twchr_action']);
  $body = json_decode($_POST['body']);

  switch ($twchr_action) {
    case 'create':
        # code...
        break;
    case 'delete':
        # code...
        break;
    case 'update':
        $post_id = $body->post_id;
        twchr_save_cf_slide_1($post_id,$body);
        break;
    
    default:
        # code...
        break;
  }

  // Actualiza el campo personalizado
  //update_post_meta( $post_id, 'mi_campo_personalizado', $valor_del_campo );

  // Envía una respuesta al navegador
  wp_send_json_success(json_encode($_POST));
}

function twchr_save_cf_slide_1($post_id,$body){
    $allowed = [];
	/**
	          post_id: getParameterByName("post"),
              schedule_id: segment.id,
              is_recurring: segment.is_recurring,
              date_time: segment.start_time,
              streaming_title: segment.title,
              twicth_category: segment.category,
              streaming_duration: minutes,
	 **/
    if ( twchr_post_isset_and_not_empty( $body->is_recurring ) ) {
		$to_api_is_recurring =  wp_kses( $body->is_recurring, $allowed );
		update_post_meta( $post_id, $body->is_recurring, $to_api_is_recurring );
	}

	if ( twchr_post_isset_and_not_empty( $body->streaming_title ) ) {
		$to_api_title = sanitize_text_field( $body->streaming_title);
		update_post_meta( $post_id, $body->streaming_title, $to_api_title );
	}


	if ( twchr_post_isset_and_not_empty($body->date_time)) {
		update_post_meta( $post_id, 'twchr_schedule_card_input--dateTime', $body->date_time );
	}
	if ( twchr_post_isset_and_not_empty( 'twchr_schedule_card_input--duration' ) ) {
		$to_api_duration = (int) wp_kses( $_POST['twchr_schedule_card_input--duration'], $allowed );
		update_post_meta( $post_id, 'twchr_schedule_card_input--duration', $to_api_duration );
	}

	if ( twchr_post_isset_and_not_empty( 'twchr_schedule_card_input--serie__id' ) ) {
		wp_set_post_terms( $post_id, array( (int) $_POST['twchr_schedule_card_input--serie__id'] ), 'serie' );
	}
	if ( twchr_post_isset_and_not_empty( 'twchr_dateTime_slot' ) ) {
		update_post_meta( $post_id, 'twchr_dateTime_slot', $_POST['twchr_dateTime_slot'] );
	}

	if ( twchr_post_isset_and_not_empty( 'twchr_stream_data_dateTime' ) ) {
		update_post_meta( $post_id, 'twchr_stream_data_dateTime', wp_kses( $_POST['twchr_stream_data_dateTime'], $allowed ) );
	}

	if(twchr_post_isset_and_not_empty('schedule_id')){
		$schedule_segment_id = sanitize_term_field($_POST['schedule_id']);
		update_post_meta( $post_id, 'twchr_stream_twtich_schedule_id', $schedule_segment_id );
	}

    return 200;
}

?>