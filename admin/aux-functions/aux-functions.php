<?php
// TODO:Describir función
// Especifico el meta key y meta value de un custom field y si esos canmpos existen
// retorna true y si no existen retorna false
function twchr_validate_cf_db_exist( $key, $value ) {
	global $wpdb;
	$sql = "SELECT * FROM wp_postmeta WHERE meta_key = '$key' AND meta_value = '$value';";
	$wpdb->query( $sql );
	$response = $wpdb->{'last_result'};
	if ( COUNT( $response ) > 0 ) {
		return $response;
	} else {
		return false;
	}

}

/**
 * Extrae el value de un objecto de tipo JSON por su Key
 *
 * @param [type] $key
 * @param [type] $json
 * @return void
 */
function twchr_object_get_value_by( $key, $json ) {
	$object = json_decode( $json );
	return $object->{$key};
}

/**
 * Extrae el value de un array de tipo JSON por su indice
 *
 * @param [type] $key
 * @param [type] $json
 * @return void
 */
function twchr_array_get_value_by( $key, $json ) {
	$array = json_decode( $json );
	return $array[ $key ];
}

/**
 * Verifica si un dato enviado por POST existe en el array $_POST y si su value no esta vacio
 *
 * @param string $name
 * @return void
 */
function twchr_post_isset_and_not_empty( string $name ) {
	if ( isset( $_POST[ $name ] ) && ! empty( $_POST[ $name ] ) ) {
		return true;
	} else {
		return false;
	}
}

function twchr_twitch_video_duration_calculator( $start_time, $end_time ) {
	$start_time_h = (int) date( 'H', strtotime( $start_time ) );
	$start_time_m = (int) date( 'i', strtotime( $start_time ) );
	$end_time_h = (int) date( 'H', strtotime( $end_time ) );
	$end_time_m = (int) date( 'i', strtotime( $end_time ) );
	$minutos = '';

		// Sucede en la misma hora
	if ( ( $start_time_h - $end_time_h ) == 0 ) {
		// restas lo minutos
		$minutos = $end_time_m - $start_time_h;

	} else {
		// si sucede en mas de una hora
		// resta los minutos
		$horas = $end_time_h - $start_time_h;
		$minutos = $horas * 60;

	}
	return $minutos;
}

function twchr_javaScript_redirect( $url ) {
	echo "<script>location.href='$url'</script>";
	die();
}

/**
 * Sanitiza u guarda un custom field
 *
 * @param [type] $post_id
 * @param [type] $key
 * @param [type] $value
 * @return void
 */
function twchr_sanitize_cf_save( $post_id, $key, $value ) {
	$santize = sanitize_text_field( $value );
	if ( update_post_meta( $post_id, $key, $santize ) == false ) {
		add_post_meta( $post_id, $key, $santize );
	}
}

/**
 * Convierte un array a objeto php.
 *
 * @param [type] $array
 * @return void
 */
function twchr_array_to_object($array){
	$new_array = '';
	if(is_array($array)){
		$old_array = json_encode($array);
		$new_array = json_decode($old_array);
	}else{
		$new_array = $array;
	}

	return $new_array;

}