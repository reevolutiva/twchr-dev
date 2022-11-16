<?php 
// Retorna true si $data existe en wp_options
    function twittcher_data_exist($data){
        if(get_option($data, false) != false){
            return true;
        }else{
            return false;
        }
    }

    // Si existe un dato en BDD me lo devuelves
    function twittcher_getData($table,$key,$data){
        global $wpdb;
        $sql = "SELECT * FROM $table WHERE meta_key='$key' AND meta_value='$data'";
        $wpdb->query($sql);
        $response = $wpdb->last_result[0];
        if(!empty($response)){
            return $response;
        }else{
            return false;
        }
    }

    // Guarda $client_secret y $clientId en wp_options
    function fronted_to_db($client_secret,$clientId){
        if(twittcher_data_exist('twchr_keys') == false){
            $array_keys = array(
                'client-secret' => $client_secret,
                'client-id' => $clientId
            );
            $json_array = json_encode($array_keys);
            add_option('twchr_keys',$json_array);
            
        }else{
            $array_keys = array(
                'client-secret' => $client_secret,
                'client-id' => $clientId
            );
            $json_array = json_encode($array_keys);
            update_option('twchr_keys',$json_array);
        }
    }

    // Guarda appToken en wp_option
    function twchr_save_app_token($token){
        //show_dump($token);
        if(get_option('twchr_app_token') != false || get_option('twchr_app_token') == ''){
            update_option('twchr_app_token',$token);
                        
        }else{
            add_option('twchr_app_token',$token);
        }

    }

    // Guarda el user token validado
    function savetwchr_validateToken($twchr_validateTokenObject){ 
        global $wpdb;       
        $twchr_validateToken = $twchr_validateTokenObject->{'access_token'};
        $twchr_validateTokenRefresh = $twchr_validateTokenObject->{'refresh_token'};
        if(!twittcher_data_exist('twchr_keys_validate')){
            $array_keys = array(
                'validate-token' => $twchr_validateToken,
                'token-refresh' => $twchr_validateTokenRefresh
            );
            $json_array = json_encode($array_keys);
            $sql = "INSERT INTO wp_options VALUE(null,'twchr_keys_validate','$json_array',true)";
            //$wpdb->query($sql);
        }else{
            $array_keys = array(
                'validate-token' => $twchr_validateToken,
                'token-refresh' => $twchr_validateTokenRefresh
            );
            $json_array = json_encode($array_keys);
            $sql = "UPDATE wp_options SET option_value='$json_array' WHERE option_name='twchr_keys_validate'";
            $wpdb->query($sql);
            //var_dump($sql);
        }
        
    }
