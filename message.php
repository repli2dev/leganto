<?php
require_once("include/config.php");

class MessagePage extends CommonPage {
	
	public function __construct() {
		parent::__construct();
		$this->setTitle(Lng::MESSAGES);
		$this->addContent(new FormMessage());
		switch(Page::get("action")) {
			case "messageDestroy":
				if (Page::get("message")) {

					Message::destroy(Page::get("message"));
				}
				break;	
		}
		$this->addContent(new MessageBox(Page::get("action")));
		$this->addContent(new Paging(__FILE__));
	}
}
$page = new MessagePage();
$page->view();
?>