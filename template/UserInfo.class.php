<?php
class UserInfo extends Div {

	private $user = NULL;
	
	/**
	 * Konstruktor (pokud nevyplnite parametr, zobrazi informace o prihlasenem uzivatei].
	 * @param int ID uzivatele
	 * @return void
	 */
	public function __construct($user = NULL) {
		parent::__construct();
		$this->setClass("column");
		$owner = Page::session("login");
		if ($user) {
			// Zobrazi informace o uzivateli.
			$this->user = User::getInfo($user);
			$this->sharedPart();
		}
		elseif($owner->id) {
			// Zobrazi informace o prihlasenem uzivateli.
			$this->user = $owner;
			$this->sharedPart();
			if ($owner->level > User::LEVEL_COMMON) {
				$p = new P();
				$p->addValue(new A(
					Lng::WIKI_NOT_ALLOWED." (".mysql_num_rows(Wiki::getNotAllowed()).")",
					"moderator.php?action=wikiNotAllowed"
				));
				$this->addValue($p);
				unset($p);
			}
			$p = new P();
			$p->addValue(new A(
				Lng::MESSAGES." (".Message::notRead().")",
				"message.php",
				Lng::MESSAGES
			));
			$this->addValue($p);
			unset($p);
			$control = new P();
			$control->addValue(new P(new A(
				Lng::CHANGE_USER_INFO,
				"user.php?action=userForm&amp;user=".$owner->id
			)));
			$control->addValue(new P(new A(
				Lng::LOG_OUT,
				"user.php?action=logOut"
			)));
			$this->addValue($control);
		}
	}
	
	protected function sharedPart() {
		$this->addValue(new H(2,new A(
			$this->user->name,
			"user.php?user=".$this->user->id
		)));
		$ico = "image/ico/".$this->user->id.".jpg";
		if (!file_exists($ico)) {
			$ico = "image/ico/default.jpg";
		}
		$ico = new Img($ico,$this->user->name);
		$ico->setClass("ico");
		$this->addValue($ico);
		$this->addValue(new P(new String(Lng::CARMA.": ".$this->user->recommend)));
		$this->addValue(new P(new A(
			Lng::ALL_USER_BOOKS,
			"user.php?action=allUserBooks&amp;user=".$this->user->id
		)));
		$desc = new P(new String($this->user->description));
		$desc->setClass("description");
		$this->addValue($desc);
	}
}
?>