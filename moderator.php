<?php
require_once("include/config.php");

class ModeratorPage extends CommonPage {
	
	public function __construct() {
		parent::__construct();
		$owner = Page::session("login");
		if ($owner->level < User::LEVEL_MODERATOR) {
			die(Lng::ACCESS_DENIED);
		}
		$this->addContent(new FormModeratorSearch());
		$this->addContent(new ModeratorTable(Page::get("searchColumn")));
		$this->addContent(new Paging(__FILE__));
		switch(Page::get("action")) {
			case "wikiAllow":
				if (Page::get("wiki")) {
					Wiki::allow(Page::get("wiki"));
					Header("Location: moderator.php?action=wikiNotAllowed");
				}
				break;
			case "wikiDestroy":
					if (Page::get("wiki")) {
						Wiki::destroy(Page::get("wiki"));
						Header("Location: moderator.php?action=wikiNotAllowed");
					}
				break;
			case "formBook":
				$this->content->clean();
				$this->addContent(new FormModeratorBook(Page::get("book")));
				break;
			case "formTag":
				$this->content->clean();
				$this->addContent(new FormModeratorTag(Page::get("tag")));
				break;
			case "formWriter":
				$this->content->clean();
				$this->addContent(new FormModeratorWriter(Page::get("writer")));
				break;
			case "wikiNotAllowed":
				$this->content->clean();
				$this->addContent(new WikiNotAllowed());
				break;

			
		}
		
	}
}
$page = new ModeratorPage();
$page->view();
?>