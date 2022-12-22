const twchr_card_header_menu = document.querySelectorAll(".twchr_custom_card_header h3");
const twchr_slide_card_row = document.querySelector(".twchr_custom_card--contain .custom_card_row");
const twchr_slide_card__section_to_api = document.querySelector(".twchr_custom_card--contain .custom_card_row .twchr_car_tab1");
const twchr_slide_card__section_to_api_input = twchr_slide_card__section_to_api.querySelectorAll("input.twchr_schedule_card_input");





document.querySelector("body").classList.add("twchr-single-streaming-active");

twchr_card_header_menu[1].addEventListener('click', ()=>{
    twchr_slide_card_row.style.transform = 'translateX(calc(-100% - .5cm))';
    document.querySelector(".twchr_car_tab2").style.display = 'block';
    document.querySelector(".twchr_custom_card--contain").style.height = "auto";
});
twchr_card_header_menu[2].addEventListener('click', ()=>{
    twchr_slide_card_row.style.transform = 'translateX(calc(-200% - .5cm))';
    document.querySelector(".twchr_custom_card--contain").style.height = "auto";
});

