<?php
// Registra la acción AJAX para actualizar el campo personalizado
add_action( 'wp_ajax_twchr_ajax_recive', 'twchr_ajax_recive_callback' );

// Define la función que maneja la solicitud AJAX para actualizar el campo personalizado
function twchr_ajax_recive_callback() {
  // Recupera los datos enviados con la solicitud AJAX

  $valor_del_campo = sanitize_text_field( $_POST['nombresito']);

  // Actualiza el campo personalizado
  //update_post_meta( $post_id, 'mi_campo_personalizado', $valor_del_campo );

  // Envía una respuesta al navegador
  wp_send_json_success(json_encode($_POST));
}

?>