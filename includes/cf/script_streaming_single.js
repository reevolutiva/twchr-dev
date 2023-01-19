const twchr_card_header_menu = document.querySelectorAll(".twchr-tab-card-bar .twchr-tab-card");
const twchr_slide_card_row = document.querySelector(".twchr_custom_card--contain .custom_card_row");
const twchr_slide_card__section_to_api = document.querySelector(".twchr_custom_card--contain .custom_card_row .twchr_car_tab1");
const twchr_slide_card__section_to_api_input = twchr_slide_card__section_to_api.querySelectorAll("input.twchr_schedule_card_input");
const twchr_card_embed_menu = document.querySelectorAll(".twchr_card_embed_menu  input");
const twchr_modal_schedule__btn = document.querySelector("#twchr-modal-schedule__btn");
const twchr_term_serie_list = GSCJS.queryOnly("#twchr_term_serie_list");


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




function twchr_schedule_chapter_asign() {

  const twchr_dateTime_slot = document.querySelector("#twchr_dateTime_slot");

  const twchr_ajax_input_serie = document.querySelector("#twchr_schedule_card_input--serie");
  const twchr_ajax_label_serie = document.querySelector("label#twchr_schedule_card_input--serie__name--label");


  /** 
    * Vuelvo a pedir una lista de schdules segment antes de seleccionar un chapter
  **/

  
  getSchedules_by_id(e=>{
    let segments;
    if(e == undefined){
      alert('Series not foud in Twitch');  
      GSCJS.queryOnly(".twchr_car_tab1 .twchr__schedule__loading").style.display = "none";
      return
    }else{
      segments = e.segments;
    }
    
    if(segments != false && e != 'undefined'){
      wp.ajax.send('twchr_taxonomy_update',{
        data:{
          nonce: twchr_taxonomy_update,
          segment: segments
        },
        success: res =>{
          console.log(res);
          GSCJS.queryOnly(".twchr_car_tab1 .twchr__schedule__loading").style.display = "none";
        },
        error: err =>{
          //console.log(err);
          GSCJS.queryOnly(".twchr_car_tab1 .twchr__schedule__loading").style.display = "none";
          //alert("message: "+res.message);
				  alert("You will be redirected to the authentication page in a few seconds.");
				  location.href = twchr_admin_url+'edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true';
        }
      }
      ).done(succs => {
        //console.log(succs);
        if(succs.terms){
          const terms = succs.terms;

          terms.forEach(item => {
            let selected = '';
            //console.log(item);
            if(GSCJS.queryOnly("#twchr_term_serie_list").textContent.toLowerCase() == item.slug.toLowerCase()){
              selected = 'selected="true"';
            }
            const option = `<option value="${item.term_id}|${item.name}" ${selected} >${item.name + " - " + item.term_id}</option>`;
            twchr_ajax_input_serie.innerHTML = twchr_ajax_input_serie.innerHTML + option;
          });

          if(twchr_ajax_input_serie.value == 'undefined'){
            GSCJS.queryAll(".silde-1 .twchr-card-row").forEach(item => {
              if(item.classList.contains("serie") ||
                item.classList.contains("serie-name") ||
                item.classList.contains("is_recurring")){
                }else{
                  item.style.display = "none";
              }
            });
          }else{
            GSCJS.queryAll(".silde-1 .twchr-card-row").forEach(item => {
              if(item.classList.contains("serie") ||
                item.classList.contains("serie-name") ||
                item.classList.contains("is_recurring")){
                }else{
                  item.style.display = "";
              }
            });
          }
    
          if(twchr_is_recurring[0].checked == true){
            GSCJS.queryAll(".silde-1 .twchr-card-row").forEach(item => {
                // SI este item tiene un input
                if(item.querySelector("input") != null){
                    const input = item.querySelector("input");
                    if(input.value.length > 0){
                        item.style.display = '';
                    }
                }
                if(item.querySelector("twchr_cards_input_badges badges")){
                    const badge = querySelector("twchr_cards_input_badges badges");
                    if(badge.textContent.length > 0){
                        item.style.display = '';
                    }
                }
            });
          }

          twchr_ajax_input_serie.addEventListener('change', (event) => {

            const term = {
              term_id: event.target.value.split('|')[0],
              name: event.target.value.split('|')[1],
            };
    
    
            terms.forEach(item => {
              if (item.term_id == term.term_id) {
                if(item.chapters && item.chapters != 'this serie not contains chapters'){
                  
                  const chapters = item.chapters;
                  
                  twchr_dateTime_slot.innerHTML = "";              
                        
                  chapters.forEach(chapter => {
                    const opt = `<option value="${chapter.id};${chapter.title}|${chapter.start_time};${chapter.end_time}">${chapter.title} ${chapter.start_time} - ${chapter.end_time}</option>`;
                    twchr_dateTime_slot.innerHTML = twchr_dateTime_slot.innerHTML + opt;
                  });
    
                }else{
                  twchr_dateTime_slot.innerHTML = "";
                  const opt = `<option value="false">this serie not contains chapter</option>`;
                  twchr_dateTime_slot.innerHTML = twchr_dateTime_slot.innerHTML + opt;
                }
              }
            });
    
            twchr_dateTime_slot.addEventListener('change',e =>{
              const id = e.target.value.split(";")[0];
              console.log(id);
              
              terms.forEach(element =>{
                if(element.chapters != 'this serie not contains chapters'){
                  const capitulo = element.chapters[0] != undefined ? element.chapters[0] : false;
                  console.log(capitulo);
                  if(capitulo.id == id && capitulo != false){
                    const cat = capitulo.category;
                    GSCJS.queryOnly("#twchr_schedule_card_input--category__name").value = cat.name;
                    GSCJS.queryOnly("#twchr_schedule_card_input--category__value").value = cat.id;
                    //console.log(cat);
                  } 
                }
              });
            });
    
          },{ passive: true});
    
          twchr_ajax_input_serie.addEventListener('change', e =>{
            // Si el value del select twchr_ajax_input_serie es undefined. 
            if(twchr_ajax_input_serie.value == 'undefined'){
              GSCJS.queryOnly("#twchr_schedule_card_input--title").parentElement.style.display = "grid";
              GSCJS.queryOnly("#twchr_schedule_card_input--title").value = '';
              GSCJS.queryAll(".silde-1 .twchr-card-row").forEach(item => {
                if(item.classList.contains("serie") ||
                  item.classList.contains("serie-name") ||
                  item.classList.contains("is_recurring")){
                  }else{
                    item.style.display = "none";
                }
              });
              // Si no es undefined.
            }else{
              GSCJS.queryAll(".silde-1 .twchr-card-row").forEach(item => {
                if(item.classList.contains("serie") ||
                  item.classList.contains("serie-name") ||
                  item.classList.contains("is_recurring")){
                  }else{
                    item.style.display = "";
                  }
    
                  GSCJS.queryOnly("#twchr_schedule_card_input--title").value = twchr_ajax_input_serie.value.split("|")[1];
                  GSCJS.queryOnly("#twchr_schedule_card_input--title").parentElement.style.display = "none";
                  const curr_url = GSCJS.queryOnly("#twchr_card_button_create_new_serie a").getAttribute("href");
                  GSCJS.queryOnly("#twchr_card_button_create_new_serie a").setAttribute("href",`${curr_url}&from_cpt_name=${twchr_ajax_input_serie.value.split("|")[1]}`);
          
              });
            }
          },{ passive: true});
        }
      });
    }
      
  });


  
}


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
twchr_card_header_menu[0].addEventListener('click',()=>{
  twchr_card_state = 'schedule';
    twchr_card_header_menu[1].classList.add("disabled");
    twchr_card_header_menu[0].classList.remove("disabled");
    twchr_slide_card_row.style.transform = 'translateX(0)';
    document.querySelector(".twchr_car_tab2").style.display = 'none';
    document.querySelector("#twchr-modal-selection__btn").classList.add("disabled");
});

