<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Formular pro zmenu osobni ikonky uzivatele.
* @package readerTemplate
*/
class FormLibraryMenu extends Form {
	
	
	public function __construct() {
		parent::__construct("formLibraryMenu","","post");
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$res = Library::getAll();
		$this->addFieldset(Lng::LIBRARY_SEARCH_SET);
		while ($record = mysql_fetch_object($res)) {
			$this->addSelect(
				TRUE,
				$record->id,
				array(Lng::SHOW => "yes",Lng::SHOW_NOT => "no"),
				$record->name.":"
			);
		}
		$this->addSubmitButton("formLibraryMenuSubmitButton",Lng::LIBRARY_SEARCH_SET);
		
	}
	
	protected function isSend() {
		if (Page::post("formLibraryMenuSubmitButton")) {
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
		unset($_POST["formLibraryMenuSubmitButton"]);
		if (LibraryMenu::create($_POST)) {
			Header("Location: user.php");
		}
	}
}
?>