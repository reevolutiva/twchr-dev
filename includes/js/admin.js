/*
 * == INDICE ==
 * FUNCTIONS
 * TERMS
 * PAGE
 * PLUGINS
 * EDIT
 */

// FUNCTIONS.

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
    let timeOffset = new Date();
    timeOffset = timeOffset.getTimezoneOffset() / 60;
    const fecha = new Date(newDate_raw);
   
    //Positivo true y Negativo false
    const sing = twchr_math_sign(timeOffset);
    if(sing){
        fecha.setMinutes(fecha.getMinutes()+Math.abs(timeOffset));
    }else{
        fecha.setMinutes(fecha.getMinutes()-Math.abs(timeOffset));
    }
    
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
    

    const start_time = fecha.toLocaleTimeString();

    fecha.setMinutes(fecha.getMinutes() + parseInt(duration));

    const end_time = fecha.toLocaleTimeString();
    
    const fecha_msg = `${dia} from <b>${start_time}</b> to <b>${end_time}</b>`;
    
    return fecha_msg;
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
  

// END FUNCTIONS.

// PAGE.PHP PAGE.

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

// END PAGE.PHP PAGE.

// TERM.PHP PAGE.
//taxonomy=serie&post_type=twchr_streams
if(getParameterByName('taxonomy') ==='serie' && getParameterByName('post_type') == 'twchr_streams' && location.href.split(tchr_vars_admin.site_url+"/wp-admin/")[1].includes("term.php"))
   {
    
    const allData = GSCJS.queryOnly("#twchr_fromApi_allData");
    const dateTime = GSCJS.queryOnly("#twchr_toApi_dateTime");


    dateTime.addEventListener('click',(e)=>{
        if(e.target.getAttribute('type') == 'text'){
            e.target.setAttribute('type','dateTime-local');
        }
    },);

    const twchr_response_template = (status,txt,msg=false) =>{
        let template = `<h3>${txt}</h3>`;
        if(status !== 200 && msg != false){
            template+= `<p>${msg}</p>`;
        }

        return template;
    }

    if(allData.textContent.length > 0){
        const twchr_response = JSON.parse(allData.textContent);
        //console.log(twchr_response);
        
        if(twchr_response.status == 401 ){
            const div = GSCJS.crearNodo("DIV");
            div.innerHTML = twchr_response_template(401,`<span style="color:red;">Disconected Twitch</span>`,twchr_response.message);
            allData.parentElement.appendChild(div);
            
        }else if((twchr_response.start_time && twchr_response.end_time) || twchr_response.allData){
            const div = GSCJS.crearNodo("DIV");
            div.innerHTML = twchr_response_template(200,`<span style="color:green;">Conected Twitch</span>`);
            allData.parentElement.appendChild(div);

        }else{
            const div = GSCJS.crearNodo("DIV");
            div.innerHTML = twchr_response_template(twchr_response.status,
                                                    `<span style="color:red;">Disconected Twitch</span>`,
                                                    `<b>Error: </b>${twchr_response.status} 
                                                        </br> 
                                                    <b>Glosa: </b>${twchr_response.message}`);
            allData.parentElement.appendChild(div);
        }
    }else{
        const div = GSCJS.crearNodo("DIV");
            div.innerHTML = twchr_response_template(404,`<span style="color:red;">Disconected Twitch</span>`);
            allData.parentElement.appendChild(div)
    }
    

    if(twchr_getCookie('twchr_serie_twitch_response_term_id') != undefined &&
       twchr_getCookie('twchr_serie_twitch_response_state') != undefined
    ){
        const twchr_tw_cookie_response = {
          term_id: twchr_getCookie("twchr_serie_twitch_response_term_id"),
          state: twchr_getCookie("twchr_serie_twitch_response_state"),
        };


        if (twchr_tw_cookie_response.state == "succses") {
          if (document.querySelector("#twchr_toApi_schedule_segment_id").value.length > 0) {
                alert("serie created in twitch");
            }
        }else if(twchr_tw_cookie_response.state == "error"){
            const twchr_response = JSON.parse(allData.textContent);
            if (allData.textContent.length > 0) {
              const data = JSON.parse(allData.textContent);
              const txt = `Error: ${data.error} Message: ${data.message}`;
              alert(txt);

              if(twchr_response.status == 401 ){
                if(confirm(twchr_response.message)){
                    location.href = twchr_response.url_redirect;
                }
                
                }
            }
        }

        twchr_deleteCookie("twchr_serie_twitch_response_term_id");
        twchr_deleteCookie("twchr_serie_twitch_response_state");
        
    }
    

    



    

    const inputTxtCategory = document.querySelector("#twchr_toApi_category_ajax");
    const twchr_modal = crearElemento("MODAL","twchr_modal");
    const padreInput = inputTxtCategory.parentElement;
    padreInput.classList.add("twchr_toApi_category_ajax--container");
    padreInput.appendChild(twchr_modal);

    let radios;   
    inputTxtCategory.oninput = ()=>{
        const query = inputTxtCategory.value;
        const appToken = tchr_vars_admin.twchr_app_token;
        const twch_data_prime = tchr_vars_admin.twchr_keys;
        twchr_modal.classList.add('active');
        getCategorysTwitch(appToken, twch_data_prime['client-id'], query, ()=>{
            radios = document.querySelectorAll(".twchr_toApi_category_ajax_radio");
            if(radios.length >= 1){
               radios.forEach(radio =>{
                    const section = radio.parentElement;
                    section.addEventListener('click',()=>{
                        console.log(radio);
                        if(radio.checked === true){
                            const optionName = radio.parentElement.children[0].textContent;
                            twchr_modal.classList.remove('active');
                            twchr_modal.innerHTML ="";
                            document.querySelector("#twchr_toApi_category_value").value = radio.value;
                            document.querySelector("#twchr_toApi_category_name").value = optionName;
                            inputTxtCategory.value = optionName;
                        }
                    },);
                    
                });
            }
        });
    }  
     
    const twchr_toApi_dateTime = document.querySelector("#twchr_toApi_dateTime");
    const twchr_toApi_duration = document.querySelector("#twchr_toApi_duration");
    if(twchr_toApi_dateTime.value.length > 0 && twchr_toApi_duration.value.length > 0){
        const date = twchr_every_reapeat_writer(twchr_toApi_dateTime.value,twchr_toApi_duration.value);
        document.querySelector("h4.twchr_serie_repeat").innerHTML = date;
        //console.log(date);    
    }
}

