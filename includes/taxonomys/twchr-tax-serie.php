<?php
// Manejadores de taxonomía Serie
// TODO: Cambiar el nombre de la taxonomía Serie por "Twitch_Serie"
// TODO:Acivar el Switch "Is Recurring" y manejar los errores
// Registra la taxonomía Serie en Wordpress
// twchr_tax_serie
// twchr_tax_serie_register
function twchr_tax_serie_register() {
	$labels = array(
		'name'              => _x( 'Serie', 'taxonomy general name', 'twitcher' ),
		'singular_name'     => _x( 'serie', 'taxonomy singular name', 'twitcher' ),
		'search_items'      => __( 'Search serie', 'twitcher' ),
		'all_items'         => __( 'All serie', 'twitcher' ),
		'parent_item'       => __( 'Parent serie', 'twitcher' ),
		'parent_item_colon' => __( 'Parent serie:', 'twitcher' ),
		'edit_item'         => __( 'Edit serie', 'twitcher' ),
		'update_item'       => __( 'Update serie', 'twitcher' ),
		'add_new_item'      => __( 'Add New serie', 'twitcher' ),
		'new_item_name'     => __( 'New serie Name', 'twitcher' ),
		'menu_name'         => __( 'Serie', 'twitcher' ),
	);
	$args = array(
		'hierarchical'      => false,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'serie' ),
	);
	register_taxonomy( 'serie', array('twchr_streams' ), $args );
}

add_action( 'init', 'twchr_tax_serie_register' ); // Fin Guardar taxonomía

