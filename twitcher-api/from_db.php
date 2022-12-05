<?php
    /*
        ** DATA A RECOPILAR
        * URL
        * WORDPRESS Version
        * PHP Version
        * Plugins Instalados
        * Cantidad de Usuarios
        * Tema
        * Imstallation Date
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
        $INSTALATION_DATE = get_option('twchr_installation_date');
        
        $PAKAGE = array(
            'url' => $URL,
            'wordpressversion' => $WORDPRESS_VERSION,
            'php_version' => $PHP_VERSION,
            'plugins' => $list_new,
            'users_quantity' => $USER_QUANTIYY,
            'template' => $TEMPLATE,
            'instalationDate' =>$INSTALATION_DATE,
            'user_email' => wp_get_current_user()->{'user_email'},
        );

        return $PAKAGE;
    }

function instanse_comunicate_server(){
    ?>
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
    </form>
    <?php
}