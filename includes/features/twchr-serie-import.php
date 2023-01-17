<?php 
/**
 * Toma por parametro la lista schedule segment traidos por la API de Twitch
 * y los usa para crear o actualizar una taxonomia serie, mientras el sechedule segment
 * sea is_recurring == true.
 *
 * @param [type] $schedules_twitch
 * @return void
 */
function twchr_tax_serie_update($schedules_twitch){

    // Obtiene todas las series guardadas en wordpress.
	$schedules_wp = get_terms(
		array(
			'taxonomy' => 'serie',
			'hide_empty' => false,
		)
	);

    // Si no hay series en twitch denten el flujo.
	$response = '';
    $response_terms = [];
    if($schedules_twitch == 'segments were not found'){
        $response = 'segments were not found';
        return $response;
    }

    
	try {
        //Si wordpress no tiene 0 series (es decir si tiene series).
        if ( ! COUNT( $schedules_wp ) == 0 ){

            // Recorre todas las series guardadas en wordpress.
            foreach ( $schedules_wp as $item ) {

                // Obtengo el term_id y title de cada serie.
                $wp_id = $item->term_id;
                $wp_title = $item->{'name'};

                // Recorro la lista de schedules que retorna twitch.
                foreach ( $schedules_twitch as $key => $schedule ) {

                    // si es un array lo convierto a objeto.
                    $schedule = twchr_array_to_object($schedule);
                    
                    // Obtengo el titulo del schedule que retorna twitch.
                    $tw_title = $schedule->{'title'};
                    
                    // Si la propiedad is_recurrin del schedule es true(boolean) o "true"(string).
                    if($schedule->{'is_recurring'} == true || $schedule->{'is_recurring'} == "true"):

                        // Si el titulo de wordpress es el mismo titulo que el de twitch.
                        // El titulo existe existe actualiza la serie.
                        if ( $tw_title == $wp_title ) {
                                
                                $dateTime = $schedule->start_time;
                                update_term_meta( $wp_id, 'twchr_toApi_dateTime', $dateTime );

                                // Guardo Custom field de twitch category.
                                if($schedule->category !== null){
                                    $select_value = $schedule->category->id;
                                    update_term_meta( $wp_id, 'twchr_toApi_category_value', $select_value );
                                    $select_name = $schedule->category->name;
                                    update_term_meta( $wp_id, 'twchr_toApi_category_name', $select_name );
                                }
                                // end - Guardo Custom field de twitch category.
                                
                                $schedule_segment_id = $schedule->id;
                                update_term_meta( $wp_id, 'twchr_toApi_schedule_segment_id', $schedule_segment_id );
                                $allData = json_encode( $schedule );
                                update_term_meta( $wp_id, 'twchr_fromApi_allData', $allData );

                                
                                $schedule_segments_array = twtchr_twitch_schedule_segment_get();

                                // Si exiten segmentos.
                                if($schedule_segments_array != 'segments were not found' && !isset($schedule_segments_array->{'error'})){
                                    
                                    $schedule_segments = array();
                                        foreach ( $schedule_segments_array as $segment ) {
                                            if ( $segment->{'title'} === $wp_title ) {
                                                array_push( $schedule_segments, $segment );
                                            }
                                        }

                                        update_term_meta( $wp_id, 'twchr_schdules_chapters', json_encode( $schedule_segments ) );

                                }
    

                                // Convertir las fechas a timestamp
                                $start_time = $schedule->start_time;
                                $end_time = $schedule->end_time;
                                $minutos = twchr_twitch_video_duration_calculator( $start_time, $end_time );
                                update_term_meta( $wp_id, 'twchr_toApi_duration', $minutos );
                        } else {
                            
                            // Si no existe crea una serie.
                            $title = empty( $schedule->{'title'} ) ? __( 'No title', 'twitcher' ) : $schedule->{'title'};
                            $new_term = wp_insert_term( $title, 'serie' );

                            // Si esta serie efetivamente no existe
                            if ( isset( $new_term->errors['term_exists'] ) ) {
                            } else {
                                $new_term_id = $new_term['term_id'];

                                $dateTime = $schedule->start_time;
                                add_term_meta( $new_term_id, 'twchr_toApi_dateTime', $dateTime );
                               
                                // Guardo Custom field de twitch category.
                                if($schedule->category !== null){
                                    $select_value = $schedule->category->id;
                                    update_term_meta( $new_term_id, 'twchr_toApi_category_value', $select_value );
                                    $select_name = $schedule->category->name;
                                    update_term_meta( $new_term_id, 'twchr_toApi_category_name', $select_name );
                                }
                                // end - Guardo Custom field de twitch category.

                                $schedule_segment_id = $schedule->id;
                                add_term_meta( $new_term_id, 'twchr_toApi_schedule_segment_id', $schedule_segment_id );
                                $allData = json_encode( $schedule );
                                add_term_meta( $new_term_id, 'twchr_fromApi_allData', $allData );


                                
                                $schedule_segments_array = twtchr_twitch_schedule_segment_get();

                                // Si exiten segmentos.
                                if($schedule_segments_array != 'segments were not found'){

                                    $schedule_segments = array();
                                        foreach ( $schedule_segments_array as $segment ) {
                                            if ( $segment->{'title'} == $title ) {
                                                array_push( $schedule_segments, $segment );
                                            }
                                        }

                                        $var = add_term_meta( $new_term_id, 'twchr_schdules_chapters', json_encode( $schedule_segments ) );

                                }

                                // Convertir las fechas a timestamp
                                $start_time = $schedule->start_time;
                                $end_time = $schedule->end_time;
                                $minutos = twchr_twitch_video_duration_calculator( $start_time, $end_time );
                                add_term_meta( $new_term_id, 'twchr_toApi_duration', $minutos );
                            }
                        }
                    endif;

                    $wp_date = get_term_meta($wp_id,'twchr_toApi_dateTime');
                    $date_now = date( DateTimeInterface::RFC3339);



                }
                
            }
            
        }else{
            
            foreach ( $schedules_twitch as $schedule_vanilla ) {
                $schedule = twchr_array_to_object($schedule_vanilla);
				$tw_title = $schedule->{'title'};
				// Si el schedule segment es recurrente.
				if($schedule->{'is_recurring'} == true || $schedule->{'is_recurring'} == "true"):
					
						$new_term = wp_insert_term( $tw_title, 'serie' );

						if ( isset( $new_term->errors['term_exists'] ) ) {
							// TODO: Poner esta redireccion en el error handler
							echo "<script>location.href='" . TWCHR_ADMIN_URL . "edit-tags.php?taxonomy=serie&post_type=twchr_streams'</script>";
							die();
						}

						$new_term_id = $new_term['term_id'];

						$date_time= $schedule->start_time;
						add_term_meta( $new_term_id, 'twchr_toApi_dateTime', $date_time);
						$select_value = $schedule->category->id;
						add_term_meta( $new_term_id, 'twchr_toApi_category_value', $select_value );
						$select_name = $schedule->category->name;
						add_term_meta( $new_term_id, 'twchr_toApi_category_name', $select_name );
						$schedule_segment_id = $schedule->id;
						add_term_meta( $new_term_id, 'twchr_toApi_schedule_segment_id', $schedule_segment_id );
						$allData = json_encode( $schedule );
						add_term_meta( $new_term_id, 'twchr_fromApi_allData', $allData );

                        $schedule_segments_array = twtchr_twitch_schedule_segment_get();
                        /*
                        // Si exiten segmentos.
                        if ($schedule_segments_array != 'segments were not found') {

                            $schedule_segments = array();
                            foreach ($schedule_segments_array as $segment) {
                                if ($segment->{'title'} === $tw_title) {
                                    array_push($schedule_segments, $segment);
                                }
                            }

                            add_term_meta($new_term_id, 'twchr_schdules_chapters', json_encode($schedule_segments));
                        }
                        */
                        
                        

                        
						// Convertir las fechas a timestamp
						$start_time = $schedule->start_time;
						$end_time = $schedule->end_time;
						$minutos = twchr_twitch_video_duration_calculator( $start_time, $end_time );
						add_term_meta( $new_term_id, 'twchr_toApi_duration', $minutos );
					
				endif;
			}
            
        }
        
		$response = ['status' => 200, 'terms' => twchr_endpoint_tax_register_callback_serie()];

	} catch ( Exception $e ) {
		$response = $e;
	}
    return $response;
}

?>