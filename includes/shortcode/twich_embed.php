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
	$video_src = twchr_twitch_video_exist($atts['video'],$token,$client_id); 
	
	switch ($video_src) {
		case 200:
			$host = $_SERVER['SERVER_NAME'];
			$url = "https://player.twitch.tv/?autoplay=true&chanel=".$atts['host']."&height=".$atts['alto']."&parent=".$host."&referrer=https%3A%2F%2F".$host."%2Ftest%2F&video=".$atts['video']."&width=".$atts['ancho'];
			$idClass = 'twich-frame'.rand();
			$html = "<twichcontainer id='".$idClass."'>
						<iframe src=".$url." width='".$atts['ancho']."' height='".$atts['alto']."'></iframe>
						<script>
							console.log($atts_ouput);
						</script>
					</twichcontainer>";
			break;
		
		default:
			$yt_url = get_post_meta( get_the_ID(), 'twchr_streams__yt-link-video-src', true );
			$html = "<iframe width='".$atts['ancho']."' height='".$atts['alto']."' src='".$yt_url."' title='YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
			break;
	}
	
	   return $html;

}
add_shortcode( 'twich_embed', 'shorcode_twich_embed' );

//[twich_embed video="1577869766"  ancho="800" alto="400"]

// NO FUNCIONA
// https://player.twitch.tv/?autoplay=true&chanel=egosapiens&height=400&parent=egosapiens.local&referrer=https%3A%2F%2Fegosapiens.local%2Ftest%2F&video=%23&width=800

// Si FUNCIONA
// https://player.twitch.tv/?autoplay=true&chanel=egosapiens&height=400&parent=egosapiens.local&referrer=https%3A%2F%2Fegosapiens.local%2Ftest%2F&video=1577869766&width=800