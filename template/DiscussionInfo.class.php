<?php
class DiscussionInfo extends Div {
	
	/**
	 * @param record Zobrazovany diskusni prispevek.
	 * @param int Cislo oznacujici poradi komentare v diskusi.
	 */
	public function __construct($dis) {
		parent::__construct();
		$this->setClass("comment");
		$info = new Div();
		$date = new Span(new String(String::dateFormat($dis->date)));
		$date->setClass("date");
		//Answering link
		$owner = Page::session("login");
		if ($owner->level > User::LEVEL_BAN) {
			Page::addJsFile("helper()");
			if(empty($dis->title)){
				$new_title = "Re: ".$dis->userName;
			} else {
				$temp = str_replace("Re: ","",$dis->title);
				$temp2 = explode(" (",$temp);
				$new_title = "Re: ".$temp2[0]." (".$dis->userName.")"; 
			}
			$answer = new A(
				" ".Lng::ANSWER,
				"javascript:onClick_fillin_title('".$new_title."')"
			);
		}
		unset($owner);
		$date->addValue($answer);
		$info->addValue($date);
		unset($date);
		$owner = Page::session("login");
		if (($owner->level > User::LEVEL_COMMON) || ($owner->id == $dis->userID)) {
			$admin = new A(
				Lng::DELETE,
				"discussion.php?action=discussionDestroy&amp;discussion=".$dis->id."&amp;topic=".Page::get("topic")."&amp;follow=".Page::get("follow")
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
			$info->addValue(new Strong(new String(" - ".$dis->title)));
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