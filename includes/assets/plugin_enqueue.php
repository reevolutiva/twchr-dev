<?php

add_action('wp_after_admin_bar_render','twchr_fonts');

function twchr_fonts(){
    ?>
    <style>
        @font-face {
            font-family: 'Comfortaa';
            src: url(<?php echo TWCHR_URL_FONTS.'/Comfortaa-Regular.ttf';?>);
            font-weight: normal;
        }
        @font-face {
            font-family: 'Comfortaa';
            src: url(<?php echo  TWCHR_URL_FONTS.'/Comfortaa-Bold.ttf';?>);
            font-weight: bold;
        }
        @font-face {
            font-family: 'Comfortaa';
            src: url(<?php echo  TWCHR_URL_FONTS.'/Comfortaa-Light.ttf';?>);
            font-weight: light;
        }
    </style>
    <?php
}


function twchr_admin_js() {
    $version = 'beta.4.152';
    
    // Estilos
    wp_enqueue_style('admin-styles', TWCHR_URL."includes/css/admin.css" ,array(),$version,'all');

    // Scripts
    wp_enqueue_script('twchr_gscjs',TWCHR_URL."includes/js/gscjs.js",array(),$version,false);

    
    $setInstaled = get_option('twchr_setInstaled');
    //Usamos esta función para que coloque los valores inline
    if($setInstaled == 3){
        //Definimos las variables WordPress a enviar dentro de un array
        $params = array (
            'twchr_keys' => json_decode(get_option('twchr_keys')) ,
            'twchr_app_token' => get_option('twchr_app_token'),
            'twitcher_data_broadcaster' => json_decode(get_option( 'twchr_data_broadcaster'))->{'data'}[0],
            'twitcher_data_clear_all' => get_option( 'twchr_delete_all')
        );
        wp_register_script('twchr_custom_script',TWCHR_URL."includes/js/admin.js",array(),$version,true);
        wp_enqueue_script('twchr_custom_script');
        wp_localize_script('twchr_custom_script','tchr_vars_admin',$params);
    }
    
}

add_action('admin_enqueue_scripts', 'twchr_admin_js');
