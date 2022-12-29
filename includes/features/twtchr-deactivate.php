<?php
// Desactivación del plugin y eliminación de datos.
function twchr_desactivar() {
	// Eliminar datos en BDD correpondientes al pluigin al desactivar el plugin
	if ( get_option( 'twchr_delete_all' ) == 1 ) {
		delete_option( 'twchr_set_instaled' );
		delete_option( 'twchr_installation_date' );
		delete_option( 'twchr_log' );
		delete_option( 'twchr_share_permissions' );
	}
}

register_activation_hook( __FILE__, 'twchr_desactivar' );
