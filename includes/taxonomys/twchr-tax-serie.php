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

	$dateTime_old = get_term_meta( $term_id, 'twchr_toApi_dateTime', true );
	$duration_old = get_term_meta( $term_id, 'twchr_toApi_duration', true );
	$select_old = get_term_meta( $term_id, 'twchr_toApi_category', true );
	$select_value_old = get_term_meta( $term_id, 'twchr_toApi_category_value', true );
	$select_name_old = get_term_meta( $term_id, 'twchr_toApi_category_name', true );
	// Saneamos lo introducido por el usuario.
	$dateTime = sanitize_text_field( $_POST['twchr_toApi_dateTime'] );
	$duration = sanitize_text_field( $_POST['twchr_toApi_duration'] );
	$select_value = sanitize_text_field( $_POST['twchr_toApi_category_value'] );
	$select_name = sanitize_text_field( $_POST['twchr_toApi_category_name'] );

	// Actualizamos el campo meta en la base de datos.
	update_term_meta( $term_id, 'twchr_toApi_dateTime', $dateTime, $dateTime_old );
	update_term_meta( $term_id, 'twchr_toApi_duration', $duration, $duration_old );
	update_term_meta( $term_id, 'twchr_toApi_category_value', $select_value, $select_value_old );
	update_term_meta( $term_id, 'twchr_toApi_category_name', $select_name, $select_name_old );
	if ( isset( $_POST['twchr_toApi_dateTime'] ) && isset( $_POST['twchr_toApi_duration'] ) && isset( $_POST['twchr_toApi_category_value'] ) ) {

		$dateTime_raw = sanitize_text_field( $_POST['twchr_toApi_dateTime'] );
		$dateTime_stg = strtotime( $dateTime_raw );
		$dateTime_rfc = date( DateTimeInterface::RFC3339, $dateTime_stg );

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
		// Envia los datos a la API de twich
		$response = twtchr_twitch_schedule_segment_create( $term_id, $tag_name, $dateTime_rfc, $select_value, $duration );
		$schedule_segment_id = $response['allData']->{'segments'}[0]->{'id'};

		$schedule_segments_array = twtchr_twitch_schedule_segment_get();

		$schedule_segments = array();
		foreach ( $schedule_segments_array as $segment ) {
			if ( $segment->{'title'} === $tag_name ) {
				array_push( $schedule_segments, $segment );
			}
		}

		update_term_meta( $term_id, 'twchr_schdules_chapters', json_encode( $schedule_segments ) );
		update_term_meta( $term_id, 'twchr_toApi_schedule_segment_id', $schedule_segment_id );

		$allData = json_encode( $response );
		update_term_meta( $term_id, 'twchr_fromApi_allData', $allData );
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


add_action( 'create_serie', 'twchr_tax_serie_create', 10, 5 );

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
	$schedule_segment_id = get_term_meta( $term->term_id, 'twchr_toApi_schedule_segment_id' );
	if(!empty($allData)){
		$schedule_segment_id = empty( $schedule_segment_id ) ? json_decode( $allData )->{'allData'}->{'segments'}[0]->id : $schedule_segment_id;
	}
	$twchr_streams_relateds = get_term_meta( $term->term_id, 'twchr_schdules_chapters', true );

	$select_cat = array(
		'name' => $select_name,
		'value' => $select_value,
	);

	require_once 'form-serie.php';
}
add_action( 'serie_edit_form_fields', 'twchr_tax_serie_edit', 10, 2 );

