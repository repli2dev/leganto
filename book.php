<?php
require_once("include/config.php");

class BookPage extends CommonPage {
	
	public function __construct() {
		parent::__construct();
		$this->rightColumn->clean();
		switch(Page::get("action")) {
			case "":
				$book = Book::getInfo(Page::get("book"));
				$this->setTitle($book->title." - ".$book->writerName);
				if (Page::get("book")) {
					$this->addRightColumn(new BookSubMenu());
					$this->addRightColumn(new OpinionListBox(Page::get("book")));
					$this->addRightColumn(new SimilarBooksBox(Page::get("book")));
					$this->addContent(new BookInfo(Page::get("book")));
				}
				break;
			case "formBook":
				if (Page::get("opinion")) {
					$this->addContent(new FormBook(Page::get("opinion"),FALSE));
				}
				else {
					$this->addContent(new FormBook(Page::get("book")));
				}
				$this->addRightColumn(new LastCommentedBooks());
				$this->addRightColumn(new TagBox());
				break;
			case "formWiki":
				$this->setTitle(Lng::CHANGE_WIKI);
				$this->addContent(new FormWiki(Page::get("book")));
				$this->addRightColumn(new LastCommentedBooks());
				$this->addRightColumn(new TagBox());
				break;
			case "commentDestroy":
				Comment::destroy(Page::get("comment"));
				Header("Location: book.php?book=".Page::get("book")."#comment");
				break;
		}
	}
}
$page = new BookPage();
$page->view();
?>