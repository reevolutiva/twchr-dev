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

    <a href="<?php echo TWCHR_ADMIN_URL.'/post.php?post='.get_the_id().'&action=edit&twchr_insert_shorcode=ancho-800,alto-400'?>"><?php _e('Create a new shorcode','twitcher'); ?></a>

</metabox>

<?php 
if(isset($_GET['twchr_insert_shorcode'])){
    $post_id = get_the_id();
    update_post_meta($post_id, 'twchr_stream_src_priority', 'yt',);
    $array = explode(",",$_GET['twchr_insert_shorcode']);
    $shorcode = '[twich_embed ancho="'.explode('-',$array[0])[1].'" alto="'.explode('-',$array[1])[1].'"]';
    wp_update_post(array(
        'ID' => $post_id,
        'post_content' => $shorcode
    ));
    
    echo "<script>location.href='".TWCHR_ADMIN_URL."/post.php?post='".get_the_id()."'&action=edit'</script>";
}
?>