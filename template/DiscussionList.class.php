<?php
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
			new A(Lng::SUBJECT,"discussion.php?topic=".Page::get("topic")."&amp;order=$orderName",Lng::ORDER),
			new A(Lng::NUMBER_OF_DISCUSSION,"discussion.php?topic=".Page::get("topic")."&amp;order=$orderNumDis",Lng::ORDER),
			new A(Lng::LAST_DISCUSSION_DATE,"discussion.php?topic=".Page::get("topic")."&amp;order=$orderLastDate",Lng::ORDER),
		));
		while ($dis = mysql_fetch_object($res)) {
			$this->addRow(array(
				new A($dis->title,"discussion.php?topic=".Page::get("topic")."&amp;follow=".$dis->id),
				new String($dis->numDis),
				new String(String::dateFormatShort($dis->lastDate))
			));
		}
	}
}
?>