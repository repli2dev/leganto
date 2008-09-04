<?php
class FormMessage extends Form {
	
	public function __construct() {
		parent::__construct("formMessage","message.php","post",FALSE);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->addFieldset(Lng::SEND_MESSAGE);
		$this->addTextInput(TRUE,"userName",Lng::USER_NAME.":",NULL,NULL,User::getAllName());
		$this->addTextarea(TRUE,"message",Lng::MESSAGE.":");
		$this->addSubmitButton("formMessageSubmitButton",Lng::SEND_MESSAGE);
	}
	
	protected function isSend() {
		if (Page::post("formMessageSubmitButton")) {
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
		Message::send(Page::post("userName"),Page::post("message"));
		Header("Location: message.php");
	}
}
?>