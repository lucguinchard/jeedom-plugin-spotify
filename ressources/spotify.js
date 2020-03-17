var SpotifyWebApi = require('spotify-web-api-node');

var http = require('http');
var https = require('https');

var url = process.argv[2];
var	debug = process.argv[3];
var protocol = process.argv[4];

var device_id = Array()
var device_is_active = Array();
var device_name = Array();
var device_type = Array();
var device_volume = Array();

var shuffle_state = Array();

var item_id = Array();
var item_artist = Array();
var item_title = Array();
var item_album = Array();
var item_image = Array();

var context_type = Array();
var context_uri = Array();

var playlist_id = Array();
var playlist_name = Array();

var access = Array();
var refresh = Array();
var expire = Array();

function debugLog( str) {

    var datetime = new Date();
    if( debug == 'true') console.log( "[" + datetime.toISOString().substring(0,19) + "][DEBUG] : " + str);

}

//function spotifyCheck( _apikey, _index, _clientid, _clientsecret, _command, _refresh, _itemcallback, _devicecallback, _playlistcallback) {
function spotifyCheck( _apikey, _index, _command, _access, _refresh, _expire, _itemcallback, _devicecallback, _playlistcallback) {

    var spotifyApi = new SpotifyWebApi();
    
    // =============
    // REFRESH TOKEN 
    // =============
  
    spotifyApi.setAccessToken(_access);
    
    // ==============
  	// GET MY DEVICES
  	// ==============
  
    spotifyApi.getMyDevices().then( function(data) {

      _device_id = '';
      _device_is_active = '';
      _device_name = '';
      _device_type = '';
      _device_volume = '';

      separator = '';

      for ( var i = 0; i < data.body['devices'].length; i++) {

        _device_id = _device_id + separator + data.body['devices'][i]['id'];
        _device_is_active = _device_is_active + separator + data.body['devices'][i]['is_active'];
      	_device_name = _device_name + separator + data.body['devices'][i]['name'];
        _device_type = _device_type + separator + data.body['devices'][i]['type'];
      	_device_volume = _device_volume + separator + data.body['devices'][i]['volume_percent'];

        separator = '|';

      }

      if(  	device_id[_index] != _device_id 
      || 	device_is_active[_index] != _device_is_active 
      || 	device_name[_index] != _device_name 
      || 	device_type[_index] != _device_type 
      || 	device_volume[_index] != _device_volume 
      ) {

        debugLog('%%%%%%%%%%%%%%%% BEGIN DEVICE REQUEST ('+_command+') %%%%%%%%%%%%%%%%');

        var _url = _devicecallback;

        _url = _url.replace('#APIKEY#', _apikey);
        debugLog('--- API KEY '+_apikey+' ---');

        _url = _url.replace('#ID#', _command);
        debugLog('--- ID '+_command+' ---');

        device_id[_index] = _device_id;
        _url = _url.replace('#DEVICE_ID#', encodeURIComponent(device_id[_index]));
        debugLog('--- DEVICE ID '+_device_id+' ---');

        device_is_active[_index] = _device_is_active;
        _url = _url.replace('#DEVICE_IS_ACTIVE#', encodeURIComponent(device_is_active[_index]));
        debugLog('--- DEVICE IS ACTIVE '+_device_is_active+' ---');

        device_name[_index] = _device_name;
        _url = _url.replace('#DEVICE_NAME#', encodeURIComponent(device_name[_index]));
        debugLog('--- DEVICE NAME '+_device_name+' ---');

        device_type[_index] = _device_type;
        _url = _url.replace('#DEVICE_TYPE#', encodeURIComponent(device_type[_index]));
        debugLog('--- DEVICE TYPE '+_device_type+' ---');

        device_volume[_index] = _device_volume;
        _url = _url.replace('#DEVICE_VOLUME#', encodeURIComponent(device_volume[_index]));
        debugLog('--- DEVICE VOLUME '+_device_volume+' ---');

        if( protocol == 'HTTP' ) {
          http.get(_url);
        } else {
          https.get(_url);
        }

        debugLog('%%%%%%%%%%%%%%%% END DEVICE REQUEST ('+_command+') %%%%%%%%%%%%%%%%');

      } 

    }, function(err) {

      debugLog('--- Failed Get My Devices ' + err + ' ---');

    });

	// =============================
    // GET MY CURRENT PLAYBACK STATE
    // =============================
  
    spotifyApi.getMyCurrentPlaybackState({}).then( function(data) {
      
      if ( !data.body['is_playing'] ) {

        var _item_id = '';         
        var _item_title = '';         
        var _item_artist = '';       
		var _item_album = '';    
        var _item_image = '';   
        
        var _shuffle_state = '';
        
        var _context_type = '';
        var _context_uri = '';
        
                
      } else {
        
        var _item_id = data.body['item']['id']; 
        var _item_title = data.body['item']['name']; 
        var _item_artist = data.body['item']['artists']['0']['name'];         
		var _item_image = data.body['item']['album']['images']['0']['url'];  
        var _item_album = data.body['item']['album']['name'];   
        
        var _shuffle_state = data.body['shuffle_state']; 
        
        var _context_type = data.body['context']['type'];  
        var _context_uri = data.body['context']['uri'];    
        
      }
      
      if( shuffle_state[_index] != _shuffle_state ) {
        
      	debugLog('%%%%%%%%%%%%%%%% BEGIN SHUFFLE REQUEST ('+_command+') %%%%%%%%%%%%%%%%');
        
        var _url = _shufflecallback;

        _url = _url.replace('#APIKEY#', _apikey);
        debugLog('--- API KEY '+_apikey+' ---');
        
        _url = _url.replace('#ID#', _command);
        debugLog('--- ID '+_command+' ---');

        _url = _url.replace('#STATE#', _shuffle_state);
        debugLog('--- STATE '+_shuffle_state+' ---');

        shuffle_state[_index] = _shuffle_state;

        if( protocol == 'HTTP' ) {
          http.get(_url);
        } else {
          https.get(_url);
        }
                
      	debugLog('%%%%%%%%%%%%%%%% END SHUFFLE REQUEST ('+_command+') %%%%%%%%%%%%%%%%');
      
      } 

      if(	item_id[_index] != _item_id 
      ||	item_title[_index] != _item_title 
      || 	item_artist[_index] != _item_artist 
      || 	item_album[_index] != _item_album 
      || 	item_image[_index] != _item_image 
      || 	context_type[_index] != _context_type 
      || 	context_uri[_index] != _context_uri 
      ) {

        debugLog('%%%%%%%%%%%%%%%% BEGIN ITEM REQUEST ('+_command+') %%%%%%%%%%%%%%%%');

        var _url = _itemcallback;

        _url = _url.replace('#APIKEY#', _apikey);
        debugLog('--- API KEY '+_apikey+' ---');

        _url = _url.replace('#ID#', _command);
        debugLog('--- ID '+_command+' ---');

        item_id[_index] = _item_id;
        _url = _url.replace('#ITEM_ID#', encodeURIComponent(item_id[_index]));
        debugLog('--- ITEM ID '+_item_id+' ---');

        item_title[_index] = _item_title;
        _url = _url.replace('#ITEM_TITLE#', encodeURIComponent(item_title[_index]));
        debugLog('--- ITEM TITLE '+_item_title+' ---');

        item_artist[_index] = _item_artist;
        _url = _url.replace('#ITEM_ARTIST#', encodeURIComponent(item_artist[_index]));
        debugLog('--- ITEM ARTIST '+_item_artist+' ---');

        item_album[_index] = _item_album;
        _url = _url.replace('#ITEM_ALBUM#', encodeURIComponent(item_album[_index]));
        debugLog('--- ITEM ALBUM '+_item_album+' ---');

        item_image[_index] = _item_image;
        _url = _url.replace('#ITEM_IMAGE#', encodeURIComponent(item_image[_index]));
        debugLog('--- ITEM IMAGE '+_item_image+' ---');

        context_type[_index] = _context_type;
        _url = _url.replace('#CONTEXT_TYPE#', encodeURIComponent(context_type[_index]));
        debugLog('--- CONTEXT TYPE '+_context_type+' ---');

        context_uri[_index] = _context_uri;
        _url = _url.replace('#CONTEXT_URI#', encodeURIComponent(context_uri[_index]));
        debugLog('--- CONTEXT URI '+_context_uri+' ---');

        if( protocol == 'HTTP' ) {
          http.get(_url);
        } else {
          https.get(_url);
        }

        debugLog('%%%%%%%%%%%%%%%% END ITEM REQUEST ('+_command+') %%%%%%%%%%%%%%%%');

      } 

    }, function(err) {

      debugLog('--- Failed Get My Current Playback State ' + err + ' ---');

    }); 
    
    // ==================
    // GET USER PLAYLISTS
  	// ==================
  
    spotifyApi.getUserPlaylists().then( function(data) {

      _playlist_id = '';
      _playlist_name = '';

      separator = '';

      //debugLog('===================================================================================================');
      
      for ( var i = 0; i < data.body['items'].length; i++) {

        _uri = data.body['items'][i]['owner']['uri'] + ':' + data.body['items'][i]['type'] + ':' + data.body['items'][i]['id'];
        
        //debugLog('=== PLAYLIST ' + i + ' === ' + _uri + ' === ' + data.body['items'][i]['name'] + ' === ');
                    
        _playlist_id = _playlist_id + separator + _uri;
        _playlist_name = _playlist_name + separator + data.body['items'][i]['name'];
       
        separator = '|';

      }

      if( playlist_id[_index] != _playlist_id || playlist_name[_index] != _playlist_name ) {

        debugLog('%%%%%%%%%%%%%%%% BEGIN PLAYLIST REQUEST ('+_command+') %%%%%%%%%%%%%%%%');    
        
        var _url = _playlistcallback;

        _url = _url.replace('#APIKEY#', _apikey);
        debugLog('--- API KEY '+_apikey+' ---');

        _url = _url.replace('#ID#', _command);
        debugLog('--- ID '+_command+' ---');

        playlist_id[_index] = _playlist_id;
        _url = _url.replace('#PLAYLIST_ID#', encodeURIComponent(playlist_id[_index]));
        debugLog('--- PLAYLIST ID '+_playlist_id+' ---');

        playlist_name[_index] = _playlist_name;
        _url = _url.replace('#PLAYLIST_NAME#', encodeURIComponent(playlist_name[_index]));
        debugLog('--- PLAYLIST NAME '+_playlist_name+' ---');

        if( protocol == 'HTTP' ) {
          http.get(_url);
        } else {
          https.get(_url);
        }

        debugLog('%%%%%%%%%%%%%%%% END PLAYLIST REQUEST ('+_command+') %%%%%%%%%%%%%%%%');    

      } 
      
    }, function(err) {

      debugLog('--- Failed Get User Playlists ' + err + ' ---');

    });
  
}

