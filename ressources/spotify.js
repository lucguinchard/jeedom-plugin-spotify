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

var shuffle_state = '';

var item_id = Array();
var item_artist = Array();
var item_title = Array();
var item_album = Array();
var item_image = Array();

var context_type = Array();
var context_uri = Array();

var playlist_id = Array();
var playlist_name = Array();

//function spotifyCheck( _apikey, _index, _clientid, _clientsecret, _command, _refresh, _itemcallback, _devicecallback, _playlistcallback) {
function spotifyCheck( _apikey, _index, _command, _access, _refresh, _expire, _itemcallback, _devicecallback, _playlistcallback) {

  //var spotifyApi = new SpotifyWebApi({
  //	clientId: _clientid,
  //	clientSecret: _clientsecret
  //});

  var spotifyApi = new SpotifyWebApi();
    
  //spotifyApi.setRefreshToken(_refresh);

  // =============
  // REFRESH TOKEN 
  // =============
  
  //spotifyApi.refreshAccessToken().then( function(data) {
    
    //_access = data.body['access_token'];
    spotifyApi.setAccessToken(_access);
    
    // ==============
  	// GET MY DEVICES
  	// ==============
  
    spotifyApi.getMyDevices().then( function(data) {

      //console.log( data.body);
      
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

        if( debug == 'true') console.log('=================== BEGIN GET MY DEVICE ==========================');

        var _url = _devicecallback;

        _url = _url.replace('#APIKEY#', _apikey);
        if( debug == 'true') console.log('--- API KEY '+_apikey+' ---');

        _url = _url.replace('#ID#', _command);
        if( debug == 'true') console.log('--- ID '+_command+' ---');

        device_id[_index] = _device_id;
        _url = _url.replace('#DEVICE_ID#', encodeURIComponent(device_id[_index]));
        if( debug == 'true') console.log('--- DEVICE ID '+_device_id+' ---');

        device_is_active[_index] = _device_is_active;
        _url = _url.replace('#DEVICE_IS_ACTIVE#', encodeURIComponent(device_is_active[_index]));
        if( debug == 'true') console.log('--- DEVICE IS ACTIVE '+_device_is_active+' ---');

        device_name[_index] = _device_name;
        _url = _url.replace('#DEVICE_NAME#', encodeURIComponent(device_name[_index]));
        if( debug == 'true') console.log('--- DEVICE NAME '+_device_name+' ---');

        device_type[_index] = _device_type;
        _url = _url.replace('#DEVICE_TYPE#', encodeURIComponent(device_type[_index]));
        if( debug == 'true') console.log('--- DEVICE TYPE '+_device_type+' ---');

        device_volume[_index] = _device_volume;
        _url = _url.replace('#DEVICE_VOLUME#', encodeURIComponent(device_volume[_index]));
        if( debug == 'true') console.log('--- DEVICE VOLUME '+_device_volume+' ---');

        if( protocol == 'HTTP' ) {
          http.get(_url);
        } else {
          https.get(_url);
        }

        if( debug == 'true') console.log('=================== END GET MY DEVICE ==========================');

      }

    }, function(err) {

      console.log('--- Failed Get My Devices ---', err);

    });

	// =============================
    // GET MY CURRENT PLAYBACK STATE
    // =============================
  
    spotifyApi.getMyCurrentPlaybackState({}).then( function(data) {
      
      //console.log( data.body);
      
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
      
      if( shuffle_state != _shuffle_state ) {
        
      	if( debug == 'true') console.log('=================== BEGIN SHUFFLE ==========================');
        
        var _url = _shufflecallback;

        _url = _url.replace('#APIKEY#', _apikey);
        if( debug == 'true') console.log('--- API KEY '+_apikey+' ---');
        
        _url = _url.replace('#ID#', _command);
        if( debug == 'true') console.log('--- ID '+_command+' ---');

        _url = _url.replace('#STATE#', _shuffle_state);
        if( debug == 'true') console.log('--- STATE '+_shuffle_state+' ---');

        shuffle_state = _shuffle_state;

        if( protocol == 'HTTP' ) {
          http.get(_url);
        } else {
          https.get(_url);
        }
                
      	if( debug == 'true') console.log('=================== END SHUFFLE ==========================');
      
      } 

      if(	item_id[_index] != _item_id 
      ||	item_title[_index] != _item_title 
      || 	item_artist[_index] != _item_artist 
      || 	item_album[_index] != _item_album 
      || 	item_image[_index] != _item_image 
      || 	context_type[_index] != _context_type 
      || 	context_uri[_index] != _context_uri 
      ) {

        if( debug == 'true') console.log('=================== BEGIN CURRENT PLAYBACK ITEM ==========================');

        var _url = _itemcallback;

        _url = _url.replace('#APIKEY#', _apikey);
        if( debug == 'true') console.log('--- API KEY '+_apikey+' ---');

        _url = _url.replace('#ID#', _command);
        if( debug == 'true') console.log('--- ID '+_command+' ---');

        item_id[_index] = _item_id;
        _url = _url.replace('#ITEM_ID#', encodeURIComponent(item_id[_index]));
        if( debug == 'true') console.log('--- ITEM ID '+_item_id+' ---');

        item_title[_index] = _item_title;
        _url = _url.replace('#ITEM_TITLE#', encodeURIComponent(item_title[_index]));
        if( debug == 'true') console.log('--- ITEM TITLE '+_item_title+' ---');

        item_artist[_index] = _item_artist;
        _url = _url.replace('#ITEM_ARTIST#', encodeURIComponent(item_artist[_index]));
        if( debug == 'true') console.log('--- ITEM ARTIST '+_item_artist+' ---');

        item_album[_index] = _item_album;
        _url = _url.replace('#ITEM_ALBUM#', encodeURIComponent(item_album[_index]));
        if( debug == 'true') console.log('--- ITEM ALBUM '+_item_album+' ---');

        item_image[_index] = _item_image;
        _url = _url.replace('#ITEM_IMAGE#', encodeURIComponent(item_image[_index]));
        if( debug == 'true') console.log('--- ITEM IMAGE '+_item_image+' ---');

        context_type[_index] = _context_type;
        _url = _url.replace('#CONTEXT_TYPE#', encodeURIComponent(context_type[_index]));
        if( debug == 'true') console.log('--- CONTEXT TYPE '+_context_type+' ---');

        context_uri[_index] = _context_uri;
        _url = _url.replace('#CONTEXT_URI#', encodeURIComponent(context_uri[_index]));
        if( debug == 'true') console.log('--- CONTEXT URI '+_context_uri+' ---');

        if( protocol == 'HTTP' ) {
          http.get(_url);
        } else {
          https.get(_url);
        }

        if( debug == 'true') console.log('==================== END CURRENT PLAYBACK ITEM ===========================');

      }

    }, function(err) {

      console.log('--- Failed Get My Current Playback State ---', err);

    }); 
    
    // ==================
    // GET USER PLAYLISTS
  	// ==================
  
    spotifyApi.getUserPlaylists().then( function(data) {

      //console.log(data.body);
      
      _playlist_id = '';
      _playlist_name = '';

      separator = '';

      console.log('===================================================================================================');
      
      for ( var i = 0; i < data.body['items'].length; i++) {

        _uri = data.body['items'][i]['owner']['uri'] + ':' + data.body['items'][i]['type'] + ':' + data.body['items'][i]['id'];
        
        // console.log('=== PLAYLIST ' + i + ' === ' + data.body['items'][i]['id'] + ' === ' + data.body['items'][i]['name'] + ' === ');
        console.log('=== PLAYLIST ' + i + ' === ' + _uri + ' === ' + data.body['items'][i]['name'] + ' === ');
                    
        //_playlist_id = _playlist_id + separator + data.body['items'][i]['id'];
        _playlist_id = _playlist_id + separator + _uri;
        _playlist_name = _playlist_name + separator + data.body['items'][i]['name'];
        
        /// console.log(data.body['items'][i]);
  
        separator = '|';

      }

      console.log('===================================================================================================');
      console.log('--- PLAYLIST ID '+_playlist_id+' ---');
      console.log('--- PLAYLIST NAME '+_playlist_name+' ---');
      console.log('===================================================================================================');
      
      if( playlist_id[_index] != _playlist_id || playlist_name[_index] != _playlist_name ) {

        if( debug == 'true') console.log('==================== BEGIN PLAYLISTS ===========================');        
        
        var _url = _playlistcallback;

        _url = _url.replace('#APIKEY#', _apikey);
        if( debug == 'true') console.log('--- API KEY '+_apikey+' ---');

        _url = _url.replace('#ID#', _command);
        if( debug == 'true') console.log('--- ID '+_command+' ---');

        playlist_id[_index] = _playlist_id;
        _url = _url.replace('#PLAYLIST_ID#', encodeURIComponent(playlist_id[_index]));
        if( debug == 'true') console.log('--- PLAYLIST ID '+_playlist_id+' ---');

        playlist_name[_index] = _playlist_name;
        _url = _url.replace('#PLAYLIST_NAME#', encodeURIComponent(playlist_name[_index]));
        if( debug == 'true') console.log('--- PLAYLIST NAME '+_playlist_name+' ---');

        if( protocol == 'HTTP' ) {
          http.get(_url);
        } else {
          https.get(_url);
        }

        if( debug == 'true') console.log('==================== END PLAYLISTS ===========================');

      }

    }, function(err) {

      console.log('--- Failed Get User Playlists ---', err);

    });
      
  //}, function(err) {
    
  //  console.log('--- Failed Refresh Token ---', err);
    
  //});
  
}

