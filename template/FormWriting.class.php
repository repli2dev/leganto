<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Formular pro pridani diskusniho prispevku.
* @package readerTemplate
*/
class FormWriting extends Form {

	
	private $update = FALSE;
	
	public function __construct($id = NULL) {
		if ($id) {
			$this->update = TRUE;
		}
		parent::__construct("formWriting","writing.php?action=addWriting&id=".Page::get("id"),"post",$id);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->setID("formWriting");
		if ($this->update) {
			$legend = Lng::CHANGE_WRITING;
		}
		else {
			$legend = Lng::ADD_WRITING;
		}
		$this->addFieldset($legend);
		$id = Page::get("id");
		$this->addTextInput(TRUE,"title",Lng::WRITING_TITLE.":");
		$this->addTextInput(FALSE,"link",Lng::LINK.":");
		if (!$this->update) {
			$this->addTextInput(TRUE,"tags",Lng::TAGS.":");
		}
		$this->addTextarea(TRUE,"disContent",Lng::WRITING_TEXT.":");
		$this->addSubmitButton("formWritingSubmitButton",$legend);
	}	
	
	protected function isSend() {
		if (Page::post("formWritingSubmitButton")) {
			return TRUE;
		}
		else {
			FALSE;
		}
	}
	
	protected function getDataToFill($id) {
		if($this->update){
			$row = Writing::getInfo($id);
			$data = array("title" => $row->title, "link" => $row->link, "disContent" => $row->text);
			return $data;
		}
	}
	
	protected function execute() {
		$id = Page::get("id");
		if(empty($id)){
			$dis = Writing::create(Page::post("title"),Page::post("disContent"),Page::post("link"),Page::post("tags"));
		} else {
			$dis = Writing::change(Page::post("title"),Page::post("disContent"),Page::post("link"),Page::get("id"));
			$dis->id = Page::get("id");
		}
		Header("Location: writing.php?action=readOne&id=".$dis->id);
	}
	
	public function getValue() {
		$owner = Page::session("login");
		if ($owner->level > User::LEVEL_BAN) {
			$string = new String(Lng::TEXT_FORMAT_TEXT,FALSE);
			$this->addValue($string);
			unset($string);
			return parent::getValue();
		}
	}	
}
?>