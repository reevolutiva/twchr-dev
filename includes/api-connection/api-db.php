<?php
/**
 * Retorna true si $data existe en wp_options
 *
 * @param [type] $data
 * @return void
 */
function twittcher_data_exist( $data ) {
	if ( get_option( $data, false ) != false ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Si existe un dato en BDD me lo devuelves
 *
 * @param [type] $table
 * @param [type] $key
 * @param [type] $data
 * @return void
 */
function twittcher_getData( $table, $key, $data ) {
	global $wpdb;
	$sql = "SELECT * FROM $table WHERE meta_key='$key' AND meta_value='$data'";
	$wpdb->query( $sql );
	$response = $wpdb->last_result[0];
	if ( ! empty( $response ) ) {
		return $response;
	} else {
		return false;
	}
}

/**
 * Guarda $client_secret y $client_id en wp_options
 *
 * @param [type] $client_secret
 * @param [type] $client_id
 * @return void
 */
function fronted_to_db( $client_secret, $client_id ) {
	if ( twittcher_data_exist( 'twchr_keys' ) == false ) {
		$array_keys = array(
			'client-secret' => $client_secret,
			'client-id' => $client_id,
		);
		$json_array = json_encode( $array_keys );
		add_option( 'twchr_keys', $json_array );

	} else {
		$array_keys = array(
			'client-secret' => $client_secret,
			'client-id' => $client_id,
		);
		$json_array = json_encode( $array_keys );
		update_option( 'twchr_keys', $json_array );
	}
}

/**
 * Guarda appToken en wp_option
 *
 * @param [type] $token
 * @return void
 */
function twchr_save_app_token( $token ) {

	if ( get_option( 'twchr_app_token' ) != false || get_option( 'twchr_app_token' ) == '' ) {
		update_option( 'twchr_app_token', $token );

	} else {
		add_option( 'twchr_app_token', $token );
	}

}

