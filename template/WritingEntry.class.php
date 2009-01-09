<?php
/**
* @package readerTemplate
* @author Jan Drabek
* @copyright Jan Drabek 2008
* @link http://ctenari.cz
*/
/**
* Vlastní tvorba
* @package readerTemplate
*/
class WritingEntry extends Div {
	
	public function __construct($entry,$full = FALSE) {
		parent::__construct();
		$this->setClass("entry");
		
		// Titulek zápisku
		if(!empty($entry->link)){
			$url = $entry->link;
		} else {
			$url = "writing.php?action=readOne&amp;id=".$entry->id."";
		}
		$this->addValue(new H(2,new a(
			$entry->title,
			$url
		)));
		$info = new Div();
		
		//Odkaz více...
		if($full == FALSE OR !empty($entry->link)){
			if(!empty($entry->link)){
				$url = $entry->link;
			} else {
				$url = "writing.php?action=readOne&amp;id=".$entry->id."";
			}
			$a = new A(
						Lng::MORE,
						$url
			);
			$a->setClass("more");
			$info->addvalue($a);
		}
		// Pokud je prihlaseny uzivatel moderator nebo vlastnik prispevku, ma moznost jej smazat
		$owner = Page::session("login");
		if (($owner->level > User::LEVEL_COMMON) || ($owner->id == $entry->user)) {
			$admin = new A(
				Lng::DELETE,
				"writing.php?action=destroyWriting&amp;id=".$entry->id.""
			);
			$admin->setClass("admin");
			$admin->addEvent("onclick","return confirm('".Lng::ASSURANCE_WRITING." ".$entry->title."');");
			$info->addValue($admin);
			unset($admin);
		}
		// Autor prispevku
		$author = new A(
			$entry->userName,
			"user.php?user=".$entry->user
		);
		$author->setClass("author");
		$info->addValue($author);
		unset($author);
		
		// Datum
		$date = new Span(new String(String::dateFormat($entry->date)));
		$date->setClass("date");
		$info->setClass("info");
		$info->addValue($date);
		unset($date);
		
		
		
		
		// Obsah prispevku
		if($full == FALSE AND empty($entry->link)){
			$entry->text = String::trimText($entry->text,300);
		}
		$content = new Div(new String($entry->text,TRUE));
		$content->setClass("content");
		$this->addValue($content);
		//info
		$this->addValue($info);
		unset($info);
		unset($content);
	}

}
?>