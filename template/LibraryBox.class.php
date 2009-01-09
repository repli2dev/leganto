<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Box s odkazy na vyhledavani v knihovnach.
* @package readerTemplate
*/
class LibraryBox extends Column {
	
	private $res = NULL;
	
	public function __construct($bookID = 0) {
		parent::__construct();
		$owner = Page::session("login");
		if (!empty($bookID) && (!empty($owner->id))) {
			$book = Book::getInfo($bookID);
			$this->res = Library::getByUser($owner->id);
			$this->addValue(new H(2,Lng::LIBRARY_SEARCH));
			$ul = new Ul();
			while ($record = mysql_fetch_object($this->res)) {
				if ($record->ascii == 'yes') {
					$title = String::utf2lowerAscii($book->title);
				}
				else {
					$title = $book->title;
				}
				$change = array(Library::CHANGE => $title);
				$link = strtr($record->link,$change);
				$ul->addLi(new A(
					$record->name,
					$link
				));
			}
			$this->addValue($ul);
			unset($ul);
		}
	}
	
	public function getValue() {
		if (($this->res != NULL) && (mysql_num_rows($this->res) > 0)) {
			return parent::getValue();
		}
	}
}
?>