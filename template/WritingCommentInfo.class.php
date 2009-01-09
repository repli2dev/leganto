<?php
class WritingCommentInfo extends Div {
	
	/**
	 * @param record Zobrazovany diskusni prispevek.
	 * @param int Cislo oznacujici poradi komentare v diskusi.
	 */
	public function __construct($dis,$level = NULL) {
		parent::__construct();
		$this->setClass("comment");
		if(!empty($dis->parent) OR !$dis->parent == 0){
			$this->addAtribut("style", "margin-left: ".($level*10)."px;");
		}
		$info = new Div();
		$date = new Span(new String(String::dateFormat($dis->date)));
		$date->setClass("date");
		//Answering link
		$owner = Page::session("login");
		if ($owner->level > User::LEVEL_BAN) {
			Page::addJsFile("helper()");
			$answer = new A(
				" ".Lng::ANSWER,
				"writing.php?action=readOne&amp;id=".Page::get("id")."&amp;parent=".$dis->id."&amp;parent_title=".$dis->title."#formWritingComment"
			);
			$answer->addEvent("onclick", "javascript:onClick_fillin_parent('".$dis->id."','".$dis->title."'); return false;");
		}
		unset($owner);
		$date->addValue($answer);
		$info->addValue($date);
		unset($date);
		$owner = Page::session("login");
		if (($owner->level > User::LEVEL_COMMON)) { //mazat můžou pouze moderátoři
			$admin = new A(
				Lng::DELETE,
				"writing.php?action=destroyComment&amp;comment=".$dis->id."&amp;id=".Page::get("id")
			);
			$admin->setClass("admin");
			$admin->addEvent("onclick","return confirm('".Lng::ASSURANCE_DISCUSSION." ".$dis->title."');");
			$info->addValue($admin);
			unset($admin);
		}
		$author = new A(
			$dis->userName,
			"user.php?user=".$dis->userID
		);
		$author->setClass("author");
		$info->addValue($author);
		unset($author);
		if ($dis->title) {
			$title = new Strong(new String($dis->title));
			$title->addAtribut("id", $dis->id);
			$info->addValue($title);
			unset($title);
		}
		$this->addValue($info);
		unset($info);
		$content = new Div(new String($dis->text,TRUE));
		$content->setClass("content");
		$this->addValue($content);
		unset($content);
	}
}
?>