<?php
class OpinionListBox extends Div {
	
	public function __construct($book) {
		parent::__construct();
		$this->setClass("column");
		$this->addValue(new H(2,new String(Lng::BOOK_WAS_READ)));
		$res = Opinion::getListByBook($book);
		$p = new P();
		while ($opinion = mysql_fetch_object($res)) { 
			if (($opinion->content != "") && ($opinion->content != Lng::OPINION)) {
				$item = new Strong(new A(
					$opinion->userName,
					"book.php?book=".$book."#opinionInfo_".User::simpleName($opinion->userName),
					$opinion->userName." (".$opinion->rating.")"
				));
			}
			else {
				$item = new A(
					$opinion->userName,
					"user.php?user=".$opinion->userID,
					$opinion->userName." (".$opinion->rating.")"
				);
			}
			$p->addValue($item);
		}
		$this->addValue($p);
	}
}
?>