<?php

function get_twicth_api($client_id,$client_secret){
  //$url = 'https://id.twitch.tv/oauth2/token?client_id=80i53du4hlrjvnp6yag1lzirzk2kpd&client_secret=oc3y4236g7hh43o6z3y3pd2mzlt3pn&grant_type=client_credentials';
  $url = 'https://id.twitch.tv/oauth2/token?client_id='.$client_id.'&client_secret='.$client_secret.'&grant_type=client_credentials';
  $data = wp_remote_post($url);
  $response = json_decode(wp_remote_retrieve_body($data));
  return $response;
}

function get_twicth_video($app_token, $client_id,$user_id){
  $args = array(
    'headers'=> array(
      'Authorization' => "Bearer $app_token",
      'client-id' => $client_id
    )
  );

  $url = "https://api.twitch.tv/helix/videos?user_id=$user_id";

  $data = wp_remote_get($url,$args);

  $response = json_decode(wp_remote_retrieve_body($data));
  
  return $response;
}


function validateToken($client_id,$client_secret,$code,$redirect){
  $url = "https://id.twitch.tv/oauth2/token";
  $urlecode = 'client_id='.$client_id.'&client_secret='.$client_secret.'&code='.$code.'&grant_type=authorization_code&redirect_uri='.$redirect; 

  $args = array(
    'body'=> $urlecode
  );
  
  $res = wp_remote_post($url,$args);
  //show_dump($url_full);
  return $res;
}

function post_stream($post_id,$tokenValidate,$client_id,$twchr_titulo,$twchr_start_time ,$twchr_category,$twchr_duration){
  $body = array(
    'start_time' => $twchr_start_time,
    'title' => $twchr_titulo,
    'timezone' => 'America/New_York',
    'is_recurring' => true,
    'duration' => $twchr_duration,
    'category_id' => $twchr_category
  );

  $args = array(
    'headers' => array(
      'authorization' => 'Bearer '.$tokenValidate,
      'client-id' => $client_id
    ),
    'body' => $body
  );
  
  $url = "https://api.twitch.tv/helix/schedule/segment?broadcaster_id=817863896";

  //show_dump($args);
  //die();

  $res = wp_remote_post($url,$args);
  $response_body = json_decode(wp_remote_retrieve_body($res));
  $response_response = $res['response'];
  //show_dump($response_body);
  //die();
  // codigo para accionar segun la respuesta de la api
  switch ($response_response['code']) {
    case 200:
        $allData = $response_body->{'data'};
        return array('allData'=>$allData,'status'=>200,'message'=>__('successfully created series','twitcher'));
      //die();
      break;
    //case 401:
    case 401:
      return array("message"=>__('USER TOKEN is invalid, wait a moment, in a few moments you will be redirected to a place where you can get an updated USER TOKEN','twitcher'),'status'=>401,'url_redirect'=>'https://'.$_SERVER['SERVER_NAME'].'/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true','post-id'=>$post_id);
     
      break;
    //case 400:
    case 400:
      $glosa = str_replace('"','`',$response_body->{'message'});
      return array('error'=>$response_body->{'error'},'status'=> $response_body->{'status'},'message' => $glosa,'title'=>$twchr_titulo);
      
      break;
    default:
      break;
  } 
  
} 


function autenticate($api_key, $client_id,$redirect,$scope){
  $twch_data_prime = get_option('twchr_keys') == false ? false : json_decode(get_option('twchr_keys'));
  $token = isset($twch_data_prime->{'user_token'}) ? $twch_data_prime->{'user_token'} : false;
  $token_validate ;
  $token_status ;
  $twch_data_app_token;

  if($token != false){
    $token_validate = twchr_token_validate($token);
    $token_status = isset($token_validate->{'status'}) ? false : true;
    $twch_data_app_token = get_option('twchr_app_token');
  }else{
    $token_status = false;
  }

  
  // IF endpoint validate
  if($token_status){
    $user_login = $token_validate ->{'login'};
    
    $args = array(
        'headers' => array(
            'Authorization' => 'Bearer '.$twch_data_app_token,
            'client-id' => $twch_data_prime->{'client-id'}
        )
    );

    $url = "https://api.twitch.tv/helix/users?login=".$user_login;
    $response = wp_remote_get( $url, $args);
    $body = wp_remote_retrieve_body( $response);

    update_option( 'twchr_data_broadcaster', $body, true);

    $urlRedirection = 'https://'.$_SERVER['SERVER_NAME'].'/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard';
    echo "<script>location.href='$urlRedirection'</script>";
    
  }else{

  $twitchtv = new TwitchTV($api_key, $client_id,urlencode($redirect),$scope);
  $authUrl = $twitchtv->authenticate();

  $msg = "<h3>Usted sera redirigido a Twcht en unos segundos</h3>";
  $script = "<script>location.href ='$authUrl';</script>";
  
  echo $msg;
  //echo $authUrl;
  echo $script;
  }
}

function get_subcribers($app_token, $client_id){
  $args = array(
    'headers' => array(
      'Authorization' => 'Bearer '.$app_token,
      'client-id' => $client_id 
    )
  );

  $get =  wp_remote_get( 'https://api.twitch.tv/helix/eventsub/subscriptions', $args);

  $response = wp_remote_retrieve_body( $get);

  return json_decode($response);
}