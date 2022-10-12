<?php
/*
Plugin Name: Twitcher
Plugin URI: twtcher.pro
Description: Un plugin para convertir wordpress es un centro de cotrol de streamings
Version: 1
Author: reevolutiva
Author URI: twitcher.pro
License: GPL2
*/

//setings
require_once 'includes/dev_functions.php';

require_once 'includes/api-connection/twicht.php';
require_once 'includes/api-connection/twichtv.php';
require_once 'includes/api-connection/api_db.php';

// admin styles
require_once 'admin/admin_dashboard.php';

// Custom post types
require_once 'includes/cpt/streamings.php';

// Custom fields
require_once 'includes/cf/streamings.php';
//require_once 'includes/cf/to_api.php';
require_once 'includes/cf/stream_yt.php';

// Shortcodes
require_once 'includes/shortcode/twich_embed.php';
require_once 'includes/shortcode/twchr-shortcode.php';

//Crear post stream
require_once 'includes/create-post/crear_twchr_stream.php';

//Actualizar post
require_once 'includes/update-post/update_twchr_stream.php';

require_once 'includes/twchr_get_videos.php';

//Taxonomias
require_once 'includes/taxonomys/programs.php';

/* 
    Funciones de activacion iniciales del plugin
*/


function Activar(){
    require_once 'includes/create-post/crear_setUp_page.php';
    crear_page_setUP();
    
    //wp_die();
}
register_activation_hook(__FILE__,'Activar' );

function Desactivar(){
    // Eliminar datos en BDD correpondientes al pluigin al desactivar el plugin    
    //delete_option('twchr_setInstaled' );
}
register_activation_hook(__FILE__,'Desactivar' );


// Menu consola de administracion en Dashboard WP
function twchr_main_menu(){
    //add_media_page($page_title:string,$menu_title:string,$capability:string,$menu_slug:string,$callback:callable,$position:integer|null )

    add_submenu_page(
        'edit.php?post_type=twchr_streams', //$parent_slug
        'Twitcher Settings',  //$page_title
        'Twitcher Settings',        //$menu_title
        'manage_options',           //$capability
        'twchr-settings',//$menu_slug
        'twchr_main_page'//$function
    );

    /*
    add_menu_page(
        'Twitcher',
        'Twitcher',
        'administrator',
        'twchr_wp_menu',
        'twchr_main_page',
        plugin_dir_url(__FILE__).'includes/assets/Logo.png',
        2
    );

    add_submenu_page(
        'twchr_wp_menu',
        'User Token',
        'User Token',
        'administrator',
        'twchr_user_token',
        'twchr_submenu_user_token',
    );
    */
}

add_action('admin_menu','twchr_main_menu');

// Template de menu prinicpal de plugin
function twchr_main_page(){
    require_once 'admin/main_page.php';
}

/* Template de meunu secudario de plugin
function twchr_submenu_user_token(){
    require_once 'admin/submenu_user_token.php';
}
*/


/* Funcion is_this_single_of_cpt() que verifica si estamos en un archive o un single 
   Preguntando cuantos items le sigen al dominio

    Ejemplo:
        1 dominio.cl/streaming/streamn
        2 dominio.cl/streaming/

    Esta funcion en el caso 1 retornaria true y en el caso 2 fasle
*/
function is_this_single_of_cpt($data_cpt, $data_single){
    $url = explode("/",$_SERVER['REQUEST_URI']);
	$url_leng = count($url) - 2 ;
    if($url_leng > 1){
		$cpt = $url[1];
		$single = $url[2];
		if($cpt === $data_cpt && $single === $data_single) return true;
		else return false;
	}else{
		return false;
	}
}

// En base a la url verifica si el paramatro de la funcion es igual a la consulta en la url
function is_this_cpt($is_cpt){
    $url = explode("/",$_SERVER['REQUEST_URI']);
    return $url[1] === $is_cpt;		
}

