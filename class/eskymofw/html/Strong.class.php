<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
 * Trida pro praci s HTML tagem <strong></strong>
 * @package eskymoFW
 */
class Strong extends HTMLTag {
	
	/**
	 * Konstruktor
	 * @param Object Obsah tagu.
	 * @return void
	 */
	public function __construct($value = NULL) {
		parent::__construct($value);
		$this->setTag("strong");
		$this->setPair();
	}

}
?>