<div class="twchr_car_tab1">
    <label for="twchr_schedule_card_input--title"><?php _e('Streaming Title','twitcher');?></label>
    <input id="twchr_schedule_card_input--title" name="twchr_schedule_card_input--title"
        class="twchr_schedule_card_input" type="text" <?php echo $is_recurring == true ? 'disabled="true"' : 'required'?>  value="<?php echo $title ?>">
    <label for="twchr_schedule_card_input--category"><?php _e('Twitch category','twitcher');?></label>
    <div class="twchr_cards_input_badges">
        <input id="twchr_schedule_card_input--category" class="twchr_schedule_card_input" name="twchr_schedule_card_input--category__name" type="text" value="<?php echo !empty($term_cat_twcht_name) ? $term_cat_twcht_name : ''?>" />
        <badges><?php echo $term_cat_twcht_list; ?></badges>
    </div>
    <input name="twchr_schedule_card_input--category__value" type="hidden" value="<?php echo !empty($term_cat_twcht_id) ? $term_cat_twcht_id : ''?>" />
    <label for="twchr_schedule_card_input--dateTime"><?php _e('Date time Streaming','twitcher');?></label>
    <input id="twchr_schedule_card_input--dateTime" required name="twchr_schedule_card_input--dateTime"
        class="twchr_schedule_card_input" type="<?php echo empty($dateTime) ? 'datetime-local' : 'text' ?>" value="<?php echo $dateTime ?>">
    <label for="twchr_schedule_card_input--duration"><?php _e('Duration','twitcher');?></label>
    <input id="twchr_schedule_card_input--duration" required name="twchr_schedule_card_input--duration"
        class="twchr_schedule_card_input" type="number" value="<?php echo $duration ?>">
    <label for="twchr_schedule_card_input--is_recurrig"><?php _e('Is Recurring ?','twitcher');?></label>
    <input id="twchr_schedule_card_input--is_recurrig" required name="twchr_schedule_card_input--is_recurrig"
        class="twchr_schedule_card_input" type="checkbox" <?php echo !empty($is_recurring) == 'on' ? 'checked' :'';?> >
    <label for="twchr_schedule_card_input--serie__name" id="twchr_schedule_card_input--serie__name--label"><?php _e('Serie','twitcher');?></label>
    <div class="twchr_cards_input_badges">
        <input id="twchr_schedule_card_input--serie" required name="twchr_schedule_card_input--serie__name" class="twchr_schedule_card_input" type="text" value="<?php echo !empty($term_serie_name) ? $term_serie_name : ''?>">
        <badges><?php echo $term_serie_list; ?></badges>
    </div>
    <input name="twchr_schedule_card_input--serie__id" type="hidden" value="<?php echo !empty($term_serie_id) ? $term_serie_id : ''?>">
    <label for="twchr_schedule_card_input--show--slot__validate"><?php _e('user future schedule segment','twitcher'); ?></label>
    <input type="checkbox" class="twchr_schedule_card_input" name="twchr_schedule_card_input--show--slot__validate" id="twchr_schedule_card_input--show--slot__validate--show__validate">
    <select name="twchr_dateTime_slot" id="twchr_dateTime_slot"></select>
 

    <section id="twchr_schedule_card_input--show">
        <h5><?php _e('Repeat every:','twitcher');?></h5>
        <p>
            <?php _e('Select you Date Time','twitcher')?>
        </p>
    </section>
</div>
<script>
const twchr_schedule_metabox_container = document.querySelectorAll(".streaming-metabox-container");
const twchr_schedule_card = document.querySelector(".twchr_custom_card--contain");
const twchr_schedule_card_cat_tw = twchr_schedule_card.querySelector("#twchr_schedule_card_input--category");
const twchr_schedule_card_duration = twchr_schedule_card.querySelector("#twchr_schedule_card_input--duration");
const twchr_schedule_card_dateTime = twchr_schedule_card.querySelector("#twchr_schedule_card_input--dateTime");
const twchr_is_recurring = twchr_schedule_card.querySelector("input[type='checkbox']");
const input_title = twchr_schedule_card.querySelector("#twchr_schedule_card_input--title");
const input_post_title = document.querySelector("#title");
const twchr_data_broadcaster = <?php echo get_option('twchr_data_broadcaster');?>;

const twchr_broad_type = twchr_data_broadcaster.data[0].broadcaster_type;

if(twchr_is_recurring.checked == true){
    input_title.value = input_post_title.value;
}


twchr_schedule_card_duration.oninput = ()=>{
    if(twchr_is_recurring.checked == true && twchr_schedule_card_dateTime.value.length > 0 && twchr_schedule_card_dateTime.value.length > 0){
        const repeat_every = twchr_every_reapeat_writer(twchr_schedule_card_dateTime.value,twchr_schedule_card_duration.value);
        document.querySelector("#twchr_schedule_card_input--show p").innerHTML = repeat_every;
    }
}
twchr_schedule_card_dateTime.oninput = ()=>{
    if(twchr_is_recurring.checked == true && twchr_schedule_card_dateTime.value.length > 0 && twchr_schedule_card_dateTime.value.length > 0){
        const repeat_every = twchr_every_reapeat_writer(twchr_schedule_card_dateTime.value,twchr_schedule_card_duration.value);
        document.querySelector("#twchr_schedule_card_input--show p").innerHTML = repeat_every;
    }
}

twchr_is_recurring.addEventListener('click', (e) => {
    const tag = e.target;
    const input_serie = twchr_schedule_card.querySelector("#twchr_schedule_card_input--serie");
    
    const input_serie_label = twchr_schedule_card.querySelector("label#twchr_schedule_card_input--serie__name--label");

    const show_date = twchr_schedule_card.querySelector("#twchr_schedule_card_input--show");
    const dateRaw = document.querySelector("input#twchr_schedule_card_input--dateTime").value;
    const duration = document.querySelector("input#twchr_schedule_card_input--duration").value;
    


    // Si is_recurring es false
    if (tag.checked == false) {
        // Evaluamos el estado broadcaster
        if (twchr_broad_type == 'partner' || twchr_broad_type == 'afiliate') {

            input_serie.setAttribute('disabled', true);
            input_serie.parentElement.style.display = 'none';
            input_serie_label.style.display = 'none';
            show_date.style.display = 'none';
            input_title.removeAttribute('disabled');
            
        } else {
            // Si el broacater type no es ni pather ni afilate
            const opt1 = confirm(
                "usted no es ni afiliado ni phatner asi que no puede crear un streaming singular ¿desea continuar?"
                );
            // si opt1 es true lo desaparce
            if (opt1) {
                input_serie.setAttribute('disabled', true);
                input_serie.parentElement.style.display = 'none';
                input_serie_label.style.display = 'none';
                show_date.style.display = 'none';
                input_title.removeAttribute('disabled');
            } else {
                // volvermos al estado inicial del ckeckbox
                tag.checked = true;
            }
        }
    } else {
        //Si checkbox is true
        input_serie.removeAttribute('disabled');
        input_serie.parentElement.style.display = 'block';
        input_serie_label.style.display = 'block';
        show_date.style.display = 'flex';
        if( twchr_schedule_card_dateTime.value.length > 0 && twchr_schedule_card_dateTime.value.length > 0){
            const repeat_every = twchr_every_reapeat_writer(dateRaw,duration);
            show_date.querySelector("p").innerHTML = repeat_every;
        }
  
        input_title.value = input_post_title.value;
        input_title.setAttribute('disabled', 'true');
    }
});
</script>