// guarda una entrada de la taxonomía Serie en WordPress
// twchr_tax_serie
// twchr_tax_serie_save
function twchr_tax_serie_save( $term_id, $tt_id ) {

	$date_time_old = get_term_meta( $term_id, 'twchr_toApi_dateTime', true );
	$duration_old = get_term_meta( $term_id, 'twchr_toApi_duration', true );
	$select_old = get_term_meta( $term_id, 'twchr_toApi_category', true );
	$select_value_old = get_term_meta( $term_id, 'twchr_toApi_category_value', true );
	$select_name_old = get_term_meta( $term_id, 'twchr_toApi_category_name', true );
	// Saneamos lo introducido por el usuario.
	$date_time= sanitize_text_field( $_POST['twchr_toApi_dateTime'] );
	$duration = sanitize_text_field( $_POST['twchr_toApi_duration'] );
	$select_value = sanitize_text_field( $_POST['twchr_toApi_category_value'] );
	$select_name = sanitize_text_field( $_POST['twchr_toApi_category_name'] );
	$term_name = sanitize_text_field($_POST['name']);
	$timezone = (int) $_POST['twchr_toApi_timeZone'];
	$stream_id = isset($_POST['twchr_toApi_schedule_segment_id']) && !empty($_POST['twchr_toApi_schedule_segment_id']) ? sanitize_text_field($_POST['twchr_toApi_schedule_segment_id']) : get_term_meta($term_id,'twchr_toApi_schedule_segment_id',true);

	// Actualizamos el campo meta en la base de datos.
	
	update_term_meta( $term_id, 'twchr_toApi_duration', $duration, $duration_old );
	update_term_meta( $term_id, 'twchr_toApi_category_value', $select_value, $select_value_old );
	update_term_meta( $term_id, 'twchr_toApi_category_name', $select_name, $select_name_old );

	// Si vienen pos POST twchr_toApi_dateTime y twchr_toApi_duration y twchr_toApi_duration.
	if ( isset( $_POST['twchr_toApi_dateTime'] ) && isset( $_POST['twchr_toApi_duration'] ) && isset( $_POST['twchr_toApi_category_value'] ) ) {
		$schedule_segment_id = '';
		$date_time_raw = sanitize_text_field( $_POST['twchr_toApi_dateTime'] );
		$timestamp = '';
		//var_dump($date_time_raw);
		if( $timezone > 0){
			$timestamp = strtotime("+ ".abs($timezone)." hours",strtotime($date_time_raw));
		}else{
			$timestamp = strtotime("- ".abs($timezone)." hours", strtotime($date_time_raw));
		}
		
		$dateTime_rfc = date( DateTimeInterface::RFC3339, $timestamp );
		
		$duration = sanitize_text_field( $_POST['twchr_toApi_duration'] );
		$select_value = sanitize_text_field( $_POST['twchr_toApi_category_value'] );
		$tag_name = '';
		/*
			Si existe la variable 'tag-name'
			significa que se que la taxonomia se creo en el hook create-schedule
			asi $tag-name valdra 'tag-name'
		*/
		if ( isset( $_POST['tag-name'] ) ) {
			$tag_name = sanitize_text_field( $_POST['tag-name'] );

			/*
			Si no existe la variable 'tag-name'
			verifica que exista la variable 'name'

			Si la variable 'name' existe significa que
			la taxonomia se creo en el hook edit-schedule
			asi que $tag-name valdra 'name'
			*/
		} elseif ( isset( $_POST['name'] ) ) {
			$tag_name = sanitize_text_field( $_POST['name'] );
		}

		//TODO: verifica que esta serie ya existe.

		$current_schedule = '';
		if($stream_id != false){
			$current_schedule = twtchr_twitch_schedule_segment_get($stream_id);
			update_term_meta($term_id,'twchr_fromApi_allData',json_encode($current_schedule));
			$response = '';
			if(isset($current_schedule->{'message'}) && $current_schedule->{'message'} == 'Invalid OAuth token'){
				twchr_twitch_autentication_error_handdler( $current_schedule->{'error'}, $current_schedule->{'message'} );
			}
			if($current_schedule == 'segments were not found'){
				$schedule_segment_id = '';
			}
		}
		
		// no existe
		if($current_schedule == null || $stream_id == false){
			// SI la seire no existe
			// Envia los datos a la API de twich
			$response = twtchr_twitch_schedule_segment_create( $term_id, $tag_name, $dateTime_rfc, $select_value, $duration );
			$schedule_segment_id = isset($response['error']) ? 'disconected': $response['allData']->{'segments'}[0]->{'id'};
		}

		$res = '';

		if(is_array($current_schedule)){
			if(COUNT($current_schedule) == 1){
				$twchr_category = $select_value;
				$twchr_duration = $duration;
				// CAmbio term name
				$response = twtchr_twitch_schedule_segment_update('',$term_name, $stream_id,$twchr_category, $twchr_duration,$dateTime_rfc );
				update_term_meta($term_id,'twchr_fromApi_allData',json_encode($response));
				if(!isset($response['error'])){
					update_term_meta( $term_id, 'twchr_toApi_dateTime', $date_time, $date_time_old );
					$schedule_segment_id = $response['allData']->{'segments'}[0]->{'id'};
				}
			}else{
				foreach($current_schedule as $schedule){
					$title = $schedule->{'title'};
					if($title == $term_name){
						$twchr_category = $schedule->{'category'}->{'id'};
						$twchr_duration = $duration;
						
						$res = twtchr_twitch_schedule_segment_update('',$title, $stream_id,$twchr_category, $twchr_duration, $dateTime_rfc);
						update_term_meta($term_id,'twchr_fromApi_allData',json_encode($response));
						break;
					}
				}
			}
		}


			$schedule_segments_array = twtchr_twitch_schedule_segment_get();
			if(isset($schedule_segments_array->{'error'})){
				echo "<script>
					alert('message: " . $schedule_segments_array->{'message'} . "');
					alert('" . __( 'You will be redirected to the authentication page in a few seconds.', 'twitcher' ) . "');
					location.href = '" . TWCHR_ADMIN_URL . "edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true';
				</script>";
			}

		// Si exiten segmentos.
		if($schedule_segments_array != 'segments were not found'){
			$schedule_segments = array();
				/*
					* Itero la streamins desde twitch
				*/
				foreach ( $schedule_segments_array as $segment ) {
					if ( $segment->{'title'} === $tag_name ) {
						array_push( $schedule_segments, $segment );
					}
				}

				update_term_meta( $term_id, 'twchr_schdules_chapters', json_encode( $schedule_segments ) );

		}



		
		update_term_meta( $term_id, 'twchr_toApi_schedule_segment_id', $schedule_segment_id );

		// Custom fields que nesesian que exista la propiedad allData.
		if(isset($response['allData'])){
			$date_time = $response['allData']->{'segments'}[0]->{'start_time'};
			update_term_meta( $term_id, 'twchr_toApi_dateTime', $date_time);
		}else{
			update_term_meta( $term_id, 'twchr_toApi_dateTime', $date_time);
		}
		// Fin Custom fields que nesesian que exista propiedad allData.

		$allData = json_encode( $response );
		update_term_meta( $term_id, 'twchr_fromApi_allData', $allData );

		$state = isset($response['error']) ? 'error' : 'succses';
		setcookie( 'twchr_serie_twitch_response_state', $state);
		setcookie( 'twchr_serie_twitch_response_term_id', $term_id);
	}
}


add_action( 'edit_serie', 'twchr_tax_serie_save', 10, 5 );


// Redirecciona del create al Edit de la Taxonomía
// twchr_tax_serie
// twchr_tax_serie_create
function twchr_tax_serie_create( $term_id, $tt_id ) {
	if ( str_contains( $_SERVER['HTTP_REFERER'], 'edit-tags.php?taxonomy=serie&post_type=twchr_streams' ) && ! isset( $_GET['sync_serie'] ) ) :
		?>
	<script>
		location.href = '
		<?php
		echo TWCHR_ADMIN_URL . 'term.php?taxonomy=serie&tag_ID='
									. $term_id
									. '&post_type=twchr_streams&wp_http_referer=%2Fwp-admin%2Fedit-tags.php%3Ftaxonomy%3Dserie%26post_type%3Dtwchr_streams'
		?>
						';
	</script>
		<?php
		die();
	endif;
}


