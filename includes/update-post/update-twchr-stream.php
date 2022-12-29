<?php
/**
 * actualizo un cpt streaming
 *
 * @param [type]  $title
 * @param [type]  $id
 * @param [type]  $create_at
 * @param [type]  $description
 * @param [type]  $duration
 * @param [type]  $api_languaje
 * @param [type]  $muted_segment
 * @param [type]  $published_at
 * @param [type]  $stream_id
 * @param [type]  $thumbnail_url
 * @param [type]  $type
 * @param [type]  $url
 * @param [type]  $user_id
 * @param [type]  $user_login
 * @param [type]  $user_name
 * @param [type]  $view_count
 * @param [type]  $viewble
 * @param integer $author
 * @return void
 */
function twitcher_update_cpt( $title, $id, $create_at, $description, $duration, $api_languaje, $muted_segment, $published_at, $stream_id, $thumbnail_url, $type, $url, $user_id, $user_login, $user_name, $view_count, $viewble, $author = 1 ) {
	$display_name = '';
	$data_broadcaster_raw = get_option( 'twchr_data_broadcaster', false ) == false ? false : json_decode( get_option( 'twchr_data_broadcaster' ) );
	if ( $data_broadcaster_raw != false ) {
		$display_name = $data_broadcaster_raw->{'data'}[0]->{'display_name'};
	}

	$postarr = array(
		'post_title' => $title,
		'post_status' => 'publish',
		'post_author' => $author,
		'post_type' => 'twchr_streams',
		'post_content' => '[twchr_tw_video host="' . $display_name . '" video="' . $id . '"  ancho="800" alto="400"]',
		'meta_input'   => array(
			'twchr-from-api_create_at' => $create_at,
			'twchr-from-api_description' => $description,
			'twchr-from-api_duration' => $duration,
			'twchr-from-api_id' => $id,
			'twchr-from-api_languaje' => $api_languaje,
			'twchr-from-api_muted_segment' => $muted_segment,
			'twchr-from-api_published_at' => $published_at,
			'twchr-from-api_stream_id' => $stream_id,
			'twchr-from-api_thumbnail_url' => $thumbnail_url,
			'twchr-from-api_type' => $type,
			'twchr-from-api_url' => $url,
			'twchr-from-api_user_id' => $user_id,
			'twchr-from-api_user_login' => $user_login,
			'twchr-from-api_user_name' => $user_name,
			'twchr-from-api_view_count' => $view_count,
			'twchr-from-api_viewble' => $viewble,
			'twchr-from-api_title' => $title,
		),
	);

	wp_update_post( $postarr );

}
