<?php
 $twchr_twicth_schedule_response = get_post_meta( get_the_ID(), 'twchr_stream_all_data_from_twitch') != false ? get_post_meta( get_the_ID(), 'twchr_stream_all_data_from_twitch')[0] : false;
 if(!empty($term_cat_twcht)){
	 $twchr_twtich_schedule_chapters = get_term_meta( $term_cat_twcht[0]->term_id, 'twchr_schdules_chapters', true );
	 $twchr_twicth_twitch_cat_img = get_term_meta( $term_cat_twcht[0]->term_id, 'twchr_stream_category_thumbail', true );
 }
 $twchr_stream_twtich_schedule_id = get_post_meta( get_the_ID(), 'twchr_stream_twtich_schedule_id' ) != false ? get_post_meta( get_the_ID(), 'twchr_stream_twtich_schedule_id' )[0] : '';

if ( isset( $_GET['twitcher_twitch_schedule_response'] ) && $_GET['twitcher_twitch_schedule_response'] == 'delete' ) {
	update_post_meta( get_the_ID(), 'twchr_stream_all_data_from_twitch', '' );

}


?>
<div class="twchr_car_tab1">
	<div class="twchr-card-row is_recurring">
		<label><?php twchr_esc_i18n( 'Is Recurring ?', 'html' ); ?></label>
		<div class="is-recurring-input-group">
			<div>
				<input id="twchr_schedule_card_input--is_recurrig__yes"  name="twchr_schedule_card_input--is_recurrig" class="twchr_schedule_card_input" type="radio" value="true" <?php echo $is_recurring == 'true' ? 'checked' : ''; ?> >
				<label for="twchr_schedule_card_input--is_recurrig__yes"><?php twchr_esc_i18n( 'Yes', 'html' ); ?></label>
			</div>
			<div>
				<input id="twchr_schedule_card_input--is_recurri__no"  name="twchr_schedule_card_input--is_recurrig" class="twchr_schedule_card_input" type="radio" value="false" <?php echo $is_recurring == 'false' ? 'checked' : ''; ?>>
				<label for="twchr_schedule_card_input--is_recurrig__no"><?php twchr_esc_i18n( 'No', 'html' ); ?></label>
			</div>
		</div>
		<div class="status" style="text-align: center;">
				<?php 
					if(COUNT($twchr_streaming_states) > 0){
						$term = $twchr_streaming_states[0];
						if($term->{'slug'} == 'future'){
							echo "<h4>Programed</h4>";
						}
					}				
				?>
			</div>
		<picture class="twchr-schedule-card-status-container">
			<?php if ( ! empty( $twchr_twicth_twitch_cat_img ) && $twchr_twicth_twitch_cat_img != false ) : ?>
					<div>
						<img  src="<?php echo str_replace( '-52x72', '-104x144', $twchr_twicth_twitch_cat_img ); ?>" alt="Twitcher Stream Category Thumbnail">
						<h5></h5>
					</div>
				<?php endif; ?>    
		</picture>
		<p><?php twchr_esc_i18n( '¿Is this streaming part of a serie or recurrent streaming?', 'html' ); ?></p>
	</div>
	<div class="twchr-card-row serie-name">
		<label for="twchr_schedule_card_input--title"><?php twchr_esc_i18n( 'Streaming Title', 'html' ); ?></label>
		<input id="twchr_schedule_card_input--title" name="twchr_schedule_card_input--title" class="twchr_schedule_card_input" type="text" <?php echo $is_recurring == true ? 'disabled="true"' : ''; ?>  value="<?php echo $title; ?>">
	</div>
	<div class="twchr-card-row serie">	
		<label for="twchr_schedule_card_input--serie__name" id="twchr_schedule_card_input--serie__name--label"><?php twchr_esc_i18n( 'Serie', 'html' ); ?></label>
		<div class="twchr_cards_input_badges">
			<select name="twchr_schedule_card_input--serie__name" id="twchr_schedule_card_input--serie">
				<option value="undefined"><?php esc_html_e('New serie','twitcher');?></option>
			</select>
			<badges id="twchr_term_serie_list"><?php echo $term_serie_list; ?></badges>
		</div>
		<p id="twchr_card_button_create_new_serie"><a target="_blank" href="<?php echo TWCHR_ADMIN_URL . 'edit-tags.php?taxonomy=serie&post_type=twchr_streams&from_cpt_id=' . get_the_id(); ?>"><?php twchr_esc_i18n( 'Create or Edit serie', 'html' ); ?></a></p>
	</div> 
	<div class="twchr-card-row" style="display: none;">
		<label for="twchr_schedule_card_input--dateTime"><?php twchr_esc_i18n( 'Streaming Date & Time', 'html' ); ?></label>
		<div>
			<input id="twchr_schedule_card_input--dateTime"  name="twchr_schedule_card_input--dateTime"	class="twchr_schedule_card_input" type="datetime-local" value="<?php echo esc_html( $date_time ); ?>">
			<p><?php echo ! empty( $dateTime ) ? esc_html( $date_time ) : ''; ?></p>
		</div>
		<div class="twchr_cards_input_badges twchr_schedule_card_select--dateTime">
			<select name="twchr_dateTime_slot" id="twchr_dateTime_slot">
			</select>
			<badges id="twchr_dateTime_slot" ><span><?php echo $twchr_date_time_slot != 'false' ? $twchr_date_time_slot : 'this serie not contains chapters'; ?></span></badges>
		</div>
	</div>
	<div class="twchr-card-row tw-category" style="display: none;">
		<label for="twchr_schedule_card_input--category"><?php twchr_esc_i18n( 'Twitch category', 'html' ); ?></label>
		<div class="twchr_cards_input_badges">
			<input id="twchr_schedule_card_input--category__value" name="twchr_schedule_card_input--category__value" type="hidden" value="<?php echo ! empty( $term_cat_twcht_id ) ? $term_cat_twcht_id : ''; ?>" />
			<input id="twchr_schedule_card_input--category__name" class="twchr_schedule_card_input" name="twchr_schedule_card_input--category__name" type="text" value="<?php echo ! empty( $term_cat_twcht_name ) ? $term_cat_twcht_name : ''; ?>" />
			<badges><?php echo $term_cat_twcht_list; ?></badges>
		</div>
	
	</div>
	<div class="twchr-card-row" style="display: none;">
		<label for="twchr_schedule_card_input--duration"><?php twchr_esc_i18n( 'Duration (mins)', 'html' ); ?></label>
		<input id="twchr_schedule_card_input--duration"  name="twchr_schedule_card_input--duration"	class="twchr_schedule_card_input" type="number" value="<?php echo esc_html( $duration ); ?>">
	</div>	
	<p id="twchr_twtich_schedule_response" style="display: none;"><?php echo esc_js( $twchr_twicth_schedule_response ); ?><p>
	<input type="hidden" name="twchr_stream_twtich_schedule_id" id="twchr_stream_twtich_schedule_id" value ="<?php echo $twchr_stream_twtich_schedule_id; ?>">
	<div class="twchr__schedule__loading">
		<h3>Loading... </h3>
		<span class="twchr__schedule__loading--icon"></span>
	</div>
