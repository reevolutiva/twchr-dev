<?php 
    require_once 'aux_functions/twchr_max_of_list.php';
?>
<style>
    <?php include 'main_page.css'; ?>
</style>
<div class="twchr-for-back twchr-container">
    <article class='twchr-dashboard-card plugin-hello'>
        <picture>
            <img src="<?= plugins_url('/twitcher/includes/assets/Isologo_twitcher.svg')?>" alt="Logo Twitcher">
        </picture>
        <h2><?= __('Dashboard','twitcher'); ?></h2>
    </article>
        <article>
            <h3><?php _e('Twitcher Data'); ?></h3>
            <div class="twchr-dashboard-card twchr-card-keys">
            <?php 
                $data_broadcaster_raw = get_option( 'twchr_data_broadcaster', false ) == false ?  false :  json_decode(get_option( 'twchr_data_broadcaster'));
            
                $twch_data_prime = get_option('twchr_keys') == false ? false : json_decode(get_option('twchr_keys'));
                //$twch_data_prime_lengt = count($twch_data_prime);
                $twch_data_app_token = get_option('twchr_app_token');
            
                
                
                if($data_broadcaster_raw != false):
                    $display_name = $data_broadcaster_raw->{'data'}[0]->{'display_name'};
                    $nombre = $data_broadcaster_raw->{'data'}[0]->{'login'};
                    $broadcaster_id = $data_broadcaster_raw->{'data'}[0]->{'id'};
                    $description = $data_broadcaster_raw->{'data'}[0]->{'description'};
                    $foto = $data_broadcaster_raw->{'data'}[0]->{'profile_image_url'};
                    $type = $data_broadcaster_raw->{'data'}[0]->{'type'};
                    $broadcaster_type = $data_broadcaster_raw->{'data'}[0]->{'broadcaster_type'};
                    $created_at = $data_broadcaster_raw->{'data'}[0]->{'created_at'};
            ?>
                <div class='hello-twchr-user'> 
                    <h2><?php printf('%s', $display_name);?></h2>                                
                    <p><?php printf('Description: %s', $description);?></p>
                    <picture><img src="<?= $foto ?>" alt="twchr-profile-picture"></picture>
                </div>
                <div class='keys-twchr twchr-data'> 
                
                    <form method="GET" action="./edit.php">
                        <input type="hidden" name="post_type" value="twchr_streams">
                        <input type="hidden" name="page" value="twchr-dashboard">
                        <?php $clientID = !empty($twch_data_prime->{'client-id'}) ? $twch_data_prime->{'client-id'} : null ; ?>
                        <input id="client-id" type="hidden" placeholder="Client ID" name="client-id" value="<?= $clientID?>">
                        <?php $clientSecret = !empty($twch_data_prime->{'client-secret'}) ?  $twch_data_prime->{'client-secret'} : null; ?>
                        <input id="client-secret" type="hidden" placeholder="Client Secret" name="client-secret" value="<?= $clientSecret ?>" disabled="true">
                        <div>
                            <p>Broadcaster Type</p>
                            <p class="twchr-key-value">Lorem Ipsum Dolor</p>
                        </div>
                        <div>
                            <p>Type</p>
                            <p class="twchr-key-value">Lorem Ipsum Dolor</p>
                        </div>
                        <div>
                            <p>Created at</p>
                            <p class="twchr-key-value created_at"><?= $created_at ?></p>
                            
                        </div>
                        <div>
                            <p>Client ID</p>
                            <p class="twchr-key-value"><?= $clientID?></p>
                        </div>
                        <div>
                            <p>User login</p>
                            <p class="twchr-key-value"><?= $nombre ?></p>
                        </div>
                        <input type="submit" value="<?php esc_attr_e('Reconnect','twitcher');?>" name="renew" id='twchr_submitbutton' >
                        <?php 
                            if(isset($_GET['renew'])){
                                ?>
                                    <script> 
                                        const wishexist = prompt('<?php _e('If you continue this process, all the api-keys installed in wordpress will be removed. Are you sure to do it?','twitcher');?> y = yes & n = no.');
                                        if(wishexist === 'y' || wishexist === 'yes'){
                                            location.href='<?= site_url("/wp-admin/edit.php?post_type=twchr_streams&page=twchr_help&setUpPage=true&clearAll")?>';
                                        }else{
                                            location.href='<?=site_url("/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard")?>';
                                        }
                                    </script> 
                                <?php
                                die();
                            }
                        ?>    
                        </form>
                    </div>
                </div>
            <?php endif; 
        if($data_broadcaster_raw == false): ?>
            <div class="connect-card">
                <h2>Connect...</h2>
            </div>
        <?php endif;?>
        </article>
        <article>
            <h3>Your Twitch Results:</h3>
            <?php 
                $data_broadcaster = $data_broadcaster_raw->{'data'}[0];
                $client_id = $twch_data_prime->{'client-id'};
                $broadcaster_id = $data_broadcaster_raw->{'data'}[0]->{'id'};

                $subcribers = get_subcribers($twch_data_app_token, $client_id);
                             
                $listVideo_from_api = get_twicth_video($twch_data_app_token, $twch_data_prime->{'client-id'},$broadcaster_id)->{'status'} === 401 ? false : get_twicth_video($twch_data_app_token, $twch_data_prime->{'client-id'},$broadcaster_id);
                $listVideo_from_wp = twchr_get_stream();
                //show_dump($listVideo_from_api->data[0]->view_count);
                if($listVideo_from_api === false && get_option('twchr_setInstaled') <= 3){
                    
                }else{
            
                    $mostViwed_from_api = twchr_max_of_list($listVideo_from_api->{'data'},'view_count','title');
                    $mostViwed_from_wp = twchr_max_of_list($listVideo_from_wp,'twchr-from-api_view_count','post_title',true);

                    //show_dump($mostViwed_from_api);
                }

                //show_dump($mostViwed_from_api);
                //show_dump($listVideo);
                ?>
            <div class="twchr-dashboard-card twitch-result">
                <?php if($listVideo_from_api != false && get_option('twchr_setInstaled') == 3): ?>
                <table>
                    <tbody>
                        <tr>
                            <td><?php _e('View Count','twitcher'); ?></td>
                            <td data-twchr-final-number="<?= isset($data_broadcaster->{'view_count'}) ? $data_broadcaster->{'view_count'} : 0 ?>" class='twchr-results-item' ><?= isset($data_broadcaster->{'view_count'}) ? $data_broadcaster->{'view_count'} : 'null' ?></td>
                        </tr>
                        <tr>
                            <td><?php _e('Suscribers','twitcher'); ?></td>
                            <td data-twchr-final-number="<?= isset($subcribers->{'total'}) ? $subcribers->{'total'} : 0 ?>" class='twchr-results-item' ><?= isset($subcribers->{'total'}) ? $subcribers->{'total'} : 'null' ?></td>
                        </tr>
                        <tr>
                            <td><?php _e('Videos','twitcher'); ?></td>
                            <td data-twchr-final-number="<?= isset($listVideo_from_api) ? COUNT($listVideo_from_api->{'data'}) : 0 ?>" class='twchr-results-item'><?= isset($listVideo_from_api) ? COUNT($listVideo_from_api->{'data'}) : 0 ?></td>
                        </tr>
                        <tr>
                            <td><?php _e('Most viewed','twitcher'); ?></td>
                            <td data-twchr-final-number="<?= $mostViwed_from_api != false ? $mostViwed_from_api['view'] : 0  ?>" class='twchr-results-item'>0</td>
                            <td class="twchr-tooltip"><?= $mostViwed_from_api != false ? $mostViwed_from_api['title'] : 'undefined' ?></td>
                        </tr>
                        <tr>
                            <td><?php _e('Last Imported','twitcher'); ?></td>
                            <td data-twchr-final-number="<?= $mostViwed_from_wp != false ? $mostViwed_from_wp['view'] : 0  ?>" class='twchr-results-item' >12</td>
                            <td class="twchr-tooltip"><?= $mostViwed_from_wp != false ? $mostViwed_from_wp['title'] : 'undefined' ?></td>
                        </tr>
                        <tr>
                            <td class="btn-renew-apiKeys">
                                <a href="<?= site_url('/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard')?>"><?= __('Refresh','twitcher') ?></a>
                                
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php endif; 
                    if(get_option('twchr_setInstaled') <= 3):
                        $setInstaled = get_option('twchr_setInstaled');
                        if($setInstaled == 3 && $listVideo_from_api === false){
                            $error = get_twicth_video($twch_data_app_token, $twch_data_prime->{'client-id'},$broadcaster_id);
                            ?>
                            <div class="error-card">
                                <h3>Error: <?= $error->{'status'} ?></h3>
                                <p><?= $error->{'message'} ?></p>
                            </div>
                        <?php
                        }else{
                            ?>
                            <div class="connect-card">
                                <h2>Connect...</h2>
                            </div>
                            <?php
                        }
                    endif;
                ?>
            </div>
        </article>
        <article>
        <h3>Your Twitch connection:</h3>
        <?php 
            if(isset($_GET['from']) && $_GET['from'] == 'setUp-plugin'){
                             
                if(
                    isset($_GET['client-id']) &&
                    isset($_GET['client-secret'])
                ){
                    $client_id = $_GET['client-id'];
                    $client_secret = $_GET['client-secret'];
                    
                    fronted_to_db($client_secret, $client_id);

                    // Obtengo App Token
                    $twchr_token_app = get_twicth_api($client_id, $client_secret );
                    
                    // Guardo AppToken                    
                    twchr_save_app_token($twchr_token_app->{'access_token'});

                    // Paso 2 de la instalacion
                    update_option('twchr_setInstaled',2,true);
                      
                   

                    echo "<script> location.href='https://".$_SERVER['SERVER_NAME']."/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true'; </script>";
                }
            }else{ ?>
            
            <div class="twchr-dashboard-card twitch-connect">
                <table>
                    <tbody>
                        <tr>
                            <td><?= __('Renew Client ID','twitcher'); ?></td>
                            <td class='twitch-connect__status'><?= isset($twch_data_prime->{'client-id'}) != false ?  "<img src='".plugins_url('twitcher/includes/assets/sync.svg')."'>" : "<span style='color:tomato;'>".__('Error','twitcher')."</span>" ?></td>
                        </tr>
                        <tr>
                            <td><?= __('Renew Client Secret','twitcher'); ?></td>
                            <td class='twitch-connect__status'><?= isset($twch_data_prime->{'client-secret'}) != false ?  "<img src='".plugins_url('twitcher/includes/assets/sync.svg')."'>" : "<span style='color:tomato;'>".__('Error','twitcher')."</span>" ?></td>
                        </tr>
                        <tr>
                            <td><a class="btn" href="<?= bloginfo('url')?>/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true"><?= __('Renew User Token','twitcher'); ?></a></td>
                            <td class='twitch-connect__status'><?= isset($twch_data_prime->{'user_token'}) != false ?  "<img src='".plugins_url('twitcher/includes/assets/sync.svg')."'>" : "<span style='color:tomato;'>".__('Error','twitcher')."</span>" ?></td>
                        </tr>
                        <tr>
                            <td><a href="<?= bloginfo('url');?>/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard&app_token_action=update"><?= __('Renew App Token','twitcher'); ?></a></td>
                            <td class='twitch-connect__status'><?= $twch_data_app_token != false ?  "<img src='".plugins_url('twitcher/includes/assets/sync.svg')."'>" : "<span style='color:tomato;'>".__('Error','twitcher')."</span>" ?></td>
                        </tr>
                        <tr>
                            <td class="btn-renew-apiKeys"><a href="<?= site_url('wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard')?>&app_token_action=renewAll_api_keys"><?php _e('Renew all','twitcher'); ?></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        <?php 
            if(isset($_GET['app_token_action'])){
                switch ($_GET['app_token_action']) {
                    case 'update':
                        
                        $twchr_token_app = get_twicth_api($twch_data_prime->{'client-id'},$twch_data_prime->{'client-secret'});
                        twchr_save_app_token($twchr_token_app->{'access_token'});
                        // Paso 3 de instalaccion
                        echo "<script>location.href='".site_url('/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard')."'</script>";
                        break;
                    case 'renewAll_api_keys':
                        $twchr_token_app = get_twicth_api($twch_data_prime->{'client-id'},$twch_data_prime->{'client-secret'});                
                        twchr_save_app_token($twchr_token_app->{'access_token'});
                        echo "<script>location.href='".site_url('/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true')."'</script>";
                        
                    default:
                        # code...
                        break;
                }
            }
        ?>
        
    </article>
    <?php 
            if(isset($_GET)){  
                if(count($_GET) > 1){
                    if(isset($_GET['sincronizar'])){
                        if( isset($_GET['client-id']) && isset($_GET['client-secret'])){
                            $client_id = $_GET['client-id'];
                            $client_secret = $_GET['client-secret'];
                            $array_keys = array(
                                'client-secret' => $client_secret,
                                'client-id' => $client_id
                            );
                            $json_array = json_encode($array_keys);
                            
                            add_option('twchr_keys',$json_array);

                            wp_redirect(site_url('/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard'));
                            exit;

                            
                        }
                    }
                    
                    if(isset($_GET['autentication'])){
                        if ($_GET['autentication'] == true) {
                            //show_dump($twch_data_prime);
                            if(!empty($twch_data_prime->{'client-secret'}) && 
                            !empty($twch_data_prime->{'client-id'})
                            ):
                                $client_id = $twch_data_prime->{'client-id'};
                                $secret_key = $twch_data_prime->{'client-secret'};
                                $return = site_url('/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard');
                                $scope = array(
                                    "channel:manage:schedule"
                                );

                                if(isset($_GET['twchr_id'])){
                                    $term_id = $_GET['twchr_id'];
                                    $allData = '';
                                    update_term_meta($term_id,'twchr_fromApi_allData',$allData);
                                }

                                autenticate($client_id, $secret_key, $return,$scope);  
                            endif;
                        }
                    }
                    if (isset($_GET['code'])) {
                        $client_id = $twch_data_prime->{'client-id'};
                        $client_secret = $twch_data_prime->{'client-secret'};
                        $code = $_GET['code'];
                        $scope = $_GET['scope'];
                        $redirect = site_url('/wp-admin/edit.php?post_type=twchr_streams%26page=twchr-dashboard');
        
                        $response = validateToken($client_id,$client_secret,$code, $redirect);
                        $validateTokenObject = json_decode($response['body']);
                        $response_response = $response['response'];
                        
                        if($response_response['code'] == 200){
                            $userToken = $validateTokenObject->{'access_token'};
                            $userTokenRefresh = $validateTokenObject->{'refresh_token'};
        
                            $array_keys = array(
                                'client-secret' => $client_secret,
                                'client-id' => $client_id,
                                'code' => $code,
                                'scope' => $scope,
                                'user_token' => $userToken ,
                                'user_token_refresh' => $userTokenRefresh
                            );
        
                            $json_array = json_encode($array_keys);
        
                            update_option( 'twchr_keys', $json_array, true);
                            update_option( 'twchr_setInstaled',3, true);

                            
                            echo "<h3>User Token actualizado actualizado correctamente</h3>";
                            $urlRedirection = 'https://'.$_SERVER['SERVER_NAME'].'/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true';
                            echo "<script>location.href='$urlRedirection'</script>";
                        }else{
                        ?>
                            <div class="twchr-modal-error">
                                <h3>¡Ups! User Token no ha sido actualizado actualizado correctamente</h3>
                                <p>Intente nuevamente</p>
                                <p><b>Error: </b><?= $validateTokenObject->{'message'} ?></p>
                                <p><a href="<?= site_url('/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard');?>">ok</a></p>
                            </div>
                        <?php
                            die();
                        }
                
                        
                    }
        
                    if(isset($_GET['error']) && isset($_GET['error_description'])){
                        echo "Error: ".$_GET['error'];
                        echo "</br>";
                        echo "Descripción del error: ".$_GET['error_description'];
                    }
        
                    if(isset($_GET['twch_api_error'])){
                        $data = str_replace('\\',"",$_GET['twch_api_error'],);
                        echo "<h4>".$data."</h4>";
                        echo "<a href='https://".$_SERVER['SERVER_NAME']."/wp-admin/post.php?post=".$_GET['twch_post_id']."&action=edit'>voler a intentar</a>";
                    }
                }
            }
            //var_dump($twch_data_prime);
        } 
    ?>