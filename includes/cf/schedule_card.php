<?php
function twchr_cf_schedule__card(){
    $values  = get_post_custom($_GET['post']);

    $title = isset($values['twchr_schedule_card_input--title']) ? $values['twchr_schedule_card_input--title'][0] : '';
    $category = isset($values['twchr_schedule_card_input--category']) ? $values['twchr_schedule_card_input--category'][0] : '';
    $dateTime = isset($values['twchr_schedule_card_input--dateTime']) ? $values['twchr_schedule_card_input--dateTime'][0] : '';
    $duration = isset($values['twchr_schedule_card_input--duration']) ? $values['twchr_schedule_card_input--duration'][0] : '';
    $is_recurring = isset($values['twchr_schedule_card_input--is_recurrig']) ? $values['twchr_schedule_card_input--is_recurrig'][0] : '';
    $serie = isset($values['twchr_schedule_card_input--serie']) ? $values['twchr_schedule_card_input--serie'][0] : '';
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

            //var_dump($_POST);
            //die();
            
            if ( isset( $_POST['twchr_schedule_card_input--title'] ) ) {
                add_post_meta( $post_id, 'twchr_schedule_card_input--title', wp_kses( $_POST['twchr_schedule_card_input--title'], $allowed ) );
            }
            if ( isset( $_POST['twchr_schedule_card_input--category'] ) ) {
                add_post_meta( $post_id, 'twchr_schedule_card_input--category', wp_kses( $_POST['twchr_schedule_card_input--category'], $allowed ) );
            }
            if ( isset( $_POST['twchr_schedule_card_input--dateTime'] ) ) {
                add_post_meta( $post_id, 'twchr_schedule_card_input--dateTime', wp_kses( $_POST['twchr_schedule_card_input--dateTime'], $allowed ) );
            }
            if ( isset( $_POST['twchr_schedule_card_input--duration'] ) ) {
                add_post_meta( $post_id, 'twchr_schedule_card_input--duration', wp_kses( $_POST['twchr_schedule_card_input--duration'], $allowed ) );
            }
            if ( isset( $_POST['twchr_schedule_card_input--is_recurring'] ) ) {
                add_post_meta( $post_id, 'twchr_schedule_card_input--is_recurring', wp_kses( $_POST['twchr_schedule_card_input--is_recurring'], $allowed ) );
            }
            if ( isset( $_POST['twchr_schedule_card_input--serie'] ) ) {
                add_post_meta( $post_id, 'twchr_schedule_card_input--serie', wp_kses( $_POST['twchr_schedule_card_input--serie'], $allowed ) );
            }
          
    }
    
    
    add_action( 'save_post', 'twchr_cf_schedule__card__metadata_save' );