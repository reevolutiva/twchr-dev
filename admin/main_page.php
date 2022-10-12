<style>
    <?php include 'main_page.css'; ?>
</style>

<div class="twchr-for-back container">
    <main>
        <h1>Twitcher Settings</h1>
        <?php 
            if(isset($_GET['from']) && $_GET['from'] == 'setUp-plugin'){
                delete_option('twitcher_keys');
                delete_option('twitcher_app_token');
                
                if(
                    isset($_GET['client-id']) &&
                    isset($_GET['client-secret'])
                ){
                    $client_id = $_GET['client-id'];
                    $client_secret = $_GET['client-secret'];
                    //var_dump($_GET);
                    fronted_to_db($client_secret, $client_id);

                    $twchr_token_app = get_twicth_api($client_id, $client_secret );
                    twchr_save_app_token($twchr_token_app->{'access_token'});

            
                    echo "<script> location.href='https://".$_SERVER['SERVER_NAME']."/wp-admin/edit.php?post_type=twchr_streams&page=twchr-settings&autentication=true'; </script>";
                }
            }else{
            $twch_data_prime = get_option('twitcher_keys') == false ? false : json_decode(get_option('twitcher_keys'));
            //$twch_data_prime_lengt = count($twch_data_prime);
            $twch_data_app_token = get_option('twitcher_app_token');
            
            
            //show_dump($twch_data_prime);
        ?>
        <form method="GET" action="./edit.php">
            <input type="hidden" name="post_type" value="twchr_streams">
            <input type="hidden" name="page" value="twchr-settings">
            <?php $clientID = !empty($twch_data_prime->{'client-id'}) ? $twch_data_prime->{'client-id'} : null ; ?>
            <input id="client-id" type="text" placeholder="Client ID" name="client-id" value="<?= $clientID?>" disabled="true">
            <?php $clientSecret = !empty($twch_data_prime->{'client-secret'}) ?  $twch_data_prime->{'client-secret'} : null; ?>
            <input id="client-secret" type="password" placeholder="Client Secret" name="client-secret" value="<?= $clientSecret ?>" disabled="true">
            <input type="submit" value="resincronizar" name="resincronizar" >
            <?php 
                if(isset($_GET['resincronizar'])){
                    echo "<script>const wishexist = prompt('Si continuas este proseso se eliminaran todas las api-keys instaladas en wordpress ¿Esta seguro de hacerlo? s = sí y n = no.'); if(wishexist === 's' || wishexist === 'sí'){ location.href='".site_url("/twttcher-setup")."'}else{location.href='".site_url("/wp-admin/edit.php?post_type=twchr_streams&page=twchr-settings")."'}</script>";
                    die();
                }
            ?>
        </form>

        <hr/>
        <h3>Actualiza tu USER TOKEN</h3>   
        <p><a class="btn" href="<?= bloginfo('url')?>/wp-admin/edit.php?post_type=twchr_streams&page=twchr-settings&autentication=true">renovar user token</a></p>

        <hr/>
        
        <h3>Actualiza tu APP TOKEN</h3>
        <a href="<?= bloginfo('url');?>/wp-admin/edit.php?post_type=twchr_streams&page=twchr-settings&app_token_action=update">renovar app token</a>
        <?php 
            if(isset($_GET['app_token_action'])){
                switch ($_GET['app_token_action']) {
                    case 'update':
                        
                        $twchr_token_app = get_twicth_api($twch_data_prime->{'client-id'},$twch_data_prime->{'client-secret'});
                        twchr_save_app_token($twchr_token_app->{'access_token'});
                        wp_redirect(site_url('/wp-admin/edit.php?post_type=twchr_streams&page=twchr-settings'));
                        exit;
                        
                        break;
                    
                    default:
                        # code...
                        break;
                }
            }
        ?>
        
    </main>
    <aside>
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
                            
                            add_option('twitcher_keys',$json_array);

                            wp_redirect(site_url('/wp-admin/edit.php?post_type=twchr_streams&page=twchr-settings'));
                            exit;

                            
                        }
                    }
                    
                    if(isset($_GET['autentication'])){
                        if ($_GET['autentication'] == true) {
                            if(!empty($twch_data_prime->{'client-secret'}) && 
                            !empty($twch_data_prime->{'client-id'})
                            ):
                                $client_id = $twch_data_prime->{'client-id'};
                                $secret_key = $twch_data_prime->{'client-secret'};
                                $return = site_url('/wp-admin/edit.php?post_type=twchr_streams&page=twchr-settings');
                                $scope = array(
                                    "channel:manage:schedule"
                                );
                                
                                autenticate($client_id, $secret_key, $return,$scope);  
                            endif;
                        }
                    }
                    if (isset($_GET['code'])) {
                        $client_id = $twch_data_prime->{'client-id'};
                        $client_secret = $twch_data_prime->{'client-secret'};
                        $code = $_GET['code'];
                        $scope = $_GET['scope'];
                        $redirect = site_url('/wp-admin/edit.php?post_type=twchr_streams%26page=twchr-settings');
        
                        $response = validateToken($client_id,$client_secret,$code, $redirect);
                        $validateTokenObject = json_decode($response['body']);
                        $response_code = $response['response'];
        
                        if($response_code['code'] == 200){
                            global $wpdb;
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
                            $sql = "UPDATE wp_options SET option_value='$json_array' WHERE option_name='twitcher_keys'";
        
                            $wpdb->query($sql);
                        }else{
                            var_dump($validateTokenObject);
                        }
        
                        echo "<h3>User Token actualizado actualizado correctamente</h3>";
                        $urlRedirection = 'https://'.$_SERVER['SERVER_NAME'].'/wp-admin/edit.php?post_type=twchr_streams&page=twchr-settings';
                        echo "<script>location.href='$urlRedirection'</script>";
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
        ?>
        <table class="twittcher-table">
            <h3>Keys para API de Twitch</h3>
            <?php if($twch_data_prime != false): ?>
                <tr>
                <td>Client ID</td>
                <td><?= $twch_data_prime->{'client-id'}?></td>
            </tr>
            <tr>
                <td>Client Secret</td>
                <td><?= $twch_data_prime->{'client-secret'}?></td>
            </tr>
            <tr>
                <td>Code</td>
                <td>
                <?php 
                    if(isset($twch_data_prime->{'code'})) {
                        echo $twch_data_prime->{'code'};
                    }else{
                        echo'key sin registrar';
                    } 
                ?>
                </td>
            </tr>
            <tr>
                <td>Scope</td>
                <td>
                <?php 
                    if(isset($twch_data_prime->{'scope'})) {
                        echo $twch_data_prime->{'scope'};
                    }else{
                        echo'key sin registrar';
                    } 
                ?>
            </td>
            </tr>
            <tr>
                <td>User token</td>
                <td>
                <?php 
                    if(isset($twch_data_prime->{'user_token'})) {
                        echo $twch_data_prime->{'user_token'};
                    }else{
                        echo'key sin registrar';
                    } 
                ?>
            </td>
            </tr>
            <tr>
                <td>User token - refresh</td>
                <td>
                <?php 
                    if(isset($twch_data_prime->{'user_token_refresh'})) {
                        echo $twch_data_prime->{'user_token_refresh'};
                    }else{
                        echo'key sin registrar';
                    } 
                ?>
            </td>
            </tr>
            <?php endif; ?>
            <?php if($twch_data_app_token != false): ?>
            <tr>
                <td>App Token</td>
                <td><?= $twch_data_app_token?></td>
            </tr>
            <?php endif; ?>
        </table>
    </aside>
    <?php } 
    ?>