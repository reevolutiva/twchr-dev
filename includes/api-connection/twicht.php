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



function post_stream(){
  $body = array(
    'start_time' => '2021-07-01T18:00:00Z',
    'title' => 'TwitchDev Monthly Update',
    'timezone' => 'America/New_York',
    'is_recurring' => false,
    'duration' => "60",
    'category_id' => "509670"
  );

  $args = array(
    'headers' => array(
      'authorization' => 'Bearer g24wyvgtwvwud0k48h33e7fbssixpg',
      'client-id' => '80i53du4hlrjvnp6yag1lzirzk2kpd'
    ),
    'body' => $body
  );
  
  $url = "https://api.twitch.tv/helix/schedule/segment?broadcaster_id=780848608";


  $res = wp_remote_post($url,$args);
  $response = wp_remote_retrieve_body($res);

  var_dump($response);
  
} 

function autenticate($api_key, $client_id,$redirect){
  //$api_key = 'lvlu0kmiervxate3yqfhppsh4d2kol';
  //$client_id = 'mtxa43qjzhqij6793d1l095a5hwwcd';
  //$redirect = 'https://egosapiens.local/ego_stream/sadasdsadsad/';
  $twitchtv = new TwitchTV($api_key, $client_id,urlencode($redirect),array('channel_editor'));
  $authUrl = $twitchtv->authenticate();
  if(!function_exists('wp_redirect'))
           {
               include_once( ABSPATH . 'wp-includes/pluggable.php' );
           }
  wp_redirect($authUrl);
}