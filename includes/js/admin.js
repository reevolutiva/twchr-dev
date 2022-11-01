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


function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
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
    if(finalNumber | finalNumber > 1){
        const counter = setInterval(() => {
            currentNumber++;
            nodeTarget.textContent = currentNumber;
            if(currentNumber === finalNumber) clearInterval(counter);
        }, time);
    }
  }
  

const getCategorysTwitch = async (appToken, client_id, query)=>{
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
            
            

            
        }else{
            response = new Error('Parametro query vacio');
            console.log(response);
        }
        
     
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
            date = `${date.getDay()}/${date.getMonth()}/${date.getFullYear()}`; // fecha en formato dd/mm/yyyy
            Content += `<section class='twchr_modal_video_ajax'>
                            <label data-twchrDataPosition='${index}' for='twchr_videos_ajax-${title}'><span>${title}</span><span>${date}</span><span></span></label>
                            <input type='radio' data-position='${index}' id='twchr_videos_ajax-${title}' class='twchr_videos_ajax' name=twchr_videos_ajax${id}' value='${id}'>
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
                GSCJS.queryOnly("#titlewrap label").classList.add('screen-reader-text');
                GSCJS.queryOnly("#titlewrap input").value = data.title; // Escribo el titulo del post
                postBox.forEach((input,index)=>{
                    switch (index) {
                        case 0:
                            input.value = data.created_at;
                            break;
                        case 1:
                            input.value = data.description;
                            break;
                        case 2:
                            input.value = data.duration;
                            break;
                        case 3:
                            input.value = data.id;
                            break;
                        case 4:
                            input.value = data.language;
                            break;
                        case 5:
                            input.value = data.muted_segment;
                            break;
                        case 6:
                            input.value = data.published_at;
                            break;
                        case 7:
                            input.value = data.stream_id;
                            break;
                        case 8:
                            input.value = data.thumbnail_url;
                            break;
                        case 9:
                            input.value = data.type;
                            break;
                        case 10:
                            input.value = data.url;
                            break;
                        case 11:
                            input.value = data.user_id;
                            break;
                        case 12:
                            input.value = data.user_login;
                            break;
                        case 13:
                            input.value = data.user_name;
                            break;
                        case 14:
                            input.value = data.view_count;
                            break;
                        case 15:
                            input.value = data.viewable;
                            break;
                        case 16:
                            input.value = data.title;
                            break;
                                   
                        default:
                            break;
                    }
                });

                // Creo un fragmeto de HTML que me muestra el shorcode actualizado
                // Ya que JS no escribe en Iframes y el campo de post_content es un iframe
                GSCJS.queryOnly("#twittcher-stream").style.display = 'block';
                const alertCode = GSCJS.crearNodo('DIV');
                alertCode.classList.add("modal-edit-shordcode");
                alertCode.innerHTML = `<h3>Copy this shortcode</h3><p>[twich_embed host="${data.user_name}" video="${data.id}"  ancho="800" alto="400"]</p>`;
                GSCJS.queryOnly("#wp-content-editor-tools #wp-content-media-buttons").appendChild(alertCode);
                GSCJS.queryOnly("stream.twchr_modal_get_videos.twchr-modal.active").classList.remove('active');
            }
           });
      }else{ // Sí se definio un callback ejecuta el callback
        callback_ajax(arrayList); // Pasa al Callback el arrayList con los videos
      }
       
}


if((getParameterByName('post_type') == 'twchr_streams' && location.pathname.includes('post-new.php')) ||
(getParameterByName('action') == 'edit' && location.pathname.includes('post.php')) ){
    const element = GSCJS.queryOnly("#twittcher-stream .inside input");
    const twittcher_stream = GSCJS.queryOnly("#twittcher-stream");
    if(element.value.length < 1){
        //twittcher_stream.style.display = 'none';
    }

    const postBox = GSCJS.queryAll("#twittcher-stream .inside input");
    const twchr_edit_card = GSCJS.queryOnly(".twchr_custom_card--contain");
    

    console.log(postBox)
    // Lleno Twchr card
    GSCJS.queryOnly(".twchr_custom_card--contain .twchr_card_header--title h3").textContent = postBox[16].value === '' ? 'undefined' : postBox[16].value;
    GSCJS.queryOnly(".twchr_custom_card--contain .twchr_card_header-description h4").textContent = postBox[1].value === '' ? 'undefined' : postBox[1].value;
    
    GSCJS.queryOnly(".twchr_custom_card--contain .twchr_card_body--list li:nth-of-type(1) span.value").textContent = postBox[0].value === '' ? 'undefined' : postBox[0].value;
    GSCJS.queryOnly(".twchr_custom_card--contain .twchr_card_body--list li:nth-of-type(2) span.value").textContent = postBox[2].value === '' ? 'undefined' : postBox[2].value;
    GSCJS.queryOnly(".twchr_custom_card--contain .twchr_card_body--list li:nth-of-type(3) span.value").textContent = postBox[4].value === '' ? 'undefined' : postBox[4].value;
    GSCJS.queryOnly(".twchr_custom_card--contain .twchr_card_body--list li:nth-of-type(4) span.value").textContent = postBox[9].value === '' ? 'undefined' : postBox[9].value;
    GSCJS.queryOnly(".twchr_custom_card--contain .twchr_card_body--list li:nth-of-type(5) span.value").textContent = postBox[15].value === '' ? 'undefined' : postBox[15].value;
    GSCJS.queryOnly(".twchr_custom_card--contain .twchr_card_body--list li:nth-of-type(6) span.value").textContent = postBox[10].value === '' ? 'undefined' : postBox[10].value;
    let card_img = postBox[8].value;
    card_img = card_img.replace("%{width}x%{height}","250x150");
    GSCJS.queryOnly(".twchr_custom_card--contain .twchr_card_header--img img").setAttribute('src',card_img);
  
    GSCJS.queryOnly(".twchr_custom_card--contain .twchr_card_body--status .item h3").textContent = postBox[14].value === '' ? 'undefined' : postBox[14].value;
   
    twchrFetchGet(
        'https://api.twitch.tv/helix/videos?id='+postBox[3].value,
        (element)=>{
            console.log(element.data);
            if(element.data){
                GSCJS.queryOnly(".twchr_custom_card--contain .twchr_card_body--status .item.status h3").classList.add('on');
                GSCJS.queryOnly(".twchr_custom_card--contain .twchr_card_body--status .item.status h3").textContent = 'Online';
            }else{
                GSCJS.queryOnly(".twchr_custom_card--contain .twchr_card_body--status .item.status h3").classList.add('failed');
                GSCJS.queryOnly(".twchr_custom_card--contain .twchr_card_body--status .item.status h3").textContent = 'Offline';    
            }
        },
        'json',{headers: {
            "Authorization": `Bearer ${tchr_vars_admin.twchr_app_token}`,
            "client-id": tchr_vars_admin.twchr_keys['client-id']
    }});
    
}

//post_type=twchr_streams&page=twchr-settings
if(getParameterByName('post_type')=='twchr_streams' && getParameterByName('page')=='twchr-settings'){
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
           const res = await response.json();
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
                                const url_redirect  = `${GSCJS.getURLorigin()}/wp-admin/edit.php?post_type=twchr_streams&page=twchr-settings&autentication=true`;
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
                                    }
                                });
                                
                            }else{
                                console.log(res);
                            }
                            
                            
                            
                        }
                        
                        
                        alert.classList.add("warning");
                        alert.innerHTML = `<h3>Ups!</h3><p><b>${element.name}</b> was created in wordpress, but not exist in twitch</p><p>serie: <b>${element.name}</b></p><input type="checkbox" name="twchr_schedule_exist" >`;
                        ajaxResponse.appendChild(alert)
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

    const url = location.origin+'/wp-json/twchr/twchr_get_serie';
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
     
}

if(
    (location.pathname.split("/")[2] == 'post.php' && getParameterByName('action') == 'edit') ||
    (location.pathname.split("/")[2] == 'post-new.php' && getParameterByName('post_type') == 'twchr_streams')
){
   const btn_get_video = document.querySelector(".twchr_button_get_videos");
   const modal_get_video = document.querySelector(".twchr_modal_get_videos");
    
   btn_get_video.addEventListener('click',e=>{
    e.preventDefault();
    modal_get_video.classList.toggle('active');
    const user_id = tchr_vars_admin.twitcher_data_broadcaster.id;
    const client_id = tchr_vars_admin.twchr_keys['client-id'];
    const appToken = tchr_vars_admin.twchr_app_token;
    tchr_get_clips(appToken,client_id,user_id)
   });
}

if(location.pathname.split("/")[2] == 'edit.php' && getParameterByName('post_type') == 'twchr_streams' && getParameterByName('get_thing') == 'videos_ajax'){
    
    function twchr_videos_ajax (data){
        // Agrego un EventLitener al boton enviar del modal
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
            console.log(newURL);
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
            date = `${date.getDay()}/${date.getMonth()}/${date.getFullYear()}`; // fecha en formato dd/mm/yyyy
            Content += `<section class='twchr_modal_video_ajax'>
                            <label data-twchrDataPosition='${index}' for='twchr_videos_ajax-${title}'><span>${title}</span><span>${date}</span><span></span></label>
                            <input type='checkbox' data-position='${index}' id='twchr_videos_ajax-${title}' class='twchr_videos_ajax' name=twchr_videos_ajax${id}' value='${id}'>
                        </section>`;
    
        });
    
    
        modal.innerHTML += Content;
        
        // Me comunico con la API de wordpress
        twchrFetchGet(location.origin+'/wp-json/twchr/twchr_get_streaming/', twchr_verification_videos,'json');

        function twchr_verification_videos(WpData){
            const chekeed = GSCJS.queryAll("#twchr-modal-selection__content input[type=checkbox]"); // Guarda una lista de todos los checkbox
            chekeed.forEach(check => {
                
                const pos = parseInt(check.getAttribute('data-position'));
                const twtch_stream_id = parseInt(data[pos].stream_id);
                if(WpData.length > 1){
                    const wp_stream_id = WpData[pos] != undefined ? WpData[pos].twchr_stream_id : false;
                    
                    if(wp_stream_id === twtch_stream_id){
                        console.log("alive");
                        check.parentElement.children[0].children[2].classList.add('video-saved');
                    }
                }
            });
        }

    }

    const user_id = tchr_vars_admin.twitcher_data_broadcaster.id;
    const client_id = tchr_vars_admin.twchr_keys['client-id'];
    const appToken = tchr_vars_admin.twchr_app_token;
    tchr_get_clips(appToken,client_id,user_id,twchr_videos_ajax);
}

if(location.pathname.split("/")[2] == 'edit.php' && getParameterByName('post_type') == 'twchr_streams' && getParameterByName('page') == 'twchr-settings'){
    const field_created_at = GSCJS.queryOnly(".twchr-data .created_at");
    const create_at_data_raw = field_created_at.textContent;
    let create_at = new Date(create_at_data_raw);
    create_at = `${create_at.getDay()} / ${create_at.getMonth()} / ${create_at.getFullYear()}`;
    field_created_at.textContent = create_at;
}