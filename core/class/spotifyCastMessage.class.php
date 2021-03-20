<?php

require_once __DIR__ . '/../../../../core/php/core.inc.php';
require_once 'spotify.class.php';

class CastMessage {

	public $protocolversion = 0; // CASTV2_1_0 - It's always this
	public $source_id; // Source ID String
	public $receiver_id; // Receiver ID String
	public $urnnamespace; // Namespace
	public $payloadtype = 0; // PayloadType String=0 Binary = 1
	public $payloadutf8; // Payload

	private function hex_dump($data) {
		static $from = '';
		static $to = '';

		static $width = 16; # number of bytes per line

		static $pad = '.'; # padding for non-visible characters

		if ($from === '') {
			for ($i = 0; $i <= 0xFF; $i++) {
				$from .= chr($i);
				$to .= ($i >= 0x20 && $i <= 0x7E) ? chr($i) : $pad;
			}
		}

		$hex = str_split(bin2hex($data), $width * 2);
		$chars = str_split(strtr($data, $from, $to), $width);

		$offset = 0;
		foreach ($hex as $i => $line) {
			log::add('spotify', 'debug', sprintf('%6X', $offset) . ' : ' . implode(' ', str_split($line, 2)) . ' [' . $chars[$i] . ']');
			$offset += $width;
		}

		return $txt;
	}

	public function decode($binstring, $debug = 1) {

		if ($debug == 1)
			log::add('spotify', 'debug', '    --- DECODE --- BEGIN ---');

		$index = 0;
		$size = strlen($binstring);

		if ($debug == 1)
			$this->hex_dump($binstring);

		$length = hex2bin(base_convert(
						( base_convert(bin2hex(substr($binstring, $index++, 1)), 16, 10) << 24 ) + ( base_convert(bin2hex(substr($binstring, $index++, 1)), 16, 10) << 16 ) + ( base_convert(bin2hex(substr($binstring, $index++, 1)), 16, 10) << 8 ) + ( base_convert(bin2hex(substr($binstring, $index++, 1)), 16, 10) )
						, 10, 16));

		if ($debug == 1)
			$this->hex_dump($length);

		while ($index < $size) {

			$next = base_convert(bin2hex(substr($binstring, $index++, 1)), 16, 10);
			$field = base_convert(( ( $next & 0b11111000 ) >> 3), 10, 2);
			$type = base_convert(( $next & 0b111), 10, 2);

			if ($field == "1" && $type == "0") {

				$this->protocolversion = $this->binToVarint(substr($binstring, $index++, 1));
				if ($debug == 1)
					log::add('spotify', 'debug', '    --- PROTOCOL = "' . $this->protocolversion . '" ---');
			} else if ($field == "10" && $type == "10") {

				$l_source_id = ord(substr($binstring, $index++, 1));
				$this->source_id = substr($binstring, $index, $l_source_id);
				$index += $l_source_id;
				if ($debug == 1)
					log::add('spotify', 'debug', '    --- SOURCE ID = "' . $this->source_id . '" ---');
			} else if ($field == "11" && $type == "10") {

				$l_receiver_id = ord(substr($binstring, $index++, 1));
				$this->receiver_id = substr($binstring, $index, $l_receiver_id);
				$index += $l_receiver_id;
				if ($debug == 1)
					log::add('spotify', 'debug', '    --- RECEIVER ID = "' . $this->receiver_id . '" ---');
			} else if ($field == "100" && $type == "10") {

				$l_urnnamespace = ord(substr($binstring, $index++, 1));
				$this->urnnamespace = substr($binstring, $index, $l_urnnamespace);
				$index += $l_urnnamespace;
				if ($debug == 1)
					log::add('spotify', 'debug', '    --- URNNAMESPACE = "' . $this->urnnamespace . '" ---');
			} else if ($field == "101" && $type == "0") {

				$this->payloadtype = $this->binToVarint(substr($binstring, $index++, 1));
				if ($debug == 1)
					log::add('spotify', 'debug', '    --- PAYLOADTYPE = "' . $this->payloadtype . '" ---');
			} else if ($field == "110" && $type == "10") {

				$l_payloadutf8 = ord(substr($binstring, $index++, 1));
				if ($l_payloadutf8 > 128) {
					$l_payloadutf8 = $l_payloadutf8 - 128 + ord(substr($binstring, $index++, 1)) * 128;
				}
				if ($debug == 1)
					log::add('spotify', 'debug', '    --- PAYLOADLENGTH = "' . $l_payloadutf8 . '" ---');
				$this->payloadutf8 = substr($binstring, $index, $l_payloadutf8);
				$index += $l_payloadutf8;
				if ($debug == 1)
					log::add('spotify', 'debug', '    --- PAYLOADUTF8 = "' . $this->payloadutf8 . '" ---');
			} else {

				if ($debug == 1)
					log::add('spotify', 'debug', '    ??? FIELD = ' . $field . ' ---');
				if ($debug == 1)
					log::add('spotify', 'debug', '    ??? TYPE = ' . $type . ' ---');
			}
		}

		if ($debug == 1)
			log::add('spotify', 'debug', '    --- DECODE --- END ---');
	}

