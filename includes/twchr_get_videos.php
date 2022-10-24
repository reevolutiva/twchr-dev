<?php 

// Boton de sincronizar para pestaña todos los streams

add_action('submitpost_box','twchr_get_videos_function_edit');

function twchr_get_videos_function_edit(){
    $twch_data_prime = get_option('twitcher_keys') == false ? false : json_decode(get_option('twitcher_keys'));
    $data_broadcaster = get_option( 'twchr_data_broadcaster', false ) == false ?  false :  json_decode(get_option( 'twchr_data_broadcaster'));
    $broadcaster_id = $data_broadcaster->{'data'}[0]->{'id'};
    $twch_data_app_token = get_option('twitcher_app_token');
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
            a.twchr_button_get_videos{
                text-decoration: none;
                padding: 5px 10px;
                display: block;
                margin-bottom: 10pt;
                width: max-content;
                border-radius: 5px;
                background-color: var(--twchr-purple);
                color: #fff;
            }
            modal.twchr_modal_get_videos{
                transition:.5s ease-in-out ;
                position: absolute;
                opacity:0;
            }
            modal.twchr_modal_get_videos.active{
                width: 500px;
                height: 300px;
                background-color: #fff;
                z-index: 10000;
                right: 19px;
                box-shadow:0 0 3px rgba(0,0,0,.5);
                opacity:1;
                display: block;
            }

            modal .tchr_modal_list{
                list-none:none;
            }
            modal .tchr_modal_list li span:after{
                content:'x';
            }
        </style>
        <a class="twchr_button_get_videos" href="<?= bloginfo('url').$_SERVER['REQUEST_URI']?>"><?php _e('Asign Twitch Streaming','twitcher')?></a>
            
        <modal class="twchr_modal_get_videos">
    
        </modal>
        <?php
        endif; 
            
        }


// Boton sincronizar en edit        

add_action('restrict_manage_posts','twchr_get_videos_function');

