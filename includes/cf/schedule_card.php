<?php
function twchr_cf_schedule__card(){
    $post_id = $_GET['post'];
    $term_serie = wp_get_post_terms($post_id, 'serie');
    $term_serie_list = '';
    foreach($term_serie as $term){
        $str = "<span>".$term->{'slug'}."</span>";
        $term_serie_list = $term_serie_list.$str;
    }
    $term_cat_twcht_list = '';
    $term_cat_twcht = wp_get_post_terms($post_id, 'cat_twcht');
    //var_dump($term_cat_twcht);
    foreach($term_cat_twcht as $term){
        $str = "<span>".$term->{'slug'}."</span>";
        $term_cat_twcht_list = $term_cat_twcht_list.$str;
    }
    $values  = get_post_custom($post_id);
    
    $title = isset($values['twchr_schedule_card_input--title']) ? $values['twchr_schedule_card_input--title'][0] : '';
    $category = isset($values['twchr_schedule_card_input--category']) ? $values['twchr_schedule_card_input--category'][0] : '';
    $dateTime = isset($values['twchr_schedule_card_input--dateTime']) ? $values['twchr_schedule_card_input--dateTime'][0] : '';
    $duration = isset($values['twchr_schedule_card_input--duration']) ? $values['twchr_schedule_card_input--duration'][0] : '';
    
    $is_recurring = isset($values['twchr_schedule_card_input--is_recurrig']) ? $values['twchr_schedule_card_input--is_recurrig'][0] : false;
    $serie = isset($values['twchr_schedule_card_input--serie']) ? $values['twchr_schedule_card_input--serie'][0] : '';
    //var_dump($term_serie);
    require_once 'schedule_custom_card.php';
}

add_action('edit_form_after_title','twchr_cf_schedule__card');

function twchr_cf_schedule__card__metadata_save($post_id){
    /* 
    Antes de guardar la información, necesito verificar tres cosas:
        1. Si la entrada se está autoguardando
        2. Comprobar que el usuario actual puede realmente modificar este contenido.
    */
        
        if (! current_user_can( 'edit_posts' )) {
            return;
        }		
        
    
            $allowed = array();

            $dateTime_raw = sanitize_text_field($_POST['twchr_schedule_card_input--dateTime']);
            $dateTime_stg = strtotime($dateTime_raw);
            $dateTime_rfc = date(DateTimeInterface::RFC3339,$dateTime_stg);
            
            
            if ( isset( $_POST['twchr_schedule_card_input--title'] ) ) {
                update_post_meta( $post_id, 'twchr_schedule_card_input--title', wp_kses( $_POST['twchr_schedule_card_input--title'], $allowed ) );
            }
            
            if ( isset( $_POST['twchr_schedule_card_input--dateTime'] ) ) {
                
                update_post_meta( $post_id, 'twchr_schedule_card_input--dateTime', wp_kses( $_POST['twchr_schedule_card_input--dateTime'], $allowed ) );
                
            }
            if ( isset( $_POST['twchr_schedule_card_input--duration'] ) ) {
                update_post_meta( $post_id, 'twchr_schedule_card_input--duration', wp_kses( $_POST['twchr_schedule_card_input--duration'], $allowed ) );
            }
            if ( isset( $_POST['twchr_schedule_card_input--is_recurring'] ) ) {
                update_post_meta( $post_id, 'twchr_schedule_card_input--is_recurring', wp_kses( $_POST['twchr_schedule_card_input--is_recurring'], $allowed ) );
            }
            if ( twchr_post_isset_and_not_empty('twchr_schedule_card_input--serie__id')) {
                wp_set_post_terms($post_id,[(int)$_POST['twchr_schedule_card_input--serie__id']] ,'serie');
            }
            if (twchr_post_isset_and_not_empty('twchr_schedule_card_input--category__value') && twchr_post_isset_and_not_empty('twchr_schedule_card_input--category__name')) {
                $cat_twitch_id = (int)$_POST['twchr_schedule_card_input--category__value'];
                $cat_twitch_name = $_POST['twchr_schedule_card_input--category__name'];
                $actual_terms = get_terms(array( 'taxonomy' => 'cat_twcht', 'hide_empty' => false));
                // Creo una taxonomia cat_twcht
                $response = wp_create_term($cat_twitch_name,'cat_twcht');
               
                $id = (int)$response['term_id'];
                wp_set_post_terms($post_id,[$id],'cat_twcht');
                    
                    

                
            }
          
    }
    
    
    add_action( 'save_post', 'twchr_cf_schedule__card__metadata_save' );