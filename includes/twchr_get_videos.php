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
    $dataUrl = sanitize_url($_SERVER['REQUEST_URI']);
    $dataUrl = explode('/',$dataUrl)[2];
   
    if(
    // Si la url contiene "post-new.php" y "post_type=twchr_streams"
    (str_contains( $dataUrl, 'post-new.php') && $_GET['post_type'] == 'twchr_streams') ||
     // Si la url contiene "post.php" y "action=edit"
    (str_contains( $dataUrl, 'post.php') && $_GET['action'] == 'edit' && get_post_type() === 'twchr_streams')
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
                background-image: url(<?php echo TWCHR_URL_ASSETS.'help.png'?>);
                background-size: contain;
                background-repeat: no-repeat;
                margin-right: 6pt;
            }

        </style>
        <a class="twchr_button_get_videos"'
            href="<?php twchr_esc_i18n(TWCHR_HOME_URL.$_SERVER['REQUEST_URI'],'html')  ?>"><?php twchr_esc_i18n('Asign Twitch Streaming','html')?></a>

        <stream class="twchr_modal_get_videos twchr-modal">
            <div class="twchr-modal-selection_close">
                x
            </div>
            <div class="twchr-modal-selection__info">
                <h3><?php twchr_esc_i18n('Asign video to post','html') ?></h3>

                <picture>
                    <img src="<?php echo TWCHR_URL_ASSETS.'Isologo_twitcher.svg';?>" alt="logo-twitcher">
                </picture>
            </div>

            <div id="twchr_button_get_videos__content">
                <ul class="twchr-modal-selection__list">
                    <li><?php twchr_esc_i18n('Streaming name','html'); ?></li>
                    <li><?php twchr_esc_i18n('Date','html'); ?></li>
                    <li><?php twchr_esc_i18n('Already saved?','html'); ?></li>
                    <li><?php twchr_esc_i18n('Import','html'); ?></li>
                </ul>
                <div class="content">

                </div>
            </div>

            <div class="twchr-modal-footer">
                <span class="twchr_help_button">
                    <p><?php twchr_esc_i18n('The folowing list is the avaiable videos in your twitch account. Select the video that you want to asign to this post.','html'); ?>
                    </p>
                    
                </span>
                <button id="twchr-modal-selection__btn"><?php twchr_esc_i18n('Asign','html');?></button>
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
           
    $twch_data_app_token = get_option('twchr_app_token');
    $get_length = COUNT($_GET);
    $dataUrl = $_GET['post_type'];
    $dataUrl = sanitize_text_field($dataUrl);
    if($dataUrl == 'twchr_streams' && $get_length == 1):
        ?>
