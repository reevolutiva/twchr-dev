<?php
$delete_all = get_option( 'twchr_delete_all' );
$delete_from_tw = get_option( 'twchr_set_clear_tw' );

if($delete_from_tw == false){
	add_option('twchr_set_clear_tw',0);
}

if ( isset( $_GET['setUpPage'] ) && $_GET['setUpPage'] == true ) {
	require_once 'twchr-set-up.php';
	die();
}


if ( isset( $_GET['twchr_set_clear_all'] ) ) {
	if ( $_GET['twchr_set_clear_all'] == 1 ) {
		update_option( 'twchr_delete_all', 1 );
		echo "<script>location.href='" . TWCHR_ADMIN_URL . '/edit.php?post_type=twchr_streams&page=twchr_help' . "'</script>";
	} else if ( $_GET['twchr_set_clear_all'] == 0 ) {
		update_option( 'twchr_delete_all', 0 );
		echo "<script>location.href='" . TWCHR_ADMIN_URL . '/edit.php?post_type=twchr_streams&page=twchr_help' . "'</script>";
	}
}
if ( isset( $_GET['twchr_set_clear_tw'] ) ) {
	if ( $_GET['twchr_set_clear_tw'] == 1 ) {
		update_option( 'twchr_set_clear_tw', 1 );
		echo "<script>location.href='" . TWCHR_ADMIN_URL . '/edit.php?post_type=twchr_streams&page=twchr_help' . "'</script>";
	} else if ( $_GET['twchr_set_clear_tw'] == 0 ) {
		update_option( 'twchr_set_clear_tw', 0 );
		echo "<script>location.href='" . TWCHR_ADMIN_URL . '/edit.php?post_type=twchr_streams&page=twchr_help' . "'</script>";
	}
}

// TODO: twchr_set_clear_tw == null mark off.

?>

<style>
	<?php include 'main_page.css'; ?>
</style>
<div class="twchr-for-back-broadcast container">
	<h1>Help</h1>
	<h3><?php twchr_esc_i18n( 'Mail your support requests to  contacto@twitcher.pro', 'html' ); ?></h3>
	<p><a href='https://twitcher.pro/'><?php twchr_esc_i18n( 'For more information visit https://twitcher.pro/', 'html' ); ?></a></p>
	</hr>
	<form action="./edit.php" method="get">
		<input type="hidden" name="post_type" value="twchr_streams">
		<input type="hidden" name="page" value="twchr_help">
		<p><?php twchr_esc_i18n( "Remove all WordpPress's data on uninstall", 'html' ); ?></p>
		<label>
			on
			<input type="radio" name="twchr_set_clear_all" value="1" <?php echo $delete_all !== false && $delete_all == 1 ? 'checked' : ''; ?>>
		</label>
		<label>
			off
			<input type="radio" name="twchr_set_clear_all" value="0" <?php echo $delete_all !== false && $delete_all == 0 ? 'checked' : ''; ?>>
		</label>
		<hr/>
		<p><?php twchr_esc_i18n( "Remove Twitch's schedules on delete Wordpress's serie", "html" ); ?></p>
		<label>
			on
			<input type="radio" name="twchr_set_clear_tw" value="1" <?php echo $delete_from_tw !== false && $delete_from_tw == 1 ? 'checked' : ''; ?>>
		</label>
		<label>
			off
			<input type="radio" name="twchr_set_clear_tw" value="0" <?php echo $delete_from_tw !== false && $delete_from_tw == 0 ? 'checked' : ''; ?>>
		</label>
		<input style="margin-top: 10px; display:block;" type="submit" value="<?php twchr_esc_i18n( 'save', 'html' ); ?>">
	</form> 
	<h3>Setup</h3>
	<a class="twchr-btn-general" href="<?php echo TWCHR_ADMIN_URL . '/edit.php?post_type=twchr_streams&page=twchr_help&setUpPage=true'; ?>">Run Setup</a>  
</div>

<?php
