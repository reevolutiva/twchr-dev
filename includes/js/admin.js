// Funcion que obtiene los parametros GET con JS
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
            const name = item.title;
            Content += `<section><label data-twchrDataPosition='${index}' for='twchr_videos_ajax-${name}'>${name}</label><input type='radio' id='twchr_videos_ajax-${name}' class='twchr_videos_ajax' name=twchr_videos_ajax' value='${id}'></section>`;
           });
    
           // Introdusco la lista de check-boxs al modal .twchr_modal_get_videos
           GSCJS.queryOnly(".twchr_modal_get_videos").innerHTML = Content;
    
           // Guardo los inputs radio creados más arriba
           const options = GSCJS.queryAll(".twchr_modal_get_videos section label");
           const postBox = GSCJS.queryAll("#twittcher-stream .inside input");

           options.forEach(e => {
            // Agrego un eventLitener a los labels
            e.addEventListener('click',(event)=>{
                GSCJS.queryOnly("#titlewrap label").classList.add('screen-reader-text');
                GSCJS.queryOnly("#titlewrap input").value = e.textContent; // Escribo el titulo del post
                const pos = event.target.getAttribute('data-twchrDataPosition'); // Guardo la posision del input seleccionado
                const data = arrayList[pos]; // tomo el video de la api con el mismo index guardado en pos
                
                // Relleno la metabox Twittcher Stream con los datos que devuelve la API de Twitch
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
                                   
                        default:
                            break;
                    }
                });

                // Creo un fragmeto de HTML que me muestra el shorcode actualizado
                // Ya que JS no escribe en Iframes y el campo de post_content es un iframe
                GSCJS.queryOnly("#twittcher-stream").style.display = 'block';
                const alertCode = GSCJS.crearNodo('SPAN');
                alertCode.textContent = `[twich_embed host="${data.user_name}" video="${data.id}"  ancho="800" alto="400"]`;
                GSCJS.queryOnly("#wp-content-editor-tools #wp-content-media-buttons").appendChild(alertCode);
                GSCJS.queryOnly(".twchr_modal_get_videos").classList.remove('active');
    
               
                
            });
           });
      }else{ // Sí se definio un callback ejecuta el callback
        callback_ajax(arrayList); // Pasa al Callback el arrayList con los videos
      }
       
}


if((getParameterByName('post_type') == 'twchr_streams' && location.pathname.includes('post-new.php')) ||
(getParameterByName('action') == 'edit' && location.pathname.includes('post.php')) ){
    console.log('estoy donde devo estar');
    const element = GSCJS.queryOnly("#twittcher-stream .inside input");
    if(element.value.length < 1){
        GSCJS.queryOnly("#twittcher-stream").style.display = 'none';
    }
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

//taxonomy=schedule&post_type=twchr_streams
if((getParameterByName('taxonomy') ==='schedule' && getParameterByName('post_type') == 'twchr_streams' && location.pathname.split("/")[2] == 'edit-tags.php') ||
   (getParameterByName('taxonomy') ==='schedule' && getParameterByName('post_type') == 'twchr_streams' && location.pathname.split("/")[2] == 'term.php')
   ){
    const ajaxResponse = document.querySelector("#ajax-response");
    const getResponse = async (url) =>{
        try {
           const response = await fetch(url);
           const res = await response.json();
           res.forEach(element => {
            const dataFromApi = JSON.parse(element.dataFromTwitch);
            const alert = crearElemento("DIV","alert-twchr-back");
            const state = dataFromApi.status;
                
                switch (state) {
                    case 200:
                        alert.innerHTML = `<h3>Éxito</h3><p>${dataFromApi.message}</p><p>Schedule: <b>${element.name}</b></p>`;
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
                        alert.innerHTML = `<h3>${dataFromApi.error}</h3><p>${dataFromApi.message}</p><p>Schedule: <b>${dataFromApi.title}</b></p>`;
                        ajaxResponse.appendChild(alert);
                        break;
                    
                    
                
                    default:
                        break;
                }
                
           });

          
           //console.log(dataFromApi);
        } catch (error) {
            console.log(error);
        }
    }

    const url = location.origin+'/wp-json/twchr/twchr_get_schedule';
    getResponse(url);


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
        const appToken = tchr_vars_admin.twitcher_app_token;
        const twch_data_prime = tchr_vars_admin.twitcher_keys;
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
    const client_id = tchr_vars_admin.twitcher_keys['client-id'];
    const appToken = tchr_vars_admin.twitcher_app_token;
    tchr_get_clips(appToken,client_id,user_id)
   });
}

if(location.pathname.split("/")[2] == 'edit.php' && getParameterByName('post_type') == 'twchr_streams' && getParameterByName('get_thing') == 'videos_ajax'){
    
    function twchr_videos_ajax (data){
        // Agrego un EventLitener al boton enviar del modal
        GSCJS.queryOnly("#twchr-modal-selection__btn").addEventListener('click',event =>{
            event.preventDefault(); // Detengo su ejecucion por defecto
            let getParameters = '?post_type=twchr_streams&get_thing=videos' // Creo la primera parte de la nueva ruta
            data.forEach((item, index) => {
                const cheked = GSCJS.queryAll("#twchr-modal-selection__content input[type=checkbox]"); // Guarda una lista de todos los checkbox
                if(cheked.length > 0){ // Si hay algun checkbox en cheked
                    if(cheked[index].checked == true){ // Si checkbox esta activo
                         getParameters += `&streams_id=${item.stream_id}`; // Agrega a getParameters el stream_id de los checkbox selecionados
                    }
                }else{
                    console.log('No has seleccionado ninguno');
                }
                
            });
            newURL = GSCJS.getURLorigin()+GSCJS.getURLpath()+getParameters; // Crea una nueva url con la infromacion de las variables seleccionadas
            location.href=newURL; // Redireciona al navegador a la url newURL
        });

        const modal = GSCJS.queryOnly("#twchr-modal-selection__content"); // Guarda el modal #twchr-modal-selection__content
        let Content = '';

        // Genera tantos checkbox como videos tenga el la API
        data.forEach((item, index) => {
            console.log(item);
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
        gscFetch (location.origin+'/wp-json/twchr/twchr_get_streaming/', twchr_verification_videos,'json');

        function twchr_verification_videos(WpData){
            const chekeed = GSCJS.queryAll("#twchr-modal-selection__content input[type=checkbox]"); // Guarda una lista de todos los checkbox
            chekeed.forEach(check => {
                const pos = check.getAttribute('data-position');
                const twtch_stream_id = data[pos].stream_id;
                const wp_stream_id = WpData[pos].twchr_stream_id;

                if(wp_stream_id == twtch_stream_id){
                    check.parentElement.children[0].children[2].classList.add('video-saved');
                }
            });
        }

    }

    const user_id = tchr_vars_admin.twitcher_data_broadcaster.id;
    const client_id = tchr_vars_admin.twitcher_keys['client-id'];
    const appToken = tchr_vars_admin.twitcher_app_token;
    tchr_get_clips(appToken,client_id,user_id,twchr_videos_ajax);
}