<?php
class FormWiki extends Form {
	
	public function __construct($bookID = NULL) {
		parent::__construct("formWiki","book.php?book=".Page::get("book")."&amp;action=formWiki","post",$bookID);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->addFieldset(Lng::CHANGE_WIKI);
		$this->addTextInput(FALSE,"isbn",Lng::ISBN.":");
		$this->addTextarea(TRUE,"wiki",Lng::WIKI.":");
		$this->addSubmitButton("formWikiSubmitButton",Lng::CHANGE_WIKI);
	}
	
	protected function isSend() {
		if (Page::post("formWikiSubmitButton")) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	protected function getDataToFill($bookID) {
		$wiki = Wiki::getByBook($bookID);
		return array(
			"isbn" => $wiki->isbn,
			"wiki" => $wiki->text
		);
	}
	
	protected function execute() {
		if (Page::get("book")) {
			Wiki::create(Page::get("book"),Page::post("wiki"), Page::post("isbn"));
			Header("Location: book.php?book=".Page::get("book"));
		}
	}
}
?>