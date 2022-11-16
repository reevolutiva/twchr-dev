<?php 
$twch_data_prime = json_decode(db_to_front('twchr_keys')['last_result'][0]->option_value);

$client_id = $twch_data_prime->{'client-id'};
$client_secret = $twch_data_prime->{'client-secret'};
$code = $twch_data_prime->{'code'};
$scope = $twch_data_prime->{'scope'};

//var_dump($twch_data_prime);
//$token = get_twicth_api('80i53du4hlrjvnp6yag1lzirzk2kpd','mtxa43qjzhqij6793d1l095a5hwwcd');

//$list_videos = get_twicth_video($token)->data;
				
//crearPostParaTwitch($list_videos);
//$validateTokenObject = validateToken($client_id, $client_secret, $code );
//saveValidateToken($validateTokenObject);

//$twch_data_vericate = json_decode(db_to_front('twchr_keys_validate')['last_result'][0]->option_value);

//$validateToken = $twch_data_vericate->{'validate-token'};

/*TODO Activar post_stream en el hook de insert o update de cpt streaming*/
//post_stream($validateToken,$client_id);

//var_dump($validateTokenObject);

/*
    Configuracion inicial
    ---------------------

    twicher_twicht_init_api($twtch_client-id, $twtch_client-secret)
    Cuando instalo el plugin y seteo client-id y client-secrent
    Ejecuto un script que solicita un code y lo valida y retorna un objeto
    con accses_token, refresh token y exiperes, instanteamente lo guardo en BD

    
    twitcher_refresh_api()
    Usar acces-token

    Response Codes

    if(!status === 200) 
     twitcher_refresh_api()
        try{
            Enviar refrech token con lo demas a https://id.twitch.tv/oauth2/token
        }catch{
            if(400 or 401){
                twicher_twicht_init_api()
            }
        }

    Code	Meaning
    200	Stream schedule segment created successfully.
    400	Request was invalid.
    401	Authorization failed.

    Usar save_post global
        if(post -> is cpt)
            code

*/