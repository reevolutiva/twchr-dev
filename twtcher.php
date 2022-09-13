<?php
/*
Plugin Name: Twtcher
Plugin URI: twtcher.pro
Description: Un plugin para convertir wordpress es un centro de cotrol de streamings
Version:1
Author:Equipo de conjuntas club
Author URI:conjuntas.club
License: GPL2
*/

function Activar(){
    
}
register_activation_hook(__FILE__,'Activar' );

function Desactivar(){

}
register_activation_hook(__FILE__,'Desactivar' );

// admin styles
require_once 'admin/admin_dashboard.php';

// Custom post types
require_once 'includes/cpt/streamings.php';

// Custom fields
require_once 'includes/cf/streamings.php';
require_once 'includes/cf/to_api.php';

// Shortcodes
require_once 'includes/shortcode/twich_embed.php';

require_once 'includes/api-connection/twicht.php';
require_once 'includes/api-connection/twichtv.php';

require_once 'includes/create-post/test.php';

function egosapiens_main_menu(){
    //add_media_page($page_title:string,$menu_title:string,$capability:string,$menu_slug:string,$callback:callable,$position:integer|null )
    add_menu_page(
        'Egosapiens',
        'Egosapiens',
        'administrator',
        'egosapiens_wp_menu',
        'egosapiens_main_page',
        plugin_dir_url(__FILE__).'includes/assets/Logo.png',
        2
    );  
}

add_action('admin_menu','egosapiens_main_menu');


function egosapiens_main_page(){
    require_once 'admin/main_page.php';
}

add_filter( 'template_include', 'template_replace' );
function template_replace( $template ) {
    $post_types = array( 'ego_streams' );
    if ( is_single($post_type)){
        $template = __DIR__.'/public/single-ego_streams.php';
    }
    return $template;
}