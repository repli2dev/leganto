<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Podmenu na strane s literarnimi soutezemi.
* @package readerTemplate
*/
class CompetitionSubMenu extends Div {
	
	public function getValue() {
		$items = FALSE;
		$ul = new Ul;
		$this->setClass("submenu");
		$owner = Page::session("login");
		if (Page::get("comp") && ($owner->level >= User::LEVEL_MODERATOR || Competition::isMine(Page::get("comp")))) {
			$items = TRUE;
			$ul->addLi(new A(
				Lng::CHANGE_COMPETITION,
				"competition.php?action=formCompetition&amp;comp=".Page::get("comp")
				));
			$ul->addLi(new A(
				Lng::DELETE,
				"competition.php?action=competitionDestroy&amp;comp=".Page::get("comp")
			));
		}
		$this->addValue($ul);
		unset($ul);
		if ($items) {
			return parent::getValue();
		}
	}
	
}
?>