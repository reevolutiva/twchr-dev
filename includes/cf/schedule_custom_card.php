<?php if ( get_post_type() === 'twchr_streams' ) :
	$twchr_streaming_states = wp_get_post_terms(get_the_ID(), 'twchr_streaming_states');	
?>
	<div class="twchr_custom_card--contain" <?php if(!str_contains($_SERVER['REQUEST_URI'],'post.php')) echo"style='min-height: auto;'";?>>
		<?php if(str_contains($_SERVER['REQUEST_URI'],'post.php')){ ?>
			<div class="twchr-tab-card-bar">
				<div class="twchr-tab-card twchr-tab-card-stream">
					<img src="<?php echo TWCHR_URL_ASSETS . 'twitcht_white.png'; ?>"" alt="twitch">
					<h3><?php _e( 'Schedule streamings and series', 'twitcher' ); ?></h3>
				</div>
				<div class="twchr-tab-card twchr-tab-card-embed disabled">
					<img src="<?php echo TWCHR_URL_ASSETS . 'twitcht_white.png'; ?>" alt="twitch">
					<img src="<?php echo TWCHR_URL_ASSETS . 'youtube_white.png'; ?>" alt="twitch">
					<h3><?php _e( 'Embed past streaming or VOD', 'twitcher' ); ?></h3>
				</div>
				
			</div>
			<div class="custom_card_row">
				<section class="silde-1">
					<?php require 'streaming_custom_tab1.php'; ?>
					<button class="twchr-btn-general" id="twchr-modal-schedule__btn"><?php twchr_esc_i18n( 'save', 'html' ); ?></button>
				</section>
				<section>
					<div class="twchr_card_embed_menu">
						<span><?php twchr_esc_i18n( 'Embed from', 'html' ); ?></span>
						<span>
							<input class="twchr_button_get_videos" type="radio" name="twchr-card-src-priority" id="twchr-card-src-priority--tw" value="tw" <?php echo $twchr_card_src_priority == 'tw' ? 'checked' : ''; ?> >
							<label for="twchr-card-src-priority--tw"><img src="<?php echo TWCHR_URL_ASSETS . 'twitch_logo.png'; ?>" alt=""><?php twchr_esc_i18n( 'Twitch', 'html' ); ?></label>
						</span>
						<span>
							<input type="radio" name="twchr-card-src-priority" id="twchr-card-src-priority--yt" value="yt" <?php echo $twchr_card_src_priority == 'yt' ? 'checked' : ''; ?>>
							<label for="twchr-card-src-priority--yt"><img src="<?php echo TWCHR_URL_ASSETS . 'youtube.png'; ?>" alt=""> <?php twchr_esc_i18n( 'Youtube', 'html' ); ?></label>
						</span>
					</div>
					<div class="silde-2">
						<?php require 'streaming_custom_tab2.php'; ?>
						<?php require 'streaming_custom_tab3.php'; ?>
						<button id="twchr-modal-selection__btn"><?php twchr_esc_i18n( 'Asign and Embed', 'html' ); ?></button>
					</div>
					
				</section>
			</div>
			<div class="twchr-card-img-footer">
				<img src="<?php echo TWCHR_URL_ASSETS . 'Isologo_twitcher.svg'; ?>" alt="logo twitcher">
			</div>
		<?php }else{
			echo "<h2>".__('Define a name for your streaming or video, and describe to your audience what they will see on it. On the next page, link your streaming with your platform','twitcher')."</h2>";			
		} ?>
		<script>
			<?php 
			if(str_contains($_SERVER['REQUEST_URI'],'post.php')){
				require 'script_streaming_single.js'; 
			}
			?>
		</script>
	</div>
	<?php
	if(str_contains($_SERVER['REQUEST_URI'],'post.php')){
		if(COUNT($twchr_streaming_states) > 0){
			$term = $twchr_streaming_states[0];
			$slug = $term->{'slug'};
			$color = '';
			switch ($slug) {
				case 'future':
					$color = 'var(--twchr-blue)';
					break;
				case 'live':
					$color = 'var(--twchr-green)';
					break;
				
				default:
					$color = 'var(--twchr-purple)';
					break;
			}
			echo "<h2 class='twchr_streaming_states_bage'>Status: <span style='background-color:".$color.";'>".$slug."</span></h2>";
		
		}
		require 'schedule_card_previw_card.php';
	}
	endif;
?>
