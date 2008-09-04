<?php
class FormBook extends Form {
	
	/**
	 * @var boolean Nese informaci, zda je upravovana kniha.
	 */
	private $updating = FALSE;
	
	private $new = FALSE;
	
	public function __construct($id = NULL, $new = TRUE) {
		if ($id) {
			if ($new) {
				$this->new = TRUE;	
			}
			else {
				$this->updating = TRUE;
			}
		}
		parent::__construct("formBook","book.php?opinion=".Page::get("opinion")."&amp;action=formBook","post",$id);
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		$titleDisabled = FALSE;
		$writerNameFirstDisabled = FALSE;
		$writerNameSecondDisabled = FALSE;
		if ($this->updating) {
			$action = Lng::CHANGE_OPINION;
			$op = Opinion::getInfo(Page::get("opinion"));
			if (!Book::isOnlyMine($op->bookID)) {
				$titleDisabled = TRUE;
				$writerNameFirstDisabled = TRUE;
				$writerNameSecondDisabled = TRUE;
			}
			unset($op);
		}
		else {
			$action = Lng::ADD_BOOK;
		}
		$this->addFieldset($action);
		Page::addJsFile("helper()");
		$this->addTextInput(TRUE,"bookTitle",Lng::BOOK_TITLE.":"	,NULL,$titleDisabled,Book::getAll(),array("onBlur" => "null"));
		$this->addTextInput(TRUE,"writerNameFirst",Lng::WRITER_NAME_FIRST.":",NULL,$writerNameFirstDisabled,Writer::getNameListFirst());
		$this->addTextInput(TRUE,"writerNameSecond",Lng::WRITER_NAME_SECOND.":",NULL,$writerNameSecondDisabled,Writer::getNameListSecond());
		if (!$this->updating) {
			$this->addTextInput(TRUE,"tag",Lng::TAGS.":");
		}
		$this->addSelect(TRUE,"rating",array(
			Lng::RATING_1 => 1,
			Lng::RATING_2 => 2,
			Lng::RATING_3 => 3,
			Lng::RATING_4 => 4,
			Lng::RATING_5 => 5
		),Lng::RATING.":");
		$this->addTextarea(FALSE,"opinion",Lng::OPINION.":");
		$this->addSubmitButton("formBookSubmitButton",$action);
	}
	
	protected function isSend() {
		if (Page::post("formBookSubmitButton")) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	protected function getDataToFill($id) {
		if ($this->new) {
			$book = Book::getInfo($id);
			return array(
				"bookTitle" => $book->title,
				"writerNameFirst" => Writer::getNameFirst($book->writerName),
				"writerNameSecond" => Writer::getNameSecond($book->writerName)
			);		
		}
		elseif ($this->updating) {
			$op = Opinion::getInfo($id);
			return array(
				"bookTitle" => $op->bookTitle,
				"opinion" => $op->content,
				"writerNameFirst" => Writer::getNameFirst($op->writerName),
				"writerNameSecond" => Writer::getNameSecond($op->writerName),
				""
			);
		}
	}
	
	protected function execute() {
		if (Page::get("opinion")) {
			$changes = array(
				"content" => Page::post("opinion"),
				"rating" => Page::post("rating")
			);
			$op = Opinion::getInfo(Page::get("opinion"));
			if (Book::isOnlyMine($op->bookID)) {
				$changes["bookTitle"] = Page::post("bookTitle");
				$changes["writerNameFirst"] = Page::post("writerNameFirst");
				$changes["writerNameSecond"] = Page::post("writerNameSecond");
			} 
			Opinion::change(Page::get("opinion"),$changes);
			Header("Location: book.php?book=".$op->bookID);
		}
		else {
			Opinion::create(Page::post("bookTitle"),Page::post("writerNameFirst"),Page::post("writerNameSecond"),Page::post("opinion"),Page::post("rating"),Page::post("tag"));
			Header("Location: user.php");
		}
	}
	
	public function view() {
		parent::view();
		$string = new String(Lng::TEXT_FORMAT_TEXT,FALSE);
		$string->view();
		unset($string);
	}
}
?>
