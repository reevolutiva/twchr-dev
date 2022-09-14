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
            <?php $apiKey = !empty($twch_data_prime->{'api-key'}) ? $twch_data_prime->{'api-key'} : null ; ?>
            <input id="client-id" type="text" placeholder="API KEY" name="api-key" value="<?= $apiKey?>" <?php if(!empty($twch_data_prime->{'api-key'})) echo 'disabled="true"' ?>>
            <?php $clientID = !empty($twch_data_prime->{'client-id'}) ?  $twch_data_prime->{'client-id'} : null; ?>
            <input id="api-key" type="text" placeholder="Client ID" name="client-id" value="<?= $clientID ?>" <?php if(!empty($twch_data_prime->{'client-id'})) echo 'disabled="true"' ?>>
            <input type="submit" value="sincronizar" name="sincronizar" <?php if(!empty($twch_data_prime->{'api-key'}) && !empty($twch_data_prime->{'client-id'})) echo 'disabled="true"' ?>>
            <?php 
                if(!empty($twch_data_prime->{'api-key'}) && 
                   !empty($twch_data_prime->{'client-id'})
                   ): ?>
                   
                   <input type="submit" value="resincronizar" name="resincronizar">
                <?php
                if(isset($_GET['resincronizar'])){
                    global $wpdb;
                    $array_keys = array(
                        'api-key' => '',
                        'client-id' => '',
                        'user_token' => ''
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
                        isset($_GET['api-key'])
                    ){
                        $client_id = $_GET['client-id'];
                        $secret_key = $_GET['api-key'];
                        fronted_to_db($client_id, $secret_key);
                    }
                }
            }
        ?>
        <table class="twittcher-table">
            <tr>
                <td>API KEY</td>
                <td><?= $twch_data_prime->{'api-key'}?></td>
            </tr>
            <tr>
                <td>Client ID</td>
                <td><?= $twch_data_prime->{'client-id'}?></td>
            </tr>
            <tr>
                <td>User token</td>
                <td><?= $twch_data_prime->{'user_token'}?></td>
            </tr>
        </table>
    </aside>