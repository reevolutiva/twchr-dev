<?php
function crear_page_setUP(){
    if(twchr_post_db_exist('page','Twttcher SetUp') === false){
        $postarr = array(
            'post_title' => 'Twttcher SetUp',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'page',
            'post_content' => '[twchr-setup]'
        );

        wp_insert_post($postarr);
    }
}