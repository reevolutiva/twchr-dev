<?php
// Registra la acción AJAX para actualizar el campo personalizado
add_action( 'wp_ajax_twchr_ajax_recive', 'twchr_ajax_recive_callback' );

// Define la función que maneja la solicitud AJAX para actualizar el campo personalizado
function twchr_ajax_recive_callback() {
  // Recupera los datos enviados con la solicitud AJAX

  $twchr_action = sanitize_text_field($_POST['twchr_action']);
  $body = $_POST['body'];
  $response = 'LE NO';

  
  if ( ! check_ajax_referer( 'twchr_ajax_recive', 'nonce', false ) ) {
    wp_die( 'Invalid security token' );
  }  
  

  switch ($twchr_action) {
    case 'create':
        # code...
        break;
    case 'delete':
        # code...
        break;
    case 'update':
        $post_id = (int) $body['post_id'];
        twchr_save_cf_slide_1($post_id,$body);
        break;
    
    default:
        # code...
        break;
  }

  // Actualiza el campo personalizado
  //update_post_meta( $post_id, 'mi_campo_personalizado', $valor_del_campo );

  // Envía una respuesta al navegador
  wp_send_json_success($_POST);
}

function twchr_save_cf_slide_1($post_id,$body){
    $allowed = [];
	
    if ( isset( $body['is_recurring'] ) ) {
		$to_api_is_recurring =  wp_kses( $body['is_recurring'], $allowed );
		update_post_meta( $post_id, 'twchr_schedule_card_input--is_recurrig',$body['is_recurring'] );
	}

	if ( isset($body['streaming_title'] ) ) {
		$to_api_title = sanitize_text_field( $body['streaming_title']);
		update_post_meta( $post_id, 'twchr_schedule_card_input--title',$to_api_title);
	}else{
        update_post_meta( $post_id, 'twchr_schedule_card_input--title',' ');
    }


	if ( isset($body['date_time'])) {
		update_post_meta( $post_id, 'twchr_schedule_card_input--dateTime', $body['date_time'] );
	}
	if ( isset($body['streaming_duration'])) {
		$to_api_duration = (int) wp_kses( $body['streaming_duration'], $allowed );
		update_post_meta( $post_id, 'twchr_schedule_card_input--duration',  $to_api_duration);
	}

	if(isset($body['schedule_id'])){
		$schedule_segment_id = sanitize_text_field($body['schedule_id']);
		update_post_meta( $post_id, 'twchr_stream_twtich_schedule_id', $schedule_segment_id );
	}

  if ( isset($body['twicth_category'])) {
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


?>