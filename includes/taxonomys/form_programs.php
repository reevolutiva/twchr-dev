<style>
    .twchr_toApi_form-field{
        width: 95%;
        display: grid;
        grid-template-columns:200px 1fr;
        grid-gap:30px 20px;
    }
    .twchr_toApi_form-field label{
        line-height: 1.3;
        font-weight: 600;
    }

    .twchr_toApi_form-field input,
    .twchr_toApi_form-field select{
        width: 100%;
        display: block;
        max-width:none;
    }

    @media screen and (max-width: 782px){
        .twchr_toApi_form-field{
            grid-template-columns:1fr;
            width: 100%;
            grid-gap:10px 0px;
        }
    }
</style>
<div class='twchr_toApi_form-field'>
    <label for="twchr_toApi_dateTime">Fecha y hora</label>
    <div>
        <input type="datetime-local" id="twchr_toApi_dateTime" name='twchr_toApi_dateTime' value="<?=$dateTime?>">
        <p>La fecha recurente en que se emitira tu transmisión.</p>
    </div>
    <label for="twchr_toApi_duration">Duration (minutos)</label>
    <div>
        <input type="number" id="twchr_toApi_duration" name="twchr_toApi_duration" value="<?=$duration?>">
        <p>Tiempo promedio que dura su transmición.</p>
    </div>
    <label for="twchr_toApi_category">Categoria</label>
    <div>
        <select name="twchr_toApi_category" <?php selected($select,509658)?> id="twchr_toApi_category">  
            <option value="509658">Just Chating</option> 
        </select>
        <p>Categoria del stream</p>
    </div>
    <label for="twchr_fromApi_allData">All Data</label>
    <div>
        <input type="text" name="twchr_fromApi_allData" id="twchr_fromApi_allData" disabled="true" value='<?= $allData;?>'>
        <p>Data procedente de la API de Twitch.</p>
    </div>
</div>