<?php
function crearStream( $title, $id, $create_at, $description, $duration, $api_languaje, $muted_segment_par, $published_at, $stream_id, $thumbnail_url, $type, $url, $user_id, $user_login, $user_name, $view_count, $viewble, $author = 1, $host = undefined ) {
	$muted_segment = $muted_segment_par == null ? '' : $muted_segment_par;
	$postarr = array(
		'post_title' => $title,
		'post_status' => 'publish',
		'post_author' => $author,
		'post_type' => 'twchr_streams',
		'post_content' => '[twchr_tw_video host="' . $host . '" video="' . $id . '"]',
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

	return wp_insert_post( $postarr );
}