</div>

<script>

function twchr_get_duration_form_RFC3666(end_time, start_time) {
	let date1Object = new Date(end_time);
	date1Object = Date.parse(date1Object);
	
	let date2Object = new Date(start_time);
	date2Object = Date.parse(date2Object);
	

	// Get the difference in milliseconds
	let diff = date1Object - date2Object;

	

	// Convert the difference to seconds, minutes, hours, and days
	let seconds = diff / 1000;
	let minutes = diff / (1000 * 60);
	let hours = diff / (1000 * 60 * 60);
	let days = diff / (1000 * 60 * 60 * 24);

	const response = {
		'seconds': seconds,
		'minutes': minutes,
		'hours': hours,
		'days': days
	};

	return response;
	
}

function twchr_every_reapeat_writer(newDate_raw,duration){
	const fecha = new Date(newDate_raw);
	let dia = '';
	switch (fecha.getDay()) {
		case 0 : dia = 'domingo';
		break;
		case 1 : dia = 'lunes';
		break;
		case 2 : dia = 'martes';
		break;
		case 3 : dia = 'miercoles';
		break;
		case 4 : dia = 'jueves';
		break;
		case 5 : dia = 'viernes';
		break;
		case 6 : dia = 'sabado';
		break;
		default : '';
		break;
	}
	

	const start_time = `${fecha.getHours()}:${fecha.getMinutes()}`;

	fecha.setMinutes(fecha.getMinutes() + duration);

	const end_time = `${fecha.getHours()}:${fecha.getMinutes()}`;
	
	const fecha_msg = `${dia} from <b>${start_time}</b> to <b>${end_time}</b>`;
	
	return fecha_msg;
}


