<style>
    <?php include 'main_page.css'; ?>
</style>

<div class="twchr-for-back-broadcast container">
    <h1>Broadcaster Settings</h1>
    <section class="twchr-for-back-broadcast__show">
        <?php 
           $data_broadcaster = get_option( 'twchr_data_broadcaster', false ) == false ?  false :  json_decode(get_option( 'twchr_data_broadcaster'));
           $nombre = $data_broadcaster->{'data'}[0]->{'display_name'};
           $broadcaster_id = $data_broadcaster->{'data'}[0]->{'id'};
           $description = $data_broadcaster->{'data'}[0]->{'description'};
           $foto = $data_broadcaster->{'data'}[0]->{'profile_image_url'};
        
        ?>
        <form action="./" method="get" class="formulario">
        <?php
           $arrayDataUser = $data_broadcaster->{'data'}[0];
           foreach ($arrayDataUser as $key => $value): 
        ?>
           <label for=""><?= $key?></label>
           <input type="text" value='<?= $value?>' disabled='true'> 
       <?php endforeach; ?>
        </form>
        
    </section>
</div>