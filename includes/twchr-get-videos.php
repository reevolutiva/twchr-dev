<?php

// Boton sincronizar en edit

add_action( 'restrict_manage_posts', 'twchr_get_videos_function' );

function twchr_get_videos_function() {
	if(isset($_GET['post_type'])):
		$twch_data_prime = get_option( 'twchr_keys' ) == false ? false : json_decode( get_option( 'twchr_keys' ) );
		$twch_data_app_token = get_option( 'twchr_app_token' );
		$get_length = COUNT( $_GET );
		$dataUrl = sanitize_text_field( $_GET['post_type'] );
	
	if ( $dataUrl == 'twchr_streams' && $get_length == 1 ) :
		?>
<a style="text-decoration: none;display:inline-block;color:#fff;background-color: var(--twchr-purple);padding: .5em;border: 1px solid;border-radius: 5px;line-height: 1em;"
	href="<?php echo TWCHR_HOME_URL; ?>/wp-admin/edit.php?post_type=twchr_streams&get_thing=videos_ajax"><?php twchr_esc_i18n( 'Import Twitch Streamings', 'html' ); ?></a>
		<?php

	endif;
	if ( isset( $_GET['get_thing'] ) ) {
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

#twchr-modal-selection__btn {
	background-color: var(--twchr-purple);
	width: 107px;
	height: 30px;
	border: none;
	border-radius: 10px;
	color: #fff;
	font-size: 15px;
	display: block;
	filter: drop-shadow(0px 4px 4px rgba(0, 0, 0, 0.25))
}

.twchr-modal .twchr_help_button {
	display: block;
	width: 40px;
	height: 40px;
	background-image: url(<?php echo TWCHR_URL_ASSETS . 'help.png'; ?>);
	background-size: contain;
	background-repeat: no-repeat;
	margin-right: 6pt;
}
</style>
		<?php
		switch ( $_GET['get_thing'] ) {
			case 'videos':
				$twchr_post_streaming_a_crear_repetido = array();
				if ( $twch_data_prime != false || $twch_data_app_token != false ) {
					 // Extrago de la API un array con todos los videos publicados en la cuenta de twtch
					$data_broadcaster = get_option( 'twchr_data_broadcaster', false ) == false ? false : json_decode( get_option( 'twchr_data_broadcaster' ) );
					$broadcaster_id = $data_broadcaster->{'data'}[0]->{'id'};

					$list_videos = twchr_twitch_video_get( $twch_data_app_token, $twch_data_prime->{'client-id'}, $broadcaster_id );

					$list_videos_array = $list_videos->{'data'};
					// List de todos los post
					$streams_id = sanitize_text_field( $_GET['streams_id'] );
					$streams_id = explode( ',', $streams_id );
					$data_broadcaster = get_option( 'twchr_data_broadcaster', false ) == false ? false : json_decode( get_option( 'twchr_data_broadcaster' ) );
					$data_broadcaster = $data_broadcaster->data[0];
					$twitch_chanel = $data_broadcaster->login;

					while ( COUNT( $streams_id ) > 0 ) {
						$index = $streams_id[0];

						// Existe en BDD

						foreach ( $list_videos_array as $video ) {
							if ( $video->id === $index ) {
								$post_id = '';
								if ( twchr_validate_cf_db_exist( 'twchr-from-api_id', $index ) != false ) {
									$title_new = $video->title . __( ' (Duplicated)', 'twitcher' );
									$post_id = crearStream( $title_new, $video->id, $video->{'created_at'}, $video->{'description'}, $video->{'duration'}, $video->{'language'}, $video->{'muted_segments'}, $video->{'published_at'}, $video->{'stream_id'}, $video->{'thumbnail_url'}, $video->{'type'}, $video->{'url'}, $video->{'user_id'}, $video->{'user_login'}, $video->{'user_name'}, $video->{'view_count'}, $video->{'viewable'}, get_current_user_id(), $twitch_chanel );
								} else {
									$post_id = crearStream( $video->title, $video->id, $video->{'created_at'}, $video->{'description'}, $video->{'duration'}, $video->{'language'}, $video->{'muted_segments'}, $video->{'published_at'}, $video->{'stream_id'}, $video->{'thumbnail_url'}, $video->{'type'}, $video->{'url'}, $video->{'user_id'}, $video->{'user_login'}, $video->{'user_name'}, $video->{'view_count'}, $video->{'viewable'}, get_current_user_id(), $twitch_chanel );
								}

								wp_set_post_terms( $post_id, 'Past', 'twchr_streaming_states' );
							}
						}
						array_shift( $streams_id );

						if ( COUNT( $streams_id ) == 0 ) {
							echo "<script>location.href='" . TWCHR_ADMIN_URL . '/edit.php?post_type=twchr_streams' . "'</script>";
						}
					}
				}

				break;
			case 'videos_ajax':
				?>
							
							<stream id="twchr-modal-selection" class='twchr-modal 
							<?php
							if ( isset( $_GET['stream_id'] ) ) {
								echo 'disabled';}
							?>
							'>
								<div class="twchr-modal-selection_close">
									x
								</div>
								<div class="twchr-modal-selection__info">
									<h3><?php twchr_esc_i18n( 'Importing Twitch Videos to Streaming Post Tool', 'html' ); ?></h3>

									<picture>
										<img src="<?php echo TWCHR_URL_ASSETS . 'Isologo_twitcher.svg'; ?>" alt="logo-twitcher">
									</picture>
								</div>

								<div id="twchr-modal-selection__content">
									<ul class="twchr-modal-selection__list">
										<li><?php twchr_esc_i18n( 'Streaming name', 'html' ); ?></li>
										<li><?php twchr_esc_i18n( 'Date', 'html' ); ?></li>
										<li><?php twchr_esc_i18n( 'Already saved?', 'html' ); ?></li>
										<li><?php twchr_esc_i18n( 'Import', 'html' ); ?></li>
									</ul>
								</div>

								<div class="twchr-modal-footer">
									<span class="twchr_help_button">
										<p><?php twchr_esc_i18n( 'The following list is the avaible videos in your Twitch account. The videos whit “ok” marc are already saved as post type Streaming. Select te videos and press import button to create a new post for your video streaming.', 'html' ); ?>
										</p>
									</span>
									<button id="twchr-modal-selection__btn"><?php twchr_esc_i18n( 'Import', 'html' ); ?></button>
								</div>
							</stream>
							<script>
							const twchr_modal_error = document.querySelector("#twchr-modal-selection");
							const twchr_modal_error_button_close = document.querySelector(".twchr-modal-selection_close");

							twchr_modal_error_button_close.addEventListener('click', e => {
								twchr_modal_error.style.display = 'none';
								location.href = '<?php echo TWCHR_ADMIN_URL . '/edit.php?post_type=twchr_streams&'; ?>';
							});
							</script>
							<?php
				break;
			default:
				// code...
				break;
		}
	}
endif;
}
