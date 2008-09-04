<?php
class FormSearch extends Form {
	
	public function __construct() {
		parent::__construct("search","search.php","get",FALSE);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->setID("search");
		$this->addFieldset();
		$this->addTextInput(FALSE,"searchWord");
		$this->addSubmitButton("searchSubmitButton",Lng::SEARCH);
	}
	
	protected function isSend() {
		return FALSE;
	}
	
	protected function getDataToFill($id) {
		return array();
	}
	
	protected function execute() {
		
	}
	
}