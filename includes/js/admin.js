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


const getCategorysTwitch = (appToken, client_id, query)=>{
        const arrayResponse = [];
        var myHeaders = new Headers();
        myHeaders.append("Authorization", `Bearer ${appToken}`);
        myHeaders.append("Client-Id", client_id);

        var requestOptions = {
        method: 'GET',
        headers: myHeaders,
        redirect: 'follow'
        };

        fetch("https://api.twitch.tv/helix/search/categories?query="+query, requestOptions)
        .then(response => response.text())
        .then(result => {
            const data = JSON.parse(result);
            data.data.forEach(e =>{
                arrayResponse.push(e);
            });
        })
        .catch(error => console.log('error', error));

        return arrayResponse;
}


if((getParameterByName('post_type') == 'twchr_streams' && location.pathname.includes('post-new.php')) ||
(getParameterByName('action') == 'edit' && location.pathname.includes('post.php')) ){
    console.log('estoy donde devo estar');

    const twchr_streamas_metaBox__input = document.querySelectorAll("#twittcher-stream .inside input");
    for (let i = 0; i < twchr_streamas_metaBox__input.length; i++) {
        const element = twchr_streamas_metaBox__input[i];
        element.setAttribute('disabled',true);
    }

    const twchr_streamas_metaBox__textArea = document.querySelectorAll("#twittcher-stream .inside textarea");
    for (let i = 0; i < twchr_streamas_metaBox__textArea.length; i++) {
        const element = twchr_streamas_metaBox__textArea[i];
        element.setAttribute('disabled',true);
    }
}

//post_type=twchr_streams&page=twchr-settings
if(getParameterByName('post_type')=='twchr_streams' && getParameterByName('page')=='twchr-settings'){
    const table_tchr_setting = document.querySelector("table.twittcher-table");
    const table_tchr_setting__txt = document.querySelectorAll("table.twittcher-table tr td:nth-child(2)");
    for (let i = 0; i < table_tchr_setting__txt.length; i++) {
        const element = table_tchr_setting__txt[i];
        // Si el indice es diferente a 0 y 3
        if(i != 0 && i !=  3){
            // guarda el contenido de table_tchr_setting__txt
            const strg = element.textContent.trim(); 
            const strg_lengt = strg.length;
            const strg_start = strg.slice(0,4);
            if(strg != 'key sin registrar'){
                element.textContent = strg_start+create_strg_secret(strg_lengt);
            }
            

        }
        
    }

}

//taxonomy=schedule&post_type=twchr_streams
if(getParameterByName('taxonomy') ==='schedule' && getParameterByName('post_type') == 'twchr_streams' && location.pathname.split("/")[2] == 'edit-tags.php'){
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
                        alert.innerHTML = `<h4>${dataFromApi[0]}</h4>`;
                        ajaxResponse.appendChild(alert);
                        const url = dataFromApi.url_redirect;
                        
                        setTimeout(() => {
                            location.href = url;
                        }, 1000);

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

    const buttonSubmit = document.querySelector("form#addtag input[type='submit']");
    buttonSubmit.setAttribute('disabled',true);

    const inputTxtCategory = document.querySelector("#twchr_toApi_category_ajax");
    const span = crearElemento("SPAN","btn");
    const twchr_modal = crearElemento("MODAL","twchr_modal");
    const padreInput = inputTxtCategory.parentElement;
    span.textContent = 'Buscar';
    padreInput.classList.add("twchr_toApi_category_ajax--container");
    padreInput.appendChild(span);
    padreInput.appendChild(twchr_modal);

    span.addEventListener('click',()=>{
        twchr_modal.classList.toggle('active');
        const query = inputTxtCategory.value;
        const appToken = tchr_vars_admin.twitcher_app_token;
        const twch_data_prime = JSON.parse(tchr_vars_admin.twitcher_keys);

        let responseFromApi = getCategorysTwitch(appToken, twch_data_prime['client-id'], query);
        const selectForm = crearElemento("SELECT", 'tchr-select-from-js');
        /*
        responseFromApi.forEach(item => {
            const id = item.id;
            const title = item.title;
            const optionForm = crearElemento("OPTION", "");
            optionForm.textContent = title;
            optionForm.setAttribute("value", id);
            selectForm.appendChild(optionForm);
        });
        twchr_modal.appendChild(selectForm);
        */

        console.log(responseFromApi);


    });  
     
}