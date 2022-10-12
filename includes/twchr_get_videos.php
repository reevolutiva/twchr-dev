<?php 

add_action('restrict_manage_posts','twchr_get_videos_function');

function twchr_get_videos_function(){
    $twch_data_prime = get_option('twitcher_keys') == false ? false : json_decode(get_option('twitcher_keys'));
            //$twch_data_prime_lengt = count($twch_data_prime);
    $twch_data_app_token = get_option('twitcher_app_token');
    $get_length = COUNT($_GET);
    $dataUrl = $_GET['post_type'];
    if($dataUrl == 'twchr_streams' && $get_length == 1):
        ?>
        <a style="text-decoration: none;display: inline-block;background-color: #fff;padding: .5em;border: 1px solid;border-radius: 5px;line-height: 1em;" href="<?= bloginfo('url');?>/wp-admin/edit.php?post_type=twchr_streams&get_thing=videos">Sicronizar con videos de Twitch</a>
        <?php
    endif; 
            if(isset($_GET['get_thing'])){
                switch ($_GET['get_thing']) {
                    case 'videos':
                        /*
                        $args = array(
                            "post_type" => "twchr_streams"
                        );
                        */
                        $post_streaming_a_crear_repetido = array();
                        if($twch_data_prime != false || $twch_data_app_token != false){
                             // Extrago de la API un array con todos los videos publicados en la cuenta de twtch
                            $list_videos = get_twicth_video($twch_data_app_token, $twch_data_prime->{'client-id'},'817863896');
                            //show_dump($list_videos);
                            $list_videos_array = $list_videos->{'data'};
                            
                            /*$streamig_post_raw = new WP_Query($args);*/
                            // List de todos los post
                            /*$streamig_post = $streamig_post_raw->{'posts'};*/
                            //show_dump($list_videos);
                            
                            foreach($list_videos_array as $video){
                                //$response = twchr_post_db_exist('twchr_streams',$video->{'title'});
                                $video_exist = twchr_post_db_exist('twchr_streams',$video->{'title'});
                                //show_dump($video);
                                if($video_exist === false){
                                    $all_data = json_encode($video);
                                    crearStream($video->{'id'},$video->{'title'},$video->{'user_id'},$video->{'description'},$video->{'url'},$video->{'duration'},$video->{'stream_id'},$all_data);
                                    echo "<h4>El video: <i>'".$video->{'title'}."'</i> fue agregado exitosamente en la base de datos</h4>";
                                    wp_redirect(site_url('wp-admin/edit.php?post_type=twchr_streams'));
                                    exit;
                                    
                                }else{
                                    //echo "<script>alert('El video: `".$video->{'title'}."` ya existe en la base de datos');alert('Si no encuentras el video puede ser porque esta en la papelera.'); location.href='".site_url('/wp-admin/edit.php?post_type=twchr_streams')."'</script>";
                                    
                                    ?>
                                        <style>
                                            stream.twchr-modal{
                                                background-color: #fff;
                                                position: absolute;
                                                left: 30%;
                                                min-height: 20vh;
                                                box-shadow: 0px 0px 8px 2px rgba(0,0,0,.3);
                                                padding:0 10px;
                                            }
                                            stream.twchr-modal .twchr-modal-button{
                                                display: block;
                                                width: max-content;
                                                margin:0 auto;
                                                cursor: pointer;
                                                background-color: green;
                                                color:#fff;
                                                padding:.3em 1em;
                                            }
                                        </style>
                                        <stream id="twchr-modal-error" class='twchr-modal'> 
                                            <h4>"El video: '<?= $video->{'title'}?>' ya existe en la base de datos</h4>
                                            <h4>"Si no lo ves en las lista quiza este en la papelera</h4>
                                            <div class='twchr-modal-button'>aceptar</div>
                                        </stream>
                                        <script>
                                            const twchr_modal_error = document.querySelector("#twchr-modal-error");
                                            const twchr_modal_error_button = document.querySelector("#twchr-modal-error .twchr-modal-button");

                                            twchr_modal_error_button.addEventListener('click',e => {
                                                twchr_modal_error.style.display = 'none';
                                                location.href='<?php echo site_url('/wp-admin/edit.php?post_type=twchr_streams&')?>';
                                            });
                                        </script>
                                    <?php
                                }   
                            }
                        }else{
                            wp_redirect(site_url('/twttcher-setup'));
                            exit;
                        }
                       
                        
                        

                        break;
                    
                    default:
                        # code...
                        break;
                }
            }
        }
