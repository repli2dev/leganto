<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro praci s HTML tagem <li></li>.
*/
class Li extends HTMLTag {
	
	/**
	 * Konstruktor.
	 * @param Object
	 * @return void
	 */
	public function __construct($value = NULL) {
		parent::__construct($value);
		$this->setTag("li");
		$this->setPair();
	}
}
?>