// END TERM.PHP PAGE.

// POST.PHP Y POST-NEW.PHP PAGE.

if(getParameterByName('action') == 'edit' && location.pathname.includes('post.php')){
    const element = GSCJS.queryOnly("#twittcher-stream .inside input");
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
   
    if(postBox[3].value != ""){
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
                    document.querySelector("#twchr-modal-selection__btn").classList.add("disabled");
                    twchr_card_header_menu[0].addEventListener('click', ()=>{
                        twchr_card_state = 'schedule';
                        twchr_card_header_menu[1].classList.add("disabled");
                        twchr_card_header_menu[0].classList.remove("disabled");
                        twchr_slide_card_row.style.transform = 'translateX(0%)';
                    },);
    
                    if(![...twchr_meta_box_serie.classList].find(item => item == 'hide-if-js')) twchr_meta_box_serie.classList.add("hide-if-js"); 
                    if(![...twchr_meta_box_cat_tw.classList].find(item => item == 'hide-if-js')) twchr_meta_box_cat_tw.classList.add("hide-if-js");
    
                    
                    // Si no todos los campos son undefined es porque fue asignado
                }else{
                    twchr_card_header_menu[0].classList.remove("active");
                    twchr_card_header_menu[0].classList.add("diactive");
                    twchr_slide_card_row.style.transform = 'translateX(calc(-100% - .5cm))';
                    document.querySelector("#twchr-modal-selection__btn").classList.remove("disabled");
                    twchr_card_header_menu[0].classList.add("disabled");
                    twchr_card_header_menu[1].classList.remove("disabled");
      
                }
            },
            'json',{headers: {
                "Authorization": `Bearer ${tchr_vars_admin.twchr_app_token}`,
                "client-id": tchr_vars_admin.twchr_keys['client-id']
        }});
    }else{
        GSCJS.queryOnly(".previw_card").parentElement.style.display = "none";
    }
    
    

    if(twchr_is_recurring[0].checked == true ||  (twchr_is_recurring[0].checked == false && twchr_is_recurring[1].checked == false)){

        twchr_is_recurring[0].checked = true;
        twchr_schedule_card_dateTime.parentElement.style.display = 'none';      
        document.querySelector("#twchr_dateTime_slot").style.display = 'block';
        GSCJS.queryOnly("#twchr_schedule_card_input--title").parentElement.querySelector("label").textContent= 'Serie Name';
        const button = GSCJS.queryOnly("#twchr_card_button_create_new_serie a");
        const url_original = button.getAttribute('href');
        GSCJS.queryOnly("#twchr_schedule_card_input--title").oninput = ()=>{
            const text = GSCJS.queryOnly("#twchr_schedule_card_input--title").value;
            const url = url_original+'&from_cpt_name='+text;
             
            button.setAttribute('href', url);
            //console.log(url);
        }

        twchr_schedule_chapter_asign();

    
    }else{
        const twchr_schedule_card = document.querySelector(".twchr_custom_card--contain");
        const input_serie = twchr_schedule_card.querySelector("#twchr_schedule_card_input--serie");
        const input_serie_label = twchr_schedule_card.querySelector("label#twchr_schedule_card_input--serie__name--label");
        twchr_modal_schedule__btn.setAttribute('data-twchr-is-recurring',false);
        //const twchr_dateTime_slot = document.querySelector("#twchr_dateTime_slot");
        
        //twchr_dateTime_slot.style.display = "none";
    
        input_serie.parentElement.parentElement.style.display = 'none';
        input_serie_label.style.display = 'none';
        input_title.removeAttribute('disabled');
        document.querySelector("#twchr_dateTime_slot").parentElement.style.display = 'none';
        document.querySelector("#twchr_card_button_create_new_serie").style.display = "none";
    }

    


    
}

