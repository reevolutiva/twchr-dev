

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function twchr_get_duration_form_RFC3666(end_time, start_time) {
    let date1Object = new Date(Date.parse(end_time));
    let date2Object = new Date(Date.parse(start_time));

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



// Funcion que obtiene los parametros GET con JS
/**
 * It fetches a URL and returns the response in the format you specify
 * @param url - The url to fetch from.
 * @param callback - The function to be called when the request is complete.
 * @param mode - json, blob, or text
 * @param [requestOptions=false] - This is an object that contains the request options.
 */
 async function twchrFetchGet (url, callback, mode, requestOptions=false){
    let get;
    if(requestOptions != false){
        get = await fetch(url,requestOptions);
    }else{
        get = await fetch(url);
    }
    
    let response;
    switch (mode) {
      case "json":
        response = await get.json();
        break;
      case "blob":
        response = await get.blob();
        break;
      default:
        response = await get.text();
        break;
    }
    callback(response);
  }




const crearElemento = (element,className) =>{
    const tag = document.createElement(element);
    if(className.length > 0){
        tag.classList.add(className);
    }
    
    return tag;
}

function create_strg_secret(str_lg){
    let txt ='';
    for (let index = 0; index < str_lg; index++) {
        if(index > 4){
            txt = txt+"•";
        }
    }
    return txt;
}


const twchrFrontEndCounter = (nodeTarget,time) =>{
    const finalNumber = parseInt(nodeTarget.getAttribute('data-twchr-final-number'));
    let currentNumber = 0;
    let count = 0;
    let var_time = time;
    if(finalNumber | finalNumber > 1){
        const counter = setInterval(() => {
            
                var_time = 35000;
                    
                    const arrayNumber = [
                        Math.trunc(finalNumber/finalNumber),
                        Math.trunc(finalNumber/20),
                        Math.trunc(finalNumber/18),
                        Math.trunc(finalNumber/16),
                        Math.trunc(finalNumber/14),
                        Math.trunc(finalNumber/12),
                        Math.trunc(finalNumber/10),
                        Math.trunc(finalNumber/8),
                        Math.trunc(finalNumber/6),
                        Math.trunc(finalNumber/4),
                        Math.trunc(finalNumber/2),
                        Math.trunc(finalNumber/1)
                    ];

                    currentNumber = arrayNumber[count];
                    count++;
                
            
            
            nodeTarget.textContent = currentNumber;
            if(currentNumber === finalNumber) clearInterval(counter);
        }, var_time);
    }
  }
  

const getCategorysTwitch = async (appToken, client_id, query, callback=false)=>{
        const myHeaders = new Headers();
        myHeaders.append("Authorization", `Bearer ${appToken}`);
        myHeaders.append("Client-Id", client_id);

        const requestOptions = {
        method: 'GET',
        headers: myHeaders,
        redirect: 'follow'
        };

        let response;
        if(!query==''){
            const get = await fetch("https://api.twitch.tv/helix/search/categories?query="+query, requestOptions);
            response = await get.json();
            const responseFromApi = response.data;
           
                const select = document.querySelector(".twchr_modal");
    
                let innerSelect= '';
                responseFromApi.forEach(item => {
                    const id = item.id;
                    const name = item.name;
                    const optionForm = `<section><label for='twchr_toApi_category_ajax-${name}'>${name}</label><input type='radio' id='twchr_toApi_category_ajax-${name}' class='twchr_toApi_category_ajax_radio' name=twchr_toApi_category_ajax' value='${id}'></section>`;
                    innerSelect += optionForm;
                });
                select.innerHTML = innerSelect;
            
            if(callback != false){
                callback(response);
            }

            
        }else{
            response = new Error('Parametro query vacio');
            console.log(response);
        }
        
     
}

const getSchedules_by_id = async (callback) => {
        const myHeaders = new Headers();
        myHeaders.append("Authorization", `Bearer ${tchr_vars_admin.twchr_app_token}`);
        myHeaders.append("client-id", tchr_vars_admin.twchr_keys['client-id']);

        const broadcaster_id = tchr_vars_admin.twitcher_data_broadcaster.id;
        const requestOptions = {
        method: 'GET',
        headers: myHeaders,
        redirect: 'follow'
        };

       let url = `https://api.twitch.tv/helix/schedule?broadcaster_id=${broadcaster_id}`;
    
       const get = await fetch(url , requestOptions);
       const response = await get.json();
       const arrayList = response.data;

       callback(arrayList)


}

// Hago la peticion de videos a Twitch
const tchr_get_clips = async (appToken, client_id, user_id,callback_ajax=false)   =>{
    const myHeaders = new Headers();
    myHeaders.append("Authorization", `Bearer ${appToken}`);
    myHeaders.append("client-id", client_id);

        const requestOptions = {
        method: 'GET',
        headers: myHeaders,
        redirect: 'follow'
        };

       const get = await fetch(`https://api.twitch.tv/helix/videos?user_id=${user_id}`, requestOptions);
       const response = await get.json();
       const arrayList = response.data;
       let Content = ""; // Inizializo Content
       
       // Si el parametro callback_ajax no esta definido
       // Significa que estamos en edit del post y sigue el curso
       // del algoritmo
      if(callback_ajax === false){
        // Creo tantos input-radio como videos tenga la api
        arrayList.forEach((item, index) => {
            const id = item.id;
            const title = item.title;
            const date_raw = item.created_at; // Fecha en RFC
            let date = new Date(date_raw);
            
            date = `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}`; // fecha en formato dd/mm/yyyy
            Content += `<section class='twchr_modal_video_ajax'>
                            <label data-twchrDataPosition='${index}' for='twchr_videos_ajax-${title}'><span>${title}</span><span>${date}</span><span></span></label>
                            <input type='radio' data-position='${index}' id='twchr_videos_ajax-${title}' class='twchr_videos_ajax' name=twchr_videos_ajax' value='${id}'>
                        </section>`;
    
        });
    
    
           // Introdusco la lista de check-boxs al modal .twchr_modal_get_videos
           GSCJS.queryOnly("#twchr_button_get_videos__content .content").innerHTML = Content;
    
           // Guardo los inputs radio creados más arriba
           const input = GSCJS.queryAll(".twchr_modal_get_videos section input");
           const postBox = GSCJS.queryAll("#twittcher-stream .inside input");

           // Guardo boton asign 
           const asign_btn = GSCJS.queryOnly(".twchr_modal_get_videos #twchr-modal-selection__btn");

           asign_btn.addEventListener('click',(event)=>{
            //console.log(event.target);
            event.preventDefault();
            let pos;
            input.forEach(item => {
                item.checked ? pos = item.getAttribute('data-position') : 'not found';
            });
            if(pos != 'not found'){
                const data = arrayList[pos]; // tomo el video de la api con el mismo index guardado en pos
                let titulo = data.title;
                twchrFetchGet(tchr_vars_admin.wp_api_route+'twchr/v1/twchr_get_streaming',(i)=>{
                    // Pregunto si este video ya existe
                    i.some(it => it.title == titulo) ? titulo = titulo+" (Duplicate)" : titulo = titulo; 
                    GSCJS.queryOnly("#titlewrap label").classList.add('screen-reader-text');
                    GSCJS.queryOnly("#titlewrap input").value = titulo; // Escribo el titulo del post                    
                },'json'); 

                let stream_data_from_twitch = '';
                for (element in data){
                    stream_data_from_twitch =  stream_data_from_twitch + "&"+element+"="+data[element];
                }

                location.href = location.href+"&twchr_twitch_embed__host="+data.user_name+"&twchr_twitch_embed__video="+data.id+stream_data_from_twitch;
            }
           });
      }else{ // Sí se definio un callback ejecuta el callback
        callback_ajax(arrayList); // Pasa al Callback el arrayList con los videos
      }
       
}


if((getParameterByName('post_type') == 'twchr_streams' && location.pathname.includes('post-new.php')) ||
(getParameterByName('action') == 'edit' && location.pathname.includes('post.php')) ){
    const element = GSCJS.queryOnly("#twittcher-stream .inside input");
    const twchr_meta_box_serie = document.querySelector("#tagsdiv-serie");
    const twchr_meta_box_cat_tw = document.querySelector("#tagsdiv-cat_twcht");
    if(element.value.length < 1){
        //twittcher_stream.style.display = 'none';
    }

    const postBox = GSCJS.queryAll("#twittcher-stream .inside input");
    // Lleno Twchr card
    
    //GSCJS.queryOnly("#twchr_stream_data input[name='twchr_stream_data_dateTime']").value === '' ? null : GSCJS.queryOnly("#twchr_stream_data input[name='twchr_stream_data_dateTime']").setAttribute("disabled",true);
    GSCJS.queryOnly(".previw_card .twchr_card_header--title h3").textContent = postBox[16].value === '' ? 'undefined' : postBox[16].value;
    GSCJS.queryOnly(".previw_card .twchr_card_header-description h4").textContent = postBox[1].value === '' ? 'undefined' : postBox[1].value;
    
    GSCJS.queryOnly(".previw_card .twchr_card_body--list li:nth-of-type(1) span.value").textContent = postBox[0].value === '' ? 'undefined' : postBox[0].value;
    GSCJS.queryOnly(".previw_card .twchr_card_body--list li:nth-of-type(2) span.value").textContent = postBox[2].value === '' ? 'undefined' : postBox[2].value;
    GSCJS.queryOnly(".previw_card .twchr_card_body--list li:nth-of-type(3) span.value").textContent = postBox[4].value === '' ? 'undefined' : postBox[4].value;
    GSCJS.queryOnly(".previw_card .twchr_card_body--list li:nth-of-type(4) span.value").textContent = postBox[9].value === '' ? 'undefined' : postBox[9].value;
    GSCJS.queryOnly(".previw_card .twchr_card_body--list li:nth-of-type(5) span.value").textContent = postBox[15].value === '' ? 'undefined' : postBox[15].value;
    GSCJS.queryOnly(".previw_card .twchr_card_body--list li:nth-of-type(6) span.value").textContent = postBox[10].value === '' ? 'undefined' : postBox[10].value;
    let card_img = postBox[8].value;
    card_img = card_img.replace("%{width}x%{height}","250x150");
    GSCJS.queryOnly(".previw_card .twchr_card_header--img img").setAttribute('src',card_img);
  
    GSCJS.queryOnly(".previw_card .twchr_card_body--status .item h3").textContent = postBox[14].value === '' ? 'undefined' : postBox[14].value;
   
    twchrFetchGet(
        'https://api.twitch.tv/helix/videos?id='+postBox[3].value,
        (element)=>{
            
            if(element.data){
                GSCJS.queryOnly(".previw_card .twchr_card_body--status .item.status h3").classList.add('on');
                GSCJS.queryOnly(".previw_card .twchr_card_body--status .item.status h3").textContent = 'Online';
            }else{
                GSCJS.queryOnly(".previw_card .twchr_card_body--status .item.status h3").classList.add('failed');
                GSCJS.queryOnly(".previw_card .twchr_card_body--status .item.status h3").textContent = 'Offline';    
            }

            const stream_isset = document.querySelectorAll('.previw_card .previw_card__status ul li span.value');         

            const stream_isset_array = [];
            for (let i = 0; i < stream_isset.length; i++) {
                const element = stream_isset[i];
                if (element.textContent === 'undefined') {
                    stream_isset_array.push(true);
                } else {
                    stream_isset_array.push(false);
                }
            }

            // Sí todos los campos de previw_card son undefined es porque no se ha asignado
            if (stream_isset_array.every(item => item === true)) {
                document.querySelector('.previw_card').parentElement.style.display = 'none';
                twchr_card_header_menu[0].addEventListener('click', ()=>{
                    twchr_slide_card_row.style.transform = 'translateX(0%)';
                });

                if(![...twchr_meta_box_serie.classList].find(item => item == 'hide-if-js')) twchr_meta_box_serie.classList.add("hide-if-js"); 
                if(![...twchr_meta_box_cat_tw.classList].find(item => item == 'hide-if-js')) twchr_meta_box_cat_tw.classList.add("hide-if-js");

                
                // Si no todos los campos son undefined es porque fue asignado
            }else{
                twchr_card_header_menu[0].classList.remove("active");
                twchr_card_header_menu[0].classList.add("diactive");
                twchr_slide_card_row.style.transform = 'translateX(calc(-100% - .5cm))';
                document.querySelector(".twchr_custom_card--contain").style.height = "3cm";
  
                if([...twchr_meta_box_serie.classList].find(item => item == 'hide-if-js')) twchr_meta_box_serie.classList.remove("hide-if-js"); 
                if([...twchr_meta_box_cat_tw.classList].find(item => item == 'hide-if-js')) twchr_meta_box_cat_tw.classList.remove("hide-if-js");
            }
        },
        'json',{headers: {
            "Authorization": `Bearer ${tchr_vars_admin.twchr_app_token}`,
            "client-id": tchr_vars_admin.twchr_keys['client-id']
    }});

    if(document.querySelector("#twchr_schedule_card_input--is_recurrig").checked == true){


        twchr_schedule_card_dateTime.parentElement.style.display = 'none'
        document.querySelector("#twchr_dateTime_slot").style.display = 'block';
        const twchr_dateTime_slot = document.querySelector("#twchr_dateTime_slot");

        const twchr_ajax_input_serie = document.querySelector("#twchr_schedule_card_input--serie__name");
          
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
                                    twchr_dateTime_slot.innerHTML = twchr_dateTime_slot.innerHTML + opt;
                                });
                            }
                        });

                        document.querySelector("#twchr_schedule_card_input--serie__id").value = term_id;
                        
                });

                
                
                
                
                
                
                
            },
            'json');

        
    
    }else{
        const twchr_schedule_card = document.querySelector(".twchr_custom_card--contain");
        const input_serie = twchr_schedule_card.querySelector("#twchr_schedule_card_input--serie__name");
        const input_serie_label = twchr_schedule_card.querySelector("label#twchr_schedule_card_input--serie__name--label");
        const show_date = twchr_schedule_card.querySelector("#twchr_schedule_card_input--show");

    
        input_serie.parentElement.style.display = 'none';
        input_serie_label.style.display = 'none';
        show_date.style.display = 'none';
        input_title.removeAttribute('disabled');
        document.querySelector("#twchr_dateTime_slot").style.display = 'none';
    }

    


    
}

