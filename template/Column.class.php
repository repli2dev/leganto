<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Box.
* @package readerTemplate
*/
class Column extends Div {
	
	public function __construct($object = NULL) {
		parent::__construct($object);
		$this->setClass("column");
	}
}
?>