const twchr_schedule_metabox_container = document.querySelectorAll(".streaming-metabox-container");
const twchr_schedule_card = document.querySelector(".twchr_custom_card--contain");
const twchr_schedule_card_cat_tw = twchr_schedule_card.querySelector("#twchr_schedule_card_input--category");
const twchr_schedule_card_serie_id = twchr_schedule_card.querySelector("#twchr_schedule_card_input--serie__id");
const twchr_schedule_card_duration = twchr_schedule_card.querySelector("#twchr_schedule_card_input--duration");
const twchr_schedule_card_dateTime = twchr_schedule_card.querySelector("#twchr_schedule_card_input--dateTime");
const twchr_is_recurring = twchr_schedule_card.querySelectorAll(".is-recurring-input-group input");
const input_title = twchr_schedule_card.querySelector("#twchr_schedule_card_input--title");
const input_post_title = document.querySelector("#title");
const twchr_data_broadcaster = <?php echo get_option( 'twchr_data_broadcaster' ); ?>;
const twchr_twtich_schedule_response = document.querySelector("#twchr_twtich_schedule_response");
const twchr_dateTime_slot = document.querySelector("#twchr_dateTime_slot span");
const twchr_dateTime_slot_option = document.querySelector("#twchr_dateTime_slot");
const twchr_stream_twtich_schedule_id = document.querySelector("#twchr_stream_twtich_schedule_id");

// Si is recurring es false
if (twchr_is_recurring[1].checked == true) {
    GSCJS.queryAll(".silde-1 .twchr-card-row").forEach(item => {
        if (item.classList.contains("serie") ||
            item.classList.contains("is_recurring")) {} else {
            item.style.display = "";
        }
    });
}

let twchr_card_connect_status = '<button style="background-color: transparent;color: var(--twchr-purple); border: 0; font-family: `Comfortaa`;font-weight: bold;font-size: 13px;text-decoration: underline;">Connect with Twitch</button>';
if(twchr_stream_twtich_schedule_id.value.length > 0){
	twchr_card_connect_status = '<span style="color:green;">Connected with Twitch<span>';
	// Obtengo el nombre del stream con ese id
}

if(document.querySelector(".twchr-schedule-card-status-container h5")){
	document.querySelector(".twchr-schedule-card-status-container h5").innerHTML = twchr_card_connect_status;
}

// si no esta vacio
if(!twchr_dateTime_slot.textContent.length == 0 && twchr_dateTime_slot.textContent.includes("{")){
	const badge = twchr_dateTime_slot.textContent;
	const object = JSON.parse(badge);
	const stream_id = object.chapter_id;
	const start_time = object.start_time;
	const end_time = object.end_time;
	const chapter_name = object.chapter_name;

	

	const duration = twchr_get_duration_form_RFC3666(end_time, start_time);
	
	const twchr_date = twchr_every_reapeat_writer(start_time,duration.minutes);
	document.querySelector("#twchr_dateTime_slot").innerHTML = `<option value="${stream_id};${chapter_name}|${start_time};${end_time}" >${chapter_name} ${start_time} - ${end_time}</option>`;
	twchr_dateTime_slot.innerHTML = twchr_date;
}


if(twchr_twtich_schedule_response.textContent.length > 0){
	const response = JSON.parse(twchr_twtich_schedule_response.textContent);
	if(response.status != 200 && !location.search.includes("twitcher_twitch_schedule_response=delete")){
		if(response.error){
			alert("Error: " + response.error);
		}

		alert("message: "+ response.message);

		if(response.message == 'single segment creation not authorized'){
			location.href = location.href+'&twitcher_twitch_schedule_response=delete';
		}

		if(response.url_redirect){
			function getParameterByName(name) {
				name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
				var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
				results = regex.exec(location.search);
				return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
			}
			const id_cpt_from = getParameterByName('post');
			location.href= response.url_redirect+"&twcr_from_cpt="+id_cpt_from;
		}
	}
	
}

if(twchr_schedule_card_dateTime.getAttribute("value").length > 0){
	twchr_schedule_card_dateTime.setAttribute('type','text');
	twchr_schedule_card_dateTime.value = twchr_schedule_card_dateTime.getAttribute("value");
}


const twchr_broad_type = twchr_data_broadcaster.data[0].broadcaster_type;



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

