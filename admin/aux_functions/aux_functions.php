<?php
//TODO:Describir función
// Especifico el meta key y meta value de un custom field y si esos canmpos existen
// retorna true y si no existen retorna false
function twchr_validate_cf_db_exist($key,$value){
    global $wpdb;
    $sql = "SELECT * FROM wp_postmeta WHERE meta_key = '$key' AND meta_value = '$value';";
    $wpdb->query($sql);
    $response = $wpdb->{'last_result'};
    if(COUNT($response) > 0){
        return $response;
    }else{
        return false;
    }
    
}

/**
 * Extrae el value de un objecto de tipo JSON por su Key
 *
 * @param [type] $key
 * @param [type] $json
 * @return void
 */
function twchr_object_get_value_by($key,$json){
    $object = json_decode($json);
    return $object->{$key};
}

/**
 * Extrae el value de un array de tipo JSON por su indice
 *
 * @param [type] $key
 * @param [type] $json
 * @return void
 */
function twchr_array_get_value_by($key,$json){
    $array = json_decode($json);
    return $array[$key];
}