twchr_card_header_menu[1].addEventListener('click', ()=>{
    twchr_card_state = 'embed';
    twchr_card_header_menu[1].classList.remove("disabled");
    twchr_card_header_menu[0].classList.add("disabled");
    twchr_slide_card_row.style.transform = 'translateX(calc(-100% - .5cm))';
    document.querySelector(".twchr_car_tab2").style.display = 'block';
    document.querySelector(".twchr_custom_card--contain").style.height = "auto";
    document.querySelector("#twchr-modal-selection__btn").classList.remove("disabled");
    
});

/**
 * Enviar infromacion del front al back usando
 * el action twchr_ajax_recive.
 * @param {*} twchr_object
 * @param {*} twchr_callback
 */
function twchr_send_front_to_bk(twchr_object,twchr_callback){
    //console.log(twchr_object);
    wp.ajax.send('twchr_ajax_recive',{data:twchr_object}).done(e => twchr_callback(e));
}

twchr_modal_schedule__btn.addEventListener('click',e => {
    e.preventDefault();
    // GUARDA EL INPUT QUE ESTE CHECKED
    let is_recurring = [...twchr_is_recurring].filter(x => x.checked == true);
    
    if (document.querySelector("#titlewrap #title").value.length == 0 ){
      alert("The streaming title is not yet established");
      return;
    }
      if (is_recurring[0].value == "true") {
        // Si is_recurring vale "YES".
        let twchr_chapter_slot;
        if (
          document.querySelector("#twchr_dateTime_slot").value.includes("|")
        ) {
          twchr_chapter_slot = {
            chapter_id: document
              .querySelector("#twchr_dateTime_slot")
              .value.split("|")[0]
              .split(";")[0],
            start_time: document
              .querySelector("#twchr_dateTime_slot")
              .value.split("|")[1]
              .split(";")[0],
            end_time: document
              .querySelector("#twchr_dateTime_slot")
              .value.split("|")[1]
              .split(";")[1],
            chapter_name: document
              .querySelector("#twchr_dateTime_slot")
              .value.split("|")[0]
              .split(";")[1],
          };
        } else {
          twchr_chapter_slot = document.querySelector(
            "#twchr_dateTime_slot"
          ).value;
        }
        const data = {
          twchr_action: "asing",
          twchr_target: "slide-1",
          nonce: twchr_post_nonce,
          body: {
            post_id: twchr_post_id,
            post_title: document.querySelector("#titlewrap #title").value,
            stream: {
              duration: document.querySelector(
                "#twchr_schedule_card_input--duration"
              ).value,
              title: document.querySelector("#twchr_schedule_card_input--title")
                .value,
            },
            serie: {
              term_id: document
                .querySelector("#twchr_schedule_card_input--serie")
                .value.split("|")[0],
              name: document
                .querySelector("#twchr_schedule_card_input--serie")
                .value.split("|")[1],
            },
            twitch_category: {
              id: document.querySelector(
                "#twchr_schedule_card_input--category__value"
              ).value,
              name: document.querySelector(
                "#twchr_schedule_card_input--category__name"
              ).value,
            },
            twchr_slot: twchr_chapter_slot,
          },
        };

        twchr_send_front_to_bk(data, (e) => {
          
          if(e.status == 200){
            console.log(e);
            // Bloqueo todos los inputs.
            GSCJS.queryAll(".twchr_custom_card--contain .custom_card_row input").forEach(
              i => {
                i.setAttribute("disabled", true);
              }
            );

            // Bloqueo todos los select.
            GSCJS.queryAll(".twchr_custom_card--contain .custom_card_row select").forEach(
              i => {
                i.setAttribute("disabled", true);
              }
            );
            setTimeout(() => {
              location.reload();
            }, 1000);
          }else{
            console.log(e);
          }
        });

        // SI is_recurring vale "NO".
      } else {
        const body = {
          is_recurring: false,
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
            if (res.error) {
              alert(res.error);
              alert(res.message);
            }
            if (res.data != false) {
              const segment = res.data.segments[0];
              let date1Object = new Date(Date.parse(segment.end_time));
              let date2Object = new Date(Date.parse(segment.start_time));

              // Get the difference in milliseconds
              let diff = date1Object - date2Object;

              // Convert the difference to minutes
              let minutes = diff / (1000 * 60);
              const data = {
                twchr_action: "update",
                twchr_target: "slide-1",
                nonce: twchr_post_nonce,
                body: {
                  post_id: twchr_post_id,
                  schedule_id: segment.id,
                  is_recurring: segment.is_recurring,
                  date_time: segment.start_time,
                  streaming_title: segment.title,
                  twicth_category: segment.category,
                  streaming_duration: minutes,
                },
              };
              twchr_send_front_to_bk(data, (e) => {
                //console.log(e);
                if(e.status == 200){
                  //console.log(e);
                  location.href = twchr_admin_url+"post.php?post="+e.post_id+"&action=edit"
                }                
              });
            }
          },
          (e) => console.log(e)
        );
      }
    
    
},);

function twchr_date_to_rfc366(dateTimeRaw) {
  const dateTimeStg = Date.parse(dateTimeRaw);
  const rfc3339Date = new Date(dateTimeStg).toISOString();
  return rfc3339Date;
}
GSCJS.queryOnly(".twchr-card-row.serie");