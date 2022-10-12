<?php 
    function twittcher_data_exist($data){
        global $wpdb;
        $sql = "SELECT * FROM wp_options WHERE option_name='$data'";
        //show_dump($sql);
        $wpdb->query($sql);
        //$response = $wpdb->last_result[0];
        $response = $wpdb->last_result;
        //show_dump($wpdb->last_result);

        if(!empty($response[0])){
            return true;
        }else{
            return false;
        }
    }
    function twittcher_getData_wp_optios($data){
        global $wpdb;
        $sql = "SELECT * FROM wp_options WHERE option_name='$data'";
        $wpdb->query($sql);
        $response = $wpdb->last_result[0];
        if(!empty($response)){
            return $response;
        }else{
            return false;
        }
    }

    function fronted_to_db($client_secret,$clientId){
        
        if(!twittcher_data_exist('twitcher_keys')){
            $array_keys = array(
                'client-secret' => $client_secret,
                'client-id' => $clientId
            );
            $json_array = json_encode($array_keys);
            add_option('twitcher_keys',$json_array);
            
        }else{
            $array_keys = array(
                'client-secret' => $client_secret,
                'client-id' => $clientId
            );
            $json_array = json_encode($array_keys);
            update_option('twitcher_keys',$json_array);
        }
    }

    function twchr_save_app_token($token){
        
        if(!twittcher_data_exist('twitcher_app_token')){
            add_option('twitcher_app_token',$token);
        }else{
            update_option('twitcher_app_token',$token);
        }
    }

    function db_to_front($data){
        global $wpdb;
        $sql = "SELECT * FROM wp_options WHERE option_name='$data'";
        $wpdb->query($sql);
        return array(
            'last_result' => $wpdb->last_result,
            'result' => $wpdb->result,
            'all' => $wpdb
        );
    }

    function saveValidateToken($validateTokenObject){ 
        global $wpdb;       
        $validateToken = $validateTokenObject->{'access_token'};
        $validateTokenRefresh = $validateTokenObject->{'refresh_token'};
        if(!twittcher_data_exist('twitcher_keys_validate')){
            $array_keys = array(
                'validate-token' => $validateToken,
                'token-refresh' => $validateTokenRefresh
            );
            $json_array = json_encode($array_keys);
            $sql = "INSERT INTO wp_options VALUE(null,'twitcher_keys_validate','$json_array',true)";
            //$wpdb->query($sql);
        }else{
            $array_keys = array(
                'validate-token' => $validateToken,
                'token-refresh' => $validateTokenRefresh
            );
            $json_array = json_encode($array_keys);
            $sql = "UPDATE wp_options SET option_value='$json_array' WHERE option_name='twitcher_keys_validate'";
            $wpdb->query($sql);
            //var_dump($sql);
        }
        
    }

    function twchr_post_db_exist($cpt,$value_title){
        global $wpdb;
        $sql = "SELECT post_title FROM wp_posts WHERE post_type = '$cpt' AND post_title = '$value_title';";
        $wpdb->query($sql);
        $response = $wpdb->{'last_result'};
        if(COUNT($response) > 0){
            return $response;
        }else{
            return false;
        }
        
    }

   /* Sin usos 
   function setInstalled(){
        // Pregunto si existe twchr_setInstaled
        $datainstaled = db_to_front('twchr_setInstaled');
        show_dump('de setInstalled');
        show_dump(COUNT($datainstaled['last_result']));
        
        //show_dump();
            if(COUNT($datainstaled['last_result']) == 0){
                //Crear variable twchr_setInstaled   
                add_option('twchr_setInstaled',0,'',true );
            }
            //die();
        
    }
    */