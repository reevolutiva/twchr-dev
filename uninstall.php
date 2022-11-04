<?php 

if(!defined('WP_UNINSTALL_PLUGIN')){
    die();
}
// if (twchr delte all == true)
delete_option('twchr_keys');
delete_option('twchr_app_token');
delete_option('twchr_setInstaled');
delete_option('twchr_data_broadcaster');

?>