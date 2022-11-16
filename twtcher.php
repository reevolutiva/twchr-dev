<?php
/*
* Plugin Name: Manage Twitch Account: Easy Wordpress Integration
* Plugin URI: twitcher.pro
* Description: Manage, promote and monetise your Twitch.tv streamings integrating Twitch to Wordpress.
* Version: 0.1.1
* Author: Conjuntas.Club
* Author URI: conjuntas.club
* License: GPL3
* Text Domain: twitcher
* Domain Path: /languages
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
//require_once 'includes/cf/stream_yt.php';
require_once 'includes/cf/twchr_stream_data.php';

// Shortcodes
require_once 'includes/shortcode/twich_embed.php';

//Crear post stream
require_once 'includes/create-post/crear_twchr_stream.php';

//Actualizar post
require_once 'includes/update-post/update_twchr_stream.php';

// Traer un post
require_once 'includes/get-post/get_twchr_stream.php';

require_once 'includes/twchr_get_videos.php';

//Taxonomias
require_once 'includes/taxonomys/programs.php';

// Enqueue
require_once 'includes/assets/plugin_enqueue.php';

/* 
    Funciones de activacion iniciales del plugin
*/

define('TWCHR_PATH', __FILE__);
define('TWCHR_URL', plugin_dir_url(__FILE__));

define('TWCHR_URL_ASSETS', plugin_dir_url(__FILE__).'includes/assets/');

function twchr_activar(){
    
}
register_activation_hook(__FILE__,'twchr_activar' );

function twchr_desactivar(){
    // Eliminar datos en BDD correpondientes al pluigin al desactivar el plugin
    if (get_option('twchr_delete_all') == 1){
        delete_option('twchr_setInstaled' );
    }    
    
}
register_activation_hook(__FILE__,'twchr_desactivar' );


// Menu consola de administracion en Dashboard WP
function twchr_main_menu(){
    //add_media_page($page_title:string,$menu_title:string,$capability:string,$menu_slug:string,$callback:callable,$position:integer|null )

    add_submenu_page(
        'edit.php?post_type=twchr_streams', //$parent_slug
        'Twitcher Dashboard',  //$page_title
        'Twitcher Dashboard',        //$menu_title
        'manage_options',           //$capability
        'twchr-dashboard',//$menu_slug
        'twchr_main_page'//$function
    );

    
        
    add_submenu_page(
        'edit.php?post_type=twchr_streams',
        __('Help','twitcher'),
        __('Help','twitcher'),
        'manage_options',
        'twchr_help',
        'twchr_menu_help',
    );
    
    
}

add_action('admin_menu','twchr_main_menu');

/*
function twchr_post_db_exist($cpt,$value_title){
    global $wpdb;
    $sql = "SELECT post_title FROM wp_posts WHERE post_type = '$cpt' AND post_title = '$value_title';";
    $wpdb->query($sql);
    $response = $wpdb->{'last_result'};
    if(COUNT($response) > 0){
        return $response;
    }else{
        return false;
    }
    
}*/
function twchr_cf_db_exist($key,$value){
    global $wpdb;
    $sql = "SELECT * FROM wp_postmeta WHERE meta_key = '$key' AND meta_value = '$value';";
    $wpdb->query($sql);
    $response = $wpdb->{'last_result'};
    if(COUNT($response) > 0){
        return $response;
    }else{
        return false;
    }
    
}


// Template de menu prinicpal de plugin
function twchr_main_page(){
    require_once 'admin/main_page.php';
}

//Template de meunu secudario de plugin
function twchr_menu_help(){
    require_once 'admin/submenu_menu_help.php';
}


function twchr_token_validate($token){
    $url = 'https://id.twitch.tv/oauth2/validate';
    $args = array(
        'headers' => array(
            'Authorization' => 'Bearer '.$token
        )
    );

    $response = wp_remote_get( $url, $args);
    $body = wp_remote_retrieve_body($response);
    return json_decode($body);
}


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
//add_filter( 'template_include', 'template_replace' );
function template_replace( $template ) {

    // Sí estoy en un cpt twchr_streams y hay algo más en la url que /twchr_streams/ entonces has esto
    if(is_this_cpt('twchr_streams') && how_directory_accses() > 1){
        $template = __DIR__.'/public/single-twchr_streams.php';    
    // Sí solo en la url esta la direcion /twchr_streams/ has esto
    }else if(is_this_cpt('twchr_streams')){
        $template = __DIR__.'/public/archive-twchr_streams.php';
    }
    return $template;
}

