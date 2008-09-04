<?php
class TopicList extends Table {
	
	public function __construct() {
		parent::__construct();
		$res = Topic::read(Page::get("order"),Page::get("page"));
		$orderName = "name";
		$orderNumDis = "numDis";
		switch (Page::get("order")) {
			case "":
			case $orderName:
				$orderName .= " DESC";
				break;
			case $orderNumDis:
				$orderNumDis .=" DESC";
				break;
		}
		$this->setHead(array(
			new A(Lng::TOPIC,"discussion.php?order=$orderName",Lng::ORDER),
			new A(Lng::NUMBER_OF_DISCUSSION_IN_TOPIC,"discussion.php?order=$orderNumDis",Lng::ORDER)
		));
		while ($topic = mysql_fetch_object($res)) {
			$this->addRow(array(
				new A($topic->name,"discussion.php?topic=".$topic->id."&amp;order=lastDate DESC"),
				new String($topic->numDis)
			));
		}
	}
}
?>