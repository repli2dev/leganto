<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Podmenu na strane uzivatele.
* @package readerTemplate
*/
class UserSubMenu extends Div {
	
	public function getValue() {
		$owner = Page::session("login");
		if (($owner->id) && (Page::get("user")) && (Page::get("user") != $owner->id)) {
			$ul = new Ul;
			$this->setClass("submenu");
			if (Recommend::isMine(Page::get("user"))) {
				$ul->addLi(new A(
					Lng::FAVOURITE_DESTROY,
					"user.php?action=favouriteDestroy&amp;user=".Page::get("user")
				));
			}
			else {
				$ul->addLi(new A(
					Lng::FAVOURITE_MAKE,
					"user.php?action=favouriteMake&amp;user=".Page::get("user")
				));
			}
			$this->addValue($ul);
			unset($ul);
		}
		return parent::getValue();
	}
}
?>