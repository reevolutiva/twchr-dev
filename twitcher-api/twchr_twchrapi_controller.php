<?php
/*
    IMPORATANTE
    Solo podemos insertar un formulario a la vez
*/

//TODO:Describir función
function twchr_form_plugin_footer()
{
    $dataUrl1 = str_contains($_SERVER['REQUEST_URI'], 'post_type=twchr_streams');
    $dataUrl2 = str_contains($_SERVER['REQUEST_URI'], 'plugins.php');
    if (get_option('twchr_setInstaled') >= 3 && ($dataUrl1 || $dataUrl2)) {
        instanse_comunicate_server();
    }
}

add_action("shutdown", "twchr_form_plugin_footer");