var started = false;

var _apikey = null;
var _commands = null;        
var _itemcallback = null;
var _devicecallback = null;
var _playlistcallback = null;
var _shufflecallback = null;
var _refreshcallback = null;

setInterval( spotifyLoop, 1000);

debugLog('%%%%%%%%%%%%%%%% BEGIN ACCOUNT REQUEST %%%%%%%%%%%%%%%%');  

if( protocol == 'HTTP' ) {
  http.get( url, (resp) => {
      let data = '';
      resp.on('data', (chunk) => {
          data += chunk;
      });
      resp.on('end', () => {

          debugLog('%%%%%%%%%%%%%%%% BEGIN ACCOUNT RESPONSE %%%%%%%%%%%%%%%%');  
        
          var _data = JSON.parse(data);

          _apikey = _data['result']['apikey'];
          debugLog('--- API KEY '+_apikey+' ---');
        
          _commands = JSON.parse(_data['result']['commands']);    
          debugLog('--- COMMANDS '+_commands+' ---');
        
          _itemcallback = _data['result']['itemcallback'];
          debugLog('--- ITEM CALLBACK '+_itemcallback+' ---');
        
          _devicecallback = _data['result']['devicecallback'];
          debugLog('--- DEVICE CALLBACK '+_devicecallback+' ---');
        
          _playlistcallback = _data['result']['playlistcallback'];
		  debugLog('--- PLAYLIST CALLBACK '+_playlistcallback+' ---');
          
          _shufflecallback = _data['result']['shufflecallback'];
          debugLog('--- SHUFFLE CALLBACK '+_shufflecallback+' ---');
          
          _refreshcallback = _data['result']['refreshcallback'];
          debugLog('--- REFRESH CALLBACK '+_refreshcallback+' ---');
          
          debugLog("--- INIT COMPLETE ---");
          started = true;

          debugLog('%%%%%%%%%%%%%%%% END ACCOUNT RESPONSE %%%%%%%%%%%%%%%%'); 
          
      });
  }).on("error", (err) => {

      debugLog('--- Failed Get Account ' + err + ' ---');

  });
} else {
  https.get( url, (resp) => {
      let data = '';
      resp.on('data', (chunk) => {
          data += chunk;
      });
      resp.on('end', () => {

          debugLog('%%%%%%%%%%%%%%%% BEGIN ACCOUNT RESPONSE %%%%%%%%%%%%%%%%');  
        
          var _data = JSON.parse(data);

          _apikey = _data['result']['apikey'];
          debugLog('--- API KEY '+_apikey+' ---');
        
          _commands = JSON.parse(_data['result']['commands']);    
          debugLog('--- COMMANDS '+_commands+' ---');
        
          _itemcallback = _data['result']['itemcallback'];
          debugLog('--- ITEM CALLBACK '+_itemcallback+' ---');
        
          _devicecallback = _data['result']['devicecallback'];
          debugLog('--- DEVICE CALLBACK '+_devicecallback+' ---');
        
          _playlistcallback = _data['result']['playlistcallback'];
		  debugLog('--- PLAYLIST CALLBACK '+_playlistcallback+' ---');
          
          _shufflecallback = _data['result']['shufflecallback'];
          debugLog('--- SHUFFLE CALLBACK '+_shufflecallback+' ---');
          
          _refreshcallback = _data['result']['refreshcallback'];
          debugLog('--- REFRESH CALLBACK '+_refreshcallback+' ---');
          
          debugLog("--- INIT COMPLETE ---");
          started = true;

          debugLog('%%%%%%%%%%%%%%%% END ACCOUNT RESPONSE %%%%%%%%%%%%%%%%'); 

      });
  }).on("error", (err) => {

      debugLog('--- Failed Get Account ' + err + ' ---');

  });  
}
  
