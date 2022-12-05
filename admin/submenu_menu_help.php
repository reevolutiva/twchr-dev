<?php 

if(isset($_GET['setUpPage']) && $_GET['setUpPage'] == true){
    require_once 'twchr_setUp.php';
    die();
}


if(isset($_GET['twchr_set_clear_all']) ){
    if($_GET['twchr_set_clear_all'] == 1){
        update_option('twchr_delete_all', 1);
        echo "<script>location.href='".TWCHR_ADMIN_URL.'/edit.php?post_type=twchr_streams&page=twchr_help'."'</script>";
    }else if($_GET['twchr_set_clear_all'] == 0){
        update_option('twchr_delete_all', 0);
        echo "<script>location.href='".TWCHR_ADMIN_URL.'/edit.php?post_type=twchr_streams&page=twchr_help'."'</script>";
    }
}

?>

<style>
    <?php include 'main_page.css'; ?>
</style>
<div class="twchr-for-back-broadcast container">
    <h1>Help</h1>
    <h3><?php twchr_esc_i18n('Mail your support requests to  contacto@twitcher.pro','html'); ?></h3>
    <p><a href='https://twitcher.pro/'><?php twchr_esc_i18n('More information visite https://twitcher.pro/','html'); ?></a></p>
    </hr>
    <form action="./edit.php" method="get">
        <input type="hidden" name="post_type" value="twchr_streams">
        <input type="hidden" name="page" value="twchr_help">
        <p><?php twchr_esc_i18n('Remove all Twitcher data on uninstall','html'); ?></p>
        <label>
            on
            <input type="radio" name="twchr_set_clear_all" value="1" >
        </label>
        <label>
            off
            <input type="radio" name="twchr_set_clear_all" value="0" >
        </label>
        <input type="submit" value="<?php twchr_esc_i18n('save','html')?>">
    </form> 
    <h3>Setup</h3>
    <a class="twchr-btn-general" href="<?php echo  TWCHR_ADMIN_URL.'/edit.php?post_type=twchr_streams&page=twchr_help&setUpPage=true';?>">Run Setup</a>  
</div>
<form action="https://twitcher.pro/twch_server/twchr_get/" method="post">
<?php 
    
    $db = twchr_recopiate_data();
    
    foreach ($db as $key => $value) {
        if(is_array($value)){
            $json = json_encode($value);
        }else{
            $json = $value;
        }
        echo "<input type='hidden' name='to-twitcher-server-".$key."' value ='$json'>";
        //var_dump($json);
    }
?>
<input type="submit" value="enviar">
</form>
