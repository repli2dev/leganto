<?php
/**
* @package readerTemplate
* @author Jan Drabek
* @copyright Jan Drabek 2008
* @link http://ctenari.cz
*/
/**
* Podmenu na hlavni strance
* @package readerTemplate
*/
class ActionSubMenu extends Div {

	public function __construct() {
		parent::__construct();
		$this->setClass("submenu");
		$ul = new Ul;
			$ul->addLi(new A(
				Lng::RANDOM_BOOK,
				"book.php?action=random"
			));
		$this->addValue($ul);
		unset($ul);
	}
}
?>