<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro praci s HTML tagem <img />
* @example doc_example/A.phps
*/
class Img extends HTMLTag {

	/**
	* Konstruktor.
	* @param string Adresa obrazku.
	* @param string Titulek obrazku.
	* @return void
	*/
	public function __construct($src,$alt = NULL) {
		$this->setTag("img");
		$this->addAtribut("src",$src);
		if ($alt) {
			$this->addAtribut("alt",$alt);
		}
	}

}
