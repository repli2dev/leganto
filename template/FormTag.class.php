<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Formular pro pridani klicovych slov ke knize.
* @package readerTemplate
*/
class FormTag extends Form {
	
	private $type = "book";
	
	private $action;
	
	private $id;
	
	private $redir;
	
	public function __construct($type = "book") {
		$this->type = $type;
		switch($type) {
			default:
				$this->id = Page::get("book");
				$this->action = "book.php?book=".Page::get("book");
				$this->redir = $this->action;
				break;
			case "writing":
				$this->id = Page::get("id");
				$this->action = "writing.php?action=readOne&amp;id=".Page::get("id");
				$this->redir = "writing.php?action=readOne&id=".Page::get("id");
				break;
			case "competition":
				$this->id = Page::get("comp");
				$this->action = "competition.php?action=readOne&amp;comp=".Page::get("comp");
				$this->redir = "competition.php?action=readOne&comp=".Page::get("comp");
				break;
		}
		parent::__construct("formTag",$action,"post",FALSE);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->setID("addTagForm");
		$this->addFieldset();
		$this->addTextInput(TRUE,"tagName",Lng::TAG.":");
		$this->addSubmitButton("formTagSubmitButton",Lng::ADD_TAG);
	}
	
	protected function isSend() {
		if (Page::post("formTagSubmitButton")) {
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
		TagReference::create(Page::post("tagName"),$this->id,$this->type);
		Header("Location: $this->redir");
	}
	
	public function getValue() {
		$owner = Page::session("login");
		if ($owner->id) {
			return parent::getValue();
		}
	}
}
?>