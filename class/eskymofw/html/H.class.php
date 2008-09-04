<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro praci s tagy <hN></hN>.
*/
class H extends HTMLTag {

	/**
	 * Konstruktor
	 * @var int Cislo nadpisu.
	 * @var Object Obsah nadpusu.
	 * @return void
	 */
	public function __construct($number,$value) {
		$this->setTag("h".$number);
		$this->setPair();
		$this->addValue($value);
	}
}
?>