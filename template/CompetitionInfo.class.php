<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Literární soutěž	
* @package readerTemplate
*/
class CompetitionInfo extends Div {
	
	public function __construct($comp) {
		parent::__construct();
		$this->setClass("competition");
		
		
		// Nadpis souteze
		$this->addValue(new H(2,new A(
			$comp->name,
			"competition.php?action=readOne&amp;comp=$comp->id"
		)));
		$info = new Div();
		
		// Obsah prispevku
		$content = new Div(new String($comp->content,TRUE));
		$content->setClass("content");
		$this->addValue($content);
		unset($content);
		
		// Datum
		$date = new Span(new String(String::dateFormat($comp->date)));
		$date->setClass("date");
		$info->addValue($date);
		unset($date);
		
		
		// Autor prispevku
		$author = new A(
			$comp->userName,
			"user.php?user=".$comp->userID
		);
		$author->setClass("author");
		$info->addValue($author);
		unset($author);
		
		$this->addValue($info);
		unset($info);
	}
}
?>