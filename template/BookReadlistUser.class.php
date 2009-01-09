<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Tabulka s knihami, ktere precetl dany uzivatel.
* @package readerTemplate
*/
class BookReadlistUser extends BookList {
	
	public function __construct($user) {
		$this->data = ReadList::byUser($user,Page::get("order"),Page::get("page"));
		$this->switcherRead = FALSE;
		$orderWriter = "writerName";
		$orderBook = "title";
		$orderRating = "rating DESC";
		$orderRead = "countRead DESC";
		switch(Page::get("order")) {
			case "":
				$orderBook .= " DESC";
				break;
			case $orderBook:
				$orderBook .= " DESC";
				break;
			case $orderWriter:
				$orderWriter .= " DESC";
				break;
			case $orderRating:
				$orderRating = "rating";
				break;
			case $orderRead:
				$orderRead = "rating";
				break;
		}
		$this->setHead(array(
			new A(Lng::BOOK_TITLE,"user.php?action=userReadlist&amp;user=".Page::get("user")."&amp;order=$orderBook",Lng::ORDER),
			new A(Lng::WRITER,"user.php?action=userReadlist&amp;user=".Page::get("user")."&amp;order=$orderWriter",Lng::ORDER),
			new A(Lng::RATING,"user.php?action=userReadlist&amp;user=".Page::get("user")."&amp;order=$orderRating",Lng::ORDER)
		));
		parent::__construct();
	}

}
?>