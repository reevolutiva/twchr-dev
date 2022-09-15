<?php 
    function twittcher_data_exist($data){
        global $wpdb;
        $sql = "SELECT * FROM wp_options WHERE option_name='$data'";
        $wpdb->query($sql);
        $response = $wpdb->last_result[0];
        if(!empty($response)){
            return true;
        }else{
            return false;
        }
    }

    function fronted_to_db($client_secret,$clientId){
        global $wpdb;
        if(!twittcher_data_exist('twitcher_keys')){
            $array_keys = array(
                'client-secret' => $client_secret,
                'client-id' => $clientId
            );
            $json_array = json_encode($array_keys);
            $sql = "INSERT INTO wp_options VALUE(null,'twitcher_keys','$json_array',true)";
            $wpdb->query($sql);
        }else{
            $array_keys = array(
                'client-secret' => $client_secret,
                'client-id' => $clientId
            );
            $json_array = json_encode($array_keys);
            $sql = "UPDATE wp_options SET option_value='$json_array' WHERE option_name='twitcher_keys'";
            $wpdb->query($sql);
        }
    }

    function db_to_front($data){
        global $wpdb;
        $sql = "SELECT * FROM wp_options WHERE option_name='$data'";
        $wpdb->query($sql);
        return array(
            'last_result' => $wpdb->last_result,
            'result' => $wpdb->result
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