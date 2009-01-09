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
class FormCompetition extends Form {

	
	private $id = FALSE;
	
	public function __construct($id = NULL) {
		$this->id = $id;
		parent::__construct("formCompetition","competition.php?action=formCompetition&amp;comp=$id","post",$id);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->setID("formCompetition");
		if ($this->id) {
			$legend = Lng::CHANGE_COMPETITION;
		}
		else {
			$legend = Lng::ADD_COMPETITION;
		}
		$this->addFieldset($legend);
		$follow = Page::get("follow");
		$this->addTextInput(TRUE,"name",Lng::COMPETITION_NAME.":");
		$this->addTextInput(TRUE,"expiration",Lng::EXPIRATION_DATE.":");
		if (!$this->id) {
			$this->addTextInput(TRUE,"tags",Lng::TAGS.":");
		}
		$this->addTextarea(TRUE,"content",Lng::COMPETITION_INFO.":");
		$this->addSubmitButton("formCompetitionSubmitButton",$legend);
	}	
	
	protected function isSend() {
		if (Page::post("formCompetitionSubmitButton")) {
			return TRUE;
		}
		else {
			FALSE;
		}
	}
	
	protected function getDataToFill($id) {
		$comp = Competition::getInfo($id);
		return array("name" => ($comp->name),
			"content" => ($comp->content),
			"expiration" => String::dateFormatFromShortToShort($comp->expiration)
		);
	}
	
	protected function execute() {
		$input = array(
			"name" => Page::post("name"),
			"content" => Page::post("content"),
			"expiration" => Page::post("expiration")
		);
		if (!$this->id) {
			$input["tags"] = Page::post("tags");
			if ($comp = Competition::create($input)) {
				Header("Location: competition.php?comp=".$comp->id);
			}
			else {
				$this->getData($id,"post");
				$this->renderForm("formCompetition","competition.php?action=formCompetition","post",NULL);
			}
		}
		else {
			if (Competition::change($this->id,$input)) {				
				Header("Location: competition.php?comp=".$comp->id);
			}
			else {
				$this->getData($id,"post");
				$this->renderForm("formCompetition","competition.php?action=formCompetition","post",NULL);
			}
		}
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
