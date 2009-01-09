<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Moderatorsky formular pro klicova slova.
* @package readerTemplate
*/
class FormModeratorTag extends Form {

	public function __construct($id = NULL) {
		parent::__construct("formModeratorTag","moderator.php?action=formTag&amp;tag=$id","post",$id);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->addFieldset(Lng::TAG_EDIT);
		$this->addTextInput(TRUE,"tag",Lng::TAG.":");
		$this->addSubmitButton("formModeratorTagSubmitButton",Lng::TAG_EDIT);
	}
	
	protected function isSend() {
		if (Page::post("formModeratorTagSubmitButton")) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	protected function getDataToFill($id) {
		$tag = Tag::getInfo($id);
		return array(
			"tag" => $tag->name
		);
	}
	
	protected function execute() {
		if (Page::get("tag")) {
			Tag::change(Page::get("tag"),Page::post("tag"));
		}
		Header("Location: moderator.php");
	}
	
}
?>