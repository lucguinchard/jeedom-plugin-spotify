function addCmdToTable(_cmd) {
  
	var tr = '';
  
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
  
    tr += '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    tr += ' <td>';
    tr += '  <span class="cmdAttr" data-l1key="id">';
    tr += ' </td>';
    tr += ' <td>';
    tr += '  <span class="cmdAttr" data-l1key="name">';
  	tr += ' </td>';
    tr += ' <td>';
    tr += '  <span class="cmdAttr" data-l1key="type"></span>';
    tr += ' </td>';
    tr += ' <td>';
    tr += '  <span><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" /> {{Historiser}}<br/></span>';
   	tr += '  <span><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" /> {{Affichage}}<br/></span>';
	tr += ' </td>';
    tr += ' <td>';
    if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
    }
    tr += '  <i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
  	tr += ' </td>';
    tr += '</tr>';
  
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
  
    jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
  
}

var spotify_window = null;

function spotify_callback(code) {

  	spotify_window.close();
  
  	console.log( '### CODE = ' + code);
  
	$("#spotify_detail").find('.eqLogicAttr[data-l1key=configuration][data-l2key=code]').val(code);
  	$("#spotify_detail").find('.eqLogicAttr[data-l1key=configuration][data-l2key=callback]').val(window.location.protocol + "//" + window.location.host + window.location.pathname + "?v=d&m=spotify&p=spotify");
  	
	$.ajax({
		type: "POST", 
      	url: "plugins/spotify/core/ajax/spotify.ajax.php",     
      	data: {
        	action: "get_tokens",
          	callback: $url, 
          	code: code
      	},
      	dataType: 'json',
      	error: function(request, status, error) {
        	$('#md_spotify_alert').showAlert({message: error, level: 'danger'});
      	},
      	success: function(data) {
        	if (data.state != 'ok') {
          		
            	$('#md_spotify_alert').showAlert({message: data.result, level: 'danger'});
        	
            } else {
            
              	accessToken = data.result.accessToken;
              	console.log( '### ACCESS TOKEN = ' + accessToken);
              	$("#spotify_detail").find('.eqLogicAttr[data-l1key=configuration][data-l2key=access]').val(accessToken);
              
              	refreshToken = data.result.refreshToken;
              	console.log( '### REFRESH TOKEN = ' + refreshToken);
  				$("#spotify_detail").find('.eqLogicAttr[data-l1key=configuration][data-l2key=refresh]').val(refreshToken);
  	
             
        	}
      	}
	});
  
}

$('#tokenize').click(function(e) {

    e.preventDefault();
  
  	$url = window.location.protocol + "//" + window.location.host + window.location.pathname + "?v=d&m=spotify&p=spotify";
  	console.log( '### CALLBACK = ' + $url);
  
  	$.ajax({
		type: "POST", 
      	url: "plugins/spotify/core/ajax/spotify.ajax.php",     
      	data: {
        	action: "get_authorize_url",
          	callback: $url
      	},
      	dataType: 'json',
      	error: function(request, status, error) {
        	$('#md_spotify_alert').showAlert({message: error, level: 'danger'});
      	},
      	success: function(data) {
        	if (data.state != 'ok') {
          		
            	$('#md_spotify_alert').showAlert({message: data.result, level: 'danger'});
        	
            } else {
            
              	var width = 450;
            	var height = 730;
              
            	var left = (screen.width / 2) - (width / 2);
            	var top = (screen.height / 2) - (height / 2);
              
              	window.addEventListener("message", function(event) {
                  	console.log(event);
           			var hash = JSON.parse(event.data);
            		if (hash.type == 'access_token') {
                		spotify_callback(hash.access_token);
            		}	
        		}, false);
              
              	console.log( data.result);
              
              	spotify_window = window.open( data.result, 'Spotify', 'menubar=no,location=no,resizable=no,scrollbars=no,status=no, width=' + width + ', height=' + height + ', top=' + top + ', left=' + left );
             
        	}
      	}
	});
  
});