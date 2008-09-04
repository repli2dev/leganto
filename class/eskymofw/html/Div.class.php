<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro praci s tagem Div.
*/
class Div extends HTMLTag {
	
	/**
	 * Konstruktor.
	 * @return void
	 */
	public function __construct($value = NULL) {
		parent::__construct($value);
		$this->setTag("div");
		$this->setPair();
	}
	
}
?>