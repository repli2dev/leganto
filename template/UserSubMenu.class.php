<?php
class UserSubMenu extends Div {
	
	public function view() {
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
			parent::view();
		}
	}
}
?>