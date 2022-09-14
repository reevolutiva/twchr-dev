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
                endif;
            ?>
        </form>

        <hr/>

        <form action="./admin.php" method="get">
            <p>User este enlace para la redireccion que solicita twitch: <span style="color:tomato;"><?= bloginfo('url')?>/wp-admin/admin.php?page=egosapiens_wp_menu</span></p>
            <input type="hidden" name="page" value="egosapiens_wp_menu">
            <input type="hidden" name="userToken_api-token" value="<?= $apiKey ?>">
            <input type="hidden" name="userToken_client-id" value="<?= $clientID ?>">
            <input type="hidden" name="autentication" value="true">
            <input type="submit" value="solitiar user token">
            <?php 
                if($_GET['autentication']==true){
                    $client_id = $_GET['userToken_api-token'];
                    $secret_key = $_GET['userToken_client-id'];
                    autenticate($client_id, $secret_key,'https://egosapiens.local/wp-admin/admin.php?page=egosapiens_wp_menu');
                } 
                if(isset($_GET['code'])){
					var_dump($_GET['code']);
				}
				
            ?>
            <input type="text" placeholder="user token" name="user-token">
        </form>
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
                        var_dump($_GET);
                        fronted_to_db($client_id, $secret_key);
                    }
                }
            }
        ?>
    </aside>