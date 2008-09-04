<?php
require_once("include/config.php");

class AdminPage extends CommonPage {
	
	public function __construct() {
		parent::__construct();
		$owner = Page::session("login");
		if ($owner->level < User::LEVEL_ADMIN) {
			die(Lng::ACCESS_DENIED);
		}
		$this->addContent(new FormTopic());
	}
}

$page = new AdminPage();
$page->view();
?>