for (let i = 0; i < twchr_is_recurring.length; i++) {
	const element = twchr_is_recurring[i];
	element.addEventListener('click', (e) => {
				if(i == 0) twchr_modal_schedule__btn.setAttribute('data-twchr-is-recurring',true);
				const tag = e.target;
				const value = tag.value;
				const input_serie = twchr_schedule_card.querySelector("#twchr_schedule_card_input--serie");
				
				const input_serie_label = twchr_schedule_card.querySelector("label#twchr_schedule_card_input--serie__name--label");

				
				const dateRaw = document.querySelector("input#twchr_schedule_card_input--dateTime").value;
				const duration = document.querySelector("input#twchr_schedule_card_input--duration").value;
				


				// Si is_recurring es false
				if (twchr_is_recurring[1].checked == true) {
					GSCJS.queryAll(".silde-1 .twchr-card-row").forEach(item => {
						if(item.classList.contains("serie") ||
						item.classList.contains("is_recurring")){
						}else{
							item.style.display = "";
						}
					});
					GSCJS.queryOnly("#twchr_schedule_card_input--title").parentElement.querySelector("label").textContent= 'Streaming Title';
					// Evaluamos el estado broadcaster
					if (twchr_broad_type == 'partner' || twchr_broad_type == 'affiliate') {

					
						input_serie.parentElement.parentElement.style.display = 'none';
						input_serie_label.style.display = 'none';
						input_title.removeAttribute('disabled');
						document.querySelector("#twchr_dateTime_slot").parentElement.style.display = 'none'; 
						twchr_schedule_card_dateTime.parentElement.style.display = 'block';
						twchr_schedule_card_dateTime.removeAttribute('disabled');
						document.querySelector("#twchr_card_button_create_new_serie").style.display = "none";
						if(i == 1) twchr_modal_schedule__btn.setAttribute('data-twchr-is-recurring',false);
						
					} else {
						// Si el broacater type no es ni pather ni afilate
						const opt1 = confirm(
							"<?php twchr_esc_i18n( 'you are neither an affiliate nor a phatner so you cannot create a unique stream do you want to continue?', 'html' ); ?>"
							);
						// si opt1 es true lo desaparce
						if (opt1) {
						
							input_serie.parentElement.parentElement.style.display = 'none';
							input_serie_label.style.display = 'none';
							input_title.removeAttribute('disabled');
							document.querySelector("#twchr_dateTime_slot").parentElement.style.display = 'none'; 
							twchr_schedule_card_dateTime.parentElement.style.display = 'block';
							twchr_schedule_card_dateTime.removeAttribute('disabled')
							document.querySelector("#twchr_card_button_create_new_serie").style.display = "none";
							if(i == 1) twchr_modal_schedule__btn.setAttribute('data-twchr-is-recurring',false);
						} else {
							// volvermos al estado inicial del ckeckbox
							twchr_is_recurring[0].checked = true;
						}
					}
				} else {
					//Si is_recurring is true
					GSCJS.queryOnly("#twchr_schedule_card_input--title").parentElement.querySelector("label").textContent= 'Serie Name';
					input_serie.parentElement.parentElement.style.display = 'grid';
					input_serie_label.style.display = 'block';
					document.querySelector("#twchr_card_button_create_new_serie").style.display = "block";
					
					
			
					input_title.value = input_post_title.value;
					input_title.setAttribute('disabled', 'true');
					twchr_schedule_card_dateTime.setAttribute('disabled', 'true');
					twchr_schedule_card_dateTime.parentElement.style.display = 'none';
					document.querySelector("#twchr_dateTime_slot").parentElement.style.display = 'block'; 
					
					const twchr_ajax_input_serie = document.querySelector("#twchr_schedule_card_input--serie");
					twchrFetchGet(tchr_vars_admin.wp_api_route+"twchr/v1/twchr_get_serie",
						(res) => {
							res.forEach(item =>{
								const option = `<option value="${item.term_id}">${item.name+" - "+item.term_id}</option>`;
								twchr_ajax_input_serie.innerHTML = twchr_ajax_input_serie.innerHTML + option;
							});

							twchr_ajax_input_serie.addEventListener('click', (event) =>{
					
								const term_id = event.target.value;
									res.forEach(item =>{
										if(item.term_id == term_id){
											const chapters = item.chapters;
											chapters.forEach(chapter =>{
												const opt = `<option value=" ${chapter.id} | ${chapter.start_time} - ${chapter.end_time}">${chapter.title} ${chapter.start_time} - ${chapter.end_time}</option>`;
												twchr_dateTime_slot_option.innerHTML = twchr_dateTime_slot_option.innerHTML + opt;
											});
										}
									});

									document.querySelector("#twchr_schedule_card_input--serie__id").value = term_id;
									
							},{ passive: true});

							
							
							
							
							
							
							
						},
						'json');
							}
						},{ passive: true});
		}


</script>