debugLog('%%%%%%%%%%%%%%%% END ACCOUNT REQUEST %%%%%%%%%%%%%%%%');  

function refreshToken( i ) {

  	debugLog('%%%%%%%%%%%%%%%% BEGIN REFRESH REQUEST ('+_commands[i].id+') %%%%%%%%%%%%%%%%');  
  
	if( protocol == 'HTTP' ) {
              	
	  var _url = _refreshcallback;

      _url = _url.replace('#APIKEY#', _apikey);
      debugLog('--- APIKEY '+_apikey+' ---');

      _url = _url.replace('#I#', i);
      debugLog('--- I '+i+' ---');

      _url = _url.replace('#ID#', _commands[i].id);
	  debugLog('--- ID '+_commands[i].id+' ---');

      http.get( _url, (resp) => { 
        let data = '';
        resp.on('data', (chunk) => {
          data += chunk;
        } );
        resp.on('end', () => {

          var _data = JSON.parse(data);  

          _i = _data['result']['i'];
          
          debugLog('%%%%%%%%%%%%%%%% BEGIN REFRESH RESPONSE ('+_commands[i].id+') %%%%%%%%%%%%%%%%');  
          
          access[ _i ] = _data['result']['access'];
          debugLog('--- ACCESS '+access[ _i ]+' ---');

          refresh[ _i ] = _data['result']['refresh'];
          debugLog('--- REFRESH '+refresh[ _i ]+' ---');

          expire[ _i ] = _data['result']['expire'];
          debugLog('--- EXPIRE '+expire[ _i ]+' ---');

          debugLog('%%%%%%%%%%%%%%%% END REFRESH RESPONSE ('+_commands[i].id+') %%%%%%%%%%%%%%%%');  

        } );
      }).on("error", (err) => {

      	debugLog("--- Refresh error: " + err.message + " ---");

  	  });

    } else {

      var _url = _refreshcallback;

      _url = _url.replace('#APIKEY#', _apikey);
      debugLog('--- APIKEY '+_apikey+' ---');

      _url = _url.replace('#I#', i);
      debugLog('--- I '+i+' ---');

      _url = _url.replace('#ID#', _commands[i].id);
	  debugLog('--- ID '+_commands[i].id+' ---');

      https.get(_url, (resp) => { 
        let data = '';
        resp.on('data', (chunk) => {
          data += chunk;
        } );
        resp.on('end', () => {

          var _data = JSON.parse(data);  

          _i = _data['result']['i'];
          
          debugLog('%%%%%%%%%%%%%%%% BEGIN REFRESH RESPONSE ('+_commands[i].id+') %%%%%%%%%%%%%%%%');

          access[ _i ] = _data['result']['access'];
          debugLog('--- ACCESS '+access[ _i ]+' ---');

          refresh[ _i ] = _data['result']['refresh'];
          debugLog('--- REFRESH '+refresh[ _i ]+' ---');

          expire[ _i ] = _data['result']['expire'];
          debugLog('--- EXPIRE '+expire[ _i ]+' ---');

          debugLog('%%%%%%%%%%%%%%%% END REFRESH RESPONSE ('+_commands[i].id+') %%%%%%%%%%%%%%%%');                    	

        } );

      }).on("error", (err) => {

      	debugLog("--- Refresh error: " + err.message + " ---");

  	  });

    }
  
    debugLog('%%%%%%%%%%%%%%%% END REFRESH REQUEST ('+_commands[i].id+') %%%%%%%%%%%%%%%%');  
  
}

function spotifyLoop() {

	var _datetime = Math.trunc(Date.now()/1000);
  
	if(started == true) {

		for ( var i = 0; i < _commands.length; i++) {
            
          	if( expire[i] > _datetime) {
              
          		spotifyCheck( _apikey, i, _commands[i].id, access[i], refresh[i], expire[i], _itemcallback, _devicecallback, _playlistcallback); 
              
            } else {
              
              	refreshToken( i );
             
            }
          
		}
  	  
    } else {
      
      	debugLog("--- WAITING FOR INIT COMPLETE---");
  	
    }
  	
}