	public function encode($debug = 0) {

		if ($debug == 1)
			log::add('spotify', 'debug', '    --- ENCODE --- BEGIN ---');

		$r = "";

		// Protocol version
		if ($debug == 1)
			log::add('spotify', 'debug', '    --- PROTOCOL = "' . $this->protocolversion . '" ---');
		$r = "00001"; // Field Number 1
		$r .= "000"; // Int
		$r .= $this->varintToBin($this->protocolversion);

		// Source id
		if ($debug == 1)
			log::add('spotify', 'debug', '    --- SOURCE ID = "' . $this->source_id . '" ---');
		$r .= "00010"; // Field Number 2
		$r .= "010"; // String
		$r .= $this->stringToBin($this->source_id);

		// Receiver id
		if ($debug == 1)
			log::add('spotify', 'debug', '    --- RECEIVER ID = "' . $this->receiver_id . '" ---');
		$r .= "00011"; // Field Number 3
		$r .= "010"; // String
		$r .= $this->stringToBin($this->receiver_id);

		// Namespace
		if ($debug == 1)
			log::add('spotify', 'debug', '    --- URNNAMESPACE = "' . $this->urnnamespace . '" ---');
		$r .= "00100"; // Field Number 4
		$r .= "010"; // String
		$r .= $this->stringToBin($this->urnnamespace);

		// Payload type
		if ($debug == 1)
			log::add('spotify', 'debug', '    --- PAYLOADTYPE = "' . $this->payloadtype . '" ---');
		$r .= "00101"; // Field Number 5
		$r .= "000"; // VarInt
		$r .= $this->varintToBin($this->payloadtype);

		// Payload utf8
		if ($debug == 1)
			log::add('spotify', 'debug', '    --- PAYLOADUTF8 = "' . $this->payloadutf8 . '" ---');
		$r .= "00110"; // Field Number 6
		$r .= "010"; // String
		$r .= $this->stringToBin($this->payloadutf8);

		// Ignore payload_binary field 7 as never used
		// Now convert it to a binary packet
		$hexstring = "";
		for ($i = 0; $i < strlen($r); $i = $i + 8) {
			$thischunk = substr($r, $i, 8);
			$hx = dechex(bindec($thischunk));
			if (strlen($hx) == 1) {
				$hx = "0" . $hx;
			}
			$hexstring .= $hx;
		}
		$l = strlen($hexstring) / 2;
		$l = dechex($l);
		while (strlen($l) < 8) {
			$l = "0" . $l;
		}
		$hexstring = $l . $hexstring;
		$ret = hex2bin($hexstring);

		if ($debug == 1)
			$this->hex_dump($ret);

		if ($debug == 1)
			log::add('spotify', 'debug', '    --- ENCODE --- END ---');

		return $ret;
	}

	private function binToVarint($inval) {

		return ( ( base_convert(substr(base_convert($inval, 2, 16), 2, 2), 16, 10) & 0b1111111 ) * 128 ) + ( base_convert(substr(base_convert($inval, 2, 16), 0, 2), 16, 10) & 0b1111111 );
	}

	private function varintToBin($inval) {
		// Convert an integer to a binary varint
		// A variant is returned least significant part first.
		// Number is represented in 7 bit portions. The 8th (MSB) of a byte represents if there
		// is a following byte.
		$r = array();
		while ($inval / 128 > 1) {
			$thisval = ($inval - ($inval % 128)) / 128;
			array_push($r, $thisval);
			$inval = $inval - ($thisval * 128);
		}
		array_push($r, $inval);
		$r = array_reverse($r);
		$binaryString = "";
		$c = 1;
		foreach ($r as $num) {
			if ($c != sizeof($r)) {
				$num = $num + 128;
			}
			$tv = decbin($num);
			while (strlen($tv) < 8) {
				$tv = "0" . $tv;
			}
			$c++;
			$binaryString .= $tv;
		}
		return $binaryString;
	}

	private function stringToBin($string) {
		// Convert a string to a Binary string
		// First the length (note this is a binary varint)
		$l = strlen($string);
		$ret = "";
		$ret = $this->varintToBin($l);
		for ($i = 0; $i < $l; $i++) {
			$n = decbin(ord(substr($string, $i, 1)));
			while (strlen($n) < 8) {
				$n = "0" . $n;
			}
			$ret .= $n;
		}
		return $ret;
	}

}