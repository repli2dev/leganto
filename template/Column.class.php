<?php
class Column extends Div {
	
	public function __construct($object = NULL) {
		parent::__construct($object);
		$this->setClass("column");
	}
}
?>