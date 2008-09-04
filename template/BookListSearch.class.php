<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* Tato trida slouzi pro zobrazeni tabulky ve vyhledavani.
* @package reader
*/
class BookListSearch extends BookList {
	
	public function __construct() {
		$this->switcherRead = TRUE;
		$this->data = Search::search(Page::get("column"),Page::get("searchWord"),Page::get("order"),Page::get("page"));
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
				$orderRead = "countRead";
				break;
		}
		$this->setHead(array(
			new A(Lng::BOOK_TITLE,"search.php?searchWord=".Page::get("searchWord")."&amp;column=".Page::get("column")."&amp;order=$orderBook",Lng::ORDER),
			new A(Lng::WRITER,"search.php?searchWord=".Page::get("searchWord")."&amp;column=".Page::get("column")."&amp;order=$orderWriter",Lng::ORDER),
			new A(Lng::RATING,"search.php?searchWord=".Page::get("searchWord")."&amp;column=".Page::get("column")."&amp;order=$orderRating",Lng::ORDER),
			new A(Lng::READ,"search.php?searchWord=".Page::get("searchWord")."&amp;column=".Page::get("column")."&amp;order=$orderRead",Lng::ORDER)
		));
		parent::__construct();		
	}
	
	
	
}
?>