<?php
class BookByFavourite extends Column {
	
	public function __construct() {
		parent::__construct();
		$this->addValue(new H(2,Lng::BOOK_BY_FAVOURITE));
		$res = Book::byFavourite();
		$ul = new Ul;
		while($book = mysql_fetch_object($res)) {
			$ul->addLi(new A(
				$book->title." (".$book->userName.")",
				"book.php?book=".$book->id."#opinionInfo_".User::simpleName($book->userName),
				$book->title." - ".$book->writerName
			));
		}
		$this->addValue($ul);
		unset($ul);
	}
}
?>