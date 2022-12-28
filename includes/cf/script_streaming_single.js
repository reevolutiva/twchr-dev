const twchr_card_header_menu = document.querySelectorAll(".twchr-tab-card-bar .twchr-tab-card");
const twchr_slide_card_row = document.querySelector(".twchr_custom_card--contain .custom_card_row");
const twchr_slide_card__section_to_api = document.querySelector(".twchr_custom_card--contain .custom_card_row .twchr_car_tab1");
const twchr_slide_card__section_to_api_input = twchr_slide_card__section_to_api.querySelectorAll("input.twchr_schedule_card_input");
const twchr_card_embed_menu = document.querySelectorAll(".twchr_card_embed_menu  input");
const twchr_modal_schedule__btn = document.querySelector("#twchr-modal-schedule__btn");


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