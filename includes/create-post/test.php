<?php 
function crearStream($id,$title,$user_id,$description,$url,$duration,$all_data){
    $postarr = array(
        'post_title' => $title,
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'ego_stream',
        'meta_input'   => array(
            'ego_stream_id' => $id,
            'ego_stream_title' => $title,
            'ego_stream_user_id' => $user_id,
            'ego_stream_description' => $description,
            'ego_stream_url' => $url,
            'ego_stream_duration' => $duration,
            'ego_stream_all_data' => $all_data,
        )
    );

    wp_insert_post($postarr);
}

?>