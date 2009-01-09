<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Naposledy pridane (komentovane knihy)
* @package readerTemplate
*/
class WritingLastBox extends Div {

	public function __construct() {
		parent::__construct();
		$this->setClass("column");
		$this->addValue(new H(2,Lng::WRITING_LAST));
		$res = Writing::getLast();
		$ul = new Ul();
		while ($record = mysql_fetch_object($res)) {
			if(!empty($record->link)){
				$url = $record->link;
			} else {
				$url = "writing.php?action=readOne&amp;id=".$record->id;
			}
			$ul->addLi(new A(
				$record->title ." (". $record->userName .")",
				$url
			));
		}
		$this->addValue($ul);
		unset($ul);
	}
}
?>
