<?php  
// Add Shortcode
function shorcode_twich_embed( $atts ) {
	
	// Attributes
	$atts = shortcode_atts(
		array(
			'host' => '#',
			'video' => '#',
            'ancho' => 800,
            'alto' => 400
		),
		$atts
	);
    $atts_ouput = json_encode($atts);

	$token = get_option('twchr_app_token');
	$twch_data_prime = get_option('twchr_keys') == false ? false : json_decode(get_option('twchr_keys'));
	$client_id = $twch_data_prime->{'client-id'};

	$video_src = get_post_meta( get_the_ID(), 'twchr_stream_src_priority', true );

	switch ($video_src) {
		case 'yt':
			$yt_url = get_post_meta( get_the_ID(), 'twchr_streams__yt-link-video-src', true );
			if(!empty($yt_url)){
				$html = "<iframe width='".$atts['ancho']."' height='".$atts['alto']."' src='".$yt_url."' title='YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
			}else{
				$html = "<h3>".__('Video not found in any source: Delete the shortcode ([twich_embed]) on this streaming edit page to dismiss this message..','twitcher')."<h3> <a style='text-decoration:underline' href='".TWCHR_ADMIN_URL."/post.php"."?post=".get_the_ID()."&action=edit'>edit</a>";
			}
			
			return $html;
			break;
		default:
			$host = sanitize_text_field($_SERVER['SERVER_NAME']);
			$url = "https://player.twitch.tv/?autoplay=true&chanel=".$atts['host']."&height=".$atts['alto']."&parent=".$host."&referrer=https%3A%2F%2F".$host."%2Ftest%2F&video=".$atts['video']."&width=".$atts['ancho'];
			$idClass = 'twich-frame'.rand();
			$html = "<twichcontainer id='".$idClass."'>
						<iframe src=".$url." width='".$atts['ancho']."' height='".$atts['alto']."'></iframe>
						<script>
							console.log($atts_ouput);
						</script>
					</twichcontainer>";
			return $html;
			break;
	}
	

}
add_shortcode( 'twich_embed', 'shorcode_twich_embed' );

//[twich_embed host="reevolutiva" video="1640517875" ancho="800" alto="400"]

//Shorcode para listar todas las series
function twtchr_shortcode_tx_series() {
	$series = get_terms( array(
	  'taxonomy' => 'serie',
	  'hide_empty' => false
	) );
  
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

