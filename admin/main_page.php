<style>
    <?php include 'main_page.css'; ?>
</style>

<div class="ego-for-back container">
    <main>
        <h1>Bienvenido a Hello Egosapiens</h1>
        <?php 
            $twch_data_prime = json_decode(db_to_front('twitcher_keys')['last_result'][0]->option_value);
            
        ?>
        <form method="GET" action="./admin.php">
            <input type="hidden" name="page" value="egosapiens_wp_menu">
            <?php $apiKey = !empty($twch_data_prime->{'client-secret'}) ? $twch_data_prime->{'client-secret'} : null ; ?>
            <input id="client-id" type="text" placeholder="Client Secret" name="client-secret" value="<?= $apiKey?>" <?php if(!empty($twch_data_prime->{'client-secret'})) echo 'disabled="true"' ?>>
            <?php $clientID = !empty($twch_data_prime->{'client-id'}) ?  $twch_data_prime->{'client-id'} : null; ?>
            <input id="client-secret" type="text" placeholder="Client ID" name="client-id" value="<?= $clientID ?>" <?php if(!empty($twch_data_prime->{'client-id'})) echo 'disabled="true"' ?>>
            <input type="submit" value="sincronizar" name="sincronizar" <?php if(!empty($twch_data_prime->{'client-secret'}) && !empty($twch_data_prime->{'client-id'})) echo 'disabled="true"' ?>>
            <?php 
                if(!empty($twch_data_prime->{'client-secret'}) && 
                   !empty($twch_data_prime->{'client-id'})
                   ): ?>
                   
                   <input type="submit" value="resincronizar" name="resincronizar">
                <?php
                if(isset($_GET['resincronizar'])){
                    global $wpdb;
                    $array_keys = array(
                        'client-secret' => '',
                        'client-id' => '',
                        'code' => ''
                    );
                    $json_array = json_encode($array_keys);
                    $sql = "UPDATE wp_options SET option_value='$json_array' WHERE option_name='twitcher_keys'";
                    $wpdb->query($sql);
                }
                endif;
            ?>
        </form>

        <hr/>

        <p>User este enlace para la redireccion que solicita twitch: <span style="color:tomato;"><?= bloginfo('url')?>/ego_stream/get-user-token/</span></p>
            
        <p><a class="btn" href="<?= bloginfo('url')?>/ego_stream/get-user-token/">solicitar token</a></p>
        
    </main>
    <aside>
    <?php 
            if(isset($_GET)){  
                if(count($_GET) > 1){
                    if(
                        isset($_GET['client-id']) &&
                        isset($_GET['client-secret'])
                    ){
                        $client_id = $_GET['client-id'];
                        $secret_key = $_GET['client-secret'];
                        fronted_to_db($client_id, $secret_key);
                    }
                }
            }
        ?>
        <table class="twittcher-table">
            <tr>
                <td>Client Secret</td>
                <td><?= $twch_data_prime->{'client-secret'}?></td>
            </tr>
            <tr>
                <td>Client ID</td>
                <td><?= $twch_data_prime->{'client-id'}?></td>
            </tr>
            <tr>
                <td>Code</td>
                <td><?= $twch_data_prime->{'code'}?></td>
            </tr>
        </table>
    </aside>