if( debug == 'true') console.log("--- STARTING ---");

var started = false;

var _apikey = null;
var _clientid = null;
var _clientsecret = null;
var _commands = null;        
var _apikey = null;
var _itemcallback = null;
var _devicecallback = null;
var _playlistcallback = null;
var _shufflecallback = null;

setInterval( spotifyLoop, 1000);

if( protocol == 'HTTP' ) {
  http.get( url, (resp) => {
      let data = '';
      resp.on('data', (chunk) => {
          data += chunk;
      });
      resp.on('end', () => {

          var _data = JSON.parse(data);

          _apikey = _data['result']['apikey'];
          //_clientid = _data['result']['clientid'];
          //_clientsecret = _data['result']['clientsecret'];

          _commands = JSON.parse(_data['result']['commands']);    

          _itemcallback = _data['result']['itemcallback'];
          _devicecallback = _data['result']['devicecallback'];
          _playlistcallback = _data['result']['playlistcallback'];
		  _shufflecallback = _data['result']['shufflecallback'];
          _refreshcallback = _data['result']['refreshcallback'];
        
          console.log("--- RUNNING ---");
          started = true;

      });
  }).on("error", (err) => {

      console.log("Error: " + err.message);

  });
} else {
  https.get( url, (resp) => {
      let data = '';
      resp.on('data', (chunk) => {
          data += chunk;
      });
      resp.on('end', () => {

          var _data = JSON.parse(data);

          _apikey = _data['result']['apikey'];
          //_clientid = _data['result']['clientid'];
          //_clientsecret = _data['result']['clientsecret'];

          _commands = JSON.parse(_data['result']['commands']);    

          _itemcallback = _data['result']['itemcallback'];
          _devicecallback = _data['result']['devicecallback'];
          _playlistcallback = _data['result']['playlistcallback'];
		  _shufflecallback = _data['result']['shufflecallback'];
       	  _refreshcallback = _data['result']['refreshcallback'];
        
          console.log("--- RUNNING ---");
          started = true;

      });
  }).on("error", (err) => {

      console.log("Error: " + err.message);

  });  
}
  
