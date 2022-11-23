<?php
/**
 * Function for translate and lastest escape
 *
 * @param string $var
 * @param string $cas
 * @return Content for HTML
 */
function twchr_esc_i18n(string $var,string $case){
    switch($case){
        case 'html': 
            $i18n = __($var,'twiwcher');
            $result = esc_html($i18n);
            echo $result;
            break;
        case 'attr':
            $i18n = __($var,'twiwcher');
            $result = esc_attr($var);
            echo $result;
            break;

    }
}
