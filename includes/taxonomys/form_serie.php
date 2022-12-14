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
    </div>
    <label for="twchr_toApi_duration"><?php twchr_esc_i18n('Chapter','html'); ?></label>
    <div>
        <input type="text" id="twchr_toApi_schedule_chapter" name='twchr_toApi_schedule_chapter' value="<?php echo /*$schedule_chapter*/ '{"starting_at":"2020-12-10","duration":"60","id":"lkasjdlasjdlja"}';?>">
    </div>
</div>