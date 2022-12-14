
<div class="twchr_custom_card--contain custom_card_hd">
    <div class="twchr_card_body">
        <label for="twchr_schedule_card_input--title"><?php _e('Streaming Title','twitcher');?></label>
        <input id="twchr_schedule_card_input--title" name="twchr_schedule_card_input--title" class="twchr_schedule_card_input" type="text" value="<?php echo $title ?>">
        <label for="twchr_schedule_card_input--category"><?php _e('Twitch category','twitcher');?></label>
        <input id="twchr_schedule_card_input--category" name="twchr_schedule_card_input--category__name" type="text"/>       
        <input  name="twchr_schedule_card_input--category__value" type="hidden"/>       
        <label for="twchr_schedule_card_input--dateTime"><?php _e('Date time Streaming','twitcher');?></label>
        <input id="twchr_schedule_card_input--dateTime" name="twchr_schedule_card_input--dateTime" class="twchr_schedule_card_input" type="datetime-local" value="<?php echo $dateTime ?>">
        <label for="twchr_schedule_card_input--duration"><?php _e('Duration','twitcher');?></label>
        <input id="twchr_schedule_card_input--duration" name="twchr_schedule_card_input--duration" class="twchr_schedule_card_input" type="number" value="<?php echo $duration ?>">
        <label for="twchr_schedule_card_input--is_recurrig"><?php _e('Is Recurring ?','twitcher');?></label>
        <input id="twchr_schedule_card_input--is_recurrig" name="twchr_schedule_card_input--is_recurrig" class="twchr_schedule_card_input" type="checkbox" <?php echo $is_recurring == 'on' ? 'checked' : '' ?>>
        <label for="twchr_schedule_card_input--serie" <?php echo $is_recurring == false ? 'style="display:none;"' : '' ;?>><?php _e('Serie','twitcher');?></label>
        <input id="twchr_schedule_card_input--serie" name="twchr_schedule_card_input--serie" class="twchr_schedule_card_input" type="text" value="<?php echo $serie ?>" <?php echo $is_recurring == false ? 'disabled style="display:none;"' : '' ;?> >
        <input name="twchr_schedule_card_input--serie__id" type="hidden" >
        
        <section id="twchr_schedule_card_input--show" <?php echo $is_recurring == false ? 'style="display:none;"' : '' ;?>>
            <h5><?php _e('Repeat every:','twitcher');?></h5>
            <p>
                <span>Monday</span>
                <span>from 14:00 to 16:00 pm</span>
            </p>
        </section>
    </div>
</div>
<script>
    const twchr_schedule_metabox_container = document.querySelectorAll(".streaming-metabox-container");
    const twchr_schedule_card = document.querySelector(".twchr_custom_card--contain.custom_card_hd");
    const twchr_schedule_card_cat_tw = twchr_schedule_card.querySelector("#twchr_schedule_card_input--category");
    const twchr_is_recurring = twchr_schedule_card.querySelector("input[type='checkbox']");
    const twchr_data_broadcaster = <?php echo get_option('twchr_data_broadcaster');?>;
    const twchr_broad_type = twchr_data_broadcaster.data[0].broadcaster_type;
    twchr_is_recurring.addEventListener('click', (e)=>{
        const tag = e.target;
        const input_serie = twchr_schedule_card.querySelector("#twchr_schedule_card_input--serie");
        const input_serie_label = twchr_schedule_card.querySelector("label[for='twchr_schedule_card_input--serie']");

        const show_date = twchr_schedule_card.querySelector("#twchr_schedule_card_input--show");
        const dateRaw = new Date(document.querySelector("input#twchr_schedule_card_input--dateTime").value);
        


        // Si is_recurring 
        if(tag.checked == true){
            if(twchr_broad_type == 'partner' || twchr_broad_type == 'afiliate'){
                input_serie.removeAttribute('disabled');
                input_serie.style.display = 'block';
                input_serie_label.style.display = 'block';
                show_date.style.display = 'flex';
            }else{
                const opt1 = confirm("usted no es ni afiliado ni phatner asi que no puede crear un streaming singular ¿desea continuar?");
                // OJO USTED NO ES AFIADO ESTO DARA ERROR
                if(opt1){
                    input_serie.removeAttribute('disabled');
                    input_serie.style.display = 'block';
                    input_serie_label.style.display = 'block';
                    show_date.style.display = 'flex';
                }
            }
        }else{
            //
            input_serie.setAttribute('disabled','true');
            input_serie.style.display = 'none';
            input_serie_label.style.display = 'none';
            show_date.style.display = 'none';
            
            
            /*
                (broadcaster.broadcaster_type == 'partner' || broadcaster.broadcaster_type == 'afiliate') ?
                    muestro toda la card
                    :
                    opt1 = confirm("usted no es ni afiliado ni phatner asi que no puede crear un streaming singular ¿desea continuar?");
                    // OJO USTED NO ES AFIADO ESTO DARA ERROR
                    if(opt1 == true){
                        res = twchr_twitch_schedule_create()
                        alert(res.data);
                        SWCHT(OPT_user){
                            case 1 : get sereie data in sctream;
                            case 2 : redirect to serie tax edit
                        }
                    } 
                        

            */

        }       
    });
</script>