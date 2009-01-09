<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Informace o knize.
* @package readerTemplate
*/
class BookInfo extends Div {
	
	public function __construct($book) {
		parent::__construct();
		$this->setID("bookInfo");
		$book = Book::getInfo($book);
		$title = new H(2,new String($book->title." - "));
		$title->addValue(new A(
			$book->writerName,
			"search.php?searchWord=".$book->writerName
		));
		$this->addValue($title);
		unset($title);
		$this->addValue(new P(new Img("image/rating_".$book->rating.".png")));
		$wiki = Wiki::getByBook($book->id);
		if ($wiki->id) {
			$wikiInfo = new Div();
			$wikiInfo->setID("wikiInfo");
			if ($wiki->isbn) {
				$ul = new Ul();
				$li = new Li(new Strong(Lng::ISBN.": "));
				$li->addValue(new String($wiki->isbn));
				$ul->addValue($li);
				unset($li);
				$wikiInfo->addValue($ul);
				unset($ul);
			}
			$wikiInfo->addValue(new String($wiki->text,TRUE));
			unset($wiki);
			$this->addValue($wikiInfo);
			unset($wikiInfo);
		}
		$this->addValue(new TagBox("book",$book->id));	
		$this->addValue(new FormTag());
		$res = Opinion::getListByBook($book->id);
		while ($opinion = mysql_fetch_object($res)) {
			if (($opinion->content != "") && ($opinion->content != Lng::OPINION)) {
				$this->addValue(new OpinionInfo($opinion));
			}
		}
		$this->addValue(new Comments($book->id,"book"));
		$this->addValue(new FormComment());
	}
}
?>