const twchr_card_header_menu = document.querySelectorAll(".twchr-tab-card-bar .twchr-tab-card");
const twchr_slide_card_row = document.querySelector(".twchr_custom_card--contain .custom_card_row");
const twchr_slide_card__section_to_api = document.querySelector(".twchr_custom_card--contain .custom_card_row .twchr_car_tab1");
const twchr_slide_card__section_to_api_input = twchr_slide_card__section_to_api.querySelectorAll("input.twchr_schedule_card_input");
const twchr_card_embed_menu = document.querySelectorAll(".twchr_card_embed_menu  input");
const twchr_modal_schedule__btn = document.querySelector("#twchr-modal-schedule__btn");


/**
 * //FLOW CREATE SCHEDULE NOT RECURENT FROM CARD
 * IF CPT CONTAINS SCHEDULE ID ?
 *  IF ({GET SCHEDULE BY ID FROM TWITCH()}) == SUCCESS ?
 *        REWRITE CARD WITH DATA FROM API
 * 
 * RECOLLET INPUTS FROM SLIDE 1
 * PUSH INPUTS IN OBJET
 * SEND OBJET TO WP API
 * WP UPDATE  CF
 * SEND RESPONSE EXIST OR ERROR
 *   
 */

/*
 * //FLOW ASIGNS STREAMING OR VOD
    RECOLLECT IN OBJECT
    WP.AJAX.SEND OBJECT
    SAVE CF IN ACTION
    REPONSE EXIT OR ERROR

*/

function twtchr_schedule_segment_create(body,callback,error_callback) {
    const client_id = twchr_card_credentials.twchr_keys['client-id'];
    const token = twchr_card_credentials.twchr_keys['user_token']; 
    const broadcaster_id = twchr_card_credentials.twitcher_data_broadcaster.id; 
	var myHeaders = new Headers();
    myHeaders.append("Authorization", "Bearer "+token);
    myHeaders.append("client-id", client_id);
    myHeaders.append("Content-Type", "application/json");
    let requestOptions = {
        method: 'POST',
        headers: myHeaders,
        body: body
    };
    let response;
    fetch(
      "https://api.twitch.tv/helix/schedule/segment?broadcaster_id=" +
        broadcaster_id,
      requestOptions
    )
      .then((response) => response.text())
      .then((result) => callback(result))
      .catch((error) => error_callback(error));

    return response;
}

function twchr_card_embend_change_by_state(state){
    if(state === 'tw'){
        document.querySelector(".twchr_car_tab2").style.display = "block";
        document.querySelector(".twchr_car_tab3").style.display = "none";
        twchr_card_embed_menu[0].checked = true;
    }else if(state == 'yt'){
        document.querySelector(".twchr_car_tab2").style.display = "none";
        document.querySelector(".twchr_car_tab3").style.display = "block";
        twchr_card_embed_menu[1].checked = true;
    }
}

twchr_card_embed_menu[0].addEventListener('click',(e)=>{
    twchr_card_embed_menu_state = 'tw';
    twchr_card_embend_change_by_state(twchr_card_embed_menu_state);
});

twchr_card_embed_menu[1].addEventListener('click',(e)=>{
    twchr_card_embed_menu_state = 'yt';
    twchr_card_embend_change_by_state(twchr_card_embed_menu_state);
});

twchr_card_embend_change_by_state(twchr_card_embed_menu_state);
document.querySelector("body").classList.add("twchr-single-streaming-active");
let twchr_card_state = 'schedule';
twchr_card_header_menu[1].addEventListener('click', ()=>{
    twchr_card_state = 'embed';
    twchr_card_header_menu[1].classList.remove("disabled");
    twchr_card_header_menu[0].classList.add("disabled");
    twchr_slide_card_row.style.transform = 'translateX(calc(-100% - .5cm))';
    document.querySelector(".twchr_car_tab2").style.display = 'block';
    document.querySelector(".twchr_custom_card--contain").style.height = "auto";
    document.querySelector("#twchr-modal-selection__btn").classList.remove("disabled");
    
});


function twchr_send_front_to_bk(twchr_object,twchr_callback){
    wp.ajax.send('twchr_ajax_recive',{data:twchr_object}).done(e => twchr_callback(e));
}

twchr_modal_schedule__btn.addEventListener('click',e => {
    e.preventDefault();
    // GUARDA EL INPUT QUE ESTE CHECKED
    let is_recurring = [...twchr_is_recurring].filter(x => x.checked == true);

    
    const body = {
      is_recurring: is_recurring[0].value,
      start_time: twchr_date_to_rfc366(twchr_schedule_card_dateTime.value),
      timezone: "America/New_York",
      title: input_title.value,
      category_id: document.querySelector(
        "input[name='twchr_schedule_card_input--category__value']"
      ).value,
      duration: twchr_schedule_card_duration.value,
    };
    twtchr_schedule_segment_create(
      JSON.stringify(body),
      (e) => {
        const res = JSON.parse(e);
        if(res.error){
            alert(res.error);
            alert(res.message);
        }
        if(res.data){
          const segment = res.data.segments[0];
          let date1Object = new Date(Date.parse(segment.end_time));
          let date2Object = new Date(Date.parse(segment.start_time));

          // Get the difference in milliseconds
          let diff = date1Object - date2Object;

          // Convert the difference to minutes
          let minutes = diff / (1000 * 60);
          const data = {
            twchr_action: "update",
            body: {
              post_id: getParameterByName("post"),
              schedule_id: segment.id,
              is_recurring: segment.is_recurring,
              date_time: segment.start_time,
              streaming_title: segment.title,
              twicth_category: segment.category,
              streaming_duration: minutes,
            },
          };
          twchr_send_front_to_bk(data,e=>console.log(e));
        }
        console.log(res);
      },
      (e) => console.log(e)
    );
    
});

function twchr_date_to_rfc366(dateTimeRaw) {
  const dateTimeStg = Date.parse(dateTimeRaw);
  const rfc3339Date = new Date(dateTimeStg).toISOString();
  return rfc3339Date;
}