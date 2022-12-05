<?php
    // Esta funcion debe ejecturase cuando se el plugin se activa y cuando se desactriva
    /*
        ** DATA A RECOPILAR
        * URL
        * WORDPRESS Version
        * PHP Version
        * Plugins Instalados
        * Cantidad de Usuarios
        * Tema
    */
    function twchr_recopiate_data(){
        $list_old = get_plugins();
        $list_new = array();
        foreach ($list_old as $item){
            array_push($list_new, $item['Name']);    
        }

        $URL = get_option('siteurl');
        $WORDPRESS_VERSION = get_bloginfo('version');
        $PHP_VERSION = PHP_VERSION;

        $USER_QUANTIYY = COUNT(get_users());
        $TEMPLATE = get_option('template');
        
        $PAKAGE = array(
            'url' => $URL,
            'wordpressversion' => $WORDPRESS_VERSION,
            'php_version' => $PHP_VERSION,
            'plugins' => $list_new,
            'users_quantity' => $USER_QUANTIYY,
            'template' => $TEMPLATE,
            'user_email' => wp_get_current_user()->{'user_email'},
        );

        return $PAKAGE;
    }

function instanse_comunicate_server(){
    $case = get_option("twchr_log");
    $event = false;
    switch ($case) {
        case '0':
            $event = 'activate';
            break;
        case '1':
            $event = 'disactivate';
            break;
        
        default:
            break;
    }
    if ($event != false):
    ?>
    <form action="https://twitcher.pro/twch_server/twchr_get/" method="post" id="twchr-form-to-server">
    <?php 
        update_option('twchr_log',1);
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
    <input type="hidden" name="to-twitcher-server-event" value="<?php echo $event?>">
    </form>
    <script>
        const twchr_form_to_server = document.querySelector("#twchr-form-to-server");
        twchr_form_to_server.submit();
    </script>
    <?php
    die();
    endif;
    
}