//post_type=twchr_streams&page=twchr-dashboard
if(getParameterByName('post_type')=='twchr_streams' && getParameterByName('page')=='twchr-dashboard'){
    const table_tchr_setting__txt = GSCJS.queryAll(".keys-twchr p.twchr-key-value");
    for (let i = 0; i < table_tchr_setting__txt.length; i++) {
        const element = table_tchr_setting__txt[i];
        // Si el indice es diferente a 0 y 3
        if(i === 3 ){
            // guarda el contenido de table_tchr_setting__txt
            const strg = element.textContent.trim();
            const strg_start = strg.slice(0,6);
            if(strg != 'key sin registrar'){
                element.textContent = strg_start+create_strg_secret(10);
            }
            

        }
        
    }
    const twchr_result_items = GSCJS.queryAll("td.twchr-results-item");
    
    twchrFrontEndCounter(twchr_result_items[0],80);
    twchrFrontEndCounter(twchr_result_items[1],80);
    twchrFrontEndCounter(twchr_result_items[2],80);
    twchrFrontEndCounter(twchr_result_items[3],80);
    twchrFrontEndCounter(twchr_result_items[4],80);

}

//taxonomy=serie&post_type=twchr_streams
if(getParameterByName('taxonomy') ==='serie' && getParameterByName('post_type') == 'twchr_streams' && location.pathname.split("/")[2] == 'term.php')
   {
    const ajaxResponse = document.querySelector("#ajax-response");
    const getResponse = async (url) =>{
        try {
           const response = await fetch(url);
           let res = await response.text();
           res = JSON.parse(res);
           
           const allData = GSCJS.queryOnly("input#twchr_fromApi_allData");
           let current_stream_id;
               if(allData.value != ""){
                    const object = JSON.parse(allData.value);
                    current_stream_id = object.allData.segments[0].id; 
            }
           res.forEach((element, index) => {
            const dataFromApi = JSON.parse(element.dataFromTwitch);
            const alert = crearElemento("DIV","alert-twchr-back");
            const state = dataFromApi.status;
            const segment_id = dataFromApi.allData.segments[0].id;

            
            
            if(segment_id === current_stream_id){
                let existTwitch = false;
                switch (state) {
                    case 200:

                        let requestOptionsgetSchedule = {
                            method: 'GET',
                            headers: {
                                    "Authorization" : `Bearer ${tchr_vars_admin.twchr_keys.user_token}`,
                                    "client-id" : tchr_vars_admin.twchr_keys['client-id']
                                },
                            redirect: 'follow'
                            };
                        twchrFetchGet(`https://api.twitch.tv/helix/schedule?broadcaster_id=${tchr_vars_admin.twitcher_data_broadcaster.id}`,getScheduleCallback,'json',requestOptionsgetSchedule);
                        function getScheduleCallback(res){
                           
                            if(res.status === 401){
                                const url_redirect  = `${GSCJS.getURLorigin()}/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true`;
                                alert('Invalid User Token, You will be redirected to another page to get a new User Token');
                                location.href = url_redirect
                            }
                    
                            if(res.status != 404){
                                const schedule_segment = res.data.segments;
                                
                                schedule_segment.forEach(segment =>{
                                    if(segment.id === segment_id){
                                        //console.log('existe');
                                        //console.log(segment.title);
                                        alert.classList.remove("warning");
                                        alert.innerHTML = `<h3>Success</h3><p>${dataFromApi.message}</p><p>serie: <b>${element.name}</b></p><input type="checkbox" name="twchr_schedule_exist" checked>`;
                                    }else{
                                        alert.classList.add("warning");
                                        alert.innerHTML = `<h3>Ups!</h3><p><b>${element.name}</b> was created in wordpress, but not exist in twitch</p><p>serie: <b>${element.name}</b></p><input type="checkbox" name="twchr_schedule_exist" >`;
                                        ajaxResponse.appendChild(alert)
                                    }
                                });
                                
                            }else{
                                console.log(res);
                            }
                            
                            
                            
                        }
                        
                        
                        
                        break;

                    case 401:
                        alert.classList.add("warning");
                        alert.innerHTML = `<h4>${dataFromApi['message']}</h4>`;
                        ajaxResponse.appendChild(alert);
                        const url = dataFromApi.url_redirect+"&tchr_id="+dataFromApi['post-id'];
                        
                        setTimeout(() => {
                            //location.href = url;
                        }, 2000);

                        break;
                    case 400:
                        alert.classList.add("warning");
                        alert.innerHTML = `<h3>${dataFromApi.error}</h3><p>${dataFromApi.message}</p><p>serie: <b>${dataFromApi.title}</b></p>`;
                        ajaxResponse.appendChild(alert);
                        break;
                    
                    
                
                    default:
                        break;
                }
            }
                
           });

          
           //console.log(dataFromApi);
        } catch (error) {
            console.log(error);
        }

    }

    const url = tchr_vars_admin.wp_api_route+'twchr/v1/twchr_get_serie';
    getResponse(url);

    const allData = GSCJS.queryOnly("input#twchr_fromApi_allData");
    let current_stream_id;
        if(allData.value != ""){
            const object = JSON.parse(allData.value);
            const state  = object.status;
            const alert = crearElemento("DIV","alert-twchr-back");
            switch (state) {
               case 401:
                    alert.classList.add("warning");
                    alert.innerHTML = `<h4>${object['message']}</h4>`;
                    ajaxResponse.appendChild(alert);
                    const url = dataFromApi.url_redirect+"&tchr_id="+object['post-id'];
                    
                    setTimeout(() => {
                        //location.href = url;
                    }, 2000);

                    break;
                case 400:
                    alert.classList.add("warning");
                    alert.innerHTML = `<h3>${object.error}</h3><p>${object.message}</p><p>serie: <b>${object.title}</b></p>`;
                    ajaxResponse.appendChild(alert);
                    break;
                
                
            
                default:
                    break;
            }
        }


    

    const inputTxtCategory = document.querySelector("#twchr_toApi_category_ajax");
    const span = crearElemento("SPAN","btn");
    span.classList.add("twchr-category-button-select");
    span.classList.add("twchr-btn-general");
    const twchr_modal = crearElemento("MODAL","twchr_modal");
    const padreInput = inputTxtCategory.parentElement;
    span.textContent = 'select';
    padreInput.classList.add("twchr_toApi_category_ajax--container");
    padreInput.appendChild(span);
    padreInput.appendChild(twchr_modal);

    span.addEventListener('click',()=>{
        const radios = document.querySelectorAll(".twchr_toApi_category_ajax_radio");
        if(radios.length > 1){
            radios.forEach(radio =>{
                if(radio.checked === true){
                    const optionName = radio.parentElement.children[0].textContent;
                    document.querySelector("#twchr_toApi_category_value").value = radio.value;
                    document.querySelector("#twchr_toApi_category_name").value = optionName;
                    inputTxtCategory.value = optionName;
                    
                }
            });
        }

        


        twchr_modal.classList.remove('active');
    });
    inputTxtCategory.oninput = ()=>{
        const query = inputTxtCategory.value;
        const appToken = tchr_vars_admin.twchr_app_token;
        const twch_data_prime = tchr_vars_admin.twchr_keys;
        twchr_modal.classList.add('active');
        getCategorysTwitch(appToken, twch_data_prime['client-id'], query);
    }  
     
    const twchr_toApi_dateTime = document.querySelector("#twchr_toApi_dateTime");
    const twchr_toApi_duration = document.querySelector("#twchr_toApi_duration");
    if(twchr_toApi_dateTime.value.length > 0 && twchr_toApi_duration.value.length > 0){
        const date = twchr_every_reapeat_writer(twchr_toApi_dateTime.value,twchr_toApi_duration.value);
        document.querySelector("h4.twchr_serie_repeat").innerHTML = date;
        //console.log(date);    
    }
}

