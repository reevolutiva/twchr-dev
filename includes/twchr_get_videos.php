<?php 

// Boton de sincronizar para pestaña todos los streams

add_action('submitpost_box','twchr_get_videos_function_edit');

function twchr_get_videos_function_edit(){
    $twch_data_prime = get_option('twchr_keys') == false ? false : json_decode(get_option('twchr_keys'));
    $data_broadcaster = get_option( 'twchr_data_broadcaster', false ) == false ?  false :  json_decode(get_option( 'twchr_data_broadcaster'));
    $broadcaster_id = $data_broadcaster->{'data'}[0]->{'id'};
    $twch_data_app_token = get_option('twchr_app_token');
    // domain.net/wp-admin/post-new.php
    // Divide la url por sus "/" y escoje el ultimo item
    $dataUrl = explode('/',$_SERVER['REQUEST_URI'])[2];
    if(
    // Si la url contiene "post-new.php" y "post_type=twchr_streams"
    (str_contains( $dataUrl, 'post-new.php') && $_GET['post_type'] == 'twchr_streams') ||
     // Si la url contiene "post.php" y "action=edit"
    (str_contains( $dataUrl, 'post.php') && $_GET['action'] == 'edit')
    ):
        ?>
<style>
a.twchr_button_get_videos {
    text-decoration: none;
    padding: 5px 10px;
    display: block;
    margin-bottom: 10pt;
    width: max-content;
    border-radius: 5px;
    background-color: var(--twchr-purple);
    color: #fff;
}

stream.twchr_modal_get_videos{
    display: none;
}

stream.twchr_modal_get_videos.active {
    z-index: 10000;
    right: 19px;
    box-shadow: 0 0 3px rgba(0, 0, 0, .5);
    opacity: 1;
    display: block;
}

.twchr-modal .twchr_help_button {
    display: block;
    width: 40px;
    height: 40px;
    background-image: url(<?= plugins_url('twitcher/includes/assets/help.png')?>);
    background-size: contain;
    background-repeat: no-repeat;
    margin-right: 6pt;
}

</style>
<a class="twchr_button_get_videos"
    href="<?= bloginfo('url').$_SERVER['REQUEST_URI']?>"><?php _e('Asign Twitch Streaming','twitcher')?></a>

<stream class="twchr_modal_get_videos twchr-modal">
    <div class="twchr-modal-selection_close">
        x
    </div>
    <div class="twchr-modal-selection__info">
        <h3><?php _e('Asign video to post','twitcher') ?></h3>

        <picture>
            <img src="<?= plugins_url('/twitcher/includes/assets/Isologo_twitcher.svg')?>" alt="logo-twitcher">
        </picture>
    </div>

    <div id="twchr_button_get_videos__content">
        <ul class="twchr-modal-selection__list">
            <li><?= __('Streaming name','twitcher'); ?></li>
            <li><?= __('Date','twitcher'); ?></li>
            <li><?= __('Already saved?','twitcher'); ?></li>
            <li><?= __('Import','twitcher'); ?></li>
        </ul>
        <div class="content">

        </div>
    </div>

    <div class="twchr-modal-footer">
        <span class="twchr_help_button">
            <p><?php _e('The folowing list is the avaiable videos in your twitch account. Select the video that you want to asign to this post.','twitcher'); ?>
            </p>
            
        </span>
        <button id="twchr-modal-selection__btn"><?= __('Asign','twitcher');?></button>
    </div>
</stream>
<script>
const twchr_modal = document.querySelector(".twchr_modal_get_videos.twchr-modal");
const twchr_modal_button_close = document.querySelector(".twchr_modal_get_videos.twchr-modal .twchr-modal-selection_close");

twchr_modal_button_close.addEventListener('click', e => {
    twchr_modal.classList.remove('active');
});
</script>
<?php
        endif; 
            
        }


// Boton sincronizar en edit        

add_action('restrict_manage_posts','twchr_get_videos_function');

