<style>
    .twchr_todo_list ul li label{
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    .twchr_todo_list ul li.active span{
        background-color: purple;
        color: #fff;
    }
    .twchr_todo_list ul li span{
        display: block;
        font-size: 2rem;
        border-radius: 50px;
        width: 4rem;
        height: 4rem;
        background-color: #fff;
        display: grid;
        place-items: center;
        margin-right: 2rem;


    }

    
</style>
<div class="twchr-container">
    <div class="twchr_todo_list">
        <ul>
        </ul>
    </div>
</div>

<script>
    const  array = [  "Agendar Stream",
                "Notificar por Discord",
                "Notificar por Faceboock",
                "Notificar por Twiter",
                "Iniciar Transmicion",
                "Insertar Live en un post",
                "Descargar Live",
                "Importar Live como Streming",
                "Subir Streming a YouTube",
                "Cambiar fuente de Streaming de Twitch a YouTube"
            ];
    const todo_list = document.querySelector(".twchr_todo_list ul");
    

    // Render todo_list
    array.forEach((value, key) => {
        const li = `  <li class=""> <label> <span>${key}</span> <h3>${value}</h3> <input type="checkbox" name="twchr_todo_list" > </label></li>`;
        todo_list.innerHTML = todo_list.innerHTML + li;
    });

    const items_list = todo_list.querySelectorAll("input");
    let todoListUpdateState = [];
    [...todo_list.querySelectorAll("li")].forEach( (li, index) => {
        li.addEventListener('click',(e)=>{
            if(items_list[index].checked == true){
                li.classList.add("active");
                if(!todoListUpdateState.includes(index)){
                    todoListUpdateState.push(index);
                }
            }else{
                li.classList.remove("active");
            }

            [...todo_list.querySelectorAll("li")].forEach( (li_1, index_1) => {
                if(items_list[index_1].checked == false){
                    //const pos = todoListUpdateState.indexOf(li.querySelector("span").innerText);
                    console.log(index_1);
            
                }
                
            });
            
            console.log(todoListUpdateState);  
        });
    }
    );
    
</script>