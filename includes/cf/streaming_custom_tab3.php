<?php 
$values    = get_post_custom( $_GET['post'] );
//show_dump($values);
$dateTime = isset($values['twchr_stream_data_dateTime'][0]) ? $values['twchr_stream_data_dateTime'][0] : '';
$dateTimeTwitch = isset($values['twchr-from-api_create_at'][0]) ? $values['twchr-from-api_create_at'][0] : false;
$yt_url = get_post_meta( get_the_ID(), 'twchr_streams__yt-link-video-src', true );
$select = isset($values['twchr_stream_src_priority']) ? $values['twchr_stream_src_priority'][0] : ''; 
?>
<metabox>
    <picture>
        <img src="<?php echo TWCHR_URL_ASSETS.'logo_menu.svg' ?>" alt="logo-twitch">
    </picture>
    <label>Fecha y hora del streming 
        <input <?php echo $dateTimeTwitch == false ? null : 'disabled'?>
            type="<?php echo $dateTimeTwitch == false ? "datetime-local" : "text"?>" name='twchr_stream_data_dateTime'
            value="<?php if($dateTimeTwitch){ twchr_esc_i18n($dateTimeTwitch,'html'); }else{ twchr_esc_i18n($dateTime,'html');}  ?>">
    </label>
    <label><?php twchr_esc_i18n('Source Priority','html'); ?></label>
    <select name="twchr_stream_src_priority">
        <option value="tw" <?php selected($select,'tw')?>>Twitch</option>
        <option value="yt" <?php selected($select,'yt')?>>Youtube</option>
    </select>
    
    <label>Youtbe URL <input type="text" name='twchr_streams__yt-link-video-src'
            value="<?php $yt_url != false ? twchr_esc_i18n($yt_url,'html') : ''?>"></label>

</metabox>