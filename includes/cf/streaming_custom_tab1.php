<div class="twchr_car_tab1">
    <label for="twchr_schedule_card_input--title"><?php _e('Streaming Title','twitcher');?></label>
    <input id="twchr_schedule_card_input--title" name="twchr_schedule_card_input--title"
        class="twchr_schedule_card_input" type="text" value="<?php echo $title ?>">
    <label for="twchr_schedule_card_input--category"><?php _e('Twitch category','twitcher');?></label>
    <div class="twchr_cards_input_badges">
        <input id="twchr_schedule_card_input--category" name="twchr_schedule_card_input--category__name" type="text" value="<?php echo !empty($term_cat_twcht_name) ? $term_cat_twcht_name : ''?>" />
        <badges><?php echo $term_cat_twcht_list; ?></badges>
    </div>
    <input name="twchr_schedule_card_input--category__value" type="hidden" value="<?php echo !empty($term_cat_twcht_id) ? $term_cat_twcht_id : ''?>" />
    <label for="twchr_schedule_card_input--dateTime"><?php _e('Date time Streaming','twitcher');?></label>
    <input id="twchr_schedule_card_input--dateTime" name="twchr_schedule_card_input--dateTime"
        class="twchr_schedule_card_input" type="datetime-local" value="<?php echo $dateTime ?>">
    <label for="twchr_schedule_card_input--duration"><?php _e('Duration','twitcher');?></label>
    <input id="twchr_schedule_card_input--duration" name="twchr_schedule_card_input--duration"
        class="twchr_schedule_card_input" type="number" value="<?php echo $duration ?>">
    <label for="twchr_schedule_card_input--is_recurrig"><?php _e('Is Recurring ?','twitcher');?></label>
    <input id="twchr_schedule_card_input--is_recurrig" name="twchr_schedule_card_input--is_recurrig"
        class="twchr_schedule_card_input" type="checkbox" checked>
    <label for="twchr_schedule_card_input--serie"><?php _e('Serie','twitcher');?></label>
    <div class="twchr_cards_input_badges">
        <input id="twchr_schedule_card_input--serie" name="twchr_schedule_card_input--serie" class="twchr_schedule_card_input" type="text" value="<?php echo !empty($term_serie_name) ? $term_serie_name : ''?>">
        <badges><?php echo $term_serie_list; ?></badges>
    </div>
    <input name="twchr_schedule_card_input--serie__id" type="hidden" value="<?php echo !empty($term_serie_id) ? $term_serie_id : ''?>">

    <section id="twchr_schedule_card_input--show">
        <h5><?php _e('Repeat every:','twitcher');?></h5>
        <p>
            <span>Monday</span>
            <span>from 14:00 to 16:00 pm</span>
        </p>
    </section>
</div>
<script>
const twchr_schedule_metabox_container = document.querySelectorAll(".streaming-metabox-container");
const twchr_schedule_card = document.querySelector(".twchr_custom_card--contain");
const twchr_schedule_card_cat_tw = twchr_schedule_card.querySelector("#twchr_schedule_card_input--category");
const twchr_is_recurring = twchr_schedule_card.querySelector("input[type='checkbox']");
const twchr_data_broadcaster = <?php echo get_option('twchr_data_broadcaster');?>;
const twchr_broad_type = twchr_data_broadcaster.data[0].broadcaster_type;
twchr_is_recurring.addEventListener('click', (e) => {
    const tag = e.target;
    const input_serie = twchr_schedule_card.querySelector("#twchr_schedule_card_input--serie");
    const input_serie_label = twchr_schedule_card.querySelector(
    "label[for='twchr_schedule_card_input--serie']");

    const show_date = twchr_schedule_card.querySelector("#twchr_schedule_card_input--show");
    const dateRaw = new Date(document.querySelector("input#twchr_schedule_card_input--dateTime").value);



    // Si is_recurring es false
    if (tag.checked == false) {
        // Evaluamos el estado broadcaster
        if (twchr_broad_type == 'partner' || twchr_broad_type == 'afiliate') {

            input_serie.setAttribute('disabled', true);
            input_serie.style.display = 'none';
            input_serie_label.style.display = 'none';
            show_date.style.display = 'none';
        } else {
            // Si el broacater type no es ni pather ni afilate
            const opt1 = confirm(
                "usted no es ni afiliado ni phatner asi que no puede crear un streaming singular ¿desea continuar?"
                );
            // si opt1 es true lo desaparce
            if (opt1) {
                input_serie.setAttribute('disabled', true);
                input_serie.style.display = 'none';
                input_serie_label.style.display = 'none';
                show_date.style.display = 'none';
            } else {
                // volvermos al estado inicial del ckeckbox
                tag.checked = true;
            }
        }
    } else {
        //Si checkbox is true
        input_serie.removeAttribute('disabled');
        input_serie.style.display = 'block';
        input_serie_label.style.display = 'block';
        show_date.style.display = 'flex';
    }
});
</script>