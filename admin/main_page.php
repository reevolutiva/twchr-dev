<style>
    <?php include 'main_page.css'; ?>
</style>

<div class="ego-for-back container">
    <main>
        <h1>Bienvenido a Hello Egosapiens</h1>
        <form method="GET" action="./admin.php">
            <input type="hidden" name="page" value="egosapiens_wp_menu">
            <input type="text" placeholder='Titulo' name="titulo">
            <label for="">
                <p>Fecha y hora</p>
                <input type="datetime-local" name="date-time" id="">
            </label>
            <label for="">
                <p>¿Es una serie?</p>
                <input type="checkbox" name="isSeries" id="">
            </label>
            <label for="">
                <p>Duracion</p>
                <input type="number" name="duration" id="">
            </label>
            <input type="submit" value="enviar">
        </form>
        <?php 
            $fecha  = date("Y-m-d\TH:i:sP");
            var_dump($fecha);
            if(isset($_GET)){  
                if(count($_GET) > 1){
                    $titulo = $_GET['title'];
                    $dateTime = $_GET['date-time'];
                    $is_series = $_GET['isSeries'];
                    $duration = $_GET['duration']."s";
                    var_dump($_GET);
                }
            }
        ?>
    </main>
    <aside><img src="<?php echo plugin_dir_url(__DIR__)?>includes/assets/logo-color.png" alt=""></aside>
</div>