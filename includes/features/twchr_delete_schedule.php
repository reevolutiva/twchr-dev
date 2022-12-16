<?php 
    function twchr_delete_schedule($post_id){
        $is_recurrig = get_post_meta($post_id,'twchr_schedule_card_input--is_recurrig')[0];
        // ESTE CPT es una serie
        if($is_recurrig == true){
            $series = wp_get_post_terms($post_id, 'serie');
            $series_id = $series[0]->{'term_id'};
            $schedule_related = get_term_meta($series_id,'twchr_streams_relateds');
            $schedule_related = json_decode($schedule_related[0]);
            $schedule_id = '';
            foreach($schedule_related as $schedule){
                if($schedule->{'wp_stream_id'} == $post_id){
                    $schedule_id = $schedule->{'twicth_id'};
                }
            }
            $twchr_titulo =  get_the_title($post_id);
    
        }else{
        // ESTE CPT es un solo streaming
            $schedule_id = get_post_meta($post_id,'twchr_stream_twtich_schedule_id');

        }
        $delete = twtchr_twitch_schedule_segment_delete($post_id,$twchr_titulo,$schedule_id);
        
        if($delete != null && $delete['status'] != 204){
            //var_dump($delete);
            $url_list = '';
            $url_main = TWCHR_ADMIN_URL."edit.php?post_type=twchr_streams";
            foreach($delete as $key => $value){
                $url_list .= '&' . $key . '=' . $value;
            }

            
            //echo "<script>location.href='".$url_main.$url_list."';</script>";
            //die();
        }
        
    }

    add_action('wp_trash_post','twchr_delete_schedule');
?>