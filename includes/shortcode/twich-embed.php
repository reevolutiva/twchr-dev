<?php
// Add Shortcode.
function twchr_shortcode_tw_video( $atts ) {

	// Attributes.
	$atts = shortcode_atts(
		array(
			'host' => '#',
			'video' => '#',
			'ancho' => 800,
			'alto' => 400,
		),
		$atts
	);
	$atts_ouput = json_encode( $atts );

	$token = get_option( 'twchr_app_token' );
	$twch_data_prime = get_option( 'twchr_keys' ) == false ? false : json_decode( get_option( 'twchr_keys' ) );
	$client_id = $twch_data_prime->{'client-id'};

	$host = sanitize_text_field( $_SERVER['SERVER_NAME'] );
	$url = 'https://player.twitch.tv/?autoplay=true&chanel=' . $atts['host'] . '&height=' . $atts['alto'] . '&parent=' . $host . '&referrer=https%3A%2F%2F' . $host . '%2Ftest%2F&video=' . $atts['video'] . '&width=' . $atts['ancho'];
	$id_class = 'twich-frame' . rand();
	$html = "<twichcontainer id='" . $id_class . "'>
						<iframe src=" . $url . " width='" . $atts['ancho'] . "' height='" . $atts['alto'] . "'></iframe>
						<script>
							console.log($atts_ouput);
						</script>
					</twichcontainer>";
	return $html;

}
add_shortcode( 'twchr_tw_video', 'twchr_shortcode_tw_video' );
/** [twchr_tw_video host="reevolutiva" video="46325675668" ancho="800" alto="400"] **/

function twchr_shortcode_yt_video_embed( $atts ) {
	$atts = shortcode_atts(
		array(
			'ancho' => 800,
			'alto' => 400,
		),
		$atts
	);

	$atts_ouput = json_encode( $atts );

	$host = sanitize_text_field( $_SERVER['SERVER_NAME'] );
	$url = 'https://player.twitch.tv/?autoplay=true&chanel=' . $atts['host'] . '&height=' . $atts['alto'] . '&parent=' . $host . '&referrer=https%3A%2F%2F' . $host . '%2Ftest%2F&video=' . $atts['video'] . '&width=' . $atts['ancho'];
	$id_class = 'twich-frame' . rand();
	$html = "<twichcontainer id='" . $id_class . "'>
				<iframe src=" . $url . " width='" . $atts['ancho'] . "' height='" . $atts['alto'] . "'></iframe>
	
			</twichcontainer>";
	return $html;
}

add_shortcode( 'twchr_yt_video_embed', 'twchr_shortcode_yt_video_embed' );
/** [twchr_yt_video_embed ancho="800" alto="400"] **/


// Shorcode para listar todas las series.
function twtchr_shortcode_tx_series() {
	$series = get_terms(
		array(
			'taxonomy' => 'serie',
			'hide_empty' => false,
		)
	);

	if ( ! empty( $series ) && ! is_wp_error( $series ) ) {
		$output = '<ul>';
		foreach ( $series as $serie ) {
			$output .= '<li>' . $serie->name . '</li>';
		}
		$output .= '</ul>';
	} else {
		$output = '<p>No hay series disponibles.</p>';
	}

	return $output;
}
  add_shortcode( 'twtchr_list_series', 'twtchr_shortcode_tx_series' );

