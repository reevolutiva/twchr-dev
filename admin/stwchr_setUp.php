<?php  
		$twchrKeysJSON = get_option('twitcher_keys');
		$clientId =  $twchrKeysJSON != false ? json_decode($twchrKeysJSON)->{'client-id'} : '';
		$clientSecret =  $twchrKeysJSON != false ? json_decode($twchrKeysJSON)->{'client-secret'} : '';
		
?>
<style>
		
</style>
<div class="twchr_settUp_overlay">
	<div id='twchr-setUp'>
		<div class="presentation">
				<h1><?php _e('Welcome to  ','twitcher');?> </h1>
				<img src="<?= plugins_url('/twitcher/includes/assets/Isologo_twitcher.svg')?>" alt="Logo Twitcher">
		</div>
		<section class="card-section">
			<h3>1</h3>
			<h4><?php _e('Go to Twitch Developers Console App Creation (Click here). ','twitcher');?></h4>
			<p>(Twitch credentials required).</p>
			<img src="<?= plugins_url('/twitcher/admin/setUp-img/setUp-image-1.jpg') ?>" alt="">
		</section>
		<section class="card-section">
			<h3>2</h3>
			<h4><?php _e('Set a name for your app (could be your Wordpress site name). ','twitcher');?></h4>
			<img src="<?= plugins_url('/twitcher/admin/setUp-img/setUp-image-2.jpg') ?>" alt="">
		</section>
		<section class="card-section">
			<h3>3</h3>
			<h4><?php _e('Set at “oAuth redirection url field” the follow Link:','twitcher');?> <span><?php _e('https site only','twitcher');?></span></h4>
			<p><a href="https://dev.egosapiens.net/wp-admin/edit.php?post_type=twchr_streams&page=twchr-settings">https://dev.egosapiens.net/wp-admin/edit.php?post_type=twchr_streams&page=twchr-settings</a></p>
			<img src="<?= plugins_url('/twitcher/admin/setUp-img/setUp-image-3.jpg') ?>" alt="">
		</section>
		<section class="card-section">
			<h3>4</h3>
			<h4><?php _e('Go to Cateogory and select “Others”, then resolve the captcha and press “Create”','twitcher');?></h4>
			<p></p>
			<img src="<?= plugins_url('/twitcher/admin/setUp-img/setUp-image-4.jpg') ?>" alt="">
		</section>
	</div>
</div>