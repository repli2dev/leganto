<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* Formular pro pridani diskusniho prispevku.
* @package reader
*/
class FormDiscussion extends Form {
	
	public function __construct() {
		parent::__construct("formDiscussion","?topic=".Page::get("topic")."&amp;follow=".Page::get("follow"),"post",FALSE);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->setID("formDiscussion");
		$this->addFieldset(Lng::ADD_DISCUSS);
		$follow = Page::get("follow");
		if (empty($follow)) {
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
		$dis = Discussion::create(Page::get("topic"),Page::post("disContent"),Page::get("follow"),Page::post("title"));
		if (Page::get("follow")) {
			$follow = Page::get("follow");
		}
		else {
			$follow = $dis->id;
		}
		Header("Location: discussion.php?topic=".Page::get("topic")."&follow=$follow");
	}
	
	public function view() {
		$owner = Page::session("login");
		if ($owner->level > User::LEVEL_BAN) {
			parent::view();
			$string = new String(Lng::TEXT_FORMAT_TEXT,FALSE);
			$string->view();
			unset($string);
		}
	}
	
}
?>