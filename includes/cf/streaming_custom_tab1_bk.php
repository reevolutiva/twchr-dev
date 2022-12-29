<?php $twchr_twicth_schedule_response = get_post_meta( get_the_ID(), 'twchr_stream_all_data_from_twitch' )[0]; ?>
<?php $twchr_twtich_schedule_chapters = get_term_meta( $term_id, 'twchr_schdules_chapters', $single ); ?>
<div class="twchr_car_tab1">
	<div class="twchr-card-row">
		<label><?php twchr_esc_i18n( 'Is Recurring ?', 'html' ); ?></label>
		<div class="is-recurring-input-group">
			<label for="twchr_schedule_card_input--is_recurrig__yes"><?php twchr_esc_i18n( 'Yes', 'html' ); ?></label>
			<input id="twchr_schedule_card_input--is_recurrig__yes"  name="twchr_schedule_card_input--is_recurrig" class="twchr_schedule_card_input" type="radio" value="true" <?php echo $is_recurring == true ? 'checked' : '' ?> >
			<label for="twchr_schedule_card_input--is_recurrig__no"><?php twchr_esc_i18n( 'No', 'html' ); ?></label>
			<input id="twchr_schedule_card_input--is_recurri__no"  name="twchr_schedule_card_input--is_recurrig" class="twchr_schedule_card_input" type="radio" value="false" <?php echo $is_recurring == false ? 'checked' : '' ?>>
		</div>
		<p><?php twchr_esc_i18n("¿Is this streaming part of a serie?",'html');?></p>
	</div>
	<div class="twchr-card-row">
		<label for="twchr_schedule_card_input--title"><?php twchr_esc_i18n( 'Streaming Title', 'html' ); ?></label>
		<input id="twchr_schedule_card_input--title" name="twchr_schedule_card_input--title" class="twchr_schedule_card_input" type="text" <?php echo $is_recurring == true ? 'disabled="true"' : ''; ?>  value="<?php echo $title; ?>">
		<label for="twchr_schedule_card_input--category"><?php twchr_esc_i18n( 'Twitch category', 'html' ); ?></label>
		<div class="twchr_cards_input_badges">
			<input id="twchr_schedule_card_input--category" class="twchr_schedule_card_input" name="twchr_schedule_card_input--category__name" type="text" value="<?php echo ! empty( $term_cat_twcht_name ) ? $term_cat_twcht_name : ''; ?>" />
			<badges><?php echo $term_cat_twcht_list; ?></badges>
		</div>
	</div>
	<div class="twchr-card-row">
		<div>
			<label for="twchr_schedule_card_input--dateTime"><?php twchr_esc_i18n( 'Date time Streaming', 'html' ); ?></label>
			<input id="twchr_schedule_card_input--dateTime"  name="twchr_schedule_card_input--dateTime"	class="twchr_schedule_card_input" type="datetime-local" value="<?php echo esc_html( $date_time ); ?>">
			<p><?php echo ! empty( $dateTime ) ? esc_html( $date_time ) : ''; ?></p>
		</div>
		<label for="twchr_schedule_card_input--duration"><?php twchr_esc_i18n( 'Duration (mins)', 'html' ); ?></label>
		<input id="twchr_schedule_card_input--duration"  name="twchr_schedule_card_input--duration"	class="twchr_schedule_card_input" type="number" value="<?php echo esc_html( $duration ); ?>">
	</div>
	<div class="twchr-card-row">	
		<input name="twchr_schedule_card_input--category__value" type="hidden" value="<?php echo ! empty( $term_cat_twcht_id ) ? $term_cat_twcht_id : ''; ?>" />
		
		
		<div class="twchr_cards_input_badges">
			<select name="twchr_dateTime_slot" id="twchr_dateTime_slot">
			</select>
			<badges id="twchr_dateTime_slot" ><span><?php echo $twchr_dateTime_slot; ?></span></badges>
		</div>

			
		<label for="twchr_schedule_card_input--serie__name" id="twchr_schedule_card_input--serie__name--label"><?php twchr_esc_i18n( 'Serie', 'html' ); ?></label>
		<div class="twchr_cards_input_badges">
			<select name="twchr_schedule_card_input--serie__name" id="twchr_schedule_card_input--serie__name">
				<option value="null">notting</option>
			</select>
			<badges id="twchr_term_serie_list"><?php echo $term_serie_list; ?></badges>
			<p><a target="_blank" href="<?php echo TWCHR_ADMIN_URL . 'edit-tags.php?taxonomy=serie&post_type=twchr_streams&from_cpt_id=' . get_the_id(); ?>"><?php twchr_esc_i18n( 'Create a new serie', 'html' ); ?></a></p>
		</div>
	
	</div>
	<p id="twchr_twtich_schedule_response" style="display: none;"><?php echo esc_js( $twchr_twicth_schedule_response ); ?><p>
   

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
const twchr_is_recurring = twchr_schedule_card.querySelector("input[type='checkbox']");
const input_title = twchr_schedule_card.querySelector("#twchr_schedule_card_input--title");
const input_post_title = document.querySelector("#title");
const twchr_data_broadcaster = <?php echo get_option( 'twchr_data_broadcaster' ); ?>;
const twchr_twtich_schedule_response = document.querySelector("#twchr_twtich_schedule_response");
const twchr_dateTime_slot = document.querySelector("#twchr_dateTime_slot span");
// si no esta vacio
if(!twchr_dateTime_slot.textContent.length == 0){
	const badge = twchr_dateTime_slot.textContent;
	const byPipe = badge.split("|");
	const stream_id = byPipe[0];
	const dates = byPipe[1];

	const start_time = dates.split(" - ")[0].trim();
	const end_time = dates.split(" - ")[1];


	const duration = twchr_get_duration_form_RFC3666(end_time, start_time);
	
	const twchr_date = twchr_every_reapeat_writer(start_time,duration.minutes);
	twchr_dateTime_slot.innerHTML = twchr_date;
}