if(
    (location.pathname.includes('post.php') && getParameterByName('action') == 'edit') ||
    (location.pathname.includes('post-new.php') && getParameterByName('post_type') == 'twchr_streams')
){
   const btn_get_video = document.querySelector(".twchr_button_get_videos");
   const modal_get_video = document.querySelector(".twchr_modal_get_videos");
    
   btn_get_video.addEventListener('click',e=>{
    e.preventDefault();
    const user_id = tchr_vars_admin.twitcher_data_broadcaster.id;
    const client_id = tchr_vars_admin.twchr_keys['client-id'];
    const appToken = tchr_vars_admin.twchr_app_token;
    tchr_get_clips(appToken,client_id,user_id)
   });
    
}

if(location.pathname.includes('edit.php') && getParameterByName('post_type') == 'twchr_streams' && getParameterByName('get_thing') == 'videos_ajax'){
    console.log("funcona");
    function twchr_videos_ajax (data){
        // Agrego un EventLitener al boton enviar del modal
        console.log(data);
        GSCJS.queryOnly("#twchr-modal-selection__btn").addEventListener('click',event =>{
            event.preventDefault(); // Detengo su ejecucion por defecto
            let getParameters = '?post_type=twchr_streams&get_thing=videos' // Creo la primera parte de la nueva ruta
            let arrayCVS = '';
            data.forEach((item, index) => {
                const cheked = GSCJS.queryAll("#twchr-modal-selection__content input[type=checkbox]"); // Guarda una lista de todos los checkbox
                if(cheked.length > 0){ // Si hay algun checkbox en cheked
                    if(cheked[index].checked == true){ // Si checkbox esta activo
                        if(index == data.length - 1){
                            arrayCVS += `${item.id}`;
                        }else{
                            arrayCVS += `${item.id},`; // Agrega a getParameters el stream_id de los checkbox selecionados
                        }                       
                        
                    }
                }else{
                    console.log('No has seleccionado ninguno');
                }
                
            });
            getParameters += `&streams_id=${arrayCVS}`;
            newURL = GSCJS.getURLorigin()+GSCJS.getURLpath()+getParameters; // Crea una nueva url con la infromacion de las variables seleccionadas
            //console.log(newURL);
            location.href=newURL; // Redireciona al navegador a la url newURL
        });

        const modal = GSCJS.queryOnly("#twchr-modal-selection__content"); // Guarda el modal #twchr-modal-selection__content
        let Content = '';

        // Genera tantos checkbox como videos tenga el la API
        data.forEach((item, index) => {
            //console.log(item);
            const id = item.id;
            const title = item.title;
            const date_raw = item.created_at; // Fecha en RFC
            let date = new Date(date_raw);
            //console.log(item);
            //console.log(index);
            date = `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}`; // fecha en formato dd/mm/yyyy
            Content += `<section class='twchr_modal_video_ajax'>
                            <label data-twchrDataPosition='${index}' for='twchr_videos_ajax-${title}'><span>${title}</span><span>${date}</span><span></span></label>
                            <input type='checkbox' data-position='${index}' id='twchr_videos_ajax-${title}' class='twchr_videos_ajax' name=twchr_videos_ajax${id}' value='${id}'>
                        </section>`;
    
        });
    
    
        modal.innerHTML += Content;
        
        // Me comunico con la API de wordpress
        twchrFetchGet(tchr_vars_admin.wp_api_route+'twchr/v1/twchr_get_streaming', twchr_verification_videos,'json');

        function twchr_verification_videos(WpData){
            const chekeed = GSCJS.queryAll("#twchr-modal-selection__content input[type=checkbox]"); // Guarda una lista de todos los checkbox
            chekeed.forEach((check, index) => { 
                if(WpData.length > 1 && index < WpData.length ){
    
                    const wp_id = WpData[index].twchr_id;
                   

                    data.forEach(item =>{                        
                        const twtch_id = parseInt(item.id);
                    
                        if(wp_id === twtch_id){
                            /*
                            console.log(item.title);
                            console.log(WpData[index].title);
                            console.log(index);
                            */
                            if(chekeed.some(elemento =>  elemento.value == wp_id)){
                                const check_active = chekeed.find(e => e.value == wp_id);
                                check_active.parentElement.children[0].children[2].classList.add('video-saved');
                            }
                        }
                    
                        
                    });

                    
                   
                }
            });
        }

    }

    const user_id = tchr_vars_admin.twitcher_data_broadcaster.id;
    const client_id = tchr_vars_admin.twchr_keys['client-id'];
    const appToken = tchr_vars_admin.twchr_app_token;
    tchr_get_clips(appToken,client_id,user_id,twchr_videos_ajax);
}

