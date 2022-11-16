<?php  
		
		if(isset($_GET['clearAll'])){
			delete_option('twchr_setInstaled');
			delete_option('twchr_keys');
			delete_option('twchr_app_token');
		}
		add_option('twchr_setInstaled',1,'',true );
		
		$twchrKeysJSON = get_option('twchr_keys');
		$clientId =  $twchrKeysJSON != false ? json_decode($twchrKeysJSON)->{'client-id'} : '';
		$clientSecret =  $twchrKeysJSON != false ? json_decode($twchrKeysJSON)->{'client-secret'} : '';
		
?>
<div class="twchr_settUp_overlay">
	<div id='twchr-setUp'>
		<div class="presentation">
				<h1><?php _e('Welcome to  ','twitcher');?> </h1>
				<img src="<?= plugins_url('/twitcher/includes/assets/Isologo_twitcher.svg')?>" alt="Logo Twitcher">
		</div>
		<section class="card-section">
			<h3>1</h3>
			<h4><?php _e('Go to ','twitcher');?><a target="new_black" href="https://dev.twitch.tv/console/apps/create"><?= __('Twitch Developers Console App Creation (click here)','twitcher'); ?></a></h4>
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
			<p><?= site_url('wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard') ?></p>
			<img src="<?= plugins_url('/twitcher/admin/setUp-img/setUp-image-3.jpg') ?>" alt="">
		</section>
		<section class="card-section">
			<h3>4</h3>
			<h4><?php _e('Go to Cateogory and select “Others”, then resolve the captcha and press “Create”','twitcher');?></h4>
			<img src="<?= plugins_url('/twitcher/admin/setUp-img/setUp-image-4.jpg') ?>" alt="">
		</section>
		<section class="card-section form">
			<h3>5</h3>
			<h4><?php _e('Get your Client-id and your Client-secret from your app and paste at the following forms','twitcher');?></h4>
			<form method='GET' action='https://<?=$_SERVER['SERVER_NAME']?>/wp-admin/edit.php'>
				<input type='hidden' name='post_type' value='twchr_streams'>
				<input type='hidden' name='page' value='twchr-dashboard'>
				<input type='hidden' name='from' value='setUp-plugin'>
				<label for="client-id">Client Id</label>
				<input id='client-id' type='text' placeholder='Client ID' name='client-id' value='<?= $clientId ?>'>
				<label for="client-secret">Client Secret</label>
				<input id='client-secret' type='password' placeholder='Client Secret' name='client-secret' value='<?= $clientSecret ?>'>								
				<input type='submit' value='sincronizar' name='sincronizar'>
			</form>
		</section>
	</div>
</div>

<?php 
	if(isset($_GET['error'])){
		?>
			<script>
				alert("Ups! Error: <?=$_GET['error']?>");
			</script>
		<?php
	}
?>