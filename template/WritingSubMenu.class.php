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
class WritingSubMenu extends Div {
	
	public function getValue() {
		$owner = Page::session("login");

		if ($owner->level >= User::LEVEL_COMMON){
			$ul = new Ul;
			$this->setClass("submenu");
			$ul->addLi(new A(
				Lng::ADD_WRITING,
				"writing.php?action=addWriting"
			));
			$id = Page::get("id");
			if(!empty($id)){
				//odkaz upravit
				if($op = Writing::isMine($id) OR ($owner->level > User::LEVEL_COMMON)){
					$ul->addLi(new A(
						Lng::EDIT_WRITING,
						"writing.php?action=addWriting&amp;id=".$id.""
					));
				}
				//odkaz smazat
				if($op = Writing::isMine($id) OR ($owner->level > User::LEVEL_COMMON)){
					$a = new A(
						Lng::DELETE_WRITING,
						"writing.php?action=destroyWriting&amp;id=".$id.""
					);
					$a->addEvent("onclick","return confirm('".Lng::ASSURANCE_WRITING2."');");
					$ul->addLi($a);
					unset($a);
				}
			}
			$this->addValue($ul);
			unset($ul);
		}
		return parent::getValue();
	}
}
?>