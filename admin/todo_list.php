<?php 
    $array = [  "Agendar Stream",
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
?>
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

    .twchr_todo_list ul li label input{
       display: none;
    }
</style>
<div class="twchr-container">
    <div class="twchr_todo_list">
        <ul>
        <?php foreach($array as $key => $item): ?>
            <li class=""> <label> <span><?php echo $key?></span> <h3><?php echo $item?></h3> <input type="checkbox" name="twchr_todo_list" > </label></li>
        <?php endforeach;?>
        </ul>
    </div>
</div>

<script>
    const todo_list = document.querySelector(".twchr_todo_list ul");
    const items_list = todo_list.querySelectorAll("input");

    [...todo_list.querySelectorAll("li")].forEach( (li, index) => {
        li.addEventListener('click',(e)=>{
            //console.log(e.target);
            items_list[index].checked == true ? li.classList.add("active") : li.classList.remove("active");
        });
    }
    );
</script>