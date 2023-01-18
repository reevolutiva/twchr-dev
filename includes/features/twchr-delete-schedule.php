<?php
/**
 * En este archivo estan las funcioens que eliminan schedules segments
 */

/**
 * Esta funcion elimina un schedule segmentt de twitch
 * desde un custom post type.
 *
 * @param [type] $post_id
 * @return void
 */
function twchr_delete_schedule_by_cpt( $post_id ) {
	$is_recurrig = get_post_meta( $post_id, 'twchr_schedule_card_input--is_recurrig' ) == false ? 'not-exist' : get_post_meta( $post_id, 'twchr_schedule_card_input--is_recurrig' )[0];
	$schedule_id = get_post_meta( $post_id, 'twchr_stream_twtich_schedule_id' );
	$delete = null;
	if ( $is_recurrig === false &&  $schedule_id != false && get_option( 'twchr_set_clear_tw') == 1) {
		// ESTE CPT es un solo streaming.
		
		$twchr_titulo = get_the_title( $post_id );
		$delete = twtchr_twitch_schedule_segment_delete( $schedule_id, $twchr_titulo, $post_id );
	}

	if ( $delete != null && $delete['status'] != 204 ) {

		$url_list = '';
		$url_main = TWCHR_ADMIN_URL . 'edit.php?post_type=twchr_streams';
		foreach ( $delete as $key => $value ) {
			$url_list .= '&' . $key . '=' . $value;
		}
		echo '<script>' . esc_js( "location.href='" . $url_main . $url_list . "';" ) . '</script>';
		die();
	}

}

add_action( 'wp_trash_post', 'twchr_delete_schedule_by_cpt' );


/**
 * Esta funcion elimina un schedule segment desde un custom term.
 *
 * @param [type] $term_id
 * @return void
 */
function twchr_delete_schedule_by_term( $term_id ) {
	// Guarda schedule_id del array en twchr_streams_relateds.
	$twchr_schdules_chapters = get_term_meta( $term_id, 'twchr_schdules_chapters',true);
	$schedule_id = '';
	if(get_option( 'twchr_set_clear_tw') == 1){
		// Si $twchr_schdules_chapters esta vacio.
		if ( empty($twchr_schdules_chapters)) {
			// obtiene $twchr_schdules_chapters de custom_field twchr_toApi_schedule_segment_id.
			$schedule_id = get_term_meta( $term_id, 'twchr_toApi_schedule_segment_id',true);
			twtchr_twitch_schedule_segment_delete( $schedule_id );

		} else {
			$twchr_schdules_chapters = json_decode( $twchr_schdules_chapters );
			foreach ( $twchr_schdules_chapters as $chapter ) {
				$schedule_id = $chapter->{'id'};
				// Elimina schedule segment de twitch.tv.
				twtchr_twitch_schedule_segment_delete( $schedule_id );

			}
		}
	}

}

add_action( 'pre_delete_term', 'twchr_delete_schedule_by_term', 10 );


