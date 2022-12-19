<?php 
$values    = get_post_custom( $_GET['post'] );

$yt_url = get_post_meta( get_the_ID(), 'twchr_streams__yt-link-video-src', true );
$select = isset($values['twchr_stream_src_priority']) ? $values['twchr_stream_src_priority'][0] : ''; 
?>
<metabox>
    <picture>
        <img src="<?php echo TWCHR_URL_ASSETS.'logo_menu.svg' ?>" alt="logo-twitch">
    </picture>
    <label>Fecha y hora del streming:  <?php echo $dateTime; ?> </label>
    <label><?php twchr_esc_i18n('Source Priority','html'); ?></label>
    <select name="twchr_stream_src_priority">
        <option value="tw" <?php selected($select,'tw')?>>Twitch</option>
        <option value="yt" <?php selected($select,'yt')?>>Youtube</option>
    </select>
    
    <label>Youtbe URL <input type="text" name='twchr_streams__yt-link-video-src'
            value="<?php $yt_url != false ? twchr_esc_i18n($yt_url,'html') : ''?>"></label>

</metabox>