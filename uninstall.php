<?php 

if(!defined('WP_UNINSTALL_PLUGIN')){
    die();
}

delete_option('twitcher_keys');
delete_option('twitcher_app_token');
delete_option('twchr_setInstaled');

?>