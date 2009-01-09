<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Moderatorsky formular pro vyhledavani.
* @package readerTemplate
*/
class FormModeratorSearch extends Form {
	
	public function __construct() {
		parent::__construct("formModerator","moderator.php","get",TRUE);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->addFieldset(Lng::SEARCH);
		$this->addTextInput(FALSE,"searchWord",Lng::SEARCH_VALUE);
		$this->addSelect(TRUE,"searchColumn",array(
			Lng::BOOK => "book",
			Lng::WRITER => "writer",
			Lng::TAG => "tag"
		),Lng::SEARCH_ITEM);
		$this->addSubmitButton("formModeratorSubmitButton",Lng::SEARCH);
	}
	
	protected function isSend() {
		if (Page::get("formModeratorSubmitButton")) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	protected function getDataToFill($id) {
		return array("searchWord" => Page::get("searchWord"));
	}
	
	protected function execute() {
		Header("Location: moderator.php?searchWord=".Page::get("searchWord")."&searchColumn=".Page::get("searchColumn"));
	}
}
?>