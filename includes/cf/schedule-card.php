<?php
/**
 * Renderizo twchr_card
 *
 * @return void
 */
function twchr_cf_schedule__card() {
	$post_id = get_the_ID();
	$term_serie = wp_get_post_terms( $post_id, 'serie' );
	$term_serie_list = '';
	$term_serie_id = '';
	$term_serie_name = '';
	foreach ( $term_serie as $term ) {
		$str = '<span>' . $term->{'slug'} . '</span>';
		$term_serie_list = $term_serie_list . $str;
		$term_serie_id = $term->term_id;
		$term_serie_name = $term->name;
	}
	$term_cat_twcht_list = '';
	$term_cat_twcht = wp_get_post_terms( $post_id, 'cat_twcht' );
	$term_cat_twcht_id = '';
	$term_cat_twcht_name = '';
	//var_dump($term_cat_twcht);
	foreach ( $term_cat_twcht as $term ) {
		$str = '<span>' . $term->{'slug'} . '</span>';
		$term_cat_twcht_list = $term_cat_twcht_list . $str;
		$term_cat_twcht_id = get_term_meta( $term->term_id, 'twchr_stream_category_id' )[0];
		$term_cat_twcht_name = $term->name;
	}
	$values  = get_post_custom( $post_id );

	$title = isset( $values['twchr_schedule_card_input--title'] ) ? $values['twchr_schedule_card_input--title'][0] : '';
	$category = isset( $values['twchr_schedule_card_input--category'] ) ? $values['twchr_schedule_card_input--category'][0] : '';
	$date_time = isset( $values['twchr_schedule_card_input--dateTime'] ) ? $values['twchr_schedule_card_input--dateTime'][0] : '';
	$twchr_date_time_slot = isset( $values['twchr_dateTime_slot'] ) ? $values['twchr_dateTime_slot'][0] : '';
	$duration = isset( $values['twchr_schedule_card_input--duration'] ) ? $values['twchr_schedule_card_input--duration'][0] : '';
	
	if($twchr_date_time_slot != 'false' && !empty($twchr_date_time_slot)){
		$objetc = json_decode($twchr_date_time_slot);
		$start_time = new DateTime($objetc->{'start_time'});
		$end_time = new DateTime($objetc->{'end_time'});
		$date_now = new DateTime(date("c"));

		/*
		* Si start time es igual que la fecha actual
		* cambia este cpt a twchr_streaming_states Live.
		*/ 
		if($start_time->getTimestamp() == $date_now->getTimestamp()){
			wp_set_post_terms( $post_id, 'Live', 'twchr_streaming_states' );
		}

		/*
		* Si end time es mayo que la fecha actual
		* cambia este cpt a twchr_streaming_states Past.
		*/ 
		if($date_now->getTimestamp() > $end_time->getTimestamp()){
			wp_set_post_terms( $post_id, 'Past', 'twchr_streaming_states' );
		}

	}else{
		$create_at_raw = get_post_meta( get_the_ID(), 'twchr-from-api_create_at', true );
		$create_at = new DateTime($create_at_raw);
		$date_now = new DateTime(date("c"));

		if($date_now->getTimestamp() > $create_at->getTimestamp()){
			wp_set_post_terms( $post_id, 'Past', 'twchr_streaming_states' );
		}

	}
	
	
	$is_recurring = isset( $values['twchr_schedule_card_input--is_recurrig'] ) ? $values['twchr_schedule_card_input--is_recurrig'][0] : false;
	$serie = isset( $values['twchr_schedule_card_input--serie'] ) ? $values['twchr_schedule_card_input--serie'][0] : '';
	$twchr_card_src_priority = isset( $values['twchr-card-src-priority'] ) ? $values['twchr-card-src-priority'][0] : '';
	$card_keys = array(
		'twchr_keys' => json_decode( get_option( 'twchr_keys' ) ),
		'twchr_app_token' => get_option( 'twchr_app_token' ),
		'twitcher_data_broadcaster' => json_decode( get_option( 'twchr_data_broadcaster' ) )->{'data'}[0],
	);
	?>
	<script>const twchr_post_id=<?php echo get_the_ID(); ?>;  const twchr_taxonomy_update = "<?php echo wp_create_nonce( 'twchr_taxonomy_update' ); ?>"; const twchr_post_nonce = "<?php echo wp_create_nonce( 'twchr_ajax_recive' ); ?>"; const twchr_card_credentials = JSON.parse(`<?php echo json_encode( $card_keys ); ?>`);</script>
	<?php
	require_once 'schedule_custom_card.php';
}

add_action( 'edit_form_after_title', 'twchr_cf_schedule__card' );

/**
 * Guardo los custom fields que provienene de twitchr_card
 *
 * @param [type] $post_id
 * @return void
 */
function twchr_cf_schedule__card__metadata_save( $post_id ) {


	/*
	Antes de guardar la información, necesito verificar tres cosas:
		1. Si la entrada se está autoguardando
		2. Comprobar que el usuario actual puede realmente modificar este contenido.
	*/

	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	$allowed = array();

	$to_api_title = '';
	$to_api_date_time = '';
	$to_api_is_recurring = '';
	$to_api_duration = '';

	if ( twchr_post_isset_and_not_empty( 'twchr_streams__yt-link-video-src' ) ) {
		update_post_meta( $post_id, 'twchr_streams__yt-link-video-src', sanitize_text_field( $_POST['twchr_streams__yt-link-video-src'] ) );
	}

	if ( twchr_post_isset_and_not_empty( 'twchr-card-src-priority' ) ) {
		update_post_meta( $post_id, 'twchr-card-src-priority', sanitize_text_field( $_POST['twchr-card-src-priority'] ) );
	}

	$twch_res = false;
	if ( twchr_post_isset_and_not_empty( 'twchr_schedule_card_input--category__value' ) ) {
		$cat_twitch_id = (int) $_POST['twchr_schedule_card_input--category__value'];
		$cat_twitch_name = $_POST['twchr_schedule_card_input--category__name'];

		// Creo una taxonomia cat_twcht
		$response = wp_create_term( $cat_twitch_name, 'cat_twcht' );

		$id = (int) $response['term_id'];

		// Creo stream relacionado.
		wp_set_post_terms( $post_id, array( $id ), 'cat_twcht' );
	}

	if ( $to_api_is_recurring == 'false' && isset( $to_api_title ) && isset( $to_api_date_time ) && isset( $cat_twitch_id ) && isset( $to_api_duration ) && ! isset( $_POST['twchr-from-api_create_at'] ) && ! isset( $_POST['twchr-from-api_title'] ) ) {
		$twch_res = twtchr_twitch_schedule_segment_create( $post_id, $to_api_title, $to_api_date_time, $cat_twitch_id, $to_api_duration, false );

		if ( isset( $twch_res->error ) ) {
		} else {
			$schedule_segment_id = $twch_res['allData']->{'segments'}[0]->{'id'};
			update_post_meta( $post_id, 'twchr_stream_twtich_schedule_id', $schedule_segment_id );
		}

		$all_data = json_encode( $twch_res );
		update_post_meta( $post_id, 'twchr_stream_all_data_from_twitch', $all_data );

	} else {
		update_post_meta( $post_id, 'twchr_stream_all_data_from_twitch', '' );

	}
}


add_action( 'save_post', 'twchr_cf_schedule__card__metadata_save' );
