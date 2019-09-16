<?php

header('Content-Type: application/json');

require_once dirname(__FILE__).'/../../3rdparty/SpotifyWebAPI/Session.php';
require_once dirname(__FILE__).'/../../3rdparty/SpotifyWebAPI/Request.php';
require_once dirname(__FILE__).'/../../3rdparty/SpotifyWebAPI/SpotifyWebAPI.php';
require_once dirname(__FILE__).'/../../3rdparty/SpotifyWebAPI/SpotifyWebAPIException.php';
require_once dirname(__FILE__).'/../../3rdparty/SpotifyWebAPI/SpotifyWebAPIAuthException.php';

try {
  
	require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
  
	include_file('core', 'authentification', 'php');

    if (init('action') == 'account') {
    
   	  $apikey = config::byKey( 'api', 'spotify');
      log::add('spotify', 'debug', '### APIKEY '.$apikey.' ###');   
      
      $clientid = config::byKey('clientid', 'spotify');      
      log::add('spotify', 'debug', '### CLIENT ID '.$clientid.' ###');   
      
      $clientsecret = config::byKey('clientsecret', 'spotify');
      log::add('spotify', 'debug', '### CLIENT SECRET '.$clientsecret.' ###');   
    
      $protocol = config::byKey('protocol', 'spotify');      
      log::add('spotify', 'debug', '### PROTOCOL '.$protocol.' ###');   
      
      if( $protocol == 'HTTP' ) {
        $url = network::getNetworkAccess('internal');
        log::add('spotify', 'debug', '### URL '.$url.' ###');   
      } else {
        $url = network::getNetworkAccess('external');
        log::add('spotify', 'debug', '### URL '.$url.' ###');   
      }
      
      $itemcallback = $url.'/plugins/spotify/core/ajax/spotify.ajax.php?api=#APIKEY#&type=spotify&action=item&id=#ID#&item_id=#ITEM_ID#&item_album=#ITEM_ALBUM#&item_title=#ITEM_TITLE#&item_artist=#ITEM_ARTIST#&item_image=#ITEM_IMAGE#&context_type=#CONTEXT_TYPE#&context_uri=#CONTEXT_URI#';
      log::add('spotify', 'debug', '### ITEM CALLBACK '.$itemcallback.' ###');   
      
      $devicecallback = $url.'/plugins/spotify/core/ajax/spotify.ajax.php?api=#APIKEY#&type=spotify&action=device&id=#ID#&device_id=#DEVICE_ID#&device_name=#DEVICE_NAME#&device_type=#DEVICE_TYPE#&device_volume=#DEVICE_VOLUME#&device_is_active=#DEVICE_IS_ACTIVE#';
      log::add('spotify', 'debug', '### DEVICE CALLBACK '.$devicecallback.' ###');   
            
      $playlistcallback = $url.'/plugins/spotify/core/ajax/spotify.ajax.php?api=#APIKEY#&type=spotify&action=playlist&id=#ID#&playlist_id=#PLAYLIST_ID#&playlist_name=#PLAYLIST_NAME#';
      log::add('spotify', 'debug', '### PLAYLIST CALLBACK '.$playlistcallback.' ###');   
            
      $shufflecallback = $url.'/plugins/spotify/core/ajax/spotify.ajax.php?api=#APIKEY#&id=#ID#&state=#STATE#&action=shuffle';
      log::add('spotify', 'debug', '### SHUFFLE CALLBACK '.$shufflecallback.' ###'); 
      
      $result['apikey'] = $apikey;
      $result['clientid'] = $clientid;
      $result['clientsecret'] = $clientsecret;
      
      $_eq = eqLogic::byType('spotify', true);
      $length = count($_eq);
      log::add('spotify', 'debug', '### LENGTH '.$length.' ###'); 
      
	  $_cmd = '[';
      $_sep = '';
      
      foreach ($_eq as $eq) {
        
        log::add('spotify', 'debug', '### CONFIG '. $eq->getId() .' ###'); 
      	$_cmd = $_cmd . '{"token":"' . $eq->getConfiguration('refresh') . '","id":"' . $eq->getId() . '"}' . $_sep;
        $_sep = ',';
        
      }
      
      $_cmd = $_cmd . ']';      
      log::add('spotify', 'debug', '### COMMANDS '.$_cmd.' ###');
      
      $result['commands'] = $_cmd;
      $result['itemcallback'] = $itemcallback;
      $result['devicecallback'] = $devicecallback;
      $result['playlistcallback'] = $playlistcallback;      
      $result['shufflecallback'] = $shufflecallback;  
      
      ajax::success($result);
      
    }  
      
	if (init('action') == 'shuffle') {
      
      $id = init('id');
      log::add('spotify', 'debug', '### ID '.$id.' ###'); 
      $cmd = spotify::byId($id); 
      
      if( init('state') != '') {
      	$state = init('state');
      } else {
        $state = 'false';
      }      
      log::add('spotify', 'debug', '### STATE '.$state.' ###'); 
      $cmd->checkAndUpdateCmd('shuffling', $state);
          
    }
  
  	if (init('action') == 'device') {
      
      $id = init('id');
      log::add('spotify', 'debug', '### ID '.$id.' ###'); 
      $cmd = spotify::byId($id); 
                              
      $device_id = init('device_id');
      log::add('spotify', 'debug', '### DEVICE ID '.$device_id.' ###'); 
      $_device_id = explode("|", $device_id);
      
      $device_name = init('device_name');
      log::add('spotify', 'debug', '### DEVICE NAME '.$device_name.' ###'); 
      $_device_name = explode("|", $device_name);
      
      $device_volume = init('device_volume');
      log::add('spotify', 'debug', '### DEVICE VOLUME '.$device_volume.' ###'); 
      $_device_volume = explode("|", $device_volume);
      
      $device_is_active = init('device_is_active');
      log::add('spotify', 'debug', '### DEVICE IS ACTIVE '.$device_is_active.' ###'); 
 	  $_device_is_active = explode("|", $device_is_active);
      
      $device_type = init('device_type');
      log::add('spotify', 'debug', '### DEVICE TYPE '.$device_type.' ###'); 
 	  $_device_type = explode("|", $device_type);
      
      $length = count($_device_id);
      log::add('spotify', 'debug', '### LENGTH '.$length.' ###'); 
      
      $separator = '';
      $list = '';
      $current_id = 'N/A';
      $current_name = 'N/A';
      $current_volume = '0';
      $current_is_active = 'false';
      
	  for ( $i = 0; $i < $length; $i++) {   
        $device_name = str_replace(' ', '_', $_device_name[$i]);
        $list = $list . $separator . $_device_id[$i] . '|' . $device_name;
        $separator = ';';        
        if($_device_is_active[$i]==='true') {
          $current_id = $_device_id[$i];
          $current_name = $device_name;
          $current_volume = $_device_volume[$i];
          $current_is_active = $_device_is_active[$i];
        }
	  }
      
      log::add('spotify', 'info', '### LIST '.$list.' ###'); 
      
      log::add('spotify', 'info', '### CURRENT DEVICE ID '.$current_id.' ###'); 
      $cmd->checkAndUpdateCmd('device_id', $current_id);
      
      log::add('spotify', 'info', '### CURRENT DEVICE NAME '.$current_name.' ###'); 
      $cmd->checkAndUpdateCmd('device_name', $current_name);
      
      log::add('spotify', 'info', '### CURRENT DEVICE VOLUME '.$current_volume.' ###'); 
      $cmd->checkAndUpdateCmd('device_volume', $current_volume);
      
      log::add('spotify', 'info', '### CURRENT DEVICE IS ACTIVE '.$current_is_active.' ###'); 
      $cmd->checkAndUpdateCmd('device_is_active', $current_is_active);
      
      $device_id_set = $cmd->getCmd(null, 'device_id_set');
      $device_id_set->setConfiguration('listValue',$list);
      $device_id_set->save();
      $cmd->refreshWidget();       
      
    }
  
  	if (init('action') == 'playlist') {
      
      $id = init('id');
      log::add('spotify', 'debug', '### ID '.$id.' ###'); 
      $cmd = spotify::byId($id); 
                              
      $playlist_id = init('playlist_id');
      log::add('spotify', 'debug', '### PLAYLIST ID '.$playlist_id.' ###'); 
      $_playlist_id = explode("|", $playlist_id);
      
      $playlist_name = init('playlist_name');
      log::add('spotify', 'debug', '### PLAYLIST NAME '.$playlist_name.' ###'); 
      $_playlist_name = explode("|", $playlist_name);
      
      $length = count($_playlist_id);
      log::add('spotify', 'debug', '### LENGTH '.$length.' ###'); 
      
      $separator = '';
      $list = "";
      
	  for ( $i = 0; $i < $length; $i++) {        
  	  	$list = $list . $separator . $_playlist_id[$i] . '|' . $_playlist_name[$i];
        $separator = ';';  
	  }
      
      log::add('spotify', 'info', '### LIST '.$list.' ###'); 
      
      $playlist_id_set = $cmd->getCmd(null, 'playlist_id_set');
      $playlist_id_set->setConfiguration('listValue',$list);
      $playlist_id_set->save();
      $cmd->refreshWidget();       
      
    }
  
    if (init('action') == 'item') {
      
      $id = init('id');
      log::add('spotify', 'info', '### ID '.$id.' ###'); 
      $cmd = spotify::byId($id); 
      
      if( init('item_id') != '') {
      	$playing = 'true';
      } else {
        $playing = 'false';
      }      
      log::add('spotify', 'info', '### PLAYING '.$playing.' ###'); 
      $cmd->checkAndUpdateCmd('playing', $playing);
      
      if( init('item_id') != '') {
      	$item_id = init('item_id');
      } else {
        $item_id = 'N/A';
      }      
      log::add('spotify', 'info', '### ITEM ID '.$item_id.' ###'); 
      $cmd->checkAndUpdateCmd('item_id', $item_id);
      
      if( init('item_album') != '') {
      	$item_album = init('item_album');
      } else {
        $item_album = 'N/A';
      }
      log::add('spotify', 'info', '### ITEM ALBUM '.$item_album.' ###'); 
      $cmd->checkAndUpdateCmd('item_album', $item_album);
      
      if( init('item_title') != '') {
        $item_title = init('item_title');
      } else {
        $item_title = 'N/A';
      }
      log::add('spotify', 'info', '### ITEM TITLE '.$item_title.' ###'); 
      $cmd->checkAndUpdateCmd('item_title', $item_title);
      
      if( init('item_artist') != '') {
        $item_artist = init('item_artist');
      } else {
        $item_artist = 'N/A';
      }
      log::add('spotify', 'info', '### ITEM ARTIST '.$item_artist.' ###'); 
      $cmd->checkAndUpdateCmd('item_artist', $item_artist);
      
      $is_playing = init('is_playing');
      log::add('spotify', 'info', '### IS PLAYING '.$is_playing.' ###'); 
      $cmd->checkAndUpdateCmd('is_playing', $is_playing);      
      
      if( init('item_image') != '' ) {
        $item_image = init('item_image');
      } else {
         $item_image = network::getNetworkAccess('external', 'proto:127.0.0.1:port:comp').'/plugins/spotify/ressources/spotify.png';
      }
      log::add('spotify', 'info', '### ITEM IMAGE '.$item_image.' ###'); 
      $cmd->checkAndUpdateCmd('item_image', $item_image);
        
      $context_type = init('context_type');
      log::add('spotify', 'info', '### CONTEXT TYPE '.$context_type.' ###'); 
      
      $context_uri = init('context_uri');
      log::add('spotify', 'info', '### CONTEXT URI '.$context_uri.' ###'); 
      
      // if( $context_type !== 'playlist' ) {
        
        // $_uri = strstr( $context_uri, 'playlist:');
        // $_playlist = explode(":", $_uri,10);
        // $_playlist_id = $_playlist[1];
        
        $_playlist_id = $context_uri;
        
        log::add('spotify', 'info', '### PLAYLIST ID '.$_playlist_id.' ###');     
        $cmd->checkAndUpdateCmd('playlist_id', $_playlist_id);   
        
        $_playlist_id_set = $cmd->getCmd(null, 'playlist_id_set');
        $_list = $_playlist_id_set->getConfiguration('listValue');
        log::add('spotify', 'info', '### LIST '.$_list.' ###');   
        
        $valeurs = explode(";", $_list,50);
	  	$length = count($valeurs);
        $content = 'N/A';
        
        for ( $i = 0; $i < $length; $i++) {        
  	  		$_valeur = explode("|", $valeurs[$i], 2);
            log::add('spotify', 'debug', '--- PLAYLIST PARSE = ' .$_valeur[1] . ' ---'); 
            if($_playlist_id===$_valeur[0]) {
            	log::add('spotify', 'debug', '--- PLAYLIST FOUND = ' . $_valeur[1] . ' ---'); 
              	$content = $_valeur[1];
                break;
            }
	  	}
        
        log::add('spotify', 'info', '### PLAYLIST NAME '.$content.' ###');    
        $cmd->checkAndUpdateCmd('playlist_name', $content);  
        
      // }
      // else 
      // {
         //$cmd->checkAndUpdateCmd('playlist_id', '');        
         // $cmd->checkAndUpdateCmd('playlist_name', 'N/A');    
      // }
      
      if( $context_type === 'artist' ) {
        $_uri = strstr( $context_uri, 'artist:');
        $_artist = explode(":", $_uri,10);
        $_artist_id = $_artist[1];
        log::add('spotify', 'info', '### ARTIST ID '.$_artist_id.' ###');     
        //$cmd->checkAndUpdateCmd('artist_id', $_artist_id);            
      }
      else 
      {
         //$cmd->checkAndUpdateCmd('artist_id', '');          
      }
      
      $cmd->refreshWidget();
      
    }
  
  	if (init('action') == 'get_authorize_url') {
  
      	$callback = init ("callback");
      	log::add('spotify', 'debug', '### CALLBACK '.$callback.' ###');   
      
    	$clientid = config::byKey('clientid', 'spotify');      
      	log::add('spotify', 'debug', '### CLIENT ID '.$clientid.' ###');   
      
      	$clientsecret = config::byKey('clientsecret', 'spotify');
      	log::add('spotify', 'debug', '### CLIENT SECRET '.$clientsecret.' ###');   
      
      	$session = new SpotifyWebAPI\Session( $clientid, $clientsecret, $callback);
          
        $options = [
			'scope' => [
                'user-library-modify',
				'user-library-read',
				'app-remote-control',
				'streaming',
				'playlist-read-private',
				'playlist-read-collaborative',
				'playlist-modify-public',
				'playlist-modify-private',
                'user-follow-modify',
				'user-follow-read',
                'user-read-recently-played',
				'user-top-read',
                'user-read-email',
          		'user-read-private',
                'user-read-currently-playing',
				'user-read-playback-state',
				'user-modify-playback-state'
        	]
      	];
          
        $url = $session->getAuthorizeUrl($options);
        log::add('spotify', 'debug', '### AUTHORIZE URL '.$url.' ###');          
          
    	ajax::success($url);
      
    }
  
  	if (init('action') == 'get_tokens') {
  
      	$callback = init ("callback");
      	log::add('spotify', 'debug', '### CALLBACK '.$callback.' ###');   
      
    	$clientid = config::byKey('clientid', 'spotify');      
      	log::add('spotify', 'debug', '### CLIENT ID '.$clientid.' ###');   
      
      	$clientsecret = config::byKey('clientsecret', 'spotify');
      	log::add('spotify', 'debug', '### CLIENT SECRET '.$clientsecret.' ###');   
      
      	$code = init('code');
      	log::add('spotify', 'debug', '### CODE '.$code.' ###');  
      
      	$session = new SpotifyWebAPI\Session( $clientid, $clientsecret, $callback);
          
        $session->requestAccessToken($code);

		$accessToken = $session->getAccessToken();
      	log::add('spotify', 'debug', '### ACCESS TOKEN '.$accessToken.' ###');  
      
		$refreshToken = $session->getRefreshToken();
      	log::add('spotify', 'debug', '### REFRESH TOKEN '.$refreshToken.' ###');  
         
     	$result['accessToken'] = $accessToken;
      	$result['refreshToken'] = $refreshToken;
      
    	ajax::success( $result );
      
    }
  
    throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));

} catch (Exception $e) {
  
	ajax::error(displayExeption($e), $e->getCode());
  
}

?>