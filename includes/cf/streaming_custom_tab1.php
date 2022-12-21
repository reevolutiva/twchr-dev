<?php $twchr_twicth_schedule_response = get_post_meta(get_the_ID(),'twchr_stream_all_data_from_twitch') ?>
<div class="twchr_car_tab1">
    <label for="twchr_schedule_card_input--title"><?php _e('Streaming Title','twitcher');?></label>
    <input id="twchr_schedule_card_input--title" name="twchr_schedule_card_input--title"
        class="twchr_schedule_card_input" type="text" <?php echo $is_recurring == true ? 'disabled="true"' : ''?>  value="<?php echo $title ?>">
    <label for="twchr_schedule_card_input--category"><?php _e('Twitch category','twitcher');?></label>
    <div class="twchr_cards_input_badges">
        <input id="twchr_schedule_card_input--category" class="twchr_schedule_card_input" name="twchr_schedule_card_input--category__name" type="text" value="<?php echo !empty($term_cat_twcht_name) ? $term_cat_twcht_name : ''?>" />
        <badges><?php echo $term_cat_twcht_list; ?></badges>
    </div>
    <input name="twchr_schedule_card_input--category__value" type="hidden" value="<?php echo !empty($term_cat_twcht_id) ? $term_cat_twcht_id : ''?>" />
    <label for="twchr_schedule_card_input--dateTime"><?php _e('Date time Streaming','twitcher');?></label>
    <input id="twchr_schedule_card_input--dateTime"  name="twchr_schedule_card_input--dateTime"
        class="twchr_schedule_card_input" type="<?php echo empty($dateTime) ? 'datetime-local' : 'text' ?>" value="<?php echo $dateTime ?>">
    <select name="twchr_dateTime_slot" id="twchr_dateTime_slot"></select>
    <label for="twchr_schedule_card_input--duration"><?php _e('Duration','twitcher');?></label>
    <input id="twchr_schedule_card_input--duration"  name="twchr_schedule_card_input--duration"
        class="twchr_schedule_card_input" type="number" value="<?php echo $duration ?>">
    <label for="twchr_schedule_card_input--is_recurrig"><?php _e('Is Recurring ?','twitcher');?></label>
    <input id="twchr_schedule_card_input--is_recurrig"  name="twchr_schedule_card_input--is_recurrig"
        class="twchr_schedule_card_input" type="checkbox" <?php echo !empty($is_recurring) == 'on' ? 'checked' :'';?> >
    <label for="twchr_schedule_card_input--serie__name" id="twchr_schedule_card_input--serie__name--label"><?php _e('Serie','twitcher');?></label>
    <div class="twchr_cards_input_badges">
        <input id="twchr_schedule_card_input--serie"  name="twchr_schedule_card_input--serie__name" class="twchr_schedule_card_input" type="text" value="<?php echo !empty($term_serie_name) ? $term_serie_name : ''?>">
        <badges><?php echo $term_serie_list; ?></badges>
        <p><a target="_blank" href="<?php echo TWCHR_ADMIN_URL.'edit-tags.php?taxonomy=serie&post_type=twchr_streams&from_cpt_id='.get_the_id(); ?>"><?php _e('Create a new serie','twitcher'); ?></a></p>
    </div>
    <input type="hidden" name="twchr_twtich_schedule_response" value="<?php echo $twchr_twicth_schedule_response?>">
   
    <input name="twchr_schedule_card_input--serie__id" type="hidden" value="<?php echo !empty($term_serie_id) ? $term_serie_id : ''?>"> 
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
            document.querySelector("#twchr_dateTime_slot").style.display = 'none'; 
            twchr_schedule_card_dateTime.style.display = 'block';
            twchr_schedule_card_dateTime.removeAttribute('disabled');
            
        } else {
            // Si el broacater type no es ni pather ni afilate
            const opt1 = confirm(
                "<?php _e('you are neither an affiliate nor a phatner so you cannot create a unique stream do you want to continue?','twitcher');?>"
                );
            // si opt1 es true lo desaparce
            if (opt1) {
                input_serie.setAttribute('disabled', true);
                input_serie.parentElement.style.display = 'none';
                input_serie_label.style.display = 'none';
                show_date.style.display = 'none';
                input_title.removeAttribute('disabled');
                document.querySelector("#twchr_dateTime_slot").style.display = 'none'; 
                twchr_schedule_card_dateTime.style.display = 'block';
                twchr_schedule_card_dateTime.removeAttribute('disabled')
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
        if( twchr_schedule_card_dateTime.value.length > 0){
            const repeat_every = twchr_every_reapeat_writer(dateRaw,duration);
            show_date.querySelector("p").innerHTML = repeat_every;
        }
  
        input_title.value = input_post_title.value;
        input_title.setAttribute('disabled', 'true');
        twchr_schedule_card_dateTime.setAttribute('disabled', 'true');
        twchr_schedule_card_dateTime.style.display = 'none';
        document.querySelector("#twchr_dateTime_slot").style.display = 'block'; 
        getSchedules_by_id((data)=>{
            const segments = data.segments;
            document.querySelector("#twchr_dateTime_slot").innerHTML = '';
            segments.forEach(segment =>{
                const id = segment.id;
                const title = segment.title;
                const option = `<option value="${id}" >${title} - ${segment.start_time} - ${segment.end_time}</option>`;
                document.querySelector("#twchr_dateTime_slot").innerHTML = document.querySelector("#twchr_dateTime_slot").innerHTML + option;

                
            });

            [...document.querySelectorAll("#twchr_dateTime_slot option")].forEach(
                option => {
                    option.addEventListener('click', (event) =>{
                        twchr_schedule_id = event.target.value;
                        segments.forEach(segment =>{
                            const id = segment.id;
                            if(id === twchr_schedule_id){
                                const title = segment.title;
                                const start_time = segment.start_time;
                                const end_time = segment.end_time;
                                const category = segment.category;

                                                          
                                twchr_schedule_card_cat_tw.value = category.name;
                                const duration = twchr_get_duration_form_RFC3666(end_time, start_time);
                                twchr_schedule_card_duration.value  = duration.minutes;
                                twchr_schedule_card_dateTime.setAttribute('type','text');
                                twchr_schedule_card_dateTime.value =  start_time;
                                input_title.value = title;

                                const repeat_every = twchr_every_reapeat_writer(dastart_time,duration);
                                show_date.querySelector("p").innerHTML = repeat_every;
                                

                            }
                        })

                    });
                    //console.log(option);
                }
            );
        });
    }
});
</script>