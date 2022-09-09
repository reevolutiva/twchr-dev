<?php

function get_twicth_api($client_id,$client_secret){
  //$url = 'https://id.twitch.tv/oauth2/token?client_id=80i53du4hlrjvnp6yag1lzirzk2kpd&client_secret=oc3y4236g7hh43o6z3y3pd2mzlt3pn&grant_type=client_credentials';
  $url = 'https://id.twitch.tv/oauth2/token?client_id='.$client_id.'&client_secret='.$client_secret.'&grant_type=client_credentials';
  $data = wp_remote_post($url,$arrg);
  $response = json_decode(wp_remote_retrieve_body($data));
  return $response;
}

function get_twicth_video($data){
  $args = array(
    'headers'=> array(
      'Authorization' => "Bearer $data->access_token",
      'client-id' => '80i53du4hlrjvnp6yag1lzirzk2kpd'
    )
  );

  $url = "https://api.twitch.tv/helix/videos?user_id=780848608";

  $data = wp_remote_get($url,$args);

  $response = json_decode(wp_remote_retrieve_body($data));
  return $response;
}

