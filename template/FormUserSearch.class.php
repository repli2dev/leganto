<?php
class FormUserSearch extends Form {
	
	public function __construct() {
		parent::__construct("formUserSearch","user.php?action=search","post",TRUE);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->addFieldset(Lng::SEARCH);
		$this->addTextInput(FALSE,"searchWord",Lng::USER_NAME.":");
		$this->addSubmitButton("formUserSearchSubmitButton",Lng::SEARCH);
	}
	
	protected function isSend() {
		if (Page::post("formUserSearchSubmitButton")) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	protected function getDataToFill($bookID) {
		return array(
			"searchWord" => Page::get("searchWord")
		);
	}
	
	protected function execute() {
		Header("Location: user.php?action=search&searchWord=".Page::post("searchWord"));
	}
}
?>