<?php
class BookSubMenu extends Div {
	
	public function __construct() {
		parent::__construct();
		$this->setClass("submenu");
		$ul = new Ul;
		$owner = Page::session("login");
		if ($owner->level >= User::LEVEL_COMMON) { 
			$ul->addLi(new A(
				Lng::CHANGE_WIKI,
				"book.php?book=".Page::get("book")."&amp;action=formWiki"
			));
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