<?php

function twchr_tax_calendar_import()
{

 ?>
   <a class="twchr-btn-general twchr-btn-general-lg" href="<?php echo TWCHR_ADMIN_URL ?>edit-tags.php?taxonomy=calendar&post_type=twchr_streams&sync_calendar=true">import calendar</a>
<?php
    if(isset($_GET['sync_calendar']) && $_GET['sync_calendar'] == 'true'){
      

        //FROM TWCH
        $schedules_twitch = twtchr_twitch_schedule_segment_get();

        // FROM WP
        $schedules_wp = get_terms(array(
            'taxonomy' => 'calendar',
            'hide_empty' => false
        ));

        foreach($schedules_wp as $item){
            $id = $item->term_id;
            $title = $item->title;
            wp_delete_term($id,$title);
        }
    
        // CLIKC BUTTON -> foreach (item -> item.delryr)
        // GET LIST SCHEDULE -> foreach (item -> item.title | insert_term(items.title) | insert_meta_term
        // // item.title exist == false insert_term(items.title) | insert_meta_term
             
           
        
    
                foreach($schedules_twitch->data->segments as $schedule){
                    $tw_id = $schedule->{'id'};
                    if($tw_id == $wp_tw_id){
                    }else{
                        $new_term = wp_insert_term($schedule->title, 'calendar');
                        
                        if(isset($new_term->errors['term_exists'])){
                            //TODO: Poner esta redireccion en el error handler
                            echo "<script>location.href='".TWCHR_ADMIN_URL."edit-tags.php?taxonomy=calendar&post_type=twchr_streams'</script>";
                            die();
                        }

                        $new_term_id = $new_term['term_id'];
                       
                        
                        $dateTime = $schedule->start_time;
                        add_term_meta($new_term_id,'twchr_toApi_dateTime',$dateTime);
                        $select_value = $schedule->category->id;
                        add_term_meta($new_term_id,'twchr_toApi_category_value',$select_value);
                        $select_name = $schedule->category->name;
                        add_term_meta($new_term_id,'twchr_toApi_category_name',$select_name);
                        $schedule_segment_id = $schedule->id;
                        add_term_meta($new_term_id,'twchr_toApi_schedule_segment_id',$schedule_segment_id);
                        $schedule_is_recurring = $schedule->is_recurring;
                        add_term_meta($new_term_id,'twchr_toApi_schedule_is_recurring',$schedule_is_recurring);
                        $allData = json_encode($schedule);
                        add_term_meta($new_term_id,'twchr_fromApi_allData',$allData);
                    }
                   
                }
            
       
                                    
        
    }  
}

 add_action('calendar_pre_add_form','twchr_tax_calendar_import');
