<?php 
$values    = get_post_custom(get_the_id());

$yt_url = get_post_meta( get_the_ID(), 'twchr_streams__yt-link-video-src', true );
$select = isset($values['twchr_stream_src_priority']) ? $values['twchr_stream_src_priority'][0] : '';
$youtubeData = get_post_meta(get_the_ID(),'twchr-from-api_create_at')[0];
$youtubeData == false || $youtubeData == '' ? $youtubeData = $dateTime : ''; 
?>
<metabox>
    <label>Date:  <?php echo $youtubeData; ?> </label>
        
    <label>Youtbe URL <input type="text" name='twchr_streams__yt-link-video-src'
            value="<?php $yt_url != false ? twchr_esc_i18n($yt_url,'html') : ''?>"></label>

    <a href="<?php echo TWCHR_ADMIN_URL.'/post.php?post='.get_the_id().'&action=edit&twchr_insert_shorcode=ancho-800,alto-400'?>"><?php _e('Insert shorcode','twitcher'); ?></a>

</metabox>

<?php 
if(isset($_GET['twchr_insert_shorcode'])){
    $post_id = get_the_id();
    $array = explode(",",$_GET['twchr_insert_shorcode']);
    $alto = explode('-',$array[1])[1];
    $ancho = explode('-',$array[0])[1];
    $shorcode = '[twchr_yt_video_embed ancho="'.$ancho.'" alto="'.$alto.'"]';
    wp_update_post(array(
        'ID' => $post_id,
        'post_content' => $shorcode
    ));
    $url = TWCHR_ADMIN_URL."/post.php?post=".get_the_id()."&action=edit";
    echo "<script>location.href='$url'</script>";
    die();

    
}
?>