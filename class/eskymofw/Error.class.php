<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro praci s vyjimkami
*/
class Error extends Exception {

	public function scream() {
		Page::addSystemMessage($this->getMessage());
	}
}
