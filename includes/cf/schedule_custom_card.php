<div class="streaming-metabox-container twchr-streaming-metabox-container">

</div>
<div class="twchr_custom_card--contain custom_card_hd">
    <div class="twchr_card_body">
        <label for="twchr_schedule_card_input--title"><?php _e('Streaming Title','twitcher');?></label>
        <input id="twchr_schedule_card_input--title" name="twchr_schedule_card_input--title" class="twchr_schedule_card_input" type="text">
        <label for="twchr_schedule_card_input--category"><?php _e('Twitch category','twitcher');?></label>
        <input id="twchr_schedule_card_input--category" type="text"/>       
        <label for="twchr_schedule_card_input--dateTime"><?php _e('Date time Streaming','twitcher');?></label>
        <input id="twchr_schedule_card_input--dateTime" name="twchr_schedule_card_input--dateTime" class="twchr_schedule_card_input" type="text">
        <label for="twchr_schedule_card_input--duration"><?php _e('Duration','twitcher');?></label>
        <input id="twchr_schedule_card_input--duration" name="twchr_schedule_card_input--duration" class="twchr_schedule_card_input" type="text">
        <label for="twchr_schedule_card_input--is_recurrig"><?php _e('Is Recurring ?','twitcher');?></label>
        <input id="twchr_schedule_card_input--is_recurrig" name="twchr_schedule_card_input--is_recurrig" class="twchr_schedule_card_input" type="checkbox" checked="true">
        <label for="twchr_schedule_card_input--serie_name"><?php _e('Serie name','twitcher');?></label>
        <input id="twchr_schedule_card_input--serie_name" name="twchr_schedule_card_input--serie_name" class="twchr_schedule_card_input" type="text">
        
        <section id="twchr_schedule_card_input--show">
            <h5><?php _e('Repeat every:','twitcher');?></h5>
            <p>
                <span>Monday</span>
                <span>from 14:00 to 16:00 pm</span>
            </p>
        </section>
    </div>
</div>
<script>
    const twchr_schedule_metabox_container = document.querySelector(".streaming-metabox-container");
    const twchr_schedule_card = document.querySelector(".twchr_custom_card--contain.custom_card_hd");
    const twchr_schedule_card_cat_tw = twchr_schedule_card.querySelector("#twchr_schedule_card_input--category");
    const twchr_is_recurring = twchr_schedule_card.querySelector("input[type='checkbox']");
    twchr_is_recurring.addEventListener('click', (e)=>{
        const tag = e.target;
        const input_serie = twchr_schedule_card.querySelector("#twchr_schedule_card_input--serie_name");
        const input_serie_label = twchr_schedule_card.querySelector("label[for='twchr_schedule_card_input--serie_name']");

        const show_date = twchr_schedule_card.querySelector("#twchr_schedule_card_input--show");
        if(tag.checked == true){
            input_serie.setAttribute('disabled', false);
            input_serie.style.display = 'block';
            input_serie_label.style.display = 'block';
            show_date.style.display = 'flex';
        }else{
            input_serie.setAttribute('disabled', true);
            input_serie.style.display = 'none';
            input_serie_label.style.display = 'none';
            show_date.style.display = 'none';
        }       
    });
</script>