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
    <h4>Start SetUp</h4><a href="<?= site_url('/wp-admin/edit.php?post_type=twchr_streams&page=twchr_help&setUpPage=true'); ?>">Start</a>   
</div>