<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Zobrazi soukrome zpravy.
* @package readerTemplate
*/
class MessageBox extends Div {
	
	public function __construct() {
		parent::__construct();
		$this->setID("messageBox");
		$res = Message::readAll(Page::get("page"));
		while ($message = mysql_fetch_object($res)) {
			$this->addValue(new MessageInfo($message));
		}
	}
}
?>