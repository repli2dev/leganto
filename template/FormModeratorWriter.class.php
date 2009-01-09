<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Moderatorsky formular pro spisovatele.
* @package readerTemplate
*/
class FormModeratorWriter extends Form {

	public function __construct($id = NULL) {
		parent::__construct("formModeratorWriter","moderator.php?action=formWriter&amp;writer=$id","post",$id);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->addFieldset(Lng::WRITER_EDIT);
		$this->addTextInput(TRUE,"writer",Lng::TAG.":");
		$this->addSubmitButton("formModeratorWriterSubmitButton",Lng::WRITER_EDIT);
	}
	
	protected function isSend() {
		if (Page::post("formModeratorWriterSubmitButton")) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	protected function getDataToFill($id) {
		$writer = Writer::getInfo($id);
		return array(
			"writer" => $writer->name
		);
	}
	
	protected function execute() {
		if (Page::get("writer")) {
			Writer::change(Page::get("writer"),Page::post("writer"));
		}
		Header("Location: moderator.php");
	}
	
}
?>