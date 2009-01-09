<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Paticka.
* @package readerTemplate
*/
class Footer extends Div {
	
	public function __construct() {
		parent::__construct();		
		$this->setID("foot");
		$this->addValue(new String("&copy; 2007 - 2009"));
		$this->addValue(new A("Jan Papoušek","mailto:jan.papousek@gmail.com"));
		$this->addValue(new String(","));
		$this->addValue(new A("Jan Drábek","mailto:repli2dev@gmail.com"));
		$this->addValue(new String("; Vzhled navrhl "));
		$this->addValue(new A("Richard Šefr","mailto:richard.sefr@gmail.com"));
	}
}
?>