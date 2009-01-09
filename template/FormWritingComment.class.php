<?php
/**
* @package readerTemplate
* @author Jan Drábek
* @copyright Jan Drábek 2008
* @link http://ctenari.cz
*/
/**
* Formular pro pridani diskusniho prispevku.
* @package readerTemplate
*/
class FormWritingComment extends Form {
	
	public function __construct() {
		parent::__construct("formWritingComment","?action=".Page::get("action")."&amp;id=".Page::get("id"),"post",FALSE);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->setID("formWritingComment");
		$this->addFieldset(Lng::ADD_DISCUSS);
		$this->data['parent'] = Page::get("parent");
		$this->data['parent_title'] = Page::get("parent_title");
		$this->addTextInput(TRUE,"title",Lng::SUBJECT.":");
		$this->addHiddenInput(FALSE,"parent");
		$a = new A(Lng::REMOVE_PARENT,"writing.php?action=readOne&amp;id=".Page::get("id")."#formWritingComment");
		$a->addEvent("onclick","javascript:onClick_remove_parent(); return false;");
		$this->addTextInput(FALSE,"parent_title",Lng::REPLY_TO.":".$a->getValue(),NULL,TRUE);
		unset($a);
		$this->addTextarea(TRUE,"disContent",Lng::DISCUSSION_CONTENT.":");
		$this->addSubmitButton("formWritingCommentSubmitButton",Lng::ADD_DISCUSS);
	}
	
	protected function isSend() {
		if (Page::post("formWritingCommentSubmitButton")) {
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
		if ($dis = Discussion::create(Page::post("disContent"),Page::get("id"),"writing",Page::post("title"),Page::post("parent")))
		Header("Location: writing.php?action=readOne&id=".$dis->follow."#".$dis->id."");
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