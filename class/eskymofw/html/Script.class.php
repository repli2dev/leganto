<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro praci s HTML tagem <script></script>.
*/
class Script extends HTMLTag {

	/**
	* Konstruktor.
	* @param string Hodnota atributu rel.
	* @param string Hodnota atributu style.
	* @param string Hodnota atributu href.
	* @return void
	*/
	public function __construct($type = NULL, $src = NULL) {
		parent :: __construct(new String(""));
		$this->setTag("script");
		$this->setPair();
		if ($type) {
			$this->type($type);
		}
		if ($src) {
			$this->src($src);
		}
	}

	/**
	* Nastavi adresu (src).
	* @param string Adresa
	* @return void
	*/
	public function src($src) {
		$this->addAtribut("src",$src);
	}
	
	/**
	* Nastavi typ (type).
	* @param string Titulek.
	* @return void
	*/
	public function type($type) {
		$this->addAtribut("type",$type);
	}
}