function twchr_get_videos_function(){
    $twch_data_prime = get_option('twchr_keys') == false ? false : json_decode(get_option('twchr_keys'));
            //$twch_data_prime_lengt = count($twch_data_prime);
    $twch_data_app_token = get_option('twchr_app_token');
    $get_length = COUNT($_GET);
    $dataUrl = $_GET['post_type'];
    if($dataUrl == 'twchr_streams' && $get_length == 1):
        ?>
<a style="text-decoration: none;display:inline-block;color:#fff;background-color: var(--twchr-purple);padding: .5em;border: 1px solid;border-radius: 5px;line-height: 1em;"
    href="<?= bloginfo('url');?>/wp-admin/edit.php?post_type=twchr_streams&get_thing=videos_ajax"><?php _e('Import Twitch Streamings','twitcher')?></a>
<?php   
        
    endif; 
            if(isset($_GET['get_thing'])){
                ?>
<style>
.twchr_modal_video_ajax span.video-saved {
    display: block;
    width: 25px;
    height: 100%;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    background-image: url(<?= plugins_url('twitcher/includes/assets/twchr_check.png')?>);
    margin: 0;
}

#twchr-modal-selection__btn {
    background-color: var(--twchr-purple);
    width: 107px;
    height: 30px;
    border: none;
    border-radius: 10px;
    color: #fff;
    font-size: 15px;
    display: block;
    filter: drop-shadow(0px 4px 4px rgba(0, 0, 0, 0.25))
}

.twchr-modal .twchr_help_button {
    display: block;
    width: 40px;
    height: 40px;
    background-image: url(<?= plugins_url('twitcher/includes/assets/help.png')?>);
    background-size: contain;
    background-repeat: no-repeat;
    margin-right: 6pt;
}
</style>
<?php
                switch ($_GET['get_thing']) {
                    case 'videos':
                        $post_streaming_a_crear_repetido = array();
                        if($twch_data_prime != false || $twch_data_app_token != false){
                             // Extrago de la API un array con todos los videos publicados en la cuenta de twtch
                            $data_broadcaster = get_option( 'twchr_data_broadcaster', false ) == false ?  false :  json_decode(get_option( 'twchr_data_broadcaster'));
                            $broadcaster_id = $data_broadcaster->{'data'}[0]->{'id'};

                            $list_videos = get_twicth_video($twch_data_app_token, $twch_data_prime->{'client-id'},$broadcaster_id);
                            //show_dump($list_videos);
                            $list_videos_array = $list_videos->{'data'};
                            /*$streamig_post_raw = new WP_Query($args);*/
                            // List de todos los post
                            /*$streamig_post = $streamig_post_raw->{'posts'};*/
                            $streams_id = explode(",",$_GET['streams_id']);
                            $data_broadcaster_raw = get_option( 'twchr_data_broadcaster', false ) == false ?  false :  json_decode(get_option( 'twchr_data_broadcaster'));
                            $twitch_chanel = $data_broadcaster_raw->login;
                            
                            while(COUNT($streams_id)  > 0){
                                $index = $streams_id[0];

                                // Existe en BDD
                                if(twchr_cf_db_exist('twchr-from-api_id',$index) != false){
                                    foreach($list_videos_array as $video){
                                        if($video->id === $index){
                                            $title_new = $video->title.__(' (Duplicated)','twitcher'); 
                                            crearStream($title_new ,$video->id ,$video->{'created_at'} ,$video->{'description'} ,$video->{'duration'} ,$video->{'language'} ,$video->{'muted_segment'} ,$video->{'published_at'} ,$video->{'stream_id'} ,$video->{'thumbnail_url'} ,$video->{'type'} ,$video->{'url'} ,$video->{'user_id'} ,$video->{'user_login'} ,$video->{'user_name'} ,$video->{'view_count'} ,$video->{'viewable'}, get_current_user_id(),$twitch_chanel);
                                        }
                                    }
                                    echo "<script> location.href='".site_url('/wp-admin/edit.php?post_type=twchr_streams')."'</script>";
                                                                     
                                    break;
                                }
                                
                                
                                foreach($list_videos_array as $video){
                                    if($video->id === $index){
                                            $video_exist = twittcher_getData('wp_postmeta','twchr-from-api_id', $video->id);
                                           
                                            if($video_exist === false){
                                                crearStream($video->title ,$video->id ,$video->{'created_at'} ,$video->{'description'} ,$video->{'duration'} ,$video->{'language'} ,$video->{'muted_segment'} ,$video->{'published_at'} ,$video->{'stream_id'} ,$video->{'thumbnail_url'} ,$video->{'type'} ,$video->{'url'} ,$video->{'user_id'} ,$video->{'user_login'} ,$video->{'user_name'} ,$video->{'view_count'} ,$video->{'viewable'}, get_current_user_id(),$twitch_chanel);
                                            }
                                            
                                    }
                                }
                                array_shift($streams_id);
                                
                                if(COUNT($streams_id) ==  0){
                                    echo "<script>location.href='".site_url('/wp-admin/edit.php?post_type=twchr_streams&streams_id=')."'</script>";
                                }                                
                            }
                            
                        }else{
                            wp_redirect(site_url('/twttcher-setup'));
                            exit;
                        }
                        
                       
                        
                        

                        break;
                    case 'videos_ajax':
                         ?>
<stream id="twchr-modal-selection" class='twchr-modal <?php if(isset($_GET['stream_id'])) echo "disabled";?>'>
    <div class="twchr-modal-selection_close">
        x
    </div>
    <div class="twchr-modal-selection__info">
        <h3><?php _e('Importing Twitch Videos to Streaming Post Tool','twitcher') ?></h3>

        <picture>
            <img src="<?= plugins_url('/twitcher/includes/assets/Isologo_twitcher.svg')?>" alt="logo-twitcher">
        </picture>
    </div>

    <div id="twchr-modal-selection__content">
        <ul class="twchr-modal-selection__list">
            <li><?= __('Streaming name','twitcher'); ?></li>
            <li><?= __('Date','twitcher'); ?></li>
            <li><?= __('Already saved?','twitcher'); ?></li>
            <li><?= __('Import','twitcher'); ?></li>
        </ul>
    </div>

    <div class="twchr-modal-footer">
        <span class="twchr_help_button">
            <p><?php _e('The following list is the avaible videos in your Twitch account. The videos whit “ok” marc are already saved as post type Streaming. Select te videos and press import button to create a new post for your video streaming.','twitcher'); ?>
            </p>
        </span>
        <button id="twchr-modal-selection__btn"><?= __('Import','twitcher');?></button>
    </div>
</stream>
<script>
const twchr_modal_error = document.querySelector("#twchr-modal-selection");
const twchr_modal_error_button_close = document.querySelector(".twchr-modal-selection_close");

twchr_modal_error_button_close.addEventListener('click', e => {
    twchr_modal_error.style.display = 'none';
    location.href = '<?php echo site_url('/wp-admin/edit.php?post_type=twchr_streams&')?>';
});
</script>
<?php
                        break;
                    case 'videos_update':
                        if(isset($_GET['json'])){
                            $postarr = array(
                                'post_title' => $_GET['title'],
                                'post_type' => 'twchr_streams',
                                'post_content' => '[twich_embed host="reevolutiva" video="'.$id.'"  ancho="800" alto="400"]',
                                'meta_input'   => array(
                                    'twchr-from-api_create_at' => $_GET["created_at"],
                                    'twchr-from-api_duration' => $_GET["duration"],
                                    'twchr-from-api_id' => $_GET["id"],
                                    'twchr-from-api_languaje' => $_GET["language"],
                                    'twchr-from-api_muted_segment' => $_GET["muted_segments"],
                                    'twchr-from-api_published_at' => $_GET["published_at"],
                                    'twchr-from-api_stream_id' => $_GET["stream_id"],
                                    'twchr-from-api_thumbnail_url' => $_GET["thumbnail_url"],
                                    'twchr-from-api_type' => $_GET["type"],
                                    'twchr-from-api_url' => $_GET["url"],
                                    'twchr-from-api_user_id' => $_GET["user_id"],
                                    'twchr-from-api_user_login' => $_GET["user_login"],
                                    'twchr-from-api_user_name' => $_GET["user_name"],
                                    'twchr-from-api_view_count' => $_GET["view_count"],
                                    'twchr-from-api_viewble' => $_GET["viewble"],
                                )
                            );
                            $update = wp_insert_post( $postarr, true, true);
                            if($update){
                                ?>
<stream id="twchr-modal-error" class='twchr-modal'>
    <h3>'Post <?=$_GET['title']?>'</h3>
    <h4 style="text-align:center;"><?= __('Streaming upgraded successfully.'); ?></h4>
    <div class='twchr-modal-button close'>Ok</div>
</stream>
<script>
const twchr_modal_error = document.querySelector("#twchr-modal-error");
const twchr_modal_error_button_close = document.querySelector("#twchr-modal-error .twchr-modal-button.close");
twchr_modal_error_button_close.addEventListener('click', e => {
    twchr_modal_error.style.display = 'none';
    location.href = '<?php echo site_url('/wp-admin/edit.php?post_type=twchr_streams&')?>';
});
</script>
<?php
                            }
                        }
                         
                        break;
                    default:
                        # code...
                        break;
                }
            }
        }