// twchr_tax_serie
// twchr_tax_serie_import
function twchr_tax_serie_import() {
	?>
   <a class="twchr-btn-general twchr-btn-general-lg" href="<?php echo TWCHR_ADMIN_URL; ?>edit-tags.php?taxonomy=serie&post_type=twchr_streams&sync_serie=true">import serie</a>
	<?php
	if ( isset( $_GET['from_cpt_id'] ) ) {
		$term = wp_create_term( 'serie-' . $_GET['from_cpt_id'], 'serie' );
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


		// Si hay series
		if ( ! COUNT( $schedules_wp ) == 0 ) {

			foreach ( $schedules_wp as $item ) {

				$wp_id = $item->term_id;
				$wp_title = $item->{'name'};

				foreach ( $schedules_twitch as $key => $schedule ) {

					$tw_title = $schedule->{'title'};
					if ( $tw_title == $wp_title ) {
						
					} else {
						$title = empty( $schedule->title ) ? __( 'No title', 'twitcher' ) : $schedule->title;
						$new_term = wp_insert_term( $title, 'serie' );

						if ( isset( $new_term->errors['term_exists'] ) ) {

						} else {
							$new_term_id = $new_term['term_id'];

							$dateTime = $schedule->start_time;
							add_term_meta( $new_term_id, 'twchr_toApi_dateTime', $dateTime );
							$select_value = $schedule->category->id;
							add_term_meta( $new_term_id, 'twchr_toApi_category_value', $select_value );
							$select_name = $schedule->category->name;
							add_term_meta( $new_term_id, 'twchr_toApi_category_name', $select_name );
							$schedule_segment_id = $schedule->id;
							add_term_meta( $new_term_id, 'twchr_toApi_schedule_segment_id', $schedule_segment_id );
							$allData = json_encode( $schedule );
							add_term_meta( $new_term_id, 'twchr_fromApi_allData', $allData );

							$schedule_segments = array();
							foreach ( $schedules_twitch as $segment ) {
								if ( $segment->{'title'} === $schedule->{'title'} ) {
									array_push( $schedule_segments, $segment );
								}
							}

							add_term_meta( $new_term_id, 'twchr_schdules_chapters', json_encode( $schedule_segments ) );

							// Convertir las fechas a timestamp
							$start_time = $schedule->start_time;
							$end_time = $schedule->end_time;
							$minutos = twchr_twitch_video_duration_calculator( $start_time, $end_time );
							add_term_meta( $new_term_id, 'twchr_toApi_duration', $minutos );
						}
					}

					if ( $key === COUNT( $schedules_twitch ) - 1 ) {
						twchr_javaScript_redirect( TWCHR_ADMIN_URL . '/edit-tags.php?taxonomy=serie' );
					}
				}
				die();
			}
		} else {
			foreach ( $schedules_twitch as $schedule ) {

				$tw_title = $schedule->{'title'};
				if ( $tw_title == $wp_title ) {
				} else {
					$new_term = wp_insert_term( $tw_title, 'serie' );

					if ( isset( $new_term->errors['term_exists'] ) ) {
						// TODO: Poner esta redireccion en el error handler
						echo "<script>location.href='" . TWCHR_ADMIN_URL . "edit-tags.php?taxonomy=serie&post_type=twchr_streams'</script>";
						die();
					}

					$new_term_id = $new_term['term_id'];

					$dateTime = $schedule->start_time;
					add_term_meta( $new_term_id, 'twchr_toApi_dateTime', $dateTime );
					$select_value = $schedule->category->id;
					add_term_meta( $new_term_id, 'twchr_toApi_category_value', $select_value );
					$select_name = $schedule->category->name;
					add_term_meta( $new_term_id, 'twchr_toApi_category_name', $select_name );
					$schedule_segment_id = $schedule->id;
					add_term_meta( $new_term_id, 'twchr_toApi_schedule_segment_id', $schedule_segment_id );
					$allData = json_encode( $schedule );
					add_term_meta( $new_term_id, 'twchr_fromApi_allData', $allData );

					$schedule_segments = array();
					foreach ( $schedules_twitch as $segment ) {
						if ( $segment->{'title'} === $schedule->{'title'} ) {
							array_push( $schedule_segments, $segment );
						}
					}

					add_term_meta( $new_term_id, 'twchr_schdules_chapters', json_encode( $schedule_segments ) );

					// Convertir las fechas a timestamp
					$start_time = $schedule->start_time;
					$end_time = $schedule->end_time;
					$minutos = twchr_twitch_video_duration_calculator( $start_time, $end_time );
					add_term_meta( $new_term_id, 'twchr_toApi_duration', $minutos );
				}
			}
			echo "<script>location.href='" . TWCHR_ADMIN_URL . "edit-tags.php?taxonomy=serie&post_type=twchr_streams'</script>";
			die();
		}
	}
}

 add_action( 'serie_pre_add_form', 'twchr_tax_serie_import' );
