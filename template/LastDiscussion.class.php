<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Seznam aktualnich diskusi.
* @package readerTemplate
*/
class LastDiscussion extends Div {
	
	public function __construct() {
		parent::__construct();
		$this->setClass("column");
		$this->addValue(new H(2,Lng::LAST_DISCUSSION));
		$res = Discussion::last();
		$ul = new Ul();
		while($dis = mysql_fetch_object($res)) {
			switch($dis->type) {
				case "topic":
					$name = "[".Lng::DISCUSSION."] ".$dis->topicName." ($dis->numDis)";
					$link =	"discussion.php?action=readDis&amp;follow=".$dis->id."&amp;type=discussion";
					break;
				case "discussion":
					$name = "[".Lng::DISCUSSION."] ".$dis->topicName." ($dis->numDis)";
					$link =	"discussion.php?action=readDis&amp;follow=".$dis->follow."&amp;type=".$dis->type;
					break;
				case "book":
					$name = "[".Lng::BOOK."] ".$dis->topicName." ($dis->numDis)";
					$link =	"book.php?book=".$dis->follow."#comment";					
					break;
				case "writing":
					$name = "[".Lng::WRITINGS."] ".$dis->topicName." ($dis->numDis)";
					$link =	"writing.php?action=readOne&amp;id=".$dis->follow."#comment";					
					break;
				case "competition":
					$name = "[".Lng::COMPETITIONS."] ".$dis->topicName." ($dis->numDis)";
					$link =	"competition.php?action=readOne&amp;comp=".$dis->follow."#comment";	
					break;
			}
			$ul->addLi(new A(
				$name,
				$link
			));
		}
		$this->addValue($ul);
		unset($ul);
	}
}
?>