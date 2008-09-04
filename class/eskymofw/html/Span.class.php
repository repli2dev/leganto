<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
 * Trida pro praci s HTML tagem <span></span>
 * @package eskymoFW
 */
class Span extends HTMLTag {
	
	/**
	 * Konstruktor
	 * @param Object Obsah tagu.
	 * @return void
	 */
	public function __construct($value = NULL) {
		parent::__construct($value);
		$this->setTag("span");
		$this->setPair();
	}

}
?>