<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* Paticka stranky.
* @package reader
*/
class Footer extends Div {
	
	public function __construct() {
		parent::__construct();		
		$this->setID("foot");
		$this->addValue(new String("&copy; 2007 - 2008"));
		$this->addValue(new A("Jan Papoušek","mailto:jan.papousek@gmail.com"));
		$this->addValue(new String("; Spolupracoval "));
		$this->addValue(new A("Jan Drábek","mailto:repli2dev@gmail.com"));
		$this->addValue(new String("; Vzhled navrhl "));
		$this->addValue(new A("Richard Šefr","mailto:richard.sefr@gmail.com"));
	}
}
?>