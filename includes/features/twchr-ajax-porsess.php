<?php
// Registra la acción AJAX para actualizar el campo personalizado
add_action( 'wp_ajax_twchr_ajax_recive', 'twchr_ajax_recive_callback' );

// Define la función que maneja la solicitud AJAX para actualizar el campo personalizado
function twchr_ajax_recive_callback() {
  // Recupera los datos enviados con la solicitud AJAX

  $twchr_action = sanitize_text_field($_POST['twchr_action']);

  switch ($twchr_action) {
    case 'create':
        # code...
        break;
    case 'delete':
        # code...
        break;
    case 'update':
        # code...
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

function twchr_save_cf_slide_1($post_id){
    $allowed = [];
    if ( twchr_post_isset_and_not_empty( 'twchr_schedule_card_input--is_recurrig' ) ) {
		$to_api_is_recurring =  wp_kses( $_POST['twchr_schedule_card_input--is_recurrig'], $allowed );
		update_post_meta( $post_id, 'twchr_schedule_card_input--is_recurrig', $to_api_is_recurring );
	}

	if ( twchr_post_isset_and_not_empty( 'twchr_schedule_card_input--title' ) ) {
		$to_api_title = sanitize_text_field( $_POST['twchr_schedule_card_input--title']);
		update_post_meta( $post_id, 'twchr_schedule_card_input--title', $to_api_title );
	}

	// Si API IS RECURRING
	// El titulo sera serie name
	if ( $to_api_is_recurring && twchr_post_isset_and_not_empty( 'twchr_schedule_card_input--serie__name' ) ) {
		$to_api_title = wp_kses( $_POST['twchr_schedule_card_input--serie__name'], $allowed );
	}

	if ( twchr_post_isset_and_not_empty( 'twchr_schedule_card_input--dateTime' ) ) {

		$date_time_raw = sanitize_text_field( $_POST['twchr_schedule_card_input--dateTime'] );
		$date_time_stg = strtotime( $date_time_raw );
		$to_api_date_time = date( DateTimeInterface::RFC3339, $date_time_stg );

		update_post_meta( $post_id, 'twchr_schedule_card_input--dateTime', $to_api_date_time );
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

    return 200;
}

?>