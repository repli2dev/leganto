<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Formular pro pridani diskusniho prispevku.
* @package readerTemplate
*/
class FormDiscussion extends Form {
	
	public function __construct() {
		parent::__construct("formDiscussion","?action=".Page::get("action")."&amp;follow=".Page::get("follow")."&type=".Page::get("type"),"post",FALSE);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->setID("formDiscussion");
		$this->addFieldset(Lng::ADD_DISCUSS);
		if (Page::get("type") == "topic") {
			$titleImportant = TRUE;
		}
		else {
			$titleImportant = FALSE;
		}
		$this->addTextInput($titleImportant,"title",Lng::SUBJECT.":");
		$this->addTextarea(TRUE,"disContent",Lng::DISCUSSION_CONTENT.":");
		$this->addSubmitButton("formDiscussionSubmitButton",Lng::ADD_DISCUSS);
	}
	
	protected function isSend() {
		if (Page::post("formDiscussionSubmitButton")) {
			return TRUE;
		}
		else {
			FALSE;
		}
	}
	
	protected function getDataToFill($id) {
		return array();
	}
	
	protected function execute() {
		$dis = Discussion::create(Page::post("disContent"),Page::get("follow"),Page::get("type"),Page::post("title"));
		if($dis) {
			Header("Location: discussion.php?action=readDis&follow=".Page::get("follow")."&type=".Page::get("type"));	
		}
	}
	
	public function getValue() {
		$owner = Page::session("login");
		if ($owner->level > User::LEVEL_BAN) {
			$string = new String(Lng::TEXT_FORMAT_TEXT,FALSE);
			$this->addValue($string);
			unset($string);
			return parent::getValue();
		}
	}
	
}
?>