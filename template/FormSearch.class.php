<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Formular pro vyhledavani (v hlavicce).
* @package readerTemplate
*/
class FormSearch extends Form {
	
	private $type = "book";
	
	public function __construct($type = "book") {
		$this->type = $type;
		switch($type) {
			default:
				$action = "search.php";
				break;
			case "competition":
				$action = "competition.php";
				break;
			case "writing":
				$action = "writing.php";
				break;
		}
		parent::__construct("search",$action,"get",TRUE);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->setID("search");
		$this->addFieldset();
		$this->addTextInput(FALSE,"searchWord");
		$this->addHiddenInput(FALSE,"action");
		$this->addSubmitButton("searchSubmitButton",Lng::SEARCH);
	}
	
	protected function isSend() {
		return FALSE;
	}
	
	protected function getDataToFill($id) {
		return array("action" => "search");
	}
	
	protected function execute() {
		
	}
	
}