<?php
class FormTag extends Form {
	
	public function __construct() {
		parent::__construct("formTag","book.php?book=".Page::get("book"),"post",FALSE);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->setID("addTagForm");
		$this->addFieldset();
		$this->addTextInput(TRUE,"tagName",Lng::TAG.":");
		$this->addSubmitButton("formTagSubmitButton",Lng::ADD_TAG);
	}
	
	protected function isSend() {
		if (Page::post("formTagSubmitButton")) {
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
		TagReference::create(Page::post("tagName"),Page::get("book"));
		Header("Location: book.php?book=".Page::get("book"));
	}
	
	public function view() {
		$owner = Page::session("login");
		if ($owner->id) {
			parent::view();
		}
	}
}
?>