<?php
require_once("include/config.php");

class BookPage extends CommonPage {
	
	public function __construct() {
		parent::__construct();
		$this->rightColumn->clean();
		switch(Page::get("action")) {
			case "random":
				$book = Book::getRandom();
				Header("Location: book.php?book=".$book."");
				break;
			case "":
				$book = Book::getInfo(Page::get("book"));
				$this->setTitle($book->title." - ".$book->writerName);
				if (Page::get("book")) {
					$this->addRightColumn(new BookSubMenu());
					$this->addRightColumn(new OpinionListBox(Page::get("book")));
					$this->addRightColumn(new SimilarBooksBox(Page::get("book")));
					$this->addRightColumn(new LibraryBox(Page::get("book")));
					$this->addContent(new BookInfo(Page::get("book")));
				}
				break;
			case "formBook":
				$owner = Page::session("login");
				if(!empty($owner->id)){
					$this->addContent(new H(2,Lng::BOOK));
					$this->addContent(new String(Lng::OPINION_WARNING));
					if (Page::get("opinion")) {
						$this->addContent(new FormBook(Page::get("opinion"),FALSE));
					}
					else {
						$this->addContent(new FormBook(Page::get("book")));
					}
					$this->addRightColumn(new LastCommentedBooks());
					$this->addRightColumn(new TagBox());
				} else {
					$this->addContent(new P(Lng::ACCESS_DENIED));
				}
				break;
			case "formWiki":
				$this->setTitle(Lng::CHANGE_WIKI);
				$this->addContent(new FormWiki(Page::get("book")));
				$this->addRightColumn(new LastCommentedBooks());
				$this->addRightColumn(new TagBox());
				break;
			case "commentDestroy":
				Discussion::destroy(Page::get("comment"));
				Header("Location: book.php?book=".Page::get("book")."#comment");
				break;
			case "toReadlist":
				ReadList::create(Page::get("book"));
				Header("Location: book.php?book=".Page::get("book")."");
				break;
			case "fromReadlist":
				ReadList::destroy(Page::get("book"));
				$owner = Page::session("login");
				Header("Location: user.php?action=userReadlist&user=".$owner->id."");
				break;
		}
	}
}
$page = new BookPage();
$page->view();
?>