if(twchr_twtich_schedule_response.textContent.length > 0){
	const response = JSON.parse(twchr_twtich_schedule_response.textContent);
	if(response.status != 200){
		alert("Error: " + response.error);
		alert("message: "+ response.message);
	}
	
}


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
	const input_serie = twchr_schedule_card.querySelector("#twchr_schedule_card_input--serie__name");
	
	const input_serie_label = twchr_schedule_card.querySelector("label#twchr_schedule_card_input--serie__name--label");

	const show_date = twchr_schedule_card.querySelector("#twchr_schedule_card_input--show");
	const dateRaw = document.querySelector("input#twchr_schedule_card_input--dateTime").value;
	const duration = document.querySelector("input#twchr_schedule_card_input--duration").value;
	


	// Si is_recurring es false
	if (tag.checked == false) {
		// Evaluamos el estado broadcaster
		if (twchr_broad_type == 'partner' || twchr_broad_type == 'afiliate') {

		   
			input_serie.parentElement.style.display = 'none';
			input_serie_label.style.display = 'none';
			show_date.style.display = 'none';
			input_title.removeAttribute('disabled');
			document.querySelector("#twchr_dateTime_slot").style.display = 'none'; 
			twchr_schedule_card_dateTime.parentElement.style.display = 'block';
			twchr_schedule_card_dateTime.removeAttribute('disabled');
			
		} else {
			// Si el broacater type no es ni pather ni afilate
			const opt1 = confirm(
				"<?php twchr_esc_i18n( 'you are neither an affiliate nor a phatner so you cannot create a unique stream do you want to continue?', 'html' ); ?>"
				);
			// si opt1 es true lo desaparce
			if (opt1) {
			   
				input_serie.parentElement.style.display = 'none';
				input_serie_label.style.display = 'none';
				show_date.style.display = 'none';
				input_title.removeAttribute('disabled');
				document.querySelector("#twchr_dateTime_slot").style.display = 'none'; 
				twchr_schedule_card_dateTime.parentElement.style.display = 'block';
				twchr_schedule_card_dateTime.removeAttribute('disabled')
			} else {
				// volvermos al estado inicial del ckeckbox
				tag.checked = true;
			}
		}
	} else {
		//Si checkbox is true
		
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
		twchr_schedule_card_dateTime.parentElement.style.display = 'none';
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
