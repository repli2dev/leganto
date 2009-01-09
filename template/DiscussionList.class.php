<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Seznam diskusi v danem tematu.
* @package readerTemplate
*/
class DiscussionList extends Table {
	
	public function __construct($res) {
		parent::__construct();
		$orderName = "title";
		$orderNumDis = "numDis";
		$orderLastDate = "lastDate";
		switch (Page::get("order")) {
			case $orderLastDate:
				$orderLastDate .= " DESC";
				break;
			case $orderName:
				$orderName .= " DESC";
				break;
			case $orderNumDis:
				$orderNumDis .=" DESC";
				break;
		}
		$this->setHead(array(
			new A(Lng::SUBJECT,"discussion.php?action=readTopic&amp;follow=".Page::get("follow")."&amp;type=".Page::get("type")."&amp;order=$orderName",Lng::ORDER),
			new A(Lng::NUMBER_OF_DISCUSSION,"discussion.php?action=readTopic&amp;follow=".Page::get("follow")."&amp;type=".Page::get("type")."&amp;order=$orderNumDis",Lng::ORDER),
			new A(Lng::LAST_DISCUSSION_DATE,"discussion.php?action=readTopic&amp;follow=".Page::get("follow")."&amp;type=".Page::get("type")."&amp;order=$orderLastDate",Lng::ORDER),
		));
		while ($dis = mysql_fetch_object($res)) {
			$this->addRow(array(
				new A($dis->title,"discussion.php?action=readDis&amp;follow=".$dis->id."&amp;type=discussion"),
				new String($dis->numDis),
				new String(String::dateFormatShort($dis->lastDate))
			));
		}
	}
}
?>