<a style="text-decoration: none;display:inline-block;color:#fff;background-color: var(--twchr-purple);padding: .5em;border: 1px solid;border-radius: 5px;line-height: 1em;"
    href="<?php echo  TWCHR_HOME_URL;?>/wp-admin/edit.php?post_type=twchr_streams&get_thing=videos_ajax"><?php twchr_esc_i18n('Import Twitch Streamings','html')?></a>
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
    background-image: url(<?php echo TWCHR_URL_ASSETS.'twchr_check.png';?>);
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
    background-image: url(<?php echo TWCHR_URL_ASSETS.'help.png';?>);
    background-size: contain;
    background-repeat: no-repeat;
    margin-right: 6pt;
}
</style>
<?php
                switch ($_GET['get_thing']) {
                    case 'videos':
                        $twchr_post_streaming_a_crear_repetido = array();
                        if($twch_data_prime != false || $twch_data_app_token != false){
                             // Extrago de la API un array con todos los videos publicados en la cuenta de twtch
                            $data_broadcaster = get_option( 'twchr_data_broadcaster', false ) == false ?  false :  json_decode(get_option( 'twchr_data_broadcaster'));
                            $broadcaster_id = $data_broadcaster->{'data'}[0]->{'id'};

                            $list_videos = twchr_get_twicth_video($twch_data_app_token, $twch_data_prime->{'client-id'},$broadcaster_id);
           
                            $list_videos_array = $list_videos->{'data'};
                            // List de todos los post
                            $streams_id = sanitize_text_field($_GET['streams_id']);
                            $streams_id = explode(",",$streams_id);
                            $data_broadcaster = get_option( 'twchr_data_broadcaster', false ) == false ?  false :  json_decode(get_option( 'twchr_data_broadcaster'));
                            $data_broadcaster = $data_broadcaster->data[0];
                            $twitch_chanel = $data_broadcaster->login;
                                
                            while(COUNT($streams_id)  > 0){
                                $index = $streams_id[0];

                                // Existe en BDD
                                                            
                                foreach($list_videos_array as $video){
                                    if($video->id === $index){
                                        if(twchr_cf_db_exist('twchr-from-api_id',$index) != false){
                                            $title_new = $video->title.__(' (Duplicated)','twitcher'); 
                                            crearStream($title_new ,$video->id ,$video->{'created_at'} ,$video->{'description'} ,$video->{'duration'} ,$video->{'language'} ,$video->{'muted_segment'} ,$video->{'published_at'} ,$video->{'stream_id'} ,$video->{'thumbnail_url'} ,$video->{'type'} ,$video->{'url'} ,$video->{'user_id'} ,$video->{'user_login'} ,$video->{'user_name'} ,$video->{'view_count'} ,$video->{'viewable'}, get_current_user_id(),$twitch_chanel); 
                                        }else{              
                                            crearStream($video->title ,$video->id ,$video->{'created_at'} ,$video->{'description'} ,$video->{'duration'} ,$video->{'language'} ,$video->{'muted_segment'} ,$video->{'published_at'} ,$video->{'stream_id'} ,$video->{'thumbnail_url'} ,$video->{'type'} ,$video->{'url'} ,$video->{'user_id'} ,$video->{'user_login'} ,$video->{'user_name'} ,$video->{'view_count'} ,$video->{'viewable'}, get_current_user_id(),$twitch_chanel);
                                        }    
                                            
                                    }
                                }
                                array_shift($streams_id);
                                
                                if(COUNT($streams_id) ==  0){
                                    echo "<script>location.href='".site_url('/wp-admin/edit.php?post_type=twchr_streams')."'</script>";
                                }                                
                            }
                            
                        }else{
                            //wp_redirect(site_url('/wp-admin/edit.php?post_type=twchr_streams&page=twchr_help&setUpPage=true'));
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
                                    <h3><?php twchr_esc_i18n('Importing Twitch Videos to Streaming Post Tool','html') ?></h3>

                                    <picture>
                                        <img src="<?php echo TWCHR_URL_ASSETS.'Isologo_twitcher.svg';?>" alt="logo-twitcher">
                                    </picture>
                                </div>

                                <div id="twchr-modal-selection__content">
                                    <ul class="twchr-modal-selection__list">
                                        <li><?php twchr_esc_i18n('Streaming name','html'); ?></li>
                                        <li><?php twchr_esc_i18n('Date','html'); ?></li>
                                        <li><?php twchr_esc_i18n('Already saved?','html'); ?></li>
                                        <li><?php twchr_esc_i18n('Import','html'); ?></li>
                                    </ul>
                                </div>

                                <div class="twchr-modal-footer">
                                    <span class="twchr_help_button">
                                        <p><?php twchr_esc_i18n('The following list is the avaible videos in your Twitch account. The videos whit “ok” marc are already saved as post type Streaming. Select te videos and press import button to create a new post for your video streaming.','html'); ?>
                                        </p>
                                    </span>
                                    <button id="twchr-modal-selection__btn"><?php twchr_esc_i18n('Import','html');?></button>
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
                    default:
                        # code...
                        break;
                }
            }
        }