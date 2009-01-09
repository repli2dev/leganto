<?php
require_once("include/config.php");

class MessagePage extends CommonPage {
	
	public function __construct() {
        parent::__construct();
        $owner = Page::session("login");
        if($owner->id){
            $this->setTitle(Lng::MESSAGES);
            $this->addContent(new H(2,Lng::MESSAGES));
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
        } else {
            $this->setTitle(Lng::ACCESS_DENIED);
            $this->addContent(Lng::ACCESS_DENIED);
        }
	}
}
$page = new MessagePage();
$page->view();
?>