if(location.pathname.split("/")[2] == 'edit.php' && getParameterByName('post_type') == 'twchr_streams' && getParameterByName('page') == 'twchr-dashboard'){
    const field_created_at = GSCJS.queryOnly(".twchr-data .created_at");
    const create_at_data_raw = field_created_at.textContent;
    let create_at = new Date(create_at_data_raw);
    create_at = `${create_at.getDay()} / ${create_at.getMonth()} / ${create_at.getFullYear()}`;
    field_created_at.textContent = create_at;
}

if(document.querySelector("body").classList.contains("twchr-single-streaming-active")){
    const twchr_modal = crearElemento("MODAL","twchr_modal");
    const twchr_ajax_input_category = document.querySelector("#twchr_schedule_card_input--category");
    const twchr_ajax_input_serie = document.querySelector("#twchr_schedule_card_input--serie");
    const twchr_ajax_label_serie = document.querySelector("label#twchr_schedule_card_input--serie__name--label");
    const twchr_ajax_label_category = document.querySelector("label[for='twchr_schedule_card_input--category']");
    twchr_ajax_label_category.classList.add("twchr_toApi_category_ajax--container");
    twchr_ajax_label_category.appendChild(twchr_modal);

   
    let radios;   

    twchr_ajax_input_category.oninput = ()=>{
        const query = twchr_ajax_input_category.value;
        const appToken = tchr_vars_admin.twchr_app_token;
        const twch_data_prime = tchr_vars_admin.twchr_keys;
        twchr_modal.classList.add('active');
        
        getCategorysTwitch(appToken, twch_data_prime['client-id'], query,()=>{
            radios = document.querySelectorAll(".twchr_toApi_category_ajax_radio");
            if(radios.length >= 1){
                radios.forEach(radio =>{
                    const section = radio.parentElement;
                    section.addEventListener('click',()=>{
                        console.log(radio);
                        if(radio.checked === true){
                            const optionName = radio.parentElement.children[0].textContent;
                            document.querySelector("input[name='twchr_schedule_card_input--category__value']").value = radio.value;
                            document.querySelector("input[name='twchr_schedule_card_input--category__name']").value = optionName;
                            twchr_ajax_input_category.value = optionName;   
                            twchr_modal.classList.remove('active');
                            twchr_modal.innerHTML ="";
                        }
                    });
                    
                });
            }
        });
        
    }
     
}


