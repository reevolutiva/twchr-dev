<?php 

if(isset($_GET['setUpPage']) && $_GET['setUpPage'] == true){
    require_once 'stwchr_setUp.php';
    die();
}


if(isset($_GET['twchr_set_clear_all']) ){
    if($_GET['twchr_set_clear_all'] == 1){
        update_option('twchr_delete_all', 1);
        echo "<script>location.href='".site_url('/wp-admin/edit.php?post_type=twchr_streams&page=twchr_help')."'</script>";
    }else if($_GET['twchr_set_clear_all'] == 0){
        update_option('twchr_delete_all', 0);
        echo "<script>location.href='".site_url('/wp-admin/edit.php?post_type=twchr_streams&page=twchr_help')."'</script>";
    }
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
    <form action="./edit.php" method="get">
        <input type="hidden" name="post_type" value="twchr_streams">
        <input type="hidden" name="page" value="twchr_help">
        <p><?php _e('Remove all Twitcher data on uninstall','twitcher'); ?></p>
        <label>
            on
            <input type="radio" name="twchr_set_clear_all" value="1" >
        </label>
        <label>
            off
            <input type="radio" name="twchr_set_clear_all" value="0" >
        </label>
        <input type="submit" value="<?php _e('save','twitcher')?>">
    </form> 
    <h3>Setup</h3>
    <a class="twchr-btn-general" href="<?php echo  site_url('/wp-admin/edit.php?post_type=twchr_streams&page=twchr_help&setUpPage=true');?>">Run Setup</a>  
</div>