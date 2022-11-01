<?php

add_action('wp_after_admin_bar_render','twchr_fonts');

function twchr_fonts(){
    ?>
    <style>
        @font-face {
            font-family: 'Comfortaa';
            src: url(<?= plugins_url('twitcher/includes/assets/fonts/Comfortaa-Regular.ttf');?>);
            font-weight: normal;
        }
        @font-face {
            font-family: 'Comfortaa';
            src: url(<?= plugins_url('twitcher/includes/assets/fonts/Comfortaa-Bold.ttf');?>);
            font-weight: bold;
        }
        @font-face {
            font-family: 'Comfortaa';
            src: url(<?= plugins_url('twitcher/includes/assets/fonts/Comfortaa-Light.ttf');?>);
            font-weight: light;
        }
    </style>
    <?php
}


function twchr_admin_js() {
    $version = 'beta.4.26';
    
    // Estilos
    wp_enqueue_style('admin-styles', plugins_url('twitcher/includes/css/admin.css'),array(),$version,'all');

    // Scripts
    wp_enqueue_script('twchr_gscjs',plugins_url('twitcher/includes/js/gscjs.js'),array(),$version,true);
    wp_register_script('twchr_custom_script',plugins_url('twitcher/includes/js/admin.js'),array(),$version,true);
    wp_enqueue_script('twchr_custom_script');

    //Definimos las variables WordPress a enviar dentro de un array
    $params = array (
        'twchr_keys' => json_decode(get_option('twchr_keys')) ,
        'twchr_app_token' => get_option('twchr_app_token'),
        'twitcher_data_broadcaster' => json_decode(get_option( 'twchr_data_broadcaster'))->{'data'}[0]
    );

    //Usamos esta función para que coloque los valores inline
    wp_localize_script('twchr_custom_script','tchr_vars_admin',$params);
}

add_action('admin_enqueue_scripts', 'twchr_admin_js');
