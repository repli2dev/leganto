<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Formular pro prihlaseni.
* @package readerTemplate
*/
class FormLogIn extends Form {
	
	public function __construct() {
		parent::__construct("login","","post",FALSE);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->setID("login");
		$this->addFieldset(Lng::LOG_IN);
		$this->addTextInput(FALSE,"name",Lng::NAME.":");
		$this->addPasswordInput(FALSE,"password",Lng::PASSWORD.":");
		$this->addCheckboxInput(FALSE,"rememberMe",Lng::REMEMBER_ME,TRUE);
		$this->addSubmitButton("loginSubmitButton",Lng::LOG_IN);
	}
	
	protected function isSend() {
		if (Page::post("loginSubmitButton")) {
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
		if (User::logIn(Page::post("name"),Page::post("password"),Page::post("rememberMe"))) {
			Header("Location: index.php");
		}
		else {
			$this->renderForm("login","","post",FALSE);
		}
	}
	
}
?>