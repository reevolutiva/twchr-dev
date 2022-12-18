const stream_isset = document.querySelectorAll('.twchr_car_tab2 .previw_card__status ul li span.value');
const twchr_tagchecklist = document.querySelector("#tagsdiv-cat_twcht .tagchecklist");

const twchr_card_header_menu = document.querySelectorAll(".twchr_custom_card_header h3");
const twchr_slide_card_row = document.querySelector(".twchr_custom_card--contain .custom_card_row");

const stream_isset_array = [];
for (let i = 0; i < stream_isset.length; i++) {
    const element = stream_isset[i];
    //console.log(element.value);
    if (element.textContent === undefined) {
        stream_isset_array.push(true);
    } else {
        stream_isset_array.push(false)
    }
}
if (stream_isset_array.every(item => item === true)) {
    document.querySelector('.twchr_car_tab2 .previw_card').style.display = 'none';

    twchr_card_header_menu[0].addEventListener('click', ()=>{
        twchr_slide_card_row.style.transform = 'translateX(0%)';
    });
    
}else{
    twchr_card_header_menu[0].classList.remove("active");
    twchr_card_header_menu[0].classList.add("diactive");
    twchr_slide_card_row.style.transform = 'translateX(calc(-100% - .5cm))';
}
const metabox_cat_tw = document.querySelector("#tagsdiv-cat_twcht");
const metabox_cat_calendar = document.querySelector("#tagsdiv-serie");
const metaboxCategoryTwitch = document.querySelector("#tagsdiv-cat_twcht #cat_twcht");

document.querySelector("body").classList.add("twchr-single-streaming-active");

twchr_card_header_menu[1].addEventListener('click', ()=>{
    twchr_slide_card_row.style.transform = 'translateX(calc(-100% - .5cm))';
});

