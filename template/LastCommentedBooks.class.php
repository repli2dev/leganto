<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Naposledy komentovane knihy.
* @package readerTemplate
*/
class LastCommentedBooks extends Div {
	
	public function __construct() {
		parent::__construct();
		$this->setClass("column");
		$this->addValue(new H(2,Lng::COMMENT_LAST));
		$res = Comment::lastCommentedBooks(5);
		$ul = new Ul();
		while($book = mysql_fetch_object($res)) {
			$ul->addLi(new A(
				$book->bookTitle." ($book->comNumber)",
				"book.php?book=".$book->bookID."#comment",
				$book->title
			));
		}
		$this->addValue($ul);
		unset($ul);
	}
}
?>