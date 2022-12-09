<?php
//Manejadores de taxonomía Series

//Registra la taxonomía Serie en Wordpress
function twchr_taxonomy_serie() {
    $labels = array(
        'name'              => _x( 'Series', 'taxonomy general name' , 'twitcher'),
        'singular_name'     => _x( 'serie', 'taxonomy singular name' , 'twitcher'),
        'search_items'      => __( 'Search series' , 'twitcher'),
        'all_items'         => __( 'All series' , 'twitcher'),
        'parent_item'       => __( 'Parent serie' , 'twitcher'),
        'parent_item_colon' => __( 'Parent serie:' , 'twitcher'),
        'edit_item'         => __( 'Edit serie' , 'twitcher'),
        'update_item'       => __( 'Update serie' , 'twitcher'),
        'add_new_item'      => __( 'Add New serie' , 'twitcher'),
        'new_item_name'     => __( 'New serie Name' , 'twitcher'),
        'menu_name'         => __( 'Series' , 'twitcher'),
    );
    $args = array( 
        'hierarchical'      => false, 
		 'labels'            => $labels,
		 'show_ui'           => true,
		 'show_admin_column' => true,
		 'query_var'         => true,
		 'rewrite'           => [ 'slug' => 'serie' ],
    );
    register_taxonomy( 'serie', array( 'post', 'twchr_streams' ), $args );
}

//guarda una entrada de la taxonomía Serie en WordPress
function twchr_taxnonomy_save( $term_id, $tt_id ) {
    
    $dateTime_old = get_term_meta( $term_id, 'twchr_toApi_dateTime', true );
    $duration_old = get_term_meta( $term_id, 'twchr_toApi_duration', true );
    $select_old = get_term_meta( $term_id, 'twchr_toApi_category', true );
    $select_value_old = get_term_meta($term_id,'twchr_toApi_category_value',true);
    $select_name_old = get_term_meta($term_id,'twchr_toApi_category_name',true);
            
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
            
        // Recoje data de BDD
        $twch_data_prime = json_decode(get_option( 'twchr_keys', false ));
        $tokenValidate = $twch_data_prime->{'user_token'};
        $client_id = $twch_data_prime->{'client-id'};

        $dateTime_raw = sanitize_text_field($_POST['twchr_toApi_dateTime']);
        $dateTime_stg = strtotime($dateTime_raw);
        $dateTime_rfc = date(DateTimeInterface::RFC3339,$dateTime_stg);

        $duration = sanitize_text_field($_POST['twchr_toApi_duration']);
        $select_value = sanitize_text_field($_POST['twchr_toApi_category_value']);
        $tag_name = '';
        /*
            Si existe la variable 'tag-name'
            significa que se que la taxonomia se creo en el hook create-schedule
            asi $tag-name valdra 'tag-name'
        */
        if(isset($_POST['tag-name'])){
            $tag_name = sanitize_text_field($_POST['tag-name']);

        /*
            Si no existe la variable 'tag-name'
            verifica que exista la variable 'name'

            Si la variable 'name' existe significa que 
            la taxonomia se creo en el hook edit-schedule
            asi que $tag-name valdra 'name'
        */    
        }elseif (isset($_POST['name'])) {
            $tag_name = sanitize_text_field($_POST['name']);
        }
        // Envia los datos a la API de twich
        $response = twtchr_twitch_schedule_update($term_id,$tokenValidate,$client_id,$tag_name,$dateTime_rfc ,$select_value,$duration);
        $allData = json_encode($response);          
        update_term_meta($term_id,'twchr_fromApi_allData',$allData);
   }
  }
add_action('init', 'twchr_taxonomy_serie'); //Fin Guardar taxonomía

add_action( 'edit_serie', 'twchr_taxnonomy_save', 10,5);

add_action( 'create_serie', 'twchr_save_serie_redirect', 10,5);

//Redirecciona del create al Edit de la Taxonomía
function twchr_save_serie_redirect($term_id, $tt_id){
   ?>
    <script>
        location.href = '<?php echo TWCHR_ADMIN_URL."term.php?taxonomy=serie&tag_ID="
                                    .$term_id
                                    ."&post_type=twchr_streams&wp_http_referer=%2Fwp-admin%2Fedit-tags.php%3Ftaxonomy%3Dserie%26post_type%3Dtwchr_streams" ?>
                        ';
    </script>
   <?php
   die();
}


//Formulario que aparece en el Edit de la taxonomía Series en Wordpress
function twchr_edit_taxonomy_cf_to_api($term,$taxonomy) {
    //wp_nonce_field( 'serie_cf', 'serie_cf_nonce' );
    $dateTime = get_term_meta( $term->term_id, 'twchr_toApi_dateTime', true );
	$duration = get_term_meta( $term->term_id, 'twchr_toApi_duration', true );
    $select_value = get_term_meta($term->term_id,'twchr_toApi_category_value',true);
    $select_value = sanitize_text_field($select_value);
    $select_name = get_term_meta($term->term_id,'twchr_toApi_category_name',true);
    $select_name = sanitize_text_field($select_name);
	$allData = get_term_meta( $term->term_id, 'twchr_fromApi_allData', true );

    $select_cat = array(
        'name' => $select_name,
        'value' => $select_value
    );
    //show_dump($dateTime);

	require_once 'form_programs.php';
}
add_action( 'serie_edit_form_fields', 'twchr_edit_taxonomy_cf_to_api',10, 2 );
