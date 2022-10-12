<?php
function crearStream($id,$title,$user_id,$description,$url,$duration,$stream_id,$all_data){
    $postarr = array(
        'post_title' => $title,
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'twchr_streams',
        'post_content' => '[twich_embed host="reevolutiva" video="'.$id.'"  ancho="800" alto="400"]',
        'meta_input'   => array(
            'twchr-from-api_id' => $id,
            'twchr-from-api_title' => $title,
            'twchr-from-api_user_id' => $user_id,
            'twchr-from-api_description' => $description,
            'twchr-from-api_url' => $url,
            'twchr-from-api_duration' => $duration,
            'twchr-from-api_stream_id' => $stream_id,
            'twchr-from-api_all-data' => $all_data,
        )
    );

    wp_insert_post($postarr);
}

?>