<?php
function twchr_cf_schedule__card(){
    require_once 'schedule_custom_card.php';
}

add_action('edit_form_after_title','twchr_cf_schedule__card');