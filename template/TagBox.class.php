<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* Tato trida slouzi jako predek pro zobrazeni boxu s klicovymi slovy.
* @package reader
*/
class TagBox extends Div {
	
	public function __construct($book = 0) {
		parent::__construct();
		$this->setClass("column");
		$this->addValue(new H("2",Lng::TAGS));
		if (!$book) {
			$res = Tag::getListTop();
		}
		else {
			$res = Tag::getByBook($book);
		}
		$p = new P;
		while($tag = mysql_fetch_object($res)) {
			$a = new A(
				$tag->name,
				"search.php?searchWord=".$tag->name."&amp;column=tag"
			);
			$a->setClass("tag".$tag->size);
			$p->addValue($a);
			unset($a);
		}
		$this->addValue($p);
		unset($p);
	}
}
?>