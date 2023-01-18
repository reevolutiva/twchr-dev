<?php
$values    = get_post_custom( get_the_id() );
$yt_url = get_post_meta( get_the_ID(), 'twchr_streams__yt-link-video-src', true );

?>
<metabox class="twchr_car_tab3" style="<?php echo $twchr_card_src_priority == 'tw' ? 'display:none;' : ''; ?>" >	
	<label>Youtbe URL <input id="twchr-yt-url-link" type="text" name='twchr_streams__yt-link-video-src' class="twchr_schedule_card_input" value="<?php $yt_url != false ? twchr_esc_i18n( $yt_url, 'html' ) : ''; ?>"></label>
	<script>
		let twchr_card_embed_menu_state = 'tw';
		<?php echo $twchr_card_src_priority != false ? 'twchr_card_embed_menu_state = "' . $twchr_card_src_priority . '";' : ''; ?>
	</script>
</metabox>

<?php
if ( isset( $_GET['twchr_insert_shorcode'] ) ) {
	$post_id = get_the_id();
	$array = explode( ',', $_GET['twchr_insert_shorcode'] );
	$alto = explode( '-', $array[1] )[1];
	$ancho = explode( '-', $array[0] )[1];
	$yt_url = $_GET['yt_url'];

	function get_youtube_video_id( $url ) {
		if ( strpos( $url, 'youtu.be' ) !== false ) {
			// La URL utiliza el dominio youtu.be
			$parts = explode( '/', $url );
			return end( $parts );
		} else {
			// La URL utiliza el dominio www.youtube.com
			$query = parse_url( $url, PHP_URL_QUERY );
			parse_str( $query, $params );
			return $params['v'];
		}
	}

	$id = get_youtube_video_id( $yt_url );

	$shorcode = '<br/> https://youtu.be/' . $id;

	update_post_meta( get_the_ID(), 'twchr_streams__yt-link-video-src', $shorcode );

	$post = get_post( get_the_ID() );
	/**
	 * SI el post contiene un shorcode que cominece con "twchr_tw_video"
	 * lo eliminas.
	 */
	$post_content = $post->post_content;
	$post_content = preg_replace( '/\[twchr_tw_video.*\]/', '', $post_content );

	/**
	 * Llenas el cpt con loque tenia antes y el shorcode
	 */
	wp_update_post(
		array(
			'ID' => $post_id,
			'post_content' => $post_content .  $shorcode,
		)
	);
	$url = TWCHR_ADMIN_URL . '/post.php?post=' . get_the_id() . '&action=edit';
	echo "<script>location.href='$url'</script>";
	die();


}
?>
