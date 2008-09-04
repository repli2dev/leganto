<?php
class LastDiscussion extends Div {
	
	public function __construct() {
		parent::__construct();
		$this->setClass("column");
		$this->addValue(new H(2,Lng::LAST_DISCUSSION));
		$res = Discussion::last();
		$ul = new Ul();
		while($dis = mysql_fetch_object($res)) {
			$ul->addLi(new A(
				$dis->title." ($dis->numDis)",
				"discussion.php?topic=".$dis->topicID."&amp;follow=".$dis->id
			));
		}
		$this->addValue($ul);
		unset($ul);
	}
}
?>