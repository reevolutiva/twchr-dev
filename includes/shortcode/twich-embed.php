<?php
/**
 *  En este archivo declaro los shorcodes
 * que retornaran un iframe con una transmision en vivo o grabada
 * en Twitch o en YouTube
 */


/**
 * Retornona un iframe con el reproductor de twich
 * 
 * [twchr_tw_video host="reevolutiva" video="46325675668" ancho="800" alto="400"]
 * 
 * - video: id del video
 * - host: el dominio de la web desde donde se incursta el iframe
 * - ancho: el ancho del iframe en px
 * - alto: el alto del iframe en px
 * 
 * @param [type] $atts
 * @return void
 */
function twchr_shortcode_tw_video( $atts ) {

	//Attributes.
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
/**
 * Est funcion retorna un iframe con la transmision en vivo de tu canal de twitch
 *	[twchr_tw_video_live host="reevolutiva"]
 * @param [type] $atts
 * @return void
 */
function twchr_shortcode_tw_video_live( $atts ) {
	
	// Attributes.
	$atts = shortcode_atts(
		array(
			'host' => '#',
            'ancho' => 800,
            'alto' => 400
		),
		$atts
	);
    $atts_ouput = json_encode($atts);
			$host = sanitize_text_field($_SERVER['SERVER_NAME']);
			
			$url = "https://player.twitch.tv/?autoplay=true&channel=".$atts['host']."&parent=".$host;
			$idClass = 'twich-frame'.rand();
			$html = "<twichcontainer id='".$idClass."'>
						<iframe src=".$url." width='".$atts['ancho']."' height='".$atts['alto']."'></iframe>
					</twichcontainer>";
			
			return $html;

}
add_shortcode( 'twchr_tw_video_live', 'twchr_shortcode_tw_video_live' );


/**
 * Esta funcion retorna un iframe con la trasmicion en vivo de tu canal
 * de twitch y un iframe con el chat en vivo de ese canal
 *	[twchr_tw_video_live_chat host="reevolutiva"]
 * @param [type] $atts
 * @return void
 */
function twchr_shortcode_tw_video_live_chat( $atts ) {
	
	// Attributes
	$atts = shortcode_atts(
		array(
			'host' => '#',
            'ancho' => 800,
            'alto' => 400
		),
		$atts
	);
    $atts_ouput = json_encode($atts);
			$host = sanitize_text_field($_SERVER['SERVER_NAME']);
			$url = "https://player.twitch.tv/?autoplay=true&channel=".$atts['host']."&parent=".$host;
			$urlChat = "https://www.twitch.tv/embed/".$atts['host']."/chat?parent=".$host;
			$idClass = 'twich-frame'.rand();
			$html = "<twichcontainer id='".$idClass."'>
						<iframe src=".$url." width='".$atts['ancho']."' height='".$atts['alto']."'></iframe>
						<iframe src=".$urlChat." width='".($atts['ancho'] / 2)."' height='".$atts['alto']."'></iframe>
					</twichcontainer>";
			
			return $html;

}
add_shortcode( 'twchr_tw_video_live_chat', 'twchr_shortcode_tw_video_live_chat' );


/**
 * Esta funicion retorna un iframe con el chat en vivo de tu canal de twitch
 * [twchr_tw_chat host="reevolutiva"]
 * @param [type] $atts
 * @return void
 */
function twchr_shortcode_tw_chat( $atts ) {
	
	// Attributes
	$atts = shortcode_atts(
		array(
			'host' => '#',
            'ancho' => 800,
            'alto' => 400
		),
		$atts
	);
    $atts_ouput = json_encode($atts);
			$host = sanitize_text_field($_SERVER['SERVER_NAME']);
			$urlChat = "https://www.twitch.tv/embed/".$atts['host']."/chat?parent=".$host;
			$idClass = 'twich-frame'.rand();
			$html = "<twichcontainer id='".$idClass."'>
						<iframe src=".$urlChat." width='".$atts['ancho']."' height='".$atts['alto']."'></iframe>
					</twichcontainer>";
			
			return $html;

}
add_shortcode( 'twchr_tw_chat', 'twchr_shortcode_tw_chat' );






/**
 * Retornona un iframe con el reproductor de youtube
 * 
 * [twchr_yt_video_embed ancho="800" alto="400" src="http://www.youtube.com/watch"]
 * 
 * - ancho: el ancho del iframe en px
 * - alto: el alto del iframe en px
 * 
 * @param [type] $atts
 * @return void
 */
function twchr_shortcode_yt_video_embed( $atts ) {
	$atts = shortcode_atts(
		array(
			'ancho' => 800,
			'alto' => 400,
			'src' => '#'
		),
		$atts
	);

	$atts_ouput = json_encode( $atts );
	$id_class = 'twich-frame' . rand();
	$html = "<twichcontainer id='" . $id_class . "'>
				<iframe width='" . $atts['ancho'] . "' height='" . $atts['alto'] . " src='".$atts['src']."' title='YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>
			</twichcontainer>";
	return $html;
}

add_shortcode( 'twchr_yt_video_embed', 'twchr_shortcode_yt_video_embed' );



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
  add_shortcode( 'list_series', 'twtchr_shortcode_tx_series' );