function spotifyLoop() {

	if(started == true) {

		// if( debug == 'true') console.log("--- LOOPING ---");
  	
		for ( var i = 0; i < _commands.length; i++) {
            
          	if( debug == 'true') console.log('=================== BEGIN REFRESH ==========================');
        
  			var _url = _refreshcallback;

  			_url = _url.replace('#APIKEY#', _apikey);
  			if( debug == 'true') console.log('--- API KEY '+_apikey+' ---');

  			_url = _url.replace('#ID#', _commands[i].id);
  			if( debug == 'true') console.log('--- ID '+_commands[i].id+' ---');

          	if( debug == 'true') console.log('--- REFRESH URL '+_url+' ---');
          
  			if( protocol == 'HTTP' ) {
    			http.get( _url, (resp) => { 
                	let data = '';
      				resp.on('data', (chunk) => {
          				data += chunk;
      				} );
      				resp.on('end', () => {
                      	var _data = JSON.parse(data);  
                      	_i = _data['result']['i'];
                  		if( debug == 'true') console.log('--- I '+_i+' ---');
          				_id = _data['result']['id'];
                  		if( debug == 'true') console.log('--- ID '+_id+' ---');
          				_access = _data['result']['access'];
                      	if( debug == 'true') console.log('--- ACCESS '+_access+' ---');
          				_refresh = _data['result']['refresh'];
                      	if( debug == 'true') console.log('--- REFRESH '+_refresh+' ---');
          				_expire = _data['result']['expire'];
                  		if( debug == 'true') console.log('--- EXPIRE '+_expire+' ---');
          				spotifyCheck( _apikey, _i, _id, _access, _refresh, _expire, _itemcallback, _devicecallback, _playlistcallback); 
                    } );
                } );
  			} else {
    			https.get(_url, (resp) => { 
                	let data = '';
      				resp.on('data', (chunk) => {
          				data += chunk;
      				} );
      				resp.on('end', () => {
          				var _data = JSON.parse(data);  
                      	_i = _data['result']['i'];
                  		if( debug == 'true') console.log('--- I '+_i+' ---');
          				_id = _data['result']['id'];
                  		if( debug == 'true') console.log('--- ID '+_id+' ---');
          				_access = _data['result']['access'];
                      	if( debug == 'true') console.log('--- ACCESS '+_access+' ---');
          				_refresh = _data['result']['refresh'];
                      	if( debug == 'true') console.log('--- REFRESH '+_refresh+' ---');
          				_expire = _data['result']['expire'];
                  		if( debug == 'true') console.log('--- EXPIRE '+_expire+' ---');
          				spotifyCheck( _apikey, _i, _id, _access, _refresh, _expire, _itemcallback, _devicecallback, _playlistcallback); 
                    } );
            	} );
  			}
          
          	if( debug == 'true') console.log('=================== END REFRESH ==========================');
          
	  		//spotifyCheck( _apikey, i, _clientid, _clientsecret, _commands[i].id, _commands[i].token, _itemcallback, _devicecallback, _playlistcallback);
          
		}
      
    } else {
      
      	if( debug == 'true') console.log("--- WAITING ---");
  	
    }
  	
}

if( debug == 'true') console.log("--- ENDING ---");