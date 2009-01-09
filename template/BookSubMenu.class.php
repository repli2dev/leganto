<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Podmenu na strance knihy.
* @package readerTemplate
*/
class BookSubMenu extends Div {
	
	public function __construct() {
		parent::__construct();
		$this->setClass("submenu");
		$ul = new Ul;
		$owner = Page::session("login");
			$ul->addLi(new A(
				Lng::RANDOM_BOOK,
				"book.php?action=random"
			));
		if ($owner->level >= User::LEVEL_COMMON) { 
			$ul->addLi(new A(
				Lng::CHANGE_WIKI,
				"book.php?book=".Page::get("book")."&amp;action=formWiki"
			));
			if($toread = ReadList::isToRead(Page::get("book"))){
				$ul->addLi(new A(
					Lng::DEL_THIS_BOOK_FROM_READLIST,
					"book.php?book=".Page::get("book")."&amp;action=fromReadlist"
				));
			} else {
				if(!($op = Opinion::isMine(Page::get("book")))){
					$ul->addLi(new A(
						Lng::ADD_THIS_BOOK_TO_READLIST,
						"book.php?book=".Page::get("book")."&amp;action=toReadlist"
					));
				}
			}
			if ($op = Opinion::isMine(Page::get("book"))) {
				$ul->addLi(new A(
					Lng::CHANGE_OPINION,
					"book.php?book=".Page::get("book")."&amp;opinion=".$op."&amp;action=formBook"
				));
			}
			else {
				$ul->addLi(new A(
					Lng::ADD_THIS_BOOK,
					"book.php?book=".Page::get("book")."&amp;action=formBook"
				));
			}
		}
		$ul->addLi(new A(
			Lng::COMMENTS,
			"#comment"
		));
		$this->addValue($ul);
		unset($ul);
	}
}
?>