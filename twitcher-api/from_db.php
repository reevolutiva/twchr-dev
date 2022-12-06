<?php
    // Esta funcion debe ejecturase cuando se el plugin se activa y cuando se desactriva
    /*
        ** DATA A RECOPILAR
        * URL
        * WORDPRESS Version
        * PHP Version
        * Plugins Instalados
        * Cantidad de Usuarios
        * Tema
    */
    function twchr_recopiate_data(){
        $list_old = get_plugins();
        $list_new = array();
        foreach ($list_old as $item){
            array_push($list_new, $item['Name']);    
        }

        $URL = get_option('siteurl');
        $WORDPRESS_VERSION = get_bloginfo('version');
        $PHP_VERSION = PHP_VERSION;

        $USER_QUANTIYY = COUNT(get_users());
        $TEMPLATE = get_option('template');
        
        $PAKAGE = array(
            'url' => $URL,
            'wordpressversion' => $WORDPRESS_VERSION,
            'php_version' => $PHP_VERSION,
            'plugins' => $list_new,
            'users_quantity' => $USER_QUANTIYY,
            'template' => $TEMPLATE,
            'user_email' => wp_get_current_user()->{'user_email'},
        );
        

        return $PAKAGE;
    }

function instanse_comunicate_server(){
    $case = get_option("twchr_log");
    $event = false;
    
    switch ($case) {
        case '0':
            $event = 'activate';
            
            break;
        case '1':
            $event = 'disactivate';
            
            break;
        
        default:
            break;
    }
    if ($event != false && ($case == 0 || $case == 1)):
    ?>
    <form action="https://twitcher.pro/twch_server/twchr_get/" method="post" id="twchr-form-to-server">
    <?php 
        $db = twchr_recopiate_data();
        foreach ($db as $key => $value) {
            if(is_array($value)){
                $json = json_encode($value);
            }else{
                $json = $value;
            }
            echo "<input type='hidden' name='to-twitcher-server-".$key."' value ='$json'>";
            //var_dump($json);
        }
    ?>
    <input type="hidden" name="to-twitcher-server-event" value="<?php echo $event?>">
    </form>
    <script>
        const twchr_form_to_server = document.querySelector("#twchr-form-to-server");
        <?php
            if($case == 0){
                ?>
                twchr_form_to_server.submit();
                <?php
                update_option('twchr_log',1);
            }
        ?>
    </script>
    <?php
    die();
    endif;
    
}

function twchr_recopiate_data_twitch(){
    //KEYS
    
    $data_broadcaster = get_option( 'twchr_data_broadcaster', false ) == false ?  false :  json_decode(get_option( 'twchr_data_broadcaster'));
    $broadcaster_id = $data_broadcaster->{'data'}[0]->{'id'};
    $twch_data_app_token = get_option('twchr_app_token');
    $twch_data_prime = get_option('twchr_keys') == false ? false : json_decode(get_option('twchr_keys'));
    $client_id = $twch_data_prime->{'client-id'};
    $subcriptores = twchr_get_subcribers($twch_data_app_token, $client_id)->{'total'};
    $followers = twchr_get_user_followers($twch_data_app_token , $client_id, $broadcaster_id)->{'total'}; 
     
    // VIDEOS
    
    $list_videos = twchr_get_twicth_video($twch_data_app_token, $twch_data_prime->{'client-id'},$broadcaster_id)->{'data'};
    $videos =  COUNT($list_videos);

    $vistas = $data_broadcaster->{'data'}[0]->{'view_count'};
    
    $schedules = COUNT(twchr_get_schedule());
    
    //$moderatos = COUNT(twchr_get_moderators($twch_data_app_token , $client_id, $broadcaster_id)-); 
     
    $clips = COUNT(twchr_get_clips($twch_data_app_token , $client_id, $broadcaster_id)->{'data'});
    $user_data = get_option('twchr_data_broadcaster', false ) == false ?  false :  json_decode(get_option( 'twchr_data_broadcaster'));
    $user_login = $user_data->{'data'}[0]->{'login'};

      
    $PAKAGE = array(
        'wp-id' => wp_get_current_user()->{'user_email'},
        'subcriptores' =>  $subcriptores,
        'followers' => $followers,
        'videos' => $videos,
        'vistas' => $vistas,
        'schedules' => $schedules,
        //'moderatos' => $moderatos,
        'clips' => $clips,
        'user_login' => $user_login
    );

    return $PAKAGE;


    
}

function instanse_comunicate_server_twitch(){
    ?>
    <form action="https://twitcher.pro/twch_server/twchr_get/" method="post" id="twchr-form-to-server-twitch-data">
        <input type="hidden" name="twchr-from-data-server" value="twitch">
    <?php 
        $db = twchr_recopiate_data_twitch();
        foreach ($db as $key => $value) {
            if(is_array($value)){
                $json = json_encode($value);
            }else{
                $json = $value;
            }
            echo "<input type='hidden' name='to-twitcher-server-".$key."' value ='$json'>";
            //var_dump($json);
        }
    ?>
    </form>
    <script>
        const twchr_form_to_server_twitch_data = document.querySelector("#twchr-form-to-server-twitch-data");
        //twchr_form_to_server_twitch_data.submit();
    
    </script>
    <?php
    die();
}