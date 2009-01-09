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
class CompetitionLastBox extends Div {
	
	public function __construct() {
		parent::__construct();
		$this->setClass("column");
		$this->addValue(new H(2,Lng::COMPETITION_LAST));
		$res = Competition::getLast();
		$ul = new Ul();
		while ($record = mysql_fetch_object($res)) {
			$ul->addLi(new A(
				$record->name,
				"competition.php?action=readOne&amp;comp=".$record->id
			));
		}
		$this->addValue($ul);
		unset($ul);
	}
}
?>