<?php if ( get_post_type() === 'twchr_streams' ) : ?>
	<div class="twchr_custom_card--contain">
		<div class="twchr-tab-card-bar">
			<div class="twchr-tab-card twchr-tab-card-stream">
				<img src="<?php echo TWCHR_URL_ASSETS . 'twitcht_white.png'; ?>"" alt="twitch">
				<h3><?php _e( 'Schedule streaming', 'twitcher' ); ?></h3>
			</div>
			<div class="twchr-tab-card twchr-tab-card-embed">
				<img src="<?php echo TWCHR_URL_ASSETS . 'twitcht_white.png'; ?>" alt="twitch">
				<img src="<?php echo TWCHR_URL_ASSETS . 'youtube_white.png'; ?>" alt="twitch">
				<h3 class="twchr_button_get_videos"><?php _e( 'Embed Streaming or VOD', 'twitcher' ); ?></h3>
			</div>
			
		</div>
		<div class="custom_card_row">
			<section>
				<?php require_once 'streaming_custom_tab1.php'; ?>
			</section>
			<section>
				<div class="twchr_card_embed_menu">
					<span><?php twchr_esc_i18n('Embed from','html');?></span>
					<span>
						<input type="radio" name="twchr-card-src-priority" id="twchr-card-src-priority--yt" value="tw">
						<label for="twchr-card-src-priority--yt"><img src="<?php echo TWCHR_URL_ASSETS . 'twitch_logo.png'; ?>" alt=""><?php twchr_esc_i18n('Twitch','html');?></label>
					</span>
					<span>
						<input type="radio" name="twchr-card-src-priority" id="twchr-card-src-priority--tw" value="yt">
						<label for="twchr-card-src-priority--tw""><img src="<?php echo TWCHR_URL_ASSETS . 'youtube.png'; ?>" alt=""> <?php twchr_esc_i18n('Youtube','html');?></label>
					</span>
				</div>
				<?php require_once 'streaming_custom_tab2.php'; ?>
				<?php require_once 'streaming_custom_tab3.php'; ?>
			</section>
		</div>
		<img src="<?php echo TWCHR_URL_ASSETS . 'Isologo_twitcher.svg'; ?>" alt="logo twitcher" class="twchr-card-img-footer">
		<script>
			<?php require 'script_streaming_single.js'; ?>
		</script>
	</div>
	<?php
	require_once 'schedule_card_previw_card.php';
	endif;
?>