// TODO: Reemplazar por is_sigle
function is_get_user_token_page($term_id){
    $url = explode("/",$_SERVER['REQUEST_URI']);
    return $url[2]=== 'get-user-token';		
}

function serie_update($term_id) {

    // Recoje data de BDD
    $twch_data_prime = json_decode(get_option( 'twchr_keys', false ));
    $tokenValidate = $twch_data_prime->{'user_token'};
    $client_id = $twch_data_prime->{'client-id'};


    $dateTime_raw = sanitize_text_field($_POST['twchr_toApi_dateTime']);
    $dateTime_stg = strtotime($dateTime_raw);
    $dateTime_rfc = date(DateTimeInterface::RFC3339,$dateTime_stg);

    $duration = sanitize_text_field($_POST['twchr_toApi_duration']);
    $select_value = sanitize_text_field($_POST['twchr_toApi_category_value']);
    $tag_name = '';
    /*
        Si existe la variable $_POST['tag-name']
        significa que se que la taxonomia se creo en el hook create-schedule
        asi $tag-name valdra $_POST['tag-name']
    */
    if(isset($_POST['tag-name'])){
        $tag_name = $_POST['tag-name'];

    /*
        Si no existe la variable $_POST['tag-name']
        verifica que exista la variable $_POST['name']

        Si la variable $_POST['name'] existe significa que 
        la taxonomia se creo en el hook edit-schedule
        asi que $tag-name valdra $_POST['name']
    */    
    }elseif (isset($_POST['name'])) {
        $tag_name = $_POST['name'];
    }
    // Envia los datos a la API de twich
    return post_stream($term_id,$tokenValidate,$client_id,$tag_name,$dateTime_rfc ,$select_value,$duration);

    
}

add_action('shutdown','twchr_redirect_setUp');

// Funcion para redurecian a pagina de instalacion
function twchr_redirect_setUp(){
    // Si la url contiene 'plugins' retorna true
    $dataUrl1 = str_contains($_SERVER['REQUEST_URI'],'plugins');
    $dataUrl2 = str_contains($_SERVER['REQUEST_URI'],'action=upload-plugin');
    $dataUrl3 = str_contains($_SERVER['REQUEST_URI'],'post_type=twchr_streams&page=twchr-dashboard');
    //show_dump(twittcher_data_exist('twchr_setInstaled'));

    //add_option('twchr_setInstaled',0,'',true );
    // ¿Eciste dato en wp_options twchr_setInstaled? 
    $setInstaled = get_option('twchr_setInstaled');
    //show_dump($setInstaled);
    //echo "dentro de la funcion redirect_setUp";
    if($setInstaled <= 1 || $setInstaled == false){
        if($dataUrl1 || $dataUrl2 || $dataUrl3){  
            $url = site_url('/wp-admin/edit.php?post_type=twchr_streams&page=twchr_help&setUpPage=true');
            echo "<script>location.href='$url'</script>";
            //exit;
            
        }
    }
    //show_dump($dataUrl);
}

function twchr_twitch_video_exist($video_id,$token,$client_id){
    if(isset($video_id) && isset($token) && isset($client_id)){
        $url = 'https://api.twitch.tv/helix/videos?id='.$video_id;
        $args = array(
            'headers'=> array(
                    "Authorization" => "Bearer $token",
                    "client-id" => $client_id
            )
        );
        $get = wp_remote_get($url, $args);
        $response = wp_remote_retrieve_body($get);
        $response = json_decode($response);
        
        if($response->data){
            return 200;
        }else{
            return 404;
        }
    }
}

if(get_option('twchr_delete_all') == false){
    add_option( 'twchr_delete_all', 0, '', true );
}



add_filter( 'postmeta_form_limit', function( $limit ) {
    return 100;
} );