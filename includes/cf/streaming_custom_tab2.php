<div class="twchr_car_tab2">
	<?php
		$twch_data_prime = get_option( 'twchr_keys' ) == false ? false : json_decode( get_option( 'twchr_keys' ) );
		$data_broadcaster = get_option( 'twchr_data_broadcaster', false ) == false ? false : json_decode( get_option( 'twchr_data_broadcaster' ) );
		$broadcaster_id = $data_broadcaster->{'data'}[0]->{'id'};
		$twch_data_app_token = get_option( 'twchr_app_token' );
		// domain.net/wp-admin/post-new.php.
		// Divide la url por sus "/" y escoje el ultimo item.
		// FIXME: sanitize_url es una funcion deprecada
		$data_url = esc_url_raw( $_SERVER['REQUEST_URI'] );
		$data_url = explode( '/', $data_url )[2];

	?>
			<style>
			.twchr_modal_video_ajax span.video-saved {
				display: block;
				width: 25px;
				height: 100%;
				background-size: contain;
				background-repeat: no-repeat;
				background-position: center;
				background-image: url(<?php echo TWCHR_URL_ASSETS . 'twchr_check.png'; ?>);
				margin: 0;
			}
			a.twchr_button_get_videos {
					text-decoration: none;
					padding: 5px 10px;
					display: block;
					margin-bottom: 10pt;
					width: max-content;
					border-radius: 5px;
					background-color: var(--twchr-purple);
					color: #fff;
				}
	
				stream.twchr_modal_get_videos{
					position: static;
					border: 0;
					box-shadow: none;
					background: 0;
					display: none;
				}
				stream.twchr_modal_get_videos.active{
					display: block;
				}

				
	
				.twchr-modal .twchr_help_button {
					display: block;
					width: 40px;
					height: 40px;
					background-image: url(<?php echo esc_html( TWCHR_URL_ASSETS . 'help.png' ); ?>);
					background-size: contain;
					background-repeat: no-repeat;
					margin-right: 6pt;
				}
				.previw_card.disabled{
					display: none;
				}
	
			</style>
			<stream class="twchr_modal_get_videos twchr-modal active">    
				<div id="twchr_button_get_videos__content">
					<ul class="twchr-modal-selection__list">
						<li><?php twchr_esc_i18n( 'Streaming name', 'html' ); ?></li>
						<li><?php twchr_esc_i18n( 'Date', 'html' ); ?></li>
						<li><?php twchr_esc_i18n( 'Already saved?', 'html' ); ?></li>
						<li><?php twchr_esc_i18n( 'Import', 'html' ); ?></li>
					</ul>
					<div class="content">
	
					</div>
					
				</div>
	
				<div class="twchr-modal-footer">
					<span class="twchr_help_button">
						<p><?php twchr_esc_i18n( 'The folowing list is the avaiable videos in your twitch account. Select the video that you want to asign to this post.', 'html' ); ?>
						</p>
						
					</span>
				</div>
			</stream>
			<script>
			const twchr_modal = document.querySelector(".twchr_modal_get_videos.twchr-modal");
			
			</script>
			
</div>

<?php

if ( isset( $_GET['twchr_twitch_embed__host'] ) && $_GET['twchr_twitch_embed__video'] ) {

	
	$host = sanitize_text_field( $_GET['twchr_twitch_embed__host'] );
	$video = sanitize_text_field( $_GET['twchr_twitch_embed__video'] );
	$post_id = get_the_id();
	$shortcode = '[twchr_tw_video host="' . $host . '" video="' . $video . '" ancho="800" alto="400"]';

	// Antes de actualizar video obtener el content del streaming.

	$post = get_post($post_id);
	$post_content = $post->{'post_content'};

	// Concateno el shortcode con el contenido del streaming.
	$content = $post_content.$shortcode;

	// Actualizo el streaming.
	wp_update_post(
		array(
			'ID' => $post_id,
			'post_content' => $content,
			'meta_input'   => array(
				'twchr-from-api_create_at' => sanitize_text_field( $_GET['created_at'] ),
				'twchr-from-api_description' => sanitize_text_field( $_GET['description'] ),
				'twchr-from-api_id' => sanitize_text_field( $_GET['id'] ),
				'twchr-from-api_languaje' => sanitize_text_field( $_GET['language'] ),
				'twchr-from-api_muted_segment' => sanitize_text_field( $_GET['muted_segment'] ),
				'twchr-from-api_published_at' => sanitize_text_field( $_GET['published_at'] ),
				'twchr-from-api_stream_id' => sanitize_text_field( $_GET['stream_id'] ),
				'twchr-from-api_thumbnail_url' => sanitize_text_field( $_GET['thumbnail_url'] ),
				'twchr-from-api_type' => sanitize_text_field( $_GET['type'] ),
				'twchr-from-api_url' => sanitize_text_field( $_GET['url'] ),
				'twchr-from-api_user_id' => sanitize_text_field( $_GET['user_id'] ),
				'twchr-from-api_user_login' => sanitize_text_field( $_GET['user_login'] ),
				'twchr-from-api_user_name' => sanitize_text_field( $_GET['user_name'] ),
				'twchr-from-api_view_count' => sanitize_text_field( $_GET['view_count'] ),
				'twchr-from-api_viewble' => sanitize_text_field( $_GET['viewable'] ),
				'twchr-from-api_title' => sanitize_text_field( $_GET['title'] ),
			),
		)
	);

	twchr_javaScript_redirect( TWCHR_ADMIN_URL . '/post.php?post=' . $post_id . '&action=edit' );

}


?>
