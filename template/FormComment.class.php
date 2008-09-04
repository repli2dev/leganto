<?php
class FormComment extends Form {
	
	public function __construct() {
		parent::__construct("formComment","book.php?book=".Page::get("book"),"post",FALSE);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->addFieldset(Lng::ADD_COMMENT);
		$this->addTextarea(TRUE,"commentContent",Lng::COMMENT.":");
		$this->addSubmitButton("formCommentSubmitButton",Lng::ADD_COMMENT);
	}
	
	protected function isSend() {
		if (Page::post("formCommentSubmitButton")) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	protected function getDataToFill($id) {
		return array();
	}
	
	protected function execute() {
		Comment::create(Page::get("book"),Page::post("commentContent"));
		Header("Location: book.php?book=".Page::get("book")."#comment");
	}
	
	public function view() {
		$owner = Page::session("login");
		if ($owner->id) {
			parent::view();
			$string = new String(Lng::TEXT_FORMAT_TEXT,FALSE);
			$string->view();
			unset($string);
		}
	}
}
?>