function twchr_get_videos_function(){
    $twch_data_prime = get_option('twitcher_keys') == false ? false : json_decode(get_option('twitcher_keys'));
            //$twch_data_prime_lengt = count($twch_data_prime);
    $twch_data_app_token = get_option('twitcher_app_token');
    $get_length = COUNT($_GET);
    $dataUrl = $_GET['post_type'];
    if($dataUrl == 'twchr_streams' && $get_length == 1):
        ?>
        <a style="text-decoration: none;display:inline-block;color:#fff;background-color: var(--twchr-purple);padding: .5em;border: 1px solid;border-radius: 5px;line-height: 1em;" href="<?= bloginfo('url');?>/wp-admin/edit.php?post_type=twchr_streams&get_thing=videos_ajax"><?php _e('Import Twitch Streamings','twitcher')?></a>
        <?php   
        
    endif; 
            if(isset($_GET['get_thing'])){
                ?>
                    <style>
                    stream.twchr-modal{
                    background-color: #fff;
                    position: absolute;
                    left: 16%;
                    max-width:650px;
                    max-height: 630px;
                    border: 2px solid var(--twchr-purple);
                    box-shadow: -2.5px -2.5px 5px #FAFBFF, 2.5px 2.5px 5px #A6ABBD;
                    border-radius: 15px;
                    padding:1cm;
                }

                stream.twchr-modal h3{
                    color:var(--twchr-purple);
                }
                stream.twchr-modal p{
                    color:#848484;
                }
                    stream.twchr-modal .twchr-modal-button{
                    display: block;
                    width: max-content;
                    margin:0 auto;
                    cursor: pointer;
                    color:var(--e-context-primary-color);
                    border:2px solid var(--e-context-primary-color);
                    padding:.3em 1em;
                }
                stream.twchr-modal.disabled{
                      display: none;
                }
                .twchr_button_container{
                    display: flex;
                    justify-content:center;
                }
                stream.twchr-modal .twchr-modal-button.close{
                   background-color:var(--e-context-primary-color);
                   color:#fff;
                }

                .twchr-modal-selection__info{
                    display: grid;
                    grid-template-columns:2fr 1fr;
                }

                .twchr-modal-selection__info picture{
                    grid-column:2/3;
                    grid-row:1/3;
                }
                .twchr-modal-selection__list{
                    display: flex;
                    justify-content:space-between;
                    margin-top:1cm;
                }

                .twchr-modal-selection__list li {
                    color:var(--twchr-purple);
                    font-style: normal;
                    font-weight: 400;
                    font-size: 15px;
                    line-height: 17px;
                    text-align: center;
                }

                .twchr_modal_video_ajax{
                    display: grid;
                    grid-template-columns:auto 56px;
                }
                .twchr_modal_video_ajax input[type='checkbox']{
                    display: block;
                    margin:2pt auto;
                    width: 25px;
                    height: 25px;
                    background: linear-gradient(317.7deg, rgba(0, 0, 0, 0.4) 0%, rgba(255, 255, 255, 0.4) 105.18%), #E7EBF0;
                    background-blend-mode: soft-light, normal;
                    box-shadow: inset -2.5px -2.5px 5px #FAFBFF, inset 2.5px 2.5px 5px #A6ABBD;
                    border-radius: 3px;
                }
                .twchr_modal_video_ajax input[type='checkbox']:checked{
                    background: linear-gradient(317.7deg, rgba(0, 0, 0, 0.4) 0%, rgba(255, 255, 255, 0.4) 105.18%), #65449B;
                    background-blend-mode: soft-light, normal;
                    border: 0.5px solid rgba(255, 255, 255, 0.4);
                    box-shadow: inset -2.5px -2.5px 5px rgba(250, 251, 255, 0.1), inset 2.5px 2.5px 5px #366CBD;
                }
                .twchr_modal_video_ajax input[type='checkbox']:checked::before {
                    margin:0; 
                    height: none; 
                    width: none; 
                    width: 100%;
                    filter: brightness(10);
                }

                .twchr_modal_video_ajax label{
                    display: grid;
                    grid-template-columns:repeat(3,auto);
                    width: 100%;
                    border: 1px solid #429CD6;
                    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
                    border-radius: 5px;
                    padding: 3pt 8pt;
                    box-sizing: border-box;
                }
                .twchr_modal_video_ajax span{
                    font-family: 'Comfortaa';
                    font-style: normal;
                    font-weight: 400;
                    font-size: 15px;
                    line-height: 2em;
                }
                .twchr_modal_video_ajax span.video-saved{
                    display: block;
                    width: 25px;
                    height: 100%;
                    background-size: contain;
                    background-repeat: no-repeat;
                    background-position: center;
                    background-image:url(<?= plugins_url('twitcher/includes/assets/twchr_check.png')?>);
                    margin:0;
                }

                #twchr-modal-selection__btn{
                    background-color:var(--twchr-purple);
                    width: 107px;
                    height: 30px;
                    border: none;
                    border-radius: 10px;
                    color:#fff;
                    font-size:15px;
                    margin: 19pt 0 0 auto;
                    display: block;
                    filter: drop-shadow(0px 4px 4px rgba(0, 0, 0, 0.25))
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
                            //show_dump($list_videos);
                            
                            foreach($list_videos_array as $video){
                                if(str_contains($_GET['streams_id'],$video->{'stream_id'}) ):
                                //$response = twchr_post_db_exist('twchr_streams',$video->{'title'});
                                //$video_exist = twchr_post_db_exist('twchr_streams',$video->{'title'});
                                $video_exist = twittcher_getData('wp_postmeta','twchr-from-api_stream_id', $video->{'stream_id'});
                                //show_dump($video);
                                if($video_exist === false){
                                    crearStream($video->{'title'} ,$video->{'id'} ,$video->{'created_at'} ,$video->{'description'} ,$video->{'duration'} ,$video->{'language'} ,$video->{'muted_segment'} ,$video->{'published_at'} ,$video->{'stream_id'} ,$video->{'thumbnail_url'} ,$video->{'type'} ,$video->{'url'} ,$video->{'user_id'} ,$video->{'user_login'} ,$video->{'user_name'} ,$video->{'view_count'} ,$video->{'viewable'}, get_current_user_id());
                                    echo "<h4>";
                                    printf("Streaming: %s was successfully added to the database",$video->{'title'});
                                    echo "</h4>";
                                    echo "<script>location.href='".site_url('wp-admin/edit.php?post_type=twchr_streams')."'</script>";
                                    
                                }else{
                                    $json = '';
                                    foreach ($video as $key => $val){
                                        $json .= $key."=".$val."&";    
                                    }
                                    
                                    ?>
                                        
                                        <stream id="twchr-modal-error" class='twchr-modal'> 
                                            <h4><?php printf("Streaming: %s already exists in the database",$video->{'title'}); ?></h4>
                                        <h4><?= __("If you don't see it in the list, maybe it's in the trash.","twitcher"); ?></h4>
                                            <div class="twchr_button_container">
                                                <div class='twchr-modal-button next'><?= __('overwrite','twitcher'); ?></div>
                                                <div class='twchr-modal-button close'><?= __('cancel','twitcher'); ?></div>
                                            </div>
                                        </stream>
                                        <script>
                                            const twchr_modal_error = document.querySelector("#twchr-modal-error");
                                            const twchr_modal_error_button_next = document.querySelector("#twchr-modal-error .twchr-modal-button.next");
                                            const twchr_modal_error_button_close = document.querySelector("#twchr-modal-error .twchr-modal-button.close");

                                            twchr_modal_error_button_next.addEventListener('click',e => {
                                                twchr_modal_error.style.display = 'none';
                                                location.href='<?php echo site_url('/wp-admin/edit.php?post_type=twchr_streams&get_thing=videos_update&json=true&'.$json)?>';
                                            });

                                            twchr_modal_error_button_close.addEventListener('click',e => {
                                                twchr_modal_error.style.display = 'none';
                                                location.href='<?php echo site_url('/wp-admin/edit.php?post_type=twchr_streams&')?>';
                                            });
                                        </script>
                                    <?php
                                }
                                endif;   
                            }
                        }else{
                            wp_redirect(site_url('/twttcher-setup'));
                            exit;
                        }
                       
                        
                        

                        break;
                    case 'videos_ajax':
                         ?>
                            <stream id="twchr-modal-selection" class='twchr-modal <?php if(isset($_GET['stream_id'])) echo "disabled";?>'>
                                <div class="twchr-modal-selection__info">
                                    <h3><?php _e('Importing Twitch Videos to Streaming Post Tool','twitcher') ?></h3>
                                    <p><?php _e('The following list is the avaible videos in your Twitch account. The videos whit “ok” marc are already saved as post type Streaming. Select te videos and press import button to create a new post for your video streaming.','twitcher'); ?></p>
                                    <picture>
                                        <img src="<?= plugins_url('/twitcher-original/includes/assets/Isologo_twitcher.svg')?>" alt="logo-twitcher">
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
                                <button id="twchr-modal-selection__btn"><?= __('send','twitcher');?></button>
                            </stream>
                         <?php
                        break;
                    case 'videos_update':
                        if(isset($_GET['json'])){
                            $postarr = array(
                                'post_title' => $_GET['title'],
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
                            $update = wp_update_post( $postarr, true, true);
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
                                        twchr_modal_error_button_close.addEventListener('click',e => {
                                                twchr_modal_error.style.display = 'none';
                                                location.href='<?php echo site_url('/wp-admin/edit.php?post_type=twchr_streams&')?>';
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
