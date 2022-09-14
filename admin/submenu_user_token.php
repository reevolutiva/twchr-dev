<?php 
$twch_data_prime = json_decode(db_to_front('twitcher_keys')['last_result'][0]->option_value);

$client_id = $twch_data_prime->{'client-id'};
$apikey = $twch_data_prime->{'api-key'};
$code = $twch_data_prime->{'user_token'};
$client_secret = "mtxa43qjzhqij6793d1l095a5hwwcd";

var_dump($twch_data_prime);
//$token = get_twicth_api('80i53du4hlrjvnp6yag1lzirzk2kpd','mtxa43qjzhqij6793d1l095a5hwwcd');

//$list_videos = get_twicth_video($token)->data;
				
//crearPostParaTwitch($list_videos);

validateToken($client_id,$apikey,$code );

post_stream('8swzh3bywwn7jx0nhgc1aimpkgt9rh',$client_id);

//var_dump($token);