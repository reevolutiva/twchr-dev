<?php
function twchr_get_stream() {
	$args = array(
		'post_type' => 'twchr_streams'
	);

	$post = get_posts( $args );

	return $post;
}
