<?php

class FormChangeIco extends Form {
	
	public function __construct() {
		parent::__construct("formChangeIco","","post",FALSE,"multipart/form-data");
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->setID("formChangeIco");
		$this->addFieldset(Lng::CHANGE_ICO);
		$this->addFiletInput("ico",Lng::ICO);
		$this->addSubmitButton("formChangeIcoSubmitButton",Lng::CHANGE_ICO);
	}
	
	protected function isSend() {
		if (Page::post("formChangeIcoSubmitButton")) {
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
		if ($_FILES["ico"]) {
			if (User::changeIco($_FILES["ico"])) {
				Header("Location: user.php");
			}
		}
	}
}
?>