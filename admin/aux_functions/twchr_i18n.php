<?php
/**
 * Function for translate and lastest escape
 *
 * @param string $var
 * @param string $case
 * @return Content for HTML
 */
function twchr_esc_i18n(string $var,string $case){
    switch($case){
        case 'html': 
            $i18n = __($var,'twitcher');
            $result = esc_html($i18n);
            echo $result;
            break;
        case 'attr':
            $i18n = __($var,'twitcher');
            $result = esc_attr($var);
            echo $result;
            break;

    }
}

function cnjts_getoption_json($data){
    // case not exist
    if(get_option($data, false)){
        return false;
    }else{
        $result = json_decode(get_option($data));
        return $result;
    }
}
