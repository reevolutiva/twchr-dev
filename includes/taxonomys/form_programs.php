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
    <label for="twchr_toApi_dateTime"><?php _e('Date and Time','twitcher'); ?></label>
    <div>
        <input type="datetime-local" id="twchr_toApi_dateTime" name='twchr_toApi_dateTime' value="<?=$dateTime?>">
        <p><?php _e('The recurring date your stream aired.','twitcher') ?></p>
    </div>
    <label for="twchr_toApi_duration"><?php _e('Duration','twitcher'); ?></label>
    <div>
        <input type="number" id="twchr_toApi_duration" name="twchr_toApi_duration" value="<?=$duration?>">
        <p><?php _e('Average time that your transmission lasts.','twitcher');?></p>
    </div>
    <label for="twchr_toApi_category_ajax"><?php _e('Category of Twitch','twitcher'); ?></label>
    <div>
        <input type="text" name="twchr_toApi_category_ajax" id="twchr_toApi_category_ajax" placeholder="write a category" value='<?= $select_name?>'>
        <p><?php _e('Category of twitch stream','twitcher'); ?></p>
    </div>
    <input type="hidden" name="twchr_toApi_category_value" id='twchr_toApi_category_value' value='<?= $select_value?>'>
    <input type="hidden" name="twchr_toApi_category_name" id='twchr_toApi_category_name' value='<?= $select_name?>'>
    <label for="twchr_fromApi_allData"><?php _e('All Data','twitcher');?></label>
    <div>
        <input data-twchr-stream-id="" type="text" name="twchr_fromApi_allData" id="twchr_fromApi_allData" disabled="true" value='<?= $allData;?>'>
        <p><?php _e('Data from Twitch','twitcher');?></p>
    </div>
</div>