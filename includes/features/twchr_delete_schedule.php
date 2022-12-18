<?php 
    function twchr_delete_schedule_by_cpt($post_id){
        $is_recurrig = get_post_meta($post_id,'twchr_schedule_card_input--is_recurrig')[0];
    
        if($is_recurrig === false){
        // ESTE CPT es un solo streaming
            $schedule_id = get_post_meta($post_id,'twchr_stream_twtich_schedule_id');
            $twchr_titulo = get_the_title($post_id);
            $delete = twtchr_twitch_schedule_segment_delete($schedule_id,$twchr_titulo,$post_id);
        }
        
        
        if($delete != null && $delete['status'] != 204){
            //var_dump($delete);
            $url_list = '';
            $url_main = TWCHR_ADMIN_URL."edit.php?post_type=twchr_streams";
            foreach($delete as $key => $value){
                $url_list .= '&' . $key . '=' . $value;
            }
            echo "<script>location.href='".$url_main.$url_list."';</script>";
            die();
        }
        
    }

    add_action('wp_trash_post','twchr_delete_schedule_by_cpt');

    function twchr_delete_schedule_by_term($term_id){
        // Guarda schedule_id del array en twchr_streams_relateds
        $schedule_related = get_term_meta($term_id, 'twchr_streams_relateds')[0];
        $schedule_id = '';

        // Si $schedule_related esta vacio
        if($schedule_related == null){
            // obtiene $schedule_related de custom_field twchr_toApi_schedule_segment_id
            $schedule_id = get_term_meta($term_id, 'twchr_toApi_schedule_segment_id')[0];
            twtchr_twitch_schedule_segment_delete($schedule_id);
            
        }else{
            $schedule_related = json_decode($schedule_related);
            foreach($schedule_related as $chapter){
                $schedule_id = $chapter->{'twicth_id'};
                $post_id = $chapter->{'wp_stream_id'};
                // Elimina schedule segment de twitch.tv/
                twtchr_twitch_schedule_segment_delete($schedule_id);
                // Elimina cpt stream 
                wp_delete_post($post_id,true);
            }
        }
        
        
    }

    add_action('pre_delete_term','twchr_delete_schedule_by_term',10);