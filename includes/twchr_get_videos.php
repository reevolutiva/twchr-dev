<?php 



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
                            // List de todos los post
                            $streams_id = explode(",",$_GET['streams_id']);
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
                    default:
                        # code...
                        break;
                }
            }
        }