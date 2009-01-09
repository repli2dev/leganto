<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Komentar.
* @package readerTemplate
*/
class CommentInfo extends Div {
	
	/**
	 * @param 	record 	Zobrazovany komentar.
	 * @param	string	Typ komentovane polozky.
	 * @param 	int 	Cislo oznacujici poradi komentare v diskusi.
	 */
	public function __construct($comment,$type,$n) {
		parent::__construct();
		$this->setClass("comment");
		$this->setID("com".$n);
		$info = new Div();
		$info->setClass("info");
		$date = new Span(new String(String::dateFormat($comment->date)));
		$date->setClass("date");
		$info->addValue($date);
		unset($date);
		$owner = Page::session("login");
		if (($owner->level > User::LEVEL_COMMON) || ($owner->id == $comment->userID)) {
			switch($type) {
				case "book":
					$link = "book.php?action=commentDestroy&amp;comment=".$comment->id."&amp;book=".$comment->follow;
					break;
				case "competition":
					$link = "competition.php?action=commentDestroy&amp;comment=".$comment->id."&amp;comp=".$comment->follow;
					break;
			}
			$admin = new A(
				Lng::DELETE,
				$link
			);
			$admin->setClass("admin");
			$admin->addEvent("onclick","return confirm('".Lng::ASSURANCE_COMMENT."');");
			$info->addValue($admin);
			unset($admin);
		}
		$number = new A(
			"(".$n.")",
			"#com$n",
			""
		);
		$number->setClass("number");
		$info->addValue($number);
		unset($number);
		$author = new A(
			$comment->userName,
			"user.php?user=".$comment->userID
		);
		$author->setClass("author");
		$info->addValue($author);
		unset($author);
		$this->addValue($info);
		$content = new Div(new String($comment->text,TRUE));
		$content->setClass("content");
		$this->addValue($content);
		unset($content);
	}
}
?>