if(location.pathname.includes('plugins.php')){
    //console.log("hello");
    const disactive_link = GSCJS.queryOnly("table.plugins tr[data-slug='manage-twitch'] a");
    const url_disactive = disactive_link.href;
    const modal_disactive = GSCJS.crearNodo("DIV","");
    modal_disactive.classList.add("twchr-modal-general");
    modal_disactive.classList.add("twchr-modal-disactive");
    modal_disactive.innerHTML = `
        <div>
            <h3>Help us to improove</h3>
            <p>We thank you for give a try to Twitcher.
            We want to develope the best monetization tool for streamers.
            Please, share with us yout sugestions about your experience with Twitcher </p>
            <h4>How was your experiencie with Twitcher?</h4>
            <div>
                <svg width="32" height="29" viewBox="0 0 32 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 0L19.5922 11.0557H31.2169L21.8123 17.8885L25.4046 28.9443L16 22.1115L6.59544 28.9443L10.1877 17.8885L0.783095 11.0557H12.4078L16 0Z" fill="#D9D9D9"/>
                </svg>
                <svg width="32" height="29" viewBox="0 0 32 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 0L19.5922 11.0557H31.2169L21.8123 17.8885L25.4046 28.9443L16 22.1115L6.59544 28.9443L10.1877 17.8885L0.783095 11.0557H12.4078L16 0Z" fill="#D9D9D9"/>
                </svg>
                <svg width="32" height="29" viewBox="0 0 32 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 0L19.5922 11.0557H31.2169L21.8123 17.8885L25.4046 28.9443L16 22.1115L6.59544 28.9443L10.1877 17.8885L0.783095 11.0557H12.4078L16 0Z" fill="#D9D9D9"/>
                </svg>
                <svg width="32" height="29" viewBox="0 0 32 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 0L19.5922 11.0557H31.2169L21.8123 17.8885L25.4046 28.9443L16 22.1115L6.59544 28.9443L10.1877 17.8885L0.783095 11.0557H12.4078L16 0Z" fill="#D9D9D9"/>
                </svg>
                <svg width="32" height="29" viewBox="0 0 32 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 0L19.5922 11.0557H31.2169L21.8123 17.8885L25.4046 28.9443L16 22.1115L6.59544 28.9443L10.1877 17.8885L0.783095 11.0557H12.4078L16 0Z" fill="#D9D9D9"/>
                </svg>
            </div>
            <input type="range" step="1" min="1" max="5">
            <h4>How we can improove Twitcher?</h4>
            <textarea></textarea>
        </div>
        <div>
            <picture>
                <img src="${tchr_vars_admin.site_url}/wp-content/plugins/twitcher/includes/assets/Isologo_twitcher.svg"/>
            </picture>
        </div>
        <input type="hidden" name="url-disactivate" value='${url_disactive}'>
        <button>Share your feedback </button>
    `;
   
    disactive_link.setAttribute("src","#");
    disactive_link.addEventListener('click',(e)=>{
       
        //const url = modal_disactive.querySelector("input[name='url-disactivate']").value;
        //GSCJS.queryOnly(".wp-heading-inline").appendChild(modal_disactive);
        //window.scrollTo(0,0);
        
    });

}