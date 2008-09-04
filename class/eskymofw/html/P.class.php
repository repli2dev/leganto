<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
 * Trida pro praci s HTML tagem <p></p>
 * @package eskymoFW
 */
class P extends HTMLTag {
	
	/**
	 * Konstruktor
	 * @param Object Obsah odstavce
	 * @return void
	 */
	public function __construct($value = NULL) {
		parent::__construct($value);
		$this->setTag("p");
		$this->setPair();
	}

}
?>