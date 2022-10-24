<?php

//TODO: usar create_{taxonomy} y edit_{taxonomy}

add_action( 'schedule_add_form_fields', 'twchr_add_taxonomy_cf_to_api');

function twchr_add_taxonomy_cf_to_api($taxonomy) {
    ?>
        <style>
        .twchr_toApi_form-field{
            width: 95%;
            display: grid;
            grid-template-columns:200px 1fr;
            grid-gap:30px 20px;
        }
        .twchr_toApi_form-field label{
            line-height: 1.3;
            font-weight: 600;
        }

        .twchr_toApi_form-field input,
        .twchr_toApi_form-field select{
            width: 100%;
            display: block;
            max-width:none;
        }

        @media screen and (max-width: 782px){
            .twchr_toApi_form-field{
                grid-template-columns:1fr;
                width: 100%;
                grid-gap:10px 0px;
            }
        }
    </style>
    <div class='twchr_toApi_form-field'>
        <label for="twchr_toApi_dateTime">Fecha y hora</label>
        <div>
            <input type="datetime-local" id="twchr_toApi_dateTime" name='twchr_toApi_dateTime' value="">
            <p>La fecha recurente en que se emitira tu transmisión.</p>
        </div>
        <label for="twchr_toApi_duration">Duration (minutos)</label>
        <div>
            <input type="number" id="twchr_toApi_duration" name="twchr_toApi_duration" value="">
            <p>Tiempo promedio que dura su transmición.</p>
        </div>
        <label for="twchr_toApi_category">Categoria</label>
        <div>
            <input type="text" name="twchr_toApi_category_ajax" id="twchr_toApi_category_ajax" placeholder="escribe tu categoria">
            <p>Categoria del stream</p>
        </div>
        <input type="hidden" name="twchr_toApi_category_value" id='twchr_toApi_category_value'>
        <input type="hidden" name="twchr_toApi_category_name" id='twchr_toApi_category_name'>
    </div>
    <?php
    
}
add_action( 'schedule_edit_form_fields', 'twchr_edit_taxonomy_cf_to_api',10, 2 );

function twchr_edit_taxonomy_cf_to_api($term,$taxonomy) {
    //wp_nonce_field( 'schedule_cf', 'schedule_cf_nonce' );
    $dateTime = get_term_meta( $term->term_id, 'twchr_toApi_dateTime', true );
	$duration = get_term_meta( $term->term_id, 'twchr_toApi_duration', true );
    $select_value = get_term_meta($term->term_id,'twchr_toApi_category_value',true);
    $select_name = get_term_meta($term->term_id,'twchr_toApi_category_name',true);
	$allData = get_term_meta( $term->term_id, 'twchr_fromApi_allData', true );
    //show_dump($dateTime);

	require_once 'form_programs.php';
}


add_action('init', 'twchr_taxonomy_schedule');
function twchr_taxonomy_schedule() {
    $labels = array(
        'name'              => _x( 'Schedules', 'taxonomy general name' ),
        'singular_name'     => _x( 'Schedule', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Schedules' ),
        'all_items'         => __( 'All Schedules' ),
        'parent_item'       => __( 'Parent Schedule' ),
        'parent_item_colon' => __( 'Parent Schedule:' ),
        'edit_item'         => __( 'Edit Schedule' ),
        'update_item'       => __( 'Update Schedule' ),
        'add_new_item'      => __( 'Add New Schedule' ),
        'new_item_name'     => __( 'New Schedule Name' ),
        'menu_name'         => __( 'Schedule' ),
    );
    $args = array( 
        'hierarchical'      => false, 
		 'labels'            => $labels,
		 'show_ui'           => true,
		 'show_admin_column' => true,
		 'query_var'         => true,
		 'rewrite'           => [ 'slug' => 'schedule' ],
    );
    register_taxonomy( 'schedule', array( 'post', 'twchr_streams' ), $args );
}

function twchr_taxnonomy_save( $term_id ) {
    // Comprobamos si se ha definido el nonce.
    
    /*
    if ( ! isset( $_POST['schedule_cf_nonce'] ) ) {
      return $term_id;
    }
    $nonce = $_POST['schedule_cf_nonce'];
        
    // Verificamos que el nonce es válido.
    if ( !wp_verify_nonce( $nonce, 'schedule_cf' ) ) {
      return $term_id;
    }
    */
    
    
    
    $dateTime_old = get_term_meta( $term->term_id, 'twchr_toApi_dateTime', true );
    $duration_old = get_term_meta( $term->term_id, 'twchr_toApi_duration', true );
    $select_old = get_term_meta( $term->term_id, 'twchr_toApi_category', true );
    $select_value_old = get_term_meta($term->term_id,'twchr_toApi_category_value',true);
    $select_name_old = get_term_meta($term->term_id,'twchr_toApi_category_name',true);
    
        
    // Saneamos lo introducido por el usuario.            
    $dateTime = sanitize_text_field($_POST['twchr_toApi_dateTime']);
    $duration = sanitize_text_field($_POST['twchr_toApi_duration']);
    $select_value = sanitize_text_field($_POST['twchr_toApi_category_value']);
    $select_name = sanitize_text_field($_POST['twchr_toApi_category_name']);
    
        
    // Actualizamos el campo meta en la base de datos.
    update_term_meta($term_id,'twchr_toApi_dateTime',$dateTime,$dateTime_old);
    update_term_meta($term_id,'twchr_toApi_duration',$duration, $duration_old);
    update_term_meta($term_id,'twchr_toApi_category_value',$select_value, $select_value_old);
    update_term_meta($term_id,'twchr_toApi_category_name',$select_name, $select_name_old);
    

    if(isset($_POST['twchr_toApi_dateTime']) && isset($_POST['twchr_toApi_duration']) && isset($_POST['twchr_toApi_category_value']) ){
            $response = schedule_update($term_id);
         
            $allData = json_encode($response);
            update_term_meta($term_id,'twchr_fromApi_allData',$allData);
            
            //wp_redirect(site_url('wp-admin/term.php?taxonomy=schedule&tag_ID='.$term_id));
            //exit;
            //die();
        
   }

   //echo "hola";
  }
  add_action( 'edit_schedule', 'twchr_taxnonomy_save' );
  add_action( 'create_schedule', 'twchr_taxnonomy_save' );


  function schedule_endpoint() {
    register_rest_route( 'twchr/', 'twchr_get_schedule', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'get_schedule',
    ) );
}

add_action( 'rest_api_init', 'schedule_endpoint' );

function get_schedule( $request ) {
    $args = array(
        'taxonomy' => 'schedule',
        'hide_empty' => false
    );
    $request = get_terms($args);
    $response = array();
    foreach($request as $term){
        $term_id = $term->{'term_id'};
        $array_rest = array(
            "term_id" => $term_id,
            "name" => $term->{'name'},
            "taxonomy" => $term->{'taxonomy'},
            "dateTime" => get_term_meta( $term_id, 'twchr_toApi_dateTime', true ),
            "duration" => get_term_meta( $term_id, 'twchr_toApi_duration', true ),
            "select" => get_term_meta( $term_id, 'twchr_toApi_category', true ),
            "dataFromTwitch" => get_term_meta( $term_id, 'twchr_fromApi_allData', true )
        );

        array_push($response, $array_rest);
    }
 
    /*
    $args = array(
        'taxonomy'               => 'schedule',
        'orderby'                => 'name',
        'order'                  => 'ASC',
        'hide_empty'             => false,
    );
    $the_query = new WP_Term_Query($args);
    foreach($the_query->get_terms() as $term){ 

    }
    */

    return $response;
}