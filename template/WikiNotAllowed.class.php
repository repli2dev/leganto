<?php
class WikiNotAllowed extends Div {
	
	public function __construct() {
		parent::__construct();
		$this->addValue(new H(2,Lng::WIKI_NOT_ALLOWED));
		$this->addValue(new String(Lng::TEXT_WIKI_NOT_ALLOWED,TRUE));
		$res = Wiki::getNotAllowed();
		while ($item = mysql_fetch_object($res)) {
			$wiki = new Div();
			$wiki->setClass("column");
			$allow = new A(
				Lng::WIKI_ALLOW,
				"moderator.php?action=wikiAllow&amp;wiki=".$item->id
			);
			$allow->setClass("admin");
			$wiki->addValue($allow);
			unset($allow);
			$destroy = new A(
				Lng::DELETE,
				"moderator.php?action=wikiDestroy&amp;wiki=".$item->id
			);
			$destroy->setClass("admin");
			$wiki->addValue($destroy);
			unset($destroy);
			$content = new Div();
			$content->setClass("content");
			$book = Book::getInfo($item->book);
			$title = new H(3,new A(
				$book->title,
				"book.php?book=$book->id"
			));
			$title->addValue(new String(" ($book->writerName)"));
			$content->addValue($title);
			unset($title);
			$content->addValue(new String($item->text, TRUE));
			$wiki->addValue($content);
			unset($content);
			$this->addValue($wiki);	
		}
	}
}
?>