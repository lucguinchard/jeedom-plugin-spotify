<?php

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

include_file('core', 'authentification', 'php');

if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

?>
  
<form class="form-horizontal">
	<fieldset>
		<div class="form-group">
    		<label class="col-lg-4 control-label">{{Client ID}}</label>
    		<div class="col-lg-4">
     			<input class="configKey form-control" data-l1key="clientid" />
   			</div>
 		</div>
  		<div class="form-group">
    		<label class="col-lg-4 control-label">{{Client Secret}}</label>
    		<div class="col-lg-4">
     			<input class="configKey form-control" data-l1key="clientsecret" />
   			</div>
 		</div>
        <div class="form-group">
    		<label class="col-lg-4 control-label">{{Jeedom protocol}}</label>
    		<div class="col-lg-4">              
      			<select class="configKey form-control" data-l1key="protocol">
  					<option value="HTTP">HTTP</option>
  					<option value="HTTPS">HTTPS</option>
				</select>   
   			</div>
 		</div>
	</fieldset>
</form>