if(location.pathname.includes('post.php') && getParameterByName('action') == 'edit'){
   const btn_get_video = document.querySelector(".twchr_button_get_videos");
   const modal_get_video = document.querySelector(".twchr_modal_get_videos");
   const user_id = tchr_vars_admin.twitcher_data_broadcaster.id;
   const client_id = tchr_vars_admin.twchr_keys['client-id'];
   const appToken = tchr_vars_admin.twchr_app_token;
   
   tchr_get_clips(appToken,client_id,user_id); //TODO:

   btn_get_video.addEventListener('click',e=>{
    //e.preventDefault();
    
    if(twchr_card_embed_menu_state == 'tw'){
        tchr_get_clips(appToken,client_id,user_id);
    }
    
   },);

   document.querySelector("#twchr-modal-selection__btn").addEventListener('click',(e)=>{
        e.preventDefault();
        if(twchr_card_embed_menu_state == 'yt'){
            
			const url = '/post.php?post='+getParameterByName('post')+'&action=edit&twchr_insert_shorcode=ancho-800,alto-400';
			const new_link = tchr_vars_admin.site_url+'/wp-admin/'+url+"&yt_url="+document.querySelector("#twchr-yt-url-link").value;
			location.href= new_link;
        }
   },);
    
}

if(location.pathname.includes('post-new.php') || location.pathname.includes('post.php') && getParameterByName('action') == 'edit'){
    const twchr_meta_box_serie = document.querySelector("#tagsdiv-serie");
    const twchr_meta_box_cat_tw = document.querySelector("#tagsdiv-cat_twcht");

    const postBox = GSCJS.queryAll("#twittcher-stream .inside input");
    const isAssigned = postBox[3].value == "" ? false : true;

        const twchr_modal = crearElemento("MODAL","twchr_modal");
        const twchr_ajax_input_category = document.querySelector("#new-tag-cat_twcht");
        
        const twchr_ajax_label_category = twchr_ajax_input_category.parentElement.parentElement;
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
                            //console.log(radio);
                            if(radio.checked === true){
                                const optionName = radio.parentElement.children[0].textContent;
                                twchr_ajax_input_category.value = optionName
                                twchr_modal.classList.remove('active');
                                twchr_modal.innerHTML ="";
                            }
                        });
                        
                    },);
                }
    
                
            },'#cat_twcht .twchr_modal ');
            
        }
        
    if(!GSCJS.queryOnly("#tagsdiv-twchr_streaming_states").classList.contains("hide-if-js")){
        GSCJS.queryOnly("#tagsdiv-twchr_streaming_states").classList.add("hide-if-js");
    }
    if(isAssigned){
        if(twchr_meta_box_serie.classList.contains("hide-if-js")){
            twchr_meta_box_serie.classList.remove("hide-if-js");
        }
        if(twchr_meta_box_cat_tw.classList.contains("hide-if-js")){
            twchr_meta_box_cat_tw.classList.remove("hide-if-js");
        }
    }else{
        if(!twchr_meta_box_serie.classList.contains("hide-if-js")){
            twchr_meta_box_serie.classList.add("hide-if-js");
        }
    }
}

