<?php 
 function twitcher_admin_style() {
    wp_enqueue_style('twitcher_admin_style', plugins_url('twitcher_admin_style.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'twitcher_admin_style');
