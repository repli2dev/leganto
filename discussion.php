<?php
require_once("include/config.php");

class DiscussionPage extends CommonPage {
	
	public function __construct() {
		parent::__construct();
		$owner = Page::session("login");
		$this->setTitle(Lng::DISCUSSION);
		if (Page::get("action") == "discussionDestroy") {
			if (Discussion::destroy(Page::get("discussion"))) {
				Header("Location: discussion.php?topic=".Page::get("topic")."&follow=".Page::get("follow"));
			}
		}
		$action = Page::get("action");
		switch($action) {
			case "":
				$this->content->clean();
				$this->setTitle(Lng::DISCUSSION." - ".Lng::TOPIC_LIST);
				$this->addContent(new H(2,new String(Lng::DISCUSSION." - ".Lng::TOPIC_LIST)));
				$this->addContent(new TopicList());
				break;
			case "readTopic":
				$res = Discussion::read(Page::get("follow"),Page::get("type"),Page::get("page"),Page::get("order"));
	            $this->addContent(new H(2,new String(Lng::DISCUSSION)));
				if(!empty($owner->id)){
					$this->addContent(new String(Lng::DISCUSSION_LIST_WARNING));
				}
				$this->addContent(new FormDiscussion());
				$this->setTitle(Lng::DISCUSSION." - ".Lng::DISCUSSION_LIST);
				$this->addContent(new H(2,new String(Lng::DISCUSSION_LIST)));
				$this->addContent(new DiscussionList($res));				
				break;
			case "readDis":
				$disHelp = Discussion::getInfo(Page::get("follow"));
				$this->setTitle(Lng::DISCUSSION." - ".$disHelp->title);
				$this->addContent(new H(2,new String(Lng::DISCUSSION." - ".$disHelp->title)));
				$this->addContent(new DiscussionInfo($disHelp));
				if(!empty($owner->id)){
					$this->addContent(new String(Lng::DISCUSSION_WARNING));
				}
				$this->addContent(new FormDiscussion());
				$res = Discussion::read(Page::get("follow"),Page::get("type"),Page::get("page"),Page::get("order"));
				while($dis = mysql_fetch_object($res)) {
					$this->addContent(new DiscussionInfo($dis));
				}
				break;
		}
		$this->addContent(new Paging(__FILE__));

	}
}
$page = new DiscussionPage;
$page->view();
?>