<?php
// twtchr_twitch_schedule
// Actualiza los schedules segment
function  twtchr_twitch_schedule_segment_update($post_id,$user_token,$client_id,$twchr_titulo,$twchr_start_time ,$twchr_category,$twchr_duration){
  $body = array(
    'start_time' => $twchr_start_time,
    'duration' => $twchr_duration,
    'category_id' => $twchr_category,
    'title' => $twchr_titulo,
    'is_canceled' => true,
    'timezone' => 'America/New_York'
  );

  $args = array(
    'headers' => array(
      'authorization' => 'Bearer '.$user_token,
      'client-id' => $client_id,
      'Content-Type' => 'application/json'
    ),
    'body' => $body
  );

  $data_broadcaster_raw = get_option( 'twchr_data_broadcaster', false ) == false ?  false :  json_decode(get_option( 'twchr_data_broadcaster'));
  $broadcaster_id = $data_broadcaster_raw->{'data'}[0]->{'id'};
  
  $url = " https://api.twitch.tv/helix/schedule/segment/?broadcaster_id=".$broadcaster_id;

  $res = wp_remote_post($url,$args);
  $response_body = json_decode(wp_remote_retrieve_body($res));
  $response_response = $res['response'];

  // codigo para accionar segun la respuesta de la api
  switch ($response_response['code']) {
    case 200:
        $allData = $response_body->{'data'};
        return array('allData'=>$allData,'status'=>200,'message'=>__('Successfully updated serie.','twitcher'));
      //die();
      break;
    //case 401:
    case 401:
      return array("message"=>__('USER TOKEN is invalid, wait a moment, in a few moments you will be redirected to a place where you can get an updated USER TOKEN','twitcher'),'status'=>401,'url_redirect'=>'https://'.TWCHR_HOME_URL.'/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true','post-id'=>$post_id);
     
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
// twtchr_twitch_schedule
// eliminar los schedules segment
/**
 * Eliminar un schedule segment
 *
 * @param [type] $post_id
 * @param [type] $user_token
 * @param [type] $client_id
 * @param [type] $twchr_titulo
 * @param [type] $schedule_id
 * @return void
 */
function twtchr_twitch_schedule_segment_delete($schedule_id, $twchr_titulo = false, $post_id = false){

  // Credentials
  $twch_data_prime = get_option('twchr_keys') == false ? false : json_decode(get_option('twchr_keys'));
  $client_id = $twch_data_prime->{'client-id'};
  $user_token = $twch_data_prime->{'user_token'};
  $data_broadcaster_raw = get_option( 'twchr_data_broadcaster', false ) == false ?  false :  json_decode(get_option( 'twchr_data_broadcaster'));
  $broadcaster_id = $data_broadcaster_raw->{'data'}[0]->{'id'};

  $args = array(
    'headers' => array(
      'authorization' => 'Bearer '.$user_token,
      'client-id' => $client_id,
      'Content-Type' => 'application/json'
    ),
    'method' => 'DELETE'
  );

  
  $url = "https://api.twitch.tv/helix/schedule/segment?broadcaster_id=".$broadcaster_id."&id=".$schedule_id;

  $res = wp_remote_post($url,$args);
  $response_body = json_decode(wp_remote_retrieve_body($res));
  $response_response = $res['response'];

  if($post_id != false && $twchr_titulo != false){
  // codigo para accionar segun la respuesta de la api
    switch ($response_response['code']) {
      case 204:
          $allData = $response_body->{'data'};
          return array('allData'=>$allData,'status'=>204,'message'=>__('Successfully updated serie.','twitcher'));
        //die();
        break;
      //case 401:
      case 401:
        return array("message"=>__('USER TOKEN is invalid, wait a moment, in a few moments you will be redirected to a place where you can get an updated USER TOKEN','twitcher'),'status'=>401,'url_redirect'=>'https://'.TWCHR_HOME_URL.'/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true','post-id'=>$post_id);
      
        break;
      //case 400:
      case 400:
        $glosa = str_replace('"','`',$response_body->{'message'});
        return array('error'=>$response_body->{'error'},'status'=> $response_body->{'status'},'message' => $glosa,'title'=>$twchr_titulo);
        
        break;
      default:
        break;
    }
  }else{
    return $response_body;
  }
}

function  twtchr_twitch_schedule_segment_get($schedule_id = false){
  $twch_data_prime = get_option('twchr_keys') == false ? false : json_decode(get_option('twchr_keys'));
  $client_id = $twch_data_prime->{'client-id'};
  $user_token = $twch_data_prime->{'user_token'};
 
  $args = array(
    'headers' => array(
      'authorization' => 'Bearer '.$user_token,
      'client-id' => $client_id
    )
  );

  $data_broadcaster_raw = get_option( 'twchr_data_broadcaster', false ) == false ?  false :  json_decode(get_option( 'twchr_data_broadcaster'));
  $broadcaster_id = $data_broadcaster_raw->{'data'}[0]->{'id'};
  

  if($schedule_id == false) {
    $url = "https://api.twitch.tv/helix/schedule?broadcaster_id=".$broadcaster_id;
  }else{
    $url = "https://api.twitch.tv/helix/schedule?broadcaster_id=".$broadcaster_id."&id=".$schedule_id;
  }

  

  $res = wp_remote_get($url,$args);
  $response_body = json_decode(wp_remote_retrieve_body($res));
  $response_response = $res['response'];
  
  if( isset($response_body->{'data'}->{'segments'})) {
    $data = $response_body->{'data'}->{'segments'};
    return $data;
  }else{
   
    if(isset($response_body->{'error'})){
            //var_dump($schedules_twitch);
            twchr_twitch_autentication_error_handdler($response_body->{'error'}, $response_body->{'message'});
      }
  }
}
// Create twitch schedule segment twtchr_twitch_schedule_segment_create
function twtchr_twitch_schedule_segment_create($post_id,$twchr_titulo,$twchr_start_time ,$twchr_category,$twchr_duration,$is_recurring = true){

  //GET CREDENTIALS
  $twch_data_prime = json_decode(get_option( 'twchr_keys', false ));
  $tokenValidate = $twch_data_prime->{'user_token'};
  $client_id = $twch_data_prime->{'client-id'};

   $data_broadcaster_raw = get_option( 'twchr_data_broadcaster', false ) == false ?  false :  json_decode(get_option( 'twchr_data_broadcaster'));
  $broadcaster_id = $data_broadcaster_raw->{'data'}[0]->{'id'};

  $body = array(
    'start_time' => $twchr_start_time,
    'title' => $twchr_titulo,
    'timezone' => 'America/New_York',
    'is_recurring' => $is_recurring,
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
  
  $url = "https://api.twitch.tv/helix/schedule/segment?broadcaster_id=".$broadcaster_id;

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
      return array("message"=>__('USER TOKEN is invalid, wait a moment, in a few moments you will be redirected to a place where you can get an updated USER TOKEN','twitcher'),'status'=>401,'url_redirect'=>'https://'.TWCHR_HOME_URL.'/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true','post-id'=>$post_id);
     
      break;
    //case 400:
    case 400:
      $glosa = str_replace('"','`',$response_body->{'message'});
      return array('error'=>$response_body->{'error'},'status'=> $response_body->{'status'},'message' => $glosa,'title'=>$twchr_titulo);
      
      break;
    default:
      return $response_body;
      break;
  } 
  
}
//
//twchr_twitch_video 
//Obtiene un array de videos
function twchr_twitch_video_get($app_token, $client_id,$user_id){ 
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

//Pregunta a Twitcht api por un video ID especifico si ese video existe retonra 200 y si no existe 404

function twchr_twitch_video_exist($video_id,$token,$client_id){
  if(isset($video_id) && isset($token) && isset($client_id)){
      $url = 'https://api.twitch.tv/helix/videos?id='.$video_id;
      $args = array(
          'headers'=> array(
                  "Authorization" => "Bearer $token",
                  "client-id" => $client_id
          )
      );
      $get = wp_remote_get($url, $args);
      $response = wp_remote_retrieve_body($get);
      $response = json_decode($response);
      
      if($response->data){
          return 200;
      }else{
          return 404;
      }
  }
}

//twtchr_twitch_subscribers
//twtchr_twitch_subscribers_get 
function twtchr_twitch_subscribers_get($user_token, $client_id){
  $args = array(
    'headers' => array(
      'Authorization' => 'Bearer '.$user_token,
      'client-id' => $client_id 
    )
  );
  $data_broadcaster_raw = get_option( 'twchr_data_broadcaster', false ) == false ?  false :  json_decode(get_option( 'twchr_data_broadcaster'));
  $broadcaster_id = $data_broadcaster_raw->{'data'}[0]->{'id'};
  
  $get =  wp_remote_get( 'https://api.twitch.tv/helix/subscriptions?broadcaster_id='.$broadcaster_id, $args);

  $response = wp_remote_retrieve_body( $get);

  return json_decode($response);
}

//twtchr_twitch_categories
//twtchr_twitch_categories_get 
function twtchr_twitch_categories_get($app_token, $client_id,$query){
  $url = "https://api.twitch.tv/helix/search/categories?query=$query";
  $args = array(
      'headers'=> array(
        'Authorization' => "Bearer $app_token",
        'client-id' => $client_id
      )
  );
              
    $data = wp_remote_get($url,$args);

    $response = json_decode(wp_remote_retrieve_body($data));

    return $response;
}

//twtchr_twitch_users
//twtchr_twitch_users_get 
function twtchr_twitch_users_get_followers($app_token, $client_id, $user_id){
  $url = "https://api.twitch.tv/helix/users/follows?to_id=".$user_id;

  $args = array(
    'headers'=> array(
      'Authorization' => "Bearer $app_token",
      'client-id' => $client_id
    )
);

$get = wp_remote_get($url, $args);
$response = wp_remote_retrieve_body($get);
$object = json_decode($response);

return $object;


}
//twtchr_twitch_moderators
//twtchr_twitch_moderators_get 
function twtchr_twitch_moderators_get($app_token, $client_id, $user_id){
  $url = "https://api.twitch.tv/helix/moderation/moderators?broadcaster_id=".$user_id;
    $args = array(
      'headers'=> array(
        'Authorization' => "Bearer $app_token",
        'client-id' => $client_id
      )
    );

    $get = wp_remote_get($url, $args);
    
    $response = wp_remote_retrieve_body($get);
    var_dump($response);
    
    $object = json_decode($response);

    return $object;


}

// twtchr_twitch_clips
// twtchr_twitch_clips_get. 
function twtchr_twitch_clips_get($app_token, $client_id, $user_id){
    $url = "https://api.twitch.tv/helix/clips?broadcaster_id=".$user_id;

    $args = array(
      'headers'=> array(
        'Authorization' => "Bearer $app_token",
        'client-id' => $client_id
      )
    );

    $get = wp_remote_get($url, $args);
    $response = wp_remote_retrieve_body($get);
    $object = json_decode($response);

    return $object;


}

//
//Funciones de autenticación//
// Obtener token
// twtchr_twitch_autenticate_apptoken_get 
function twtchr_twitch_autenticate_apptoken_get($client_id,$client_secret){
  $url = 'https://id.twitch.tv/oauth2/token?client_id='.$client_id.'&client_secret='.$client_secret.'&grant_type=client_credentials';
  $data = wp_remote_post($url);
  $response = json_decode(wp_remote_retrieve_body($data));
  return $response;
}
//Validar Token
//FIXME twtchr_twitch_autenticate_usertoken_validate
function twchr_validateToken($client_id,$client_secret,$code,$redirect){
  $url = "https://id.twitch.tv/oauth2/token";
  $urlecode = 'client_id='.$client_id.'&client_secret='.$client_secret.'&code='.$code.'&grant_type=authorization_code&redirect_uri='.$redirect; 

  $args = array(
    'body'=> $urlecode
  );
  
  $res = wp_remote_post($url,$args);
  //show_dump($url_full);
  return $res;
}
//twtchr_twitch_autenticate 
function twtchr_twitch_autenticate($api_key, $client_id,$redirect,$scope){
  $twch_data_prime = get_option('twchr_keys') == false ? false : json_decode(get_option('twchr_keys'));
  $token = isset($twch_data_prime->{'user_token'}) ? $twch_data_prime->{'user_token'} : false;
  $token_validate = '';
  $token_status = '';
  $twch_data_app_token = '';

  if($token != false){
    $token_validate = twchr_twitch_token_validate($token);
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

    if(get_option('twchr_data_broadcaster') == false){
      add_option( 'twchr_data_broadcaster', $body, '', true );
    }else{
      update_option( 'twchr_data_broadcaster', $body, true);
    }
    
    add_option('twchr_log', 0);

    $urlRedirection = TWCHR_HOME_URL.'/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard';
    echo "<script>location.href='$urlRedirection'</script>";
    
    
  }else{

    $twitchtv = new TwitchTV($api_key, $client_id,urlencode($redirect),$scope);
    $authUrl = $twitchtv->authenticate();


    $msg = '<h3>Usted sera redirigido a Twcht en unos segundos</h3>';
    $script = '<script>location.href ="'.$authUrl.'";</script>';
    
    echo esc_html($msg);
    echo $script;
    

  }
}

function twchr_twitch_token_validate($token){
  $url = 'https://id.twitch.tv/oauth2/validate';
  $args = array(
      'headers' => array(
          'Authorization' => 'Bearer '.$token
      )
  );

  $response = wp_remote_get( $url, $args);
  $body = wp_remote_retrieve_body($response);
  return json_decode($body);
}

function twchr_twitch_autentication_error_handdler($error_code , $msg){
  echo "<script>
    alert('Error: ".$error_code."'); 
    alert('message: ".$msg."');
    alert('".__("You will be redirected to the authentication page in a few seconds.",'twitcher')."');
    location.href = '".TWCHR_ADMIN_URL."edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true';
  </script>";
}
