const twchr_card_header_menu = document.querySelectorAll(".twchr-tab-card-bar .twchr-tab-card");
const twchr_slide_card_row = document.querySelector(".twchr_custom_card--contain .custom_card_row");
const twchr_slide_card__section_to_api = document.querySelector(".twchr_custom_card--contain .custom_card_row .twchr_car_tab1");
const twchr_slide_card__section_to_api_input = twchr_slide_card__section_to_api.querySelectorAll("input.twchr_schedule_card_input");
const twchr_card_embed_menu = document.querySelectorAll(".twchr_card_embed_menu  input");

twchr_card_embed_menu[0].addEventListener('click',(e)=>{
    document.querySelector(".twchr_car_tab2").style.display = "block";
    document.querySelector(".twchr_car_tab3").style.display = "none";
});

twchr_card_embed_menu[1].addEventListener('click',(e)=>{
    document.querySelector(".twchr_car_tab2").style.display = "none";
    document.querySelector(".twchr_car_tab3").style.display = "block";
});


document.querySelector("body").classList.add("twchr-single-streaming-active");

twchr_card_header_menu[1].addEventListener('click', ()=>{
    twchr_slide_card_row.style.transform = 'translateX(calc(-100% - .5cm))';
    document.querySelector(".twchr_car_tab2").style.display = 'block';
    document.querySelector(".twchr_custom_card--contain").style.height = "auto";
    document.querySelector("#twchr-modal-selection__btn").classList.remove("disabled");
});
