<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Tato trida slouzi jako predek pro tridy nesouci nejakou hodnotu.
*/
abstract class Object {

	/**
	* @var Object Polozka nesouci hodnotu objektu
	*/	
	private $value;

	/**
	* Konstruktor tridy. Nastavy atribut $value na pozadovanou hodnotu.
	* @param value Pozadovana hodnota.
	* @return void
	*/
	public function __construct($value = NULL) {
		$this->setValue($value);
	}

	/**
	* Nastavi atribut $value na pozadovanou hodnotu.
	* @param value Pozadovana hodnota.
	* @return void
	*/
	public function setValue($value) {
		$this->value = $value;
	}

	/**
	* Vrati hodnotu atributu $value
	* @return value 
	*/
	public function getValue() {
		return $this->value;
	}

	/**
	* Vytiskne objekt.
	* @return void
	*/
	public function view() {
		echo $this->getValue();
	}
}
?>
