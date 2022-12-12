<?php
//Manejadores de taxonomía Calendar
//TODO: Cambiar el nombre de la taxonomía Serie por "Twitch_Calendar"
//TODO:Acivar el Switch "Is Recurring" y manejar los errores
//Registra la taxonomía Serie en Wordpress
//twchr_tax_calendar
//twchr_tax_calendar_register  
function twchr_tax_calendar_register() {
    $labels = array(
        'name'              => _x( 'Calendar', 'taxonomy general name' , 'twitcher'),
        'singular_name'     => _x( 'calendar', 'taxonomy singular name' , 'twitcher'),
        'search_items'      => __( 'Search calendar' , 'twitcher'),
        'all_items'         => __( 'All calendar' , 'twitcher'),
        'parent_item'       => __( 'Parent calendar' , 'twitcher'),
        'parent_item_colon' => __( 'Parent calendar:' , 'twitcher'),
        'edit_item'         => __( 'Edit calendar' , 'twitcher'),
        'update_item'       => __( 'Update calendar' , 'twitcher'),
        'add_new_item'      => __( 'Add New calendar' , 'twitcher'),
        'new_item_name'     => __( 'New calendar Name' , 'twitcher'),
        'menu_name'         => __( 'Calendar' , 'twitcher'),
    );
    $args = array( 
        'hierarchical'      => false, 
		 'labels'            => $labels,
		 'show_ui'           => true,
		 'show_admin_column' => true,
		 'query_var'         => true,
		 'rewrite'           => [ 'slug' => 'calendar' ],
    );
    register_taxonomy( 'calendar', array( 'post', 'twchr_streams' ), $args );
}

add_action('init', 'twchr_tax_calendar_register'); //Fin Guardar taxonomía

//guarda una entrada de la taxonomía Serie en WordPress
// twchr_tax_calendar
// twchr_tax_calendar_save
function twchr_tax_calendar_save( $term_id, $tt_id ) {
    
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
        $response = twtchr_twitch_schedule_segment_update($term_id,$tokenValidate,$client_id,$tag_name,$dateTime_rfc ,$select_value,$duration);
        $allData = json_encode($response);          
        update_term_meta($term_id,'twchr_fromApi_allData',$allData);
   }
  }


add_action( 'edit_calendar', 'twchr_tax_calendar_save', 10,5);


//Redirecciona del create al Edit de la Taxonomía
// twchr_tax_calendar
// twchr_tax_calendar_create
function twchr_tax_calendar_create($term_id, $tt_id){
   ?>
    <script>
        location.href = '<?php echo TWCHR_ADMIN_URL."term.php?taxonomy=calendar&tag_ID="
                                    .$term_id
                                    ."&post_type=twchr_streams&wp_http_referer=%2Fwp-admin%2Fedit-tags.php%3Ftaxonomy%3Dcalendar%26post_type%3Dtwchr_streams" ?>
                        ';
    </script>
   <?php
   die();
}

add_action( 'create_calendar', 'twchr_tax_calendar_create', 10,5);

//Formulario que aparece en el Edit de la taxonomía Calendar en Wordpress
//twchr_tax_calendar
//twchr_tax_calendar_edit
function twchr_tax_calendar_edit($term,$taxonomy) {
    //wp_nonce_field( 'calendar_cf', 'calendar_cf_nonce' );
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
add_action( 'calendar_edit_form_fields', 'twchr_tax_calendar_edit',10, 2 );

// twchr_tax_calendar
// twchr_tax_calendar_import
function twchr_tax_calendar_import()
{
 ?>
   <a class="twchr-btn-general twchr-btn-general-lg" href="<?php echo TWCHR_ADMIN_URL ?>edit-tags.php?taxonomy=calendar&post_type=twchr_streams&sync_calendar=true">import calendar</a>
<?php
    if(isset($_GET['sync_calendar']) && $_GET['sync_calendar'] == 'true'){
        $twch_data_prime = get_option('twchr_keys') == false ? false : json_decode(get_option('twchr_keys'));
        $client_id = $twch_data_prime->{'client-id'};
        $user_token = $twch_data_prime->{'user_token'};

        //FROM TWCH
        $schedules_twitch = twtchr_twitch_schedule_segment_get($user_token,$client_id);
        // FROM WP
        $schedules_wp = get_terms(array(
            'taxonomy' => 'calendar',
            'hide_empty' => false
        ));

        if(!COUNT($schedules_wp) == 0){
            foreach($schedules_wp as $schedule_wp){
                var_dump($schedule_wp);
                /*foreach($schedules_twitch as $schedule_tw){
                
                } 
                */ 
            }
        }else{
            ?>
            <section class="twchr-alert twchr-alert-inner">
                <img src="<?php echo TWCHR_URL_ASSETS ?>/warning.png" alt="">
                <h3 class="twchr-alert__title"><?php _e("oops! you haven't saved any events to your twitch calendar yet.", 'twitcher'); ?></h3>
            </section>
            <?php
        }
                                    
        
    }  
}

 add_action('calendar_pre_add_form','twchr_tax_calendar_import');
