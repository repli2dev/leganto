<?php
require_once("include/config.php");

class DiscussionPage extends CommonPage {
	
	public function __construct() {
		parent::__construct();
		$this->setTitle(Lng::DISCUSSION);
		if (Page::get("action") == "discussionDestroy") {
			Discussion::destroy(Page::get("discussion"));
		}
		$res = Discussion::read(Page::get("topic"),Page::get("follow"),Page::get("page"),Page::get("order"));
		if (Page::get("follow")) {
			$disHelp = Discussion::getInfo(Page::get("follow"));
			$this->setTitle(Lng::DISCUSSION." - ".$disHelp->title);
			$this->addContent(new H(2,new String(Lng::DISCUSSION." - ".$disHelp->title)));			
			$this->addContent(new FormDiscussion());
			while($dis = mysql_fetch_object($res)) {
				$this->addContent(new DiscussionInfo($dis));
			}
		}
		else if (Page::get("topic")) {
			$this->addContent(new FormDiscussion());
			$this->setTitle(Lng::DISCUSSION." - ".Lng::DISCUSSION_LIST);
			$this->addContent(new H(2,new String(Lng::DISCUSSION_LIST)));
			$this->addContent(new DiscussionList($res));
		}
		else {
			$this->content->clean();
			$this->setTitle(Lng::DISCUSSION." - ".Lng::TOPIC_LIST);
			$this->addContent(new H(2,new String(Lng::DISCUSSION." - ".Lng::TOPIC_LIST)));
			$this->addContent(new TopicList());
		}
		$this->addContent(new Paging(__FILE__));

	}
}
$page = new DiscussionPage;
$page->view();
?>