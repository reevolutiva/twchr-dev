<?php 

function twitcher_update_cpt($post_id, $twchr_stream_id, $twchr_stream_title, $user_id, $twchr_start_time, $twchr_end_time, $all_data){
    $data = array(
        'ID' => $post_id,
        'meta_input'   => array(
            'twchr-from-api_stream_id' => $twchr_stream_id,
            'twchr-from-api_title' => $twchr_stream_title,
            'twchr_stream_user_id' => $user_id,
            'twchr-from-api_url' => $url,
            'twchr-from-api_start-time' => $twchr_start_time,
            'twchr-from-api_end-time' => $twchr_end_time,
            'twchr-from-api_all-data' => $all_data
        )
    );

    wp_update_post($data);

}
