<?php
require_once("include/config.php");

class CompetitionPage extends CommonPage {
	
	public function __construct() {
		parent::__construct();
		$this->header = new Header("competition");
		$this->setTitle(Lng::COMPETITIONS);
		$this->clearStyleSheet();
		$this->addStyleSheet("competition.css");
		$this->rightColumn->clean();
		$this->addRightColumn(new CompetitionSubMenu());
		$this->addRightColumn(new LastDiscussion());
		$this->addRightColumn(new TagBox("competition"));
		switch(Page::get("action")) {
			case "":
				$this->addContent(new CompetitionIntroduction());
				$res = Competition::read(Page::get("page"));
				while ($comp = mysql_fetch_object($res)) {
					$this->addContent(new CompetitionInfo($comp));
				}
				break;
			case "formCompetition":
				$this->addContent(new FormCompetition(Page::get("comp")));
				break;
			case "readOne":
				$comp = Competition::getInfo(Page::get("comp"));
				$this->addContent(new CompetitionInfo($comp));
				$this->addContent(new TagBox("competition",$comp->id));
				$this->addContent(new Comments(Page::get("comp"),"competition"));
				$this->addContent(new FormComment("competition"));
				break;
			case "competitionDestroy":
				Competition::destroy(Page::get("comp"));
				Header("Location: competition.php");
				break;
			case "commentDestroy":
				Discussion::destroy(Page::get("comment"));
				Header("Location: competition.php?action=readOne&comp=".Page::get("comp")."#comment");
				break;
			case "search":
				$this->addContent(new CompetitionListSearch());
				break;
		}
	}
}
$page = new CompetitionPage();
$page->view();
?>