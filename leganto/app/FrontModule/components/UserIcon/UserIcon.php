<?php
/**
 * Show image with user's latest books
 * @author Jan Drabek
 * @author Jan Papousek
 */
namespace FrontModule\Components;
use Leganto\DB\User\Entity;
class UserIcon extends BaseComponent {

	private $user;

	public function render() {
		if ($this->getUser()->isLoggedIn() && $this->getUser()->getId() == $this->user->getId()) {
			return parent::render();
		}
	}

	public function setUser(Entity $user) {
		$this->user = $user;
	}

}
