<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro praci s HTML tagem <link />.
*/
class Link extends HTMLTag {

	/**
	* Konstruktor.
	* @param string Hodnota atributu rel.
	* @param string Hodnota atributu style.
	* @param string Hodnota atributu href.
	* @return void
	*/
	public function __construct($rel = NULL, $type = NULL, $href = NULL) {
		parent :: __construct();
		$this->setTag("link");
		if ($rel) {
			$this->rel($rel);
		}
		if ($type) {
			$this->type($type);
		}
		if ($href) {
			$this->href($href);
		}
	}

	/**
	* Nastavi adresu (href).
	* @param string Adresa
	* @return void
	*/
	public function href($url) {
		$this->addAtribut("href",$url);
	}
	
	/**
	* Nastavi typ (type).
	* @param string Type.
	* @return void
	*/
	public function type($type) {
		$this->addAtribut("type",$type);
	}
	
	/**
	* Nastavi atribut rel.
	* @param string Rel.
	* @return void
	*/
	public function rel($rel) {
		$this->addAtribut("rel",$rel);
	}
}
?>