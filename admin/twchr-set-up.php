<?php

if ( isset( $_GET['clearAll'] ) ) {
	delete_option( 'twchr_set_instaled' );
	delete_option( 'twchr_keys' );
	delete_option( 'twchr_app_token' );
	delete_option( 'twchr_data_broadcaster' );
}
		add_option( 'twchr_set_instaled', 1, '', true );

		$twchr_keys_json = get_option( 'twchr_keys' );
		$client_id = $twchr_keys_json != false ? json_decode( $twchr_keys_json )->{'client-id'} : '';
		$client_secret = $twchr_keys_json != false ? json_decode( $twchr_keys_json )->{'client-secret'} : '';
		$user_email = wp_get_current_user()->{'user_email'};

?>
<div class="twchr_settUp_overlay">
	<div id='twchr-setUp'>
		<div class="presentation">
				<h1><?php twchr_esc_i18n( 'Welcome to  ', 'html' ); ?> </h1>
				<img src="<?php echo TWCHR_URL_ASSETS . 'Isologo_twitcher.svg'; ?>" alt="Logo Twitcher">
		</div>
	
		<section class="card-section">
			<h3>1</h3>
			<h4><?php twchr_esc_i18n( 'Go to ', 'html' ); ?><a target="new_black" href="https://dev.twitch.tv/console/apps/create"><?php twchr_esc_i18n( 'Twitch Developers Console App Creation (click here)', 'html' ); ?></a></h4>
			<p>(Twitch credentials required).</p>
			<img src="<?php echo TWCHR_SETUP_ASSETS . '/setUp-image-1.jpg'; ?>" alt="">
		</section>
		<section class="card-section">
			<h3>2</h3>
			<h4><?php twchr_esc_i18n( 'Set a name for your app (could be your Wordpress site name). ', 'html' ); ?></h4>
			<img src="<?php echo TWCHR_SETUP_ASSETS . '/setUp-image-2.jpg'; ?>" alt="">
		</section>
		<section class="card-section">
			<h3>3</h3>
			<h4><?php twchr_esc_i18n( 'Set at “oAuth redirection url field” the follow Link:', 'html' ); ?> <span><?php twchr_esc_i18n( 'https site only', 'html' ); ?></span></h4>
			<p><?php echo TWCHR_ADMIN_URL . 'edit.php?post_type=twchr_streams&page=twchr-dashboard'; ?></p>
			<img src="<?php echo TWCHR_SETUP_ASSETS . '/setUp-image-3.jpg'; ?>" alt="">
		</section>
		<section class="card-section">
			<h3>4</h3>
			<h4><?php twchr_esc_i18n( 'Go to Cateogory and select “Others”, then resolve the captcha and press “Create”', 'html' ); ?></h4>
			<img src="<?php echo TWCHR_SETUP_ASSETS . '/setUp-image-4.jpg'; ?>" alt="">
		</section>
		<section class="card-section form">
			<h3>5</h3>
			<h4><?php twchr_esc_i18n( 'Get your Client-id and your Client-secret from your app and paste at the following forms', 'html' ); ?></h4>
			<form method='GET' action='<?php echo TWCHR_HOME_URL; ?>/wp-admin/edit.php'>
				<input type='hidden' name='post_type' value='twchr_streams'>
				<input type='hidden' name='page' value='twchr-dashboard'>
				<input type='hidden' name='from' value='setUp-plugin'>
				<label for="client-id">Client Id</label>
				<input id='client-id' type='text' placeholder='Client ID' name='client-id' value='<?php echo $client_id; ?>'>
				<label for="client-secret">Client Secret</label>
				<input id='client-secret' type='password' placeholder='Client Secret' name='client-secret' value='<?php echo $client_secret; ?>'>						
				<div class="row-input">
					<input type="checkbox" name="twchr_share_twitch_data" id="twchr_share_twitch_data" checked>
					<label for="twchr_share_twitch_data"><?php _e( 'Help Twitcher to improve sharing your public Twitch data anonymously', 'twitcher' ); ?></label>
				</div>		
				<div class="row-input">
					<input type="checkbox" name="twchr_share_crm_data" id="twchr_share_crm_data" checked>
					<label for="twchr_share_crm_data">
					<?php
					_e( 'Keepme informed about Twitcher’s features and news on ', 'twitcher' );
					echo $user_email;
					_e( ' (optional)', 'twitcher' );
					?>
					</label>
				</div>
				<div class="footer-row">
					<input type='submit' value='<?php _e( 'Finish Installation', 'twitcher' ); ?>' name='sincronizar'>
					<p><?php _e( 'I Acept Twitcher ', 'twitcher' ); ?> <a href="https://twitcher.pro/privacy-policy/"><?php _e( 'terms & conditions', 'twicher' ); ?></a></p>
				</div>	
			</form>
			
		</section>
	</div>
</div>

<?php
if ( isset( $_GET['error'] ) ) {
	?>
			<script>
				alert("Ups! Error: <?php twchr_esc_i18n( $_GET['error'], '' ); ?>");
			</script>
		<?php
}
?>
