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

    function fronted_to_db($apikey,$clientId){
        global $wpdb;
        if(!twittcher_data_exist('twitcher_keys')){
            $array_keys = array(
                'api-key' => $apikey,
                'client-id' => $clientId
            );
            $json_array = json_encode($array_keys);
            $sql = "INSERT INTO wp_options VALUE(null,'twitcher_keys','$json_array',true)";
            $wpdb->query($sql);
        }else{
            $array_keys = array(
                'api-key' => $apikey,
                'client-id' => $clientId
            );
            $json_array = json_encode($array_keys);
            $sql = "UPDATE wp_options SET option_value='$json_array' WHERE option_name='twitcher_keys'";
            $wpdb->query($sql);
        }
        
        var_dump($wpdb->result);
    }