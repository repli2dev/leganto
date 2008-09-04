<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro praci s HTML tagem <ul></ul>.
*/

class Ul extends HTMLTag {
	
	/**
	 * Konstruktor.
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->setTag("ul");
		$this->setPair();
	}
	
	/**
	 * Prida polozku do seznamu.
	 * @param Object Polozka.
	 * @return int Pocet polozek.
	 */
	public function addLi($object) {
		$li = new Li($object);
		return $this->addValue($li);
	}
}
?>