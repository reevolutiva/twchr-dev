<?php 

if(isset($_GET['setUpPage']) && $_GET['setUpPage'] == true){
    require_once 'stwchr_setUp.php';
    die();
}


?>

<style>
    <?php include 'main_page.css'; ?>
</style>
<div class="twchr-for-back-broadcast container">
    <h1>Help</h1>
    <h3><?php _e('Mail your support requests to  contacto@twitcher.pro','twittcher'); ?></h3>
    <p><a href='https://twitcher.pro/'><?php _e('More information visite https://twitcher.pro/','twittcher'); ?></a></p>
    </hr>   
</div>