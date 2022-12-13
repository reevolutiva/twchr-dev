<div class="twchr_custom_card--contain custom_card_hd">
    <div class="twchr_card_header">
        <div class="twchr_card_header--title">
            <img src="<?php echo TWCHR_URL_ASSETS.'twitch_logo.png'; ?>" alt="logo-twitch">
            <h3>Schedule Data</h3>
        </div>
    </div>
    <div class="twchr_card_body">
        <label for="twchr_schedule_card_input--title"><?php _e('Streaming Title','twitcher');?></label>
        <input id="twchr_schedule_card_input--title" name="twchr_schedule_card_input--title" class="twchr_schedule_card_input" type="text">
        <label for="twchr_schedule_card_input--category"><?php _e('Twitch category','twitcher');?></label>
        <input id="twchr_schedule_card_input--category" name="twchr_schedule_card_input--category" class="twchr_schedule_card_input" type="text">
        <label for="twchr_schedule_card_input--dateTime"><?php _e('Date time Streaming','twitcher');?></label>
        <input id="twchr_schedule_card_input--dateTime" name="twchr_schedule_card_input--dateTime" class="twchr_schedule_card_input" type="text">
        <label for="twchr_schedule_card_input--duration"><?php _e('Duration','twitcher');?></label>
        <input id="twchr_schedule_card_input--duration" name="twchr_schedule_card_input--duration" class="twchr_schedule_card_input" type="text">
        <label for="twchr_schedule_card_input--is_recurrig"><?php _e('Is Recurring ?','twitcher');?></label>
        <input id="twchr_schedule_card_input--is_recurrig" name="twchr_schedule_card_input--is_recurrig" class="twchr_schedule_card_input" type="checkbox">
        <label for="twchr_schedule_card_input--serie_name"><?php _e('Serie name','twitcher');?></label>
        <input id="twchr_schedule_card_input--serie_name" name="twchr_schedule_card_input--serie_name" class="twchr_schedule_card_input" type="text">
        <section>
            <h5><?php _e('Repeat every:','twitcher');?></h5>
            <p>
                <span>Monday</span>
                <span>from 14:00 to 16:00 pm</span>
            </p>
        </section>
    </div>
</div>