// END POST.PHP Y POST-NEW.PHP PAGE.

// EDIT.PHP PAGE.

if(location.pathname.includes('edit.php') && getParameterByName('post_type') == 'twchr_streams' && getParameterByName('get_thing') == 'videos_ajax'){
    
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
        },);

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

        

    }

    const user_id = tchr_vars_admin.twitcher_data_broadcaster.id;
    const client_id = tchr_vars_admin.twchr_keys['client-id'];
    const appToken = tchr_vars_admin.twchr_app_token;
    tchr_get_clips(appToken,client_id,user_id,twchr_videos_ajax);
}

// END EDIT.PHP PAGE.

if(location.pathname.split("/")[2] == 'edit.php' && getParameterByName('post_type') == 'twchr_streams' && getParameterByName('page') == 'twchr-dashboard'){
    const field_created_at = GSCJS.queryOnly(".twchr-data .created_at");
    const create_at_data_raw = field_created_at.textContent;
    let create_at = new Date(create_at_data_raw);
    create_at = `${create_at.getDay()} / ${create_at.getMonth()} / ${create_at.getFullYear()}`;
    field_created_at.textContent = create_at;
}

if(document.querySelector("body").classList.contains("twchr-single-streaming-active")){
    const twchr_modal = crearElemento("MODAL","twchr_modal");
    const twchr_ajax_input_category = document.querySelector("#twchr_schedule_card_input--category__name");
    
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
                    
                },);
            }

            
        });
        
    }
     
}

// PLUGINS.PHP PAGE.

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
   
    //disactive_link.setAttribute("src","#");
    disactive_link.addEventListener('click',(e)=>{
        e.preventDefault();
        if(confirm('You want to remove all twitcher information from the database when uninstalling this plugin?')){
            wp.ajax.send('twchr_delete_all',{data:{twchr_delete_all:1}}).done(

                e => {
                    if(e == 200){
                    alert('When you uninstall the plugin all twitcher settings and data will be deleted.');
                        location.href = url_disactive;
                    }else{
                        alert('When you uninstall the plugin it will not remove all twitcher settings and data.');
                        location.href = url_disactive;
                    } 
                }
            );
        }else{
            location.href = url_disactive;
        }
        //const url = modal_disactive.querySelector("input[name='url-disactivate']").value;
        //GSCJS.queryOnly(".wp-heading-inline").appendChild(modal_disactive);
        //window.scrollTo(0,0);
        
    },);

}