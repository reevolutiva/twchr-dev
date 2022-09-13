<style>
    <?php include 'main_page.css'; ?>
</style>

<div class="ego-for-back container">
    <main>
        <h1>Bienvenido a Hello Egosapiens</h1>
        <form method="GET" action="./admin.php">
            <input type="hidden" name="page" value="egosapiens_wp_menu">
            <input type="text" placeholder="Client ID" name="client-id">
            <input type="text" placeholder="Secret key" name="secret-key">
            <input type="submit" value="sincronizar">
        </form>
    </main>
    <aside>
    <?php 
            if(isset($_GET)){  
                if(count($_GET) > 1){
                    $client_id = $_GET['client-id'];
                    $secret_key = $_GET['secret-key'];
                    var_dump($_GET);
                }
            }
        ?>
    </aside>