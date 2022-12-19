const twchr_card_header_menu = document.querySelectorAll(".twchr_custom_card_header h3");
const twchr_slide_card_row = document.querySelector(".twchr_custom_card--contain .custom_card_row");

document.querySelector("body").classList.add("twchr-single-streaming-active");

twchr_card_header_menu[1].addEventListener('click', ()=>{
    twchr_slide_card_row.style.transform = 'translateX(calc(-100% - .5cm))';
});
twchr_card_header_menu[2].addEventListener('click', ()=>{
    twchr_slide_card_row.style.transform = 'translateX(calc(-200% - .5cm))';
});

