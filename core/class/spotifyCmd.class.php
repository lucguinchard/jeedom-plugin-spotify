<?php

require_once __DIR__ . '/../../../../core/php/core.inc.php';
require_once 'spotify.class.php';

class spotifyCmd extends cmd {
	/*	 * ************* Attributs ************** */

	/*	 * *********** Static methods *********** */

	/*	 * ************** Methods *************** */

	public function formatValue($_value, $_quote = false) {
		$value = cmd::formatValue($_value, $_quote);
		//log::add(__CLASS__, 'debug', '--- FORMAT VALUE '.$this->getLogicalId().' / '.json_encode($_value).' / '.json_encode($_quote).' / '.json_encode($value).' ---');
		return $value;
	}

	public function getCmdValue() {
		$value = cmd::getCmdValue();
		//log::add(__CLASS__, 'debug', '--- GET CMD VALUE '.$this->getLogicalId().' / '.json_encode($value).' ---');
		return $value;
	}

	public function getValue() {
		$value = cmd::getValue();
		//log::add(__CLASS__, 'debug', '--- GET VALUE '.$this->getLogicalId().' / '.json_encode($value).' ---');
		return $value;
	}

	public function execute($_options = array()) {
		if ($this->getType() == 'info') {
			return;
		}

		log::add(__CLASS__, 'debug', '--- EXECUTE ' . $this->getLogicalId() . ' / ' . print_r($_options, true) . ' ---');
		$eqLogic = $this->getEqLogic();
		switch ($this->getLogicalId()) {
			case 'previous' :
				$eqLogic->previous($_options);
				break;
			case 'next' :
				$eqLogic->next($_options);
				break;
			case 'play' :
				$eqLogic->play($_options);
				break;
			case 'pause' :
				$eqLogic->pause($_options);
				break;
			case 'shuffle' :
				$eqLogic->shuffle($_options);
				break;
			case 'unshuffle' :
				$eqLogic->unshuffle($_options);
				break;
			case 'device_volume_set' :
				$eqLogic->volume($_options);
				break;
			case 'device_id_set' :
			case 'device_name_set' :
				$eqLogic->device($_options);
				break;
			case 'playlist_id_set' :
			case 'playlist_name_set' :
				$eqLogic->playlist($_options);
				break;
			default :
				log::add(__CLASS__, 'info', '--- TODO: Créer la commande ' . $this->getLogicalId() . ' - ' . print_r($_options, true));
		}
	}

	/*	 * ******** Getters and setters ********* */
}
