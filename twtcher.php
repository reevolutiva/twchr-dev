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

//setings

require_once 'includes/api-connection/twicht.php';
require_once 'includes/api-connection/twichtv.php';
require_once 'includes/api-connection/api_db.php';

// admin styles
require_once 'admin/admin_dashboard.php';

// Custom post types
require_once 'includes/cpt/streamings.php';

// Custom fields
require_once 'includes/cf/streamings.php';
require_once 'includes/cf/to_api.php';

// Shortcodes
require_once 'includes/shortcode/twich_embed.php';


require_once 'includes/create-post/test.php';

function egosapiens_main_menu(){
    //add_media_page($page_title:string,$menu_title:string,$capability:string,$menu_slug:string,$callback:callable,$position:integer|null )
    add_menu_page(
        'Twittcher',
        'Twittcher',
        'administrator',
        'egosapiens_wp_menu',
        'egosapiens_main_page',
        plugin_dir_url(__FILE__).'includes/assets/Logo.png',
        2
    );
    
    add_submenu_page(
        'egosapiens_wp_menu',
        'User Token',
        'User Token',
        'administrator',
        'egosapiens_user_token',
        'egosapiens_submenu_user_token',
    );
}

add_action('admin_menu','egosapiens_main_menu');


function egosapiens_main_page(){
    require_once 'admin/main_page.php';
}

function egosapiens_submenu_user_token(){
    require_once 'admin/submenu_user_token.php';
}

function is_this_single_of_cpt($data_cpt, $data_single){
    $url = explode("/",$_SERVER['REQUEST_URI']);
    $cpt = $url[1];
    $single = $url[2];
    if($cpt === $data_cpt && $single === $data_single) return true;
    else return false;
}

function is_this_cpt($is_cpt){
    $url = explode("/",$_SERVER['REQUEST_URI']);
    return $url[1] === $is_cpt;		
}

add_filter( 'template_include', 'template_replace' );
function template_replace( $template ) {

    if ( is_this_cpt('ego_stream')){
        if(is_this_single_of_cpt('ego_stream','get-user-token')){
            $template = __DIR__.'/public/single-ego_streams-get-user-token.php';
        }
    }
    return $template;
}

function is_get_user_token_page(){
    $url = explode("/",$_SERVER['REQUEST_URI']);
    return $url[2]=== 'get-user-token';		
}

add_action('save_post','cpt_action');

function cpt_action($post_id){
   
}
?>
