<?php
class FormTopic extends Form {
	
	public function __construct() {
		parent::__construct("formTopic","admin.php?action=topic&amp;topic=".Page::get("topic"),"post",Page::get("topic"));
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->setID("formTopic");
		if (Page::get("topic")) {
			$legend = Lng::CHANGE_TOPIC;
		}
		else {
			$legend = Lng::ADD_TOPIC;
		}
		$this->addFieldset($legend);
		$this->addTextInput(TRUE,"topicName",Lng::TOPIC.":");
		$this->addSelect(TRUE,"access",array(1 => 1, 2 => 2, 3 => 3, 4 => 4),Lng::ACCESS);
		$this->addSubmitButton("formTopicSubmitButton",$legend);
	}
	
	protected function isSend() {
		if (Page::post("formTopicSubmitButton")) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	protected function getDataToFill($id) {
		$topic = Topic::getInfo($id);
		return array("topicName" => $topic->name);
	}
	
	protected function execute() {
		if (Page::get("topic")) {
			Topic::change(Page::get("topic"),array("name" => Page::post("topicName"), "access" => Page::post("access")));
			$topic = Page::get("topic");			
		}
		else {
			$topic = Topic::create(Page::post("topicName"),Page::post("access"));
			$topic = $topic->id;
		}
		Header("Location: discussion.php?topic=$topic");
	}
	
}
?>