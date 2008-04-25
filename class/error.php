<?php

class error extends Exception {
	
	public function __construct($bug) {
  		Exception::__construct($bug,0);
 	}
 	
 	public function scream() {
  		$temp = new template;
  		$temp->message(parent::getMessage());
 	}
}
?>
