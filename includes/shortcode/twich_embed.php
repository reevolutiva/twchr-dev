

<?php  
// Add Shortcode
function shorcode_twich_embed( $atts ) {

	// Attributes
	$atts = shortcode_atts(
		array(
			'video' => '#',
            'ancho' => 800,
            'alto' => 400
		),
		$atts
	);
    $atts_ouput = json_encode($atts);
	  $host = "egosapiens.local";
	    $url = "https://player.twitch.tv/?autoplay=true&chanel=egosapiens&height=".$atts['alto']."&parent=".$host."&referrer=https%3A%2F%2F".$host."%2Ftest%2F&video=".$atts['video']."&width=".$atts['ancho'];
	    $idClass = 'twich-frame'.rand();
	    $html = "<twichcontainer id='".$idClass."'>
	                <iframe src=".$url." width='".$atts['ancho']."' height='".$atts['alto']."'></iframe>
	                <script>
                        console.log($atts_ouput);
                    </script>
	            </twichcontainer>";
	   return $html;

}
add_shortcode( 'twich_embed', 'shorcode_twich_embed' );

//[twich_embed video="1577869766"  ancho="800" alto="400"]

// NO FUNCIONA
// https://player.twitch.tv/?autoplay=true&chanel=egosapiens&height=400&parent=egosapiens.local&referrer=https%3A%2F%2Fegosapiens.local%2Ftest%2F&video=%23&width=800

// Si FUNCIONA
// https://player.twitch.tv/?autoplay=true&chanel=egosapiens&height=400&parent=egosapiens.local&referrer=https%3A%2F%2Fegosapiens.local%2Ftest%2F&video=1577869766&width=800