// Cuenta cuantos items porta la url despues de el dominio
// Ejemplo: dominio.cl/streaming/streamn
//                      1          2
function how_directory_accses(){
    $url = explode("/",$_SERVER['REQUEST_URI']);
    $quaty = count($url) - 2;
    return $quaty;	
}

/* 
Funcion que hace que Worpress busaque los single de un cpt en el 
en el directorio del plugin en ves del directorio del theme
*/
// TODO: Remplazar is_this_cpt por get_post_type
add_filter( 'template_include', 'template_replace' );
function template_replace( $template ) {

    // Sí estoy en un cpt twchr_streams y hay algo más en la url que /twchr_streams/ entonces has esto
    if(is_this_cpt('twchr_streams') && how_directory_accses() > 1){
        $template = __DIR__.'/public/single-twchr_streams.php';    
    // Sí solo en la url esta la direcion /twchr_streams/ has esto
    }else if(is_this_cpt('twchr_streams')){
        $template = __DIR__.'/public/archive-twchr_streams.php';
    }else if(is_page('twttcher-setup')){
        $template = __DIR__.'/public/page-twttcher-setup.php';
    }
    return $template;
}

// TODO: Reemplazar por is_sigle
function is_get_user_token_page($term_id){
    $url = explode("/",$_SERVER['REQUEST_URI']);
    return $url[2]=== 'get-user-token';		
}

function schedule_update($term_id) {

    // Recoje data de BDD
    $twch_data_prime = json_decode(db_to_front('twitcher_keys')['last_result'][0]->option_value);
    $tokenValidate = $twch_data_prime->{'user_token'};
    $client_id = $twch_data_prime->{'client-id'};


    $dateTime_raw = sanitize_text_field($_POST['twchr_toApi_dateTime']);
    $dateTime_stg = strtotime($dateTime_raw);
    $dateTime_rfc = date(DateTimeInterface::RFC3339,$dateTime_stg);

    $duration = sanitize_text_field($_POST['twchr_toApi_duration']);
    $select = sanitize_text_field($_POST['twchr_toApi_category']);
    $tag_name = $_POST["tag-name"];

    
    // Envia los datos a la API de twich
    return post_stream($term_id,$tokenValidate,$client_id,$tag_name,$dateTime_rfc ,$select,$duration);

    
}

add_action('shutdown','twchr_redirect_setUp');

// Funcion para redurecian a pagina de instalacion
function twchr_redirect_setUp(){
    // Si la url contiene 'plugins' retorna true
    $dataUrl1 = str_contains($_SERVER['REQUEST_URI'],'plugins');
    $dataUrl2 = str_contains($_SERVER['REQUEST_URI'],'action=upload-plugin');
    //show_dump(twittcher_data_exist('twchr_setInstaled'));

    //add_option('twchr_setInstaled',0,'',true );
    // ¿Eciste dato en wp_options twchr_setInstaled? 
    $setInstaled = get_option('twchr_setInstaled');
    //show_dump($setInstaled);
    //echo "dentro de la funcion redirect_setUp";
    if(!$setInstaled){
        if($dataUrl1 || $dataUrl2){  
            $url = site_url('twttcher-setup');
            echo "<script>location.href='$url'</script>";
            //exit;
            
        }
    }
    //show_dump($dataUrl);
}


function twchr_admin_js() {
    $version = 'beta.2.14';
    
    // Estilos
    wp_enqueue_style('admin-styles', plugin_dir_url(__FILE__) . 'includes/css/admin.css',array(),$version,'all');

    // Scripts
    wp_register_script('twchr_custom_script',plugin_dir_url(__FILE__) . 'includes/js/admin.js',array(),$version,true);
    wp_enqueue_script('twchr_custom_script');

    //Definimos las variables WordPress a enviar dentro de un array
    $params = array (
        'twitcher_keys' => get_option('twitcher_keys') ,
        'twitcher_app_token' => get_option('twitcher_app_token')
    );

    //Usamos esta función para que coloque los valores inline
    wp_localize_script('twchr_custom_script','tchr_vars_admin',$params);
}

add_action('admin_enqueue_scripts', 'twchr_admin_js');
