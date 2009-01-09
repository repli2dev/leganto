<?php
require_once("include/config.php");

class AboutPage extends CommonPage {
 	
	public function __construct() {
		parent::__construct();
		$owner = Page::session("login");
		$file = "include/about.texy";
		$fp = fOpen($file,"r");
		$content = fRead($fp, fileSize($file));
		$content = str_replace("<<ID>>",$owner->id,$content);
		$this->addContent(new String($content,TRUE));
		$this->setTitle(Lng::ABOUT);
	}
}
$page = new AboutPage();
$page->view();
?>