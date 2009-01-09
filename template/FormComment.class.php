<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Formular pro pridani komentare.
* @package readerTemplate
*/
class FormComment extends Form {
	
	private $type;
	
	/**			
	 * 			Konstruktor
	 * @param	string	Typ polozky, ktera ma byt komentovana.
	 */
	public function __construct($type = "book") {
		$this->type = $type;
		switch($type) {
			case "book":
				parent::__construct("formComment","book.php?book=".Page::get("book"),"post",NULL);
				break;
			case "competition":
				parent::__construct("formComment","competition.php?action=readOne&amp;comp=".Page::get("comp"),"post",NULL);
				break;
		}
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$this->addFieldset(Lng::ADD_COMMENT);
		$this->addTextarea(TRUE,"commentContent",Lng::COMMENT.":");
		$this->addSubmitButton("formCommentSubmitButton",Lng::ADD_COMMENT);
	}
	
	protected function isSend() {
		if (Page::post("formCommentSubmitButton")) {
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
		switch($this->type) {
			case "book":
				$follow = Page::get("book");
				$location = "book.php?book=".Page::get("book")."#comment";
				break;
			case "competition":
				$follow = Page::get("comp");
				$location = "competition.php?action=readOne&comp=".Page::get("comp")."#comment";
				break;
		}
		Discussion::create(Page::post("commentContent"),$follow,$this->type);
		Header("Location: $location");
	}
	
	public function getValue() {
		$owner = Page::session("login");
		if ($owner->id) {
			$string = new String(Lng::TEXT_FORMAT_TEXT,FALSE);
			$this->addValue($string);
			unset($string);
			return parent::getValue();
		}
	}
}
?>