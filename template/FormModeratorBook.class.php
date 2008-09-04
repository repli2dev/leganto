<?php
class FormModeratorBook extends Form {

	
	public function __construct($id = NULL) {
		parent::__construct("formModeratorBook","moderator.php?action=formBook&amp;book=$id","post",$id);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->addFieldset(Lng::BOOK_EDIT);
		$this->addTextInput(TRUE,"bookTitle",Lng::BOOK_TITLE.":");
		$this->addTextInput(TRUE,"writerName",Lng::WRITER.":");
		$this->addSubmitButton("formModeratorBookSubmitButton",Lng::BOOK_EDIT);
	}
	
	protected function isSend() {
		if (Page::post("formModeratorBookSubmitButton")) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	protected function getDataToFill($id) {
		$book = Book::getInfo($id);
		return array(
			"bookTitle" => $book->title,
			"writerName" => $book->writerName
		);
	}
	
	protected function execute() {
		if (Page::get("book")) {
			Book::change(Page::get("book"),Page::post("bookTitle"),Page::post("writerName"));
		}
		Header("Location: moderator.php");
	}
}
?>