//add_action( 'create_serie', 'twchr_tax_serie_create', 10, 5 );

// Formulario que aparece en el Edit de la taxonomía Serie en Wordpress
// twchr_tax_serie
// twchr_tax_serie_edit
function twchr_tax_serie_edit( $term, $taxonomy ) {
	// wp_nonce_field( 'serie_cf', 'serie_cf_nonce' );
	$date_time = get_term_meta( $term->term_id, 'twchr_toApi_dateTime', true );
	$duration = get_term_meta( $term->term_id, 'twchr_toApi_duration', true );
	$select_value = get_term_meta( $term->term_id, 'twchr_toApi_category_value', true );
	$select_value = sanitize_text_field( $select_value );
	$select_name = get_term_meta( $term->term_id, 'twchr_toApi_category_name', true );
	$select_name = sanitize_text_field( $select_name );
	$allData = get_term_meta( $term->term_id, 'twchr_fromApi_allData', true );
	$schedule_segment_id = get_term_meta( $term->term_id, 'twchr_toApi_schedule_segment_id',true);
	if(!empty($allData) ){
		$allData_object = json_decode( $allData);
		if(isset($allData->{'allData'})){
			$schedule_segment_id = empty( $schedule_segment_id ) ? $allData_object->{'allData'}->{'segments'}[0]->id : $schedule_segment_id;
		}
		
	}
	$twchr_streams_relateds = get_term_meta( $term->term_id, 'twchr_schdules_chapters', true );

	$select_cat = array(
		'name' => $select_name,
		'value' => $select_value,
	);

	require_once 'form-serie.php';
}
add_action( 'serie_edit_form_fields', 'twchr_tax_serie_edit', 10, 2 );

/**
 *  Esta funcion importa shcedules segments como un taxnomia serie
 *	twchr_tax_serie
 *  twchr_tax_serie_import
 * @return void
 */
function twchr_tax_serie_import() {
	?>
   <a class="twchr-btn-general twchr-btn-general-lg" href="<?php echo TWCHR_ADMIN_URL; ?>edit-tags.php?taxonomy=serie&post_type=twchr_streams&sync_serie=true">import serie</a>
	<?php
	/*
	* Si por GET llega un ID de CPT y nombre de un CPT Crea una serie con esos datos
	* y luego redirecciona al edit de la serie recien creada.
	*/
	if ( isset( $_GET['from_cpt_id']) && isset( $_GET['from_cpt_name']) ) {
		$name = sanitize_text_field($_GET['from_cpt_name']);
		$term = wp_create_term( $name, 'serie' );
		$term_id = $term['term_id'];
		echo "<script>location.href='" . TWCHR_ADMIN_URL . 'term.php?taxonomy=serie&tag_ID=' . $term_id . "&post_type=twchr_streams'</script>";
		die();

		/*
		* Si por GET llega un ID de CPT y no un nombre de un CPT entonces crea una serie con esos el ID
		* y crea un nombre de la siguiente manera: "serie-{$cpt-id}"y luego redirecciona al edit de la serie recien creada.
		*/
	}else if( isset( $_GET['from_cpt_id'])){
		$name =  "serie-".$_GET['from_cpt_id'];
		$term = wp_create_term( $name, 'serie' );
		$term_id = $term['term_id'];
		echo "<script>location.href='" . TWCHR_ADMIN_URL . 'term.php?taxonomy=serie&tag_ID=' . $term_id . "&post_type=twchr_streams'</script>";
		die();
	}
	
	if ( isset( $_GET['sync_serie'] ) && $_GET['sync_serie'] == 'true' ) {
		
		// FROM TWCH
		$schedules_twitch = twtchr_twitch_schedule_segment_get();

		// FROM WP
		$schedules_wp = get_terms(
			array(
				'taxonomy' => 'serie',
				'hide_empty' => false,
			)
		);

		//var_dump($schedules_twitch);

		if ( isset( $schedules_twitch->{'error'} ) ) {
			
			twchr_twitch_autentication_error_handdler( $schedules_twitch->{'error'}, $schedules_twitch->{'message'} );
		}


		
		$response = twchr_tax_serie_update($schedules_twitch);
		if($response == 'segments were not found'){
			echo "<script>alert('segments were not found');location.href='" . TWCHR_ADMIN_URL . "edit-tags.php?taxonomy=serie&post_type=twchr_streams'</script>";
		}else{
			echo "<script>location.href='" . TWCHR_ADMIN_URL . "edit-tags.php?taxonomy=serie&post_type=twchr_streams'</script>";
		}
		die();
		
	}
}

 add_action( 'serie_pre_add_form', 'twchr_tax_serie_import' );
