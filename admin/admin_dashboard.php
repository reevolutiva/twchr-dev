<?php 
 function twitcher_admin_style() {
    wp_enqueue_style('twitcher_admin_style', plugins_url('twitcher_admin_style.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'twitcher_admin_style');

add_filter('admin_footer_text', 'left_admin_footer_text_output'); //left side
function left_admin_footer_text_output($text) {
    $text = '¡Gracias por instalar Twithcer plugin!';
    return $text;
}
 
add_filter('update_footer', 'right_admin_footer_text_output', 11); //right side
function right_admin_footer_text_output($text) {
    $text = 'Desarrollado por reevolutiva.com.';
    return $text;
}