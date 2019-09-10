<?php

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

include_file('core', 'authentification', 'php');

if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

?>
  
<!-- ============= -->
<!-- == Install == -->
<!-- ============= -->

<?php
  
function spotify_install() {

	log::add('spotify', 'error', '--- INSTAL ---');
  
}

?>
  
<!-- ============ -->
<!-- == Update == -->
<!-- ============ -->

<?php
  
function spotify_update() {

	log::add('spotify', 'error', '--- UPDATE ---');
  
}

?>
  
<!-- ============ -->
<!-- == Remove == -->
<!-- ============ -->

<?php
  
function spotify_remove() {

  	log::add('spotify', 'error', '--- REMOVE ---');
  
}

?>