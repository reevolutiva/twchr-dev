const twchr_card_header_menu = document.querySelectorAll(".twchr_custom_card_header h3");
const twchr_slide_card_row = document.querySelector(".twchr_custom_card--contain .custom_card_row");
const twchr_slide_card__section_to_api = document.querySelector(".twchr_custom_card--contain .custom_card_row .twchr_car_tab1");
const twchr_slide_card__section_to_api_input = twchr_slide_card__section_to_api.querySelectorAll("input.twchr_schedule_card_input");


if(document.querySelector("#twchr_schedule_card_input--is_recurrig").isChecked = true){
    const newDate_raw = document.querySelector("#twchr_schedule_card_input--dateTime").value;
    const duration = parseInt(document.querySelector("#twchr_schedule_card_input--duration").value);
    const fecha = new Date(newDate_raw);

    console.log(newDate_raw);
    console.log(duration);
    console.log(fecha);
    
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
    console.log(fecha);
    fecha.setMinutes(fecha.getMinutes() + duration);
    console.log(fecha);
    const end_time = `${fecha.getHours()}:${fecha.getMinutes()}`;
    
    const fecha_msg = `${dia} from <b>${start_time}</b> to <b>${end_time}</b>`;

    document.querySelector("#twchr_schedule_card_input--show p").innerHTML = fecha_msg;


}


document.querySelector("body").classList.add("twchr-single-streaming-active");

twchr_card_header_menu[1].addEventListener('click', ()=>{
    twchr_slide_card_row.style.transform = 'translateX(calc(-100% - .5cm))';
});
twchr_card_header_menu[2].addEventListener('click', ()=>{
    twchr_slide_card_row.style.transform = 'translateX(calc(-200% - .5cm))';
});

