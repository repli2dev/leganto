<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Box s podobnymi knihami k dane knize.
* @package readerTemplate
*/
class SimilarBooksBox extends Div {

	private $switcherView = FALSE;
	
	public function __construct($book) {
		parent::__construct();
		$this->setID("similarBooksBox");
		$this->setClass("column");
		$this->addValue(new H(2,new String(Lng::SIMILAR_BOOKS)));
		$ul = new Ul();
		$res = Book::getSimilar($book);
		while($book = mysql_fetch_object($res)) {
			$ul->addLi(new A(
				$book->title,
				"book.php?book=".$book->id
			));	
			$this->switcherView = TRUE;
		}
		$this->addValue($ul);
		unset($ul);
	}
	
	public function view() {
		if ($this->switcherView) {
			parent::view();
		}
	}
}
?>